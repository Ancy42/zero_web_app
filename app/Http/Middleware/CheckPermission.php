<?php

namespace App\Http\Middleware;

use App\Models\UserNonPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->hasRole('root')) {
            return $next($request);
        }

        if ($request->is('shop/*', 'shop') && $user->hasRole('shop')) {
            return $next($request);
        }
        if ($user->hasRole('shop') && $request->is('admin/laravel-filemanager*')) {
            return $next($request);
        }


        $userRole = $user->getRoleNames()->toArray()[0];

        $role = Cache::remember('role_'.$userRole, 60 * 24 * 60, function () use ($userRole) {
            return Role::where('name', $userRole)->first();
        });

        $rolePermissions = Cache::remember('role_permissions_'.$role->id, 60 * 24 * 30, function () use ($role) {
            return $role->getPermissionNames()->toArray();
        });

        $userPermissions = Cache::remember('user_permissions_'.$user->id, 60 * 24 * 30, function () use ($user) {
            return $user->getPermissionNames()->toArray();
        });

        $userNonPermissions = Cache::remember('user_non_permissions_'.$user->id, 60 * 24 * 30, function () use ($user) {
            return UserNonPermission::where('user_id', $user->id)->pluck('name')->toArray();
        });

        $customPermissions = [
            'admin.dashboard.index',
            'shop.dashboard.index',
            'admin.new.notification',
            'admin.logout',
            'shop.logout',
            'shop.pos.invoice',
            'shop.pos.product',
            'shop.pos.addToCart',
            'shop.pos.getCart',
            'shop.pos.updateCart',
            'shop.pos.removeCart',
            'shop.pos.applyCoupon',
            'shop.pos.removeCoupon',
            'shop.pos.submitOrder',
            'shop.pos.customerStore',
        ];

        if (in_array('admin.business-setting.update', $rolePermissions)) {
            $customPermissions[] = 'admin.business-setting.shop';
            $customPermissions[] = 'admin.business-setting.withdraw';
        }

        $allPermissions = array_merge($userPermissions, $rolePermissions, $customPermissions);
        $allPermissions = array_unique($allPermissions);

        $allPermissions = array_diff($allPermissions, $userNonPermissions);

        $requestName = $request->route()->getName();

        if (! in_array($requestName, $allPermissions)) {
            if (str_ends_with($requestName, '.store')) {
                $requestName = str_replace('.store', '.create', $requestName);
            } elseif (str_ends_with($requestName, '.update')) {
                $requestName = str_replace('.update', '.edit', $requestName);
            }

            if (str_ends_with($requestName, '.gallery.create')) {
                $requestName = str_replace('.gallery.create', '.gallery.store', $requestName);
            }
        }

        if (! in_array($requestName, $allPermissions)) {
            if ($request->expectsJson() || $request->ajax() || str_starts_with($requestName, 'unisharp.lfm')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have permission to perform this action.'
                ], 403);
            }
            return redirect()->back();
        }


        if (in_array($requestName, $allPermissions)) {
            return $next($request);
        }

        return abort(403);
    }
}
