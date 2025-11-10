@extends('layouts.app')

@section('header-title', __('Categories'))
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jsTree.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-3">

        <div class="bodywrapper__inner">

            <div class="d-flex mb-3 flex-wrap gap-3 justify-content-between align-items-center">
                <h6 class="page-title">
                    {{ __('All Categories') }}
                </h6>
            </div>

            <div class="row justify-content-center gy-4" >
                <div class="col-lg-6 col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="d-flex  flex-wrap gap-2 mb-3 p-3 rounded-3" style="background: rgb(30, 30, 30)">
                                @hasPermission('admin.category.create')
                                    <button type="reset" class="category-tree-btn addRootCategory flex-grow-1 active">
                                        {{ __('Add Root Category') }} <i class="fa fa-plus"></i>
                                    </button>

                                    <button type="button" class="category-tree-btn flex-grow-1" id="addChildBtn" disabled>
                                        {{ __('Add Child') }} <i class="fa fa-plus"></i>
                                    </button>
                                @endhasPermission

                                @hasPermission('admin.category.destroy')
                                    <button type="button" class="btn btn-sm btn-secondary flex-grow-1 category-delete-btn"
                                        id="deleteChildBtn" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endhasPermission
                            </div>

                            <input type="text" class="form-control" value="" id="treeSearch" placeholder="Search">
                            <button type="button" class="expand-collapse-btn text-muted btn text-sm mt-1"
                                data-state="close_all">
                                {{ __('Expand All') }}
                            </button>

                            <div id="categoryTree">
                                {!! $htmlTree !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="card right-sticky">
                        <div class="card-body p-4">
                            @hasPermission(['admin.category.create', 'admin.category.update'])
                                <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data"
                                    id="addForm">
                                    @csrf
                                @endhasPermission
                                <input type="hidden" name="parent_id" value="">
                                <div class="form-group row">
                                    <div class="col-xxl-10 col-xl-9 category-thumb">
                                        {{-- <div class="image--uploader">
                                            <div class="image-upload-wrapper">
                                                <div class="image-upload-preview overflow-hidden" id="holder">
                                                    <img  src="{{ asset('default/default.jpg') }}"
                                                        alt="" width="100%" height="100%"
                                                        style="object-fit: cover" />
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div>
                                            <div class="mb-2">
                                                <h5 class="mt-2">
                                                    {{ __('Thumbnail') }} <span class="text-danger">*</span>
                                                    <span class="text-muted">{{ __('(Ratio 1280 x 960 px)') }}</span>

                                                </h5>
                                                @error('image')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                             <x-image-picker name="image"  />
                                            {{-- <div class="input-group mt-2">
                                                <span class="input-group-text bg-primary text-white" id="inputGroup-sizing-lg">
                                                    <a id="lfm" data-input="thumbnailAdd" data-preview="holder" data-thumbnailPath="thumbnailPath" >
                                                    <i class="fa-solid fa-image"></i> Choose
                                                    </a>
                                                </span>
                                                <input type="text" id="thumbnailAdd" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
                                                <input id="thumbnailPath" name="image" type="hidden">
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-3">
                                    <x-input label=" Name" name="name" type="text" placeholder="Enter Name"
                                        required="true" />
                                </div>

                                <div class="form-group row mt-3 me-2">
                                    <label for="description">
                                        {{ __('Description') }}
                                    </label>

                                    <textarea name="description" rows="3" cols="36" class="form-control ms-3 mt-2" id="description"> </textarea>
                                </div>

                                <div class="form-group row mt-3 p-3 statusOption ms-2">
                                    <div class="col-11">
                                        <label for="is_active">
                                            {{ __('Is Active') }}
                                        </label>
                                    </div>
                                    <div class="col-1">
                                        <label class="switch mb-0">
                                            <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                @hasPermission(['admin.category.create', 'admin.category.update'])
                                    <div class="d-flex gap-2 mt-4">
                                        <button type="reset" class="btn btn-danger reset-button" id="addRootCategory"
                                            id="resetButton" title="Reset">
                                            {{ __('Reset') }}
                                        </button>
                                        <button type="submit" class="btn btn-primary py-2.5 flex-grow-1" id="submitButton">
                                            {{ __('Submit') }}
                                        </button>
                                    </div>
                                </form>
                            @endhasPermission
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/scripts/jstree.min.js') }}"></script>
    <script>
        $('#categoryTree').on('ready.jstree', function() {
            $('#categoryTree .jstree-icon.jstree-ocl').each(function() {
                $(this).removeClass('jstree-icon jstree-ocl')
                    .addClass('fa-solid fa-angle-up jstree-ocl');
            });
        });

        $('#categoryTree').on('open_node.jstree close_node.jstree', function(e, data) {

            $('#categoryTree .jstree-icon.jstree-ocl').each(function() {
                $(this).removeClass('jstree-icon jstree-ocl')
                    .addClass('fa-solid fa-angle-up jstree-ocl');
            });
            let $icon = $('#' + data.node.id).children('i');

            if (data.node.state.opened) {
                $icon.removeClass('fa-angle-up').addClass('fa-angle-down jstree-ocl');
            } else {
                $icon.removeClass('fa-angle-down').addClass('fa-angle-up jstree-ocl');
            }
        });
    </script>

    <script>
        let selectedNode;
        let timeout;

        const form = $('#addForm');
        const submitButton = $('#submitButton');
        const addRootCategoryButton = $('.addRootCategory');
        const addChildButton = $('#addChildBtn');
        const deleteChildButton = $('#deleteChildBtn');
        const deleteForm = $('#deleteForm');

        $('#categoryTree').jstree({
            core: {
                check_callback: (operation, node, parent) => {
                    if (operation === "move_node" && node.parent === parent) {
                        return true;
                    }
                    if (operation === "move_node" && node.id !== parent.id) {
                        return !$(parent).find(`#${node.id}`).length;
                    }
                },
                themes: {
                    name: "default",
                    responsive: true,
                    icons: false,
                    dots: false
                }
            },
            plugins: ["dnd", "search", "unique", "types"]
        });


        const treeSearchKeyupHandler = () => {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                $('#categoryTree').jstree(true).search($('#treeSearch').val());
            }, 450);
        }

        const expandCollapseBtnClickHandler = function() {
            const currentState = $(this).data('state');
            const newState = currentState == 'open_all' ? 'close_all' : 'open_all';
            $(this).text(newState == 'open_all' ? 'Collapse All' : 'Expand All');
            $(this).data('state', newState);
            $('#categoryTree').jstree(newState)
        }

        const deSelectAllNodeHandler = (e, data) => {
            addChildButton.attr('disabled', true);
            deleteChildButton.attr('disabled', true);
            resetForm();
        }

        const selectNodeHandler = (e, data) => {
            selectedNode = data.node;
            addChildButton.removeAttr('disabled');
            deleteChildButton.removeAttr('disabled');

            var catId = selectedNode.data.id;
            var url = "{{ route('admin.category.update', ':id') }}".replace(':id', catId);
            form.attr('action', url);

            $('.category-tree-btn').removeClass('active');

            deleteChildButton.removeClass('btn-secondary');
            deleteChildButton.addClass('btn-danger');
            deleteChildButton.attr('disabled', false);

            fetchCategoryData();
        }

        const fetchCategoryData = () => {
            if (!selectedNode) {
                return;
            }

            var catId = selectedNode.data.id;
            let url = "{{ route('admin.category.show') }}";

            $.get(`${url}?category_id=${catId}`).done((response) => {
                if (response.data.category) {
                    const data = response.data.category;

                    $('input[name="parent_id"]').val(data.parent_id);
                    $('input[name="name"]').val(data.name);
                    $('input[name="image"]').val(data.image);
                    $('#thumbnailAdd').val(data.image);
                    $('#description').val(data.description);
                    $('.holder img').attr('src', data.thumbnail);
                    $('input[name="is_active"]').prop('checked', data.is_active);
                }
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.log(jqXHR.responseText, textStatus, errorThrown);
            });
        }

        addChildButton.on('click', function() {
            addChildButton.addClass('active');
            addRootCategoryButton.removeClass('active');

            if (selectedNode) {
                $('input[name="parent_id"]').val(selectedNode.data.id);
                resetForm();
            }
        })

        $('button[type="reset"]').on('click', function() {
            addRootCategoryButton.addClass('active');
            addChildButton.removeClass('active');

            resetForm();
        });

        deleteChildButton.on('click', function() {
            if (!selectedNode) {
                return;
            }
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You won\'t be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, delete it!') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    var catId = selectedNode.data.id;
                    let url = "{{ route('admin.category.destroy', ':id') }}".replace(':id', catId);

                    deleteForm.attr('action', url);
                    deleteForm.submit();
                }
            });
        });

        const resetForm = () => {
            form.attr('action', "{{ route('admin.category.store') }}");
            $('.holder img').attr('src', '{{ asset("default/default.jpg") }}');
            $('input[name="name"]').val('');
            $('input[name="image"]').val('');
            $('#thumbnailAdd').val('');
            $('#description').val('');
            $('input[name="is_active"]').prop('checked', true);
            deleteChildButton.removeClass('btn-danger');
            deleteChildButton.addClass('btn-secondary');
            deleteChildButton.attr('disabled', true);
        }

        const moveNodeHandler = (e, data) => {
            e.preventDefault();
            const draggedNode = data.node;
            const newParent = data.parent;
            const oldParent = data.old_parent;

            // Validate new parent
            if (!newParent || draggedNode.id === newParent.id) {
                return;
            }

            // Update category model in database using AJAX
            $.post("{{ route('admin.category.menu.update') }}", {
                _token: "{{ csrf_token() }}",
                category_id: draggedNode.data.id,
                parent_id: newParent === "#" ? null : newParent,
                old_position: data.old_position,
                position: data.position,
            });
        }

        $('#categoryTree').on('deselect_all.jstree', deSelectAllNodeHandler);
        $('#categoryTree').on('move_node.jstree', moveNodeHandler);
        $('#categoryTree').on('select_node.jstree', selectNodeHandler);
        $('#treeSearch').on('keyup', treeSearchKeyupHandler);
        $('.expand-collapse-btn').on('click', expandCollapseBtnClickHandler);

        if ($('li').hasClass('active')) {
            $('.sidebar__menu-wrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
