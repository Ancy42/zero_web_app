<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\GoogleReCaptcha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Show the application login page.
     */
    public function index()
    {
        $GoogleReCaptcha = GoogleReCaptcha::first();

        return view('admin.auth.login', compact('GoogleReCaptcha'));
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(AdminLoginRequest $request)
    {
        // check credentials
        $user = $this->checkCredentials($request);

        try {
            $roles = $user?->roles?->pluck('name')->toArray() ?? [];
        } catch (\Throwable $th) {
            $roles = [];
        }

        // check user is active
        if ($user && $user->is_active && ! in_array('customer', $roles)) {

            // login the user
            Auth::login($user);

            $shop = generaleSetting('shop', $user);

            if (! $shop) {
                return to_route('admin.dashboard.index')->withSuccess('Login successfully');
            }

            $rootShop = generaleSetting('rootShop');

            if ($shop->id == $rootShop->id) {
                return to_route('admin.dashboard.index')->withSuccess('Login successfully');
            }

            $generalSetting = generaleSetting('setting');

            if ($generalSetting?->business_based_on != 'subscription' && $shop) {
                return to_route('shop.dashboard.index')->withSuccess('Login successfully');
            }

            $subscription = $shop->currentSubscription;

            if (! $subscription) {
                return to_route('shop.subscription.index')->withSuccess('Login successfully');
            }

            if ($subscription->ends_at && $subscription->ends_at->lt(now())) {
                return to_route('shop.subscription.index')->withSuccess('Login successfully');
            }

            // redirect to dashboard
            return to_route('admin.dashboard.index')->withSuccess('Login successfully');
        }

        // redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials is invalid.',
            'password' => 'The provided credentials is invalid.',
        ]);
    }

    /**
     * Check user exists or not and check password.
     */
    private function checkCredentials(AdminLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return false;
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = auth()->user();

        // logout
        auth()->logout();
        session()->forget('current_shop_id');

        // invalidate session
        $request->session()->invalidate();

        // regenerate session
        $request->session()->regenerateToken();

        $cacheKeys = [
            'admin_all_orders',
            'shop_all_orders',
        ];

        foreach (OrderStatus::cases() as $status) {
            $cacheKeys[] = 'admin_status_'.Str::camel($status->value);
            $cacheKeys[] = 'shop_status_'.Str::camel($status->value);
        }

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        Cache::forget('user_permissions_'.$user->id);
        Cache::forget('user_non_permissions_'.$user->id);

        return to_route('admin.login')->withSuccess('Logout successfully');
    }
}
