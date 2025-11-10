@extends('layouts.app')

@section('header-title', __('Orders'))

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="">
                <div class="d-flex justify-content-end align-items-center  gap-4 mb-2">
                    <div>
                        <select name="status" id="" class="form-select">
                            <option disabled selected>{{ __('Delivery Status') }}</option>
                            <option value="">{{ __('All') }}</option>
                            @foreach ($orderStatuses as $status)
                                <option value="{{ str_replace(' ', '_', $status->value) }}"
                                    {{ request('status') == str_replace(' ', '_', $status->value) ? 'selected' : '' }}>
                                    {{ __($status->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="payment_status" id="" class="form-select">
                            <option disabled selected>{{ __('Payment Status') }}</option>
                            <option value="">{{ __('All') }}</option>
                            @foreach ($paymentStatus as $status)
                                <option value="{{ str_replace(' ', '_', $status->value) }}"
                                    {{ request('payment_status') == str_replace(' ', '_', $status->value) ? 'selected' : '' }}>
                                    {{ __($status->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($businessModel == 'multi')
                        <div>
                            <select name="shop_id" id="" class="form-select">
                                <option disabled selected>{{ __('Shop') }}</option>
                                <option value="">{{ __('All') }}</option>
                                @foreach ($shops as $shop)
                                    <option value="{{ str_replace(' ', '_', $shop->id) }}"
                                        {{ request('shop_id') == str_replace(' ', '_', $shop->id) ? 'selected' : '' }}>
                                        {{ __($shop->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        <a href="{{ route('admin.order.index') }}" class="btn btn-dark"><i class="fa fa-refresh"></i></a>
                    </div>

                    <div class="input-group " style="max-width: 400px">
                        <input type="text" class="form-control" placeholder="Search here" aria-label="Username"
                            aria-describedby="basic-addon1" name="search" value="{{ request('search') }}">
                        <button type="submit" class="input-group-text" id="basic-addon1"><i
                                class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            <div class="cardTitleBox">
                <h4 class="card-title ">
                    {{ __('Orders List') }}
                </h4>
            </div>
            <div class="table-responsive">

                <table class="table border-left-right table-responsive-lg">
                    <thead>
                        <tr>
                            <th style="min-width: 85px">{{ __('Order ID') }}</th>
                            <th>{{ __('Order Date') }}</th>
                            <th>{{ __('Customer') }}</th>
                            @if ($businessModel == 'multi')
                                <th>{{ __('Shop') }}</th>
                            @endif
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Delivery Status') }}</th>
                            <th>{{ __('Payment Method') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="w-auto">#{{ $order->prefix . '-' . $order->order_code }}</td>
                                <td class="w-min">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td class="w-min">{{ $order->customer?->user?->name }}</td>

                                @if ($businessModel == 'multi')
                                    <td class="w-min">
                                        {{ $order->shop?->name }}
                                    </td>
                                @endif
                                <td class="w-min">
                                    {{ showCurrency($order->payable_amount) }}
                                    <br>
                                    <span
                                        class="badge rounded-pill {{ $order->payment_status->value === 'Paid' ? 'text-bg-success' : 'text-bg-warning' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td class="w-min">{{ $order->order_status }}</td>
                                <td class="w-min">{{ $order->payment_method }}</td>
                                <td class="w-min">
                                    @hasPermission('admin.order.show')
                                        <a href="{{ route('admin.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-title="{{ __('view details') }}"
                                            class="circleIcon svg-bg">
                                            <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                                loading="lazy" />
                                        </a>
                                    @endhasPermission
                                    <a href="{{ route('shop.download-invoice', $order->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-title="{{ __('Download Invoice') }}"
                                        class="circleIcon btn-outline-secondary">
                                        <img src="{{ asset('assets/icons-admin/download-alt.svg') }}" alt="icon"
                                            loading="lazy" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">
                                    {{ __('No order found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>
    </div>

    <div class="my-3">
        {{ $orders->links() }}
    </div>

@endsection
