<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->value('id'),
            'color_id' => $this->faker->numberBetween(1, 10),
            'size_id' => $this->faker->numberBetween(1, 10),
            'vendor_price' => $this->faker->numberBetween(50, 200),
            'customer_price' => $this->faker->numberBetween(80, 300),
        ];
    }
}
