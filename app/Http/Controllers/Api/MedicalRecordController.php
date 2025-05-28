<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();
        $records = MedicalRecord::where('doctor_id', $user->id)
                    ->orWhere('patient_id', $user->id)
                    ->get();

        return response()->json($records);
    }

    public function store(MedicalRecordRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('documents')) {
            $data['documents'] = $request->file('documents')->store('medical_documents', 'public');
        }

        $record = MedicalRecord::create($data);

        return response()->json(['success' => true, 'data' => $record]);
    }

    public function show(MedicalRecord $medicalRecord)
    {
        return response()->json($medicalRecord);
    }

    public function update(MedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        $data = $request->validated();

        if ($request->hasFile('documents')) {
            $data['documents'] = $request->file('documents')->store('medical_documents', 'public');
        }

        $medicalRecord->update($data);

        return response()->json(['success' => true, 'data' => $medicalRecord]);
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}


