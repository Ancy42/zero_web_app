@extends('layouts.app')

@section('content')
    <div class="container-fluid my-md-0 my-4">
        <form action="{{ route('shop.supplier.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row h-100vh">
                <div class="col-lg-8 m-auto">
                    <div class="card rounded-12 border-0 shadow-md">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-2.5">
                            <h4 class="m-0">{{ __('Edit Supplier') }}</h4>
                        </div>
                        <div class="card-body">

                            <div class="text-center">
                                <div>
                                    <h5>
                                        {{ __('Profile Photo ') }}
                                        <span class="text-primary bg-light">{{ __('Ratio 1:1 (500 x 500 px)') }}</span>
                                    </h5>
                                    @error('profile_photo')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="dropzone-container">
                                    <label for="thumbnail" class="mainThumbnail">
                                        <img src="{{ $supplier->thumbnail ?? 'https://placehold.co/500x500/png' }}"
                                            id="preview" alt="" width="50%" class="dropzone-area">
                                    </label>
                                    <input id="thumbnail" accept="image/*" type="file" data-crop="true"
                                        name="profile_photo" class="d-none" onchange="previewFile(event, 'preview')"
                                        data-preview="preview" data-width="500" data-height="500">
                                    <small class="text-muted d-block">
                                        {{ __('Supported formats: jpg, jpeg, png') }}
                                    </small>
                                </div>
                            </div>

                            <div class="mt-3">
                                <x-input label="Full Name" name="name" type="text" placeholder="Enter Name"
                                    required="true" :value="$supplier->name" />
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <x-input label="Phone Number" name="phone" type="number"
                                        placeholder="Enter phone number" required="true" :value="$supplier->phone" />
                                </div>

                                <div class="col-md-6 mt-3">
                                    <x-input type="email" name="email" label="Email" placeholder="Enter Email Address"
                                        :value="$supplier->email" required="true" />
                                </div>
                            </div>

                            <div class="mt-3">
                                <x-input type="text" name="address" label="Address" placeholder="Enter Address"
                                    :value="$supplier->address" />
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('shop.supplier.index') }}" class="btn btn-lg btn-outline-secondary">
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
