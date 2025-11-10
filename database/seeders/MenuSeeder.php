<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate();

        $data = [
            [
                'name' => 'Home',
                'url' => '/',
                'title' => 'Home',
                'original_name' => 'Home',
                'original_url' => '/',
                'order' => 1,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Categories',
                'url' => '/categories',
                'title' => 'Categories',
                'original_name' => 'Categories',
                'original_url' => '/categories',
                'order' => 2,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Products',
                'url' => '/products',
                'title' => 'Products',
                'original_name' => 'Products',
                'original_url' => '/products',
                'order' => 3,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Shops',
                'url' => '/shops',
                'title' => 'Shops',
                'original_name' => 'Shops',
                'original_url' => '/shops',
                'order' => 4,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Most Popular',
                'url' => '/most-popular',
                'title' => 'Most Popular',
                'original_name' => 'Most Popular',
                'original_url' => '/most-popular',
                'order' => 5,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Best Deal',
                'url' => '/best-deal',
                'title' => 'Best Deal',
                'original_name' => 'Best Deal',
                'original_url' => '/best-deal',
                'order' => 6,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Contact',
                'url' => '/contact-us',
                'title' => 'Contact',
                'original_name' => 'Contact',
                'original_url' => '/contact-us',
                'order' => 7,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Blogs',
                'url' => '/blogs',
                'title' => 'Blogs',
                'original_name' => 'Blogs',
                'original_url' => '/blogs',
                'order' => 8,
                'is_active' => true,
                'is_default' => true,
            ],
        ];

        Menu::insert($data);
    }
}
