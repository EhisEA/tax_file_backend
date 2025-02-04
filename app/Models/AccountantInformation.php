<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $profile_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $social_security_number
 * @property string|null $gender
 * @property string|null $religion
 * @property string|null $marital_status
 * @property string|null $residential_address
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $province
 * @property string|null $city
 * @property int|null $profile_picture_id
 * @property string|null $business_name
 * @property string|null $office_address
 * @property string|null $professional_designation
 * @property string|null $netfile_number
 * @property int|null $years_of_experience
 * @property string|null $office_postal_code
 * @property int|null $proof_of_identity_id
 * @property int|null $proof_of_address_id
 * @property int|null $professional_qualification_document_id
 * @property int|null $proof_of_business_registration_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\File|null $professional_qualification_document
 * @property-read \App\Models\AccountantProfile $profile
 * @property-read \App\Models\File|null $profile_picture
 * @property-read \App\Models\File|null $proof_of_address
 * @property-read \App\Models\File|null $proof_of_business_registration
 * @property-read \App\Models\File|null $proof_of_identity
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereNetfileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereOfficeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereOfficePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProfessionalDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProfessionalQualificationDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProfilePictureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProofOfAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProofOfBusinessRegistrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProofOfIdentityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereResidentialAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereSocialSecurityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantInformation whereYearsOfExperience($value)
 * @mixin \Eloquent
 */
class AccountantInformation extends Model
{
    protected $guarded = [
        // 'profile_picture',
        // 'proof_of_identity',
        // 'proof_of_address',
        // 'professional_qualification_document',
        // 'proof_of_business_registration'
    ];

    protected $with = [
        'profile_picture',
        'proof_of_identity',
        'proof_of_address',
        'professional_qualification_document',
        'proof_of_business_registration'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(AccountantProfile::class, 'profile_id');
    }

    public function profile_picture(): BelongsTo
    {
        return $this->belongsTo(File::class, 'profile_picture_id');
    }

    public function proof_of_identity(): BelongsTo
    {
        return $this->belongsTo(File::class, 'proof_of_identity_id');
    }

    public function proof_of_address(): BelongsTo
    {
        return $this->belongsTo(File::class, 'proof_of_address_id');
    }

    public function professional_qualification_document(): BelongsTo
    {
        return $this->belongsTo(File::class, 'professional_qualification_document_id');
    }

    public function proof_of_business_registration(): BelongsTo
    {
        return $this->belongsTo(File::class, 'proof_of_business_registration_id');
    }
}
