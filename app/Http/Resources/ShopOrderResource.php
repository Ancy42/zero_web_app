<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $estimatedDelivery = $this->shop?->estimated_delivery_time ?? '2-4 days';

        return [
            'order_status' => $this->order_status,
            'total_amount' => $this->total_amount,
            'discount' => $this->discount,
            'delivery_charge' => $this->delivery_charge,
            'payable_amount' => $this->payable_amount,
            'estimated_delivery_time' => (string) $estimatedDelivery,
            'address' => AddressResource::make($this->order?->address),
        ];
    }
}
