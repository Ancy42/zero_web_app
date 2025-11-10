<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < rand(80, 160); $i++) {

            $orderProducts = $faker->randomElements(Product::all(), rand(2, 6));

            $order = Order::factory()->create();

            foreach ($orderProducts as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => $faker->numberBetween(1, 3),
                ]);
            }
        }
    }
}
