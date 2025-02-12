<?php

namespace App\Actions;

use App\Models\Referral;
use App\Models\ReferralWallet;
use App\Models\User;

class AddUserReferralBonusAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(int $referree_id)
    {
        $referral = Referral::whereReferreeId($referree_id)->first();

        if ($referral === null) {
            return;
        }

        $wallet = ReferralWallet::whereUserId($referral->referrer_id)->first();

        if ($wallet === null) {
            ReferralWallet::query()->create([
                "user_id" => $referral->referrer_id,
                "amount" => 5,
            ]);
        } else {
            $wallet->amount += 5;
            $wallet->save();
        }
    }
}
