<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\User;
use App\Models\Wallet;

class WalletRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Wallet::class;
    }

    /**
     * wallet store by request
     */
    public static function storeByRequest(User $user): Wallet
    {
        return self::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
    }

    /**
     * wallet update by request
     *
     * @param  float  $balance
     * @param  string  $type  (credit or debit)
     */
    public static function updateByRequest(Wallet $wallet, $balance, $type): Wallet
    {
        // ballance increase or decrease
        $ballance = $type == 'credit' ? $wallet->balance + $balance : $wallet->balance - $balance;

        $wallet->update([
            'balance' => $ballance,
        ]);

        return $wallet;
    }

    public static function getAdminWallet(): Wallet
    {
        $role = 'root';

        $user = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->first();

        return $user->wallet;
    }
}
