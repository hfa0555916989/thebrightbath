<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmation;
use App\Mail\ConsultantEarnings;
use App\Mail\InvoiceEmail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentSetting;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NeoleapPaymentTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = 'https://pg.test/pg/payment/hosted.htm';

    private const TRANPORTAL_ID = 'IPAYtestTerminal';

    private const PASSWORD = 'test-tranportal-pass';

    private const RESOURCE_KEY = '12345678901234567890123456789012';

    private const PAYMENT_ID = '600202412345678901';

    private const PAGE_URL = 'https://securepayments.alrajhibank.com.sa/pg/paymentpage.htm';

    protected function setUp(): void
    {
        parent::setUp();

        PaymentSetting::create([
            'gateway' => 'neoleap',
            'tranportal_id' => self::TRANPORTAL_ID,
            'tranportal_password' => self::PASSWORD,
            'resource_key' => self::RESOURCE_KEY,
            'endpoint_url' => self::ENDPOINT,
            'currency' => 'SAR',
            'is_sandbox' => true,
            'is_active' => true,
        ]);

        Http::fake([
            'pg.test/*' => Http::response([[
                'status' => '1',
                'result' => self::PAYMENT_ID.':'.self::PAGE_URL,
                'error' => null,
                'errorText' => null,
            ]]),
        ]);
        Mail::fake();
    }

    // ── Starting a payment ───────────────────────────────────────────────

    public function test_old_simulated_payment_endpoint_no_longer_exists(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)
            ->post("/booking/{$booking->id}/process")
            ->assertNotFound();

        $this->assertSame('pending', $booking->fresh()->payment_status);
    }

    public function test_payment_page_submits_to_the_gateway(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)
            ->get(route('consultations.payment', $booking))
            ->assertOk()
            ->assertSee(route('payment.initiate', $booking), false);
    }

    public function test_cannot_pay_before_consultant_approval(): void
    {
        $booking = Booking::factory()->pending()->create();

        $this->actingAs($booking->user)
            ->post(route('payment.initiate', $booking))
            ->assertRedirect()
            ->assertSessionHas('error');

        Http::assertNothingSent();
        $this->assertSame(0, PaymentTransaction::count());
    }

    public function test_cannot_pay_for_someone_elses_booking(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('payment.initiate', $booking))
            ->assertForbidden();

        Http::assertNothingSent();
    }

    public function test_cannot_pay_while_gateway_is_disabled(): void
    {
        PaymentSetting::where('gateway', 'neoleap')->first()->update(['is_active' => false]);
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)
            ->post(route('payment.initiate', $booking))
            ->assertSessionHas('error');

        Http::assertNothingSent();
    }

    public function test_initiating_payment_sends_the_encrypted_request_and_redirects_to_the_bank(): void
    {
        $booking = Booking::factory()->approved()->create(['price' => 115]);

        $this->actingAs($booking->user)
            ->post(route('payment.initiate', $booking))
            ->assertRedirect(self::PAGE_URL.'?PaymentID='.self::PAYMENT_ID);

        Http::assertSent(function (HttpRequest $request) use (&$plain) {
            $body = $request->data()[0];
            $plain = $this->decryptLikeGateway($body['trandata'])[0];

            return $request->url() === self::ENDPOINT
                && str_starts_with($request->header('X-FORWARDED-FOR')[0], '127.0.0.1')
                && $body['id'] === self::TRANPORTAL_ID
                && $body['responseURL'] === route('payment.neoleap.response')
                && $body['errorURL'] === route('payment.neoleap.error');
        });

        $this->assertSame('115.00', $plain['amt']);
        $this->assertSame('1', $plain['action']);
        $this->assertSame('682', $plain['currencyCode']);
        $this->assertSame(self::TRANPORTAL_ID, $plain['id']);
        $this->assertSame(self::PASSWORD, $plain['password']);
        $this->assertMatchesRegularExpression('/^\d+$/', $plain['trackId']);
        $this->assertSame(route('payment.neoleap.response'), $plain['responseURL']);

        $transaction = PaymentTransaction::sole();
        $this->assertSame(self::PAYMENT_ID, $transaction->order_id);
        $this->assertSame($plain['trackId'], $transaction->transaction_id);
        $this->assertSame(11500, $transaction->amount_cents);
        $this->assertSame('pending', $transaction->status);
        $this->assertSame('approved', $booking->fresh()->status);
    }

    public function test_gateway_validation_error_is_shown_and_nothing_is_recorded(): void
    {
        PaymentSetting::where('gateway', 'neoleap')->first()->update(['endpoint_url' => 'https://pg-reject.test/hosted.htm']);
        Http::fake(['pg-reject.test/*' => Http::response([[
            'status' => '2', 'result' => null, 'error' => 'IPAY0100124', 'errorText' => 'Problem occurred while validating transaction data',
        ]])]);
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)
            ->post(route('payment.initiate', $booking))
            ->assertSessionHas('error', fn ($message) => str_contains($message, 'IPAY0100124'));

        $this->assertSame(0, PaymentTransaction::count());
    }

    // ── Gateway result: customer redirect ────────────────────────────────

    public function test_captured_result_confirms_booking_and_records_payment(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $sessionsBefore = $booking->consultant->total_sessions;

        $this->post(route('payment.neoleap.response'), $this->redirectPayload($result))
            ->assertRedirect(route('payment.success', ['payment_id' => self::PAYMENT_ID]));

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        $this->assertNotNull($booking->paid_at);
        $this->assertEquals(92.00, $booking->consultant_earnings);
        $this->assertEquals(23.00, $booking->admin_earnings);
        $this->assertSame($sessionsBefore + 1, $booking->consultant->fresh()->total_sessions);

        $payment = Payment::sole();
        $this->assertSame('completed', $payment->status);
        $this->assertSame('neoleap', $payment->gateway);
        $this->assertSame('201935166561122', $payment->gateway_transaction_id);

        $transaction = PaymentTransaction::sole();
        $this->assertSame('success', $transaction->status);
        $this->assertSame('1112', $transaction->card_last_four);

        Mail::assertSent(BookingConfirmation::class, 2);
        Mail::assertSent(InvoiceEmail::class, 1);
        Mail::assertSent(ConsultantEarnings::class, 1);

        $this->get(route('payment.success', ['payment_id' => self::PAYMENT_ID]))
            ->assertOk()
            ->assertViewIs('payment.success');
    }

    public function test_result_encrypted_with_another_key_is_rejected(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();

        $this->post(route('payment.neoleap.response'), [
            'paymentid' => self::PAYMENT_ID,
            'trandata' => $this->encryptLikeGateway([$result], 'ATTACKERKEYATTACKERKEYATTACKER12'),
        ])->assertRedirect(route('payment.failed'));

        $this->assertBookingStillUnpaid($booking);
        $this->assertSame('pending', PaymentTransaction::sole()->status);
    }

    public function test_result_for_a_different_payment_id_is_rejected(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $result['paymentId'] = '600200000000000000';

        $this->post(route('payment.neoleap.response'), $this->redirectPayload($result))
            ->assertRedirect(route('payment.failed'));

        $this->assertBookingStillUnpaid($booking);
    }

    public function test_result_with_a_different_track_id_is_rejected(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $result['trackId'] = '999999999';

        $this->post(route('payment.neoleap.response'), $this->redirectPayload($result))
            ->assertRedirect(route('payment.failed'));

        $this->assertBookingStillUnpaid($booking);
    }

    public function test_captured_amount_mismatch_does_not_confirm(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $result['amt'] = '1.00';

        $this->post(route('payment.neoleap.response'), $this->redirectPayload($result))
            ->assertRedirect();

        $this->assertBookingStillUnpaid($booking);
        $this->assertSame('failed', PaymentTransaction::sole()->status);
    }

    public function test_not_captured_result_marks_payment_failed(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $result['result'] = 'NOT CAPTURED';

        $this->post(route('payment.neoleap.error'), $this->redirectPayload($result))
            ->assertRedirect(route('payment.failed', ['error' => 'NOT CAPTURED']));

        $this->assertBookingStillUnpaid($booking);
        $this->assertSame('failed', PaymentTransaction::sole()->status);
    }

    public function test_plain_error_report_marks_payment_failed(): void
    {
        [$booking] = $this->bookingAwaitingResult();

        $this->post(route('payment.neoleap.error'), [
            'paymentid' => self::PAYMENT_ID,
            'Error' => 'IPAY0200025',
            'ErrorText' => 'Transaction reversed',
        ])->assertRedirect(route('payment.failed', ['error' => 'Transaction reversed']));

        $this->assertBookingStillUnpaid($booking);
        $this->assertSame('failed', PaymentTransaction::sole()->status);
    }

    public function test_replayed_result_is_processed_only_once(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $sessionsBefore = $booking->consultant->total_sessions;

        $this->post(route('payment.neoleap.response'), $this->redirectPayload($result));
        $this->post(route('payment.neoleap.response'), $this->redirectPayload($result))
            ->assertRedirect(route('payment.success', ['payment_id' => self::PAYMENT_ID]));

        $this->assertSame(1, Payment::count());
        $this->assertSame($sessionsBefore + 1, $booking->consultant->fresh()->total_sessions);
        Mail::assertSent(InvoiceEmail::class, 1);
    }

    public function test_payment_for_a_booking_cancelled_meanwhile_is_recorded_but_not_confirmed(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $booking->update(['status' => 'cancelled']);

        $this->post(route('payment.neoleap.response'), $this->redirectPayload($result));

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        Mail::assertNotSent(BookingConfirmation::class);
    }

    // ── Gateway result: server-to-server notification ────────────────────

    public function test_notification_is_acknowledged_after_confirming(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();

        $this->postJson(route('payment.neoleap.response'), [[
            'paymentId' => self::PAYMENT_ID,
            'trandata' => $this->encryptLikeGateway([$result]),
        ]])
            ->assertOk()
            ->assertExactJson([['status' => '1', 'result' => route('payment.neoleap.response')]]);

        $this->assertSame('confirmed', $booking->fresh()->status);
    }

    public function test_untrusted_notification_is_not_acknowledged_so_the_gateway_voids_it(): void
    {
        [$booking, $result] = $this->bookingAwaitingResult();
        $result['amt'] = '1.00';

        $this->postJson(route('payment.neoleap.response'), [[
            'paymentId' => self::PAYMENT_ID,
            'trandata' => $this->encryptLikeGateway([$result]),
        ]])
            ->assertOk()
            ->assertJsonPath('0.status', '2');

        $this->assertBookingStillUnpaid($booking);
    }

    // ── Result pages and status ──────────────────────────────────────────

    public function test_success_page_shows_pending_for_unknown_or_unpaid_payments(): void
    {
        $this->bookingAwaitingResult();

        $this->get(route('payment.success', ['payment_id' => self::PAYMENT_ID]))->assertViewIs('payment.pending');
        $this->get(route('payment.success', ['payment_id' => '1']))->assertViewIs('payment.pending');
    }

    public function test_payment_status_is_only_visible_to_the_booking_owner(): void
    {
        [$booking] = $this->bookingAwaitingResult();
        $transactionId = PaymentTransaction::sole()->transaction_id;

        $this->actingAs(User::factory()->create())
            ->getJson(route('payment.status', $transactionId))
            ->assertNotFound();

        $this->actingAs($booking->user)
            ->getJson(route('payment.status', $transactionId))
            ->assertOk()
            ->assertJson(['status' => 'pending']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * An approved booking whose client went through the real initiate step,
     * plus the plain trandata the gateway would send back for it.
     */
    private function bookingAwaitingResult(): array
    {
        $booking = Booking::factory()->approved()->create(['price' => 115]);

        $this->actingAs($booking->user)->post(route('payment.initiate', $booking));
        auth()->logout();

        $result = [
            'paymentId' => self::PAYMENT_ID,
            'result' => 'CAPTURED',
            'transId' => '201935166561122',
            'ref' => '935110000001',
            'date' => '1004',
            'trackId' => PaymentTransaction::sole()->gateway_response['track_id'],
            'udf1' => (string) $booking->id,
            'amt' => '115.00',
            'authRespCode' => '00',
            'authCode' => '000000',
            'cardType' => 'Visa',
            'actionCode' => '1',
            'card' => '401200XXXXXX1112',
            'expMonth' => '12',
            'expYear' => '2027',
        ];

        return [$booking, $result];
    }

    private function redirectPayload(array $result): array
    {
        return [
            'paymentid' => self::PAYMENT_ID,
            'trandata' => $this->encryptLikeGateway([$result]),
            'Error' => '',
            'ErrorText' => '',
        ];
    }

    /** Guide, "Sample Encryption Code": URL-encode → AES-256-CBC (IV PGKEYENCDECIVSPC) → upper-case hex. */
    private function encryptLikeGateway(array $payload, string $key = self::RESOURCE_KEY): string
    {
        $cipher = openssl_encrypt(urlencode(json_encode($payload)), 'aes-256-cbc', $key, OPENSSL_RAW_DATA, 'PGKEYENCDECIVSPC');

        return strtoupper(bin2hex($cipher));
    }

    private function decryptLikeGateway(string $hex): array
    {
        $plain = openssl_decrypt(hex2bin($hex), 'aes-256-cbc', self::RESOURCE_KEY, OPENSSL_RAW_DATA, 'PGKEYENCDECIVSPC');

        return json_decode(urldecode($plain), true);
    }

    private function assertBookingStillUnpaid(Booking $booking): void
    {
        $booking->refresh();
        $this->assertSame('approved', $booking->status);
        $this->assertSame('pending', $booking->payment_status);
        $this->assertSame(0, Payment::count());
        Mail::assertNotSent(BookingConfirmation::class);
    }
}
