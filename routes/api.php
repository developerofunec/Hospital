<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\PatientController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/send-code', [AuthController::class, 'sendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/patient/profile', [PatientController::class, 'profile']);

    Route::get('/appointments', [PatientController::class, 'indexAppointments']);
    Route::post('/appointments', [PatientController::class, 'storeAppointment']);
    Route::put('/appointments/{appointment}', [PatientController::class, 'updateAppointment']);
    Route::delete('/appointments/{appointment}', [PatientController::class, 'deleteAppointment']);

    Route::post('/change-password', [PatientController::class, 'changePassword']);
});

