<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an Admin user for testing login
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bookitnow.com',
            'password' => Hash::make('password'), // Password is 'password'
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create a Patient user for testing login
        User::create([
            'name' => 'Test Patient User',
            'email' => 'patient@bookitnow.com',
            'password' => Hash::make('password'), // Password is 'password'
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);

        // Create 10 additional random 'user' role users using the factory
        User::factory()->count(10)->create();
    }
}