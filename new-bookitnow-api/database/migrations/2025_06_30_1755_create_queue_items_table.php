<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queue_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->integer('queue_number');
            $table->enum('status', ['waiting', 'called', 'completed'])->default('waiting');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queue_items');
    }
};