<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $notNeedRoles = ['shop', 'customer', 'driver'];

        $roles = Role::whereNotIn('name', $notNeedRoles)->with('permissions')->get();

        return view('admin.role-permission.index', compact('roles'));
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'is_shop' => $request->for_shop ? true : false,
        ]);

        return $this->json(__('Created Successfully'), [
            'role' => $role,
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update([
            'name' => $request->name,
            'is_shop' => $request->for_shop ? true : false,
        ]);

        return back()->withSuccess(__('Updated Successfully'));
    }

    public function destroy(Role $role)
    {
        if ($role->name == 'root') {
            return back()->withError(__('You can not delete root role'));
        }

        $role->syncPermissions([]);

        $users = $role->users;

        foreach ($users as $user) {
            $user->syncRoles([]);
            $user->syncPermissions([]);

            $media = $user->media;

            if ($media && Storage::exists($media->src)) {
                Storage::delete($media->src);
            }

            $user->wallet()?->delete();

            $user->tokens()->delete();

            $user->forceDelete();

            if ($media) {
                $media->delete();
            }
        }

        $role->delete();

        return to_route('admin.role.index')->withSuccess(__('Deleted Successfully'));
    }

    public function rolePermission(Role $role)
    {
        $generaleSetting = generaleSetting('setting');

        $allPermissionArray = [];

        if ($generaleSetting?->shop_type == 'single') {
            if ($role->is_shop) {
                $allPermissionArray['shop'] = config('acl.permissions.shop');
            } else {
                $allPermissionArray['shop'] = config('acl.permissions.shop');
                $allPermissionArray['admin'] = config('acl.permissions.admin');
            }
        } else {
            if ($role->is_shop) {
                // for multi shop role
                $allPermissionArray['shop'] = config('acl.permissions.shopMultiShop');
                $allPermissionArray['shop'] = config('acl.permissions.shop');
            } else {
                $allPermissionArray['adminMultiShop'] = config('acl.permissions.adminMultiShop');
                $allPermissionArray['shop'] = config('acl.permissions.shop');
                $allPermissionArray['admin'] = config('acl.permissions.admin');
            }
        }

        if (!module_exists('purchase')) {
            unset(
                $allPermissionArray['shop']['supplier'],
                $allPermissionArray['shop']['purchase'],
                $allPermissionArray['shop']['purchaseReturn']
            );
        }

        $notNeedRoles = ['shop', 'customer', 'driver'];
        $rolesQuery = Role::whereNotIn('name', $notNeedRoles)->with('permissions');

        $firstItem = request('fst');
        if ($firstItem) {
            $rolesQuery->orderByRaw('id = ? DESC', [$firstItem]);
        }
        $roles = $rolesQuery->get();

        $permissions = $role->permissions->pluck('name')->toArray();

        $activeRole = $role->name;
        $selectedRole = $role;

        return view('admin.role-permission.index', compact('selectedRole', 'permissions', 'roles', 'activeRole', 'allPermissionArray'));
    }

    public function updateRolePermission(Request $request, Role $role)
    {
        try {
            $role->syncPermissions($request->permissions ?? []);
        } catch (\Throwable $th) {
            return back()->with('alertError', [
                'message' => $th->getMessage(),
                'message2' => 'Please run PermissionSeeder and try again. Run "php artisan db:seed PermissionSeeder"',
            ]);
        }

        Cache::forget('role_permissions_' . $role->id);

        return back()->withSuccess(__('Permission Updated Successfully'));
    }
}
