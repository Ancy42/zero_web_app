@extends('layouts.app')

@section('header-title', __('Supplier'))

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="w-100 page-title-heading d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    {{ __('Supplier') }}
                    <div class="page-title-subheading">
                        {{ __('This is a list of all Supplier') }}
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center gap-md-4">
                    <div class="d-flex gap-2 gap-md-3">
                        <form action="" class="d-flex align-items-center justify-content-between gap-3">
                            <div class="input-group w-400">
                                <input type="text" name="search" class="form-control" placeholder="Search ..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="input-group-text btn btn-primary">
                                    <i class="fa fa-search"></i>{{ __('Search') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    @hasPermission('shop.supplier.create')
                        <a href="{{ route('shop.supplier.create') }}" class="btn py-2 btn-primary">
                            <i class="fa fa-plus-circle"></i>
                            {{ __('Create New supplier') }}
                        </a>
                    @endhasPermission
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">


        <div class="mb-4 " id="listItem">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive-lg">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th class="text-center">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="text-center">
                                        {{ __('Action') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($suppliers as $key => $supplier)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td class="w-30">
                                            <div class="d-flex justify-content-start">
                                                <img class="rounded-circle" width="60"
                                                    src="{{ $supplier->thumbnail }}" />
                                                <div class="ms-3 mt-2">
                                                    <strong>{{ $supplier->name }} </strong> <br>
                                                    <span class="text-color">{{ $supplier->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td class="text-center">
                                            <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="{{ __('Click here to change status') }}">
                                                @hasPermission('shop.supplier.toggle')
                                                    <a href="{{ route('shop.supplier.toggle', $supplier->id) }}">
                                                        <input type="checkbox" {{ $supplier->is_active ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </a>
                                                @else
                                                    <input type="checkbox" {{ $supplier->is_active ? 'checked' : '' }}>
                                                @endhasPermission
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            @hasPermission('shop.supplier.edit')
                                                <a class="btn btn-outline-info circleIcon"
                                                    href="{{ route('shop.supplier.edit', $supplier->id) }}">
                                                    <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                        loading="lazy" />
                                                </a>
                                            @endhasPermission

                                            @hasPermission('shop.supplier.show')
                                                <a class="btn btn-primary-info circleIcon svg-bg"
                                                    href="{{ route('shop.supplier.show', $supplier->id) }}">
                                                    <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="view"
                                                        loading="lazy" />
                                                </a>
                                            @endhasPermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection

@push('css')
 <link rel="stylesheet" href="{{ asset('modules/purchase/css/style.css') }}">
@endpush
