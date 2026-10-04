<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Models\VideoCall;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Video sessions on Daily.co (REST API: https://docs.daily.co/reference/rest-api).
 *
 * Each booking gets one private room, created the first time a participant opens
 * the session page. A private room can only be entered with a meeting token, and
 * tokens are issued by our server to the booking's client and consultant only,
 * valid only inside the booking's join window.
 */
class DailyService
{
    public function isConfigured(): bool
    {
        return filled(config('services.daily.key'));
    }

    /**
     * The booking's session with its Daily room, creating the room on first use.
     */
    public function roomFor(Booking $booking): VideoCall
    {
        $call = VideoCall::getOrCreateForBooking($booking);

        if ($call->daily_room_url) {
            return $call;
        }

        // Lock so the client and consultant arriving together don't create two rooms.
        return DB::transaction(function () use ($call, $booking) {
            $call = VideoCall::whereKey($call->id)->lockForUpdate()->first();

            if ($call->daily_room_url) {
                return $call;
            }

            $room = $this->api()->post('rooms', [
                'name' => 'bp-'.$booking->id.'-'.Str::lower(Str::random(10)),
                'privacy' => 'private',
                'properties' => [
                    'nbf' => $booking->joinOpensAt()->timestamp,
                    'exp' => $booking->joinClosesAt()->timestamp,
                    'eject_at_room_exp' => true,
                    'max_participants' => 4,
                    'enable_chat' => true,
                    'enable_screenshare' => true,
                    'enable_prejoin_ui' => true,
                ],
            ])->throw()->json();

            $call->update([
                'daily_room_name' => $room['name'],
                'daily_room_url' => $room['url'],
            ]);

            return $call;
        });
    }

    /**
     * A meeting token for one participant, valid only during the join window.
     */
    public function tokenFor(VideoCall $call, User $user, bool $isConsultant): string
    {
        $booking = $call->booking;

        return $this->api()->post('meeting-tokens', [
            'properties' => [
                'room_name' => $call->daily_room_name,
                'user_name' => $user->name,
                'user_id' => (string) $user->id,
                'is_owner' => $isConsultant,
                'nbf' => $booking->joinOpensAt()->timestamp,
                'exp' => $booking->joinClosesAt()->timestamp,
                'eject_at_token_exp' => true,
            ],
        ])->throw()->json('token');
    }

    /**
     * Close the room for everyone (consultant ended the session). Best effort:
     * rooms also expire on their own at the end of the join window.
     */
    public function deleteRoom(VideoCall $call): void
    {
        if (!$call->daily_room_name || !$this->isConfigured()) {
            return;
        }

        try {
            $response = $this->api()->delete('rooms/'.$call->daily_room_name);

            if ($response->failed() && $response->status() !== 404) {
                Log::warning('Daily: room deletion failed', ['room' => $call->daily_room_name, 'status' => $response->status()]);
            }
        } catch (\Throwable $e) {
            Log::warning('Daily: room deletion failed', ['room' => $call->daily_room_name, 'error' => $e->getMessage()]);
        }
    }

    private function api(): PendingRequest
    {
        return Http::baseUrl(rtrim(config('services.daily.api_url'), '/'))
            ->withToken(config('services.daily.key'))
            ->acceptJson()
            ->timeout(15);
    }
}
