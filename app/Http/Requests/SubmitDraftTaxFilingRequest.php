<?php

namespace App\Http\Requests;

use App\Enums\EmploymentStatusEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubmitDraftTaxFilingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->user()->can('submit-tax-filing');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'filing_year' => [
                'required',
                'numeric',
                'integer',
                'max:'.Carbon::today()->year,
            ],
            'employment_status' => [
                'nullable',
                Rule::enum(EmploymentStatusEnum::class),
            ],
            'is_filing_for_self' => ['nullable', 'boolean'],
            'is_first_time_filing' => ['nullable', 'boolean'],
            'is_indigenous' => ['nullable', 'boolean'],
            'income_type_received' => ['nullable', 'string'],
            'business_activity_category' => ['nullable', 'string'],
            'has_income_from_rentals' => ['nullable', 'boolean'],
            'has_income_from_investments' => ['nullable', 'boolean'],
            'was_resident_of_canada_for_tax_year' => ['nullable', 'boolean'],
            'has_held_foreign_property_over_100k' => ['nullable', 'boolean'],
            'has_received_foreign_income' => ['nullable', 'boolean'],
            'marital_status' => ['nullable', 'string'],
            'qualifies_for_disability_tax_credit' => ['nullable', 'boolean'],
            'is_caregiver_for_disabled_family_member' => [
                'nullable',
                'boolean',
            ],
            'is_foster_parent' => ['nullable', 'boolean'],
            'special_occupations_work_in' => ['nullable', 'string'],
            'is_creative_pro_with_unique_expenses' => ['nullable', 'boolean'],
            'is_farmer_or_fisher' => ['nullable', 'boolean'],
            'savings_plans_contributed_to' => ['nullable', 'string'],
            'is_incurring_medical_expenses_for_self_or_dependent' => [
                'nullable',
                'boolean',
            ],
            'is_working_from_home' => ['nullable', 'boolean'],
            'has_made_charitable_donations' => ['nullable', 'boolean'],
            'has_incurred_child_care_expenses' => ['nullable', 'boolean'],
            'has_attended_post_secondary' => ['nullable', 'boolean'],
            'has_completed_eligible_home_renovations' => [
                'nullable',
                'boolean',
            ],
            'has_contributed_to_employment_expenses' => ['nullable', 'boolean'],
            'has_lived_in_remote_area_for_six_months' => [
                'nullable',
                'boolean',
            ],
            'extra_considerations' => ['nullable', 'string'],
            'documents' => ['nullable', 'array'],
            'documents.*.name' => [
                'nullable',
                'exists:tax_document_kinds,name',
            ],
            'documents.*.file_id' => ['nullable', 'exists:files,id'],
        ];
    }
}
