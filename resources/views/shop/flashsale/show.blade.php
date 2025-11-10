@extends('layouts.app')

@section('content')
    <div class="">
        <!-- Flash Deal Details -->
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">
                    {{ __('Flash Deal Details') }}
                </h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <h5>{{ __('Deal Name:') }}</h5>
                        <p>{{ $flashSale->name }}</p>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6>{{ __('Start Date') }}:</h6>
                        <p>
                            {{ $flashSale->start_date }} {{ $flashSale->start_time }}
                        </p>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6>{{ __('End Date') }}:</h6>
                        <p>
                            {{ $flashSale->end_date }} {{ $flashSale->end_time }}
                        </p>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6>{{ __('Minimum Discount') }}:</h6>
                        <p>
                            {{ $flashSale->discount }}%
                        </p>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h6>{{ __('Publish Status') }}:</h6>
                        <p class="badge {{ $flashSale->status ? 'bg-success' : 'bg-danger' }} text-white">
                            {{ $flashSale->status ? __('Active') : __('Inactive') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Section -->
    <div class="mt-3">
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">{{ __('Add New Product') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('shop.flashSale.productStore', $flashSale->id) }}" method="post">
                    @csrf

                    <div>
                        <select id="selectProduct" class="form-control select2" style="width: 100%">
                            <option value="">
                                {{ __('Select Product') }}
                            </option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="productDetails" class="d-none">
                        <div id="selectedProductsContainer" class="mt-3 d-flex flex-column gap-3">
                            <!-- Dynamic product cards will be inserted here -->
                        </div>

                        <div class="mt-3 d-flex justify-content-end border-top pt-2">
                            <button type="submit" class="btn btn-primary py-2.5 px-4">{{ __('Add Product') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Added Products List -->
    <div class="mt-3">
        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">
                    {{ __('Added Products') }}
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th class="text-center">{{ __('Price') }}</th>
                                <th class="text-center">{{ __('Quantity') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($dealProducts as $key => $product)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>
                                    <div class="product-image">
                                        <img src="{{ $product->thumbnail }}">
                                    </div>
                                </td>

                                <td>{{ Str::limit($product->name, 50, '...') }}</td>

                                <td class="text-center">
                                    {{ showCurrency($product->pivot->price) }}
                                </td>

                                <td class="text-center">
                                    {{ $product->pivot->quantity - $product->pivot->sale_quantity }} <br>
                                    <small class="text-muted">
                                        {{ __('Sold') }}: {{ $product->pivot->sale_quantity ?? 0 }}
                                    </small>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @hasPermission('shop.flashSale.product.edit')
                                            <button type="button" onclick="openProductUpdateModal({{ $product }})"
                                                class="btn btn-outline-primary circleIcon" data-bs-toggle="tooltip"
                                                data-bs-placement="left" data-bs-title="{{ __('Edit Product') }}">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                    loading="lazy" />
                                            </button>
                                        @endhasPermission

                                        @hasPermission('shop.flashSale.productRemove')
                                            <a href="{{ route('shop.flashSale.productRemove', ['flashSale' => $flashSale->id, 'product' => $product->id]) }}"
                                                class="btn btn-outline-danger deleteConfirm circleIcon">
                                                <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="delete"
                                                    loading="lazy" />
                                            </a>
                                        @endhasPermission
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

    <!--=== update Modal ===-->
    <form action="#" id="productUpdateForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal fade" id="updateProduct" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                {{ __('Name') }}
                            </label>
                            <p id="updateName"></p>
                        </div>

                        <div class="mb-3">
                            <label for="updatePrice" class="form-label m-0">
                                {{ __('Price') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="updatePrice" name="price"
                                placeholder="Enter Price" required />
                            @error('price')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="updateQuantity" class="form-label m-0">
                                {{ __('Quantity') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="updateQuantity" name="quantity"
                                placeholder="Enter Quantity" required />
                            @error('price')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        function openProductUpdateModal(product) {
            var flashSaleID = "{{ $flashSale->id }}";
            $("#updateName").text(product.name);
            $("#updatePrice").val(product.pivot.price);
            $("#updateQuantity").val(product.pivot.quantity);
            $("#productUpdateForm").attr('action',
                `{{ route('shop.flashSale.product.edit', ['flashSale' => ':flashSale', 'product' => ':product']) }}`
                .replace(':flashSale', flashSaleID).replace(':product', product.id));

            $("#updateProduct").modal('show');
        }

        $(document).ready(function() {

            const flashSale = @json($flashSale);

            const allProducts = @json($products);

            let selectedProducts = [];


            $('#selectProduct').change(function() {
                const selectedOption = $(this).find(':selected');
                const productId = selectedOption.val();
                const productName = selectedOption.data('name');
                const productPrice = selectedOption.data('price');
                const productThumbnail = selectedOption.data('thumbnail');

                if (!productId || selectedProducts.includes(productId)) {
                    return;
                }

                var product = allProducts.find(p => p.id == productId);

                if (product) {
                    console.log(product);

                    selectedProducts.find(p => p.id == productId) ? null : selectedProducts.push(product);
                    renderSelectedProducts();
                }
                $('#selectProduct').val('').trigger('change');
            });

            const renderSelectedProducts = () => {

                const productDetails = $('#productDetails');

                if (selectedProducts.length === 0) {
                    productDetails.addClass('d-none');
                } else {
                    productDetails.removeClass('d-none');
                }

                const selectedProductsContainer = $('#selectedProductsContainer');
                selectedProductsContainer.empty();

                selectedProducts.forEach((product, index) => {
                    const productPrice = product.discount_price > 0 ? product.discount_price : product
                        .price;
                    var discountPercentage = flashSale.discount / 100 * productPrice;
                    const discountPrice = parseFloat((productPrice - discountPercentage).toFixed(2));

                    const productCard = `
                     <div class="product-card" data-product-id="${product.id}">

                            <input type="hidden" name="products[${index}][id]" value="${product.id}">
                            <div class="remove-product-icon remove-product">
                                <i class="fas fa-times"></i>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                <div class="product-image">
                                    <img src="${product.thumbnail}" alt="Product">
                                </div>
                                <div>
                                    <p class="mb-0"><small>Name</small></p>
                                    <p class="product-name">${product.name}</p>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <p class="mb-1"><small>Price:</small></p>
                                <p class="product-price">
                                    ${productPrice}
                                </p>
                            </div>

                            <div class="flex-grow-1">
                                <p class="mb-1"><small>Discount Price:</small></p>
                                <input class="product-input" type="text" name="products[${index}][discount_price]" placeholder="Discount Price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^(\d*\.\d{0,2}|\d*)$/, '$1');" value="${discountPrice}"/>
                            </div>

                            <div class="flex-grow-1">
                                <p class="mb-1"><small>Quantity:</small></p>
                                <input class="product-input" type="text" name="products[${index}][quantity]" placeholder="Quantity" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^(\d*\.\d{0,2}|\d*)$/, '$1');" value="${product.quantity}">
                            </div>
                        </div>
                    `;

                    selectedProductsContainer.append(productCard);
                });
            };

            // Handle product removal
            $(document).on('click', '.remove-product', function() {
                const productCard = $(this).closest('.product-card');
                const productId = productCard.data('product-id');
                selectedProducts = selectedProducts.filter(p => p.id !== productId);

                renderSelectedProducts();
            });

        });
    </script>
@endpush

@push('css')
    <style>
        .selected-products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-card {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            gap: 16px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-3px);
        }

        .product-image img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .product-details {
            width: 100%;
            text-align: center;
        }

        .product-name {
            font-weight: 500;
            font-size: 1rem;
        }

        .product-input {
            flex: 1;
            padding: 5px;
            font-size: 0.9rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }

        .remove-product-icon {
            position: absolute;
            top: -6px;
            right: -6px;
            cursor: pointer;
            font-size: 20px;
            color: #ff5a5a;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            width: 36px;
            height: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background 0.3s, transform 0.2s;
        }

        .remove-product-icon:hover {
            background: #ff5a5a;
            color: #fff;
            transform: scale(1.1);
        }
    </style>
@endpush
