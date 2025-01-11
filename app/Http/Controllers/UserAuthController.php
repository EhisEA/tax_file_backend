<?php

namespace App\Http\Controllers;

use App\Events\Registered;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // TODO: better phone number validation
        //       also in form requests

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['email', 'required', 'unique:users'],
            'phone_number' => ['required'],
            'password' => ['required', Password::min(8)],
        ]);

        $user = User::query()->create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->userProfile()->create([
            'phone_number' => $data['phone_number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);

        $token = $user->createToken(Str::random(10));
        Registered::dispatch($user);

        return (new UserResource($user->load('user_profile')))->additional([
            'token' => $token->plainTextToken,
        ]);
    }

    /**
     * Login to a user account
     *
     */
    public function login(Request $request): UserResource|JsonResponse
    {
        $data = $request->validate([
            'email' => ['email', 'required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($data)) {
            Log::info('logging in user');
            $user = User::query()->where('email', $data['email'])->with('userProfile')->sole();

            if ($user->userProfile === null) {
                Log::info('user is not a user account');
                return response()->json([
                    'message' => 'invalid login credentials',
                ], 422);
            }

            $token = $user->createToken(Str::random(10));

            return (new UserResource($user))->additional([
                'token' => $token->plainTextToken,
            ]);
        }

        Log::info('failed login');
        return response()->json([
            'message' => 'invalid login credentials',
        ], 422);
    }
}
