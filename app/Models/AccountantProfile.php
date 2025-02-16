<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccountantInformation|null $kyc
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountantProfile whereUserId($value)
 *
 * @mixin \Eloquent
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
