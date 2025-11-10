@extends('layouts.app')
@section('header-title', __('Employees'))
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <h4>{{ __('Employees') }}</h4>

        @hasPermission('shop.employee.create')
            <a href="{{ route('shop.employee.create') }}" class="btn btn-primary py-2.5">
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
                                <th class="text-center">{{ __('SL') }}.</th>
                                <th>{{ __('Profile') }}</th>
                                <th style="min-width: 150px">{{ __('Name') }}</th>
                                <th style="min-width: 100px">{{ __('Phone') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($users as $key => $user)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>
                                    <img src="{{ $user->thumbnail }}" width="50">
                                </td>

                                <td>{{ Str::limit($user->fullName, 50, '...') }}</td>

                                <td>
                                    {{ $user->phone ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $user->email ?? 'N/A' }}
                                </td>

                                <td>
                                    @php
                                        $role = $user->roles()?->pluck('name')->first();
                                    @endphp
                                    <span class="badge text-bg-secondary">
                                        {{ $role }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2 justify-content-center">

                                        @if ($role != 'root')
                                            @hasPermission('shop.employee.permission')
                                                <a href="{{ route('shop.employee.permission', $user->id) }}"
                                                    class="btn btn-outline-primary circleIcon" style="font-size: 16px;" title="{{ __('Permission') }}">
                                                    <img src="{{ asset('assets/icons-admin/employee.svg') }}" alt="permission" loading="lazy" />
                                                </a>
                                            @endhasPermission

                                            @hasPermission('shop.employee.reset-password')
                                                <button class="btn btn-outline-warning circleIcon" type="button" title="{{ __('Reset Password') }}"
                                                    onclick="openResetPasswordModal({{ $user->id }}, '{{ $user->fullName }}')">
                                                    <img src="{{ asset('assets/icons-admin/role-permission.svg') }}" alt="permission" loading="lazy" />
                                                </button>
                                            @endhasPermission
                                            @hasPermission('shop.employee.destroy')
                                                <a href="{{ route('shop.employee.destroy', $user->id) }}" title="{{ __('Delete') }}"
                                                    class="btn btn-outline-danger deleteConfirm circleIcon">
                                                    <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="delete" loading="lazy" />
                                                </a>
                                            @endhasPermission
                                        @else
                                            N/A
                                        @endif
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
            {{ $users->withQueryString()->links() }}
        </div>

    </div>


    {{-- Reset Password Modal --}}
    <form action="#" method="POST" id="resetPasswordForm">
        @csrf
        <div class="modal fade" id="ResetPasswordModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5">{{ __('Reset Password') }} <span id="userName"></span></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="password1" class="form-label">
                                {{ __('Password') }}
                            </label>
                            <div class="position-relative passwordInput">
                                <input type="password" name="password" id="password1" class="form-control" required="true"
                                    placeholder="Enter Password">
                                <span class="eye" onclick="showHidePassword(1)">
                                    <i class="fa fa-eye-slash fa-eye" id="togglePassword1"></i>
                                </span>
                            </div>
                            @error('password')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password2" class="form-label">
                                {{ __('Confirm Password') }}
                            </label>
                            <div class="position-relative passwordInput">
                                <input type="password" name="password_confirmation" id="password2" class="form-control"
                                    required="true" placeholder="Enter Password again">
                                <span class="eye" onclick="showHidePassword(2)">
                                    <i class="fa fa-eye-slash fa-eye" id="togglePassword2"></i>
                                </span>
                            </div>
                            <span id="passwordMatch" class="text text-danger d-none"></span>
                            @error('password_confirmation')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="submit" class="btn btn-primary">
                            {{ __('Save changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const openResetPasswordModal = (id, name) => {
            $("#ResetPasswordModal").modal('show');
            $("#resetPasswordForm").attr('action', `{{ route('shop.employee.reset-password', ':id') }}`.replace(':id',
                id));
            $("#userName").text('(' + name + ')');
        }

        function showHidePassword(num) {
            const toggle = document.getElementById("togglePassword" + num);
            const password = document.getElementById("password" + num);

            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            toggle.classList.toggle("fa-eye");
        }

        document.getElementById('password2').addEventListener('keyup', function(e) {
            $password1 = document.getElementById('password1').value;
            $password2 = document.getElementById('password2').value;

            $message = document.getElementById('passwordMatch');

            if ($password1 == $password2) {
                document.getElementById('password2').classList.remove('is-invalid');
                $message.classList.add('d-none');
                document.getElementById('submit').disabled = false;
            } else {
                document.getElementById('password2').classList.add('is-invalid');
                $message.classList.remove('d-none');
                $message.innerHTML = "Password doesn't match";
                document.getElementById('submit').disabled = true;
            }
        });
    </script>
@endpush
