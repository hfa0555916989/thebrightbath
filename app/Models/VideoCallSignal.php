<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoCallSignal extends Model
{
    protected $fillable = [
        'video_call_id',
        'from_user_id',
        'to_user_id',
        'type',
        'data',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function videoCall(): BelongsTo
    {
        return $this->belongsTo(VideoCall::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}


