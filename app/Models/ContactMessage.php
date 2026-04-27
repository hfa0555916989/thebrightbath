<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    /**
     * Scope new messages
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Get status label in Arabic
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'new' => 'جديد',
            'read' => 'مقروء',
            'replied' => 'تم الرد',
            default => $this->status,
        };
    }
}






