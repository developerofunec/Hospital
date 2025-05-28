<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\Appointment;
use App\Models\Patients;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function profile()
    {
        return response()->json([
            'success' => true,
            'data' => Auth::user()
        ]);
    }

    // Appointments
    public function indexAppointments()
    {
        return response()->json([
            'appointments' => Auth::user()->appointments
        ]);
    }

    public function storeAppointment(AppointmentRequest $request)
    {
        $appointment = Auth::user()->appointments()->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    public function updateAppointment(AppointmentRequest $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    public function deleteAppointment(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appointment deleted successfully.'
        ]);
    }

    // Change password
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password does not match'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.'
        ]);
    }

    public function createPatient(Request $request)
    {
        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'fin' => $request->name,
            'doctor_id' => Auth::guard('user')->id(),
        ];

        Patients::query()->create($data);

        return response()->json([
            'success' => 'success',
            'message' => 'Patient created successfully.'
        ],201);
    }


    public function getAllPatients()
    {
        $patients = Patients::query()->where('doctor_id',Auth::guard('user')->id())->get();

        if (count($patients) == 0) {
            return response()->json([
                'success' => 'error',
                'message' => 'There is no data.'
            ],404);
        }

        return response()->json([
            'success' => 'success',
            'data' => $patients
        ]);

    }
}
