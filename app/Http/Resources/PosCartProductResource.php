<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PosCartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $price = $this->price;
        $discountPrice = $this->discount_price > 0 ? $this->discount_price: 0;


        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand?->name ?? null,
            'thumbnail' => $this->thumbnail,
            'price' => (float) $price,
            'discount_price' => (float) $discountPrice,
            'order_qty' => (int) $this->pivot->quantity,
            'pos_cart_id' => $this->pivot?->id ?? null,
            'unit' => $this->unit ?? null,
            'sku_numbers' => json_decode($this->pivot->sku_no) ?? [],
        ];
    }
}
