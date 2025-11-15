@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>{{ __('Purchase Return List') }}</h4>

        @hasPermission('shop.purchaseReturn.create')
            <a href="{{ route('shop.purchaseReturn.create') }}" class="btn py-2 btn-primary">
                <i class="bi bi-patch-plus"></i>
                {{ __('Return Create') }}
            </a>
        @endhasPermission
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}.</th>
                                <th>{{ __('Supplier') }}</th>
                                <th>{{ __('Purchase Invoice') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th class="text-center">{{ __('Total Product') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($purchaseReturns as $purchaseReturn)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $purchaseReturn?->purchase?->supplier?->name ?? '--' }} <br>
                                    <small class="text-muted">{{ $purchaseReturn->purchase->name ?? '--' }}</small>
                                </td>
                                <td>
                                    {{ $purchaseReturn?->purchase?->purchase_code }}
                                </td>
                                <td>
                                    {{ showCurrency($purchaseReturn->total_amount) }}
                                </td>
                                <td class="text-center">
                                    {{ $purchaseReturn->total_product }}
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill {{ $purchaseReturn->is_returned ? 'bg-success' : 'bg-warning' }}">
                                        {{ $purchaseReturn->is_returned ? 'Received' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $purchaseReturn->created_at->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <a href="{{ route('shop.purchaseReturn.Invoice', $purchaseReturn->id) }}"
                                            target="_blank" class="circleIcon btn-outline-primary">
                                            <img src="{{ asset('assets/icons-admin/download-alt.svg') }}" alt="icon"
                                                loading="lazy" width="20" />
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
            {{ $purchaseReturns->withQueryString()->links() }}
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        "use strict";
        $('#supplier').on('change', function() {
            let supplier = $(this).val();
            window.location.href = `{{ route('shop.purchaseReturn.index') }}?supplier=${supplier}`;
        });
    </script>
@endpush
