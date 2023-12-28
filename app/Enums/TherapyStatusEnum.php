<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum TherapyStatusEnum : string
{
    case CREATED = 'created';
    case PROCESSED = 'processed';
    case DISCARDED = 'discarded';
}
