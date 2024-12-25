<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @group User Profile
 *
 * Apis for managing user profile data
 */
class UserProfileController extends Controller
{
    /**
     * Create Profile
     *
     * Create user profile
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function store(CreateUserProfileRequest $request): UserResource
    {
        /* @var User $user */
        $user = $request->user();
        $data = $request->validated();

        $profile = UserProfile::query()->create($data);
        $profile->user()->save($user);

        return new UserResource($user);
    }

    /**
     * Update Profile
     *
     * Update user profile
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function update(UpdateUserProfileRequest $request): UserResource
    {
        /* @var User $user */
        $user = $request->user();
        $data = $request->validated();

        $user->profile()->update($data);
        $user->refresh();

        return new UserResource($user);
    }
}
