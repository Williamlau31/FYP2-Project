<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    public function definition(): array
    {
        $roles = ['Doctor', 'Nurse', 'Receptionist', 'Admin'];
        $departments = ['General', 'Cardiology', 'Orthopedics', 'Emergency'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement($roles),
            'department' => fake()->randomElement($departments),
        ];
    }
}