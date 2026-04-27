@extends('layouts.admin')

@section('title', 'الملف الشخصي')
@section('header', 'الملف الشخصي')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Page Header --}}
    <div>
        <h2 class="text-2xl font-bold text-brand-dark">الملف الشخصي</h2>
        <p class="text-brand-textMuted mt-1">إدارة معلومات حسابك وتحديث بياناتك</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile Form --}}
    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-6">معلومات الحساب</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">الاسم</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" required
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-6">تغيير كلمة المرور</h3>
            <p class="text-brand-textMuted text-sm mb-4">اتركها فارغة إذا لم ترغب في تغييرها</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">كلمة المرور الجديدة</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20"
                           placeholder="••••••••">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-brand-dark mb-2">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20"
                           placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-6">معلومات الحساب</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-brand-textMuted mb-1">نوع الحساب</p>
                    <p class="font-bold text-brand-dark">
                        @if(auth()->user()->role === 'admin')
                            <span class="text-brand-gold">مدير النظام</span>
                        @elseif(auth()->user()->role === 'counselor')
                            <span class="text-green-600">مستشار</span>
                        @else
                            <span>عميل</span>
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-brand-textMuted mb-1">تاريخ التسجيل</p>
                    <p class="font-bold text-brand-dark">{{ auth()->user()->created_at->format('Y/m/d') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-brand-textMuted mb-1">آخر تحديث</p>
                    <p class="font-bold text-brand-dark">{{ auth()->user()->updated_at->format('Y/m/d') }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                <i class="fas fa-save"></i>
                <span>حفظ التغييرات</span>
            </button>
        </div>
    </form>
</div>
@endsection

