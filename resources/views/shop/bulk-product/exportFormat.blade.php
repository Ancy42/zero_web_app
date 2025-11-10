@extends('layouts.app')
@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">
        <h4>Make CSV Template</h4>
    </div>

    <form action="{{ route('shop.bulk-product-import.export') }}" method="POST">
        @csrf
        <input type="hidden" name="quantity" value="{{ $quantity }}">
        <div class="container-fluid mt-3">

            <div class="displayGrid w-100">

                <div class="grid-cols-1">
                    <div class="card">
                        <div class="card-body w-100 overflow-hidden">
                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Thumbnail</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>price</th>
                                            <th>Discount price</th>
                                            <th>SKU</th>
                                            <th>Stock QTY</th>
                                            <th>short description</th>
                                            <th>description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($qty = 1; $qty <= $quantity; $qty++)
                                            <tr id="row{{ $qty }}" data-id="{{ $qty }}"
                                                onclick="selectRow({{ $qty }})">
                                                <td>
                                                    <input type="checkbox" name="export[]" value="{{ $qty }}"
                                                        id="item{{ $qty }}">

                                                </td>
                                                <td style="min-width: 130px">
                                                    <input type="text" name="export[{{ $qty }}][name]">
                                                </td>
                                                <td style="min-width: 120px">
                                                    <div id="thumbnail{{ $qty }}"
                                                        class="d-flex gap-1 thumbnailImg">

                                                    </div>
                                                </td>
                                                <td style="min-width: 140px">
                                                    <div id="category{{ $qty }}" class="d-flex gap-1">

                                                    </div>
                                                </td>
                                                <td style="min-width: 140px">
                                                    <div id="brand{{ $qty }}" class="d-flex gap-1">
                                                    </div>
                                                </td>
                                                <td style="min-width: 100px">
                                                    <input type="number" name="export[{{ $qty }}][price]">
                                                </td>
                                                <td style="min-width: 100px">
                                                    <input type="number"
                                                        name="export[{{ $qty }}][discount_price]">
                                                </td>
                                                <td style="min-width: 100px">
                                                    <input type="text" name="export[{{ $qty }}][product_sku]"
                                                        value="{{ random_int(100000, 999999) }}">
                                                </td>
                                                <td style="min-width: 90px">
                                                    <input type="number" name="export[{{ $qty }}][stock_qty]">
                                                </td>
                                                <td style="min-width: 140px">
                                                    <input type="text"
                                                        name="export[{{ $qty }}][short_description]">
                                                </td>
                                                <td style="min-width: 140px">
                                                    <input type="text" name="export[{{ $qty }}][description]">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-cols-1 mt-3 mt-lg-0">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills m-0 p-0 gap-1 flex-wrap" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active m-0" id="pills-image-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-image" type="button" role="tab"
                                        aria-controls="pills-image" aria-selected="true">
                                        Choose Gallery Images
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link m-0" id="pills-category-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-category" type="button" role="tab"
                                        aria-controls="pills-category" aria-selected="false">
                                        Choose Category
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link m-0" id="pills-brand-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-brand" type="button" role="tab"
                                        aria-controls="pills-brand" aria-selected="false">Choose Brand</button>
                                </li>

                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-image" role="tabpanel"
                                    aria-labelledby="pills-image-tab" tabindex="0">

                                    <div class="mt-3">
                                        <h5>Choose Thumbnail Image from Gallery</h5>
                                    </div>

                                    <div class="d-flex gap-3 flex-wrap mt-3">
                                        @foreach ($galleries as $gallery)
                                            <button type="button" class="gallery-image" data-id="{{ $gallery->id }}"
                                                onclick="selectImage({{ $gallery->id }}, '{{ $gallery->srcUrl }}')">
                                                <img src="{{ $gallery->srcUrl }}" alt="" width="100">
                                            </button>
                                        @endforeach
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="pills-category" role="tabpanel"
                                    aria-labelledby="pills-category-tab" tabindex="0">

                                    <div class="mt-3">
                                        <h5>Choose Category for selected row</h5>
                                    </div>
                                    <div class="d-flex gap-3 flex-wrap mt-3">
                                        @foreach ($categories as $category)
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick='selectCategory("{{ $category->name }}","{{ $category->id }}")'>
                                                {{ $category->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pills-brand" role="tabpanel"
                                    aria-labelledby="pills-brand-tab" tabindex="0">
                                    <div class="mt-3">
                                        <h5>Choose Brand for selected row</h5>
                                    </div>
                                    <div class="d-flex gap-3 flex-wrap mt-3">
                                        @foreach ($brands as $brand)
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick='selectBrand("{{ $brand->name }}","{{ $brand->id }}")'>
                                                {{ $brand->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="my-3">
                <div class="card">
                    <div class="card-body d-flex">
                        <button type="submit" class="btn btn-primary py-2">
                            {{ __('Confirm And Export') }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <style>
        .table input {
            width: 100%;
            border: 1px solid #dee2e6;
            padding: 2px;
            border-radius: 5px;
            outline: none;
        }

        .table input:focus {
            border: 1px solid var(--theme-color);
        }

        .table .thumbnailImg {
            min-height: 32px !important;
        }

        .nav-pills .nav-link {
            border: 1px solid #dee2e6;
        }

        .displayGrid {
            display: grid;
            overflow: hidden;
            grid-template-columns: 1fr 1fr;
            grid-gap: 16px;
        }

        .displayGrid .grid-cols-1 {
            overflow: hidden;
        }

        @media(max-width: 991px) {
            .displayGrid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
@push('scripts')
    <script>
        var selectedRowId = null;

        function selectRow(rowId) {

            // Uncheck all checkboxes
            let checkboxes = document.querySelectorAll('input[name="export[]"]');
            checkboxes.forEach(cb => cb.checked = false);

            // Check the checkbox in the clicked row
            document.getElementById('item' + rowId).checked = true;

            // Get the data-id of the selected row
            selectedRowId = document.getElementById('row' + rowId).getAttribute('data-id');
        }

        function selectImage(id, src) {
            if (!selectedRowId) {
                alert('Please select a preview row first');
                return;
            }

            const thumbnail = document.getElementById('thumbnail' + selectedRowId);

            let exits = document.getElementById('preview' + id + selectedRowId);
            const thumbnailInput = document.getElementById('input' + id + selectedRowId);
            if (exits) {
                thumbnail.removeChild(exits);
                thumbnail.removeChild(thumbnailInput);

            } else {
                let image = document.createElement('img');
                image.src = src;
                image.id = 'preview' + id + selectedRowId;
                image.width = 34;
                image.height = 30;
                thumbnail.appendChild(image);

                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'export[' + selectedRowId + '][gallery_images][' + id + ']';
                input.value = id;
                input.id = 'input' + id + selectedRowId;
                thumbnail.appendChild(input);
            }

        }

        const selectCategory = (categoryName, categoryId) => {
            if (!selectedRowId) {
                alert('Please select a preview row first');
                return;
            }

            const category = document.getElementById('category' + selectedRowId);

            const exitsCategory = document.getElementById('categoriespan' + categoryId + selectedRowId);

            if (exitsCategory) {
                category.removeChild(exitsCategory);

                const categoryInput = document.getElementById('catInput' + categoryId + selectedRowId);

                category.removeChild(categoryInput);

            } else {
                let categoriespan = document.createElement('span')
                categoriespan.id = 'categoriespan' + categoryId + selectedRowId;
                categoriespan.textContent = categoryName;
                categoriespan.className = 'badge bg-secondary';
                category.appendChild(categoriespan);

                let Categoryinput = document.createElement('input');
                Categoryinput.type = 'hidden';
                Categoryinput.name = 'export[' + selectedRowId + '][categories][' + categoryId + ']';
                Categoryinput.value = categoryName;
                Categoryinput.id = 'catInput' + categoryId + selectedRowId;
                category.appendChild(Categoryinput);
            }
        }

        const selectBrand = (brandName, brandId) => {
            if (!selectedRowId) {
                alert('Please select a preview row first');
                return;
            }

            const brand = document.getElementById('brand' + selectedRowId);

            const exitsBrand = document.getElementById('brandSpan' + selectedRowId);

            if (exitsBrand) {
                brand.removeChild(exitsBrand);
                const brandInput = document.getElementById('brandInput' + selectedRowId);
                brand.removeChild(brandInput);
            } else {
                let brandSpan = document.createElement('span')
                brandSpan.id = 'brandSpan' + selectedRowId;
                brandSpan.textContent = brandName;
                brandSpan.className = 'badge bg-secondary';
                brand.appendChild(brandSpan);

                let brandInput = document.createElement('input');
                brandInput.type = 'hidden';
                brandInput.name = 'export[' + selectedRowId + '][brand]';
                brandInput.value = brandName;
                brandInput.id = 'brandInput' + selectedRowId;
                brand.appendChild(brandInput);
            }
        }
    </script>
@endpush
