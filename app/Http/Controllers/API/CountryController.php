<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Cache::rememberForever('countries', function () {
            return Country::all();
        });

        return $this->json('all countries', [
            'countries' => CountryResource::collection($countries),
        ]);
    }
}
