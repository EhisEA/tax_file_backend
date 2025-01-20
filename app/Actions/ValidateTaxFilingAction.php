<?php

namespace App\Actions;

use App\Enums\EmploymentStatusEnum;
use App\Exceptions\InvalidTaxFilingException;
use App\Models\TaxFiling;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidateTaxFilingAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @throws InvalidTaxFilingException
     */
    public function execute(TaxFiling $taxFiling): TaxFiling
    {
        $validator = Validator::make($taxFiling->toArray(), [
            "filing_year" => ["required", "numeric", "integer", "max:" . Carbon::today()->year],
            "employment_status" => ["required", Rule::enum(EmploymentStatusEnum::class)],
            "is_filing_for_self" => ["required", "boolean"],
            "is_first_time_filing" => ["required", "boolean"],
            "is_indigenous" => ["required", "boolean"],
            "income_type_received" => ["required", "string"],
            "business_activity_category" => ["nullable", "string"],
            "has_income_from_rentals" => ["required", "boolean"],
            "has_income_from_investments" => ["required", "boolean"],
            "was_resident_of_canada_for_tax_year" => ["required", "boolean"],
            "has_held_foreign_property_over_100k" => ["required", "boolean"],
            "has_received_foreign_income" => ["required", "boolean"],
            "marital_status" => ["required", "string"],
            "qualifies_for_disability_tax_credit" => ["required", "boolean"],
            "is_caregiver_for_disabled_family_member" => ["required", "boolean"],
            "is_foster_parent" => ["required", "boolean"],
            "special_occupations_work_in" => ["required", "string"],
            "is_creative_pro_with_unique_expenses" => ["required", "boolean"],
            "is_farmer_or_fisher" => ["required", "boolean"],
            "savings_plans_contributed_to" => ["required", "string"],
            "is_incurring_medical_expenses_for_self_or_dependent" => ["required", "boolean"],
            "is_working_from_home" => ["required", "boolean"],
            "has_made_charitable_donations" => ["required", "boolean"],
            "has_incurred_child_care_expenses" => ["required", "boolean"],
            "has_attended_post_secondary" => ["required", "boolean"],
            "has_completed_eligible_home_renovations" => ["required", "boolean"],
            "has_contributed_to_employment_expenses" => ["required", "boolean"],
            "has_lived_in_remote_area_for_six_months" => ["required", "boolean"],
            "extra_considerations" => ["nullable", "string"],
        ]);

        if ($validator->fails()) {
            throw new InvalidTaxFilingException();
        }

        $taxFiling->update(['submitted_at' => Carbon::today()]);

        return $taxFiling;
    }
}
