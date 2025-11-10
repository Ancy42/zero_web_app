<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\HomeTheme;

class HomeThemeRepository extends Repository
{
    public static function model()
    {
        return HomeTheme::class;    
    }
}