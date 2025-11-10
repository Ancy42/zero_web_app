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

                    @hasPermission('admin.flashSale.toggle')
                        <div class="col-md-6 col-lg-4">
                            <h6>{{ __('Publish Status') }}:</h6>
                            <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-title="{{ __('Update Publish Status') }}">
                                <a href="{{ route('admin.flashSale.toggle', $flashSale->id) }}">
                                    <input type="checkbox" {{ $flashSale->status ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </a>
                            </label>
                        </div>
                    @endhasPermission
                </div>
            </div>
        </div>
    </div>

    <!-- Added Products List -->
    <div class="mt-3">
        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="mb-0">
                    {{ __('Added Products') }}
                </h4>
                @hasPermission('shop.flashSale.show')
                    <a href="{{ route('shop.flashSale.show', $flashSale->id) }}" class="btn btn-primary py-2 addBtn">
                       <i class="fa fa-plus-circle"></i> {{ __('Add Product') }}
                    </a>
                @endhasPermission
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th>{{ __('Shop') }}</th>
                                <th class="text-center">{{ __('Price') }}</th>
                                <th class="text-center">{{ __('Quantity') }}</th>
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

                                <td>
                                    {{ $product->shop?->name }}
                                </td>

                                <td class="text-center">
                                    {{ showCurrency($product->pivot->price) }}
                                </td>

                                <td class="text-center">
                                    {{ $product->pivot->quantity }} <br>
                                    <small class="text-muted">
                                        {{ __('Sold') }}: {{ $product->pivot->sale_quantity ?? 0 }}
                                    </small>
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
@endsection
