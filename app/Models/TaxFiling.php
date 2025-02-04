<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property int $filing_year
 * @property string $employment_status
 * @property bool $is_filing_for_self
 * @property bool $is_first_time_filing
 * @property bool $is_indigenous
 * @property string $income_type_received
 * @property string|null $business_activity_category
 * @property bool $has_income_from_rentals
 * @property bool $has_income_from_investments
 * @property bool $was_resident_of_canada_for_tax_year
 * @property bool $has_held_foreign_property_over_100k
 * @property bool $has_received_foreign_income
 * @property string $marital_status
 * @property bool $qualifies_for_disability_tax_credit
 * @property bool $is_caregiver_for_disabled_family_member
 * @property bool $is_foster_parent
 * @property string $special_occupations_work_in
 * @property bool $is_creative_pro_with_unique_expenses
 * @property bool $is_farmer_or_fisher
 * @property string $savings_plans_contributed_to
 * @property bool $is_incurring_medical_expenses_for_self_or_dependent
 * @property bool $is_working_from_home
 * @property bool $has_made_charitable_donations
 * @property bool $has_incurred_child_care_expenses
 * @property bool $has_attended_post_secondary
 * @property bool $has_completed_eligible_home_renovations
 * @property bool $has_contributed_to_employment_expenses
 * @property bool $has_lived_in_remote_area_for_six_months
 * @property string|null $extra_considerations
 * @property string|null $submitted_at
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\TaxDocument> $documents
 * @property-read int|null $documents_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereBusinessActivityCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereExtraConsiderations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereFilingYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasAttendedPostSecondary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasCompletedEligibleHomeRenovations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasContributedToEmploymentExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasHeldForeignPropertyOver100k($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasIncomeFromInvestments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasIncomeFromRentals($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasIncurredChildCareExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasLivedInRemoteAreaForSixMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasMadeCharitableDonations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereHasReceivedForeignIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIncomeTypeReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsCaregiverForDisabledFamilyMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsCreativeProWithUniqueExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsFarmerOrFisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsFilingForSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsFirstTimeFiling($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsFosterParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsIncurringMedicalExpensesForSelfOrDependent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsIndigenous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereIsWorkingFromHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereQualifiesForDisabilityTaxCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereSavingsPlansContributedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereSpecialOccupationsWorkIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaxFiling whereWasResidentOfCanadaForTaxYear($value)
 * @mixin \Eloquent
 */
class TaxFiling extends Model
{
    protected $guarded = [];

    protected $with = ["documents"];

    /**
     * @return BelongsTo<User,TaxFiling>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<TaxDocument,TaxFiling>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(TaxDocument::class);
    }
}
