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
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        <a href="{{ route('shop.order.index') }}" class="btn btn-dark"><i class="fa fa-refresh"></i></a>
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
                <table class="table table-responsive-lg">
                    <thead>
                        <tr>
                            <th>{{ __('Order ID') }}</th>
                            <th>{{ __('Order Date') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                            <th>{{ __('Payment Method') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    #{{ $order->prefix . '-' . $order->order_code }}
                                </td>
                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $order->customer?->user?->name }}</td>
                                <td>
                                    {{ showCurrency($order->payable_amount) }}
                                    <br>
                                    <span
                                        class="badge rounded-pill {{ $order->payment_status->value === 'Paid' ? 'text-bg-success' : 'text-bg-warning' }}">
                                        {{ __($order->payment_status->value) }}
                                    </span>
                                </td>
                                <td>{{ __($order->payment_method->value) }}</td>
                                <td>{{ __($order->order_status->value) }}</td>
                                <td>
                                    @hasPermission('shop.order.show')
                                        <a href="{{ route('shop.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-title="{{ __('view order details') }}"
                                            class="circleIcon btn-outline-primary svg-bg">
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
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

    <div class="my-3">
        {{ $orders->links() }}
    </div>
@endsection
