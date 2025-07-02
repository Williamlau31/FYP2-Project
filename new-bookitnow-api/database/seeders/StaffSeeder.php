<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific staff member (e.g., a doctor)
        Staff::create([
            'name' => 'Dr. Alice Smith',
            'email' => 'alice.smith@bookitnow.com',
            'phone' => '01122334455',
            'role' => 'Doctor',
            'department' => 'General',
            'specialization' => 'Family Medicine',
            'license_number' => 'DR-123456',
            'hire_date' => '2020-01-01',
            'salary' => 8000.00,
            'address' => '456 Clinic Road, Cityville',
        ]);

        // Create 5 additional random staff members using the factory
        Staff::factory()->count(5)->create();
    }
}