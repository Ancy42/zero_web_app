<?php

namespace App\Providers;

use App\Models\UserNonPermission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Check if the user has any of the required permissions
         *
         * @param  string|array  $permissions
         * @return bool
         *
         * @throws \Exception
         */
        Blade::if('hasPermission', function ($permissions) {
            /** @var \app\Models\User $user */
            $user = auth()->user();

            if (! $user) {
                return false;
            }

            if ($user->hasRole('root') || $user->hasRole('shop')) {
                return true;
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

            $allPermissions = array_merge($userPermissions, $rolePermissions);
            $allPermissions = array_unique($allPermissions);

            $allPermissions = array_diff($allPermissions, $userNonPermissions);

            // check is not array
            if (! is_array($permissions)) {
                $permissions = [$permissions];
            }

            // check has any permission
            foreach ((array) $permissions as $permission) {
                $permissionsToCheck = [
                    $permission,
                    str_replace('.create', '.store', $permission),
                    str_replace('.update', '.edit', $permission),
                ];

                if (count(array_intersect($permissionsToCheck, $allPermissions)) > 0) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
