@extends('layouts.app')
@section('header-title', __('Order Details'))

@section('content')

    <div class="row my-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                    <h4 class="card-title mb-0">{{ __('Order Details') }}</h4>
                    <div class="d-flex gap-2 flex-wrap">
                        @if (module_exists('purchase'))
                            <button type="button" class="btn btn-info py-2.5" data-bs-toggle="modal"
                                data-bs-target="#stockOutModal">
                                {{ __('Attach Product Barcode') }}
                            </button>
                        @endif
                        <a href="{{ route('shop.payment-slip', $order->id) }}" target="_blank"
                            class="btn btn-success py-2.5">
                            <img src="{{ asset('assets/icons-admin/download-alt.svg') }}" alt="icon" loading="lazy"
                                width="20" />
                            {{ __('Payment Slip') }}
                        </a>
                        <a href="{{ route('shop.download-invoice', $order->id) }}" target="_blank"
                            class="btn btn-primary py-2.5">
                            <img src="{{ asset('assets/icons-admin/download-alt.svg') }}" alt="icon" loading="lazy"
                                width="20" />
                            {{ __('Download Invoice') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3 flex-wrap align-items-center">
                        <div class="flex-grow-1">
                            <div class="order-item">
                                <label class="label">{{ __('Order Id') }}:</label>
                                <span class="value">#{{ $order->prefix . $order->order_code }}</span>
                            </div>
                            <div class="order-item">
                                <label class="label">{{ __('Payment Status') }}:</label>
                                <span class="value">{{ $order->payment_status }}</span>
                            </div>
                            <div class="order-item">
                                <label class="label">{{ __('Payment Method') }}:</label>
                                <span class="value">{{ $order->payment_method }}</span>
                            </div>
                        </div>

                        <div class="item-divider"></div>

                        <div class="flex-grow-1">
                            <div class="order-item">
                                <label class="label">{{ __('Order Status') }}:</label>
                                @php
                                    $statusClasses = [
                                        'Cancelled' => 'badge badge-danger',
                                        'Delivered' => 'badge badge-success',
                                    ];

                                    $class = $statusClasses[$order->order_status->value] ?? 'badge badge-warning';
                                @endphp

                                <span class="{{ $class }}">{{ $order->order_status }}</span>

                            </div>
                            <div class="order-item">
                                <label class="label">{{ __('Order Date') }}:</label>
                                <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="order-item">
                                <label class="label">{{ __('Delivery Date') }}:</label>
                                <span class="value">
                                    {{ $order->delivery_date ? Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4 mb-0">
                        <table class="table border-left-right">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    @if ($businessModel == 'multi')
                                        <th>{{ __('Shop') }}</th>
                                    @endif
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th class="text-end">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex gap-1 align-items-center">
                                                <img src="{{ $product->thumbnail }}" alt="" width="40"
                                                    height="40" loading="lazy">
                                                <span>{{ $product->name }}
                                                    @if (module_exists('purchase') && !empty($product->pivot->sku))
                                                        <span class="fw-bold">
                                                            #{{ __('Sku') }}:
                                                            <span class="text-primary">({{ $product->pivot->sku }})</span>
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        @if ($businessModel == 'multi')
                                            <td>{{ $product->shop?->name }}</td>
                                        @endif
                                        <td>{{ $product->pivot->quantity }}</td>
                                        <td>{{ $product->pivot->unit }}</td>
                                        <td>
                                            @php
                                                $price =
                                                    $product->pivot->price > 0
                                                        ? $product->pivot->price
                                                        : ($product->discount_price > 0
                                                            ? $product->discount_price
                                                            : $product->price);
                                            @endphp
                                            {{ showCurrency($price) }}
                                        </td>
                                        <td class="text-end">
                                            {{ showCurrency($product->pivot->quantity * $price) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="max-300 ms-auto d-flex flex-column gap-1">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div>{{ __('Sub Total') }}</div>
                            <div>{{ showCurrency($order->total_amount) }}</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div>{{ __('Coupon Discount') }}</div>
                            <div>{{ showCurrency($order->coupon_discount) }}</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div>{{ __('Delivery Charge') }}</div>
                            <div>{{ showCurrency($order->delivery_charge) }}</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div>{{ __('VAT & Tax') }}</div>
                            <div>{{ showCurrency($order->tax_amount) }}</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-2 border-top pt-1 mt-1">
                            <div class="fw-bold">{{ __('Grand Total') }}</div>
                            <div class="fw-bold">{{ showCurrency($order->payable_amount) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!--##### Customer Info #####-->
            <div class="mt-3 card">
                <h5 class="fz-16 border-bottom px-3 py-12 m-0">{{ __('Customer Info') }}</h5>

                <div class="border-bottom px-3 py-2 d-flex  align-items-center gap-3">
                    <span class="text-color">{{ __('Name') }}: </span>
                    <span class="fw-medium">{{ $order->customer?->user?->name }}</span>
                </div>
                <div class="px-3 py-2 d-flex  align-items-center gap-3">
                    <span class="text-color">{{ __('Phone') }}: </span>
                    <span class="fw-medium">{{ $order->customer?->user?->phone }}</span>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <!--##### Order & Shipping Info #####-->
            <div class="card">
                <h5 class="fz-18 border-bottom p-3 m-0">{{ __('Order & Shipping Info') }}</h5>

                <div class="px-3 py-12 d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
                    <div class="text-color">{{ __('Change Order Status') }}</div>
                    <div class="dropdown">
                        <a class="btn border text-start dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $order->order_status->value }}
                        </a>
                        @if ($order->order_status->value != 'Delivered' && $order->order_status->value != 'Cancelled')
                            @hasPermission(['shop.order.status.change'])
                                <ul class="dropdown-menu order-status">
                                    @foreach ($orderStatus as $status)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('shop.order.status.change', $order->id) }}?status={{ $status->value }}">
                                                {{ __($status->value) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endhasPermission
                        @endif
                    </div>
                </div>

                <div class="border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2 p-3">
                    <div class="text-color">{{ __('Payment Status') }}</div>
                    <div class="d-flex align-items-center gap-1">
                        <span>{{ $order->payment_status }}</span>
                        @hasPermission('shop.order.payment.status.toggle')
                            <label class="switch mb-0">
                                <a href="{{ route('shop.order.payment.status.toggle', $order->id) }}">
                                    <input type="checkbox" {{ $order->payment_status->value == 'Paid' ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        @endhasPermission
                    </div>
                </div>
            </div>

            <!--##### Shipping Address #####-->
            <div class="card mt-3">
                <h5 class="fz-18 border-bottom p-3 m-0">{{ __('Shipping Address') }}</h5>

                <div class="border-bottom d-flex align-items-center justify-content-between gap-2 px-3 py-12">
                    <span class="text-color">{{ __('Name') }}: </span>
                    <span class="fw-medium">{{ $order->address?->name }}</span>
                </div>
                <div class="border-bottom d-flex align-items-center justify-content-between gap-2 px-3 py-12">
                    <span class="text-color">{{ __('Phone') }}: </span>
                    <span class="fw-medium">{{ $order->address?->phone }}</span>
                </div>
                <div class="border-bottom d-flex align-items-center justify-content-between gap-2 px-3 py-12">
                    <span class="text-color">{{ __('Address Type') }}: </span>
                    <span class="fw-medium">{{ $order->address?->address_type }}</span>
                </div>
                <div class="border-bottom d-flex align-items-center justify-content-between gap-2 px-3 py-12">
                    <span class="text-color">{{ __('Area') }}: </span>
                    <span class="fw-medium">{{ $order->address?->area }}</span>
                </div>
                <div class="d-flex gap-2 border-bottom align-items-center justify-content-between flex-wrap px-3 py-12">
                    <div>
                        <span class="text-color">{{ __('Road No') }}: </span>
                        <span class="fw-medium">{{ $order->address?->road_no }}</span>,
                    </div>
                    <div>
                        <span class="text-color">{{ __('Flat No') }}: </span>
                        <span class="fw-medium">{{ $order->address?->flat_no }}</span>,
                    </div>
                    <div>
                        <span class="text-color">{{ __('House No') }}: </span>
                        <span class="fw-medium">{{ $order->address?->house_no }}</span>
                    </div>
                </div>

                <div class="border-bottom d-flex align-items-center justify-content-between gap-2 px-3 py-12">
                    <span class="text-color">{{ __('Post Code') }}: </span>
                    <span class="fw-medium">{{ $order->address?->post_code }}</span>
                </div>
                <div class="border-bottom d-flex align-items-center justify-content-between gap-2 px-3 py-12">
                    <span class="text-color">{{ __('Address Line') }}: </span>
                    <span class="fw-medium">{{ $order->address?->address_line }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between gap-2 px-3 py-12">
                    <span class="text-color">{{ __('Address Line') }} 2: </span>
                    <span class="fw-medium">{{ $order->address?->address_line2 }}</span>
                </div>
            </div>

        </div>
    </div>
    @if (module_exists('purchase'))
        <form id="scannerForm" method="POST" action="{{ route('shop.order.attach.barcode') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}" />
            <div class="modal fade" id="stockOutModal">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Scan Barcode for attachment') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="barcodeInput" class="form-label">{{ __('Enter Barcode Manually / Scan Barcode') }}</label>
                                <div class="input-group">
                                    <input type="text" id="barcodeInput" class="form-control"
                                        placeholder="Type barcode and press Enter" autofocus />
                                </div>
                            </div>
                            <h6>{{ __('Scanned Products') }} :</h6>
                            <div id="scanner-container" class="mb-3 p-2"></div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary py-2.5 px-4" id="scanSubmit">
                               {{ __('Confirm Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection
@push('css')
    <style>
        .dropdown-menu.order-status {
            min-width: 200px;
            padding: 8px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 0 10px #e5e5e5;
        }

        .dropdown-menu.order-status .dropdown-item {
            border-bottom: 1px solid #f1f1f1;
        }

        .app-theme-dark .dropdown-menu.order-status {
            border: 1px solid #343a40;
            box-shadow: 0 0 10px #343a40;
        }

        .app-theme-dark .dropdown-menu.order-status .dropdown-item {
            border-bottom: 1px solid #343a40;
        }

        .max-300 {
            max-width: 340px;
        }

        .min-w-200 {
            min-width: 200px;
            display: inline;
        }

        .item-divider {
            height: 80px;
            width: 1px;
            background: #e5e5e5;
            margin: 0 20px;
        }

        .app-theme-dark .item-divider {
            background: #343a40;
        }

        .order-item {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .order-item:last-child {
            margin-bottom: 0;
        }

        .order-item .label {
            color: #687387;
            line-height: 22px;
        }

        .app-theme-dark .order-item .label {
            color: #8f96a6;
        }

        .order-item .value {
            line-height: 22px;
            font-weight: 500;
            color: #000;
        }

        .app-theme-dark .order-item .value {
            color: #fff;
        }

        @media (max-width: 768px) {
            .item-divider {
                display: none;
            }
        }
    </style>
@endpush
@if (module_exists('purchase'))
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
        <script>
            // scanner script
            let scannedBarcodes = new Set();
            let modal = document.getElementById("stockOutModal");

            function addScannedBarcode(barcode) {
                if (!scannedBarcodes.has(barcode)) {
                    fetchProductsBySku(barcode);

                    scannedBarcodes.add(barcode);
                    $('#barcodeInput').val('').focus();
                } else {
                    $('#barcodeInput').val('').focus();
                }
            }

            function absProductAssign(product_id) {
                $('#assignProductId').val(product_id);
                $('#assignRider').modal('show');
            }

            function fetchProductsBySku(sku) {
                $.ajax({
                    url: "{{ route('shop.order.fetch.products') }}",
                    type: "post",
                    data: {
                        sku: sku,
                        _token: "{{ csrf_token() }}",
                        order_id: "{{ $order->id }}"
                    },
                    success: function(response) {
                        let product = response.data.product;
                        let scannerContainer = document.getElementById("scanner-container");

                        if ($(`#scanned-product${product.id}`).length == 0) {

                            let html = `
                        <div class="w-100 border rounded p-4 shadow-sm" id="scanned-product${product.id}">
                            <div class="d-flex gap-1 align-items-center w-100 mb-1">
                                <div class="product-image">
                                    <img src="${product.thumbnail}" alt="thumbnail" loading="lazy" />
                                </div>
                                <div class="product-info">
                                    <div class="product-name">${product.name}</div>
                                </div>
                            </div>
                            <table class="table mt-1 w-100 border-left-right">
                                <thead>
                                    <tr>
                                        <th class="py-1">Barcode</th>
                                    </tr>
                                </thead>
                                <tbody id="scannedProduct${product.id}">
                                    <tr style="display: table-row !important">
                                        <td>${product.barcode}</td>
                                        <td>
                                            <input type="hidden" name="scanned_barcodes[]" value="${product.barcode}" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>`;

                            scannerContainer.insertAdjacentHTML('afterbegin', html);
                        } else {
                            let table = document.getElementById(`scannedProduct${product.id}`);
                            table.insertAdjacentHTML('afterbegin',
                                `<tr style="display: table-row !important">
                                <td>
                                    ${product.barcode}
                                    <input type="hidden" name="scanned_barcodes[]" value="${product.barcode}" />
                                </td>
                            </tr>`);
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message
                        });
                    }
                })
            }

            // Handle manual barcode input
            document.getElementById("barcodeInput").addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    let barcode = this.value.trim();
                    if (barcode) {
                        addScannedBarcode(barcode);
                    }
                }
            });

            // Start QuaggaJS when modal opens
            function startScannerQuaggaJS() {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.getElementById("scanner-container"),
                        constraints: {
                            width: 640,
                            height: 300,
                            facingMode: "environment" // Rear camera (scanner gun)
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
                    }
                }, function(err) {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    Quagga.start();
                });

                Quagga.onDetected(function(result) {
                    let barcode = result.codeResult.code;
                    addScannedBarcode(barcode);
                });
            }

            // Stop QuaggaJS when modal closes
            modal.addEventListener("hidden.bs.modal", function() {
                modal.setAttribute("aria-hidden", "true");
                modal.removeAttribute("aria-modal");
                Quagga.stop();
            });

            modal.addEventListener("shown.bs.modal", function() {
                setTimeout(function() {
                    document.getElementById("barcodeInput").focus();
                });
            })

            function scannerBarcode() {

                scannedBarcodes = new Set();

                $('#scannerModal').modal('show');
                modal.removeAttribute("aria-hidden");
                modal.setAttribute("aria-modal", "true");

                setTimeout(function() {
                    document.getElementById("barcodeInput").focus();
                }, 200);

                $('#scanner-container').empty();
                startScannerQuaggaJS();

                selectedOptions.each(function() {
                    var barcode = $(this).val();
                    addScannedBarcode(barcode);
                });
            }
        </script>
    @endpush
@endif
