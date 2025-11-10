@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Country List') }}
        </h4>
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">

                <form action="" class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <div class="input-group" style="max-width: 400px">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name') }}"
                            value="{{ request('search') }}">
                        <button type="submit" class="input-group-text btn btn-primary">
                            <i class="fa fa-search"></i> {{ __('Search') }}
                        </button>
                    </div>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#createCountry"
                        class="btn py-2 btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        {{ __('Create New') }}
                    </button>
                </form>

                <div class="table-responsive">
                    <table class="table border table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Phone Code') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($countries as $key => $country)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>{{ $country->name }}</td>

                                <td>{{ $country->phone_code }}</td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @hasPermission('admin.country.create')
                                            <button type="button" class="btn btn-outline-primary circleIcon btn-sm"
                                                onclick="openCountryUpdateModal({{ $country }})">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                    loading="lazy" />
                                            </button>
                                        @endhasPermission
                                        @hasPermission('admin.country.delete')
                                            <a href="{{ route('admin.country.destroy', $country->id) }}"
                                                class="circleIcon btn btn-outline-danger btn-sm deleteConfirm">
                                                <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="delete"
                                                    loading="lazy" />
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
            {{ $countries->links() }}
        </div>

    </div>

    <!--=== Create Color Modal ===-->
    <form action="{{ route('admin.country.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="createCountry">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Add New Country') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <x-input type="text" name="name" label="Name" placeholder="Name" required="true" />
                        </div>

                        <div class="mb-3">
                            <x-input type="text" name="phone_code" label="Phone Code" placeholder="Phone Code"
                                required="true" onlyNumber="true" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--=== update color Modal ===-->
    <form action="" id="updateCountryForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="previous_url" value="{{ url()->previous() }}" />
        <div class="modal fade" id="updateCountry" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Update Country') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <x-input type="text" id="update_name" name="name" label="Name"
                                placeholder="Country Name" required="true" />
                        </div>

                        <div class="mb-3">
                            <x-input type="text" id="update_phone_code" name="phone_code" label="Phone Code" placeholder="Phone Code" required="true" onlyNumber="true" min="1" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const openCountryUpdateModal = (country) => {

            $("#update_name").val(country.name);
            $("#update_phone_code").val(country.phone_code);
            $("#updateCountryForm").attr('action', `{{ route('admin.country.update', ':id') }}`.replace(':id', country
                .id));

            $("#updateCountry").modal('show');
        }
    </script>
@endpush
