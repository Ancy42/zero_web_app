@extends('layouts.app')
@php
    $isSupplier = !auth()->user()->supplier;
@endphp
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-semibold">{{ $supplier->name }}</h4>
                            <i class="bi bi-envelope-fill me-2 text-secondary"></i>
                            <span>{{ $supplier->email }}</span>
                            <i class="bi bi-telephone-fill ms-2 me-2 text-secondary"></i>
                            <span>{{ $supplier->phone }}</span><br>
                            <i class="bi bi-geo-alt-fill me-2 text-secondary"></i>
                            <span>{{ $supplier->address }}</span>
                        </div>
                        <div class="ms-3">
                            <img class="rounded-circle" src="{{ $supplier->thumbnail }}" alt="" width="60" height="60">
                        </div>
                    </div>
                    <hr>

                    <div class="row g-4 ">

                        <div class="col-md-3">
                            <div class="wallet-others">
                                <div class="fz-18 fw-bold">{{ showCurrency($totalBalance) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Balance Amount') }}</div>
                                    <div class="icon svg-bg">
                                        <img src="{{ asset('assets/icons-admin/chart-trend-up-green.svg') }}" alt="icon"
                                            loading="lazy" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="wallet-others">
                                <div class="fz-18 fw-bold">{{ showCurrency($paidAmount) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    @if ($isSupplier)
                                        <div class="title">{{ __('Paid Amount') }}</div>
                                    @else
                                        <div class="title">{{ __('Received Amount') }}</div>
                                    @endif
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons-admin/credit-card-orange.svg') }}" alt="icon"
                                            loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="wallet-others">
                                <div class="fz-18 fw-bold">{{ showCurrency(str_replace('-', '', $dueAmount)) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ $dueAmount >= 0 ? 'Due' : 'Advance' }} {{ __('Amount') }}</div>
                                    <div class="icon svg-bg">
                                        <img src="{{ asset('assets/icons-admin/withdraw.svg') }}" alt="icon"
                                            loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="wallet-others">
                                <div class="fz-18 fw-bold"> {{ $supplier->purchases->count() }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Total Purchases') }}</div>
                                    <div class="icon svg-bg">
                                        <img src="{{ asset('assets/icons-admin/business.svg') }}" alt="icon"
                                            loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <h4 class="m-0 px-3 py-2 border-bottom fz-20">
                    {{ __('Wallet Info') }}
                </h4>
                <div class="card-body pt-0">
                    <table class="table mb-0">
                        @if ($isSupplier)
                            <tr>
                                <td class="td-180">{{ __('Pay amount to supplier') }}:</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#paymentModal">
                                        <i class="bi bi-cash-coin"></i>
                                        {{ __('Pay Now') }}
                                    </button>
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="td-180">{{ __('Total Transaction') }}:</td>
                            <td>
                                <span class="badge fz-16 bg-light text-black">
                                    {{ $supplier->transactions->count() }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


         <div class="col-xl-8 mt-3">
            <div class="card h-100 d-flex flex-column">
                <div class="card-header py-2.5 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 fz-18">
                        {{ __('Latest Purchases') }}
                    </h5>
                    <a href="{{ route('shop.purchase.index', 'supplier=' . $supplier->id) }}"
                        class="btn btn-primary btn-sm">
                        {{ __('View All') }}
                    </a>
                </div>
                <div class="card-body mb-0">
                    <div class="table-responsive">
                        <table class="table border table-responsive-lg">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('SL') }}.</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Total Products') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ showCurrency($purchase->total_amount) }}
                                        @if ($purchase->paid_amount > 0)
                                            <br>
                                            <small class="text-success">
                                                -{{ showCurrency($purchase->paid_amount) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $purchase->total_product }}</td>
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
                                                class="circleIcon btn-outline-info" target="_blank">
                                                <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="">
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
        </div>
        <div class="col-xl-4 mt-3">
            <div class="card  h-100 d-flex flex-column">
                <div class="card-header py-2.5">
                    <h5 class="card-title m-0 fs-16">
                        {{ __('Current Year Statistics') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div id="currentYearStatistics"></div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="mb-3 card">
                <div class="card-header py-2.5">
                    <h4 class="card-title m-0 fz-18"> {{ __('Transactions') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-left-right table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('SL') }}</th>
                                    <th>
                                        {{ __('Credit') }}
                                    </th>
                                    <th>
                                        {{ __('Debit') }}
                                    </th>
                                    <th>{{ __('Transaction ID') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Attachment') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            @forelse($transactions as $key => $transaction)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    @if ($isSupplier)
                                        <td>
                                            @if ($transaction->type == 'credit')
                                                +{{ showCurrency($transaction->amount) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaction->type == 'debit')
                                                -{{ showCurrency($transaction->amount) }}
                                            @endif
                                        </td>
                                    @else
                                        <td>
                                            @if ($transaction->type == 'debit')
                                                +{{ showCurrency($transaction->amount) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaction->type == 'credit')
                                                -{{ showCurrency($transaction->amount) }}
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $transaction->transaction_no }}</td>
                                    <td>{{ $transaction->note }}</td>
                                    <td>{{ $transaction->transaction_date }}</td>
                                    <td>
                                        <a href="{{ $transaction->thumbnail }}" class="attachmentLink"
                                            data-gall="attachment">
                                            <img src="{{ $transaction->thumbnail }}" width="60" height="60"
                                                alt="attachment" loading="lazy" />
                                        </a>
                                    </td>
                                    <td>
                                        @if ($transaction->is_paid)
                                            <span class="badge bg-success">{{ __('Paid') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('Pending') }}</span>
                                        @endif
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
                    <div>
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    @if ($isSupplier)
        <form action="{{ route('shop.supplier.payment', $supplier->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="paymentModal" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            {{ __('Pay Amount To Supplier') }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label class="form-label">
                                {{ __('Amount') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                required min="0" />

                            <p class="text-danger" id="amount-error"></p>
                        </div>


                        <div class="mt-3">
                            <label class="form-label">{{ __('Transaction ID') }}</label>
                            <input type="text" name="transaction_id" class="form-control"
                                placeholder="Transaction ID" />
                            @error('transaction_no')
                               <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror

                        </div>

                        <div class="mt-3">
                            <label class="form-label mb-1">
                               {{ __('File Attachment (jpg, jpeg, png,)') }}
                            </label>
                            <input type="file" name="file_attachment" id="" class="form-control">
                        </div>

                        <div class="mt-3">
                            <label class="form-label">
                                {{ __('Note') }}
                            </label>
                            <textarea name="message" placeholder="{{ __('Any message') }}" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    @endif
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/apexcharts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('assets/scripts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/venobox.min.js') }}"></script>

    <script>
        "use strict";
        new VenoBox({
            selector: '.attachmentLink',
            numeration: true,
            infinigall: true,
            share: true,
        });


        let options = {
            series: [{
                    name: "Purchases",
                    type: "column",
                    data: [],
                },
                {
                    name: "Total Products",
                    type: "line",
                    data: [],
                },
            ],
            colors: ["#006600", "#000000"],
            chart: {
                height: 350,
                type: "line",
            },
            stroke: {
                width: [0, 4],
            },
            title: {
                text: "Traffic Sources",
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1],
            },
            labels: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
            xaxis: {
                type: "months",
            },
            yaxis: [{
                    title: {
                        text: "Total Purchase",
                    },
                },
                {
                    opposite: true,
                    title: {
                        text: "Total Products",
                    },
                },
            ],
        };

        let chart = new ApexCharts(
            document.querySelector("#currentYearStatistics"),
            options
        );
        chart.render();

        const url = "{{ route('shop.supplier.statistic', $supplier->id) }}";
        $.ajax({
            url: url,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {

                chart.updateSeries([{
                        name: "Purchases",
                        type: "column",
                        data: response.data.purchases,
                    },
                    {
                        name: "Total Products",
                        type: "line",
                        data: response.data.items,
                    },
                ]);
            }
        });
    </script>
@endpush
