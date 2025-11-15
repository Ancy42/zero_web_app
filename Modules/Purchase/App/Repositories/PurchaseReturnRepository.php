<?php

namespace Modules\Purchase\App\Repositories;

use Modules\Purchase\App\Models\Purchase;
use Modules\Purchase\App\Models\PurchaseReturn;
use Modules\Purchase\App\Models\PurchaseProduct;
use Modules\Purchase\App\Models\ProductSku;
use Modules\Purchase\App\Models\PurchaseReturnProduct;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\App\Models\ReturnProductSku;
use Abedin\Maker\Repositories\Repository;

class PurchaseReturnRepository extends Repository
{
    public static function model()
    {
        return PurchaseReturn::class;
    }

    public static function storeByRequest(Purchase $purchase, $request)
    {
        try {
            return DB::transaction(function () use ($purchase, $request) {
                $requestPurchaseProducts = $request->return_quantity;
                $requestSkus = $request->return_sku;
                if (
                    empty($requestPurchaseProducts) ||
                    collect($requestPurchaseProducts)->filter(fn($qty) => !is_null($qty) && $qty > 0)->isEmpty()
                ) {
                    return back()->with('error', __('Select Return Quantity.'));
                }

                $lastPurchaseId = PurchaseReturn::max('id') ?? 0;
                $purchaseReturn = PurchaseReturn::firstOrCreate(
                    [
                        'purchase_id' => $purchase->id,
                        'shop_id' => $purchase->shop_id,
                    ],
                    ['return_purchase_code' => str_pad($lastPurchaseId + 1, 6, '0', STR_PAD_LEFT)]
                );

                foreach ($requestPurchaseProducts as $purchaseProductId => $quantity) {
                    $quantity = (int) $quantity;

                    if ($quantity <= 0) {
                        continue;
                    }

                    $purchaseProduct = PurchaseProduct::find($purchaseProductId);

                    if (! $purchaseProduct || $quantity > $purchaseProduct->quantity) {
                        continue;
                    }

                    $product = $purchaseProduct->product;

                    // --- Simple Product (No SKU) ---
                    if ($purchaseProduct->productSkus->isEmpty()) {
                        $purchaseProductReturn = PurchaseReturnProduct::firstOrCreate(
                            ['purchase_return_id' => $purchaseReturn->id, 'product_id' => $product->id],
                            ['quantity' => 0, 'price' => 0]
                        );
                        $purchaseProductReturn->increment('quantity', $quantity);

                        $unitPrice = $purchaseProduct->quantity > 0
                            ? ($purchaseProduct->price / $purchaseProduct->quantity)
                            : 0;

                        $priceReduction = $unitPrice * $quantity;

                        $purchaseProduct->decrement('price', $priceReduction);
                        $purchaseProductReturn->increment('price', $priceReduction);
                        $purchase->decrement('total_amount', $priceReduction);
                        $purchaseProduct->decrement('quantity', $quantity);
                        $purchaseProduct->increment('return_quantity', $quantity);
                        $product->decrement('quantity', $quantity);
                        $purchaseReturn->increment('total_amount', $priceReduction);
                    } else {  // --- Product with SKU ---
                        foreach ($requestSkus ?? [] as $sku) {
                            $productSku = ProductSku::where('sku', $sku)->where('in_stock', true)->first();
                            if (!$productSku) {
                                continue;
                            }

                            $purchaseProductReturn = PurchaseReturnProduct::firstOrCreate(
                                ['purchase_return_id' => $purchaseReturn->id, 'product_id' => $product->id],
                                ['quantity' => 0, 'price' => 0]
                            );

                            $purchaseProductReturn->increment('quantity');

                            ReturnProductSku::create([
                                'purchase_return_product_id' => $purchaseProductReturn->id,
                                'sku' => $sku,
                                'price' => $productSku->price,
                            ]);

                            $priceReduction = $productSku->price;

                            $purchaseProduct->decrement('price', $priceReduction);
                            $purchase->decrement('total_amount', $priceReduction);
                            $purchaseProduct->decrement('quantity');
                            $purchaseProduct->increment('return_quantity');
                            $product->decrement('quantity');
                            $purchaseReturn->increment('total_amount', $priceReduction);
                            $purchaseProductReturn->increment('price', $priceReduction);
                            $productSku->delete();
                        }
                    }
                }

                // Final update on lot return
                $purchaseReturn->update([
                    'is_returned' => 1,
                    'total_product' => $purchaseReturn->purchaseReturnProducts->count()
                ]);

                return $purchaseReturn;
            });
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', __('An unexpected error occurred while processing the return. Please try again.'));
        }
    }
}
