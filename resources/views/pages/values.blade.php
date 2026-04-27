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
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Value 1 --}}
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition group">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-brand-gold/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-brand-gold transition">
                            <i class="fas fa-medal text-3xl text-brand-gold group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark mb-3">التميز</h3>
                            <p class="text-brand-textMuted leading-relaxed">
                                نسعى دائماً لتقديم أفضل ما لدينا في كل خدمة نقدمها. نلتزم بأعلى معايير الجودة في اختباراتنا وبرامجنا التدريبية وجلساتنا الإرشادية.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Value 2 --}}
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition group">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-brand-DEFAULT/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-brand-DEFAULT transition">
                            <i class="fas fa-handshake text-3xl text-brand-DEFAULT group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark mb-3">الأمانة والمصداقية</h3>
                            <p class="text-brand-textMuted leading-relaxed">
                                نتعامل بشفافية ومصداقية مع جميع عملائنا. نقدم معلومات دقيقة وصادقة، ونحافظ على سرية بيانات المستفيدين.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Value 3 --}}
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition group">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-brand-orange/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-brand-orange transition">
                            <i class="fas fa-lightbulb text-3xl text-brand-orange group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark mb-3">الإبداع والتطوير</h3>
                            <p class="text-brand-textMuted leading-relaxed">
                                نؤمن بأهمية التطوير المستمر والبحث عن حلول مبتكرة. نحرص على تحديث أدواتنا ومناهجنا لمواكبة أحدث الممارسات العالمية.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Value 4 --}}
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition group">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-green-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-500 transition">
                            <i class="fas fa-users text-3xl text-green-600 group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark mb-3">الشراكة والتعاون</h3>
                            <p class="text-brand-textMuted leading-relaxed">
                                نبني علاقات شراكة حقيقية مع عملائنا. نرافقهم في رحلتهم المهنية ونعمل معاً لتحقيق أهدافهم.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Value 5 --}}
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition group">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-purple-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-500 transition">
                            <i class="fas fa-heart text-3xl text-purple-600 group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark mb-3">الاحترام والتقدير</h3>
                            <p class="text-brand-textMuted leading-relaxed">
                                نحترم تفرد كل فرد ونقدر اختلافاته. نتعامل مع الجميع باحترام ونقدر ثقتهم بنا في مسيرتهم المهنية.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Value 6 --}}
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition group">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-blue-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-500 transition">
                            <i class="fas fa-shield-alt text-3xl text-blue-600 group-hover:text-white transition"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-dark mb-3">الموثوقية العلمية</h3>
                            <p class="text-brand-textMuted leading-relaxed">
                                نعتمد على أسس علمية راسخة في جميع خدماتنا. اختباراتنا مبنية على نظريات مثبتة ومعتمدة عالمياً.
                            </p>
                        </div>
                    </div>
                </div>

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



