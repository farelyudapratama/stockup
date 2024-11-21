<?php

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'vendor_id' => fake()->numberBetween(1, 10),
            'purchase_date' => now(),
            'total_amount' => 0,
        ];
    }
}