<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmation;
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

class NeoleapRefundAndInquiryTest extends TestCase
{
    use RefreshDatabase;

    private const SUPPORT_ENDPOINT = 'https://pg-support.test/pg/payment/tranportal.htm';

    private const RESOURCE_KEY = '12345678901234567890123456789012';

    /** Plain trandata the fake gateway answers with (null = transport-level rejection). */
    private ?array $gatewayAnswer = null;

    /** Decrypted plain requests the fake gateway received. */
    private array $received = [];

    protected function setUp(): void
    {
        parent::setUp();

        PaymentSetting::create([
            'gateway' => 'neoleap',
            'tranportal_id' => 'IPAYtestTerminal',
            'tranportal_password' => 'test-tranportal-pass',
            'resource_key' => self::RESOURCE_KEY,
            'endpoint_url' => 'https://pg.test/pg/payment/hosted.htm',
            'support_endpoint_url' => self::SUPPORT_ENDPOINT,
            'currency' => 'SAR',
            'is_active' => true,
        ]);

        Http::fake(function (HttpRequest $request) {
            $this->received[] = $this->decrypt($request->data()[0]['trandata'])[0];

            if ($this->gatewayAnswer === null) {
                return Http::response([['status' => '2', 'error' => 'IPAY0100001', 'errorText' => 'Rejected', 'trandata' => null]]);
            }

            return Http::response([[
                'tranid' => '301935166561199',
                'status' => '1',
                'trandata' => $this->encrypt([$this->gatewayAnswer]),
                'error' => null,
                'errorText' => null,
            ]]);
        });
        Mail::fake();
    }

    // ── Refunds ──────────────────────────────────────────────────────────

    public function test_client_cancellation_refunds_through_the_gateway(): void
    {
        $booking = $this->paidBooking();
        $this->gatewayAnswer = ['result' => 'CAPTURED', 'transId' => '301935166561199', 'amt' => '115.00'];

        $this->actingAs($booking->user)
            ->delete(route('consultations.cancel', $booking))
            ->assertSessionHas('success', fn ($m) => str_contains($m, 'استرداد المبلغ إلى بطاقتك'));

        $request = $this->received[0];
        $this->assertSame('2', $request['action']);
        $this->assertSame('TRANID', $request['udf5']);
        $this->assertSame('201935166561122', $request['transId']);
        $this->assertSame('115.00', $request['amt']);
        $this->assertSame('682', $request['currencyCode']);

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('refunded', $booking->payment_status);
        $this->assertSame('refunded', $booking->payment->status);
        $this->assertEquals(115.00, $booking->payment->refund_amount);
        $this->assertSame('refunded', PaymentTransaction::sole()->status);
    }

    public function test_failed_gateway_refund_still_cancels_and_leaves_payment_for_manual_refund(): void
    {
        $booking = $this->paidBooking();
        $this->gatewayAnswer = null;

        $this->actingAs($booking->user)
            ->delete(route('consultations.cancel', $booking))
            ->assertSessionHas('success', fn ($m) => str_contains($m, 'الإدارة'));

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        $this->assertSame('failed', PaymentTransaction::sole()->gateway_response['refund_status']);
    }

    public function test_mada_manual_refund_is_reported_as_processing(): void
    {
        $booking = $this->paidBooking();
        $this->gatewayAnswer = ['result' => 'PROCESSING', 'amt' => '115.00'];

        $this->actingAs($booking->user)
            ->delete(route('consultations.cancel', $booking))
            ->assertSessionHas('success', fn ($m) => str_contains($m, 'قيد المعالجة'));

        $this->assertSame('paid', $booking->fresh()->payment_status);
        $this->assertSame('processing', PaymentTransaction::sole()->gateway_response['refund_status']);
    }

    public function test_cancelling_an_unpaid_booking_does_not_call_the_gateway(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)->delete(route('consultations.cancel', $booking));

