<?php

namespace App\Mail;

use App\Models\Booking;
use App\Support\BookingCalendar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SessionReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $recipientType  'client' or 'consultant'
     * @param  string  $kind  '24h', '1h' or 'open' (the room just opened)
     */
    public function __construct(
        public Booking $booking,
        public string $recipientType,
        public string $kind,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match ($this->kind) {
                'open' => 'غرفة جلستك الاستشارية مفتوحة الآن',
                '1h' => 'جلستك الاستشارية تبدأ بعد ساعة',
                default => 'تذكير: جلستك الاستشارية غدًا',
            },
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.session-reminder');
    }

    public function attachments(): array
    {
        if ($this->kind !== '24h') {
            return [];
        }

        return [
            Attachment::fromData(fn () => BookingCalendar::ics($this->booking, $this->recipientType), 'session.ics')
                ->withMime('text/calendar'),
        ];
    }
}
