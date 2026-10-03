@extends('layouts.admin')

@section('title', 'إدارة الشعار')
@section('header', 'إدارة الشعار')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Page Header --}}
    <div>
        <h2 class="text-2xl font-bold text-brand-dark">إدارة شعار الموقع</h2>
        <p class="text-brand-textMuted mt-1">رفع وتحديث شعار الموقع والأيقونات</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.logo.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Main Logo --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                <i class="fas fa-image text-brand-gold"></i>
                الشعار الرئيسي (للهيدر)
            </h3>
            
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-brand-border">
                            <img src="{{ site_image('site_logo', 'images/bright-path-logo.png') }}?v={{ time() }}" alt="الشعار" class="max-h-28 max-w-28 object-contain">
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-brand-dark mb-2">رفع شعار جديد</label>
                    <input type="file" name="logo" accept="image/png,image/jpeg,image/webp"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <p class="text-xs text-brand-textMuted mt-2">PNG أو JPG - يفضل خلفية شفافة - الحد الأقصى 2MB</p>
                </div>
            </div>
        </div>

        {{-- White Logo --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                <i class="fas fa-image text-brand-gold"></i>
                الشعار الأبيض (للفوتر والقائمة)
            </h3>
            
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-brand-dark rounded-xl flex items-center justify-center border-2 border-dashed border-brand-border">
                            <img src="{{ site_image('site_logo_white', 'images/logo-white.png') }}?v={{ time() }}" alt="الشعار الأبيض" class="max-h-28 max-w-28 object-contain">
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-brand-dark mb-2">رفع الشعار الأبيض</label>
                    <input type="file" name="logo_white" accept="image/png,image/jpeg,image/webp"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <p class="text-xs text-brand-textMuted mt-2">PNG بخلفية شفافة - يظهر على الخلفيات الداكنة</p>
                </div>
            </div>
        </div>

        {{-- Favicon --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                <i class="fas fa-globe text-brand-gold"></i>
                أيقونة الموقع (Favicon)
            </h3>
            
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-brand-border">
                            <img src="{{ site_image('site_favicon', 'favicon.png') }}?v={{ time() }}" alt="Favicon" class="w-12 h-12 object-contain">
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-brand-dark mb-2">رفع أيقونة الموقع</label>
                    <input type="file" name="favicon" accept="image/png,image/x-icon,image/ico"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <p class="text-xs text-brand-textMuted mt-2">PNG أو ICO - الحجم المثالي 64x64 أو 32x32 بكسل</p>
                </div>
            </div>
        </div>

        {{-- OG Image --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                <i class="fab fa-whatsapp text-green-500"></i>
                صورة مشاركة الواتساب والسوشيال ميديا
            </h3>
            
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-48 h-28 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-brand-border">
                            <img src="{{ site_image('site_og_image', 'images/og-image.jpg') }}?v={{ time() }}" alt="OG Image" class="max-h-24 max-w-44 object-contain">
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-brand-dark mb-2">صورة المشاركة</label>
                    <input type="file" name="og_image" accept="image/png,image/jpeg,image/webp"
                           class="w-full px-4 py-3 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <p class="text-xs text-brand-textMuted mt-2">JPG أو PNG - الحجم المثالي 1200x630 بكسل - تظهر عند مشاركة الموقع</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark px-6 py-3 rounded-lg font-bold hover:bg-brand-goldDeep transition">
                <i class="fas fa-upload"></i>
                <span>رفع وحفظ التغييرات</span>
            </button>
        </div>
    </form>
</div>
@endsection


