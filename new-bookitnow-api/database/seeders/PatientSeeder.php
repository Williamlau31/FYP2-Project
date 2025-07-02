<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific patient record for the test patient user
        Patient::create([
            'name' => 'Test Patient User',
            'email' => 'patient@bookitnow.com', // Link by email, assuming your app logic uses this
            'phone' => '0123456789',
            'address' => '123 Test Street, Test City',
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'emergency_contact' => 'Jane Doe - 0198765432',
            'medical_history' => 'No significant medical history.',
        ]);

        // Create 20 additional random patients using the factory
        Patient::factory()->count(20)->create();
    }
}