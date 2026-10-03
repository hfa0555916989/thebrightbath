<?php

namespace Database\Factories;

use App\Models\Consultant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Default state is a fresh request awaiting consultant approval (status pending_approval),
 * which is what ConsultationController::book() creates.
 *
 * @extends Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'consultant_id' => Consultant::factory(),
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '10:30',
            'duration_minutes' => 30,
            'price' => 115.00,
            'status' => 'pending_approval',
            'payment_status' => 'pending',
        ];
    }

    /** Awaiting consultant approval (same as the default state). */
    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending_approval', 'payment_status' => 'pending']);
    }

    /** Approved by the consultant, awaiting payment. */
    public function approved(): static
    {
        return $this->state(fn () => ['status' => 'approved', 'payment_status' => 'pending']);
    }

    /** Paid and confirmed. */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
            'consultant_earnings' => round($attributes['price'] * 0.8, 2),
            'admin_earnings' => round($attributes['price'] * 0.2, 2),
        ]);
    }

    public function completed(): static
    {
        return $this->confirmed()->state(fn () => [
            'status' => 'completed',
            'booking_date' => now()->subDays(3)->toDateString(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => 'rejected']);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => 'إلغاء من قبل العميل',
        ]);
    }
}
