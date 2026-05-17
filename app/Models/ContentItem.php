<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContentItem extends Model
{
    protected $fillable = [
        'type', 'page', 'title', 'subtitle', 'body',
        'image', 'icon', 'color', 'link', 'order', 'is_active', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function getImageUrlAttribute(): string
    {
        return storage_asset($this->image);
    }

    public function getMeta(string $key, $default = null): mixed
    {
        return $this->meta[$key] ?? $default;
    }
}
