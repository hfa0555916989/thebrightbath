@extends('emails.layout')

@section('title', 'رمز الدخول')
@section('header-title', 'رمز الدخول')

@section('content')
    <p class="greeting">مرحباً! 👋</p>

    <p class="content">
        استخدم الرمز التالي لتسجيل الدخول إلى <strong>الطريق المشرق للتدريب والتطوير</strong>:
    </p>

    <div style="text-align: center; margin: 25px 0;">
        <span style="display: inline-block; font-size: 34px; font-weight: bold; letter-spacing: 10px; direction: ltr; background: #f8f9fa; border: 2px dashed #F8C524; border-radius: 12px; padding: 14px 24px; color: #1F3A63;">{{ $code }}</span>
    </div>

    <div class="info-box">
        <h3>⏰ ملاحظات هامة</h3>
        <div class="info-row">
            <span class="info-label">صلاحية الرمز</span>
            <span class="info-value">{{ $minutes }} دقائق</span>
        </div>
        <div class="info-row">
            <span class="info-label">الاستخدام</span>
            <span class="info-value">مرة واحدة فقط</span>
        </div>
    </div>

    <p class="warning-text">
        ⚠️ <strong>لا تشارك هذا الرمز مع أي أحد.</strong> فريق الطريق المشرق لن يطلبه منك أبداً.
        إذا لم تطلب الدخول، تجاهل هذه الرسالة وحسابك آمن.
    </p>
@endsection
