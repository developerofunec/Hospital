<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
{
    return [
        'diagnose' => 'required|string',
        'diagnose_date' => 'date',
        'prescription' => 'string',
        'documents' => 'file|mimes:pdf,jpg,png',

        'plan_title' => 'string',
        'description' => 'string',
        'start_date' => 'date',
        'end_date' => 'date|after_or_equal:start_date',
        'procedures' => 'string',
        'follow_up' => 'string',
        'status' => 'required|in:pending,in_progress,done',

        'doctor_id' => 'required|exists:users,id',
        'patient_id' => 'required|exists:users,id',
    ];
}

}
