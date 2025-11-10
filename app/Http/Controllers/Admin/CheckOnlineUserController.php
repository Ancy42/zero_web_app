<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckOnlineUserController extends Controller
{
    public function checkOnlineStatus()
    {
        if (!auth()->check()) {
            return;
        }

        $shop = generaleSetting('shop');
        $shop->update(['last_online' => now()->addMinutes(10)]);

        // Save new time in session
        Session::put('last_online', now()->addMinutes(10));
    }
}
