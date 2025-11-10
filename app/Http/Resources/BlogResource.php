<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $description = strip_tags($this->description);
        $description = str_replace(['&nbsp;', '&nbsp', '&#160;', '&#160'], ' ', $description);
        $description = preg_replace('/\s+/', ' ', $description);
        $description = mb_strimwidth($description, 0, 200);

        $daysSinceCreated = now()->diffInDays($this->created_at);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'post_by' => [
                'name' => $this->user?->fullName ?? 'Admin',
                'profile_photo' => $this->user?->thumbnail ?? asset('default/default.jpg'),
            ],
            'thumbnail' => $this->thumbnail,
            'total_views' => $this->views->count(),
            'description' => $description,
            'created_at' => $this->created_at->format('d M, Y'),
            'is_new' => $daysSinceCreated < 5 ? true : false,
        ];
    }
}
