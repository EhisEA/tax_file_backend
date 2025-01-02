<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * User Profile
 *
 * Apis for managing user profile data
 */
class UserProfileController extends Controller
{
    /**
     * Create user profile
     *
     */
    public function store(CreateUserProfileRequest $request): UserResource|JsonResponse
    {
        /* @var User $user */
        $user = $request->user();
        $data = $request->validated();

        $user->load('user_profile');

        $user->user_profile()->update($data);
        $user->assignRole('user');

        return new UserResource($user);
    }

    /**
     * Update user profile
     *
     */
    public function update(UpdateUserProfileRequest $request): UserResource|JsonResponse
    {
        /* @var User $user */
        $user = $request->user();
        $data = $request->validated();

        $user->user_profile()->update($data);

        return new UserResource($user);
    }
}
