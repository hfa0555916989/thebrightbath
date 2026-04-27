<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingApprovalResponse extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public bool $approved;

    public function __construct(Booking $booking, bool $approved)
    {
        $this->booking = $booking;
        $this->approved = $approved;
    }

    public function envelope(): Envelope
    {
        $status = $this->approved ? '✅ تمت الموافقة على حجزك' : '❌ تم رفض طلب الحجز';
        return new Envelope(
            subject: $status . ' - الطريق المشرق',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-approval-response',
        );
    }
}





