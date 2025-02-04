<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\EmailVerification;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Apis for verifying email
 *
 */
class EmailVerificationController extends Controller
{
    /**
     * Send verification mail
     *  TODO: if user has already been verified, they shouldn't be able to send the email
     */
    public function sendEmail(Request $request): JsonResponse
    {
        $verification_code = VerificationCode::query()->create([
            "code" => mt_rand(1000, 9999),
            "user_id" => $request->user()->id,
            "used_at" => null,
            "expires_at" => Carbon::now()->addHour(),
        ]);

        Mail::to($request->user())->send(
            new EmailVerification($verification_code)
        );

        return response()->json([
            "message" => "Email verification link sent on your email.",
        ]);
    }

    /**
     * Verify email by code
     *
     */
    public function verify(Request $request): JsonResponse|UserResource
    {
        $user = $request->user();
        $code = $request->string("code");

        $verification_code = VerificationCode::query()
            ->where("code", "=", $code)
            ->where("user_id", "=", $user->id)
            ->where("expires_at", ">", Carbon::now())
            ->whereNull("used_at")
            ->first();

        if ($verification_code === null) {
            return response()->json(
                [
                    "message" => "Invalid verification code",
                ],
                422
            );
        }

        $verification_code->used_at = Carbon::now();
        $verification_code->save();

        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json([
            "message" => "Email verified successfully",
        ]);
    }
}
