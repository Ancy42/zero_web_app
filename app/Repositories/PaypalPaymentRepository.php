<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\PaypalPayment;

class PaypalPaymentRepository extends Repository
{
    public static function model()
    {
        return PaypalPayment::class;    
    }
}