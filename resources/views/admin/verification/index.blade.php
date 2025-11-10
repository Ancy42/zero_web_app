@extends('layouts.app')

@section('header-title', __('Verification OTP Settings'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-unlock"></i> {{ __('Verification OTP Settings') }}
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9">
            <form action="{{ route('admin.verification.update') }}" method="POST">
                @csrf

                <!--######## Basic Information ##########-->
                <div class="card mt-4">
                    <div class="card-body">

                        <div class="border mt-3 rounded-3">

                            <div class="d-flex align-items-center gap-2 border-bottom p-2">
                                <i class="bi bi-briefcase-fill"></i>
                                <h5 class="mb-0">{{ __('Verification') }}</h5>
                            </div>

                            <div class="p-3">
                                <div class="row gy-3">
                                    <div class="col-lg-6">
                                        <div class="border rounded p-2 d-flex align-items-center justify-content-between gap-2 flex-wrap flex-grow-1">
                                            <label class="form-label m-0 fw-medium" for="otpVerify">
                                                {{ __('Customer Registration OTP Verify') }}
                                            </label>
                                            <label class="switch mb-0">
                                                <input id="otpVerify" type="checkbox"
                                                    {{ $verifyManage?->register_otp ? 'checked' : '' }} name="register_otp">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="border rounded p-2 d-flex align-items-center justify-content-between gap-2 flex-wrap flex-grow-1">
                                            <label class="form-label m-0 fw-medium" for="verifyAccount">
                                                {{ __('Must Verify Account on Order Placement') }}
                                            </label>
                                            <label class="switch mb-0">
                                                <input id="verifyAccount" type="checkbox"
                                                    {{ $verifyManage?->order_place_account_verify ? 'checked' : '' }} name="order_place_account_verify">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gy-3 mt-3">
                                    <div class="col-lg-6">
                                        <label class="form-label fw-medium">
                                            {{ __('Register OTP Send / Account Verify Method') }}
                                        </label>
                                        <div class="d-flex flex-wrap align-items-center gap-5 border rounded fw-medium"
                                            style="padding: 10px;">
                                            <div class="flex-grow-1 d-flex align-items-center gap-1">
                                                <input type="radio" name="register_otp_type" value="phone"
                                                    class="form-check-input m-0" id="single"
                                                    {{ $verifyManage?->register_otp_type == 'phone' ? 'checked' : '' }}>
                                                <label for="single" class="m-0 cursor-pointer">
                                                    {{ __('Phone') }}
                                                </label>
                                            </div>

                                            <div class="flex-grow-1 d-flex align-items-center gap-1">
                                                <input type="radio" name="register_otp_type" value="email"
                                                    class="form-check-input m-0" id="emailRegisterOTP"
                                                    {{ $verifyManage?->register_otp_type == 'email' ? 'checked' : '' }}>
                                                <label for="emailRegisterOTP" class="m-0 cursor-pointer">
                                                    {{ __('Email') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label fw-medium">
                                            {{ __('Forget Password OTP Send Method') }}
                                        </label>
                                        <div class="d-flex flex-wrap align-items-center gap-5 border rounded fw-medium"
                                            style="padding: 10px;">
                                            <div class="flex-grow-1 d-flex align-items-center gap-1">
                                                <input type="radio" name="forgot_otp_type" value="phone"
                                                    class="form-check-input m-0" id="forgetOTPPhone"
                                                    {{ $verifyManage?->forgot_otp_type == 'phone' ? 'checked' : '' }}>
                                                <label for="forgetOTPPhone" class="m-0 cursor-pointer">
                                                    {{ __('Phone') }}
                                                </label>
                                            </div>

                                            <div class="flex-grow-1 d-flex align-items-center gap-1">
                                                <input type="radio" name="forgot_otp_type" value="email"
                                                    class="form-check-input m-0" id="forgetOTPEmail"
                                                    {{ $verifyManage?->forgot_otp_type == 'email' ? 'checked' : '' }}>
                                                <label for="forgetOTPEmail" class="m-0 cursor-pointer">
                                                    {{ __('Email') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border mt-4 rounded-3">
                            <div class="border-bottom p-2 fw-bold fz-16">
                                {{ __('Phone Number Validation') }}
                            </div>

                            <div class="p-3">

                                <div class="border rounded p-2 d-flex align-items-center justify-content-between gap-2 flex-wrap"
                                    style="max-width: 400px">
                                    <label class="form-label m-0 fw-medium" for="toggle">
                                        {{ __('Registration Phone Required') }}
                                    </label>
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="Optional/Required">
                                        <input id="toggle" type="checkbox"
                                            {{ $verifyManage?->phone_required ? 'checked' : '' }} name="phone_required">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="row gy-3 mt-3">
                                    <div class="col-md-6">
                                        <x-input type="text" name="phone_min_length"
                                            label="Minimum Length (without Country Code)" :value="$verifyManage?->phone_min_length ?? 9"
                                            onlyNumber="true" />
                                    </div>

                                    <div class="col-md-6">
                                        <x-input type="text" name="phone_max_length"
                                            label="Maximum Length (without Country Code)" :value="$verifyManage?->phone_max_length ?? 14"
                                            onlyNumber="true" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        @hasPermission('admin.verification.update')
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary py-2.5 px-3">
                                    {{ __('Save And Update') }}
                                </button>
                            </div>
                        @endhasPermission

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
