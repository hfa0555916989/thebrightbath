<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نسيت كلمة المرور - الطريق المشرق</title>
    <meta name="robots" content="noindex, nofollow">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
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
        
        {{-- Forgot Password Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-brand-gold/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-brand-gold text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-brand-dark">نسيت كلمة المرور؟</h2>
                <p class="text-gray-500 mt-2">أدخل بريدك الإلكتروني وسنرسل لك رابط استعادة كلمة المرور</p>
            </div>
            
            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            {{-- Error Message --}}
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-brand-dark font-medium mb-2">
                        البريد الإلكتروني
                    </label>
                    <div class="relative">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                               placeholder="example@email.com">
                    </div>
                </div>
                
                {{-- Submit Button --}}
                <button type="submit" 
                        class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg">
                    <i class="fas fa-paper-plane ml-2"></i>
                    إرسال رابط الاستعادة
                </button>
            </form>
            
            {{-- Back to Login --}}
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-brand-DEFAULT hover:text-brand-gold transition">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة لتسجيل الدخول
                </a>
            </div>
        </div>
        
        {{-- Back to Home --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition">
                <i class="fas fa-home ml-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
    
</body>
</html>



