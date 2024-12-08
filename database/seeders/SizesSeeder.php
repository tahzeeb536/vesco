<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sizes')->insert([
            ['name' => '5 x 5 cm', 'status' => true],
            ['name' => '7 x 2 cm', 'status' => true],
            ['name' => '8 x 3 cm', 'status' => true],
            ['name' => '10 x 2 cm', 'status' => true],
            ['name' => '12 x 3 cm', 'status' => true],
            ['name' => '15 x 4 cm', 'status' => true],
            ['name' => '18 x 5 cm', 'status' => true],
            ['name' => '20 x 4 cm', 'status' => true],
            ['name' => '22 x 6 cm', 'status' => true],
            ['name' => '25 x 5 cm', 'status' => true],
            ['name' => '7.5 x 2.5 cm', 'status' => true],
            ['name' => '10 x 2.5 cm', 'status' => true],
            ['name' => '12.5 x 3.5 cm', 'status' => true],
            ['name' => '15 x 3.5 cm', 'status' => true],
            ['name' => '17 x 4.5 cm', 'status' => true],
            ['name' => '19 x 5 cm', 'status' => true],
            ['name' => '21 x 5.5 cm', 'status' => true],
            ['name' => '24 x 6 cm', 'status' => true],
            ['name' => '10 x 3 cm', 'status' => true],
            ['name' => '15 x 6 cm', 'status' => true],
            ['name' => '8 x 8 cm', 'status' => true],
            ['name' => '12 x 8 cm', 'status' => true],
            ['name' => '15 x 10 cm', 'status' => true],
            ['name' => '18 x 12 cm', 'status' => true],
            ['name' => '20 x 15 cm', 'status' => true],
            ['name' => '22 x 17 cm', 'status' => true],
            ['name' => '25 x 20 cm', 'status' => true],
            ['name' => '30 x 22 cm', 'status' => true],
            ['name' => '35 x 25 cm', 'status' => true],
            ['name' => '40 x 30 cm', 'status' => true],
        ]);
        
    }
}
