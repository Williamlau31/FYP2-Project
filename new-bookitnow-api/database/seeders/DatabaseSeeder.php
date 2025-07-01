<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\QueueItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => Hash::make('user'),
            'role' => 'user',
        ]);

        // Create sample data
        $patients = Patient::factory(10)->create();
        $staff = Staff::factory(5)->create();
        
        // Create appointments
        Appointment::factory(15)->create([
            'patient_id' => $patients->random()->id,
            'staff_id' => $staff->random()->id,
        ]);

        // Create queue items
        QueueItem::factory(5)->create([
            'patient_id' => $patients->random()->id,
        ]);
    }
}