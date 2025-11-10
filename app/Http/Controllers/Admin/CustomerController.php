<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ShopPasswordResetRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::role(Roles::CUSTOMER->value)->latest('id')->with('media')->paginate(20);

        return view('admin.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customer.create');
    }

    public function store(RegistrationRequest $request)
    {
        // Create a new user
        $user = UserRepository::registerNewUser($request);

        // Create a new customer
        CustomerRepository::storeByRequest($user);

        // create wallet
        WalletRepository::storeByRequest($user);

        $user->assignRole(Roles::CUSTOMER->value);

        return to_route('admin.customer.index')->withSuccess(__('Created successfully'));
    }

    public function edit(User $user)
    {
        return view('admin.customer.edit', compact('user'));
    }

    public function update(User $user, UserRequest $request)
    {
        UserRepository::updateByRequest($request, $user);

        return to_route('admin.customer.index')->withSuccess(__('Updated successfully'));
    }

    public function destroy(User $user)
    {
        $media = $user->media;

        if ($media && Storage::exists($media->src)) {
            Storage::delete($media->src);
        }

        $user->wallet()?->delete();
        $user->syncPermissions([]);
        $user->syncRoles([]);

        $delTime = now()->format('YmdHis');

        $user->update([
            'phone' => $user->phone.'_deleted:'.$delTime,
            'email' => $user->email.'_deleted:'.$delTime,
            'deleted_at' => now(),
        ]);

        $media?->delete();

        return back()->withSuccess(__('Deleted successfully'));
    }

    public function resetPassword(User $user, ShopPasswordResetRequest $request)
    {
        // Update the user password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->withSuccess(__('Password updated successfully'));
    }
}
