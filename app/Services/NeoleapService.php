<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use Exception;
use Illuminate\Support\Facades\DB;
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

    private const ACTION_REFUND = '2';

    private const ACTION_INQUIRY = '8';

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

        $trackId = $this->newTrackId($booking->id);

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
                // Guide best practice: keep the customer IP with each transaction;
                // also reused for later inquiry/refund calls.
                'customer_ip' => $customerIps[0] ?? null,
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

        $this->applyResult($transaction, $data);

        return $transaction->fresh();
    }

    /**
     * Refund a paid booking in full (guide: "Refund" — action 2, by gateway transId).
     *
     * @return string 'refunded' | 'processing' (MADA manual refund accepted) | 'failed'
     */
    public function refundBooking(Booking $booking, string $reason, array $ips = []): string
    {
        $transaction = PaymentTransaction::where('payable_type', Booking::class)
            ->where('payable_id', $booking->id)
            ->whereIn('status', ['success', 'refunded'])
            ->latest()
            ->first();

        if (!$transaction || !$this->canUseSupportApi()) {
            Log::critical('Neoleap refund not possible — refund manually', [
                'booking_id' => $booking->id,
                'has_transaction' => (bool) $transaction,
            ]);

            return 'failed';
        }

        // Claim the refund under a lock so two requests can't refund twice.
        $previous = DB::transaction(function () use ($transaction) {
            $locked = PaymentTransaction::whereKey($transaction->id)->lockForUpdate()->first();
            $status = $locked->gateway_response['refund_status'] ?? null;

            if (!in_array($status, ['refunded', 'processing', 'requested'], true)) {
                $locked->update(['gateway_response' => array_merge($locked->gateway_response ?? [], ['refund_status' => 'requested'])]);
            }

            return $status;
        });

        if (in_array($previous, ['refunded', 'processing'], true)) {
            return $previous;
        }
        if ($previous === 'requested') {
            return 'processing';
        }

        $data = $this->supportRequest([
            'amt' => number_format((float) $transaction->amount, 2, '.', ''),
            'action' => self::ACTION_REFUND,
            'trackId' => $this->newTrackId($booking->id),
            'udf5' => 'TRANID',
            'transId' => (string) ($transaction->gateway_response['trans_id'] ?? $transaction->transaction_id),
        ], $ips ?: [$transaction->gateway_response['customer_ip'] ?? '']);

        $result = strtoupper(trim((string) ($data['result'] ?? '')));
        $status = match ($result) {
            'CAPTURED' => 'refunded',
            'PROCESSING' => 'processing',
            default => 'failed',
        };

        $transaction->refresh();
        $transaction->update([
            'status' => $status === 'refunded' ? 'refunded' : $transaction->status,
            'gateway_response' => array_merge($transaction->gateway_response ?? [], [
                'refund_status' => $status,
                'refund_result' => $data['result'] ?? null,
                'refund_trans_id' => $data['transid'] ?? null,
                'refund_reason' => $reason,
                'refund_at' => now()->toIso8601String(),
            ]),
        ]);

        if ($status === 'refunded') {
            $booking->update(['payment_status' => 'refunded']);
            $booking->payment?->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $transaction->amount,
                'refund_reason' => $reason,
            ]);
            Log::info('Neoleap: booking refunded', ['booking_id' => $booking->id]);
        } elseif ($status === 'processing') {
            $booking->payment?->update(['refund_reason' => $reason]);
            Log::info('Neoleap: refund accepted for manual processing (MADA)', ['booking_id' => $booking->id]);
        } else {
            Log::critical('Neoleap refund failed — refund manually', [
                'booking_id' => $booking->id,
                'result' => $data['result'] ?? null,
            ]);
        }

        return $status;
    }

    /**
     * Ask the gateway about payments still pending after 15 minutes — e.g. the customer
     * paid and closed the page before coming back (guide: "Inquiry" — action 8).
     * Returns how many were resolved.
     */
    public function reconcilePending(): int
    {
        if (!$this->canUseSupportApi()) {
            return 0;
        }

        $resolved = 0;

        $pending = PaymentTransaction::where('status', 'pending')
            ->where('payable_type', Booking::class)
            ->whereBetween('created_at', [now()->subDays(2), now()->subMinutes(15)])
            ->get();

        foreach ($pending as $transaction) {
            $data = $this->supportRequest([
                'amt' => number_format((float) $transaction->amount, 2, '.', ''),
                'action' => self::ACTION_INQUIRY,
                'trackId' => (string) ($transaction->gateway_response['track_id'] ?? ''),
                'udf5' => 'PaymentID',
                'transId' => (string) $transaction->order_id,
            ], [$transaction->gateway_response['customer_ip'] ?? '']);

            if ($data === null || (string) ($data['paymentid'] ?? $transaction->order_id) !== (string) $transaction->order_id) {
                continue;
            }

            $result = strtoupper(trim((string) ($data['result'] ?? '')));

            // Only act on answers we understand; anything else stays pending for a human.
            if ($result === 'CAPTURED' && isset($data['amt'])) {
                $this->applyResult($transaction, $data);
                $resolved++;
            } elseif (in_array($result, ['NOT CAPTURED', 'DENIED BY RISK', 'HOST TIMEOUT', 'NOT APPROVED'], true)) {
                $this->applyResult($transaction, $data);
                $resolved++;
            } else {
                Log::info('Neoleap inquiry: undetermined result, left pending', [
                    'payment_id' => $transaction->order_id,
                    'result' => $data['result'] ?? null,
                ]);
            }
        }

        return $resolved;
    }

    /**
     * Record a decrypted, matched gateway result on the transaction and confirm the
     * booking when the full amount was captured.
     */
    private function applyResult(PaymentTransaction $transaction, array $data): void
    {
        $paymentId = $transaction->order_id;

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
    }

    private function canUseSupportApi(): bool
    {
        return $this->settings && $this->settings->isConfigured() && filled($this->settings->support_endpoint_url);
    }

    private function newTrackId(int|string $prefix): string
    {
        return $prefix.now()->format('ymdHis').random_int(10, 99);
    }

    /**
     * Refund / inquiry call to the Tranportal endpoint. Returns the decrypted trandata or null.
     */
    private function supportRequest(array $fields, array $ips): ?array
    {
        $plain = [array_merge([
            'id' => $this->settings->tranportal_id,
            'password' => $this->settings->tranportal_password,
            'currencyCode' => self::CURRENCY_SAR,
        ], $fields)];

        try {
            $response = Http::withHeaders(['X-FORWARDED-FOR' => implode(',', array_filter($ips))])
                ->acceptJson()
                ->timeout(30)
                ->post($this->settings->support_endpoint_url, [[
                    'id' => $this->settings->tranportal_id,
                    'trandata' => $this->encrypt(json_encode($plain, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)),
                ]]);
        } catch (Exception $e) {
            Log::error('Neoleap support request failed', ['action' => $fields['action'], 'error' => $e->getMessage()]);

            return null;
        }

        $body = $response->json();
        $body = $this->normalize(is_array($body) ? $body : []);

        if (filled($body['trandata'] ?? null)) {
            $data = $this->decryptTrandata((string) $body['trandata']);
            if ($data !== null) {
                return $data;
            }
        }

        Log::error('Neoleap support request rejected', [
            'action' => $fields['action'],
            'http_status' => $response->status(),
            'status' => $body['status'] ?? null,
            'error' => $body['error'] ?? null,
            'error_text' => $body['errortext'] ?? null,
        ]);

        return null;
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
