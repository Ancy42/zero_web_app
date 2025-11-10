@extends('layouts.app')

@section('header-title', __('Point of Sale (POS)'))

@section('content')
    <div class="app-page-title mb-0">
        <div class="page-title-wrapper">
            <div class="page-title-heading d-flex align-items-center justify-content-between">
                <div>
                    {{ __('Point of Sale (POS)') }}
                </div>
                {{-- <div>
                    <button class="btn btn-primary" type="button" id="fullscreen">
                        <i class="fa-solid fa-maximize"></i>
                    </button>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row border-bottom pb-3">
                        <div class="col-6 col-xl-3">
                            <select name="brand" class="form-select form-control select2" style="width: 100%">
                                <option selected value="">
                                    {{ __('Select Brand') }}
                                </option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-xl-3">
                            <select name="category" class="form-select form-control select2" style="width: 100%">
                                <option selected value="">
                                    {{ __('Select Category') }}
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-6 mt-2 mt-xl-0">
                            <div class="input-group">
                                <input type="search"
                                    class="form-control border-0 border-start border-top text-muted border-bottom"
                                    placeholder="{{ __('Search by product name') }}" name="search" id="search" />
                                <button class="btn border-top border-bottom border-end text-muted pe-3" type="button">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="row g-3 mt-2" id="product-list">
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3" id="simplePagination"></div>

                </div>
            </div>
        </div>

        <div class="col-xl-5 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 border-bottom pb-3 mb-3">
                        <div class="flex-grow-1">
                            <div class="input-group d-flex">
                                <span
                                    class="input-group-text border-start border-top border-bottom border-muted border-0 bg-white ps-2 pe-1">
                                    <i class="bi bi-person-circle text-muted"></i>
                                </span>
                                <div class="flex-grow-1 customerSelect">
                                    <select name="customer_id" class="form-control select2" style="width: 100%;"
                                        data-placeholder="{{ __('Enter customer name or phone number') }}" id="customerId">
                                        <option selected value="">{{ __('Select Customer') }}</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ Str::limit($customer->user?->name, 30, '...') . '-(' . $customer->user?->phone . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 flex-lg-grow-0">
                            <button class="btn btn-outline-primary w-100 py-2" data-bs-toggle="modal"
                                data-bs-target="#customerModal">
                                <i class="bi bi-plus-circle-fill me-2"></i>
                                {{ __('Customer') }}
                            </button>
                        </div>
                    </div>

                    <!--##### Cart name #####-->
                    <input type="hidden" name="name" id="name" value="{{ request('name') }}" />

                    <div class="pb-3 border-bottom">
                        <div class="bg-light border p-2 pos-cart" id="pos-cart-list"></div>
                    </div>

                    <div class="row py-4 border-bottom gy-1">
                        <div class="col-6 fs-5">
                            <div>
                                {{ __('Sub Total') }}
                            </div>
                            <div class="mt-1">
                                {{ __('Discount Amount') }}
                            </div>
                        </div>
                        <div class="col-6 fs-5 text-end">
                            <div id="subtotal"></div>
                            <div class="text-danger mt-1" id="discount"></div>
                        </div>
                        <div class="col-12">
                            <!-- Taxes -->
                            <div id="taxes" class="d-none flex-column gap-1 mt-1">
                                <div class="vat-taxes-summary">
                                    <h2 class="summary-title">
                                        {{ __('VAT & Taxes Summary') }}
                                    </h2>

                                    <div id="vatTaxesList" class="vat-taxes-list">
                                        <!-- Add more VAT tax items here -->
                                    </div>

                                    <div class="total-tax">
                                        <h3 class="total-tax-title m-0">{{ __('Total Tax Amount') }}:</h3>
                                        <p id="totalTaxAmount" class="total-tax-amount"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex mt-2">
                                <input type="text" id="coupon" class="form-control text-muted me-2 py-2.5"
                                    placeholder="{{ __('Add Coupon') }}">

                                <!-- Coupon Control Button -->
                                <div id="couponControlBtn"></div>
                            </div>
                        </div>
                    </div>

                    <div class="py-3 d-flex">
                        <button class="btn bg-warning-light text-warning fw-bold w-25 me-2 py-3" onclick="draftOrder()">
                            {{ __('Draft') }}
                        </button>
                        <a data-bs-toggle="offcanvas" href="#checkoutOffcanvas" role="button"
                            aria-controls="checkoutOffcanvas" class="btn btn-primary w-75 py-3">
                            {{ __('Grand Total') }}
                            <span id="total"></span>
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="checkoutOffcanvas" aria-labelledby="checkoutOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="checkoutOffcanvasLabel">{{ __('Checkout information') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column gap-3 justify-content-between">
            <div>
                <div class="bg-light rounded p-4 mb-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('Total Product') }}</span>
                        <span class="bg-primary rounded-circle text-white checkout-item-number"
                            id="totalProducts">2</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>
                            {{ __('Total Amount') }}
                        </span>
                        <span class="fw-bold fs-5" id="total2"></span>
                    </div>
                </div>

                <h5 class="mb-3">
                    {{ __('Payment Method') }}
                </h5>
                <div class="d-flex mb-4">
                    <button class="border px-4 py-3 rounded-3 me-3 paymentMethod active" id="cash"
                        onclick="selectPaymentMethod('cash')">
                        <img src="{{ asset('assets/gateway/cash.png') }}">
                    </button>
                    {{-- <button class="border px-4 py-3 rounded-3 me-3 paymentMethod" id="visa"
                        onclick="selectPaymentMethod('visa')">
                        <img src="{{ asset('assets/gateway/visa.png') }}">
                    </button> --}}
                </div>

                <h5 class="mb-3">
                    {{ __('Received Amount') }}
                </h5>
                <div class="rounded-pill w-100 border border-muted fs-5 fw-bold text-center p-3">
                    <span id="total3"></span>
                </div>
            </div>

            <div class="d-flex">
                <button class="btn btn-primary w-100 p-3 mt-auto" onclick="confirmOrder()">
                    {{ __('Confirm') }}
                </button>
            </div>
        </div>
    </div>

    <!-- add or edit product modal -->
    <div class="modal fade" id="productDetailModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-4" id="productModalLabel">
                        {{ __('Product Details') }}
                    </h1>
                    <button type="button" class="btn-close bg-light rounded-circle p-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <div class="mb-3 my-3">
                        <div class="row g-0">
                            <div class="col-4">
                                <!-- Image -->
                                <img src="" id="productImage"
                                    class="rounded-3 pos-product-image object-fit-cover w-100"
                                    height="250px"alt="Product Image">
                            </div>
                            <div class="col-8">
                                <div class="card-body py-0">
                                    <!-- Title -->
                                    <h5 class="card-title fs-4 fw-normal pos-product-title fw-bold" id="productTitle">
                                    </h5>
                                    <p class="card-text pos-product-unit  pb-3" id="productUnit"></p>
                                    <p class="card-text border-bottom pb-3 mb-3">
                                        <!-- Price -->
                                        <strong class="text-primary fs-4 me-1 pos-product-price" id="productPrice">
                                        </strong>
                                        <!-- Original Price -->
                                        <small class="text-muted fs-5 text-decoration-line-through me-1"
                                            id="productOriginalPrice"></small>

                                        <!-- Discount -->
                                        <span class="bg-danger text-white rounded-pill pos-product-discount py-1 px-2"
                                            id="productDiscount">
                                        </span>
                                    </p>

                                    <!-- Quantity -->
                                    <div class="row pt-3">
                                        <div class="col-3 my-auto">
                                            {{ __('Quantity') }}
                                        </div>
                                        <div class="col-9 my-auto">
                                            <div class="d-flex quantity">
                                                <button onclick="if(quantityNumber.value>1) quantityNumber.value--"
                                                    class="btn btn-light substract-btn">
                                                    <i class="bi bi-dash-lg"></i>
                                                </button>
                                                <input id="quantityNumber" type="number"
                                                    class="form-control rounded-3 text-center fw-bold quantity-number"
                                                    min="1" max="100000" value="1" />
                                                <button onclick="quantityNumber.value++" class="btn btn-light add-btn">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary w-100 py-3" id="addToCartBtn">
                        {{ __('Confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- customer create modal -->
    <form action="#" id="customerForm">
        <div class="modal fade" id="customerModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header border-0">
                        <h1 class="modal-title fs-4" id="productModalLabel">
                            {{ __('Add New Customer') }}
                        </h1>
                        <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <x-input label="First Name" name="first_name" type="text"
                                                placeholder="First Name" class="form-control" required="true" />
                                            <span id="firstNameError" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <x-input label="Last Name" name="last_name" type="text"
                                                placeholder="Enter Name" />
                                            <span id="lastNameError" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <x-input label="Phone Number" name="phone" type="number"
                                        placeholder="Enter phone number" required="true" />
                                    <span id="phoneError" class="text-danger"></span>
                                </div>

                                <div class="mt-3">
                                    <x-select label="Gender" name="gender">
                                        <option value="male">{{ __('Male') }}</option>
                                        <option value="female">{{ __('Female') }}</option>
                                    </x-select>
                                </div>
                                <div class="mt-3">
                                    <x-input type="email" name="email" label="Email"
                                        placeholder="Enter Email Address" />
                                    <span id="emailError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="is_active" value="1">
                    </div>
                    <div
                        class="modal-footer border-0 mt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <button type="button" class="btn btn-secondary py-3 flex-grow-1" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>

                        <button type="submit" class="btn btn-primary py-3 flex-grow-1">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/simplePagination.css') }}" />
    <style>
        .product-divider {
            height: 14px;
            width: 1px;
            background: #dee2e6;
        }

        .app-theme-dark .product-divider {
            background: #343a40;
        }

        .customerSelect .select2-container--default .select2-selection--single {
            border-left: 0;
            border-radius: 0 5px 5px 0;
        }

        .paymentMethod {
            border: 1px solid #dee2e6;
            background: transparent;
        }

        .paymentMethod.active {
            border: 2px solid var(--theme-color) !important;
            background: var(--theme-hover-bg);
        }

        .vat-taxes-summary {
            padding: 0.5rem;
            background-color: #f1f5f9;
            color: #000;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .summary-title {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .vat-tax-item {
            display: flex;
            justify-content: space-between;
            background-color: #e2e8f0;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .vat-tax-name {
            font-weight: 500;
        }

        .vat-tax-amount {
            font-weight: 500;
        }

        .total-tax {
            padding: 0.75rem;
            background-color: #e2e8f0;
            border-radius: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .total-tax-title {
            font-size: 1rem;
            font-weight: 500;
        }

        .total-tax-amount {
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/scripts/jquery.simplePagination.js') }}"></script>
    <script>
        const purchaseModuleActive = @json(function_exists('module_exists') && module_exists('Purchase'));
        var offcanvasElement = document.getElementById('checkoutOffcanvas');

        offcanvasElement.addEventListener('show.bs.offcanvas', function() {
            $('body').addClass('modal-open');
        });

        offcanvasElement.addEventListener('hidden.bs.offcanvas', function() {
            $('body').removeClass('modal-open');
        });

        var search = $('#search').val();
        var category = $('select[name="category"]').val();
        var brand = $('select[name="brand"]').val();

        $('select[name="category"]').on('change', function() {
            fetchProducts();
        });

        $('select[name="brand"]').on('change', function() {
            fetchProducts();
        });

        var currency = "{{ $currency }}";
        var currencyPosition = "{{ $currencyPosition }}";

        var perPage = 14;
        var currentPage = 1;
        var totalItems = 0;
        var callPagination = true;

        let timeout;
        $('#search').on('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                currentPage = 1;
                callPagination = true;
                fetchProducts();
            }, 500);
        });

        // fetch products
        function fetchProducts() {
            $.ajax({
                url: "{{ route('shop.pos.product') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    search: $('#search').val(),
                    category: $('select[name="category"]').val(),
                    brand: $('select[name="brand"]').val(),
                    page: currentPage,
                    per_page: perPage,
                },
                success: function(response) {
                    totalItems = response.data.total;
                    currency = response.data.currency;
                    currencyPosition = response.data.currency_position;
                    appendProducts(response.data.products);

                    if (callPagination) {
                        setupPagination();
                    }
                },
                error: function(error) {
                    console.error('Error fetching products:', error);
                }
            });
        }

        function setupPagination() {
            callPagination = false;
            $('#simplePagination').pagination({
                items: totalItems,
                itemsOnPage: perPage,
                cssStyle: 'light-theme',
                onPageClick: function(pageNumber) {
                    currentPage = pageNumber;
                    fetchProducts();
                }
            });
        }

        function showCurrency(amount) {
            return currencyPosition == 'prefix' ? currency + amount : amount + currency;
        }

        // append products
        function appendProducts(products) {
            const productList = $('#product-list');
            productList.empty(); // Clear the existing product list if necessary
            products.forEach(product => {
                const productHtml = `
                    <div class="col-xxl-6 col-md-6">
                        <div class="border rounded cursor-pointer theme-dark" data-product='${JSON.stringify(product)}' onclick="openProductModal(${product.id})" id="product-${product.id}">
                            <div class="d-flex align-items-center">
                                <div class="bg-light">
                                    <img src="${product.thumbnail}"
                                        class="rounded-start pos-product-image object-fit-cover" height="110"
                                        width="110" alt="Product Image">
                                </div>
                                <div class="p-2 overflow-hidden w-100">
                                    <h5 class="card-title fs-5 fw-normal pos-product-title mb-1">
                                        <p class="overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;" title="${product.name}">
                                            ${product.name}
                                        </p>
                                    </h5>
                                    <p class="card-text mb-1">
                                        <strong class="text-primary fs-5 me-1 pos-product-price">
                                            ${showCurrency(product.discount_price > 0 ? product.discount_price : product.price)}
                                        </strong>
                                        ${product.discount_price > 0 ?
                                        `<small class="text-muted text-decoration-line-through me-1">
                                                                    ${showCurrency(product.price)}
                                                                </small>
                                                                ` : ''}
                                        <span class="bg-danger text-white rounded-pill pos-product-discount py-1 px-2" style="display: ${product.discount_percentage > 0 ? 'inline-block' : 'none'}">
                                            ${product.discount_percentage}% OFF
                                        </span>
                                    </p>
                                    <p class="card-text d-flex align-items-center justify-content-between w-100">
                                        <span class="text-muted pos-product-meta">
                                            ${product.unit ?? 'pcs'}
                                        </span>
                                        <span class="product-divider"></span>
                                        ${product.quantity > 0 ? `<span class="text-muted pos-product-meta">
                                                                ${product.quantity} Left</span>` :
                                        `<span class="text-danger fw-bold">
                                                                    Stock Out
                                                                </span>
                                                                `}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                productList.append(productHtml);
            });
        }

        // call fetch products
        fetchProducts();

        var posCartBasket = [];

        function fetchPosCart() {
            $.ajax({
                url: "{{ route('shop.pos.getCart') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    name: $('#name').val(),
                },
                success: function(response) {
                    posCartBasket = response.data.products;
                    appendPosCart(response.data.products);
                    $('#totalProducts').text(response.data.products.length);
                    $('#subtotal').text(showCurrency(response.data.subtotal));
                    $('#total').text(showCurrency(response.data.total));
                    $('#total2').text(showCurrency(response.data.total));
                    $('#total3').text(showCurrency(response.data.total));
                    $('#discount').text('- ' + showCurrency(response.data.discount));
                    $('#name').val(response.data.name);
                    $('#coupon').val(response.data.coupon_code);

                    var couponControlBtn = $('#couponControlBtn');
                    couponControlBtn.empty();
                    if (response.data.coupon_code != null) {
                        couponControlBtn.append(`
                            <button class="btn btn-danger px-4 py-2.5" onclick="removeCoupon()">
                                {{ __('Remove') }}
                            </button>
                        `);
                    } else {
                        couponControlBtn.append(`
                            <button class="btn btn-primary px-4 py-2.5" onclick="applyCoupon()">
                                {{ __('Apply') }}
                            </button>
                        `);
                    }

                    // taxes summary
                    let taxesDiv = $('#taxes');
                    let vatTaxesList = $('#vatTaxesList');
                    let totalTaxAmount = $('#totalTaxAmount');

                    if (response.data.taxes.length > 0) {
                        taxesDiv.removeClass('d-none');
                        taxesDiv.addClass('d-flex');
                        totalTaxAmount.text(showCurrency(response.data.total_tax_amount));
                    } else {
                        taxesDiv.removeClass('d-flex');
                        taxesDiv.addClass('d-none');
                    }

                    vatTaxesList.empty();
                    response.data.taxes.forEach(tax => {
                        let taxHtml = `
                            <div class="vat-tax-item">
                                <span class="vat-tax-name">${tax.name}(${tax.percentage}%)</span>
                                <span class="vat-tax-amount">${showCurrency(tax.amount)}</span>
                            </div>
                        `;
                        vatTaxesList.append(taxHtml);
                    });
                },
                error: function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                }
            });
        }

        function appendPosCart(cartItems) {
            const cartList = $('#pos-cart-list');
            cartList.empty(); // Clear the existing cart items if necessary

            cartItems.forEach(item => {
                var barCodeBox = ''
                if (purchaseModuleActive) {
                    for (let i = 0; i < item.order_qty; i++) {
                        const skuValue = item.sku_numbers?.[`sl_${i}`] ?? '';
                        barCodeBox += `
                        <div class="input-group mt-1">
                            <span class="input-group-text py-0" style="height: 30px;">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control py-0 px-1"
                                style="min-height: 30px;"
                                placeholder="SKU NO."
                                value="${skuValue}"
                                onchange="skuAddOrUpdate(this.value, 'sl_${i}', ${item.id}, ${item.pos_cart_id}, this)"
                            >
                        </div>`;
                    }
                }
                const cartItemHtml = `
                <div class="border bg-white rounded mb-2 p-3">
                    <div class="row g-0">
                        <div class="col-lg-2">
                            <img src="${item.thumbnail}" class="rounded pos-product-image object-fit-cover w-100" height="70px" alt="Product Image">
                        </div>
                        <div class="col-lg-10">
                            <div class="card-body py-0">
                                <h5 class="card-title fw-bold fw-normal pos-product-title mb-0 truncate">${item.name}</h5>
                                <p class="card-text mb-1">
                                    <small class="text-muted fw-bold"> Qty : ${item.order_qty}   |   Unit : ${item.unit ?? 'pcs'} </small>
                                </p>
                                <div class="d-flex">
                                    <div class="me-auto">
                                        <strong class="text-primary me-1 pos-product-price">${item.discount_price > 0 ? showCurrency(item.discount_price) : showCurrency(item.price)}</strong>
                                        <span class="text-muted text-decoration-line-through me-1" style="display: ${item.discount_price > 0 ? 'inline-block' : 'none'}">
                                            ${showCurrency(item.price)}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0)" class="text-primary me-2" onclick="openProductEditModal(${item.id}, ${item.pos_cart_id})">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="text-danger" onclick="deletePosCartItem(${item.pos_cart_id})">
                                            <i class="bi bi-trash3 fs-5"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <span >
                                ${barCodeBox}
                            </span>
                        </div>
                    </div>
                </div>
            `;
                cartList.append(cartItemHtml);
            });

            // check if cart is empty
            if (cartItems.length <= 0) {
                const emptyHtml = `
                <div class="text-center d-flex align-items-center justify-content-center w-100 h-100 text-muted">
                    <i class="fa-solid fa-basket-shopping fs-3"></i>
                    <span class="fs-5 ms-2">{{ __('Cart is empty') }}</span>
                </div>
                `;
                cartList.append(emptyHtml);
            }
        }

        // Call fetchPosCart function when needed, for example on page load or button click
        fetchPosCart();
        var selectedUnit = null;
        var selectedProductID = null;
        var selectedPosCartItemID = null;
        var isModalEdit = false;
        var selectedPaymentMethod = 'cash';
        var orderType = 'sale';

        function openProductModal(productID) {
            isModalEdit = false;
            var product = $('#product-' + productID).data('product');

            showProductDetailModal(productID, product);
        }

        function showProductDetailModal(productID, product, posCartID = null) {
            $('#productDetailModal').modal('show');

            if (isModalEdit) {
                $('#addToCartBtn').text("{{ __('Update') }}");
                $('#addToCartBtn').off('click');
                $('#addToCartBtn').on('click', updateToCart);
            } else {
                $('#addToCartBtn').text("{{ __('Confirm') }}");
                $('#addToCartBtn').off('click');
                $('#addToCartBtn').on('click', addToCart);
            }

            selectedProductID = productID;
            if (!product || product.length == 0) {
                product = $('#product-' + productID).data('product');
            }
            var posCartItem = posCartBasket.find(item => item.id == productID);

            if (posCartID) {
                posCartItem = posCartBasket.find(item => item.pos_cart_id == posCartID);
            }

            if (product.quantity == 0) {
                $('#addToCartBtn').text("{{ __('Out of Stock') }}");
                $('#addToCartBtn').attr('disabled', true);
            } else {
                $('#addToCartBtn').attr('disabled', false);
            }

            $('#productImage').attr('src', product.thumbnail);
            $('#productTitle').text(product.name);
            $('#productUnit').text(product.unit ?? 'pcs');
            $('#productPrice').text(showCurrency(product.discount_price > 0 ? product.discount_price : product
                .price));

            if (product.discount_price > 0) {
                $('#productOriginalPrice').text(showCurrency(product.price));
            } else {
                $('#productOriginalPrice').css("display", "none");
            }

            if (product.discount_percentage > 0) {
                $('#productDiscount').text(product.discount_percentage + '% OFF');
            } else {
                $('#productDiscount').css("display", "none");
            }

            if (posCartItem) {
                $('#quantityNumber').val(posCartItem.order_qty);
            } else {
                $('#quantityNumber').val(1);
            }

            var discountPrice = product.discount_price > 0 ? product.discount_price : product.price;
            var mainPrice = discountPrice;
            var originalPrice = product.price;

            $('#productPrice').text(showCurrency(mainPrice));

            if (product.discount_price > 0) {
                $('#productOriginalPrice').text(showCurrency(originalPrice));
            } else {
                $('#productOriginalPrice').css("display", "none");
            }
        }


        const addToCart = () => {
            var quantity = $('#quantityNumber').val();
            var selectedUnit = $('#productUnit').text();
            // add to cart
            $.ajax({
                url: "{{ route('shop.pos.addToCart') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val() ?? null,
                    product_id: selectedProductID,
                    quantity: quantity,
                    unit: selectedUnit,
                },
                success: (response) => {
                    fetchPosCart();
                    $('#productDetailModal').modal('hide');
                },
                error: (error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                }
            });
        }

        const updateToCart = () => {
            var quantity = $('#quantityNumber').val();
            var selectedUnit = $('#productUnit').text();
            // update cart
            $.ajax({
                url: "{{ route('shop.pos.updateCart') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    pos_cart_id: selectedPosCartItemID,
                    product_id: selectedProductID,
                    quantity: quantity,
                    unit: selectedUnit,
                },
                success: (response) => {
                    fetchPosCart();
                    $('#productDetailModal').modal('hide');
                },
                error: (error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                }
            });
        }

        function openProductEditModal(productID, posCartID) {
            isModalEdit = true;
            selectedProductID = productID;
            selectedPosCartItemID = posCartID;
            var product = $('#product-' + productID).data('product');
            if (product) {
                showProductDetailModal(productID, product, posCartID);
            } else {
                fetchProductDetail(productID, function(fetchedProduct) {
                    if (fetchedProduct) {
                        product = fetchedProduct;
                        showProductDetailModal(productID, product, posCartID);
                    } else {
                        console.error('Failed to fetch product for adding to cart.');
                        $('#productDetailModal').modal('hide');
                    }
                });
            }

        }


        function fetchProductDetail(Id, callback) {
            $.ajax({
                url: "{{ route('shop.pos.product.detail') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: Id,
                },
                success: function(response) {
                    product = response.data.productDetail;
                    if (callback && typeof callback === 'function') {
                        callback(response.data.productDetail);
                    }
                },
                error: function(error) {
                    console.error('Error fetching products:', error);
                }
            });
        }

        function deletePosCartItem(posCartID) {
            // delete from cart
            $.ajax({
                url: "{{ route('shop.pos.removeCart') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    pos_cart_id: posCartID,
                    name: $('#name').val()
                },
                success: (response) => {
                    fetchPosCart();
                },
                error: (error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                }
            });
        }

        function applyCoupon() {
            var couponCode = $('#coupon').val();
            // apply coupon
            $.ajax({
                url: "{{ route('shop.pos.applyCoupon') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    coupon_code: couponCode,
                    name: $('#name').val()
                },
                success: (response) => {
                    fetchPosCart();
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                },
                error: (error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                }
            });
        }

        // remove coupon
        function removeCoupon() {
            $('#coupon').val('');
            // remove coupon
            $.ajax({
                url: "{{ route('shop.pos.removeCoupon') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val()
                },
                success: (response) => {
                    fetchPosCart();
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                },
                error: (error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                }
            });
        };

        function selectPaymentMethod(paymentMethod) {
            selectedPaymentMethod = paymentMethod;
            $('#cash').removeClass('active');
            $('#visa').removeClass('active');
            $('#' + paymentMethod).addClass('active');
        }

        function confirmOrder() {
            orderType = 'sale';
            submitOrder();
        }

        function draftOrder() {
            orderType = 'draft';

            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You want to draft this order?') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, draft it!') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    submitOrder();
                }
            });

        }

        function submitOrder() {
            var name = $('#name').val();
            var isLoading = false;

            if (!name) {
                Toast.fire({
                    icon: 'error',
                    title: "{{ __('Please select name') }}"
                });
                return false;
            }

            $.ajax({
                url: "{{ route('shop.pos.submitOrder') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    payment_method: selectedPaymentMethod,
                    customer_id: $('#customerId').val(),
                    order_type: orderType,
                },
                success: (response) => {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    hideOffcanvas();

                    if (response.data.invoice_url != null) {
                        window.open(response.data.invoice_url, '_blank');
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                },
                error: (error) => {

                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                }
            });
        }

        function hideOffcanvas() {
            var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if (offcanvasInstance) {
                offcanvasInstance.hide();
            } else {
                offcanvasInstance = new bootstrap.Offcanvas(offcanvasElement);
                offcanvasInstance.hide();
            }
        }

        $('#customerForm').submit(function(e) {
            e.preventDefault();
            $('#firstNameError').text('');
            $('#lastNameError').text('');
            $('#phoneError').text('');
            $('#emailError').text('');
            $.ajax({
                url: "{{ route('shop.pos.customerStore') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    first_name: $('input[name="first_name"]').val(),
                    last_name: $('input[name="last_name"]').val(),
                    phone: $('input[name="phone"]').val(),
                    gender: $('select[name="gender"]').val(),
                    email: $('input[name="email"]').val()
                },
                success: (response) => {
                    $('#customerModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });

                    var customer = $('select[name="customer_id"]');

                    var user = response.data.user;

                    customer.append(
                        `<option value="${user.id}" selected>${user.name}</option>`);
                    customer.val(user.id);

                    $('input[name="first_name"]').val('');
                    $('input[name="last_name"]').val('');
                    $('input[name="phone"]').val('');
                    $('select[name="gender"]').val('');
                    $('input[name="email"]').val('');

                    $('#firstNameError').text('');
                    $('#lastNameError').text('');
                    $('#phoneError').text('');
                    $('#emailError').text('');
                },
                error: (error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });

                    $('#firstNameError').text(error.responseJSON.errors.first_name);
                    $('#lastNameError').text(error.responseJSON.errors.last_name);
                    $('#phoneError').text(error.responseJSON.errors.phone);
                    $('#emailError').text(error.responseJSON.errors.email);
                }
            });
        });

        // function fullscreen() {
        //     var hasFullScreen = localStorage.getItem('hasFullScreen');

        //     if (hasFullScreen) {
        //         // alert('Already in fullscreen');
        //         document.documentElement.requestFullscreen();
        //     } else {
        //         document.exitFullscreen();
        //     }
        // }

        // $('#fullscreen').click(function() {
        //     var hasFullScreen = localStorage.getItem('hasFullScreen') ?? false;
        //     localStorage.setItem('hasFullScreen', !hasFullScreen);
        //     fullscreen();
        // });

        // setTimeout(function() {
        //     $('#fullscreen').trigger('click');
        // },1000);
    </script>
@endpush
@if (module_exists('purchase'))
    @push('scripts')
        <script>
            function skuAddOrUpdate(sku, sl, itemId, cartId, el) {
                console.log(sku, sl, itemId, cartId, el);
                $.ajax({
                    url: "{{ route('shop.pos.addOrUpdateSKU') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        sku: sku,
                        sl: sl,
                        cart_id: cartId,
                        item_id: itemId
                    },
                    success: function(response) {
                        fetchPosCart();
                    },
                    error: function(error) {
                        Toast.fire({
                            icon: 'error',
                            title: error.responseJSON.message
                        });
                        if (el) {
                            $(el).val('');
                        }
                    }
                });
            }
        </script>
    @endpush
@endif
