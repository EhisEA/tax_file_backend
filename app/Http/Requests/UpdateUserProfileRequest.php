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
            'date_of_birth' => ['date', 'before:' . Carbon::now()->subYears(18)->ceilYear(), 'nullable'], // at least 18 years
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
}
