<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class ReferralController extends Controller
{
    public function code(Request $request)
    {
        $user = Auth::user();

        if (!$user->referral_code) {
            $referralCode = Str::upper(Str::random(8));

            $user->update(["referral_code" => $referralCode]);
        }

        return response()->json([
            "referral code" => $user->referral_code,
            "invite_link" =>
                route("auth.user.register") .
                "?referral_code=" .
                $user->referral_code,
        ]);
    }

    // TODO: use a user referral api resource and collection
    public function index(Request $request)
    {
        $user = $request->user();
        $user_referrals = Referral::with("user.profile")
            ->where("referrer_id", $user->id)
            ->get();

        $user_referrals = $user_referrals->map(function ($referral) {
            $profile = $referral->user->profile;
            $referral->referree_details = [
                "first_name" => $profile->first_name ?? null,
                "middle_name" => $profile->middle_name ?? null,
                "last_name" => $profile->last_name ?? null,
                "email" => $referral->user->email ?? null,
            ];
            unset($referral->user);
            unset($referral->profile);
            return $referral;
        });

        return response()->json(["user_referrals" => $user_referrals]);
    }
}
