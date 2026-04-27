<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - الطريق المشرق</title>
    <meta name="robots" content="noindex, nofollow">
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#1F3A63',
                            dark: '#162032',
                            gold: '#F8C524',
                            goldDeep: '#E5A91F',
                            orange: '#F28C28',
                            bg: '#F5F7FA',
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
</head>
<body class="font-sans antialiased bg-gradient-to-br from-brand-DEFAULT via-brand-dark to-brand-DEFAULT min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <h1 class="text-3xl font-bold text-white">
                    الطريق <span class="text-brand-gold">المشرق</span>
                </h1>
            </a>
            <p class="text-gray-300 mt-2">للتدريب والتطوير</p>
        </div>
        
        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-brand-dark text-center mb-2">تسجيل الدخول</h2>
            <p class="text-brand-textMuted text-center mb-8">أدخل بياناتك للوصول إلى حسابك</p>
            
            {{-- Success Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    
                    {{-- Show resend verification option --}}
                    @if(session('show_resend'))
                        <div class="mt-4 pt-4 border-t border-red-200">
                            <p class="text-sm mb-2">لم تستلم رابط التفعيل؟</p>
                            <form action="{{ route('verification.resend') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="email" value="{{ session('unverified_email', old('email')) }}">
                                <button type="submit" class="text-brand-DEFAULT font-bold hover:underline">
                                    إعادة إرسال رابط التفعيل
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
            
            {{-- Info message for unverified --}}
            @if (session('error'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-xl mb-6">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-brand-dark font-medium mb-2">
                        البريد الإلكتروني
                    </label>
                    <div class="relative">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                               placeholder="example@email.com">
                    </div>
                </div>
                
                {{-- Password --}}
                <div>
                    <label for="password" class="block text-brand-dark font-medium mb-2">
                        كلمة المرور
                    </label>
                    <div class="relative">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                               class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                               placeholder="••••••••">
                    </div>
                </div>
                
                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-brand-gold rounded focus:ring-brand-gold">
                        <span class="text-brand-textMuted text-sm">تذكرني</span>
                    </label>
                    <a href="{{ route('password.forgot') }}" class="text-brand-DEFAULT text-sm hover:text-brand-gold transition">
                        نسيت كلمة المرور؟
                    </a>
                </div>
                
                {{-- Submit Button --}}
                <button type="submit" 
                        class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg">
                    <i class="fas fa-sign-in-alt ml-2"></i>
                    تسجيل الدخول
                </button>
            </form>
            
            {{-- Divider --}}
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-brand-textMuted text-sm">أو</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
            
            {{-- Register Link --}}
            <p class="text-center text-brand-textMuted">
                ليس لديك حساب؟
                <a href="{{ route('register') }}" class="text-brand-DEFAULT font-bold hover:text-brand-gold">
                    سجل الآن
                </a>
            </p>
        </div>
        
        {{-- Back to Home --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
    
</body>
</html>


