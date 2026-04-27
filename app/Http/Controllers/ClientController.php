<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAttempt;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Show client dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get assessment results
        $assessments = AssessmentAttempt::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        // Get upcoming bookings
        $upcomingBookings = Booking::with(['consultant.user'])
            ->where('user_id', $user->id)
            ->upcoming()
            ->limit(3)
            ->get();
        
        // Get recent payments
        $recentPayments = Payment::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        // Stats
        $stats = [
            'total_assessments' => AssessmentAttempt::where('user_id', $user->id)->count(),
            'total_sessions' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'upcoming_sessions' => Booking::where('user_id', $user->id)->upcoming()->count(),
            'total_spent' => Payment::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
        ];

        return view('client.dashboard', compact('user', 'assessments', 'upcomingBookings', 'recentPayments', 'stats'));
    }

    /**
     * Show all assessment results.
     */
    public function results()
    {
        $results = AssessmentAttempt::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('client.results', compact('results'));
    }

    /**
     * Show individual assessment result with full details.
     */
    public function showResult(AssessmentAttempt $attempt)
    {
        // Ensure the user owns this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذه النتيجة');
        }

        // Get interpretation data based on assessment type
        $interpretation = $this->getInterpretation($attempt);

        return view('client.result-detail', compact('attempt', 'interpretation'));
    }

    /**
     * Get interpretation for assessment result.
     */
    private function getInterpretation(AssessmentAttempt $attempt)
    {
        $slug = $attempt->assessment_slug;
        $scores = $attempt->scores_json ?? [];
        $typeCode = $attempt->type_code;

        if ($slug === 'holland') {
            return $this->getHollandInterpretation($typeCode, $scores);
        } elseif ($slug === 'mbti') {
            return $this->getMBTIInterpretation($typeCode);
        } elseif ($slug === 'mi') {
            return $this->getMIInterpretation($typeCode, $scores);
        }

        return null;
    }

    /**
     * Get Holland interpretation.
     */
    private function getHollandInterpretation($typeCode, $scores)
    {
        $dimensionDescriptions = [
            'R' => [
                'name' => 'الواقعي (Realistic)',
                'description' => 'تميل إلى العمل مع الأشياء والآلات، وتفضل الأنشطة البدنية والعملية.',
                'careers' => ['مهندس', 'فني', 'ميكانيكي', 'طيار', 'مزارع', 'كهربائي', 'نجار'],
            ],
            'I' => [
                'name' => 'الباحث (Investigative)',
                'description' => 'تحب التفكير والتحليل والبحث، وتستمتع بحل المشكلات العلمية.',
                'careers' => ['عالم', 'باحث', 'طبيب', 'محلل بيانات', 'مهندس برمجيات', 'صيدلي'],
            ],
            'A' => [
                'name' => 'الفني (Artistic)',
                'description' => 'تميل إلى الإبداع والتعبير عن النفس، وتقدر الجمال والفن.',
                'careers' => ['مصمم', 'فنان', 'كاتب', 'مخرج', 'موسيقي', 'مصور', 'معماري'],
            ],
            'S' => [
                'name' => 'الاجتماعي (Social)',
                'description' => 'تحب العمل مع الناس ومساعدتهم، ولديك مهارات تواصل قوية.',
                'careers' => ['معلم', 'مرشد', 'أخصائي اجتماعي', 'ممرض', 'موارد بشرية', 'مدرب'],
            ],
            'E' => [
                'name' => 'المقدام (Enterprising)',
                'description' => 'تحب القيادة والتأثير، ولديك طموح للنجاح والإنجاز.',
                'careers' => ['مدير', 'رائد أعمال', 'مسوق', 'محامي', 'سياسي', 'مندوب مبيعات'],
            ],
            'C' => [
                'name' => 'التقليدي (Conventional)',
                'description' => 'تفضل النظام والتنظيم، وتجيد العمل مع البيانات والتفاصيل.',
                'careers' => ['محاسب', 'إداري', 'سكرتير', 'مدقق', 'أمين مكتبة', 'موظف بنك'],
            ],
        ];

        $topType = $typeCode ? substr($typeCode, 0, 1) : null;
        $mainDescription = $topType ? ($dimensionDescriptions[$topType] ?? null) : null;

        return [
            'type' => 'holland',
            'main_type' => $mainDescription,
            'dimension_descriptions' => $dimensionDescriptions,
            'summary' => $mainDescription ? "كود هولاند الخاص بك هو: {$typeCode}. النمط السائد لديك هو {$mainDescription['name']}." : null,
        ];
    }

    /**
     * Get MBTI interpretation.
     */
    private function getMBTIInterpretation($typeCode)
    {
        $types = [
            'INTJ' => ['name' => 'المهندس المعماري', 'description' => 'مفكر استراتيجي، مبدع، ومستقل. يحب التخطيط طويل المدى.'],
            'INTP' => ['name' => 'المنطقي', 'description' => 'محب للمعرفة، تحليلي، ومبتكر. يستمتع بحل المشكلات المعقدة.'],
            'ENTJ' => ['name' => 'القائد', 'description' => 'قائد طبيعي، حازم، وطموح. يجيد اتخاذ القرارات.'],
            'ENTP' => ['name' => 'المناظر', 'description' => 'مبدع، ذكي، ويحب التحدي. يستمتع بالنقاشات الفكرية.'],
            'INFJ' => ['name' => 'المدافع', 'description' => 'مثالي، متعاطف، ومبدع. يسعى لمساعدة الآخرين.'],
            'INFP' => ['name' => 'الوسيط', 'description' => 'مثالي، حساس، ومبدع. يقدر القيم والمعتقدات.'],
            'ENFJ' => ['name' => 'البطل', 'description' => 'قائد ملهم، متعاطف، وكاريزمي. يحب مساعدة الآخرين على النمو.'],
            'ENFP' => ['name' => 'المناصر', 'description' => 'متحمس، مبدع، واجتماعي. يحب الاحتمالات الجديدة.'],
            'ISTJ' => ['name' => 'اللوجستي', 'description' => 'عملي، موثوق، ومنظم. يقدر التقاليد والمسؤولية.'],
            'ISFJ' => ['name' => 'المدافع', 'description' => 'متعاطف، موثوق، ومتفان. يهتم برفاهية الآخرين.'],
            'ESTJ' => ['name' => 'المدير التنفيذي', 'description' => 'منظم، عملي، وحازم. يجيد إدارة المشاريع.'],
            'ESFJ' => ['name' => 'القنصل', 'description' => 'اجتماعي، متعاون، ومهتم. يحب رعاية الآخرين.'],
            'ISTP' => ['name' => 'الحرفي', 'description' => 'عملي، مرن، ومحلل. يجيد حل المشكلات التقنية.'],
            'ISFP' => ['name' => 'المغامر', 'description' => 'فني، حساس، ومرن. يقدر الجمال والتناغم.'],
            'ESTP' => ['name' => 'رائد الأعمال', 'description' => 'نشيط، عملي، ومغامر. يحب الإثارة والتحديات.'],
            'ESFP' => ['name' => 'المؤدي', 'description' => 'عفوي، نشيط، وممتع. يحب الترفيه ومشاركة الآخرين.'],
        ];

        $typeInfo = $types[$typeCode] ?? ['name' => $typeCode, 'description' => 'نمط شخصيتك فريد.'];

        return [
            'type' => 'mbti',
            'type_info' => $typeInfo,
            'summary' => "نمط شخصيتك هو: {$typeCode} - {$typeInfo['name']}",
        ];
    }

    /**
     * Get Multiple Intelligences interpretation.
     */
    private function getMIInterpretation($typeCode, $scores)
    {
        $intelligences = [
            'linguistic' => ['name' => 'الذكاء اللغوي', 'description' => 'قدرة متميزة على استخدام الكلمات والتعبير اللغوي.', 'careers' => ['كاتب', 'محامي', 'صحفي', 'معلم لغات']],
            'logical' => ['name' => 'الذكاء المنطقي-الرياضي', 'description' => 'قدرة على التفكير المنطقي وحل المسائل الرياضية.', 'careers' => ['مهندس', 'عالم', 'محاسب', 'مبرمج']],
            'spatial' => ['name' => 'الذكاء المكاني', 'description' => 'قدرة على التفكير بالصور والتصور المكاني.', 'careers' => ['مهندس معماري', 'فنان', 'طيار', 'مصمم']],
            'musical' => ['name' => 'الذكاء الموسيقي', 'description' => 'حساسية للإيقاعات والألحان والأصوات.', 'careers' => ['موسيقي', 'مغني', 'منتج موسيقي', 'معلم موسيقى']],
            'kinesthetic' => ['name' => 'الذكاء الجسدي-الحركي', 'description' => 'قدرة على استخدام الجسم بمهارة.', 'careers' => ['رياضي', 'راقص', 'جراح', 'حرفي']],
            'interpersonal' => ['name' => 'الذكاء الاجتماعي', 'description' => 'قدرة على فهم الآخرين والتواصل معهم.', 'careers' => ['مرشد', 'مدير', 'معالج نفسي', 'مسوق']],
            'intrapersonal' => ['name' => 'الذكاء الذاتي', 'description' => 'قدرة على فهم الذات والمشاعر الداخلية.', 'careers' => ['كاتب', 'فيلسوف', 'مستشار', 'رائد أعمال']],
            'naturalistic' => ['name' => 'الذكاء الطبيعي', 'description' => 'قدرة على فهم الطبيعة والكائنات الحية.', 'careers' => ['عالم أحياء', 'مزارع', 'طبيب بيطري', 'عالم بيئة']],
        ];

        $topThree = $typeCode ? explode('-', $typeCode) : [];
        $topIntelligences = [];
        foreach ($topThree as $key) {
            if (isset($intelligences[$key])) {
                $topIntelligences[] = $intelligences[$key];
            }
        }

        return [
            'type' => 'mi',
            'top_intelligences' => $topIntelligences,
            'all_intelligences' => $intelligences,
            'summary' => count($topIntelligences) > 0 ? 'ذكاءاتك الأقوى هي: ' . implode('، ', array_column($topIntelligences, 'name')) : null,
        ];
    }

    /**
     * Show all sessions/bookings.
     */
    public function sessions()
    {
        $upcomingBookings = Booking::with(['consultant.user'])
            ->where('user_id', Auth::id())
            ->upcoming()
            ->get();

        $pastBookings = Booking::with(['consultant.user'])
            ->where('user_id', Auth::id())
            ->past()
            ->paginate(10);

        return view('client.sessions', compact('upcomingBookings', 'pastBookings'));
    }

    /**
     * Show invoices/payments.
     */
    public function invoices()
    {
        $payments = Payment::with('booking.consultant.user')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('client.invoices', compact('payments'));
    }

    /**
     * Show profile edit form.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('client.profile', compact('user'));
    }

    /**
     * Update profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}




