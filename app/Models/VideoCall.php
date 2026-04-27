<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class VideoCall extends Model
{
    protected $fillable = [
        'booking_id',
        'room_token',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($videoCall) {
            if (!$videoCall->room_token) {
                $videoCall->room_token = Str::random(32);
            }
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function signals(): HasMany
    {
        return $this->hasMany(VideoCallSignal::class);
    }

    public static function getOrCreateForBooking(Booking $booking): self
    {
        return self::firstOrCreate(
            ['booking_id' => $booking->id],
            ['status' => 'waiting']
        );
    }

    public function start(): void
    {
        $this->update([
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    public function end(): void
    {
        $this->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);
        $this->signals()->delete();
    }
}
