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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    private const HMAC_SECRET = 'test-hmac-secret';

    protected function setUp(): void
    {
        parent::setUp();

        PaymentSetting::create([
            'gateway' => 'paymob',
            'secret_key' => 'sk_test_dummy',
            'public_key' => 'pk_test_dummy',
            'hmac_secret' => self::HMAC_SECRET,
            'integration_id' => '4057',
            'currency' => 'SAR',
            'is_sandbox' => true,
            'is_active' => true,
        ]);

        Http::fake([
            'ksa.paymob.com/*' => Http::response(['id' => 'pi_test_123', 'client_secret' => 'csk_test_123'], 201),
        ]);
        Mail::fake();
    }

    // ── Fake payment endpoint ────────────────────────────────────────────

    public function test_old_simulated_payment_endpoint_no_longer_confirms_bookings(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)
            ->post("/booking/{$booking->id}/process")
            ->assertNotFound();

        $this->assertSame('approved', $booking->fresh()->status);
        $this->assertSame('pending', $booking->fresh()->payment_status);
        $this->assertSame(0, Payment::count());
    }

    public function test_payment_page_submits_to_the_real_gateway(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)
            ->get(route('consultations.payment', $booking))
            ->assertOk()
            ->assertSee(route('payment.initiate', $booking), false);
    }

    // ── Initiating payment ───────────────────────────────────────────────

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

    public function test_initiating_payment_charges_the_booking_price(): void
    {
        $booking = Booking::factory()->approved()->create(['price' => 115]);

        $this->actingAs($booking->user)
            ->post(route('payment.initiate', $booking))
            ->assertOk();

        Http::assertSent(fn ($request) => $request['amount'] === 11500 && $request['currency'] === 'SAR');

        $transaction = PaymentTransaction::sole();
        $this->assertSame(11500, $transaction->amount_cents);
        $this->assertSame($booking->id, $transaction->payable_id);
        $this->assertSame('pending', $transaction->status);
        $this->assertSame('approved', $booking->fresh()->status);
    }

    // ── Webhook ──────────────────────────────────────────────────────────

    public function test_webhook_without_hmac_is_rejected(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();

        $this->postJson(route('paymob.callback'), $payload)->assertStatus(401);

        $this->assertBookingStillUnpaid($booking);
    }

    public function test_webhook_with_wrong_hmac_is_rejected(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();

        $this->postJson(route('paymob.callback', ['hmac' => str_repeat('a', 128)]), $payload)
            ->assertStatus(401);

        $this->assertBookingStillUnpaid($booking);
    }

    public function test_webhook_signed_with_another_secret_is_rejected(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();

        $this->postJson(route('paymob.callback', ['hmac' => $this->sign($payload['obj'], 'attacker-secret')]), $payload)
            ->assertStatus(401);

        $this->assertBookingStillUnpaid($booking);
    }

    public function test_verified_webhook_confirms_booking_and_records_payment(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();
        $sessionsBefore = $booking->consultant->total_sessions;

        $this->postSigned($payload)->assertOk();

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        $this->assertNotNull($booking->paid_at);
        $this->assertEquals(92.00, $booking->consultant_earnings);
        $this->assertEquals(23.00, $booking->admin_earnings);
        $this->assertSame($sessionsBefore + 1, $booking->consultant->fresh()->total_sessions);

        $payment = Payment::sole();
        $this->assertSame('completed', $payment->status);
        $this->assertSame('paymob', $payment->gateway);
        $this->assertSame('success', PaymentTransaction::sole()->status);

        Mail::assertSent(BookingConfirmation::class, 2);
        Mail::assertSent(InvoiceEmail::class, 1);
        Mail::assertSent(ConsultantEarnings::class, 1);
    }

    public function test_replayed_webhook_is_processed_only_once(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();
        $sessionsBefore = $booking->consultant->total_sessions;

        $this->postSigned($payload)->assertOk();
        $this->postSigned($payload)->assertOk();

        $this->assertSame(1, Payment::count());
        $this->assertSame($sessionsBefore + 1, $booking->consultant->fresh()->total_sessions);
        Mail::assertSent(InvoiceEmail::class, 1);
    }

    public function test_webhook_with_mismatched_amount_does_not_confirm(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();
        $payload['obj']['amount_cents'] = 100; // paid 1 SAR instead of 115

        $this->postSigned($payload)->assertOk();

        $this->assertBookingStillUnpaid($booking);
        $this->assertSame('failed', PaymentTransaction::sole()->status);
    }

    public function test_failed_payment_webhook_does_not_confirm(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();
        $payload['obj']['success'] = false;

        $this->postSigned($payload)->assertOk();

        $this->assertBookingStillUnpaid($booking);
        $this->assertSame('failed', PaymentTransaction::sole()->status);
    }

    public function test_payment_for_a_booking_cancelled_meanwhile_is_recorded_but_not_confirmed(): void
    {
        [$booking, $payload] = $this->bookingAwaitingWebhook();
        $booking->update(['status' => 'cancelled']);

        $this->postSigned($payload)->assertOk();

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        Mail::assertNotSent(BookingConfirmation::class);
    }

    // ── Payment status endpoint ──────────────────────────────────────────

    public function test_payment_status_is_only_visible_to_the_booking_owner(): void
    {
        [$booking] = $this->bookingAwaitingWebhook();
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
     * plus the Paymob "transaction processed" callback for it.
     */
    private function bookingAwaitingWebhook(): array
    {
        $booking = Booking::factory()->approved()->create(['price' => 115]);

        $this->actingAs($booking->user)->post(route('payment.initiate', $booking))->assertOk();
        auth()->logout();

        $reference = PaymentTransaction::sole()->gateway_response['special_reference'];

        $payload = [
            'type' => 'TRANSACTION',
            'obj' => [
                'id' => 192036465,
                'pending' => false,
                'amount_cents' => 11500,
                'success' => true,
                'is_auth' => false,
                'is_capture' => false,
                'is_standalone_payment' => true,
                'is_voided' => false,
                'is_refunded' => false,
                'is_3d_secure' => true,
                'integration_id' => 4057,
                'has_parent_transaction' => false,
                'created_at' => '2026-10-03T10:00:00.000000',
                'currency' => 'SAR',
                'error_occured' => false,
                'owner' => 302852,
                'order' => ['id' => 217503754, 'merchant_order_id' => $reference],
                'source_data' => ['pan' => '2346', 'sub_type' => 'MasterCard', 'type' => 'card'],
                'data' => ['message' => 'Approved'],
            ],
        ];

        return [$booking, $payload];
    }

    private function postSigned(array $payload)
    {
        return $this->postJson(route('paymob.callback', ['hmac' => $this->sign($payload['obj'])]), $payload);
    }

    /** Paymob's documented HMAC: SHA-512 over these fields of "obj", in this order. */
    private function sign(array $obj, string $secret = self::HMAC_SECRET): string
    {
        $fields = [
            'amount_cents', 'created_at', 'currency', 'error_occured', 'has_parent_transaction', 'id',
            'integration_id', 'is_3d_secure', 'is_auth', 'is_capture', 'is_refunded', 'is_standalone_payment',
            'is_voided', 'order.id', 'owner', 'pending', 'source_data.pan', 'source_data.sub_type',
            'source_data.type', 'success',
        ];

        $string = collect($fields)
            ->map(fn ($field) => data_get($obj, $field))
            ->map(fn ($value) => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value)
            ->implode('');

        return hash_hmac('sha512', $string, $secret);
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
