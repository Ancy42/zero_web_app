<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? '',
            'parent_id' => $this->parent_id ?? null,
            'category_id' => $this->category_id ?? null,
            'is_active' => $this->status ?? null,
            'category' => $this->category ?? '',
            'attributeGet' =>$this->attributeGet ? AdminCategoryAttributeResource::make($this->attributeGet) : '',
        ];
    }
}
