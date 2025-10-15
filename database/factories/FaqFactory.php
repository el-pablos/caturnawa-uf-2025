<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => fake()->sentence() . '?',
            'question_en' => fake()->sentence() . '?',
            'question_id' => fake()->sentence() . '?',
            'answer' => fake()->paragraph(),
            'answer_en' => fake()->paragraph(),
            'answer_id' => fake()->paragraph(),
            'order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
