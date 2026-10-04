<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookingPageTest extends TestCase
{
    use RefreshDatabase;

    private function paidBookingWithTransaction(array $gatewayResponse = []): array
    {
        $booking = Booking::factory()->confirmed()->create(['price' => 115]);
        $transaction = PaymentTransaction::create([
            'transaction_id' => '201935166561122',
            'order_id' => '600202412345678901',
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'amount' => 115,
            'amount_cents' => 11500,
            'currency' => 'SAR',
            'status' => 'success',
            'card_type' => 'Visa',
            'card_last_four' => '1112',
            'gateway_response' => array_merge(['gateway' => 'neoleap', 'trans_id' => '201935166561122'], $gatewayResponse),
        ]);

        return [$booking, $transaction];
    }

    public function test_booking_page_shows_details_and_refund_button(): void
    {
        [$booking, $transaction] = $this->paidBookingWithTransaction();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee($booking->booking_number)
            ->assertSee($booking->user->email)
            ->assertSee($booking->consultant->user->name)
            ->assertSee('•••• 1112')
            ->assertSee(route('admin.payment-settings.refund', $transaction), false);
    }

    public function test_refunded_booking_shows_status_instead_of_button(): void
    {
        [$booking, $transaction] = $this->paidBookingWithTransaction(['refund_status' => 'refunded']);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee('تم استرداد المبلغ')
            ->assertDontSee(route('admin.payment-settings.refund', $transaction), false);
    }

    public function test_unpaid_booking_has_no_refund_button(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertDontSee('استرداد المبلغ للعميل');
    }

    public function test_admin_can_update_status_and_notes(): void
    {
        $booking = Booking::factory()->confirmed()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.bookings.update', $booking), ['status' => 'no_show', 'consultant_notes' => 'لم يحضر العميل'])
            ->assertSessionHas('success');

        $this->assertSame('no_show', $booking->fresh()->status);
        $this->assertSame('لم يحضر العميل', $booking->fresh()->consultant_notes);
    }

    public function test_sidebar_links_to_transactions(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.dashboard'))
            ->assertSee(route('admin.payment-settings.transactions'), false);
    }
}
