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
 * A confirmed or requested session will not take place: cancelled, or marked as no-show.
 */
class BookingStatusNotice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const CANCELLED_BY_CLIENT = 'cancelled_by_client';
    public const CANCELLED_BY_ADMIN = 'cancelled_by_admin';
    public const NO_SHOW = 'no_show';

    /**
     * @param  string  $recipientType  'client' or 'consultant'
     * @param  string  $event  one of the constants above
     */
    public function __construct(
        public Booking $booking,
        public string $recipientType,
        public string $event,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: match ($this->event) {
            self::NO_SHOW => 'تسجيل عدم حضور للجلسة الاستشارية',
            self::CANCELLED_BY_CLIENT => 'ألغى العميل الجلسة الاستشارية',
            default => 'إلغاء الجلسة الاستشارية',
        });
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-status-notice');
    }
}
