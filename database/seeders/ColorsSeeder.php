<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            ['name' => 'Pine Green', 'status' => true],
            ['name' => 'Seafoam Green', 'status' => true],
            ['name' => 'Slate Gray', 'status' => true],
            ['name' => 'Cool Blue', 'status' => true],
            ['name' => 'Ivory White', 'status' => true],
            ['name' => 'Steel Gray', 'status' => true],
            ['name' => 'Teal', 'status' => true],
            ['name' => 'Mint Green', 'status' => true],
            ['name' => 'Charcoal Gray', 'status' => true],
            ['name' => 'Sky Blue', 'status' => true],
            ['name' => 'Dusty Rose', 'status' => true],
            ['name' => 'Pebble Gray', 'status' => true],
            ['name' => 'Frost White', 'status' => true],
            ['name' => 'Graphite', 'status' => true],
            ['name' => 'Olive Green', 'status' => true],
            ['name' => 'Ash Gray', 'status' => true],
            ['name' => 'Light Blue', 'status' => true],
            ['name' => 'Pale Yellow', 'status' => true],
            ['name' => 'Cream Beige', 'status' => true],
            ['name' => 'Muted Cyan', 'status' => true],
            ['name' => 'Warm Gray', 'status' => true],
            ['name' => 'Forest Green', 'status' => true],
            ['name' => 'Powder Blue', 'status' => true],
            ['name' => 'Sandstone', 'status' => true],
            ['name' => 'Smoke Gray', 'status' => true],
            ['name' => 'Coral Pink', 'status' => true],
            ['name' => 'Dove Gray', 'status' => true],
            ['name' => 'Ice Blue', 'status' => true],
            ['name' => 'Taupe', 'status' => true],
            ['name' => 'Rust Orange', 'status' => true],
        ]);
        
    }
}
