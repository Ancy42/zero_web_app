@extends('layouts.app')
@section('header-title', __('Employee Permissions'))
@section('content')
    <h4 class="mb-3">{{ __('Employee Permissions') }}</h4>

    <div class="card mt-3">
        <div class="card-header d-flex gap-2 py-3">
            <i class="fa-solid fa-user"></i>
            <h5 class="m-0">
                {{ __('Employee Information') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <label class="form-label mb-1">
                            {{ __('Name') }}
                        </label>
                        <p class="m-0 border p-2 rounded">
                            {{ $user?->name }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label mb-1">
                            {{ __('Phone Number') }}
                        </label>
                        <p class="m-0 border p-2 rounded">
                            {{ $user?->phone }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <div>
                        <label class="form-label mb-1">
                            {{ __('Email Address') }}
                        </label>
                        <p class="m-0 border p-2 rounded">
                            {{ $user?->email }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <div>
                        <label class="form-label mb-1">
                            {{ __('Role') }}
                        </label>
                        <p class="m-0 border p-2 rounded">
                            {{ $role->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-body">
            <div class="row">
                <div class="col-12 colRight d-flex flex-column">
                    <h4 class="mb-0">
                        {{ __('Permissions') }}
                    </h4>

                    <form action="{{ route('shop.employee.permission.update', $user->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                        <div class="mt-3 permission-container flex-grow-1">
                            <div
                                class="d-flex align-items-center justify-content-between flex-wrap gap-2 border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" id="checkAll" class="form-check-input m-0"
                                        style="width: 20px; height: 20px">
                                    <span class="text-capitalize fz-18">
                                        <span id="showTotalPermission">
                                            {{ count($userAvailablePermissions) }}
                                        </span> {{ __('Permissions Selected') }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    @hasPermission('shop.employee.permission.update')
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                            {{ __('Update') }}
                                        </button>
                                    @endhasPermission
                                    <span type="button" class="text-danger cursor-pointer" id="uncheckAll">
                                        {{ __('Clear') }}
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-3 mt-3">

                                @foreach ($allPermissionArray as $adminType => $allPermissions)
                                    @php
                                        $type = $adminType == 'adminMultiShop' ? 'admin' : $adminType;
                                    @endphp
                                    <div class="border rounded-3 overflow-hidden">
                                        <div class="bg-light px-3 py-2.5 h4 text-capitalize m-0">
                                            {{ $type }}
                                        </div>
                                        <div class="p-3 d-flex flex-column gap-3 bg-white">
                                            @foreach ($allPermissions as $permissionName => $permissionValues)
                                            <div class="border rounded-3 permission-item">
                                                <div class="text-capitalize m-0 fz-18 p-2 fw-bold border-bottom">
                                                    {{ permissionName($permissionName) }}
                                                </div>
                                                    <div class="fz-18 d-flex align-items-center flex-wrap gap-3 p-3">
                                                        @foreach ($permissionValues ?? [] as $permission)
                                                            <div class="d-flex align-items-center gap-2">
                                                                <input type="checkbox"
                                                                    id="{{ $type . '-' . $permissionName . '-' . $permission }}"
                                                                    name="permissions[]"
                                                                    value="{{ $type . '.' . $permissionName . '.' . $permission }}"
                                                                    class="form-check-input m-0"
                                                                    style="width: 18px; height: 18px"{{ in_array($type . '.' . $permissionName . '.' . $permission, $userAvailablePermissions) ? 'checked' : '' }}
                                                                    onclick="countSelectedPermissions()">
                                                                <label
                                                                    for="{{ $type . '-' . $permissionName . '-' . $permission }}"
                                                                    class="m-0" onclick="countSelectedPermissions()">
                                                                    {{ permissionName($permission) }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                @hasPermission('shop.employee.permission.update')
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary py-2.5 px-4">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                            {{ __('Update') }}
                                        </button>
                                    </div>
                                @endhasPermission
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Hide the default checkbox */
        input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 18px !important;
            height: 18px !important;
            border: 1px solid #ccc;
            outline: none;
            cursor: pointer;
            position: relative;
        }

        /* When checkbox is checked */
        input[type="checkbox"]:checked {
            background-color: var(--theme-color);
            border-color: var(--theme-color);
        }

        .permission-container {
            background: #F8FAFC;
            border: 1px solid #F1F5F9;
            border-radius: 8px;
            padding: 12px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        var totalPermissions = "{{ count($userAvailablePermissions) }}";

        $('#checkAll').click(function() {
            $('input[type="checkbox"]').prop('checked', true);
            countSelectedPermissions();
        });

        $('#uncheckAll').click(function() {
            $('input[type="checkbox"]').prop('checked', false);
            countSelectedPermissions();
        });

        const countSelectedPermissions = () => {
            totalPermissions = $('input[type="checkbox"]:checked').length;
            if ($('#checkAll').is(':checked')) {
                totalPermissions = totalPermissions - 1;
            }
            $('#showTotalPermission').text(totalPermissions);
        }

        countSelectedPermissions();
    </script>
@endpush
