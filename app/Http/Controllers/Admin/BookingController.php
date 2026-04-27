<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

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
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,no_show'],
            'consultant_notes' => ['nullable', 'string'],
        ]);

        $booking->update($request->only(['status', 'consultant_notes']));

        return back()->with('success', 'تم تحديث الحجز بنجاح');
    }
}




