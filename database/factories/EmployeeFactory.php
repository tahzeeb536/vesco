<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'name_urdu' => null, 
            'father_name' => $this->faker->name('male'),
            'dob' => $this->faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
            'cnic' => $this->faker->unique()->numerify('#####-#######-#'),
            'photo' => null,
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'type' => 'Salary Based',
            'basic_salary' => $this->faker->randomFloat(2, 25000, 80000),
            'status' => $this->faker->boolean(95),
        ];
    }
}
