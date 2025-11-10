<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\SubscriptionPlanRequest;
use App\Models\SubscriptionPlan;

class SubscriptionPlanRepository extends Repository
{
    public static function model()
    {
        return SubscriptionPlan::class;
    }

    public static function storeByRequest(SubscriptionPlanRequest $request)
    {
        return self::create([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'sale_limit' => $request->sale_limit,
            'is_popular' => $request->is_popular ? true : false,
        ]);
    }

    public static function updateByRequest(SubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan): SubscriptionPlan
    {
        $subscriptionPlan->update([
            'name' => $request->name,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'sale_limit' => $request->sale_limit,
            'is_popular' => $request->is_popular ? true : false,
        ]);

        return $subscriptionPlan;
    }
}
