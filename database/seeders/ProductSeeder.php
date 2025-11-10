<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Media;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        for ($i = 1; $i <= rand(50, 120); $i++) {
            $product = Product::factory()->create();
            $product->categories()->attach($categories->random(1));
        }
    }
}
