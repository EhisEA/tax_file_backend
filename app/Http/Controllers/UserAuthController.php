<?php

namespace App\Http\Controllers;

use App\Events\Registered;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * @group User Authentication
 *
 * Apis for registering and logging in user accounts
 *
 * @unauthenticated
 */
class UserAuthController extends Controller
{
    /**
     * Register User
     *
     * Create and register a user account
     *
     * @bodyParam password_confirmation string confirm password. Example: superSecurePassword1234
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @apiResourceAdditional token=xxxxxx
     */
    public function register(Request $request): UserResource
    {
        $data = $request->validate([
            'email' => ['email', 'required', 'unique:users'],
            // User's phone number. Example: 08123456789
            'phone_number' => ['required'],
            // Super secure password. Example: superSecurePassword1234
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::query()->create([
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken(Str::random(10));
        Registered::dispatch($user);

        return (new UserResource($user))->additional([
            'token' => $token->plainTextToken,
        ]);
    }

    /**
     * Login User
     *
     * Login to a user account
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * @apiResourceAdditional token=xxxxxx
     */
    public function login(Request $request): UserResource | JsonResponse
    {
        $data = $request->validate([
            'email' => ['email', 'required'],
            // User's password. Example: superSecurePassword1234
            'password' => ['required'],
        ]);

        if (Auth::attempt($data)) {
            $user = User::query()->where('email', $data['email'])->sole();
            $token = $user->createToken(Str::random(10));

            $user->load('profile');

            return (new UserResource($user))->additional([
                'token' => $token->plainTextToken,
            ]);
        }

        return response()->json([
            'message' => 'invalid login credentials',
            'data' => null,
        ], 422);
    }
}
