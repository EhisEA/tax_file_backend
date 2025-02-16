<?php

declare(strict_types=1);

use App\Http\Controllers\AccountantAuthController;
use App\Http\Controllers\AccountantKYCController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\TaxFilingController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserProfileController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Helper route to get a user */
Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
})->middleware('auth:sanctum');

Route::name('auth.')
    ->prefix('auth')
    ->controller(UserAuthController::class)
    ->group(function () {
        Route::name('user.')->group(function () {
            Route::post('/register', 'register')->name('register');
            Route::post('/login', 'login')->name('login');
        });

        Route::prefix('accountant')
            ->name('accountant.')
            ->controller(AccountantAuthController::class)
            ->group(function () {
                Route::post('/register', 'register')->name('register');
                Route::post('/login', 'login')->name('login');
            });

        Route::prefix('password-reset')
            ->name('password.reset.')
            ->controller(PasswordResetController::class)
            ->group(function () {
                Route::post('/', 'sendEmail')->name('send');
                Route::post('/verify', 'verifyCode')->name('verify');
            });
    });

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/media/upload', UploadFileController::class)->name(
        'media.upload'
    );

    Route::post('/accountant/kyc', AccountantKYCController::class)->name(
        'accountant.kyc'
    );

    Route::prefix('auth/email')
        ->name('auth.email.verification.')
        ->controller(EmailVerificationController::class)
        ->group(function () {
            Route::get('/verify', 'sendEmail')->name('send');
            Route::post('/verify', 'verify')->name('verify');
        });

    Route::prefix('profile')
        ->name('user.profile.')
        ->controller(UserProfileController::class)
        ->group(function () {
            Route::post('/user', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
        });

    Route::prefix('notifications')
        ->name('notifications.')
        ->controller(NotificationController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{notification}', 'show')->name('show');

            Route::put('/', 'markAllAsRead')->name('read.all');
            Route::put('/{notification}', 'markAsRead')->name('read.one');

            Route::delete('/{notification}', 'delete')->name('delete');
        });

    Route::prefix('tax/file')
        ->name('tax.file.')
        ->controller(TaxFilingController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{taxFiling}', 'show')->name('show');

            Route::post('/', 'submit')->name('submit');

            Route::post('/draft/{taxFiling}', 'submitDraft')->name('submit.draft');
            Route::put('/draft/{taxFiling}', 'updateDraft')->name('update.draft');
        });

    Route::get('/tax/documents', [
        TaxFilingController::class,
        'getDocumentKinds',
    ])->name('tax.documents');

    Route::prefix('payments')
        ->name('payment.')
        ->controller(PaymentController::class)
        ->group(function () {
            Route::post('/{taxFiling}', 'initialise')->name('init');
            Route::post('/complete', 'complete')->name('complete');
            Route::post('/confirm/{payment}', 'confirm')->name('confirm');

            Route::get('/', 'index')->name('index');
            Route::get('/{payment}', 'show')->name('show');
        });

    Route::prefix('referrals')
        ->name('referral.')
        ->controller(ReferralController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/code', 'code')->name('generate');
        });
});
