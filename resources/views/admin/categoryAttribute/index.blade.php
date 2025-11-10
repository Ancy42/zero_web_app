@extends('layouts.app')

@section('header-title', __('Attributes'))

@section('content')
    <div class="container-fluid mt-3 mb-3">

        <div class="bodywrapper__inner">

            <div class="d-flex mb-3 flex-wrap gap-3 justify-content-between align-items-center">
                <h6 class="page-title">
                    {{ __('All Attributes') }}
                </h6>
            </div>

            <div class="row justify-content-center gy-4">
                <div class="col-lg-6 col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="d-flex  flex-wrap gap-2 mb-3">
                                @hasPermission('admin.categoryAttribute.create')
                                    <button type="button" class="category-tree-btn flex-grow-1" id="addChildBtn" disabled>
                                        <i class="fa fa-plus"></i> {{ __('Add Child') }}
                                    </button>
                                @endhasPermission

                                @hasPermission('admin.categoryAttribute.destroy')
                                    <button type="button" class="btn btn-sm btn-secondary flex-grow-1" id="deleteChildBtn"
                                        disabled>
                                        <i class="fa fa-trash"></i>
                                        {{ __('Delete Selected') }}
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

                <div class=" col-lg-6 col-12">
                    <div class="card right-sticky">
                        <div class="card-body">
                            @hasPermission(['admin.categoryAttribute.create', 'admin.categoryAttribute.update'])
                                <form action="{{ route('admin.categoryAttribute.store') }}" method="POST"
                                    enctype="multipart/form-data" id="addForm">
                                    @csrf
                                @endhasPermission
                                <input type="hidden" name="parent_id" value="">
                                <div class="form-group row mt-3">
                                    <div class="col-xxl-2 col-xl-3">
                                        <label for="Category" class="required">
                                            {{ __('Category') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-xxl-10 col-xl-9">
                                        <x-input name="category_name" type="text" placeholder="Enter Name"
                                            required="true" />
                                        <x-input name="category_id" type="hidden" />
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-xxl-2 col-xl-3">
                                        <label for="name" class="required">
                                            {{ __('Name') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-xxl-10 col-xl-9">
                                        <x-input name="name" type="text" placeholder="Enter Name" required="true" />
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-xxl-2 col-xl-3">
                                        <label for="is_active">
                                            {{ __('Is Active') }}
                                        </label>
                                    </div>
                                    <div class="col-xxl-10 col-xl-9">
                                        <label class="switch mb-0">
                                            <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                @hasPermission(['admin.categoryAttribute.create', 'admin.categoryAttribute.update'])
                                    <div class="d-flex gap-1 mt-4">
                                        <button type="submit" class="btn btn-primary py-2.5 flex-grow-1" id="submitButton">
                                            {{ __('Submit') }}
                                        </button>
                                        <button type="reset" class="btn btn-dark addRootCategory" id="resetButton"
                                            title="Reset">
                                            <i class="fa fa-undo"></i>
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
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jsTree.css') }}">
@endpush
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

        // $('#categoryTree').jstree({
        //     core: {
        //         check_callback: (operation, node, parent) => {
        //             if (operation === "move_node" && node.parent === parent) {
        //                 return true;
        //             }

        //             if (operation === "move_node" && node.id !== parent.id) {
        //                 return !$(parent).find(`#${node.id}`).length;
        //             }
        //         }
        //     },
        //     plugins: ["dnd", "search", "unique", "types"]
        // });

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


            $('.category-tree-btn').removeClass('active');

            deleteChildButton.removeClass('btn-secondary');
            deleteChildButton.addClass('btn-danger');
            deleteChildButton.attr('disabled', false);
            const nodeType = selectedNode.data.type;
            if (nodeType === 'category') {
                var url = "{{ route('admin.categoryAttribute.store') }}";
                form.attr('action', url);
                fetchCategoryData();
                addChildButton.attr('disabled', true);
                deleteChildButton.attr('disabled', true);
            } else if (nodeType === 'attribute') {
                var url = "{{ route('admin.categoryAttribute.update', ':id') }}".replace(':id', catId);
                form.attr('action', url);
                fetchAttributeData();
                addChildButton.removeAttr('disabled');
                deleteChildButton.removeAttr('disabled');
            }
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
                    $('input[name="category_name"]').val(data.name);
                    $('input[name="category_id"]').val(data.id);
                }
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.log(jqXHR.responseText, textStatus, errorThrown);
            });
        }
        const fetchAttributeData = () => {
            if (!selectedNode) {
                return;
            }

            var attributeId = selectedNode.data.id;

            let url = "{{ route('admin.categoryAttribute.show') }}";

            $.get(`${url}?attribute_id=${attributeId}`).done((response) => {
                if (response.data.attribute) {
                    const data = response.data.attribute;
                    const category_name = data.category.name ? data.category.name : (data.attributeGet?.category
                        ?.name ?? '');
                    $('input[name="name"]').val(data.name);
                    $('input[name="is_active"]').prop('checked', data.is_active);
                    $('input[name="category_name"]').val(category_name);
                    $('input[name="category_id"]').val('');
                }
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.log(jqXHR.responseText, textStatus, errorThrown);
            });
        };


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
                    let url = "{{ route('admin.categoryAttribute.destroy', ':id') }}".replace(':id',
                    catId);

                    deleteForm.attr('action', url);
                    deleteForm.submit();
                }
            });
        });

        const resetForm = () => {
            form.attr('action', "{{ route('admin.categoryAttribute.store') }}");
            $('input[name="name"]').val('');
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
