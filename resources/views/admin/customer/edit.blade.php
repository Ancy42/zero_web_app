@extends('layouts.app')

@section('content')
    <div class="container-fluid my-md-0 my-4">
        <form action="{{ route('admin.customer.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="row h-100vh">
                <div class="col-12 m-auto">
                    <div class="card rounded-12 border-0 shadow-md">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                            <h3 class="m-0">{{ __('Edit Customer') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mt-3">
                                                <x-input label="First Name" name="name" type="text"
                                                    placeholder="Enter Name" required="true" :value="$user->name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mt-3">
                                                <x-input label="Last Name" name="last_name" type="text"
                                                    placeholder="Enter Name" :value="$user->last_name" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        @php
                                            $phone = $user->phone;
                                            $phone = str_replace('(', '', $phone);
                                            $phone = str_replace(')', '', $phone);
                                            $phone = str_replace('-', '', $phone);
                                            $phone = str_replace('+', '', $phone);
                                        @endphp
                                        <x-input label="Phone Number" name="phone" type="number"
                                            placeholder="Enter phone number" required="true" :value="$phone"/>
                                    </div>
                                    <div class="mt-3">
                                        <x-input type="email" name="email" label="Email"
                                            placeholder="Enter Email Address" :value="$user->email" />
                                    </div>

                                    <div class="mt-3">
                                        <x-select label="Gender" name="gender">
                                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>
                                                {{ __('Male') }}
                                            </option>
                                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>
                                                {{ __('Female') }}
                                            </option>
                                        </x-select>
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
                                            <img src="{{ $user->thumbnail ?? 'https://placehold.co/500x500/png' }}" id="preview" alt=""
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
                                        <x-input type="date" name="date_of_birth" label="Date of Birth"
                                        placeholder="Enter Date of Birth" :value="$user->date_of_birth" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-lg btn-outline-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
