@extends('layouts.public')

@php
    $title = 'خدماتنا - الطريق المشرق للتدريب والتطوير';
    $description = 'تعرف على خدمات الطريق المشرق: اختبارات الميول المهنية، الإرشاد الوظيفي، برامج التدريب، والاستشارات المهنية';
    $keywords = 'خدمات التدريب، الإرشاد المهني، اختبارات الميول، برامج تدريبية، استشارات مهنية';
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
                <span class="text-brand-gold">خدماتنا</span>
            </nav>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="inline-block text-brand-gold font-bold tracking-widest mb-4 uppercase text-sm">
                <i class="fas fa-cogs ml-2"></i>
                حلول متكاملة
            </span>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-black mb-6">
                خدماتنا وبرامجنا
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                نقدم مجموعة متكاملة من الخدمات المصممة لمساعدتك في رحلتك المهنية
            </p>
        </div>
    </section>

    {{-- Main Services --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            
            {{-- Service 1: Assessments --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div class="relative order-2 lg:order-1">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-12 shadow-2xl text-center">
                        <i class="fas fa-clipboard-list text-8xl text-white/90 mb-6"></i>
                        <h3 class="text-2xl font-display font-bold text-white">اكتشف ميولك</h3>
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-brand-gold/20 rounded-2xl -z-10"></div>
                </div>
                <div class="order-1 lg:order-2">
                    <span class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        الخدمة الأولى
                    </span>
                    <h2 class="text-3xl font-display font-bold text-brand-dark mb-6">اختبارات الميول المهنية</h2>
                    <p class="text-brand-textMuted leading-relaxed mb-6">
                        نقدم مجموعة من الاختبارات المهنية المعتمدة التي تساعدك على اكتشاف ميولك وقدراتك وشخصيتك المهنية. اختباراتنا مبنية على أسس علمية موثوقة.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            اختبار هولاند للميول المهنية (RIASEC)
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            اختبار أنماط الشخصية (MBTI)
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            اختبار الذكاءات المتعددة
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            اختبار القيم المهنية
                        </li>
                    </ul>
                    <a href="{{ route('assessments.index') }}" class="inline-flex items-center gap-2 bg-brand-DEFAULT text-white px-8 py-4 rounded-lg font-bold hover:bg-brand-dark transition shadow-lg">
                        <span>استعرض الاختبارات</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>

            {{-- Service 2: Career Counseling --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div>
                    <span class="inline-block bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        الخدمة الثانية
                    </span>
                    <h2 class="text-3xl font-display font-bold text-brand-dark mb-6">الإرشاد المهني الفردي</h2>
                    <p class="text-brand-textMuted leading-relaxed mb-6">
                        جلسات إرشادية فردية مع مستشارين متخصصين لمساعدتك في فهم نتائج اختباراتك واتخاذ القرارات المهنية الصحيحة. نرافقك خطوة بخطوة في رحلتك المهنية.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            تحليل معمق لنتائج الاختبارات
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            وضع خطة مهنية واضحة
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            توجيه في اختيار التخصص أو الوظيفة
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            متابعة ودعم مستمر
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-8 py-4 rounded-lg font-bold hover:bg-brand-goldDeep transition shadow-lg">
                        <span>احجز جلستك الآن</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl p-12 shadow-2xl text-center">
                        <i class="fas fa-user-tie text-8xl text-white/90 mb-6"></i>
                        <h3 class="text-2xl font-display font-bold text-white">جلسات فردية</h3>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-brand-orange/20 rounded-2xl -z-10"></div>
                </div>
            </div>

            {{-- Service 3: Training Programs --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div class="relative order-2 lg:order-1">
                    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-12 shadow-2xl text-center">
                        <i class="fas fa-chalkboard-teacher text-8xl text-white/90 mb-6"></i>
                        <h3 class="text-2xl font-display font-bold text-white">تطوير المهارات</h3>
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-green-500/20 rounded-2xl -z-10"></div>
                </div>
                <div class="order-1 lg:order-2">
                    <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        الخدمة الثالثة
                    </span>
                    <h2 class="text-3xl font-display font-bold text-brand-dark mb-6">برامج التدريب والتطوير</h2>
                    <p class="text-brand-textMuted leading-relaxed mb-6">
                        برامج تدريبية متخصصة لتطوير المهارات الشخصية والمهنية. نقدم دورات وورش عمل تفاعلية تساعدك على النمو والتقدم في مسارك المهني.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            مهارات التواصل والعرض
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            القيادة وإدارة الفرق
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            إدارة الوقت والضغوط
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            مهارات المقابلات الوظيفية
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-8 py-4 rounded-lg font-bold hover:bg-green-700 transition shadow-lg">
                        <span>استفسر عن البرامج</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>

            {{-- Service 4: Corporate Consulting --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        الخدمة الرابعة
                    </span>
                    <h2 class="text-3xl font-display font-bold text-brand-dark mb-6">استشارات المؤسسات</h2>
                    <p class="text-brand-textMuted leading-relaxed mb-6">
                        نقدم خدمات استشارية متخصصة للمؤسسات والشركات في مجالات تطوير الموارد البشرية والتوظيف وبناء فرق العمل الفعالة.
                    </p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            تقييم الكفاءات والمهارات
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            بناء خطط التطوير المهني
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            استشارات التوظيف والاختيار
                        </li>
                        <li class="flex items-center gap-3 text-brand-dark">
                            <i class="fas fa-check-circle text-brand-gold"></i>
                            برامج تدريبية مخصصة
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-8 py-4 rounded-lg font-bold hover:bg-purple-700 transition shadow-lg">
                        <span>تواصل معنا</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-12 shadow-2xl text-center">
                        <i class="fas fa-building text-8xl text-white/90 mb-6"></i>
                        <h3 class="text-2xl font-display font-bold text-white">حلول مؤسسية</h3>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-purple-500/20 rounded-2xl -z-10"></div>
                </div>
            </div>

        </div>
    </section>

    {{-- Career Book Promo --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-br from-brand-gold to-brand-orange rounded-3xl p-12 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/10 rounded-full translate-x-1/2 translate-y-1/2"></div>
                
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div class="text-center lg:text-right">
                        <i class="fas fa-book-open text-6xl text-white/90 mb-6"></i>
                        <h2 class="text-3xl font-display font-bold text-white mb-4">الكتاب المهني</h2>
                        <p class="text-white/90 mb-6 text-lg">
                            دليلك الشامل للتطوير المهني والإرشاد الوظيفي. يحتوي على معلومات قيمة ونصائح عملية لبناء مسارك المهني.
                        </p>
                        <a href="{{ route('career-book.index') }}" class="inline-flex items-center gap-2 bg-white text-brand-dark px-8 py-4 rounded-lg font-bold hover:bg-brand-dark hover:text-white transition shadow-lg">
                            <span>اقرأ الكتاب</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="text-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-8 inline-block">
                            <i class="fas fa-book text-9xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Process Section --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-brand-gold font-bold text-sm tracking-wider uppercase">كيف نعمل</span>
                <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mt-3 mb-4">رحلتك معنا</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-brand-gold to-brand-orange mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-brand-gold rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg relative z-10">
                        <span class="text-3xl font-bold text-brand-dark">1</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mb-2">التسجيل</h3>
                    <p class="text-brand-textMuted text-sm">سجل معنا واختر الخدمة المناسبة لك</p>
                    <div class="hidden md:block absolute top-10 left-0 w-full h-0.5 bg-brand-border -z-10"></div>
                </div>
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-brand-DEFAULT rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg relative z-10">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mb-2">التقييم</h3>
                    <p class="text-brand-textMuted text-sm">أجرِ الاختبارات واحصل على نتائجك</p>
                    <div class="hidden md:block absolute top-10 left-0 w-full h-0.5 bg-brand-border -z-10"></div>
                </div>
                <div class="text-center relative">
                    <div class="w-20 h-20 bg-brand-orange rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg relative z-10">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mb-2">الإرشاد</h3>
                    <p class="text-brand-textMuted text-sm">احصل على جلسة إرشادية متخصصة</p>
                    <div class="hidden md:block absolute top-10 left-0 w-full h-0.5 bg-brand-border -z-10"></div>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-brand-navy rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl font-bold text-white">4</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mb-2">التطبيق</h3>
                    <p class="text-brand-textMuted text-sm">طبق ما تعلمته وانطلق بثقة</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-brand-DEFAULT via-brand-dark to-brand-navy text-white relative overflow-hidden">
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-3xl lg:text-4xl font-display font-bold mb-6 text-black">هل أنت مستعد للبدء؟</h2>
            <p class="text-gray-700 mb-10 max-w-2xl mx-auto text-lg">
                تواصل معنا اليوم واحصل على استشارة مجانية لمعرفة الخدمة الأنسب لك
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center justify-center gap-2 bg-brand-gold text-brand-dark px-10 py-4 font-bold rounded-lg hover:bg-white transition duration-300 shadow-xl">
                    <i class="fas fa-rocket"></i>
                    <span>ابدأ الآن</span>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-white/50 text-white px-10 py-4 font-bold rounded-lg hover:bg-white hover:text-brand-dark transition duration-300">
                    <i class="fas fa-phone"></i>
                    <span>تواصل معنا</span>
                </a>
            </div>
        </div>
    </section>

@endsection



