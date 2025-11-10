<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleResource extends JsonResource
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
            'thumbnail' => $this->thumbnail,
            'start_date' => $this->start_date.' '.$this->start_time,
            'end_date' => $this->end_date.' '.$this->end_time,
            'products' => ProductResource::collection($this->products()->isActive()->take(9)->get()),
        ];
    }
}
