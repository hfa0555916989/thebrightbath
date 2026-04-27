@extends('layouts.public')

@section('title', 'تم تأكيد الحجز')

@push('analytics')
<script>
    // إرسال حدث إتمام الشراء (purchase) إلى Google Analytics
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'purchase', {
                'transaction_id': '{{ $booking->booking_number }}',
                'currency': 'SAR',
                'value': {{ $booking->price }},
                'items': [{
                    'item_id': '{{ $booking->booking_number }}',
                    'item_name': 'استشارة مع {{ $booking->consultant->user->name }}',
                    'item_category': 'استشارات',
                    'item_category2': '{{ $booking->consultant->specialization_ar ?? "استشارة" }}',
                    'price': {{ $booking->price }},
                    'quantity': 1
                }]
            });
            
            // إرسال حدث إتمام الشراء المخصص
            gtag('event', 'page_view', {
                'page_title': 'إتمام الشراء',
                'page_location': window.location.href,
                'custom_page_name': 'إتمام الشراء - حجز رقم {{ $booking->booking_number }}'
            });
            
            // حدث تحويل مخصص
            gtag('event', 'conversion', {
                'send_to': 'G-K7ZEBV8Z8D',
                'value': {{ $booking->price }},
                'currency': 'SAR',
                'transaction_id': '{{ $booking->booking_number }}'
            });
        }
    });
</script>
@endpush

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden text-center">
                {{-- Success Icon --}}
                <div class="bg-gradient-to-l from-green-500 to-green-600 py-12">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-5xl text-green-500"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">تم تأكيد حجزك!</h1>
                    <p class="text-green-100">رقم الحجز: {{ $booking->booking_number }}</p>
                </div>

                <div class="p-8">
                    {{-- Booking Details --}}
                    <div class="bg-gray-50 rounded-xl p-6 mb-8 text-right">
                        <h3 class="font-bold text-brand-dark mb-4 text-center">تفاصيل الجلسة</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-gray-500">المستشار</span>
                                <span class="font-medium">{{ $booking->consultant->user->name }}</span>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-gray-500">التخصص</span>
                                <span class="font-medium">{{ $booking->consultant->specialization_ar }}</span>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-gray-500">التاريخ</span>
                                <span class="font-medium">{{ $booking->booking_date->format('l, d F Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-gray-500">الوقت</span>
                                <span class="font-medium">{{ date('h:i A', strtotime($booking->start_time)) }} - {{ date('h:i A', strtotime($booking->end_time)) }}</span>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-gray-500">المدة</span>
                                <span class="font-medium">{{ $booking->duration_minutes }} دقيقة</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">المبلغ المدفوع</span>
                                <span class="font-bold text-brand-gold text-xl">{{ number_format($booking->price) }} ر.س</span>
                            </div>
                        </div>
                    </div>

                    {{-- Meeting Link --}}
                    @if($booking->status === 'confirmed')
                    <div class="bg-brand-gold/10 rounded-xl p-6 mb-8">
                        <h3 class="font-bold text-brand-dark mb-3">
                            <i class="fas fa-video text-brand-gold ml-2"></i>
                            غرفة الاجتماع
                        </h3>
                        <a href="{{ route('video-call.join', $booking) }}" 
                           class="block bg-brand-gold text-brand-dark py-3 px-6 rounded-xl font-bold hover:bg-brand-goldDeep transition text-center">
                            <i class="fas fa-video ml-2"></i>
                            انضم للجلسة الآن
                        </a>
                        <p class="text-sm text-gray-500 mt-3">
                            <i class="fas fa-shield-alt text-green-500 ml-1"></i>
                            جلسة مشفرة وآمنة - مستضافة على سيرفرك
                        </p>
                    </div>
                    @endif

                    {{-- Next Steps --}}
                    <div class="text-right mb-8">
                        <h3 class="font-bold text-brand-dark mb-4">الخطوات التالية:</h3>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-center gap-3">
                                <span class="w-6 h-6 bg-brand-gold/20 rounded-full flex items-center justify-center text-sm font-bold text-brand-gold">1</span>
                                تم إرسال تأكيد الحجز إلى بريدك الإلكتروني
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-6 h-6 bg-brand-gold/20 rounded-full flex items-center justify-center text-sm font-bold text-brand-gold">2</span>
                                سيتم تذكيرك قبل موعد الجلسة
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-6 h-6 bg-brand-gold/20 rounded-full flex items-center justify-center text-sm font-bold text-brand-gold">3</span>
                                انضم للجلسة في الموعد المحدد عبر الرابط أعلاه
                            </li>
                        </ul>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('consultations.my-bookings') }}" 
                           class="flex-1 bg-brand-DEFAULT text-white py-3 rounded-xl font-bold hover:bg-brand-dark transition text-center">
                            <i class="fas fa-list ml-2"></i>
                            حجوزاتي
                        </a>
                        <a href="{{ route('consultations.index') }}" 
                           class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold hover:bg-gray-300 transition text-center">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة للمستشارين
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection




