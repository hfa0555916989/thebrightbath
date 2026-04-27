@extends('layouts.consultant')

@section('title', 'الملف الشخصي')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">👤 الملف الشخصي</h1>
            <p class="text-gray-500 mt-1">تحديث معلوماتك الشخصية والبنكية</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('consultant.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Personal Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-900">📋 المعلومات الشخصية</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ old('name', $consultant->name) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" value="{{ $consultant->user->email }}" disabled
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">التخصص</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $consultant->specialization) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الجوال</label>
                        <input type="tel" name="phone" value="{{ old('phone', $consultant->phone) }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500" dir="ltr">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">نبذة عنك</label>
                        <textarea name="bio" rows="4"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('bio', $consultant->bio) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Bank Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-l from-green-50 to-green-100">
                    <h2 class="text-lg font-bold text-gray-900">🏦 معلومات الحساب البنكي</h2>
                    <p class="text-sm text-gray-500 mt-1">لتحويل أرباحك من الجلسات الاستشارية</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم البنك</label>
                        <select name="bank_name" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            <option value="">-- اختر البنك --</option>
                            <option value="الراجحي" {{ old('bank_name', $consultant->bank_name) == 'الراجحي' ? 'selected' : '' }}>مصرف الراجحي</option>
                            <option value="الأهلي" {{ old('bank_name', $consultant->bank_name) == 'الأهلي' ? 'selected' : '' }}>البنك الأهلي السعودي</option>
                            <option value="الإنماء" {{ old('bank_name', $consultant->bank_name) == 'الإنماء' ? 'selected' : '' }}>مصرف الإنماء</option>
                            <option value="البلاد" {{ old('bank_name', $consultant->bank_name) == 'البلاد' ? 'selected' : '' }}>بنك البلاد</option>
                            <option value="الرياض" {{ old('bank_name', $consultant->bank_name) == 'الرياض' ? 'selected' : '' }}>بنك الرياض</option>
                            <option value="ساب" {{ old('bank_name', $consultant->bank_name) == 'ساب' ? 'selected' : '' }}>بنك ساب</option>
                            <option value="العربي" {{ old('bank_name', $consultant->bank_name) == 'العربي' ? 'selected' : '' }}>البنك العربي الوطني</option>
                            <option value="الجزيرة" {{ old('bank_name', $consultant->bank_name) == 'الجزيرة' ? 'selected' : '' }}>بنك الجزيرة</option>
                            <option value="السعودي الفرنسي" {{ old('bank_name', $consultant->bank_name) == 'السعودي الفرنسي' ? 'selected' : '' }}>البنك السعودي الفرنسي</option>
                            <option value="الأول" {{ old('bank_name', $consultant->bank_name) == 'الأول' ? 'selected' : '' }}>البنك الأول</option>
                            <option value="stc pay" {{ old('bank_name', $consultant->bank_name) == 'stc pay' ? 'selected' : '' }}>STC Pay</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">اسم صاحب الحساب</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $consultant->bank_account_name) }}"
                               placeholder="كما هو مسجل في البنك"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الحساب</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $consultant->bank_account_number) }}"
                               placeholder="رقم الحساب البنكي"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الآيبان (IBAN)</label>
                        <input type="text" name="bank_iban" value="{{ old('bank_iban', $consultant->bank_iban) }}"
                               placeholder="SA00 0000 0000 0000 0000 0000"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500" dir="ltr">
                        <p class="mt-1 text-xs text-gray-500">يبدأ بـ SA ويتكون من 24 حرف ورقم</p>
                    </div>
                </div>
            </div>

            {{-- Session Settings --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-900">⚙️ إعدادات الجلسات</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رابط الاجتماع (Zoom/Meet)</label>
                        <input type="url" name="meeting_link" value="{{ old('meeting_link', $consultant->meeting_link) }}"
                               placeholder="https://zoom.us/j/..."
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring-primary-500" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نسبتك من الجلسة</label>
                        <div class="px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-600">
                            {{ 100 - ($consultant->commission_rate ?? 20) }}% (يحددها الإدارة)
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition shadow-lg">
                    💾 حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



