<?php

namespace Abedin\Maker\Lib\Managers;
use Abedin\Maker\Lib\Traits\ManagerTrait;

class SetPurchaseKey
{
    use ManagerTrait;
    public static function set(): void
    {
        $key = request()->purchase_code;
        if($key){
            self::storePurchaseKey($key);
        }
    }


}
