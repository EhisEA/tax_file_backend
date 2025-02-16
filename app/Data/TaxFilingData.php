<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredUnless;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class TaxFilingData extends Data
{
    public bool $draft = false;

    /**
     * @param  array<int, TaxDocumentData>|null  $documents
     */
    public function __construct(
        #[RequiredUnless('draft', 'true'), Digits(4), Min(2014), Max(2035)]
        public Optional|int $filing_year,

        // Personal info
        #[RequiredUnless('draft', 'true')]
        public Optional|string $first_name,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $middle_name,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $last_name,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $social_insurance_number,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $phone_number,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $city,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $province,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $street_address,

        #[RequiredUnless('draft', 'true')]
        public Optional|string $postal_code,

        // Basic info
        #[RequiredUnless('draft', 'true')]
        public Optional|string $employment_status,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_filing_for_self,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_first_time_filing,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_indigenous,

        // Income and employment info
        #[RequiredUnless('draft', 'true')]
        public Optional|string $income_type_received,

        public Optional|null|string $business_activity_category,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_income_from_rentals,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_income_from_investments,

        // Residency and foreign assets
        #[RequiredUnless('draft', 'true')]
        public Optional|bool $was_resident_of_canada_for_tax_year,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_held_foreign_property_over_100k,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_received_foreign_income,

        // Personal and family situation
        #[RequiredUnless('draft', 'true')]
        public Optional|string $marital_status,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $qualifies_for_disability_tax_credit,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_foster_parent,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_caregiver_for_disabled_family_member,

        // Special occupations and volunteer work
        #[RequiredUnless('draft', 'true')]
        public Optional|string $special_occupations_work_in,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_creative_pro_with_unique_expenses,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_farmer_or_fisher,

        // Deductions and credits
        #[RequiredUnless('draft', 'true')]
        public Optional|string $savings_plans_contributed_to,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_working_from_home,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_made_charitable_donations,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_incurred_child_care_expenses,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_attended_post_secondary,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $is_incurring_medical_expenses_for_self_or_dependent,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_completed_eligible_home_renovations,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_contributed_to_employment_expenses,

        #[RequiredUnless('draft', 'true')]
        public Optional|bool $has_lived_in_remote_area_for_six_months,

        // Final Confirmation
        public Optional|null|string $extra_considerations,

        // Documents
        #[RequiredUnless('draft', 'true')]
        public array $documents,

        #[WithoutValidation]
        public ?Carbon $submitted_at
    ) {}

    public static function authorize(): bool
    {
        return Auth::user()->can('submit-tax-filing');
    }
}
