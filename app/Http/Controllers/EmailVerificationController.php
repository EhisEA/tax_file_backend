<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Symfony\Component\String\u;

/**
 * @group Email verification
 *
 * Api for verifying email
 */
class EmailVerificationController extends Controller
{

    /**
     * Verify Email
     *
     * Verify email by code
     *
     * @bodyParam code string verification code. Example: 1234
     * @response 422 {
     *     "message": "invalid verification code",
     *     "data": null,
     * }
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function __invoke(Request $request): JsonResponse | UserResource
    {
        $user = $request->user();
        $code = $request->string('code');

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

        return new UserResource($user);
    }

}
