@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-3">

        <div class="alert alert-info impotentNote">
            <h4 style="font-size: 1.5em; margin-bottom: 10px;">🌟 Important Information</h4>
            <p style="font-size: 1.1em; line-height: 1.5;">
                Just a quick note: <strong>VAT and Taxes are calculated based on your order.</strong> If you have multiple
                VAT and Tax rates active, your total VAT and Tax amount will be clearly displayed on the checkout page for
                your convenience.
            </p>
        </div>

        <!-- All Taxes -->
        <div class="my-3 card">
            <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3 p-3 border-bottom">
                <h4 class="mb-0">{{ __('All Taxes') }}</h4>

                @hasPermission('admin.vatTax.create')
                    <button type="button" data-bs-toggle="modal" data-bs-target="#createBrand" class="btn py-2 btn-primary">
                        <i class="fa fa-plus-circle"></i>
                        {{ __('Create New') }}
                    </button>
                @endhasPermission
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Percentage') }}</th>
                                @hasPermission('admin.vatTax.toggle')
                                    <th>{{ __('Status') }}</th>
                                @endhasPermission
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($vatTaxes as $key => $vatTax)
                            @php
                                $serial = $vatTaxes->firstItem() + $key;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $serial }}</td>
                                <td>{{ $vatTax->name }}</td>
                                <td>{{ $vatTax->percentage }}%</td>
                                @hasPermission('admin.vatTax.toggle')
                                    <td>
                                        <label class="switch mb-0">
                                            <a href="{{ route('admin.vatTax.toggle', $vatTax->id) }}">
                                                <input type="checkbox" {{ $vatTax->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </a>
                                        </label>
                                    </td>
                                @endhasPermission
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        @hasPermission('admin.vatTax.edit')
                                            <button type="button" class="btn btn-outline-info btn-sm circleIcon"
                                                onclick="openColorUpdateModal({{ $vatTax }})">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                    loading="lazy" />
                                            </button>
                                        @endhasPermission
                                        @hasPermission('admin.vatTax.destroy')
                                            <a href="{{ route('admin.vatTax.destroy', $vatTax->id) }}"
                                                class="btn btn-outline-danger btn-sm circleIcon deleteConfirm"
                                                onclick="openColorDeleteModal({{ $vatTax }})">
                                                <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="delete"
                                                    loading="lazy" />
                                            </a>
                                        @endhasPermission
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $vatTaxes->withQueryString()->links() }}
        </div>

    </div>


    <!--=== Create Color Modal ===-->
    <form action="{{ route('admin.vatTax.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="createBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Add New Tax') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                {{ __('Tax Name') }}
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="{{ __('Tax Name') }}" required />
                            @error('name')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="percentage" class="form-label">
                                {{ __('Percentage') }}(%)
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control" id="percentage" name="percentage"
                                placeholder="{{ __('Percentage') }}(%)"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\.\d{2})./g, '$1');"
                                step="0.01" required />
                            @error('percentage')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--=== update color Modal ===-->
    <form action="" id="updateColor" method="POST">
        @csrf
        @method('PUT')
        <div class="modal fade" id="updateBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Update Tax') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="updateName" class="form-label">
                                {{ __('Tax Name') }}
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control" id="updateName" name="name"
                                placeholder="{{ __('Tax Name') }}" required value="" />
                            @error('name')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="updatePercentage" class="form-label">
                                {{ __('Percentage') }}(%)
                                <small class="text-danger">*</small>
                            </label>
                            <input type="text" class="form-control" id="updatePercentage" name="percentage"
                                placeholder="{{ __('Percentage') }}(%)"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\.\d{2})./g, '$1');"
                                step="0.01" value="" required />
                            @error('percentage')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('css')
    <style>
        .impotentNote {
            border-radius: 8px;
            padding: 20px;
            background-color: #e7f3fe;
            color: #31708f;
            font-family: Arial, sans-serif;
        }

        .app-theme-dark .impotentNote {
            border-radius: 8px;
            padding: 20px;
            background-color: #2c2c2c;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            border: 1px solid #444;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const openColorUpdateModal = (tax) => {
            $("#updateName").val(tax.name);
            $("#updatePercentage").val(tax.percentage);
            $("#updateColor").attr('action', `{{ route('admin.vatTax.update', ':id') }}`.replace(':id', tax.id));

            $("#updateBrand").modal('show');
        }

        $('select[name="type"]').on('change', function() {
            var orderBaseTax = $('#orderBaseTax');
            var type = $(this).val();

            if (type == 'order base') {
                orderBaseTax.slideDown();

                // Update URL
                const url = new URL(window.location.href);
                url.searchParams.set('type', 'order base');
                window.history.pushState(null, '', url.toString());
            } else {
                orderBaseTax.slideUp();

                // Update URL
                const url = new URL(window.location.href);
                url.searchParams.set('type', 'product base');
                window.history.pushState(null, '', url.toString());
            }
        });
    </script>
@endpush
