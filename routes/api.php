<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;


Route::prefix('doctor')->group(function () {
    Route::post('/register', [RegisterController::class, 'registerDoctor']);
});

Route::prefix('patient')->group(function () {
    Route::post('/register', [RegisterController::class, 'registerPatient']);
});
