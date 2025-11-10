@extends('layouts.app')

@section('header-title', __('Profile Details'))

@section('content')
    <div>
        <h4>
            {{ __('Profile Details') }}
        </h4>
    </div>

    <div class="row mb-3">
        <div class="col-lg-8 mt-3">
            <div class="card rounded-12 position-relative overflow-hidden">
                <div class="card-body shop details p-2 border-bottom pb-3">
                    <div class="banner position-relative">
                        <img class="img-fit" src="{{ $shop->banner }}" />
                    </div>
                    <a href="{{ route('shop.profile.edit', $shop->id) }}" class="editBtn svg-bg">
                        <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit" loading="lazy" />
                        <span>{{ __('Edit') }}</span>
                    </a>
                    <div class="main-content d-flex align-items-center">
                        <div class="logo">
                            <img class="img-fit" src="{{ $shop->logo }}" />
                        </div>
                        <div class="personal">
                            <span class="name h4 mb-1">{{ $shop->name }}</span>
                            <div class="d-flex gap-2 align-items-center ">
                                <div>
                                    @foreach (range(1, 5) as $rating)
                                        @if ($shop->averageRating >= $rating)
                                            <i class="fa-solid fa-star text-warning"></i>
                                        @else
                                            <i class="fa-regular fa-star text-secondary"></i>
                                        @endif
                                    @endforeach
                                </div>
                                <div>
                                    <span class="fw-bold">{{ $shop->averageRating }}</span>
                                    ({{ $shop->reviews->count() }} {{ __('Reviews') }})
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="/shops/{{ $shop->id }}" target="blank"
                                    class="btn btn-outline-primary btn-sm">
                                    {{ __('View Live') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="m-0 p-3 border-bottom">{{ __('User Information') }}</h4>
                <div class="card-body pt-0">
                    <table class="table mb-0">
                        <tr>
                            <td style="width: 180px">{{ __('Name') }}:</td>
                            <td>{{ $shop->user?->name }}</td>
                        </tr>
                        <tr>
                            <td style="width: 180px">{{ __('Phone') }}:</td>
                            <td>{{ $shop->user?->phone }}</td>
                        </tr>
                        <tr>
                            <td style="width: 180px">{{ __('Email') }}:</td>
                            <td>{{ $shop->user?->email }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <h4 class="m-0 p-3 border-bottom">{{ __('Shop Information') }}</h4>
                <div class="card-body pt-0">
                    <table class="table mb-0">
                        <tr>
                            <td style="width: 180px">{{ __('Name') }}:</td>
                            <td>{{ $shop->name }}</td>
                        </tr>
                        <tr>
                            <td style="width: 180px">{{ __('Estimated Delivery') }}:</td>
                            <td>{{ $shop->estimated_delivery_time }}</td>
                        </tr>
                        <tr>
                            <td style="width: 180px">{{ __('Shop Description') }}:</td>
                            <td>{{ $shop->description }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-3">
            <div class="card h-100">
                <h4 class="m-0 p-3 border-bottom">{{ __('Product Information') }}</h4>
                <div class="card-body pt-0">
                    <table class="table mb-0">
                        <tr>
                            <td style="width: 180px">{{ __('Total Products') }}:</td>
                            <td>
                                <span class="fw-bold">{{ $shop->products->count() }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 180px">{{ __('Total Orders') }}:</td>
                            <td>
                                <span class="fw-bold">{{ $shop->orders->count() }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 180px; text-transform: capitalize">{{ __('reviews') }}</td>
                            <td>
                                <span class="fw-bold">{{ $shop->reviews->count() }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
