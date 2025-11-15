@extends('layouts.app')
@section('content')
    <form action="">
        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-start align-items-center  gap-5 mb-2">
                <div>
                    <label for="" class="form-label mb-1">{{ __('Choose Product') }}</label> <br>
                    <select name="product_id" class="form-select select2" width="200px">
                        <option value="" selected>{{ __('Select Product') }}</option>
                        @foreach ($products ?? [] as $product)
                            <option value="{{ $product?->id }}"
                                {{ request('product_id') == $product?->id ? 'selected' : '' }}>
                                {{ $product?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="purchase_id" class="form-label mb-1">{{ __('Choose Purchase') }}</label> <br>
                    <select name="purchase_id" class="form-select select2">
                        <option value="" selected>{{ __('Select Purchase') }}</option>
                        @foreach ($allPurchases ?? [] as $purchase)
                            <option value="{{ $purchase?->id }}"
                                {{ request('purchase_id') == $purchase?->id ? 'selected' : '' }}>
                                {{ $purchase?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="supplier_id" class="form-label mb-1">{{ __('Choose Supplier') }}</label> <br>
                    <select name="supplier_id" class="form-select select2">
                        <option value="" selected>{{ __('Select Supplier') }}</option>
                        @foreach ($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier?->id }}"
                                {{ request('supplier_id') == $supplier?->id ? 'selected' : '' }}>
                                {{ $supplier?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="d-flex gap-2 ">
                        <x-input type="text" id="datePicker" label="From" name="from" :value="$from"
                            placeholder="mm/dd/yyyy" />

                        <x-input type="text" id="datePicker2" label="To" name="to" :value="$to"
                            placeholder="mm/dd/yyyy" />
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i>{{ __('Filter') }}</button>
                    <a href="{{ route('shop.purchase.summary') }}" class="btn btn-dark"><i class="fa fa-refresh"></i></a>
                </div>

            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-body" id="printableArea">
            <table class="table table-bordered disableAnimate transaction-table mt-3">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">{{ __('SN') }}</th>
                        <th rowspan="2">{{ __('Purchase Name') }}</th>
                        <th rowspan="2">{{ __('Products') }}</th>
                        <th class="text-center" rowspan="2">{{ __('Price') }}</th>
                        <th colspan="3" class="text-center">{{ __('Total Stock Add') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $key => $purchase)
                        @php
                            $productId = request('product_id');
                            $purchaseProducts = $purchase
                                ->purchaseProducts()
                                ->when($productId, function ($q) use ($productId) {
                                    $q->where('product_id', $productId);
                                })
                                ->get();
                        @endphp
                        <tr>
                            <td rowspan="{{ count($purchaseProducts) + 1 }}">{{ $key + 1 }}</td>
                            <td rowspan="{{ count($purchaseProducts) + 1 }}">
                                {{ $purchase->name }}
                                <br>
                                <small class="text-muted">{{ $purchase->supplier?->name }}</small>
                            </td>
                        </tr>

                        @foreach ($purchaseProducts as $lopProduct)
                            @php
                                $price =
                                    $lopProduct->price > 0
                                        ? $lopProduct->price
                                        : $lopProduct->productSkus()->first()?->price;
                                $stockOut = $lopProduct->productSkus()->where('in_stock', 0)->count();
                                $available = $lopProduct->quantity - $stockOut;
                            @endphp

                            <tr>
                                <td>{{ $lopProduct->product->name }}</td>
                                <td>{{ $price }}</td>
                                <td class="text-center">{{ $lopProduct->quantity }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right fw-bold">{{ __('Total') }}</td>
                        <td class="text-center fw-bold">{{ $totalIn }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
@endsection

@push('css')
    <style>
        .table td {
            border-bottom: 1px solid #000 !important;
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
            border-top: 1px solid #000 !important;
        }

        .text-small {
            font-size: 13px;
        }

        .text-right {
            text-align: right;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/scripts/jQuery.print.min.js') }}"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            $("#datePicker").datepicker({
                showOtherMonths: true,
                selectOtherMonths: true
            });

            $("#datePicker2").datepicker({
                showOtherMonths: true,
                selectOtherMonths: true
            });
        });
    </script>
@endpush
