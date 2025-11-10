<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load(['reviews', 'orders', 'shop', 'brand', 'flashSales']);

        $lang = request()->header('accept-language') ?? 'en';

        $favorite = false;
        $user = Auth::guard('api')->user();

        if ($user && $user->customer) {
            $favorite = $user->customer->favorites()->where('product_id', $this->id)->exists();
        }

        $discountPercentage = $this->getDiscountPercentage($this->price, $this->discount_price);
        $totalSold = $this->orders->sum('pivot.quantity');

        $flashSale = $this->flashSales?->first();
        $flashSaleProduct = null;
        $quantity = null;

        if ($flashSale) {

            $flashSaleProduct = $flashSale->products()->where('id', $this->id)->first();

            $quantity = $flashSaleProduct->pivot->quantity - $flashSaleProduct->pivot->sale_quantity;

            if ($quantity == 0) {
                $quantity = null;
                $flashSaleProduct = null;
            } else {
                $discountPercentage = $flashSale->pivot->discount;
            }
        }

        $price = $this->price * $request->currencyData['rate'];
        $discountPrice = $flashSaleProduct ? $flashSaleProduct->pivot?->price : $this->discount_price;
        $discountCurrencyPrice = $discountPrice  * $request->currencyData['rate'];
        $discountCurrencyPercentage = $discountPercentage  * $request->currencyData['rate'];

        $translation = $this->translations()?->where('lang', $lang)->first();
        $name = $translation?->name ?? $this->name;
        $description = $translation?->description ?? $this->description;
        $shortDescription = $translation?->short_description ?? $this->short_description;

        $brandTranslation = $this->brand?->translations()?->where('lang', $lang)->first();
        $brandName = $brandTranslation?->name ?? $this->brand?->name;
        $shop = $this->shop;

        $lastOnline = $this->last_online >= now() ? true : false;

        $currentTime = Carbon::now();

        $openingTime = $shop?->opening_time;
        $closingTime = $shop?->closing_time;

        // Parse opening and closing times using Carbon
        $openingTime = Carbon::parse($openingTime)->format('H:i');
        $closingTime = Carbon::parse($closingTime)->format('H:i');

        $shopStatus = 'Offline';
        // Check if the current time is between opening and closing times
        if ($currentTime->between($openingTime, $closingTime)) {
            $shopStatus = 'Online';
        }

        return [
            'id' => $this->id,
            'slug' =>$this->slug,
            'name' => $name,
            'short_description' => $shortDescription,
            'price' => (float) number_format($price, 2, '.', ''),
            'discount_price' => (float) number_format($discountCurrencyPrice, 2, '.', ''),
            'discount_percentage' => (float) number_format($discountCurrencyPercentage, 2, '.', ''),
            'rating' => (float) $this->averageRating ?? 0.0,
            'total_reviews' => (string) Number::abbreviate($this->reviews?->count(), maxPrecision: 2),
            'total_sold' => (string) number_format($totalSold, 0, '.', ','),
            'quantity' => (int) ($quantity ?? $this->quantity),
            'is_favorite' => (bool) $favorite,
            'thumbnails' => $this->thumbnails(),
            'brand' => $brandName,
            'unit' => $this->unit ?? null,
            'unit_measurement_add' => $this->unit_measurement_add ?? 0,
            'description' => $description,
            'shop' => [
                'id' => $shop?->id,
                'name' => $shop?->name,
                'logo' => $shop?->logo,
                'banner' => $shop?->banner,
                'rating' => (float) round($shop?->averageRating, 1),
                 'shop_status' => (string) $shopStatus,
                'total_reviews' => (string) Number::abbreviate($shop?->reviews?->count(), maxPrecision: 2),
                'total_products' => (string) Number::abbreviate($shop?->products?->count(), maxPrecision: 2),
                'estimated_delivery_time' => (string) ($shop?->estimated_delivery_time ?? '2-4 days'),
                'delivery_charge' => (float) getDeliveryCharge(1),
                'last_online' => $lastOnline
            ],
            'flash_sale' => $flashSaleProduct ? FlashSaleResource::make($flashSale) : null,
            'meta_title' => $this->meta_title ?? $name,
            'meta_description' => $this->meta_description ?? $name,
            'meta_keywords' => $this->meta_keywords,
        ];
    }
}
