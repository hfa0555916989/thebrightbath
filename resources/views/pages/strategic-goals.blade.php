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
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                
                {{-- Goal 1 --}}
                <div class="flex gap-6 mb-12 items-start">
                    <div class="w-16 h-16 bg-brand-gold rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-2xl font-bold text-brand-dark">1</span>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg flex-1">
                        <h3 class="text-xl font-bold text-brand-dark mb-4">التوسع في تقديم الخدمات</h3>
                        <p class="text-brand-textMuted leading-relaxed mb-4">
                            توسيع نطاق خدماتنا لتشمل المزيد من الاختبارات والبرامج التدريبية المتخصصة، والوصول إلى شريحة أكبر من المستفيدين في جميع أنحاء المملكة.
                        </p>
                        <ul class="space-y-2 text-brand-textMuted">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                إضافة 10 اختبارات جديدة خلال 3 سنوات
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                الوصول إلى 50,000 مستفيد سنوياً
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Goal 2 --}}
                <div class="flex gap-6 mb-12 items-start">
                    <div class="w-16 h-16 bg-brand-DEFAULT rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg flex-1">
                        <h3 class="text-xl font-bold text-brand-dark mb-4">تعزيز الشراكات الاستراتيجية</h3>
                        <p class="text-brand-textMuted leading-relaxed mb-4">
                            بناء شراكات قوية مع الجامعات والمؤسسات التعليمية والشركات لتوفير خدمات الإرشاد المهني للطلاب والموظفين.
                        </p>
                        <ul class="space-y-2 text-brand-textMuted">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                شراكات مع 20 جامعة وجهة تعليمية
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                تعاون مع 50 شركة في القطاع الخاص
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Goal 3 --}}
                <div class="flex gap-6 mb-12 items-start">
                    <div class="w-16 h-16 bg-brand-orange rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg flex-1">
                        <h3 class="text-xl font-bold text-brand-dark mb-4">التميز في جودة الخدمات</h3>
                        <p class="text-brand-textMuted leading-relaxed mb-4">
                            الحصول على اعتمادات وشهادات جودة محلية ودولية، وتطوير فريق العمل بشكل مستمر لضمان أعلى مستويات الجودة.
                        </p>
                        <ul class="space-y-2 text-brand-textMuted">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                الحصول على شهادة ISO في جودة الخدمات
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                تحقيق نسبة رضا عملاء 95%
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Goal 4 --}}
                <div class="flex gap-6 mb-12 items-start">
                    <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-2xl font-bold text-white">4</span>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg flex-1">
                        <h3 class="text-xl font-bold text-brand-dark mb-4">الريادة في التقنية والابتكار</h3>
                        <p class="text-brand-textMuted leading-relaxed mb-4">
                            تطوير منصة رقمية متكاملة تقدم تجربة مستخدم متميزة، واستخدام أحدث التقنيات في تحليل البيانات والذكاء الاصطناعي.
                        </p>
                        <ul class="space-y-2 text-brand-textMuted">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                إطلاق تطبيق موبايل متكامل
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                دمج تقنيات الذكاء الاصطناعي في التحليل
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Goal 5 --}}
                <div class="flex gap-6 items-start">
                    <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-2xl font-bold text-white">5</span>
                    </div>
                    <div class="bg-white rounded-2xl p-8 shadow-lg flex-1">
                        <h3 class="text-xl font-bold text-brand-dark mb-4">المسؤولية المجتمعية</h3>
                        <p class="text-brand-textMuted leading-relaxed mb-4">
                            تقديم خدمات مجانية للفئات الأقل حظاً، والمساهمة في رفع الوعي بأهمية الإرشاد المهني في المجتمع.
                        </p>
                        <ul class="space-y-2 text-brand-textMuted">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                تقديم 1000 اختبار مجاني سنوياً
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-brand-gold"></i>
                                إقامة 20 فعالية توعوية سنوياً
                            </li>
                        </ul>
                    </div>
                </div>

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



