<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        $roomNames = [
            'Storage Room', 'Cold Storage', 'Loading Bay', 'Packing Area', 'Office Room',
            'Break Room', 'Sorting Room', 'Distribution Room', 'Archive Room', 'Control Room',
            'Equipment Room', 'Inspection Room', 'Inventory Room', 'Processing Area', 'Shipping Room',
            'Quality Control Room', 'Receiving Room', 'Returns Room', 'Main Storage', 'Overflow Storage',
            'Secure Storage', 'Bulk Storage', 'Freezer Room', 'Dry Storage', 'Maintenance Room',
            'Administration Room', 'Hazardous Material Room', 'Document Storage', 'General Storage', 'Transit Area',
        ];

        return [
            'name' => $this->faker->randomElement($roomNames),
            'store_id' => Store::inRandomOrder()->first()->id, // Assign to a random store
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
