<?php

namespace App\Services;

use App\Mail\SessionReminder;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;

/**
 * Emails client and consultant 24 hours and 1 hour before each confirmed session,
 * and once more when the session room opens (Booking::joinOpensAt()).
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
     * @return array{24h: int, 1h: int, open: int} reminders sent
     */
    public function sendDue(): array
    {
        $sent = ['24h' => 0, '1h' => 0, 'open' => 0];
        $now = now();

        $bookings = Booking::with(['user', 'consultant.user'])
            ->where('status', 'confirmed')
            ->whereBetween('booking_date', [$now->copy()->subDay()->toDateString(), $now->copy()->addDays(2)->toDateString()])
            ->where(fn ($q) => $q->whereNull('reminder_24h_sent_at')->orWhereNull('reminder_1h_sent_at')->orWhereNull('room_open_sent_at'))
            ->get();

        foreach ($bookings as $booking) {
            $startsAt = $booking->sessionStartsAt();

            // The room is open (until the start time): one "join now" email replaces any reminder not sent yet.
            if ($now->gte($booking->joinOpensAt())) {
                if ($now->lt($startsAt) && $this->claim($booking, 'room_open_sent_at')) {
                    $this->claim($booking, 'reminder_1h_sent_at');
                    $this->claim($booking, 'reminder_24h_sent_at');
                    $this->send($booking, 'open');
                    $sent['open']++;
                }

                continue;
            }

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
                $confirmedAt = $booking->paid_at ?? $booking->confirmed_at;

                if ($confirmedAt && $confirmedAt->gt($now->copy()->subMinutes(self::SKIP_24H_IF_PAID_WITHIN_MINUTES))) {
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
        // Whoever is already in the room needs no "room is open" email.
        $fresh = $kind === 'open' ? $booking->fresh() : $booking;

        if (!($kind === 'open' && $fresh->client_joined_at)) {
            Mail::to($booking->user->email)->send(new SessionReminder($booking, 'client', $kind));
        }

        if (!($kind === 'open' && $fresh->consultant_joined_at)) {
            Mail::to($booking->consultant->notificationEmail())->send(new SessionReminder($booking, 'consultant', $kind));
        }
    }
}
