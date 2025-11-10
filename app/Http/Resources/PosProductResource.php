<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Http\Resources\Json\JsonResource;

class PosProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
     {
        $this->load(['reviews', 'orders', 'brand', 'flashSales']);

        $discountPercentage = $this->getDiscountPercentage($this->price, $this->discount_price);
        $totalSold = $this->orders->sum('pivot.quantity');
        $price = $this->price;
        $discountPrice = $this->discount_price;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'price' => (float) number_format($price, 2, '.', ''),
            'discount_price' => (float) number_format($discountPrice, 2, '.', ''),
            'main_price' => (float) number_format(($discountPrice > 0 ? $discountPrice : $price), 2, '.', ''),
            'discount_percentage' => (float) number_format($discountPercentage, 2, '.', ''),
            'rating' => (float) $this->averageRating ?? 0.0,
            'total_reviews' => (string) Number::abbreviate($this->reviews?->count(), maxPrecision: 2),
            'total_sold' => (string) number_format($totalSold, 0, '.', ','),
            'quantity' => (int) ($quantity ?? $this->quantity),
            'unit' => $this->unit ?? '',
        ];
    }
}
