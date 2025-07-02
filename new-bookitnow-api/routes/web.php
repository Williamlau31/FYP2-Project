<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QueueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Add the GET route for displaying the registration form
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form'); // Give it a different name to avoid conflict with the POST route's 'register' name if you plan to use it this way
Route::post('/register', [AuthController::class, 'register'])->name('register'); // This is your existing POST route

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Patients
    Route::resource('patients', PatientController::class);
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
    
    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    
    // Staff (Admin only for create, edit, delete)
    Route::resource('staff', StaffController::class);
    
    // Queue
    Route::resource('queue', QueueController::class)->except(['create', 'edit', 'show']);
    Route::post('/queue/{queueItem}/status', [QueueController::class, 'updateStatus'])->name('queue.update-status');
    Route::post('/queue/call-next', [QueueController::class, 'callNext'])->name('queue.call-next');
    Route::post('/queue/reset', [QueueController::class, 'reset'])->name('queue.reset');
});
