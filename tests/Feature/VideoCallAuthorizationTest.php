<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Models\VideoCall;
use App\Models\VideoCallMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoCallAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private Booking $booking;

    private VideoCall $videoCall;

    protected function setUp(): void
    {
        parent::setUp();

        $this->booking = Booking::factory()->confirmed()->create();
        $this->videoCall = VideoCall::getOrCreateForBooking($this->booking);
    }

    public function test_outsider_cannot_read_or_write_session_data(): void
    {
        Storage::fake('public');
        VideoCallMessage::create([
            'video_call_id' => $this->videoCall->id,
            'user_id' => $this->booking->user_id,
            'type' => 'text',
            'content' => 'سري',
        ]);

        $this->actingAs(User::factory()->create());

        $this->getJson(route('video-call.get-messages', $this->videoCall))->assertForbidden();
        $this->getJson(route('video-call.get-signals', $this->videoCall))->assertForbidden();
        $this->getJson(route('video-call.status', $this->videoCall))->assertForbidden();
        $this->postJson(route('video-call.send-message', $this->videoCall), ['content' => 'hi'])->assertForbidden();
        $this->postJson(route('video-call.signal', $this->videoCall), [
            'type' => 'offer', 'data' => '{}', 'to_user_id' => $this->booking->user_id,
        ])->assertForbidden();
        $this->postJson(route('video-call.upload-file', $this->videoCall), [
            'file' => UploadedFile::fake()->create('notes.pdf', 10),
        ])->assertForbidden();

        $this->assertSame(1, VideoCallMessage::count());
        Storage::disk('public')->assertDirectoryEmpty('/');
    }

    public function test_client_and_consultant_can_chat(): void
    {
        $this->actingAs($this->booking->user)
            ->postJson(route('video-call.send-message', $this->videoCall), ['content' => 'مرحبا'])
            ->assertOk();

        $this->actingAs($this->booking->consultant->user)
            ->getJson(route('video-call.get-messages', $this->videoCall))
            ->assertOk()
            ->assertJsonPath('messages.0.content', 'مرحبا');
    }

    public function test_participant_can_signal_the_other_participant_only(): void
    {
        $this->actingAs($this->booking->user);

        $this->postJson(route('video-call.signal', $this->videoCall), [
            'type' => 'offer', 'data' => '{}', 'to_user_id' => $this->booking->consultant->user_id,
        ])->assertOk();

        $this->postJson(route('video-call.signal', $this->videoCall), [
            'type' => 'offer', 'data' => '{}', 'to_user_id' => User::factory()->create()->id,
        ])->assertStatus(422);
    }
}
