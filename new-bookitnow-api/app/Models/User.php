<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Make sure 'role' is fillable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Define the relationship with appointments as a patient
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    // If a user can also be a staff member and have appointments assigned to them
    public function staffAppointments()
    {
        return $this->hasMany(Appointment::class, 'staff_id', 'id');
    }

    // Define relationship to Patient model if a User *is* a Patient
    public function patient()
    {
        return $this->hasOne(Patient::class, 'email', 'email'); // Assuming email is unique and links User to Patient
    }

    // Define relationship to Staff model if a User *is* a Staff
    public function staffMember()
    {
        return $this->hasOne(Staff::class, 'email', 'email'); // Assuming email is unique and links User to Staff
    }


    // Role-checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPatient()
    {
        // A user is a patient if their role is 'patient'
        return $this->role === 'patient';
    }

    public function isStaff()
    {
        // A user is staff if their role is 'staff' or 'admin' (if admin also performs staff duties)
        // For BookItNow, 'admin' seems to encompass staff duties.
        // If 'staff' is a distinct role, you might want: return $this->role === 'staff';
        return $this->role === 'staff' || $this->role === 'admin';
    }
}
