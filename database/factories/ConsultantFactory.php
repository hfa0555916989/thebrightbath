<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Consultant>
 */
class ConsultantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->counselor(),
            'specialization' => 'Career Counseling',
            'specialization_ar' => 'الإرشاد المهني',
            'bio' => fake()->sentence(12),
            'bio_ar' => 'مستشار متخصص في الإرشاد المهني.',
            'price_per_30_min' => 115.00,
            'price_per_60_min' => 200.00,
            'experience_years' => fake()->numberBetween(1, 20),
            'meeting_link' => null,
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
