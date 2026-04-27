<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'لوحة التحكم' }} - الطريق المشرق</title>
    <meta name="robots" content="noindex, nofollow">
    
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #162032; }
        ::-webkit-scrollbar-thumb { background: #3B5B89; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #F8C524; }
        
        /* Sidebar scrollbar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #3B5B89; border-radius: 2px; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-brand-bg font-sans antialiased" x-data="{ sidebarOpen: true, mobileSidebar: false }">

    <div class="flex min-h-screen">
        
        {{-- Sidebar --}}
        <aside class="fixed lg:sticky top-0 right-0 h-screen w-64 bg-brand-navy shadow-xl z-50 transition-transform duration-300"
               :class="{'translate-x-0': mobileSidebar, 'translate-x-full lg:translate-x-0': !mobileSidebar}">
            
            {{-- Logo --}}
            <div class="h-16 flex items-center justify-between px-6 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <img src="{{ asset('images/bright-path-logo.png') }}" alt="الطريق المشرق - Bright Path" class="h-14 w-auto">
                </a>
                <button @click="mobileSidebar = false" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            {{-- Navigation --}}
            <nav class="p-4 sidebar-scroll overflow-y-auto h-[calc(100vh-4rem)]">
                <div class="space-y-2">
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>لوحة التحكم</span>
                    </a>
                    
                    {{-- Assessments --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.assessments*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-clipboard-list w-5"></i>
                                <span>الاختبارات</span>
                            </div>
                            <i class="fas fa-chevron-down transform transition" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="mr-4 mt-2 space-y-1">
                            <a href="{{ route('admin.assessments.index') }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                                <i class="fas fa-list w-4"></i>
                                قائمة الاختبارات
                            </a>
                            <a href="{{ route('admin.assessments.create') }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                                <i class="fas fa-plus w-4"></i>
                                إضافة اختبار
                            </a>
                        </div>
                    </div>
                    
                    {{-- Attempts --}}
                    @php
                        $newAttemptsCount = \App\Models\AssessmentAttempt::whereIn('status', ['new', 'completed'])->count();
                    @endphp
                    <a href="{{ route('admin.attempts.index') }}" 
                       class="flex items-center justify-between px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.attempts*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span>نتائج الاختبارات</span>
                        </div>
                        @if($newAttemptsCount > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">{{ $newAttemptsCount }}</span>
                        @endif
                    </a>
                    
                    {{-- Consultants --}}
                    <a href="{{ route('admin.consultants.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.consultants*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-user-tie w-5"></i>
                        <span>المستشارين</span>
                    </a>
                    
                    {{-- Bookings --}}
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.bookings*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span>الحجوزات</span>
                    </a>
                    
                    {{-- Finance --}}
                    <a href="{{ route('admin.finance.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.finance*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-coins w-5"></i>
                        <span>التقارير المالية</span>
                    </a>
                    
                    {{-- Payment Settings --}}
                    <a href="{{ route('admin.payment-settings.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.payment-settings*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-credit-card w-5"></i>
                        <span>إعدادات الدفع</span>
                    </a>
                    
                    {{-- Analysis Models --}}
                    <a href="{{ route('admin.analysis-models.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.analysis-models*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-table w-5"></i>
                        <span>نماذج التحليل الوظيفي</span>
                    </a>
                    
                    {{-- Book Chapters --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.book-chapters*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-book w-5"></i>
                                <span>مكتبتنا</span>
                            </div>
                            <i class="fas fa-chevron-down transform transition" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="mr-4 mt-2 space-y-1">
                            <a href="{{ route('admin.book-chapters.index') }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                                <i class="fas fa-list w-4"></i>
                                قائمة الفصول
                            </a>
                            <a href="{{ route('admin.book-chapters.create') }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                                <i class="fas fa-plus w-4"></i>
                                إضافة فصل
                            </a>
                        </div>
                    </div>
                    
                    {{-- Security --}}
                    @if(auth()->user()->role === 'admin')
                    <div x-data="{ open: {{ request()->routeIs('admin.security*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-shield-alt w-5"></i>
                                <span>الأمان</span>
                            </div>
                            <i class="fas fa-chevron-down transform transition" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="mr-4 mt-2 space-y-1">
                            <a href="{{ route('admin.security.logs') }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                                <i class="fas fa-history w-4"></i>
                                سجل الأمان
                            </a>
                            <a href="{{ route('admin.security.activity') }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                                <i class="fas fa-user-clock w-4"></i>
                                نشاط المشرفين
                            </a>
                            <a href="{{ route('admin.security.blocked-ips') }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition text-sm">
                                <i class="fas fa-ban w-4"></i>
                                IP المحظورة
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Divider --}}
                    <div class="border-t border-white/10 my-4"></div>
                    
                    {{-- Users Management --}}
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.users*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>المستخدمين</span>
                    </a>
                    @endif
                    
                    {{-- Logo Upload --}}
                    <a href="{{ route('admin.logo.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.logo*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-image w-5"></i>
                        <span>الشعار</span>
                    </a>
                    
                    {{-- Settings --}}
                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.settings*') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span>الإعدادات</span>
                    </a>
                    
                    {{-- Back to Site --}}
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition">
                        <i class="fas fa-external-link-alt w-5"></i>
                        <span>عرض الموقع</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        {{-- Mobile Overlay --}}
        <div x-show="mobileSidebar" 
             @click="mobileSidebar = false"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
        
        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen">
            
            {{-- Top Bar --}}
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 sticky top-0 z-30">
                {{-- Mobile Menu Button --}}
                <button @click="mobileSidebar = true" class="lg:hidden text-brand-dark">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                {{-- Page Title --}}
                <h1 class="text-lg font-bold text-brand-dark hidden lg:block">
                    {{ $pageTitle ?? 'لوحة التحكم' }}
                </h1>
                
                {{-- Right Side --}}
                <div class="flex items-center gap-4">
                    {{-- Notifications --}}
                    <button class="relative text-brand-textMuted hover:text-brand-DEFAULT transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-brand-red text-white text-xs rounded-full flex items-center justify-center">
                            3
                        </span>
                    </button>
                    
                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-3 hover:bg-brand-light px-3 py-2 rounded-lg transition">
                            <div class="w-10 h-10 bg-brand-gold rounded-full flex items-center justify-center text-brand-dark font-bold">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="hidden sm:block text-right">
                                <div class="font-medium text-brand-dark text-sm">{{ auth()->user()->name ?? 'المدير' }}</div>
                                <div class="text-xs text-brand-textMuted">{{ auth()->user()->role ?? 'admin' }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-brand-textMuted text-xs"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition
                             class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-brand-border py-2 z-50">
                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-4 py-2 text-brand-textMuted hover:bg-brand-light hover:text-brand-dark transition">
                                <i class="fas fa-user w-4"></i>
                                الملف الشخصي
                            </a>
                            {{-- 2FA Settings (Coming Soon)
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-brand-textMuted hover:bg-brand-light hover:text-brand-dark transition">
                                <i class="fas fa-shield-alt w-4"></i>
                                إعدادات 2FA
                            </a>
                            --}}
                            <div class="border-t border-brand-border my-2"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-brand-red hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- Page Content --}}
            <main class="flex-1 p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
            
            {{-- Footer --}}
            <footer class="bg-white border-t border-brand-border py-4 px-6 text-center text-sm text-brand-textMuted">
                &copy; {{ date('Y') }} الطريق المشرق للتدريب والتطوير. جميع الحقوق محفوظة.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>


