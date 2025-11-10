<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Number;

class CartRepository extends Repository
{
    public static function model()
    {
        return Cart::class;
    }

    public static function ShopWiseCartProducts($groupCart)
    {
        $totalItems = 0;
        $shopWiseProducts = collect([]);
        $info = null;

        foreach ($groupCart as $key => $products) {
            $productArray = collect([]);

            foreach ($products as $cart) {

                $product = $cart->product;

                if (! $product) {
                    $cart->delete();
                    $info = 'Some products are removed from cart due to unavailability';
                    continue;
                }

                $totalItems++;

                $discountPercentage = $product->getDiscountPercentage($product->price, $product->discount_price);

                $totalSold = $product->orders->sum('pivot.quantity');

                $flashSale = $product->flashSales?->first();
                $flashSaleProduct = null;
                $quantity = null;

                if ($flashSale) {
                    $flashSaleProduct = $flashSale?->products()->where('id', $product->id)->first();

                    $quantity = $flashSaleProduct?->pivot->quantity - $flashSaleProduct->pivot->sale_quantity;

                    if ($quantity == 0) {
                        $quantity = null;
                        $flashSaleProduct = null;
                    } else {
                        $discountPercentage = $flashSale?->pivot->discount;
                    }
                }


                $discountPrice = $product->discount_price > 0 ? $product->discount_price : 0;
                if ($flashSaleProduct) {
                    $discountPrice = $flashSaleProduct->pivot->price;
                }

                $mainPrice = $product->price;

                // calculate vat taxes
                $priceTaxAmount = 0;
                $discountTaxAmount = 0;
                foreach ($product->vatTaxes ?? [] as $tax) {
                    if ($tax->percentage > 0) {
                        $priceTaxAmount += $mainPrice * ($tax->percentage / 100);
                        $discountPrice > 0 ? $discountTaxAmount += $discountPrice * ($tax->percentage / 100) : null;
                    }
                }

                $mainPrice += $priceTaxAmount;
                $discountPrice > 0 ? $discountPrice += $discountTaxAmount : null;

                if ($discountPrice > 0) {
                    $discountPercentage = ($mainPrice - $discountPrice) / $mainPrice * 100;
                }

                $mainCurrencyPrice= $mainPrice * request()->currencyData['rate'];
                $discountCurrencyPrice= $discountPrice * request()->currencyData['rate'];
                $discountPercentageCurrency= $discountPercentage * request()->currencyData['rate'];

                $productArray[] = (object) [
                    'id' => $product->id,
                    'quantity' => (int) $cart->quantity,
                    'name' => $product->name,
                    'thumbnail' => $product->thumbnail,
                    'brand' => $product->brand?->name ?? null,
                    'price' => (float) number_format($mainCurrencyPrice, 2, '.', ''),
                    'discount_price' => (float) number_format($discountCurrencyPrice, 2, '.', ''),
                    'discount_percentage' => (float) number_format($discountPercentageCurrency, 2, '.', ''),
                    'rating' => (float) $product->averageRating,
                    'total_reviews' => (string) Number::abbreviate($product->reviews->count(), maxPrecision: 2),
                    'total_sold' => (string) number_format($totalSold, 0, '.', ','),
                    'unit' => $product->unit,
                ];
            }

            if ($productArray->isEmpty()) {
                continue;
            }

            $shop = $products[0]?->shop;

            $lastOnline = $shop->last_online >= now() ? true : false;

            $shopWiseProducts[] = (object) [
                'shop_id' => $key,
                'shop_name' => $shop->name,
                'shop_logo' => $shop->logo,
                'shop_rating' => (float) $shop->averageRating,
                'shop_online' => $lastOnline,
                'products' => $productArray,
            ];
        }

        return [
            'total_items' => $totalItems,
            'shop_wise_products' => $shopWiseProducts,
            'info' => $info,
        ];
    }

    /**
     * Store or update cart by request.
     */
    public static function storeOrUpdateByRequest(CartRequest $request, Product $product): Cart
    {
        $unit = $request->unit ?? $product->unit;

        $isBuyNow = $request->is_buy_now ?? false;

        $customer = auth()->user()->customer;

        $cart = $customer->carts()?->where('product_id', $product->id)->where('is_buy_now', $isBuyNow)->first();

        if ($cart) {
            $cart->update([
                'quantity' => $isBuyNow ? 1 : $cart->quantity + 1,
                'unit' => $request->unit ?? $product->unit,
            ]);

            return $cart;
        }

        return self::create([
            'product_id' => $request->product_id,
            'shop_id' => $product->shop->id,
            'is_buy_now' => $isBuyNow,
            'customer_id' => $customer->id,
            'quantity' => $request->quantity ?? 1,
            'unit' => $unit,
        ]);
    }

