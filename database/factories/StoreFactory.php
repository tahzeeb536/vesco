<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $warehouseNames = [
            'Global Warehouse',
            'Central Storage Hub',
            'Elite Logistics Depot',
            'Prime Storage Solutions',
            'Eco-Friendly Warehouse',
            'Bulk Goods Hub',
            'National Distribution Center',
            'Trade Warehouse',
            'Stock & Store Depot',
            'Mega Depot',
            'Smart Warehouse Solutions',
            'Essential Goods Storage',
            'Green Logistics Center',
            'The Storage Vault',
            'Pro Warehouse',
            'SafeGuard Warehouse',
            'Modern Storage Facility',
            'Industrial Goods Depot',
            'Allied Logistics Hub',
            'Supply Line Warehouse',
            'Rapid Distribution Center',
            'CargoCare Warehouse',
            'Urban Storage Depot',
            'SecureSpace Warehouse',
            'Metro Storage Hub',
            'Value Storage Solutions',
            'General Goods Warehouse',
            'Elite Storage Hub',
            'QuickShip Warehouse',
            'Warehouse 360',
        ];
        
        return [
            'name' => $this->faker->unique()->randomElement($warehouseNames), 
            'status' => $this->faker->boolean(98),
        ];
    }
}
