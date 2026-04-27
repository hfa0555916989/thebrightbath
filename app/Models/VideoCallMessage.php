<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoCallMessage extends Model
{
    protected $fillable = [
        'video_call_id',
        'user_id',
        'type',
        'content',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function videoCall(): BelongsTo
    {
        return $this->belongsTo(VideoCall::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '';
        
        if ($this->file_size < 1024) {
            return $this->file_size . ' B';
        } elseif ($this->file_size < 1048576) {
            return round($this->file_size / 1024, 1) . ' KB';
        } else {
            return round($this->file_size / 1048576, 1) . ' MB';
        }
    }
}


