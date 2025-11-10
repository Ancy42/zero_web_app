@extends('layouts.app')

@section('header-title', __('Shop Details'))

@section('content')

    <div class="container-fluid">

        <div class="card">
            {{-- <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap">
                <h4 class="m-0">{{ __('Shop Details') }}</h4>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ResetPasswordModal">
                    <i class="bi bi-shield-lock-fill"></i>
                    {{ __('Reset Password') }}
                </button>
            </div> --}}
            <div class="card-body">
                @include('admin.shop.header-nav')

                <div class="row mb-3">
                    <div class="col-lg-8 mt-3">
                        <div class="card rounded-12 position-relative overflow-hidden">
                            <div class="card-body shop details p-2 border-bottom pb-3">
                                <div class="banner">
                                    <img class="img-fit" src="{{ $shop->banner }}" />
                                </div>
                                <div class="main-content d-flex align-items-center">
                                    <div class="logo">
                                        <img class="img-fit" src="{{ $shop->logo }}" />
                                    </div>
                                    <div class="personal">
                                        <span class="name h4 mb-1">{{ $shop->name }}</span>
                                        <div class="d-flex gap-2 align-items-center ">
                                            <div>
                                                @foreach (range(1, 5) as $rating)
                                                    @if ($shop->averageRating >= $rating)
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                    @else
                                                        <i class="fa-regular fa-star text-secondary"></i>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div>
                                                <span class="fw-bold">{{ $shop->averageRating }}</span>
                                                ({{ $shop->reviews->count() }} {{ __('Reviews') }})
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <a href="/shops/{{ $shop->id }}" target="blank"
                                                class="btn btn-outline-primary btn-sm">
                                                {{ __('View Live') }}
                                            </a>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ResetPasswordModal">
                                                <i class="bi bi-shield-lock-fill"></i>
                                                {{ __('Reset Password') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="m-0 p-3 border-bottom">{{ __('User Information') }}</h4>
                            <div class="card-body pt-0">
                                <table class="table mb-0">
                                    <tr>
                                        <td style="width: 180px">{{ __('Name') }}:</td>
                                        <td>{{ $shop->user?->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 180px">{{ __('Phone') }}:</td>
                                        <td>{{ $shop->user?->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 180px">{{ __('Email') }}:</td>
                                        <td>{{ $shop->user?->email }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <h4 class="m-0 p-3 border-bottom">{{ __('Shop Information') }}</h4>
                            <div class="card-body pt-0">
                                <table class="table mb-0">
                                    <tr>
                                        <td style="width: 180px">{{ __('Name') }}:</td>
                                        <td>{{ $shop->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 180px">{{ __('Estimated Delivery') }}:</td>
                                        <td>{{ $shop->estimated_delivery_time }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 180px">{{ __('Shop Description') }}:</td>
                                        <td>{{ $shop->description }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3">
                        <div class="card h-100">
                            <h4 class="m-0 p-3 border-bottom">{{ __('Product Information') }}</h4>
                            <div class="card-body pt-0">
                                <table class="table mb-0">
                                    <tr>
                                        <td style="width: 180px">{{ __('Total Products') }}:</td>
                                        <td>
                                            <span class="fw-bold">{{ $shop->products->count() }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 180px">{{ __('Total Orders') }}:</td>
                                        <td>
                                            <span class="fw-bold">{{ $shop->orders->count() }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 180px; text-transform: capitalize">{{ __('reviews') }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $shop->reviews->count() }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.shop.reset.password', $shop->id) }}" method="POST">
        @csrf
        <div class="modal fade" id="ResetPasswordModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5">{{ __('Reset Password') }} ({{ $shop->name }})</h4>
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
