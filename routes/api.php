<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);

Route::post('/email-verification',[\App\Http\Controllers\Auth\EmailVerifyController::class,'emailVerification']);
Route::post('forget-password',[\App\Http\Controllers\Auth\ForgetPasswordController::class,'forgetPassword']);
Route::post('reset-password',[\App\Http\Controllers\Auth\ResetPasswordController::class,'resetPassword']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
//    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [\App\Http\Controllers\Auth\AuthController::class, 'userProfile']);
});
