<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
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

        $booking->update($request->only(['status', 'consultant_notes']));

        return back()->with('success', 'تم تحديث الحجز بنجاح');
    }
}