    public static function checkoutByRequest($request, $carts)
    {
        $totalAmount = 0;
        $deliveryCharge = 0;
        $couponDiscount = 0;
        $payableAmount = 0;

        $shopWiseTotalAmount = [];
        $totalOrderTaxAmount = 0;
        $vatTaxesArray = [];

        foreach ($carts ?? [] as $cart) {

            if (! $cart) {
                continue;
            }

            $product = $cart->product;
            $flashSale = $product->flashSales?->first();
            $flashSaleProduct = null;
            $quantity = null;

            $price = $product->discount_price > 0 ? $product->discount_price : $product->price;

            if ($flashSale) {
                $flashSaleProduct = $flashSale?->products()->where('id', $product->id)->first();

                $quantity = $flashSaleProduct?->pivot->quantity - $flashSaleProduct->pivot->sale_quantity;

                if ($quantity == 0) {
                    $quantity = null;
                    $flashSaleProduct = null;
                } else {
                    $price = $flashSaleProduct->pivot->price;
                }
            }

            // get shop wise total amount
            $shop = $product->shop;
            if (array_key_exists($shop->id, $shopWiseTotalAmount)) {
                $currentAmount = $shopWiseTotalAmount[$shop->id];
                $shopWiseTotalAmount[$shop->id] = $currentAmount + ($price * $cart->quantity);
            } else {
                $shopWiseTotalAmount[$shop->id] = $price * $cart->quantity;
            }

            $totalAmount += $price * $cart->quantity;
        }

        $groupCarts = $carts->groupBy('shop_id');

        // get delivery charge
        $deliveryCharge = 0;
        foreach ($groupCarts as $shopId => $shopCarts) {

            $productQty = 0;

            foreach ($shopCarts as $cart) {
                $productQty += $cart->quantity;
            }

            if ($productQty > 0) {
                $deliveryCharge += getDeliveryCharge($productQty);
            }
        }

        // generate array for get discount
        $products = collect([]);
        foreach ($carts as $cart) {
            $products->push([
                'id' => $cart->product_id,
                'quantity' => (int) $cart->quantity,
                'shop_id' => $cart->shop_id,
            ]);
        }
        $array = (object) [
            'coupon_code' => $request->coupon_code,
            'products' => $products,
        ];

        // get coupon discount
        $getDiscount = CouponRepository::getCouponDiscount($array);

        $couponDiscount = $getDiscount['discount_amount'];

        $payableAmount = $totalAmount + $deliveryCharge - $couponDiscount;

        // get order base tax
        $vatTaxes = VatTaxRepository::getActiveVatTaxes();

        foreach ($shopWiseTotalAmount as $shopId => $subtotal) {

            $thisFinalTax = [];

            foreach ($vatTaxes as $vatTax) {
                if ($vatTax->name && $vatTax->percentage > 0) {

                    $totalTaxAmount = round($subtotal * ($vatTax->percentage / 100), 2);

                    if (array_key_exists($vatTax->id, $thisFinalTax)) {
                        $currentAmount = $thisFinalTax[$vatTax->id];
                        $thisFinalTax[$vatTax->id] = $currentAmount + $totalTaxAmount;
                    } else {
                        $thisFinalTax[$vatTax->id] = $totalTaxAmount;
                    }
                    $totalOrderTaxAmount += $totalTaxAmount;
                }
            }

            $vatTaxesArray = $vatTaxes->map(function ($vatTax) use ($thisFinalTax) {
                return [
                    'id' => $vatTax->id,
                    'name' => $vatTax->name,
                    'percentage' => $vatTax->percentage,
                    'amount' => $thisFinalTax[$vatTax->id] ?? 0,
                ];
            })->toArray();
        }

        $payableAmount += $totalOrderTaxAmount;

        $totalCurrencyAmount=$totalAmount * $request->currencyData['rate'];
        $totalCurrencyDeliveryCharge=$deliveryCharge * $request->currencyData['rate'];
        $totalCurrencyCouponDiscount=$couponDiscount * $request->currencyData['rate'];
        $totalCurrencyOrderTaxAmount=$totalOrderTaxAmount * $request->currencyData['rate'];
        $totalCurrencyPayableAmount=$payableAmount * $request->currencyData['rate'];

        return [
            'total_amount' => (float) round($totalCurrencyAmount, 2),
            'delivery_charge' => (float) round($totalCurrencyDeliveryCharge, 2),
            'coupon_discount' => (float) round($totalCurrencyCouponDiscount, 2),
            'order_tax_amount' => (float) round($totalCurrencyOrderTaxAmount, 2),
            'payable_amount' => (float) round($totalCurrencyPayableAmount, 2),
            'all_vat_taxes' => $vatTaxesArray,
        ];
    }
}
