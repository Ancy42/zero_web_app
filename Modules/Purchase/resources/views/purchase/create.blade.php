@extends('layouts.app')

@section('content')
    <div class="container-fluid my-md-0 my-4">

        @foreach ($errors->all() as $error)
            <p class="text-danger">{{ $error }}</p>
        @endforeach

        <form action="{{ route('shop.purchase.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div>
                                <x-input label="Purchase Name or Title" name="name" type="text"
                                    placeholder="Enter name" />
                            </div>

                            <div class="row mt-3">

                                <div class="col-12 col-md-6 mb-3 mt-3">
                                    <x-input type="text" id="datepicker" label="Received Date" name="receive_date"
                                        required="true" value="{{ date('m/d/Y') }}" placeholder="mm/dd/yyyy"
                                        autocomplete="off" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="Supplier" class="form-label mb-1"> {{ __('Supplier') }} <span
                                            class="text-danger">*</span>@hasPermission('shop.supplier.create') <button type="button" class="btn btn-primary"
                                            data-bs-toggle="modal" data-bs-target="#addSupplierModal">+</button> @endhasPermission</label>
                                    <select class="form-select select2" id="Supplier" name="supplier_id" required="true">
                                        <option value="">{{ __('Select Supplier') }}</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id', request('supplier_id')) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="form-label mb-1">{{ __('Notes') }}</label>
                                <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="2"
                                    placeholder="Enter short notes">{{ old('note') }}</textarea>
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

                            <label for="thumbnail" class="flashSaleThumbnail ">
                                <img src="https://placehold.co/600x400?text=Lot+Slip+Photo" id="preview" alt="slip"
                                    width="100%" class="dropzone-area">
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

    <div class="modal fade" id="addSupplierModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ __('Add Supplier') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('shop.supplier.store') }}" method="POST" id="supplierForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card rounded-12 border-0 shadow-md">
                            <div class="card-body">
                                <div class="mt-3">
                                    <x-input label="Full Name" name="name" type="text" placeholder="Enter Name"
                                        required="true" />
                                </div>
                                <input type="hidden" name="type" value="ajax">

                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <x-input label="Phone Number" name="phone" type="number"
                                            placeholder="Enter phone number" required="true" />
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <x-input type="email" name="email" label="Email"
                                            placeholder="Enter Email Address" required="true" />
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <x-input type="text" name="address" label="Address"
                                        placeholder="Enter Address" />
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Submit') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush

@push('scripts')
    <script>
        "use strict";
        $(document).ready(function() {
            $("#datepicker").datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true,
            });
        });

        $(document).on('submit', '#supplierForm', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = new FormData(this);
            let actionUrl = form.attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#submitBtn').prop('disabled', true).text('Saving...');
                },
                success: function(response) {
                    toastr.success(response.message);
                    $('#addSupplierModal').modal('hide');
                    form[0].reset();
                    $('#submitBtn').prop('disabled', false).text('Submit');

                    let newOption = new Option(response.supplier.name, response.supplier.id, true,
                    true);
                    $('#Supplier').append(newOption).trigger('change.select2');
                },
                error: function(xhr) {
                    $('#submitBtn').prop('disabled', false).text('Submit');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong.');
                    }
                }
            });
        });
    </script>
@endpush
