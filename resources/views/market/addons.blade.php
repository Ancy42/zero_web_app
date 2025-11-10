@extends('layouts.app')

@section('header-title', __('Addons & Plugins'))
@section('header-subtitle', __('Manage Addons & Plugins'))
@include('joynala.maker::market.css')
@section('content')
    <section class="support_container">
        <header class="page_header">
            <div class=" border-b">
                <p class="header_title">{{ __('Product Support') }}</p>
                <a target="_blank" href="https://web.whatsapp.com/send?text=I%20need%20support+&phone=8801336909483"
                    class="common_btn">{{ __('Get Support') }}</a>
            </div>

            <!-- tabs  -->
            <div class="tab_container">
                <a href="{{ route('marketplace.upgrade') }}" class="btn_tab">{{ __('Product Update') }}</a>
                <a href="{{ route('marketplace.index') }}" class="btn_tab">{{ __('Marketplace') }}</a>
                <button class="btn_tab btn_tab_active">{{ __("My Addon's") }}</button>
            </div>
        </header>
    </section>

    <section class="marketplace_section_container">
        <div class="section_container ">
            <div class="w-100">
                <p class="section_title">{{ __("Upload Addon's") }}</p>
            </div>

            <div class="w-100 g-4">
                <div id="upload-area"
                    style="padding: 40px; border: 2px dashed #aaa; text-align: center; border-radius: 10px; cursor: pointer;">
                    <img src="{{ asset('assets/icons-admin/folder-upload.svg') }}" alt="" style="width:10%;">
                    <br>
                    <small>{{ __('Choose') }}
                        <strong>{{ __('ZIP') }}</strong>{{ __(' file or Drag it here to upload') }}.</small>
                    <input type="file" id="file-input" multiple hidden>

                    <div id="progress-container"
                        style="display:none; margin-top: 15px; background: #eee; border-radius: 5px; overflow: hidden;">
                        <div id="progress-bar" style="width:0%; height:10px; background:#2196f3; transition: width 0.3s;">
                        </div>
                    </div>
                    <p id="response" style="font-size: 14px; margin-top: 5px;"></p>
                </div>
            </div>
        </div>

        <div class="section_container ">
            <div class="w-100">
                <p class="section_title">{{ __("Installed Addon's") }}</p>
            </div>

            <div class="w-100 g-4">
                <div class="card p-4">
                    @foreach ($moduleData as $module)
                        <div class="mb-3 p-3 border row">
                            <div class="d-flex align-items-center col-5">
                                <img src="{{ $module['image'] }}" alt=""
                                    style="width:60px; height:auto; margin-right:15px;">
                                <div>
                                    <h5 class="mb-1">{{ $module['display_name'] }}</h5>
                                    <p class="mb-0 text-muted">{{ __('Version') }}:
                                        <strong>{{ $module['version'] }}</strong></p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center col-4">
                                <div>
                                    <h5 class="mb-1">{{ __('Purchase Key') }}</h5>
                                    @if ($module['license_key'])
                                        <p class="mb-0 text-muted">{{ $module['license_key'] }} @if ($module['is_verified'])
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        @else
                                        <p class="mb-0 text-muted">--</strong></p>
                                    @endif

                                </div>
                            </div>
                            <div class="col-3 d-flex align-items-center justify-content-end">
                                @if ($module['is_verified'])
                                    @if ($module['enabled'])
                                        <a href="{{ route('addon.status', $module['name']) }}"
                                            class="btn btn-danger">{{ __('Disable') }}</a>
                                    @else
                                        <a href="{{ route('addon.status', $module['name']) }}"
                                            class="btn btn-success">{{ __('Enable') }}</a>
                                    @endif
                                @else
                                    {{-- <a class="btn btn-info" href="">Verify Now</a> --}}
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#verifyModal_{{ $module['id'] }}">
                                        {{ __('Verify Now') }}
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="verifyModal_{{ $module['id'] }}" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                        {{ __('Verify') }} ({{ $module['display_name'] }})</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('verify.addons') }}" method="POST"> @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="purchase_key_{{ $module['id'] }}"
                                                                class="form-label">{{ __('Purchase Key') }}</label>
                                                            <input type="text" class="form-control"
                                                                id="purchase_key_{{ $module['id'] }}" name="purchase_key"
                                                                placeholder="e.g: 040afd3f-4cxa-4241-9e70-4gde9e4t674b">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="name" value="{{ $module['name'] }}">
                                                        <input type="hidden" name="id" value="{{ $module['id'] }}">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-primary">{{ __('Submit & Verify') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <style>
            #upload-area {
                border: 2px dashed #ccc;
                padding: 30px;
                text-align: center;
                transition: background 0.3s;
            }

            #upload-area.hover {
                background: #eef;
                border-color: #339;
            }
        </style>
    </section>
@endsection

@push('scripts')
    <script>
        const dropZone = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        const progressContainer = $('#progress-container');
        const progressBar = $('#progress-bar');
        const responseBox = $('#response');

        // Prevent default drag behavior
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Visual feedback for drag events
        dropZone.addEventListener('dragenter', highlight, false);
        dropZone.addEventListener('dragover', highlight, false);
        dropZone.addEventListener('dragleave', unhighlight, false);
        dropZone.addEventListener('drop', unhighlight, false);

        function highlight() {
            dropZone.classList.add('hover');
        }

        function unhighlight() {
            dropZone.classList.remove('hover');
        }

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            uploadFile(files);
        }

        // Handle file selection via input
        dropZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', (e) => uploadFile(e.target.files));

        const url = "{{ route('upload.addons') }}";

        function uploadFile(file) {
            const formData = new FormData();
            formData.append('addon_package', file[0]);
            formData.append('_token', "{{ csrf_token() }}");

            progressContainer.show();
            progressBar.css('width', '0%');
            responseBox.hide();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    let xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            let percent = (e.loaded / e.total) * 100;
                            progressBar.css('width', percent + '%');
                        }
                    });
                    return xhr;
                },
                success: function(response) {
                    console.log();

                    responseBox.show().html('<strong>' + (response.message ? response.message :
                        'Upload successful') + '</strong>');
                    progressBar.css('width', '100%');
                    // reload the page shortly after completion
                    setTimeout(function() {
                        location.reload();
                    }, 1200);
                },
                error: function(xhr) {
                    responseBox.show().html('<strong' + xhr.responseText + '</strong>');
                }
            });
        }
    </script>
@endpush
