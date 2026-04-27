@extends('layouts.public')

@section('title', 'إتمام الدفع')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-12" dir="rtl">
    <div class="max-w-4xl mx-auto px-4">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">إتمام عملية الدفع</h1>
            <p class="mt-2 text-gray-600">أدخل بيانات البطاقة لإكمال الحجز</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-4">ملخص الطلب</h3>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-600">نوع الخدمة</span>
                <span class="font-medium text-gray-900">{{ $booking->service_type ?? 'استشارة' }}</span>
            </div>
            @if($booking->consultant)
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-600">المستشار</span>
                <span class="font-medium text-gray-900">{{ $booking->consultant->user->name ?? 'المستشار' }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-600">التاريخ</span>
                <span class="font-medium text-gray-900">{{ $booking->scheduled_date?->format('Y-m-d') ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-4">
                <span class="text-lg font-semibold text-gray-900">المبلغ الإجمالي</span>
                <span class="text-2xl font-bold text-indigo-600">{{ number_format($amount, 2) }} {{ $currency }}</span>
            </div>
        </div>

        <!-- Payment Options -->
        @if(str_contains($iframeUrl, 'unifiedcheckout'))
            <!-- V2 Unified Checkout - Redirect -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">جاهز للدفع الآمن</h3>
                <p class="text-gray-600 mb-6">سيتم توجيهك إلى صفحة الدفع الآمنة لإتمام العملية</p>
                
                <a href="{{ $iframeUrl }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    الدفع الآن
                </a>

                <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 ml-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        MADA
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 ml-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Visa / Mastercard
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 ml-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Apple Pay
                    </span>
                </div>
            </div>
        @else
            <!-- Legacy iframe payment -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <iframe 
                    src="{{ $iframeUrl }}" 
                    style="border: none; width: 100%; min-height: 500px;"
                    allowpaymentrequest
                    sandbox="allow-forms allow-scripts allow-same-origin allow-top-navigation">
                </iframe>
            </div>
        @endif

        <!-- Security Note -->
        <div class="mt-6 flex items-center justify-center text-sm text-gray-500">
            <svg class="w-5 h-5 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <span>عملية الدفع مؤمنة ومشفرة بالكامل عبر Paymob KSA</span>
        </div>
    </div>
</div>
@endsection
