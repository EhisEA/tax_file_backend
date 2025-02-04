<?php

use App\Http\Controllers\AccountantAuthController;
use App\Http\Controllers\AccountantKYCController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaxFilingController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Helper route to get a user */
Route::get("/user", function (Request $request) {
    return new UserResource($request->user());
})->middleware("auth:sanctum");

Route::name("auth.")
    ->prefix("auth")
    ->group(function () {
        Route::name("user.")->group(function () {
            Route::post("/register", [
                UserAuthController::class,
                "register",
            ])->name("register");

            Route::post("/login", [UserAuthController::class, "login"])->name(
                "login"
            );
        });

        Route::name("accountant.")->group(function () {
            Route::post("/accountant/register", [
                AccountantAuthController::class,
                "register",
            ])->name("register");

            Route::post("/accountant/login", [
                AccountantAuthController::class,
                "login",
            ])->name("login");
        });

        Route::name("password.reset.")->group(function () {
            Route::post("/password-reset", [
                PasswordResetController::class,
                "sendEmail",
            ])->name("send");

            Route::post("/password-reset/verify", [
                PasswordResetController::class,
                "verifyCode",
            ])->name("verify");
        });
    });

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/accountant/kyc", AccountantKYCController::class)->name(
        "accountant.kyc"
    );

    Route::post("/media/upload", UploadFileController::class)->name(
        "media.upload"
    );

    Route::name("auth.email.verification.")
        ->prefix("auth")
        ->group(function () {
            Route::get("/email/verify", [
                EmailVerificationController::class,
                "sendEmail",
            ])->name("send");

            Route::post("/email/verify", [
                EmailVerificationController::class,
                "verify",
            ])->name("verify");
        });

    Route::name("user.profile.")->group(function () {
        Route::post("/profile/user", [
            UserProfileController::class,
            "store",
        ])->name("store");

        Route::post("/profile/update", [
            UserProfileController::class,
            "update",
        ])->name("update");
    });

    Route::name("notifications.")->group(function () {
        Route::get("/notifications", [
            NotificationController::class,
            "index",
        ])->name("index");

        Route::get("/notifications/{notification}", [
            NotificationController::class,
            "show",
        ])->name("show");

        Route::put("/notifications", [
            NotificationController::class,
            "markAllAsRead",
        ])->name("read.all");

        Route::put("/notifications/{notification}", [
            NotificationController::class,
            "markAsRead",
        ])->name("read.single");

        Route::delete("/notifications/{notification}", [
            NotificationController::class,
            "delete",
        ])->name("delete");
    });

    Route::name("tax.file.")->group(function () {
        Route::get("/tax/documents", [
            TaxFilingController::class,
            "getDocumentKinds",
        ])->name("documents");

        Route::get("/tax/file", [TaxFilingController::class, "index"])->name(
            "index"
        );

        Route::get("/tax/file/{filing_id}", [
            TaxFilingController::class,
            "show",
        ])->name("show");

        Route::post("/tax/file", [TaxFilingController::class, "submit"])->name(
            "submit"
        );

        Route::post("/tax/file/draft", [
            TaxFilingController::class,
            "storeDraft",
        ])->name("store.draft");

        Route::post("/tax/file/draft/{tax_filing}", [
            TaxFilingController::class,
            "submitDraft",
        ])->name("submit.draft");

        Route::put("/tax/file/draft/{tax_filing}", [
            TaxFilingController::class,
            "updateDraft",
        ])->name("update.draft");
    });
});
