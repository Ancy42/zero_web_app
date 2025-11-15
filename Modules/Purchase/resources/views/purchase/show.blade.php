@extends('layouts.app')
@php
    $isSupplier = !auth()->user()->supplier;
@endphp
@section('content')
    <div class="card">
        <div class="card-header py-2.5 d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ __('Purchase Details') }}  <span class="text-design" >({{ $purchase->purchase_code }})</span></h4>

            <div class="d-flex gap-2">
                @if (!$purchase->is_received)
                    <a href="{{ route('shop.purchase.makeReceived', $purchase->id) }}" class="btn btn-info">
                       {{ __('Mark as Received') }}
                    </a>
                @endif

                <a href="{{ route('shop.purchase.purchaseInvoice', $purchase->id) }}" target="_blank" class=" btn btn-success">
                    <img src="{{ asset('assets/icons-admin/download-alt.svg') }}" alt="icon" loading="lazy"
                        width="20" />
                   {{ __('Download Invoice') }}
                </a>

                @if ($isSupplier)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProduct">
                       {{ __('Attach Products') }}
                    </button>
                @endif

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong> {{ __('Name or Title') }}:</strong></label>
                            <p>{{ $purchase->name }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>{{ __('Received Date') }}:</strong></label>
                            <p>{{ \Carbon\Carbon::parse($purchase->receive_date)->format('m/d/Y') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>{{ __('Total Amount') }}:</strong></label>
                            <p>{{ showCurrency($purchase->total_amount) }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>{{ __('Supplier') }}:</strong></label>
                            <p>{{ $purchase->supplier->name }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>{{ __('Notes') }}:</strong></label>
                            <p>{{ $purchase->note }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <h5 class="mb-3">{{ __('Slip Image') }}</h5>
                    <a href="{{ $purchase->thumbnail }}" target="_blank" class="attachmentLink">
                        <img src="{{ $purchase->thumbnail }}" alt="slip" class="img-fluid">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product List -->
    <div class="card mt-3">
        <div class="card-header py-2.5">
            <h4 class="fz-18 mb-0">{{ __('Product List') }}</h4>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseProducts as $purchaseProduct)
                            @php
                                $product = $purchaseProduct->product;
                                $barcodesWithStatus = $purchaseProduct->productSkus
                                    ->map(function ($sku) {
                                        return ['sku' => $sku->sku, 'status' => $sku->in_stock];
                                    })
                                    ->toJson();
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ $product->thumbnail }}" alt="" width="40"
                                        class="me-2">{{ $product->name }}</td>
                                <td>{{ $purchaseProduct->quantity }}</td>
                                <td>{{ showCurrency($purchaseProduct->price) }}</td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <button type="button" class="btn btn-info"
                                            onclick="showAttachBarcode('{{ $barcodesWithStatus }}', '{{ $product->name }}' , '{{ $product->id }}') ">
                                            <i class="bi bi-upc-scan"></i>
                                        </button>

                                        <a href="{{ route('shop.product.show', $product->id) }}" target="_blank"
                                            class="circleIcon svg-bg">
                                            <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                                loading="lazy" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="my-3">
                    {{ $purchaseProducts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @if ($isSupplier)
        <!-- add product modal -->
        <div class="modal fade" id="addProduct">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Select Products To Attach') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="searchInputBox" class="form-label">{{ __('Type Product Name') }}</label>

                            <input type="text" id="searchInputBox" class="form-control"
                                placeholder="Type product name" />
                        </div>
                        <h6>{{ __('Searched Products') }}:</h6>
                        <div id="searchedProducts" class="mb-3 p-2 d-flex gap-3 flex-column">
                            <div class="searchLoader d-none">
                                <div class="spinner-border text-info" role="status">
                                    <span class="visually-hidden">{{ __('Loading') }}...</span>
                                </div>
                            </div>

                            <p class="text-muted mb-0 fst-italic">{{ __('No product found') }}</p>

                            <!-- added products go here -->
                        </div>

                        <form action="{{ route('shop.purchase.attach.product', $purchase->id) }}" method="POST"
                            id="addProductForm" class="d-none">
                            @csrf
                            <input type="hidden" name="product_id" id="selectedProductId" />
                            <div id="productBarcodeSection" class="d-none">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skuInput" type="radio" name="is_sku" checked
                                        id="yesSku" value="true">
                                    <label class="form-check-label" for="inlineRadio1">{{ __('Sku') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input skuInput" type="radio" name="is_sku"
                                        id="noSku" value="false">
                                    <label class="form-check-label" for="inlineRadio2">{{ __('No Sku') }}</label>
                                </div>

                                <div class="skuSection">
                                    <div class="input-group d-flex w-100 mb-3">
                                        <label class="form-label mb-0">{{ __('Product Barcodes') }}</label>
                                        <select name="product_barcodes[]" class="mySelect w-100"
                                            multiple></select>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label mb-0">{{ __('Buying Price') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="buying_price" class="form-control buying_price"
                                            min="0" placeholder="Buying Price" />
                                    </div>
                                </div>
                                <div class="noSkuSection d-none">
                                    <div class="mt-2">
                                        <label class="form-label mb-0">{{ __('Quantity') }}</label>
                                        <input type="number" name="quantity" class="form-control" min="1"
                                            placeholder="Add Quantity" />
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label mb-0">{{ __('Buying Price') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="buying_price" class="form-control buying_price"
                                            min="0" placeholder="Buying Price" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" onclick="submitProductForm()" class="btn btn-primary">{{ __('Confirm Submit') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Scanner Modal -->
    <form id="scannerForm">
        <div class="modal fade" id="scannerModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Scan Barcode') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="scan_attribute_id" />

                        <div class="mb-3">
                            <label for="barcodeInput" class="form-label">{{ __('Enter Barcode Manually / Scan Barcode') }}</label>
                            <input type="text" id="barcodeInput" class="form-control"
                                placeholder="Type barcode and press Enter" autofocus />
                        </div>
                        <h6>{{ __('Scanned BarCodes') }}:</h6>
                        <div id="scanner-container" class="mb-3 p-2"></div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary py-2.5 px-4" id="scanSubmit">{{ __('Submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- show attach barcode modal -->
    <form action="{{ route('shop.purchase.product.delete.barcode', $purchase->id) }}" method="POST"
        id="attachBarcodeForm">
        @csrf
        <div class="modal fade" id="showAttachBarcode">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Attached Barcodes') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <small class="mb-0 text-muted">{{ __('Product') }}:</small>
                            <h4 class="fw-bold fz-18" id="attachedProductName"></h4>
                            <input type="hidden" id="attachedProductId" name="product_id">
                        </div>

                        <p class="mt-3">{{ __('Attached BarCodes') }}:</p>
                        <div id="attachedBarcode" class="d-flex flex-wrap gap-2 mt-1"></div>
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                           {{ __('Close') }}
                        </button>
                        @hasPermission('shop.purchase.product.delete.barcode')
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary deleteData">{{ __('Confirm Submit') }}</button>
                            </div>
                        @endhasPermission
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush

@push('scripts')
    <script>
        "use strict";

        const isSupplier = "{{ $isSupplier }}";

        // Pass Laravel settings to JS
        const currencySymbol = "{{ generaleSetting('setting')->currency ?? '$' }}";
        const currencyPosition = "{{ generaleSetting('setting')->currency_position ?? 'prefix' }}";

        // JS version of showCurrency()
        function showCurrency(amount = 0) {
            amount = amount || 0;

            if (currencyPosition === 'suffix') {
                return amount + currencySymbol;
            }

            return currencySymbol + amount;
        }

        // -----------------------------
        // GENERAL FUNCTIONS
        // -----------------------------
        function submitProductForm() {
            document.getElementById('addProductForm').submit();
        }

        function setupSku() {
            $(".mySelect").select2({
                tags: true,
                placeholder: "Write SKU and press (comma, space, or enter) to add separate SKUs",
                tokenSeparators: [',', ' ', 13]
            });
        }

        $(document).ready(function() {
            setTimeout(() => {
                $('.scannerImg').attr('src', "{{ asset('assets/images/scanner-gun.png') }}");
            }, 200);

            setupSku();
        });

        // -----------------------------
        // SHOW ATTACHED BARCODE MODAL
        // -----------------------------
        function showAttachBarcode(barcodes, productName, productId) {
            $('#attachedProductName').text(productName);
            $('#attachedProductId').val(productId);
            $('#showAttachBarcode').modal('show');
            try {
                barcodes = JSON.parse(barcodes);
            } catch (e) {
                console.error('Invalid JSON passed to showAttachBarcode:', barcodes);
                return;
            }

            const attachedBarcode = document.getElementById('attachedBarcode');
            attachedBarcode.innerHTML = '';

            barcodes.forEach(item => {
                const span = document.createElement('span');
                span.className = 'barcode me-2 d-inline-block px-2 py-1 rounded';
                span.style.border = '1px solid #ccc';
                span.style.marginBottom = '4px';

                if (item.status === 0) {
                    span.style.backgroundColor = '#e9ecef';
                    span.style.color = '#6c757d';
                    span.innerHTML = `${item.sku} `;
                } else {
                    var code = item.sku
                    if (isSupplier) {
                        code +=
                            `<i class="fa fa-times text-danger remove-barcode ms-2" aria-hidden="true" data-barcode="${item.sku}"></i>`
                    }
                    span.innerHTML = code
                }

                attachedBarcode.appendChild(span);
            });
        }

        // -----------------------------
        // PRODUCT SEARCH FUNCTIONS
        // -----------------------------
        const searchInput = document.getElementById("searchInputBox");
        const searchedProducts = $("#searchedProducts");
        const searchLoader = document.querySelector(".searchLoader");

        let timeout;
        let allProducts = [];

        $("#searchInputBox").on("keyup", function(e) {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                fetchProducts(searchInput.value);
            }, 500);
        });


        function appendProduct(product) {
            let html = `
                <div id="productItem${product.id}" class="productItem border rounded p-1" onclick="addProduct(${product.id})">
                    <div class="d-flex gap-2 align-items-center w-100">
                        <img src="${product.thumbnail}" alt="product" loading="lazy"
                            width="60" height="60" class="rounded" />
                        <div>
                            <h6 class="mb-0 fw-bold">${product.name}</h6>
                            <div>
                                Price: ${showCurrency(product.discount_price > 0 ? product.discount_price : product.price)}
                                <del class="text-danger ${product.discount_price > 0 ? '' : 'd-none'}">
                                ${showCurrency(product.price)}
                                </del>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            searchedProducts.append(html);
        }

        function addProduct(id) {
            let productDiv = $("#productItem" + id);

            $('.productItem').addClass('d-none');
            productDiv.removeClass('d-none');

            const product = allProducts.find(product => product.id === id);

            $('#selectedProductId').val(product.id);
            $('.buying_price').val(product.buy_price);
            $('#addProductForm').removeClass('d-none');
            $('#productBarcodeSection').removeClass('d-none');
            setupSku();
        }

        function fetchProducts(query) {
            searchLoader.classList.remove("d-none");
            $("#addProductForm").addClass('d-none');

            $.ajax({
                url: "/shop/purchase/products?search=" + query,
                type: "GET",
                success: function(response) {
                    searchLoader.classList.add("d-none");

                    allProducts = response.data.products;
                    searchedProducts.empty();

                    if (allProducts.length > 0) {
                        allProducts.forEach((product) => {
                            appendProduct(product);
                        })
                    } else {
                        $('#searchedProducts').append(
                            '<p class="text-muted mb-0 fst-italic">No product found</p>');
                    }
                },
                error: function(error) {
                    searchLoader.classList.add("d-none");
                }
            });
        }
        $('.skuInput').on('change', function() {
            const isSku = $(this).val() === 'true';

            if (isSku) {
                $('.skuSection').removeClass('d-none').find('input, select').prop('disabled', false);
                $('.noSkuSection').addClass('d-none').find('input, select').prop('disabled', true);
            } else {
                $('.noSkuSection').removeClass('d-none').find('input, select').prop('disabled', false);
                $('.skuSection').addClass('d-none').find('input, select').prop('disabled', true);
            }
        });
        $('.skuInput:checked').trigger('change');

        $(document).on('click', '.remove-barcode', function() {
            const barcode = $(this).data('barcode');
            console.log(barcode);
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'barcodes[]';
            input.value = barcode;
            input.style.display = 'none';
            $(this).parent().remove();
            const attachedBarcode = document.getElementById('attachedBarcode');
            attachedBarcode.appendChild(input);
        })

        $(document).on('click', '.deleteData', function(e) {
            e.preventDefault();

            const form = $(this).closest('form');
            const url = form.data('url');

            Swal.fire({
                title: 'Are you sure?',
                text: "Delete Barcode!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
