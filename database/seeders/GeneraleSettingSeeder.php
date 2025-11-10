<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\GeneraleSetting;
use Illuminate\Database\Seeder;

class GeneraleSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $isLocal = app()->environment('local') ? true : false;
        $currency = Currency::where('is_default', true)->first();

        GeneraleSetting::truncate();

        GeneraleSetting::create([
            'name' => config('app.name'),
            'title' => config('app.name'),
            'email' => null,
            'mobile' => null,
            'address' => null,
            'primary_color' => '#51af5B',
            'secondary_color' => '#17181D',
            'shop_type' => 'multi',
            'show_download_app' => true,
            'google_playstore_url' => null,
            'app_store_url' => null,
            'currency_id' => $currency?->id ?? null,
            'currency' => $currency?->symbol ?? '$',
            'currency_position' => 'prefix',
            'direction' => 'ltr',
            'favicon_id' => null,
            'logo_id' => null,
            'show_footer' => true,
            'footer_phone' => $isLocal ? '+880123456789' : null,
            'footer_text' => 'All right reserved by RazinSoft',
            'footer_description' => 'ReadyGrocery is your trusted online shop for fresh groceries, perfumes, and daily essentials. We deliver quality products at the best prices—right to your doorstep.',
            'footer_logo_id' => null,
            'footer_qrcode_id' => null,
            'app_logo_id' => null,
            'default_delivery_charge' => 0,
            'cash_on_delivery' => 1,
            'online_payment' => 1,
            'product_description' => 'Product name: {product_name}. Short description: {short_description}. Write a long, SEO-friendly product description that includes relevant keywords, highlights unique features, and encourages buyers to take action.',
            'page_description' => 'The page title is {title}. Generate a well-structured, professional, and legally appropriate long content for this page, ensuring it covers all important points relevant to {title}.',
            'blog_description' => 'The blog title is {title}. Generate a well-structured, professional, and legally appropriate long content for this blog, ensuring it covers all important points relevant to {title}.',
        ]);
    }
}
