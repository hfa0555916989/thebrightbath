@extends('emails.layout')

@section('title', 'تفعيل حسابك')
@section('header-title', 'مرحباً بك في عائلتنا!')

@section('content')
    <p class="greeting">مرحباً {{ $user->name }}! 👋</p>
    
    <p class="content">
        شكراً لانضمامك إلى <strong>الطريق المشرق للتدريب والتطوير</strong>. نحن سعداء جداً بوجودك معنا!
    </p>
    
    <p class="content">
        لقد قمت بإنشاء حساب جديد باستخدام البريد الإلكتروني:
        <span class="highlight">{{ $user->email }}</span>
    </p>
    
    <p class="content">
        لتفعيل حسابك والبدء في الاستفادة من خدماتنا، يرجى الضغط على الزر أدناه:
    </p>
    
    <div style="text-align: center;">
        <a href="{{ $verificationUrl }}" class="btn">✨ تفعيل حسابي الآن</a>
    </div>
    
    <div class="info-box">
        <h3>🎯 ماذا يمكنك فعله بعد التفعيل؟</h3>
        <div class="info-row">
            <span class="info-label">📊</span>
            <span class="info-value">إجراء اختبارات الميول المهنية</span>
        </div>
        <div class="info-row">
            <span class="info-label">👨‍💼</span>
            <span class="info-value">حجز جلسات استشارية مع خبرائنا</span>
        </div>
        <div class="info-row">
            <span class="info-label">📚</span>
            <span class="info-value">الوصول للكتاب الإلكتروني</span>
        </div>
        <div class="info-row">
            <span class="info-label">📈</span>
            <span class="info-value">متابعة تقدمك ونتائجك</span>
        </div>
    </div>
    
    <p class="warning-text">
        ⚠️ <strong>ملاحظة هامة:</strong> هذا الرابط صالح لمدة 24 ساعة فقط. إذا لم تتمكن من تفعيل حسابك، يمكنك طلب رابط جديد.
    </p>
    
    <p class="content" style="margin-top: 25px;">
        إذا لم تقم بإنشاء هذا الحساب، يرجى تجاهل هذه الرسالة.
    </p>
@endsection



