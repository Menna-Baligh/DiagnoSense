<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;

class ForgetPasswordController extends Controller
{
    public function DoctorForgetPassword(ForgetPasswordRequest $request){
        return $this->forgetPassword($request , 'doctor');
    }
    public function PatientForgetPassword(ForgetPasswordRequest $request){
        return $this->forgetPassword($request , 'patient');
    }
    public function forgetPassword(ForgetPasswordRequest $request , string $type){
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])
                    ->where('type', $type)
                    ->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized attempt.',
            ], 403);
        }
        $user->notify(new ResetPasswordNotification());
        return response()->json([
            'success' => true,
            'message' => 'An OTP has been sent to your email for password reset. Please check your inbox.',
        ], 200);
    }
}
