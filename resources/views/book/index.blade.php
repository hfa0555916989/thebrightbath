@extends('layouts.public')

@php
    $title = 'مفاتيح رحلتك المهنية - الطريق المشرق للتدريب والتطوير';
    $description = 'مفاتيح رحلتك المهنية - اكتشف طريقك وابدأ رحلتك مع الطريق المشرق';
    $keywords = 'مفاتيح رحلتك المهنية، التطوير المهني، الإرشاد الوظيفي، تطوير الذات';
@endphp

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 bg-gradient-to-br from-brand-gold via-brand-orange to-brand-orangeDark overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <pattern id="hero-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="white"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#hero-pattern)"/>
            </svg>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-right">
                    <span class="inline-block bg-white/20 backdrop-blur-sm text-white font-bold tracking-widest mb-4 uppercase text-sm px-4 py-2 rounded-full">
                        <i class="fas fa-book-open ml-2"></i>
                        محتوى حصري
                    </span>
                    <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">
                        مفاتيح رحلتك المهنية
                    </h1>
                    <p class="text-xl text-white/90 mb-8">
                        اكتشف طريقك وابدأ رحلتك
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#chapters" class="inline-flex items-center justify-center gap-2 bg-white text-brand-dark px-8 py-4 rounded-lg font-bold hover:bg-brand-dark hover:text-white transition shadow-xl">
                            <i class="fas fa-book-reader"></i>
                            <span>ابدأ القراءة</span>
                        </a>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-3xl p-8 inline-block">
                        <i class="fas fa-book text-[150px] text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Book Info --}}
    <section class="py-12 bg-white border-b border-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-brand-gold mb-2">{{ $chapters->count() ?? 10 }}</div>
                    <div class="text-brand-textMuted">فصل</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-brand-DEFAULT mb-2">+100</div>
                    <div class="text-brand-textMuted">صفحة</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-brand-orange mb-2">مجاني</div>
                    <div class="text-brand-textMuted">بعض الفصول</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600 mb-2">PDF</div>
                    <div class="text-brand-textMuted">قابل للتحميل</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Chapters List --}}
    <section id="chapters" class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-brand-gold font-bold text-sm tracking-wider uppercase">محتوى الكتاب</span>
                <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mt-3 mb-4">فصول الكتاب</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-brand-gold to-brand-orange mx-auto rounded-full"></div>
            </div>

            <div class="max-w-4xl mx-auto space-y-6">
                @forelse($chapters ?? [] as $index => $chapter)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                        <div class="flex items-center">
                            {{-- Chapter Number --}}
                            <div class="w-24 h-24 bg-gradient-to-br {{ $chapter->is_free ? 'from-brand-gold to-brand-orange' : 'from-gray-400 to-gray-500' }} flex items-center justify-center flex-shrink-0">
                                <span class="text-3xl font-bold text-white">{{ $index + 1 }}</span>
                            </div>
                            
                            {{-- Chapter Info --}}
                            <div class="flex-1 p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-brand-dark mb-2 group-hover:text-brand-DEFAULT transition">
                                            {{ $chapter->title }}
                                        </h3>
                                        @if($chapter->excerpt ?? false)
                                            <p class="text-brand-textMuted text-sm">{{ $chapter->excerpt }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mr-4">
                                        @if($chapter->is_free)
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                                <i class="fas fa-unlock ml-1"></i>
                                                مجاني
                                            </span>
                                            <a href="{{ route('career-book.show', $chapter->slug) }}" 
                                               class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-4 py-2 rounded-lg font-bold text-sm hover:bg-brand-goldDeep transition">
                                                اقرأ الآن
                                                <i class="fas fa-arrow-left"></i>
                                            </a>
                                        @else
                                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">
                                                <i class="fas fa-lock ml-1"></i>
                                                مقفل
                                            </span>
                                            @auth
                                                @if(auth()->user()->has_book_access)
                                                    <a href="{{ route('career-book.show', $chapter->slug) }}" 
                                                       class="inline-flex items-center gap-2 bg-brand-DEFAULT text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-brand-dark transition">
                                                        اقرأ الآن
                                                        <i class="fas fa-arrow-left"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('career-book.show', $chapter->slug) }}" 
                                                       class="inline-flex items-center gap-2 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-300 transition">
                                                        عرض
                                                        <i class="fas fa-arrow-left"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{ route('career-book.show', $chapter->slug) }}" 
                                                   class="inline-flex items-center gap-2 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-300 transition">
                                                    عرض
                                                    <i class="fas fa-arrow-left"></i>
                                                </a>
                                            @endauth
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Sample Chapters --}}
                    @for($i = 1; $i <= 8; $i++)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition group">
                            <div class="flex items-center">
                                <div class="w-24 h-24 bg-gradient-to-br {{ $i <= 2 ? 'from-brand-gold to-brand-orange' : 'from-gray-400 to-gray-500' }} flex items-center justify-center flex-shrink-0">
                                    <span class="text-3xl font-bold text-white">{{ $i }}</span>
                                </div>
                                <div class="flex-1 p-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-brand-dark mb-2 group-hover:text-brand-DEFAULT transition">
                                                @switch($i)
                                                    @case(1)
                                                        مقدمة في الإرشاد المهني
                                                        @break
                                                    @case(2)
                                                        اكتشاف الذات والميول
                                                        @break
                                                    @case(3)
                                                        أنماط الشخصية المهنية
                                                        @break
                                                    @case(4)
                                                        اختيار التخصص المناسب
                                                        @break
                                                    @case(5)
                                                        مهارات البحث عن عمل
                                                        @break
                                                    @case(6)
                                                        كتابة السيرة الذاتية
                                                        @break
                                                    @case(7)
                                                        التحضير للمقابلات
                                                        @break
                                                    @case(8)
                                                        بناء المسار المهني
                                                        @break
                                                @endswitch
                                            </h3>
                                            <p class="text-brand-textMuted text-sm">فصل تعليمي شامل مع أمثلة عملية</p>
                                        </div>
                                        <div class="flex items-center gap-3 mr-4">
                                            @if($i <= 2)
                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                                    <i class="fas fa-unlock ml-1"></i>
                                                    مجاني
                                                </span>
                                                <a href="#" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-4 py-2 rounded-lg font-bold text-sm hover:bg-brand-goldDeep transition">
                                                    اقرأ الآن
                                                    <i class="fas fa-arrow-left"></i>
                                                </a>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">
                                                    <i class="fas fa-lock ml-1"></i>
                                                    مقفل
                                                </span>
                                                <a href="#" class="inline-flex items-center gap-2 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-300 transition">
                                                    عرض
                                                    <i class="fas fa-arrow-left"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-br from-brand-DEFAULT to-brand-dark rounded-3xl p-12 text-center text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                
                <div class="relative z-10">
                    <i class="fas fa-unlock-alt text-5xl text-brand-gold mb-6"></i>
                    <h2 class="text-3xl font-display font-bold mb-4">احصل على الوصول الكامل</h2>
                    <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
                        افتح جميع فصول الكتاب واحصل على محتوى حصري ونصائح متقدمة لتطوير مسارك المهني
                    </p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-8 py-4 rounded-lg font-bold hover:bg-white transition">
                        <span>تواصل معنا للاشتراك</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection



