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
 * The other party entered the session room and is waiting.
 */
class ParticipantJoined extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $recipientType  who gets the email: 'client' or 'consultant'
     */
    public function __construct(
        public Booking $booking,
        public string $recipientType,
    ) {
    }

    public function joinedName(): string
    {
        return $this->recipientType === 'consultant' ? $this->booking->user->name : $this->booking->consultant->name;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->joinedName().' دخل الجلسة وينتظرك الآن');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.participant-joined', with: ['joinedName' => $this->joinedName()]);
    }
}
