<!doctype html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ $generaleSetting?->favicon ?? asset('assets/favicon.png') }}" />

    <!-- App title -->
    <title>{{ $generaleSetting?->title ?? config('app.name', 'Laravel') }} Admin Login</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<style>
    body {
        font-family: "Inter", serif;
    }

    .logo_right img {
        height: 90px;
    }

    .logo_right {
        position: absolute;
        top: 15%;
        right: 47%;
        transform: translateX(47%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0;
    }

    .logo_right .site_name {
        font-size: 26px;
        font-weight: 900;
        color: #848589;
        text-transform: capitalize;
        text-align: center;
        letter-spacing: 0.5px;
        font-family: 'Poppins', sans-serif;
    }
</style>

@php
    $backgroundImg = asset('assets/admin_login_img.png');
    if($generaleSetting->admin_login_img &&\Storage::exists($generaleSetting->admin_login_img)){
        $backgroundImg = \Storage::url($generaleSetting->admin_login_img);
    }
@endphp

<body style="background: url('{{ $backgroundImg }}')">

    <section class="login-section">

        <div class="thumbnail position-relative text-primary">
            {{-- <img src="{{ $backgroundImg }}" alt="thumbnail" width="100%" loading="lazy" />
            <div class="logo_right">
                <img src="{{ $generaleSetting?->favicon ?? asset('assets/favicon.png') }}" alt="logo"
                    loading="lazy" />
                <span class="site_name">{{ $generaleSetting?->name ?? config('app.name') }}</span>
            </div> --}}
        </div>

        <!-- Login Card -->
        <div class="card loginCard">
            <div class="card-body">
                @if (app()->isLocal())
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <span class="powerBy">Powered by <a class="text-primary text-decoration-none fw-bold"
                                href="https://razinsoft.com/" target="_blank">RazinSoft</a>
                            ©{{ date('Y') }}</span>
                        <span class="version fw-bold">v{{ config('app.version') }}</span>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <img src="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}" alt="" height="80"
                        style="max-width: 100%">
                </div>

                <div class="page-content text-center mb-4">
                    <p class="pagePera my-3">
                        Welcome to
                        <span class="fw-bold text-primary text-capitalize">
                            {{ $generaleSetting?->name ?? config('app.name', 'Laravel') }}
                        </span>
                    </p>

                    <h2 class="pageTitle">Login To Admin</h2>
                </div>

                <hr>

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email">Enter Address</label>
                        <input type="email" value="{{ old('email') }}" name="email" id="email"
                            class="form-control" placeholder="Enter Email Address">

                        @error('email')
                            <span class="text text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password">Password</label>
                        <div class="position-relative passwordInput">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter Password">
                            <span class="eye" onclick="showHidePassword()">
                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="text text-danger" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    </div>

                    @if ($GoogleReCaptcha?->is_active)
                        <div>
                            <div class="g-recaptcha" data-sitekey="{{ $GoogleReCaptcha?->site_key }}"></div>
                        </div>
                        @error('g-recaptcha-response')
                            <span class="text text-danger">{{ $message }}</span>
                        @enderror
                    @endif

                    <button class="btn loginButton" type="submit">Login</button>

                    @if (app()->isLocal())
                        <div class="credentials-section">
                            <div class="item">
                                <div class="header">Admin Credentials</div>
                                <div class="content">
                                    <div class="credentials">
                                        <span>Email: {{config('app.demo_email')}}</span>
                                        <span>Password: {{config('app.demo_password')}}</span>
                                    </div>
                                    <div class="copyBtn" onclick="loginAdmin()">
                                        <i class="fa-regular fa-copy"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="header">Shop Credentials</div>
                                <div class="content">
                                    <div class="credentials">
                                        <span>Email: {{config('app.demo_shop_email')}}</span>
                                        <span>Password: {{config('app.demo_password')}}</span>
                                    </div>
                                    <div class="copyBtn" onclick="gotoShopLogin()">
                                        <i class="fa-regular fa-copy"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
    <script src="{{ asset('assets/scripts/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var themeColor = "{{ $generaleSetting?->primary_color ?? '#EE456B' }}";
            document.documentElement.style.setProperty('--theme_color', themeColor);
        });

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function showHidePassword() {
            const toggle = document.getElementById("togglePassword");
            const password = document.getElementById("password");

            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            toggle.classList.toggle("fa-eye");
            toggle.classList.toggle("fa-eye-slash");
        }

        var email = document.getElementById("email");
        var password = document.getElementById("password");

        function loginAdmin() {
            email.value = "{{config('app.demo_email')}}";
            password.value = "{{config('app.demo_password')}}";

            if (!sessionStorage.getItem('fromShop')) {
                Toast.fire({
                    icon: 'success',
                    title: 'Admin Credentials Copied'
                });
            }
        }

        const checkSession = () => {
            if (sessionStorage.getItem('fromShop')) {
                loginAdmin();
                sessionStorage.removeItem('fromShop');
            }
        }

        const gotoShopLogin = () => {
            sessionStorage.setItem('fromAdmin', true);
            window.open("{{ route('shop.login') }}", '_blank');
        }

        checkSession();
    </script>

</body>

</html>
