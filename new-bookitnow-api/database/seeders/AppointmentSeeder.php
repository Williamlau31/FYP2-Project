<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all patient and staff IDs
        $patientIds = Patient::pluck('id')->toArray();
        $staffIds = Staff::pluck('id')->toArray();

        // Ensure there are patients and staff to link
        if (empty($patientIds) || empty($staffIds)) {
            $this->command->warn('No patients or staff found. Skipping appointment seeding.');
            return;
        }

        // Create 30 random appointments
        Appointment::factory()->count(30)->create([
            'patient_id' => fake()->randomElement($patientIds),
            'staff_id' => fake()->randomElement($staffIds),
        ]);
    }
}