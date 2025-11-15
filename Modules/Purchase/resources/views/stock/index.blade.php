@extends('layouts.app')
@section('content')
    <form action="">
        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-start align-items-center  gap-2 mb-2">
                <div>
                    <select name="product_id[]" multiple class="form-select select2" width="200px"
                        data-placeholder="All Products">
                        <option value="">{{ __('Select Product') }}</option>
                        @foreach ($Allproduct ?? [] as $product)
                            <option value="{{ $product?->id }}" @if (is_array(request('product_id')) && in_array($product->id, request('product_id'))) selected @endif>
                                {{ $product?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="input-group w-230">
                        <input type="text" id="reportrange" name="date_range" class="form-control"
                            value="{{ request('date_range') }}" placeholder="Filter by date" autocomplete="off">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> {{ __('Filter') }} </button>
                    <a href="{{ route('shop.purchase.allProduct.stockSummary') }}" class="btn btn-dark"><i
                            class="fa fa-refresh"></i></a>
                </div>
                <button class="btn btn-primary" id="printBtn" type="button" data-bs-toggle="tooltip"
                    data-bs-title="Print as PDF">
                    <i class="fa fa-print" aria-hidden="true"></i>
                </button>

            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered  mt-3" id="printableArea">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2">{{ __('SN') }}</th>
                                <th rowspan="2">{{ __('Product Name') }}</th>
                                <th colspan="3" class="text-center">{{ __('Stock Details') }}</th>
                            </tr>
                            <tr>
                                <th>{{ __('Purchased (In)') }}</th>
                                <th>{{ __('Sold (Out)') }}</th>
                                <th>{{ __('Available Stock') }}</th>
                            </tr>
                        </thead>
                        @php
                            $totalPurchased = 0;
                            $totalSolded = 0;
                            $totalAvailable = 0;
                        @endphp
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product['product_name'] }}</td>
                                    <td class="text-center">{{ $product['purchased'] }}</td>
                                    <td class="text-center">{{ $product['solded'] }}</td>
                                    <td class="text-center">{{ $product['available_stock'] }}</td>
                                </tr>
                                @php
                                    $totalPurchased += $product['purchased'];
                                    $totalSolded += $product['solded'];
                                    $totalAvailable += $product['available_stock'];
                                @endphp
                            @endforeach
                            <tr>
                                <td colspan="2" class="text-right fw-bold">{{ __('Total') }}</td>
                                <td class="text-center fw-bold">{{ $totalPurchased }}</td>
                                <td class="text-center fw-bold">{{ $totalSolded }}</td>
                                <td class="text-center fw-bold">{{ $totalAvailable }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/scripts/jQuery.print.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        "use strict";
        $('#printBtn').click(function() {
            $('#printableArea').print({
                globalStyles: true,
                title: 'Porduct Stock Summary',
                doctype: '<!doctype html>',
                removeEmpty: true

            });
        })

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

        $(function() {
            $('input[name="date_range"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            });
            $('input[name="date_range"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(
                    picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD')
                );
            });
            $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endpush
