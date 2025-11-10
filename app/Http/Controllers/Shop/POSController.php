<?php

namespace App\Http\Controllers\Shop;

use Mpdf\Mpdf;
use App\Enums\Roles;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\PosCart;
use App\Models\Category;
use App\Models\Customer;
use App\Rules\EmailRule;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PosCartProduct;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Endroid\QrCode\Writer\PngWriter;
use App\Http\Requests\PosCartRequest;
use App\Repositories\OrderRepository;
use App\Repositories\VatTaxRepository;
use App\Http\Resources\ProductResource;
use App\Repositories\PosCartRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomerRepository;
use App\Http\Resources\PosProductResource;
use Endroid\QrCode\QrCode as EndroidQrCode;
use App\Http\Requests\PosApplyCouponRequest;
use App\Http\Resources\PosCartProductResource;

class POSController extends Controller
{
    public function index()
    {
        $rootShop = generaleSetting('rootShop');

        $categories =Category::whereNull('parent_id')->get();
        $brands = $rootShop->brands()->isActive()->get();

        $generaleSetting = generaleSetting('setting');

        $currency = $generaleSetting?->currency ?: '$';
        $currencyPosition = $generaleSetting?->currency_position ?: 'prefix';

        $customers = Customer::whereHas('user', function ($query) {
            return $query->where('deleted_at', null);
        })->get();

        return view('shop.pos.index', compact('categories', 'brands', 'customers', 'currency', 'currencyPosition'));
    }

    public function sales()
    {
        $shop = generaleSetting('shop');

        $orders = OrderRepository::query()->withoutGlobalScopes()->where('shop_id', $shop->id)->where('pos_order', true)->latest()->paginate(20);

        return view('shop.pos.sales', compact('orders'));
    }

    public function draft()
    {
        $shop = generaleSetting('shop');

        // Delete all PosCart records where products count is zero
        PosCartRepository::query()->where('is_draft', true)->whereDoesntHave('products')?->delete();

        $posCarts = PosCartRepository::query()
            ->where('shop_id', $shop->id)
            ->whereCreatedBy(request()->user()->id)
            ->where('is_draft', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('shop.pos.draft', compact('posCarts'));
    }

    public function draftDelete(PosCart $posCart)
    {
        $posCart->products()->sync([]);
        $posCart->delete();

        return back()->withSuccess(__('Draft deleted successfully'));
    }

    public function invoice($orderId)
    {
        $order = Order::withoutGlobalScopes()->findOrFail($orderId);

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

        // show stream
        return $mPdf->Output('invoice-' . $order->prefix . $order->order_code . '.pdf', 'I');
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $shop = generaleSetting('shop');

        $postCart = PosCartRepository::query()->where('shop_id', $shop->id)->where('name', $request->name)->first();

        if (! $postCart) {
            return $this->json(__('Sorry shop cart is empty'), [], 422);
        }
        if ($postCart->products->count() == 0) {
            return $this->json(__('Please select a products.'), [], 422);
        }

        $order = PosCartRepository::storeOrder($postCart, $request);

        $message = __('Sale created successfully');
        $request->order_type == 'draft' ? $message = __('Sale draft created successfully') : '';

        $invoiceUrl = null;
        if (is_object($order)) {
            $invoiceUrl = route('shop.pos.invoice', $order->id);
        }

        return $this->json($message, [
            'invoice_url' => $invoiceUrl,
        ], 200);
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:200',
            'last_name' => 'nullable|string|max:200',
            'phone' => 'required|string|unique:users,phone|digits_between:6,25',
            'email' => ['nullable', 'email', 'max:200', new EmailRule],
        ]);

        $request['is_active'] = 1;

        $user = UserRepository::registerNewUser($request);

        $user->assignRole(Roles::CUSTOMER->value);

        $customer = CustomerRepository::storeByRequest($user);

