<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Make sure Carbon is imported

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'staff_id',
        'date',
        'time',
        'appointment_type',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime', // Cast time to datetime for easier manipulation
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->date->format('M j, Y');
    }

    public function getFormattedTimeAttribute()
    {
        // Assuming time is stored as a full datetime, extract just the time
        return Carbon::parse($this->time)->format('h:i A');
    }

    // New accessor to check if the appointment has been paid
    public function isPaid(): bool
    {
        return $this->payments()->exists();
    }
}