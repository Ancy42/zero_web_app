@extends('layouts.app')
@section('header-title', __('Edit Ad'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-image"></i> {{ __('Edit Ad') }}
        </div>
    </div>
    <form action="{{ route('admin.ad.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">

            <div class="col-md-6">
                <div class="card mt-3 h-100">
                    <div class="card-body">
                        <div class="">
                            <x-input label="Title" name="title" type="text" placeholder="Enter Short Title"
                                :value="$ad->title" />
                        </div>

                        <div class="mt-4">
                            <div>
                                <h5>
                                    {{ __('Ad ') }}
                                    <span class="text-primary bg-light"> Ratio (400 x 250 px)</span>
                                    <span class="text-danger">*</span>
                                </h5>
                                @error('banner')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <x-image-picker name="banner" :value="$ad->image" />
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button class="btn btn-primary py-2 px-5">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection
