<?php

namespace Database\Factories;

use App\Models\Competition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);
        $categories = ['event_dcc', 'event_debate', 'event_scientific_paper'];
        $category = fake()->randomElement($categories);
        $types = ['individual', 'team', 'group'];
        $type = fake()->randomElement($types);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraph(),
            'category' => $category,
            'type' => $type,
            'status' => 'active',

            // Pricing (sesuai database schema)
            'price' => 200000,
            'price_unas_student' => 150000,
            'price_external_student' => 200000,
            'early_bird_price' => 100000,

            // Registration dates
            'registration_start' => now(),
            'registration_end' => now()->addDays(30),
            'early_bird_deadline' => now()->addDays(10),

            // Competition dates
            'competition_start' => now()->addDays(31),
            'competition_end' => now()->addDays(60),
            'submission_start' => now()->addDays(31),
            'submission_end' => now()->addDays(60),

            // Team configuration
            'max_team_members' => 3,
            'min_team_members' => 1,

            // Additional fields
            'is_active' => true,
            'is_team_competition' => $type === 'team',
            'allow_individual' => $type === 'individual',
            'prize_amount' => 5000000,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}

