<?php

namespace Modules\Purchase\App\Repositories;

use App\Enums\Roles;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\MediaRepository;
use Abedin\Maker\Repositories\Repository;
use Modules\Purchase\App\Models\Supplier;

class SupplierRepository extends Repository
{
    public static function model()
    {
        return Supplier::class;
    }

    public static function storeByRequest(Request $request)
    {
        $shop = generaleSetting('shop');
        $user = UserRepository::storeByRequest($request);
        $user->assignRole(Roles::SUPPLIER->value);

        $thumbnail = null;

        if ($request->hasFile('profile_photo')) {
            $thumbnail = MediaRepository::storeByRequest($request->profile_photo, 'supplier');
        }

        return self::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'email' => $request->email,
            'phone' => $request->phone,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'address' => $request->address,
            'shop_id' => $shop?->id,
            'is_active' => true,
        ]);
    }

    /**
     * Update user by request.
     *
     * @param  $request  The user request
     * @param  mixed  $user  The user
     */
    public static function updateByRequest(Request $request, Supplier $supplier)
    {
        $thumbnail = self::updateProfilePhoto($request, $supplier);

        $supplier->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'address' => $request->address,
        ]);

        return $supplier;
    }

    /**
     * Update the supplier's profile photo.
     */
    private static function updateProfilePhoto($request, $supplier)
    {
        $thumbnail = $supplier->media;

        if ($request->hasFile('profile_photo') && $thumbnail == null) {
            $thumbnail = MediaRepository::storeByRequest($request->profile_photo, 'supplier');
        }

        if ($request->hasFile('profile_photo') && $thumbnail) {
            $thumbnail = MediaRepository::updateByRequest($request->profile_photo, 'supplier', null, $thumbnail);
        }

        return $thumbnail;
    }
}
