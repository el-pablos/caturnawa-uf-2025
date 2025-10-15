<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition(): array
    {
        $filename = fake()->uuid() . '.pdf';
        $fileSize = fake()->numberBetween(100000, 5000000);

        return [
            'registration_id' => Registration::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'files' => [
                [
                    'filename' => $filename,
                    'original_name' => 'submission_' . fake()->word() . '.pdf',
                    'path' => 'submissions/' . $filename,
                    'size' => $fileSize,
                    'mime_type' => 'application/pdf',
                    'uploaded_at' => now()->toDateTimeString(),
                ]
            ],
            'file_size' => $fileSize,
            'is_final' => false,
            'submitted_at' => null,
            'status' => 'draft',
            'submission_notes' => fake()->optional()->sentence(),
        ];
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_final' => true,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_final' => false,
            'submitted_at' => null,
            'status' => 'draft',
        ]);
    }
}

