@extends('layouts.public')

@section('title', 'جاري معالجة الدفع')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-amber-50 to-orange-50 flex items-center justify-center py-12" dir="rtl">
    <div class="max-w-md w-full mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            
            <!-- Loading Animation -->
            <div class="w-24 h-24 bg-yellow-100 rounded-full mx-auto flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-yellow-600 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">جاري معالجة الدفع</h1>
            <p class="text-gray-600 mb-6">
                يتم التحقق من عملية الدفع. قد يستغرق ذلك بضع ثوانٍ.
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    سيتم تحديث الصفحة تلقائياً أو ستصلك رسالة تأكيد على بريدك الإلكتروني.
                </p>
            </div>

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
        </div>
    </div>
</div>

<script>
// Auto-refresh after 10 seconds
setTimeout(function() {
    location.reload();
}, 10000);
</script>
@endsection
