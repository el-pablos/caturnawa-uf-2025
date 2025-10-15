<?php

namespace Database\Factories;

use App\Models\Score;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    protected $model = Score::class;

    public function definition(): array
    {
        $score1 = fake()->numberBetween(60, 100);
        $score2 = fake()->numberBetween(60, 100);
        $score3 = fake()->numberBetween(60, 100);

        return [
            'registration_id' => Registration::factory(),
            'competition_id' => Competition::factory(),
            'jury_id' => User::factory(),
            'criteria_scores' => [
                'originality' => $score1,
                'methodology' => $score2,
                'impact' => $score3,
            ],
            'total_score' => round(($score1 + $score2 + $score3) / 3, 2),
            'comments' => fake()->sentence(),
            'is_final' => false,
        ];
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_final' => true,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_final' => false,
        ]);
    }
}

