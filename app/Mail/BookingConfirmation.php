<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $recipientType; // 'client' or 'consultant'

    public function __construct(Booking $booking, string $recipientType = 'client')
    {
        $this->booking = $booking;
        $this->recipientType = $recipientType;
    }

    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'consultant' 
            ? 'لديك حجز جلسة استشارية جديدة'
            : 'تأكيد حجز جلستك الاستشارية';
            
        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }
}



