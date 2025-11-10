<?php

namespace App\Enums;

enum DeductionType: string
{
    case INCLUSIVE = 'inclusive';
    case EXCLUSIVE = 'exclusive';
}
