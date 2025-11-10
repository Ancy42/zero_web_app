<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use App\Repositories\CurrencyRepository;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::paginate(20);

        $defaultCurrency = generaleSetting('defaultCurrency');

        return view('admin.currency.index', compact('currencies', 'defaultCurrency'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $defaultCurrency = generaleSetting('defaultCurrency');

        return view('admin.currency.create', compact('defaultCurrency'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CurrencyRequest $request)
    {
        CurrencyRepository::storeByRequest($request);

        return to_route('admin.currency.index')->withSuccess(__('Created Successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        $defaultCurrency = generaleSetting('defaultCurrency');

        return view('admin.currency.edit', compact('currency', 'defaultCurrency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CurrencyRequest $request, Currency $currency)
    {
        CurrencyRepository::updateByRequest($request, $currency);

        return to_route('admin.currency.index')->withSuccess(__('Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Currency::destroy($id);

        return to_route('admin.currency.index')->withSuccess(__('Deleted Successfully'));
    }
}
