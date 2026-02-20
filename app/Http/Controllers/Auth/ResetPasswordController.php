<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Hash;
use Ichtrojan\Otp\Otp;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function resetPassword(ResetPasswordRequest $request, string $type)
    {
        $validated = $request->validated();
        $fieldType = filter_var($validated['identity'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = User::where($fieldType, $validated['identity'])
            ->where('type', $type)
            ->first();
        if (! $user) {
            return ApiResponse::error('Unauthorized attempt.', null, 403);
        }

        $otp2 = $this->otp->validate($validated['identity'], $validated['otp']);
        if (! $otp2->status) {
            return ApiResponse::error('Invalid or expired OTP.', null, 400);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        $user->tokens()->delete();

        return ApiResponse::success('Password has been reset successfully.', null, 200);

    }
}
