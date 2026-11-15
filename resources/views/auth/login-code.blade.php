@extends('auth.layout')

@section('title', 'الدخول برمز البريد')

@section('content')
    <h2 class="text-2xl font-bold text-brand-dark text-center mb-2">الدخول برمز البريد</h2>
    <p class="text-brand-textMuted text-center mb-8">أدخل بريدك وسنرسل لك رمزًا من {{ \App\Services\Auth\LoginCodeService::LENGTH }} أرقام. لا تحتاج كلمة مرور، وإن لم يكن لديك حساب ننشئه لك.</p>

    <form method="POST" action="{{ route('login.code') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-brand-dark font-medium mb-2">البريد الإلكتروني</label>
            <div class="relative">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-textMuted">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       class="w-full pr-12 pl-4 py-3 rounded-xl border border-gray-200 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                       placeholder="example@email.com">
            </div>
        </div>

        <x-turnstile />

        <button type="submit"
                class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg">
            <i class="fas fa-paper-plane ml-2"></i>
            أرسل لي الرمز
        </button>
    </form>
@endsection
