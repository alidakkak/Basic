<?php

namespace App\Http\Controllers\Auth;

use App\CustomResponse\EmailService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerifyNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->only('email');
        $user = User::where('email',$input)->first();
        $user->generate_code();
        EmailService::sendHtmlEmail($user->email,$user->code);
        $user->reset_code();
        $success['success'] = true;
        return response()->json($success,200);
    }
}
