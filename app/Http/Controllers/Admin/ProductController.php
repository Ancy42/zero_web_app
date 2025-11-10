<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Events\ProductApproveEvent;
use App\Http\Controllers\Controller;
use App\Repositories\ShopRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\NotificationRepository;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $shop = $request->shop;
        $approve = $request->approve;
        $category = $request->category;
        $brand = $request->brand;
        $search = $request->search;

        $products = Product::when($status == '1', function ($query) {
            return $query->where('is_approve', false)->where('is_new', false);
        })->when($status == '0', function ($query) {
            return $query->where('is_approve', false)->where('is_new', true);
        })->when($approve, function ($query) {
            return $query->where('is_approve', true)->where('is_active', true);
        })->when($shop, function ($query) use ($shop) {
            return $query->where('shop_id', $shop);
        })->when($brand, function ($query) use ($brand) {
            return $query->where('brand_id', $brand);
        })->when($category, function ($query) use ($category) {
            return $query->whereHas('categories', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        })->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->paginate(20);

        $shops = ShopRepository::query()->isActive()->get();

        $brands = Brand::where('is_active', '1')->get();
        $categories = Category::active()->get();

        return view('admin.product.index', compact('products', 'shops', 'brands', 'categories'));
    }

    public function show(Product $product)
    {

        return view('admin.product.show', compact('product'));
    }

    /**
     * Approve the product.
     */
    public function approve(Product $product)
    {
        // update product
        $product->update([
            'is_approve' => true,
            'is_new' => false,
            'is_active' => true,
        ]);

        // admin notification message
        $message = 'Product Approved';
        try {
            ProductApproveEvent::dispatch($message, $product->shop_id);
        } catch (\Throwable $th) {
        }

        $data = (object) [
            'title' => $message,
            'content' => 'Your product approved from admin',
            'url' => '/shop/product/' . $product->id . '/show',
            'icon' => 'bi-bag-check-fill',
            'type' => 'success',
            'shop_id' => $product->shop_id,
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return back()->withSuccess(__('Product approved successfully'));
    }

    public function destroy(Product $product)
    {
        $shopID = $product->shop_id;
        $product->reviews()->delete();
        $product->categories()->detach();

        $product->delete();

        // admin notification message
        $message = 'Product Deleted';
        try {
            ProductApproveEvent::dispatch($message, $shopID);
        } catch (\Throwable $th) {
        }

        $data = (object) [
            'title' => $message,
            'content' => 'Your product deleted from admin',
            'url' => null,
            'icon' => 'bi-x-octagon-fill',
            'type' => 'danger',
            'shop_id' => $shopID,
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return back()->withSuccess(__('Product deleted successfully'));
    }
}
