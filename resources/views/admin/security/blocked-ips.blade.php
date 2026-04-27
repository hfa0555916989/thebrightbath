@extends('layouts.admin')

@section('title', 'العناوين المحظورة')
@section('header', 'العناوين المحظورة')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-brand-dark">العناوين المحظورة (IP)</h2>
            <p class="text-brand-textMuted mt-1">إدارة عناوين IP المحظورة من الوصول للموقع</p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('addIpModal').classList.remove('hidden')" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-plus"></i>
                <span>حظر IP جديد</span>
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">إجمالي المحظورين</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ban text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">حظر تلقائي</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-robot text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-brand-border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-brand-textMuted text-sm">حظر يدوي</p>
                    <p class="text-3xl font-bold text-brand-dark mt-1">0</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-slash text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Blocked IPs Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-brand-border overflow-hidden">
        <div class="p-6 border-b border-brand-border">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-bold text-brand-dark">قائمة IP المحظورة</h3>
                <input type="text" placeholder="بحث عن IP..." 
                       class="px-4 py-2 border border-brand-border rounded-lg text-sm focus:ring-2 focus:ring-brand-gold/20 w-full sm:w-64">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">السبب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">تاريخ الحظر</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">انتهاء الحظر</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-brand-textMuted uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-brand-textMuted">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-check-circle text-4xl text-green-300 mb-4"></i>
                                <p class="text-lg font-medium">لا توجد عناوين محظورة</p>
                                <p class="text-sm">جميع العناوين مسموح لها بالوصول</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-yellow-800 mb-2">تنبيه مهم</h4>
                <ul class="text-yellow-700 text-sm space-y-1">
                    <li>• تأكد من عدم حظر عنوان IP الخاص بك</li>
                    <li>• الحظر التلقائي يحدث بعد 5 محاولات دخول فاشلة</li>
                    <li>• يمكنك إلغاء الحظر يدوياً في أي وقت</li>
                    <li>• الحظر الدائم لا ينتهي تلقائياً</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Add IP Modal --}}
<div id="addIpModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-brand-border">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-brand-dark">حظر عنوان IP جديد</h3>
                <button onclick="document.getElementById('addIpModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">عنوان IP</label>
                <input type="text" placeholder="192.168.1.1" class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">سبب الحظر</label>
                <textarea rows="3" placeholder="اكتب سبب الحظر..." class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-brand-dark mb-2">مدة الحظر</label>
                <select class="w-full px-4 py-2 border border-brand-border rounded-lg focus:ring-2 focus:ring-brand-gold/20">
                    <option value="1h">ساعة واحدة</option>
                    <option value="24h">24 ساعة</option>
                    <option value="7d">7 أيام</option>
                    <option value="30d">30 يوم</option>
                    <option value="permanent">دائم</option>
                </select>
            </div>
        </div>
        <div class="p-6 border-t border-brand-border flex gap-3 justify-end">
            <button onclick="document.getElementById('addIpModal').classList.add('hidden')" 
                    class="px-4 py-2 border border-brand-border rounded-lg text-brand-textMuted hover:bg-gray-50 transition">
                إلغاء
            </button>
            <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                حظر العنوان
            </button>
        </div>
    </div>
</div>
@endsection

