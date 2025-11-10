<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentTime = Carbon::now();

        $shop = generaleSetting('shop', $this);

        // Parse opening and closing times using Carbon
        $openingTime = Carbon::parse($shop->opening_time)->format('H:i');
        $closingTime = Carbon::parse($shop->closing_time)->format('H:i');

        $shopStatus = 'Offline';

        // Check if the current time is between opening and closing times
        if ($currentTime->between($openingTime, $closingTime)) {
            $shopStatus = 'Online';
        }

        $offDay = $shop->off_day ?? [];

        if (! empty($offDay)) {
            $offDay = is_array($offDay) ? $offDay : json_decode($offDay);
            $day = strtolower($currentTime->format('D'));
            in_array($day, $offDay) ? $shopStatus = 'Offline' : false;
        }

        return [
            'id' => $this->id,
            'first_name' => $this->name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'profile_photo' => $this->thumbnail,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'is_active' => (bool) $this->is_active,
            'shop_status' => (string) $shopStatus,
            'shop' => [
                'id' => $shop->id,
                'name' => $shop->name,
                'logo' => $shop->logo,
                'banner' => $shop->banner,
                'address' => $shop->address,
                'open_time' => $openingTime,
                'close_time' => $closingTime,
                'off_day' => $offDay,
                'prefix' => $shop->prefix ?? 'RC',
                'estimated_delivery_time' => (int) $shop->estimated_delivery_time,
                'min_order_amount' => (float) $shop->min_order_amount ?? 0,
                'shop_status' => $shopStatus,
                'total_products' => (int) $shop->products?->count(),
                'total_categories' => (int) $shop->categories?->count(),
                'rating' => (float) number_format($shop->averageRating, 1, '.', ''),
                'total_reviews' => (int) $shop->reviews->count(),
                'description' => $shop->description,
            ],
            'banners' => BannerResource::collection($shop->banners),
        ];
    }
}
