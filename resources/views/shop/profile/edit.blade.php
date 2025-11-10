@extends('layouts.app')
@section('header-title', __('Edit Shop'))
@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-shop"></i>{{ __('Edit Shop') }}
        </div>
    </div>

    <form action="{{ route('shop.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mt-3">
            <div class="card-body">

                <div class="d-flex gap-2 border-bottom pb-2">
                    <i class="fa-solid fa-user"></i>
                    <h5>
                        {{ __('User Information') }}
                    </h5>
                </div>

                <div class="row">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <x-input label="First Name" name="first_name" type="text" placeholder="First Name"
                                        :value="$shop->user?->name" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <x-input label="Last Name" name="last_name" type="text" placeholder="Last Name"
                                        :value="$shop->user?->last_name" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <x-input label="Phone Number" name="phone" type="number" placeholder="phone number"
                                :value="$shop->user?->phone" required="true" />
                        </div>

                        <div class="mt-3">
                            <x-select label="Gender" name="gender">
                                <option value="male" {{ $shop->user?->gender == 'male' ? 'selected' : '' }}>
                                    {{ __('Male') }}</option>
                                <option value="female" {{ $shop->user?->gender == 'female' ? 'selected' : '' }}>
                                    {{ __('Female') }}</option>
                                <option value="other" {{ $shop->user?->gender == 'other' ? 'selected' : '' }}>
                                    {{ __('Other') }}</option>
                            </x-select>
                        </div>

                        <div class="mt-3">
                            <x-input type="email" name="email" label="Email" required="true"
                                placeholder="Enter Email Address" :value="$shop->user?->email" :readonly="$shop->user_id != auth()->id() ? true : false" />
                        </div>

                    </div>
                    <div class="col-lg-5">
                        <div>
                            <h5>
                                {{ __('Profile Photo ') }}
                                <span class="text-primary bg-light">Ratio 1:1 (500 x 500 px)</span>
                                <span class="text-danger">*</span>
                            </h5>
                            @error('profile_photo')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="dropzone-container">
                            <label for="thumbnail" class="mainThumbnail">
                                <img src="{{ $shop->user?->thumbnail ?? asset('default/default.jpg') }}" id="preview"
                                    alt="" width="50%" class="dropzone-area">
                            </label>
                            <input id="thumbnail" accept="image/*" type="file" data-crop="true" name="profile_photo"
                                class="d-none" onchange="previewFile(event, 'preview')" data-preview="preview"
                                data-width="500" data-height="500">
                            <small class="text-muted d-block">
                                {{ __('Supported formats: jpg, jpeg, png') }}
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!--######## Shop Information ##########-->
        <div class="card mt-4 mb-4">
            <div class="card-body">

                <div class="d-flex gap-2 border-bottom pb-2">
                    <i class="fa-solid fa-user"></i>
                    <h5>
                        {{ __('Shop Information') }}
                    </h5>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <x-input type="text" name="shop_name" label="Shop Name" placeholder="Shop Name" :value="$shop->name"
                            required="true" />
                    </div>

                    <div class="col-md-4 mt-3 mt-md-0">
                        <x-input type="text" name="address" label="Address" placeholder="Address" :value="$shop->address" />
                    </div>

                    <div class="col-lg-4 mt-3">
                        <div>
                            <x-input type="text" name="min_order_amount" label="Minimum Order Amount" :value="$shop->min_order_amount"
                                onlyNumber="true" />
                        </div>
                    </div>

                    <div class="col-md-4 mt-4">
                        <div>
                            <h5>
                                {{ __('Shop logo') }}
                                <span class="text-primary bg-light">Ratio 1:1 (800 x 800 px)</span>
                                <span class="text-danger">*</span>
                            </h5>
                            @error('shop_logo')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <x-image-picker name="shop_logo" :value="$shop->shop_logo" />
                    </div>

                    <div class="col-md-4 mt-4">
                        <div>
                            <h5>
                                {{ __('Shop banner') }}
                                <span class="text-primary bg-light">Ratio 1152 x 864 px</span>
                                <span class="text-danger">*</span>
                            </h5>
                            @error('shop_banner')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-image-picker name="shop_banner" :value="$shop->shop_banner" />

                    </div>

                    <div class="col-md-4 mt-3">
                        <div>
                            <x-input type="text" name="opening_time" label="Opening Time" :value="Carbon\Carbon::parse($shop->opening_time)->format('H:i')"
                                id="timepicker" required="true" />
                        </div>

                        <div class="mt-3">
                            <x-input type="text" name="closing_time" label="Closing Time" :value="Carbon\Carbon::parse($shop->closing_time)->format('H:i')"
                                id="timepicker2" required="true" />
                        </div>

                        <div class="mt-3">
                            <x-input type="text" name="estimated_delivery_time" label="Estimated Delivery"
                                :value="$shop->estimated_delivery_time" required="true" />
                        </div>

                    </div>

                    <div class="col-lg-4 mt-3">
                        <div>
                            <x-input type="text" name="prefix" label="Order ID Prefix" :value="$shop->prefix"
                                required="true" />
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <label for="">
                        {{ __('Description') }}
                    </label>
                    <textarea name="description" class="form-control" id="description" rows="2" placeholder="Enter Description"
                        onkeyup="checkDescription()">{{ old('description') ?? $shop->description }}</textarea>
                    @error('description')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror
                    <p class="text text-danger m-0" id="descriptionError"></p>
                </div>

                <div class="border-top mt-4 mb-4"></div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary py-2 px-5">
                        {{ __('Update') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#timepicker').timepicker({
                'timeFormat': 'H:i:s'
            });

            $('#timepicker2').timepicker({
                'timeFormat': 'H:i:s'
            });
        });

        function checkDescription() {
            if (document.getElementById('description').value.length > 200) {
                document.getElementById('descriptionError').innerHTML =
                    'Description must be less than or equal to 220 characters';
            } else {
                document.getElementById('descriptionError').innerHTML = '';
            }
        }
    </script>
@endpush
