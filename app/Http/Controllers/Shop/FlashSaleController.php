<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::latest('id')->paginate(20);

        return view('shop.flashsale.index', compact('flashSales'));
    }

    public function show(FlashSale $flashSale)
    {
        $shop = generaleSetting('shop');

        $dealProducts = $flashSale->products()->where('shop_id', $shop->id)->get();

        $products = $shop->products()->whereNotIn('id', $dealProducts->pluck('id'))->isActive()->get();

        return view('shop.flashsale.show', compact('flashSale', 'products', 'dealProducts'));
    }

    public function productStore(FlashSale $flashSale, Request $request)
    {
        $hasAnyErrors = [];

        foreach ($request->products as $productArr) {

            $product = Product::find($productArr['id']);

            if ($product) {

                $productPrice = $product->discount_price > 0 ? $product->discount_price : $product->price;

                $productQty = $productArr['quantity'] < $product->quantity ? $productArr['quantity'] : $product->quantity;

                $discountPercentage = ($productPrice - $productArr['discount_price']) / $productPrice * 100;
                if ($productPrice >= $productArr['discount_price']) {
                    $flashSale->products()->attach($productArr['id'], [
                        'price' => $productArr['discount_price'],
                        'quantity' => $productQty,
                        'discount' => $discountPercentage,
                    ]);
                } else {
                    $hasAnyErrors[] = $product;
                }
            }
        }

        return back()->withSuccess(__('Product added successfully'))->with('hasAnyErrors', $hasAnyErrors);
    }

    public function productRemove(FlashSale $flashSale, Product $product)
    {
        $flashSale->products()->detach($product->id);

        return back()->withSuccess(__('Product removed successfully'));
    }

    public function update(FlashSale $flashSale, Product $product, Request $request)
    {
        $discountPercentage = $request->price / 100 * $product->price;

        $productPrice = $product->discount_price > 0 ? $product->discount_price : $product->price;

        if ($productPrice <= $request->price) {
            return back()->withError(__('Discount price cannot be greater or equal than product price!'));
        }

        if ($request->quantity > $product->quantity) {
            return back()->withError(__('Quantity cannot be greater than product quantity!'));
        }

        $flashSale->products()->updateExistingPivot($product->id, [
            'price' => $request->price,
            'quantity' => $request->quantity,
            'discount' => $discountPercentage,
        ]);

        return back()->withSuccess(__('Updated Successfully'));
    }
}
