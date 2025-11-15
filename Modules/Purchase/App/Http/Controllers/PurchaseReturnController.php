<?php

namespace Modules\Purchase\App\Http\Controllers;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;
use App\Http\Controllers\Controller;
use Modules\Purchase\App\Models\Purchase;
use Modules\Purchase\App\Models\PurchaseReturn;
use Modules\Purchase\App\Repositories\PurchaseReturnRepository;
use Modules\Purchase\App\Resources\PurchaseProductSearchResource;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $shop = generaleSetting('shop');
        $purchaseReturns = PurchaseReturn::where('shop_id', $shop->id)->latest()->paginate(10);
        return view('purchase::purchaseReturn.index', compact('purchaseReturns'));
    }
    public function create()
    {
        return view('purchase::purchaseReturn.create');
    }
    public function invoiceSearch(Request $request)
    {
        $search = $request->search;
        $shop = generaleSetting('shop');

        $purchases = Purchase::where('shop_id', $shop->id)->where('purchase_code', 'like', "%$search%")->get();

        $uniquePurchases = $purchases->unique('id')->values();

        $purchasesGet = PurchaseProductSearchResource::collection($uniquePurchases);
        return response()->json([
            'data' => $purchasesGet,
            'status' => 'success',
        ], 200);
    }
    public function invoiceAdd(Request $request)
    {
        $id = $request->id;
        $purchase = Purchase::find($id);
        $purchaseProducts = $purchase->purchaseProducts()->with('product')->get();
        return view('purchase::purchaseReturn.return_form', compact('purchase', 'purchaseProducts'));
    }

    public function store(Purchase $purchase, Request $request)
    {

        $requestLotProducts = $request->return_quantity;
        if (
            empty($requestLotProducts) ||
            collect($requestLotProducts)->filter(fn($qty) => !is_null($qty) && $qty > 0)->isEmpty()
        ) {
            return back()->with('error', __('Select Return Quantity.'));
        }

        PurchaseReturnRepository::storeByRequest($purchase, $request);

        return to_route('shop.purchaseReturn.index')->withSuccess(__('Return Successfully'));
    }

    public function returnInvoice($id)
    {
        $purchaseReturn = PurchaseReturn::find($id);

        $purchaseReturnCode = '#' . 'PCB' . $purchaseReturn?->return_lot_code;
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

        $purchaseReturnProducts = $purchaseReturn->purchaseReturnProducts()->with('product')->get();

        $view = view('purchase::PDF.purchaseReturnInvoice', compact('purchaseReturn', 'purchaseReturnProducts'))->render();
        $mPdf->WriteHTML($view);

        // Output the PDF as a stream
        return $mPdf->Output('invoice-' . 'RET' . $purchaseReturn->id . '.pdf', 'I');
    }
}
