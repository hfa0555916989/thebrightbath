@extends('layouts.public')

@push('analytics')
<script>
    // تتبع الصفحة الرئيسية
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'الصفحة الرئيسية',
                'page_location': window.location.href,
                'custom_page_name': 'الصفحة الرئيسية - الطريق المشرق'
            });
        }
    });
</script>
@endpush

@push('schema')
{{-- FAQ Schema for Home Page --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "ما هي اختبارات الميول المهنية؟",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "اختبارات الميول المهنية هي أدوات علمية تساعدك على اكتشاف ميولك وقدراتك الطبيعية، وتوجيهك نحو المجالات المهنية الأنسب لشخصيتك. نقدم اختبارات هولاند، MBTI، والذكاءات المتعددة."
            }
        },
        {
            "@type": "Question",
            "name": "كيف يمكنني حجز استشارة مهنية؟",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "يمكنك حجز استشارة مهنية بسهولة من خلال صفحة الاستشارات الفورية، اختر المستشار المناسب، حدد الموعد والوقت، ثم أكمل عملية الحجز والدفع."
            }
        },
        {
            "@type": "Question",
            "name": "هل الاختبارات مجانية؟",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "نعم، نقدم بعض الاختبارات مجاناً للمستخدمين المسجلين. كما نوفر اختبارات متقدمة مع تقارير تفصيلية لمساعدتك في اتخاذ قرارات مهنية أفضل."
            }
        },
        {
            "@type": "Question",
            "name": "من هم المستشارون في الموقع؟",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "مستشارونا هم خبراء معتمدون في الإرشاد المهني والتطوير الذاتي، لديهم خبرة واسعة في مساعدة الأفراد على اكتشاف مساراتهم المهنية."
            }
        }
    ]
}
</script>

{{-- Service Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Career Counseling",
    "name": "خدمات الإرشاد المهني",
    "description": "خدمات متكاملة للإرشاد المهني تشمل اختبارات الميول واستشارات فردية مع خبراء متخصصين",
    "provider": {
        "@type": "Organization",
        "name": "الطريق المشرق للتدريب والتطوير",
        "url": "https://thebrightbath.com"
    },
    "areaServed": {
        "@type": "Country",
        "name": "المملكة العربية السعودية"
    },
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "خدماتنا",
        "itemListElement": [
            {
                "@type": "Offer",
                "itemOffered": {
                    "@type": "Service",
                    "name": "اختبارات الميول المهنية",
                    "description": "اختبارات علمية لاكتشاف ميولك وقدراتك"
                }
            },
            {
                "@type": "Offer",
                "itemOffered": {
                    "@type": "Service",
                    "name": "استشارات مهنية فورية",
                    "description": "جلسات استشارية مباشرة مع خبراء الإرشاد المهني"
                }
            }
        ]
    }
}
</script>
@endpush

