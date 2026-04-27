@extends('layouts.public')

@section('title', 'نتيجة الاختبار')

@push('analytics')
<script>
    // تتبع صفحة نتيجة الاختبار
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            @if(isset($result) && !empty($result))
            gtag('event', 'page_view', {
                'page_title': 'نتيجة اختبار - {{ $result["assessment"]["name"] ?? "الاختبار" }}',
                'page_location': window.location.href,
                'custom_page_name': 'نتيجة اختبار: {{ $result["assessment"]["name"] ?? "الاختبار" }}'
            });
            
            // حدث إتمام الاختبار
            gtag('event', 'complete_assessment', {
                'assessment_name': '{{ $result["assessment"]["name"] ?? "الاختبار" }}',
                'result_code': '{{ $result["type_code"] ?? "غير محدد" }}'
            });
            @else
            gtag('event', 'page_view', {
                'page_title': 'نتيجة الاختبار - لا توجد نتيجة',
                'page_location': window.location.href,
                'custom_page_name': 'صفحة نتيجة فارغة'
            });
            @endif
        }
    });
</script>
@endpush

@section('content')
<div class="min-h-screen bg-brand-bg py-8">
    <div class="container mx-auto px-4">
        
        @if(!isset($result) || empty($result))
            <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-lg p-8 text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-brand-dark mb-4">لا توجد نتيجة</h2>
                <p class="text-gray-600 mb-6">يرجى إكمال الاختبار أولاً للحصول على النتيجة</p>
                <a href="{{ route('assessments.index') }}" class="inline-block bg-brand-gold text-brand-dark px-8 py-3 rounded-xl font-bold">
                    العودة للاختبارات
                </a>
            </div>
        @else
            {{-- Success Header --}}
            <div class="bg-gradient-to-l from-brand-dark to-brand-navy rounded-2xl p-8 mb-8 text-white text-center">
                <div class="w-20 h-20 bg-brand-gold/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-brand-gold text-4xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">تهانينا! تم إكمال الاختبار</h1>
                <p class="text-gray-300">{{ $result['assessment']['name'] ?? 'الاختبار' }}</p>
            </div>

            {{-- Main Result Card --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                {{-- Result Type Header --}}
                <div class="bg-gradient-to-l from-brand-gold to-brand-orange p-8 text-center">
                    <h2 class="text-2xl font-bold text-brand-dark mb-2">نتيجتك</h2>
                    <div class="text-5xl font-bold text-brand-dark mb-4">{{ $result['type_code'] ?? '-' }}</div>
                    
                    @if(isset($slug) && $slug === 'holland' && isset($result['interpretation']['summary']))
                        <p class="text-brand-dark/80">{{ $result['interpretation']['summary'] }}</p>
                    @elseif(isset($slug) && $slug === 'mbti' && isset($result['interpretation']['type_info']['name']))
                        <p class="text-brand-dark/80 text-xl">{{ $result['interpretation']['type_info']['name'] }}</p>
                    @elseif(isset($slug) && $slug === 'mi' && isset($result['interpretation']['summary']))
                        <p class="text-brand-dark/80">{{ $result['interpretation']['summary'] }}</p>
                    @endif
                </div>

                {{-- Scores Section --}}
                <div class="p-8">
                    <h3 class="text-xl font-bold text-brand-dark mb-6 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-brand-gold"></i>
                        تفاصيل النتائج
                    </h3>

                    @if(isset($slug) && $slug === 'holland' && isset($result['interpretation']['dimension_descriptions']))
                        {{-- Holland Results --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                            @foreach($result['interpretation']['dimension_descriptions'] as $code => $dim)
                                @php
                                    $score = $result['scores'][$code] ?? 0;
                                    $maxScore = 35;
                                    $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;
                                @endphp
                                <div class="bg-brand-bg rounded-xl p-4 {{ isset($result['type_code']) && strlen($result['type_code']) > 0 && substr($result['type_code'], 0, 1) === $code ? 'ring-2 ring-brand-gold' : '' }}">
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
                        @if(isset($result['type_code']) && strlen($result['type_code']) > 0)
                            @php
                                $topType = substr($result['type_code'], 0, 1);
                                $mainType = $result['interpretation']['dimension_descriptions'][$topType] ?? null;
                            @endphp
                            @if($mainType)
                            <div class="bg-brand-bg rounded-xl p-6">
                                <h4 class="font-bold text-brand-dark mb-4 flex items-center gap-2">
                                    <i class="fas fa-briefcase text-brand-gold"></i>
                                    الوظائف المقترحة لنمط {{ $mainType['name'] ?? '' }}
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(($mainType['careers'] ?? []) as $career)
                                        <span class="bg-white px-4 py-2 rounded-full text-brand-dark border border-brand-border">{{ $career }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endif

                    @elseif(isset($slug) && $slug === 'mbti' && isset($result['scores']))
                        {{-- MBTI Results --}}
                        <div class="space-y-6 mb-8">
                            @php
                                $pairs = [
                                    ['E' => 'الانفتاح', 'I' => 'الانطواء'],
                                    ['S' => 'الحسي', 'N' => 'الحدسي'],
                                    ['T' => 'التفكير', 'F' => 'الشعور'],
                                    ['J' => 'الحكم', 'P' => 'الإدراك'],
                                ];
                            @endphp
                            
                            @foreach($pairs as $pair)
                                @php
                                    $keys = array_keys($pair);
                                    $left = $keys[0];
                                    $right = $keys[1];
                                    $leftScore = $result['scores'][$left] ?? 0;
                                    $rightScore = $result['scores'][$right] ?? 0;
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
                        @if(isset($result['interpretation']['type_info']['description']))
                        <div class="bg-brand-bg rounded-xl p-6">
                            <h4 class="font-bold text-brand-dark mb-4 flex items-center gap-2">
                                <i class="fas fa-user text-brand-gold"></i>
                                وصف نمط شخصيتك
                            </h4>
                            <p class="text-brand-textMuted leading-relaxed">
                                {{ $result['interpretation']['type_info']['description'] }}
                            </p>
                        </div>
                        @endif

                    @elseif(isset($slug) && $slug === 'mi' && isset($result['scores']))
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
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            @foreach($result['scores'] as $key => $score)
                                @php
                                    $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;
                                    $isTop = isset($result['type_code']) && in_array($key, explode('-', $result['type_code']));
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
                        @if(isset($result['interpretation']['top_intelligences']))
                        <div class="bg-brand-bg rounded-xl p-6">
                            <h4 class="font-bold text-brand-dark mb-4 flex items-center gap-2">
                                <i class="fas fa-star text-brand-gold"></i>
                                أقوى ذكاءاتك
                            </h4>
                            <div class="space-y-4">
                                @foreach($result['interpretation']['top_intelligences'] as $intel)
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
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('assessments.show', $slug ?? 'holland') }}" 
                       class="flex items-center justify-center gap-3 bg-brand-bg text-brand-dark px-6 py-4 rounded-xl font-bold hover:bg-gray-200 transition">
                        <i class="fas fa-redo"></i>
                        <span>إعادة الاختبار</span>
                    </a>
                    
                    <a href="{{ route('assessments.index') }}" 
                       class="flex items-center justify-center gap-3 bg-brand-DEFAULT text-white px-6 py-4 rounded-xl font-bold hover:bg-brand-dark transition">
                        <i class="fas fa-list"></i>
                        <span>اختبارات أخرى</span>
                    </a>
                    
                    <a href="https://wa.me/966543494316?text={{ urlencode('السلام عليكم، أكملت ' . ($result['assessment']['name'] ?? 'الاختبار') . ' ونتيجتي ' . ($result['type_code'] ?? '-') . ' وأرغب في استشارة') }}" 
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
        @endif
    </div>
</div>
@endsection
