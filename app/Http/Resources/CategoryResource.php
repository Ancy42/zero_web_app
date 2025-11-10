<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = request()->header('accept-language') ?? 'en';
        $translation = $lang != 'en' ? $this->translations()?->where('lang', $lang)->first() : null;

        return [
            'id' => $this->id ?? null,
            'name' => $translation ? $translation->name : ($this->name ?? null),
            'thumbnail' => $this->thumbnail ?? null,
            'total_products' => $this->products->count() ?? null,
            'sub_categories' => CategoryResource::collection($this->subCategories ?? []),
        ];
    }
}
