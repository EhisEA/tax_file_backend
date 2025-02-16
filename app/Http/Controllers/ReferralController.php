<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReferralCollection;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class ReferralController extends Controller
{
    public function code(Request $request)
    {
        $user = Auth::user();

        if (! $user->referral_code) {
            $referralCode = Str::upper(Str::random(8));

            $user->update(['referral_code' => $referralCode]);
        }

        return response()->json([
            'referral_code' => $user->referral_code,
            'invite_link' => route('auth.user.register').
                '?referral_code='.
                $user->referral_code,
        ]);
    }

    // TODO: use a user referral api resource and collection
    public function index(Request $request)
    {
        $user = $request->user();
        $userReferrals = Referral::with('user.profile')
            ->where('referrer_id', $user->id)
            ->paginate();

        return new ReferralCollection($userReferrals);
    }
}
