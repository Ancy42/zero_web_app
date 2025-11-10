@extends('layouts.app')
@section('header-title', __('Product List'))

@section('content')
    <div class="d-flex gap-2 justify-content-between mb-2">
        <h4 class="mb-0">{{ __('Product List') }}</h4>
        @if (request('approve') == true)
            <div class="mt-2 d-flex gap-2 justify-content-end">
                <a href=" {{ route('admin.product.index', ['view_type' => 'grid', 'approve' => true]) }}"
                    class="btn btn-{{request('view_type') == 'grid' ? 'primary' : 'secondary'}}"><i class="bi bi-grid"></i></a>
                <a href=" {{ route('admin.product.index', ['view_type' => 'list', 'approve' => true]) }}"
                    class="btn btn-{{request('view_type') == 'list' || !request('view_type') ? 'primary' : 'secondary'}}"><i class="bi bi-list-ul"></i></a>
            </div>
        @endif
    </div>

    <form action="" method="GET" class="card card-body filterForm">

        @if (request('approve'))
            <input type="hidden" name="approve" value="{{ request('approve') }}">
            <input type="hidden" name="view_type" value="{{ request('view_type') }}">
        @else
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <div class="d-flex justify-content-end align-items-center  gap-4">
            <div>
                <select class="form-select" name="shop" placeholder="All Shop">
                    <option value="">
                        {{ __('All Shop') }}
                    </option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if (request('approve') == true)
                <div>
                    <select class="form-select" name="category" placeholder="Select Category">
                        <option value="">
                            {{ __('Select Category') }}
                        </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select class="form-select" name="brand" placeholder="All Brand">
                        <option value="">
                            {{ __('All Brand') }}
                        </option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div>
                <div class="mt-2 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    @if (request('approve') == true)
                        <a href="{{ route('admin.product.index', ['approve' => true]) }}" class="btn btn-dark"><i
                                class="fa fa-refresh"></i></a>
                    @endif
                </div>
            </div>
            @if (request('approve') == true)
                <div>
                    <div class="input-group" style="max-width: 400px">
                        <input type="text" class="form-control" placeholder="Search here" aria-label="Username"
                            aria-describedby="basic-addon1" name="search" value="{{ request('search') }}">
                        <button type="submit" class="input-group-text btn btn-primary" id="basic-addon1"><i
                                class="fa fa-search"></i></button>
                    </div>
                </div>
            @endif
        </div>


    </form>

    <div class="container-fluid">

        @if (request('view_type') == 'grid')
            @include('admin.product.gridView')
        @else
            @include('admin.product.listView')
        @endif

        <div class="my-3">
            {{ $products->withQueryString()->links() }}
        </div>

        <form action="" method="POST" class="d-none" id="deleteForm">
            @csrf
            @method('DELETE')
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        $(".confirmApprove").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to approve this product",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Approve it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        const confirmDeny = (id) => {
            const form = document.getElementById('deleteForm');
            form.action = `{{ route('admin.product.destroy', ':id') }}`.replace(':id', id);
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this product! If you confirm, it will be deleted permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Yes, Delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endpush
