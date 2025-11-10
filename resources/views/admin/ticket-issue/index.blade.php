@extends('layouts.app')

@section('header-title', __('Ticket Issue Types'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4 class="m-0">{{ __('Ticket Issue Types') }}</h4>
        @hasPermission('admin.ticketIssueType.create')
            <button type="button" data-bs-toggle="modal" data-bs-target="#createBrand" class="btn py-2.5 btn-primary">
                <i class="fa fa-plus-circle"></i>
                {{__('Create New')}}
            </button>
        @endhasPermission
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-header py-3">
                <h5 class="card-title m-0">
                    {{__('Ticket Issue Types')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-md">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($ticketIssueTypes as $key => $issueType)
                            @php
                                $serial = $ticketIssueTypes->firstItem() + $key;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $serial }}</td>
                                <td>{{ $issueType->name }}</td>

                                <td>
                                    <label class="switch mb-0">
                                        <a href="{{ route('admin.ticketIssueType.toggle', $issueType->id) }}">
                                            <input type="checkbox" {{ $issueType->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-3 justify-content-center">
                                        @hasPermission('admin.ticketIssueType.edit')
                                        <button type="button" class="btn btn-outline-info btn-sm circleIcon"
                                            onclick="openUpdateModal({{ $issueType }})">
                                            <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="icon" loading="lazy" />
                                        </button>
                                        @endhasPermission
                                        @hasPermission('admin.ticketIssueType.delete')
                                        <a href="{{ route('admin.ticketIssueType.delete', $issueType->id) }}" class="btn btn-outline-danger btn-sm deleteConfirmAlert circleIcon">
                                            <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="icon" loading="lazy" />
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
            {{ $ticketIssueTypes->withQueryString()->links() }}
        </div>

    </div>


    <!--=== Create Brand Modal ===-->
    <form action="{{ route('admin.ticketIssueType.store') }}" method="POST"> <!--- update ticket issue route --->
        @csrf
        <div class="modal fade" id="createBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{__('Create Ticket Issue Type')}}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                {{__('Name')}}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="{{__('Name')}}" required maxlength="50" max="50" />
                            @error('name')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('Close')}}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{__('Save Issue Type')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--=== Edit Brand Modal ===-->
    <form action="" id="formEditBrand" method="POST">
        @csrf
        @method('PUT')
        <div class="modal fade" id="updateBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{__('Edit Issue Type')}}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align: left">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                {{__('Name')}}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="editName" name="name" maxlength="50" max="50"
                                placeholder="Enter Name" value="" required />
                            @error('name')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{__('Close')}}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{__('Update Issue Type')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        const openUpdateModal = (issueType) => {
            $("#editName").val(issueType.name);
            $("#formEditBrand").attr('action', `{{ route('admin.ticketIssueType.update', ':id') }}`.replace(':id',
                issueType.id)); // update ticket issue route

            $("#updateBrand").modal('show');
        }

        $(".deleteConfirmAlert").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "{{__('Are you sure?') }}",
                text: "{{__('You will not be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{__('Yes, delete it!') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
