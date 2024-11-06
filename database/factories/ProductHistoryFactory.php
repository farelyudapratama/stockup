<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductHistoryFactory extends Factory
{
    protected $model = ProductHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(), // Menghasilkan produk baru atau gunakan 'product_id' yang ada
            'changed_field' => 'current_stock',
            'old_value' => $this->faker->numberBetween(10, 100),
            'new_value' => $this->faker->numberBetween(10, 100),
            'reason_changed' => $this->faker->sentence(),
        ];
    }
}