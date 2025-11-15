@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>{{ __('All') }} {!! config('purchase.name') !!}</h4>

        @hasPermission('shop.purchase.create')
            <a href="{{ route('shop.purchase.create') }}" class="btn py-2 btn-primary">
                <i class="bi bi-patch-plus"></i>
                {{ __('Create New') }}
            </a>
        @endhasPermission
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="mb-3 w-280">
                    <select id="supplier" class="form-select">
                        <option value="">{{ __('All Supplier') }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table border table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}.</th>
                                <th>{{ __('Supplier') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th class="text-center">{{ __('Quantity') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $purchase->supplier->name ?? '--' }} <br>
                                        <small class="text-muted">{{ $purchase->name }}</small>
                                    </td>
                                    <td>
                                        {{ showCurrency($purchase->total_amount) }}
                                        @if ($purchase->paid_amount > 0)
                                            <br>
                                            <small class="text-success">
                                                -{{ showCurrency($purchase->paid_amount) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $purchase->total_product }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill {{ $purchase->is_received ? 'bg-success' : 'bg-warning' }}">
                                            {{ $purchase->is_received ? 'Received' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $purchase->receive_date }} <br>
                                        <small class="text-muted">
                                             {{ __('Created At') }}: {{ $purchase->created_at->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-2 justify-content-center">

                                            <a href="{{ route('shop.purchase.show', $purchase->id) }}"
                                                class="circleIcon btn-outline-primary">
                                                <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="">
                                            </a>
                                            @hasPermission('shop.purchase.edit')
                                                <a href="{{ route('shop.purchase.edit', $purchase->id) }}"
                                                    class="btn btn-outline-info btn-sm circleIcon">
                                                    <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                        loading="lazy" />
                                                </a>
                                            @endhasPermission

                                            @hasPermission('shop.purchase.destroy')
                                                @if ($purchase->purchaseProducts()->count() == 0)
                                                    <a href="{{ route('shop.purchase.destroy', $purchase->id) }}"
                                                        class="circleIcon btn-outline-danger deleteConfirm">
                                                        <img src="{{ asset('assets/icons-admin/trash.svg') }}"
                                                            alt="delete" />
                                                    </a>
                                                @endif
                                            @endhasPermission

                                            <a href="{{ route('shop.purchase.purchaseInvoice', $purchase->id) }}"
                                                target="_blank" class="circleIcon btn-outline-secondary">
                                                <img src="{{ asset('assets/icons-admin/download-alt.svg') }}"
                                                    alt="icon" loading="lazy" width="20" />
                                            </a>

                                        </div>
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $purchases->withQueryString()->links() }}
        </div>

    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush
@push('scripts')
    <script>
        $('#supplier').on('change', function() {
            let supplier = $(this).val();
            window.location.href = `{{ route('shop.purchase.index') }}?supplier=${supplier}`;
        });
    </script>
@endpush
