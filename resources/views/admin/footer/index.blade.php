@extends('layouts.app')
@section('header-title', __('Footer'))
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4 class="m-0">{{ __('Footer') }}</h4>
    </div>

    <div class="container-fluid my-3">

        <div class="row gy-3">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header py-2.5">
                        <h4 class="card-title m-0 fz-16">
                            {{ __('Menus') }}
                        </h4>
                        <p class="fs-16">
                            {{ __('Drag and Drop to use menu') }}
                        </p>
                    </div>
                    <div class="card-body" style="max-height: 362px; overflow-y: auto">
                        <div class="allMenu-list d-flex flex-column gap-2">
                            @foreach ($menus as $menu)
                                <div class="allMenu-item" data-id="{{ $menu->id }}" data-type="menu">
                                    <div class="move-icon">
                                        <i class="fa-solid fa-arrows-up-down"></i>
                                    </div>
                                    <div>
                                        {{ $menu->name }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header py-2.5">
                        <h4 class="card-title m-0 fz-16">
                            {{ __('Pages') }}
                        </h4>
                        <p class="fs-16">
                            {{ __('Drag and Drop to use page') }}
                        </p>
                    </div>
                    <div class="card-body" style="max-height: 362px; overflow-y: auto">
                        <div class="page-list d-flex flex-column gap-2">
                            @foreach ($pages as $page)
                                <div class="page-item" data-id="{{ $page->id }}" data-type="page">
                                    <div class="move-icon">
                                        <i class="fa-solid fa-arrows-up-down"></i>
                                    </div>
                                    <div>
                                        {{ $page->title }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header py-2.5">
                        <h4 class="card-title m-0 fz-16">
                            {{ __('Disabled Items') }}
                        </h4>
                        <p class="fs-16">
                            {{ __('Drag and Drop to enable or disable') }}
                        </p>
                    </div>
                    <div class="card-body" style="max-height: 362px; overflow-y: auto">
                        <div class="disabled-list d-flex flex-column gap-2" style="min-height: 100%;">
                            @forelse ($disableItems as $item)
                                <div class="disabled-item" data-id="{{ $item->id }}" data-type="item">
                                    <div class="move-icon">
                                        <i class="fa-solid fa-arrows-up-down"></i>
                                    </div>

                                    @if ($item->type == 'logo')
                                        <div class="icon">
                                            <img src="{{ $generaleSetting?->footerLogo }}" alt="icon" loading="lazy"
                                                class="img-fluid" style="max-width: 100%" />
                                        </div>
                                    @elseif ($item->type == 'social_links')
                                        <div class="icon">
                                            <img src="{{ asset('assets/images/footer_social.png') }}" alt="icon"
                                                loading="lazy" class="img-fluid" style="max-width: 100%" />
                                        </div>
                                    @elseif ($item->type == 'app_store')
                                        <div class="icon">
                                            <img src="{{ asset('assets/images/app_store.png') }}" alt="icon"
                                                loading="lazy" class="img-fluid" style="max-width: 100%" />
                                        </div>
                                    @else
                                        <div>
                                            {{ $item->title }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="m-0 fst-italic">{{ __('No disabled items') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 main-container">
            <div class="border-bottom p-2">
                <h4 class="fz-20 mb-0">{{ __('Footer Structure') }}</h4>
                <p class="m-0">{{ __('Drag and Drop to Reorder both sections and items') }}</p>
            </div>
            <div class="p-3">
                <div class="row gy-3" id="footerSections">
                    @foreach ($footers as $footer)
                        <div class="col-lg-6 col-md-6 col-xl-3 footer-section" data-id="{{ $footer->id }}">
                            <div class="card position-relative h-100">
                                <div class="move-section">
                                    <i class="fa-solid fa-arrows-up-down-left-right"></i>
                                </div>
                                <div class="card-body">
                                    <!-- header -->
                                    @if ($footer->title)
                                        <div class="accordion" id="accordionHeader{{ $footer->id }}">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed fw-semibold fz-18"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#accordionTitle{{ $footer->id }}">
                                                        {{ $footer->title }}
                                                    </button>
                                                </h2>
                                                <div id="accordionTitle{{ $footer->id }}" class="accordion-collapse collapse">
                                                    <div class="accordion-body border">
                                                        <form action="{{ route('admin.footer.update', $footer->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <x-input name="title" label="Title" type="text" class="form-control-sm"
                                                                value="{{ $footer->title }}" placeholder="Enter Title"
                                                                required="true" />

                                                            <button class="btn btn-primary btn-sm mt-3" type="submit">
                                                                {{ __('Update') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @php
                                        $items = $footer->items()->where('is_active', 1)->orderBy('order')->get();
                                    @endphp
                                    <ul class="accordion menu-list" id="list-{{ $footer->id }}"
                                        data-section-id="{{ $footer->id }}" style="min-height: 100%">
                                        @foreach ($items as $item)
                                            <li class="accordion-item" data-id="{{ $item->id }}">
                                                @if ($item->type == 'logo')
                                                    <div class="position-relative footerImage">
                                                        <div class="move-media">
                                                            <i class="bi bi-arrows-move"></i>
                                                        </div>
                                                        <div class="icon">
                                                            <img src="{{ $generaleSetting?->footerLogo }}" alt="icon"
                                                                loading="lazy" class="img-fluid"
                                                                style="max-width: 100%" />
                                                        </div>
                                                    </div>
                                                @elseif ($item->type == 'social_links')
                                                    <div class="position-relative footerImage">
                                                        <div class="move-media">
                                                            <i class="bi bi-arrows-move"></i>
                                                        </div>
                                                        <div class="icon">
                                                            <img src="{{ asset('assets/images/footer_social.png') }}"
                                                                alt="icon" loading="lazy" class="img-fluid"
                                                                style="max-width: 100%" />
                                                        </div>
                                                    </div>
                                                @elseif ($item->type == 'app_store')
                                                    <div class="position-relative footerImage">
                                                        <div class="move-media">
                                                            <i class="bi bi-arrows-move"></i>
                                                        </div>
                                                        <div class="icon">
                                                            <img src="{{ asset('assets/images/app_store.png') }}"
                                                                alt="icon" loading="lazy" class="img-fluid"
                                                                style="max-width: 100%" />
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="accordion-header position-relative">
                                                        <div class="move-media">
                                                            <i class="bi bi-arrows-move"></i>
                                                        </div>
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $item->id }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapse{{ $item->id }}">
                                                            {{ $item->title }}
                                                        </button>
                                                    </div>
                                                    <div id="collapse{{ $item->id }}"
                                                        class="accordion-collapse collapse"
                                                        data-bs-parent="#list-{{ $footer->id }}">
                                                        <div class="accordion-body">
                                                            <form method="POST"
                                                                action="{{ route('admin.footer.update.item', $item->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="row">
                                                                    @if ($item->type == 'email' || $item->type == 'phone')
                                                                        <div class="col-12 mb-3">
                                                                            <label class="form-label mb-1">
                                                                                {{ $item->type == 'email' ? __('Email') : __('Phone') }}
                                                                            </label>
                                                                            <input
                                                                                type="{{ $item->type == 'email' ? 'email' : 'text' }}"
                                                                                name="title"
                                                                                class="form-control form-control-sm"
                                                                                placeholder=""
                                                                                value="{{ $item->title }}" required />
                                                                        </div>
                                                                    @elseif ($item->type == 'text')
                                                                        <div class="col-12 mb-3">
                                                                            <label class="form-label mb-1">
                                                                                {{ __('Short Text') }}
                                                                            </label>
                                                                            <textarea name="title" class="form-control form-control-sm" placeholder="short text" required>{{ $item->title }}</textarea>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-12 mb-3">
                                                                            <label class="form-label mb-1">
                                                                                {{ __('Navigation Label') }}
                                                                            </label>
                                                                            <input type="text"
                                                                                class="form-control form-control-sm"
                                                                                placeholder="{{ __('Navigation Label') }}"
                                                                                name="title"
                                                                                value="{{ $item->title }}"
                                                                                required="">
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <label
                                                                                class="form-label mb-1">{{ __('URL') }}</label>
                                                                            <input type="text" name="url"
                                                                                class="form-control form-control-sm"
                                                                                placeholder=""
                                                                                value="{{ $item->url }}" required />
                                                                        </div>
                                                                    @endif
                                                                    <div class="col-12">
                                                                        <div class="d-flex align-items-center">
                                                                            <a href="{{ route('admin.footer.destroy', $item->id) }}"
                                                                                class="btn btn-danger btn-sm deleteConfirm">
                                                                                {{ __('Delete') }}
                                                                            </a>
                                                                            <span class="mx-2 text-secondary">|</span>
                                                                            <button type="submit"
                                                                                class="btn btn-primary btn-sm">
                                                                                {{ __('Update') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection
@push('css')
    <style>
        .main-container {
            background: #e3e4e68c;
            border-radius: 8px;
        }

        .disabled-item,
        .allMenu-item,
        .page-item {
            display: flex;
            align-items: center;
            position: relative;
            border: 1px solid #e6e6e6;
            gap: 0.5rem;
            border-radius: 4px;
            color: #000;
            overflow: hidden;
        }

        .disabled-item .move-icon,
        .allMenu-item .move-icon,
        .page-item .move-icon {
            padding: 0.5rem 1rem;
            background: var(--theme-hover-bg);
            color: var(--theme-color);
            cursor: move;
        }

        .app-theme-dark .disabled-item,
        .app-theme-dark .allMenu-item,
        .app-theme-dark .page-item {
            border: 1px solid var(--dark-border-color);
            color: #fff;
            background: var(--dark-theme-body-color);
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .move-media {
            padding: 0.5rem 0.825rem;
            display: flex;
            margin: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            height: 100%;
            border: 0;
            z-index: 2;
            background: none;
            position: absolute;
            color: var(--theme-color);
            background-color: var(--theme-hover-bg);
            top: 0;
            font-size: 1.25rem;
            left: 0;
            align-items: center;
        }

        .move-media+.accordion-button {
            padding-left: 3.5rem;
            background: #fff;
            color: #312a2a;
            position: relative;
            z-index: 1;
            border: 1px solid #e6e6e6;
        }

        .move-media+.icon {
            padding-left: 2.8rem;
            background: var(--theme-color);
            color: #312a2a;
            position: relative;
            z-index: 1;
            border: 1px solid var(--theme-color);
        }

        .disabled-item .icon {
            flex-grow: 1;
            background: var(--theme-color);
            border: 1px solid var(--theme-color);
        }

        .disabled-item .icon,
        .footerImage .icon {
            height: 56px
        }

        .disabled-item .icon img,
        .footerImage .icon img {
            height: 100%;
            width: 100%;
            object-fit: contain;

        }

        .app-theme-dark .move-media+.accordion-button {
            background: var(--dark-theme-body-color) !important;
            border: 1px solid var(--dark-border-color);
        }

        .accordion {
            border-color: transparent !important;
        }

        .accordion .accordion-item {
            border-color: #e6e6e6;
            max-width: 550px;
        }

        .app-theme-dark .accordion .accordion-item {
            border-color: var(--dark-border-color);
        }

        .accordion-item:first-of-type {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .accordion-item {
            margin-bottom: 1rem;
            border: 0;
        }

        .menu-list .accordion-body {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border: 1px solid rgb(238, 238, 238);
            border-top: 0;
        }

        .accordion-body {
            padding: 0.875rem 1.25rem;
        }

        .app-theme-dark .accordion-body {
            color: #fff;
        }

        .accordion-button {
            padding: 0.6rem;
        }

        .accordion-button::after {
            width: 1rem;
            height: 1rem;
            background-size: 1rem;
        }

        .accordion-button:not(.collapsed) {
            box-shadow: none;
        }

        .move-media {
            cursor: move;
        }

        .footer-section .move-section {
            cursor: move;
            position: absolute;
            top: -15px;
            right: -12px;
            height: 40px;
            width: 40px;
            z-index: 2;
            background: var(--theme-color);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            border: 0;
            transition: all 0.5s ease-in-out;
            border-radius: 100%;
            box-shadow: 0px 2px 4px 0px #aaa;
        }

        .footer-section:hover .move-section {
            transition: all 0.5s ease-in-out;
            display: flex;
        }

        .accordion-item.blue-background-class {
            background-color: #300d0d00 !important;
        }

        .accordion-item.blue-background-class .accordion-button {
            color: var(--theme-hover-bg) !important;
        }

        .accordion-item.blue-background-class .move-media {
            background-color: #300d0d00 !important;
            color: var(--theme-hover-bg) !important;
        }

        .footer-section.blue-background-class .card {
            border: 1px solid var(--theme-color) !important;
            opacity: 0.5;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('assets/scripts/Sortable.min.js') }}"></script>
    <script>
        // menu items
        new Sortable(document.querySelector('.allMenu-list'), {
            group: {
                name: "footer-items",
                pull: "clone",
                put: false
            },
            animation: 150,
            sort: false,
            handle: ".move-icon"
        });

        // disabled items
        new Sortable(document.querySelector('.disabled-list'), {
            group: {
                name: "footer-items",
                put: function(to, from) {
                    return from.el.className == 'accordion menu-list';
                }
            },
            animation: 150,
            sort: false,
            handle: ".move-icon",
            onAdd: function(evt) {
                let draggedItem = evt.item;
                let dataId = draggedItem.getAttribute('data-id');

                if (dataId) {
                    $.ajax({
                        url: "{{ route('admin.footer.disabled') }}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            id: dataId
                        },
                        success: function(response) {
                            location.reload();
                        }
                    });
                }
            }
        });

        // Pages items
        new Sortable(document.querySelector('.page-list'), {
            group: {
                name: "footer-items",
                pull: "clone",
                put: false
            },
            animation: 150,
            sort: false,
            handle: ".move-icon"
        });

        // sections
        new Sortable(document.getElementById('footerSections'), {
            animation: 150,
            handle: ".move-section",
            ghostClass: 'blue-background-class',
            onEnd: function(evt) {
                let sections = document.querySelectorAll('.footer-section');
                let sortedSections = [];

                sections.forEach((section, index) => {
                    sortedSections.push({
                        id: section.getAttribute('data-id'),
                        position: index + 1
                    });
                });

                sectionSort(sortedSections);
            }
        });

        // items
        document.querySelectorAll('.menu-list').forEach(list => {
            new Sortable(list, {
                group: 'footer-items',
                animation: 150,
                handle: ".move-media",
                ghostClass: 'blue-background-class',
                onAdd: function(evt) {
                    let draggedItem = evt.item;
                    let sectionId = evt.to.getAttribute('data-section-id');
                    let dataId = draggedItem.getAttribute('data-id');
                    let position = Array.from(evt.to.children).indexOf(draggedItem) + 1;
                    let type = draggedItem.getAttribute('data-type');

                    let finalData = {
                        id: dataId,
                        position: position,
                        section_id: sectionId,
                        type: type
                    };
                    dragOnAddNewItem(finalData);
                },
                onEnd: function(evt) {
                    let sectionId = evt.to.getAttribute('data-section-id');
                    let items = evt.to.children;
                    let sortedItems = [];

                    for (let i = 0; i < items.length; i++) {
                        sortedItems.push({
                            id: items[i].getAttribute('data-id'),
                            position: i + 1,
                            section_id: sectionId
                        });
                    }

                    dragItemSort(sortedItems);
                }
            });
        });

        const sectionSort = (sortedData) => {
            $.ajax({
                url: "{{ route('admin.footer.sectionSort') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    sorted_data: sortedData
                },
                success: function(response) {
                    location.reload();
                }
            });
        }

        const dragItemSort = (sortData) => {
            $.ajax({
                url: "{{ route('admin.footer.itemSort') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    sorted_data: sortData
                },
                success: function(response) {
                    // location.reload();
                }
            });
        }

        const dragOnAddNewItem = (sortData) => {
            $.ajax({
                url: "{{ route('admin.footer.addedNew') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: sortData,
                success: function(response) {
                    location.reload();
                }
            });
        }
    </script>
@endpush
