@extends('layouts.app')
@section('header-title', __('Offer Banners'))

@section('content')
    <div class="container-fluid mb-3">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <h4 class="m-0">{{ __('All Offer Banners') }}</h4>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Image') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offerBanners as $offerBanner)
                                <tr>
                                    <td>{{ __($offerBanner->name) }}</td>
                                    <td class="text-muted">
                                        <img src="{{ $offerBanner->banner }}" alt="no img" loading="lazy" width="50">
                                    </td>
                                    @hasPermission('admin.offerBanner.update')
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.offerBanner.edit', $offerBanner->id) }}"
                                                class="btn circleIcon btn-outline-info btn-sm">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                    loading="lazy" />
                                            </a>
                                        </div>
                                    </td>
                                    @endhasPermission
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
