<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionTimeline>
 */
class CompetitionTimelineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'competition_id' => \App\Models\Competition::factory(),
            'month' => fake()->randomElement(['January', 'February', 'March', 'April', 'May']),
            'day' => fake()->numberBetween(1, 28),
            'year' => 2025,
            'title' => fake()->sentence(),
            'title_en' => fake()->sentence(),
            'title_id' => fake()->sentence(),
            'order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
