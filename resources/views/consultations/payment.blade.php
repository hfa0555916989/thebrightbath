@extends('layouts.public')

@section('title', 'إتمام الدفع')

@push('analytics')
<script>
    // إرسال حدث بدء عملية الدفع (begin_checkout) إلى Google Analytics
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'begin_checkout', {
                'currency': 'SAR',
                'value': {{ $booking->price }},
                'items': [{
                    'item_id': '{{ $booking->booking_number }}',
                    'item_name': 'استشارة مع {{ $booking->consultant->user->name }}',
                    'item_category': 'استشارات',
                    'price': {{ $booking->price }},
                    'quantity': 1
                }]
            });
            
            // إرسال حدث مخصص لصفحة الدفع
            gtag('event', 'page_view', {
                'page_title': 'صفحة الدفع',
                'page_location': window.location.href,
                'custom_page_name': 'صفحة الدفع - حجز رقم {{ $booking->booking_number }}'
            });
        }
    });
</script>
@endpush

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-l from-brand-DEFAULT to-brand-dark p-6 text-white">
                    <h1 class="text-2xl font-bold mb-2">إتمام الدفع</h1>
                    <p class="text-gray-300">رقم الحجز: {{ $booking->booking_number }}</p>
                </div>

                <div class="p-6">
                    {{-- Booking Summary --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <h3 class="font-bold text-brand-dark mb-4">تفاصيل الحجز</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-4">
                                @if($booking->consultant->photo)
                                    <img src="{{ asset('storage/' . $booking->consultant->photo) }}" class="w-14 h-14 rounded-full object-cover">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-brand-gold/10 flex items-center justify-center">
                                        <i class="fas fa-user text-xl text-brand-gold"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-brand-dark">{{ $booking->consultant->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->consultant->specialization_ar }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-200">
                                <div>
                                    <p class="text-sm text-gray-500">التاريخ</p>
                                    <p class="font-medium">{{ $booking->booking_date->format('Y-m-d') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">الوقت</p>
                                    <p class="font-medium">{{ date('h:i A', strtotime($booking->start_time)) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">المدة</p>
                                    <p class="font-medium">{{ $booking->duration_minutes }} دقيقة</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">المبلغ</p>
                                    <p class="font-bold text-brand-gold text-xl">{{ number_format($booking->price) }} ر.س</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <div class="mb-6">
                        <h3 class="font-bold text-brand-dark mb-4">طريقة الدفع</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-brand-gold transition">
                                <input type="radio" name="payment_method" value="card" checked class="w-5 h-5 text-brand-gold">
                                <div class="flex items-center gap-3">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Visa.svg/200px-Visa.svg.png" class="h-6" alt="Visa">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/200px-Mastercard-logo.svg.png" class="h-6" alt="Mastercard">
                                </div>
                                <span class="font-medium">بطاقة ائتمانية</span>
                            </label>
                            <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-brand-gold transition">
                                <input type="radio" name="payment_method" value="mada" class="w-5 h-5 text-brand-gold">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Mada_Logo.svg/200px-Mada_Logo.svg.png" class="h-6" alt="Mada">
                                <span class="font-medium">مدى</span>
                            </label>
                            <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-brand-gold transition">
                                <input type="radio" name="payment_method" value="apple_pay" class="w-5 h-5 text-brand-gold">
                                <i class="fab fa-apple text-2xl"></i>
                                <span class="font-medium">Apple Pay</span>
                            </label>
                        </div>
                    </div>

                    {{-- Notice --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">ملاحظة هامة:</p>
                                <p>سيتم إرسال رابط الاجتماع إلى بريدك الإلكتروني بعد تأكيد الدفع. يمكنك إلغاء الحجز واسترداد المبلغ قبل موعد الجلسة بساعتين على الأقل.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Button --}}
                    <form action="{{ route('consultations.process-payment', $booking) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-brand-gold text-brand-dark py-4 rounded-xl font-bold hover:bg-brand-goldDeep transition">
                            <i class="fas fa-lock ml-2"></i>
                            ادفع {{ number_format($booking->price) }} ر.س
                        </button>
                    </form>

                    {{-- Security Note --}}
                    <p class="text-center text-sm text-gray-500 mt-4">
                        <i class="fas fa-shield-alt ml-1"></i>
                        جميع المعاملات مؤمنة ومشفرة
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection




