@extends('layouts.app')
@section('header-title', __('Ads'))
@section('header-subtitle', __('Manage Ads'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <div class="d-flex align-items-center flex-wrap gap-1">
            <h4>{{ __('Ads List') }}</h4>
            <span>({{__('max 2 ads show in home page')}})</span>
        </div>

        @hasPermission('admin.ad.create')
            <a href="{{ route('admin.ad.create') }}" class="btn py-2 btn-primary">
                <i class="fa fa-plus-circle"></i>
                {{__('Create New')}}
            </a>
        @endhasPermission
    </div>

    <div class="container-fluid mt-3">

        <div class="my-3 card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-lg">
                        <thead>
                            <tr>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Title') }}</th>
                                @hasPermission('admin.ad.toggle')
                                <th class="text-center">{{ __('Status') }}</th>
                                @endhasPermission
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($ads as $ad)
                            <tr>
                                <td>
                                    <img src="{{ $ad->thumbnail }}" height="76">
                                </td>

                                <td>{{ $ad->title }}</td>

                                @hasPermission('admin.ad.toggle')
                                <td class="text-center">
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Status Toggle">
                                        <a href="{{ route('admin.ad.toggle', $ad->id) }}">
                                            <input type="checkbox" {{ $ad->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>
                                @endhasPermission

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @hasPermission('admin.ad.edit')
                                            <a href="{{ route('admin.ad.edit', $ad->id) }}" class="btn btn-outline-info btn-sm circleIcon">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit" loading="lazy" />
                                            </a>
                                        @endhasPermission

                                        @hasPermission('admin.ad.destroy')
                                            <a href="{{ route('admin.ad.destroy', $ad->id) }}" class="btn btn-outline-danger btn-sm deleteConfirm circleIcon">
                                                <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="delete" loading="lazy" />
                                            </a>
                                        @endhasPermission
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $ads->links() }}
        </div>

    </div>
@endsection

