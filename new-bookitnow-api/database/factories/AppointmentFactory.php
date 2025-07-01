<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'staff_id' => Staff::factory(),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'time' => fake()->time('H:i'),
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}