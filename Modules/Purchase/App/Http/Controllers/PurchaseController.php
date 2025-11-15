<?php

namespace Modules\Purchase\App\Http\Controllers;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;
use App\Http\Controllers\Controller;
use Modules\Purchase\App\Models\Purchase;
use Modules\Purchase\App\Models\Supplier;
use Modules\Purchase\App\Models\PurchaseProduct;
use Modules\Purchase\App\Resources\ProductSearchResource;
use Modules\Purchase\App\Http\Requests\PurchaseRequest;
use Modules\Purchase\App\Repositories\PurchaseRepository;
use Modules\Purchase\App\Repositories\SupplierRepository;
use Modules\Purchase\App\Http\Requests\PurchaseAttachProductRequest;
use Modules\Purchase\App\Repositories\SupplierTransactionRepository;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shop = generaleSetting('shop');
        $user = auth('web')->user();
        $supplier = request('supplier');
        if ($user->supplier) {
            $supplier = $user->supplier->id;
        }
        $suppliers = Supplier::where('shop_id', $shop->id)->latest('id')->get();
        $purchases = PurchaseRepository::query()->where('shop_id', $shop->id)
            ->when($supplier, function ($query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            })->latest('id')->paginate(20);
        return view('purchase::purchase.index', compact('suppliers', 'purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shop = generaleSetting('shop');
        $suppliers = Supplier::where('shop_id', $shop->id)->latest('id')->get();
        return view('purchase::purchase.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {
        $purchase = PurchaseRepository::storeByRequest($request);
        return to_route('shop.purchase.show', $purchase->id)->withSuccess(__('Purchase Created successfully'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchaseProducts = $purchase->purchaseProducts()->with('product')->paginate(20);
        return view('purchase::purchase.show', compact('purchase', 'purchaseProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase) {
        $shop = generaleSetting('shop');
        $suppliers = Supplier::where('shop_id', $shop->id)->latest('id')->get();
        return view('purchase::purchase.edit', compact('suppliers', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseRequest $request, Purchase $purchase) {
        $purchase = PurchaseRepository::updateByRequest($request, $purchase);
        return to_route('shop.purchase.show', $purchase->id)->withSuccess(__('Purchase Updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return back()->withSuccess(__('Purchase deleted successfully'));
    }


    public function makeReceived(Purchase $purchase, Request $request)
    {
        if ($purchase->product <=0) {
            return back()->withError(__('Product not found'));
        }
        $purchase->update([
            'is_received' => true,
        ]);

        $totalAmount = $purchase->total_amount;
        $paidAmount = $purchase->paid_amount;

        $transactionData = [
            'transaction_date' => now()->format('Y-m-d'),
            'note' => $purchase->note,
            'title' => null,
        ];

        if ($totalAmount > 0) {
            $transactionData['title'] = 'Purchase Invoice';
            SupplierTransactionRepository::storeByRequest($purchase->supplier, 'credit', $totalAmount, $transactionData, $request);
        }

        if ($paidAmount > 0) {
            $transactionData['title'] = 'Supplier Payment from purchase';
            SupplierTransactionRepository::storeByRequest($purchase->supplier, 'debit', $paidAmount, $transactionData, $request);
        }

        return back()->withSuccess(__('Updated successfully'));
    }

    public function getProducts(Request $request)
    {
        $search = $request->search;
        $shop = generaleSetting('shop');
        $products = Product::where('shop_id', $shop->id)->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->isActive()->limit(10)->get();

        return $this->json('Products', [
            'products' => ProductSearchResource::collection($products),
        ]);
    }

    public function attachProduct(Purchase $purchase, PurchaseAttachProductRequest $request)
    {
        $product = Product::find($request->product_id);
        $purchaseProduct = PurchaseProduct::where('purchase_id', $purchase->id)->where('product_id', $product->id)->first();
        if ($request->has('is_sku') && $request->is_sku == 'false') {
            if ($request->quantity <= 0) {
                return back()->withError(__('Add quantity first'));
            }
        }

        PurchaseRepository::attachBarcode($product, $purchase, $purchaseProduct, $request);

        return back()->withSuccess(__('Updated Successfully'));
    }

    public function deleteProductBarcode(Purchase $purchase, Request $request)
    {

        PurchaseRepository::deleteProductBarcode($purchase, $request);

        return back()->withSuccess(__('Delete Successfully'));
    }


    public function purchaseStockSummary()
    {
        $shop = generaleSetting('shop');
        $purchaseQuery = PurchaseRepository::query()->where('shop_id', $shop->id)->whereHas('purchaseProducts');
        $allPurchases = $purchaseQuery->latest('id')->get();
        $products = Product::where('shop_id', $shop->id)->latest('id')->get();
        $suppliers = SupplierRepository::query()->where('shop_id', $shop->id)->latest('id')->get();

        $from = request('from') ?? now()->subDays(30)->format('m/d/Y');
        $to = request('to') ?? now()->format('m/d/Y');

        $selectedPurchase = request('purchase_id');
        $selectedProduct = request('product_id');
        $selectedSupplier = request('supplier_id');

        $purchases = $purchaseQuery->when($from, function ($query) use ($from) {
            $startDate = Carbon::parse($from)->format('Y-m-d');
            $query->where('receive_date', '>=', $startDate);
        })->when($to, function ($query) use ($to) {
            $endDate = Carbon::parse($to)->format('Y-m-d');
            $query->where('receive_date', '<=', $endDate);
        })->when($selectedPurchase, function ($query) use ($selectedPurchase) {
            $query->where('id', $selectedPurchase);
        })->when($selectedSupplier, function ($query) use ($selectedSupplier) {
            $query->where('supplier_id', $selectedSupplier);
        })->when($selectedProduct, function ($query) use ($selectedProduct) {
            $query->whereHas('purchaseProducts', function ($query) use ($selectedProduct) {
                $query->where('product_id', $selectedProduct);
            });
        })->latest('id')->get();

        $purchaseIds = $purchases->pluck('id')->toArray() ?: [];

        $totalIn = PurchaseProduct::whereIn('purchase_id', $purchaseIds)->sum('quantity');

        if ($selectedProduct) {
            $totalIn = PurchaseProduct::whereIn('purchase_id', $purchaseIds)->where('product_id', $selectedProduct)->sum('quantity');
        }

        return view('purchase::purchase.summary', compact('purchases', 'allPurchases', 'totalIn', 'from', 'to', 'products', 'suppliers'));
    }

    public function purchaseInvoice(Purchase $purchase)
    {
        $purchaseCode = '#' . 'PUR' . $purchase?->purchase_code;


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
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 8,
            'margin_bottom' => 8,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'tempDir' => storage_path('app/public/mpdf_tmp'),
            'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
            'fontdata' => $fontData,
            'format' => $paperSize,
        ]);

        $purchaseProducts = $purchase->purchaseProducts()->with('product')->get();

        $view = view('purchase::PDF.purchaseInvoice', compact('purchase', 'purchaseProducts'))->render();
        $mPdf->WriteHTML($view);

        // Output the PDF as a download
        return $mPdf->Output('invoice-' . 'PUR' . $purchase->id . '.pdf', 'D');
    }

    public function productStockSummary(Request $request)
    {
        $shop = generaleSetting('shop');
        $startDate = null;
        $endDate = null;
        $dateRange = $request->date_range;
        $productIds = $request->product_id;
        $Allproduct = Product::where('shop_id', $shop->id)->latest('id')->get();

        if ($dateRange && str_contains($dateRange, ' - ')) {
            [$startDate, $endDate] = explode(' - ', $dateRange, 2);
            try {
                $startDate = Carbon::createFromFormat('Y-m-d', trim($startDate))->startOfDay();
                $endDate   = Carbon::createFromFormat('Y-m-d', trim($endDate))->endOfDay();
            } catch (\Exception $e) {
                $startDate = $endDate = null;
            }
        }
        $query = Product::with(['purchaseProducts', 'orderProducts'])
            ->where('shop_id', $shop->id);

        if ($productIds) {
            $query->whereIn('id', $productIds);
        }

        $products = $query->get()->map(function ($product) use ($startDate, $endDate) {
            if (empty($startDate) || empty($endDate)) {
                $purchasedQty = $product->purchaseProducts()->sum('quantity');
                $soldQty      = $product->productStockOuts()->sum('quantity');
                $availableStock = ($purchasedQty - $soldQty);
            } else {
                // Purchased
                $purchasedQty = $product->purchaseProducts()
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->sum('quantity');

                // Sold
                $soldQty = $product->productStockOuts()
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->sum('quantity');
                $availableStock = ($purchasedQty - $soldQty);
            }
            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'purchased' => $purchasedQty,
                'solded' => $soldQty,
                'available_stock' => $availableStock,
            ];
        });
        return view('purchase::stock.index', compact('products', 'Allproduct'));
    }
}
