<?php

namespace App\Enums;

enum EmploymentStatusEnum: string
{
    case EMPLOYED = "employed";
    case SELF_EMPLOYED = "self-employed";
    case RETIRED = "retired";
    case STUDENT = "student";
    case UNEMPLOYED = "unemployed";
}
