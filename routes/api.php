<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;


Route::prefix('doctor')->group(function () {
    Route::post('/register', [RegisterController::class, 'registerDoctor']);
    Route::post('/login', [LoginController::class, 'loginDoctor']);

    Route::post('/forget-password', [ForgetPasswordController::class, 'DoctorForgetPassword']);
    Route::post('/reset-password', [ResetPasswordController::class, 'DoctorResetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LogoutController::class, 'logoutDoctor']);
        Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
        Route::get('/resend-otp', [EmailVerificationController::class, 'resendOtp']);
    });
});

Route::prefix('patient')->group(function () {
    Route::post('/register', [RegisterController::class, 'registerPatient']);
    Route::post('/login', [LoginController::class, 'loginPatient']);

    Route::post('/forget-password', [ForgetPasswordController::class, 'PatientForgetPassword']);
    Route::post('/reset-password', [ResetPasswordController::class, 'PatientResetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LogoutController::class, 'logoutPatient']);
        Route::post('/verify-email', [EmailVerificationController::class, 'verifyEmail']);
        Route::get('/resend-otp', [EmailVerificationController::class, 'resendOtp']);
    });
});
