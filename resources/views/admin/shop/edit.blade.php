@extends('layouts.app')

@section('header-title', __('Edit Shop'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-shop"></i>{{ __('Edit Shop') }}
        </div>
    </div>

    <form action="{{ route('admin.shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mt-3">
            <div class="card-body">

                <div class="d-flex gap-2 border-bottom pb-2">
                    <i class="fa-solid fa-user"></i>
                    <h5>
                        {{ __('User Information') }}
                    </h5>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <x-input label="First Name" name="first_name" type="text" placeholder="Enter Name"
                                        :value="$shop->user?->name" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <x-input label="Last Name" name="last_name" type="text" placeholder="Enter Name"
                                        :value="$shop->user?->last_name" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <x-input label="Phone Number" name="phone" type="number" placeholder="Enter phone number"
                                :value="$shop->user?->phone" required="true" />
                        </div>

                        <div class="mt-3">
                            <x-select label="Gender" name="gender">
                                <option value="male" {{ __($shop->user?->gender ?? '') == 'male' ? 'selected' : '' }}>
                                    {{ __('Male') }}</option>
                                <option value="female" {{ __($shop->user?->gender ?? '') == 'female' ? 'selected' : '' }}>
                                    {{ __('Female') }}</option>
                                <option value="other" {{ __($shop->user?->gender ?? '') == 'other' ? 'selected' : '' }}>
                                    {{ __('Other') }}</option>
                            </x-select>
                        </div>

                        <div class="mt-3">
                            <x-input type="email" name="email" label="Email" placeholder="Enter Email Address"
                                :value="$shop->user?->email" required="true" />
                        </div>

                    </div>
                    <div class="col-lg-6">
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
                                    alt="" width="100%" height="100%" class="dropzone-area">
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

        <!--######## Account Information ##########-->
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
                        <x-input type="text" name="shop_name" label="Shop Name" placeholder="Enter Shop Name"
                            :value="$shop->name" required="true" />
                    </div>

                    <div class="col-md-4 mt-3 mt-md-0">
                        <x-input type="text" name="address" label="Address" placeholder="Enter Address"
                            :value="$shop->address" />
                    </div>

                    <div class="col-md-6 mt-4">
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

                    <div class="col-md-6 mt-4">
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
                </div>

                <div class="mt-3">
                    <label for="">
                        {{ __('Description') }}
                    </label>
                    <textarea name="description" class="form-control" id="description" rows="2" placeholder="Enter Description"
                        onkeyup="checkDescription()">{{ old('description') ?? $shop->description }}</textarea>
                    @error('description')
                        <p class="text text-danger m-0" id="errorDescription">{{ $message }}</p>
                    @enderror
                    <p class="text text-danger m-0" id="descriptionError"></p>
                </div>

                <div class="d-flex justify-content-end mt-4">
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
        function checkDescription() {
            var errDescription = document.getElementById('errorDescription');
            if (errDescription) {
                errDescription.remove();
            }

            if (document.getElementById('description').value.length > 200) {
                document.getElementById('descriptionError').innerHTML =
                    'Description must be less than or equal to 220 characters';
            } else {
                document.getElementById('descriptionError').innerHTML = '';
            }
        }
    </script>
@endpush
