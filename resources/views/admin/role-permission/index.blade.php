@extends('layouts.app')
@section('header-title', __('Roles & Permissions'))

@section('content')
    <h4 class="mb-3">{{ __('Roles & Permissions') }}</h4>

    <div class="card ">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 h-100">
                    <h4>{{ __('Roles') }}</h4>

                    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between mt-3">
                        <div class="position-relative flex-grow-1">
                            <input type="text" class="form-control py-2.5" placeholder="{{ __('Search by role name') }}"
                                id="search" onkeyup="filterRoles()">
                            <span class="search-icon">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        @hasPermission('admin.role.create')
                            <button type="button" data-bs-toggle="modal" data-bs-target="#createRole"
                                class="btn btn-primary py-2.5">
                                <i class="fa fa-plus"></i>
                                {{ __('Create Role') }}
                            </button>
                        @endhasPermission
                    </div>

                    <div class="mt-3 d-flex flex-column gap-2">
                        @foreach ($roles as $role)
                            <button type="button"
                                class="d-flex align-items-center justify-content-between w-100 text-capitalize roleBtn {{ isset($activeRole) ? ($role->name == $activeRole ? 'active' : '') : '' }}"
                                data-name="{{ $role->name }}">
                                <a href="{{ route('admin.role.permission', $role->id) }}" class="p-2 linkBtn flex-grow-1">
                                    {{ $role->name }} ({{ $role->name != 'root' ? count($role->permissions) : 'All' }})
                                    @if ($role->is_shop)
                                        <span class="badge bg-primary px-1">
                                            <i class="fa-solid fa-shop"></i>
                                        </span>
                                    @endif
                                </a>

                                @if ($role->name != 'root')
                                    <div class="d-flex  gap-2">
                                        @hasPermission('admin.role.edit')
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-info circleIcon"
                                                onclick="editRole({{ $role }})">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit" loading="lazy"/>
                                            </a>
                                        @endhasPermission
                                        @hasPermission('admin.role.destroy')
                                            <a href="{{ route('admin.role.destroy', $role->id) }}" class="btn btn-sm btn-outline-danger circleIcon showConfirmAlert">
                                                <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="delete" loading="lazy"/>
                                            </a>
                                        @endhasPermission
                                    </div>
                                @else
                                    <span class="badge bg-secondary fst-italic">{{ __('No Action') }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6 colRight d-flex flex-column">
                    <h4 class="mb-0">{{ __('Permissions') }}</h4>

                    <div class="mt-3 permission-container flex-grow-1">

                        @if (request()->routeIs('admin.role.permission'))
                            <div
                                class="d-flex align-items-center justify-content-between flex-wrap gap-2 border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" id="checkAll" class="form-check-input m-0"
                                        style="width: 20px; height: 20px">
                                    <span class="text-capitalize fz-18">
                                        <span id="showTotalPermission">
                                            {{ count($role->permissions) }}
                                        </span> {{ __('Permissions Selected') }}
                                    </span>
                                </div>
                                <span type="button" class="text-danger cursor-pointer" id="uncheckAll">
                                    {{ __('Clear') }}
                                </span>
                            </div>

                            <form action="{{ route('admin.role.permission.update', $selectedRole->id) }}" method="POST">
                                @csrf
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
                                                    <div class="border rounded-3">
                                                        <div class="text-capitalize m-0 fz-18 p-2 fw-bold border-bottom">
                                                            {{ permissionName($permissionName) }}
                                                        </div>
                                                        <!--*** if role is root, then check all permissions ***-->
                                                        @if ($activeRole == 'root')
                                                            <div class="fz-18 d-flex align-items-center flex-wrap gap-3 p-3">
                                                                @foreach ($permissionValues as $permission)
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" class="form-check-input m-0"
                                                                            style="width: 18px; height: 18px" checked
                                                                            onclick="countSelectedPermissions()">
                                                                        <label class="m-0">
                                                                            {{ permissionName($permission) }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <!--*** if role is not root, then check only selected permissions ***-->
                                                        @else
                                                            <div class="fz-18 d-flex align-items-center flex-wrap gap-3 p-3">
                                                                @foreach ($permissionValues as $permission)
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox"
                                                                            id="{{ $type . '-' . $permissionName . '-' . $permission }}"
                                                                            name="permissions[]"
                                                                            value="{{ $type . '.' . $permissionName . '.' . $permission }}"
                                                                            class="form-check-input m-0"
                                                                            style="width: 18px; height: 18px"{{ in_array($type . '.' . $permissionName . '.' . $permission, $permissions) ? 'checked' : '' }}
                                                                            onclick="countSelectedPermissions()">
                                                                        <label
                                                                            for="{{ $type . '-' . $permissionName . '-' . $permission }}"
                                                                            class="m-0"
                                                                            onclick="countSelectedPermissions()">
                                                                            {{ permissionName($permission) }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    @hasPermission('admin.role.permission.update')
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary py-2.5 px-4"
                                                @if ($activeRole == 'root') disabled @endif>
                                                <i class="fa-solid fa-arrows-rotate"></i>
                                                {{ __('Update') }}
                                            </button>
                                        </div>
                                    @endhasPermission
                                </div>
                            </form>
                        @else
                            <div class="d-flex align-items-center justify-content-center flex-column h-100">
                                <div class="fs-1 text-secondary">
                                    <i class="fa-solid fa-user-lock"></i>
                                </div>
                                <span class="text-capitalize fz-22 fst-italic">
                                    {{ __('No Permissions Available') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createRole" tabindex="-1" aria-labelledby="createRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createRoleLabel">
                        {{ __('Create Role') }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="roleName" class="form-label">
                        {{ __('Role Name') }}
                    </label>
                    <input type="text" class="form-control" id="roleName" name="roleName"
                        placeholder="{{ __('Role Name') }}" required
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')" onkeyup="checkRoleName()" />
                    <p id="roleNameError" class="text-danger mt-1"></p>

                    <div class="d-flex align-items-center mt-3">
                        <label for="forShop" class="form-label m-0">
                            {{ __('Applicable For Shop') }}
                        </label>
                        <input type="checkbox" class="form-check-input ms-2 mb-0" id="forShop" name="for_shop"
                            checked />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="roleSubmit">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateRole">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">
                        {{ __('Update Role') }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="updateRoleForm">
                        @csrf
                        @method('PUT')
                        <label for="updateRoleName" class="form-label">
                            {{ __('Role Name') }}
                        </label>
                        <input type="text" class="form-control" id="updateRoleName" name="name"
                            placeholder="{{ __('Role Name') }}" required
                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                            onkeyup="checkUpdateRoleName()" />
                        <p id="updateRoleNameError" class="text-danger mt-1"></p>

                        <div class="d-flex align-items-center mt-3">
                            <label for="forShopRole" class="form-label m-0">
                                {{ __('Applicable For Shop') }}
                            </label>
                            <input type="checkbox" class="form-check-input ms-2 mb-0" id="forShopRole" name="for_shop">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button type="button" class="btn btn-primary" id="updateRoleSubmit">
                        {{ __('Update') }}
                    </button>
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

        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .roleBtn {
            background-color: #f8f9fa;
            border: 1px solid #F1F5F9;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 500;
            transition: all 0.3s ease-in-out;
        }

        .roleBtn:hover {
            background-color: #e2e8f0;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .linkBtn {
            transition: all 0.3s ease-in-out;
            text-align: left !important;
        }

        .roleBtn .linkBtn {
            color: #000;
        }

        .roleBtn:hover .linkBtn {
            color: var(--theme-color);
        }

        .roleBtn.active {
            background-color: var(--theme-hover-bg);
            border: 1px solid var(--theme-color);
            color: var(--theme-color);
            border-radius: 8px;
        }

        .roleBtn.active .linkBtn {
            color: var(--theme-color);
        }

        .permission-container {
            background: #F8FAFC;
            border: 1px solid #F1F5F9;
            border-radius: 8px;
            padding: 12px;
        }

        .colRight {
            border-left: 1px solid #E2E8F0;
        }

        @media (max-width: 768px) {
            .colRight {
                border-left: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        let roles = @json($roles);
        const roleSubmit = document.getElementById('roleSubmit');

        var isSubmittedAble = false;
        var isLoading = false;

        var isUpdateSubmittedAble = false;
        var updateDefaultRoleName = '';

        function checkRoleName() {
            let roleName = $('#roleName').val();
            roleName = roleName.toLowerCase();

            const hasRole = roles.find(role => role.name === roleName);
            if (hasRole) {
                $('#roleNameError').text('Role name already exists.');
                $('#roleName').addClass('is-invalid');
                $('#roleName').removeClass('is-valid');
                isSubmittedAble = false;
            } else {
                $('#roleNameError').text('');
                $('#roleName').addClass('is-valid');
                $('#roleName').removeClass('is-invalid');
                isSubmittedAble = true;
            }

            if (roleName == '') {
                $('#roleNameError').text('Role name is required.');
                $('#roleName').addClass('is-invalid');
                $('#roleName').removeClass('is-valid');
                isSubmittedAble = false;
            }
        }

        roleSubmit.addEventListener('click', function() {
            var roleName = $('#roleName').val();
            roleName = roleName.toLowerCase();

            var forShop = $('#forShop').is(':checked');

            if (roleName == '') {
                $('#roleNameError').text('Role name is required.');
                $('#roleName').addClass('is-invalid');
                $('#roleName').removeClass('is-valid');
            } else {
                $('#roleNameError').text('');
            }

            console.log(forShop);

            if (isSubmittedAble && !isLoading) {
                isLoading = true;
                roleSubmit.disabled = true;
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.role.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: roleName,
                        for_shop: forShop ? 1 : 0
                    },
                    success: function(response) {
                        $('#createRole').modal('hide');
                        isLoading = false;
                        var loadUrl = "{{ route('admin.role.permission', ':id') }}".replace(':id',
                            response.data.role.id);
                        window.location.href = loadUrl + '?fst=' + response.data.role.id;
                    },
                    error: function(xhr, status, error) {
                        isLoading = false;
                        roleSubmit.disabled = false;
                        $('#roleNameError').text(xhr.responseJSON.message);
                    }
                });
            }
        });

        const editRole = (role) => {
            isUpdateSubmittedAble = true;
            $('#updateRoleName').val(role.name);

            updateDefaultRoleName = role.name;

            $("#updateRoleForm").attr('action', `{{ route('admin.role.update', ':id') }}`.replace(':id', role.id));

            $('#forShopRole').prop('checked', role.is_shop ? true : false);

            $("#updateRole").modal('show');
        }

        function checkUpdateRoleName() {
            let updateRoleName = $('#updateRoleName').val();
            updateRoleName = updateRoleName.toLowerCase();
            const hasRole = roles.find(role => role.name === updateRoleName);
            if (hasRole && (updateDefaultRoleName != updateRoleName)) {
                $('#updateRoleNameError').text('Role name already exists.');
                $('#updateRoleName').addClass('is-invalid');
                $('#updateRoleName').removeClass('is-valid');
                isUpdateSubmittedAble = false;
            } else {
                $('#updateRoleNameError').text('');
                $('#updateRoleName').addClass('is-valid');
                $('#updateRoleName').removeClass('is-invalid');
                isUpdateSubmittedAble = true;
            }
        }

        $('#updateRoleSubmit').click(function() {
            var updateRoleName = $('#updateRoleName').val();
            updateRoleName = updateRoleName.toLowerCase();

            if (updateRoleName == '') {
                $('#updateRoleNameError').text('Role name is required.');
                $('#updateRoleName').addClass('is-invalid');
                $('#updateRoleName').removeClass('is-valid');
                isUpdateSubmittedAble = false;
            } else {
                $('#updateRoleNameError').text('');
            }

            if (isUpdateSubmittedAble) {
                $('#updateRoleForm').submit();
            }
        });

        function filterRoles() {
            let searchValue = document.getElementById('search').value.toLowerCase();
            let roleButtons = document.querySelectorAll('.roleBtn');

            roleButtons.forEach(function(button) {
                let roleName = button.getAttribute('data-name');
                if (roleName.includes(searchValue)) {
                    button.classList.remove('d-none'); // Show the button
                    button.classList.add('d-flex');
                } else {
                    button.classList.remove('d-flex'); // Show the button
                    button.classList.add('d-none');
                }
            });
        }

        $('.showConfirmAlert').click(function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('If you delete this, it will be gone forever. and all of users will be deleted permanently.') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, delete it!') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        });

        var totalPermissions = "{{ count($role?->permissions) }}";

        $('#checkAll').click(function() {
            $('input[type="checkbox"]').prop('checked', true);
            countSelectedPermissions();
        });

        $('#uncheckAll').click(function() {
            var activeRole = "{{ $activeRole ?? '' }}";
            if (activeRole != 'root') {
                $('input[type="checkbox"]').prop('checked', false);
                $('#forShop').prop('checked', true);
            }
            countSelectedPermissions();
        });

        const countSelectedPermissions = () => {
            var activeRole = "{{ $activeRole ?? '' }}";

            if (activeRole == 'root') {
                $('#checkAll').prop('checked', true);
            }

            totalPermissions = $('input[type="checkbox"]:checked').length;
            if ($('#checkAll').is(':checked')) {
                totalPermissions = totalPermissions - 1;
            }
            if ($('#forShop').is(':checked')) {
                totalPermissions = totalPermissions - 1;
            }
            $('#showTotalPermission').text(totalPermissions);
        }

        countSelectedPermissions();
    </script>
@endpush
