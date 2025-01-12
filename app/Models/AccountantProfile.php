<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property AccountantInformation $kyc
 */
class AccountantProfile extends Model
{
    protected $guarded = [];

    public function kyc(): HasOne
    {
        return $this->hasOne(AccountantInformation::class, 'profile_id', 'id');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profile');
    }
}
