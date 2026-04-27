@extends('layouts.public')

@section('title', 'تفاصيل نتيجة الاختبار')

@section('content')
<section class="pt-28 pb-12 bg-brand-bg min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('client.results') }}" class="inline-flex items-center gap-2 text-brand-textMuted hover:text-brand-gold transition">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة لنتائجي</span>
                </a>
            </div>

            {{-- Success Header --}}
            <div class="bg-gradient-to-l from-brand-dark to-brand-navy rounded-2xl p-8 mb-8 text-white text-center">
                <div class="w-20 h-20 bg-brand-gold/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-award text-brand-gold text-4xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">{{ $attempt->assessment_name }}</h1>
                <p class="text-gray-300">
                    <i class="fas fa-calendar ml-1"></i>
                    {{ $attempt->created_at->format('Y-m-d') }}
                    <span class="mx-2">|</span>
                    <i class="fas fa-clock ml-1"></i>
                    {{ $attempt->created_at->format('h:i A') }}
                </p>
            </div>

            {{-- Main Result Card --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                {{-- Result Type Header --}}
                <div class="bg-gradient-to-l from-brand-gold to-brand-orange p-8 text-center">
                    <h2 class="text-2xl font-bold text-brand-dark mb-2">نتيجتك</h2>
                    <div class="text-5xl font-bold text-brand-dark mb-4">{{ $attempt->type_code ?? '-' }}</div>
                    
                    @if($attempt->summary)
                        <p class="text-brand-dark/80">{{ $attempt->summary }}</p>
                    @endif
                </div>

                {{-- Scores Section --}}
                <div class="p-8">
                    <h3 class="text-xl font-bold text-brand-dark mb-6 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-brand-gold"></i>
                        تفاصيل النتائج
                    </h3>

                    @if($attempt->assessment_slug === 'holland' && $interpretation)
                        {{-- Holland Results --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                            @foreach($interpretation['dimension_descriptions'] as $code => $dim)
                                @php
                                    $score = $attempt->scores_json[$code] ?? 0;
                                    $maxScore = 35;
                                    $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;
                                    $isTop = $attempt->type_code && strlen($attempt->type_code) > 0 && substr($attempt->type_code, 0, 1) === $code;
                                @endphp
                                <div class="bg-brand-bg rounded-xl p-4 {{ $isTop ? 'ring-2 ring-brand-gold' : '' }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-brand-dark">{{ $dim['name'] ?? $code }}</span>
                                        <span class="text-brand-gold font-bold">{{ $score }}</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden mb-2">
                                        <div class="h-full bg-gradient-to-l from-brand-gold to-brand-orange rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <p class="text-xs text-brand-textMuted">{{ $dim['description'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Career Suggestions --}}
                        @if($interpretation['main_type'])
                        <div class="bg-brand-bg rounded-xl p-6">
                            <h4 class="font-bold text-brand-dark mb-4 flex items-center gap-2">
                                <i class="fas fa-briefcase text-brand-gold"></i>
                                الوظائف المقترحة لنمط {{ $interpretation['main_type']['name'] ?? '' }}
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach(($interpretation['main_type']['careers'] ?? []) as $career)
                                    <span class="bg-white px-4 py-2 rounded-full text-brand-dark border border-brand-border">{{ $career }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    @elseif($attempt->assessment_slug === 'mbti' && $interpretation)
                        {{-- MBTI Results --}}
                        <div class="space-y-6 mb-8">
                            @php
                                $pairs = [
                                    ['E' => 'الانفتاح', 'I' => 'الانطواء'],
                                    ['S' => 'الحسي', 'N' => 'الحدسي'],
                                    ['T' => 'التفكير', 'F' => 'الشعور'],
                                    ['J' => 'الحكم', 'P' => 'الإدراك'],
                                ];
                                $scores = $attempt->scores_json ?? [];
                            @endphp
                            
                            @foreach($pairs as $pair)
                                @php
                                    $keys = array_keys($pair);
                                    $left = $keys[0];
                                    $right = $keys[1];
                                    $leftScore = $scores[$left] ?? 0;
                                    $rightScore = $scores[$right] ?? 0;
                                    $total = $leftScore + $rightScore;
                                    $leftPercent = $total > 0 ? round(($leftScore / $total) * 100) : 50;
                                @endphp
                                <div class="bg-brand-bg rounded-xl p-4">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-bold text-brand-dark">{{ $pair[$left] }} ({{ $left }})</span>
                                        <span class="font-bold text-brand-dark">{{ $pair[$right] }} ({{ $right }})</span>
                                    </div>
                                    <div class="h-4 bg-gray-200 rounded-full overflow-hidden flex">
                                        <div class="h-full bg-brand-gold" style="width: {{ $leftPercent }}%"></div>
                                        <div class="h-full bg-brand-DEFAULT" style="width: {{ 100 - $leftPercent }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-1 text-sm text-brand-textMuted">
                                        <span>{{ $leftPercent }}%</span>
                                        <span>{{ 100 - $leftPercent }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Type Description --}}
                        @if(isset($interpretation['type_info']['description']))
                        <div class="bg-brand-bg rounded-xl p-6">
                            <h4 class="font-bold text-brand-dark mb-4 flex items-center gap-2">
                                <i class="fas fa-user text-brand-gold"></i>
                                {{ $interpretation['type_info']['name'] ?? $attempt->type_code }}
                            </h4>
                            <p class="text-brand-textMuted leading-relaxed">
                                {{ $interpretation['type_info']['description'] }}
                            </p>
                        </div>
                        @endif

                    @elseif($attempt->assessment_slug === 'mi' && $interpretation)
                        {{-- Multiple Intelligences Results --}}
                        @php
                            $maxScore = 25;
                            $intelligenceNames = [
                                'linguistic' => 'اللغوي',
                                'logical' => 'المنطقي-الرياضي',
                                'spatial' => 'المكاني',
                                'musical' => 'الموسيقي',
                                'kinesthetic' => 'الجسدي-الحركي',
                                'interpersonal' => 'الاجتماعي',
                                'intrapersonal' => 'الذاتي',
                                'naturalistic' => 'الطبيعي',
                            ];
                            $scores = $attempt->scores_json ?? [];
                            $typeCodeParts = $attempt->type_code ? explode('-', $attempt->type_code) : [];
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            @foreach($scores as $key => $score)
                                @php
                                    $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;
                                    $isTop = in_array($key, $typeCodeParts);
                                @endphp
                                <div class="bg-brand-bg rounded-xl p-4 {{ $isTop ? 'ring-2 ring-brand-gold' : '' }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-brand-dark">الذكاء {{ $intelligenceNames[$key] ?? $key }}</span>
                                        <span class="text-brand-gold font-bold">{{ $score }}</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $isTop ? 'bg-brand-gold' : 'bg-brand-DEFAULT' }} rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Top Intelligences --}}
                        @if(isset($interpretation['top_intelligences']) && count($interpretation['top_intelligences']) > 0)
                        <div class="bg-brand-bg rounded-xl p-6">
                            <h4 class="font-bold text-brand-dark mb-4 flex items-center gap-2">
                                <i class="fas fa-star text-brand-gold"></i>
                                أقوى ذكاءاتك
                            </h4>
                            <div class="space-y-4">
                                @foreach($interpretation['top_intelligences'] as $intel)
                                    <div class="bg-white rounded-lg p-4">
                                        <h5 class="font-bold text-brand-dark mb-2">{{ $intel['name'] ?? '' }}</h5>
                                        <p class="text-brand-textMuted text-sm mb-3">{{ $intel['description'] ?? '' }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(($intel['careers'] ?? []) as $career)
                                                <span class="bg-brand-gold/10 text-brand-dark text-xs px-3 py-1 rounded-full">{{ $career }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    @else
                        {{-- Generic Scores Display --}}
                        @if($attempt->scores_json)
                        <div class="space-y-4">
                            @php
                                $scores = $attempt->scores_json;
                                $maxScore = count($scores) > 0 ? max($scores) : 1;
                            @endphp
                            @foreach($scores as $dimension => $score)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-gray-700 font-medium">{{ $dimension }}</span>
                                    <span class="text-brand-DEFAULT font-bold">{{ $score }}</span>
                                </div>
                                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-l from-brand-gold to-brand-orange rounded-full" 
                                         style="width: {{ ($score / $maxScore) * 100 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Answers Section --}}
            @if($attempt->answers_json && count($attempt->answers_json) > 0)
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h3 class="text-xl font-bold text-brand-dark mb-6 flex items-center gap-2">
                    <i class="fas fa-list-check text-brand-gold"></i>
                    إجاباتك
                </h3>
                
                <div class="space-y-3">
                    @php
                        $answerLabels = [
                            1 => 'لا أوافق بشدة',
                            2 => 'لا أوافق',
                            3 => 'محايد',
                            4 => 'أوافق',
                            5 => 'أوافق بشدة',
                        ];
                    @endphp
                    @foreach($attempt->answers_json as $index => $answer)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">السؤال {{ $index + 1 }}</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($answer == 5) bg-green-100 text-green-700
                            @elseif($answer == 4) bg-blue-100 text-blue-700
                            @elseif($answer == 3) bg-gray-100 text-gray-700
                            @elseif($answer == 2) bg-orange-100 text-orange-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ $answerLabels[$answer] ?? $answer }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('assessments.show', $attempt->assessment_slug ?? 'holland') }}" 
                       class="flex items-center justify-center gap-3 bg-brand-bg text-brand-dark px-6 py-4 rounded-xl font-bold hover:bg-gray-200 transition">
                        <i class="fas fa-redo"></i>
                        <span>إعادة الاختبار</span>
                    </a>
                    
                    <a href="{{ route('assessments.index') }}" 
                       class="flex items-center justify-center gap-3 bg-brand-DEFAULT text-white px-6 py-4 rounded-xl font-bold hover:bg-brand-dark transition">
                        <i class="fas fa-list"></i>
                        <span>اختبارات أخرى</span>
                    </a>
                    
                    <a href="https://wa.me/966543494316?text={{ urlencode('السلام عليكم، أكملت ' . $attempt->assessment_name . ' ونتيجتي ' . ($attempt->type_code ?? '-') . ' وأرغب في استشارة') }}" 
                       target="_blank"
                       class="flex items-center justify-center gap-3 bg-green-500 text-white px-6 py-4 rounded-xl font-bold hover:bg-green-600 transition">
                        <i class="fab fa-whatsapp"></i>
                        <span>احصل على استشارة</span>
                    </a>
                </div>
            </div>

            {{-- Book Consultation --}}
            <div class="mt-8 bg-gradient-to-l from-brand-dark to-brand-navy rounded-2xl p-8 text-white">
                <div class="text-center max-w-2xl mx-auto">
                    <i class="fas fa-user-tie text-4xl text-brand-gold mb-4"></i>
                    <h3 class="text-2xl font-bold mb-4">احجز جلسة استشارية</h3>
                    <p class="text-gray-300 mb-6">
                        تحدث مع مستشار متخصص لفهم نتائجك بشكل أعمق واكتشاف الخطوات التالية
                    </p>
                    <a href="{{ route('consultations.index') }}" class="inline-flex items-center justify-center gap-2 bg-brand-gold text-brand-dark px-8 py-3 rounded-xl font-bold hover:bg-white transition">
                        <i class="fas fa-calendar-check"></i>
                        <span>احجز موعد الآن</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

