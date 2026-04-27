@extends('layouts.public')

@php
    $title = 'من نحن - الطريق المشرق للتدريب والتطوير';
    $description = 'تعرف على الطريق المشرق للتدريب والتطوير - رواد في الإرشاد المهني واختبارات الميول المهنية';
@endphp

@section('content')

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="فريق العمل" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-brand-dark/80"></div>
        </div>
        
        {{-- Breadcrumb --}}
        <div class="container mx-auto px-6 relative z-10 mb-8">
            <nav class="text-sm text-gray-300">
                <a href="{{ route('home') }}" class="hover:text-brand-gold transition">الرئيسية</a>
                <span class="mx-2">/</span>
                <span class="text-brand-gold">من نحن</span>
            </nav>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">
                عن الطريق المشرق
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                رحلة التميز في التدريب والتطوير المهني والإرشاد الوظيفي
            </p>
        </div>
    </section>

    {{-- Story Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Image Side --}}
                <div class="relative">
                    <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="جلسة إرشادية" 
                             class="w-full h-[500px] object-cover">
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-48 h-48 bg-brand-gold/20 rounded-2xl -z-10"></div>
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-brand-DEFAULT/20 rounded-full -z-10"></div>
                    
                    {{-- Stats Card --}}
                    <div class="absolute -bottom-8 -right-8 bg-white rounded-2xl shadow-2xl p-6 z-20">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-brand-gold mb-1">+5000</div>
                            <div class="text-brand-textMuted">مستفيد</div>
                        </div>
                    </div>
                </div>
                
                {{-- Content Side --}}
                <div>
                    <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                        قصتنا
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-6">من نحن؟</h2>
                    <p class="text-brand-textMuted leading-relaxed mb-6 text-lg">
                        الطريق المشرق للتدريب والتطوير - رواد في مجال التدريب والتطوير المهني والإرشاد الوظيفي في المملكة العربية السعودية.
                    </p>
                    <p class="text-brand-textMuted leading-relaxed mb-6">
                        تأسسنا بهدف مساعدة الأفراد على اكتشاف ميولهم وقدراتهم، وتوجيههم نحو المسارات المهنية الأنسب لهم. نؤمن بأن كل فرد يمتلك إمكانيات فريدة تستحق الاكتشاف والتنمية.
                    </p>
                    <p class="text-brand-textMuted leading-relaxed mb-8">
                        نقدم مجموعة متكاملة من الخدمات تشمل اختبارات الميول المهنية العلمية، والبرامج التدريبية المتخصصة، وجلسات الإرشاد المهني الفردية.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-brand-bg rounded-xl p-6 text-center">
                            <div class="text-3xl font-bold text-brand-gold mb-2">+10</div>
                            <div class="text-brand-textMuted">سنوات خبرة</div>
                        </div>
                        <div class="bg-brand-bg rounded-xl p-6 text-center">
                            <div class="text-3xl font-bold text-brand-DEFAULT mb-2">5</div>
                            <div class="text-brand-textMuted">اختبارات معتمدة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Vision & Mission --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Vision --}}
                <div class="relative rounded-2xl overflow-hidden h-[400px] group">
                    <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="الرؤية" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="w-16 h-16 bg-brand-gold rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-eye text-2xl text-brand-dark"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">رؤيتنا</h3>
                        <p class="text-gray-300">
                            أن تكون الطريق المشرق مرجعًا مميزًا في التدريب والتطوير في مجال الموارد البشرية والإرشاد المهني، ومزوّدًا رائدًا للحلول التدريبية والاعتمادات الدولية التي تسهم في صناعة كفاءات مهنية قادرة على قيادة المستقبل.
                        </p>
                    </div>
                </div>

                {{-- Mission --}}
                <div class="relative rounded-2xl overflow-hidden h-[400px] group">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="الرسالة" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-DEFAULT via-brand-DEFAULT/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-bullseye text-2xl text-brand-DEFAULT"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">رسالتنا</h3>
                        <p class="text-gray-200">
                            نسعى لتمكين الأفراد والمؤسسات من خلال تقديم برامج تدريبية متخصصة، وجلسات إرشاد مهني احترافية، وتأهيل مهني معتمد دوليًا، وذلك عبر منهجيات تعليمية حديثة، ومدربين مؤهلين، وحلول تدريبية مبتكرة تعزز التطور المهني وتلائم متطلبات سوق العمل المتجددة.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                    قيمنا
                </span>
                <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-4">ما يميزنا</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="relative mb-6">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80" 
                             alt="التميز" 
                             class="w-full h-48 object-cover rounded-2xl group-hover:scale-105 transition duration-300">
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-16 h-16 bg-brand-gold rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-award text-2xl text-brand-dark"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mt-6 mb-2">التميز</h3>
                    <p class="text-brand-textMuted text-sm">نسعى دائماً لتقديم أفضل الخدمات</p>
                </div>

                <div class="text-center group">
                    <div class="relative mb-6">
                        <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80" 
                             alt="الأمانة" 
                             class="w-full h-48 object-cover rounded-2xl group-hover:scale-105 transition duration-300">
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-16 h-16 bg-brand-DEFAULT rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-handshake text-2xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mt-6 mb-2">الأمانة</h3>
                    <p class="text-brand-textMuted text-sm">نتعامل بشفافية ومصداقية</p>
                </div>

                <div class="text-center group">
                    <div class="relative mb-6">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80" 
                             alt="الإبداع" 
                             class="w-full h-48 object-cover rounded-2xl group-hover:scale-105 transition duration-300">
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-16 h-16 bg-brand-orange rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-lightbulb text-2xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mt-6 mb-2">الإبداع</h3>
                    <p class="text-brand-textMuted text-sm">نبتكر حلولاً متميزة</p>
                </div>

                <div class="text-center group">
                    <div class="relative mb-6">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80" 
                             alt="الشراكة" 
                             class="w-full h-48 object-cover rounded-2xl group-hover:scale-105 transition duration-300">
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-16 h-16 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-2xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-brand-dark mt-6 mb-2">الشراكة</h3>
                    <p class="text-brand-textMuted text-sm">نبني علاقات حقيقية مع عملائنا</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Team Section --}}
    <section class="py-20 bg-brand-bg">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-gold/20 text-brand-gold px-4 py-2 rounded-full text-sm font-bold mb-4">
                    فريقنا
                </span>
                <h2 class="text-3xl lg:text-4xl font-display font-bold text-brand-dark mb-4">نخبة من الخبراء</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.pexels.com/photos/5669619/pexels-photo-5669619.jpeg?auto=compress&cs=tinysrgb&w=400" 
                             alt="مستشار مهني سعودي" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-brand-dark mb-1">مستشارو الإرشاد المهني</h3>
                        <p class="text-brand-textMuted text-sm">متخصصون في توجيه الأفراد نحو المسار المهني الأمثل</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden shadow-lg group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.pexels.com/photos/7504837/pexels-photo-7504837.jpeg?auto=compress&cs=tinysrgb&w=400" 
                             alt="مدرب سعودي معتمد" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-brand-dark mb-1">المدربون المعتمدون</h3>
                        <p class="text-brand-textMuted text-sm">خبراء في تقديم البرامج التدريبية المتخصصة</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden shadow-lg group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="خبير قياس نفسي" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-brand-dark mb-1">خبراء القياس النفسي</h3>
                        <p class="text-brand-textMuted text-sm">متخصصون في تطبيق وتحليل الاختبارات النفسية</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-brand-dark/85"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-3xl lg:text-4xl font-display font-bold text-white mb-6">هل ترغب في معرفة المزيد؟</h2>
            <p class="text-gray-300 mb-10 max-w-2xl mx-auto text-lg">
                تواصل معنا للاستفسار عن خدماتنا أو لحجز جلسة استشارية
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 bg-brand-gold text-brand-dark px-10 py-4 font-bold rounded-xl hover:bg-white transition shadow-xl">
                    <i class="fas fa-envelope"></i>
                    <span>تواصل معنا</span>
                </a>
                <a href="{{ route('services') }}" class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-white text-white px-10 py-4 font-bold rounded-xl hover:bg-white hover:text-brand-dark transition">
                    <i class="fas fa-cogs"></i>
                    <span>استعرض خدماتنا</span>
                </a>
            </div>
        </div>
    </section>

@endsection
