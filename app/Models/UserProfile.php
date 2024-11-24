<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class UserProfile extends Model
{
    public $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'profileable');
    }
}
