<?php

namespace Modules\Purchase\App\Repositories;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Repositories\MediaRepository;
use Abedin\Maker\Repositories\Repository;
use Modules\Purchase\App\Models\Purchase;
use Modules\Purchase\App\Models\ProductSku;
use Modules\Purchase\App\Models\PurchaseProduct;

class PurchaseRepository extends Repository
{
    public static function model()
    {
        return Purchase::class;
    }

    public static function storeByRequest($request): Purchase
    {
        $shop = generaleSetting('shop');
        $thumbnail = null;
        if ($request->hasFile('slip_image')) {
            $thumbnail = MediaRepository::storeByRequest($request->slip_image, 'purchaseSlip');
        }

        $date = Carbon::parse($request->receive_date)->format('Y-m-d');

        $lastPurchaseId = self::query()->max('id');
        $purchase = self::create([
            'purchase_code' => str_pad($lastPurchaseId + 1, 6, '0', STR_PAD_LEFT),
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'receive_date' => $date,
            'is_received' => false,
            'note' => $request->note,
            'shop_id' => $shop?->id,
        ]);


        return $purchase;
    }

    /**
     * Update user by request.
     *
     * @param  $request  The user request
     * @param  mixed  $user  The user
     */
    public static function updateByRequest($request, Purchase $purchase): Purchase
    {
        $thumbnail = self::updatePurchaseSlip($request, $purchase);

        $isReceived = $purchase->is_received;
        $date = Carbon::parse($request->receive_date)->format('Y-m-d');
        $purchase->update([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'receive_date' => $date ?? $purchase->receive_date ,
            'is_received' => $isReceived,
            'note' => $request->note,
        ]);

        return $purchase;
    }

    /**
     * Update the user's profile photo.
     */
    private static function updatePurchaseSlip($request, $purchase)
    {
        $thumbnail = $purchase->media;
        if ($request->hasFile('slip_image') && $thumbnail == null) {
            $thumbnail = MediaRepository::storeByRequest($request->slip_image, 'purchaseSlip');
        }

        if ($request->hasFile('slip_image') && $thumbnail) {
            $thumbnail = MediaRepository::updateByRequest($request->slip_image, 'purchaseSlip', null, $thumbnail);
        }

        return $thumbnail;
    }

    public static function attachBarcode(Product $product, Purchase $purchase, $purchaseProduct, $request)
    {
        return DB::transaction(function () use ($product, $purchase, $purchaseProduct, $request) {

            if (!$purchaseProduct) {
                $purchaseProduct = PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'price' => 0
                ]);
                $purchase->increment('total_product', 1);
            }

            $totalCount = 0;
            $finalSku = [];
            $totalAmount = 0;

            // With product barcodes
            if ($request->has('product_barcodes')) {
                $productBarcodes = $request->product_barcodes;
                $productCount = 0;
                $price = $request->buying_price ?? 0;

                foreach ($productBarcodes as $sku) {
                    $sku = trim(preg_replace('/\s+/', '', $sku));
                    if (in_array($sku, $finalSku)) {
                        continue;
                    }

                    $finalSku[] = $sku;

                    $productSku = ProductSku::updateOrCreate([
                        'sku' => $sku,
                    ], [
                        'product_id' => $product->id,
                        'in_stock' => true,
                        'price' => $price,
                        'purchase_product_id' => $purchaseProduct->id,
                    ]);

                    $purchaseProduct->increment('price', $price);
                    $productCount++;
                    $totalCount++;
                }

                $totalAmount = $totalCount * $price;

                $purchaseProduct->increment('quantity', $productCount);
            }

            // Without product barcodes
            if ($request->has('is_sku') && $request->is_sku == 'false') {
                $quantity = $request->quantity ?? 0;
                $price = $request->buying_price ?? 0;
                $totalAmount = $quantity * $price;

                $purchaseProduct->increment('quantity', $quantity);
                $purchaseProduct->increment('price', $totalAmount);

                $totalCount = $quantity;
            }

            $purchase->increment('total_amount', $totalAmount);
            $product->increment('quantity', $totalCount);

            $transactionData = [
                'transaction_date' => $purchase->receive_date,
                'note' => $request->note,
                'title' => 'Purchase Invoice',
            ];

            SupplierTransactionRepository::storeByRequest($purchase->supplier, 'credit', $totalAmount, $transactionData, $request);

            return $totalCount;
        });
    }


    public static function deleteProductBarcode($purchase, $request)
    {
        $purchaseProduct = PurchaseProduct::where('purchase_id', $purchase->id)
            ->where('product_id', $request->product_id)
            ->first();

        $product = Product::find($request->product_id);
        $totalCount = 0;
        $finalSku = [];

        if ($request->has('barcodes')) {
            $productBarcodes = $request->barcodes;
            $productCount = 0;
            foreach ($productBarcodes as $sku) {
                $sku = trim(preg_replace('/\s+/', '', $sku));
                $productSku = ProductSku::where('product_id', $product->id)
                    ->where('sku', $sku)
                    ->first();
                if ($productSku) {
                    $productCount++;
                    $totalCount++;
                    $purchase->decrement('total_amount', $productSku->price);
                    $purchaseProduct->decrement('price', $productSku->price);
                    $productSku->delete();
                }
            }

            $purchaseProduct->decrement('quantity', $productCount);
        }

        $product->decrement('quantity', $totalCount);

        return $totalCount;
    }
}
