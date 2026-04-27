@extends('layouts.public')

@section('title', 'بانتظار موافقة المستشار')

@push('analytics')
<script>
    // تتبع صفحة انتظار الموافقة
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': 'انتظار موافقة المستشار',
                'page_location': window.location.href,
                'custom_page_name': 'بانتظار موافقة المستشار - حجز رقم {{ $booking->booking_number ?? "" }}'
            });
            
            // حدث مخصص لمرحلة انتظار الموافقة
            gtag('event', 'booking_pending_approval', {
                'booking_number': '{{ $booking->booking_number ?? "" }}',
                'consultant_name': '{{ $booking->consultant->user->name ?? "" }}'
            });
        }
    });
</script>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-brand-bg to-white py-12 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Status Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-amber-400 to-amber-500 px-6 py-8 text-center">
                <div class="w-20 h-20 bg-white/30 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-white text-3xl animate-pulse"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">بانتظار موافقة المستشار</h1>
                <p class="text-white/90 mt-2">تم إرسال طلب الحجز للمستشار</p>
            </div>
            
            {{-- Booking Details --}}
            <div class="p-6 sm:p-8">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-amber-500 mt-1"></i>
                        <div>
                            <p class="text-amber-800 font-medium">طلبك قيد المراجعة</p>
                            <p class="text-amber-700 text-sm mt-1">
                                سيقوم المستشار بمراجعة طلبك والموافقة عليه. سيتم إشعارك عبر البريد الإلكتروني فور الموافقة لإتمام الدفع.
                            </p>
                        </div>
                    </div>
                </div>
                
                <h2 class="text-lg font-bold text-gray-800 mb-4">تفاصيل الحجز</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">المستشار</span>
                        <span class="font-semibold text-gray-800">{{ $booking->consultant->name }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">التاريخ</span>
                        <span class="font-semibold text-gray-800">{{ $booking->booking_date->format('Y-m-d') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">الوقت</span>
                        <span class="font-semibold text-gray-800" dir="ltr">
                            {{ date('h:i A', strtotime($booking->start_time)) }} - {{ date('h:i A', strtotime($booking->end_time)) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">المدة</span>
                        <span class="font-semibold text-gray-800">{{ $booking->duration_minutes }} دقيقة</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-brand-gold/10 rounded-lg">
                        <span class="text-gray-600">المبلغ</span>
                        <span class="font-bold text-brand-gold text-lg">{{ number_format($booking->price, 2) }} ر.س</span>
                    </div>
                </div>
                
                <div class="mt-8 border-t pt-6">
                    <h3 class="font-semibold text-gray-700 mb-3">ماذا بعد؟</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-brand-gold/20 text-brand-gold rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <span>سيراجع المستشار طلبك</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-brand-gold/20 text-brand-gold rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <span>ستتلقى إشعاراً على بريدك الإلكتروني بالموافقة أو الرفض</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 bg-brand-gold/20 text-brand-gold rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <span>في حالة الموافقة، يمكنك إتمام الدفع لتأكيد الحجز</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('client.dashboard') }}" 
                       class="flex-1 text-center px-6 py-3 bg-brand-DEFAULT text-white rounded-xl font-semibold hover:bg-brand-dark transition">
                        <i class="fas fa-tachometer-alt ml-2"></i>
                        لوحة التحكم
                    </a>
                    <a href="{{ route('consultations.index') }}" 
                       class="flex-1 text-center px-6 py-3 border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:border-brand-gold hover:text-brand-gold transition">
                        <i class="fas fa-users ml-2"></i>
                        تصفح المستشارين
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Booking Number --}}
        <div class="text-center mt-6">
            <p class="text-gray-500 text-sm">
                رقم الحجز: <span class="font-mono font-semibold text-gray-700">{{ $booking->booking_number }}</span>
            </p>
        </div>
    </div>
</div>
@endsection





