<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = "pending";
    case COMPLETED = "completed";
    case FAILED = "failed";
}
//
