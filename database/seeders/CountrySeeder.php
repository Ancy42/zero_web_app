<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::truncate();
        $countriesJson = file_get_contents(database_path('data/countries.json'));

        $countries = json_decode($countriesJson, true);

        foreach ($countries as $country) {
            Country::create([
                'name' => $country['name'],
                'numeric_code' => $country['numeric_code'],
                'phone_code' => $country['phone_code'],
                'capital' => $country['capital'],
                'currency' => $country['currency'],
                'currency_name' => $country['currency_name'],
                'currency_symbol' => $country['currency_symbol'],
                'native' => $country['native'],
                'region' => $country['region'],
                'latitude' => $country['latitude'],
                'longitude' => $country['longitude'],
            ]);
        }
    }
}
