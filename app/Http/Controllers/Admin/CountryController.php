<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    public function index()
    {
        $search = request('search');

        $countries = Country::when($search, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.country.index', compact('countries'));
    }

    public function store(CountryRequest $request)
    {
        Country::create([
            'name' => $request->name,
            'phone_code' => $request->phone_code,
        ]);

        $this->removeCache();

        return to_route('admin.country.index')->withSuccess(__('Created Successfully'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        $country->update([
            'name' => $request->name,
            'phone_code' => $request->phone_code,
        ]);

        $this->removeCache();

        return to_route('admin.country.index')->withSuccess(__('Updated Successfully'));
    }

    public function destroy(Country $country)
    {
        $country->delete();

        $this->removeCache();

        return back()->withSuccess(__('Deleted Successfully'));
    }

    private function removeCache()
    {
        Cache::forget('countries');
    }
}
