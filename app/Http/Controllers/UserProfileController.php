<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use Exception;
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

    public function update(UpdateUserProfileRequest $request)
    {
        try{
            $user = $request->user();
            $data = $request->validated();

            $user->profile()->update($data);
            $user->load(['profile']);

            return response()->json([
                'message' => 'user profile updated successfully',
                'data' => [
                    'user' => $user
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the user profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
