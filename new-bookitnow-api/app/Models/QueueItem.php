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

    protected $appends = ['patient_name'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getPatientNameAttribute()
    {
        return $this->patient?->name;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($queueItem) {
            if (!$queueItem->queue_number) {
                $queueItem->queue_number = static::max('queue_number') + 1;
            }
        });
    }
}