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
                    <div class="h-full bg-gradient-to-l from-brand-gold to-brand-orange transition-all duration-300 rounded-full" id="progressBar" style="width: 0%"></div>
                </div>
                <div class="text-sm text-brand-textMuted mt-2">
                    تم الإجابة على <span id="answeredCount">0</span> من {{ count($questions) }} سؤال
                </div>
            </div>

            {{-- Questions --}}
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
                                    $options = [
                                        5 => 'أوافق بشدة',
                                        4 => 'أوافق',
                                        3 => 'محايد',
                                        2 => 'لا أوافق',
                                        1 => 'لا أوافق بشدة'
                                    ];
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
    const form = document.getElementById('assessmentForm');
    const inputs = document.querySelectorAll('.answer-input');
    const totalQuestions = {{ count($questions) }};
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const answeredCount = document.getElementById('answeredCount');
    const submitWarning = document.getElementById('submitWarning');
    const submitBtn = document.getElementById('submitBtn');

    function updateProgress() {
        const answered = document.querySelectorAll('.answer-input:checked').length;
        const percentage = Math.round((answered / totalQuestions) * 100);
        
        progressBar.style.width = percentage + '%';
        progressText.textContent = percentage + '%';
        answeredCount.textContent = answered;

        // Update submit button state
        if (answered === totalQuestions) {
            submitWarning.classList.add('hidden');
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = false; // Allow submit but show warning
        }
    }

    // Add change event to all inputs
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            updateProgress();
            
            // Visual feedback for answered question
            const card = this.closest('.question-card');
            card.classList.add('border-r-4', 'border-brand-gold');
            
            // Smooth scroll to next unanswered question
            const currentIndex = parseInt(card.dataset.index);
            const nextCard = document.querySelector(`.question-card[data-index="${currentIndex + 1}"]`);
            if (nextCard) {
                const nextInputs = nextCard.querySelectorAll('.answer-input');
                const isNextAnswered = Array.from(nextInputs).some(input => input.checked);
                if (!isNextAnswered) {
                    setTimeout(() => {
                        nextCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                }
            }
        });
    });

    // Form validation on submit
    form.addEventListener('submit', function(e) {
        const answered = document.querySelectorAll('.answer-input:checked').length;
        if (answered < totalQuestions) {
            e.preventDefault();
            submitWarning.classList.remove('hidden');
            
            // Scroll to first unanswered
            const cards = document.querySelectorAll('.question-card');
            for (let card of cards) {
                const cardInputs = card.querySelectorAll('.answer-input');
                const isAnswered = Array.from(cardInputs).some(input => input.checked);
                if (!isAnswered) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.classList.add('ring-2', 'ring-red-400');
                    setTimeout(() => card.classList.remove('ring-2', 'ring-red-400'), 2000);
                    break;
                }
            }
        }
    });

    // Initialize progress
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
