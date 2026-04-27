@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('content')
@php
    use App\Models\AssessmentAttempt;
    use App\Models\User;
    
    // Statistics
    $totalAttempts = AssessmentAttempt::count();
    $todayAttempts = AssessmentAttempt::today()->count();
    $weekAttempts = AssessmentAttempt::thisWeek()->count();
    $monthAttempts = AssessmentAttempt::thisMonth()->count();
    $newAttempts = AssessmentAttempt::status('completed')->count();
    $totalUsers = User::where('role', 'client')->count();
    
    // Recent attempts
    $recentAttempts = AssessmentAttempt::with('user')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    // Assessment distribution
    $assessmentStats = AssessmentAttempt::selectRaw('assessment_slug, assessment_name, count(*) as count')
        ->groupBy('assessment_slug', 'assessment_name')
        ->orderByDesc('count')
        ->get();
    
    // Top Holland codes
    $topCodes = AssessmentAttempt::where('assessment_slug', 'holland')
        ->selectRaw('type_code, count(*) as count')
        ->groupBy('type_code')
        ->orderByDesc('count')
        ->take(5)
        ->get();
@endphp

<div class="space-y-6">
    
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-l from-brand-DEFAULT to-brand-dark rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">مرحباً، {{ auth()->user()->name }} 👋</h1>
                <p class="text-gray-300">إليك نظرة عامة على أداء الموقع</p>
            </div>
            <div class="hidden md:block text-left">
                <div class="text-sm text-gray-300">{{ now()->locale('ar')->translatedFormat('l') }}</div>
                <div class="text-2xl font-bold">{{ now()->format('Y/m/d') }}</div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-brand-gold text-xl"></i>
                </div>
                <span class="text-green-500 text-sm">
                    <i class="fas fa-arrow-up"></i>
                </span>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalAttempts) }}</div>
            <div class="text-gray-500 text-sm">إجمالي الاختبارات</div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($todayAttempts) }}</div>
            <div class="text-gray-500 text-sm">اليوم</div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-week text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($weekAttempts) }}</div>
            <div class="text-gray-500 text-sm">هذا الأسبوع</div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($monthAttempts) }}</div>
            <div class="text-gray-500 text-sm">هذا الشهر</div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bell text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-yellow-600">{{ number_format($newAttempts) }}</div>
            <div class="text-gray-500 text-sm">جديد (لم تتم المراجعة)</div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</div>
            <div class="text-gray-500 text-sm">المستخدمين المسجلين</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Recent Attempts --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clock text-brand-gold"></i>
                    آخر النتائج
                </h2>
                <a href="{{ route('admin.attempts.index') }}" class="text-brand-DEFAULT text-sm hover:underline">
                    عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentAttempts as $attempt)
                <a href="{{ route('admin.attempts.show', $attempt->id) }}" 
                   class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full {{ $attempt->status === 'completed' ? 'bg-yellow-100' : 'bg-gray-100' }} flex items-center justify-center">
                            @if($attempt->status === 'completed')
                                <i class="fas fa-circle text-yellow-500 text-xs"></i>
                            @else
                                <i class="fas fa-check text-gray-400"></i>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $attempt->client_name }}</div>
                            <div class="text-sm text-gray-500">{{ $attempt->assessment_name }}</div>
                        </div>
                    </div>
                    <div class="text-left">
                        <div class="font-bold text-brand-gold">{{ $attempt->type_code }}</div>
                        <div class="text-xs text-gray-400">{{ $attempt->created_at->diffForHumans() }}</div>
                    </div>
                </a>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                    <p>لا توجد نتائج بعد</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar Stats --}}
        <div class="space-y-6">
            
            {{-- Assessment Distribution --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-brand-gold"></i>
                    توزيع الاختبارات
                </h3>
                <div class="space-y-3">
                    @forelse($assessmentStats as $stat)
                    @php
                        $percentage = $totalAttempts > 0 ? round(($stat->count / $totalAttempts) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">{{ $stat->assessment_name }}</span>
                            <span class="text-gray-900 font-medium">{{ $stat->count }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-gold rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">لا توجد بيانات</p>
                    @endforelse
                </div>
            </div>

            {{-- Top Holland Codes --}}
            @if($topCodes->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-trophy text-brand-gold"></i>
                    أكثر أكواد هولاند شيوعاً
                </h3>
                <div class="space-y-2">
                    @foreach($topCodes as $index => $code)
                    <div class="flex items-center justify-between p-2 {{ $index === 0 ? 'bg-brand-gold/10 rounded-lg' : '' }}">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-brand-DEFAULT text-white text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <span class="font-bold text-gray-900">{{ $code->type_code }}</span>
                        </div>
                        <span class="text-gray-500">{{ $code->count }} مرة</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Links --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-link text-brand-gold"></i>
                    روابط سريعة
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.attempts.index') }}" 
                       class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-clipboard-list text-brand-DEFAULT"></i>
                        <span>جميع النتائج</span>
                    </a>
                    <a href="{{ route('admin.attempts.index', ['status' => 'completed']) }}" 
                       class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                        <i class="fas fa-bell text-yellow-600"></i>
                        <span>النتائج الجديدة</span>
                        @if($newAttempts > 0)
                        <span class="mr-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $newAttempts }}</span>
                        @endif
                    </a>
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-external-link-alt text-gray-600"></i>
                        <span>زيارة الموقع</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
