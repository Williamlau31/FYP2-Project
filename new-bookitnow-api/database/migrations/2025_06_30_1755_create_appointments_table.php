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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); // Foreign key to patients table
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade'); // Foreign key to staff table
            $table->date('date');
            $table->time('time'); // Store time as a TIME type
            $table->string('appointment_type', 100)->nullable(); // e.g., 'General Checkup', 'Follow-up'
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled'); // Enum for status
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};