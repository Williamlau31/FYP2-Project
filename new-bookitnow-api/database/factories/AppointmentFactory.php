<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $appointmentTypes = ['General Checkup', 'Follow-up', 'Consultation', 'Emergency'];

        return [
            'patient_id' => Patient::factory(),
            'staff_id' => Staff::factory(),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'time' => fake()->time('H:i:s'), // Use 'H:i:s' for TIME type in MySQL
            'appointment_type' => fake()->randomElement($appointmentTypes), // Added new field
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}