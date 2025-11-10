@extends('layouts.app')
@section('header-title', __('Product List'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3 pb-2">
        <h4 class="mb-0">
            {{ __('Product List') }}
        </h4>
        <div class="d-flex gap-2">
                <a href=" {{ route('shop.product.index', ['view_type' => 'list']) }}"
                    class="btn btn-{{request('view_type') == 'list' ? 'btn btn-primary' : 'secondary'}}"><i class="bi bi-list-ul"></i></a>
                <a href=" {{ route('shop.product.index', ['view_type' => 'grid']) }}"
                    class="btn btn-{{request('view_type') == 'grid' || !request('view_type') ? 'btn btn-primary' : 'secondary'}}"><i class="bi bi-grid"></i></a>
            @hasPermission('shop.product.create')
                <a href="{{ route('shop.product.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus-circle"></i>
                    {{ __('Create New') }}
                </a>
            @endhasPermission
        </div>

    </div>


    <form action="" method="GET" class="card card-body filterForm">

        <input type="hidden" name="view_type" value="{{ request('view_type') }}">

        <div class="d-flex justify-content-end align-items-center  gap-3">
            <div>
                <select class="form-select" name="category" placeholder="Select Category">
                    <option value="">
                        {{ __('Select Category') }}
                    </option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select class="form-select" name="brand" placeholder="All Brand">
                    <option value="">
                        {{ __('All Brand') }}
                    </option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <div class="mt-2 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    <a href="{{ route('shop.product.index','view_type=grid') }}" class="btn btn-dark"><i class="fa fa-refresh"></i></a>
                </div>
            </div>
            <div>
                <div class="input-group" style="max-width: 400px">
                    <input type="text" class="form-control" placeholder="Search here" aria-label="Username"
                        aria-describedby="basic-addon1" name="search" value="{{ request('search') }}">
                    <button type="submit" class="input-group-text btn btn-primary" id="basic-addon1"><i
                            class="fa fa-search"></i></button>
                </div>
            </div>
        </div>


    </form>


    <div class="container-fluid">
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
                            Add Product
                        </a>
                    @endhasPermission
                </div>
            </div>
        @endif
        <!-- End Flash Deal Alert -->


        @if (request('view_type') == 'grid')
        @include('shop.product.gridView')
        @else
        @include('shop.product.listView')
        @endif

        <div class="my-3">
            {{ $products->links() }}
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
                text: "You want to approve this product",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Approve it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
        $(".confirmDelete").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this product",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
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

        .addBtn {
            border-radius: 25px;
            padding: 10px 20px;
        }
    </style>
@endpush
