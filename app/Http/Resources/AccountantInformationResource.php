<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountantInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this['first_name'],
            'middle_name' => $this['middle_name'],
            'last_name' => $this['last_name'],
            'social_security_number' => $this['social_security_number'],
            'gender' => $this['gender'],
            'religion' => $this['religion'],
            'marital_status' => $this['marital_status'],
            'residential_address' => $this['residential_address'],
            'postal_code' => $this['postal_code'],
            'country' => $this['country'],
            'province' => $this['province'],
            'city' => $this['city'],
            'profile_picture' => new FileResource($this['profile_picture']),
            'business_name' => $this['business_name'],
            'office_address' => $this['office_address'],
            'professional_designation' => $this['professional_designation'],
            'netfile_number' => $this['netfile_number'],
            'years_of_experience' => $this['years_of_experience'],
            'office_postal_code' => $this['office_postal_code'],
            'proof_of_identity' => new FileResource($this['proof_of_identity']),
            'proof_of_address' => new FileResource($this['proof_of_address']),
            'professional_qualification_document' => new FileResource($this['professional_qualification_document']),
            'proof_of_business_registration' => new FileResource($this['proof_of_business_registration']),
        ];
    }
}
