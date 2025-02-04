<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $date_of_birth
 * @property string|null $social_insurance_number
 * @property string|null $gender
 * @property string|null $marital_status
 * @property string|null $occupation
 * @property string|null $preferred_language
 * @property string|null $care_of
 * @property string|null $rural_route_address
 * @property string|null $mailing_street_address
 * @property string|null $mailing_unit
 * @property string|null $mailing_po_box
 * @property string|null $mailing_postal_code
 * @property string|null $mailing_city
 * @property string|null $mailing_province
 * @property string|null $residential_street_address
 * @property string|null $residential_unit
 * @property string|null $residential_city
 * @property string|null $residential_province
 * @property string|null $residential_postal_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property string $phone_number
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereCareOf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMailingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMailingPoBox($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMailingPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMailingProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMailingStreetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMailingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile wherePreferredLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereResidentialCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereResidentialPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereResidentialProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereResidentialStreetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereResidentialUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereRuralRouteAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereSocialInsuranceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfile whereUserId($value)
 * @mixin \Eloquent
 */
class UserProfile extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profile');
    }
}
