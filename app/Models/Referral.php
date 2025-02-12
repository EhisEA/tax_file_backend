<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $referrer_id
 * @property int $referree_id
 * @property string|null $tax_filed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereReferreeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereReferrerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereTaxFiledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Referral extends Model
{
    protected $fillable = [
        'referrer_id', 'referree_id', 'tax_filed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'referree_id');
    }
}
