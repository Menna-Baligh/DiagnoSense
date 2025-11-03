<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Notifications\EmailVerificationNotification;

class EmailVerificationController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp;
    }
    public function verifyEmail(EmailVerificationRequest $request){
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if(!$user){
            return ApiResponse::error('User not found.', null, 404);
        }
        $otp2 = $this->otp->validate($validated['email'], $validated['otp']);
        if(!$otp2->status){
            return ApiResponse::error('Invalid or expired OTP.', null, 400);
        }
        $user->update([
            'email_verified_at' => now()
        ]);
        return ApiResponse::success('Email has been verified successfully.', null, 200);
    }
    public function resendOtp(Request $request){
        $request->user()->notify(new EmailVerificationNotification());
        return ApiResponse::success('A new OTP has been sent to your email.', null, 200);
    }
}
