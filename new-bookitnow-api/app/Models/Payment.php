<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'appointment_id',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2', // Cast amount to a decimal with 2 places
    ];

    /**
     * Get the patient that owns the payment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the appointment that the payment belongs to.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Accessor for formatted payment date
    public function getFormattedPaymentDateAttribute()
    {
        return $this->payment_date->format('M j, Y');
    }
}
