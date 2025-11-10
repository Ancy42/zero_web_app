<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Roles;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Driver;
use App\Models\SMSConfig;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Models\GeneraleSetting;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Services\NotificationServices;
use App\Repositories\NotificationRepository;

class OrderController extends Controller
{
    /**
     * Display a order list with filter status.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $paymentStatus = $request->payment_status;
        $search = $request->search;

        $generaleSetting = GeneraleSetting::first();
        $shop = $request->shop_id ?? null;
        if ($generaleSetting?->shop_type == 'single') {
            $shop = User::role(Roles::ROOT->value)->first()?->shop->id;
        }
        $orders = OrderRepository::query()
            ->when($shop, fn($query) => $query->where('shop_id', $shop))
            ->when($paymentStatus, fn($query) => $query->where('payment_status', $paymentStatus))
            ->when($search, fn($query) => $query->filter(['search' => $search]))
            ->when($status, fn($query) => $query->where('order_status', $status))
            ->latest('id')
            ->paginate(20);

        $orderStatuses = OrderStatus::cases();
        $paymentStatus = PaymentStatus::cases();
        $shops = Shop::isActive()->get();
        return view('admin.order.index', compact('orders', 'orderStatuses', 'paymentStatus', 'shops'));
    }

    /**
     * Display the order details.
     */
    public function show(Order $order)
    {
        $orderStatus = OrderStatus::cases();

        $riders = Driver::whereHas('user', function ($query) {
            return $query->where('is_active', true);
        })->get();

        return view('admin.order.show', compact('order', 'orderStatus', 'riders'));
    }



    private function sendOrderStatus(TwilioService $twilio, Order $order): void
    {
        $data =[
            'name' => $order->customer->user->name ?? 'Guest',
            'order_id' => $order->order_code,
            'status' => $order->order_status,
            'date' => '' . $order->updated_at->format('d M Y, h:i A')
        ];

        $to = $order->customer->user->phone_code . $order->customer->user->phone;

        $twilio->sendWhatsAppMessage($to, $data, 'HXa8f2f8b2f30065d3e8fb219e8c49c482');
    }


    /**
     * Update the order status.
     */
    public function statusChange(Order $order, Request $request)
    {
        $request->validate(['status' => 'required']);

        $order->update(['order_status' => $request->status]);

        $title = 'Order status updated';
        $message = 'Your order status updated to ' . $request->status;
        $deviceKeys = $order->customer->user->devices->pluck('key')->toArray();

        // Fetch Twilio config
        $twilioConfig = SMSConfig::where('provider', 'twilio')->first();
        $data = $twilioConfig ? json_decode($twilioConfig->data, true) : null;

        if (
            $twilioConfig &&
            $twilioConfig->status == 1 &&
            !empty($data['twilio_sid']) &&
            !empty($data['twilio_token']) &&
            !empty($data['twilio_from'])
        ) {
            try {
                $twilioService = new TwilioService($data);
                $this->sendOrderStatus($twilioService, $order);
            } catch (\Exception $e) {
            }
        }


        if ($request->status == OrderStatus::CANCELLED->value) {
            foreach ($order->products as $product) {

                $qty = $product->pivot->quantity;

                $product->update(['quantity' => $product->quantity + $qty]);

                $flashSale = $product->flashSales?->first();
                $flashSaleProduct = null;

                if ($flashSale) {
                    $flashSaleProduct = $flashSale?->products()->where('id', $product->id)->first();

                    if ($flashSaleProduct && $product->pivot?->price) {
                        if ($flashSaleProduct->pivot->sale_quantity >= $qty && ($product->pivot?->price == $flashSaleProduct->pivot->price)) {
                            $flashSale->products()->updateExistingPivot($product->id, [
                                'sale_quantity' => $flashSaleProduct->pivot->sale_quantity - $qty,
                            ]);
                        }
                    }
                }
            }

            if (function_exists('module_exists') && module_exists('Purchase')) {
                $order->productStockOuts()->delete();
            }
        }

        try {
            NotificationServices::sendNotification($message, $deviceKeys, $title);
        } catch (\Throwable $th) {
        }

        $notify = (object) [
            'title' => $title,
            'content' => $message,
            'user_id' => $order->customer->user_id,
            'type' => 'order',
        ];

        NotificationRepository::storeByRequest($notify);

        return back()->with('success', __('Order status updated successfully.'));
    }

    /**
     * Update the payment status.
     */
    public function paymentStatusToggle(Order $order)
    {
        if ($order->payment_status->value == PaymentStatus::PAID->value) {
            return back()->with('error', __('When order is paid, payment status cannot be changed.'));
        }
        $order->update(['payment_status' => PaymentStatus::PAID->value]);

        $title = 'Payment status updated';
        $message = __('Your payment status updated to paid. order code: ') . $order->prefix . $order->order_code;
        $deviceKeys = $order->customer->user->devices->pluck('key')->toArray();

        try {
            NotificationServices::sendNotification($message, $deviceKeys, $title);
        } catch (\Throwable $th) {
        }

        $notify = (object) [
            'title' => $title,
            'content' => $message,
            'user_id' => $order->customer->user_id,
            'type' => 'order',
        ];

        NotificationRepository::storeByRequest($notify);

        return back()->with('success', __('Payment status updated successfully'));
    }
}
