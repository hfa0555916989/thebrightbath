@extends('layouts.public')

@push('analytics')
<script>
    // تتبع صفحة الاختبارات
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'صفحة الاختبارات',
                'page_location': window.location.href,
                'custom_page_name': 'صفحة الاختبارات - عرض جميع الاختبارات المتاحة'
            });
            
            // حدث لتتبع عرض المحتوى
            gtag('event', 'view_item_list', {
                'item_list_name': 'اختبارات الميول المهنية',
                'items': [
                    { 'item_name': 'اختبار هولاند RIASEC', 'item_category': 'اختبارات' },
                    { 'item_name': 'اختبار الشخصية MBTI', 'item_category': 'اختبارات' },
                    { 'item_name': 'الذكاءات المتعددة', 'item_category': 'اختبارات' },
                    { 'item_name': 'القيم المهنية', 'item_category': 'اختبارات' },
                    { 'item_name': 'الملاءمة المهنية', 'item_category': 'اختبارات' }
                ]
            });
        }
    });
</script>
@endpush

@section('content')

    {{-- Login Required Banner for Guests --}}
    @guest
    <div class="bg-gradient-to-l from-brand-gold to-brand-goldDeep py-4 sticky top-[72px] z-30">
        <div class="container mx-auto px-6">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-lock text-brand-dark text-xl"></i>
                    <span class="text-brand-dark font-bold">للوصول إلى الاختبارات، يجب تسجيل الدخول أولاً</span>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-brand-dark text-white px-5 py-2 rounded-lg font-medium hover:bg-brand-navy transition">
                        <i class="fas fa-user-plus"></i>
                        <span>تسجيل جديد</span>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white text-brand-dark px-5 py-2 rounded-lg font-medium hover:bg-gray-100 transition">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>تسجيل الدخول</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endguest

    {{-- Hero Section --}}
    <header class="relative py-32 bg-brand-dark overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="اختبارات الميول المهنية" 
                 class="w-full h-full object-cover opacity-30">
        </div>
        <div class="absolute inset-0 bg-gradient-to-l from-brand-dark via-brand-dark/90 to-brand-dark/70"></div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="inline-block bg-brand-gold/20 backdrop-blur-sm text-brand-gold px-6 py-2 rounded-full text-sm font-bold mb-6">
                اكتشف ميولك
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-white mb-6">
                اختبارات <span class="text-brand-gold">الميول المهنية</span>
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                اختبارات علمية معتمدة تساعدك على اكتشاف شخصيتك المهنية وتحديد المسار الوظيفي المناسب لك
            </p>
        </div>
    </header>

    {{-- Introduction Section --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <h2 class="text-3xl font-display font-bold text-brand-dark mb-6">
                        لماذا اختبارات الميول المهنية؟
                    </h2>
                    <p class="text-brand-textMuted leading-relaxed mb-6">
                        تساعدك اختباراتنا المعتمدة دولياً على فهم ميولك وقدراتك الحقيقية، مما يمكنك من اتخاذ قرارات مهنية صحيحة تناسب شخصيتك وتطلعاتك.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-brand-gold/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-brand-gold"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark">اختبارات موثوقة</h3>
                                <p class="text-sm text-brand-textMuted">مبنية على نظريات علمية معتمدة عالمياً</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-brand-gold/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-brand-gold"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark">نتائج فورية</h3>
                                <p class="text-sm text-brand-textMuted">تحصل على تقرير مفصل بعد إكمال الاختبار مباشرة</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-brand-gold/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-brand-gold"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-brand-dark">تقارير مفصلة</h3>
                                <p class="text-sm text-brand-textMuted">تحليل شامل لميولك مع توصيات مهنية</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 lg:order-2 relative">
                    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="شخص يجري اختبار الميول" 
                         class="w-full h-[400px] object-cover rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-6 -right-6 bg-brand-gold rounded-2xl p-6 shadow-xl">
                        <div class="text-4xl font-bold text-brand-dark">+5000</div>
                        <div class="text-brand-dark/80">مستفيد من اختباراتنا</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Assessments Grid --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                    اختباراتنا
                </span>
                <h2 class="text-3xl font-display font-bold text-brand-dark mb-4">اختر الاختبار المناسب لك</h2>
                <p class="text-brand-textMuted max-w-2xl mx-auto">مجموعة متنوعة من الاختبارات العلمية لاكتشاف جوانب مختلفة من شخصيتك المهنية</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- Holland Test --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                             alt="اختبار هولاند للميول المهنية" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 right-4 bg-brand-gold text-brand-dark text-xs px-3 py-1 rounded-full font-bold">
                            الأكثر شعبية
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 right-4 left-4">
                            <h3 class="text-xl font-bold text-white">اختبار هولاند (RIASEC)</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-brand-textMuted mb-4">
                            اكتشف ميولك المهنية وفقاً لنظرية RIASEC الشهيرة التي تصنف الشخصيات إلى 6 أنماط رئيسية.
                        </p>
                        <ul class="text-sm text-brand-textMuted space-y-2 mb-6">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-clock text-brand-gold"></i>
                                <span>15-20 دقيقة</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-question-circle text-brand-gold"></i>
                                <span>60 سؤال</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-brand-gold"></i>
                                <span>تقرير PDF مفصل</span>
                            </li>
                        </ul>
                        @auth
                            <a href="{{ route('assessments.show', 'holland') }}" 
                               class="block w-full text-center bg-brand-gold text-brand-dark py-3 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                                <i class="fas fa-play ml-2"></i>
                                ابدأ الاختبار
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-gray-200 text-brand-textMuted py-3 rounded-xl font-bold hover:bg-brand-gold hover:text-brand-dark transition">
                                <i class="fas fa-lock ml-2"></i>
                                سجل دخولك للبدء
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- MBTI Test --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                             alt="اختبار الشخصية MBTI" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 right-4 left-4">
                            <h3 class="text-xl font-bold text-white">اختبار الشخصية (MBTI)</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-brand-textMuted mb-4">
                            تعرف على نمط شخصيتك من بين 16 نمطاً مختلفاً، وكيف يؤثر ذلك على اختياراتك المهنية.
                        </p>
                        <ul class="text-sm text-brand-textMuted space-y-2 mb-6">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-clock text-brand-gold"></i>
                                <span>20-25 دقيقة</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-question-circle text-brand-gold"></i>
                                <span>70 سؤال</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-brand-gold"></i>
                                <span>تقرير PDF مفصل</span>
                            </li>
                        </ul>
                        @auth
                            <a href="{{ route('assessments.show', 'mbti') }}" 
                               class="block w-full text-center bg-brand-DEFAULT text-white py-3 rounded-xl font-bold hover:bg-brand-dark transition">
                                <i class="fas fa-play ml-2"></i>
                                ابدأ الاختبار
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-gray-200 text-brand-textMuted py-3 rounded-xl font-bold hover:bg-brand-DEFAULT hover:text-white transition">
                                <i class="fas fa-lock ml-2"></i>
                                سجل دخولك للبدء
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Multiple Intelligences --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                             alt="اختبار الذكاءات المتعددة" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 right-4 left-4">
                            <h3 class="text-xl font-bold text-white">الذكاءات المتعددة</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-brand-textMuted mb-4">
                            اكتشف أنواع ذكائك المتعددة وكيف يمكنك استثمارها في حياتك المهنية والشخصية.
                        </p>
                        <ul class="text-sm text-brand-textMuted space-y-2 mb-6">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-clock text-brand-gold"></i>
                                <span>15-20 دقيقة</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-question-circle text-brand-gold"></i>
                                <span>56 سؤال</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-brand-gold"></i>
                                <span>تقرير PDF مفصل</span>
                            </li>
                        </ul>
                        @auth
                            <a href="{{ route('assessments.show', 'mi') }}" 
                               class="block w-full text-center bg-brand-DEFAULT text-white py-3 rounded-xl font-bold hover:bg-brand-dark transition">
                                <i class="fas fa-play ml-2"></i>
                                ابدأ الاختبار
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-gray-200 text-brand-textMuted py-3 rounded-xl font-bold hover:bg-brand-DEFAULT hover:text-white transition">
                                <i class="fas fa-lock ml-2"></i>
                                سجل دخولك للبدء
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Work Values --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                             alt="اختبار القيم المهنية" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 right-4 bg-brand-DEFAULT text-white text-xs px-3 py-1 rounded-full font-bold">
                            جديد
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 right-4 left-4">
                            <h3 class="text-xl font-bold text-white">القيم المهنية</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-brand-textMuted mb-4">
                            تعرف على قيمك المهنية وما الذي يحفزك في بيئة العمل ويشعرك بالرضا الوظيفي.
                        </p>
                        <ul class="text-sm text-brand-textMuted space-y-2 mb-6">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-clock text-brand-gold"></i>
                                <span>10-15 دقيقة</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-question-circle text-brand-gold"></i>
                                <span>40 سؤال</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-brand-gold"></i>
                                <span>تقرير PDF مفصل</span>
                            </li>
                        </ul>
                        @auth
                            <a href="{{ route('assessments.show', 'work-values') }}" 
                               class="block w-full text-center bg-brand-DEFAULT text-white py-3 rounded-xl font-bold hover:bg-brand-dark transition">
                                <i class="fas fa-play ml-2"></i>
                                ابدأ الاختبار
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-gray-200 text-brand-textMuted py-3 rounded-xl font-bold hover:bg-brand-DEFAULT hover:text-white transition">
                                <i class="fas fa-lock ml-2"></i>
                                سجل دخولك للبدء
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Career Fit --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                             alt="اختبار الملاءمة المهنية" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 right-4 left-4">
                            <h3 class="text-xl font-bold text-white">الملاءمة المهنية</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-brand-textMuted mb-4">
                            اكتشف المجالات المهنية الأكثر ملاءمة لشخصيتك وقدراتك واهتماماتك.
                        </p>
                        <ul class="text-sm text-brand-textMuted space-y-2 mb-6">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-clock text-brand-gold"></i>
                                <span>15-20 دقيقة</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-question-circle text-brand-gold"></i>
                                <span>50 سؤال</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-file-pdf text-brand-gold"></i>
                                <span>تقرير PDF مفصل</span>
                            </li>
                        </ul>
                        @auth
                            <a href="{{ route('assessments.show', 'career-fit') }}" 
                               class="block w-full text-center bg-brand-DEFAULT text-white py-3 rounded-xl font-bold hover:bg-brand-dark transition">
                                <i class="fas fa-play ml-2"></i>
                                ابدأ الاختبار
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-gray-200 text-brand-textMuted py-3 rounded-xl font-bold hover:bg-brand-DEFAULT hover:text-white transition">
                                <i class="fas fa-lock ml-2"></i>
                                سجل دخولك للبدء
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Premium Package --}}
                <div class="bg-gradient-to-br from-brand-dark to-brand-navy rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 relative">
                    <div class="absolute top-4 right-4 bg-brand-gold text-brand-dark text-xs px-3 py-1 rounded-full font-bold">
                        <i class="fas fa-crown ml-1"></i>
                        باقة شاملة
                    </div>
                    <div class="p-8 pt-12">
                        <div class="w-16 h-16 bg-brand-gold/20 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-gem text-3xl text-brand-gold"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">الباقة الشاملة</h3>
                        <p class="text-gray-300 mb-6">
                            احصل على جميع الاختبارات مع تقرير تفصيلي شامل وجلسة إرشادية مجانية مع مستشار متخصص.
                        </p>
                        <ul class="text-gray-300 space-y-3 mb-8">
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-brand-gold"></i>
                                <span>جميع الاختبارات (5 اختبارات)</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-brand-gold"></i>
                                <span>تقرير تكاملي شامل</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-brand-gold"></i>
                                <span>جلسة إرشادية مجانية</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-brand-gold"></i>
                                <span>خطة تطوير مهني</span>
                            </li>
                        </ul>
                        <a href="{{ route('contact') }}" 
                           class="block w-full text-center bg-brand-gold text-brand-dark py-3 rounded-xl font-bold hover:bg-white transition">
                            تواصل معنا للحصول عليها
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                    كيف يعمل
                </span>
                <h2 class="text-3xl font-display font-bold text-brand-dark mb-4">خطوات بسيطة للبدء</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="w-20 h-20 bg-brand-gold rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-brand-dark">1</span>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">سجّل حسابك</h3>
                    <p class="text-sm text-brand-textMuted">أنشئ حساب مجاني للوصول للاختبارات</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-brand-DEFAULT rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">اختر الاختبار</h3>
                    <p class="text-sm text-brand-textMuted">اختر الاختبار الذي يناسب احتياجاتك</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-brand-orange rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">أجب على الأسئلة</h3>
                    <p class="text-sm text-brand-textMuted">أجب بصراحة للحصول على نتائج دقيقة</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white">4</span>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">احصل على النتائج</h3>
                    <p class="text-sm text-brand-textMuted">استلم تقريرك المفصل فوراً</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white">5</span>
                    </div>
                    <h3 class="font-bold text-brand-dark mb-2">ابدأ رحلتك</h3>
                    <p class="text-sm text-brand-textMuted">اتخذ قراراتك المهنية بثقة</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="فريق عمل" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-brand-dark/90"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-3xl lg:text-4xl font-display font-bold text-white mb-6">
                هل لديك استفسار حول اختباراتنا؟
            </h2>
            <p class="text-gray-300 mb-10 max-w-2xl mx-auto">
                فريقنا جاهز لمساعدتك في اختيار الاختبار المناسب أو الإجابة على أي استفسار
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 bg-brand-gold text-brand-dark px-10 py-4 font-bold rounded-xl hover:bg-white transition duration-300 shadow-xl">
                    <i class="fas fa-comments"></i>
                    <span>تواصل معنا</span>
                </a>
                <a href="https://wa.me/966543494316?text=السلام%20عليكم،%20أرغب%20في%20الاستفسار%20عن%20خدماتكم" target="_blank" class="inline-flex items-center justify-center gap-2 bg-green-500 text-white px-10 py-4 font-bold rounded-xl hover:bg-green-600 transition duration-300">
                    <i class="fab fa-whatsapp text-xl"></i>
                    <span>واتساب</span>
                </a>
            </div>
        </div>
    </section>

@endsection

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "اختبارات الميول المهنية - الطريق المشرق",
    "description": "اختبارات علمية معتمدة لاكتشاف الميول والشخصية المهنية",
    "numberOfItems": 5,
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "اختبار هولاند (RIASEC)",
            "description": "اكتشف ميولك المهنية وفقاً لنظرية RIASEC"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "اختبار الشخصية (MBTI)",
            "description": "تعرف على نمط شخصيتك من 16 نمط"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "الذكاءات المتعددة",
            "description": "اكتشف أنواع ذكائك المتعددة"
        },
        {
            "@type": "ListItem",
            "position": 4,
            "name": "القيم المهنية",
            "description": "تعرف على قيمك في بيئة العمل"
        },
        {
            "@type": "ListItem",
            "position": 5,
            "name": "الملاءمة المهنية",
            "description": "اكتشف المجالات الأكثر ملاءمة لك"
        }
    ]
}
</script>
@endpush
