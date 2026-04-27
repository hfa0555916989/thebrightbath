@extends('layouts.public')

@section('title', 'الملف الشخصي')

@section('content')
<section class="pt-28 pb-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-brand-dark">الملف الشخصي</h1>
                <a href="{{ route('client.dashboard') }}" class="text-brand-gold hover:underline">
                    <i class="fas fa-arrow-right ml-1"></i> العودة
                </a>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-8">
                <form action="{{ route('client.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الجوال</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold" dir="ltr">
                            @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <hr class="my-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة (اختياري)</label>
                            <input type="password" name="password"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                            <p class="text-xs text-gray-500 mt-1">اتركها فارغة إذا لم ترد تغيير كلمة المرور</p>
                            @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                        </div>

                        <button type="submit" class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                            <i class="fas fa-save ml-2"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>

            {{-- Account Info --}}
            <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                <h3 class="font-bold text-brand-dark mb-4">معلومات الحساب</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">تاريخ التسجيل:</span>
                        <span>{{ $user->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">نوع الحساب:</span>
                        <span>{{ $user->role === 'client' ? 'عميل' : ($user->role === 'counselor' ? 'مستشار' : 'مدير') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection




