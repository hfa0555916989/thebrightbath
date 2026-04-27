<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - الطريق المشرق</title>
    <meta name="robots" content="noindex, nofollow">
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Google reCAPTCHA --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
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
<body class="font-sans antialiased min-h-screen flex">
    
    {{-- Left Side - Image --}}
    <div class="hidden lg:flex lg:w-1/2 relative">
        <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             alt="مستقبل مهني مشرق" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-l from-brand-dark/90 to-brand-dark/50"></div>
        
        {{-- Content Over Image --}}
        <div class="absolute inset-0 flex flex-col justify-center p-12 text-white">
            <h2 class="text-4xl font-bold mb-6">انضم إلى مجتمع الطريق المشرق</h2>
            <p class="text-xl text-gray-200 mb-8 leading-relaxed">
                اكتشف ميولك المهنية واحصل على إرشاد متخصص يساعدك في بناء مستقبلك الوظيفي
            </p>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-brand-gold text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold">اختبارات علمية معتمدة</h3>
                        <p class="text-gray-300 text-sm">اكتشف شخصيتك المهنية بدقة</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-pdf text-brand-gold text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold">تقارير مفصلة</h3>
                        <p class="text-gray-300 text-sm">احصل على تحليل شامل لنتائجك</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-brand-gold/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tie text-brand-gold text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold">إرشاد متخصص</h3>
                        <p class="text-gray-300 text-sm">مستشارون خبراء لمساعدتك</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Right Side - Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-brand-bg">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <h1 class="text-3xl font-bold text-brand-dark">
                        الطريق <span class="text-brand-gold">المشرق</span>
                    </h1>
                </a>
                <p class="text-brand-textMuted mt-2">للتدريب والتطوير</p>
            </div>
            
            {{-- Register Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-brand-dark text-center mb-2">إنشاء حساب جديد</h2>
                <p class="text-brand-textMuted text-center mb-8">أدخل بياناتك لإنشاء حسابك</p>
                
                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-brand-dark font-medium mb-2">
                            الاسم الكامل <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                   placeholder="أدخل اسمك الكامل">
                        </div>
                    </div>
                    
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-brand-dark font-medium mb-2">
                            البريد الإلكتروني <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                   placeholder="example@email.com">
                        </div>
                    </div>
                    
                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-brand-dark font-medium mb-2">
                            رقم الجوال <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                   class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                   placeholder="05XXXXXXXX" dir="ltr">
                        </div>
                    </div>
                    
                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-brand-dark font-medium mb-2">
                            كلمة المرور <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" required
                                   class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                   placeholder="••••••••">
                        </div>
                        <p class="text-xs text-brand-textMuted mt-1">يجب أن تحتوي على 8 أحرف على الأقل</p>
                    </div>
                    
                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-brand-dark font-medium mb-2">
                            تأكيد كلمة المرور <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                                   placeholder="••••••••">
                        </div>
                    </div>
                    
                    {{-- Terms --}}
                    <div class="flex items-start gap-3">
                        <input type="checkbox" name="terms" id="terms" required
                               class="w-5 h-5 mt-0.5 text-brand-gold rounded focus:ring-brand-gold">
                        <label for="terms" class="text-sm text-brand-textMuted">
                            أوافق على 
                            <a href="{{ route('terms') }}" target="_blank" class="text-brand-DEFAULT hover:text-brand-gold underline">الشروط والأحكام</a>
                            و
                            <a href="{{ route('privacy') }}" target="_blank" class="text-brand-DEFAULT hover:text-brand-gold underline">سياسة الخصوصية</a>
                        </label>
                    </div>
                    
                    {{-- Quick Links to View Terms --}}
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 mb-2">يرجى قراءة الشروط والأحكام قبل الموافقة</p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('terms') }}" target="_blank" class="text-xs text-brand-DEFAULT hover:text-brand-gold flex items-center gap-1">
                                <i class="fas fa-file-contract"></i>
                                <span>الشروط والأحكام</span>
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                            <a href="{{ route('privacy') }}" target="_blank" class="text-xs text-brand-DEFAULT hover:text-brand-gold flex items-center gap-1">
                                <i class="fas fa-shield-alt"></i>
                                <span>سياسة الخصوصية</span>
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Google reCAPTCHA --}}
                    <div class="flex justify-center">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}"></div>
                    </div>
                    @error('g-recaptcha-response')
                        <p class="text-red-500 text-sm text-center">{{ $message }}</p>
                    @enderror
                    
                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg">
                        <i class="fas fa-user-plus ml-2"></i>
                        إنشاء الحساب
                    </button>
                </form>
                
                {{-- Divider --}}
                <div class="flex items-center gap-4 my-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-brand-textMuted text-sm">أو</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>
                
                {{-- Login Link --}}
                <p class="text-center text-brand-textMuted">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="text-brand-DEFAULT font-bold hover:text-brand-gold">
                        سجل دخولك
                    </a>
                </p>
            </div>
            
            {{-- Back to Home --}}
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-brand-textMuted hover:text-brand-DEFAULT transition">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للرئيسية
                </a>
            </div>
        </div>
    </div>
    
</body>
</html>