        return $this->json(__('Created Successfully'), [
            'user' => (object) [
                'id' => $customer->id,
                'name' => Str::limit($user->fullName, 30, '...') . '-(' . $user->phone . ')',
            ],
        ], 200);
    }

    public function getProduct(Request $request)
    {
        $brand = $request->brand;
        $category = $request->category;
        $search = $request->search;

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 40;
        $skip = ($page * $perPage) - $perPage;

        $generaleSetting = generaleSetting('setting');

        $shop = generaleSetting('shop');

        $products = $shop->products()->when($brand, function ($query) use ($brand) {
            return $query->where('brand_id', $brand);
        })->when($category, function ($product) use ($category) {
            return $product->whereHas('categories', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        })->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->isActive();
        $total = $products->count();
        $products = $products->skip($skip)->take($perPage)->get();

        return $this->json('Products', [
            'total' => $total,
            'currency' => $generaleSetting?->currency ?? '$',
            'currency_position' => $generaleSetting?->currency_position ?? 'prefix',
            'products' => ProductResource::collection($products),
        ]);
    }

    public function addToCart(PosCartRequest $request)
    {
        $product = ProductRepository::find($request->product_id);

        if ($product->quantity < $request->quantity) {
            return $this->json(__('Sorry! product cart quantity is limited. No more stock'), [], 422);
        }

        PosCartRepository::storeByRequest($request, $product);

        return $this->json(__('Product added successfully'), [], 200);
    }

    public function getCart(Request $request)
    {
        $postCart = PosCartRepository::getLatestCart($request);

        $vatTaxes = VatTaxRepository::getActiveVatTaxes();
        $subtotal = $postCart?->subtotal ?? 0;

        $allVatTaxes = [];
        $totalTaxAmount = 0;

        foreach ($vatTaxes ?? [] as $vatTax) {
            if ($vatTax?->name && $vatTax?->percentage > 0 && $subtotal > 0) {
                $taxAmount = round($subtotal * ($vatTax->percentage / 100), 2);

                $allVatTaxes[] = (object) [
                    'name' => $vatTax->name,
                    'percentage' => $vatTax->percentage,
                    'amount' => $taxAmount,
                ];

                $totalTaxAmount += $taxAmount;
            }
        }
        $total = $postCart?->total ?? 0;


        return $this->json('pos cart', [
            'subtotal' => $postCart?->subtotal ?? 0,
            'discount' => $postCart?->discount ?? 0,
            'total_tax_amount' => $totalTaxAmount,
            'total' => (float) round($totalTaxAmount + $total, 2),
            'taxes' => $allVatTaxes,
            'user' => $postCart?->user ? UserResource::make($postCart->user) : null,
            'name' => $postCart?->name ?? null,
            'coupon_code' => $postCart?->coupon?->code ?? null,
            'products' => $postCart?->products ? PosCartProductResource::collection($postCart?->products) : [],
        ]);
    }

    public function updateCart(PosCartRequest $request)
    {
        $product = ProductRepository::find($request->product_id);

        if ($product->quantity < $request->quantity) {
            return $this->json(__('Sorry! product cart quantity is limited. No more stock'), [], 422);
        }

        $postCartProduct = PosCartProduct::find($request->pos_cart_id);
        if (! $postCartProduct) {
            return $this->json(__('Sorry this product is not in cart'), [], 422);
        }

        PosCartRepository::updateByRequest($request, $postCartProduct);
    }

    public function removeCart(Request $request)
    {
        $shop = generaleSetting('shop');

        $postCart = PosCartRepository::query()->where('shop_id', $shop->id)->where('name', $request->name)->first();

        PosCartRepository::destroyProduct($request, $postCart);

        return $this->json(__('Product deleted successfully'), [], 200);
    }

    public function applyCoupon(PosApplyCouponRequest $request)
    {
        $code = $request->coupon_code;
        $shop = generaleSetting('shop');

        $coupon = Coupon::where(function ($query) use ($shop) {
            $query->where('shop_id', $shop->id)->orWhereHas('shops', function ($q) use ($shop) {
                $q->where('id', $shop->id);
            });
        })->where('code', $code)->active()->isValid()->first();

        if (! $coupon) {
            return $this->json(__('Invalid coupon code'), [], 422);
        }

        $postCart = PosCartRepository::query()->where('shop_id', $shop->id)->where('name', $request->name)->first();

        if ($postCart) {
            $postCart = PosCartRepository::applyCoupon($request, $coupon, $postCart);

            if ($postCart->discount > 0) {
                return $this->json(__('Coupon applied'), [], 200);
            } else {
                return $this->json(__('Coupon not applied'), [], 422);
            }
        }

        return $this->json(__('Coupon not applied'), [], 422);
    }

    public function removeCoupon(Request $request)
    {
        $shop = generaleSetting('shop');

        $postCart = PosCartRepository::query()->where('shop_id', $shop->id)->where('name', $request->name)->first();

        if ($postCart) {
            $postCart = PosCartRepository::removeCoupon($postCart);

            return $this->json(__('Coupon removed'), [], 200);
        }

        return $this->json(__('Coupon not found'), [], 422);
    }


    public function getProductDetail(Request $request)
    {
        $product =ProductRepository::find($request->id);

        return $this->json('Products', [
            'productDetail' => new PosProductResource($product),
        ]);
    }
}
