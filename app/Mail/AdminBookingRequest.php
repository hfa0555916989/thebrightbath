<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Tells the supervisor (SUPERVISOR_EMAIL) that a client requested a session.
 */
class AdminBookingRequest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'طلب حجز جديد: '.$this->booking->user->name.' مع '.$this->booking->consultant->name);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-booking-request');
    }
}
