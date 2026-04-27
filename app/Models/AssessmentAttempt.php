<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'assessment_id',
        'assessment_slug',
        'assessment_name',
        'answers_json',
        'scores_json',
        'summary_level',
        'type_code',
        'summary',
        'report_path',
        'status',
        'counselor_id',
        'counselor_notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'answers_json' => 'array',
        'scores_json' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the counselor assigned to this attempt.
     */
    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    /**
     * Get the client name (user name or guest name)
     */
    public function getClientNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->guest_name ?? 'زائر';
    }

    /**
     * Get the client email
     */
    public function getClientEmailAttribute()
    {
        if ($this->user) {
            return $this->user->email;
        }
        return $this->guest_email;
    }

    /**
     * Get the client phone
     */
    public function getClientPhoneAttribute()
    {
        if ($this->user) {
            return $this->user->phone ?? null;
        }
        return $this->guest_phone;
    }
    
    /**
     * Get the type code (from either type_code or summary_level)
     */
    public function getTypeCodeAttribute($value)
    {
        return $value ?? $this->summary_level;
    }

    /**
     * Scope for filtering by assessment type
     */
    public function scopeOfAssessment($query, $slug)
    {
        return $query->where('assessment_slug', $slug);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        // Handle both old and new status values
        if ($status === 'completed') {
            return $query->whereIn('status', ['new', 'completed']);
        }
        return $query->where('status', $status);
    }

    /**
     * Scope for today's attempts
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for this week's attempts
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for this month's attempts
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
