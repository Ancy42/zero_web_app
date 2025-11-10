<?php

namespace App\Http\Resources;

use App\Repositories\ShopUserChatsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ShopUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $lastMessage = ShopUserChatsRepository::query()->where('shop_id', $this->shop?->id)->where('user_id', $this->user?->id)->latest('id')->first();

        $unreadChatUser = ShopUserChatsRepository::query()->where('shop_id', $this->shop?->id)
            ->where('user_id', $this->user?->id)
            ->where('type', 'user')
            ->where('is_seen', false)->count();

        $unreadChatShop = ShopUserChatsRepository::query()->where('shop_id', $this->shop?->id)
            ->where('user_id', $this->user?->id)
            ->where('type', 'shop')
            ->where('is_seen', false)->count();

        $lasMessageProduct = $lastMessage?->product;
        $lastMsg = Str::limit(($lastMessage->message ?? $lasMessageProduct?->name), 12, '...');

        $lastTime = optional($lastMessage?->created_at ? Carbon::parse($lastMessage->created_at) : null)?->diffForHumans();

        return [
            'id' => $this->id ?? null,
            'shop' => ShopRefineForChatResource::make($this->shop ?? null),
            'user' => UserResource::make($this->user ?? null),
            'product' => ChatProductResource::make($this->product ?? null),
            'last_message' => $lastMsg ?? null,
            'last_message_time' => $lastTime,
            'is_read' => (bool) ($lastMessage ? $lastMessage->is_seen : false),
            'unread_message_user' => $unreadChatUser,
            'unread_message_shop' => $unreadChatShop,
        ];
    }
}
