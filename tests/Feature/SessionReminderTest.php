<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmation;
use App\Mail\SessionReminder;
use App\Models\Booking;
use App\Support\BookingCalendar;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SessionReminderTest extends TestCase
{
    use RefreshDatabase;

    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        // Session on 10 Oct 2026 at 10:00 Riyadh time, paid days earlier.
        $this->travelTo(Carbon::parse('2026-10-05 12:00', 'Asia/Riyadh'));
        $this->booking = Booking::factory()->confirmed()->create([
            'booking_date' => '2026-10-10',
            'start_time' => '10:00',
            'end_time' => '10:30',
        ]);
    }

    private function at(string $dateTime): void
    {
        $this->travelTo(Carbon::parse($dateTime, 'Asia/Riyadh'));
    }

    private function runReminders(): void
    {
        $this->artisan('sessions:send-reminders')->assertSuccessful();
    }

    private function assertRemindersQueued(string $kind, int $times = 1): void
    {
        $client = $this->booking->user->email;
        $consultant = $this->booking->consultant->user->email;

        Mail::assertQueued(SessionReminder::class, fn ($m) => $m->kind === $kind && $m->recipientType === 'client' && $m->hasTo($client));
        Mail::assertQueued(SessionReminder::class, fn ($m) => $m->kind === $kind && $m->recipientType === 'consultant' && $m->hasTo($consultant));
        $this->assertCount($times * 2, Mail::queued(SessionReminder::class, fn ($m) => $m->kind === $kind));
    }

    public function test_nothing_is_sent_more_than_a_day_before(): void
    {
        $this->at('2026-10-08 09:00');
        $this->runReminders();

        Mail::assertNothingQueued();
    }

    public function test_24h_reminder_goes_to_both_sides_once(): void
    {
        $this->at('2026-10-09 10:30');
        $this->runReminders();
        $this->runReminders();

        $this->assertRemindersQueued('24h');
        $this->assertNotNull($this->booking->fresh()->reminder_24h_sent_at);
        $this->assertNull($this->booking->fresh()->reminder_1h_sent_at);
    }

    public function test_1h_reminder_goes_to_both_sides_once(): void
    {
        $this->at('2026-10-09 10:30');
        $this->runReminders();

        $this->at('2026-10-10 09:05');
        $this->runReminders();
        $this->runReminders();

        $this->assertRemindersQueued('1h');
        $this->assertNotNull($this->booking->fresh()->reminder_1h_sent_at);
    }

    public function test_late_booking_gets_only_the_1h_reminder(): void
    {
        $this->at('2026-10-10 09:30');
        $this->runReminders();

        $this->assertRemindersQueued('1h');
        $this->assertCount(0, Mail::queued(SessionReminder::class, fn ($m) => $m->kind === '24h'));
        $this->assertNotNull($this->booking->fresh()->reminder_24h_sent_at);
    }

    public function test_24h_reminder_is_skipped_right_after_payment(): void
    {
        $this->at('2026-10-09 18:00');
        $this->booking->update(['paid_at' => now()->subMinutes(20)]);

        $this->runReminders();

        Mail::assertNothingQueued();
        $this->assertNotNull($this->booking->fresh()->reminder_24h_sent_at);
    }

    public function test_unconfirmed_or_cancelled_bookings_get_no_reminders(): void
    {
        $this->booking->update(['status' => 'cancelled']);
        Booking::factory()->approved()->create(['booking_date' => '2026-10-10', 'start_time' => '11:00', 'end_time' => '11:30']);

        $this->at('2026-10-10 09:30');
        $this->runReminders();

        Mail::assertNothingQueued();
    }

    public function test_sessions_already_started_get_no_reminder(): void
    {
        $this->at('2026-10-10 10:05');
        $this->runReminders();

        Mail::assertNothingQueued();
    }

    public function test_reminder_email_links_to_the_session_and_attaches_the_calendar(): void
    {
        $mail = new SessionReminder($this->booking, 'consultant', '24h');

        $html = $mail->render();
        $this->assertStringContainsString(route('video-call.join', $this->booking), $html);
        $this->assertStringContainsString($this->booking->user->name, $html);
        $this->assertCount(1, $mail->attachments());
        $this->assertCount(0, (new SessionReminder($this->booking, 'client', '1h'))->attachments());
    }

    public function test_reminders_run_on_the_scheduler(): void
    {
        $commands = collect(app(Schedule::class)->events())->map->command->implode(' ');

        $this->assertStringContainsString('sessions:send-reminders', $commands);
    }

    // ── Calendar invitation ──────────────────────────────────────────────

    public function test_calendar_invitation_has_the_session_time_in_utc_and_the_join_link(): void
    {
        $ics = BookingCalendar::ics($this->booking, 'client');

        $this->assertStringStartsWith("BEGIN:VCALENDAR\r\n", $ics);
        $this->assertStringContainsString("DTSTART:20261010T070000Z\r\n", $ics); // 10:00 Riyadh = 07:00 UTC
        $this->assertStringContainsString("DTEND:20261010T073000Z\r\n", $ics);
        $this->assertStringContainsString('UID:booking-'.$this->booking->id.'@', $ics);
        $this->assertStringContainsString('URL:'.route('video-call.join', $this->booking), str_replace("\r\n ", '', $ics));

        foreach (explode("\r\n", $ics) as $line) {
            $this->assertLessThanOrEqual(75, strlen($line), 'iCalendar lines must be folded at 75 octets');
            $this->assertTrue(mb_check_encoding($line, 'UTF-8'), 'folding must not split Arabic characters');
        }
    }

    public function test_booking_confirmation_carries_the_calendar_invitation(): void
    {
        $attachments = (new BookingConfirmation($this->booking, 'client'))->attachments();

        $this->assertCount(1, $attachments);
        $this->assertSame('session.ics', $attachments[0]->as);
    }

    public function test_emails_show_the_consultant_name(): void
    {
        $html = (new BookingConfirmation($this->booking, 'client'))->render();

        $this->assertStringContainsString($this->booking->consultant->user->name, $html);
    }
}
