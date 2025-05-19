<?php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Mail\VerificationCodeMail;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = [
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'date_of_birth'  => Carbon::parse($request->date_of_birth),
            'fin_code'       => $request->fin_code,
            'email'          => $request->email,
            'phone_number'   => $request->phone_number,
            'address'        => $request->address,
            'gender'         => $request->gender,
            'password'       => Hash::make($request->password),
        ];

        UserDetails::create($data);

        return response()->json([
            'success' => 'success',
            'message' => 'Register successfully'
        ]);
    }

    public function login(LoginRequest $request)
    {
        $attempt = Auth::guard('user')->attempt([
            'email'    => $request->email,
            'password' => $request->password
        ]);

        if (!$attempt) {
            return response()->json([
                'success' => 'error',
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $user = Auth::guard('user')->user();
        $tokenResult = $user->createToken('token')->plainTextToken;

        return response()->json([
            'success'      => 'success',
            'access_token' => $tokenResult,
        ]);
    }

    public function get_user()
    {
        $user = Auth::user();
        return response()->json([
            'success' => 'success',
            'user'    => $user
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'success' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = UserDetails::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => 'error', 'message' => 'User not found'], 404);
        }

        $code = rand(100000, 999999);
        $user->update([
            'verification_code' => $code,
            'code_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return response()->json(['success' => 'success', 'message' => 'Verification code sent']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required'
        ]);

        $user = UserDetails::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('code_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json(['success' => 'error', 'message' => 'Invalid or expired code'], 400);
        }

        return response()->json(['success' => 'success', 'message' => 'Code verified']);
    }

    public function forgotPassword(Request $request)
    {
        return $this->sendCode($request); // alias
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required',
            'new_password' => 'required|min:6'
        ]);

        $user = UserDetails::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('code_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json(['success' => 'error', 'message' => 'Invalid or expired code'], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'verification_code' => null,
            'code_expires_at' => null,
        ]);

        return response()->json(['success' => 'success', 'message' => 'Password reset successfully']);
    }
}
