<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = ['Nike', 'Adidas', 'Apple', 'Samsung', 'Sony', 'HP', 'Dell', 'Lenovo', 'Canon', 'Sony', 'LG', 'Microsoft', 'Puma', 'H&M', 'Zara', 'Gucci', 'Toyota', 'Honda', 'BMW', 'Mercedes-Benz'];

        $shop = User::role('root')->whereHas('shop')->first()?->shop;

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'shop_id' => $shop->id,
                'media_id' => Media::factory()->create()->id,
                'is_default' => true,
            ]);
        }
    }
}
