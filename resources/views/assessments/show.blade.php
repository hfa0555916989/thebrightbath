@extends('layouts.public')

@push('analytics')
<script>
    // تتبع صفحة بدء الاختبار
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'صفحة اختبار - {{ $assessment["name"] ?? "اختبار" }}',
                'page_location': window.location.href,
                'custom_page_name': 'بدء اختبار: {{ $assessment["name"] ?? "اختبار" }}'
            });
            
            // حدث بدء اختبار
            gtag('event', 'begin_assessment', {
                'assessment_name': '{{ $assessment["name"] ?? "اختبار" }}',
                'questions_count': {{ count($questions ?? []) }}
            });
        }
    });
</script>
@endpush

@section('content')

<div class="min-h-screen bg-brand-bg py-8">
    <div class="container mx-auto px-4">
        
        {{-- Assessment Header --}}
        <div class="bg-gradient-to-l from-brand-dark to-brand-navy rounded-2xl p-8 mb-8 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $assessment['name'] }}</h1>
                    <p class="text-gray-300">{{ $assessment['description'] }}</p>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-2">
                        <i class="fas fa-clock ml-2 text-brand-gold"></i>
                        {{ $assessment['duration'] }}
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-2">
                        <i class="fas fa-question-circle ml-2 text-brand-gold"></i>
                        {{ count($questions) }} سؤال
                    </div>
                </div>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-brand-dark mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-brand-gold"></i>
                تعليمات الاختبار
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-brand-textMuted">
                @if($assessment['slug'] === 'career-fit')
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">1</span>
                    </div>
                    <p>اقرأ كل عبارة بتأنٍّ</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">2</span>
                    </div>
                    <p>ضع علامة ✓ على العبارات التي تنطبق عليك فقط</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">3</span>
                    </div>
                    <p>لا توجد إجابات صحيحة أو خاطئة — كن صادقاً</p>
                </div>
                @elseif($assessment['slug'] === 'work-values')
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">1</span>
                    </div>
                    <p>اقرأ وصف كل قيمة مهنية بعناية</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">2</span>
                    </div>
                    <p>حدد درجة أهميتها من 1 (غير مهم) إلى 5 (مهم جداً)</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">3</span>
                    </div>
                    <p>اختر بناءً على وظيفتك المثالية وليس وظيفتك الحالية</p>
                </div>
                @else
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">1</span>
                    </div>
                    <p>أجب على جميع الأسئلة بصدق حسب ما تشعر به حقاً</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">2</span>
                    </div>
                    <p>لا توجد إجابات صحيحة أو خاطئة</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-brand-gold/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-gold font-bold">3</span>
                    </div>
                    <p>اختر الإجابة الأقرب لشخصيتك</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Assessment Form --}}
        <form action="{{ route('assessments.submit', $assessment['slug']) }}" method="POST" id="assessmentForm">
            @csrf
            
            {{-- Progress Bar --}}
            <div class="sticky top-20 z-30 bg-white rounded-2xl shadow-lg p-4 mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-brand-dark font-medium">تقدم الاختبار</span>
                    <span class="text-brand-gold font-bold" id="progressText">0%</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-l from-brand-gold to-brand-orange transition-all duration-300 rounded-full"
                         id="progressBar"
                         style="width: {{ $assessment['slug'] === 'work-values' ? '100' : '0' }}%"></div>
                </div>
                <div class="text-sm text-brand-textMuted mt-2">
                    تم الإجابة على <span id="answeredCount">{{ $assessment['slug'] === 'work-values' ? count($questions) : '0' }}</span> من {{ count($questions) }}
                    {{ $assessment['slug'] === 'work-values' ? 'قيمة' : 'سؤال' }}
                </div>
            </div>

            {{-- Questions --}}
            @if($assessment['slug'] === 'career-fit')
            {{-- Career Fit: Checkbox (agree / disagree) --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <p class="text-brand-textMuted text-sm mb-1">
                    <i class="fas fa-info-circle text-brand-gold ml-1"></i>
                    اقرأ كل عبارة وضع علامة ✓ إذا كانت تنطبق عليك، أو اتركها فارغة إذا لم تنطبق.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($questions as $index => $question)
                <label class="cursor-pointer question-card" data-index="{{ $index }}">
                    <input type="hidden" name="answers[{{ $index }}]" value="0">
                    <input type="checkbox" name="answers[{{ $index }}]" value="1" class="sr-only peer answer-input">
                    <div class="flex items-center gap-4 bg-white rounded-2xl shadow p-5 border-2 border-gray-200
                                peer-checked:border-brand-gold peer-checked:bg-brand-gold/5 transition-all hover:border-brand-gold/50">
                        <div class="w-8 h-8 rounded-lg border-2 border-gray-300 peer-checked:border-brand-gold peer-checked:bg-brand-gold
                                    flex items-center justify-center flex-shrink-0 transition-all checkbox-icon">
                            <i class="fas fa-check text-white text-sm hidden check-icon"></i>
                        </div>
                        <span class="text-brand-dark font-medium leading-snug">{{ $question['text'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>

            @elseif($assessment['slug'] === 'work-values')
            {{-- Work Values: Card sorting with 5-level importance --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <p class="text-brand-textMuted text-sm">
                    <i class="fas fa-info-circle text-brand-gold ml-1"></i>
                    لكل بطاقة، حدد مدى أهميتها في وظيفتك المثالية من 1 (غير مهم) إلى 5 (مهم جداً).
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($questions as $index => $card)
                <div class="bg-white rounded-2xl shadow-lg p-5 question-card border-2 border-gray-200 transition-all" data-index="{{ $index }}">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 bg-brand-DEFAULT/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-tag text-brand-DEFAULT text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-brand-dark">{{ $card['title'] }}</h3>
                            <p class="text-sm text-brand-textMuted mt-1">{{ $card['description'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs text-brand-textMuted">غير مهم</span>
                        <div class="flex gap-2">
                            @foreach([1,2,3,4,5] as $val)
                            <label class="cursor-pointer">
                                <input type="radio" name="answers[{{ $index }}]" value="{{ $val }}"
                                       class="sr-only peer answer-input" {{ $val === 3 ? 'checked' : '' }}>
                                <div class="w-9 h-9 rounded-full border-2 border-gray-300 flex items-center justify-center
                                            text-sm font-bold text-gray-400 transition-all
                                            peer-checked:border-brand-gold peer-checked:bg-brand-gold peer-checked:text-white
                                            hover:border-brand-gold/60">
                                    {{ $val }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <span class="text-xs text-brand-textMuted">مهم جداً</span>
                    </div>
                </div>
                @endforeach
            </div>

            @else
            {{-- Default: Likert scale (Holland / MBTI / MI) --}}
            <div class="space-y-4">
                @foreach($questions as $index => $question)
                <div class="bg-white rounded-2xl shadow-lg p-6 question-card" data-index="{{ $index }}">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-brand-DEFAULT rounded-xl flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-brand-dark mb-4">{{ $question['text'] }}</h3>
                            <div class="grid grid-cols-5 gap-2 md:gap-4">
                                @php
                                    $options = [5 => 'أوافق بشدة', 4 => 'أوافق', 3 => 'محايد', 2 => 'لا أوافق', 1 => 'لا أوافق بشدة'];
                                @endphp
                                @foreach($options as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="answers[{{ $index }}]" value="{{ $value }}" class="sr-only peer answer-input" required>
                                    <div class="flex flex-col items-center p-2 md:p-4 rounded-xl border-2 border-gray-200 hover:border-brand-gold peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 transition-all">
                                        <div class="w-6 h-6 md:w-8 md:h-8 rounded-full border-2 border-gray-300 peer-checked:border-brand-gold peer-checked:bg-brand-gold flex items-center justify-center mb-1 md:mb-2 transition-all">
                                            <span class="text-xs md:text-sm font-bold text-gray-400 peer-checked:text-white">{{ $value }}</span>
                                        </div>
                                        <span class="text-[10px] md:text-xs text-center text-brand-textMuted leading-tight">{{ $label }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Submit Section --}}
            <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
                <div class="text-center">
                    <div class="mb-6">
                        <i class="fas fa-clipboard-check text-5xl text-brand-gold mb-4"></i>
                        <h3 class="text-xl font-bold text-brand-dark mb-2">هل أكملت جميع الأسئلة؟</h3>
                        <p class="text-brand-textMuted">تأكد من الإجابة على جميع الأسئلة قبل إرسال النتائج</p>
                    </div>
                    
                    <div id="submitWarning" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 hidden">
                        <p class="text-yellow-700">
                            <i class="fas fa-exclamation-triangle ml-2"></i>
                            لم تجب على جميع الأسئلة بعد. يرجى إكمال الاختبار.
                        </p>
                    </div>
                    
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center justify-center gap-3 bg-brand-gold text-brand-dark px-12 py-4 rounded-xl font-bold text-lg hover:bg-brand-goldDeep transition shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane"></i>
                        <span>عرض النتائج</span>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form          = document.getElementById('assessmentForm');
    const slug          = '{{ $assessment["slug"] }}';
    const totalQuestions = {{ count($questions) }};
    const progressBar   = document.getElementById('progressBar');
    const progressText  = document.getElementById('progressText');
    const answeredCount = document.getElementById('answeredCount');
    const submitWarning = document.getElementById('submitWarning');
    const submitBtn     = document.getElementById('submitBtn');

    function countAnswered() {
        if (slug === 'career-fit') {
            // For career-fit, progress = number of checkboxes that are checked (any interaction counts as answered)
            // We track answered cards via data attribute
            return document.querySelectorAll('.question-card.answered').length;
        }
        if (slug === 'work-values') {
            // All radio groups have a default value of 3, so all are "answered"
            return totalQuestions;
        }
        return document.querySelectorAll('.answer-input:checked').length;
    }

    function updateProgress() {
        const answered   = countAnswered();
        const percentage = slug === 'work-values'
            ? 100
            : Math.round((answered / totalQuestions) * 100);

        progressBar.style.width = percentage + '%';
        progressText.textContent = percentage + '%';
        answeredCount.textContent = slug === 'work-values' ? totalQuestions : answered;
    }

    // Career-fit: checkbox visual feedback
    if (slug === 'career-fit') {
        document.querySelectorAll('label.question-card').forEach(label => {
            const cb = label.querySelector('input[type="checkbox"]');
            const icon = label.querySelector('.check-icon');
            const box  = label.querySelector('.checkbox-icon');

            cb.addEventListener('change', function() {
                if (this.checked) {
                    label.classList.add('answered');
                    icon && icon.classList.remove('hidden');
                    box && box.classList.add('bg-brand-gold', 'border-brand-gold');
                } else {
                    label.classList.remove('answered');
                    icon && icon.classList.add('hidden');
                    box && box.classList.remove('bg-brand-gold', 'border-brand-gold');
                }
                updateProgress();
            });
        });
    }

    // Work-values: highlight selected radio
    if (slug === 'work-values') {
        updateProgress(); // all pre-selected
        document.querySelectorAll('.answer-input').forEach(input => {
            input.addEventListener('change', function() {
                const card = this.closest('.question-card');
                card.classList.add('border-brand-gold');
            });
        });
    }

    // Standard Likert inputs (holland/mbti/mi)
    if (slug !== 'career-fit' && slug !== 'work-values') {
        document.querySelectorAll('.answer-input').forEach(input => {
            input.addEventListener('change', function() {
                updateProgress();
                const card = this.closest('.question-card');
                card.classList.add('border-r-4', 'border-brand-gold');
                const currentIndex = parseInt(card.dataset.index);
                const nextCard = document.querySelector(`.question-card[data-index="${currentIndex + 1}"]`);
                if (nextCard) {
                    const isNextAnswered = Array.from(nextCard.querySelectorAll('.answer-input')).some(i => i.checked);
                    if (!isNextAnswered) {
                        setTimeout(() => nextCard.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300);
                    }
                }
            });
        });
    }

    // Form validation on submit
    form.addEventListener('submit', function(e) {
        if (slug === 'work-values') return; // always valid (defaults set)

        if (slug === 'career-fit') return; // checkboxes are optional by design

        const answered = document.querySelectorAll('.answer-input:checked').length;
        if (answered < totalQuestions) {
            e.preventDefault();
            submitWarning.classList.remove('hidden');
            const cards = document.querySelectorAll('.question-card');
            for (let card of cards) {
                const isAnswered = Array.from(card.querySelectorAll('.answer-input')).some(i => i.checked);
                if (!isAnswered) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.classList.add('ring-2', 'ring-red-400');
                    setTimeout(() => card.classList.remove('ring-2', 'ring-red-400'), 2000);
                    break;
                }
            }
        }
    });

    updateProgress();
});
</script>
@endpush

@push('styles')
<style>
    .question-card {
        transition: all 0.3s ease;
    }
    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    /* Custom radio button styling */
    .peer:checked ~ div {
        border-color: #F8C524;
        background-color: rgba(248, 197, 36, 0.1);
    }
    .peer:checked ~ div > div {
        border-color: #F8C524;
        background-color: #F8C524;
    }
    .peer:checked ~ div > div > span {
        color: white;
    }
</style>
@endpush
