@extends('layouts.public')

@php
    $title = 'الرؤية والرسالة - الطريق المشرق للتدريب والتطوير';
    $description = 'تعرف على رؤية ورسالة الطريق المشرق للتدريب والتطوير في مجال الإرشاد المهني';
    $keywords = 'رؤية، رسالة، الطريق المشرق، التدريب والتطوير';
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
                <span class="text-brand-gold">الرؤية والرسالة</span>
            </nav>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">
                الرؤية والرسالة
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                نطمح لأن نكون الخيار الأول في الإرشاد المهني والتطوير الوظيفي
            </p>
        </div>
    </section>

    {{-- Vision Section --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <div class="bg-gradient-to-br from-brand-gold to-brand-orange rounded-2xl p-16 shadow-2xl text-center">
                        <i class="fas fa-eye text-8xl text-white/90 mb-6"></i>
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-48 h-48 bg-brand-gold/20 rounded-2xl -z-10"></div>
                </div>
                <div>
                    <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i class="fas fa-eye ml-2"></i>
                        رؤيتنا
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-6">نحو مستقبل مهني مشرق للجميع</h2>
                    <p class="text-brand-textMuted leading-relaxed text-lg mb-6">
                        أن تكون الطريق المشرق مرجعًا مميزًا في التدريب والتطوير في مجال الموارد البشرية والإرشاد المهني، ومزوّدًا رائدًا للحلول التدريبية والاعتمادات الدولية التي تسهم في صناعة كفاءات مهنية قادرة على قيادة المستقبل.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block bg-brand-DEFAULT/20 text-brand-DEFAULT px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i class="fas fa-bullseye ml-2"></i>
                        رسالتنا
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-6">تمكين الأفراد والمؤسسات</h2>
                    <p class="text-brand-textMuted leading-relaxed text-lg mb-6">
                        نسعى لتمكين الأفراد والمؤسسات من خلال تقديم برامج تدريبية متخصصة، وجلسات إرشاد مهني احترافية، وتأهيل مهني معتمد دوليًا، وذلك عبر منهجيات تعليمية حديثة، ومدربين مؤهلين، وحلول تدريبية مبتكرة تعزز التطور المهني وتلائم متطلبات سوق العمل المتجددة.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-brand-gold text-xl mt-1"></i>
                            <span class="text-brand-dark">توفير اختبارات علمية معتمدة ودقيقة</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-brand-gold text-xl mt-1"></i>
                            <span class="text-brand-dark">تقديم جلسات إرشادية فردية متخصصة</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-brand-gold text-xl mt-1"></i>
                            <span class="text-brand-dark">تطوير برامج تدريبية مبتكرة</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-brand-gold text-xl mt-1"></i>
                            <span class="text-brand-dark">دعم المؤسسات في تطوير موظفيها</span>
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-brand-DEFAULT to-brand-dark rounded-2xl p-16 shadow-2xl text-center">
                        <i class="fas fa-bullseye text-8xl text-white/90 mb-6"></i>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-brand-DEFAULT/20 rounded-2xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values Preview --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-display font-bold text-brand-dark mb-8">قيمنا تقود عملنا</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mb-10">
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <i class="fas fa-medal text-3xl text-brand-gold mb-3"></i>
                    <h3 class="font-bold text-brand-dark">التميز</h3>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <i class="fas fa-handshake text-3xl text-brand-DEFAULT mb-3"></i>
                    <h3 class="font-bold text-brand-dark">الأمانة</h3>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <i class="fas fa-lightbulb text-3xl text-brand-orange mb-3"></i>
                    <h3 class="font-bold text-brand-dark">الإبداع</h3>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <i class="fas fa-users text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-bold text-brand-dark">الشراكة</h3>
                </div>
            </div>
            <a href="{{ route('values') }}" class="inline-flex items-center gap-2 text-brand-DEFAULT font-bold hover:text-brand-gold transition">
                اقرأ المزيد عن قيمنا
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </section>

@endsection



