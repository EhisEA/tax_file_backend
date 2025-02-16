<?php

namespace App\Http\Requests;

use App\Enums\EmploymentStatusEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubmitTaxFilingRequest extends FormRequest
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
                'required',
                Rule::enum(EmploymentStatusEnum::class),
            ],
            'is_filing_for_self' => ['required', 'boolean'],
            'is_first_time_filing' => ['required', 'boolean'],
            'is_indigenous' => ['required', 'boolean'],
            'income_type_received' => ['required', 'string'],
            'business_activity_category' => ['nullable', 'string'],
            'has_income_from_rentals' => ['required', 'boolean'],
            'has_income_from_investments' => ['required', 'boolean'],
            'was_resident_of_canada_for_tax_year' => ['required', 'boolean'],
            'has_held_foreign_property_over_100k' => ['required', 'boolean'],
            'has_received_foreign_income' => ['required', 'boolean'],
            'marital_status' => ['required', 'string'],
            'qualifies_for_disability_tax_credit' => ['required', 'boolean'],
            'is_caregiver_for_disabled_family_member' => [
                'required',
                'boolean',
            ],
            'is_foster_parent' => ['required', 'boolean'],
            'special_occupations_work_in' => ['required', 'string'],
            'is_creative_pro_with_unique_expenses' => ['required', 'boolean'],
            'is_farmer_or_fisher' => ['required', 'boolean'],
            'savings_plans_contributed_to' => ['required', 'string'],
            'is_incurring_medical_expenses_for_self_or_dependent' => [
                'required',
                'boolean',
            ],
            'is_working_from_home' => ['required', 'boolean'],
            'has_made_charitable_donations' => ['required', 'boolean'],
            'has_incurred_child_care_expenses' => ['required', 'boolean'],
            'has_attended_post_secondary' => ['required', 'boolean'],
            'has_completed_eligible_home_renovations' => [
                'required',
                'boolean',
            ],
            'has_contributed_to_employment_expenses' => ['required', 'boolean'],
            'has_lived_in_remote_area_for_six_months' => [
                'required',
                'boolean',
            ],
            'extra_considerations' => ['nullable', 'string'],
            'documents' => ['required', 'array'],
            'documents.*.name' => [
                'required',
                'exists:tax_document_kinds,name',
            ],
            'documents.*.file_id' => ['required', 'exists:files,id'],
        ];
    }
}
