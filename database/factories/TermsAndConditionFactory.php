<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TermsAndCondition>
 */
class TermsAndConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'title_en' => fake()->sentence(),
            'title_id' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'content_en' => fake()->paragraphs(3, true),
            'content_id' => fake()->paragraphs(3, true),
            'type' => fake()->randomElement(['general', 'competition', 'privacy', 'payment']),
            'order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
