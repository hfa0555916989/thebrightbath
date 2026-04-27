@extends('emails.layout')

@section('title', 'أرباح جديدة')
@section('header-title', 'تم إضافة أرباح لحسابك!')

@section('content')
    <p class="greeting">مرحباً {{ $consultant->name }}! 💰</p>
    
    <p class="content">
        <span class="success-badge">🎉 تم إكمال جلسة استشارية بنجاح!</span>
    </p>
    
    <p class="content">
        تهانينا! تم إضافة أرباح جديدة إلى حسابك من الجلسة مع العميل <strong>{{ $booking->user->name }}</strong>.
    </p>
    
    <div class="info-box">
        <h3>💵 تفاصيل الأرباح</h3>
        <div class="info-row">
            <span class="info-label">قيمة الجلسة</span>
            <span class="info-value">{{ number_format($booking->consultant->hourly_rate, 2) }} ر.س</span>
        </div>
        <div class="info-row">
            <span class="info-label">نسبة العمولة</span>
            <span class="info-value">{{ $consultant->commission_rate }}%</span>
        </div>
        <div class="info-row" style="background: #d4edda; margin: 10px -25px -25px -25px; padding: 15px 25px; border-radius: 0 0 8px 8px;">
            <span class="info-label" style="color: #155724; font-size: 18px;">صافي أرباحك</span>
            <span class="info-value" style="color: #155724; font-size: 22px;">{{ number_format($earnings, 2) }} ر.س</span>
        </div>
    </div>
    
    <div class="info-box">
        <h3>📅 تفاصيل الجلسة</h3>
        <div class="info-row">
            <span class="info-label">العميل</span>
            <span class="info-value">{{ $booking->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">التاريخ</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y/m/d') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الوقت</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">المدة</span>
            <span class="info-value">{{ $consultant->session_duration }} دقيقة</span>
        </div>
    </div>
    
    <div style="text-align: center;">
        <a href="{{ url('/consultant/dashboard') }}" class="btn">📊 عرض لوحة التحكم</a>
    </div>
    
    <p class="content" style="margin-top: 25px; text-align: center;">
        استمر في تقديم خدماتك المميزة! 🌟
    </p>
@endsection



