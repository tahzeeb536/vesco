<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $fullName = $firstName . ' ' . $lastName;

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'full_name' => $fullName,
            'email' => $this->faker->unique()->safeEmail(),
            'organization' => $this->faker->optional()->company(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'post_code' => $this->faker->postcode(),
            'country' => 'Pakistan',
            'state' => $this->faker->state(),
            'currency' => 'PKR',
            'status' => $this->faker->boolean(99),
            'created_at' => now(),
            'updated_at' => now(),
        ];

    }
}
