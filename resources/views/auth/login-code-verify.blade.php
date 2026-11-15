@extends('auth.layout')

@section('title', 'أدخل رمز الدخول')

@section('content')
    <h2 class="text-2xl font-bold text-brand-dark text-center mb-2">أدخل رمز الدخول</h2>
    <p class="text-brand-textMuted text-center mb-8">
        أرسلنا الرمز إلى <span class="font-bold text-brand-dark" dir="ltr">{{ $email }}</span>.
        إن لم تجده، تحقق من مجلد الرسائل غير المرغوب فيها.
    </p>

    <form method="POST" action="{{ route('login.code.verify') }}" class="space-y-6">
        @csrf

        <div>
            <label for="code" class="block text-brand-dark font-medium mb-2">الرمز</label>
            <input type="text" id="code" name="code" required autofocus
                   inputmode="numeric" autocomplete="one-time-code" pattern="[0-9 ]*"
                   maxlength="{{ \App\Services\Auth\LoginCodeService::LENGTH + 2 }}" dir="ltr"
                   class="w-full px-4 py-4 rounded-xl border border-gray-200 text-center text-3xl font-bold tracking-[0.5em] focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 transition"
                   placeholder="••••••">
        </div>

        <button type="submit"
                class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition shadow-lg">
            <i class="fas fa-sign-in-alt ml-2"></i>
            تسجيل الدخول
        </button>
    </form>

    <p class="text-center text-brand-textMuted mt-6 text-sm">
        لم يصلك الرمز أو انتهت صلاحيته؟
        <a href="{{ route('login.code') }}" class="text-brand-DEFAULT font-bold hover:text-brand-gold">اطلب رمزًا جديدًا</a>
    </p>
@endsection
