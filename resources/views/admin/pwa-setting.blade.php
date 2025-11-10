@extends('layouts.app')

@section('header-title', __('PWA Setting'))

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- form -->
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.pwaSetting.update') }}">
                @csrf @method('POST')

                <label>@lang('Enable PWA') </label><br />
                <input {{ $pwaSetting?->is_active ? 'checked' : '' }} data-bs-toggle="toggle" data-height="35"
                    data-off="@lang('Disable')" data-offstyle="-danger" data-on="@lang('Enable')" data-onstyle="-success"
                    data-size="large" data-width="180px" name="is_active" type="checkbox">

                <!-- row -->
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                value="{{ $pwaSetting?->name ?? config('app.name') }}" placeholder="Enter Name" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('Short Name')
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="short_name" class="form-control"
                                value="{{ $pwaSetting?->short_name ?? config('app.name') }}"
                                placeholder="Enter Short Name" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                @lang('Description')
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="description" class="form-control"
                                value="{{ $pwaSetting?->description ?? 'swapdeal app' }}" placeholder="Enter Description" />
                        </div>
                    </div>

                </div>
                <!-- /.row -->

                <!-- row -->
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>@lang('Theme Color') <span class="text-danger">*</span></label>
                            <input type="color" name="theme_color" class="form-control my-colorpicker"
                                value="{{ $pwaSetting?->theme_color ?? '#46446d' }}" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>@lang('Background Color') <span class="text-danger">*</span></label>
                            <input type="color" name="background_color" class="form-control my-colorpicker"
                                value="{{ $pwaSetting?->background_color ?? '#46446d' }}" />
                        </div>
                    </div>

                </div>
                <!-- /.row -->

                <!-- row -->
                <div class="row">

                    <div class="col-md-8 mt-3">
                        <div class="form-group">
                            <label class="d-block fw-bold">
                                @lang('Screenshot')
                                <span class="label bg-dark">(1080x1920)</span>
                            </label>
                            <div class="logo-box">
                                <img src="{{ asset('/pwa/pwa-screenshot.png') }}" class="img-responsive">
                            </div>
                            <input type="file" name="pwa_screenshot" id="screenshot" class="form-control mt-2">
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="form-group">
                            <label>@lang('Icon')<span class="label bg-dark">512x512</span></label>
                            <div class="logo-box">
                                <img src="{{ asset('/pwa/pwa-512x512.png') }}" class="img-responsive">
                            </div>
                            <input type="file" name="pwa_512" class="form-control mt-2">
                        </div>
                    </div>

                    <div class="col-md-3 mt-3">
                        <div class="form-group">
                            <label>
                                @lang('Icon')
                                <span class="label bg-dark">192x192</span>
                            </label>
                            <div class="logo-box">
                                <img src="{{ asset('/pwa/pwa-192x192.png') }}" class="img-responsive">
                            </div>
                            <input type="file" name="pwa_192" class="form-control mt-2">
                        </div>
                    </div>


                    <div class="col-md-3 mt-3">
                        <div class="form-group">
                            <label>@lang('Icon')<span class="label bg-dark">48x48</span></label>
                            <div class="logo-box">
                                <img src="{{ asset('/pwa/pwa-48x48.png') }}" class="img-responsive">
                            </div>
                            <input type="file" name="pwa_48" class="form-control mt-2">
                        </div>
                    </div>

                    <div class="col-md-3 mt-3">
                        <div class="form-group">
                            <label>@lang('Icon')<span class="label bg-dark">24x24</span></label>
                            <div class="logo-box">
                                <img src="{{ asset('/pwa/pwa-24x24.png') }}" class="img-responsive">
                            </div>
                            <input type="file" name="pwa_24" class="form-control mt-2">
                        </div>
                    </div>

                </div>
                <!-- /.row -->

                <div class="box-footer mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">@lang('Save And Update')</button>
                </div>

            </form>
            <!-- /.form -->
        </div>
    </div>
@endsection
@push('style')
    <style>
        .logo-box {
            width: 100%;
            max-height: 350px;
            border: 2px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            overflow-y: auto;
        }

        .logo-box img {
            object-fit: cover !important;
        }
    </style>
@endpush
