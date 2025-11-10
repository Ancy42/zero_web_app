<?php

namespace App\Http\Controllers\Shop;

use App\Models\User;
use App\Services\Chat;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductThumbnail;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;
use App\Events\AdminProductRequestEvent;
use App\Repositories\FlashSaleRepository;
use App\Repositories\NotificationRepository;

class ProductController extends Controller
{
    /**
     * Display the product list.
     */
    public function index(Request $request)
    {
        // get category, brand and search from request
        $category = $request->category;
        $brand = $request->brand;
        $search = $request->search;

        $rootShop = generaleSetting('rootShop');
        $shop = generaleSetting('shop');

        // filter products based on category, brand and search
        $products = $shop?->products()->when($brand, function ($query) use ($brand) {
            return $query->where('brand_id', $brand);
        })->when($category, function ($query) use ($category) {
            return $query->whereHas('categories', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        })->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->latest('id')->paginate(20)->withQueryString();

        // get brands and categories
        $brands = $rootShop?->brands()->get();
        $categories = Category::active()->get();

        $flashSale = FlashSaleRepository::getIncoming()->first();

        return view('shop.product.index', compact('products', 'brands', 'categories', 'flashSale'));
    }

    /**
     * Display the product details.
     */
    public function show(Product $product)
    {
        return view('shop.product.show', compact('product'));
    }

    /**
     * crete new product.
     */
    public function create()
    {
        $shop = generaleSetting('rootShop');

        // get brands, colors and categories
        $brands = $shop?->brands()->isActive()->get();
        $categories = Category::whereNull('parent_id')->get();

        $htmlTree = $this->formatCategoryTree($categories);
        $attributeHtmlTree = $this->formatCategoryAttributeTree($categories);

        return view('shop.product.create', compact('brands', 'categories', 'htmlTree','attributeHtmlTree'));
    }


    private function formatCategoryTree($categories, $selectedCategories = [])
    {
        $selectedCategories = old('categories', $selectedCategories);

        $html = '<ul class="category-tree">';
        foreach ($categories as $category) {
            $checked = in_array($category->id, $selectedCategories) ? 'checked' : '';
            $html .= '<li id="' . $category->id . '" class="category">';
            $html .= '<input type="checkbox" name="categories[]" value="' . $category->id . '" id="category_' . $category->id . '" class="form-check-input category-checkbox" ' . $checked . '>';
            $html .= '<label for="category_' . $category->id . '">' . $category->name . '</label>';
            $html .= $this->formatCategoryTree($category->subCategories, $selectedCategories);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }


    private function formatCategoryAttributeTree($categories, $selectedCategories = [], $selectedAttributes = [])
    {
        $selectedCategories = old('categories', $selectedCategories);

        $html = '<ul class="categoryAttribute-tree">';
        foreach ($categories as $category) {
            $checked = in_array($category->id, $selectedCategories) ? 'checked' : '';
            $html .= '<li id="' . $category->id . '" class="attributeCategory d-none attributeCategory-' . $category->id . '">';
            $html .= '<input type="checkbox" name="categories[]" disabled value="' . $category->id . '" id="categoryAttribute_' . $category->id . '" class="form-check-input attributeCategoryBox" ' . $checked . '>';
            $html .= '<label for="category_' . $category->id . '">' . $category->name . '</label>';
            if ($category->attributes && $category->attributes->count()) {
                $html .= $this->formatAttributeTree($category->attributes, $selectedAttributes, $category->id);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    private function formatAttributeTree($attributes, $selectedAttributes = [], $categoryId = null)
    {
        $selectedAttributes = old('category_attribute_ids', $selectedAttributes);

        $html = '<ul class="categoryAttribute-tree d-none categoryAttribute-tree-' . $categoryId . '" data-id="' . $categoryId . '">';
        foreach ($attributes as $attribute) {
            $checked = in_array($attribute->id, $selectedAttributes) ? 'checked' : '';
            $html .= '<li id="' . $attribute->id . '" class="attribute">';
            $html .= '<input type="checkbox" name="category_attribute_ids[]" disabled value="' . $attribute->id . '" id="attribute_' . $attribute->id . '" class="form-check-input attributeBox" ' . $checked . '>';
            $html .= '<label for="attribute_' . $attribute->id . '">' . $attribute->name . '</label>';
            if ($attribute->subAttributes && $attribute->subAttributes->count()) {
                $html .= $this->formatAttributeTree($attribute->subAttributes, $selectedAttributes, $categoryId);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * store new product.
     */
    public function store(ProductRequest $request)
    {
        $shop = generaleSetting('shop');

        $skuCode = $shop?->products()->where('code', $request->code)->exists();

        if ($skuCode) {
            return back()->withInput()->withErrors(['code' => __('Product code already exists!')])->with('error', __('Product code already exists!'));
        }

        ProductRepository::storeByRequest($request);

        /** @var User $user */
        $user = auth()->user();
        $isRootUser = $user?->hasRole('root');

        // admin notification message
        if (! $isRootUser && generaleSetting('setting')->shop_type != 'single') {
            $message = 'New product Created Request';
            try {
                AdminProductRequestEvent::dispatch($message);
            } catch (\Throwable $th) {
            }

            $data = (object) [
                'title' => $message,
                'content' => 'New product Created Request from ' . $shop->name,
                'url' => '/admin/products?status=0',
                'icon' => 'bi-shop',
                'type' => 'success',
            ];
            // store notification
            NotificationRepository::storeByRequest($data);
        }

        return to_route('shop.product.index')->withSuccess(__('Product created successfully!'));
    }

    /**
     * Display the product edit form.
     */
    public function edit(Product $product)
    {
        $shop = generaleSetting('shop');
        $rootShop = generaleSetting('rootShop');

        // get brands, and categories
        $brands = $rootShop?->brands()->isActive()->get();
        $categories = Category::whereNull('parent_id')->get();
        $htmlTree = $this->formatCategoryTree($categories, $product->categories?->pluck('id')->toArray());

        $attributeHtmlTree = $this->formatCategoryAttributeTree($categories, $product->categories?->pluck('id')->toArray(), $product->categoryAttributes?->pluck('id')->toArray());


        $metaKeywords = explode(',', $product->meta_keywords) ?: [];

        return view('shop.product.edit', compact('product', 'brands', 'categories', 'metaKeywords', 'htmlTree','attributeHtmlTree'));
    }

    /**
     * Update the product.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $shop = generaleSetting('shop');

        $skuCode = $shop?->products()->where('code', $request->code)->where('id', '!=', $product->id)->exists();

        if ($skuCode) {
            return back()->withInput()->withErrors(['code' => __('Product code already exists!')])->with('error', __('Product code already exists!'));
        }

        ProductRepository::updateByRequest($request, $product);

        /** @var User $user */
        $user = auth()->user();
        $isRootUser = $user?->hasRole('root');

        // admin notification message
        if (! $isRootUser && generaleSetting('setting')->shop_type != 'single') {
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

        return to_route('shop.product.index')->withSuccess(__('Product updated successfully!'));
    }

    /**
     * delete thumbnail
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->withSuccess(__('Product deleted successfully!'));
    }

    /**
     * delete thumbnail
     */
    public function thumbnailDestroy(ProductThumbnail $productThumbnail)
    {
        $productThumbnail->delete();
        return back()->withSuccess(__('Thumbnail deleted successfully!'));
    }

    /**
     * status toggle a product
     */
    public function statusToggle(Product $product)
    {
        if (! $product->is_approve) {
            return back()->withError(__('Sorry! Your Product is not approved yet!'));
        }

        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        return back()->withSuccess(__('Status updated successfully'));
    }

    /**
     * generate barcode
     */
    public function generateBarcode(Product $product)
    {
        if (! $product->code) {
            return back()->withError(__('Sorry! Your Product code is not generated yet!'));
        }

        $quantities = request('qty', 4);

        return view('shop.product.barcode', compact('product', 'quantities'));

    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->back()->with('success', 'Product restored successfully.');
    }
    public function onlyTrashedProduct()
    {
        $shop = generaleSetting('shop');
        $products = $shop?->products()->onlyTrashed()->latest('id')->paginate(20)->withQueryString();
        return view('shop.product.trarhed', compact('products'));
    }


    /**
     * generate ai description
     */
    public function generateAIData(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'short_description' => 'nullable|string',
            ]);

            $chat = new Chat();
            $chat->systemMessage($request->name);

            $question = str_replace(
                ['{product_name}', '{short_description}'],
                [$request->name, $request->short_description],
                generaleSetting()->product_description
            );

            $question .= "Format the description with proper HTML tags (<p>, <h2>, <ul>, <li>) for CKEditor. Do not include extra phrases like 'The product is','```html', 'Sure', or 'Here is'. Just output the final formatted description.";

            $response = $chat->send($question);

            return response()->json($response);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
