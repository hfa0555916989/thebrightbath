<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultantSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultant_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Days of week in Arabic.
     */
    public static array $daysArabic = [
        0 => 'الأحد',
        1 => 'الإثنين',
        2 => 'الثلاثاء',
        3 => 'الأربعاء',
        4 => 'الخميس',
        5 => 'الجمعة',
        6 => 'السبت',
    ];

    /**
     * Get the consultant that owns the schedule.
     */
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    /**
     * Get day name in Arabic.
     */
    public function getDayNameAttribute(): string
    {
        return self::$daysArabic[$this->day_of_week] ?? '';
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute(): string
    {
        return date('h:i A', strtotime($this->start_time)) . ' - ' . date('h:i A', strtotime($this->end_time));
    }
}





