<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;

class CurrencyRepository extends Repository
{
    public static function model()
    {
        return Currency::class;
    }

    public static function storeByRequest(CurrencyRequest $request): Currency
    {
        return self::create([
            'name' => $request->name,
            'code' => $request->code,
            'symbol' => $request->symbol,
            'rate' => $request->rate,
        ]);
    }

    public static function updateByRequest(CurrencyRequest $request, Currency $currency): Currency
    {
        $currency->update([
            'name' => $request->name,
            'code' => $request->code,
            'symbol' => $request->symbol,
            'rate' => $request->rate,
        ]);

        return $currency;
    }

    public static function setDefaultCurrency(Currency $currency): void
    {
        self::query()->where('is_default', true)->update(['is_default' => false]);

        $currency->update(['is_default' => true, 'rate' => 1]);
    }
}
