<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['Doctor', 'Nurse', 'Receptionist', 'Admin Assistant']; // Expanded roles
        $departments = ['General', 'Cardiology', 'Orthopedics', 'Emergency', 'Pediatrics', 'Dermatology']; // Expanded departments

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(), // Ensure this generates a string within 20 chars
            'role' => fake()->randomElement($roles), // Matches string(100)
            'department' => fake()->randomElement($departments), // Matches string(100)
            'specialization' => fake()->optional()->word() . ' ' . fake()->optional()->word(), // Example specialization
            'license_number' => fake()->unique()->regexify('[A-Z]{2}[0-9]{6}'), // Example license number format
            'hire_date' => fake()->date('Y-m-d', 'now'),
            'salary' => fake()->randomFloat(2, 2000, 10000), // Salary between 2000 and 10000
            'address' => fake()->address(), // Ensure this generates a string within 500 chars
        ];
    }
}