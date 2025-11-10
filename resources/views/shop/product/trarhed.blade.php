@extends('layouts.app')
@section('header-title', __('Trashed Product List'))

@section('content')
    <div class="d-flex gap-2 justify-content-between mb-2">
        <h4 class="mb-0">{{ __('Trashed Product List') }}</h4>
    </div>

    <div class="container-fluid">

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
                                        <div class="d-flex gap-3 justify-content-center">
                                            @hasPermission('shop.product.show')
                                                <a href="{{ route('shop.product.show', $product->id) }}"
                                                    class="circleIcon btn-outline-primary">
                                                    <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                                        loading="lazy" />
                                                </a>
                                            @endhasPermission
                                            @hasPermission('shop.product.restore')
                                                <a href="{{ route('shop.product.restore', $product->id) }}"
                                                    class="circleIcon btn-outline-info confirmApprove">
                                                    <img src="{{ asset('assets/icons-admin/rotate.svg') }}" alt="icon"
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

        <div class="my-3">
            {{ $products->withQueryString()->links() }}
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(".confirmApprove").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to restore this product",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, restore it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
