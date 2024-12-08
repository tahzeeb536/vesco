<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Rack;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shelf>
 */
class ShelfFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ShelfNames = [
            'Shelf A', 'Shelf B', 'Shelf C', 'Shelf D', 'Shelf E',
            'Shelf F', 'Shelf G', 'Shelf H', 'Shelf I', 'Shelf J',
            'Shelf K', 'Shelf L', 'Shelf M', 'Shelf N', 'Shelf O',
            'Shelf P', 'Shelf Q', 'Shelf R', 'Shelf S', 'Shelf T',
            'Shelf A1', 'Shelf B1', 'Shelf C1', 'Shelf D1', 'Shelf E1',
            'Shelf F1', 'Shelf G1', 'Shelf H1', 'Shelf I1', 'Shelf J1',
            'Shelf K1', 'Shelf L1', 'Shelf M1', 'Shelf N1', 'Shelf O1',
            'Shelf P1', 'Shelf Q1', 'Shelf R1', 'Shelf S1', 'Shelf T1',
            'Shelf A2', 'Shelf B2', 'Shelf C2', 'Shelf D2', 'Shelf E2',
            'Shelf F2', 'Shelf G2', 'Shelf H2', 'Shelf I2', 'Shelf J2',
            'Shelf K2', 'Shelf L2', 'Shelf M2', 'Shelf N2', 'Shelf O2',
            'Shelf P2', 'Shelf Q2', 'Shelf R2', 'Shelf S2', 'Shelf T2',
        ];

        return [
            'name' => $this->faker->randomElement($ShelfNames),
            'rack_id' => Rack::inRandomOrder()->first()->id, 
            'status' => $this->faker->boolean(99),
        ];
    }
}
