<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecordsModel extends Model
{

    protected $fillable = [
        'doctor_id', 'patient_id',
        'diagnose', 'diagnose_date', 'prescription', 'documents',
        'plan_title', 'description', 'start_date', 'end_date',
        'procedures', 'follow_up', 'status'
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
