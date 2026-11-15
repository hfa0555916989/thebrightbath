<?php

namespace Tests\Feature;

use App\Mail\AdminBookingRequest;
use App\Mail\BookingApprovalRequest;
use App\Mail\BookingApprovalResponse;
use App\Mail\BookingConfirmation;
use App\Mail\BookingStatusNotice;
use App\Mail\ConsultantEarnings;
use App\Mail\InvoiceEmail;
use App\Mail\SessionReminder;
use App\Models\Booking;
use App\Models\Consultant;
use App\Models\ConsultantSchedule;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\BookingFlowService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Booking lifecycle: the payments switch, and the email sent at each step.
 */
class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function paymentsOff(): void
    {
        app(BookingFlowService::class)->setPaymentsRequired(false);
    }

    private function approve(Booking $booking)
    {
        return $this->actingAs($booking->consultant->user)
            ->post(route('consultant.booking.approve', $booking));
    }

    private function requestBooking(Consultant $consultant, User $client)
    {
        $date = now()->addDays(3);
        ConsultantSchedule::create([
            'consultant_id' => $consultant->id,
            'day_of_week' => $date->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_available' => true,
        ]);

        return $this->actingAs($client)->post(route('consultations.book', $consultant), [
            'booking_date' => $date->toDateString(),
            'start_time' => '10:00',
            'duration' => '30',
        ]);
    }

    // ---- payments switch ----------------------------------------------------

    public function test_payments_are_required_by_default(): void
    {
        $this->assertTrue(BookingFlowService::paymentsRequired());

        $booking = Booking::factory()->create();
        $this->approve($booking)->assertRedirect();

        $this->assertSame('approved', $booking->fresh()->status);
        Mail::assertQueued(BookingApprovalResponse::class, fn ($m) => $m->hasTo($booking->user->email));
        Mail::assertNotQueued(BookingConfirmation::class);
    }

    public function test_with_payments_off_approval_confirms_the_booking_without_payment(): void
    {
        $this->paymentsOff();
        $booking = Booking::factory()->create(['price' => 115]);
        $totalSessions = $booking->consultant->total_sessions;

        $this->approve($booking)->assertRedirect()->assertSessionHas('success');

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('waived', $booking->payment_status);
        $this->assertSame('بدون دفع', $booking->payment_status_label);
        $this->assertNull($booking->paid_at);
        $this->assertNotNull($booking->confirmed_at);
        $this->assertEquals(0, $booking->consultant_earnings);
        $this->assertEquals(0, $booking->admin_earnings);
        $this->assertSame($totalSessions + 1, $booking->consultant->fresh()->total_sessions);
    }

    public function test_with_payments_off_both_parties_get_the_confirmation_with_calendar_and_no_payment_emails(): void
    {
        $this->paymentsOff();
        $booking = Booking::factory()->create();

        $this->approve($booking);

        Mail::assertQueued(BookingConfirmation::class, fn ($m) => $m->recipientType === 'client' && $m->hasTo($booking->user->email));
        Mail::assertQueued(BookingConfirmation::class, fn ($m) => $m->recipientType === 'consultant' && $m->hasTo($booking->consultant->user->email));
        Mail::assertNotQueued(BookingApprovalResponse::class);
        Mail::assertNotQueued(InvoiceEmail::class);
        Mail::assertNotQueued(ConsultantEarnings::class);

        $confirmation = Mail::queued(BookingConfirmation::class)->first();
        $this->assertCount(1, $confirmation->attachments());
    }

    public function test_a_confirmed_booking_without_payment_can_be_joined(): void
    {
        $this->paymentsOff();
        $booking = Booking::factory()->create();
        $this->approve($booking);

        $this->actingAs($booking->user)
            ->get(route('video-call.join', $booking))
            ->assertOk();
    }

    public function test_approving_twice_does_not_confirm_twice(): void
    {
        $this->paymentsOff();
        $booking = Booking::factory()->create();

        $this->assertTrue(app(BookingFlowService::class)->confirmWithoutPayment($booking));
        $this->assertFalse(app(BookingFlowService::class)->confirmWithoutPayment($booking));
        $this->assertCount(2, Mail::queued(BookingConfirmation::class));
    }

    public function test_turning_payments_off_leaves_existing_bookings_as_they_are(): void
    {
        $approved = Booking::factory()->approved()->create();

        $this->paymentsOff();

        $this->assertSame('approved', $approved->fresh()->status);
        Mail::assertNothingQueued();
    }

    public function test_turning_payments_back_on_restores_the_payment_step(): void
    {
        $this->paymentsOff();
        app(BookingFlowService::class)->setPaymentsRequired(true);
        $booking = Booking::factory()->create();

        $this->approve($booking);

        $this->assertSame('approved', $booking->fresh()->status);
    }

    // ---- admin settings -------------------------------------------------------

    public function test_admin_switches_payments_off_and_sets_the_notification_email(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.payment-settings.booking-flow'), [
                'payments_required' => '0',
                'admin_notification_email' => 'notify@example.com',
            ])
            ->assertRedirect(route('admin.payment-settings.index'));

        $this->assertFalse(BookingFlowService::paymentsRequired());
        $this->assertSame('notify@example.com', BookingFlowService::adminNotificationEmail());

        $this->actingAs($admin)
            ->get(route('admin.payment-settings.index'))
            ->assertOk()
            ->assertSee('الدفع موقوف')
            ->assertSee('notify@example.com');
    }

    public function test_admin_notification_email_must_be_valid(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.payment-settings.booking-flow'), [
                'payments_required' => '1',
                'admin_notification_email' => 'not-an-email',
            ])
            ->assertSessionHasErrors('admin_notification_email');
    }

    public function test_only_admins_change_the_booking_flow_settings(): void
    {
        $consultant = Consultant::factory()->create();

        $this->actingAs($consultant->user)
            ->put(route('admin.payment-settings.booking-flow'), ['payments_required' => '0']);

        $this->assertTrue(BookingFlowService::paymentsRequired());
    }

    // ---- new request ----------------------------------------------------------

    public function test_new_request_notifies_the_admin_at_the_chosen_email(): void
    {
        SiteSetting::set(BookingFlowService::ADMIN_EMAIL_SETTING, 'notify@example.com');
        $consultant = Consultant::factory()->create();

        $this->requestBooking($consultant, User::factory()->create())->assertRedirect();

        Mail::assertQueued(AdminBookingRequest::class, fn ($m) => $m->hasTo('notify@example.com'));
    }

    public function test_admin_email_falls_back_to_supervisor_email_then_to_none(): void
    {
        config(['app.supervisor_email' => 'supervisor@example.com']);
        $this->assertSame('supervisor@example.com', BookingFlowService::adminNotificationEmail());

        config(['app.supervisor_email' => null]);
        $this->assertNull(BookingFlowService::adminNotificationEmail());

        $this->requestBooking(Consultant::factory()->create(), User::factory()->create());
        Mail::assertNotQueued(AdminBookingRequest::class);
    }

    // ---- consultant's own notification email ----------------------------------

    public function test_consultant_emails_go_to_their_chosen_notification_email(): void
    {
        $consultant = Consultant::factory()->create();

        $this->actingAs($consultant->user)
            ->put(route('consultant.notification-email.update'), ['notification_email' => 'inbox@example.com'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('inbox@example.com', $consultant->fresh()->notificationEmail());

        $this->requestBooking($consultant->fresh(), User::factory()->create());
        Mail::assertQueued(BookingApprovalRequest::class, fn ($m) => $m->hasTo('inbox@example.com'));
        Mail::assertNotQueued(BookingApprovalRequest::class, fn ($m) => $m->hasTo($consultant->user->email));
    }

    public function test_empty_notification_email_falls_back_to_the_account_email(): void
    {
        $consultant = Consultant::factory()->create(['notification_email' => 'old@example.com']);

        $this->actingAs($consultant->user)
            ->put(route('consultant.notification-email.update'), ['notification_email' => '']);

        $consultant->refresh();
        $this->assertNull($consultant->notification_email);
        $this->assertSame($consultant->user->email, $consultant->notificationEmail());
    }

    public function test_notification_email_must_be_valid_and_profile_shows_it(): void
    {
        $consultant = Consultant::factory()->create(['notification_email' => 'inbox@example.com']);

        $this->actingAs($consultant->user)
            ->get(route('consultant.profile'))
            ->assertOk()
            ->assertSee('بريد الإشعارات')
            ->assertSee('inbox@example.com');

        $this->actingAs($consultant->user)
            ->put(route('consultant.notification-email.update'), ['notification_email' => 'nope'])
            ->assertSessionHasErrors('notification_email');

        $this->assertSame('inbox@example.com', $consultant->fresh()->notification_email);
    }

    public function test_confirmation_and_reminders_use_the_consultant_notification_email(): void
    {
        $this->paymentsOff();
        $consultant = Consultant::factory()->create(['notification_email' => 'inbox@example.com']);
        $booking = Booking::factory()->for($consultant)->create();

        $this->approve($booking);

        Mail::assertQueued(BookingConfirmation::class, fn ($m) => $m->recipientType === 'consultant' && $m->hasTo('inbox@example.com'));
    }

    // ---- cancellation and no-show ---------------------------------------------

    public function test_client_cancellation_notifies_the_consultant(): void
    {
        $this->paymentsOff();
        $booking = Booking::factory()->create();
        $this->approve($booking);

        $this->actingAs($booking->user)
            ->delete(route('consultations.cancel', $booking))
            ->assertRedirect();

        $this->assertSame('cancelled', $booking->fresh()->status);
        Mail::assertQueued(BookingStatusNotice::class, fn ($m) => $m->event === BookingStatusNotice::CANCELLED_BY_CLIENT
            && $m->recipientType === 'consultant'
            && $m->hasTo($booking->consultant->user->email));
    }

    public function test_admin_cancel_and_no_show_notify_both_parties_once(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->confirmed()->create();

        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), ['status' => 'no_show']);
        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), ['status' => 'no_show', 'consultant_notes' => 'x']);

        $this->assertCount(2, Mail::queued(BookingStatusNotice::class, fn ($m) => $m->event === BookingStatusNotice::NO_SHOW));
        Mail::assertQueued(BookingStatusNotice::class, fn ($m) => $m->recipientType === 'client' && $m->hasTo($booking->user->email));
        Mail::assertQueued(BookingStatusNotice::class, fn ($m) => $m->recipientType === 'consultant' && $m->hasTo($booking->consultant->user->email));

        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), ['status' => 'cancelled']);

        $this->assertCount(2, Mail::queued(BookingStatusNotice::class, fn ($m) => $m->event === BookingStatusNotice::CANCELLED_BY_ADMIN));
        $this->assertNotNull($booking->fresh()->cancelled_at);
    }

    public function test_other_admin_status_changes_send_nothing(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = Booking::factory()->confirmed()->create();

        $this->actingAs($admin)->put(route('admin.bookings.update', $booking), ['status' => 'completed']);

        Mail::assertNotQueued(BookingStatusNotice::class);
    }

    // ---- reminders for bookings without payment --------------------------------

    public function test_24h_reminder_is_skipped_right_after_confirmation_without_payment(): void
    {
        $this->travelTo(Carbon::parse('2026-10-09 18:00', 'Asia/Riyadh'));
        $this->paymentsOff();
        $booking = Booking::factory()->create(['booking_date' => '2026-10-10', 'start_time' => '10:00', 'end_time' => '10:30']);
        $this->approve($booking);

        $this->artisan('sessions:send-reminders')->assertSuccessful();

        Mail::assertNotQueued(SessionReminder::class);
    }

    // ---- wording follows the switch ----------------------------------------------

    public function test_waiting_page_and_request_emails_drop_the_payment_wording_when_payments_are_off(): void
    {
        $booking = Booking::factory()->create();

        $this->actingAs($booking->user)
            ->get(route('consultations.waiting-approval', $booking))
            ->assertOk()
            ->assertSee('لإتمام الدفع');
        $this->assertStringContainsString('قبل إتمام عملية الدفع', (new BookingApprovalRequest($booking))->render());

        $this->paymentsOff();

        $this->actingAs($booking->user)
            ->get(route('consultations.waiting-approval', $booking))
            ->assertOk()
            ->assertDontSee('لإتمام الدفع')
            ->assertSee('سيصلك تأكيد الحجز');
        $this->assertStringNotContainsString('قبل إتمام عملية الدفع', (new BookingApprovalRequest($booking))->render());
    }
}
