<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Repositories\ShopUserChatsRepository;
use App\Repositories\ShopUserRepository;
use Illuminate\Http\Request;

class CustomerMessageController extends Controller
{
    public function index()
    {
        return view('shop.chat.index');
    }
}
