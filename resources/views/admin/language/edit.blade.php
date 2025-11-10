@extends('layouts.app')
@section('header-title', __('Edit Language'))

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="m-0">{{ __('Edit Language') . '(' . $language->name . ')' }}</h4>
            <a href="{{ route('admin.language.index') }}" class="btn btn-danger">
                {{ __('Back') }}
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-12 mt-4">
            <div class="card-body">
                <form action="{{ route('admin.language.update', $language->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="form-label fw-bold mb-1">
                                {{ __('Title') . ' for ' . $language->name . '' }}
                            </label>
                            <div class="input-group">
                                <input type="text" name="title" class="form-control py-2.5" placeholder="title"
                                    value="{{ $language->title }}" />
                            </div>
                            @error('title')
                                <p class="text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('Direction') }}</label>
                            <div class="d-flex flex-wrap align-items-center gap-5 border rounded fw-medium"
                                style="padding: 10px;">
                                <div class="flex-grow-1">
                                    <input type="radio" name="direction" value="ltr" class="form-check-input"
                                        id="ltr" {{ $language->direction == 'ltr' ? 'checked' : '' }}>
                                    <label for="ltr" class="m-0 cursor-pointer">
                                        {{ __('Left to Right') }}
                                    </label>
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center gap-2">
                                    <input type="radio" name="direction" value="rtl" class="form-check-input"
                                        id="rtl" {{ $language->direction == 'rtl' ? 'checked' : '' }}>
                                    <label for="rtl" class="m-0 cursor-pointer">
                                        {{ __('Right to Left') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div>
                                <h5>
                                    {{ __('Flag ') }}
                                    <span class="text-primary bg-light">Ratio (300 x 180 px)</span>
                                    <span class="text-danger">*</span>
                                </h5>
                                @error('flag')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="dropzone-container">
                                <label for="thumbnail" class="mainThumbnail">
                                    <img src="{{ $language->flag ?? 'https://placehold.co/500x500/png' }}" id="preview"
                                        alt="" width="30%" class="dropzone-area">
                                </label>
                                <input id="thumbnail" accept="image/*" type="file" data-crop="true" name="flag"
                                    class="d-none" onchange="previewFile(event, 'preview')" data-preview="preview"
                                    data-width="300" data-height="180">
                                <small class="text-muted d-block">
                                    {{ __('Supported formats: jpg, jpeg, png') }}
                                </small>
                            </div>
                            {{-- <div class="mt-3 d-flex align-items-center justify-content-center">
                                <div class="ratio1x1">
                                    <img id="previewProfile"
                                        src="{{ $language->flag ?? 'https://placehold.co/500x500/png' }}" alt="photo"
                                        width="100%">
                                </div>
                            </div>
                            <div class="mt-2">
                                <x-file name="flag" label="User profile (Ratio 1:1)" preview="previewProfile" />
                            </div> --}}
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary py-2.5 px-4 mt-3">
                        <i class="fa-solid fa-floppy-disk"></i>
                        {{ __('Update') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="row mb-4 mt-4">
            <div class="col-md-6">
                @hasPermission('admin.language.import')
                    <div class="card shadow border-0 rounded-lg import-card">
                        <div class="card-body text-center">
                            <h4 class="text-dark mb-2 font-weight-bold">
                                {{ __('Select JSON File to Import') }}
                            </h4>
                            <p class="text-muted mb-3">
                                Upload a JSON file to update your language settings.
                            </p>

                            <form action="{{ route('admin.language.import', $language->id) }}" method="POST"
                                enctype="multipart/form-data" id="bulkForm">
                                @csrf

                                <!-- Enhanced Drop Zone -->
                                <div class="drop-zone mx-auto">
                                    <span class="drop-zone__prompt">
                                        <div class="icon">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        </div>
                                        {{ __('Drop file here or click to upload') }}
                                    </span>
                                    <input name="file" type="file" class="drop-zone__input" accept=".json">
                                </div>

                                <!-- File Input Error -->
                                @error('file')
                                    <p class="text-danger m-0">{{ $message }}</p>
                                @enderror

                                <!-- Submit Button -->
                                <div id="galler" style="display: none">
                                    <button type="submit" class="btn btn-primary btn-lg mt-4 py-2 px-4">
                                        {{ __('Update Language JSON') }}
                                        <i class="fa-solid fa-upload ml-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endhasPermission
            </div>

            <div class="col-md-6">
                @hasPermission('admin.language.export')
                    <div class="card h-100 shadow border-0  rounded-lg export-card">
                        <div class="card-body text-center">
                            <div class="icon-container mb-3">
                                <i class="fa-solid fa-file-code fa-3x text-primary"></i>
                            </div>
                            <h4 class="text-dark mb-3 font-weight-bold">
                                {{ __('Export JSON File') }}
                            </h4>
                            <p class="text-muted">
                                Export your language files in JSON format with just one click!
                            </p>
                            <form action="{{ route('admin.language.export', $language->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg mt-4 export-btn py-2 px-4">
                                    {{ __('Export') }}
                                    <i class="fa-solid fa-cloud-arrow-down ml-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endhasPermission
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-12 mb-3">
            <div class="card-header py-2.5">
                <h4 class="m-0">
                    {{ $language->name }}.json (file content)
                </h4>
            </div>
            <div class="card-body p-3">
                <div class="mb-2">
                    <input type="text" class="form-control" placeholder="Search in JSON" id="search"
                        onkeyup="filterJSON()" />
                </div>
                <div class="json-view-container">
                    @foreach ($languageData as $key => $value)
                        <div class="json-item">
                            <span class="json-key">"{{ $key }}":</span>
                            <span class="json-value">{{ is_null($value) ? 'null' : '"' . $value . '"' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection

@push('css')
    <style>
        .export-card {
            background-color: #f8f9fa;
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 20px;
            border-radius: 15px;
        }

        .export-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .icon-container {
            background-color: #e7f3ff;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        h4 {
            color: #343a40;
        }

        .app-theme-dark h4 {
            color: #f8f9fa;
        }

        .export-btn {
            display: inline-block;
            border-radius: 8px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .export-btn i {
            margin-left: 10px;
        }

        p {
            font-size: 14px;
            color: #6c757d;
        }

        .import-card {
            background-color: #f8f9fa;
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
        }

        .import-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .json-view-container {
            background-color: #2d2d2d;
            color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            overflow-y: auto;
            max-height: 600px;
        }

        .json-item {
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
        }

        .json-item:last-child {
            border-bottom: none;
        }

        .json-key {
            color: #66d9ef;
            font-weight: bold;
        }

        .json-value {
            color: #a6e22e;
        }

        .highlight {
            background-color: yellow;
            /* Highlight color */
            font-weight: bold;
            /* Make it bold */
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/scripts/drop-zone.js') }}"></script>
    <script>
        $('input[name="file"]').change(function() {
            $('#galler').css('display', 'block');
        });

        function selectFolder(button, name) {
            var gallery = $('#bulkForm');

            var input = $('#input' + name);

            if (input.length) {
                input.remove();
                $(button).removeClass('active');
            } else {
                var element = document.createElement('input');
                element.type = 'hidden';
                element.name = 'folder[' + name + ']';
                element.value = name;
                element.id = 'input' + name;
                gallery.append(element);

                $(button).addClass('active');
            }
        }

        function submitForm() {
            $('#galleryModal').modal('hide');

            $('#bulkForm').submit();
        }
    </script>

    @if (session('successAlert'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Language Import Successful',
                text: "{{ session('successAlert') }}"
            });
        </script>
    @endif

    <script>
        let debounceTimer;

        function filterJSON() {
            clearTimeout(debounceTimer); // Clear the previous timer

            debounceTimer = setTimeout(() => {
                const searchInput = document.getElementById('search').value.toLowerCase();
                const jsonItems = document.querySelectorAll('.json-item');

                jsonItems.forEach(item => {
                    const key = item.querySelector('.json-key').innerText.toLowerCase();
                    const value = item.querySelector('.json-value').innerText.toLowerCase();
                    const keyElement = item.querySelector('.json-key');
                    const valueElement = item.querySelector('.json-value');

                    // Reset highlighting
                    keyElement.innerHTML = key; // Reset to original key
                    valueElement.innerHTML = value; // Reset to original value

                    // Check if the key or value includes the search string
                    let isMatch = false;
                    if (key.includes(searchInput) || value.includes(searchInput)) {
                        // Highlight matching text
                        if (key.includes(searchInput)) {
                            keyElement.innerHTML = key.replace(new RegExp(`(${searchInput})`, 'gi'),
                                '<span class="highlight">$1</span>');
                            isMatch = true;
                        }
                        if (value.includes(searchInput)) {
                            valueElement.innerHTML = value.replace(new RegExp(`(${searchInput})`, 'gi'),
                                '<span class="highlight">$1</span>');
                            isMatch = true;
                        }

                        item.style.display = 'flex'; // Show item
                    } else {
                        item.style.display = 'flex'; // Show all items
                    }
                });

                // Scroll to the first matching item
                const firstMatch = [...jsonItems].find(item => {
                    const key = item.querySelector('.json-key').innerText.toLowerCase();
                    const value = item.querySelector('.json-value').innerText.toLowerCase();
                    return key.includes(searchInput) || value.includes(searchInput);
                });

                if (firstMatch) {
                    firstMatch.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
            }, 300); // Delay in milliseconds
        }
    </script>

@endpush
