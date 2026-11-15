<?php

namespace App\Services;

use App\Mail\BookingApprovalResponse;
use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Booking flow after the consultant's decision, with the payments switch:
 * - payments required (default): approved → client pays → BookingPaymentService::confirm().
 * - payments switched off: approval confirms the booking straight away, with nothing to pay
 *   (payment_status "waived"). Paid bookings are still confirmed only by BookingPaymentService.
 */
class BookingFlowService
{
    /** Site setting holding the switch ("1" = payments required, "0" = off). */
    public const SETTING = 'payments_required';

    /** Site setting: where the admin wants booking notifications. */
    public const ADMIN_EMAIL_SETTING = 'admin_notification_email';

    public static function paymentsRequired(): bool
    {
        return (string) setting(self::SETTING, '1') !== '0';
    }

    /**
     * Admin notifications address: set in the admin panel, else SUPERVISOR_EMAIL, else none.
     */
    public static function adminNotificationEmail(): ?string
    {
        return setting(self::ADMIN_EMAIL_SETTING, '') ?: (config('app.supervisor_email') ?: null);
    }

    /**
     * Only changes what happens on the next approvals; existing bookings stay as they are.
     */
    public function setPaymentsRequired(bool $required): void
    {
        SiteSetting::set(self::SETTING, $required ? '1' : '0');
    }

    /**
     * The consultant accepted the request.
     *
     * @return string the booking's new status: "approved" (awaiting payment) or "confirmed"
     */
    public function approve(Booking $booking): string
    {
        if (!self::paymentsRequired()) {
            $this->confirmWithoutPayment($booking);

            return 'confirmed';
        }

        $booking->update(['status' => 'approved']);

        try {
            $booking->load(['consultant.user', 'user']);
            Mail::to($booking->user->email)->send(new BookingApprovalResponse($booking, true));
        } catch (\Exception $e) {
            Log::error('Failed to send booking approval notification: '.$e->getMessage());
        }

        return 'approved';
    }

    /**
     * Confirm a booking that needs no payment, and send both parties the confirmation
     * (with the calendar invitation). False if the booking is not awaiting confirmation.
     */
    public function confirmWithoutPayment(Booking $booking): bool
    {
        $confirmed = DB::transaction(function () use ($booking) {
            $booking = Booking::whereKey($booking->id)->lockForUpdate()->first();

            if (!in_array($booking->status, ['pending_approval', 'approved'], true) || $booking->payment_status !== 'pending') {
                return false;
            }

            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'waived',
                'confirmed_at' => now(),
                // Nothing was paid, so nothing is earned (decision 2026-11-15).
                'consultant_earnings' => 0,
                'admin_earnings' => 0,
            ]);

            $booking->consultant->increment('total_sessions');

            return true;
        });

        if ($confirmed) {
            $booking->refresh()->load(['consultant.user', 'user']);

            try {
                Mail::to($booking->user->email)->send(new BookingConfirmation($booking, 'client'));
                Mail::to($booking->consultant->notificationEmail())->send(new BookingConfirmation($booking, 'consultant'));
            } catch (\Exception $e) {
                Log::error('Failed to send booking confirmation emails: '.$e->getMessage());
            }
        }

        return $confirmed;
    }
}
