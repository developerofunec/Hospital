<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = [
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'date_of_birth'=>Carbon::parse($request->date_of_birth) ,
            'fin_code'=>$request->fin_code,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'address'=>$request->address,
            'gender'=>$request->gender,
            'password'=>Hash::make($request->password)
        ];

        UserDetails::create($data);

        return response()->json([
            'success'=>'success',
            'message'=>'Register successfully'
        ]);
    }

    public function login(Request $request)
    {
//        return $request;

        $attempt = Auth::guard('user')->attempt(['email'=>$request->email,'password'=>$request->password]);

        if(!$attempt){
            return response()->json([
                'success'=>'error',
                'message'=>'Invalid Credentials'
            ],401);
        }

        $user = Auth::guard('user')->user();
        $tokenResult = $user->createToken('token')->plainTextToken;

        return response()->json([
            'success'=>'success',
            'access_token' => $tokenResult,
        ]);
    }

    public function get_user()
    {
        $user = Auth::user();
        return response()->json([
            'success'=>'success',
            'user'=>$user
        ]);
    }

    public function logout(){

        Auth::user()->tokens()->delete();

        return response()->json([
            'success'=>'success',
            'message'=>'Logged out successfully'
        ]);
    }


}
