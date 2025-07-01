<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class QueueItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'queue_number' => fake()->unique()->numberBetween(1, 100),
            'status' => fake()->randomElement(['waiting', 'called', 'completed']),
        ];
    }
}