<?php

namespace App\Http\Middleware;

use App\Enums\SubscriptionStatus;
use App\Repositories\ShopSubscriptionRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->routeIs('shop.*', 'admin.*')) {
            return $next($request);
        }

        $request->merge([
            'subscription_required' => false,
            'current_subscription' => null,
            'subscription_about_to_expire' => false,
            'subscription_time_left' => null,
            'subscription_expired' => false,
        ]);

        $user = $request->user();

        if (! $user || $user->hasRole('root')) {
            return $next($request);
        }

        $shop = generaleSetting('shop');

        if (! $shop) {
            return $next($request);
        }

        $rootShop = generaleSetting('rootShop');

        if ($shop->id == $rootShop->id) {
            return $next($request);
        }

        $generaleSetting = generaleSetting('setting');
        $subscriptionEnabled = $generaleSetting?->business_based_on == 'subscription' ?? false;

        if (! $subscriptionEnabled) {
            return $next($request);
        }

        $request->merge([
            'subscription_required' => true,
        ]);

        $currentSubscription = ShopSubscriptionRepository::query()
            ->where('shop_id', $shop->id)
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->first();

        if (! $currentSubscription) {
            return $next($request);
        }

        $request->merge([
            'current_subscription' => $currentSubscription,
        ]);

        if ($currentSubscription->ends_at && $currentSubscription->ends_at->lt(now())) {
            $request->merge([
                'subscription_expired' => true,
            ]);
        } elseif ($currentSubscription->ends_at && $currentSubscription->ends_at->lt(now()->addDays(7))) {
            $request->merge([
                'subscription_about_to_expire' => true,
                'subscription_time_left' => diffInLargestUnit(now(), $currentSubscription->ends_at),
            ]);
        }

        return $next($request);
    }
}
