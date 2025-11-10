<?php

namespace App\Providers;

use App\Enums\OrderStatus;
use App\Enums\Roles;
use App\Models\GeneraleSetting;
use App\Models\Order;
use App\Models\User;
use App\Repositories\LanguageRepository;
use App\Repositories\ThemeColorRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // set default string length
        Schema::defaultStringLength(255);

        // share order status with view
        view()->composer(['layouts.app', 'admin.dashboard', 'shop.dashboard'], function ($view) {

            // general setting
            $generaleSetting = generaleSetting('setting');

            $businessModel = $generaleSetting?->shop_type ?? 'multi';

            // Cache key prefix
            $cacheKeyPrefix = request()->is('admin*') ? 'admin_' : 'shop_';
            $shop = null;

            if ($businessModel === 'single' || request()->is('admin*')) {
                $shop = Cache::remember("{$cacheKeyPrefix}shop", 60 * 24, function () {
                    return User::role(Roles::ROOT->value)->whereHas('shop')->first()?->shop;
                });
            } else {
                $shop = generaleSetting('shop');
            }

            // Cache all orders count
            if (request()->is('admin*') && $businessModel != 'single') {
                $allOrders = Cache::remember("{$cacheKeyPrefix}all_orders", 60 * 24, function () {
                    return Order::count();
                });

                // Share status-wise orders
                foreach (OrderStatus::cases() as $status) {
                    $statusKey = "{$cacheKeyPrefix}status_".Str::camel($status->value);

                    $statusOrder = Cache::remember($statusKey, 60 * 24, function () use ($status) {
                        return Order::whereOrderStatus($status->value)->count();
                    });

                    $view->with(Str::camel($status->value), $statusOrder);
                }
            } else {
                $allOrders = Cache::remember("{$cacheKeyPrefix}all_orders", 60 * 24, function () use ($shop) {
                    return $shop?->orders()?->count() ?? Order::count();
                });

                // Share status-wise orders
                foreach (OrderStatus::cases() as $status) {

                    $statusKey = "{$cacheKeyPrefix}status_".Str::camel($status->value);

                    $statusOrder = Cache::remember($statusKey, 60 * 24, function () use ($shop, $status) {

                        return $shop?->orders()?->whereOrderStatus($status->value)->count();
                    });

                    $view->with(Str::camel($status->value), $statusOrder);
                }
            }

            // Share all orders
            $view->with('allOrders', $allOrders);
        });

        view()->composer('*', function ($view) {
            // Cache general setting data for  30 days
            $generaleSetting = Cache::remember('generale_setting', 60 * 24 * 30, function () {
                return GeneraleSetting::first();
            });

            $businessModel = $generaleSetting?->shop_type ?? 'multi';

            // language
            $languages = Cache::remember('languages', 60 * 24, function () {
                return LanguageRepository::getAll();
            });

            // share languages with view
            $view->with('languages', $languages);

            $rootUser = Cache::remember('rootUser', 60 * 24 * 30, function () {
                return User::role(Roles::ROOT->value)->whereHas('shop')->count();
            });

            $seederRun = true;
            // check if users exists
            if ($rootUser > 0) {
                $seederRun = false;
            }

            $storageLink = true;
            // check if storage folder exists
            if (file_exists(public_path('storage'))) {
                $storageLink = false;
            }

            // share seederRun and storageLink
            $view->with('seederRun', $seederRun);
            $view->with('storageLink', $storageLink);

            // share business model and generale setting
            $view->with('generaleSetting', $generaleSetting);
            $view->with('businessModel', $businessModel);
        });

        // use bootstrap 5 for pagination
        Paginator::useBootstrapFive();
    }
}
