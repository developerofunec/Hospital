<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'doctor_name' => 'required|string|max:255',
            'appointment_time' => 'required|date|after:now',
        ];
    }
}
