<div class="mb-3 card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border-left-right table-responsive-lg">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('SL') }}.</th>
                        <th>{{ __('Thumbnail') }}</th>
                        <th style="min-width: 150px">{{ __('Product Name') }}</th>
                        <th style="min-width: 100px">{{ __('Shop') }}</th>
                        <th style="min-width: 100px">{{ __('Brand') }}</th>
                        <th class="text-center">{{ __('Price') }}</th>
                        <th class="text-center" style="min-width: 120px">{{ __('Discount Price') }}</th>
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
                            <a class="text-decoration-none text-dark"
                                href="{{ route('admin.shop.show', $product->shop_id) }}">
                                {{ $product->shop->name }}
                            </a>
                        </td>
                        <td>
                            {{ $product->brand->name ?? '' }}
                        </td>

                        <td class="text-center">
                            {{ showCurrency($product->price) }}
                        </td>

                        <td class="text-center">
                            {{ showCurrency($product->discount_price) }}
                        </td>

                        <td class="text-center">
                            @if (!$product->is_approve)
                                <div class="d-flex gap-3 justify-content-center">
                                    @hasPermission('admin.product.approve')
                                        <a href="{{ route('admin.product.approve', $product->id) }}"
                                            class="btn btn-success btn-sm confirmApprove">{{ __('Approved') }}</a>
                                    @endhasPermission
                                    @hasPermission('admin.product.destroy')
                                        <button class="btn btn-danger btn-sm" onclick="confirmDeny({{ $product->id }})">
                                            {{ __('Denied') }}
                                        </button>
                                    @endhasPermission
                                </div>
                            @else
                                <div class="d-flex gap-3 justify-content-center">
                                    @hasPermission('admin.product.show')
                                        <a href="{{ route('admin.product.show', $product->id) }}"
                                            class="circleIcon btn-outline-primary">
                                            <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                                loading="lazy" />
                                        </a>
                                    @endhasPermission
                                </div>
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
    </div>
</div>
