<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function store(CreateUserProfileRequest $request): JsonResponse
    {
        /* @var User $user */
        $user = $request->user();
        $data = $request->validated();

        $profile = $user->profile()->create($data);
        $user->load(['profile']);

        return response()->json([
            'message' => 'user profile created successfully',
            'data' => [
                'user' => $user
            ]
        ]);
    }
}
