<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Appointment.php
class Appointment extends Model
{
    protected $fillable = [
        'patient_id', 
        'doctor_id',
        'name', 
        'surname',
        'birthdate', 
        'date',
        'time',
        'status'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}