        Http::assertNothingSent();
        $this->assertSame('cancelled', $booking->fresh()->status);
    }

    public function test_admin_refund_cancels_booking_and_cannot_be_repeated(): void
    {
        $booking = $this->paidBooking();
        $transaction = PaymentTransaction::sole();
        $admin = User::factory()->admin()->create();
        $this->gatewayAnswer = ['result' => 'CAPTURED', 'amt' => '115.00'];

        $this->actingAs($admin)
            ->post(route('admin.payment-settings.refund', $transaction))
            ->assertSessionHas('success');

        $this->assertSame('cancelled', $booking->fresh()->status);
        $this->assertSame('refunded', $booking->fresh()->payment_status);

        $this->actingAs($admin)
            ->post(route('admin.payment-settings.refund', $transaction))
            ->assertSessionHas('error');

        $this->assertCount(1, $this->received);
    }

    public function test_counselors_cannot_refund(): void
    {
        $this->paidBooking();

        $this->actingAs(User::factory()->counselor()->create())
            ->post(route('admin.payment-settings.refund', PaymentTransaction::sole()))
            ->assertRedirect(route('home'));

        Http::assertNothingSent();
    }

    // ── Inquiry of pending payments ──────────────────────────────────────

    public function test_pending_payment_found_captured_by_inquiry_confirms_booking(): void
    {
        [$booking, $transaction] = $this->pendingPayment(minutesAgo: 20);
        $this->gatewayAnswer = [
            'paymentId' => $transaction->order_id, 'result' => 'CAPTURED', 'transId' => '201935166561122',
            'amt' => '115.00', 'trackId' => $transaction->gateway_response['track_id'],
        ];

        $this->artisan('payments:reconcile')->assertSuccessful();

        $request = $this->received[0];
        $this->assertSame('8', $request['action']);
        $this->assertSame('PaymentID', $request['udf5']);
        $this->assertSame($transaction->order_id, $request['transId']);

        $this->assertSame('confirmed', $booking->fresh()->status);
        $this->assertSame('success', $transaction->fresh()->status);
        Mail::assertSent(BookingConfirmation::class, 2);
    }

    public function test_pending_payment_reported_not_captured_is_marked_failed(): void
    {
        [$booking, $transaction] = $this->pendingPayment(minutesAgo: 20);
        $this->gatewayAnswer = ['paymentId' => $transaction->order_id, 'result' => 'NOT CAPTURED', 'amt' => '115.00'];

        $this->artisan('payments:reconcile')->assertSuccessful();

        $this->assertSame('failed', $transaction->fresh()->status);
        $this->assertSame('approved', $booking->fresh()->status);
    }

    public function test_unclear_inquiry_answer_leaves_payment_pending(): void
    {
        [$booking, $transaction] = $this->pendingPayment(minutesAgo: 20);
        $this->gatewayAnswer = ['paymentId' => $transaction->order_id, 'result' => 'success'];

        $this->artisan('payments:reconcile')->assertSuccessful();

        $this->assertSame('pending', $transaction->fresh()->status);
        $this->assertSame('approved', $booking->fresh()->status);
    }

    public function test_recent_pending_payments_are_left_alone(): void
    {
        $this->pendingPayment(minutesAgo: 5);

        $this->artisan('payments:reconcile')->assertSuccessful();

        Http::assertNothingSent();
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function paidBooking(): Booking
    {
        $booking = Booking::factory()->confirmed()->create(['price' => 115]);

        PaymentTransaction::create([
            'transaction_id' => '201935166561122',
            'order_id' => '600202412345678901',
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => 115,
            'amount_cents' => 11500,
            'currency' => 'SAR',
            'status' => 'success',
            'gateway_response' => ['gateway' => 'neoleap', 'track_id' => '1251004101010', 'trans_id' => '201935166561122', 'customer_ip' => '203.0.113.195'],
        ]);
        Payment::factory()->completed()->create(['booking_id' => $booking->id, 'user_id' => $booking->user_id, 'amount' => 115]);

        return $booking;
    }

    private function pendingPayment(int $minutesAgo): array
    {
        $booking = Booking::factory()->approved()->create(['price' => 115]);

        $transaction = PaymentTransaction::create([
            'transaction_id' => '1251004101011',
            'order_id' => '600202412345678902',
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => 115,
            'amount_cents' => 11500,
            'currency' => 'SAR',
            'status' => 'pending',
            'gateway_response' => ['gateway' => 'neoleap', 'track_id' => '1251004101011', 'payment_id' => '600202412345678902', 'customer_ip' => '203.0.113.195'],
        ]);
        $transaction->forceFill(['created_at' => now()->subMinutes($minutesAgo)])->save();

        return [$booking, $transaction->fresh()];
    }

    private function encrypt(array $payload): string
    {
        return strtoupper(bin2hex(openssl_encrypt(urlencode(json_encode($payload)), 'aes-256-cbc', self::RESOURCE_KEY, OPENSSL_RAW_DATA, 'PGKEYENCDECIVSPC')));
    }

    private function decrypt(string $hex): array
    {
        return json_decode(urldecode(openssl_decrypt(hex2bin($hex), 'aes-256-cbc', self::RESOURCE_KEY, OPENSSL_RAW_DATA, 'PGKEYENCDECIVSPC')), true);
    }
}
