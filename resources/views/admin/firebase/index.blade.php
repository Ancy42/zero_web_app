@extends('layouts.app')
@section('header-title', __('Firebase Notification'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>
            {{ __('Firebase Notification') }}
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="modal"
                    data-bs-target="#showInstraction" aria-expanded="false" aria-controls="showInstraction">
                    {{ __('Get instructions') }}
                    <span class="info ms-2" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="{{ __('Get instructions for bulk import') }}">
                        <i class="bi bi-info"></i>
                    </span>
                </button>
            </h2>
        </h4>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="showInstraction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">FCM setup instrctions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Step 1 -->
                    <div class="col-12">
                        <div class="export-steps-item h-100">
                            <div class="d-flex gap-3 justify-content-between align-items-center">
                                <div>
                                    <h3 class="fz-20 text-dark">Step 1</h3>
                                    <div>
                                        Login to your firebase account
                                    </div>
                                </div>
                                <img src="{{ asset('assets/images/firebase-login.png') }}" alt="" width="60">
                            </div>

                            <h4 class="mt-3 text-dark fz-20">Instruction</h4>

                            <ul class="m-0 pl-4">
                                <li>
                                    First of all, login to your firebase account.
                                </li>
                                <li>
                                    Then, create a new project in firebase or select an existing project.
                                </li>
                                <li>
                                    Then, go to project settings.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-12">
                        <div class="export-steps-item h-100">
                            <div class="d-flex gap-3 justify-content-between align-items-center">
                                <div>
                                    <h3 class="fz-20 text-dark">Step 2</h3>
                                    <div>
                                        Generate new private key
                                    </div>
                                </div>
                                <img src="{{ asset('assets/images/firebase-download.png') }}" alt="" width="60">
                            </div>

                            <h4 class="mt-3 text-dark fz-20">Instruction</h4>

                            <ul class="m-0 pl-4">
                                <li>
                                    Go to <strong>Service account</strong> in project settings."
                                </li>
                                <li>
                                    Click on <strong>Generate new private key</strong> button.
                                </li>
                                <li>
                                    Then, click on <strong>Generate key</strong> button.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="col-12">
                        <div class="export-steps-item h-100">
                            <div class="d-flex gap-3 justify-content-between align-items-center">
                                <div>
                                    <h3 class="fz-20 text-dark">Step 3</h3>
                                    <div>
                                        Upload firebase Credential
                                    </div>
                                </div>
                                <img src="{{ asset('assets/images/firebase-upload.png') }}" alt="" width="60">
                            </div>

                            <h4 class="mt-3 text-dark fz-20">Instruction</h4>

                            <ul class="m-0 pl-4">
                                <li>
                                    Select or drag and drop your generate private key file here.
                                </li>
                                <li>
                                    Then, click on <strong>Upload</strong> button.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
            </div>
        </div>
        </div>
    </div>

    <div class="container-fluid mt-3">

        @hasPermission('admin.firebase.update')
            @if ($hasConfig)
                <div class="card my-3">
                    <div class="card-body text-center">
                        <h4 class="text-success mb-3">
                            <i class="fa-solid fa-check-circle"></i> {{ __('Firebase Configuration Completed') }}
                        </h4>
                        <p class="text-muted mb-3">
                            {{ __('You already have a Firebase configuration file uploaded.') }}
                        </p>

                        <div class="d-flex justify-content-center mb-3">
                            <div class="bg-light p-2 rounded-3 fz-20 d-flex align-items-center justify-content-center flex-wrap gap-1">
                                <span class="badge bg-primary">{{ __('Project ID') }}: </span>
                                <span class="badge bg-light text-dark fw-bold">{{ $projectId }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <!-- Download Button -->
                            <a href="{{ $filePath }}" class="btn btn-primary px-4 py-2.5" download target="_blank">
                                <i class="fa-solid fa-download"></i> {{ __('Download JSON File') }}
                            </a>

                            <!-- Change Configuration Button -->
                            <button id="changeConfigBtn" class="btn btn-info px-4 py-2.5">
                                <i class="fa-solid fa-pen-to-square"></i> {{ __('Change Configuration') }}
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger d-flex align-items-center justify-content-between p-3 rounded-3 shadow-sm mt-3"
                    role="alert" id="alertBox">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-container d-flex justify-content-center align-items-center rounded-circle bg-danger text-white"
                            style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <strong class="h5 mb-1">{{ __('Firebase Configuration Incomplete') }}</strong>
                            <p class="mb-0">
                                {{ __('Please complete Firebase configuration to enable notifications. Notifications will not be sent without it.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card my-3 {{ $hasConfig ? 'd-none' : '' }} " id="uploadSection">
                <div class="card-body text-center">
                    <h4 class="text-muted mb-3">
                        {{ __('Select generated Json File') }}
                    </h4>
                    <form action="{{ route('admin.firebase.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="drop-zone mx-auto">
                            <span class="drop-zone__prompt">
                                <div class="icon">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                {{ __('Drop file here or click to upload') }}
                            </span>
                            <input name="file" type="file" class="drop-zone__input" accept=".json">
                        </div>
                        @error('file')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror

                        <div id="gallery" style="display: none">
                            <button type="submit" class="btn btn-primary btn-lg mt-3 py-2">
                                {{ __('Upload File') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        @endhasPermission
    </div>
@endsection

@push('css')
    <style>
        .export-steps-item ul li {
            margin-bottom: 6px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/scripts/drop-zone.js') }}"></script>
    <script>
        $('input[name="file"]').change(function() {
            $('#gallery').css('display', 'block');
        });

        $('#changeConfigBtn').click(function() {
            $('#uploadSection').removeClass('d-none');
        });
    </script>
@endpush
