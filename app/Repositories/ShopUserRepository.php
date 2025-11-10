<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\ShopUser;

class ShopUserRepository extends Repository
{
    public static function model()
    {
        return ShopUser::class;    
    }
}