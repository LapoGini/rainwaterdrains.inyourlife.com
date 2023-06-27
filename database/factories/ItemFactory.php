<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'altitude' => fake()->longitude(),
            'accuracy' => fake()->randomFloat(1, 1, 150),
            'height' => fake()->randomFloat(1, 1, 150),
            'width' => fake()->randomFloat(1, 1, 150),
            'depth' => fake()->randomFloat(1, 1, 150),
            'pic' => fake()->word().'_'.fake()->word().'.jpg',
            'note' => fake()->sentence(),
        ];
    }
}
