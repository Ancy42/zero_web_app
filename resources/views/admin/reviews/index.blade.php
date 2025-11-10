@extends('layouts.app')

@section('header-title', __('Reviews'))

@section('content')
    <div>
        <h4>{{ __('Reviews') }}</h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="card">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-responsive-lg border-left-right">
                        <thead>
                            <tr>
                                <th>{{ __('Thumbnail') }}</th>
                                <th style="min-width: 120px">{{ __('Product Name') }}</th>
                                <th style="min-width: 280px">{{ __('Review') }}</th>
                                <th>{{ __('Rating') }}</th>
                                <th>{{ __('Action') }}</th>
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
                                    <td>
                                        {{ Str::limit($review->product?->name, 30, '...') }}
                                    </td>
                                    <td>{{ $review->description }}</td>
                                    <td>
                                        <i class="fa fa-star text-warning"></i>
                                        {{ number_format($review->rating, 1) }}
                                    </td>

                                    <td class="text-center">
                                        <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Status Toggle">
                                            <a href="{{ route('admin.review.toggle', $review->id) }}">
                                                <input type="checkbox" {{ $review->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </a>
                                        </label>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">
                                        {{ __('No reviews found') }}
                                    </td>
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
