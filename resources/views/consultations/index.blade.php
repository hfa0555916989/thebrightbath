@extends('layouts.public')

@section('title', 'استشارات فورية')

@push('analytics')
<script>
    // تتبع صفحة الاستشارات
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'صفحة الاستشارات',
                'page_location': window.location.href,
                'custom_page_name': 'صفحة الاستشارات - عرض المستشارين المتاحين'
            });
        }
    });
</script>
@endpush

@section('content')
{{-- Hero --}}
<section class="relative py-20 bg-gradient-to-bl from-brand-dark via-brand-DEFAULT to-brand-dark overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-64 h-64 bg-brand-gold rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-20 w-80 h-80 bg-brand-orange rounded-full blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">استشارات فورية</h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto">
            احجز جلسة استشارية مع نخبة من المستشارين المتخصصين في الإرشاد المهني
        </p>
    </div>
</section>

{{-- How it Works --}}
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-brand-gold">1</span>
                </div>
                <h3 class="font-bold text-brand-dark mb-2">اختر المستشار</h3>
                <p class="text-sm text-gray-600">اختر المستشار المناسب حسب تخصصك</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-brand-gold">2</span>
                </div>
                <h3 class="font-bold text-brand-dark mb-2">حدد الموعد</h3>
                <p class="text-sm text-gray-600">اختر الوقت المناسب من الجدول</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-brand-gold">3</span>
                </div>
                <h3 class="font-bold text-brand-dark mb-2">أكمل الدفع</h3>
                <p class="text-sm text-gray-600">ادفع بشكل آمن عبر البطاقة</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-brand-gold">4</span>
                </div>
                <h3 class="font-bold text-brand-dark mb-2">انضم للجلسة</h3>
                <p class="text-sm text-gray-600">استلم رابط الاجتماع عبر البريد</p>
            </div>
        </div>
    </div>
</section>

{{-- Consultants Grid --}}
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-brand-dark mb-4">المستشارون المتاحون</h2>
            <p class="text-gray-600">اختر المستشار الذي يناسب احتياجاتك</p>
        </div>

        @if($consultants->isEmpty())
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-user-tie text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2">لا يوجد مستشارون متاحون حالياً</h3>
            <p class="text-gray-500">يرجى العودة لاحقاً</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($consultants as $consultant)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                {{-- Featured Badge --}}
                @if($consultant->is_featured)
                <div class="bg-brand-gold text-brand-dark text-center py-2 text-sm font-bold">
                    <i class="fas fa-star ml-1"></i> مستشار مميز
                </div>
                @endif

                <div class="p-6">
                    {{-- Photo & Info --}}
                    <div class="flex items-center gap-4 mb-6">
                        @if($consultant->photo)
                            <img src="{{ asset('storage/' . $consultant->photo) }}" alt="{{ $consultant->user->name }}" 
                                 class="w-20 h-20 rounded-full object-cover border-4 border-brand-gold/20">
                        @else
                            <div class="w-20 h-20 rounded-full bg-brand-gold/10 flex items-center justify-center border-4 border-brand-gold/20">
                                <i class="fas fa-user text-3xl text-brand-gold"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark">{{ $consultant->user->name }}</h3>
                            <p class="text-brand-gold font-medium">{{ $consultant->specialization_ar }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex items-center text-yellow-400">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star text-sm {{ $i < floor($consultant->rating) ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-500">({{ $consultant->reviews_count }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    @if($consultant->bio_ar)
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $consultant->bio_ar }}</p>
                    @endif

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <i class="fas fa-briefcase text-brand-gold mb-1"></i>
                            <p class="text-sm text-gray-600">{{ $consultant->experience_years }} سنوات خبرة</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <i class="fas fa-users text-brand-gold mb-1"></i>
                            <p class="text-sm text-gray-600">{{ $consultant->total_sessions }} جلسة</p>
                        </div>
                    </div>

                    {{-- Prices --}}
                    <div class="flex justify-between items-center py-4 border-t border-b border-gray-100 mb-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-brand-dark">{{ number_format($consultant->price_per_30_min) }}</p>
                            <p class="text-xs text-gray-500">ر.س / 30 دقيقة</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-brand-DEFAULT">{{ number_format($consultant->price_per_60_min) }}</p>
                            <p class="text-xs text-gray-500">ر.س / ساعة</p>
                        </div>
                    </div>

                    {{-- Book Button --}}
                    @auth
                    <a href="{{ route('consultations.show', $consultant) }}" 
                       class="block w-full bg-brand-gold text-brand-dark text-center py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                        <i class="fas fa-calendar-check ml-2"></i>
                        احجز موعد
                    </a>
                    @else
                    <a href="{{ route('login') }}?redirect={{ route('consultations.show', $consultant) }}" 
                       class="block w-full bg-brand-DEFAULT text-white text-center py-4 rounded-xl font-bold hover:bg-brand-dark transition">
                        <i class="fas fa-sign-in-alt ml-2"></i>
                        سجل دخول للحجز
                    </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $consultants->links() }}
        </div>
        @endif
    </div>
</section>
@endsection




