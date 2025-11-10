<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\WithdrawRequest;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return Withdraw::class;
    }

    /**
     * store new withdraw
     */
    public static function storeByRequest(WithdrawRequest $request): Withdraw
    {
        $shop = generaleSetting('shop');

        return self::create([
            'shop_id' => $shop->id,
            'amount' => $request->amount,
            'name' => $request->name ?? auth()->user()->fullName,
            'contact_number' => $request->contact_number ?? auth()->user()->phone,
            'reason' => $request->message,
        ]);
    }

    /**
     * update withdraw
     */
    public static function updateWithdraw(Withdraw $withdraw, Request $request): Withdraw
    {
        $withdraw->update([
            'status' => $request->status,
            'reason' => $request->reason ?? $withdraw->reason,
        ]);

        if ($request->status == 'approved') {
            WalletRepository::updateByRequest($withdraw->shop->user->wallet, $withdraw->amount, 'debit');
        }

        return $withdraw;
    }
}
