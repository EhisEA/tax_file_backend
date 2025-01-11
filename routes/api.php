<?php

use App\Http\Controllers\AccountantAuthController;
use App\Http\Controllers\AccountantKYCController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    /* @var User $user*/
    $user = $request->user();

    $user->load('user_profile');
    $user->load('accountant_profile.kyc');

    return new UserResource($request->user());
})->middleware('auth:sanctum');

Route::name('auth.')->prefix('auth')->group(function () {
    Route::controller(UserAuthController::class)->name('user.')->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
    });

    Route::controller(AccountantAuthController::class)->name('accountant.')->group(function () {
        Route::post('/accountant/register', 'register')->name('register');
        Route::post('/accountant/login', 'login')->name('login');
    });

    Route::controller(PasswordResetController::class)->name('password.')->group(function () {
        Route::post('/password-reset', 'sendEmail')->name('password.reset');
        Route::post('/password-reset/verify', 'verifyCode')->name('password.reset.verify');
    });

    Route::controller(EmailVerificationController::class)->name('verification.')->group(function () {
        Route::get('/email/verify', 'sendEmail')->name('email')->middleware('auth:sanctum');
        Route::post('/email/verify', 'verify')->name('verify')->middleware('auth:sanctum');
    });
});

Route::controller(UserProfileController::class)->name('user.profile.')->middleware('auth:sanctum')->group(function () {
    Route::post('/profile/user', 'store')->name('store');
    Route::post('/profile/update', 'update')->name('update');
});

Route::post('/accountant/kyc', AccountantKYCController::class)->name('accountant.kyc')->middleware('auth:sanctum');

Route::controller(UserNotificationController::class)->name('user.notifications.')->middleware('auth:sanctum')->group(function (){
    Route::get('/notifications', 'index')->name('index');
    Route::get('/notifications/{notification}', 'show')->name('show');
    Route::put('/notifications', 'markAllAsRead')->name('read-all');
    Route::put('/notifications/{notification}', 'markNotificationAsRead')->name('read-single');
});
