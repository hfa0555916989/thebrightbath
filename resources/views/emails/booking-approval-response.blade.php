@extends('emails.layout')

@section('title', $approved ? 'تمت الموافقة على حجزك' : 'تم رفض طلب الحجز')
@section('header-title', $approved ? 'تمت الموافقة على حجزك ✅' : 'تم رفض طلب الحجز ❌')

@section('content')
    <p class="greeting">مرحباً {{ $booking->user->name }}! 👋</p>
    
    @if($approved)
        <p class="content">
            يسعدنا إبلاغك بأن المستشار <strong>{{ $booking->consultant->name }}</strong> قد وافق على طلب حجزك!
        </p>
        
        <div class="info-box" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-color: #28a745;">
            <h3>✅ الحجز جاهز للدفع</h3>
            <div class="info-row">
                <span class="info-label">المستشار</span>
                <span class="info-value">{{ $booking->consultant->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">التاريخ</span>
                <span class="info-value">{{ $booking->booking_date->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">الوقت</span>
                <span class="info-value">{{ date('h:i A', strtotime($booking->start_time)) }} - {{ date('h:i A', strtotime($booking->end_time)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">المدة</span>
                <span class="info-value">{{ $booking->duration_minutes }} دقيقة</span>
            </div>
            <div class="info-row">
                <span class="info-label">المبلغ المطلوب</span>
                <span class="info-value">{{ number_format($booking->price, 2) }} ر.س</span>
            </div>
        </div>
        
        <p class="content" style="margin-top: 20px;">
            يرجى إتمام عملية الدفع لتأكيد الحجز بشكل نهائي.
        </p>
        
        <div style="text-align: center;">
            <a href="{{ url('/consultations/payment/' . $booking->id) }}" class="btn">💳 إتمام الدفع الآن</a>
        </div>
        
        <p class="warning-text" style="margin-top: 25px;">
            ⏰ <strong>تنبيه:</strong> يرجى إتمام الدفع خلال 24 ساعة لضمان حجز موعدك.
        </p>
    @else
        <p class="content">
            نعتذر لإبلاغك بأن المستشار <strong>{{ $booking->consultant->name }}</strong> لم يتمكن من قبول طلب حجزك في الوقت المحدد.
        </p>
        
        <div class="info-box" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-color: #dc3545;">
            <h3>❌ تفاصيل الطلب المرفوض</h3>
            <div class="info-row">
                <span class="info-label">المستشار</span>
                <span class="info-value">{{ $booking->consultant->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">التاريخ المطلوب</span>
                <span class="info-value">{{ $booking->booking_date->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">الوقت المطلوب</span>
                <span class="info-value">{{ date('h:i A', strtotime($booking->start_time)) }}</span>
            </div>
            @if($booking->cancellation_reason)
            <div class="info-row">
                <span class="info-label">السبب</span>
                <span class="info-value">{{ $booking->cancellation_reason }}</span>
            </div>
            @endif
        </div>
        
        <p class="content" style="margin-top: 20px;">
            يمكنك اختيار موعد آخر مع نفس المستشار أو حجز جلسة مع مستشار آخر.
        </p>
        
        <div style="text-align: center;">
            <a href="{{ url('/consultations') }}" class="btn">🔍 البحث عن مستشار آخر</a>
        </div>
        
        <p class="content" style="margin-top: 25px; font-size: 14px; color: #6c757d;">
            لم يتم خصم أي مبلغ من حسابك.
        </p>
    @endif
@endsection





