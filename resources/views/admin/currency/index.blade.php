@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4 class="m-0"> {{ __('Currencies') }} </h4>

        @hasPermission('admin.currency.create')
            <a href="{{ route('admin.currency.create') }}" class="btn py-2 btn-primary">
                <i class="bi bi-patch-plus"></i>
                {{ __('Create New') }}
            </a>
        @endhasPermission
    </div>

    <div class="mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card rounded-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Symbol') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($currencies as $currency)
                                        <tr>
                                            <td>{{ $currency->name }}</td>
                                            <td>
                                                {{ $currency->symbol }}
                                            </td>
                                            <td>
                                                {{ $currency->rate }}
                                                @if ($currency->is_default)
                                                    <sub>({{ __('Default') }})</sub>
                                                @else
                                                    <sub>{{ __('(From ') . ($defaultCurrency?->name ?? 'USD') . ')' }}</sub>
                                                @endif
                                            </td>
                                            <td>
                                                @hasPermission('admin.currency.edit')
                                                    <a href="{{ route('admin.currency.edit', $currency->id) }}"
                                                        class="btn btn-outline-info circleIcon" data-bs-toggle="tooltip"
                                                        data-bs-placement="left" data-bs-title="{{ __('Edit') }}">
                                                        <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                            loading="lazy" />
                                                    </a>
                                                @endhasPermission

                                                @hasPermission('admin.currency.destroy')
                                                    <a href="{{ route('admin.currency.destroy', $currency->id) }}"
                                                        class="btn btn-outline-danger circleIcon deleteConfirm"
                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                        data-bs-title="{{ __('Delete') }}">
                                                        <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="delete"
                                                            loading="lazy" />
                                                    </a>
                                                @endhasPermission
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">
                                                {{ __('No Data Found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{ $currencies->links() }}

            </div>
        </div>
    </div>
@endsection
