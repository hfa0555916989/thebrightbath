@extends('layouts.public')

@section('title', 'فشلت عملية الدفع')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-rose-50 to-pink-50 flex items-center justify-center py-12" dir="rtl">
    <div class="max-w-md w-full mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            
            <!-- Error Icon -->
            <div class="w-24 h-24 bg-red-100 rounded-full mx-auto flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">فشلت عملية الدفع</h1>
            <p class="text-gray-600 mb-6">
                عذراً، لم نتمكن من إتمام عملية الدفع. 
                @if(request('error'))
                <br><span class="text-red-600 text-sm">{{ request('error') }}</span>
                @endif
            </p>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 text-right">
                <h3 class="font-medium text-amber-800 mb-2">أسباب محتملة:</h3>
                <ul class="text-sm text-amber-700 space-y-1">
                    <li>• رصيد غير كافٍ في البطاقة</li>
                    <li>• بيانات البطاقة غير صحيحة</li>
                    <li>• البطاقة منتهية الصلاحية</li>
                    <li>• رفض من البنك المصدر</li>
                </ul>
            </div>

            <div class="space-y-3">
                <a href="javascript:history.back()" 
                   class="block w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition">
                    حاول مرة أخرى
                </a>
                <a href="{{ url('/contact') }}" 
                   class="block w-full border border-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-50 transition">
                    تواصل معنا للمساعدة
                </a>
                <a href="{{ url('/') }}" 
                   class="block w-full text-gray-500 py-2 hover:text-gray-700 transition">
                    العودة للرئيسية
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
