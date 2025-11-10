<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FlashSaleRequest;
use App\Models\FlashSale;
use App\Repositories\FlashSaleRepository;
use Illuminate\Support\Facades\Storage;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::latest('id')->paginate(20);

        return view('admin.flashSale.index', compact('flashSales'));
    }

    public function create()
    {
        return view('admin.flashSale.create');
    }

    public function store(FlashSaleRequest $request)
    {
        FlashSaleRepository::storeByRequest($request);

        return to_route('admin.flashSale.index')->withSuccess(__('Created successfully'));
    }

    public function edit(FlashSale $flashSale)
    {
        return view('admin.flashSale.edit', compact('flashSale'));
    }

    public function update(FlashSaleRequest $request, FlashSale $flashSale)
    {
        FlashSaleRepository::updateByRequest($request, $flashSale);

        return to_route('admin.flashSale.index')->withSuccess(__('Updated successfully'));
    }

    public function destroy(FlashSale $flashSale)
    {

        $productIds = $flashSale->products()->pluck('id')->toArray();
        $flashSale->products()->detach($productIds);

        $flashSale->delete();

        return to_route('admin.flashSale.index')->withSuccess(__('Deleted successfully'));
    }

    public function statusToggle(FlashSale $flashSale)
    {
        $flashSale->update([
            'status' => ! $flashSale->status,
        ]);

        return back()->withSuccess(__('Updated successfully'));
    }

    public function show(FlashSale $flashSale)
    {
        $dealProducts = $flashSale->products;

        return view('admin.flashSale.show', compact('flashSale', 'dealProducts'));
    }
}
