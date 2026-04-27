<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'config_json',
        'icon',
        'estimated_minutes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'config_json' => 'array',
        ];
    }

    /**
     * Get the questions for this assessment
     */
    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class)->orderBy('order');
    }

    /**
     * Get all attempts for this assessment
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    /**
     * Get the scale configuration
     */
    public function getScaleAttribute(): array
    {
        return $this->config_json['scale'] ?? [
            1 => 'لا أوافق بشدة',
            2 => 'لا أوافق',
            3 => 'محايد',
            4 => 'أوافق',
            5 => 'أوافق بشدة',
        ];
    }

    /**
     * Get the dimensions for this assessment
     */
    public function getDimensionsAttribute(): array
    {
        return $this->config_json['dimensions'] ?? [];
    }

    /**
     * Get interpretations configuration
     */
    public function getInterpretationsAttribute(): array
    {
        return $this->config_json['interpretations'] ?? [];
    }

    /**
     * Scope to get active assessments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}






