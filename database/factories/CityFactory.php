<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $districts = ['BG', 'NA', 'FI'];
        
        return [
            'name' => fake()->city(),
            'district' => $districts[array_rand($districts)],
            'pics' => true,
            'user_id' => 3,
        ];
    }
}
