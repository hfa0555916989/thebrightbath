<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Al Rajhi Bank payment gateway (operated by Neoleap) — Bank Hosted integration.
 *
 * Reference: "ARB Merchant Integration Guide – REST APIs" v1.31.
 * Flow: token request (encrypted trandata) → customer pays on the bank page →
 * gateway posts the encrypted result to our responseURL (server notification and/or
 * browser redirect) → we decrypt, verify and confirm the booking.
 */
class NeoleapService
{
    /** Fixed IV mandated by the gateway (guide: "Sample Encryption and Decryption Code"). */
    private const IV = 'PGKEYENCDECIVSPC';

    private const CIPHER = 'AES-256-CBC';

    private const CURRENCY_SAR = '682';

    private const ACTION_PURCHASE = '1';

    protected ?PaymentSetting $settings;

    public function __construct()
    {
        // Inactive settings are still loaded: results of payments started before the
        // gateway was switched off must be processed, and admins test before activating.
        $this->settings = PaymentSetting::getNeoleap()
            ?? PaymentSetting::where('gateway', 'neoleap')->first();
    }

    /**
     * Use specific settings (e.g. the admin's freshly saved, not yet active ones).
     */
    public function usingSettings(?PaymentSetting $settings): static
    {
        $this->settings = $settings;

        return $this;
    }

    public function isConfigured(): bool
    {
        return $this->settings && $this->settings->is_active && $this->settings->isConfigured();
    }

    /**
     * Ask the gateway for a payment page for this booking and return the URL to send the customer to.
     *
     * @param  string[]  $customerIps  customer IP first (sent as X-FORWARDED-FOR, mandatory since v1.31)
     */
    public function createPaymentForBooking(Booking $booking, array $customerIps): string
    {
        if (!$this->isConfigured()) {
            throw new Exception('بوابة الدفع غير مفعلة');
        }

        $amount = (float) $booking->price;
        if ($amount <= 0) {
            throw new Exception('مبلغ الدفع غير صحيح');
        }

        $trackId = $booking->id.now()->format('ymdHis').random_int(10, 99);

        [$paymentId, $paymentUrl] = $this->requestPaymentToken($amount, $trackId, $customerIps, (string) $booking->id);

        $user = $booking->user;

        PaymentTransaction::create([
            'transaction_id' => $trackId,
            'order_id' => $paymentId,
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => $amount,
            'amount_cents' => (int) round($amount * 100),
            'currency' => 'SAR',
            'status' => 'pending',
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'gateway_response' => [
                'gateway' => 'neoleap',
                'track_id' => $trackId,
                'payment_id' => $paymentId,
            ],
        ]);

        return $paymentUrl;
    }

