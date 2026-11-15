@extends('emails.layout')

@section('title', 'طلب حجز جديد')
@section('header-title', 'طلب حجز جديد 🔔')

@section('content')
    <p class="greeting">مرحباً!</p>

    <p class="content">
        أرسل <strong>{{ $booking->user->name }}</strong> طلب حجز جلسة مع المستشار <strong>{{ $booking->consultant->name }}</strong>،
        وهو بانتظار موافقة المستشار.
    </p>

    <div class="info-box">
        <h3>📅 تفاصيل الطلب</h3>
        <div class="info-row">
            <span class="info-label">رقم الحجز</span>
            <span class="info-value">{{ $booking->booking_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">العميل</span>
            <span class="info-value">{{ $booking->user->name }} ({{ $booking->user->email }})</span>
        </div>
        <div class="info-row">
            <span class="info-label">المستشار</span>
            <span class="info-value">{{ $booking->consultant->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">التاريخ</span>
            <span class="info-value">{{ $booking->sessionStartsAt()->locale('ar')->translatedFormat('l j F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الوقت</span>
            <span class="info-value">{{ $booking->formatted_time }} (بتوقيت السعودية)</span>
        </div>
        <div class="info-row">
            <span class="info-label">المدة</span>
            <span class="info-value">{{ $booking->duration_minutes }} دقيقة</span>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn">عرض الحجز في لوحة الإدارة</a>
    </div>
@endsection
