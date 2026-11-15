<?php

namespace Tests\Feature;

use App\Mail\ParticipantJoined;
use App\Mail\SessionReminder;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * "X joined the session" alerts, the "room is open" email, and the dashboard badges.
 */
class SessionJoinAlertTest extends TestCase
{
    use RefreshDatabase;

    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        config(['services.daily.key' => 'test-daily-key']);

        Http::fake(fn (HttpRequest $request) => match (true) {
            $request->method() === 'POST' && str_ends_with($request->url(), '/rooms') => Http::response([
                'name' => $request['name'],
                'url' => 'https://thebrightbath.daily.co/bp-room',
                'privacy' => 'private',
            ]),
            str_ends_with($request->url(), '/meeting-tokens') => Http::response(['token' => 'token']),
            default => Http::response([], 404),
        });

        // Session on 10 Oct 2026, 10:00-10:30 Riyadh time: room open 09:50-11:00.
        $this->at('2026-10-05 12:00');
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

    private function joinAsClient()
    {
        return $this->actingAs($this->booking->user)->get(route('video-call.join', $this->booking));
    }

    private function joinAsConsultant()
    {
        return $this->actingAs($this->booking->consultant->user)->get(route('video-call.join', $this->booking));
    }

    // ---- joined alerts -------------------------------------------------------

    public function test_client_joining_alerts_the_consultant_only(): void
    {
        $this->at('2026-10-10 09:55');

        $this->joinAsClient()->assertOk()->assertViewHas('state', 'live');

        Mail::assertQueued(ParticipantJoined::class, fn ($m) => $m->recipientType === 'consultant'
            && $m->hasTo($this->booking->consultant->user->email));
        Mail::assertNotQueued(ParticipantJoined::class, fn ($m) => $m->hasTo($this->booking->user->email));
        $this->assertNotNull($this->booking->fresh()->client_joined_at);
    }

    public function test_consultant_joining_alerts_the_client(): void
    {
        $this->at('2026-10-10 10:00');

        $this->joinAsConsultant()->assertOk();

        Mail::assertQueued(ParticipantJoined::class, fn ($m) => $m->recipientType === 'client'
            && $m->hasTo($this->booking->user->email));
        $this->assertCount(1, Mail::queued(ParticipantJoined::class));
    }

    public function test_alert_is_sent_once_per_party_even_after_rejoining(): void
    {
        $this->at('2026-10-10 09:55');
        $this->joinAsClient();
        $this->joinAsClient();
        $this->at('2026-10-10 10:10');
        $this->joinAsClient();
        $this->joinAsConsultant();
        $this->joinAsConsultant();

        $this->assertCount(1, Mail::queued(ParticipantJoined::class, fn ($m) => $m->recipientType === 'consultant'));
        $this->assertCount(1, Mail::queued(ParticipantJoined::class, fn ($m) => $m->recipientType === 'client'));
    }

    public function test_no_alert_outside_the_join_window(): void
    {
        $this->at('2026-10-10 09:30');
        $this->joinAsClient()->assertOk()->assertViewHas('state', 'waiting');

        $this->at('2026-10-10 11:05');
        $this->joinAsClient()->assertOk()->assertViewHas('state', 'ended');

        Mail::assertNotQueued(ParticipantJoined::class);
        $this->assertNull($this->booking->fresh()->client_joined_at);
    }

    public function test_no_alert_when_the_room_cannot_open(): void
    {
        config(['services.daily.key' => null]);
        $this->at('2026-10-10 09:55');

        $this->joinAsClient()->assertOk()->assertViewHas('state', 'unavailable');

        Mail::assertNotQueued(ParticipantJoined::class);
    }

    public function test_no_alert_for_unconfirmed_bookings_or_outsiders(): void
    {
        $this->booking->update(['status' => 'approved']);
        $this->at('2026-10-10 09:55');

        $this->joinAsClient()->assertRedirect();
        $this->actingAs(\App\Models\User::factory()->create())
            ->get(route('video-call.join', $this->booking))
            ->assertForbidden();

        Mail::assertNotQueued(ParticipantJoined::class);
    }

