<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlantField>
 */
class PlantFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plant_id' => \App\Models\Plant::factory(),
            'field_id' => \App\Models\Field::factory(),
            'text_value' => fake()->word(),
            'number_value' => fake()->randomNumber(2),
        ];
    }
}
