<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\QueueController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Patient routes
    Route::apiResource('patients', PatientController::class);
    
    // Staff routes (admin only)
    Route::apiResource('staff', StaffController::class);
    
    // Appointment routes
    Route::apiResource('appointments', AppointmentController::class);
    
    // Queue routes
    Route::apiResource('queue', QueueController::class);
});