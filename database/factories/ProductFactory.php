<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => fake()->unique()->randomElement([
                'Kipas Angin',
                'Setrika',
                'Rice Cooker',
                'Blender',
                'TV LED',
                'Kulkas',
                'Speaker Bluetooth',
                'Lampu LED',
                'AC Portable',
                'Mixer'
            ]),
            'description' => fake()->sentence(),
            'initial_stock' => fake()->numberBetween(10, 100),
            'current_stock' => fake()->numberBetween(0, 100),
        ];
    }
}