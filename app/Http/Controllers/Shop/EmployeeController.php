<?php

namespace App\Http\Controllers\Shop;

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

class EmployeeController extends Controller
{
    public function index()
    {
        $shop = generaleSetting('shop');

        $notNeedRoles = ['shop', 'customer', 'driver'];

        $users = User::whereHas('roles', function ($q) use ($notNeedRoles) {
            $q->whereNotIn('name', $notNeedRoles);
        })->where('shop_id', $shop->id)->with('roles')->paginate(20);

        return view('shop.employee.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('is_shop', 1)->get();

        return view('shop.employee.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $shop = generaleSetting('shop');
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            return back()->withError(__('Phone number already exists'));
        }

        $request['is_active'] = true;
        $request['shop_id'] = $shop->id;
        $user = UserRepository::storeByRequest($request);

        $user->assignRole($request->role);

        WalletRepository::storeByRequest($user);

        return to_route('shop.employee.index')->withSuccess(__('Created successfully'));
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

        $allPermissionArray['shop'] = config('acl.permissions.shop');
        $allPermissionArray['shop']['withdraw'] = config('acl.permissions.shopMultiShop.withdraw');
        $allPermissionArray['shop']['dashboard'] = config('acl.permissions.shopMultiShop.dashboard');
        $allPermissionArray['shop']['brand'] = config('acl.permissions.shopMultiShop.brand');
        $allPermissionArray['shop']['category'] = config('acl.permissions.shopMultiShop.category');
        $allPermissionArray['shop']['withdraw'] = config('acl.permissions.shopMultiShop.withdraw');
        if (!module_exists('purchase')) {
            unset(
                $allPermissionArray['shop']['supplier'],
                $allPermissionArray['shop']['purchase'],
                $allPermissionArray['shop']['purchaseReturn']
            );
        }

        $userAvailablePermissions = array_diff($allPermissions, $userNonPermissions);

        return view('shop.employee.permission', compact('user', 'role', 'allPermissionArray', 'userAvailablePermissions'));
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
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage());
        }

        UserNonPermission::where('user_id', $user->id)->delete();

        foreach ($removePermissions as $permission) {
            UserNonPermission::create([
                'user_id' => $user->id,
                'name' => $permission,
            ]);
        }

        Cache::forget('user_permissions_' . $user->id);
        Cache::forget('user_non_permissions_' . $user->id);

        return to_route('shop.employee.index')->withSuccess(__('Permission Updated Successfully'));
    }
}
