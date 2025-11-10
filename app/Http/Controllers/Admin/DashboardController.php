<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use App\Repositories\FlashSaleRepository;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $generaleSetting = generaleSetting('setting');

        $totalCustomer = Customer::count();

        $totalRider = User::role('driver')->count();

        $shop = null;

        if (! $generaleSetting || $generaleSetting?->shop_type != 'single') {
            $totalShop = Shop::count();
            $totalOrder = Order::count();
            $totalProduct = Product::count();
            $totalCategories = Category::count();
        } else {
            $shop = generaleSetting('shop');
            $totalShop = 0;
            $totalOrder = Order::where('shop_id', $shop?->id)->count();
            $totalProduct = Product::where('shop_id', $shop?->id)->count();
            $totalCategories = Category::whereHas('shops', function ($query) use ($shop) {
                $query->where('id', $shop?->id);
            })->count();
        }

        $orderStatuses = OrderStatus::cases();

        $topCustomers = Customer::withCount('orders')->orderBy('orders_count', 'desc')->limit(8)->get();

        $productObject = Product::when($shop, function ($query) use ($shop) {
            return $query->where('shop_id', $shop?->id);
        });

        $topSellingProducts = (clone $productObject)->withCount('orders')->orderBy('orders_count', 'desc')->limit(8)->get();

        $topReviewProducts = (clone $productObject)->withAvg('reviews as average_rating', 'rating')->orderBy('average_rating', 'desc')->limit(8)->get();

        $topShops = Shop::withCount('orders')->orderBy('orders_count', 'desc')->withAvg('reviews as average_rating', 'rating')->orderBy('average_rating', 'desc')->limit(8)->get();

        $latestOrders = Order::when($shop, function ($query) use ($shop) {
            return $query->where('shop_id', $shop?->id);
        })->latest('id')->limit(6)->get();

        $topFavorites = (clone $productObject)->whereHas('favorites')->withCount('favorites')->orderBy('favorites_count', 'desc')->limit(8)->get();

        $pendingWithdraw = Withdraw::where('status', 'pending')->sum('amount');
        $alreadyWithdraw = Withdraw::where('status', 'approved')->sum('amount');
        $deniedWithdraw = Withdraw::where('status', 'denied')->sum('amount');

        $totalCommission = Transaction::where('is_commission', true)->sum('amount');

        $flashSale = FlashSaleRepository::getIncoming()->first();

        return view('admin.dashboard', compact('totalShop', 'totalOrder', 'totalCustomer', 'totalProduct', 'orderStatuses', 'topCustomers', 'topSellingProducts', 'topReviewProducts', 'topShops', 'latestOrders', 'topFavorites', 'pendingWithdraw', 'alreadyWithdraw', 'deniedWithdraw', 'totalCommission', 'totalCategories', 'flashSale', 'totalRider'));
    }

    public function orderStatistics()
    {
        $type = request('type') ?? 'daily';
        $generaleSetting = generaleSetting('setting');

        $shop = $generaleSetting?->shop_type == 'single' ? generaleSetting('shop') : null;

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
