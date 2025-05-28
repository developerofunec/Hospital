<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::guard('user')->user();

        $appointments = Appointment::where('doctor_id', $user->id)
            ->orWhere('patient_id', $user->id)
            ->orderBy('name')
            ->orderBy('surname')
            ->orderBy('birthdate')
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return response()->json(['appointments' => $appointments]);
    }

    public function store(AppointmentRequest $request)
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $data['doctor_id'],
            'name' => $data['name'],
            'surname' => $data['surname'],
            'birthdate' => $data['birthdate'],
            'date' => $data['date'],
            'time' => $data['time'],
        ]);

        return response()->json(['success' => true, 'data' => $appointment]);
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        if (Auth::id() !== $appointment->doctor_id && Auth::id() !== $appointment->patient_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->update($request->only([
            'name', 'surname', 'birthdate','date', 'time'
        ]));

        return response()->json(['success' => true, 'data' => $appointment]);
    }

    public function destroy(Appointment $appointment)
    {
        if (Auth::id() !== $appointment->doctor_id && Auth::id() !== $appointment->patient_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->delete();

        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}

