@extends('layouts.admin')

@section('title', 'سجل النشاطات')
@section('header', 'سجل النشاطات')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">سجل النشاطات</h2>
            <p class="text-brand-textMuted mt-1">متابعة جميع الإجراءات في لوحة التحكم</p>
        </div>
        <div class="flex gap-3">
            <button onclick="location.reload()" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-DEFAULT text-white rounded-lg hover:bg-brand-dark transition">
                <i class="fas fa-sync-alt"></i>
                <span>تحديث</span>
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">المستخدم</label>
                <select class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <option value="">جميع المستخدمين</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">نوع الإجراء</label>
                <select class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <option value="">جميع الإجراءات</option>
                    <option value="create">إنشاء</option>
                    <option value="update">تعديل</option>
                    <option value="delete">حذف</option>
                    <option value="login">تسجيل دخول</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">من تاريخ</label>
                <input type="date" class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">إلى تاريخ</label>
                <input type="date" class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
            </div>
        </div>
    </div>

    {{-- Activity Timeline --}}
    <div class="bg-white rounded-xl shadow-sm border border-brand-border overflow-hidden">
        <div class="p-6 border-b border-brand-border">
            <h3 class="text-lg font-bold text-brand-dark">آخر النشاطات</h3>
        </div>
        
        <div class="p-6">
            <div class="flex flex-col items-center py-12 text-brand-textMuted">
                <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                <p class="text-lg font-medium">لا توجد نشاطات مسجلة</p>
                <p class="text-sm">سيتم عرض النشاطات هنا عند إجراء أي عملية</p>
            </div>
        </div>
    </div>

    {{-- Activity Types Legend --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
        <h4 class="font-bold text-brand-dark mb-4">أنواع النشاطات</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                <span class="text-sm text-brand-textMuted">إنشاء</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                <span class="text-sm text-brand-textMuted">تعديل</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                <span class="text-sm text-brand-textMuted">حذف</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                <span class="text-sm text-brand-textMuted">تسجيل دخول</span>
            </div>
        </div>
    </div>
</div>
@endsection

