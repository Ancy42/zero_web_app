@extends('layouts.app')
@section('header-title', __('Create New FlashSale'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-bolt"></i> {{ __('Create New FlashSale') }}
        </div>
    </div>
    <form action="{{ route('admin.flashSale.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div>
                            <x-input label="Name" name="name" type="text" placeholder="Enter name"
                                required="true" />
                        </div>

                        <div class="mt-3">
                            <x-input label="Minimum Discount" onlyNumber="true" name="discount" type="text"
                                required="true" placeholder="Enter discount" />
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-md-6 mb-3">
                                <x-input type="text" id="datepicker" label="Start Date" name="start_date" required="true"
                                    placeholder="mm/dd/yyyy" autocomplete="off" />
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <x-input type="time" id="timepicker" label="Start Time" name="start_time"
                                    required="true" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <x-input type="text" id="datepicker2" label="End Date" name="end_date" required="true"
                                    placeholder="mm/dd/yyyy" autocomplete="off" />
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <x-input type="time" id="timepicker2" label="End Time" name="end_time" required="true" />
                            </div>
                        </div>

                        <div>
                            <label for="" class="form-label mb-1">
                                {{ __('Description') }}
                                <span class="text-danger">*</span>
                            </label>
                            <textarea required name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                placeholder="Enter short description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div>
                            <h5>
                                {{ __('Thumbnail') }}
                                <span class="text-primary bg-light">Ratio 3:2 (600 x 400 px)</span>
                                <span class="text-danger">*</span>
                            </h5>
                            @error('thumbnail')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <x-image-picker name="thumbnail" />
                    </div>


                </div>

            </div>

            <div class="card-footer d-flex justify-content-between flex-wrap py-3">
                <a href="{{ route('admin.flashSale.index') }}" class="btn btn-light px-4 py-2">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-lg btn-primary rounded py-2 px-5">
                    {{ __('Submit') }}
                </button>
            </div>
        </div>
    </form>
@endsection
@push('css')
    <style>
        .flashSaleThumbnail {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 6px;
            border: 2px dashed #99a7ba;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            padding: 4px;
        }

        .flashSaleThumbnail img {
            object-fit: contain;
            width: 100%;
            height: 100%;
            border-radius: 6px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#datepicker").datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true,
                minDate: 1,
            });

            $("#datepicker2").datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                minDate: 1,
            });

            $('#datepicker').on('change', function() {
                let date = $(this).datepicker('getDate');
                date.setDate(date.getDate() + 1);

                $('#datepicker2').datepicker('option', 'minDate', date);
            });

            $('#timepicker').timepicker({
                'timeFormat': 'H:i:s'
            });

            $('#timepicker2').timepicker({
                'timeFormat': 'H:i:s'
            });
        });
    </script>
@endpush
