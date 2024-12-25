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
            'id' => $this['id'],
            'email' => $this['email'],
            'phone_number' => $this['phone_number'],
            'profile' => $this->when(
                $this['profileable_type'] === get_class(new UserProfile()),
                new UserProfileResource($this->whenLoaded('profileable')),
                null
            )
        ];
    }
}
