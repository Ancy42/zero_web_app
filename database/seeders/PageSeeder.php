<?php

namespace Database\Seeders;

use App\Models\Page;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::truncate();

        $faker = Factory::create();

        // Pages
        $pages = [
            [
                'title' => 'Products',
                'slug' => 'products',
                'url' => 'products',
                'description' => null,
                'is_active' => true,
                'is_default' => true,
                'is_editable' => false,
            ],
            [
                'title' => 'Shops',
                'slug' => 'shops',
                'url' => 'shops',
                'description' => null,
                'is_active' => true,
                'is_default' => true,
                'is_editable' => false,
            ],
            [
                'title' => 'Most Popular',
                'slug' => 'most-popular',
                'url' => 'most-popular',
                'description' => null,
                'is_active' => true,
                'is_default' => true,
                'is_editable' => false,
            ],
            [
                'title' => 'Best Deal',
                'slug' => 'best-deal',
                'url' => 'best-deal',
                'description' => null,
                'is_active' => true,
                'is_default' => true,
                'is_editable' => false,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact-us',
                'url' => 'contact-us',
                'description' => null,
                'is_active' => true,
                'is_default' => true,
                'is_editable' => false,
            ],
            [
                'title' => 'Blogs',
                'slug' => 'blogs',
                'url' => 'blogs',
                'description' => null,
                'is_active' => true,
                'is_default' => true,
                'is_editable' => false,
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'url' => 'about-us',
                'description' => $faker->randomHtml(4, rand(4, 10)),
                'is_active' => true,
                'is_default' => true,
                'is_editable' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'url' => 'privacy-policy',
                'description' => $faker->randomHtml(),
                'is_active' => true,
                'is_default' => true,
                'is_editable' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-and-conditions',
                'url' => 'terms-and-conditions',
                'description' => $faker->randomHtml(),
                'is_active' => true,
                'is_default' => true,
                'is_editable' => true,
            ],
            [
                'title' => 'Return policy / Refund Policy',
                'slug' => 'return-and-refund-policy',
                'url' => 'page/return-and-refund-policy',
                'description' => $faker->randomHtml(),
                'is_active' => true,
                'is_default' => false,
                'is_editable' => true,
            ],
            [
                'title' => 'Shipping & Delivery Policy',
                'slug' => 'shipping-and-delivery-policy',
                'url' => 'page/shipping-and-delivery-policy',
                'description' => $faker->randomHtml(),
                'is_active' => true,
                'is_default' => false,
                'is_editable' => true,
            ],
        ];

        Page::insert($pages);
    }
}
