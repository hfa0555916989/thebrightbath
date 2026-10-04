@extends('emails.layout')

@section('title', 'تذكير بالجلسة')
@section('header-title', $kind === '1h' ? 'جلستك تبدأ بعد ساعة ⏰' : 'تذكير بجلستك غدًا 📅')

@section('content')
    @php($otherName = $recipientType === 'consultant' ? $booking->user->name : $booking->consultant->name)

    <p class="greeting">مرحباً {{ $recipientType === 'consultant' ? $booking->consultant->name : $booking->user->name }}!</p>

    <p class="content">
        @if($kind === '1h')
            نذكّرك بأن جلستك الاستشارية مع <strong>{{ $otherName }}</strong> تبدأ بعد ساعة تقريبًا.
            تُفتح غرفة الجلسة قبل الموعد بـ {{ \App\Models\Booking::JOIN_OPENS_MINUTES_BEFORE }} دقائق.
        @else
            نذكّرك بجلستك الاستشارية مع <strong>{{ $otherName }}</strong>.
            أرفقنا لك ملف التقويم لإضافة الموعد إلى تقويمك.
        @endif
    </p>

    <div class="info-box">
        <h3>📅 تفاصيل الجلسة</h3>
        <div class="info-row">
            <span class="info-label">{{ $recipientType === 'consultant' ? 'العميل' : 'المستشار' }}</span>
            <span class="info-value">{{ $otherName }}</span>
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
        <a href="{{ route('video-call.join', $booking) }}" class="btn">🎥 الدخول إلى الجلسة</a>
    </div>

    <p class="warning-text">
        💡 تأكد من اتصال إنترنت جيد، واسمح للمتصفح باستخدام الكاميرا والميكروفون.
        @if($recipientType === 'client')
            ويمكنك مشاركة أي ملفات مع المستشار من صفحة الجلسة.
        @endif
    </p>
@endsection
