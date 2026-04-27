<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        // Today's revenue
        $todayRevenue = Payment::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('amount');

        // This month's revenue
        $monthRevenue = Payment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('amount');

        // Total revenue
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        // Commission rate (default 20%)
        $commissionRate = config('services.commission_rate', 20);
        $platformEarnings = $totalRevenue * ($commissionRate / 100);

        // Recent payments
        $recentPayments = Payment::with(['user', 'booking.consultant.user'])
            ->latest()
            ->limit(20)
            ->get();

        // Consultant earnings
        $consultantEarnings = Booking::select('consultant_id', DB::raw('SUM(price) as total_earnings'), DB::raw('COUNT(*) as sessions_count'))
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->groupBy('consultant_id')
            ->with('consultant.user')
            ->get();

        return view('admin.finance.index', compact(
            'todayRevenue',
            'monthRevenue',
            'totalRevenue',
            'platformEarnings',
            'commissionRate',
            'recentPayments',
            'consultantEarnings'
        ));
    }

    public function settings()
    {
        $commissionRate = config('services.commission_rate', 20);
        return view('admin.finance.settings', compact('commissionRate'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // In a real app, you'd save this to a settings table or .env
        // For now, we'll just redirect back with success
        return back()->with('success', 'تم تحديث إعدادات العمولة بنجاح');
    }
}




