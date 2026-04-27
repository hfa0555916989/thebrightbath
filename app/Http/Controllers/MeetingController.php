<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    /**
     * Join meeting room for a booking.
     */
    public function join(Booking $booking)
    {
        $user = Auth::user();
        
        // Check if user is authorized (client or consultant)
        $isClient = $booking->user_id === $user->id;
        $isConsultant = $booking->consultant && $booking->consultant->user_id === $user->id;
        
        if (!$isClient && !$isConsultant) {
            abort(403, 'غير مصرح لك بالدخول لهذه الجلسة');
        }
        
        // Check if booking is confirmed
        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'الجلسة غير مؤكدة بعد');
        }
        
        $booking->load(['consultant.user', 'user']);
        
        // Generate unique room name based on booking
        $roomName = $this->generateRoomName($booking);
        
        // Determine user role
        $userRole = $isConsultant ? 'consultant' : 'client';
        $displayName = $user->name;
        
        return view('meetings.room', compact('booking', 'roomName', 'userRole', 'displayName'));
    }
    
    /**
     * Generate unique room name for booking.
     */
    private function generateRoomName(Booking $booking): string
    {
        // Create a unique, secure room name
        $hash = md5($booking->id . $booking->booking_number . config('app.key'));
        return 'brightbath-' . substr($hash, 0, 12);
    }
    
    /**
     * End meeting and redirect.
     */
    public function end(Booking $booking)
    {
        $user = Auth::user();
        
        // Check if consultant
        $isConsultant = $booking->consultant && $booking->consultant->user_id === $user->id;
        
        if ($isConsultant) {
            // Mark booking as completed
            $booking->update(['status' => 'completed']);
            
            return redirect()->route('consultant.sessions')
                ->with('success', 'تم إنهاء الجلسة بنجاح');
        }
        
        return redirect()->route('consultations.my-bookings')
            ->with('success', 'شكراً لك! نتمنى أن تكون الجلسة مفيدة');
    }
}

