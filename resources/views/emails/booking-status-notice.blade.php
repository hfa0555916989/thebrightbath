@extends('emails.layout')

@section('title', 'تحديث على الجلسة')
@section('header-title', $event === \App\Mail\BookingStatusNotice::NO_SHOW ? 'تسجيل عدم حضور' : 'تم إلغاء الجلسة')

@section('content')
    @php($isConsultant = $recipientType === 'consultant')
    @php($otherName = $isConsultant ? $booking->user->name : $booking->consultant->name)

    <p class="greeting">مرحباً {{ $isConsultant ? $booking->consultant->name : $booking->user->name }}!</p>

    <p class="content">
        @if($event === \App\Mail\BookingStatusNotice::CANCELLED_BY_CLIENT)
            @if($isConsultant)
                نود إعلامك بأن العميل <strong>{{ $otherName }}</strong> ألغى جلسته معك، وأصبح الموعد متاحًا من جديد.
            @else
                تم إلغاء جلستك مع <strong>{{ $otherName }}</strong> بناءً على طلبك.
            @endif
        @elseif($event === \App\Mail\BookingStatusNotice::CANCELLED_BY_ADMIN)
            نأسف لإبلاغك بأن إدارة المنصة ألغت الجلسة مع <strong>{{ $otherName }}</strong>.
            @unless($isConsultant)
                إن كان لديك أي استفسار، يسعدنا تواصلك معنا.
            @endunless
        @else
            سُجِّلت هذه الجلسة مع <strong>{{ $otherName }}</strong> بحالة "لم يحضر".
            إن كان في ذلك خطأ، يرجى التواصل مع إدارة المنصة.
        @endif
    </p>

    <div class="info-box">
        <h3>📅 تفاصيل الجلسة</h3>
        <div class="info-row">
            <span class="info-label">رقم الحجز</span>
            <span class="info-value">{{ $booking->booking_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ $isConsultant ? 'العميل' : 'المستشار' }}</span>
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
    </div>

    <div style="text-align: center;">
        <a href="{{ $isConsultant ? route('consultant.sessions') : route('client.sessions') }}" class="btn">عرض جلساتي</a>
    </div>
@endsection
