<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Root Shop
        $this->call(RootAdminShopSeeder::class);

        // Test shop for testing
        $user = User::factory()->create([
            'name' => 'Test Shop',
            'phone' => '01100000001',
            'email' => 'shop@readygrocery.com',
            'is_active' => true,
        ]);

        $shop = Shop::factory()->create([
            'name' => 'Demo Shop',
            'user_id' => $user->id,
        ]);
        $shop->user->assignRole('shop');

        // Create 10 shops
        for ($i = 1; $i <= 10; $i++) {
            $shop = Shop::factory()->create();

            $shop->user->assignRole('shop');
        }
    }
}
