<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralWallet whereUserId($value)
 * @mixin \Eloquent
 */
class ReferralWallet extends Model
{
    protected $fillable = ['user_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
