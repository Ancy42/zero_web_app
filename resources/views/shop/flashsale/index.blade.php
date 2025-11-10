@extends('layouts.app')
@section('header-title', __('Flash Sales'))
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4 class="mb-0">
            {{ __('Flash Sales') }}
        </h4>
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
                                <th>{{ __('Status') }}</th>
                                <th style="max-width: 250px">{{ __('Description') }}</th>
                                @hasPermission('shop.flashSale.show')
                                    <th class="text-center">{{ __('Action') }}</th>
                                @endhasPermission
                            </tr>
                        </thead>
                        @forelse($flashSales as $key => $flashsale)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>
                                    <div class="product-image" style="width: 90px; height: 76px">
                                        <img src="{{ $flashsale->thumbnail }}" alt="thumbnail"
                                            style="width: 100%; height: 100%" loading="lazy" />
                                    </div>
                                </td>

                                <td>{{ $flashsale->name }}</td>

                                <td>{{ $flashsale->start_date . ' - ' . $flashsale->start_time }}</td>
                                <td>{{ $flashsale->end_date . ' - ' . $flashsale->end_time }}</td>

                                <td>
                                    @if ($flashsale->status)
                                        <span class="badge rounded-pill bg-success d-inline-flex align-items-center justify-content-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span style="line-height: 0">{{ __('Active') }}</span>
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-danger d-inline-flex align-items-center justify-content-center gap-1">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            <span style="line-height: 0">{{ __('Inactive') }}</span>
                                        </span>
                                    @endif
                                </td>

                                <td style="max-width: 250px">
                                    {{ $flashsale->description ?? '--' }}
                                </td>

                                @hasPermission('admin.flashSale.product')
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('shop.flashSale.show', $flashsale->id) }}"
                                                class="btn btn-outline-primary circleIcon" data-bs-toggle="tooltip"
                                                data-bs-placement="left" data-bs-title="{{ __('View Product') }}">
                                                <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="view" loading="lazy" />
                                            </a>
                                        </div>
                                    </td>
                                @endhasPermission
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
