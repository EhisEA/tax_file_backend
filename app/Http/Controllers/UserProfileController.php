<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        $user->userProfile()->update($data);
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

        DB::transaction(function () use ($user, $data) {
            // update payload should only include non-null data and should not include the password
            $updatePayload = collect($data)->except('password')->filter(function ($value, $key) {
                return !is_null($value);
            });

            $user->userProfile()->update($updatePayload->toArray());

            if (isset($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }
        });

        return new UserResource($user);
    }
}
