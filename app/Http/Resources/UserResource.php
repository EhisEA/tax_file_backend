<?php

namespace App\Http\Resources;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this['id'],
                'email' => $this['email'],
                'profile' => $this->whenLoaded('profile', function () {
                    if ($this['profile'] instanceof UserProfile) {
                        return new UserProfileResource($this['profile']);
                    }
                    return new AccountantProfileResource($this['profile']);
                }),
            ]
        ];
    }
}
