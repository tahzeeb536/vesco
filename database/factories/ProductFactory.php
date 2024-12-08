<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
        $name = $this->faker->word();

        return [
            'name' => $name,
            'name_for_vendor' => $name,
            'category_id' => Category::inRandomOrder()->value('id') ?: null,
            'article_number' => $this->faker->optional()->regexify('[A-Za-z0-9]{8}'),
            'image' => null,
            'status' => $this->faker->boolean(99), 
        ];
    }
}
