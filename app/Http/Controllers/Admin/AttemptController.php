<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentAttempt;
use App\Models\User;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    /**
     * Display a listing of assessment attempts.
     */
    public function index(Request $request)
    {
        $query = AssessmentAttempt::with(['user', 'counselor'])
            ->orderBy('created_at', 'desc');

        // Filter by assessment type
        if ($request->filled('assessment')) {
            $query->where('assessment_slug', $request->assessment);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhere('type_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $attempts = $query->paginate(20);

        // Get statistics
        $stats = [
            'total' => AssessmentAttempt::count(),
            'today' => AssessmentAttempt::today()->count(),
            'this_week' => AssessmentAttempt::thisWeek()->count(),
            'this_month' => AssessmentAttempt::thisMonth()->count(),
            'new' => AssessmentAttempt::status('completed')->count(),
            'reviewed' => AssessmentAttempt::status('reviewed')->count(),
        ];

        // Assessment types for filter
        $assessmentTypes = [
            'holland' => 'اختبار هولاند',
            'mbti' => 'اختبار MBTI',
            'mi' => 'الذكاءات المتعددة',
            'work-values' => 'القيم المهنية',
            'career-fit' => 'الملاءمة المهنية',
        ];

        return view('admin.attempts.index', compact('attempts', 'stats', 'assessmentTypes'));
    }

    /**
     * Display the specified attempt.
     */
    public function show($id)
    {
        $attempt = AssessmentAttempt::with(['user', 'counselor'])->findOrFail($id);
        
        // Mark as viewed if new
        if ($attempt->status === 'completed') {
            $attempt->update(['status' => 'viewed']);
        }

        // Get counselors for assignment dropdown
        $counselors = User::whereIn('role', ['admin', 'counselor'])->get();

        return view('admin.attempts.show', compact('attempt', 'counselors'));
    }

    /**
     * Update the specified attempt.
     */
    public function update(Request $request, $id)
    {
        $attempt = AssessmentAttempt::findOrFail($id);

        $request->validate([
            'counselor_id' => 'nullable|exists:users,id',
            'counselor_notes' => 'nullable|string|max:5000',
            'status' => 'required|in:completed,viewed,reviewed',
        ]);

        $attempt->update([
            'counselor_id' => $request->counselor_id,
            'counselor_notes' => $request->counselor_notes,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
    }

    /**
     * Remove the specified attempt.
     */
    public function destroy($id)
    {
        $attempt = AssessmentAttempt::findOrFail($id);
        $attempt->delete();

        return redirect()->route('admin.attempts.index')->with('success', 'تم حذف المحاولة بنجاح');
    }

    /**
     * Export attempts to CSV
     */
    public function export(Request $request)
    {
        $query = AssessmentAttempt::with(['user', 'counselor'])
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('assessment')) {
            $query->where('assessment_slug', $request->assessment);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $attempts = $query->get();

        $filename = 'assessment_attempts_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($attempts) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'الرقم',
                'الاسم',
                'البريد الإلكتروني',
                'الهاتف',
                'الاختبار',
                'النتيجة',
                'الحالة',
                'المستشار',
                'ملاحظات المستشار',
                'التاريخ',
            ]);

            foreach ($attempts as $attempt) {
                fputcsv($file, [
                    $attempt->id,
                    $attempt->client_name,
                    $attempt->client_email,
                    $attempt->client_phone,
                    $attempt->assessment_name,
                    $attempt->type_code,
                    $attempt->status,
                    $attempt->counselor?->name ?? '-',
                    $attempt->counselor_notes ?? '-',
                    $attempt->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


