<?php

namespace Modules\Purchase\App\Repositories;

use Carbon\Carbon;
use App\Repositories\MediaRepository;
use Abedin\Maker\Repositories\Repository;
use Modules\Purchase\App\Models\Supplier;
use Modules\Purchase\App\Models\SupplierTransaction;

class SupplierTransactionRepository extends Repository
{

    public static function model()
    {
        return SupplierTransaction::class;
    }


    public static function storeByRequest(Supplier $supplier, string $type, float $amount, array $arrData, $request)
    {
        if (! array_key_exists('transaction_no', $arrData) || $arrData['transaction_no'] == '') {
            $tnxCount = SupplierTransaction::where('supplier_id', $supplier->id)->where('type', $type)->count();
            $arrData['transaction_no'] = 'Tnx-'.str_pad($tnxCount + 1, 6, '0', STR_PAD_LEFT);
        }

        $isPaid = false;
        if (! array_key_exists('transaction_date', $arrData)) {
            $arrData['transaction_date'] = now()->format('Y-m-d');
            $isPaid = true;
        } else {
            $arrData['transaction_date'] = Carbon::parse($arrData['transaction_date'])->format('Y-m-d');
            $isPaid = $arrData['transaction_date'] == date('Y-m-d') ? true : false;
        }

        $accountBalance = $supplier->balance;
        if ($type == 'credit') {
            $accountBalance += $amount;
            $supplier->update(['balance' => $supplier->balance + $amount]);
        } else {
            $accountBalance -= $amount;
            $supplier->update(['balance' => $accountBalance < 0 ? 0 : $accountBalance]);
        }

        $thumbnail = null;
        if ($request->hasFile('file_attachment')) {
            $thumbnail = MediaRepository::storeByRequest($request->file_attachment, 'transactionSlip');
        }

        $supplierTransaction = self::create([
            'supplier_id' => $supplier->id,
            'type' => $type,
            'amount' => $amount,
            'transaction_no' => $arrData['transaction_no'],
            'transaction_date' => $arrData['transaction_date'],
            'note' => $arrData['note'],
            'is_paid' => $isPaid,
            'title' => array_key_exists('title', $arrData) ? $arrData['title'] : '',
            'balance' => $accountBalance,
            'media_id' => $thumbnail ? $thumbnail->id : null,
        ]);

        return $supplierTransaction;
    }

    public function statusUpdate($request, SupplierTransaction $transaction)
    {
        $transaction->update([
            'is_paid' => $request->is_paid,
        ]);
        return $transaction;
    }

}
