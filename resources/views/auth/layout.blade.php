<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - الطريق المشرق</title>
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
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <h1 class="text-3xl font-bold text-white">
                    الطريق <span class="text-brand-gold">المشرق</span>
                </h1>
            </a>
            <p class="text-gray-300 mt-2">للتدريب والتطوير</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-xl mb-6">
                    <p>{{ session('error') }}</p>
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

            @yield('content')
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة لتسجيل الدخول
            </a>
        </div>
    </div>

</body>
</html>
