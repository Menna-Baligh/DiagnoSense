<?php

use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(SocialAuthController::class)->prefix('auth')->group(function () {
    Route::get('/google/redirect', 'redirectToGoogle');
    Route::get('/google/callback', 'handleGoogleCallback');
});
