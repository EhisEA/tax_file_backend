<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    public function sendPasswordResetEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $email = $request->string('email');

        $user = User::query()->where('email', $email)->first();

        $code = VerificationCode::query()->create([
            'code' => mt_rand(1000, 9999),
            'user_id' => $user->id,
            'used_at' => null,
            'expires_at' => Carbon::now()->addHour(),
        ]);

        Mail::to($user)->send(new PasswordReset($code));

        return response()->json([
            'message' => 'password reset code sent successfully',
            'data' => null,
        ]);
    }

    public function verifyPasswordResetCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        $user = User::query()->where('email', $request->string('email'))->first();

        $verification_code = VerificationCode::query()->where('code', '=', $request->string('code'))
            ->where('user_id', '=', $user->id)
            ->where('expires_at', '>', Carbon::now())->whereNull('used_at')->first();

        if ($verification_code === null) {
            return response()->json([
                'message' => 'invalid verification code',
                'data' => null,
            ], 422);
        }

        $verification_code->used_at = Carbon::now();
        $verification_code->save();

        $user->password = Hash::make($request->string('password')->toString());
        $user->save();

        return response()->json([
            'message' => 'password reset successfully',
            'data' => null,
        ]);
    }
}
