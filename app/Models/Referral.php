<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
