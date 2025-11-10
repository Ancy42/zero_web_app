<?php

namespace Database\Seeders;

use App\Models\HomeTheme;
use App\Models\OfferBanner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HomeScreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        HomeTheme::truncate();
        OfferBanner::truncate();
        Schema::enableForeignKeyConstraints();


        $homeThemes = [
            [
                'theme_name' => 'NovaStore',
                'is_active' => 1,
                'alias' => 'NovaStore',
            ],
            [
                'theme_name' => 'MegaMart',
                'is_active' => 0,
                'alias' => 'MegaMart',
            ],
            [
                'theme_name' => 'NextMart',
                'is_active' => 0,
                'alias' => 'NextMart',
            ],
            [
                'theme_name' => 'PrimeCart',
                'is_active' => 0,
                'alias' => 'PrimeCart',
            ],
            [
                'theme_name' => 'UltraMart',
                'is_active' => 0,
                'alias' => 'UltraMart',
            ],
            [
                'theme_name' => 'SuperMart',
                'is_active' => 0,
                'alias' => 'upcoming',
            ],
        ];

        $themeMap = [];
        foreach ($homeThemes as $homeTheme) {
            $created = HomeTheme::create($homeTheme);
            $themeMap[$homeTheme['alias']] = $created->id;
        }

        $offerBanners = [
            [
                'name' => 'Home Banner One',
                'slug' => 'home-banner-1',
                'position' => 1,
                'alias' => 'home_banner_1',
                'theme_alias' => 'NovaStore',
            ],
            [
                'name' => 'Home Banner Two',
                'slug' => 'home-banner-2',
                'position' => 2,
                'alias' => 'home_banner_2',
                'theme_alias' => 'NovaStore',
            ],
            [
                'name' => 'Home Banner Three',
                'slug' => 'home-banner-3',
                'position' => 3,
                'alias' => 'home_banner_3',
                'theme_alias' => 'NovaStore',
            ],
            [
                'name' => 'Home Banner Four',
                'slug' => 'home-banner-4',
                'position' => 4,
                'alias' => 'home_banner_4',
                'theme_alias' => 'NovaStore',
            ],
            [
                'name' => 'Product Page Offer Banner',
                'slug' => 'product-page-offer-banner',
                'position' => 5,
                'alias' => 'product_page_banner',
                'theme_alias' => 'NovaStore',
            ],
            [
                'name' => 'Home Banner One',
                'slug' => 'home-banner-1',
                'position' => 1,
                'alias' => 'home_banner_1',
                'theme_alias' => 'MegaMart',
            ],
            [
                'name' => 'Home Banner One',
                'slug' => 'home-banner-1',
                'position' => 1,
                'alias' => 'home_banner_1',
                'theme_alias' => 'NextMart',
            ],
            [
                'name' => 'Home Banner Two',
                'slug' => 'home-banner-2',
                'position' => 2,
                'alias' => 'home_banner_2',
                'theme_alias' => 'NextMart',
            ],
            [
                'name' => 'Home Banner One',
                'slug' => 'home-banner-1',
                'position' => 1,
                'alias' => 'home_banner_1',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Two',
                'slug' => 'home-banner-2',
                'position' => 2,
                'alias' => 'home_banner_2',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Three',
                'slug' => 'home-banner-3',
                'position' => 3,
                'alias' => 'home_banner_3',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Four',
                'slug' => 'home-banner-4',
                'position' => 4,
                'alias' => 'home_banner_4',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Five',
                'slug' => 'home-banner-5',
                'position' =>5,
                'alias' => 'home_banner_5',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Six',
                'slug' => 'home-banner-6',
                'position' => 6,
                'alias' => 'home_banner_6',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Seven',
                'slug' => 'home-banner-6',
                'position' => 6,
                'alias' => 'home_banner_7',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Eight',
                'slug' => 'home-banner-6',
                'position' => 6,
                'alias' => 'home_banner_8',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner Nine',
                'slug' => 'home-banner-6',
                'position' => 6,
                'alias' => 'home_banner_9',
                'theme_alias' => 'PrimeCart',
            ],
            [
                'name' => 'Home Banner One',
                'slug' => 'home-banner-1',
                'position' => 1,
                'alias' => 'home_banner_1',
                'theme_alias' => 'UltraMart',
            ],
            [
                'name' => 'Home Banner Two',
                'slug' => 'home-banner-2',
                'position' => 2,
                'alias' => 'home_banner_2',
                'theme_alias' => 'UltraMart',
            ],
            [
                'name' => 'Home Banner Three',
                'slug' => 'home-banner-3',
                'position' => 3,
                'alias' => 'home_banner_3',
                'theme_alias' => 'UltraMart',
            ],
            [
                'name' => 'Home Banner Four',
                'slug' => 'home-banner-4',
                'position' => 4,
                'alias' => 'home_banner_4',
                'theme_alias' => 'UltraMart',
            ],
            [
                'name' => 'Home Banner Five',
                'slug' => 'home-banner-5',
                'position' =>5,
                'alias' => 'home_banner_5',
                'theme_alias' => 'UltraMart',
            ],
            [
                'name' => 'Home Banner Six',
                'slug' => 'home-banner-6',
                'position' => 6,
                'alias' => 'home_banner_6',
                'theme_alias' => 'UltraMart',
            ],
        ];

        foreach ($offerBanners as $banner) {
            OfferBanner::create([
                'name' => $banner['name'],
                'slug' => $banner['slug'],
                'position' => $banner['position'],
                'alias' => $banner['alias'],
                'home_theme_id' => $themeMap[$banner['theme_alias']],
            ]);
        }
    }
}
