<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\Registered;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * User Authentication
 *
 */
class UserAuthController extends Controller
{
    /**
     * Create and register a user account
     *
     */
    public function register(Request $request): UserResource
    {
        $data = $request->validate([
            "first_name" => ["required", "string", "max:255"],
            "last_name" => ["required", "string", "max:255"],
            "email" => ["email", "required", "unique:users"],
            // TODO: better phone number validation (and also in form requests)
            "phone_number" => ["required"],
            "password" => ["required", Password::min(8)],
        ]);

        DB::beginTransaction();

        $user = User::query()->create([
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
        ]);

        $profile = UserProfile::query()->create([
            "phone_number" => $data["phone_number"],
            "first_name" => $data["first_name"],
            "last_name" => $data["last_name"],
        ]);

        $user->profile()->associate($profile);
        $user->save();

        $token = $user->createToken(Str::random(10));

        DB::commit();

        Registered::dispatch($user);

        $user->load("profile");
        Log::info($user->profile);

        return (new UserResource($user))->additional([
            "token" => $token->plainTextToken,
        ]);
    }

    /**
     * Login to a user account
     *
     */
    public function login(Request $request): UserResource|JsonResponse
    {
        $data = $request->validate([
            "email" => ["email", "required"],
            "password" => ["required"],
        ]);

        if (Auth::attempt($data)) {
            /* @var User $user */
            $user = User::query()->where("email", $data["email"])->sole();

            if ($user->profile instanceof UserProfile === false) {
                return response()->json(
                    [
                        "message" => "invalid login credentials",
                    ],
                    422
                );
            }

            $token = $user->createToken(Str::random(10));
            return (new UserResource($user))->additional([
                "token" => $token->plainTextToken,
            ]);
        }

        return response()->json(
            [
                "message" => "invalid login credentials",
            ],
            422
        );
    }
}
