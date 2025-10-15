<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionRequirement>
 */
class CompetitionRequirementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fieldTypes = ['text', 'textarea', 'select', 'checkbox', 'radio', 'file', 'date', 'email', 'number'];
        $fieldType = fake()->randomElement($fieldTypes);

        $fieldOptions = null;
        if (in_array($fieldType, ['select', 'radio', 'checkbox'])) {
            $fieldOptions = [
                'Option 1',
                'Option 2',
                'Option 3',
            ];
        }

        return [
            'competition_id' => \App\Models\Competition::factory(),
            'field_name' => fake()->unique()->slug(2),
            'field_type' => $fieldType,
            'field_label' => fake()->sentence(3),
            'help_text' => fake()->optional()->sentence(),
            'is_required' => fake()->boolean(70), // 70% chance of being required
            'validation_rules' => $this->getValidationRules($fieldType),
            'field_options' => $fieldOptions,
            'field_group' => fake()->randomElement(['basic', 'personal_info', 'team_info', 'documents', 'additional']),
            'order_index' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Get validation rules based on field type
     *
     * @param string $fieldType
     * @return array
     */
    private function getValidationRules(string $fieldType): array
    {
        $rules = [];

        switch ($fieldType) {
            case 'email':
                $rules = ['email'];
                break;
            case 'number':
                $rules = ['numeric', 'min:0'];
                break;
            case 'file':
                $rules = ['file', 'max:5120']; // 5MB max
                break;
            case 'date':
                $rules = ['date'];
                break;
            case 'text':
                $rules = ['string', 'max:255'];
                break;
            case 'textarea':
                $rules = ['string', 'max:1000'];
                break;
            default:
                $rules = ['string'];
        }

        return $rules;
    }

    /**
     * Create a text field requirement
     */
    public function textField(): static
    {
        return $this->state(fn (array $attributes) => [
            'field_type' => 'text',
            'validation_rules' => ['string', 'max:255'],
            'field_options' => null,
        ]);
    }

    /**
     * Create a required field
     */
    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_required' => true,
        ]);
    }

    /**
     * Create an optional field
     */
    public function optional(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_required' => false,
        ]);
    }

    /**
     * Create a file upload field
     */
    public function fileField(): static
    {
        return $this->state(fn (array $attributes) => [
            'field_type' => 'file',
            'validation_rules' => ['file', 'max:5120', 'mimes:pdf,doc,docx'],
            'field_options' => null,
        ]);
    }

    /**
     * Create a select field
     */
    public function selectField(array $options = null): static
    {
        return $this->state(fn (array $attributes) => [
            'field_type' => 'select',
            'validation_rules' => ['string', 'in:' . implode(',', $options ?? ['Option 1', 'Option 2', 'Option 3'])],
            'field_options' => $options ?? ['Option 1', 'Option 2', 'Option 3'],
        ]);
    }
}
