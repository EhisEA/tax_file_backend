<?php

namespace App\Http\Controllers;

use App\Events\Registered;
use App\Http\Resources\UserResource;
use App\Models\AccountantProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Str;

class AccountantAuthController extends Controller
{
    public function register(Request $request): UserResource
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)],
        ]);

        DB::beginTransaction();

        $user = User::query()->create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $profile = AccountantProfile::query()->create();
        $profile->kyc()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);

        $user->profile()->associate($profile);
        $user->save();

        $token = $user->createToken(Str::random(10));

        DB::commit();

        Registered::dispatch($user);

        return (new UserResource($user))->additional([
            'token' => $token->plainTextToken,
        ]);
    }

    public function login(Request $request): UserResource|JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($data)) {
            $user = User::query()->where('email', $data['email'])->sole();
            if (($user->profile instanceof AccountantProfile) === false) {
                return response()->json([
                    'message' => 'invalid login credentials',
                ], 422);
            }

            $token = $user->createToken(Str::random(10));

            return (new UserResource($user))->additional([
                'token' => $token->plainTextToken,
            ]);
        }

        return response()->json([
            'message' => 'invalid login credentials',
        ], 422);
    }
}
