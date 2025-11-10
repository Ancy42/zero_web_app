@extends('layouts.app')
@section('header-title', __('Flash Sales'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4 class="mb-0">
            {{ __('Flash Sales') }}
        </h4>

        @hasPermission('admin.flashSale.create')
            <a href="{{ route('admin.flashSale.create') }}" class="btn py-2.5 btn-primary">
                <i class="fa fa-plus-circle"></i>
                {{ __('Create New') }}
            </a>
        @endhasPermission
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                @hasPermission('shop.flashSale.toggle')
                                    <th class="text-center">{{ __('Status') }}</th>
                                @endhasPermission
                                <th style="max-width: 250px">{{ __('Description') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($flashSales as $key => $flashSale)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>
                                    <div class="product-image" style="width: 90px; height: 76px">
                                        <img src="{{ $flashSale->thumbnail }}" alt="thumbnail" style="width: 100%; height: 100%" loading="lazy"/>
                                    </div>
                                </td>

                                <td>{{ $flashSale->name }}</td>

                                <td>{{ $flashSale->start_date . ' - ' . $flashSale->start_time }}</td>
                                <td>{{ $flashSale->end_date . ' - ' . $flashSale->end_time }}</td>

                                @hasPermission('admin.flashSale.toggle')
                                    <td class="text-center">
                                        <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                            data-bs-title="{{ __('Update Publish Status') }}">
                                            <a href="{{ route('admin.flashSale.toggle', $flashSale->id) }}">
                                                <input type="checkbox" {{ $flashSale->status ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </a>
                                        </label>
                                    </td>
                                @endhasPermission

                                <td style="max-width: 250px">
                                    {{ $flashSale->description ?? '--' }}
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @hasPermission('admin.flashSale.product')
                                            <a href="{{ route('admin.flashSale.product', $flashSale->id) }}"
                                                class="btn svg-bg circleIcon" data-bs-toggle="tooltip"
                                                data-bs-placement="left" data-bs-title="{{ __('View Product') }}">
                                                <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="view" loading="lazy" />
                                            </a>
                                        @endhasPermission

                                        @hasPermission('admin.flashSale.edit')
                                            <a href="{{ route('admin.flashSale.edit', $flashSale->id) }}"
                                                class="btn btn-outline-info circleIcon" data-bs-toggle="tooltip"
                                                data-bs-placement="left" data-bs-title="{{ __('Edit') }}">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit" loading="lazy" />
                                            </a>
                                        @endhasPermission

                                        @hasPermission('admin.flashSale.destroy')
                                            <a href="{{ route('admin.flashSale.destroy', $flashSale->id) }}"
                                                class="btn btn-outline-danger circleIcon deleteConfirm" data-bs-toggle="tooltip"
                                                data-bs-placement="left" data-bs-title="{{ __('Delete') }}">
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
            {{ $flashSales->links() }}
        </div>

    </div>
@endsection
