<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CategorySeeder::class,
            EmployeeSeeder::class,
            VendorSeeder::class,
            StoresSeeder::class,
            RoomsSeeder::class,
            RacksSeeder::class,
            ShelvesSeeder::class,
            ColorsSeeder::class,
            SizesSeeder::class,
            CustomerSeeder::class,
            ProductsSeeder::class,
        ]);

    }
}
