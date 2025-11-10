<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FooterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $generaleSetting = generaleSetting('setting');
        $shopType = $generaleSetting?->shop_type ?? 'multi';

        $items = $this->items()->when($shopType != 'multi', function ($query) {
            $query->where('shop_type', '!=', 'multi');
        })->where('is_active', 1)->orderBy('order')->get();

        return [
            'id' => $this->id,
            'position' => $this->order,
            'title' => $this->title,
            'items' => FooterItemResource::collection($items),
        ];
    }
}
