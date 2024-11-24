<?php

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/register', [UserAuthController::class, 'register'])->name('user.auth.register');

Route::post('/auth/login', function (Request $request) {
});

Route::post('/auth/verify', EmailVerificationController::class)->name('auth.verify')->middleware('auth:sanctum');

Route::post('/auth/password-reset', [PasswordResetController::class, 'sendPasswordResetEmail'])->name('auth.password.reset');
Route::post('/auth/password-reset/verify', [PasswordResetController::class, 'verifyPasswordResetCode'])->name('auth.password.reset.verify');

Route::post('/auth/social', function (Request $request) {
});

Route::post('/auth/callback', function (Request $request) {
});

Route::post('/admin/login', function (Request $request) {
});

Route::post('/admin/register', function (Request $request) {
});

Route::post('/profile/user', function (Request $request) {
});
Route::post('/profile/admin', function (Request $request) {
});
