@extends('layouts.app')
@section('header-title', __('Generate Barcode'))

@section('content')
    <div class="container-fluid mt-3">

        <form action="" method="GET">
            <div class="card my-3">
                <div class="card-body">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm d-flex flex-column h-100 border-0">
                                <img src="{{ $product->thumbnail }}" alt="" class="card-img-top rounded" loading="lazy"
                                    width="100%" style="height: 200px; object-fit: cover" />
                                <div class="card-body h-100 ps-0">
                                    <div class="mt-3">
                                        <h5 class="card-title">{{ Str::limit($product->name, 50, '...') }}</h5>

                                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                                            <p class="card-text m-0 d-flex align-items-center gap-1">
                                                <strong>{{ showCurrency($product->price) }}</strong>
                                                @if ($product->discount_price)
                                                    <span class="badge bg-primary rounded-pill">
                                                        {{ showCurrency($product->discount_price) }}
                                                    </span>
                                                @endif
                                            </p>

                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fa fa-star text-warning"></i>
                                                ({{ number_format($product->reviews->avg('rating'), 1) }})
                                            </div>

                                            <div class="d-flex align-items-center gap-1">
                                                <span>{{ __('Code') }}:</span>
                                                <span class="fw-bold">{{ $product->code }}</span>
                                            </div>
                                        </div>
                                        <p>
                                            {{ $product->short_description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                {{-- <span>{{ __('Quantity') }}</span> --}}

                                <div class="d-flex gap-2 justify-content-center">

                                    <div class="input-group">
                                        <input  type="number" name="qty" class="form-control" value="{{ $quantities }}" />
                                        <button type="submit" class="btn btn-outline-primary py-2 px-4">
                                            <i class="fa-solid fa-barcode"></i>
                                        </button>
                                    </div>

                                    <a href="{{ route('shop.product.barcode', $product->id) }}" class="btn btn-outline-danger">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </a>

                                    <button type="button" class="btn btn-outline-success" onclick="print()">
                                        <i class="fa-solid fa-print"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="barcodeBox" id="printelement">
                                @for ($i = 0; $i < $quantities; $i++)
                                    <div class="productBarcode">
                                        <div class="siteName">{{ $generaleSetting?->name ?? 'ReadyCommerce' }}</div>
                                        <div class="name">{{ $product->name }}</div>
                                        <div class="price">
                                            {{ $generaleSetting?->currency ?? '$' }}{{ number_format($product->discount_price > 0 ? $product->discount_price : $product->price, 2) }}
                                        </div>
                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->code, 'I25', 3, 28) }}"
                                            alt="barcode" />
                                        <div class="code">{{ __('Code') }}: {{ $product->code }}</div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function print() {
            $("#printelement").print();
        }
    </script>
@endpush
