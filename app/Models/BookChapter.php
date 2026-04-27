<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'order',
        'is_free',
        'content_html',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Scope published chapters
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope ordered chapters
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Check if user can access this chapter
     */
    public function canAccess(?User $user): bool
    {
        if ($this->is_free) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->has_book_access || $user->isAdmin();
    }

    /**
     * Get access status label
     */
    public function getAccessLabelAttribute(): string
    {
        return $this->is_free ? 'مجاني' : 'مدفوع';
    }
}






