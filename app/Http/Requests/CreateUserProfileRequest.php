<?php

namespace App\Http\Requests;

use App\Enums\Enums\MaritalStatusOptions;
use App\Enums\GenderOptions;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserProfileRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Personal info
            'first_name' => ['string'],
            'middle_name' => ['string', 'nullable'],
            'last_name' => ['string'],
            'date_of_birth' => ['date', 'before:' . Carbon::now()->subYears(18)->ceilYear()], // at least 18 years
            'social_insurance_number' => ['string', 'regex:/^\d{3}-\d{3}-\d{3}$/'],
            'gender' => [Rule::enum(GenderOptions::class)],
            'marital_status' => [Rule::enum(MaritalStatusOptions::class)],
            'occupation' => ['string'],
            'preferred_language' => ['string'],

            // Contact info
            'care_of' => ['string'],
            'rural_route_address' => ['string'],
            'mailing_street_address' => ['string'],
            'mailing_unit' => ['string'],
            'mailing_po_box' => ['string'],
            'mailing_postal_code' => ['string'],
            'mailing_city' => ['string'],
            'mailing_province' => ['string'],
            'residential_street_address' => ['string'],
            'residential_unit' => ['string'],
            'residential_city' => ['string'],
            'residential_province' => ['string'],
            'residential_postal_code' => ['string']
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
