@extends('layouts.admin')

@section('title', 'سجلات الأمان')
@section('header', 'سجلات الأمان')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">سجلات الأمان</h2>
            <p class="text-brand-textMuted mt-1">مراقبة الأحداث الأمنية والتنبيهات</p>
        </div>
        <div class="flex gap-3">
            <button onclick="location.reload()" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-DEFAULT text-white rounded-lg hover:bg-brand-dark transition">
                <i class="fas fa-sync-alt"></i>
                <span>تحديث</span>
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">محاولات الدخول اليوم</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">محاولات فاشلة</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">تنبيهات أمنية</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">IP محظورة</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ban text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Logs Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-brand-border overflow-hidden">
        <div class="p-6 border-b border-brand-border">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-bold text-brand-dark">آخر الأحداث الأمنية</h3>
                <div class="flex gap-2">
                    <select class="px-4 py-2 border border-brand-border rounded-lg text-sm focus:ring-2 focus:ring-brand-gold/20">
                        <option value="">جميع الأنواع</option>
                        <option value="login">تسجيل دخول</option>
                        <option value="failed">محاولة فاشلة</option>
                        <option value="blocked">محظور</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">التفاصيل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-brand-textMuted">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shield-alt text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium">لا توجد سجلات أمنية</p>
                                <p class="text-sm">سيتم عرض الأحداث الأمنية هنا عند حدوثها</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-blue-800 mb-2">معلومات مهمة</h4>
                <ul class="text-blue-700 text-sm space-y-1">
                    <li>• يتم تسجيل جميع محاولات الدخول الناجحة والفاشلة</li>
                    <li>• يتم حظر IP تلقائياً بعد 5 محاولات فاشلة متتالية</li>
                    <li>• يتم إرسال تنبيه بالبريد عند اكتشاف نشاط مشبوه</li>
                    <li>• السجلات محفوظة لمدة 90 يوم</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

