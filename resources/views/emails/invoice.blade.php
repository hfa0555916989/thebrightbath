@extends('emails.layout')

@section('title', 'فاتورة الدفع')
@section('header-title', 'فاتورة الدفع')

@section('content')
    <p class="greeting">مرحباً {{ $payment->booking->user->name }}! 📧</p>
    
    <p class="content">
        <span class="success-badge">✅ تم الدفع بنجاح!</span>
    </p>
    
    <p class="content">
        شكراً لك! إليك تفاصيل فاتورتك:
    </p>
    
    <!-- Invoice Card -->
    <div style="background: #fff; border: 2px solid #1a5f7a; border-radius: 16px; overflow: hidden; margin: 25px 0;">
        <!-- Invoice Header -->
        <div style="background: linear-gradient(135deg, #1a5f7a 0%, #0d3d4d 100%); padding: 20px; color: #fff;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="color: #fff;">
                        <h2 style="margin: 0; font-size: 24px;">فاتورة</h2>
                        <p style="margin: 5px 0 0 0; opacity: 0.9;">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </td>
                    <td style="text-align: left; color: #fff;">
                        <p style="margin: 0; font-size: 14px;">تاريخ الإصدار</p>
                        <p style="margin: 5px 0 0 0; font-weight: 600;">{{ $payment->created_at->format('Y/m/d') }}</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Invoice Body -->
        <div style="padding: 25px;">
            <!-- From/To -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                <tr>
                    <td width="50%" style="vertical-align: top; padding-left: 15px;">
                        <p style="color: #6c757d; font-size: 12px; margin: 0;">من</p>
                        <p style="color: #1a5f7a; font-weight: 600; margin: 5px 0;">الطريق المشرق للتدريب والتطوير</p>
                        <p style="color: #6c757d; font-size: 13px; margin: 0;">cs@thebrightbath.com</p>
                    </td>
                    <td width="50%" style="vertical-align: top;">
                        <p style="color: #6c757d; font-size: 12px; margin: 0;">إلى</p>
                        <p style="color: #1a5f7a; font-weight: 600; margin: 5px 0;">{{ $payment->booking->user->name }}</p>
                        <p style="color: #6c757d; font-size: 13px; margin: 0;">{{ $payment->booking->user->email }}</p>
                    </td>
                </tr>
            </table>
            
            <!-- Items Table -->
            <table width="100%" cellpadding="12" cellspacing="0" style="border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="text-align: right; border-bottom: 2px solid #e9ecef; color: #495057;">الوصف</th>
                        <th style="text-align: center; border-bottom: 2px solid #e9ecef; color: #495057;">المدة</th>
                        <th style="text-align: left; border-bottom: 2px solid #e9ecef; color: #495057;">المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border-bottom: 1px solid #e9ecef;">
                            <p style="margin: 0; font-weight: 600; color: #2d3748;">جلسة استشارية</p>
                            <p style="margin: 5px 0 0 0; font-size: 13px; color: #6c757d;">المستشار: {{ $payment->booking->consultant->name }}</p>
                            <p style="margin: 3px 0 0 0; font-size: 13px; color: #6c757d;">التخصص: {{ $payment->booking->consultant->specialization }}</p>
                        </td>
                        <td style="text-align: center; border-bottom: 1px solid #e9ecef; color: #495057;">
                            {{ $payment->booking->consultant->session_duration }} دقيقة
                        </td>
                        <td style="text-align: left; border-bottom: 1px solid #e9ecef; font-weight: 600; color: #2d3748;">
                            {{ number_format($payment->amount, 2) }} ر.س
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: left; padding-top: 15px;">
                            <span style="color: #6c757d;">المجموع الفرعي</span>
                        </td>
                        <td style="text-align: left; padding-top: 15px; color: #495057;">
                            {{ number_format($payment->amount, 2) }} ر.س
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <span style="color: #6c757d;">ضريبة القيمة المضافة (15%)</span>
                        </td>
                        <td style="text-align: left; color: #495057;">
                            {{ number_format($payment->amount * 0.15, 2) }} ر.س
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left; padding-top: 10px; border-top: 2px solid #1a5f7a;">
                            <strong style="color: #1a5f7a; font-size: 18px;">الإجمالي</strong>
                        </td>
                        <td style="text-align: left; padding-top: 10px; border-top: 2px solid #1a5f7a;">
                            <strong style="color: #1a5f7a; font-size: 18px;">{{ number_format($payment->amount * 1.15, 2) }} ر.س</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Invoice Footer -->
        <div style="background: #f8f9fa; padding: 15px 25px; border-top: 1px solid #e9ecef;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <span style="background: #d4edda; color: #155724; padding: 5px 12px; border-radius: 20px; font-size: 13px;">
                            ✓ مدفوعة
                        </span>
                    </td>
                    <td style="text-align: left; color: #6c757d; font-size: 13px;">
                        طريقة الدفع: {{ $payment->payment_method === 'card' ? 'بطاقة ائتمانية' : 'تحويل بنكي' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="info-box">
        <h3>📅 تفاصيل الجلسة</h3>
        <div class="info-row">
            <span class="info-label">التاريخ</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($payment->booking->booking_date)->format('Y/m/d') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الوقت</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($payment->booking->start_time)->format('H:i') }}</span>
        </div>
    </div>
    
    <div style="text-align: center;">
        <a href="{{ url('/client/invoices') }}" class="btn">📋 عرض جميع الفواتير</a>
    </div>
    
    <p class="content" style="margin-top: 25px; text-align: center; color: #6c757d;">
        شكراً لثقتك بخدماتنا! 🙏
    </p>
@endsection



