@extends('emails.layout')

@section('title', 'طلب حجز جديد')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
            <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
            </svg>
        </div>
    </div>
    
    <h1 style="color: #1e293b; font-size: 28px; font-weight: 700; margin: 0 0 15px; text-align: center;">
        طلب حجز جديد يحتاج موافقتك
    </h1>
    
    <p style="color: #64748b; font-size: 16px; line-height: 1.7; margin: 0 0 25px; text-align: center;">
        مرحباً {{ $booking->consultant->user->name ?? 'المستشار' }}،
        <br>
        لديك طلب حجز جديد من عميل يحتاج موافقتك قبل إتمام عملية الدفع.
    </p>

    <!-- Booking Details Card -->
    <div style="background: #f8fafc; border-radius: 16px; padding: 25px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
        <h2 style="color: #334155; font-size: 18px; font-weight: 600; margin: 0 0 20px; text-align: center;">
            تفاصيل الحجز
        </h2>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">العميل:</span>
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: left;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ $booking->user->name }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">البريد:</span>
                </td>
                <td style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; text-align: left;">
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ $booking->user->email }}</span>
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
                    <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ date('h:i A', strtotime($booking->start_time)) }} - {{ date('h:i A', strtotime($booking->end_time)) }}</span>
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
            @if($booking->client_notes)
            <tr>
                <td colspan="2" style="padding: 12px 0; border-top: 1px solid #e2e8f0;">
                    <span style="color: #64748b; font-size: 14px;">ملاحظات العميل:</span>
                    <p style="color: #1e293b; font-size: 14px; margin: 8px 0 0;">{{ $booking->client_notes }}</p>
                </td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Action Note -->
    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; padding: 20px; margin-bottom: 25px; border: 1px solid #f59e0b;">
        <p style="color: #92400e; font-size: 14px; line-height: 1.6; margin: 0; text-align: center;">
            <strong>ملاحظة:</strong> لن يتم تحصيل أي مبلغ من العميل إلا بعد موافقتك على الحجز.
        </p>
    </div>

    <!-- CTA Button -->
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/consultant/pending-requests') }}" 
           style="display: inline-block; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);">
            عرض طلبات الحجز
        </a>
    </div>
@endsection


