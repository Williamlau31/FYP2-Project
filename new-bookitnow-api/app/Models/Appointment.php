<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'staff_id',
        'date',
        'time',
        'status',
        'notes',
        'appointment_type',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('M j, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->time)->format('g:i A');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'scheduled' => 'calendar-check',
            'completed' => 'check-circle',
            'cancelled' => 'x-circle',
            default => 'calendar'
        };
    }
}
