<?php

namespace App\Actions;

use App\Enums\PaymentStatus;
use App\Enums\StripePaymentStatusEnum;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Payment as CashierPayment;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidateStripePayment
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(CashierPayment $paymentIntent, Payment $payment)
    {
        if (
            $paymentIntent->requiresAction() ||
            $paymentIntent->requiresPaymentMethod() ||
            $paymentIntent->requiresConfirmation()
        ) {
            return StripePaymentStatusEnum::REQUIRES_ACTION;
        }

        if ($paymentIntent->isProcessing()) {
            return StripePaymentStatusEnum::PROCESSING;
        }

        if ($paymentIntent->isSucceeded()) {
            $payment->update([
                "status" => PaymentStatus::COMPLETED,
                "completed_at" => now(),
            ]);

            return StripePaymentStatusEnum::SUCCESSFUL;
        }

        if ($paymentIntent->isCanceled()) {
            $payment->update([
                "status" => PaymentStatus::FAILED,
            ]);

            return StripePaymentStatusEnum::FAILED;
        }

        throw new HttpException(
            statusCode: 500,
            message: "unknown stripe payment status"
        );
    }
}
