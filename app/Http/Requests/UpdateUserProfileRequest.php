<?php

namespace App\Http\Requests;

use App\Enums\Enums\MaritalStatusOptions;
use App\Enums\GenderOptions;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal info
            'first_name' => ['string', 'nullable'],
            'middle_name' => ['string', 'nullable'],
            'last_name' => ['string', 'nullable'],
            'date_of_birth' => ['date', 'before:' . Carbon::now()->subYears(18)
                    ->ceilYear(), 'nullable'], // at least 18 years
            'social_insurance_number' => ['string', 'regex:/^\d{3}-\d{3}-\d{3}$/', 'nullable'],
            'gender' => [Rule::enum(GenderOptions::class), 'nullable'],
            'marital_status' => [Rule::enum(MaritalStatusOptions::class), 'nullable'],
            'occupation' => ['string', 'nullable'],
            'preferred_language' => ['string', 'nullable'],

            // Contact info
            'care_of' => ['string', 'nullable'],
            'rural_route_address' => ['string', 'nullable'],
            'mailing_street_address' => ['string', 'nullable'],
            'mailing_unit' => ['string', 'nullable'],
            'mailing_po_box' => ['string', 'nullable'],
            'mailing_postal_code' => ['string', 'nullable'],
            'mailing_city' => ['string', 'nullable'],
            'mailing_province' => ['string', 'nullable'],
            'residential_street_address' => ['string', 'nullable'],
            'residential_unit' => ['string', 'nullable'],
            'residential_city' => ['string', 'nullable'],
            'residential_province' => ['string', 'nullable'],
            'residential_postal_code' => ['string', 'nullable']
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'first_name' => ['description' => 'First name', 'example' => 'Peter'],
            'middle_name' => ['description' => 'Middle name', 'example' => 'Benjamin'],
            'last_name' => ['description' => 'Last name', 'example' => 'Parker'],
            'date_of_birth' => ['description' => 'Date of birth'],
            'social_insurance_number' => ['description' => 'Social Insurance Number'],
            'gender' => [Rule::enum(GenderOptions::class)],
            'marital_status' => [Rule::enum(MaritalStatusOptions::class)],
            'occupation' => ['description' => 'Occupation', 'example' => 'Carpenter'],
            'preferred_language' => ['description' => 'Preferred language', 'example' => 'English'],

            'mailing_street_address' => ['description' => 'Mailing street address', 'example' => '123 Heavens Gate'],
            'mailing_unit' => ['description' => 'Mailing unit', 'example' => 'Unit 69'],
            'mailing_po_box' => ['description' => 'Mailing PO', 'example' => 'PO Box 123456'],
            'mailing_postal_code' => ['description' => 'Mailing postal code', 'example' => 'A1A 1A1'],
            'mailing_city' => ['description' => 'Mailing city', 'example' => 'Toronto'],
            'mailing_province' => ['description' => 'Mailing Province', 'example' => 'Ontario'],
            'residential_street_address' => ['description' => 'Residential street address', 'example' => '123 Heavens Gate'],
            'residential_unit' => ['description' => 'Residential unit', 'example' => 'Unit 69'],
            'residential_city' => ['description' => 'Residential city', 'example' => 'Toronto'],
            'residential_province' => ['description' => 'Residential Province', 'example' => 'Ontario'],
            'residential_postal_code' => ['description' => 'Residential postal code', 'example' => 'A1A 1A1']
        ];
    }
}
