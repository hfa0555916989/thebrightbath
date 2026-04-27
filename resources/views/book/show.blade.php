@extends('layouts.public')

@php
    $title = ($chapter->title ?? 'الفصل') . ' - الكتاب المهني - الطريق المشرق';
    $description = 'قراءة فصل: ' . ($chapter->title ?? '') . ' من الكتاب المهني الخاص بالطريق المشرق للتدريب والتطوير';
    $keywords = 'الكتاب المهني، ' . ($chapter->title ?? '') . '، التطوير المهني';
@endphp

@section('hide_newsletter')
@endsection

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-12 bg-gradient-to-br from-brand-gold via-brand-orange to-brand-orangeDark">
        {{-- Breadcrumb --}}
        <div class="container mx-auto px-6 relative z-10 mb-8">
            <nav class="text-sm text-white/80">
                <a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a>
                <span class="mx-2">/</span>
                <a href="{{ route('career-book.index') }}" class="hover:text-white transition">الكتاب المهني</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $chapter->title ?? 'الفصل' }}</span>
            </nav>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <span class="text-3xl font-bold text-white">{{ $chapter->order ?? 1 }}</span>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">
                        {{ $chapter->title ?? 'عنوان الفصل' }}
                    </h1>
                    @if($chapter->is_free ?? true)
                        <span class="inline-flex items-center gap-1 bg-white/20 text-white px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-unlock"></i>
                            فصل مجاني
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Chapter Content --}}
    <section class="py-12 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                {{-- Sidebar --}}
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="font-bold text-brand-dark mb-4">فصول الكتاب</h3>
                        <ul class="space-y-2">
                            @forelse($chapters ?? [] as $ch)
                                <li>
                                    <a href="{{ route('career-book.show', $ch->slug) }}" 
                                       class="flex items-center gap-3 p-2 rounded-lg {{ ($ch->id ?? 0) == ($chapter->id ?? 0) ? 'bg-brand-gold/20 text-brand-dark' : 'text-brand-textMuted hover:bg-brand-light' }} transition">
                                        <span class="w-6 h-6 rounded-full {{ $ch->is_free ? 'bg-brand-gold' : 'bg-gray-300' }} flex items-center justify-center text-xs text-white font-bold">
                                            {{ $ch->order }}
                                        </span>
                                        <span class="text-sm truncate">{{ $ch->title }}</span>
                                        @if(!$ch->is_free)
                                            <i class="fas fa-lock text-xs text-gray-400 mr-auto"></i>
                                        @endif
                                    </a>
                                </li>
                            @empty
                                @for($i = 1; $i <= 8; $i++)
                                    <li>
                                        <a href="#" class="flex items-center gap-3 p-2 rounded-lg {{ $i == 1 ? 'bg-brand-gold/20 text-brand-dark' : 'text-brand-textMuted hover:bg-brand-light' }} transition">
                                            <span class="w-6 h-6 rounded-full {{ $i <= 2 ? 'bg-brand-gold' : 'bg-gray-300' }} flex items-center justify-center text-xs text-white font-bold">
                                                {{ $i }}
                                            </span>
                                            <span class="text-sm truncate">الفصل {{ $i }}</span>
                                            @if($i > 2)
                                                <i class="fas fa-lock text-xs text-gray-400 mr-auto"></i>
                                            @endif
                                        </a>
                                    </li>
                                @endfor
                            @endforelse
                        </ul>
                        
                        <div class="mt-6 pt-6 border-t border-brand-border">
                            <a href="{{ route('career-book.index') }}" class="flex items-center gap-2 text-brand-DEFAULT font-medium hover:text-brand-gold transition">
                                <i class="fas fa-arrow-right"></i>
                                العودة للفهرس
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="lg:col-span-3 order-1 lg:order-2">
                    <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12">
                        <article class="prose prose-lg max-w-none text-brand-text leading-relaxed">
                            {!! $chapter->content_html ?? '
                            <p class="text-xl text-brand-textMuted mb-8">
                                مرحباً بك في هذا الفصل من الكتاب المهني. ستتعلم في هذا الفصل أساسيات الإرشاد المهني وأهميته في حياتك العملية.
                            </p>
                            
                            <h2>مقدمة</h2>
                            <p>
                                الإرشاد المهني هو عملية منظمة تهدف إلى مساعدة الأفراد على فهم ذاتهم وقدراتهم وميولهم، ومن ثم اتخاذ قرارات مهنية سليمة تناسب شخصياتهم وتطلعاتهم.
                            </p>
                            
                            <h2>أهمية الإرشاد المهني</h2>
                            <p>
                                يكتسب الإرشاد المهني أهمية كبيرة في حياة الفرد لعدة أسباب:
                            </p>
                            <ul>
                                <li>يساعد على اكتشاف الميول والقدرات الحقيقية</li>
                                <li>يوجه الفرد نحو المسار المهني الأنسب</li>
                                <li>يقلل من احتمالية الفشل أو التغيير المتكرر للمهنة</li>
                                <li>يزيد من الرضا الوظيفي والإنتاجية</li>
                            </ul>
                            
                            <h2>خطوات الإرشاد المهني</h2>
                            <p>
                                تمر عملية الإرشاد المهني بعدة خطوات أساسية:
                            </p>
                            <ol>
                                <li><strong>التقييم الذاتي:</strong> معرفة نقاط القوة والضعف</li>
                                <li><strong>استكشاف الخيارات:</strong> البحث عن المجالات المتاحة</li>
                                <li><strong>اتخاذ القرار:</strong> اختيار المسار المناسب</li>
                                <li><strong>التخطيط والتنفيذ:</strong> وضع خطة عمل واضحة</li>
                            </ol>
                            
                            <blockquote>
                                "اختر العمل الذي تحبه ولن تضطر للعمل يوماً واحداً في حياتك" - كونفوشيوس
                            </blockquote>
                            
                            <h2>الخلاصة</h2>
                            <p>
                                الإرشاد المهني ليس رفاهية بل ضرورة في عالم اليوم المتسارع. استثمر في فهم نفسك وميولك لتبني مستقبلاً مهنياً ناجحاً ومرضياً.
                            </p>
                            ' !!}
                        </article>
                        
                        {{-- Navigation --}}
                        <div class="mt-12 pt-8 border-t border-brand-border flex justify-between items-center">
                            @if(isset($previousChapter))
                                <a href="{{ route('career-book.show', $previousChapter->slug) }}" 
                                   class="flex items-center gap-2 text-brand-DEFAULT hover:text-brand-gold transition">
                                    <i class="fas fa-arrow-right"></i>
                                    الفصل السابق
                                </a>
                            @else
                                <span></span>
                            @endif
                            
                            @if(isset($nextChapter))
                                <a href="{{ route('career-book.show', $nextChapter->slug) }}" 
                                   class="flex items-center gap-2 text-brand-DEFAULT hover:text-brand-gold transition">
                                    الفصل التالي
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            @else
                                <a href="{{ route('career-book.index') }}" 
                                   class="flex items-center gap-2 text-brand-DEFAULT hover:text-brand-gold transition">
                                    العودة للفهرس
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    .prose h2 {
        color: #1F3A63;
        font-weight: 700;
        font-size: 1.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose h3 {
        color: #1F3A63;
        font-weight: 600;
        font-size: 1.25rem;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.8;
    }
    .prose ul, .prose ol {
        margin-bottom: 1rem;
        padding-right: 1.5rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
    }
    .prose blockquote {
        border-right: 4px solid #F8C524;
        padding-right: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6B7280;
        background: #F5F7FA;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>
@endpush



