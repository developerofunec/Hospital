<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDetails;
use App\Models\Appointment;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class PatientController extends Controller
{
    public function getProfile()
    {
        return response()->json(['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only('first_name', 'last_name', 'phone_number', 'address'));

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function appointments()
    {
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)->get();

        return response()->json(['appointments' => $appointments]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return response()->json(['error' => 'Incorrect old password'], 403);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function sendCode()
    {
        $code = rand(1000, 9999);
        $user = Auth::user();

        VerificationCode::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code]
        );

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return response()->json(['message' => 'Code sent']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required']);

        $check = VerificationCode::where('user_id', Auth::id())
            ->where('code', $request->code)
            ->first();

        if (!$check) {
            return response()->json(['error' => 'Invalid code'], 400);
        }

        return response()->json(['message' => 'Code verified']);
    }
}

