<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::query()->delete();

        $currencies = [
            [
                'name' => 'USD',
                'symbol' => '$',
                'rate' => 1,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'EUR',
                'symbol' => '€',
                'rate' => 0.84,
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        Currency::insert($currencies);
    }
}
