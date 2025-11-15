<?php

namespace Modules\Purchase\App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PosCartProduct;
use App\Http\Controllers\Controller;
use Modules\Purchase\App\Models\ProductSku;

class OrderController extends Controller
{
    public function fetchOrderProducts(Request $request)
    {
        $productSku = ProductSku::where('sku', $request->sku)
            ->where('in_stock', true)
            ->first();
        $orderId = (int)$request->order_id;


        if (! $productSku) {
            return $this->json('Sorry! Barcode not found', [], Response::HTTP_BAD_REQUEST);
        }

        $product = $productSku->product;
        $order = Order::withoutGlobalScopes()->find($orderId);

        if (!$order) {
            return $this->json('Order not found', [], Response::HTTP_BAD_REQUEST);
        }
        if ($order->order_status->value == OrderStatus::CANCELLED->value) {
            return $this->json('Order Cancelled', [], Response::HTTP_BAD_REQUEST);
        }

        $orderProductIds = $order->products?->pluck('id')->toArray();

        if (! in_array($product->id, $orderProductIds)) {
            return $this->json('Sorry! Product not found in this order', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->json('Product found', [
            'product' => [
                'id' => $product->id,
                'thumbnail' => $product->thumbnail,
                'name' => $product->name,
                'product_sku_id' => $productSku->id,
                'barcode' => $productSku->sku,
            ],
        ]);
    }

    public function attachBarcode(Request $request)
    {
        if (empty($request->scanned_barcodes)) {
            return back()->with('error', __('Please scan or add barcodes first'));
        }
        $orderId = (int)$request->order_id;
        $order = Order::withoutGlobalScopes()->find($orderId);


        if ($order->order_status->value == OrderStatus::CANCELLED->value) {
            return back()->with('error', __('Order Cancelled'));
        }
        if ($order->order_status->value != OrderStatus::DELIVERED->value) {
            return back()->with('error', __('Order Not Delivered Yet'));
        }

        $scannedBarCodes = $request->scanned_barcodes;

        foreach ($scannedBarCodes as $key => $barcode) {
            if (!$barcode) {
                continue;
            }

            $productSku = ProductSku::where('sku', $barcode)->first();

            if ($productSku) {
                $orderProduct = OrderProduct::where('order_id', $order->id)
                    ->where('product_id', $productSku->product_id)
                    ->first();

                if (!$orderProduct) {
                    continue;
                }

                $existingSkus = $orderProduct->sku ? explode(',', $orderProduct->sku) : [];

                if (in_array($barcode, $existingSkus)) {
                    continue;
                }

                if (count($existingSkus) >= $orderProduct->quantity) {
                    continue;
                }

                $newSkuList = array_merge($existingSkus, [$barcode]);

                $order->products()->updateExistingPivot($productSku->product_id, [
                    'sku' => implode(',', $newSkuList),
                ]);

                $productSku->update(['in_stock' => false]);
            }
        }

        return back()->with('success', __('BarCodes attached successfully'));
    }

    public function addOrUpdateSKU(Request $request)
    {
        $product = PosCartProduct::where('id', $request->cart_id)->where('product_id', $request->item_id)->first();
        $productSku = ProductSku::where('sku', $request->sku)->where('product_id', $request->item_id)
            ->where('in_stock', true)
            ->first();
        $skuNo = json_decode($product->sku_no, true) ?? [];

        if (! $productSku) {
            if (isset($skuNo[$request->sl])) {
                unset($skuNo[$request->sl]);
                $product->update(['sku_no' => json_encode($skuNo)]);
            }
            return $this->json('Sorry! Barcode not found', [], Response::HTTP_BAD_REQUEST);
        }

        $data = [$request->sl => $request->sku];


        if ($product->sku_no) {
            $skuNo = json_decode($product->sku_no, true);
            $skuNo[$request->sl] = $request->sku;
            $data = $skuNo;
        }
        $product->update([
            'sku_no' => json_encode($data),
        ]);

        return $this->json(__('SKU added/updated successfully'), [], 200);
    }
}
