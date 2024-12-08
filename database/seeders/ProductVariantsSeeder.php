<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Product;

class ProductVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        return [
            'product_id' => Product::inRandomOrder()->value('id'),
            'color_id' => $this->faker->numberBetween(1, 10),
            'size_id' => $this->faker->numberBetween(1, 10),
            'vendor_price' => $this->faker->randomFloat(2, 50, 200),
            'customer_price' => $this->faker->randomFloat(2, 100, 300)
        ];
    }
}
