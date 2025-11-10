@extends('layouts.app')

@section('header-title', __('Shop Reviews'))

@section('content')
    <div class="container-fluid">

        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap">
                <h4 class="m-0">{{ __('Shop product reviews') }}</h4>
            </div>
            <div class="card-body">
                @include('admin.shop.header-nav')

                <div class="table-responsive mt-3">

                    <table class="table table-responsive-lg border-left-right">
                        <thead>
                            <tr>
                                <th>{{ __('Thumbnail') }}</th>
                                <th style="min-width: 120px">{{ __('Product Name') }}</th>
                                <th style="min-width: 280px">{{ __('Review') }}</th>
                                <th>{{ __('Rating') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="customer-image">
                                            <img src="{{ $review->product?->thumbnail }}" alt="" width="50">
                                        </div>
                                    </td>
                                    <td>{{ $review->product?->name }}</td>
                                    <td>{{ $review->description }}</td>
                                    <td>
                                        <i class="fa fa-star text-warning"></i>
                                        {{ number_format($review->rating, 1) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __('No Data Found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

        <div class="my-3">
            {{ $reviews->links() }}
        </div>

    </div>
@endsection
