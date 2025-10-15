<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sponsor>
 */
class SponsorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'logo' => null,
            'website' => fake()->url(),
            'type' => fake()->randomElement(['platinum', 'gold', 'silver', 'bronze', 'media_partner']),
            'order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
