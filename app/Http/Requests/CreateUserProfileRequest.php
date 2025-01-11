<?php

namespace App\Http\Requests;

use App\Enums\Enums\MaritalStatusOptions;
use App\Enums\GenderOptions;
use App\Models\User;
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
        /* @var User $user */
        $user = $this->user();

        if (!$user->hasVerifiedEmail()){
            return false;
        }

        $user->load('userProfile');
        if ($user->userProfile === null) {
            return false;
        }

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
            'phone_number' => ['string'],
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
}
