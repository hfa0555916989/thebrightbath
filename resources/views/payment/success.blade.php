@extends('layouts.public')

@section('title', 'تمت عملية الدفع بنجاح')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center py-12" dir="rtl">
    <div class="max-w-md w-full mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            
            <!-- Success Animation -->
            <div class="relative mb-6">
                <div class="w-24 h-24 bg-green-100 rounded-full mx-auto flex items-center justify-center animate-pulse">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-400 rounded-full animate-ping"></div>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">تمت عملية الدفع بنجاح!</h1>
            <p class="text-gray-600 mb-6">شكراً لك، تم استلام الدفعة بنجاح وتأكيد حجزك.</p>

            @if(isset($transaction))
            <div class="bg-gray-50 rounded-xl p-4 mb-6 text-right">
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">رقم العملية</span>
                    <span class="font-mono text-sm text-gray-900">{{ $transaction->transaction_id }}</span>
                </div>
                <div class="flex justify-between py-2 border-t border-gray-100">
                    <span class="text-gray-500">المبلغ</span>
                    <span class="font-semibold text-gray-900">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</span>
                </div>
                <div class="flex justify-between py-2 border-t border-gray-100">
                    <span class="text-gray-500">التاريخ</span>
                    <span class="text-gray-900">{{ $transaction->paid_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i') }}</span>
                </div>
            </div>
            @endif

            <div class="space-y-3">
                <a href="{{ url('/bookings') }}" 
                   class="block w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition">
                    عرض حجوزاتي
                </a>
                <a href="{{ url('/') }}" 
                   class="block w-full border border-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-50 transition">
                    العودة للرئيسية
                </a>
            </div>

            <!-- Email Notification -->
            <p class="mt-6 text-sm text-gray-500">
                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                تم إرسال تأكيد إلى بريدك الإلكتروني
            </p>
        </div>
    </div>
</div>
@endsection
