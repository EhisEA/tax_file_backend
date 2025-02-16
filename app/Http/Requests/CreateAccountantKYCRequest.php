<?php

namespace App\Http\Requests;

use App\Enums\Enums\MaritalStatusOptions;
use App\Enums\GenderOptions;
use App\Models\AccountantProfile;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAccountantKYCRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /* @var User $user */
        $user = $this->user();

        if (! $user->hasVerifiedEmail()) {
            return false;
        }

        return $user->profile instanceof AccountantProfile;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'social_security_number' => ['string', 'regex:/^\d{3}-\d{3}-\d{3}$/'],
            'gender' => [Rule::enum(GenderOptions::class)],
            'religion' => ['required', 'string'],
            'marital_status' => [Rule::enum(MaritalStatusOptions::class)],
            'residential_address' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'country' => ['required', 'string'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'profile_picture' => ['required', 'file'],

            // Professional Information
            'business_name' => ['required', 'string'],
            'office_address' => ['required', 'string'],
            'professional_designation' => ['required', 'string'],
            'netfile_number' => ['required', 'string'],
            'years_of_experience' => ['required', 'numeric'],
            'office_postal_code' => ['required', 'string'],

            // Identification documents
            'proof_of_identity' => ['required', 'file'],
            'proof_of_address' => ['required', 'file'],
            'professional_qualification_document' => ['required', 'file'],
            'proof_of_business_registration' => ['required', 'file'],
        ];
    }
}
