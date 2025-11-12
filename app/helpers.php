<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Currency;
use App\Models\DeliveryCharge;
use App\Models\GeneraleSetting;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

if (! function_exists('getDistance')) {
    /**
     * Calculate the distance between two geographical coordinates.
     *
     * @param  array  $firstLatLng  The first [latitude, longitude] coordinates
     * @param  array  $secondLatLng  The second [latitude, longitude] coordinates
     * @return float The distance between the two coordinates in kilometers
     */
    function getDistance(array $firstLatLng, array $secondLatLng): float
    {
        if (empty($firstLatLng) || empty($secondLatLng)) {
            return 0;
        }

        $theta = ($firstLatLng[1] - $secondLatLng[1]);
        $dist = sin(deg2rad($firstLatLng[0])) *
            sin(deg2rad($secondLatLng[0])) +
            cos(deg2rad($firstLatLng[0])) *
            cos(deg2rad($secondLatLng[0])) *
            cos(deg2rad($theta));

        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles * 1.609344;
    }
}

if (! function_exists('generaleSetting')) {
    /**
     * Get the generale setting Or shop Or default currency.
     *
     * @param  string|null  $type  = 'setting|shop|rootShop|defaultCurrency'
     *
     * @type setting|shop|rootShop|defaultCurrency
     *
     * @default GeneraleSetting
     *
     * @return GeneraleSetting|Shop|Currency
     *
     * @throws \Exception
     */
    function generaleSetting($type = null, $authUser = null)
    {
        // Cache general setting data for  30 days
        $generaleSetting = Cache::remember('generale_setting', 60 * 24 * 30, function () {
            return GeneraleSetting::first();
        });

        if ($type == 'setting' || $type == null) {
            return $generaleSetting;
        }

        if ($type == 'rootShop') {
            return Cache::remember('admin_shop', 60 * 24 * 7, function () {
                return User::role('root')->whereHas('shop')->first()?->shop;
            });
        }

        if ($type == 'shop') {
            if ($generaleSetting?->shop_type == 'single') {
                $shop = User::role('root')->whereHas('shop')->first()?->shop;
            } else {
                /** @var User */
                $user = $authUser ?? auth()->user();
                $shop = $user?->shop ?? $user?->myShop;
            }

            if (! $shop) {
                $shop = User::role('root')->whereHas('shop')->first()?->shop;
            }

            return $shop;
        }

        if ($type == 'defaultCurrency') {
            $defaultCurrency = Cache::remember('default_currency', 60 * 24 * 30, function () {
                return Currency::where('is_default', 1)->first();
            });

            return $defaultCurrency;
        }

        return $generaleSetting;
    }
}

if (! function_exists('showCurrency')) {

    /**
     * Show the currency in the given amount.
     *
     * @param  float  $amount
     */
    function showCurrency($amount = null): string
    {
        $generaleSetting = generaleSetting('setting');

        $currency = $generaleSetting?->currency ?? '$';

        $amount = ($amount == 0 || $amount == null) ? 0 : $amount;

        if ($generaleSetting?->currency_position == 'suffix') {
            return $amount . $currency;
        }

        return $currency . $amount;
    }
}

if (! function_exists('getDeliveryCharge')) {

    /**
     * get the delivery charge.
     *
     * @param  int  $orderQuantity
     */
    function getDeliveryCharge($orderQuantity): float
    {
        $deliveryCharge = DeliveryCharge::where('min_qty', '<=', $orderQuantity)
            ->where('max_qty', '>=', $orderQuantity)
            ->first();

        return $deliveryCharge?->charge ?? 0;
    }
}

if (! function_exists('permissionName')) {

    /**
     * get the permission name for the customer readable.
     *
     * @param  string  $permission
     */
    function permissionName($permission): string
    {
        $customerReadAbleNames = config('acl.customerReadableNames');

        if (isset($customerReadAbleNames[$permission])) {
            return trans($customerReadAbleNames[$permission]);
        }

        return trans($permission);
    }
}

if (! function_exists('getYear')) {
    function diffInLargestUnit(Carbon $from, ?Carbon $to = null): string
    {
        $to = $to ?? now();

        $diff = $from->diff($to);

        if ($diff->y >= 1) {
            return $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
        }

        if ($diff->m >= 1) {
            return $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
        }

        if ($diff->d >= 1) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
        }

        if ($diff->h >= 1) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
        }

        if ($diff->i >= 1) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
        }

        return $diff->s . ' second' . ($diff->s !== 1 ? 's' : '');
    }
}

if (! function_exists('daysToLargestUnit')) {
    function daysToLargestUnit(int $days): string
    {
        if ($days >= 365) {
            $years = floor($days / 365);
            return $years . ' year' . ($years > 1 ? 's' : '');
        }

        if ($days >= 30) {
            $months = floor($days / 30);
            return $months . ' month' . ($months > 1 ? 's' : '');
        }

        if ($days >= 7) {
            $weeks = floor($days / 7);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '');
        }

        return $days . ' day' . ($days > 1 ? 's' : '');
    }
}



if (! function_exists('currencyData')) {

    /**
     * Show the currency in the given amount.
     *
     * @param  float  $amount
     */
    // function currencyData($input)
    // {
    //     if (!$input) {
    //         return null;
    //     }

    //     // if object -> extract currency_id, otherwise assume it's the id already
    //     $currencyId = is_object($input) ? ($input->currency_id ?? null) : $input;

    //     if (!$currencyId) {
    //         return null;
    //     }

    //     $currency = Currency::find($currencyId);

    //     if (!$currency) {
    //         return null;
    //     }

    //     return [
    //         'id'     => $currency->id,
    //         'symbol' => $currency->symbol,
    //         'rate'   => $currency->rate,
    //     ];
    // }

    function currencyData($id)
    {
        $currency = Currency::find($id);
        if(!$currency) {
            $currency = Currency::first();
        }

        return  [
            'id' => $currency->id,
            'symbol' => $currency->symbol,
            'rate' => $currency->rate
        ];
    }


    if (!function_exists('module_exists')) {
        /**
         * Check if a given module exists either by folder or in config/modules.php
         *
         * @param  string  $moduleName
         * @return bool
         */
        function module_exists(string $moduleName): bool
        {
            $moduleName = ucfirst($moduleName);
            $modulePath = base_path("Modules/{$moduleName}");
            $configPath = config_path('modules.php');

            if (class_exists(Module::class) && Module::has($moduleName) && Module::isEnabled($moduleName) && File::isDirectory($modulePath) && File::exists($configPath)) {
                return true;
            }
            // Otherwise, module not found
            return false;
        }
    }
}
