<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\ConsultantSchedule;
use App\Models\AssessmentAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ConsultantController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $consultant = Consultant::with('user')->where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403, 'ليس لديك صلاحية الوصول لهذه الصفحة');
        }
        
        $todayBookings = Booking::where('consultant_id', $consultant->id)
            ->whereDate('booking_date', today())
            ->where('status', 'confirmed')
            ->with('user')
            ->orderBy('start_time')
            ->get();
            
        $upcomingBookings = Booking::where('consultant_id', $consultant->id)
            ->whereDate('booking_date', '>', today())
            ->where('status', 'confirmed')
            ->with('user')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
            
        $monthlyEarnings = Booking::where('consultant_id', $consultant->id)
            ->where('status', 'completed')
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->sum('consultant_earnings');
            
        $totalSessions = Booking::where('consultant_id', $consultant->id)
            ->where('status', 'completed')
            ->count();
            
        return view('consultant.dashboard', compact(
            'consultant',
            'todayBookings',
            'upcomingBookings',
            'monthlyEarnings',
            'totalSessions'
        ));
    }
    
    public function schedule(): View
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403);
        }
        
        $schedules = ConsultantSchedule::where('consultant_id', $consultant->id)
            ->orderBy('day_of_week')
            ->get();
            
        $days = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];
            
        return view('consultant.schedule', compact('consultant', 'schedules', 'days'));
    }
    
    public function updateSchedule(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403);
        }
        
        $scheduleData = $request->input('schedule', []);
        
        foreach ($scheduleData as $dayOfWeek => $data) {
            ConsultantSchedule::updateOrCreate(
                [
                    'consultant_id' => $consultant->id,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'is_available' => isset($data['available']),
                    'start_time' => $data['start_time'] ?? '09:00',
                    'end_time' => $data['end_time'] ?? '17:00',
                ]
            );
        }
        
        return back()->with('success', 'تم تحديث جدول المواعيد بنجاح');
    }
    
    public function earnings(): View
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403);
        }
        
        $monthlyData = Booking::where('consultant_id', $consultant->id)
            ->where('status', 'completed')
            ->whereYear('booking_date', now()->year)
            ->selectRaw('MONTH(booking_date) as month, SUM(consultant_earnings) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $totalEarnings = Booking::where('consultant_id', $consultant->id)
            ->where('status', 'completed')
            ->sum('consultant_earnings');
            
        $recentPayments = Booking::where('consultant_id', $consultant->id)
            ->where('status', 'completed')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('consultant.earnings', compact('consultant', 'monthlyData', 'totalEarnings', 'recentPayments'));
    }
    
    public function sessions(): View
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403);
        }
        
        $sessions = Booking::where('consultant_id', $consultant->id)
            ->with('user')
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);
            
        return view('consultant.sessions', compact('consultant', 'sessions'));
    }
    
    public function clientDetails(Booking $booking): View
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant || $booking->consultant_id !== $consultant->id) {
            abort(403);
        }
        
        // Get client assessment results
        $assessmentResults = AssessmentAttempt::where('user_id', $booking->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('consultant.client-details', compact('booking', 'assessmentResults'));
    }
    
    public function profile(): View
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403);
        }
        
        return view('consultant.profile', compact('consultant'));
    }
    
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'meeting_link' => 'nullable|url|max:500',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_iban' => 'nullable|string|max:34',
        ]);
        
        $consultant->update($validated);
        
        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Show pending booking requests
     */
    public function pendingRequests(): View
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant) {
            abort(403);
        }
        
        $pendingBookings = Booking::where('consultant_id', $consultant->id)
            ->where('status', 'pending_approval')
            ->with('user')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();
            
        return view('consultant.pending-requests', compact('consultant', 'pendingBookings'));
    }

    /**
     * Approve a booking request
     */
    public function approveBooking(Booking $booking): RedirectResponse
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant || $booking->consultant_id !== $consultant->id) {
            abort(403);
        }
        
        if ($booking->status !== 'pending_approval') {
            return back()->with('error', 'لا يمكن تغيير حالة هذا الحجز');
        }
        
        // التحقق من المستشار التجريبي (السعر = 0 أو الإيميل التجريبي)
        $isTestConsultant = $consultant->price_per_30_min == 0 || 
                           $consultant->user->email === 'consultant@test.com' ||
                           str_contains(strtolower($consultant->user->email), 'test');
        
        if ($isTestConsultant) {
            // تجاوز الدفع للمستشار التجريبي
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
            
            $this->sendApprovalNotification($booking, true);
            
            return back()->with('success', 'تم قبول الحجز وتأكيده تلقائياً (مستشار تجريبي)');
        }
        
        // للمستشارين العاديين - يجب الدفع
        $booking->update([
            'status' => 'approved',
        ]);
        
        // Send notification to client that booking is approved and ready for payment
        $this->sendApprovalNotification($booking, true);
        
        return back()->with('success', 'تم قبول الحجز وإشعار العميل بالدفع');
    }

    /**
     * Reject a booking request
     */
    public function rejectBooking(Request $request, Booking $booking): RedirectResponse
    {
        $user = auth()->user();
        $consultant = Consultant::where('user_id', $user->id)->first();
        
        if (!$consultant || $booking->consultant_id !== $consultant->id) {
            abort(403);
        }
        
        if ($booking->status !== 'pending_approval') {
            return back()->with('error', 'لا يمكن تغيير حالة هذا الحجز');
        }
        
        $booking->update([
            'status' => 'rejected',
            'cancellation_reason' => $request->input('reason', 'رفض من قبل المستشار'),
        ]);
        
        // Send notification to client
        $this->sendApprovalNotification($booking, false);
        
        return back()->with('success', 'تم رفض الحجز وإشعار العميل');
    }

    /**
     * Send approval/rejection notification to client
     */
    private function sendApprovalNotification(Booking $booking, bool $approved): void
    {
        $booking->load(['consultant.user', 'user']);
        
        try {
            \Illuminate\Support\Facades\Mail::to($booking->user->email)
                ->send(new \App\Mail\BookingApprovalResponse($booking, $approved));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking approval notification: ' . $e->getMessage());
        }
    }
}

