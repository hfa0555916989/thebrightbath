<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory()->confirmed(),
            'user_id' => fn (array $attributes) => Booking::find($attributes['booking_id'])->user_id,
            'amount' => fn (array $attributes) => Booking::find($attributes['booking_id'])->price,
            'currency' => 'SAR',
            'status' => 'pending',
            'payment_method' => 'card',
            'gateway' => 'paymob',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'gateway_transaction_id' => (string) fake()->randomNumber(9, true),
            'completed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => ['status' => 'failed']);
    }

    public function refunded(): static
    {
        return $this->completed()->state(fn (array $attributes) => [
            'status' => 'refunded',
            'refunded_at' => now(),
            'refund_amount' => $attributes['amount'],
        ]);
    }
}
