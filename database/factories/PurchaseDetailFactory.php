<?php

namespace Database\Factories;

use App\Models\PurchaseDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseDetailFactory extends Factory
{
    protected $model = PurchaseDetail::class;

    public function definition()
    {
        return [
            'purchase_id' => fake()->numberBetween(1, 20),
            'product_id' => fake()->numberBetween(1, 200),
            'quantity' => fake()->numberBetween(1, 10),
            'unit_price' => fake()->numberBetween(100, 1000),
            'subtotal' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['unit_price'];
            },
        ];
    }
}