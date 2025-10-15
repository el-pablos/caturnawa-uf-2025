<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactInformation>
 */
class ContactInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->email(),
            'whatsapp' => '+62812345678',
            'instagram' => '@caturnawa',
            'tiktok' => '@caturnawa',
            'youtube' => '@caturnawa',
            'address' => fake()->address(),
            'is_active' => true,
        ];
    }
}
