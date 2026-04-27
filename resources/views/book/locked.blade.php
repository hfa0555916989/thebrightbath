@extends('layouts.public')

@php
    $title = 'فصل مقفل - الكتاب المهني - الطريق المشرق';
    $description = 'هذا الفصل مقفل - اشترك للحصول على الوصول الكامل للكتاب المهني';
@endphp

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-12 bg-gradient-to-br from-gray-600 to-gray-800">
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <i class="fas fa-lock text-3xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">
                        {{ $chapter->title ?? 'فصل مقفل' }}
                    </h1>
                    <span class="inline-flex items-center gap-1 bg-white/20 text-white px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-lock"></i>
                        يتطلب اشتراك
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Locked Content --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center">
                <div class="bg-white rounded-3xl shadow-xl p-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-lock text-4xl text-gray-400"></i>
                    </div>
                    
                    <h2 class="text-2xl font-display font-bold text-brand-dark mb-4">هذا الفصل مقفل</h2>
                    <p class="text-brand-textMuted mb-8">
                        للوصول إلى هذا المحتوى والاستفادة من جميع فصول الكتاب المهني، يرجى تسجيل الدخول أو التواصل معنا للاشتراك.
                    </p>
                    
                    <div class="space-y-4">
                        @guest
                            <a href="{{ route('login') }}" 
                               class="block w-full bg-brand-DEFAULT text-white py-4 rounded-xl font-bold hover:bg-brand-dark transition">
                                <i class="fas fa-sign-in-alt ml-2"></i>
                                تسجيل الدخول
                            </a>
                        @endguest
                        
                        <a href="{{ route('contact') }}" 
                           class="block w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                            <i class="fas fa-envelope ml-2"></i>
                            تواصل معنا للاشتراك
                        </a>
                        
                        <a href="{{ route('career-book.index') }}" 
                           class="block w-full border-2 border-brand-border text-brand-textMuted py-4 rounded-xl font-bold hover:border-brand-DEFAULT hover:text-brand-DEFAULT transition">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة لفهرس الكتاب
                        </a>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-brand-border">
                        <p class="text-sm text-brand-textMuted">
                            <i class="fas fa-info-circle ml-1 text-brand-gold"></i>
                            يمكنك قراءة الفصول المجانية بدون اشتراك
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection



