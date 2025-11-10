<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlashSaleResource;
use App\Http\Resources\ProductResource;
use App\Models\FlashSale;
use App\Repositories\FlashSaleRepository;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    /**
     * Get the incoming flash sale.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $incoming = FlashSaleRepository::getIncoming();
        $running = FlashSaleRepository::getRunning();

        return $this->json('incoming and running flash sale', [
            'incoming_flash_sale' => $incoming ? FlashSaleResource::collection($incoming) : [],
            'running_flash_sale' => $running ? FlashSaleResource::collection($running) : [],
        ]);
    }

    /**
     * Get the flash sale.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(FlashSale $flashSale, Request $request)
    {
        $categoryId = $request->category_id;

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 18;
        $skip = ($page * $perPage) - $perPage;

        $products = $flashSale->products()->where(function ($query) use ($categoryId) {
            $query->when($categoryId, function ($query) use ($categoryId) {
                return $query->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('id', $categoryId);
                });
            });
        });

        $total = $products->count();

        $products = $products->when($perPage, function ($query) use ($perPage, $skip) {
            return $query->skip($skip)->take($perPage);
        })->get();

        return $this->json('flash sale details', [
            'total_products' => $total,
            'flash_sale' => FlashSaleResource::make($flashSale),
            'products' => ProductResource::collection($products),
        ]);
    }
}
