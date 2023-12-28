<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum RoleEnum : int
{
    case SUPERADMIN = 1;
    case ADMINISTRATOR = 2;
    case RECEPSIONIST = 3;
    case DOCTOR = 4;
    case DR_OZONO = 5;
}
