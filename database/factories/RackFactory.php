<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Room;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rack>
 */
class RackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rackNames = [
            'Rack A', 'Rack B', 'Rack C', 'Rack D', 'Rack E',
            'Rack F', 'Rack G', 'Rack H', 'Rack I', 'Rack J',
            'Rack K', 'Rack L', 'Rack M', 'Rack N', 'Rack O',
            'Rack P', 'Rack Q', 'Rack R', 'Rack S', 'Rack T',
            'Rack A1', 'Rack B1', 'Rack C1', 'Rack D1', 'Rack E1',
            'Rack F1', 'Rack G1', 'Rack H1', 'Rack I1', 'Rack J1',
            'Rack K1', 'Rack L1', 'Rack M1', 'Rack N1', 'Rack O1',
            'Rack P1', 'Rack Q1', 'Rack R1', 'Rack S1', 'Rack T1',
            'Rack A2', 'Rack B2', 'Rack C2', 'Rack D2', 'Rack E2',
            'Rack F2', 'Rack G2', 'Rack H2', 'Rack I2', 'Rack J2',
            'Rack K2', 'Rack L2', 'Rack M2', 'Rack N2', 'Rack O2',
            'Rack P2', 'Rack Q2', 'Rack R2', 'Rack S2', 'Rack T2',
        ];

        return [
            'name' => $this->faker->randomElement($rackNames),
            'room_id' => Room::inRandomOrder()->first()->id, 
            'status' => $this->faker->boolean(99),
        ];
    }
}
