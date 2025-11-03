<?php

namespace App\Http\Controllers\Auth;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;

class ForgetPasswordController extends Controller
{

    public function forgetPassword(ForgetPasswordRequest $request , string $type){
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])
                    ->where('type', $type)
                    ->first();
        if(!$user){
            return ApiResponse::error('Unauthorized attempt.', null, 403);
        }
        $user->notify(new ResetPasswordNotification());
        return ApiResponse::success('An OTP has been sent to your email for password reset. Please check your inbox.', null, 200);

    }
}
