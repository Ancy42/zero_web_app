<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\DeviceKey;

class DeviceKeyRepository extends Repository
{
    public static function model()
    {
        return DeviceKey::class;
    }

    public static function findByKey($key)
    {
        return self::query()->where('key', $key)->first();
    }

    public static function storeByRequest($user, $request)
    {
        if ($request->device_key && $user->id) {
            return self::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'key' => $request->device_key,
                ],
                [
                    'device_type' => $request->device_type ?? 'android',
                ]
            );
        }

        return null;
    }

    public static function destroy($key): bool
    {
        $exists = self::findByKey($key);

        if ($exists) {
            $exists->delete();

            return true;
        }

        return false;
    }

    public static function deleteByKey($key, $user)
    {
        self::query()->where('key', $key)->where('user_id', $user->id)->delete();
    }
}
