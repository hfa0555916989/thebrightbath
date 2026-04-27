<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    {{-- Google Analytics (gtag.js) --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-K7ZEBV8Z8D"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-K7ZEBV8Z8D');
    </script>
    
    {{-- SEO Component --}}
    <x-seo 
        :title="$title ?? null" 
        :description="$description ?? null" 
        :keywords="$keywords ?? null" 
        :image="$image ?? null" 
        :canonical="$canonical ?? null" 
        :type="$type ?? null" 
    />
    
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Tailwind Config --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#f7fafc',
                            DEFAULT: '#1F3A63',
                            dark: '#162032',
                            gold: '#F8C524',
                            goldDeep: '#E5A91F',
                            goldLight: '#FFD96A',
                            orange: '#F28C28',
                            orangeDark: '#E56A1F',
                            accent: '#e2e8f0',
                            blue: '#1F3A63',
                            blueLight: '#3B5B89',
                            navy: '#162032',
                            red: '#D94235',
                            bg: '#F5F7FA',
                            card: '#FFFFFF',
                            border: '#E0E6ED',
                            text: '#1F2933',
                            textMuted: '#6B7280'
                        }
                    },
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                        display: ['Noto Kufi Arabic', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Smooth Scrolling */
        html { scroll-behavior: smooth; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }

        /* Animations */
        .fade-in-up { animation: fadeInUp 0.8s ease-out; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in-right { animation: fadeInRight 0.8s ease-out; }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .scale-up { animation: scaleUp 0.5s ease-out; }
        @keyframes scaleUp {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(-5deg);
        }
        
        .service-card:hover .service-action {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Floating animation */
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #F8C524 0%, #F28C28 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Glass effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        /* Pulse effect for CTA */
        .pulse-gold {
            animation: pulseGold 2s infinite;
        }
        @keyframes pulseGold {
            0%, 100% { box-shadow: 0 0 0 0 rgba(248, 197, 36, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(248, 197, 36, 0); }
        }
        
        /* Mobile Menu Styles */
        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(22, 32, 50, 0.7);
            backdrop-filter: blur(4px);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-menu-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            max-width: 85vw;
            height: 100%;
            background: white;
            z-index: 999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 30px rgba(0,0,0,0.2);
        }
        .mobile-menu-drawer.active {
            transform: translateX(0);
        }
        
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }
        
        /* Mobile responsive fixes */
        @media (max-width: 640px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
    
    @stack('styles')
    
    {{-- Schema.org Structured Data --}}
    <x-schema type="organization" />
    <x-schema type="website" />
    <x-schema type="localBusiness" />
    @stack('schema')
</head>
<body class="bg-brand-bg text-brand-text font-sans antialiased">

    {{-- Notification Toast --}}
    <div id="toast" class="fixed top-5 left-5 bg-brand-DEFAULT text-white px-6 py-4 rounded-lg shadow-2xl transform -translate-x-full transition-transform duration-300 z-50 flex items-center gap-3" style="display: none;">
        <i class="fas fa-check-circle text-brand-gold"></i>
        <span id="toast-message">تم بنجاح!</span>
    </div>

    {{-- Navigation --}}
    <nav class="fixed w-full glass-effect shadow-sm z-40 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-4 lg:px-6 py-3 lg:py-4">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                    <img src="{{ asset('images/bright-path-logo.png') }}" alt="الطريق المشرق - Bright Path" class="h-20 sm:h-24 lg:h-32 w-auto">
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden lg:flex items-center gap-6 xl:gap-8">
                    <a href="{{ route('home') }}" class="text-brand-text hover:text-brand-gold transition font-medium">الرئيسية</a>
                    <a href="{{ route('about') }}" class="text-brand-textMuted hover:text-brand-gold transition">من نحن</a>
                    <a href="{{ route('services') }}" class="text-brand-textMuted hover:text-brand-gold transition">خدماتنا</a>
                    <a href="{{ route('assessments.index') }}" class="text-brand-textMuted hover:text-brand-gold transition">اختبارات الميول</a>
                    <a href="{{ route('consultations.index') }}" class="text-brand-textMuted hover:text-brand-gold transition">استشارات فورية</a>
                    <a href="{{ route('career-book.index') }}" class="text-brand-textMuted hover:text-brand-gold transition">مكتبتنا</a>
                    <a href="{{ route('analysis-models.index') }}" class="text-brand-textMuted hover:text-brand-gold transition">نماذج التحليل</a>
                    <a href="{{ route('contact') }}" class="text-brand-textMuted hover:text-brand-gold transition">تواصل معنا</a>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <div class="hidden sm:block relative" id="user-dropdown-container">
                            <button id="user-dropdown-btn" class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition shadow-md hover:opacity-90 text-sm"
                                    style="background-color: #1F3A63; color: white;">
                                <i class="fas fa-user-circle"></i>
                                <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="user-dropdown-menu" class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 hidden">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-tachometer-alt ml-2 text-brand-gold"></i> لوحة الإدارة
                                    </a>
                                @elseif(auth()->user()->role === 'consultant')
                                    <a href="{{ route('consultant.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-tachometer-alt ml-2 text-brand-gold"></i> لوحة المستشار
                                    </a>
                                @else
                                    <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-tachometer-alt ml-2 text-brand-gold"></i> لوحة التحكم
                                    </a>
                                    <a href="{{ route('client.results') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-chart-pie ml-2 text-brand-gold"></i> نتائجي
                                    </a>
                                    <a href="{{ route('client.sessions') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-calendar ml-2 text-brand-gold"></i> جلساتي
                                    </a>
                                @endif
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('register') }}" 
                           class="hidden md:inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition hover:opacity-90 text-sm"
                           style="background-color: #1F3A63; color: white;">
                            <i class="fas fa-user-plus"></i>
                            <span>تسجيل جديد</span>
                        </a>
                        <a href="{{ route('login') }}" 
                           class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition shadow-md pulse-gold hover:opacity-90 text-sm"
                           style="background-color: #F8C524; color: #1F3A63;">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>دخول</span>
                        </a>
                    @endauth
                    
                    {{-- Mobile Menu Button --}}
                    <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 flex items-center justify-center text-brand-dark focus:outline-none rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-xl" id="mobile-menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    {{-- Mobile Menu Overlay --}}
    <div class="mobile-menu-overlay lg:hidden" id="mobile-menu-overlay"></div>
    
    {{-- Mobile Menu Drawer --}}
    <div class="mobile-menu-drawer lg:hidden" id="mobile-menu-drawer">
        {{-- Header --}}
        <div class="p-4 bg-gradient-to-l from-brand-DEFAULT to-brand-dark flex-shrink-0">
            <div class="flex justify-between items-center">
                <img src="{{ asset('images/bright-path-logo.png') }}" alt="الطريق المشرق - Bright Path" class="h-14 w-auto">
                <button id="mobile-menu-close" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white hover:bg-white/30 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        {{-- Navigation Links --}}
        <div class="flex-1 overflow-y-auto p-4 bg-white">
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                    <div class="w-9 h-9 bg-brand-gold/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-brand-gold text-sm"></i>
                    </div>
                    <span class="font-medium text-brand-dark">الرئيسية</span>
                </a>
                <a href="{{ route('about') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                    <div class="w-9 h-9 bg-brand-DEFAULT/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-brand-DEFAULT text-sm"></i>
                    </div>
                    <span class="font-medium text-brand-dark">من نحن</span>
                </a>
                <a href="{{ route('services') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                    <div class="w-9 h-9 bg-brand-orange/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cogs text-brand-orange text-sm"></i>
                    </div>
                    <span class="font-medium text-brand-dark">خدماتنا</span>
                </a>
                <a href="{{ route('assessments.index') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                    <div class="w-9 h-9 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-green-600 text-sm"></i>
                    </div>
                    <span class="font-medium text-brand-dark">اختبارات الميول</span>
                </a>
                <a href="{{ route('consultations.index') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                    <div class="w-9 h-9 bg-brand-gold/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-brand-gold text-sm"></i>
                    </div>
                    <span class="font-medium text-brand-dark">استشارات فورية</span>
                </a>
                <a href="{{ route('career-book.index') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                    <div class="w-9 h-9 bg-purple-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-purple-600 text-sm"></i>
                    </div>
                    <span class="font-medium text-brand-dark">مكتبتنا</span>
                </a>
                                <a href="{{ route('analysis-models.index') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                                    <div class="w-9 h-9 bg-teal-500/10 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-table text-teal-600 text-sm"></i>
                                    </div>
                                    <span class="font-medium text-brand-dark">نماذج التحليل</span>
                                </a>
                <a href="{{ route('contact') }}" class="mobile-menu-link flex items-center gap-3 py-3 px-3 rounded-xl hover:bg-brand-gold/10 transition">
                    <div class="w-9 h-9 bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600 text-sm"></i>
                    </div>
                    <span class="font-medium text-brand-dark">تواصل معنا</span>
                </a>
            </div>
            
            {{-- WhatsApp Quick Contact --}}
            <div class="mt-4 p-3 bg-gradient-to-l from-green-50 to-green-100 rounded-xl">
                <a href="https://wa.me/966543494316" target="_blank" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fab fa-whatsapp text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">تواصل مباشر</p>
                        <p class="font-bold text-green-600 text-sm" dir="ltr">+966 54 349 4316</p>
                    </div>
                </a>
            </div>
        </div>
        
        {{-- Footer Actions --}}
        <div class="p-4 bg-gray-50 border-t border-gray-200 space-y-2 flex-shrink-0">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="mobile-menu-link flex items-center justify-center gap-2 py-3 rounded-xl font-medium shadow-lg transition text-sm"
                       style="background-color: #1F3A63; color: white;">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>لوحة الإدارة</span>
                    </a>
                @elseif(auth()->user()->role === 'consultant')
                    <a href="{{ route('consultant.dashboard') }}" 
                       class="mobile-menu-link flex items-center justify-center gap-2 py-3 rounded-xl font-medium shadow-lg transition text-sm"
                       style="background-color: #1F3A63; color: white;">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>لوحة المستشار</span>
                    </a>
                @else
                    <a href="{{ route('client.dashboard') }}" 
                       class="mobile-menu-link flex items-center justify-center gap-2 py-3 rounded-xl font-medium shadow-lg transition text-sm"
                       style="background-color: #1F3A63; color: white;">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>لوحة التحكم</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-medium bg-red-100 text-red-700 hover:bg-red-200 transition text-sm">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}" 
                   class="mobile-menu-link flex items-center justify-center gap-2 py-3 rounded-xl font-medium shadow-lg transition hover:opacity-90 text-sm"
                   style="background-color: #1F3A63; color: white;">
                    <i class="fas fa-user-plus"></i>
                    <span>تسجيل حساب جديد</span>
                </a>
                <a href="{{ route('login') }}" 
                   class="mobile-menu-link flex items-center justify-center gap-2 py-3 rounded-xl font-medium shadow-lg transition hover:opacity-90 text-sm"
                   style="background-color: #F8C524; color: #1F3A63;">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>تسجيل الدخول</span>
                </a>
            @endauth
        </div>
    </div>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Newsletter Section --}}
    @hasSection('hide_newsletter')
    @else
    <section class="py-12 lg:py-20 bg-brand-dark text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <i class="fas fa-graduation-cap absolute -top-10 -right-10 text-[150px] lg:text-[200px] transform rotate-12"></i>
            <i class="fas fa-compass absolute bottom-0 left-0 text-[150px] lg:text-[200px] transform -rotate-12"></i>
        </div>
        <div class="container mx-auto px-4 lg:px-6 relative z-10 text-center">
            <h2 class="text-2xl lg:text-4xl font-display font-bold mb-4">انضم إلى مجتمع الطريق المشرق</h2>
            <p class="text-gray-300 mb-6 lg:mb-8 max-w-lg mx-auto text-base lg:text-lg">اشترك ليصلك كل جديد من مقالات ونصائح مهنية وعروض حصرية</p>
            <form action="{{ route('contact') }}" method="GET" class="flex flex-col sm:flex-row-reverse justify-center max-w-lg mx-auto gap-3">
                <input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" 
                       class="w-full px-4 py-3 lg:px-6 lg:py-4 rounded-lg text-brand-text focus:outline-none focus:ring-2 focus:ring-brand-gold">
                <button type="submit" class="bg-brand-gold text-brand-dark px-6 py-3 lg:px-8 lg:py-4 rounded-lg font-bold hover:bg-white transition duration-300 whitespace-nowrap">
                    <i class="fas fa-paper-plane ml-2"></i>
                    اشترك الآن
                </button>
            </form>
        </div>
    </section>
    @endif

    {{-- Footer --}}
    <footer class="bg-brand-navy text-white pt-12 lg:pt-16 pb-8">
        <div class="container mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-8 lg:mb-12">
                {{-- Brand Info --}}
                <div class="col-span-2 md:col-span-2 lg:col-span-1">
                    <a href="{{ route('home') }}" class="inline-block mb-4 lg:mb-6">
                        <img src="{{ asset('images/bright-path-logo.png') }}" alt="الطريق المشرق - Bright Path" class="h-20 lg:h-28 w-auto">
                    </a>
                    <p class="text-gray-400 leading-relaxed mb-4 lg:mb-6 text-sm lg:text-base">
                        الطريق المشرق للتدريب والتطوير - رواد في التدريب والتطوير المهني والإرشاد الوظيفي.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 lg:w-10 lg:h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-brand-gold transition text-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-9 h-9 lg:w-10 lg:h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-brand-gold transition text-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-9 h-9 lg:w-10 lg:h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-brand-gold transition text-sm">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-base lg:text-lg font-bold mb-4 lg:mb-6 text-brand-gold">روابط سريعة</h4>
                    <ul class="space-y-2 lg:space-y-3 text-gray-400 text-sm lg:text-base">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">من نحن</a></li>
                        <li><a href="{{ route('services') }}" class="hover:text-white transition">خدماتنا</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">تواصل معنا</a></li>
                    </ul>
                </div>

                {{-- Services --}}
                <div>
                    <h4 class="text-base lg:text-lg font-bold mb-4 lg:mb-6 text-brand-gold">خدماتنا</h4>
                    <ul class="space-y-2 lg:space-y-3 text-gray-400 text-sm lg:text-base">
                        <li><a href="{{ route('assessments.index') }}" class="hover:text-white transition">اختبارات الميول</a></li>
                        <li><a href="{{ route('consultations.index') }}" class="hover:text-white transition">استشارات فورية</a></li>
                        <li><a href="{{ route('analysis-models.index') }}" class="hover:text-white transition">نماذج التحليل</a></li>
                        <li><a href="{{ route('career-book.index') }}" class="hover:text-white transition">مكتبتنا</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="col-span-2 md:col-span-1">
                    <h4 class="text-base lg:text-lg font-bold mb-4 lg:mb-6 text-brand-gold">تواصل معنا</h4>
                    <ul class="space-y-3 lg:space-y-4 text-gray-400 text-sm lg:text-base">
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone-alt text-brand-gold"></i>
                            <a href="tel:+966543494316" class="hover:text-white transition" dir="ltr">+966 54 349 4316</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fab fa-whatsapp text-brand-gold"></i>
                            <a href="https://wa.me/966543494316" target="_blank" class="hover:text-white transition" dir="ltr">واتساب</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-brand-gold"></i>
                            <a href="mailto:cs@thebrightbath.com" class="hover:text-white transition text-xs lg:text-base">cs@thebrightbath.com</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 lg:pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-xs lg:text-sm text-center md:text-right">
                    &copy; {{ date('Y') }} الطريق المشرق للتدريب والتطوير. جميع الحقوق محفوظة.
                </p>
                <div class="flex gap-4 lg:gap-6 text-xs lg:text-sm text-gray-500">
                    <a href="{{ route('privacy') }}" class="hover:text-white transition">سياسة الخصوصية</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition">الشروط والأحكام</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating Contact Buttons --}}
    <div class="fixed bottom-4 left-4 lg:bottom-6 lg:left-6 z-50 flex flex-col gap-2 lg:gap-3">
        {{-- Phone Call Button --}}
        <a href="tel:+966543494316" 
           class="group w-12 h-12 lg:w-14 lg:h-14 bg-brand-DEFAULT rounded-full flex items-center justify-center shadow-xl hover:scale-110 transition-all duration-300"
           title="اتصل بنا">
            <i class="fas fa-phone-alt text-white text-lg lg:text-xl"></i>
        </a>
        
        {{-- WhatsApp Button --}}
        <a href="https://wa.me/966543494316?text=السلام%20عليكم،%20أرغب%20في%20الاستفسار%20عن%20خدماتكم" 
           target="_blank"
           class="group w-14 h-14 lg:w-16 lg:h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-xl hover:scale-110 transition-all duration-300 animate-bounce-slow relative"
           title="تواصل عبر الواتساب">
            <i class="fab fa-whatsapp text-white text-2xl lg:text-3xl"></i>
            <span class="absolute inset-0 rounded-full bg-green-400 animate-ping opacity-30"></span>
        </a>
    </div>

    {{-- Scripts --}}
    <script>
        // Toast function
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            toastMessage.textContent = message;
            toast.style.display = 'flex';
            toast.classList.remove('-translate-x-full');
            setTimeout(() => {
                toast.classList.add('-translate-x-full');
                setTimeout(() => { toast.style.display = 'none'; }, 300);
            }, 3000);
        }

        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const menuOverlay = document.getElementById('mobile-menu-overlay');
            const menuDrawer = document.getElementById('mobile-menu-drawer');
            const closeBtn = document.getElementById('mobile-menu-close');
            const menuIcon = document.getElementById('mobile-menu-icon');
            const menuLinks = document.querySelectorAll('.mobile-menu-link');
            
            function openMenu() {
                menuDrawer.classList.add('active');
                menuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                if (menuIcon) {
                    menuIcon.classList.remove('fa-bars');
                    menuIcon.classList.add('fa-times');
                }
            }
            
            function closeMenu() {
                menuDrawer.classList.remove('active');
                menuOverlay.classList.remove('active');
                document.body.style.overflow = '';
                if (menuIcon) {
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
            }
            
            if (menuBtn) {
                menuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (menuDrawer.classList.contains('active')) {
                        closeMenu();
                    } else {
                        openMenu();
                    }
                });
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeMenu();
                });
            }
            
            if (menuOverlay) {
                menuOverlay.addEventListener('click', closeMenu);
            }
            
            // Close menu when clicking links
            menuLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    setTimeout(closeMenu, 100);
                });
            });
            
            // User dropdown
            const userBtn = document.getElementById('user-dropdown-btn');
            const userMenu = document.getElementById('user-dropdown-menu');
            
            if (userBtn && userMenu) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(e) {
                    if (!userMenu.classList.contains('hidden')) {
                        if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                            userMenu.classList.add('hidden');
                        }
                    }
                });
            }
        });
    </script>

    @stack('scripts')
    @stack('schema')
    
    {{-- Google Analytics Page Tracking --}}
    <script>
        // إرسال اسم الصفحة الحالية إلى Google Analytics
        document.addEventListener('DOMContentLoaded', function() {
            var pageTitle = document.title || 'الطريق المشرق';
            var pagePath = window.location.pathname;
            
            // تحديد اسم الصفحة بناءً على المسار
            var pageNames = {
                '/': 'الصفحة الرئيسية',
                '/about': 'من نحن',
                '/vision-mission': 'الرؤية والرسالة',
                '/strategic-goals': 'الأهداف الاستراتيجية',
                '/values': 'القيم',
                '/services': 'الخدمات والبرامج',
                '/terms': 'الشروط والأحكام',
                '/privacy': 'سياسة الخصوصية',
                '/assessments': 'صفحة الاختبارات',
                '/library': 'المكتبة المهنية',
                '/contact': 'صفحة التواصل',
                '/consultations': 'صفحة الاستشارات',
                '/my-bookings': 'حجوزاتي',
                '/dashboard': 'لوحة تحكم العميل',
                '/my-results': 'نتائجي',
                '/my-sessions': 'جلساتي',
                '/my-invoices': 'فواتيري',
                '/profile': 'ملفي الشخصي',
                '/analysis-models': 'نماذج التحليل الوظيفي',
                '/login': 'صفحة تسجيل الدخول',
                '/register': 'صفحة التسجيل'
            };
            
            // البحث عن اسم الصفحة المطابق
            var currentPageName = pageNames[pagePath];
            
            // إذا كان المسار يحتوي على /booking/ و /payment
            if (pagePath.includes('/booking/') && pagePath.includes('/payment')) {
                currentPageName = 'صفحة الدفع';
            }
            // إذا كان المسار يحتوي على /booking/ و /confirmation
            else if (pagePath.includes('/booking/') && pagePath.includes('/confirmation')) {
                currentPageName = 'تأكيد الشراء';
            }
            // إذا كان المسار يحتوي على /booking/ و /waiting-approval
            else if (pagePath.includes('/booking/') && pagePath.includes('/waiting-approval')) {
                currentPageName = 'انتظار موافقة المستشار';
            }
            // إذا كان في صفحة اختبار معين
            else if (pagePath.startsWith('/assessments/') && pagePath !== '/assessments/') {
                var testSlug = pagePath.split('/')[2];
                if (testSlug) {
                    if (testSlug.includes('/result')) {
                        currentPageName = 'نتيجة الاختبار';
                    } else {
                        currentPageName = 'صفحة اختبار: ' + testSlug;
                    }
                }
            }
            // إذا كان في صفحة استشاري معين
            else if (pagePath.startsWith('/consultations/') && pagePath !== '/consultations/') {
                currentPageName = 'صفحة مستشار';
            }
            // إذا كان في صفحة كتاب معين
            else if (pagePath.startsWith('/library/') && pagePath !== '/library/') {
                currentPageName = 'فصل من الكتاب المهني';
            }
            // إذا كان في صفحة نماذج التحليل
            else if (pagePath.startsWith('/analysis-models/') && pagePath !== '/analysis-models/') {
                currentPageName = 'نموذج تحليل وظيفي';
            }
            
            // إذا لم يتم العثور على اسم، استخدم عنوان الصفحة
            if (!currentPageName) {
                currentPageName = pageTitle;
            }
            
            // إرسال حدث تتبع الصفحة مع الاسم المخصص
            if (typeof gtag !== 'undefined') {
                gtag('event', 'page_view', {
                    'page_title': currentPageName,
                    'page_location': window.location.href,
                    'page_path': pagePath,
                    'custom_page_name': currentPageName
                });
            }
        });
    </script>
    
    {{-- Stack للأحداث المخصصة للصفحات --}}
    @stack('analytics')
</body>
</html>
