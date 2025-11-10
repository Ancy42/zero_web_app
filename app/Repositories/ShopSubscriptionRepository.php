<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enums\SubscriptionStatus;
use App\Http\Requests\SubscriptionPurchaseRequest;
use App\Models\Payment;
use App\Models\ShopSubscription;
use App\Models\SubscriptionPlan;
use Mpdf\Tag\Sub;

class ShopSubscriptionRepository extends Repository
{
    public static function model()
    {
        return ShopSubscription::class;
    }

    public static function storeByRequest(SubscriptionPurchaseRequest $request, SubscriptionPlan $subscriptionPlan)
    {
        $payment = Payment::create([
            'amount' => $subscriptionPlan->price,
            'payment_method' => $request->payment_method,
        ]);

        $shop = generaleSetting('shop');

        $subscription = self::create([
            'shop_id' => $shop->id,
            'plan_id' => $subscriptionPlan->id,
            'price' => $subscriptionPlan->price,
            'duration' => $subscriptionPlan->duration,
            'sale_limit' => $subscriptionPlan->sale_limit,
            'remaining_sales' => $subscriptionPlan->sale_limit,
            'payment_id' => $payment->id,
        ]);

        return [
            'subscription' => $subscription,
            'payment' => $payment
        ];
    }
}
