<?php

namespace Database\Seeders;

use App\Models\QueueItem;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class QueueItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all patient IDs
        $patientIds = Patient::pluck('id')->toArray();

        // Ensure there are patients to link
        if (empty($patientIds)) {
            $this->command->warn('No patients found. Skipping queue item seeding.');
            return;
        }

        // Create 15 random queue items
        // Ensure unique queue_number by iterating or using factory's unique()
        for ($i = 0; $i < 15; $i++) {
            QueueItem::factory()->create([
                'patient_id' => fake()->randomElement($patientIds),
                'queue_number' => QueueItem::max('queue_number') + 1 ?? 1, // Ensures unique and incremental
                'status' => fake()->randomElement(['waiting', 'called', 'completed']),
            ]);
        }
    }
}