@section('content')

    {{-- Hero Section with Career Counseling Image --}}
    <header id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        {{-- Background Image - Career Counseling/Mentoring --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="جلسة إرشاد مهني" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-l from-brand-dark/95 via-brand-dark/85 to-brand-dark/70"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Content --}}
                <div class="text-white text-center lg:text-right">
                    <span class="inline-block bg-brand-gold/20 backdrop-blur-sm text-brand-gold font-bold tracking-widest mb-6 uppercase text-sm px-6 py-2 rounded-full border border-brand-gold/30">
                        الطريق المشرق للتدريب والتطوير
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold mb-6 leading-tight">
                        اكتشف <span class="text-brand-gold">ميولك المهنية</span>
                        <br>
                        وابنِ مستقبلك بثقة
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-10 leading-relaxed max-w-xl mx-auto lg:mx-0 lg:mr-0">
                        نقدم لك منظومة متكاملة من خدمات الإرشاد المهني، تشمل الجلسات الفردية، ورخص الإرشاد المهني، إضافة إلى الدورات وورش العمل، ومكتبة مهنية داعمة تساعدك في اختيار المسار المهني الأنسب لك
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('assessments.index') }}" class="inline-flex items-center justify-center gap-2 bg-brand-gold text-brand-dark px-10 py-4 font-bold rounded-xl hover:bg-white transition duration-300 shadow-xl text-lg">
                            <i class="fas fa-clipboard-list"></i>
                            <span>ابدأ الاختبار الآن</span>
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-10 py-4 font-bold rounded-xl hover:bg-white hover:text-brand-dark transition duration-300">
                            <i class="fas fa-user-plus"></i>
                            <span>سجل مجاناً</span>
                        </a>
                    </div>
                </div>
                
                {{-- Stats Cards --}}
                <div class="hidden lg:grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <div class="text-4xl font-bold text-brand-gold mb-2">+5000</div>
                        <div class="text-gray-300">مستفيد من خدماتنا</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mt-8">
                        <div class="text-4xl font-bold text-brand-gold mb-2">5</div>
                        <div class="text-gray-300">اختبارات متخصصة</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <div class="text-4xl font-bold text-brand-gold mb-2">+10</div>
                        <div class="text-gray-300">سنوات من الخبرة</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 mt-8">
                        <div class="text-4xl font-bold text-brand-gold mb-2">98%</div>
                        <div class="text-gray-300">نسبة رضا العملاء</div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/50 animate-bounce">
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </header>

    {{-- Features Strip --}}
    <section class="py-16 bg-white relative z-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-gold group-hover:scale-110 transition duration-300">
                        <i class="fas fa-certificate text-2xl text-brand-gold group-hover:text-white"></i>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-1">اختبارات معتمدة</h3>
                    <p class="text-sm text-brand-textMuted">علمية وموثوقة دولياً</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-brand-DEFAULT/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-DEFAULT group-hover:scale-110 transition duration-300">
                        <i class="fas fa-user-shield text-2xl text-brand-DEFAULT group-hover:text-white"></i>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-1">سرية تامة</h3>
                    <p class="text-sm text-brand-textMuted">بياناتك محمية ومشفرة</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-brand-orange/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-brand-orange group-hover:scale-110 transition duration-300">
                        <i class="fas fa-bolt text-2xl text-brand-orange group-hover:text-white"></i>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-1">نتائج فورية</h3>
                    <p class="text-sm text-brand-textMuted">تقرير PDF مفصل</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-green-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500 group-hover:scale-110 transition duration-300">
                        <i class="fas fa-headset text-2xl text-green-500 group-hover:text-white"></i>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-1">دعم متواصل</h3>
                    <p class="text-sm text-brand-textMuted">فريق متخصص لمساعدتك</p>
                </div>
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Image Side --}}
                <div class="relative">
                    <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl bg-white flex items-center justify-center h-[500px]">
                        <img src="{{ asset('images/bright-path-logo.png') }}" 
                             alt="الطريق المشرق - Bright Path" 
                             class="w-auto h-[400px] object-contain">
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-48 h-48 bg-brand-gold/20 rounded-2xl -z-10"></div>
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-brand-DEFAULT/20 rounded-full -z-10"></div>
                    
                    {{-- Floating Card --}}
                    <div class="absolute -bottom-4 -right-4 bg-white rounded-xl shadow-xl p-4 z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-brand-gold rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-white"></i>
                            </div>
                            <div>
                                <div class="font-bold text-brand-dark">+10 سنوات</div>
                                <div class="text-sm text-brand-textMuted">من الخبرة</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Content Side --}}
                <div>
                    <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                        من نحن
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-6">
                        الطريق المشرق
                        <span class="text-brand-gold">للتدريب والتطوير</span>
                    </h2>
                    <p class="text-brand-textMuted leading-relaxed mb-6 text-lg">
                        نحن رواد في التدريب والتطوير المهني والإرشاد الوظيفي في المملكة العربية السعودية. نؤمن بأن كل فرد يمتلك إمكانيات فريدة تستحق الاكتشاف والتنمية.
                    </p>
                    <p class="text-brand-textMuted leading-relaxed mb-8">
                        نقدم اختبارات علمية معتمدة وبرامج تدريبية متخصصة وجلسات إرشادية فردية لمساعدتك على اكتشاف ميولك المهنية واتخاذ القرارات الصحيحة.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center">
                                <i class="fas fa-check text-brand-gold"></i>
                            </div>
                            <span class="text-brand-dark font-medium">اختبارات معتمدة</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center">
                                <i class="fas fa-check text-brand-gold"></i>
                            </div>
                            <span class="text-brand-dark font-medium">خبراء متخصصون</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center">
                                <i class="fas fa-check text-brand-gold"></i>
                            </div>
                            <span class="text-brand-dark font-medium">تقارير مفصلة</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center">
                                <i class="fas fa-check text-brand-gold"></i>
                            </div>
                            <span class="text-brand-dark font-medium">دعم متواصل</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('about') }}" class="inline-flex items-center gap-2 bg-brand-DEFAULT text-white px-8 py-4 rounded-xl font-bold hover:bg-brand-dark transition shadow-lg">
                        <span>اعرف المزيد عنا</span>
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section id="services" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                    خدماتنا
                </span>
                <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-4">ماذا نقدم لك؟</h2>
                <p class="text-brand-textMuted max-w-2xl mx-auto">نوفر لك مجموعة متكاملة من الخدمات التي تساعدك في رحلتك المهنية</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Service 1: Assessments --}}
                <div class="group relative rounded-2xl overflow-hidden shadow-xl h-[450px]">
                    <img src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="اختبارات الميول المهنية" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="w-14 h-14 bg-brand-gold rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-clipboard-list text-2xl text-brand-dark"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">اختبارات الميول المهنية</h3>
                        <p class="text-gray-300 mb-4">اختبارات علمية معتمدة لاكتشاف ميولك وقدراتك الحقيقية</p>
                        <a href="{{ route('assessments.index') }}" class="inline-flex items-center gap-2 text-brand-gold font-bold group-hover:gap-3 transition-all">
                            ابدأ الآن
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                
                {{-- Service 2: Counseling --}}
                <div class="group relative rounded-2xl overflow-hidden shadow-xl h-[450px]">
                    <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="الإرشاد المهني" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="w-14 h-14 bg-brand-gold rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-user-tie text-2xl text-brand-dark"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">الإرشاد المهني الفردي</h3>
                        <p class="text-gray-300 mb-4">جلسات إرشادية مع مستشارين متخصصين لرسم مسارك المهني</p>
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-brand-gold font-bold group-hover:gap-3 transition-all">
                            احجز جلستك
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>

                {{-- Service 3: Training --}}
                <div class="group relative rounded-2xl overflow-hidden shadow-xl h-[450px]">
                    <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="برامج التدريب" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="w-14 h-14 bg-brand-gold rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-chalkboard-teacher text-2xl text-brand-dark"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">برامج التدريب والتطوير</h3>
                        <p class="text-gray-300 mb-4">دورات وورش عمل متخصصة لتطوير مهاراتك المهنية</p>
                        <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-brand-gold font-bold group-hover:gap-3 transition-all">
                            استعرض البرامج
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Assessments Section --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 gap-6">
                <div>
                    <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                        اختباراتنا
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-2">اختبارات الميول المهنية</h2>
                    <p class="text-brand-textMuted">اكتشف نفسك من خلال اختباراتنا العلمية المعتمدة</p>
                </div>
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center gap-2 bg-brand-DEFAULT text-white px-6 py-3 rounded-xl font-bold hover:bg-brand-dark transition">
                    عرض جميع الاختبارات
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Assessment Card 1 --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="اختبار هولاند" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute top-3 right-3 bg-brand-gold text-brand-dark text-xs px-3 py-1 rounded-full font-bold">
                            الأكثر شعبية
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-brand-dark mb-2">اختبار هولاند</h3>
                        <p class="text-brand-textMuted text-sm mb-4">اكتشف ميولك المهنية وفق نظرية RIASEC</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-brand-textMuted">
                                <i class="fas fa-clock ml-1"></i> 15-20 دقيقة
                            </span>
                            <a href="{{ route('assessments.show', 'holland') }}" class="text-brand-gold font-bold text-sm hover:text-brand-orange">
                                ابدأ <i class="fas fa-arrow-left mr-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Assessment Card 2 --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="اختبار MBTI" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-brand-dark mb-2">اختبار الشخصية MBTI</h3>
                        <p class="text-brand-textMuted text-sm mb-4">تعرف على نمط شخصيتك من 16 نمط</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-brand-textMuted">
                                <i class="fas fa-clock ml-1"></i> 20-25 دقيقة
                            </span>
                            <a href="{{ route('assessments.show', 'mbti') }}" class="text-brand-gold font-bold text-sm hover:text-brand-orange">
                                ابدأ <i class="fas fa-arrow-left mr-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Assessment Card 3 --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="الذكاءات المتعددة" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-brand-dark mb-2">الذكاءات المتعددة</h3>
                        <p class="text-brand-textMuted text-sm mb-4">اكتشف أنواع ذكائك وكيف تستثمرها</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-brand-textMuted">
                                <i class="fas fa-clock ml-1"></i> 15-20 دقيقة
                            </span>
                            <a href="{{ route('assessments.show', 'mi') }}" class="text-brand-gold font-bold text-sm hover:text-brand-orange">
                                ابدأ <i class="fas fa-arrow-left mr-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Assessment Card 4 --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1560472355-536de3962603?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="القيم المهنية" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute top-3 right-3 bg-brand-DEFAULT text-white text-xs px-3 py-1 rounded-full font-bold">
                            جديد
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-brand-dark mb-2">القيم المهنية</h3>
                        <p class="text-brand-textMuted text-sm mb-4">تعرف على قيمك في بيئة العمل</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-brand-textMuted">
                                <i class="fas fa-clock ml-1"></i> 10-15 دقيقة
                            </span>
                            <a href="{{ route('assessments.show', 'work-values') }}" class="text-brand-gold font-bold text-sm hover:text-brand-orange">
                                ابدأ <i class="fas fa-arrow-left mr-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Analysis Models Section --}}
    @php
        $analysisModels = \App\Models\AnalysisModel::active()->featured()->ordered()->take(4)->get();
    @endphp
    @if($analysisModels->count() > 0)
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 gap-6">
                <div>
                    <span class="inline-block bg-brand-DEFAULT/10 text-brand-DEFAULT px-4 py-2 rounded-full text-sm font-bold mb-4">
                        أدوات التحليل المهني
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-2">نماذج التحليل الوظيفي والكفاءات</h2>
                    <p class="text-brand-textMuted">نماذج تفاعلية تساعدك في تقييم وتطوير مهاراتك المهنية</p>
                </div>
                <a href="{{ route('analysis-models.index') }}" class="inline-flex items-center gap-2 bg-brand-DEFAULT text-white px-6 py-3 rounded-xl font-bold hover:bg-brand-dark transition">
                    عرض جميع النماذج
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($analysisModels as $model)
                <a href="{{ route('analysis-models.show', $model) }}" 
                   class="group bg-brand-bg rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="h-2" style="background-color: {{ $model->color }};"></div>
                    <div class="p-6">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110" 
                             style="background-color: {{ $model->color }}20;">
                            <i class="fas {{ $model->icon }} text-2xl" style="color: {{ $model->color }};"></i>
                        </div>
                        <h3 class="text-lg font-bold text-brand-dark mb-2 group-hover:text-brand-DEFAULT transition">
                            {{ $model->name }}
                        </h3>
                        @if($model->description)
                        <p class="text-brand-textMuted text-sm mb-4 line-clamp-2">{{ Str::limit($model->description, 80) }}</p>
                        @endif
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <span><i class="fas fa-eye ml-1"></i>{{ number_format($model->views_count) }}</span>
                                <span><i class="fas fa-download ml-1"></i>{{ number_format($model->downloads_count) }}</span>
                            </div>
                            <span class="font-bold text-sm group-hover:gap-2 flex items-center gap-1 transition-all" style="color: {{ $model->color }};">
                                عرض <i class="fas fa-arrow-left text-xs"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- How It Works Section --}}
    <section class="py-20 bg-white relative overflow-hidden">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                    كيف نعمل
                </span>
                <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-4">رحلتك معنا</h2>
                <p class="text-brand-textMuted max-w-2xl mx-auto">أربع خطوات بسيطة لاكتشاف مسارك المهني</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center relative">
                    <div class="w-24 h-24 bg-brand-gold rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl relative z-10">
                        <i class="fas fa-hand-pointer text-3xl text-white"></i>
                    </div>
                    <span class="text-5xl font-bold text-brand-border">01</span>
                    <h3 class="text-lg font-bold text-brand-dark mt-2 mb-2">اختر الاختبار</h3>
                    <p class="text-brand-textMuted text-sm">اختر الاختبار المناسب من قائمتنا</p>
                </div>
                <div class="text-center relative">
                    <div class="w-24 h-24 bg-brand-DEFAULT rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl relative z-10">
                        <i class="fas fa-edit text-3xl text-white"></i>
                    </div>
                    <span class="text-5xl font-bold text-brand-border">02</span>
                    <h3 class="text-lg font-bold text-brand-dark mt-2 mb-2">أجب على الأسئلة</h3>
                    <p class="text-brand-textMuted text-sm">أجب بصدق للحصول على نتائج دقيقة</p>
                </div>
                <div class="text-center relative">
                    <div class="w-24 h-24 bg-brand-orange rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl relative z-10">
                        <i class="fas fa-chart-pie text-3xl text-white"></i>
                    </div>
                    <span class="text-5xl font-bold text-brand-border">03</span>
                    <h3 class="text-lg font-bold text-brand-dark mt-2 mb-2">احصل على النتائج</h3>
                    <p class="text-brand-textMuted text-sm">تحليل فوري ومفصل لميولك</p>
                </div>
                <div class="text-center">
                    <div class="w-24 h-24 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                        <i class="fas fa-rocket text-3xl text-white"></i>
                    </div>
                    <span class="text-5xl font-bold text-brand-border">04</span>
                    <h3 class="text-lg font-bold text-brand-dark mt-2 mb-2">ابدأ رحلتك</h3>
                    <p class="text-brand-textMuted text-sm">اتخذ قراراتك المهنية بثقة</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                    آراء عملائنا
                </span>
                <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-4">ماذا يقولون عنا؟</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Testimonial 1 --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg relative">
                    <div class="absolute -top-4 right-8 text-6xl text-brand-gold/20 font-serif">"</div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-brand-DEFAULT flex items-center justify-center text-white text-2xl font-bold">
                            م
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark">محمد العتيبي</h4>
                            <p class="text-sm text-brand-textMuted">مهندس - الرياض</p>
                        </div>
                    </div>
                    <p class="text-brand-textMuted leading-relaxed">
                        "اختبار هولاند ساعدني كثيراً في فهم ميولي المهنية واتخاذ قرار تغيير مساري الوظيفي. الآن أعمل في مجال يناسبني تماماً."
                    </p>
                    <div class="flex gap-1 mt-4 text-brand-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                {{-- Testimonial 2 --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg relative">
                    <div class="absolute -top-4 right-8 text-6xl text-brand-gold/20 font-serif">"</div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-brand-gold flex items-center justify-center text-brand-dark text-2xl font-bold">
                            ن
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark">نورة الشمري</h4>
                            <p class="text-sm text-brand-textMuted">خريجة - جدة</p>
                        </div>
                    </div>
                    <p class="text-brand-textMuted leading-relaxed">
                        "الكتاب المهني كان مرجعاً قيماً لي في رحلة البحث عن عمل. المعلومات مفيدة جداً والتقارير واضحة ومفصلة."
                    </p>
                    <div class="flex gap-1 mt-4 text-brand-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                {{-- Testimonial 3 --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg relative">
                    <div class="absolute -top-4 right-8 text-6xl text-brand-gold/20 font-serif">"</div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-brand-orange flex items-center justify-center text-white text-2xl font-bold">
                            ع
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark">عبدالرحمن القحطاني</h4>
                            <p class="text-sm text-brand-textMuted">طالب جامعي - الدمام</p>
                        </div>
                    </div>
                    <p class="text-brand-textMuted leading-relaxed">
                        "جلسة الإرشاد المهني كانت نقطة تحول في حياتي. المرشد كان محترفاً وساعدني على وضع خطة واضحة لمستقبلي."
                    </p>
                    <div class="flex gap-1 mt-4 text-brand-gold">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="مكتب احترافي" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-brand-dark/90"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-3xl lg:text-5xl font-display font-bold text-white mb-6">
                هل أنت مستعد لاكتشاف
                <span class="text-brand-gold">ميولك المهنية؟</span>
            </h2>
            <p class="text-gray-300 mb-10 max-w-2xl mx-auto text-lg">
                ابدأ رحلتك الآن مع اختباراتنا المعتمدة واحصل على تقرير مفصل يساعدك في اتخاذ القرار الصحيح
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center justify-center gap-2 bg-brand-gold text-brand-dark px-10 py-4 font-bold rounded-xl hover:bg-white transition duration-300 shadow-xl text-lg">
                    <i class="fas fa-rocket"></i>
                    <span>ابدأ الاختبار مجاناً</span>
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-white text-white px-10 py-4 font-bold rounded-xl hover:bg-white hover:text-brand-dark transition duration-300">
                    <i class="fas fa-user-plus"></i>
                    <span>سجل حساب جديد</span>
                </a>
            </div>
        </div>
    </section>

@endsection

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "الطريق المشرق للتدريب والتطوير",
    "alternateName": "Bright Path",
    "url": "https://thebrightbath.com",
    "logo": "https://thebrightbath.com/images/bright-path-logo.png",
    "description": "رواد في التدريب والتطوير المهني والإرشاد الوظيفي",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "SA"
    }
}
</script>
@endpush
