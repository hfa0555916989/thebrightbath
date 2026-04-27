@extends('layouts.admin')

@section('title', 'إضافة مستشار جديد')
@section('page-title', 'إضافة مستشار جديد')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.consultants.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- معلومات الحساب --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-user text-brand-gold"></i>
                معلومات الحساب
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الجوال *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold" dir="ltr">
                    @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">صورة المستشار</label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور *</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                </div>
            </div>
        </div>

        {{-- معلومات التخصص --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-briefcase text-brand-gold"></i>
                معلومات التخصص
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">التخصص (بالإنجليزية) *</label>
                    <input type="text" name="specialization" value="{{ old('specialization') }}" required placeholder="Career Counseling"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('specialization')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">التخصص (بالعربية) *</label>
                    <input type="text" name="specialization_ar" value="{{ old('specialization_ar') }}" required placeholder="الإرشاد المهني"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('specialization_ar')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سنوات الخبرة *</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', 0) }}" min="0" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('experience_years')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رابط الاجتماع (Zoom/Meet)</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link') }}" placeholder="https://zoom.us/j/..."
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold" dir="ltr">
                    @error('meeting_link')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نبذة عن المستشار (بالعربية)</label>
                    <textarea name="bio_ar" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">{{ old('bio_ar') }}</textarea>
                    @error('bio_ar')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نبذة عن المستشار (بالإنجليزية)</label>
                    <textarea name="bio" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold" dir="ltr">{{ old('bio') }}</textarea>
                    @error('bio')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- الأسعار --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-brand-gold"></i>
                الأسعار
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سعر 30 دقيقة (ر.س) *</label>
                    <input type="number" name="price_per_30_min" value="{{ old('price_per_30_min', 115) }}" min="0" step="0.01" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('price_per_30_min')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سعر 60 دقيقة (ر.س) *</label>
                    <input type="number" name="price_per_60_min" value="{{ old('price_per_60_min', 200) }}" min="0" step="0.01" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('price_per_60_min')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- الإعدادات --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-cog text-brand-gold"></i>
                الإعدادات
            </h3>
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-brand-gold rounded focus:ring-brand-gold">
                    <span>تفعيل المستشار</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="w-5 h-5 text-brand-gold rounded focus:ring-brand-gold">
                    <span>مستشار مميز</span>
                </label>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-4">
            <button type="submit" class="bg-brand-gold text-brand-dark px-8 py-3 rounded-xl font-medium hover:bg-brand-goldDeep transition">
                <i class="fas fa-save ml-2"></i>
                حفظ المستشار
            </button>
            <a href="{{ route('admin.consultants.index') }}" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-medium hover:bg-gray-300 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection




