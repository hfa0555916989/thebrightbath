@extends('layouts.public')

@php
    $title = 'القيم - الطريق المشرق للتدريب والتطوير';
    $description = 'تعرف على القيم الأساسية التي توجه عمل الطريق المشرق للتدريب والتطوير';
    $keywords = 'قيمنا، التميز، الأمانة، الإبداع، الشراكة';
@endphp

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-brand-DEFAULT via-brand-dark to-brand-navy overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <pattern id="hero-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="white"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#hero-pattern)"/>
            </svg>
        </div>
        
        {{-- Breadcrumb --}}
        <div class="container mx-auto px-6 relative z-10 mb-8">
            <nav class="text-sm text-gray-300">
                <a href="{{ route('home') }}" class="hover:text-brand-gold transition">الرئيسية</a>
                <span class="mx-2">/</span>
                <span class="text-brand-gold">القيم</span>
            </nav>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">
                قيمنا
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                القيم التي نؤمن بها وتوجه كل ما نقوم به
            </p>
        </div>
    </section>

    {{-- Values Grid --}}
    @php $values = \App\Models\ContentItem::ofType('value')->forPage('global')->active()->ordered()->get(); @endphp
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($values as $value)
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition group">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center flex-shrink-0 transition"
                            style="background-color: {{ $value->color ?? '#F8C524' }}20"
                            x-data
                            @mouseenter="$el.style.backgroundColor='{{ $value->color ?? '#F8C524' }}'"
                            @mouseleave="$el.style.backgroundColor='{{ $value->color ?? '#F8C524' }}20'">
                            <i class="{{ $value->icon ?? 'fas fa-star' }} text-3xl transition"
                                style="color: {{ $value->color ?? '#F8C524' }}"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark mb-3">{{ $value->title }}</h3>
                            <p class="text-brand-textMuted leading-relaxed">{{ $value->body }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-br from-brand-DEFAULT via-brand-dark to-brand-navy text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-display font-bold mb-6">شاركنا قيمنا</h2>
            <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
                انضم إلى مجتمع الطريق المشرق واكتشف كيف يمكننا مساعدتك في رحلتك المهنية
            </p>
            <a href="{{ route('assessments.index') }}" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-8 py-4 rounded-lg font-bold hover:bg-white transition">
                <span>ابدأ رحلتك الآن</span>
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </section>

@endsection



