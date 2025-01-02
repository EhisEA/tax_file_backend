<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'phone_number' => $this['phone_number'],
            'last_name' => $this['last_name'],
            'date_of_birth' => $this['date_of_birth'],
            'social_insurance_number' => $this['social_insurance_number'],
            'gender' => $this['gender'],
            'marital_status' => $this['marital_status'],
            'occupation' => $this['occupation'],
            'preferred_language' => $this['preferred_language'],
            'care_of' => $this['care_of'],
            'rural_route_address' => $this['rural_route_address'],
            'mailing_street_address' => $this['mailing_street_address'],
            'mailing_unit' => $this['mailing_unit'],
            'mailing_po_box' => $this['mailing_po_box'],
            'mailing_postal_code' => $this['mailing_postal_code'],
            'mailing_city' => $this['mailing_city'],
            'mailing_province' => $this['mailing_province'],
            'residential_street_address' => $this['residential_street_address'],
            'residential_unit' => $this['residential_unit'],
            'residential_city' => $this['residential_city'],
            'residential_province' => $this['residential_province'],
            'residential_postal_code' => $this['residential_postal_code'],
        ];
    }
}
