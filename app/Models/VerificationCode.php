<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    //
    protected $fillable = [
        'code',
        'used_at',
        'expires_at',
        'user_id'
    ];
}
