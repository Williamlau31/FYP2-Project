<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('role', 100); // e.g., 'Doctor', 'Nurse', 'Admin Assistant'
            $table->string('department', 100);
            $table->string('specialization')->nullable(); // e.g., 'Cardiology', 'Pediatrics'
            $table->string('license_number', 100)->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable(); // Salary with 2 decimal places
            $table->string('address', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};