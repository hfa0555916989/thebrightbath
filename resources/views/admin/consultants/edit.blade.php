@extends('layouts.admin')

@section('title', 'تعديل المستشار')
@section('page-title', 'تعديل بيانات المستشار')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.consultants.update', $consultant) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- معلومات الحساب --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-user text-brand-gold"></i>
                معلومات الحساب
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                    <input type="text" name="name" value="{{ old('name', $consultant->user->name) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                    <input type="email" name="email" value="{{ old('email', $consultant->user->email) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الجوال *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $consultant->user->phone) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold" dir="ltr">
                    @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">صورة المستشار</label>
                    @if($consultant->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $consultant->photo) }}" class="w-20 h-20 rounded-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    <p class="text-xs text-gray-500 mt-1">اتركها فارغة إذا لم ترد التغيير</p>
                    @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation"
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
                    <input type="text" name="specialization" value="{{ old('specialization', $consultant->specialization) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('specialization')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">التخصص (بالعربية) *</label>
                    <input type="text" name="specialization_ar" value="{{ old('specialization_ar', $consultant->specialization_ar) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('specialization_ar')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سنوات الخبرة *</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', $consultant->experience_years) }}" min="0" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    @error('experience_years')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رابط الاجتماع</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link', $consultant->meeting_link) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold" dir="ltr">
                    @error('meeting_link')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نبذة بالعربية</label>
                    <textarea name="bio_ar" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">{{ old('bio_ar', $consultant->bio_ar) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نبذة بالإنجليزية</label>
                    <textarea name="bio" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold" dir="ltr">{{ old('bio', $consultant->bio) }}</textarea>
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
                    <input type="number" name="price_per_30_min" value="{{ old('price_per_30_min', $consultant->price_per_30_min) }}" min="0" step="0.01" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سعر 60 دقيقة (ر.س) *</label>
                    <input type="number" name="price_per_60_min" value="{{ old('price_per_60_min', $consultant->price_per_60_min) }}" min="0" step="0.01" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                </div>
            </div>
        </div>

        {{-- البيانات البنكية --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-university text-green-600"></i>
                البيانات البنكية
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم البنك</label>
                    <input type="text" value="{{ $consultant->bank_name ?? 'غير محدد' }}" disabled
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم صاحب الحساب</label>
                    <input type="text" value="{{ $consultant->bank_account_name ?? 'غير محدد' }}" disabled
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الحساب</label>
                    <input type="text" value="{{ $consultant->bank_account_number ?? 'غير محدد' }}" disabled
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-600" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الآيبان (IBAN)</label>
                    <input type="text" value="{{ $consultant->bank_iban ?? 'غير محدد' }}" disabled
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-600" dir="ltr">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                <i class="fas fa-info-circle ml-1"></i>
                يتم تحديث البيانات البنكية من قبل المستشار نفسه من لوحة تحكمه
            </p>
        </div>

        {{-- نسبة العمولة --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-brand-dark mb-6 flex items-center gap-2">
                <i class="fas fa-percent text-brand-gold"></i>
                نسبة العمولة
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نسبة عمولة الإدارة (%)</label>
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', $consultant->commission_rate ?? 20) }}" min="0" max="100"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/20 focus:border-brand-gold">
                    <p class="text-xs text-gray-500 mt-1">نسبة الخصم من أرباح المستشار</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نسبة المستشار</label>
                    <div class="px-4 py-3 border border-gray-200 rounded-xl bg-green-50 text-green-700 font-bold">
                        {{ 100 - ($consultant->commission_rate ?? 20) }}%
                    </div>
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
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $consultant->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 text-brand-gold rounded focus:ring-brand-gold">
                    <span>تفعيل المستشار</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $consultant->is_featured) ? 'checked' : '' }}
                           class="w-5 h-5 text-brand-gold rounded focus:ring-brand-gold">
                    <span>مستشار مميز</span>
                </label>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-4">
            <button type="submit" class="bg-brand-gold text-brand-dark px-8 py-3 rounded-xl font-medium hover:bg-brand-goldDeep transition">
                <i class="fas fa-save ml-2"></i>
                حفظ التعديلات
            </button>
            <a href="{{ route('admin.consultants.index') }}" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-medium hover:bg-gray-300 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection


