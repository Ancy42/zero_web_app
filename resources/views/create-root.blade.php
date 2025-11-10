<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Super Admin</title>
        <!-- App favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ $generaleSetting?->favicon ?? asset('assets/favicon.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .main {
            background: url("/assets/account-setup-bg.png");
            width: 100%;
            height: 100vh;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: bottom;
        }

        .opachity {
            background: #00000030;
            width: 100%;
            height: 100%;
        }

        .fs-7 {
            font-size: 0.75rem !important;
        }

        .fw-500 {
            font-weight: 500 !important;
        }

        .fw-600 {
            font-weight: 600 !important;
        }

        .w-80 {
            width: 80% !important;
        }

        .btn-install {
            width: 280px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 20px;
            background: linear-gradient(to right, #e90608 0%, #f59e39 100%);
            box-shadow: 0px 8px 16px rgba(255, 88, 0, 0.16);
            font-weight: bold;
            font-size: 14px;
            line-height: 18px;
            text-align: center;
            color: #fff !important;
            transition: all 0.5s;
        }

        .btn-install:hover {
            box-shadow: 0px 8px 40px rgb(255 88 0 / 30%);
            letter-spacing: 0.3px;
        }

        .move-right{
            position: absolute;
            right: 10px
        }
        .loader{
            position: absolute;
            z-index: 999;
            width: 100%;
            height: 100%;
            background: #d9d9d982;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <main class="main">
        <div class="opachity">
            <div class="container-fluid">
                <div class="row px-5">
                    <div class="col-6 col-md-4 ms-auto d-flex align-items-center me-5" style="height: 100vh;" id="mainCard">
                        <div class="card w-100 pt-3" style="overflow: hidden;border-bottom: none;">
                            <h2 class="text-center mt-3">Create Super Admin</h2>
                            <p class="fs-7 text-center">
                                <strong class="text-danger">You are about to create the Super Admin account.</strong>
                            </p>

                            <div class="w-80 m-auto">
                                <form method="POST" action="{{ route('create.superadmin') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="">Email<strong class="text-danger">*</strong></label>
                                        <input name="email" type="email" value="{{ old('email') }}" placeholder="e.g: superadmin@example.com" class="form-control @error('email') is-invalid @enderror"></input>
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Password<strong class="text-danger">*</strong></label>
                                        <input name="password" type="password" placeholder="e.g: ********"  class="form-control @error('password') is-invalid @enderror"></input>
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Password Confirmation<strong class="text-danger">*</strong></label>
                                        <input name="password_confirmation" type="password" placeholder="e.g: ********" class="form-control"></input>
                                    </div>

                                    <div class="d-flex mt-3">
                                        <div class="me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <g id="Group_22706" data-name="Group 22706"
                                                    transform="translate(-704 -571)">
                                                    <g id="Rectangle_19036" data-name="Rectangle 19036"
                                                        transform="translate(704 571)" fill="#fff" stroke="#ea4335"
                                                        stroke-width="1">
                                                        <rect width="16" height="16" rx="8" stroke="none">
                                                        </rect>
                                                        <rect x="0.5" y="0.5" width="15" height="15" rx="7.5"
                                                            fill="none"></rect>
                                                    </g>
                                                    <g id="Group_22693" data-name="Group 22693"
                                                        transform="translate(0 -12)">
                                                        <g id="Group_22698" data-name="Group 22698">
                                                            <rect id="Rectangle_19044" data-name="Rectangle 19044"
                                                                width="1.5" height="5" rx="0.75"
                                                                transform="translate(715.475 589.939) rotate(45)"
                                                                fill="#ea4335"></rect>
                                                            <rect id="Rectangle_19111" data-name="Rectangle 19111"
                                                                width="1.5" height="5" rx="0.75"
                                                                transform="translate(716.536 591) rotate(135)"
                                                                fill="#ea4335"></rect>
                                                            <rect id="Rectangle_19051" data-name="Rectangle 19051"
                                                                width="8" height="1.5" rx="0.75"
                                                                transform="translate(708 590.25)" fill="#ea4335"></rect>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <p class="ml-2 mb-0 fs-7 fw-500" style="color: #666; line-height: 18px;">
                                            <strong>Note:</strong> Please remember this information — you will need it to access your application after installation. You can update it later from the settings.
                                        </p>
                                    </div>

                                    <div class="my-4 py-4 absolute-bottom-left right-0 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-install text-uppercase">Create Super Admin</button>
                                    </div>
                                </form>
                            </div>

                            <div class="row">
                                <div class="col" style="min-height: 3px; background: #006a4e"></div>
                                <div class="col" style="min-height: 3px; background: #f42a41"></div>
                                <div class="col" style="min-height: 3px; background: #006a4e"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
