@extends('layouts.app')

@section('title', __('Admin Settings'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center ms-2">
           <i class="bi bi-gear"></i>
            {{ __('Ai Prompt') }}
        </div>
    </div>

    <div class="row mb-5 backImg">
        <div class="col-md-6 col-12">
            <div class="card mt-3 cardBox">

                <div class="card-header d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-journal-bookmark-fill"></i>

                    <h5 class="mb-0">{{ __('Product Description Note') }}</h5>
                </div>

                <form action="{{ route('admin.aiPrompt.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div>
                            <strong>Note: </strong>Use <strong style="color: var(--theme-color) !important">
                                <i>{product_name}</i></strong> to insert the product’s name, and <strong
                                style="color: var(--theme-color) !important"><i>{short_description}</i></strong> to insert
                            the product’s short description in the prompt.

                        </div>
                        <label for="" class="mb-1 mt-3">
                            {{ __('Product Description') }} <span class="text-danger">*</span>
                        </label>
                        <textarea name="product_description" id="product_description" class="form-control" rows="4" required
                            placeholder="Enter Product Description">{{ $generaleSetting?->product_description }}</textarea>

                        @error('product_description')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror

                        <input name="page_description" type="hidden" value="{{ $generaleSetting?->page_description }}">
                        <input name="blog_description" type="hidden" value="{{ $generaleSetting?->blog_description }}">

                    </div>
                    @hasPermission('admin.aiPrompt.update')
                        <div class="d-flex justify-content-end mt-4 mb-3 me-3">
                            <button type="submit" id="saveBtn1" class="btn btn-primary py-2.5 px-3">
                                {{ __('Save And Update') }}
                            </button>
                        </div>
                    @endhasPermission

                </form>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card mt-3 cardBox">
                <div class="card-header d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <h5 class="mb-0">{{ __('Page Description Note') }}</h5>
                </div>
                <form action="{{ route('admin.aiPrompt.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="mb-3">
                            <strong>Note: </strong>If you use <strong style="color: var(--theme-color) !important">
                                <i>{title}</i></strong>, the page title will be automatically retrieved and inserted into
                            the main prompt.
                        </div>

                        <input name="product_description" type="hidden"
                            value="{{ $generaleSetting?->product_description }}">
                        <input name="blog_description" type="hidden" value="{{ $generaleSetting?->blog_description }}">
                        <label for="" class="mb-1">
                            {{ __('Page  Description') }} <span class="text-danger">*</span>
                        </label>
                        <textarea name="page_description" id="page_description" class="form-control" rows="4" required
                            placeholder="Enter Page Description">{{ $generaleSetting?->page_description }}</textarea>
                        @error('page_description')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror

                    </div>
                    @hasPermission('admin.aiPrompt.update')
                        <div class="d-flex justify-content-end mt-4 mb-3 me-3">
                            <button type="submit" id="saveBtn2" class="btn btn-primary py-2.5 px-3">
                                {{ __('Save And Update') }}
                            </button>
                        </div>
                    @endhasPermission

                </form>
            </div>
        </div>
        <div class="col-md-6 col-12 ">
            <div class="card mt-3 cardBox">
                <div class="card-header d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <h5 class="mb-0">{{ __('Blog Description Note') }}</h5>
                </div>
                <form action="{{ route('admin.aiPrompt.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="mb-3">
                            <strong>Note: </strong>If you use <strong style="color: var(--theme-color) !important">
                                <i>{title}</i></strong>, the blog title will be automatically retrieved and inserted into
                            the main prompt.
                        </div>

                        <input name="product_description" type="hidden"
                            value="{{ $generaleSetting?->product_description }}">
                        <input name="page_description" type="hidden" value="{{ $generaleSetting?->page_description }}">
                        <label for="" class="mb-1">
                            {{ __('Blog  Description') }} <span class="text-danger">*</span>
                        </label>
                        <textarea name="blog_description" id="blog_description" class="form-control" rows="4" required
                            placeholder="Enter Page Description">{{ $generaleSetting?->blog_description }}</textarea>

                        @error('blog_description')
                            <p class="text text-danger m-0">{{ $message }}</p>
                        @enderror

                    </div>
                    @hasPermission('admin.aiPrompt.update')
                        <div class="d-flex justify-content-end mt-4 mb-3 me-3">
                            <button type="submit" id="saveBtn3" class="btn btn-primary py-2.5 px-3">
                                {{ __('Save And Update') }}
                            </button>
                        </div>
                    @endhasPermission

                </form>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        .cardBox {
            box-shadow: 0 1px 11px rgba(99, 99, 99, 0.476) !important;
        }

        .btn-primary:disabled {
            background-color: #484848 !important;
            border-color: #484848 !important;
        }

        .backImg {
            position: relative;
            z-index: 1;
        }

        .backImg::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url("{{ asset('assets/icons-admin/intelligence.svg') }}") no-repeat center;
            background-size: contain;
            opacity: 0.06;
            z-index: -1;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productDesc = document.getElementById("product_description");
            const pageDesc = document.getElementById("page_description");
            const blogDesc = document.getElementById("blog_description");
            const saveBtn1 = document.getElementById("saveBtn1");
            const saveBtn2 = document.getElementById("saveBtn2");
            const saveBtn3 = document.getElementById("saveBtn3");

            function validateProductDesc() {
                const value = productDesc.value;
                const hasProductName = value.includes("{product_name}");
                const hasShortDesc = value.includes("{short_description}");
                saveBtn1.disabled = !(hasProductName && hasShortDesc);
            }

            function validatePageDesc() {
                const value = pageDesc.value;
                const hasTitle = value.includes("{title}");
                saveBtn2.disabled = !hasTitle;
            }

            function validateblogDesc() {
                const value = blogDesc.value;
                const hasTitle = value.includes("{title}");
                saveBtn3.disabled = !hasTitle;
            }

            validateProductDesc();
            validatePageDesc();
            validateblogDesc();

            productDesc.addEventListener("input", validateProductDesc);
            pageDesc.addEventListener("input", validatePageDesc);
            blogDesc.addEventListener("input", validateblogDesc);
        });
    </script>
@endpush
