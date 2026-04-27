<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'order_id',
        'payable_type',
        'payable_id',
        'amount',
        'amount_cents',
        'currency',
        'status',
        'payment_method',
        'card_type',
        'card_last_four',
        'gateway_response',
        'error_message',
        'customer_email',
        'customer_phone',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_cents' => 'integer',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the parent payable model (booking, etc.)
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for successful transactions
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark as successful
     */
    public function markAsSuccessful(array $gatewayResponse = []): self
    {
        $this->update([
            'status' => 'success',
            'paid_at' => now(),
            'gateway_response' => $gatewayResponse,
        ]);

        return $this;
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $errorMessage, array $gatewayResponse = []): self
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'gateway_response' => $gatewayResponse,
        ]);

        return $this;
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'success' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            'refunded' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get status label in Arabic
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'success' => 'ناجحة',
            'pending' => 'قيد الانتظار',
            'failed' => 'فاشلة',
            'refunded' => 'مستردة',
            default => 'غير معروف',
        };
    }
}
