<?php

namespace Database\Factories;

use App\Models\FieldGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field_group_id' => FieldGroup::factory(),
            'name' => fake()->unique()->word(),
            'type' => fake()->randomElement(['text', 'select', 'number']),
        ];
    }
}
