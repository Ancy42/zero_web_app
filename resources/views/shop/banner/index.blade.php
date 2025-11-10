@extends('layouts.app')
@section('header-title', __('Banner List'))
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{__('Banner List')}}
        </h4>

        @hasPermission('shop.banner.create')
        <a href="{{ route('shop.banner.create') }}" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{__('Create New')}}
        </a>
        @endhasPermission
    </div>

    <div class="container-fluid mt-3">

        <div class="my-3 card">
            <div class="card-body">
                <div class="cardTitleBox">
                    <h5 class="card-title chartTitle">
                        {{__('Banner List')}}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-lg">
                        <thead>
                            <tr>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Title') }}</th>
                                @hasPermission(['shop.banner.toggle'])
                                <th class="text-center">{{ __('Status') }}</th>
                                @endhasPermission
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($banners as $banner)
                            <tr>
                                <td>
                                    <img src="{{ $banner->thumbnail }}" height="76">
                                </td>

                                <td>{{ $banner->title }}</td>

                                @hasPermission(['shop.banner.toggle'])
                                <td class="text-center">
                                    <label class="switch mb-0">
                                        <a href="{{ route('shop.banner.toggle', $banner->id) }}">
                                            <input type="checkbox" {{ $banner->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>
                                @endhasPermission

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @hasPermission('shop.banner.edit')
                                        <a href="{{ route('shop.banner.edit', $banner->id) }}" class="btn btn-outline-info btn-sm circleIcon">
                                            <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit" loading="lazy" />
                                        </a>
                                        @endhasPermission
                                        @hasPermission('shop.banner.destroy')
                                        <a href="{{ route('shop.banner.destroy', $banner->id) }}" class="btn btn-outline-danger btn-sm deleteConfirm circleIcon">
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
            {{ $banners->links() }}
        </div>

    </div>
@endsection

