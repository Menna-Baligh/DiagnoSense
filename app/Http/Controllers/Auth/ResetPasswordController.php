<?php

namespace App\Http\Controllers\Auth;

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
    public function DoctorResetPassword(ResetPasswordRequest $request){
        return $this->resetPassword($request , 'doctor');
    }
    public function PatientResetPassword(ResetPasswordRequest $request){
        return $this->resetPassword($request , 'patient');
    }
    public function resetPassword(ResetPasswordRequest $request , string $type){
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
        
        $otp2 = $this->otp->validate($validated['email'], $validated['otp']);
        if(!$otp2->status){
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);
        $user->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ], 200);
    }
}
