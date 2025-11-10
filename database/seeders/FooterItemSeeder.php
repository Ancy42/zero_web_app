<?php

namespace Database\Seeders;

use App\Models\Footer;
use App\Models\FooterItem;
use App\Models\GeneraleSetting;
use Illuminate\Database\Seeder;

class FooterItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FooterItem::query()->delete();

        $generaleSetting = GeneraleSetting::first();

        $footerItems = [
            // Footer 1
            [
                [
                    'id' => 1,
                    'footer_id' => 1,
                    'type' => 'logo',
                    'title' => null,
                    'url' => null,
                    'is_active' => 1,
                    'order' => 0,
                    'is_default' => 1,
                ],
                [
                    'id' => 2,
                    'footer_id' => 1,
                    'type' => 'text',
                    'title' => $generaleSetting?->footer_description ?? 'ReadyGrocery is your trusted online shop for fresh groceries, perfumes, and daily essentials. We deliver quality products at the best prices—right to your doorstep.',
                    'url' => null,
                    'is_active' => 1,
                    'order' => 1,
                    'is_default' => 1,
                ],
                [
                    'id' => 3,
                    'footer_id' => 1,
                    'type' => 'phone',
                    'title' => $generaleSetting?->footer_phone ?? '+8801711257498',
                    'url' => null,
                    'is_active' => 1,
                    'order' => 2,
                    'is_default' => 1,
                ],
                [
                    'id' => 4,
                    'footer_id' => 1,
                    'type' => 'email',
                    'title' => $generaleSetting?->footer_email ?? 'support@razinsoft.com',
                    'url' => null,
                    'is_active' => 1,
                    'order' => 3,
                    'is_default' => 1,
                ],
                [
                    'id' => 5,
                    'footer_id' => 1,
                    'type' => 'social_links',
                    'title' => null,
                    'url' => null,
                    'is_active' => 1,
                    'order' => 4,
                    'is_default' => 1,
                ],
            ],

            // Footer 2
            [

                [
                    'id' => 6,
                    'footer_id' => 2,
                    'type' => 'link',
                    'title' => 'Products',
                    'url' => '/products',
                    'is_active' => 1,
                    'order' => 0,
                    'is_default' => 0,
                ],
                [
                    'id' => 7,
                    'footer_id' => 2,
                    'type' => 'link',
                    'title' => 'Most Popular',
                    'url' => '/most-popular',
                    'is_active' => 1,
                    'order' => 1,
                    'is_default' => 0,
                ],
                [
                    'id' => 8,
                    'footer_id' => 2,
                    'type' => 'link',
                    'title' => 'Best Deal',
                    'url' => '/best-deal',
                    'is_active' => 1,
                    'order' => 2,
                    'is_default' => 0,
                ],
                [
                    'id' => 9,
                    'footer_id' => 2,
                    'type' => 'link',
                    'title' => 'Become a Seller',
                    'url' => '/shop/register',
                    'shop_type' => 'multi',
                    'target' => '_blank',
                    'is_active' => 1,
                    'order' => 3,
                    'is_default' => true,
                ],
                [
                    'id' => 10,
                    'footer_id' => 2,
                    'type' => 'link',
                    'title' => 'Blogs',
                    'url' => '/blogs',
                    'is_active' => 1,
                    'order' => 5,
                    'is_default' => 0,
                ],
                [
                    'id' => 11,
                    'footer_id' => 3,
                    'type' => 'link',
                    'title' => 'About us',
                    'url' => '/about-us',
                    'is_active' => 1,
                    'order' => 0,
                    'is_default' => 0,
                ],
            ],

            // Footer 3
            [
                [
                    'id' => 12,
                    'footer_id' => 3,
                    'type' => 'link',
                    'title' => 'Contact',
                    'url' => '/contact-us',
                    'is_active' => 1,
                    'order' => 1,
                    'is_default' => 0,
                ],
                [
                    'id' => 13,
                    'footer_id' => 3,
                    'type' => 'link',
                    'title' => 'Terms & Conditions',
                    'url' => '/terms-and-conditions',
                    'is_active' => 1,
                    'order' => 2,
                    'is_default' => 0,
                ],
                [
                    'id' => 14,
                    'footer_id' => 3,
                    'type' => 'link',
                    'title' => 'Privacy Policy',
                    'url' => '/privacy-policy',
                    'is_active' => 1,
                    'order' => 3,
                    'is_default' => 0,
                ],
            ],

            // Footer 4
            [
                [
                    'id' => 15,
                    'footer_id' => 4,
                    'type' => 'app_store',
                    'title' => null,
                    'url' => null,
                    'is_active' => 1,
                    'order' => 0,
                    'is_default' => true,
                ],
            ],
        ];

        $footers = Footer::all();

        foreach ($footers as $key => $footer) {

            $items = $footerItems[$key];

            foreach ($items as $item) {

                $item['footer_id'] = $footer->id;
                FooterItem::create($item);
            }
        }
    }
}
