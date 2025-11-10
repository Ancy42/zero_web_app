<?php

namespace App\Http\Controllers\API;

use App\Events\SendMessageToShop;
use App\Events\SendMessageToUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ShopUserResource;
use App\Http\Resources\UserResource;
use App\Models\Shop;
use App\Models\ShopUserChats;
use App\Models\User;
use App\Repositories\ShopUserChatsRepository;
use App\Repositories\ShopUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MessageBird\Objects\Conversation\SendMessage;
use Twilio\Rest\Chat;

class ChatController extends Controller
{
    public function index()
    {
        $shop = generaleSetting('shop');
        return view('shop.chat.index', compact('shop'));
    }

    public function storeMessage(Request $request)
    {
        $storeShopUser = ShopUserRepository::query()->updateOrCreate(
            [
                'shop_id' => $request->shop_id,
                'user_id' => $request->user_id,
            ],
            [
                'product_id' => $request->product_id ?? null,
            ]
        );

        if ($storeShopUser->product_id) {
            $chat =  ShopUserChatsRepository::query()->create([
                'shop_user_id' => $storeShopUser->id,
                'type' => $request->type,
                'message' => $request->message,
                'product_id' => $storeShopUser->product_id,
                'shop_id' => $storeShopUser->shop_id,
                'user_id' => $storeShopUser->user_id,
            ]);

            try {
                SendMessageToShop::dispatch($storeShopUser->shop_id, $storeShopUser->user_id, $chat);
            } catch (\Throwable $th) {
                dd($th);
            }
        }

        return $this->json('success', []);
    }

    public function getShops(Request $request)
    {
        $auth = Auth::guard('api')->user();
        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $shops = ShopUserRepository::query()
            ->where('user_id', $auth->id)
            ->with('latestMessage')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('shop', function ($query) use ($search) {
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
            'total' => $shops->count(),
            'data' => ShopUserResource::collection($shops ?? []),
        ]);
    }
    public function getUsers(Request $request)
    {
        $shop = generaleSetting('shop');

        // $users = ShopUserRepository::query()->where('shop_id', $shop->id)->latest('id')->get();
        $users = ShopUserRepository::query()
            ->where('shop_id', $shop->id)
            ->with('latestMessage')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
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

    public function getMessage(Request $request)
    {
        $auth = Auth::guard('api')->user();

        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $chats = ShopUserChatsRepository::query()
            ->where('user_id', $auth->id)
            ->where('shop_id', $request->shop_id)
            ->orderBy('id', 'desc')
            ->when($skip, function ($query) use ($skip) {
                return $query->skip($skip);
            })->when($perPage, function ($query) use ($perPage) {
                return $query->take($perPage);
            })
            ->get();

        ShopUserChatsRepository::query()
            ->where('user_id', $auth->id)
            ->where('shop_id', $request->shop_id)
            ->where('type', 'shop')
            ->update(['is_seen' => true]);

        return $this->json('success', [
            'data' => ChatResource::collection($chats),
        ]);
    }
    public function getMessageAdmin(Request $request)
    {
        $shop = generaleSetting('shop');
        $chats = ShopUserChatsRepository::query()
            ->where('user_id', $request->user_id)
            ->where('shop_id', $shop->id)
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

    public function sendMessage(Request $request)
    {
        $auth = Auth::guard('api')->user();

        $shopUser = ShopUserRepository::query()->where('user_id', $auth->id)->where('shop_id', $request->shop_id)->first();

        if (!$shopUser) {
            return $this->json('your account is not connected with this shop', [], 404);
        }

        $chat = ShopUserChatsRepository::query()->create([
            'shop_user_id' => $shopUser->id,
            'type' => $request->type,
            'message' => $request->message,
            'shop_id' => $request->shop_id,
            'user_id' => $auth?->id,
        ]);

        try {
            SendMessageToShop::dispatch($request->shop_id, $auth->id, $chat);
        } catch (\Throwable $th) {
            dd($th);
        }

        return $this->json('message sent successfully', ['data' => ChatResource::make($chat)]);
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
