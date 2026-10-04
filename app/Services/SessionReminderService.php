<?php

namespace App\Services;

use App\Mail\SessionReminder;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;

/**
 * Emails client and consultant 24 hours and 1 hour before each confirmed session.
 * Run every few minutes by the scheduler (sessions:send-reminders); each reminder
 * is claimed with a conditional update, so it goes out once even if runs overlap.
 */
class SessionReminderService
{
    /**
     * Sessions confirmed less than this long ago just received their confirmation
     * email, so the 24-hour reminder would be noise.
     */
    private const SKIP_24H_IF_PAID_WITHIN_MINUTES = 120;

    /**
     * @return array{24h: int, 1h: int} reminders sent
     */
    public function sendDue(): array
    {
        $sent = ['24h' => 0, '1h' => 0];
        $now = now();

        $bookings = Booking::with(['user', 'consultant.user'])
            ->where('status', 'confirmed')
            ->whereBetween('booking_date', [$now->copy()->subDay()->toDateString(), $now->copy()->addDays(2)->toDateString()])
            ->where(fn ($q) => $q->whereNull('reminder_24h_sent_at')->orWhereNull('reminder_1h_sent_at'))
            ->get();

        foreach ($bookings as $booking) {
            $startsAt = $booking->sessionStartsAt();

            if ($startsAt->lte($now)) {
                continue;
            }

            $minutesLeft = $now->diffInMinutes($startsAt);

            // 1 hour before (with a few minutes of slack for the scheduler interval)
            if ($minutesLeft <= 65 && $this->claim($booking, 'reminder_1h_sent_at')) {
                $this->claim($booking, 'reminder_24h_sent_at'); // too late for the 24h one
                $this->send($booking, '1h');
                $sent['1h']++;

                continue;
            }

            // 24 hours before
            if ($minutesLeft <= 24 * 60 && $minutesLeft > 65 && $this->claim($booking, 'reminder_24h_sent_at')) {
                if ($booking->paid_at && $booking->paid_at->gt($now->copy()->subMinutes(self::SKIP_24H_IF_PAID_WITHIN_MINUTES))) {
                    continue;
                }

                $this->send($booking, '24h');
                $sent['24h']++;
            }
        }

        return $sent;
    }

    /**
     * Mark the reminder as sent; false if another run already did.
     */
    private function claim(Booking $booking, string $column): bool
    {
        return Booking::whereKey($booking->id)->whereNull($column)->update([$column => now()]) === 1;
    }

    private function send(Booking $booking, string $kind): void
    {
        Mail::to($booking->user->email)->send(new SessionReminder($booking, 'client', $kind));
        Mail::to($booking->consultant->user->email)->send(new SessionReminder($booking, 'consultant', $kind));
    }
}
