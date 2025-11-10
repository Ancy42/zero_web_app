@extends('layouts.app')

@section('header-title', __('Mail Configuration'))

@section('content')
    <div class="container-fluid my-4">
        <div class="row">

            <div class="col-xl-8 col-lg-9 mx-auto">
                <!-- Error Message -->
                @if (session('messageError'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-md" role="alert">
                        <div class="fz-20">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <strong class="ms-0 fz-16">Error:</strong>
                        </div>
                        <div>
                            {{ session('messageError') }}
                        </div>
                    </div>
                @endif

                <!-- send test mail -->
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                {{ __('Send Test Mail') }}
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <form action="{{ route('admin.mailConfig.sendTestMail') }}" method="POST">
                                    @csrf
                                    <div class="">
                                        <label class="block text-gray-600 font-semibold mb-1">
                                            {{ __('To / Recipient Email') }}
                                        </label>
                                        <input type="email" name="email" class="w-full form-control"
                                            placeholder="{{ __("Recipient's Email") }}" required>
                                    </div>

                                    <div class="mt-3">
                                        <label class="block text-gray-600 font-semibold mb-1">Message</label>
                                        <textarea name="message" rows="1" class="w-full form-control" placeholder="{{ __('Message to be sent') }}"
                                            required></textarea>
                                    </div>

                                    <div class="mt-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            Send Email
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mail Configuration -->
            <div class="col-xl-8 col-lg-9 mx-auto mt-3">
                <form action="{{ route('admin.mailConfig.update') }}" method="POST">
                    @method('put')
                    @csrf
                    <div class="card">
                        <div class="card-header py-3">
                            <h4 class="m-0">{{ __('Mail Configuration') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_mailer')" name="mailer" type="text" placeholder="smtp"
                                        label="{{ __('Mail Mailer') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_host')" name="host" type="text"
                                        placeholder="ex: smtp.gmail.com" label="{{ __('Mail Host') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_port')" name="port" type="text" placeholder="ex: 465"
                                        label="{{ __('Mail Port') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_username')" name="username" type="text"
                                        placeholder="ex: example@gmail.com" label="{{ __('Mail User Name') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_password')" name="password" type="text"
                                        placeholder="Your app password" label="{{ __('Mail Password') }}" />
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <x-input :value="config('app.mail_encryption')" name="encryption" type="text" placeholder="tls or ssl"
                                        label="{{ __('Mail Encryption') }}" />
                                </div>
                                <div class="col-lg-6">
                                    <x-input :value="config('app.mail_from_address')" name="from_address" type="text"
                                        placeholder="from email address" label="{{ __('Mail From Address') }}"
                                        required />
                                </div>
                            </div>

                        </div>

                        @hasPermission('admin.mailConfig.update')
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary py-2">{{ __('Save And Update') }}</button>
                            </div>
                        @endhasPermission
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
