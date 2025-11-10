<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SMSConfig;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\VerifyManage;
use Illuminate\Http\Request;
use App\Services\TwilioService;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\OrderDetailsResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders with status filter and pagination options.
     *
     * @param  Request  $request  The HTTP request
     * @return Some_Return_Value json Response
     *
     * @throws Some_Exception_Class If something goes wrong
     */
    public function index(Request $request)
    {
        $orderStatus = $request->order_status;

        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $customer = auth()->user()->customer;

        $orders = $customer->orders()->when($orderStatus, function ($query) use ($orderStatus) {
            return $query->where('order_status', $orderStatus);
        })->latest('id');

        $total = $orders->count();

        // paginate
        $orders = $orders->when($perPage && $page, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->get();

        // status wise orders
        $statusWiseOrders = collect(OrderStatus::cases())->mapWithKeys(function ($status) use ($customer) {
            return [$status->value => $customer->orders()->where('order_status', $status->value)->count()];
        });

        // Response
        return $this->json('orders', [
            'total' => $total,
            'status_wise_orders' => [
                'all' => $customer->orders()->count(),
                'pending' => $statusWiseOrders[OrderStatus::PENDING->value],
                'confirm' => $statusWiseOrders[OrderStatus::CONFIRM->value],
                'processing' => $statusWiseOrders[OrderStatus::PROCESSING->value],
                'pickup' => $statusWiseOrders[OrderStatus::PICKUP->value],
                'on_the_way' => $statusWiseOrders[OrderStatus::ON_THE_WAY->value],
                'delivered' => $statusWiseOrders[OrderStatus::DELIVERED->value],
                'cancelled' => $statusWiseOrders[OrderStatus::CANCELLED->value],
            ],
            'orders' => OrderResource::collection($orders),
        ]);
    }

    /**
     * Store a newly created order in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderRequest $request)
    {
        $isBuyNow = $request->is_buy_now ?? false;
        $user = auth()->user();

        $verifyManage = Cache::rememberForever('verify_manage', function () {
            return VerifyManage::first();
        });

        $accountVerified = false;
        if ($user->email_verified_at || $user->phone_verified_at) {
            $accountVerified = true;
        }

        if ($verifyManage?->order_place_account_verify && ! $accountVerified) {
            return $this->json('Please verify your account first. without verify account you can not place order', [], 422);
        }

        $carts = $user->customer?->carts()->whereIn('shop_id', $request->shop_ids)->where('is_buy_now', $isBuyNow)->get();

        if ($carts->isEmpty()) {
            return $this->json('Sorry shop cart is empty', [], 422);
        }

        $toUpper = strtoupper($request->payment_method);
        $paymentMethods = PaymentMethod::cases();

        $paymentMethod = $paymentMethods[array_search($toUpper, array_column(PaymentMethod::cases(), 'name'))];

        // Store the order
         [$payment, $order] = OrderRepository::storeByRequestFromCart($request, $paymentMethod, $carts);

        $paymentUrl = null;
        if ($paymentMethod->name != 'CASH') {
            $paymentUrl = route('order.payment', ['payment' => $payment, 'gateway' => $request->payment_method]);
        }

        // Send WhatsApp template message

        $template =[
            '1' => $order->order_code,
            '2' => $order->customer->user->name ?? 'Guest',
            '3' => $order->address->address_line ?? 'address not found',
            '4' => $this->formatProductList($order),
            '5' => "$order->total_amount"
        ];
        $to = $order->customer->user->phone_code . $order->customer->user->phone;

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
                $twilioService->sendWhatsAppMessage($to, $template, "HXa3be8bac06c35b2a9f8276dc3ff37d59");
            } catch (\Exception $e) {
            }
        }

        return $this->json('Order created successfully', [
            'order_payment_url' => $paymentUrl,
        ]);
    }

    private function formatProductList($order)
    {
        $products = $order->products()->get()->toArray();

        $lines = [];
        foreach ($products as $p) {
            $name = $p['name'] ?? 'Unnamed';
            $quantity = $p['pivot']['quantity'] ?? 0;
            $price = $p['pivot']['price'] ?? 0;

            $line = "- {$name} ({$quantity} X {$price}) = " . ($quantity * $price);
            $lines[] = preg_replace('/[\x{200B}-\x{200D}\x{2060}]/u', '', $line);
        }
        return implode("", $lines);
    }

    /**
     * Again order
     */
    public function reOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $user = auth()->user();

        $verifyManage = Cache::rememberForever('verify_manage', function () {
            return VerifyManage::first();
        });

        $accountVerified = false;
        if ($user->email_verified_at || $user->phone_verified_at) {
            $accountVerified = true;
        }

        if ($verifyManage?->order_place_account_verify && ! $accountVerified) {
            return $this->json('Please verify your account first. without verify account you can not place order', [], 422);
        }

        // Find the order
        $order = Order::find($request->order_id);
        $subscription = null;

        if (! $order->shop->user->hasRole('root')) {
            $generalSetting = generaleSetting('setting');

            if ($generalSetting?->business_based_on == 'subscription') {
                $subscription = $order->shop->currentSubscription;

                if (! $subscription) {
                    return $this->json('Sorry, the shop is not available now', [], 422);
                }
            }
        }

        if ($order->order_status->value == OrderStatus::DELIVERED->value) {

            // Check product quantity
            foreach ($order->products as $product) {
                if ($product->quantity < $product->pivot->quantity) {
                    return $this->json('Sorry, your product quantity out of stock', [], 422);
                }
            }

            // create payment
            $toUpper = strtoupper($request->payment_method ?? $order->payment_method);
            $paymentMethods = PaymentMethod::cases();
            $paymentMethod = $paymentMethods[array_search($toUpper, array_column(PaymentMethod::cases(), 'name'))];

            $payment = Payment::create([
                'amount' => $order->payable_amount,
                'payment_method' => $paymentMethod?->value,
            ]);

            // re-order
            $order = OrderRepository::reOrder($order, $payment);

            if ($subscription) {
                $subscription->update([
                    'remaining_sales' => $subscription->remaining_sales - 1,
                ]);
            }

            // attach payment to order
            $payment->orders()->attach($order->id);

            // payment url
            $paymentUrl = null;
            if ($paymentMethod->name != 'CASH') {
                $paymentUrl = route('order.payment', ['payment' => $payment, 'gateway' => $payment->payment_method]);
            }

            // return
            return $this->json('Re-order created successfully', [
                'order_payment_url' => $paymentUrl,
                'order' => OrderResource::make($order),
            ]);
        }

        return $this->json('Sorry, You can not  re-order because order is not delivered', [], 422);
    }

    /**
     * Show the order details.
     *
     * @param  Request  $request  The request object
     */
    public function show(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        // Find the order
        $order = Order::find($request->order_id);

        return $this->json('order details', [
            'order' => OrderDetailsResource::make($order),
        ]);
    }

    /**
     * Cancel the order.
     */
    public function cancel(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        // Find the order
        $order = Order::find($request->order_id);

        if ($order->order_status->value == OrderStatus::PENDING->value) {

            // update order status
            $order->update([
                'order_status' => OrderStatus::CANCELLED->value,
            ]);

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

            return $this->json('Order cancelled successfully', [
                'order' => OrderResource::make($order),
            ]);
        }

        return $this->json('Sorry, order cannot be cancelled because it is not pending', [], 422);
    }

    public function payment(Order $order, $paymentMethod = null)
    {
        if ($paymentMethod != 'cash' && $paymentMethod != null) {

            $payment = Payment::create([
                'amount' => $order->payable_amount,
                'payment_method' => $paymentMethod,
            ]);

            $payment->orders()->attach($order->id);

            $paymentUrl = route('order.payment', ['payment' => $payment, 'gateway' => $payment->payment_method]);

            return $this->json('Payment created', [
                'order_payment_url' => $paymentUrl,
            ]);

            // $payment = $order->payments()?->first();

            // if ($payment->payment_method != $paymentMethod) {

            //     $order->update([
            //         'payment_method' => $paymentMethod,
            //     ]);

            //     $orders = $payment->orders()->where('order_status', '!=', OrderStatus::CANCELLED->value)->where('payment_status', PaymentStatus::PENDING->value)->get();

            //     $payment->update([
            //         'payment_method' => $paymentMethod,
            //         'amount' => $orders->sum('payable_amount'),
            //     ]);

            //     $payment->orders()->sync($orders);

            //     $paymentUrl = route('order.payment', ['payment' => $payment, 'gateway' => $payment->payment_method]);

            //     return $this->json('Payment created', [
            //         'order_payment_url' => $paymentUrl,
            //         'order' => OrderResource::make($order),
            //     ]);
            // }

            // $payment = Payment::create([
            //     'amount' => $order->payable_amount,
            //     'payment_method' => $paymentMethod,
            // ]);
        }

        return $this->json('Sorry, You can not  re-payment because payment is CASH', [], 422);
    }
}
