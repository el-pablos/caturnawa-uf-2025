<?php

namespace Database\Factories;

use App\Models\Registration;
use App\Models\User;
use App\Models\Competition;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'competition_id' => Competition::factory(),
            'registration_number' => 'UF' . date('Y') . '-' . date('m') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'team_name' => fake()->company(),
            'team_members' => null,
            'institution' => fake()->company(),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(['male', 'female']),
            'education_level' => 'university',
            'participant_category' => 'external_student',
            'pricing_phase' => 'regular',
            'amount' => 200000,
            'original_price' => 200000,
            'status' => 'pending',
            'is_locked' => false,
            'lock_reason' => null,
            'locked_at' => null,
            'locked_by' => null,
            'registered_at' => now(),
            'confirmed_at' => null,
            'cancelled_at' => null,
            'cancelled_reason' => null,
            'reopened_at' => null,
            'reopened_by' => null,
            'ticket_code' => 'TICKET-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'qr_code' => null,
            'dynamic_data' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'confirmed_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_reason' => 'Cancelled by user',
        ]);
    }

    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_locked' => true,
        ]);
    }
}

