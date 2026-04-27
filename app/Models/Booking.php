<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'consultant_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'price',
        'status',
        'client_notes',
        'consultant_notes',
        'meeting_link',
        'payment_status',
        'payment_method',
        'transaction_id',
        'paid_at',
        'cancelled_at',
        'cancellation_reason',
        'consultant_earnings',
        'admin_earnings',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'price' => 'decimal:2',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Status labels in Arabic.
     */
    public static array $statusLabels = [
        'pending_approval' => 'بانتظار موافقة المستشار',
        'approved' => 'تمت الموافقة - بانتظار الدفع',
        'pending' => 'في الانتظار',
        'confirmed' => 'مؤكد',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'rejected' => 'مرفوض',
        'no_show' => 'لم يحضر',
    ];

    /**
     * Payment status labels in Arabic.
     */
    public static array $paymentStatusLabels = [
        'pending' => 'في انتظار الدفع',
        'paid' => 'مدفوع',
        'refunded' => 'مسترد',
        'failed' => 'فشل',
    ];

    /**
     * Boot method.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'BK-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the client that made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the consultant for the booking.
     */
    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    /**
     * Get the payment for the booking.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get status label in Arabic.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Get payment status label in Arabic.
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return self::$paymentStatusLabels[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Get formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->booking_date->format('Y-m-d');
    }

    /**
     * Get formatted time.
     */
    public function getFormattedTimeAttribute(): string
    {
        return date('h:i A', strtotime($this->start_time)) . ' - ' . date('h:i A', strtotime($this->end_time));
    }

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        if (in_array($this->status, ['cancelled', 'completed', 'no_show'])) {
            return false;
        }

        // Can cancel up to 2 hours before the booking
        $bookingDateTime = $this->booking_date->format('Y-m-d') . ' ' . $this->start_time;
        $cancelDeadline = strtotime($bookingDateTime) - (2 * 60 * 60);

        return time() < $cancelDeadline;
    }

    /**
     * Scope for upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'pending_approval', 'approved', 'confirmed'])
            ->orderBy('booking_date')
            ->orderBy('start_time');
    }

    /**
     * Scope for past bookings.
     */
    public function scopePast($query)
    {
        return $query->where(function ($q) {
            $q->where('booking_date', '<', now()->toDateString())
              ->orWhere('status', 'completed');
        })
        ->orderByDesc('booking_date')
        ->orderByDesc('start_time');
    }
}





