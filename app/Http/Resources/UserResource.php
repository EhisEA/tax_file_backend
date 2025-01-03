<?php

namespace App\Http\Resources;

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
            'user_profile' => new UserProfileResource($this->whenLoaded('user_profile')),
            'accountant_profile' => new AccountantProfileResource($this->whenLoaded('accountant_profile')),
        ];
    }
}
