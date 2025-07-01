<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Patients API
    Route::apiResource('patients', PatientController::class);
    Route::get('/patients/search/{query}', [PatientController::class, 'search']);
    
    // Appointments API
    Route::apiResource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    
    // Staff API
    Route::apiResource('staff', StaffController::class);
    
    // Queue API
    Route::apiResource('queue', QueueController::class)->except(['show']);
    Route::post('/queue/{queueItem}/status', [QueueController::class, 'updateStatus']);
    Route::post('/queue/call-next', [QueueController::class, 'callNext']);
    Route::post('/queue/reset', [QueueController::class, 'reset']);
});
