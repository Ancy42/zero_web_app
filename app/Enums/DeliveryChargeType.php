<?php

namespace App\Enums;

enum DeliveryChargeType: string
{
    case PERORDER = 'Per Order';
    case PERPRODUCT = 'Per Product';
}
