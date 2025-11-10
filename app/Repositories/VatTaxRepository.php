<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\VatTaxRequest;
use App\Models\VatTax;

class VatTaxRepository extends Repository
{
    public static function model()
    {
        return VatTax::class;
    }

    public static function getActiveVatTaxes()
    {
        return self::query()->where('is_active', true)->get();
    }

    public static function storeByRequest(VatTaxRequest $request)
    {
        return self::create([
            'name' => $request->name,
            'percentage' => $request->percentage,
        ]);
    }

    public static function updateByRequest(VatTax $vatTax, VatTaxRequest $request)
    {
        return $vatTax->update([
            'name' => $request->name,
            'percentage' => $request->percentage,
        ]);
    }

    public static function toggle(VatTax $vatTax)
    {
        return $vatTax->update([
            'is_active' => ! $vatTax->is_active,
        ]);
    }
}
