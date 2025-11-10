@extends('layouts.app')
@section('header-title', __('Create New Language'))

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-7 mt-2 mx-auto ">
                <form action="{{ route('admin.language.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h3 class="m-0">{{ __('Create New Language') }}</h3>
                        </div>
                        <div class="card-body">
                            <div>
                                <x-input type="text" name="title" label="Title" placeholder="Title" value="" />
                            </div>
                            <div class="mt-3">
                                <label class="mb-0">
                                    {{ __('Short Name') }} <small>
                                        ({{ __('only allow English characters') }})</small>
                                </label>
                                <input name="name" oninput="this.value=this.value.replace(/[^a-z]/gi,'')"
                                    class="form-control" placeholder="{{ __('Exm: bn') }}" autocomplete="off" required />
                            </div>


                            <div class="mt-3">
                                <h5>
                                    {{ __('Flag ') }}
                                    <span class="text-primary bg-light">Ratio (300 x 180 px)</span>
                                    <span class="text-danger">*</span>
                                </h5>
                                @error('flag')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="dropzone-container">
                                <label for="thumbnail" class="mainThumbnail">
                                    <img src="{{ $language->flag ?? 'https://placehold.co/500x500/png' }}" id="preview"
                                        alt="" width="30%" class="dropzone-area">
                                </label>
                                <input id="thumbnail" accept="image/*" type="file" required="true" data-crop="true" name="flag"
                                    class="d-none" onchange="previewFile(event, 'preview')" data-preview="preview"
                                    data-width="300" data-height="180">
                                <small class="text-muted d-block">
                                    {{ __('Supported formats: jpg, jpeg, png') }}
                                </small>
                            </div>

                        </div>
                        <div class="card-footer d-flex justify-content-start flex-wrap gap-2 ">
                            <a href="{{ route('admin.language.index') }}" class="btn btn-danger">
                                {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary py-2 px-4">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
