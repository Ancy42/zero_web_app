@extends('layouts.app')

@section('header-title', __('Welcome Back,') . ' ' . Str::limit(auth()->user()?->name, 20))
@section('header-subtitle', __('Monitor your business analytics and statistics.'))

@section('content')

    <!-- Flash Deal Alert -->
    @if ($flashSale)
        <div>
            <div class="alert flash-deal-alert d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex flex-column">
                    <div class="deal-text">{{ $flashSale->name }}</div>
                    <div class="deal-title">{{ __('Coming Soon') }}</div>
                </div>
                <div class="countdown d-flex align-items-center">
                    <!-- Days -->
                    <div class="countdown-section">
                        <div class="countdown-label">{{ __('Days') }}</div>
                        <div id="days" class="countdown-time">00</div>
                    </div>
                    <!-- Hours -->
                    <div class="countdown-section">
                        <div class="countdown-label">{{ __('Hours') }}</div>
                        <div id="hours" class="countdown-time">00</div>
                    </div>
                    <!-- Minutes -->
                    <div class="countdown-section">
                        <div class="countdown-label">{{ __('Minutes') }}</div>
                        <div id="minutes" class="countdown-time">00</div>
                    </div>
                    <!-- Seconds -->
                    <div class="countdown-section">
                        <div class="countdown-label">{{ __('Seconds') }}</div>
                        <div id="seconds" class="countdown-time">00</div>
                    </div>
                </div>
                @hasPermission('shop.flashSale.show')
                    <a href="{{ route('shop.flashSale.show', $flashSale->id) }}" class="btn btn-primary py-2.5 addBtn">
                        {{ __('Add Product') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    @endif
    <!-- End Flash Deal Alert -->

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dashboard-box item-1">
                        <h2 class="count">{{ $totalProduct }}</h2>
                        <h3 class="title">{{ __('Total Products') }}</h3>
                        <div class="icon">
                            <img src="{{ asset('assets/icons-admin/dashboard-product.svg') }}" alt="icon"
                                loading="lazy" />
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dashboard-box item-2">
                        <h2 class="count">{{ $totalOrder }}</h2>
                        <h3 class="title">{{ __('Total Orders') }}</h3>
                        <div class="icon">
                            <img src="{{ asset('assets/icons-admin/dashboard-order.svg') }}" alt="icon"
                                loading="lazy" />
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dashboard-box item-4">
                        <h2 class="count">{{ $totalBrand }}</h2>
                        <h3 class="title">{{ __('Total Brands') }}</h3>
                        <div class="icon svg-bg">
                            <img src="{{ asset('assets/icons-admin/brand.svg') }}" alt="icon" loading="lazy" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!---- Order Analytics -->
    @hasPermission('shop.order.index')
        <div class="card mt-3">
            <div class="card-body">
                <div class="cardTitleBox">
                    <h5 class="card-title chartTitle">
                        {{ __('Order Analytics') }}
                    </h5>
                </div>
                @php
                    $icons = [
                        'pending' => asset('assets/icons-admin/clock.svg'),
                        'confirm' => asset('assets/icons-admin/shopping-cart-check.svg'),
                        'processing' => asset('assets/icons-admin/rotate-circle.svg'),
                        'pickup' => asset('assets/icons-admin/delivery-cart-arrow-up.svg'),
                        'delivered' => asset('assets/icons-admin/box-check.svg'),
                        'onTheWay' => asset('assets/icons-admin/truck.svg'),
                        'cancelled' => asset('assets/icons-admin/shopping-cart-times.svg'),
                    ];
                @endphp
                <div class="d-flex flex-wrap gap-3 orderStatus">
                    @foreach ($orderStatuses as $status)
                        <a href="{{ route('admin.order.index', str_replace(' ', '_', $status->value)) }}"
                            class="d-flex status flex-grow-1 {{ Str::camel($status->value) }}">
                            <div class="d-flex align-items-center gap-2 justify-content-between w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $icons[Str::camel($status->value)] }}" alt="icon" loading="lazy" />
                                    <span>{{ __($status->value) }}</span>
                                </div>
                                <div class="icon">
                                    <img src="{{ asset('assets/icons-admin/arrow-export.svg') }}" alt="icon"
                                        loading="lazy" />
                                </div>
                            </div>
                            <span class="count">{{ ${Str::camel($status->value)} }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endhasPermission

    @php
        $user = auth()->user();
        $isAdmin = true;
        if (!$user->hasRole('root') && ($user->shop || $user->myShop)) {
            $isAdmin = false;
        }
    @endphp

    <!---- Shop Wallet -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="cardTitleBox">
                <h5 class="card-title chartTitle">
                    <i class="bi bi-wallet2"></i> {{ __('Shop Wallet') }}
                </h5>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <div class="wallet h-100">
                        <h3 class="balance">{{ showCurrency(auth()->user()?->wallet?->balance) }}</h3>
                        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap w-100">
                            <div>
                                <div class="d-flex align-items-center gap-1 percentUp">
                                    <span>+18.53%</span>
                                    <img src="{{ asset('assets/icons-admin/arrow.svg') }}" alt="icon" loading="lazy" />
                                </div>
                                @hasPermission('shop.withdraw.store')
                                    <div class="title">{{ __('Available Balance') }}</div>

                                    <button class="btn btn-primary py-2 px-4 mt-3" data-bs-toggle="modal"
                                        data-bs-target="#withdrawModal">
                                        {{ __('Withdraw') }}
                                    </button>
                                @endhasPermission
                            </div>
                            <div class="wallet-icon svg-bg">
                                <img src="{{ asset('assets/icons-admin/wallet.svg') }}" alt="" width="100%">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="wallet-others">
                                <div class="amount">{{ showCurrency($pendingWithdraw) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Pending Withdraw') }}</div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons-admin/credit-card-convert.svg') }}"
                                            alt="icon" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wallet-others">
                                <div class="amount">{{ showCurrency($alreadyWithdraw) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Already Withdraw') }}</div>
                                    <div class="icon svg-bg">
                                        <img src="{{ asset('assets/icons-admin/withdraw.svg') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wallet-others">
                                <div class="amount">{{ showCurrency($deniedWithdraw) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Rejected Withdraw') }}</div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons-admin/withdraw-reject.svg') }}"
                                            alt="icon" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wallet-others">
                                <div class="amount">{{ showCurrency($totalWithdraw) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Total Withdraw') }}</div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons/totalEarn.png') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wallet-others">
                                <div class="amount">{{ showCurrency($totalDeliveryCollected) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Delivery Charge Collected') }}</div>
                                    <div class="icon">
                                        <img src="{{ asset('assets/icons/deliveryCharge.png') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wallet-others">
                                <div class="amount">{{ showCurrency($totalPosSales) }}</div>
                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                    <div class="title">{{ __('Total Pos Sales') }}</div>
                                    <div class="icon svg-bg">
                                        <img src="{{ asset('assets/icons-admin/pos-sale.svg') }}" alt="icon" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Withdraw Modal -->
        <form id="withdrawForm" method="POST">
            @csrf
            <div class="modal fade" id="withdrawModal" tabindex="-1">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">{{ __('Withdraw Request') }}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <label class="form-label">
                                    {{ __('Withdraw Amount') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="amount" id="amount" class="form-control"
                                    placeholder="Enter amount"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                    required>

                                <p class="text-danger" id="amount-error"></p>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">
                                    {{ __('Name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter name" required>
                                <span class="text-danger" id="name-error"></span>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">
                                    {{ __('Contact Number') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control"
                                    placeholder="Enter contact number"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                    required>
                                <span class="text-danger" id="contact_number-error"></span>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">{{ __('Any message') }}</label>
                                <textarea name="message" placeholder="Enter message" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" id="submitBtn" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <!-- Orders Overview -->
    <div class="card mt-3">
        <div class="card-body">
            <div class="cardTitleBox">
                <h5 class="card-title chartTitle">
                    {{ __('Order Summary') }} <span style="color: #687387">({{ __('latest 8th orders') }})</span>
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table dashboard">
                    <thead>
                        <tr>
                            <th><strong>{{ __('Order ID') }}</strong></th>
                            <th><strong>{{ __('Qty') }}</strong></th>
                            <th><strong>{{ __('Date') }}</strong></th>
                            <th><strong>{{ __('Status') }}</strong></th>
                            <th><strong>{{ __('Action') }}</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestOrders as $order)
                            <tr>
                                <td class="tableId">#{{ $order->prefix . $order->order_code }}</td>
                                <td class="tableId">
                                    {{ $order->products->count() }}
                                </td>
                                <td class="tableId">
                                    {{ $order->created_at->format('d M, Y') }}
                                </td>
                                @php
                                    $status = Str::ucfirst(str_replace(' ', '', $order->order_status->value));
                                @endphp
                                <td class="tableStatus">
                                    <div class="statusItem">
                                        <div class="circleDot animated{{ $status }}"></div>
                                        <div class="statusText">
                                            <span class="status{{ $status }}">
                                                {{ $order->order_status->value }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="tableAction">
                                    <a href="{{ route('shop.order.show', $order->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-title="Order details"
                                        class="circleIcon btn-sm btn-outline-primary svg-bg">
                                        <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                            loading="lazy">
                                    </a>
                                    <a href="{{ route('shop.download-invoice', $order->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-title="Download Invoice"
                                        class="circleIcon btn-outline-secondary btn-sm">
                                        <img src="{{ asset('assets/icons-admin/download-alt.svg') }}" alt="icon"
                                            loading="lazy">
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Top Selling Products -->
        <div class="col-xxl-4 col-lg-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">
                            <i class="bi bi-bag-check fz-16"></i> {{ __('Top Selling Products') }}
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        @foreach ($topSellingProducts as $product)
                            <a href="{{ route('shop.product.show', $product->id) }}" class="customer-section">
                                <div class="customer-details">
                                    <div class="customer-image">
                                        <img src="{{ $product->thumbnail }}" alt="">
                                    </div>
                                    <div class="customer-about">
                                        <p class="text-dark name">{{ Str::limit($product->name, 30, '...') }}</p>
                                        <p class="order">{{ __('Rating') }}:
                                            {{ number_format($product->reviews->avg('rating'), 1) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="border rounded text-primary px-2 py-1 flex-shrink-0" style="font-size: 13px">
                                    <div>{{ __('Sold') }}: {{ $product->orders_count }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Rating Products -->
        <div class="col-xxl-4 col-lg-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">
                            <i class="bi bi-stars fz-16"></i> {{ __('Top Rating Products') }}
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        @foreach ($topReviewProducts as $product)
                            <a href="{{ route('shop.product.show', $product->id) }}" class="customer-section">
                                <div class="customer-details">
                                    <div class="customer-image">
                                        <img src="{{ $product->thumbnail }}" alt="">
                                    </div>
                                    <div class="customer-about">
                                        <p class="name text-dark">{{ Str::limit($product->name, 30, '...') }}</p>
                                        <p class="order">{{ __('Sold') }}: {{ $product->orders->count() }}</p>
                                    </div>
                                </div>
                                <div class="border rounded text-primary px-2 py-1 flex-shrink-0" style="font-size: 13px">
                                    <div>{{ __('Rating') }}: <i class="bi bi-star-fill text-warning"></i>
                                        {{ number_format($product->average_rating, 1) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Favorite Products -->
        <div class="col-xxl-4 col-lg-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="cardTitleBox">
                        <h5 class="card-title chartTitle">
                            <i class="bi bi-bag-heart fz-16"></i> {{ __('Most Favorite Products') }}
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        @foreach ($topFavorites as $product)
                            <a href="{{ route('shop.product.show', $product->id) }}" class="customer-section">
                                <div class="customer-details">
                                    <div class="customer-image">
                                        <img src="{{ $product->thumbnail }}" alt="">
                                    </div>
                                    <div class="customer-about">
                                        <p class="name text-dark">{{ Str::limit($product->name, 30, '...') }}</p>
                                        <div class="d-flex gap-2 align-items-center">
                                            <p class="order">{{ __('Sold') }}: {{ $product->orders->count() }}</p>
                                            <div class="border-start" style="width: 1px; height: 14px;"></div>
                                            <p class="order">
                                                {{ __('Rating') }}: <i class="bi bi-star-fill text-warning"></i>
                                                {{ number_format($product->average_rating, 1) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border rounded text-danger px-2 py-1 flex-shrink-0" style="font-size: 16px">
                                    <div>{{ $product->favorites_count }} <i class="bi bi-heart-fill text-danger"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        $('#withdrawForm').on('submit', function(e) {
            e.preventDefault();
            const amount = $('#amount').val();
            const name = $('#name').val();
            const contact_number = $('#contact_number').val();
            const message = $('#message').val();
            $('#submitBtn').prop('disabled', true);
            $.ajax({
                url: "{{ route('shop.withdraw.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    amount: amount,
                    name: name,
                    contact_number: contact_number,
                    message: message,
                },
                success: function(response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                },
                error: function(response) {
                    $('#submitBtn').prop('disabled', false);
                    const errors = response.responseJSON.errors;
                    if (errors && errors.amount) {
                        $('#amount').addClass('is-invalid');
                        $('#amount-error').text(errors.amount[0]);
                    }
                    if (errors && errors.name) {
                        $('#name').addClass('is-invalid');
                        $('#name-error').text(errors.name[0]);
                    }
                    if (errors && errors.contact_number) {
                        $('#contact_number').addClass('is-invalid');
                        $('#contact_number-error').text(errors.contact_number[0]);
                    }

                    if (!errors) {
                        Swal.fire({
                            title: response.responseJSON.message,
                            icon: "warning",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Ok"
                        });
                    }
                }

            });
        });
    </script>

    @if ($flashSale)
        <script>
            // Set the start and end date/time
            var startDateAndTime = "{{ $flashSale->start_date }}T{{ $flashSale->start_time }}";
            var endDateAndTime = "{{ $flashSale->end_date }}T{{ $flashSale->end_time }}";
            let startDate = new Date(startDateAndTime).getTime();
            let endDate = new Date(endDateAndTime).getTime();

            // Update the countdown every 1 second
            let countdownInterval = setInterval(() => {
                let now = new Date().getTime();

                // If current time is before the start date, show "Deal Coming" message
                if (now < startDate) {
                    let distanceToStart = startDate - now;

                    // Time calculations for days, hours, minutes, and seconds
                    let days = Math.floor(distanceToStart / (1000 * 60 * 60 * 24));
                    let hours = Math.floor((distanceToStart % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distanceToStart % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distanceToStart % (1000 * 60)) / 1000);

                    // Display the countdown with a "Deal Coming" message
                    document.getElementById("days").innerHTML = String(days).padStart(2, '0');
                    document.getElementById("hours").innerHTML = String(hours).padStart(2, '0');
                    document.getElementById("minutes").innerHTML = String(minutes).padStart(2, '0');
                    document.getElementById("seconds").innerHTML = String(seconds).padStart(2, '0');
                    return;
                }

                // Once the current time is after the start date and before the end date, show the active countdown
                let distance = endDate - now;

                // If the deal has ended, stop the countdown and show the message
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById("days").innerHTML = "00";
                    document.getElementById("hours").innerHTML = "00";
                    document.getElementById("minutes").innerHTML = "00";
                    document.getElementById("seconds").innerHTML = "00";
                    document.querySelector(".deal-text").innerHTML = "Deal Ended!";
                    return;
                }

                // Time calculations for days, hours, minutes, and seconds
                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result
                document.getElementById("days").innerHTML = String(days).padStart(2, '0');
                document.getElementById("hours").innerHTML = String(hours).padStart(2, '0');
                document.getElementById("minutes").innerHTML = String(minutes).padStart(2, '0');
                document.getElementById("seconds").innerHTML = String(seconds).padStart(2, '0');
            }, 1000);
        </script>
    @endif
@endpush
@push('css')
    <style>
        /* Flash Deal Alert Styles */
        .flash-deal-alert {
            background: url("{{ asset('assets/images/flash-sale.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 8px;
            color: white;
            border-radius: 8px;
            padding: 16px 32px;
        }

        .deal-title,
        .deal-text {
            font-size: 24px;
            font-weight: 600;
            color: white;
            margin: 0;
            line-height: 32px;
        }

        /* Countdown Timer Styles */
        .countdown {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .countdown-section {
            text-align: center;
            padding: 4px 8px;
            border-radius: 8px;
            background-color: white;
            min-width: 68px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .countdown-label {
            font-size: 12px;
            color: #000;
        }

        .countdown-time {
            font-size: 20px;
            font-weight: bold;
            color: var(--theme-color);
        }
        .addBtn{
            border-radius: 25px;
            padding: 10px 20px;
        }
    </style>
@endpush
