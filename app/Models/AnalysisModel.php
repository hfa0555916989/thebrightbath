<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AnalysisModel extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'original_file_name',
        'file_path',
        'structure',
        'data',
        'settings',
        'is_active',
        'is_featured',
        'order',
        'views_count',
        'downloads_count',
    ];

    protected $casts = [
        'structure' => 'array',
        'data' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Get active models
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get featured models
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get models ordered by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment download count
     */
    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    /**
     * Get the route key name
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get headers from structure
     */
    public function getHeaders(): array
    {
        return $this->structure['headers'] ?? [];
    }

    /**
     * Get rows from data
     */
    public function getRows(): array
    {
        return $this->data['rows'] ?? [];
    }

    /**
     * Get column types
     */
    public function getColumnTypes(): array
    {
        return $this->structure['column_types'] ?? [];
    }
}
