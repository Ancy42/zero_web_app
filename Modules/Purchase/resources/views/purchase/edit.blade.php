@extends('layouts.app')

@section('content')
    <div class="container-fluid my-md-0 my-4">

        @foreach ($errors->all() as $error)
            <p class="text-danger">{{ $error }}</p>
        @endforeach

        <form action="{{ route('shop.purchase.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div>
                                <x-input label="Purchase Name or Title" name="name" :value="$purchase->name" type="text"
                                    placeholder="Enter name" />
                            </div>

                            <div class="row mt-3">

                                <div class="col-12 col-md-6 mb-3">
                                    <x-input type="text" id="datepicker" label="Received Date" name="receive_date"
                                        required="true" :value="$purchase->receive_date" placeholder="mm/dd/yyyy" autocomplete="off" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <x-select label="Supplier" name="supplier_id" required="true">
                                        <option value="">{{ __('Select Supplier') }}</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>

                            <div>
                                <label class="form-label mb-1">{{ __('Notes') }}</label>
                                <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="2"
                                    placeholder="Enter short notes">{{ $purchase->note }}</textarea>
                                @error('note')
                                    <p class="text text-danger m-0">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 dropzone-container">
                            <div>
                                <h5>{{ __('Purchase Slip') }}</h5>
                                @error('slip_image')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <label for="thumbnail" class="flashSaleThumbnail">
                                <img src="{{ $purchase->thumbnail ?? 'https://placehold.co/600x400?text=Lot+Slip+Photo' }}"
                                    id="preview" alt="slip" width="100%" class="dropzone-area">
                            </label>
                            <input id="thumbnail" accept="image/*" data-preview="preview" data-width="500" data-height="500"
                                type="file" name="slip_image" class="d-none" onchange="previewFile(event, 'preview')">
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between flex-wrap py-3">
                    <a href="{{ route('shop.purchase.index') }}" class="btn btn-light px-4 py-2">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-lg btn-primary rounded py-2 px-5">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#datepicker").datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true,
            });
        });
    </script>
@endpush
