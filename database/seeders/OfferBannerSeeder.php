<?php

namespace Database\Seeders;

use App\Models\OfferBanner;
use Illuminate\Database\Seeder;
use App\Repositories\MediaRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfferBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfferBanner::truncate();
        $offerBanners = [
            [
                'name' => 'Home Page Offer Banner One',
                'slug' => 'home-page-offer-banner-one',
                'position' => 1,
                'alias' => 'home_banner_1',
            ],
            [
                'name' => 'Home Page Offer Banner Two',
                'slug' => 'home-page-offer-banner-two',
                'position' => 2,
                'alias' => 'home_banner_2',
            ],
            [
                'name' => 'Home Page Offer Banner Three',
                'slug' => 'home-page-offer-banner-three',
                'position' => 3,
                'alias' => 'home_banner_3',
            ],
            [
                'name' => 'Popular Product Banner',
                'slug' => 'popular-product-banner',
                'position' => 4,
                'alias' => 'popular_product_banner',
            ],
            [
                'name' => 'Product Page Offer Banner',
                'slug' => 'product-page-offer-banner',
                'position' => 5,
                'alias' => 'product_page_banner',
            ],
        ];

        foreach ($offerBanners as $offerBanner) {
            OfferBanner::create($offerBanner);
        }
    }
}
