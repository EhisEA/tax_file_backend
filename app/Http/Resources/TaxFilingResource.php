<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxFilingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this["id"],
            "filing_year" => $this["filing_year"],
            "employment_status" => $this["employment_status"],
            "is_filing_for_self" => $this["is_filing_for_self"],
            "is_first_time_filing" => $this["is_first_time_filing"],
            "is_indigenous" => $this["is_indigenous"],
            "income_type_received" => $this["income_type_received"],
            "business_activity_category" => $this["business_activity_category"],
            "has_income_from_rentals" => $this["has_income_from_rentals"],
            "has_income_from_investments" =>
                $this["has_income_from_investments"],
            "was_resident_of_canada_for_tax_year" =>
                $this["was_resident_of_canada_for_tax_year"],
            "has_held_foreign_property_over_100k" =>
                $this["has_held_foreign_property_over_100k"],
            "has_received_foreign_income" =>
                $this["has_received_foreign_income"],
            "marital_status" => $this["marital_status"],
            "qualifies_for_disability_tax_credit" =>
                $this["qualifies_for_disability_tax_credit"],
            "is_caregiver_for_disabled_family_member" =>
                $this["is_caregiver_for_disabled_family_member"],
            "is_foster_parent" => $this["is_foster_parent"],
            "special_occupations_work_in" =>
                $this["special_occupations_work_in"],
            "is_creative_pro_with_unique_expenses" =>
                $this["is_creative_pro_with_unique_expenses"],
            "is_farmer_or_fisher" => $this["is_farmer_or_fisher"],
            "savings_plans_contributed_to" =>
                $this["savings_plans_contributed_to"],
            "is_incurring_medical_expenses_for_self_or_dependent" =>
                $this["is_incurring_medical_expenses_for_self_or_dependent"],
            "is_working_from_home" => $this["is_working_from_home"],
            "has_made_charitable_donations" =>
                $this["has_made_charitable_donations"],
            "has_incurred_child_care_expenses" =>
                $this["has_incurred_child_care_expenses"],
            "has_attended_post_secondary" =>
                $this["has_attended_post_secondary"],
            "has_completed_eligible_home_renovations" =>
                $this["has_completed_eligible_home_renovations"],
            "has_contributed_to_employment_expenses" =>
                $this["has_contributed_to_employment_expenses"],
            "has_lived_in_remote_area_for_six_months" =>
                $this["has_lived_in_remote_area_for_six_months"],
            "extra_considerations" => $this["extra_considerations"],
            "submitted_at" => $this["submitted_at"],
            "is_draft" => $this["submitted_at"] === null,
            "documents" => new TaxDocumentCollection($this["documents"]),
            "updated_at" => $this["updated_at"],
            "created_at" => $this["created_at"],
        ];
    }
}
