<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingStatusNotice;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'consultant.user'])
            ->latest()
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'consultant.user', 'payment']);

        // Latest gateway transaction (paid or refunded): drives the refund button
        $transaction = PaymentTransaction::where('payable_type', Booking::class)
            ->where('payable_id', $booking->id)
            ->whereIn('status', ['success', 'refunded'])
            ->latest()
            ->first();

        return view('admin.bookings.show', compact('booking', 'transaction'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Booking::$statusLabels))],
            'consultant_notes' => ['nullable', 'string'],
        ]);

        $previousStatus = $booking->status;
        $booking->update($request->only(['status', 'consultant_notes']));

        // Cancelled or no-show from the admin panel: tell both parties.
        if ($booking->status !== $previousStatus && in_array($booking->status, ['cancelled', 'no_show'], true)) {
            if ($booking->status === 'cancelled' && !$booking->cancelled_at) {
                $booking->update(['cancelled_at' => now(), 'cancellation_reason' => $booking->cancellation_reason ?? 'إلغاء من الإدارة']);
            }

            self::notifyParties($booking, $booking->status === 'no_show'
                ? BookingStatusNotice::NO_SHOW
                : BookingStatusNotice::CANCELLED_BY_ADMIN);
        }

        return back()->with('success', 'تم تحديث الحجز بنجاح');
    }

    /**
     * Email the client and the consultant that their session will not take place.
     */
    public static function notifyParties(Booking $booking, string $event): void
    {
        $booking->load(['user', 'consultant.user']);

        try {
            Mail::to($booking->user->email)->send(new BookingStatusNotice($booking, 'client', $event));
            Mail::to($booking->consultant->notificationEmail())->send(new BookingStatusNotice($booking, 'consultant', $event));
        } catch (\Exception $e) {
            Log::error('Failed to send booking status emails: '.$e->getMessage(), ['booking_id' => $booking->id]);
        }
    }
}
