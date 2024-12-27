<?php

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserProfileController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
})->middleware('auth:sanctum');

Route::name('auth.')->group(function () {
    Route::controller(UserAuthController::class)->name('user.')->group(function () {
        Route::post('/auth/register', 'register')->name('register');
        Route::post('/auth/login', 'login')->name('login');
    });

    Route::controller(PasswordResetController::class)->name('password.')->group(function () {
        Route::post('/auth/password-reset', 'sendPasswordResetEmail')->name('password.reset');
        Route::post('/auth/password-reset/verify', 'verifyPasswordResetCode')->name('password.reset.verify');
    });

    Route::post('/auth/verify', EmailVerificationController::class)->name('verify')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserProfileController::class)->name('user.profile.')->group(function () {
        Route::post('/profile/user', 'store')->name('store');
        Route::post('/profile/update', 'update')->name('update');
    });
});
