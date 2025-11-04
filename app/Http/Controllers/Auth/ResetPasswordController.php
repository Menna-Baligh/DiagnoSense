<?php

namespace App\Http\Controllers\Auth;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Hash;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function resetPassword(ResetPasswordRequest $request , string $type){
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])
                    ->where('type', $type)
                    ->first();
        if(!$user){
            return ApiResponse::error('Unauthorized attempt.', null, 403);
        }

        $otp2 = $this->otp->validate($validated['email'], $validated['otp']);
        if(!$otp2->status){
            return ApiResponse::error('Invalid or expired OTP.', null, 400);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);
        $user->tokens()->delete();
        return ApiResponse::success('Password has been reset successfully.', null, 200);

    }
}
