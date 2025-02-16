<?php

namespace App\Listeners;

use App\Actions\AddUserReferralBonusAction;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\ReferralWallet;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Events\WebhookReceived;
use Log;

class StripeEventListener
{
    public function handle(WebhookReceived $event): void
    {
        Log::info('Stripe event recieved: '.$event->payload['type']);

        switch ($event->payload['type']) {
            case 'payment_intent.succeeded':
                $paymentIntentId = $event->payload['id'];

                $payment = Payment::whereStatus(PaymentStatus::PENDING->value)
                    ->whereStripePaymentIntentId($paymentIntentId)
                    ->first();

                if ($payment === null) {
                    break;
                }

                DB::beginTransaction();

                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    'completed_at' => now(),
                ]);

                $wallet = ReferralWallet::whereUserId(
                    $payment->user_id
                )->first();

                if ($wallet) {
                    $wallet->amount -= $payment->discount;
                    $wallet->save();
                } else {
                    ReferralWallet::create([
                        'user_id' => $payment->user_id,
                        'amount' => 10,
                    ]);
                }

                app(AddUserReferralBonusAction::class)->execute(
                    $payment->user_id
                );

                DB::commit();

                break;
            case 'payment_intent.canceled':
                $paymentIntentId = $event->payload['id'];

                $payment = Payment::whereStatus(PaymentStatus::PENDING->value)
                    ->whereStripePaymentIntentId($paymentIntentId)
                    ->first();

                if ($payment === null) {
                    break;
                }

                $payment->update(['status' => PaymentStatus::FAILED]);
                break;
        }
    }
}
