@extends('emails.layout')

@section('title', 'تأكيد الحجز')
@section('header-title', $recipientType === 'consultant' ? 'لديك حجز جديد!' : 'تم تأكيد حجزك!')

@section('content')
    @if($recipientType === 'consultant')
        <p class="greeting">مرحباً {{ $booking->consultant->name }}! 👨‍💼</p>
        
        <p class="content">
            <span class="success-badge">🎉 لديك حجز جلسة استشارية جديدة!</span>
        </p>
        
        <p class="content">
            قام العميل <strong>{{ $booking->user->name }}</strong> بحجز جلسة استشارية معك.
        </p>
    @else
        <p class="greeting">مرحباً {{ $booking->user->name }}! 🎉</p>
        
        <p class="content">
            <span class="success-badge">✅ تم تأكيد حجزك بنجاح!</span>
        </p>
        
        <p class="content">
            شكراً لثقتك بنا. تم حجز جلستك الاستشارية مع <strong>{{ $booking->consultant->name }}</strong>.
        </p>
    @endif
    
    <div class="info-box">
        <h3>📅 تفاصيل الجلسة</h3>
        <div class="info-row">
            <span class="info-label">المستشار</span>
            <span class="info-value">{{ $booking->consultant->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">التخصص</span>
            <span class="info-value">{{ $booking->consultant->specialization }}</span>
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
            <span class="info-value">{{ $booking->consultant->session_duration }} دقيقة</span>
        </div>
        <div class="info-row">
            <span class="info-label">رقم الحجز</span>
            <span class="info-value">#{{ $booking->id }}</span>
        </div>
    </div>
    
    @if($recipientType === 'consultant')
        <div class="info-box">
            <h3>👤 معلومات العميل</h3>
            <div class="info-row">
                <span class="info-label">الاسم</span>
                <span class="info-value">{{ $booking->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">البريد الإلكتروني</span>
                <span class="info-value">{{ $booking->user->email }}</span>
            </div>
        </div>
        
        <p class="content">
            يمكنك مراجعة نتائج اختبارات العميل من لوحة التحكم الخاصة بك للاستعداد للجلسة.
        </p>
    @else
        <div style="text-align: center;">
            <a href="{{ url('/client/sessions') }}" class="btn">📋 عرض جلساتي</a>
        </div>
        
        <p class="warning-text">
            💡 <strong>نصيحة:</strong> قم بإكمال اختبارات الميول المهنية قبل الجلسة ليتمكن المستشار من مساعدتك بشكل أفضل.
        </p>
    @endif
    
    <p class="content" style="margin-top: 25px;">
        إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.
    </p>
@endsection



