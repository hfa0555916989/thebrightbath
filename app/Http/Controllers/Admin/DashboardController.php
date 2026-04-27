<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\ContactMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // KPIs
        $totalAttempts = AssessmentAttempt::count();
        $todayAttempts = AssessmentAttempt::whereDate('created_at', today())->count();
        $weekAttempts = AssessmentAttempt::where('created_at', '>=', now()->subDays(7))->count();
        $monthAttempts = AssessmentAttempt::where('created_at', '>=', now()->subDays(30))->count();
        $newAttempts = AssessmentAttempt::where('status', 'new')->count();
        $totalClients = User::where('role', 'client')->count();
        $unreadMessages = ContactMessage::where('status', 'new')->count();

        // Attempts per assessment type
        $attemptsByType = AssessmentAttempt::select('assessment_id', DB::raw('count(*) as count'))
            ->groupBy('assessment_id')
            ->with('assessment:id,name,slug')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->assessment->name ?? 'غير معروف',
                'slug' => $item->assessment->slug ?? '',
                'count' => $item->count,
            ]);

        // Holland code distribution (top 5)
        $hollandDistribution = AssessmentAttempt::whereHas('assessment', function ($q) {
                $q->where('slug', 'holland');
            })
            ->whereNotNull('summary_level')
            ->select('summary_level', DB::raw('count(*) as count'))
            ->groupBy('summary_level')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Daily attempts for last 30 days (for line chart)
        $dailyAttempts = AssessmentAttempt::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Fill missing dates with 0
        $chartDates = [];
        $chartValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartDates[] = Carbon::parse($date)->format('m/d');
            $chartValues[] = $dailyAttempts[$date] ?? 0;
        }

        // Recent attempts
        $recentAttempts = AssessmentAttempt::with(['assessment', 'user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalAttempts',
            'todayAttempts',
            'weekAttempts',
            'monthAttempts',
            'newAttempts',
            'totalClients',
            'unreadMessages',
            'attemptsByType',
            'hollandDistribution',
            'chartDates',
            'chartValues',
            'recentAttempts'
        ));
    }
}






