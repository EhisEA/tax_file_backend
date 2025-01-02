<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
