<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;


Route::prefix('doctor')->group(function () {
    Route::post('/register', [RegisterController::class, 'registerDoctor']);
    Route::post('/login', [LoginController::class, 'loginDoctor']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LogoutController::class, 'logoutDoctor']);
    });
});

Route::prefix('patient')->group(function () {
    Route::post('/register', [RegisterController::class, 'registerPatient']);
    Route::post('/login', [LoginController::class, 'loginPatient']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LogoutController::class, 'logoutPatient']);
    });
});
