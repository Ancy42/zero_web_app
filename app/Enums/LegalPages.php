<?php

namespace App\Enums;

enum LegalPages: string
{
    case PRIVACYPOLICY = 'Privacy Policy';
    case TERMSANDCONDITIONS = 'Terms of Service';
    case RETURNANDREFUND = 'Return policy / Refund Policy';
    case SHIPPINGANDDELIVERY = 'Shipping & Delivery Policy';
    case ABOUTUS = 'About Us';
}
