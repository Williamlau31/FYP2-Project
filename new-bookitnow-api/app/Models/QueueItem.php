<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'queue_number',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'waiting' => 'warning',
            'called' => 'primary',
            'completed' => 'success',
            default => 'secondary'
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'waiting' => 'hourglass-split',
            'called' => 'megaphone',
            'completed' => 'check-circle',
            default => 'clock'
        };
    }
}
