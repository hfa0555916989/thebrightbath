@extends('emails.layout')

@section('title', 'دخل الجلسة')
@section('header-title', 'بانتظارك في الجلسة الآن 🎥')

@section('content')
    <p class="greeting">مرحباً {{ $recipientType === 'consultant' ? $booking->consultant->name : $booking->user->name }}!</p>

    <p class="content">
        {{ $recipientType === 'consultant' ? 'العميل' : 'المستشار' }} <strong>{{ $joinedName }}</strong>
        دخل غرفة الجلسة الآن وينتظرك. انضم من الزر أدناه.
    </p>

    <div class="info-box">
        <h3>📅 الجلسة</h3>
        <div class="info-row">
            <span class="info-label">الموعد</span>
            <span class="info-value">{{ $booking->formatted_time }} (بتوقيت السعودية)</span>
        </div>
        <div class="info-row">
            <span class="info-label">المدة</span>
            <span class="info-value">{{ $booking->duration_minutes }} دقيقة</span>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('video-call.join', $booking) }}" class="btn">🎥 انضم الآن</a>
    </div>
@endsection