    /**
     * Admin "test connection": a token request for 1.00 SAR. Nothing is charged
     * unless someone completes the returned payment page.
     */
    public function testConnection(array $ips): array
    {
        if (!$this->settings || !$this->settings->isConfigured()) {
            return ['success' => false, 'message' => 'أكمل جميع حقول الربط أولاً'];
        }

        try {
            $this->requestPaymentToken(1.00, '9'.now()->format('ymdHis'), $ips);

            return ['success' => true, 'message' => 'تم الاتصال بنجاح! البيانات صحيحة والبوابة أنشأت صفحة دفع تجريبية.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Process a result coming back from the gateway — the server-to-server notification
     * or the customer's browser redirect (responseURL / errorURL). Idempotent.
     *
     * Returns the matching transaction, or null when the message can't be trusted/matched.
     */
    public function handleCallback(array $input): ?PaymentTransaction
    {
        $fields = $this->normalize($input);
        $paymentId = (string) ($fields['paymentid'] ?? '');

        $transaction = $paymentId !== ''
            ? PaymentTransaction::where('order_id', $paymentId)->first()
            : null;

        if (!$transaction) {
            Log::warning('Neoleap callback: unknown payment id', ['payment_id' => $paymentId]);

            return null;
        }

        // Gateway may report the same payment several times: success is final.
        if ($transaction->status === 'success') {
            return $transaction;
        }

        $trandata = (string) ($fields['trandata'] ?? '');

        if ($trandata === '') {
            // Plain error report (validation/timeout) — nothing proves a payment.
            $transaction->markAsFailed(
                $fields['errortext'] ?? $fields['error'] ?? 'فشل في عملية الدفع',
                array_merge($transaction->gateway_response ?? [], ['error' => array_intersect_key($fields, array_flip(['error', 'errortext']))])
            );

            return $transaction;
        }

        $data = $this->decryptTrandata($trandata);

        if ($data === null
            || (string) ($data['paymentid'] ?? '') !== $paymentId
            || (string) ($data['trackid'] ?? '') !== (string) ($transaction->gateway_response['track_id'] ?? '')) {
            Log::warning('Neoleap callback rejected: trandata could not be decrypted or does not match', [
                'payment_id' => $paymentId,
            ]);

            return null;
        }

        $gatewayResponse = array_merge($transaction->gateway_response ?? [], [
            'result' => $data['result'] ?? null,
            'trans_id' => $data['transid'] ?? null,
            'ref' => $data['ref'] ?? null,
            'auth_code' => $data['authcode'] ?? null,
            'auth_resp_code' => $data['authrespcode'] ?? null,
            'amt' => $data['amt'] ?? null,
        ]);

        $transaction->update([
            'payment_method' => isset($data['cardtype']) ? strtolower($data['cardtype']) : 'card',
            'card_type' => $data['cardtype'] ?? null,
            'card_last_four' => isset($data['card']) ? substr((string) $data['card'], -4) : null,
            'gateway_response' => $gatewayResponse,
        ]);

        $captured = strtoupper(trim((string) ($data['result'] ?? ''))) === 'CAPTURED';
        $amountMatches = abs((float) ($data['amt'] ?? 0) - (float) $transaction->amount) < 0.005;

        if ($captured && $amountMatches) {
            if (filled($data['transid'] ?? null)) {
                $transaction->update(['transaction_id' => (string) $data['transid']]);
            }
            $transaction->markAsSuccessful($gatewayResponse);

            if ($transaction->payable_type === Booking::class && $booking = Booking::find($transaction->payable_id)) {
                app(BookingPaymentService::class)->confirm($booking, $transaction);
            }

            Log::info('Neoleap: payment captured', ['payment_id' => $paymentId, 'trans_id' => $data['transid'] ?? null]);
        } elseif ($captured) {
            $transaction->markAsFailed('المبلغ المدفوع لا يطابق مبلغ الطلب', $gatewayResponse);

            Log::critical('Neoleap: captured amount does not match transaction', [
                'payment_id' => $paymentId,
                'expected' => $transaction->amount,
                'paid' => $data['amt'] ?? null,
            ]);
        } else {
            $transaction->markAsFailed((string) ($data['result'] ?? $data['errortext'] ?? 'فشل في عملية الدفع'), $gatewayResponse);
        }

        return $transaction->fresh();
    }

    // ── Gateway request ───────────────────────────────────────────────────

    /**
     * @return array{0: string, 1: string} [payment id, payment page URL]
     */
    private function requestPaymentToken(float $amount, string $trackId, array $customerIps, string $reference = ''): array
    {
        $responseUrl = route('payment.neoleap.response');
        $errorUrl = route('payment.neoleap.error');

        $plain = [[
            'amt' => number_format($amount, 2, '.', ''),
            'action' => self::ACTION_PURCHASE,
            'password' => $this->settings->tranportal_password,
            'id' => $this->settings->tranportal_id,
            'currencyCode' => self::CURRENCY_SAR,
            'trackId' => $trackId,
            'responseURL' => $responseUrl,
            'errorURL' => $errorUrl,
            'udf1' => $reference,
            'langid' => 'ar',
        ]];

        $response = Http::withHeaders(['X-FORWARDED-FOR' => implode(',', array_filter($customerIps))])
            ->acceptJson()
            ->timeout(30)
            ->post($this->settings->endpoint_url, [[
                'id' => $this->settings->tranportal_id,
                'trandata' => $this->encrypt(json_encode($plain, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)),
                'responseURL' => $responseUrl,
                'errorURL' => $errorUrl,
            ]]);

        $body = $response->json();
        $body = is_array($body) && array_is_list($body) ? ($body[0] ?? []) : (array) $body;
        $result = (string) ($body['result'] ?? '');

        if (!$response->successful() || (string) ($body['status'] ?? '') !== '1' || !str_contains($result, ':')) {
            Log::error('Neoleap: payment token request failed', [
                'track_id' => $trackId,
                'http_status' => $response->status(),
                'error' => $body['error'] ?? null,
                'error_text' => $body['errorText'] ?? null,
            ]);

            throw new Exception('تعذر بدء عملية الدفع'.(isset($body['error']) ? " ({$body['error']})" : ''));
        }

        // "result" is "<paymentId>:<payment page URL>"
        [$paymentId, $pageUrl] = explode(':', $result, 2);

        Log::info('Neoleap: payment token created', ['track_id' => $trackId, 'payment_id' => $paymentId, 'amount' => $amount]);

        return [$paymentId, $pageUrl.'?PaymentID='.$paymentId];
    }

    // ── Encryption (guide: URL-encode → AES-256-CBC → upper-case hex) ─────

    public function encrypt(string $plain): string
    {
        $cipher = openssl_encrypt(urlencode($plain), self::CIPHER, $this->settings->resource_key, OPENSSL_RAW_DATA, self::IV);

        return strtoupper(bin2hex($cipher));
    }

    public function decrypt(string $hex): ?string
    {
        $hex = trim($hex);
        if ($hex === '' || strlen($hex) % 32 !== 0 || !ctype_xdigit($hex)) {
            return null;
        }

        $plain = openssl_decrypt(hex2bin($hex), self::CIPHER, $this->settings?->resource_key ?? '', OPENSSL_RAW_DATA, self::IV);

        return $plain === false ? null : urldecode($plain);
    }

    /**
     * Decrypted trandata as a flat array with lower-case keys, or null.
     */
    private function decryptTrandata(string $trandata): ?array
    {
        if (!$this->settings) {
            return null;
        }

        $decoded = json_decode((string) $this->decrypt($trandata), true);

        return is_array($decoded) ? $this->normalize($decoded) : null;
    }

    /**
     * The gateway wraps payloads in a one-element list and is inconsistent about
     * key casing (paymentId / paymentid, errorText / ErrorText).
     */
    private function normalize(array $payload): array
    {
        if (array_is_list($payload)) {
            $payload = is_array($payload[0] ?? null) ? $payload[0] : [];
        }

        return array_change_key_case($payload, CASE_LOWER);
    }
}
