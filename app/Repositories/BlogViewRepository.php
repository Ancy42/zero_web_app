<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\BlogView;

class BlogViewRepository extends Repository
{
    public static function model()
    {
        return BlogView::class;
    }
}
