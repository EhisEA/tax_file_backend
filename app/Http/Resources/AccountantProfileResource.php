<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountantProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'is_approved' => $this['approved_at'] !== null,
            'approved_at' => $this['approved_at'],
            'kyc' => new AccountantInformationResource($this->whenLoaded('kyc'))
        ];
    }
}
