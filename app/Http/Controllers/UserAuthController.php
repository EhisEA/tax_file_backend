<?php

namespace App\Http\Controllers;

use App\Events\Registered;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['email', 'required', 'unique:users'],
            'phone_number' => ['numeric', 'required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::query()->create([
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken(Str::random(10));
        Registered::dispatch($user);

        return response()->json([
            'message' => 'user created',
            'data' => [
                'user' => $user,
                'token' => $token->plainTextToken,
            ],
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        // TODO: implement
        return response()->json([]);
    }
}
