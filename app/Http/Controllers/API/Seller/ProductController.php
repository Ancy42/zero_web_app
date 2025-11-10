<?php

namespace App\Http\Controllers\API\Seller;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Handlers\LfmConfigHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\BrandResource;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;
use App\Events\AdminProductRequestEvent;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use App\Repositories\NotificationRepository;
use App\Http\Resources\SellerProductResource;
use App\Http\Resources\SellerProductDetailsResource;
use App\Http\Requests\AdditionThumbnailDeleteRequest;

class ProductController extends Controller
{
    /**
     * Display the product list.
     */
    public function index(Request $request)
    {
        // get category, brand, color and search from request
        $category = $request->category;
        $brand = $request->brand;
        $color = $request->color;
        $search = $request->search;

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 20;
        $skip = ($page * $perPage) - $perPage;

        $shop = generaleSetting('shop');

        // filter products based on category, brand, color and search
        $products = $shop?->products()->when($brand, function ($query) use ($brand) {
            return $query->where('brand_id', $brand);
        })->when($category, function ($query) use ($category) {
            return $query->whereHas('categories', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        })->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->latest('id');

        $total = $products->count();
        $products = $products->skip($skip)->take($perPage)->get();

        return $this->json('Product list', [
            'total' => $total,
            'products' => SellerProductResource::collection($products),
        ]);
    }

    /**
     * Display the product details.
     */
    public function show(Product $product)
    {
        return $this->json('Product details', [
            'product' => SellerProductDetailsResource::make($product),
        ]);
    }

    public function createData()
    {
        $shop = generaleSetting('rootShop');

        $brands = $shop?->brands()->isActive()->get();
        $categories = CategoryRepository::query()->whereNull('parent_id')->get();

        return $this->json('create product data', [
            'categories' => CategoryResource::collection($categories),
            'brands' => BrandResource::collection($brands),
        ]);
    }

    /**
     * store new product.
     */
    public function store(ProductRequest $request)
    {
        /**
         * @var \App\Models\user $user
         */
        $user = auth()->user();

        $shop = generaleSetting('shop');

        $skuCode = $shop?->products()->where('code', $request->code)->exists();

        if ($skuCode) {
            return $this->json('Product code already exists', [
                'errors' => (object) [
                    'code' => ['The product code already exists'],
                ],
            ], 422);
        }

        $product = ProductRepository::storeByRequest($request);

        $isRootUser = $user?->hasRole('root');

        // admin notification message
        if (! $isRootUser) {
            $message = 'New product Created Request';
            try {
                AdminProductRequestEvent::dispatch($message);
            } catch (\Throwable $th) {
            }

            $data = (object) [
                'title' => $message,
                'content' => 'New product Created Request from ' . $shop?->name,
                'url' => '/admin/products?status=0',
                'icon' => 'bi-shop',
                'type' => 'success',
            ];
            // store notification
            NotificationRepository::storeByRequest($data);
        }

        return $this->json('Product created successfully', [
            'product' => SellerProductResource::make($product),
        ]);
    }

    /**
     * Update the product.
     */
    public function update(ProductRequest $request, Product $product)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();
        $shop = generaleSetting('shop');

        $skuCode = $shop?->products()->where('code', $request->code)->where('id', '!=', $product->id)->exists();

        if ($skuCode) {
            return $this->json('Product code already exists', [
                'errors' => (object) [
                    'code' => ['The product code already exists'],
                ],
            ], 422);
        }

        $product = ProductRepository::updateByRequest($request, $product);

        $isRootUser = $user?->hasRole('root');

        // admin notification message
        if (! $isRootUser) {
            $message = 'Product Updated Request';
            try {
                AdminProductRequestEvent::dispatch($message);
            } catch (\Throwable $th) {
            }

            $data = (object) [
                'title' => $message,
                'content' => 'Product Updated Request from ' . $shop->name,
                'url' => '/admin/products?status=1',
                'icon' => 'bi-shop',
                'type' => 'success',
            ];
            // store notification
            NotificationRepository::storeByRequest($data);
        }

        return $this->json('Product updated successfully', [
            'product' => SellerProductResource::make($product),
        ]);
    }

    /**
     * delete thumbnail
     */
    public function thumbnailDestroy(AdditionThumbnailDeleteRequest $request)
    {
        $product = Product::find($request->product_id);

        $media = Media::find($request->thumbnail_id);

        $product->medias()->detach($media->id);

        if (Storage::exists($media->src)) {
            Storage::delete($media->src);
        }

        $media->delete();

        return $this->json('Thumbnail deleted successfully!', [
            'product' => SellerProductDetailsResource::make($product),
        ]);
    }

    /**
     * status toggle a product
     */
    public function statusToggle(Product $product)
    {
        if (! $product->is_approve) {
            return $this->json(__('Sorry! Your Product is not approved yet!'), [], 422);
        }

        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        $product->refresh();

        return $this->json(__('Status updated successfully'), [
            'product' => SellerProductResource::make($product),
        ]);
    }


    public function laravelFilemanagerImage(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $configHandler = new LfmConfigHandler();
        $shopId = $configHandler->userField();


        if (! $shopId) {
            return response()->json(['message' => 'No shop ID found for current session'], 404);
        }

        if (! Storage::disk('public')->exists("files/{$shopId}")) {
            return response()->json([
                'shop_id' => $shopId,
                'images' => [],
                'message' => 'No files found for this shop',
            ]);
        }

        $files = Storage::disk('public')->allFiles("files/{$shopId}");

        $images = collect($files)
            ->filter(fn($file) => Str::endsWith($file, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
            ->map(fn($file) => [
                'name' => basename($file),
                'store_path' => $file,
                'url'  => asset("storage/{$file}"),
                'size_kb' => round(Storage::disk('public')->size($file) / 1024, 2),
                'last_modified' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
            ])
            ->values();

        return $this->json(__('Image Fetch successfully'), [
            'count'   => $images->count(),
            'images'  => $images,
        ]);
    }

    public function laravelFilemanagerUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048|mimes:png,jpg,jpeg,gif,svg',
        ]);

        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $configHandler = new LfmConfigHandler();
        $shopId = $configHandler->userField();

        if (! $shopId) {
            return response()->json(['message' => 'No shop ID found for current session'], 404);
        }

        $file = $request->file('file');

        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            "files/{$shopId}",
            $filename,
            'public'
        );

        $url = asset("storage/{$path}");

        return $this->json(__('Image Upload successfully'), [
            'name'      => $filename,
            'path'      => $path,
            'url'       => $url,
            'size_kb'   => round(Storage::disk('public')->size($path) / 1024, 2),
            'extension' => $file->getClientOriginalExtension(),
        ]);
    }
}
