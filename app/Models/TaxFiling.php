<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 *
 *
 * @property int $id
 * @property int $filing_year
 * @property string|null $employment_status
 * @property bool|null $is_filing_for_self
 * @property bool|null $is_first_time_filing
 * @property bool|null $is_indigenous
 * @property string|null $income_type_received
 * @property string|null $business_activity_category
 * @property bool|null $has_income_from_rentals
 * @property bool|null $has_income_from_investments
 * @property bool|null $was_resident_of_canada_for_tax_year
 * @property bool|null $has_held_foreign_property_over_100k
 * @property bool|null $has_received_foreign_income
 * @property string|null $marital_status
 * @property bool|null $qualifies_for_disability_tax_credit
 * @property bool|null $is_foster_parent
 * @property bool|null $is_caregiver_for_disabled_family_member
 * @property string|null $special_occupations_work_in
 * @property bool|null $is_creative_pro_with_unique_expenses
 * @property bool|null $is_farmer_or_fisher
 * @property string|null $savings_plans_contributed_to
 * @property bool|null $is_working_from_home
 * @property bool|null $has_made_charitable_donations
 * @property bool|null $has_incurred_child_care_expenses
 * @property bool|null $has_attended_post_secondary
 * @property bool|null $is_incurring_medical_expenses_for_self_or_dependent
 * @property bool|null $has_completed_eligible_home_renovations
 * @property bool|null $has_contributed_to_employment_expenses
 * @property bool|null $has_lived_in_remote_area_for_six_months
 * @property string|null $extra_considerations
 * @property string|null $submitted_at
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\TaxDocument> $documents
 * @property-read int|null $documents_count
 * @property-read Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Payment|null $pendingPayment
 * @property-read \App\Models\Payment|null $successfulPayment
 * @property-read \App\Models\User $user
 * @method static Builder<static>|TaxFiling newModelQuery()
 * @method static Builder<static>|TaxFiling newQuery()
 * @method static Builder<static>|TaxFiling query()
 * @method static Builder<static>|TaxFiling whereBusinessActivityCategory($value)
 * @method static Builder<static>|TaxFiling whereCreatedAt($value)
 * @method static Builder<static>|TaxFiling whereEmploymentStatus($value)
 * @method static Builder<static>|TaxFiling whereExtraConsiderations($value)
 * @method static Builder<static>|TaxFiling whereFilingYear($value)
 * @method static Builder<static>|TaxFiling whereHasAttendedPostSecondary($value)
 * @method static Builder<static>|TaxFiling whereHasCompletedEligibleHomeRenovations($value)
 * @method static Builder<static>|TaxFiling whereHasContributedToEmploymentExpenses($value)
 * @method static Builder<static>|TaxFiling whereHasHeldForeignPropertyOver100k($value)
 * @method static Builder<static>|TaxFiling whereHasIncomeFromInvestments($value)
 * @method static Builder<static>|TaxFiling whereHasIncomeFromRentals($value)
 * @method static Builder<static>|TaxFiling whereHasIncurredChildCareExpenses($value)
 * @method static Builder<static>|TaxFiling whereHasLivedInRemoteAreaForSixMonths($value)
 * @method static Builder<static>|TaxFiling whereHasMadeCharitableDonations($value)
 * @method static Builder<static>|TaxFiling whereHasReceivedForeignIncome($value)
 * @method static Builder<static>|TaxFiling whereId($value)
 * @method static Builder<static>|TaxFiling whereIncomeTypeReceived($value)
 * @method static Builder<static>|TaxFiling whereIsCaregiverForDisabledFamilyMember($value)
 * @method static Builder<static>|TaxFiling whereIsCreativeProWithUniqueExpenses($value)
 * @method static Builder<static>|TaxFiling whereIsFarmerOrFisher($value)
 * @method static Builder<static>|TaxFiling whereIsFilingForSelf($value)
 * @method static Builder<static>|TaxFiling whereIsFirstTimeFiling($value)
 * @method static Builder<static>|TaxFiling whereIsFosterParent($value)
 * @method static Builder<static>|TaxFiling whereIsIncurringMedicalExpensesForSelfOrDependent($value)
 * @method static Builder<static>|TaxFiling whereIsIndigenous($value)
 * @method static Builder<static>|TaxFiling whereIsWorkingFromHome($value)
 * @method static Builder<static>|TaxFiling whereMaritalStatus($value)
 * @method static Builder<static>|TaxFiling whereQualifiesForDisabilityTaxCredit($value)
 * @method static Builder<static>|TaxFiling whereSavingsPlansContributedTo($value)
 * @method static Builder<static>|TaxFiling whereSpecialOccupationsWorkIn($value)
 * @method static Builder<static>|TaxFiling whereSubmittedAt($value)
 * @method static Builder<static>|TaxFiling whereUpdatedAt($value)
 * @method static Builder<static>|TaxFiling whereUserId($value)
 * @method static Builder<static>|TaxFiling whereWasResidentOfCanadaForTaxYear($value)
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

    /**
     * @return HasMany<Payment, TaxFiling>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * @return HasOne<Payment, TaxFiling>
     */
    public function successfulPayment(): HasOne
    {
        return $this->payments()
            ->one()
            ->ofMany(["invoice_id" => "max", "updated_at" => "max"], function (
                Builder $query
            ) {
                $query->where("status", "=", PaymentStatus::COMPLETED->value);
            });
    }

    /**
     * @return HasOne<Payment, TaxFiling>
     */
    public function pendingPayment(): HasOne
    {
        return $this->payments()
            ->one()
            ->ofMany(["updated_at" => "max"], function (Builder $query) {
                $query->where("status", "=", PaymentStatus::PENDING->value);
            });
    }
}
