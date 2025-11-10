<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     */
    public function index()
    {
        $shop = generaleSetting('rootShop');

        // Get brands
        $brands = $shop->brands()->paginate(20);

        return view('admin.brand.index', compact('brands'));
    }

    /**
     * store a new brand
     */
    public function store(BrandRequest $request)
    {
        BrandRepository::storeByRequest($request);

        return to_route('admin.brand.index')->withSuccess(__('Brand created successfully'));
    }

    /**
     * update a brand
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        BrandRepository::updateByRequest($request, $brand);

        return to_route('admin.brand.index')->withSuccess(__('Brand updated successfully'));
    }

    /**
     * status toggle a brand
     */
    public function statusToggle(Brand $brand)
    {
        $brand->update([
            'is_active' => ! $brand->is_active,
        ]);

        return to_route('admin.brand.index')->withSuccess(__('Brand status updated'));
    }
}
