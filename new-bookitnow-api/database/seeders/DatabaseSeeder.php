<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call specific seeders in order of dependency
        $this->call([
            UserSeeder::class,      // Create users (including admin and test patient user)
            PatientSeeder::class,   // Create patients (including one for the test patient user)
            StaffSeeder::class,     // Create staff
            AppointmentSeeder::class, // Create appointments (depends on patients and staff)
            QueueItemSeeder::class,   // Create queue items (depends on patients)
        ]);
    }
}