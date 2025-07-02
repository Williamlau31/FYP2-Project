<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random patient and appointment (appointment can be null)
        $patient = Patient::inRandomOrder()->first();
        $appointment = Appointment::inRandomOrder()->first();

        return [
            'patient_id' => $patient ? $patient->id : Patient::factory(), // Ensure a patient exists
            'appointment_id' => fake()->boolean(70) ? ($appointment ? $appointment->id : Appointment::factory()) : null, // 70% chance to link to an appointment
            'amount' => fake()->randomFloat(2, 10, 500),
            'payment_method' => fake()->randomElement(['Cash', 'Card', 'Online Transfer', 'Insurance']),
            'payment_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
