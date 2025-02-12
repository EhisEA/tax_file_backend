<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Laravel\Cashier\Events\WebhookReceived;
use Log;

class StripeEventListener
{
    public function handle(WebhookReceived $event): void
    {
        Log::info("Stripe event recieved: " . $event->payload["type"]);

        switch ($event->payload["type"]) {
            case "payment_intent.succeeded":
                $paymentIntentId = $event->payload["id"];

                $payment = Payment::whereStatus(PaymentStatus::PENDING->value)
                    ->whereStripePaymentIntentId($paymentIntentId)
                    ->first();

                if ($payment === null) {
                    break;
                }

                $payment->update([
                    "status" => PaymentStatus::COMPLETED,
                    "completed_at" => now(),
                ]);

                break;
            case "payment_intent.canceled":
                $paymentIntentId = $event->payload["id"];

                $payment = Payment::whereStatus(PaymentStatus::PENDING->value)
                    ->whereStripePaymentIntentId($paymentIntentId)
                    ->first();

                if ($payment === null) {
                    break;
                }

                $payment->update(["status" => PaymentStatus::FAILED]);
                break;
        }
    }
}
