<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerifyController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\chat\MessageController;
use App\Http\Controllers\chat\StarredMessageController;
use App\Http\Controllers\chat\StoriesController;
use App\Http\Controllers\Controller;
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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/email-verification',[EmailVerifyController::class,'emailVerification']);
Route::post('forget-password',[ForgetPasswordController::class,'forgetPassword']);
Route::post('reset-password',[ResetPasswordController::class,'resetPassword']);
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    ////// Stories
    Route::post('/stories', [StoriesController::class, 'store']);
    Route::get('/stories', [StoriesController::class, 'index']);
    Route::get('/showMyStory', [StoriesController::class, 'showMyStory']);
    Route::post('/seeStory', [StoriesController::class, 'seeStory']);
    Route::delete('/stories/{story}', [StoriesController::class, 'delete']);

    /////  Starred Message
    Route::post('/stars', [StarredMessageController::class, 'store']);
    Route::get('/stars', [StarredMessageController::class, 'index']);
    Route::delete('/stars/{star}', [StarredMessageController::class, 'destroy']);

    ///// Search Message
    Route::group(["middleware"=>'check_membership:admin,member'],function () {
        Route::get('/search', [MessageController::class, 'search']);
    });
//    Route::get('/search', [MessageController::class, 'getMessageByDate']);
});
Route::post("/import",[Controller::class,"importExcel"]);


