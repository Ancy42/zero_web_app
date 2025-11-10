@extends('layouts.app')

@section('header-title', __('Google ReCaptcha'))

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-8 col-lg-9 m-auto">
                <form action="{{ route('admin.googleReCaptcha.update') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header py-3">
                            <h4 class="m-0">{{ __('Google ReCaptcha Configuration') }}</h4>
                        </div>
                        <div class="card-body pb-4">
                            <div class="mb-3 border rounded p-2 d-flex align-items-center gap-2">
                                <label class="m-0 fw-bold">{{ __('Enable Google ReCaptcha') }}</label>
                                <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                    data-bs-title="Toggle">
                                    <input name="is_active" type="checkbox" {{ $reCaptcha?->is_active ? 'checked' : '' }} />
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="mb-4">
                                <x-input type="text" name="site_key" label="SITE KEY"
                                    placeholder="ex: 6LfrbF0qAAAAAB5hAhrIEmFSSd5_ZN492XsZBvhF" :value="$reCaptcha?->site_key" />
                            </div>

                            <div class="">
                                <x-input type="text" name="secret_key" label="SECRET KEY"
                                    placeholder="ex: 6LfrbF0qAAAAAIVYBH93-R2dJP2gKEp4hHBmRfz8" :value="$reCaptcha?->secret_key" />
                            </div>
                        </div>
                        @hasPermission('admin.googleReCaptcha.update')
                            <div class="card-footer py-3 ">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary py-2">{{ __('Save And Update') }}</button>
                                </div>
                            </div>
                        @endhasPermission
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
