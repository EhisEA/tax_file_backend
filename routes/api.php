<?php

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('auth.')->group(function () {
    Route::post('/auth/register', [UserAuthController::class, 'register'])->name('user.register');
    Route::post('/auth/login', [UserAuthController::class, 'login'])->name('user.login');

    Route::post('/auth/password-reset', [PasswordResetController::class, 'sendPasswordResetEmail'])
        ->name('password.reset');

    Route::post('/auth/password-reset/verify', [PasswordResetController::class, 'verifyPasswordResetCode'])
        ->name('password.reset.verify');

    Route::post('/auth/verify', EmailVerificationController::class)->name('verify')->middleware('auth:sanctum');

    // TODO:
    //    Route::post('/admin/login', function (Request $request) { });
    //    Route::post('/admin/register', function (Request $request) { });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::name('user.profile.')->group(function () {
        Route::post('/profile/user', [UserProfileController::class, 'store'])->name('store');
        Route::post('/profile/update', [UserProfileController::class, 'update'])->name('update');
    });
});
