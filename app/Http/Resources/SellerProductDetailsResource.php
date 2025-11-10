<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
            'quantity' => (int) $this->quantity,
            'code' => (int) $this->code,
            'min_order_quantity' => (int) $this->min_order_quantity,
            'thumbnail' => $this->thumbnail,
            'additional_thumbnail' => $this->additionalThumbnails(),
            'category' => OnlyCategoryResource::make($this->categories()?->first()),
            'brand' => BrandResource::make($this->brand),
            'unit' => $this->unit,
            'short_description' => $this->short_description,
            'description' => $this->description,
        ];
    }
}
