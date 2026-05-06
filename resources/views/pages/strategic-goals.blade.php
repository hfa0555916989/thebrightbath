@extends('layouts.public')

@php
    $title = 'الأهداف الاستراتيجية - الطريق المشرق للتدريب والتطوير';
    $description = 'تعرف على الأهداف الاستراتيجية للطريق المشرق للتدريب والتطوير';
    $keywords = 'الأهداف الاستراتيجية، التدريب، التطوير، الإرشاد المهني';
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
                <span class="text-brand-gold">الأهداف الاستراتيجية</span>
            </nav>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">
                الأهداف الاستراتيجية
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                خارطة طريقنا نحو تحقيق رؤيتنا ورسالتنا
            </p>
        </div>
    </section>

    {{-- Goals Section --}}
    @php try { $goals = \App\Models\ContentItem::ofType('goal')->forPage('global')->active()->ordered()->get(); } catch (\Exception $e) { $goals = collect([]); } @endphp
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                @foreach($goals as $i => $goal)
                <div class="flex gap-6 {{ !$loop->last ? 'mb-12' : '' }} items-start">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
                        style="background-color: {{ $goal->color ?? '#F8C524' }}">
                        <span class="text-2xl font-bold text-white">{{ $loop->iteration }}</span>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg flex-1">
                        <h3 class="text-xl font-bold text-brand-dark mb-4">{{ $goal->title }}</h3>
                        <p class="text-brand-textMuted leading-relaxed mb-4">{{ $goal->body }}</p>
                        @if($goal->getMeta('sub_goals'))
                        <ul class="space-y-2 text-brand-textMuted">
                            @foreach($goal->getMeta('sub_goals') as $subGoal)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                {{ $subGoal }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-display font-bold text-brand-dark mb-6">كن جزءاً من رحلتنا</h2>
            <p class="text-brand-textMuted mb-8 max-w-2xl mx-auto">
                انضم إلينا واستفد من خدماتنا المتميزة في الإرشاد المهني والتطوير الوظيفي
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center justify-center gap-2 bg-brand-gold text-brand-dark px-8 py-4 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                    <span>ابدأ الاختبار</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 border-2 border-brand-DEFAULT text-brand-DEFAULT px-8 py-4 rounded-lg font-bold hover:bg-brand-DEFAULT hover:text-white transition">
                    <span>تواصل معنا</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </section>

@endsection



