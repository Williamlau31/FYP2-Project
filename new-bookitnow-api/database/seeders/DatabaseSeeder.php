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
            UserSeeder::class,
            PatientSeeder::class,
            StaffSeeder::class,
            AppointmentSeeder::class,
            QueueItemSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}