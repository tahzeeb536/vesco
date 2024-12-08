<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batchSize = 100; 
        $totalRecords = 5000;

        for ($i = 0; $i < $totalRecords / $batchSize; $i++) {
            $products = Product::factory()->count($batchSize)->create();

            $products->each(function ($product) {
                $variantCount = rand(3, 7);
                ProductVariant::factory()->count($variantCount)->create([
                    'product_id' => $product->id,
                ]);
            });
        }
    }
}
