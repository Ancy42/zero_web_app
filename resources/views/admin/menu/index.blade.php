@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>{{ __('Menus') }}</h4>
        <button class="btn btn-primary py-2.5" data-bs-toggle="modal" data-bs-target="#addMenu">
            <i class="fa-solid fa-plus"></i> {{ __('Add New Menu') }}
        </button>
    </div>

    <div class="container-fluid my-3">

        <div class="row gy-3">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header py-3">
                        <h4 class="card-title m-0">
                            {{ __('Inactive Menus') }}
                        </h4>
                        <p class="fs-13">
                            {{ __('Drag and Drop to Inactive Menus') }}
                        </p>
                    </div>
                    <div class="card-body">
                        <ul class="accordion menu-list" id="simpleInactiveList">
                            @forelse ($inActiveMenus as $menu)
                                <li class="accordion-item menu-item" id="{{ $menu->id }}" data-id="{{ $menu->id }}">
                                    <div class="accordion-header position-relative">
                                        <div class="move-media dd-handle">
                                            <i class="fa-solid fa-arrows-up-down-left-right"></i>
                                        </div>
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $menu->id }}" aria-expanded="false"
                                            aria-controls="collapse{{ $menu->id }}">
                                            {{ $menu->name }}
                                        </button>
                                    </div>
                                    <div id="collapse{{ $menu->id }}" class="accordion-collapse collapse"
                                        data-bs-parent="#simpleInactiveList" style="">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            {{ __('URL') }}
                                                        </label>
                                                        <input type="text" class="form-control solid"
                                                            placeholder="{{ $menu->url }}" value="{{ $menu->url }}"
                                                            @if (!$menu->is_external) readonly @endif />
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            {{ __('Navigation Label') }}
                                                        </label>
                                                        <input type="text" class="form-control solid"
                                                            placeholder="{{ $menu->name }}"
                                                            value="{{ $menu->name }}" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            {{ __('Title Attribute') }}
                                                        </label>
                                                        <input type="text" class="form-control solid"
                                                            placeholder="{{ $menu->title }}"
                                                            value="{{ $menu->title }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            @if (!$menu->is_default)
                                                <a href="{{ route('admin.menu.destroy', $menu->id) }}"
                                                    class="btn btn-danger deleteConfirm btn-sm">
                                                    <i class="fa-solid fa-trash text-white"></i> {{ __('Delete') }}
                                                </a>
                                            @endif
                                            <a href=""></a>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <h5 class="text-muted fst-italic fs-5">
                                    {{ __('No Inactive Menus') }}
                                </h5>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="filter cm-content-box box-primary">
                        <div class="cm-content-body form excerpt rounded-0">
                            <div class="card-body">
                                <h6 class="mb-1">
                                    {{ __('Active Menu Structure') }}
                                </h6>
                                <p class="fs-13 mb-4">
                                    {{ __('Drag and Drop to Reorder') }}
                                </p>
                                <div class="">
                                    <div class="menu-list">
                                        <ul class="accordion" id="simpleList">
                                            @forelse ($activeMenus as $menu)
                                                <li class="accordion-item menu-item" id="{{ $menu->id }}"
                                                    data-id="{{ $menu->id }}">
                                                    <div class="accordion-header position-relative">
                                                        <div class="move-media dd-handle">
                                                            <i class="fa-solid fa-arrows-up-down-left-right"></i>
                                                        </div>
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $menu->id }}"
                                                            aria-expanded="false" aria-controls="collapseOne">
                                                            {{ $menu->name }}
                                                        </button>
                                                    </div>
                                                    <div id="collapse{{ $menu->id }}"
                                                        class="accordion-collapse collapse" data-bs-parent="#simpleList">
                                                        <div class="accordion-body">
                                                            <form method="POST"
                                                                action="{{ route('admin.menu.update', $menu->id) }}">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">
                                                                                {{ __('URL') }}
                                                                            </label>
                                                                            <input type="text" name="menu_url"
                                                                                class="form-control solid"
                                                                                placeholder="{{ $menu->url }}"
                                                                                value="{{ $menu->url }}"
                                                                                @if (!$menu->is_external) readonly @endif>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">
                                                                                {{ __('Navigation Label') }}
                                                                            </label>
                                                                            <input type="text"
                                                                                class="form-control solid"
                                                                                placeholder="{{ $menu->name }}"
                                                                                name="menu_name"
                                                                                value="{{ $menu->name }}" required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">
                                                                                {{ __('Title Attribute') }}
                                                                            </label>
                                                                            <input type="text"
                                                                                class="form-control solid"
                                                                                placeholder="{{ $menu->title }}"
                                                                                name="menu_title"
                                                                                value="{{ $menu->title }}" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <a href="{{ route('admin.menu.remove', $menu->id) }}"
                                                                            class="btn btn-danger btn-sm">
                                                                            {{ __('Remove') }}
                                                                        </a>
                                                                        <span class="mx-2 text-secondary">|</span>

                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm">
                                                                            {{ __('Update') }}
                                                                        </button>
                                                                        <span class="mx-2 text-secondary">|</span>

                                                                        <a class="text-hover cancel collapsed"
                                                                            href="javascript:void(0);"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#collapse{{ $menu->id }}"
                                                                            aria-expanded="false"
                                                                            aria-controls="collapse{{ $menu->id }}">
                                                                            Cancel
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </li>
                                            @empty
                                                <li>
                                                    <h5 class="text-muted fst-italic fs-5">
                                                        {{ __('No Active Menus') }}
                                                    </h5>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <form method="POST" action="{{ route('admin.menu.store') }}">
            @csrf
            <div class="modal fade" id="addMenu" tabindex="-1" aria-labelledby="addMenu" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">
                                {{ __('Add New Menu') }}
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                                <x-input type="text" label="Navigation Label" placeholder="Enter Navigation Label"
                                    name="name" required="true" />
                            </div>

                            <div class="mt-3">
                                <div class="mt-3">
                                    <label class="form-label">{{ __('Open Link In') }}</label>
                                    <select name="target" class="form-control select2" style="width: 100%">
                                        <option value="_self">{{ __('Open in the same tab') }}</option>
                                        <option value="_blank">{{ __('Open in a new tab') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3" id="pageUrl">
                                <x-select name="page_id" label="Select Page" placeholder="Select Page" required="true">
                                    @foreach ($pages as $page)
                                        <option value="">{{ __('Select Page') }}</option>
                                        <option value="{{ $page->id }}" data-slug="{{ $page->slug }}">
                                            {{ $page->title }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="mt-3 d-none" id="customUrl">
                                <x-input type="url" label="Custom URL" placeholder="Enter Custom URL"
                                    name="custom_url" />
                            </div>
                            <div class="mt-2 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="customUrlBtn">
                                    {{ __('Custom URL') }}
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm d-none" id="pageUrlBtn">
                                    {{ __('Page URL') }}
                                </button>
                            </div>

                            <div class="mt-3 border p-2 rounded d-flex align-items-center justify-content-between flex-wrap gap-2"
                                style="max-width: 400px">
                                <label class="form-label m-0 fw-bold" for="isActive">
                                    {{ __('Is Active') }}
                                </label>
                                <label class="switch mb-0">
                                    <input type="checkbox" name="is_active" id="isActive">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary py-2.5 px-4" data-bs-dismiss="modal">
                                {{ __('Close') }}
                            </button>
                            <button type="submit" class="btn btn-primary py-2.5 px-4">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection
@push('css')
    <style>
        #simpleList,
        #simpleInactiveList {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dd-handle.move-media {
            padding: 0.5rem 1rem;
            display: flex;
            margin: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            height: 100%;
            border: 0;
            z-index: 2;
            background: none;
            position: absolute;
            color: #fff;
            background-color: var(--theme-color);
            top: 0;
            font-size: 1.25rem;
            left: 0;
            align-items: center;
        }

        .dd-handle.move-media+.accordion-button {
            padding-left: 4.063rem;
            background: #fff;
            color: #312a2a;
            position: relative;
            z-index: 1;
            border: 1px solid #e6e6e6;
        }

        .accordion {
            border-color: transparent !important;
        }

        .accordion .accordion-item {
            border-color: #e6e6e6;
            max-width: 550px;
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

        .accordion-button:not(.collapsed) {
            box-shadow: none;
        }

        .move-media {
            cursor: move;
        }

        .menu-item.blue-background-class {
            background-color: #300d0d00 !important;
        }

        .menu-item.blue-background-class .dd-handle.move-media {
            background-color: var(--theme-hover-bg) !important;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('assets/scripts/Sortable.min.js') }}"></script>
    <script>
        new Sortable.create(simpleList, {
            group: 'shared',
            handle: ".move-media",
            animation: 150,
            ghostClass: 'blue-background-class',
            onEnd: function(evt) {

                if (evt.from.id === 'simpleList' && evt.to.id === 'simpleInactiveList') {
                    const draggedItem = evt.item; // The item that was dragged
                    const dataId = draggedItem.getAttribute('data-id'); // Get the data-id of the dragged item
                    console.log(`Item with ID ${dataId} moved from Active to Inactive.`);

                    dragMenuActiveInactive(dataId, 'inactive');
                }

                const items = evt.from.children;
                const sortedData = [];

                for (let i = 0; i < items.length; i++) {
                    const item = items[i];
                    const dataId = item.getAttribute('data-id');
                    sortedData.push({
                        id: dataId,
                        position: i + 1
                    });
                }
                // console.log(sortedData);
                sendToServer(sortedData);
            }
        });

        new Sortable.create(simpleInactiveList, {
            group: 'shared',
            animation: 150,
            ghostClass: 'blue-background-class',
            onEnd: function(evt) {
                // Check if an item was moved from inactive to active
                if (evt.from.id === 'simpleInactiveList' && evt.to.id === 'simpleList') {
                    const draggedItem = evt.item; // The item that was dragged
                    const dataId = draggedItem.getAttribute('data-id'); // Get the data-id of the dragged item
                    console.log(`Item with ID ${dataId} moved from Inactive to Active.`);
                    // You can also perform additional actions here, like updating the server
                    dragMenuActiveInactive(dataId, 'active');
                }
            }
        });

        const sendToServer = (sortedData) => {
            $.ajax({
                url: "{{ route('admin.menu.sort') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    sorted_data: sortedData
                },
                success: function(response) {
                    // location.reload();
                }
            });
        }

        const dragMenuActiveInactive = (menuId, status) => {
            $.ajax({
                url: "{{ route('admin.menu.drag') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    id: menuId,
                    status: status
                },
                success: function(response) {
                    location.reload();
                }
            });
        }

        $(document).ready(function() {
            $('#page_id').select2({
                dropdownParent: $('#addMenu .modal-content'),
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text; // Return the placeholder
                    }
                    var $result = $('<span>' + data.text + ' <small class="text-muted">(/' + $(data
                        .element).data('slug') + ')</small></span>');
                    return $result;
                },
                templateSelection: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    return data.text;
                }
            });

            $('#customUrlBtn').on('click', function() {
                $('#pageUrlBtn').removeClass('d-none');
                $('#customUrl').removeClass('d-none');
                $('#customUrlBtn').addClass('d-none');
                $('#pageUrl').addClass('d-none');

                $('#page_id').val(null).trigger('change').prop('required', false);
                $('input[name="custom_url"]').prop('required', true);
            });

            $('#pageUrlBtn').on('click', function() {
                $('#customUrlBtn').removeClass('d-none');
                $('#pageUrl').removeClass('d-none');
                $('#customUrl').addClass('d-none');
                $('#pageUrlBtn').addClass('d-none');
                $('#page_id').val(null).trigger('change').prop('required', true);
                $('input[name="custom_url"]').prop('required', false);
            });
        });
    </script>
@endpush
