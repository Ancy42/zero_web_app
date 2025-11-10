@extends('layouts.app')

@section('header-title', __('Edit Offer Banner'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-image"></i> {{ __('Edit Offer Banner') }}
        </div>
    </div>

    <form action="{{ route('admin.offerBanner.update', $offerBanner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <h5>
                                {{ __('Offer Banner') }}
                                <span class="text-danger">*</span>
                            </h5>
                        </div>
                        @php
                            $banner=$offerBanner->image ?? 'assets/offerBanner' . '/' . $offerBanner->homeTheme->theme_name  . '/' . $offerBanner->alias . '.png';
                        @endphp
                        <x-image-picker name="thumbnail" :value="$banner" />
                        @error('thumbnail')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                    </div>
                    <div class="col-6"></div>
                    <div class="col-6 mt-3">
                        <div class="mb-2">
                            <h5>
                                {{ __('Link') }}
                            </h5>
                        </div>
                        <input type="text" name="link" class="form-control" value="{{ $offerBanner->link }}" />
                        @error('link')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>


                </div>
            </div>
            {{-- <div class="card-footer"> --}}
                <div class="d-flex gap-3 flex-wrap justify-content-start align-items-center w-100 p-4">
                    <button type="submit" class="btn btn-lg btn-primary rounded py-2.5 px-5">
                        {{ __('Submit') }}
                    </button>
                </div>
            {{-- </div> --}}
        </div>
    </form>
@endsection
