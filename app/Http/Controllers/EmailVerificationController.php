<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        $code = $request->input('code');

        $verification_code = VerificationCode::query()->where('code', '=', $code)->where('user_id', '=', $user->id)
            ->where('expires_at', '>', Carbon::now())->whereNull('used_at')->first();

        if ($verification_code === null) {
            return response()->json([
                'message' => 'invalid verification code',
                'data' => null,
            ], 422);
        }

        $verification_code->used_at = Carbon::now();
        $verification_code->save();

        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json([
            'message' => 'user email verified successfully',
            'data' => ['user' => $user],
        ]);
    }

}
