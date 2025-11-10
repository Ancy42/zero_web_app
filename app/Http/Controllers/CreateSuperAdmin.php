<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateSuperAdmin extends Controller
{
    public function index()
    {
        return view('create-root');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $rootUser = User::factory()->create([
            'name' => 'Super Admin',
            'email' => $request->email,
            'phone' => '01000000001',
            'password' => bcrypt($request->password),
            'is_active' => true,
        ]);

        $rootUser->assignRole(Roles::ROOT->value);

        Shop::factory()->create([
            'user_id' => $rootUser->id,
            'name' => 'My Shop',
            'delivery_charge' => 0,
            'description' => 'My Shop Description',
            'status' => true,
            'shop_logo' => 'default/vendors/logo/My-shop.png',
            'shop_banner' => 'default/vendors/banner/My-Shop.jpg',
            'min_order_amount' => 1,
        ]);

        $rootUser->assignRole(Roles::SHOP->value);

        // Redirect to the dashboard or any other page
        return redirect()->route('admin.login')->with('success', 'You are ready to use ReadyPOS! Please login with your credentials.');
    }
}
