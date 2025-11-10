<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

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

        return view('shop.brand.index', compact('brands'));
    }
}
