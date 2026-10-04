<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Models\VideoCall;
use App\Models\VideoCallMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DailySessionTest extends TestCase
{
    use RefreshDatabase;

    private const ROOM_URL = 'https://thebrightbath.daily.co/bp-room';

    private Booking $booking;

    /** Requests the fake Daily API received. */
    private array $dailyRequests = [];

    private bool $dailyFails = false;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.daily.key' => 'test-daily-key']);

        Http::fake(function (HttpRequest $request) {
            $this->dailyRequests[] = $request;

            if ($this->dailyFails) {
                return Http::response(['error' => 'server-error'], 500);
            }

            return match (true) {
                $request->method() === 'POST' && str_ends_with($request->url(), '/rooms') => Http::response([
                    'name' => $request['name'],
                    'url' => self::ROOM_URL,
                    'privacy' => 'private',
                ]),
                str_ends_with($request->url(), '/meeting-tokens') => Http::response(['token' => 'token-for-'.$request['properties']['user_id']]),
                $request->method() === 'DELETE' => Http::response(['deleted' => true]),
                default => Http::response([], 404),
            };
        });

        // Session on 10 Oct 2026, 10:00-10:30 Riyadh time: room open 09:50-11:00.
        $this->booking = Booking::factory()->confirmed()->create([
            'booking_date' => '2026-10-10',
            'start_time' => '10:00',
            'end_time' => '10:30',
        ]);
    }

    private function at(string $time): void
    {
        $this->travelTo(Carbon::parse("2026-10-10 {$time}", 'Asia/Riyadh'));
    }

    private function client(): User
    {
        return $this->booking->user;
    }

    private function consultant(): User
    {
        return $this->booking->consultant->user;
    }

    private function roomRequests(): array
    {
        return array_values(array_filter($this->dailyRequests, fn ($r) => $r->method() === 'POST' && str_ends_with($r->url(), '/rooms')));
    }

    private function tokenRequests(): array
    {
        return array_values(array_filter($this->dailyRequests, fn ($r) => str_ends_with($r->url(), '/meeting-tokens')));
    }

    // ── Join window ──────────────────────────────────────────────────────

    public function test_before_the_window_the_page_waits_and_no_room_is_created(): void
    {
        $this->at('09:40');

        $this->actingAs($this->client())
            ->get(route('video-call.join', $this->booking))
            ->assertOk()
            ->assertViewHas('state', 'waiting')
            ->assertSee('الجلسة لم تبدأ بعد');

        $this->assertSame([], $this->dailyRequests);
    }

    public function test_inside_the_window_client_gets_a_private_room_and_a_guest_token(): void
    {
        $this->at('09:55');

        $this->actingAs($this->client())
            ->get(route('video-call.join', $this->booking))
            ->assertOk()
            ->assertViewHas('state', 'live')
            ->assertViewHas('roomUrl', self::ROOM_URL)
            ->assertViewHas('token', 'token-for-'.$this->client()->id)
            ->assertSee('daily-frame', false);

        $room = $this->roomRequests()[0];
        $this->assertSame('Bearer test-daily-key', $room->header('Authorization')[0]);
        $this->assertSame('private', $room['privacy']);
        $this->assertSame(Carbon::parse('2026-10-10 09:50', 'Asia/Riyadh')->timestamp, $room['properties']['nbf']);
        $this->assertSame(Carbon::parse('2026-10-10 11:00', 'Asia/Riyadh')->timestamp, $room['properties']['exp']);
        $this->assertTrue($room['properties']['eject_at_room_exp']);

        $token = $this->tokenRequests()[0];
        $this->assertFalse($token['properties']['is_owner']);
        $this->assertSame($room['name'], $token['properties']['room_name']);
        $this->assertSame($room['properties']['exp'], $token['properties']['exp']);

        $call = VideoCall::sole();
        $this->assertSame(self::ROOM_URL, $call->daily_room_url);
        $this->assertSame('active', $call->status);
    }

    public function test_room_is_created_once_and_the_consultant_joins_as_owner(): void
    {
        $this->at('09:55');

        $this->actingAs($this->client())->get(route('video-call.join', $this->booking))->assertOk();
        $this->actingAs($this->consultant())->get(route('video-call.join', $this->booking))
            ->assertOk()
            ->assertViewHas('token', 'token-for-'.$this->consultant()->id);

        $this->assertCount(1, $this->roomRequests());
        $this->assertTrue($this->tokenRequests()[1]['properties']['is_owner']);
    }

    public function test_after_the_window_the_session_is_ended(): void
    {
        $this->at('11:01');

        $this->actingAs($this->client())
            ->get(route('video-call.join', $this->booking))
            ->assertOk()
            ->assertViewHas('state', 'ended');

        $this->assertSame([], $this->dailyRequests);
    }

    public function test_completed_session_shows_ended_even_inside_the_window(): void
    {
        $this->at('10:10');
        $this->booking->update(['status' => 'completed']);

        $this->actingAs($this->client())
            ->get(route('video-call.join', $this->booking))
            ->assertViewHas('state', 'ended');
    }

    public function test_missing_daily_key_shows_unavailable_instead_of_an_error(): void
    {
        $this->at('09:55');
        config(['services.daily.key' => null]);

        $this->actingAs($this->client())
            ->get(route('video-call.join', $this->booking))
            ->assertOk()
            ->assertViewHas('state', 'unavailable');

        $this->assertSame([], $this->dailyRequests);
    }

    public function test_daily_outage_shows_unavailable_instead_of_an_error(): void
    {
        $this->at('09:55');
        $this->dailyFails = true;

        $this->actingAs($this->client())
            ->get(route('video-call.join', $this->booking))
            ->assertOk()
            ->assertViewHas('state', 'unavailable');

        $this->assertNull(VideoCall::sole()->daily_room_url);
    }

    // ── Access ───────────────────────────────────────────────────────────

    public function test_outsider_cannot_open_the_session(): void
    {
        $this->at('09:55');

        $this->actingAs(User::factory()->create())
            ->get(route('video-call.join', $this->booking))
            ->assertForbidden();

        $this->assertSame([], $this->dailyRequests);
    }

    public function test_unpaid_booking_is_sent_to_payment(): void
    {
        $this->at('09:55');
        $this->booking->update(['status' => 'approved', 'payment_status' => 'pending']);

        $this->actingAs($this->client())
            ->get(route('video-call.join', $this->booking))
            ->assertRedirect(route('consultations.payment', $this->booking));
    }

    // ── Ending ───────────────────────────────────────────────────────────

    public function test_consultant_ending_completes_the_booking_and_closes_the_room(): void
    {
        $this->at('09:55');
        $this->actingAs($this->client())->get(route('video-call.join', $this->booking));

        $this->actingAs($this->consultant())
            ->post(route('video-call.end', $this->booking))
            ->assertRedirect(route('consultant.dashboard'));

        $this->assertSame('completed', $this->booking->fresh()->status);
        $this->assertSame('ended', VideoCall::sole()->status);
        $this->assertNotEmpty(array_filter($this->dailyRequests, fn ($r) => $r->method() === 'DELETE'));
    }

    public function test_client_leaving_does_not_end_the_session(): void
    {
        $this->at('09:55');
        $this->actingAs($this->client())->get(route('video-call.join', $this->booking));

        $this->actingAs($this->client())
            ->post(route('video-call.end', $this->booking))
            ->assertRedirect(route('client.dashboard'));

        $this->assertSame('confirmed', $this->booking->fresh()->status);
        $this->assertEmpty(array_filter($this->dailyRequests, fn ($r) => $r->method() === 'DELETE'));
    }

    // ── Shared files ─────────────────────────────────────────────────────

    public function test_participants_share_files_privately(): void
    {
        Storage::fake('private');
        $call = VideoCall::getOrCreateForBooking($this->booking);

        $this->actingAs($this->client())
            ->postJson(route('video-call.upload-file', $call), ['file' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf')])
            ->assertOk()
            ->assertJsonPath('file.name', 'cv.pdf');

        $message = VideoCallMessage::sole();
        Storage::disk('private')->assertExists($message->file_path);
        $this->assertStringStartsWith('session-files/'.$call->id.'/', $message->file_path);

        $this->actingAs($this->consultant())
            ->getJson(route('video-call.files', $call))
            ->assertOk()
            ->assertJsonPath('files.0.name', 'cv.pdf')
            ->assertJsonPath('files.0.mine', false);

        $this->actingAs($this->consultant())
            ->get(route('video-call.download-file', $message))
            ->assertOk()
            ->assertDownload('cv.pdf');
    }

    public function test_outsider_cannot_list_upload_or_download_session_files(): void
    {
        Storage::fake('private');
        $call = VideoCall::getOrCreateForBooking($this->booking);
        $this->actingAs($this->client())
            ->postJson(route('video-call.upload-file', $call), ['file' => UploadedFile::fake()->create('cv.pdf', 10, 'application/pdf')]);
        $message = VideoCallMessage::sole();

        $this->actingAs(User::factory()->create());
        $this->getJson(route('video-call.files', $call))->assertForbidden();
        $this->get(route('video-call.download-file', $message))->assertForbidden();
        $this->postJson(route('video-call.upload-file', $call), ['file' => UploadedFile::fake()->create('x.pdf', 10, 'application/pdf')])
            ->assertForbidden();

        $this->assertSame(1, VideoCallMessage::count());
    }

    public function test_executable_files_are_refused(): void
    {
        Storage::fake('private');
        $call = VideoCall::getOrCreateForBooking($this->booking);

        $this->actingAs($this->client())
            ->postJson(route('video-call.upload-file', $call), ['file' => UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;')])
            ->assertStatus(422);

        $this->assertSame(0, VideoCallMessage::count());
    }

    // ── Old WebRTC endpoints are gone ────────────────────────────────────

    public function test_old_webrtc_signalling_endpoints_no_longer_exist(): void
    {
        $call = VideoCall::getOrCreateForBooking($this->booking);
        $this->actingAs($this->client());

        $this->post("/video-call/{$call->id}/signal")->assertNotFound();
        $this->get("/video-call/{$call->id}/signals")->assertNotFound();
        $this->get("/video-call/{$call->id}/messages")->assertNotFound();
    }

    public function test_framework_service_defaults_survive_the_app_services_config(): void
    {
        // config/services.php only adds "daily"; Resend must still read RESEND_KEY.
        $this->assertArrayHasKey('key', config('services.resend'));
        $this->assertArrayHasKey('key', config('services.daily'));
    }
}
