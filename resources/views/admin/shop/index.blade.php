@extends('layouts.app')

@section('header-title', __('Shops'))

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="w-100 page-title-heading d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    {{__('Shops')}}
                    <div class="page-title-subheading">
                        {{__('This is a shops list')}}
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center gap-md-4">
                    <div class="d-flex gap-2 gap-md-3">
                        <button class="gridBtn" id="gridView" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="{{__('Grid View')}}">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button class="gridBtn" id="listView" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="{{__('List View')}}">
                            <i class="fa-solid fa-list-ul"></i>
                        </button>
                    </div>

                    @hasPermission('admin.shop.create')
                    <a href="{{ route('admin.shop.create') }}" class="btn py-2 btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        {{__('Create New Shop')}}
                    </a>
                    @endhasPermission
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <div class="row row-gap mb-4 d-none" id="gridItem">
            @foreach ($shops as $key => $shop)
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
                    <div class="card shadow-sm rounded-12 show-card position-relative overflow-hidden">
                        <div class="card-body shop p-2">
                            <div class="banner">
                                <img class="img-fit" src="{{ $shop->banner }}" />
                            </div>
                            <div class="main-content">
                                <div class="logo">
                                    <img class="img-fit" src="{{ $shop->logo }}" />
                                </div>
                                <div class="personal">
                                    <span class="name">{{ $shop->name }}</span>
                                    <span class="email">{{ $shop->user?->email }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2 px-3 mt-2">
                                <div class="item">
                                    <strong>{{ __('Status') }}</strong>
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{__('Click here to change status')}}">
                                        @hasPermission('admin.shop.status.toggle')
                                            <a href="{{ route('admin.shop.status.toggle', $shop->id) }}">
                                                <input type="checkbox" {{ $shop->user?->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </a>
                                        @else
                                            <input type="checkbox" {{ $shop->user?->is_active ? 'checked' : '' }}>
                                        @endhasPermission
                                    </label>
                                </div>
                                @hasPermission('admin.shop.products')
                                <div class="item">
                                    <strong>{{ __('Products') }}</strong>
                                    <a href="{{ route('admin.shop.products', $shop->id) }}" class="btn btn-secondary btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{__('Click here to see products')}}">
                                        {{ $shop->products->count() }}
                                    </a>
                                </div>
                                @endhasPermission

                                @hasPermission('admin.shop.orders')
                                <div class="item">
                                    <strong>{{ __('Orders') }}</strong>
                                    <a href="{{ route('admin.shop.orders', $shop->id) }}" class="btn btn-primary btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{__('Click here to see orders')}}">
                                        {{ $shop->orders->count() }}
                                    </a>
                                </div>
                                @endhasPermission
                            </div>
                        </div>
                        <div class="overlay">
                            @hasPermission('admin.shop.edit')
                            <a class="icons btn-outline-info" href="{{ route('admin.shop.edit', $shop->id) }}" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-title="Edit">
                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit" loading="lazy" />
                            </a>
                            @endhasPermission
                            @hasPermission('admin.shop.show')
                            <a class="icons svg-bg" href="{{ route('admin.shop.show', $shop->id) }}" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-title="View">
                                <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="view" loading="lazy" />
                            </a>
                            @endhasPermission
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mb-4 d-none" id="listItem">
            <div class="table-responsive">

                <table class="table shopTable table-striped table-responsive-lg">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Logo') }}</th>
                            <th>{{ __('Name') }}</th>
                            @hasPermission('admin.shop.status.toggle')
                            <th>{{ __('Status') }}</th>
                            @endhasPermission
                            @hasPermission('admin.shop.products')
                            <th class="text-center">{{ __('Products') }}</th>
                            @endhasPermission
                            @hasPermission('admin.shop.orders')
                            <th class="text-center">{{ __('Orders') }}</th>
                            @endhasPermission
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($shops as $key => $shop)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <div class="payment-image">
                                        <img class="img-fit" src="{{ $shop->logo }}" />
                                    </div>
                                </td>
                                <td>{{ $shop->name }}</td>
                                @hasPermission('admin.shop.status.toggle')
                                <td>
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Click here to change status')}}">
                                        <a href="{{ route('admin.shop.status.toggle', $shop->id) }}">
                                            <input type="checkbox" {{ $shop->user?->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>
                                @endhasPermission
                                @hasPermission('admin.shop.products')
                                <td class="text-center">
                                    <a href="{{ route('admin.shop.products', $shop->id) }}" class="badge badge-square badge-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{__('Click here to view total products')}}">
                                        {{ $shop->products->count() }}
                                    </a>
                                </td>
                                @endhasPermission
                                @hasPermission('admin.shop.orders')
                                <td class="text-center">
                                    <a href="{{ route('admin.shop.orders', $shop->id) }}"
                                        class="badge badge-square badge-info" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{__('Click here to view total orders')}}">
                                        {{ $shop->orders->count() }}
                                    </a>
                                </td>
                                @endhasPermission
                                <td class="text-center">
                                    @hasPermission('admin.shop.show')
                                    <a class="svg-bg circleIcon"
                                        href="{{ route('admin.shop.show', $shop->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="View">
                                        <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="edit" loading="lazy" />
                                    </a>
                                    @endhasPermission
                                    @hasPermission('admin.shop.edit')
                                    <a href="{{ route('admin.shop.edit', $shop->id) }}"
                                        class="btn-outline-info circleIcon" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Edit">
                                        <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit" loading="lazy" />
                                    </a>
                                    @endhasPermission
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="my-3">
            {{ $shops->links() }}
        </div>
    </div>
@endsection
