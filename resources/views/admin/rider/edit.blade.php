@extends('layouts.app')

@section('header-title', __('Edit Driver'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-user"></i>{{ __('Edit Driver') }}
        </div>
    </div>

    <form action="{{ route('admin.rider.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12 mx-auto">
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
                                    <div class="col-lg-6 mt-3">
                                        <x-input label="First Name" name="first_name" type="text"
                                            placeholder="Enter first name" :value="$user?->name" required="true" />
                                    </div>

                                    <div class="col-lg-6 mt-3">
                                        <x-input label="Last Name" name="last_name" type="text"
                                            placeholder="Enter last name" :value="$user->last_name" />
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <x-input label="Phone Number" name="phone" type="number"
                                        placeholder="Enter phone number" :value="$user?->phone" required="true" />
                                </div>

                                <div class="mt-3">
                                    <x-input type="email" name="email" label="Email" placeholder="Enter Email Address"
                                        :value="$user?->email" />
                                </div>

                                <div class="mt-3">
                                    <x-select label="{{ __('Gender') }}" name="gender">
                                        <option value="male" {{ $user?->gender == 'male' ? 'selected' : '' }}>
                                            {{ __('Male') }}
                                        </option>
                                        <option value="female" {{ $user?->gender == 'female' ? 'selected' : '' }}>
                                            {{ __('Female') }}
                                        </option>
                                        <option value="other" {{ $user?->gender == 'other' ? 'selected' : '' }}>
                                            {{ __('Other') }}
                                        </option>
                                    </x-select>
                                </div>

                                <div class="mt-3">
                                    <x-input type="text" name="driving_lience" label="Driving License"
                                        placeholder="Enter License" :value="$user?->driving_lience" />
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
                                        <img src="{{ $user->thumbnail ?? 'https://placehold.co/500x500/png' }}"
                                            id="preview" alt="" width="50%" class="dropzone-area">
                                    </label>
                                    <input id="thumbnail" accept="image/*" type="file" data-crop="true"
                                        name="profile_photo" class="d-none" onchange="previewFile(event, 'preview')"
                                        data-preview="preview" data-width="500" data-height="500">
                                    <small class="text-muted d-block">
                                        {{ __('Supported formats: jpg, jpeg, png') }}
                                    </small>
                                </div>

                                <div class="mt-3">
                                    <x-input type="date" name="date_of_birth" label="Date of Birth" :value="$user?->date_of_birth" />
                                </div>

                                <div class="mt-3">
                                    <x-input type="text" name="vehicle_type" label="Vehicle Type"
                                        placeholder="Enter Vehicle Type" :value="$user?->vehicle_type" required="true" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary py-2">
                                {{ __('Save And Update') }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
