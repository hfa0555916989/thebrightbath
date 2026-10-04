<?php

namespace App\Support;

use App\Models\Booking;

/**
 * iCalendar (.ics, RFC 5545) invitation for a booked session, so client and
 * consultant can add it to Google/Apple/Outlook calendars in one tap.
 */
class BookingCalendar
{
    public static function ics(Booking $booking, string $recipientType = 'client'): string
    {
        $booking->loadMissing(['user', 'consultant.user']);

        $otherName = $recipientType === 'consultant' ? $booking->user->name : $booking->consultant->name;
        $joinUrl = route('video-call.join', $booking);
        $utc = fn ($time) => $time->copy()->utc()->format('Ymd\THis\Z');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Bright Path//Sessions//AR',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:booking-'.$booking->id.'@'.parse_url(config('app.url'), PHP_URL_HOST),
            'DTSTAMP:'.$utc(now()),
            'DTSTART:'.$utc($booking->sessionStartsAt()),
            'DTEND:'.$utc($booking->sessionEndsAt()),
            'SUMMARY:'.self::escape('جلسة استشارية مع '.$otherName),
            'DESCRIPTION:'.self::escape("رابط الدخول للجلسة:\n".$joinUrl),
            'URL:'.$joinUrl,
            'BEGIN:VALARM',
            'TRIGGER:-PT15M',
            'ACTION:DISPLAY',
            'DESCRIPTION:'.self::escape('جلستك الاستشارية بعد 15 دقيقة'),
            'END:VALARM',
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return implode("\r\n", array_map([self::class, 'fold'], $lines))."\r\n";
    }

    private static function escape(string $text): string
    {
        return str_replace(['\\', ';', ',', "\n"], ['\\\\', '\;', '\,', '\n'], $text);
    }

    /**
     * Lines longer than 75 octets are folded (continuation lines start with a space),
     * without splitting a multi-byte (Arabic) character.
     */
    private static function fold(string $line): string
    {
        $chunks = [];
        $current = '';

        foreach (mb_str_split($line) as $char) {
            $limit = $chunks === [] ? 75 : 74;
            if (strlen($current) + strlen($char) > $limit) {
                $chunks[] = $current;
                $current = '';
            }
            $current .= $char;
        }
        $chunks[] = $current;

        return implode("\r\n ", $chunks);
    }
}
