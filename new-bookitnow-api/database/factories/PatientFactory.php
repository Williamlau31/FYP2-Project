<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(), // Ensure this generates a string within 20 chars
            'address' => fake()->address(), // Ensure this generates a string within 500 chars
            'date_of_birth' => fake()->date('Y-m-d', '2005-01-01'), // Example: born before 2005
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'emergency_contact' => fake()->name() . ' - ' . fake()->phoneNumber(), // Example format
            'medical_history' => fake()->optional()->paragraph(), // Use paragraph for text type
        ];
    }
}