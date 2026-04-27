@extends('emails.layout')

@section('title', 'استعادة كلمة المرور')
@section('header-title', 'استعادة كلمة المرور')

@section('content')
    <p class="greeting">مرحباً {{ $user->name }}! 🔐</p>
    
    <p class="content">
        لقد تلقينا طلباً لاستعادة كلمة المرور الخاصة بحسابك في <strong>الطريق المشرق للتدريب والتطوير</strong>.
    </p>
    
    <p class="content">
        اضغط على الزر أدناه لإنشاء كلمة مرور جديدة:
    </p>
    
    <div style="text-align: center;">
        <a href="{{ $resetUrl }}" class="btn">🔑 استعادة كلمة المرور</a>
    </div>
    
    <div class="info-box">
        <h3>⏰ ملاحظات هامة</h3>
        <div class="info-row">
            <span class="info-label">صلاحية الرابط</span>
            <span class="info-value">ساعة واحدة فقط</span>
        </div>
        <div class="info-row">
            <span class="info-label">البريد الإلكتروني</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
    </div>
    
    <p class="warning-text">
        ⚠️ <strong>إذا لم تطلب استعادة كلمة المرور:</strong> يرجى تجاهل هذه الرسالة. حسابك آمن ولن يتم إجراء أي تغيير.
    </p>
    
    <p class="content" style="margin-top: 25px; font-size: 14px; color: #6c757d;">
        إذا كان زر "استعادة كلمة المرور" لا يعمل، يمكنك نسخ الرابط التالي ولصقه في متصفحك:
    </p>
    <p style="word-break: break-all; font-size: 12px; color: #6c757d; background: #f8f9fa; padding: 10px; border-radius: 8px;">
        {{ $resetUrl }}
    </p>
@endsection



