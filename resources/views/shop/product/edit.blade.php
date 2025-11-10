@extends('layouts.app')

@section('header-title', __('Edit Product'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            {{ __('Edit Product') }}
        </div>
    </div>
    <form action="{{ route('shop.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mt-3">
            <div class="card-body">

                <div class="">
                    <x-input label="Product Name" name="name" id="product_name" type="text" placeholder="Product Name"
                        required="true" value="{{ $product->name }}" />
                </div>

                <label for="basic-url" class="form-label mt-2">Product Permalink/Slug</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">{{ config('app.url') }}</span>
                    <input type="text" name="slug" placeholder="your-product-permalink" class="form-control"
                        id="basic-url" aria-describedby="basic-addon3" value="{{ $product->slug }}">
                </div>

                @error('slug')
                    <p class="text text-danger m-0">{{ $message }}</p>
                @enderror

                <div class="mt-3">
                    <label for="">
                        {{ __('Short Description') }}
                        <span class="text-danger">*</span>
                    </label>
                    <textarea name="short_description" class="form-control" rows="2" placeholder="Short Description">{{ old('short_description') ?? $product->short_description }}</textarea>
                    @error('short_description')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="">
                        {{ __('Description') }}
                        <span class="text-danger">*</span>
                    </label>
                    @hasPermission('admin.product.generate.AI.data')
                        <button class="btn btn-sm btn-primary rounded mb-1" id="generateAi" type="button">
                            <span class="icon"></span> <strong>Generate AI</strong>
                        </button>
                    @endhasPermission
                    <div id="editor" style="max-height: 750px; overflow-y: auto">
                        {!! old('description') ?? $product->description !!}
                    </div>
                    <input type="hidden" id="description" name="description"
                        value="{{ old('description') ?? $product->description }}">
                    @error('description')
                        <p class="text text-danger m-0">{{ $message }}</p>
                    @enderror
                </div>


                <!--######## General Information ##########-->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex gap-2 border-bottom pb-2">
                            <i class="fa-solid fa-user"></i>
                            <h5>
                                {{ __('Generale Information') }}
                            </h5>
                        </div>
                        <div class="row mt-3">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6 mt-3 mt-md-0">
                                                <x-select label="Select Brand" name="brand">
                                                    <option value="">
                                                        {{ __('Select Brand') }}
                                                    </option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}"
                                                            {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <x-input type="text" name="unit" label="Unit" placeholder="Unit"
                                                    :value="$product->unit" required="true" />
                                            </div>


                                            <div class="col-md-6 col-lg-6 mt-4">
                                                <label
                                                    class="form-label d-flex align-items-center gap-2 justify-content-between">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span>
                                                            {{ __('Product SKU') }}
                                                            <span class="text-danger">*</span>
                                                        </span>
                                                        <span class="info" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            data-bs-title="{{ __('Create a unique product code. This will be used generate barcode') }}">
                                                            <i class="bi bi-info"></i>
                                                        </span>
                                                    </div>
                                                    <span class="text-primary cursor-pointer" onclick="generateCode()">
                                                        {{ __('Generate Code') }}
                                                    </span>
                                                </label>
                                                <input type="text" id="barcode" name="code" placeholder="Ex: 134543"
                                                    class="form-control" value="{{ $product->code }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                                @error('code')
                                                    <p class="text text-danger m-0">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100" style="max-height:430px;">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">{{ __('Categories') }}</h5>
                                        @error('categories')
                                            <p class="text text-danger m-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="card-body ps-0 overflow-auto">
                                        {!! $htmlTree !!}
                                    </div>
                                </div>
                                <div class="card h-100 mt-2" style="max-height:420px;">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">{{ __('Category Attribute') }}</h5>
                                    </div>
                                    <div class="card-body ps-0 overflow-auto categoryAttribute">
                                        {!! $attributeHtmlTree !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--######## Price Information ##########-->
                <div class="card mt-4 mb-4">
                    <div class="card-body">

                        <div class="d-flex gap-2 border-bottom pb-2">
                            <i class="fa-solid fa-user"></i>
                            <h5>
                                {{ __('Price Information') }}
                            </h5>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-3 col-md-6">
                                <x-input type="text" name="buy_price" label="Buying Price" placeholder="Buying Price"
                                    required="true" onlyNumber="true" :value="$product->buy_price" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <x-input type="text" name="price" label="Selling Price" placeholder="Selling Price"
                                    required="true" onlyNumber="true" :value="$product->price" />
                            </div>

                            <div class="col-lg-3 col-md-6 mt-3 mt-md-0">
                                <x-input type="text" name="discount_price" label="Discount Price"
                                    placeholder="Discount Price" onlyNumber="true" :value="$product->discount_price" />
                            </div>

                            <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
                                <x-input type="text" name="quantity" label="Current Stock Quantity"
                                    placeholder="Current Stock Quantity" onlyNumber="true" :value="$product->quantity" />
                            </div>

                            <div class="col-lg-3 col-md-6 mt-3">
                                <x-input type="text" onlyNumber="true" name="min_order_quantity"
                                    label="Minimum Order Quantity" placeholder="Minimum Order Quantity"
                                    :value="$product->min_order_quantity" />
                            </div>
                        </div>
                    </div>
                </div>

                <!--######## Thumbnail Information ##########-->
                <div class="row mb-3">
                    <div class="col-md-5 col-xl-3">
                        <div class="card card-body h-100" style="display:inline-block">
                            <div class="mb-2">
                                <h5>
                                    {{ __('Thumbnail') }}
                                    <span class="text-primary">(Ratio 1280 x 960 px)</span> *
                                </h5>
                                @error('thumbnail')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <x-image-picker name="thumbnail" :value="$product->product_thumbnail" />

                        </div>
                    </div>

                    <div class="col-md-7 col-xl-9 mt-3 mt-md-0">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="mb-2">
                                    <h5>
                                        {{ __('Additional Thumbnail') }}
                                        <span class="text-primary">(Ratio 1280 x 960 px)</span> *
                                    </h5>
                                    @error('additionThumbnail')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="d-flex flex-wrap gap-2" id="additionalElements">

                                    @foreach ($product->medias as $media)
                                        <div class="thumbnail-wrapper d-flex flex-column align-items-start gap-1">
                                            <button type="button"
                                                class="delete btn btn-sm btn-outline-danger circleIcon m-1">
                                                <img src="{{ asset('assets/icons-admin/trash.svg') }}" loading="lazy"
                                                    alt="trash" />
                                            </button>
                                            <x-image-picker name="additionThumbnail[]" :value="$media->additional_thumbnail" />
                                        </div>
                                    @endforeach

                                    <div class="thumbnail-wrapper d-flex flex-column align-items-start gap-1">
                                        <button type="button" class="delete btn btn-sm btn-outline-danger circleIcon m-1"
                                            style="display: none">
                                            <img src="{{ asset('assets/icons-admin/trash.svg') }}" loading="lazy"
                                                alt="trash" />
                                        </button>
                                        <x-image-picker name="additionThumbnail[]" />
                                    </div>

                                </div>

                                <template id="imagePickerTemplate">
                                    <div class="thumbnail-wrapper d-flex flex-column align-items-start gap-1">
                                        <button type="button" class="delete btn btn-sm btn-outline-danger circleIcon m-1"
                                            style="display:none">
                                            <img src="data:image/svg+xml;charset=utf-8,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%0A%3Cpath%20d%3D%22M20%205.25H15.786C15.693%205.068%2015.621%204.862%2015.544%204.632L15.342%204.02499C15.138%203.41299%2014.565%203%2013.919%203H10.081C9.43499%203%208.862%203.41299%208.658%204.02499L8.45599%204.632C8.37899%204.862%208.307%205.068%208.214%205.25H4C3.586%205.25%203.25%205.586%203.25%206C3.25%206.414%203.586%206.75%204%206.75H20C20.414%206.75%2020.75%206.414%2020.75%206C20.75%205.586%2020.414%205.25%2020%205.25Z%22%20fill%3D%22%23ef4444%22%2F%3E%0A%3Cpath%20d%3D%22M14%2016.75C13.586%2016.75%2013.25%2016.414%2013.25%2016V11C13.25%2010.586%2013.586%2010.25%2014%2010.25C14.414%2010.25%2014.75%2010.586%2014.75%2011V16C14.75%2016.414%2014.414%2016.75%2014%2016.75Z%22%20fill%3D%22%23ef4444%22%2F%3E%0A%3Cpath%20d%3D%22M10%2016.75C9.586%2016.75%209.25%2016.414%209.25%2016V11C9.25%2010.586%209.586%2010.25%2010%2010.25C10.414%2010.25%2010.75%2010.586%2010.75%2011V16C10.75%2016.414%2010.414%2016.75%2010%2016.75Z%22%20fill%3D%22%23ef4444%22%2F%3E%0A%3Cpath%20opacity%3D%220.4%22%20d%3D%22M18.95%206.75L18.19%2018.2C18.08%2019.78%2017.25%2021%2015.19%2021H8.81004C6.75004%2021%205.92004%2019.78%205.81004%2018.2L5.05005%206.75H18.95Z%22%20fill%3D%22%23ef4444%22%2F%3E%0A%3C%2Fsvg%3E%0A"
                                                loading="lazy" alt="trash" />
                                        </button>
                                        <x-image-picker name="additionThumbnail[]" />
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!--######## Product Video ##########-->
                <div class="card mt-4">
                    <div class="card-body">

                        <div class="d-flex gap-2 border-bottom pb-2">
                            <i class="fa-solid fa-play"></i>
                            <h5>
                                {{ __('Upload or Add Product Video') }}
                            </h5>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <!-- Select Upload Type -->
                            <div class="mb-3">
                                <label for="uploadType" class="form-label">
                                    {{ __('Select Video Type') }}
                                </label>
                                <select class="form-select" name="uploadVideo[type]" id="uploadType"
                                    onchange="toggleFields()">
                                    <option value="file" {{ $product->video?->type == 'file' ? 'selected' : '' }}>
                                        {{ __('Upload Video File') }}
                                    </option>
                                    <option value="youtube" {{ $product->video?->type == 'youtube' ? 'selected' : '' }}>
                                        {{ __('YouTube Link') }}
                                    </option>
                                    <option value="vimeo" {{ $product->video?->type == 'vimeo' ? 'selected' : '' }}>
                                        {{ __('Vimeo Link') }}
                                    </option>
                                    <option value="dailymotion"
                                        {{ $product->video?->type == 'dailymotion' ? 'selected' : '' }}>
                                        {{ __('Dailymotion Link') }}
                                    </option>
                                </select>
                            </div>

                            <!-- Upload File Section -->
                            <div class="mb-3 flex-grow-1" id="fileUploadField">
                                <label for="productVideo" class="form-label">
                                    {{ __('Upload Product Video') }}
                                </label>
                                <input type="file" class="form-control" name="uploadVideo[file]" id="productVideo"
                                    accept="video/*">
                                <small class="text-muted">
                                    {{ __('Supported formats: MP4, AVI, MOV, WMV') }}
                                </small>
                            </div>

                            <!-- YouTube Link Section -->
                            <div class="mb-3 d-none flex-grow-1" id="youtubeField">
                                <label for="youtubeLink" class="form-label">
                                    {{ __('YouTube Video Link') }}
                                </label>
                                <textarea class="form-control" name="uploadVideo[youtube_url]" id="youtubeLink" rows="3"
                                    placeholder='<iframe width="560" height="315" src="https://www.youtube.com/embed/MxcgrT_Kdxw?si=V63-aJ-4tPZUEKyk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'>{{ $product->video?->type == 'youtube' ? $product->video->url : '' }}</textarea>
                                <small class="text-muted">{{ __('Paste a valid YouTube video embed code') }}</small>
                            </div>

                            <!-- Vimeo Link Section -->
                            <div class="mb-3 d-none flex-grow-1" id="vimeoField">
                                <label for="vimeoLink" class="form-label">
                                    {{ __('Vimeo Video Link') }}
                                </label>
                                <textarea name="uploadVideo[vimeo_url]" id="vimeoLink" class="form-control" rows="3">{{ $product->video?->type == 'vimeo' ? $product->video->url : '' }}</textarea>
                                <small class="text-muted">{{ __('Paste a valid Vimeo video embed code') }}</small>
                            </div>

                            <!-- Dailymotion Link Section -->
                            <div class="mb-3 d-none flex-grow-1" id="dailymotionField">
                                <label for="dailymotionLink" class="form-label">
                                    {{ __('Dailymotion Video Link') }}
                                </label>
                                <textarea name="uploadVideo[dailymotion_url]" id="dailymotionLink" class="form-control" rows="3">{{ $product->video?->type == 'dailymotion' ? $product->video->url : '' }}</textarea>
                                <small class="text-muted">{{ __('Paste a valid Dailymotion video embed code') }}</small>
                            </div>
                        </div>
                        @error('uploadVideo.file')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!--######## SEO section ##########-->
                <div class="card mt-4 mb-3">
                    <div class="card-body">

                        <div class="d-flex gap-2 border-bottom pb-2">
                            <i class="fa-solid fa-square-poll-vertical"></i>
                            <h5>
                                {{ __('SEO Information') }}
                            </h5>
                        </div>
                        <div class="mt-3">
                            <label for="uploadType" class="form-label">
                                {{ __('Meta Title') }}
                            </label>
                            <x-input name="meta_title" type="text" placeholder="Meta Title"
                                value="{{ old('meta_title', $product->meta_title) }}" />
                        </div>

                        <div class="mt-3">
                            <label for="uploadType" class="form-label">
                                {{ __('Meta Description') }}
                            </label>
                            <textarea name="meta_description" type="text" placeholder="{{ __('Meta Description') }}" class="form-control">{{ old('meta_description', $product->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="tags" class="form-label">@lang('Meta Keywords')</label>
                            <select id="tags" name="meta_keywords[]" class="form-control selectTags" multiple
                                style="width: 100%">
                                @foreach (old('meta_keywords', $metaKeywords) as $keyword)
                                    <option value="{{ $keyword }}" selected>{{ $keyword }}</option>
                                @endforeach
                            </select>
                            <small>@lang('Write keywords and Press enter to add new one')</small>
                            @error('meta_keywords')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 justify-content-end align-items-center my-3">
            <button type="reset" class="btn btn-lg btn-outline-secondary rounded py-2">
                {{ __('Reset') }}
            </button>
            <button type="submit" class="btn btn-lg btn-primary rounded py-2 px-5">
                {{ __('Update') }}
            </button>
        </div>

    </form>
@endsection
@push('css')
    <style>
        .category-tree {
            list-style: none;
            font-size: 16px;
            color: #5b6e88;
        }

        .category-tree li {
            line-height: 1.6;
        }

        .category-tree li input {
            margin-right: 5px;
        }

        .categoryAttribute-tree {
            list-style: none;
            font-size: 16px;
            color: #5b6e88;
        }

        .categoryAttribute-tree li {
            line-height: 1.6;
        }

        .categoryAttribute-tree li input {
            margin-right: 5px;
        }

        .circleIcon {
            position: absolute;
        }

        .thumbnail-wrapper {
            border: 2px dashed var(--theme-color);
        }
    </style>
@endpush
@push('scripts')
    <script>
        function toggleFields() {
            // Hide all fields
            document.getElementById('fileUploadField').classList.add('d-none');
            document.getElementById('youtubeField').classList.add('d-none');
            document.getElementById('vimeoField').classList.add('d-none');
            document.getElementById('dailymotionField').classList.add('d-none');

            // Get selected type
            const selectedType = document.getElementById('uploadType').value;

            // Show relevant field
            if (selectedType === 'file') {
                document.getElementById('fileUploadField').classList.remove('d-none');
            } else if (selectedType === 'youtube') {
                document.getElementById('youtubeField').classList.remove('d-none');
            } else if (selectedType === 'vimeo') {
                document.getElementById('vimeoField').classList.remove('d-none');
            } else if (selectedType === 'dailymotion') {
                document.getElementById('dailymotionField').classList.remove('d-none');
            }
        }

        $(document).ready(function() {
            toggleFields();

            var productName = $('input[name="name"]').val();

            var replace = productName.replace(/&amp;/g, '&').replace(/&#039;/g, "'").replace(/&quot;/g, '"')
                .replace(/&lt;/g, '<').replace(/&gt;/g, '>');
            $('input[name="name"]').val(replace);


            $(".selectTags").select2({
                tags: true,
                placeholder: "{{ __('Write keywords and Press enter to add new one') }}"
            });

            $('#price').on('input', function() {
                var productPrice = $(this).val() ?? 0;
                var productDiscountPrice = $('#discount_price').val() ?? 0;
                var mainPrice = productDiscountPrice > 0 ? productDiscountPrice : productPrice;
                $('.mainProductPrice').text(mainPrice);
            });

            $('#discount_price').on('input', function() {
                var productPrice = $('#price').val() ?? 0;
                var productDiscountPrice = $(this).val() ?? 0;
                var mainPrice = productDiscountPrice > 0 ? productDiscountPrice : productPrice;
                $('.mainProductPrice').text(mainPrice);
            });



            // form submit loader
            $('form').on('submit', function() {
                var submitButton = $(this).find('button[type="submit"]');

                submitButton.prop('disabled', true);
                submitButton.removeClass('px-5');

                submitButton.html(`<div class="d-flex align-items-center gap-1">
                    <div class="spinner-border" role="status"></div>
                    <span>Updating...</span>
                </div>`)
            });
        });

        const generateCode = () => {
            const code = document.getElementById('barcode');
            code.value = Math.floor(Math.random() * 900000) + 100000;
        }
    </script>

    <!-- additional thumbnail script -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const additionalContainer = document.getElementById("additionalElements");
            const pickerTemplate = document.getElementById("imagePickerTemplate");

            function initializeImagePicker(wrapper) {
                if (!wrapper) return;

                const removeBtn = wrapper.querySelector(".delete");
                const thumbnailPathInput = wrapper.querySelector(".thumbnailPath");
                const imageHolder = wrapper.querySelector(".mainThumbnail img");

                removeBtn?.addEventListener("click", () => removeThumbnail(wrapper));

                new MutationObserver(() => {
                    const newValue = thumbnailPathInput.value;
                    removeBtn.style.display = newValue ? "block" : "none";

                    const allWrappers = [...additionalContainer.querySelectorAll(".thumbnail-wrapper")];
                    if (newValue && allWrappers.at(-1) === wrapper) addThumbnail();
                }).observe(thumbnailPathInput, {
                    attributes: true,
                    attributeFilter: ["value"]
                });

                wrapper.setUrlCallback = function(items) {
                    if (!items?.length) return;

                    const imageUrl = items[0].url;
                    thumbnailPathInput.value = imageUrl.replace(window.location.origin + "/storage/", "");
                    imageHolder.src = imageUrl;

                    thumbnailPathInput.dispatchEvent(new Event("input", {
                        bubbles: true
                    }));
                };

                initializeLfmForElement(wrapper);
            }

            function initializeLfmForElement(container) {
                container.querySelectorAll(".lfm").forEach(button => {
                    button.addEventListener("click", e => {
                        e.preventDefault();
                        const iframe = document.getElementById("lfmIframe");
                        iframe.dataset.containerId = button.dataset.containerId;
                        iframe.src = `${route_prefix}?type=file&callback=SetUrl`;
                        $("#lfmModal").modal("show");
                    });
                });
            }

            function addThumbnail() {
                if (!pickerTemplate) return;

                const clone = pickerTemplate.content.cloneNode(true);
                const newWrapper = clone.querySelector(".thumbnail-wrapper");
                const container = newWrapper.querySelector(".image-container");

                container.dataset.containerId = `picker-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

                additionalContainer.appendChild(clone);

                initializeImagePicker(additionalContainer.lastElementChild);
            }

            function removeThumbnail(wrapper) {
                const allWrappers = additionalContainer.querySelectorAll(".thumbnail-wrapper");
                if (allWrappers.length > 1) {
                    wrapper.remove();
                } else {
                    const img = wrapper.querySelector(".mainThumbnail img");
                    const input = wrapper.querySelector(".thumbnailPath");
                    img.src = input.dataset.defaultImage;
                    input.value = "";
                }
            }

            additionalContainer.querySelectorAll(".thumbnail-wrapper").forEach(initializeImagePicker);

            window.SetUrl = function(items) {
                const iframe = document.getElementById("lfmIframe");
                if (!iframe?.dataset.containerId) return;

                const container = document.querySelector(
                    `.image-container[data-container-id="${iframe.dataset.containerId}"]`);
                const wrapper = container?.closest(".thumbnail-wrapper");
                wrapper?.setUrlCallback?.(items);
            };
        });
    </script>


    <!-- color select2 script -->
    <script>
        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(
                '<span class="d-flex align-items-center"> <span style="background-color:' + state.element.dataset
                .color +
                ';width:20px;height:20px;display:inline-block; border-radius:5px;margin-right:5px;"></span>' + state
                .text + '</span>'
            );
            return $state;
        };

        $(document).ready(function() {
            $('.colorSelect').select2({
                templateResult: formatState
            });
        });
    </script>

    <script>
        correctULTagFromQuill = (str) => {
            if (str) {
                let re = /(<ol><li data-list="bullet">)(.*?)(<\/ol>)/;
                let strArr = str.split(re);

                while (
                    strArr.findIndex((ele) => ele === '<ol><li data-list="bullet">') !== -1
                ) {
                    let index = strArr.findIndex(
                        (ele) => ele === '<ol><li data-list="bullet">'
                    );
                    if (index) {
                        strArr[index] = '<ul><li data-list="bullet">';
                        let endTagIndex = strArr.findIndex((ele) => ele === "</ol>");
                        strArr[endTagIndex] = "</ul>";
                    }
                }
                return strArr.join("");
            }
            return str;
        };

        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    [{
                        'font': []
                    }],
                    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['link', 'image', 'video', 'formula']
                ]
            }
        });

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('description').value = correctULTagFromQuill(quill.root.innerHTML);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.category-checkbox');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        let parentLi = this.closest('li').parentElement.closest('li');
                        while (parentLi) {
                            let parentCheckbox = parentLi.querySelector('.category-checkbox');
                            if (parentCheckbox && !parentCheckbox.checked) {
                                parentCheckbox.checked = true;

                                $(parentCheckbox).trigger('change');
                            }
                            parentLi = parentLi.parentElement.closest('li');
                        }
                    } else {
                        const childCheckboxes = this.closest('li').querySelectorAll(
                            'ul .category-checkbox');
                        childCheckboxes.forEach(child => {
                            if (child.checked) {
                                child.checked = false;
                                $(child).trigger('change');
                            }
                        });

                        let parentLi = this.closest('li').parentElement.closest('li');
                        while (parentLi) {
                            let parentCheckbox = parentLi.querySelector('.category-checkbox');
                            if (parentCheckbox) {
                                const siblingsChecked = parentLi.querySelectorAll(
                                    'ul .category-checkbox:checked');
                                if (siblingsChecked.length > 0) {
                                    parentCheckbox.checked = true;
                                    $(parentCheckbox).trigger('change');
                                } else {
                                    parentCheckbox.checked = false;
                                    $(parentCheckbox).trigger('change');
                                }
                            }
                            parentLi = parentLi.parentElement.closest('li');
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input.category-checkbox').on('change', function() {
                handleCategoryCheckbox($(this));
            });

            $('input.category-checkbox:checked').each(function() {
                handleCategoryCheckbox($(this));
            });

            function handleCategoryCheckbox($checkbox) {
                var categoryId = $checkbox.val();
                var $attributeCategory = $('.attributeCategory-' + categoryId);
                var $tree = $('.categoryAttribute-tree-' + categoryId);

                if ($checkbox.is(':checked')) {
                    $attributeCategory.removeClass('d-none');
                    $tree.removeClass('d-none');
                    $tree.find('.attributeBox').removeAttr('disabled');
                    $tree.closest('li').find('.attributeCategoryBox').prop('checked', true);
                } else {
                    $attributeCategory.addClass('d-none');
                    $tree.addClass('d-none');
                    $tree.find('.attributeBox').prop('checked', false).attr('disabled', true);
                    $tree.closest('li').find('.attributeCategoryBox').prop('checked', false).attr('disabled', true);
                }
            }
        });
    </script>
    <script>
        $(document).on('click', '#generateAi', function() {
            var name = $('#product_name').val();
            var short_description = $('#short_description').val();
            $('#description').val("Generating description・・・・・ Please wait ⏳");
            quill.clipboard.dangerouslyPasteHTML("<p><em>Generating description・・・・・ Please wait ⏳</em></p>");
            $.ajax({
                url: "{{ route('shop.product.generate.AI.data') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    short_description: short_description
                },
                success: function(response) {
                    $('#description').val("");
                    quill.setText("");
                    console.log(response);

                    let lastResponse = "";
                    let fullText = response;
                    let index = 0;

                    function typeStep() {
                        if (index >= fullText.length) return;
                        lastResponse += fullText[index++];
                        $('#description').val(lastResponse);
                        quill.clipboard.dangerouslyPasteHTML(lastResponse);
                        quill.setSelection(quill.getLength(), 0);
                        setTimeout(typeStep, 10); // 10ms delay per character
                    }

                    typeStep();
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        let firstError = Object.values(error.responseJSON.errors)[0][0];
                        toastr.error(firstError);
                    } else if (error.responseJSON && error.responseJSON.message) {
                        toastr.error(error.responseJSON.message);
                    } else {
                        toastr.error("Something went wrong");
                    }
                    $('#description').val("");
                    quill.setText("");
                }
            })
        });
    </script>
@endpush
