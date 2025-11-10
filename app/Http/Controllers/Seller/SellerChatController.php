<?php

namespace App\Http\Controllers\Seller;

use App\Events\SendMessageToUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ShopUserResource;
use App\Repositories\ShopUserChatsRepository;
use App\Repositories\ShopUserRepository;
use Illuminate\Http\Request;

class SellerChatController extends Controller
{
    public function getUsers(Request $request)
    {
        $shop = generaleSetting('shop');
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        // $users = ShopUserRepository::query()->where('shop_id', $shop->id)->latest('id')->get();
        $users = ShopUserRepository::query()
            ->where('shop_id', $shop->id)
            ->with('latestMessage')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->when($skip, function ($query) use ($skip) {
                return $query->skip($skip);
            })->when($perPage, function ($query) use ($perPage) {
                return $query->take($perPage);
            })
            ->get()
            ->sortByDesc(function ($shopUser) {
                return optional($shopUser->latestMessage)->created_at;
            })
            ->values();

        return $this->json('success', [
            'total' => $users->count(),
            'data' => ShopUserResource::collection($users),
        ]);
    }
    public function getMessageAdmin(Request $request)
    {
        $shop = generaleSetting('shop');
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;


        $chats = ShopUserChatsRepository::query()
            ->where('user_id', $request->user_id)
            ->where('shop_id', $shop->id)
            ->orderBy('id', 'desc')
            ->when($skip, function ($query) use ($skip) {
                return $query->skip($skip);
            })->when($perPage, function ($query) use ($perPage) {
                return $query->take($perPage);
            })
            ->get();

        ShopUserChatsRepository::query()
            ->where('user_id', $request->user_id)
            ->where('shop_id', $shop->id)
            ->where('type', 'user')
            ->update(['is_seen' => true]);

        return $this->json('success', [
            'data' => ChatResource::collection($chats),
        ]);
    }
    public function sendMessageAdmin(Request $request)
    {
        $shop = generaleSetting('shop');

        $shopUser = ShopUserRepository::query()->where('user_id', $request->user_id)->where('shop_id', $shop->id)->first();

        if (!$shopUser) {
            return $this->json('your account is not connected with this shop', [], 404);
        }

        $chat = ShopUserChatsRepository::query()->create([
            'shop_user_id' => $shopUser->id,
            'type' => $request->type,
            'message' => $request->message,
            'shop_id' => $shop->id,
            'user_id' => $request->user_id,
        ]);

        try {
            SendMessageToUser::dispatch($request->user_id, $chat);
        } catch (\Throwable $th) {
            // dd($th);
        }

        return $this->json('message sent successfully', ['data' => ChatResource::make($chat)]);
    }
    public function unreadMessages(Request $request)
    {
        if ($request->user_id) {
            $chats = ShopUserChatsRepository::query()
                ->where('user_id', $request->user_id)
                ->where('is_seen', false)
                ->where('type', 'shop')
                ->get();
        } else {
            $chats = ShopUserChatsRepository::query()
                ->where('shop_id', $request->shop_id)
                ->where('is_seen', false)
                ->where('type', 'user')
                ->get();
        }

        return $this->json('success', [
            'unread_messages' => $chats->count(),
        ]);
    }
}
