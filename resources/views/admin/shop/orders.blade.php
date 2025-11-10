@extends('layouts.app')

@section('header-title', __('Shop Orders'))

@section('content')

    <div class="container-fluid">

        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap">
                <h4 class="m-0">{{ __('Shop Orders') }}</h4>
            </div>
            <div class="card-body">
                @include('admin.shop.header-nav')

                <div class="table-responsive mt-3">

                    <table class="table table-responsive-lg border-left-right">
                        <thead>
                            <tr>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Order Date') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Total Amount') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->prefix . $order->order_code }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->customer?->user?->name }}</td>
                                    <td>
                                        {{ showCurrency($order->payable_amount) }}
                                        <br>
                                        <span
                                            class="badge rounded-pill text-bg-primary">{{ $order->payment_status }}</span>
                                    </td>
                                    <td>{{ $order->payment_method }}</td>
                                    <td>
                                        <a href="{{ route('admin.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-title="view order details"
                                            class="circleIcon svg-bg">
                                            <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                                loading="lazy" />
                                        </a>
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
            {{ $orders->links() }}
        </div>

    </div>
@endsection
