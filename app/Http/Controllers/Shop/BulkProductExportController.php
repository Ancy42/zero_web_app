<?php

namespace App\Http\Controllers\Shop;

use App\Exports\TemplateExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BulkProductExportController extends Controller
{
    public function index()
    {
        return view('shop.bulk-product.export');
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $type = $request->type;
        $shop = generaleSetting('shop');
        $products = $shop?->products;

        $exportData = collect(
            [
                [
                    'id',
                    'name',
                    'product thumbnail',
                    'category',
                    'brand',
                    'unit',
                    'price',
                    'discount price',
                    'product sku',
                    'stock qty',
                    'short description',
                    'description',
                    'additional thumbnail'
                ],
            ]
        );

        foreach ($products as $product) {

            $categories = $product->categories->pluck('name')->toArray();
            $thumbnails = $product->medias()->pluck('additional_thumbnail')->toArray();


            $exportData[] = [
                $product->id,
                $product->name,
                $product->product_thumbnail,
                implode(',', $categories),
                $product->brand?->name,
                $product->unit ?? '',
                $product->price,
                $product->discount_price ?? 0,
                $product->code,
                $product->quantity ?? 0,
                $product->short_description,
                $product->description,
                implode(',', $thumbnails),

            ];
        }
        return Excel::download(new TemplateExport($exportData), 'products.xlsx');
    }

    // export for demo
    public function demoExport(Request $request)
    {
        $shop = generaleSetting('shop');

        $exportData = collect(
            [
                [
                    'id',
                    'name',
                    'product thumbnail',
                    'category',
                    'brand',
                    'unit',
                    'price',
                    'discount price',
                    'product sku',
                    'stock qty',
                    'short description',
                    'description',
                    'additional thumbnail'
                ],
            ]
        );

        // get first product
        $product = $shop?->products()?->first();

        // check if product exists
        if ($product) {

            $categories = $product->categories->pluck('name')->toArray();
            $thumbnails = $product->additionalThumbnails->pluck('additional_thumbnail')->toArray();
            $exportData[] = [
                0,
                $product->name,
                $product->product_thumbnail,
                implode(',', $categories),
                $product->brand?->name,
                $product->unit ?? '',
                $product->price,
                $product->discount_price ?? 0,
                $product->code,
                $product->quantity ?? 0,
                $product->short_description,
                $product->description,
                implode(',', $thumbnails),
            ];
        }

        return Excel::download(new TemplateExport($exportData), 'demo-template.xlsx');
    }
}
