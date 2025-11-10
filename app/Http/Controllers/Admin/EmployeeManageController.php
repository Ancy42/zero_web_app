<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopPasswordResetRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserNonPermission;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class EmployeeManageController extends Controller
{
    public function index()
    {
        $notNeedRoles = ['shop', 'customer', 'driver'];

        $users = User::whereHas('roles', function ($q) use ($notNeedRoles) {
            $q->whereNotIn('name', $notNeedRoles);
        })->whereNull('shop_id')->with('roles')->paginate(20);

        return view('admin.employee.index', compact('users'));
    }

    public function create()
    {
        $notNeedRoles = ['shop', 'customer', 'driver'];

        $roles = Role::whereNotIn('name', $notNeedRoles)->get();

        return view('admin.employee.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            return back()->withError(__('Phone number already exists'));
        }

        $request['is_active'] = true;
        $user = UserRepository::storeByRequest($request);

        $user->assignRole($request->role);

        WalletRepository::storeByRequest($user);

        return to_route('admin.employee.index')->withSuccess(__('Created successfully'));
    }

    public function resetPassword(User $user, ShopPasswordResetRequest $request)
    {
        // Update the user password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->withSuccess(__('Updated successfully'));
    }

    public function destroy(User $user)
    {
        $user->syncRoles([]);
        $user->syncPermissions([]);

        $media = $user->media;

        if ($media && Storage::exists($media->src)) {
            Storage::delete($media->src);
        }

        $user->wallet()?->delete();
        $user->forceDelete();

        if ($media) {
            $media->delete();
        }

        return back()->withSuccess(__('Deleted successfully'));
    }

    public function permission(User $user)
    {
        $generaleSetting = generaleSetting();

        $userRole = $user->getRoleNames()->toArray()[0];

        $role = Role::where('name', $userRole)->first();

        $rolePermissions = $role->getPermissionNames()->toArray();
        $userPermissions = $user->getPermissionNames()->toArray();

        $userNonPermissions = UserNonPermission::where('user_id', $user->id)->pluck('name')->toArray();

        $allPermissions = array_merge($userPermissions, $rolePermissions);
        $allPermissions = array_unique($allPermissions);

        $allPermissionArray = [];

        if ($generaleSetting?->shop_type == 'single') {
            $allPermissionArray['shop'] = config('acl.permissions.shop');
            $allPermissionArray['admin'] = config('acl.permissions.admin');
        } else {
            $allPermissionArray['adminMultiShop'] = config('acl.permissions.adminMultiShop');
            $allPermissionArray['shop'] = config('acl.permissions.shop');
            $allPermissionArray['admin'] = config('acl.permissions.admin');
        }

        if (!module_exists('purchase')) {
            unset(
                $allPermissionArray['shop']['supplier'],
                $allPermissionArray['shop']['purchase'],
                $allPermissionArray['shop']['purchaseReturn']
            );
        }

        $userAvailablePermissions = array_diff($allPermissions, $userNonPermissions);

        return view('admin.employee.permission', compact('user', 'role', 'allPermissionArray', 'userAvailablePermissions'));
    }

    public function updatePermission(User $user, Request $request)
    {
        $permissions = $request->permissions ?? [];

        $role = Role::where('id', $request->role_id)->first();
        $rolePermissions = $role->getPermissionNames()->toArray();

        $customPermissions = [];
        $removePermissions = [];

        foreach ($permissions as $permission) {
            if (! in_array($permission, $rolePermissions)) {
                $customPermissions[] = $permission;
            }
        }

        foreach ($rolePermissions ?? [] as $permission) {
            if (! in_array($permission, $permissions)) {
                $removePermissions[] = $permission;
            }
        }

        try {
            $user->syncPermissions($customPermissions);
        } catch (\Throwable $e) {
            return back()->withError($e->getMessage());
        }

        UserNonPermission::where('user_id', $user->id)->delete();

        foreach ($removePermissions as $permission) {
            UserNonPermission::create([
                'user_id' => $user->id,
                'name' => $permission,
            ]);
        }

        Cache::forget('user_permissions_'.$user->id);
        Cache::forget('user_non_permissions_'.$user->id);

        return to_route('admin.employee.index')->withSuccess(__('Permission Updated Successfully'));
    }
}
