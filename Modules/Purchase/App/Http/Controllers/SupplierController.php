<?php

namespace Modules\Purchase\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Purchase\App\Models\Supplier;
use Modules\Purchase\App\Repositories\SupplierTransactionRepository;
use Modules\Purchase\App\Http\Requests\SupplierRequest;
use Modules\Purchase\App\Repositories\SupplierRepository;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shop = generaleSetting('shop');
        $search = request()->search;
        $suppliers = SupplierRepository::query()->where('shop_id', $shop->id)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            })->latest('id')->paginate(20);
        return view('purchase::supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchase::supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $supplier = SupplierRepository::storeByRequest($request);
        if ($request->type && $request->type == 'ajax') {
            return response()->json([
                'message' => __('Supplier created successfully'),
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                ]
            ]);
        }
        return to_route('shop.supplier.index')->withSuccess(__('Supplier created successfully'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $query = $supplier->transactions();

        $totalBalance = (clone $query)->where('type', 'credit')->sum('amount');
        $paidAmount = (clone $query)->where('type', 'debit')->sum('amount');

        $dueAmount = $totalBalance - $paidAmount;

        $purchases = $supplier->purchases()->latest('id')->limit(5)->get();

        $transactions = $supplier->transactions()->latest()->paginate(10);

        return view('purchase::supplier.show', compact('totalBalance', 'supplier', 'paidAmount', 'dueAmount', 'purchases', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('purchase::supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        SupplierRepository::updateByRequest($request, $supplier);
        return to_route('shop.supplier.index')->withSuccess(__('Supplier updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return to_route('shop.supplier.index')->withSuccess(__('Supplier deleted successfully'));
    }

    public function statusToggle(Supplier $supplier)
    {
        $supplier->update(['is_active' => ! $supplier->is_active]);

        return back()->withSuccess(__('Updated successfully'));
    }

    public function getStatistic(Supplier $supplier)
    {
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();

        $monthWiseData = [];

        for ($date = $startDate; $date->lte($endDate); $date->addMonth()) {
            $query = $supplier->purchases()->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year);
            $monthWiseData[] = [
                'date' => $date->toDateString(),
                'label' => $date->format('M'),
                'purchase' => $query->count(),
                'amount' => $query->sum('total_amount'),
                'item' => (int) $query->sum('total_product'),
            ];
        }

        $purchases = array_column($monthWiseData, 'purchase');
        $items = array_column($monthWiseData, 'item');

        return $this->json('current year purchase statistics', [
            'purchases' => $purchases,
            'items' => $items,
        ]);
    }

    public function makePayment(Supplier $supplier, Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'nullable|string|unique:supplier_transactions,transaction_no',
            'file_attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'transaction_date' => now()->format('Y-m-d'),
            'note' => $request->message,
            'title' => 'Make Supplier Payment',
            'transaction_no' => $request->transaction_id,
        ];

        SupplierTransactionRepository::storeByRequest($supplier, 'debit', $request->amount, $data, $request);

        return back()->withSuccess(__('Updated successfully'));
    }
}
