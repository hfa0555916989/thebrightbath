@extends('emails.layout')

@section('title', 'طلب حجز جلسة قيد المراجعة')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
            <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
            </svg>
        </div>
    </div>
    
    <h1 style="color: #1e293b; font-size: 28px; font-weight: 700; margin: 0 0 15px; text-align: center;">
        طلب الحجز قيد المراجعة
    </h1>
    
    <p style="color: #64748b; font-size: 16px; line-height: 1.7; margin: 0 0 25px; text-align: center;">
        مرحباً {{ $booking->user->name }}،
        <br>
        تم استلام طلب حجز جلستك بنجاح وهو الآن قيد المراجعة من قبل المستشار.
    </p>

    <!-- Booking Details Card -->
    <div style="background: #f8fafc; border-radius: 16px; padding: 25px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
        <h2 style="color: #334155; font-size: 18px; font-weight: 600; margin: 0 0 20px; text-align: center;">
            <span style="display: inline-block; width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; margin-left: 10px;"></span>
            تفاصيل الحجز
        </h2>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">رقم الحجز:</span>
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: left;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">#{{ $booking->booking_number ?? $booking->id }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">المستشار:</span>
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: left;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ $booking->consultant->user->name ?? 'المستشار' }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">التاريخ:</span>
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: left;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">الوقت:</span>
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: left;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">المدة:</span>
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: left;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ $booking->duration_minutes }} دقيقة</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0;">
                    <span style="color: #64748b; font-size: 14px;">المبلغ:</span>
                </td>
                <td style="padding: 12px 0; text-align: left;">
                    <span style="color: #10b981; font-weight: 700; font-size: 16px;">{{ number_format($booking->price, 2) }} ر.س</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Status Note -->
    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; padding: 20px; margin-bottom: 25px; border: 1px solid #f59e0b;">
        <div style="display: flex; align-items: flex-start;">
            <div style="flex-shrink: 0; margin-left: 12px;">
                <svg width="24" height="24" fill="#d97706" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>
            <div>
                <h4 style="color: #92400e; font-size: 16px; font-weight: 600; margin: 0 0 8px;">ماذا بعد؟</h4>
                <p style="color: #92400e; font-size: 14px; line-height: 1.6; margin: 0;">
                    سيقوم المستشار بمراجعة طلبك وسيتم إشعارك عبر البريد الإلكتروني فور الموافقة أو الرفض.
                    <br>
                    في حال الموافقة، ستتمكن من إتمام عملية الدفع لتأكيد الحجز.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Button -->
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('consultations.waiting-approval', $booking) }}" 
           style="display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
            متابعة حالة الطلب
        </a>
    </div>

    <p style="color: #64748b; font-size: 14px; line-height: 1.7; margin: 20px 0 0; text-align: center;">
        شكراً لاختيارك الطريق المشرق للتدريب والتطوير!
    </p>
@endsection
