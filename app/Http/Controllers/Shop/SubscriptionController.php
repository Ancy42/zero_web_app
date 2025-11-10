<?php

namespace App\Http\Controllers\Shop;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPurchaseRequest;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Repositories\ShopSubscriptionRepository;
use App\Repositories\SubscriptionPlanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubscriptionController extends Controller
{
    public function index()
    {
        $generalSettings = generaleSetting('setting');

        if ($generalSettings?->business_based_on != 'subscription') {
            abort(404);
        }

        $subscriptionPlans = SubscriptionPlanRepository::query()->active()->paginate(20);
        $paymentGateways = Cache::rememberForever('payment_gateway', function () {
            return PaymentGateway::where('is_active', true)->get();
        });

        return view('shop.subscription.index', compact('subscriptionPlans', 'paymentGateways'));
    }

    public function purchase(SubscriptionPurchaseRequest $request)
    {
        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->first();

        if (! $paymentGateway || ! $paymentGateway->is_active) {
            $message = $paymentGateway ? 'Payment gateway not active' : 'Payment gateway not found';

            return back()->withErrors(['payment_method' => $message]);
        }

        $subscriptionPlan = SubscriptionPlanRepository::find($request->plan_id);
        $result = ShopSubscriptionRepository::storeByRequest($request, $subscriptionPlan);

        $payment = $result['payment'];
        $dirName = $paymentGateway->alias;
        $controller = 'App\\Http\\Controllers\\Gateway\\'.$dirName.'\\ProcessController';

        $user = $request->user();

        $info = [
            'email' => $user->email,
            'phone' => $user->phone,
            'name' => $user->name,
            'description' => sprintf('Plan: %s, price: %s, duration: %s, sale limit: %s',
                $subscriptionPlan->name,
                $subscriptionPlan->price,
                $subscriptionPlan->duration,
                $subscriptionPlan->sale_limit
            ),
            'type' => 'subscription',
        ];

        $url = $controller::process($paymentGateway, $payment, $info);

        $error = json_decode($url);

        if ($error) {
            $message = 'Opps! Payment gateway error occurred not configured correctly';
            $message2 = 'Payment gateway problem: '.$error->error;

            return back()->with('alertError', [
                'message' => $message,
                'message2' => $message2,
            ]);
        }

        return redirect()->away($url);
    }

    public function paymentSuccess(Request $request, Payment $payment)
    {
        $token = $request->token;

        if ($payment->payment_token !== $token) {
            return to_route('shop.subscription.index')->with('error', 'Invalid payment token');
        }

        if ($payment->is_paid) {
            return to_route('shop.subscription.index')->with('error', 'Payment already processed');
        }

        $payment->update([
            'is_paid' => true,
        ]);

        $subscription = ShopSubscriptionRepository::query()->where('payment_id', $payment->id)->first();

        $currentSubscription = ShopSubscriptionRepository::query()
            ->where('shop_id', $subscription->shop_id)
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->first();

        $saleLimit = $subscription->sale_limit;
        $remainingSales = $subscription->sale_limit;

        if ($currentSubscription) {
            if ($saleLimit && $currentSubscription->remaining_sales) {
                $saleLimit = $saleLimit + $currentSubscription->remaining_sales;
                $remainingSales = $saleLimit + $currentSubscription->remaining_sales;
            }

            $currentSubscription->update([
                'status' => SubscriptionStatus::CANCELLED,
            ]);
        }

        $subscription->update([
            'starts_at' => now(),
            'ends_at' => $subscription->duration ? now()->addDays($subscription->duration) : null,
            'sale_limit' => $saleLimit,
            'remaining_sales' => $remainingSales,
            'status' => SubscriptionStatus::ACTIVE,
        ]);

        $user = $subscription->shop->user;
        auth()->login($user);

        return to_route('shop.subscription.index')->with('success', 'Subscription successfully purchased');
    }

    public function paymentCancel(Request $request, Payment $payment)
    {
        $token = $request->token;

        if ($payment->payment_token !== $token) {
            return to_route('shop.subscription.index')->with('error', 'Invalid payment token');
        }

        if ($payment->is_paid) {
            return to_route('shop.subscription.index')->with('error', 'Payment already processed');
        }

        $subscription = ShopSubscriptionRepository::query()->where('payment_id', $payment->id)->first();
        $user = $subscription->shop->user;
        auth()->login($user);

        return to_route('shop.subscription.index')->with('error', 'Subscription purchase canceled');
    }
}
