<?php

namespace Modules\Purchase\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $this->load(['flashSales']);

        $discountPercentage = $this->getDiscountPercentage($this->price, $this->discount_price);

        $flashSale = $this->flashSales?->first();
        $flashSaleProduct = null;
        $quantity = null;

        if ($flashSale) {
            $flashSaleProduct = $flashSale?->products()->where('id', $this->id)->first();

            $quantity = $flashSaleProduct?->pivot->quantity - $flashSaleProduct->pivot->sale_quantity;

            if ($quantity == 0) {
                $quantity = null;
                $flashSaleProduct = null;
            } else {
                $discountPercentage = $flashSale?->pivot->discount;
            }
        }

        $discountPrice = $flashSaleProduct ? $flashSaleProduct->pivot->price : $this->discount_price;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'price' => (float) number_format($this->price, 2, '.', ''),
            'buy_price' => $this->buy_price,
            'discount_price' => (float) number_format($discountPrice, 2, '.', ''),
            'discount_percentage' => (float) number_format($discountPercentage, 2, '.', ''),
            'quantity' => (int) ($flashSaleProduct ? $quantity : $this->quantity),
            'unit' => $this->unit ?? null,
            'unit_measurement_add' => $this->unit_measurement_add ?? 0,
        ];
    }
}
