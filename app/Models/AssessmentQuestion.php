<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'code',
        'text_ar',
        'dimension_key',
        'order',
        'options_json',
    ];

    protected function casts(): array
    {
        return [
            'options_json' => 'array',
        ];
    }

    /**
     * Get the assessment this question belongs to
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get custom options if available, otherwise return null
     */
    public function getOptionsAttribute(): ?array
    {
        return $this->options_json;
    }
}






