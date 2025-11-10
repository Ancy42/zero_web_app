@extends('layouts.app')

@section('header-title', __('Create New Employee'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-user"></i> {{ __('Create New') }}
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-10 mx-auto">
            <form action="{{ route('shop.employee.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!--######## User Information ##########-->
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
                                            <x-input label="First Name" name="name" type="text"
                                                placeholder="Enter Name" required="true" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <x-input label="Last Name" name="last_name" type="text"
                                                placeholder="Enter Name" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <x-input label="Phone Number" name="phone" type="number"
                                        placeholder="Enter phone number" required="true" />
                                </div>

                                <div class="mt-3">
                                    <x-select label="Gender" name="gender">
                                        <option value="male">{{ __('Male') }}</option>
                                        <option value="female">{{ __('Female') }}</option>
                                    </x-select>
                                </div>
                                <div class="mt-3">
                                    <x-input type="email" name="email" label="Email" placeholder="Enter Email Address"
                                        required="true" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <h5>
                                        {{ __('Thumbnail') }}
                                        <span class="text-primary">{{ __('(Ratio 1:1 (500 x 500 px))') }}</span>
                                        <span class="text-danger">*</span>
                                    </h5>
                                    @error('thumbnail')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="dropzone-container">
                                    <label for="thumbnail" class="mainThumbnail">
                                        <img src="https://placehold.co/500x500/png" id="preview" alt=""
                                            width="50%" class="dropzone-area">
                                    </label>
                                    <input id="thumbnail" accept="image/*" type="file" data-crop="true"
                                        name="profile_photo" class="d-none" onchange="previewFile(event, 'preview')"
                                        data-preview="preview" data-width="500" data-height="500">
                                    <small class="text-muted d-block">
                                        {{ __('Supported formats: jpg, jpeg, png') }}
                                    </small>
                                </div>

                                <div class="mt-3">
                                    <x-select label="Role" name="role" required="true">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">
                                                {{ __($role->name) }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <x-input type="text" name="password" label="Password" placeholder="Enter Password"
                                    required="true" />
                            </div>

                            <div class="col-lg-6 mt-3">
                                <x-input type="text" name="password_confirmation" label="Confirm Password"
                                    placeholder="Enter Confirm Password" required="true" />
                            </div>
                        </div>

                    </div>
                    <div class="card-footer py-3">
                        <button class="btn btn-primary px-5 py-2.5" type="submit">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
