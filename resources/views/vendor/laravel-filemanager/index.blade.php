<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#333844">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#333844">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#333844">

    <title>{{ trans('laravel-filemanager::lfm.title-page') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('vendor/laravel-filemanager/img/72px color.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/mime-icons.min.css') }}">
    {{-- Use the line below instead of the above if you need to cache the css. --}}
    <link rel="stylesheet" href="{{ asset('/vendor/laravel-filemanager/css/lfm.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


</head>

<body>
    <div class="d-flex align-items-center flex-wrap gx-2 justify-content-between px-3 mb-2">
        <div>
            <h4>
                {{ __('Gallery Import') }}
            </h4>
        </div>


        <div class="d-flex flex-wrap  justify-content-between sm-gap">
            <a class="btn  btn-outline-primary mr-2" id="add-folder" data-label="New Folder">
                {{ __('Create New Folder') }} <i class="fa fa-folder-plus"></i>
            </a>
            <a class="btn btn-primary" id="upload" data-label="upload">
                {{ __('Upload Image') }} <i class="fa fa-image"></i>
            </a>
        </div>
    </div>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark" id="nav">
        <a class=" d-none d-lg-inline" id="to-previous">
            <i class="fas fa-arrow-left fa-fw"></i>
        </a>
        <nav id="breadcrumbs">
            <ol class="breadcrumb">
                <li class="breadcrumb-item invisible">Home</li>
            </ol>
        </nav>
        <a class="navbar-brand d-block d-lg-none" id="current_folder"></a>
        <a id="loading" class="navbar-brand"><i class="fas fa-spinner fa-spin"></i></a>
        <div class="ml-auto px-2">
            <a class="navbar-link " id="multi_selection_toggle">
                <i class="bi bi-circle"></i>
                <span class="d-none d-lg-inline">{{ trans('laravel-filemanager::lfm.menu-multiple') }}</span>
            </a>
        </div>
        <a class="navbar-toggler collapsed border-0 px-1 py-2 m-0" data-toggle="collapse" data-target="#nav-buttons">
            <i class="fas fa-cog fa-fw"></i>
        </a>
        <div class="collapse navbar-collapse flex-grow-0" id="nav-buttons">
            <ul class="navbar-nav">
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle sortBtn" data-toggle="dropdown" role="button"
                        aria-haspopup="true" aria-expanded="false">
                        Sort <img src="{{ asset('vendor/laravel-filemanager/img/sort-vertical.svg') }}" alt=""
                            class="ml-2">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right border-0"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-display="list">
                        <img src="{{ asset('vendor/laravel-filemanager/img/list.svg') }}" alt="">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active-display" data-display="grid">
                        <img src="{{ asset('vendor/laravel-filemanager/img/grid-square.svg') }}" alt="">
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    @if (request()->get('from') !== 'gallery')
        <nav class="bg-light fixed-bottom border-top d-none actionBoxGallery" id="actions">
            <a  data-action="use" class=" px-2 px-md-5" style="width: auto !important;" data-multiple="true">
                <i class="fas fa-check"></i>
                {{ __('Confirm')}}
            </a>
        </nav>
    @endif

    <div class=""
        style="background: #ffffff">

    <div id="main">
        <div id="alerts"></div>

        <div id="empty" class="d-none">
            <i class="far fa-folder-open"></i>
            {{ trans('laravel-filemanager::lfm.message-empty') }}
        </div>


        {{-- start --}}


        <div id="content"></div>
        <div class="d-flex justify-content-between mt-3 paginationBox">
            <div id="total-items"></div>
            <div id="pagination"></div>
        </div>



        <!-- GRID TEMPLATE (your card design) -->

        <a id="item-template-grid" class="d-none">
            <div class="row">
                <div class="col-6 col-sm-6 col-md-4 col-lg-2 mb-4 gridCart">
                    <div class="card" style="background: #F6F7F9; box-shadow: 0px 2px 4px 0px #0000003D;">
                        <div class="dropdown-container">
                            <div class="dropdown ms-auto griddrop">
                                <button class="gridDotBtn" type="button" data-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu action-menu">
                                </ul>
                            </div>
                        </div>

                        <div class="square">
                            <div class="image-container  d-none">
                                <img src="" class="card-img-top" alt="">
                            </div>
                            <div class="folder-icon-container  d-none">
                                <img src="{{ asset('vendor/laravel-filemanager/img/folder.svg') }}" alt="">
                            </div>
                        </div>


                        <div class="card-body pt-0 text-center">
                            <hr class="m-0 p-0">
                            <div class="info">
                                <div class="item_name text-truncate"></div>
                                <div class="item_meta text-muted font-weight-light text-truncate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </a>

        <!-- LIST TEMPLATE (old layout) -->
        <a id="item-template-list" class="d-none">

            <div class="square"></div>
            <div class="info">
                <div class="item_name text-truncate"></div>
                <div class="item_meta text-muted font-weight-light text-truncate"></div>
                <time class="text-muted font-weight-light text-truncate"></time>
            </div>

            <div class="dropdown ml-auto listdrop">
                <button class="listDotBtn" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu action-menu">
                </ul>
            </div>

        </a>

        {{-- start --}}

    </div>

    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ trans('laravel-filemanager::lfm.title-upload') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aia-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('unisharp.lfm.upload') }}" role='form' id='uploadForm' name='uploadForm'
                        method='post' enctype='multipart/form-data' class="dropzone">
                        <div class="form-group" id="attachment">
                            <div class="controls text-center">
                                <div class="input-group w-100">
                                    <a class="btn btn-primary w-100 text-white" id="upload-button">Choose File <i
                                            class="fa fa-upload"></i></a>
                                </div>
                            </div>
                        </div>
                        <input type='hidden' name='working_dir' id='working_dir'>
                        <input type='hidden' name='type' id='type' value='{{ request('type') }}'>
                        <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notify" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary w-100" data-dismiss="modal"
                        id="confirm-button-yes">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div id="carouselTemplate" class="d-none carousel slide bg-light" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#previewCarousel" data-slide-to="0" class="active"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <a class="carousel-label"></a>
                <div class="carousel-image"></div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#previewCarousel" role="button" data-slide="prev">
            <div class="carousel-control-background" aria-hidden="true">
                <i class="fas fa-chevron-left"></i>
            </div>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#previewCarousel" role="button" data-slide="next">
            <div class="carousel-control-background" aria-hidden="true">
                <i class="fas fa-chevron-right"></i>
            </div>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/cropper.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/dropzone.min.js') }}"></script>
    <script>
        var lang = {!! json_encode(trans('laravel-filemanager::lfm')) !!};
        var actions = [
            //   {
            //     name: 'use',
            //     icon: 'check',
            //     label: 'Confirm',
            //     multiple: true
            //   },
            {
                name: 'rename',
                icon: 'edit',
                label: lang['menu-rename'],
                multiple: false
            },
            {
                name: 'download',
                icon: 'download',
                label: lang['menu-download'],
                multiple: false
            },
            {
                name: 'preview',
                icon: 'image',
                label: lang['menu-view'],
                multiple: true
            },
            //   {
            //     name: 'move',
            //     icon: 'paste',
            //     label: lang['menu-move'],
            //     multiple: true
            //   },
            {
                name: 'resize',
                icon: 'arrows-alt',
                label: lang['menu-resize'],
                multiple: false
            },
            {
                name: 'crop',
                icon: 'crop',
                label: lang['menu-crop'],
                multiple: false
            },
            {
                name: 'trash',
                icon: 'trash',
                label: lang['menu-delete'],
                multiple: true
            },
        ];

        var sortings = [{
                by: 'alphabetic',
                icon: 'sort-alpha-down',
                label: lang['nav-sort-alphabetic']
            },
            {
                by: 'time',
                icon: 'sort-numeric-down',
                label: lang['nav-sort-time']
            }
        ];
    </script>
    <script src="{{ asset('vendor/laravel-filemanager/js/script.js') }}"></script>


    <script>
        var myDropzone;

        Dropzone.options.uploadForm = {
            paramName: "upload[]",
            uploadMultiple: false,
            parallelUploads: 5,
            timeout: 0,
            clickable: '#upload-button',
            dictDefaultMessage: lang['message-drop'],
            init: function() {
                var _this = this;
                myDropzone = _this;

                this.on('success', function(file, response) {
                    if (response) {
                        loadFolders();
                    } else {
                        this.defaultOptions.error(file, response.join('\n'));
                    }
                });
            },
            headers: {
                'Authorization': 'Bearer ' + getUrlParam('token')
            },
            acceptedFiles: "{{ implode(',', $helper->availableMimeTypes()) }}",
            maxFilesize: ({{ $helper->maxUploadSize() }} / 1000)
        };
        $('#uploadModal').on('hidden.bs.modal', function() {
            if (myDropzone) {
                myDropzone.removeAllFiles();
            }
        });
    </script>
    </body>

</html>
