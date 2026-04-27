<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'booking_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'gateway',
        'gateway_transaction_id',
        'gateway_response',
        'card_brand',
        'card_last_four',
        'description',
        'ip_address',
        'user_agent',
        'completed_at',
        'refunded_at',
        'refund_amount',
        'refund_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'completed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    /**
     * Status labels in Arabic.
     */
    public static array $statusLabels = [
        'pending' => 'في الانتظار',
        'processing' => 'قيد المعالجة',
        'completed' => 'مكتمل',
        'failed' => 'فشل',
        'refunded' => 'مسترد',
        'cancelled' => 'ملغي',
    ];

    /**
     * Boot method.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->payment_id) {
                $payment->payment_id = 'PAY-' . strtoupper(uniqid()) . '-' . time();
            }
        });
    }

    /**
     * Get the booking for this payment.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who made the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status label in Arabic.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment can be refunded.
     */
    public function canBeRefunded(): bool
    {
        return $this->status === 'completed' && !$this->refunded_at;
    }
}





