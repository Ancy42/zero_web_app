<?php

namespace Modules\Purchase\Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductStockRemoveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        foreach ($products as $product) {
            $product->update([
                'quantity' => 0,
            ]);
        }
    }
}
