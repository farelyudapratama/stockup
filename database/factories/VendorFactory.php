<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $domain = fake()->boolean ? fake()->freeEmailDomain() : fake()->companyEmail();
        return [
            'name' => fake()->company(),
            'email' => fake()->userName() . '@' . $domain,
        ];
    }
}