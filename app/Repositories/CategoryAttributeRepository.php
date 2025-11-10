<?php
namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\TranslateUtility;
use App\Models\CategoryAttribute;
use Abedin\Maker\Repositories\Repository;

class CategoryAttributeRepository extends Repository
{
    public static function model()
    {
        return CategoryAttribute::class;
    }


     public static function storeByRequest($request)
    {

        $categoryAttribute = self::create([
            'category_id' => $request->category_id,
            'parent_id' => $request->parent_id ?? null,
            'name' => $request->name,
            'status' => $request->is_active ? true : false,
        ]);

        return $categoryAttribute;
    }


     public static function updateByRequest($request, CategoryAttribute $categoryAttribute)
    {

        $categoryAttribute->update([
            'name' => $request->name,
            'status' => $request->is_active ? true : false,
        ]);

        return $categoryAttribute;
    }
}
