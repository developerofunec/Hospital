<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function profile()
    {
        return response()->json([
            'success' => true,
            'data' => Auth::guard('user')->user()
        ]);
    }

    

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::guard('user')->user();

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
}
