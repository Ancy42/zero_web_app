<?php

namespace App\Repositories;

use App\Http\Requests\FlashSaleRequest;
use App\Models\FlashSale;
use Abedin\Maker\Repositories\Repository;
use Carbon\Carbon;

class FlashSaleRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return FlashSale::class;
    }

    /**
     * get incoming flash sale
     *
     * @return \App\Models\FlashSale | null
     *
     * @throws \Exception
     */
    public static function getIncoming()
    {
        return self::query()->where('start_date', '>', Carbon::now()->format('Y-m-d'))
            ->where('status', true)->latest('id')->get();
    }

    /**
     * Get the running flash sale.
     *
     * @return \App\Models\FlashSale | null
     */
    public static function getRunning()
    {
        $currentDateTime = Carbon::now();

        return self::query()
            ->whereDate('start_date', '<=', $currentDateTime->toDateString())
            ->where(function ($query) use ($currentDateTime) {
                $query->whereDate('end_date', '>', $currentDateTime->toDateString())
                    ->orWhere(function ($query) use ($currentDateTime) {
                        $query->whereDate('end_date', '=', $currentDateTime->toDateString())
                            ->whereTime('end_time', '>=', $currentDateTime->toTimeString());
                    });
            })
            ->where('status', true)
            ->latest('id')
            ->get();
    }

    /**
     * store new flash sale from request.
     */
    public static function storeByRequest(FlashSaleRequest $request): FlashSale
    {
        $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = Carbon::parse($request->end_date)->format('Y-m-d');

        return self::create([
            'name' => $request->name,
            'discount' => $request->discount,
            'start_date' => $startDate,
            'start_time' => $request->start_time,
            'end_date' => $endDate,
            'end_time' => $request->end_time,
            'image' => $request->thumbnail,
            'description' => $request->description,
            'status' => true,
        ]);
    }

    /**
     * Update a flash sale from the given request
     */
    public static function updateByRequest(FlashSaleRequest $request, FlashSale $flashSale): FlashSale
    {
        $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = Carbon::parse($request->end_date)->format('Y-m-d');

        $flashSale->update([
            'name' => $request->name,
            'discount' => $request->discount,
            'start_date' => $startDate,
            'start_time' => $request->start_time,
            'end_date' => $endDate,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'image' => $request->thumbnail ?? $flashSale->image,
        ]);

        return $flashSale;
    }
}
