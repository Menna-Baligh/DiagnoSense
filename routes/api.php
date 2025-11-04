<?php

use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;


Route::middleware('check-user-type')->group(function () {
    Route::post('/register/{type}', [RegisterController::class, 'register']);
    Route::post('/login/{type}', [LoginController::class, 'login']);

    Route::post('/forget-password/{type}', [ForgetPasswordController::class, 'forgetPassword']);
    Route::post('/reset-password/{type}', [ResetPasswordController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout/{type}', [LogoutController::class, 'logout']);
        Route::post('/verify-email/{type}', [EmailVerificationController::class, 'verifyEmail']);
        Route::get('/resend-otp/{type}', [EmailVerificationController::class, 'resendOtp']);
    });
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
});

Route::controller(SocialAuthController::class)->group(function () {
    Route::get('/google/redirect', 'redirectToGoogle');
    Route::get('/google/callback', 'handleGoogleCallback');
});
