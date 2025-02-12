<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ValidateStripePayment;
use App\Enums\PaymentStatus;
use App\Enums\StripePaymentStatusEnum;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\ReferralWallet;
use App\Models\TaxFiling;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;
use Str;

class PaymentController extends Controller
{
    public function __construct(
        protected ValidateStripePayment $validateStripePayment
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();

        $status = PaymentStatus::tryFrom(
            $request->string("status")->toString()
        );

        if ($status) {
            $payments = Payment::whereStatus($status->value)
                ->whereUserId($user->id)
                ->paginate();

            return new PaymentCollection($payments);
        }

        return new PaymentCollection($user->payments()->paginate());
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();

        if ($user->id !== $payment->user_id) {
            return response()->json(["message" => "payment not found"], 404);
        }

        return new PaymentResource($payment);
    }

    public function initialise(Request $request, TaxFiling $taxFiling)
    {
        $user = Auth::user();
        $useReferral = $request->boolean("use_referral");

        DB::beginTransaction();

        if ($user->id !== $taxFiling->user_id) {
            return response()->json(["message" => "Tax filing not found"], 404);
        }

        if ($taxFiling->submitted_at === null) {
            return response()->json(
                ["message" => "Cannot pay for a draft tax filing"],
                422
            );
        }

        $discount = 0;
        if ($useReferral) {
            // Get discount from referral bonus
            $wallet = ReferralWallet::whereUserId($user->id)->first();

            if ($wallet) {
                $discount = $wallet->amount;
            } else {
                ReferralWallet::create([
                    "user_id" => $user->id,
                    "amount" => 10,
                ]);
            }
        }

        // Can only pay for tax filings that don't a successful or pending payment
        $taxFiling->load(["successfulPayment", "pendingPayment"]);
        $existingPayment =
            $taxFiling->successfulPayment ?? $taxFiling->pendingPayment;

        if ($existingPayment) {
            $paymentIntent = $user->findPayment(
                $existingPayment->stripe_payment_intent_id
            );

            // validate the payment incase status has changed
            $stripePaymentStatus = $this->validateStripePayment->execute(
                $paymentIntent,
                $existingPayment
            );

            DB::commit();

            $data = new PaymentResource($existingPayment);

            if ($existingPayment->status === PaymentStatus::COMPLETED) {
                return response()->json([
                    "completed" => true,
                    "message" => "Payment completed for this taxfiling",
                    "data" => $data,
                ]);
            }

            return match ($stripePaymentStatus) {
                StripePaymentStatusEnum::REQUIRES_ACTION => response()->json([
                    "requires_action" => true,
                    "message" => $stripePaymentStatus->message(),
                    "data" => $data,
                    "stripe_client_secret" => $paymentIntent->clientSecret(),
                ]),

                StripePaymentStatusEnum::PROCESSING => response()->json([
                    "processing" => true,
                    "message" => $stripePaymentStatus->message(),
                    "data" => $data,
                ]),

                StripePaymentStatusEnum::FAILED => response()->json([
                    "failed" => true,
                    "message" => $stripePaymentStatus->message(),
                    "data" => $data,
                ]),
            };
        }

        $payment = $taxFiling->payments()->create([
            "invoice_id" => Str::random(8),
            "total" => 100.0,
            "discount" => $discount,
            "charged_amount" => 100 - $discount,
            "user_id" => $user->id,
            "status" => PaymentStatus::PENDING,
        ]);

        $paymentIntent = $user->pay($payment->charged_amount, [
            "currency" => "CAD",
            "automatic_payment_methods" => [
                "enabled" => true,
            ],
            "metadata" => [
                "payment_id" => $payment->id,
            ],
        ]);

        $paymentIntentId = $paymentIntent->asStripePaymentIntent()->id;
        $payment->update([
            "stripe_payment_intent_id" => $paymentIntentId,
        ]);

        DB::commit();

        return response()->json([
            "message" => "Payment initialised",
            "data" => new PaymentResource($payment),
            "stripe_client_secret" => $paymentIntent->clientSecret(),
        ]);
    }

    public function complete(Request $request)
    {
        $user = Auth::user();

        $paymentIntentId = $request->string("payment_intent");
        $paymentIntent = $user->findPayment($paymentIntentId);

        $payment = Payment::whereUserId($user->id)
            ->whereStatus(PaymentStatus::PENDING->value)
            ->whereStripePaymentIntentId($paymentIntentId)
            ->first();

        if ($payment === null) {
            return response()->json(
                ["message" => "Payment does not exist"],
                404
            );
        }

        $stripePaymentStatus = $this->validateStripePayment->execute(
            $paymentIntent,
            $payment
        );

        return match ($stripePaymentStatus) {
            StripePaymentStatusEnum::SUCCESSFUL => response()->json([
                "success" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
            ]),
            StripePaymentStatusEnum::REQUIRES_ACTION => response()->json([
                "requires_action" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
                "stripe_client_secret" => $paymentIntent->clientSecret(),
            ]),
            StripePaymentStatusEnum::PROCESSING => response()->json([
                "processing" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
            ]),
            StripePaymentStatusEnum::FAILED => response()->json([
                "failed" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
            ]),
        };
    }

    public function confirm(Payment $payment)
    {
        $user = Auth::user();

        $paymentIntent = $user->findPayment($payment->stripe_payment_intent_id);

        if (!$paymentIntent) {
            Log::warning(
                "Payment intent for payment {$payment->id} does not exist"
            );

            $payment->update(["status" => PaymentStatus::FAILED->value]);

            return response()->json(
                [
                    "message" =>
                        "Stripe payment does not exist for this payment",
                ],
                422
            );
        }

        $stripePaymentStatus = $this->validateStripePayment->execute(
            $paymentIntent,
            $payment
        );

        return match ($stripePaymentStatus) {
            StripePaymentStatusEnum::SUCCESSFUL => response()->json([
                "success" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
            ]),
            StripePaymentStatusEnum::REQUIRES_ACTION => response()->json([
                "requires_action" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
                "stripe_client_secret" => $paymentIntent->clientSecret(),
            ]),
            StripePaymentStatusEnum::PROCESSING => response()->json([
                "processing" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
            ]),
            StripePaymentStatusEnum::FAILED => response()->json([
                "failed" => true,
                "message" => $stripePaymentStatus->message(),
                "data" => new PaymentResource($payment),
            ]),
        };
    }
}
