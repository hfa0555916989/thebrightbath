<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'لوحة تحكم المستشار' }} - الطريق المشرق</title>
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
                            navy: '#162032',
                            bg: '#F5F7FA',
                            card: '#FFFFFF',
                            border: '#E0E6ED',
                            text: '#1F2933',
                            textMuted: '#6B7280'
                        },
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
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
                <a href="{{ route('consultant.dashboard') }}" class="flex items-center">
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
                    <a href="{{ route('consultant.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('consultant.dashboard') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>الرئيسية</span>
                    </a>
                    
                    {{-- Pending Requests --}}
                    @php
                        $consultant = \App\Models\Consultant::where('user_id', auth()->id())->first();
                        $pendingCount = $consultant ? \App\Models\Booking::where('consultant_id', $consultant->id)->where('status', 'pending_approval')->count() : 0;
                    @endphp
                    <a href="{{ route('consultant.pending-requests') }}" 
                       class="flex items-center justify-between px-4 py-3 rounded-xl transition {{ request()->routeIs('consultant.pending-requests') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-inbox w-5"></i>
                            <span>طلبات الحجز</span>
                        </div>
                        @if($pendingCount > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    
                    {{-- Sessions --}}
                    <a href="{{ route('consultant.sessions') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('consultant.sessions') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span>جلساتي</span>
                    </a>
                    
                    {{-- Schedule --}}
                    <a href="{{ route('consultant.schedule') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('consultant.schedule') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-clock w-5"></i>
                        <span>جدول المواعيد</span>
                    </a>
                    
                    {{-- Earnings --}}
                    <a href="{{ route('consultant.earnings') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('consultant.earnings') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-coins w-5"></i>
                        <span>أرباحي</span>
                    </a>
                    
                    {{-- Divider --}}
                    <div class="border-t border-white/10 my-4"></div>
                    
                    {{-- Profile --}}
                    <a href="{{ route('consultant.profile') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('consultant.profile') ? 'bg-brand-gold text-brand-dark' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-user-cog w-5"></i>
                        <span>الملف الشخصي</span>
                    </a>
                    
                    {{-- Back to Site --}}
                    <a href="{{ route('home') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition">
                        <i class="fas fa-external-link-alt w-5"></i>
                        <span>العودة للموقع</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        {{-- Main Content --}}
        <div class="flex-1 lg:mr-0">
            {{-- Top Bar --}}
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 sticky top-0 z-40">
                {{-- Mobile Menu Button --}}
                <button @click="mobileSidebar = true" class="lg:hidden text-gray-600 hover:text-brand-dark">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                {{-- Page Title --}}
                <h1 class="text-lg font-bold text-gray-800 hidden lg:block">@yield('title', 'لوحة التحكم')</h1>
                
                {{-- User Menu --}}
                <div class="flex items-center gap-4">
                    {{-- Notifications --}}
                    @if($pendingCount > 0)
                    <a href="{{ route('consultant.pending-requests') }}" class="relative text-gray-600 hover:text-brand-gold">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ $pendingCount }}</span>
                    </a>
                    @endif
                    
                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-gray-700 hover:text-brand-dark">
                            <div class="w-9 h-9 bg-gradient-to-br from-brand-gold to-amber-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:inline font-medium">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition
                             class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                            <a href="{{ route('consultant.profile') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user w-4"></i>
                                الملف الشخصي
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 w-full text-right">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            {{-- Page Content --}}
            <main class="p-6">
                @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    {{-- Mobile Sidebar Overlay --}}
    <div x-show="mobileSidebar" 
         @click="mobileSidebar = false"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden">
    </div>
    
    @stack('scripts')
</body>
</html>




