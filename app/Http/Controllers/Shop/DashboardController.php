<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Order;
use App\Repositories\FlashSaleRepository;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $shop = generaleSetting('shop');

        $totalOrder = $shop->orders()->count();
        $totalProduct = $shop->products()->count();
        $totalBrand = Brand::all()->count();

        $orderStatuses = OrderStatus::cases();

        $productObject = $shop->products();
        $orderObject = $shop->orders();

        $topSellingProducts = (clone $productObject)->whereHas('orders')->withCount('orders')->orderBy('orders_count', 'desc')->limit(8)->get();

        $topReviewProducts = (clone $productObject)->whereHas('reviews')->withAvg('reviews as average_rating', 'rating')->orderBy('average_rating', 'desc')->limit(8)->get();

        $latestOrders = (clone $orderObject)->latest('id')->limit(8)->get();

        $topFavorites = (clone $productObject)->whereHas('favorites')->withCount('favorites')->orderBy('favorites_count', 'desc')->limit(8)->get();

        $pendingWithdraw = $shop->withdraws()->where('status', 'pending')->sum('amount');
        $alreadyWithdraw = $shop->withdraws()->where('status', 'approved')->sum('amount');
        $deniedWithdraw = $shop->withdraws()->where('status', 'denied')->sum('amount');

        $totalWithdraw = $pendingWithdraw + $alreadyWithdraw;

        $totalPosSales = Order::withoutGlobalScopes()->where('shop_id', $shop->id)->where('pos_order', true)->where('order_status', OrderStatus::DELIVERED->value)->sum('payable_amount');

        $totalDeliveryCollected = (clone $orderObject)->where('order_status', OrderStatus::DELIVERED->value)->sum('delivery_charge');

        $flashSale = FlashSaleRepository::getIncoming()->first();

        return view('shop.dashboard', compact('totalOrder', 'totalProduct', 'orderStatuses', 'topSellingProducts', 'topReviewProducts', 'latestOrders', 'topFavorites', 'totalBrand', 'totalWithdraw', 'totalPosSales', 'totalDeliveryCollected', 'pendingWithdraw', 'alreadyWithdraw', 'deniedWithdraw', 'flashSale'));
    }

    public function orderStatistics()
    {
        $type = request('type') ?? 'daily';
        $generaleSetting = generaleSetting('setting');

        $shop = generaleSetting('shop');

        if ($type == 'daily') {

            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();

            $datesAndDays = [];

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $datesAndDays[] = [
                    'date' => $date->toDateString(),
                    'label' => $date->format('l'),
                    'value' => Order::when($shop, function ($query) use ($shop) {
                        return $query->where('shop_id', $shop?->id);
                    })->whereDate('created_at', $date->toDateString())->count(),
                ];
            }

            $labels = array_column($datesAndDays, 'label');
            $values = array_column($datesAndDays, 'value');

            return $this->json('daily order statistics', [
                'labels' => $labels,
                'values' => $values,
                'total' => array_sum($values),
            ]);
        } elseif ($type == 'monthly') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();

            $datesAndMonths = [];

            for ($date = $startDate; $date->lte($endDate); $date->addMonth()) {
                $datesAndMonths[] = [
                    'date' => $date->toDateString(),
                    'label' => $date->format('M'), // Get the month name
                    'value' => Order::when($shop, function ($query) use ($shop) {
                        return $query->where('shop_id', $shop?->id);
                    })->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                ];
            }

            $labels = array_column($datesAndMonths, 'label');
            $values = array_column($datesAndMonths, 'value');

            $total = array_sum($values);

            return $this->json('monthly order statistics', [
                'labels' => $labels,
                'values' => $values,
                'total' => $total,
            ]);
        } else {

            $datesAndYears = [];

            for ($date = now()->startOfYear(); $date->year >= now()->subYears(6)->year; $date->subYear()) {
                $datesAndYears[] = [
                    'date' => $date->toDateString(),
                    'label' => $date->format('Y'),
                    'value' => Order::when($shop, function ($query) use ($shop) {
                        return $query->where('shop_id', $shop?->id);
                    })->whereYear('created_at', $date->year)->count(),
                ];
            }

            $labels = array_column($datesAndYears, 'label');
            $values = array_column($datesAndYears, 'value');

            $total = array_sum($values);

            return $this->json('yearly order statistics', [
                'labels' => $labels,
                'values' => $values,
                'total' => $total,
            ]);
        }
    }
}
