<?php

namespace App\Enums;

enum StripePaymentStatusEnum: string
{
    case PROCESSING = "processing";
    case REQUIRES_ACTION = "requires_action";
    case SUCCESSFUL = "successful";
    case FAILED = "failed";

    public function message()
    {
        return match ($this) {
            self::PROCESSING => "Payment is processing",
            self::REQUIRES_ACTION => "Payment requires an action from the user",
            self::FAILED => "Payment failed",
            self::SUCCESSFUL => "Payment completed",
        };
    }
}
