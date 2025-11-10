<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('brand', 'reviews');

        $review = $this->reviews()->where('customer_id', auth()->user()->customer?->id)->where('product_id', $this->id)->where('order_id', $request->order_id)->first();

        $price = $this->pivot->price > 0 ? $this->pivot->price : ($this->discount_price > 0 ? $this->discount_price : $this->price);

        $currencyPrice = $price * $this->currency_rate ?? 1;
        $discount = $this->discount_price > 0 ? $price : 0;
        $currencyDiscount = $discount * $this->currency_rate ?? 1;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug'=>$this->slug,
            'brand' => $this->brand?->name ?? null,
            'thumbnail' => $this->thumbnail,
            'price' => (float) $currencyPrice,
            'discount_price' => (float) $currencyDiscount,
            'order_qty' => (int) $this->pivot->quantity,
            'currency_symbol' => $this->currency_symbol ?? '$',
            'rating' => $review ? (float) $review->rating : null,
            'unit' => $this->pivot->unit ?? '',
        ];
    }
}
