@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>{{ __('Purchase Return Create') }}</h4>
    </div>

    <div class="container-fluid mt-3">
        <div class="mb-3 card">
            <div class="card-header py-3">
                <div class="d-flex align-items-center justify-content-start flex-wrap gap-2">
                    <div>
                        <form action="" class="d-flex align-items-center justify-content-between gap-3">
                            <div class="input-group w-500">
                                <input type="text" name="search" id="search" class="form-control"
                                    placeholder="Search by invoice or barcode" value="{{ request('search') }}">
                                <button type="submit" class="input-group-text btn btn-primary">
                                    <i class="fa fa-search"></i> {{ __('Search') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <a href="{{ route('shop.purchaseReturn.create') }}" class="btn btn-primary"><i
                            class="fa-solid fa-rotate"></i> {{ __('Reset') }}</a>
                </div>
            </div>
            <div class="searchResult d-none">
                <ul>
                </ul>
            </div>
            <div class="returnForm">

            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush

@push('scripts')
    <script>
        "use strict";
        $(document).ready(function() {
            $('#search').on('keyup', function(e) {
                e.preventDefault();
                let search = $(this).val().trim();

                if (search.length === 0) {
                    $('.searchResult').addClass('d-none').find('ul').html('');
                    return;
                }

                $.ajax({
                    url: "{{ route('shop.purchase.invoice.search') }}",
                    method: "GET",
                    data: {
                        search: search
                    },
                    success: function(response) {
                        const searchResult = $('.searchResult');
                        const ulElement = searchResult.find('ul');
                        ulElement.empty();

                        if (response.data.length === 0) {
                            ulElement.append(
                                `<li class="text-muted px-2">{{ __('No results found.') }}</li>`);
                        } else {
                            response.data.forEach(purchase => {
                                let products = purchase.purchase_product.join(' ');
                                let html = `
                                    <li class="px-2 py-1 border-bottom purchaseId cursor-pointer" data-purchaseid="${purchase.id}">
                                        <strong>${purchase.purchase_code}</strong> ${products}
                                    </li>
                                `;
                                ulElement.append(html);
                            });
                        }
                        searchResult.removeClass('d-none');
                    },
                    error: function(xhr) {
                        console.error('Search failed:', xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.purchaseId', function() {
                let id = $(this).data('purchaseid');

                $.ajax({
                    url: "{{ route('shop.purchase.invoice.add') }}",
                    method: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('#search').val('');
                        $('.searchResult').addClass('d-none').find('ul').html('');
                        $('.returnForm').html(response);
                    },
                    error: function(xhr) {
                        console.error('Failed to load lot details:', xhr.responseText);
                    }
                });
            });
            $(document).on('keyup', '.return_quantity', function() {
                let input = $(this);
                let quantity = parseInt(input.val()) || 0;
                let purchaseProductQTY = parseInt(input.data('purproqty')) || 0;
                let price = parseFloat(input.data('price')) || 0;

                // Restrict input to max quantity
                if (quantity > purchaseProductQTY) {
                    quantity = purchaseProductQTY;
                    input.val(quantity);
                }
                // Calculate and update subtotal
                let subtotal = (quantity * price).toFixed(2);
                input.closest('tr').find('.subtotal').text(subtotal);
                let grandTotal = 0;
                $('.subtotal').each(function() {
                    let val = parseFloat($(this).text()) || 0;
                    grandTotal += val;
                });

                $('.grandtotal').text(grandTotal.toFixed(2));
            });

            $(document).on('change', '.return_sku', function() {
                let input = $(this);
                let purchaseId = input.data('purchaseid');

                let qtyInput = $('.return_quantity[data-purchaseid="' + purchaseId + '"]');
                let currentQty = parseInt(qtyInput.val()) || 0;

                if (input.is(':checked')) {
                    currentQty++;
                } else {
                    currentQty = Math.max(currentQty - 1, 0);
                }
                qtyInput.val(currentQty).trigger('keyup');
            });
        });
    </script>
@endpush
