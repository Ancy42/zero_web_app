<?php

namespace App\Http\Controllers\Shop;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use App\Services\NotificationServices;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class OrderController extends Controller
{
    /**
     * Display the order list with filter status.
     */
    public function index(Request $request)
    {

        $status = $request->status;
        $paymentStatus = $request->payment_status;
        $search = $request->search;
        $shop = generaleSetting('shop');

        $orders = $shop?->orders()
            ->when($paymentStatus, fn($query) => $query->where('payment_status', $paymentStatus))
            ->when($search, fn($query) => $query->filter(['search' => $search]))
            ->when($status, fn($query) => $query->where('order_status', $status))
            ->latest('id')
            ->paginate(20);

        $orderStatuses = OrderStatus::cases();
        $paymentStatus = PaymentStatus::cases();

        return view('shop.order.index', compact('orders', 'orderStatuses', 'paymentStatus'));
    }

    /**
     * Display the order details.
     */
    public function show($orderId)
    {
        $order = OrderRepository::query()->withoutGlobalScopes()->whereId($orderId)->firstOrFail();

        $orderStatus = OrderStatus::cases();

        $riders = Driver::whereHas('user', function ($query) {
            return $query->where('is_active', true);
        })->get();

        return view('shop.order.show', compact('order', 'orderStatus', 'riders'));
    }

    /**
     * Update the order status.
     */
    public function statusChange(Order $order, Request $request)
    {
        // $request->validate(['status' => 'required']);


        $order->update(['order_status' => $request->status]);

        if ($request->status == OrderStatus::DELIVERED->value) {
            $this->updateWalletAndTransaction($order);
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
        }

        $title = 'Order status updated';
        $message = 'Your order status updated to ' . $request->status . ' order code: ' . $order->prefix . $order->order_code;
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

        return back()->with('success', __('Payment status updated successfully'));
    }

    public function downloadInvoice($id)
    {
        $order = Order::withoutGlobalScopes()->findOrFail($id);

        $orderCode = '#' . $order->prefix . $order->order_code;

        $qrCode = new EndroidQrCode($orderCode);
        $qrCode->setSize(100);

        $writer = new PngWriter;
        $qrCodeImage = $writer->write($qrCode)->getDataUri();

        // pdf config
        $defaultConfig = (new ConfigVariables)->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables)->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $fontData['kalpurush'] = [
            'R' => 'kalpurush.ttf',
        ];

        $paperSize = 'A4';

        $mPdf = new Mpdf([
            'mode' => 'UTF-8',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'tempDir' => storage_path('app/public/mpdf_tmp'),
            'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
            'fontdata' => $fontData,
            'format' => $paperSize,
        ]);

        $view = view('PDF.invoice', compact('order', 'qrCodeImage'))->render();
        $mPdf->WriteHTML($view);

        // Output the PDF as a download
        return $mPdf->Output('invoice-' . $order->prefix . $order->order_code . '.pdf', 'D');

        // Output the PDF as a stream
        // return $mPdf->Output('invoice-' . $order->prefix . $order->order_code . '.pdf', 'I');
    }

    public function paymentSlip($id)
    {
        $order = Order::findOrFail($id);

        // pdf config
        $defaultConfig = (new ConfigVariables)->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables)->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $fontData['kalpurush'] = [
            'R' => 'kalpurush.ttf',
        ];

        $paperSize = 'A4';

        $mPdf = new Mpdf([
            'mode' => 'UTF-8',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'tempDir' => storage_path('app/public/mpdf_tmp'),
            'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
            'fontdata' => $fontData,
            'format' => $paperSize,
        ]);

        $view = view('PDF.payment-slip', compact('order'))->render();
        $mPdf->WriteHTML($view);

        $pdfContent = $mPdf->Output('payment-slip-' . $order->prefix . $order->order_code . '.pdf', 'S');

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="payment-slip-' . $order->prefix . $order->order_code . '.pdf"',
        ]);
    }

    private function updateWalletAndTransaction($order)
    {

        $generaleSetting = generaleSetting('setting');

        $commission = 0;

        if ($generaleSetting?->commission_charge != 'monthly') {

            if ($generaleSetting?->commission_type != 'fixed') {
                $commission = $order->total_amount * $generaleSetting->commission / 100;
            } else {
                $commission = $generaleSetting->commission ?? 0;
            }
        }

        $order->update([
            'delivery_date' => now(),
            'delivered_at' => now(),
            'payment_status' => PaymentStatus::PAID->value,
            'admin_commission' => $commission,
        ]);

        $wallet = $order->shop->user->wallet;
        if (!$wallet) {
            $wallet = WalletRepository::storeByRequest($order->shop->user);
        }

        WalletRepository::updateByRequest($wallet, $order->payable_amount, 'credit');

        TransactionRepository::storeByRequest($wallet, $commission, 'debit', true, true, 'admin commission', 'order');
    }

}
