<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id ?? null,
            'thumbnail' => $this->thumbnail ?? null,
            'image' => $this->image ?? null,
            'description' => $this->description ?? null,
            'is_active' => $this->status ?? null,
        ];
    }
}
