@extends('layouts.app')

@section('header-title', __('Social Authentication'))

@section('content')
    <div class="container-fluid mb-3">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h4 class="m-0">{{ __('Social Authentication') }}</h4>
        </div>

        <div class="row">
            @foreach ($socials as $socialAuth)
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                            <div class="paymentTitle m-0 d-flex align-items-center gap-1">
                                <img src="{{ asset($socialAuth->logo) }}" alt="{{ $socialAuth->name }}" width="32px">
                                <span>{{ __(strtoupper($socialAuth->name)) }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="{{ $socialAuth->is_active ? 'statusOn' : 'statusOff' }}">
                                    {{ $socialAuth->is_active ? 'On' : 'Off' }}
                                </span>
                                @hasPermission('admin.socialAuth.toggle')
                                    <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                        data-bs-title="{{ $socialAuth->is_active ? 'Turn off' : 'Turn on' }}">
                                        <a href="{{ route('admin.socialAuth.toggle', $socialAuth->id) }}" class="confirm">
                                            <input type="checkbox" {{ $socialAuth->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                @endhasPermission
                            </div>
                        </div>
                        <div class="card-body">
                            @hasPermission('admin.socialAuth.update')
                                <form action="{{ route('admin.socialAuth.update', $socialAuth->id) }}" method="POST">
                                    @csrf
                                @endhasPermission

                                @if ($socialAuth->provider == 'apple')
                                    <x-input label="Identifier / Bundle ID" name="client_id" type="text"
                                        :placeholder="'Enter ' .
                                            strtoupper($socialAuth->name) .
                                            ' ' .
                                            'Identifier / Bundle ID'" :value="$socialAuth->client_id" required="true" />
                                @else
                                    @php
                                        $clientID = 'Client ID';
                                        if ($socialAuth->provider == 'facebook') {
                                            $clientID = 'App ID';
                                        }
                                    @endphp
                                    <x-input :label="$clientID" name="client_id" type="text" :placeholder="'Enter ' . strtoupper($socialAuth->name) . ' ' . $clientID"
                                        :value="$socialAuth->client_id" required="true" />

                                    <div class="mt-3">
                                        @php
                                            $clientSecret = 'Client Secret';
                                            if ($socialAuth->provider == 'facebook') {
                                                $clientSecret = 'App Key';
                                            }
                                        @endphp
                                        <x-input :label="$clientSecret" name="client_secret" type="text" :placeholder="'Enter ' . strtoupper($socialAuth->name) . ' ' . $clientSecret"
                                            :value="$socialAuth->client_secret" required="true" />
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <x-input type="text" name="redirect" label="Redirect URL/ Return URL"
                                        :value="$socialAuth->redirect" placeholder="Enter Redirect URL/ Return URL" />
                                </div>

                                @hasPermission('admin.socialAuth.update')
                                    <div class="mt-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary py-2">
                                            {{ __('Save And Update') }}
                                        </button>
                                    </div>
                                </form>
                            @endhasPermission
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".confirm").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to change status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endpush