    public function test_alert_email_names_who_joined_and_links_to_the_session(): void
    {
        $html = (new ParticipantJoined($this->booking, 'consultant'))->render();

        $this->assertStringContainsString($this->booking->user->name, $html);
        $this->assertStringContainsString(route('video-call.join', $this->booking), $html);
        $this->assertStringContainsString($this->booking->consultant->name, (new ParticipantJoined($this->booking, 'client'))->envelope()->subject);
    }

    // ---- dashboard badges ------------------------------------------------------

    public function test_dashboards_show_who_is_already_in_the_session(): void
    {
        $this->at('2026-10-10 09:55');

        $this->actingAs($this->booking->consultant->user)->get(route('consultant.dashboard'))
            ->assertOk()->assertDontSee('العميل في الجلسة الآن');

        $this->joinAsClient();

        $this->actingAs($this->booking->consultant->user)->get(route('consultant.dashboard'))
            ->assertOk()->assertSee('العميل في الجلسة الآن');

        $this->actingAs($this->booking->user)->get(route('client.dashboard'))
            ->assertOk()->assertDontSee('المستشار في الجلسة الآن');

        $this->joinAsConsultant();

        $this->actingAs($this->booking->user)->get(route('client.dashboard'))
            ->assertOk()->assertSee('المستشار في الجلسة الآن');
    }

    public function test_badge_disappears_after_the_join_window(): void
    {
        $this->at('2026-10-10 09:55');
        $this->joinAsClient();

        $this->at('2026-10-10 11:05');

        $this->assertFalse($this->booking->fresh()->otherPartyInSession(true));
    }

    // ---- "room is open" email --------------------------------------------------

    public function test_room_open_email_goes_to_both_once_when_the_room_opens(): void
    {
        $this->at('2026-10-10 09:50');
        $this->artisan('sessions:send-reminders')->assertSuccessful();
        $this->at('2026-10-10 09:55');
        $this->artisan('sessions:send-reminders')->assertSuccessful();

        $open = Mail::queued(SessionReminder::class, fn ($m) => $m->kind === 'open');
        $this->assertCount(2, $open);
        Mail::assertQueued(SessionReminder::class, fn ($m) => $m->kind === 'open' && $m->hasTo($this->booking->user->email));
        Mail::assertQueued(SessionReminder::class, fn ($m) => $m->kind === 'open' && $m->hasTo($this->booking->consultant->user->email));
        // Too late for the other reminders: they are not sent after this one.
        Mail::assertNotQueued(SessionReminder::class, fn ($m) => $m->kind !== 'open');
        $this->assertNotNull($this->booking->fresh()->reminder_1h_sent_at);
    }

    public function test_room_open_email_is_not_sent_before_the_room_opens(): void
    {
        $this->at('2026-10-10 09:44');
        $this->artisan('sessions:send-reminders')->assertSuccessful();

        Mail::assertNotQueued(SessionReminder::class, fn ($m) => $m->kind === 'open');
    }

    public function test_room_open_email_skips_whoever_is_already_in_the_room(): void
    {
        $this->at('2026-10-10 09:51');
        $this->joinAsConsultant();
        $this->artisan('sessions:send-reminders')->assertSuccessful();

        Mail::assertQueued(SessionReminder::class, fn ($m) => $m->kind === 'open' && $m->recipientType === 'client');
        Mail::assertNotQueued(SessionReminder::class, fn ($m) => $m->kind === 'open' && $m->recipientType === 'consultant');
    }

    public function test_room_open_email_is_not_sent_once_the_session_started(): void
    {
        $this->at('2026-10-10 10:05');
        $this->artisan('sessions:send-reminders')->assertSuccessful();

        Mail::assertNotQueued(SessionReminder::class);
    }

    public function test_room_open_email_links_to_the_session(): void
    {
        $mail = new SessionReminder($this->booking, 'client', 'open');

        $this->assertSame('غرفة جلستك الاستشارية مفتوحة الآن', $mail->envelope()->subject);
        $this->assertStringContainsString(route('video-call.join', $this->booking), $mail->render());
        $this->assertSame([], $mail->attachments());
    }
}
