<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\ConsultantEarnings;
use App\Mail\InvoiceEmail;
use App\Models\Booking;
use App\Models\Consultant;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    /**
     * Display consultants listing.
     */
    public function index()
    {
        $consultants = Consultant::with('user')
            ->active()
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->paginate(12);

        return view('consultations.index', compact('consultants'));
    }

    /**
     * Show consultant details and booking form.
     */
    public function show(Consultant $consultant)
    {
        if (!$consultant->is_active) {
            abort(404);
        }

        $consultant->load(['user', 'schedules' => function ($query) {
            $query->where('is_available', true)->orderBy('day_of_week');
        }]);

        // Get available slots for the next 30 days
        $availableSlots = $this->getAvailableSlots($consultant, 30);

        return view('consultations.show', compact('consultant', 'availableSlots'));
    }

    /**
     * Get available time slots for a consultant.
     */
    private function getAvailableSlots(Consultant $consultant, int $days = 30): array
    {
        $slots = [];
        $today = Carbon::today();

        for ($i = 0; $i < $days; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;

            $schedules = $consultant->schedules
                ->where('day_of_week', $dayOfWeek)
                ->where('is_available', true);

            foreach ($schedules as $schedule) {
                $startTime = Carbon::parse($schedule->start_time);
                $endTime = Carbon::parse($schedule->end_time);

                // Generate 30-minute slots
                while ($startTime->copy()->addMinutes(30) <= $endTime) {
                    $slotStart = $startTime->format('H:i');
                    $slotEnd = $startTime->copy()->addMinutes(30)->format('H:i');

                    // Check if slot is available (not booked)
                    $isBooked = $consultant->bookings()
                        ->where('booking_date', $date->format('Y-m-d'))
                        ->where('start_time', $slotStart)
                        ->whereIn('status', ['pending', 'pending_approval', 'approved', 'confirmed'])
                        ->exists();

                    // Skip past times for today
                    if ($date->isToday() && $startTime->lt(Carbon::now()->addHour())) {
                        $startTime->addMinutes(30);
                        continue;
                    }

                    if (!$isBooked) {
                        $dateKey = $date->format('Y-m-d');
                        if (!isset($slots[$dateKey])) {
                            $slots[$dateKey] = [
                                'date' => $date->format('Y-m-d'),
                                'day_name' => $this->getArabicDayName($dayOfWeek),
                                'formatted_date' => $date->format('d/m/Y'),
                                'slots' => [],
                            ];
                        }
                        $slots[$dateKey]['slots'][] = [
                            'start' => $slotStart,
                            'end' => $slotEnd,
                            'formatted' => date('h:i A', strtotime($slotStart)),
                        ];
                    }

                    $startTime->addMinutes(30);
                }
            }
        }

        return array_values($slots);
    }

    /**
     * Get Arabic day name.
     */
    private function getArabicDayName(int $dayOfWeek): string
    {
        $days = [
            0 => 'الأحد',
            1 => 'الإثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];
        return $days[$dayOfWeek] ?? '';
    }

    /**
     * Store a new booking.
     */
    public function book(Request $request, Consultant $consultant)
    {
        $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration' => ['required', 'in:30,60'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Calculate end time and price
        $duration = (int) $request->duration;
        $endTime = Carbon::parse($request->start_time)->addMinutes($duration)->format('H:i');
        $price = $duration === 30 ? $consultant->price_per_30_min : $consultant->price_per_60_min;

        // Check availability
        if (!$consultant->isAvailableAt($request->booking_date, $request->start_time, $endTime)) {
            return back()->withErrors(['booking' => 'هذا الموعد غير متاح، يرجى اختيار موعد آخر']);
        }

        // Create booking with pending_approval status
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'consultant_id' => $consultant->id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime,
            'duration_minutes' => $duration,
            'price' => $price,
            'status' => 'pending_approval', // Waiting for consultant approval
            'payment_status' => 'pending',
            'client_notes' => $request->notes,
            'meeting_link' => $consultant->meeting_link,
        ]);

        // Send notification to consultant for approval
        $this->sendApprovalRequestNotification($booking);

        // Redirect to waiting for approval page
        return redirect()->route('consultations.waiting-approval', $booking)
            ->with('success', 'تم إرسال طلب الحجز للمستشار. سيتم إشعارك عند الموافقة.');
    }

    /**
     * Show payment page.
     */
    public function payment(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('consultations.confirmation', $booking);
        }

        // Check if booking is approved by consultant
        if ($booking->status === 'pending_approval') {
            return redirect()->route('consultations.waiting-approval', $booking)
                ->with('info', 'يرجى الانتظار حتى يوافق المستشار على طلب الحجز');
        }

        if ($booking->status === 'rejected') {
            return redirect()->route('consultations.index')
                ->with('error', 'تم رفض طلب الحجز من قبل المستشار');
        }

        $booking->load(['consultant.user']);

        return view('consultations.payment', compact('booking'));
    }

    /**
     * Process payment (placeholder for payment gateway).
     */
    public function processPayment(Request $request, Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['consultant.user', 'user']);

        // Calculate consultant earnings (after commission)
        $consultant = $booking->consultant;
        $commissionRate = $consultant->commission_rate ?? 20; // Default 20%
        $consultantEarnings = $booking->price * (1 - ($commissionRate / 100));
        $adminEarnings = $booking->price - $consultantEarnings;

        // This is a placeholder for actual payment gateway integration
        // In production, this would integrate with Moyasar, Tap, or HyperPay

        // Simulate successful payment
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'amount' => $booking->price,
            'currency' => 'SAR',
            'status' => 'completed',
            'payment_method' => 'card',
            'gateway' => 'placeholder', // Will be replaced with actual gateway
            'completed_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Update booking with earnings breakdown
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => $payment->payment_id,
            'consultant_earnings' => $consultantEarnings,
            'admin_earnings' => $adminEarnings,
        ]);

        // Increment consultant sessions
        $booking->consultant->increment('total_sessions');

        // Send confirmation emails
        $this->sendBookingNotifications($booking, $payment);

        return redirect()->route('consultations.confirmation', $booking)
            ->with('success', 'تم الدفع بنجاح! تم تأكيد حجزك.');
    }

    /**
     * Send booking notification emails.
     */
    private function sendBookingNotifications(Booking $booking, Payment $payment): void
    {
        $booking->load(['consultant.user', 'user']);

        try {
            // Send confirmation to client
            Mail::to($booking->user->email)
                ->send(new BookingConfirmation($booking, 'client'));

            // Send notification to consultant
            Mail::to($booking->consultant->user->email)
                ->send(new BookingConfirmation($booking, 'consultant'));

            // Send invoice to client
            Mail::to($booking->user->email)
                ->send(new InvoiceEmail($payment));

            // Send earnings notification to consultant
            $consultantEarnings = $booking->consultant_earnings ?? $booking->price * 0.8;
            Mail::to($booking->consultant->user->email)
                ->send(new ConsultantEarnings($booking->consultant, $booking, $consultantEarnings));

        } catch (\Exception $e) {
            // Log error but don't fail the payment process
            \Log::error('Failed to send booking notification emails: ' . $e->getMessage());
        }
    }

    /**
     * Show booking confirmation.
     */
    public function confirmation(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['consultant.user']);

        return view('consultations.confirmation', compact('booking'));
    }

    /**
     * Show user's bookings.
     */
    public function myBookings()
    {
        $upcomingBookings = Booking::with(['consultant.user'])
            ->where('user_id', Auth::id())
            ->upcoming()
            ->get();

        $pastBookings = Booking::with(['consultant.user'])
            ->where('user_id', Auth::id())
            ->past()
            ->limit(10)
            ->get();

        return view('consultations.my-bookings', compact('upcomingBookings', 'pastBookings'));
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$booking->canBeCancelled()) {
            return back()->withErrors(['cancel' => 'لا يمكن إلغاء هذا الحجز']);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => 'إلغاء من قبل العميل',
        ]);

        // Handle refund if paid
        if ($booking->payment_status === 'paid') {
            // Placeholder for refund logic
            $booking->update(['payment_status' => 'refunded']);
        }

        return back()->with('success', 'تم إلغاء الحجز بنجاح');
    }

    /**
     * Show waiting for approval page.
     */
    public function waitingApproval(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // If already approved, redirect to payment
        if ($booking->status === 'approved') {
            return redirect()->route('consultations.payment', $booking);
        }

        // If already confirmed (paid), redirect to confirmation
        if ($booking->status === 'confirmed') {
            return redirect()->route('consultations.confirmation', $booking);
        }

        $booking->load(['consultant.user']);

        return view('consultations.waiting-approval', compact('booking'));
    }

    /**
     * Send approval request notification to consultant and pending notification to client.
     */
    private function sendApprovalRequestNotification(Booking $booking): void
    {
        $booking->load(['consultant.user', 'user']);

        try {
            // Send notification to consultant about new booking request
            Mail::to($booking->consultant->user->email)
                ->send(new \App\Mail\BookingApprovalRequest($booking));
                
            // Send notification to client that booking is pending review
            Mail::to($booking->user->email)
                ->send(new \App\Mail\BookingPendingNotification($booking));
                
        } catch (\Exception $e) {
            \Log::error('Failed to send booking notifications: ' . $e->getMessage());
        }
    }
}
