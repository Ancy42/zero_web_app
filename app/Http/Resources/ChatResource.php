<?php

namespace App\Http\Resources;

use App\Models\Shop;
use App\Models\User;
use GPBMetadata\Google\Api\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Twilio\Rest\Chat;

class ChatResource extends JsonResource
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
            'shop' => ShopRefineForChatResource::make($this->shop) ?? null,
            'user' => UserResource::make($this->user) ?? null,
            'product' => ChatProductResource::make($this->product) ?? null,
            'type' => $this->type ?? null,
            'message' => $this->message ?? null,
             'is_seen' => (bool) ($this->is_seen ?? false),
            'created_at' => $this->created_at ?? null,
            "shop_active_status" => $this->shop->last_online >= now() ? true : false,
            "user_active_status" => $this->user->last_online >= now() ? true : false,
        ];
    }
}
