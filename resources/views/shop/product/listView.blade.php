<div class="mb-3 card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border-left-right table-responsive-lg">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('SL') }}.</th>
                        <th>{{ __('Thumbnail') }}</th>
                        <th style="min-width: 150px">{{ __('Product Name') }}</th>
                        <th style="min-width: 100px">{{ __('Brand') }}</th>
                        <th class="text-center">{{ __('Price') }}</th>
                        <th class="text-center" style="min-width: 120px">{{ __('Discount Price') }}</th>
                        @if (function_exists('module_exists') && module_exists('Purchase'))
                            <th class="text-center" style="min-width: 120px">{{ __('Current Stock') }}</th>
                        @endif
                        <th class="text-center">{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                @forelse($products as $key => $product)
                    <tr>
                        <td class="text-center">{{ ++$key }}</td>

                        <td>
                            <img src="{{ $product->thumbnail }}" width="50">
                        </td>

                        <td>{{ Str::limit($product->name, 50, '...') }}</td>
                        <td>
                            {{ $product->brand->name ?? '' }}
                        </td>

                        <td class="text-center">
                            {{ showCurrency($product->price) }}
                        </td>

                        <td class="text-center">
                            {{ showCurrency($product->discount_price) }}
                        </td>
                        @if (function_exists('module_exists') && module_exists('Purchase'))
                            <td class="text-center">
                                @if ($product->quantity > 0)
                                    {{ $product->quantity }}
                                @else
                                    <span class="badge bg-danger">Stock Out</span>
                                @endif

                            </td>
                        @endif
                        <td class="text-center">
                            <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="{{ __('Update product status') }}">
                                <a href="{{ route('shop.product.toggle', $product->id) }}">
                                    <input type="checkbox" {{ $product->is_active ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        </td>

                        <td class="text-center">
                            @hasPermission('shop.product.show')
                                <a href="{{ route('shop.product.show', $product->id) }}"
                                    class="svg-bg btn-outline-primary circleIcon btn-sm" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="{{ __('View Product') }}">
                                    <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon" loading="lazy" />
                                </a>
                            @endhasPermission

                            @hasPermission('shop.product.edit')
                                <a href="{{ route('shop.product.edit', $product->id) }}"
                                    class="btn-outline-info circleIcon btn-sm" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="{{ __('Edit Product') }}">
                                    <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="icon" loading="lazy" />
                                </a>
                            @endhasPermission

                            @hasPermission('shop.product.barcode')
                                <a href="{{ route('shop.product.barcode', $product->id) }}"
                                    class="btn-outline-info circleIcon btn-sm" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="{{ __('Generate Barcode for this product') }}">
                                    <img src="{{ asset('assets/icons-admin/scanner.svg') }}" alt="icon"
                                        loading="lazy" />
                                </a>
                            @endhasPermission

                            @hasPermission('shop.product.destroy')
                                <a href="{{ route('shop.product.destroy', $product->id) }}"
                                    class="btn-outline-danger circleIcon btn-sm confirmDelete" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="{{ __('Delete Product') }}">
                                    <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="icon" loading="lazy" />
                                </a>
                            @endhasPermission

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
