<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'specialization',
        'specialization_ar',
        'bio',
        'bio_ar',
        'photo',
        'price_per_30_min',
        'price_per_60_min',
        'hourly_rate',
        'session_duration',
        'experience_years',
        'certifications',
        'meeting_link',
        'is_active',
        'is_featured',
        'total_sessions',
        'rating',
        'reviews_count',
        'commission_rate',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_iban',
    ];

    protected $casts = [
        'certifications' => 'array',
        'price_per_30_min' => 'decimal:2',
        'price_per_60_min' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the user that owns the consultant profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the schedules for the consultant.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(ConsultantSchedule::class);
    }

    /**
     * Get the bookings for the consultant.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get available schedules for a specific day.
     */
    public function getScheduleForDay(int $dayOfWeek)
    {
        return $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->get();
    }

    /**
     * Check if consultant is available at a specific date and time.
     */
    public function isAvailableAt($date, $startTime, $endTime): bool
    {
        $dayOfWeek = date('w', strtotime($date));
        
        // Check if schedule allows
        $hasSchedule = $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->exists();

        if (!$hasSchedule) {
            return false;
        }

        // Check if no conflicting booking exists
        $hasConflict = $this->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'pending_approval', 'approved', 'confirmed'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        return !$hasConflict;
    }

    /**
     * Scope for active consultants.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured consultants.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}



