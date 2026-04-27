<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Consultant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultantEarnings extends Mailable
{
    use Queueable, SerializesModels;

    public Consultant $consultant;
    public Booking $booking;
    public float $earnings;

    public function __construct(Consultant $consultant, Booking $booking, float $earnings)
    {
        $this->consultant = $consultant;
        $this->booking = $booking;
        $this->earnings = $earnings;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم إضافة أرباح جديدة لحسابك',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultant-earnings',
        );
    }
}



