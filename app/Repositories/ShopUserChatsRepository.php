<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\ShopUserChats;

class ShopUserChatsRepository extends Repository
{
    public static function model()
    {
        return ShopUserChats::class;    
    }
}