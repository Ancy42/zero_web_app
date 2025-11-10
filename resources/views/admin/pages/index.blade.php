@extends('layouts.app')
@section('header-title', __('Pages'))

@section('content')
    <div class="container-fluid mb-3">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <h4 class="m-0">{{ __('All Pages') }}</h4>

            @hasPermission('admin.page.create')
                <a href="{{ route('admin.page.create') }}" class="btn py-2.5 btn-primary">
                    <i class="fa fa-plus-circle"></i>
                    {{ __('Create New') }}
                </a>
            @endhasPermission
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('URL') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $page)
                                @if ($page->is_editable)
                                <tr>
                                    <td>{{ __($page->title) }}</td>
                                    <td class="text-muted">
                                        {{ $page->url }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.page.edit', $page->id) }}"
                                                class="btn circleIcon btn-outline-info btn-sm">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                    loading="lazy" />
                                            </a>
                                            @if ($page->is_editable)
                                                <a href="{{ route('admin.page.show', $page->id) }}"
                                                    class="btn circleIcon btn-outline-primary btn-sm">
                                                    <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="view"
                                                        loading="lazy" />
                                                </a>
                                            @endif
                                            @if (!$page->is_default)
                                                <a href="{{ route('admin.page.destroy', $page->id) }}"
                                                    class="btn circleIcon btn-outline-danger deleteConfirm btn-sm">
                                                    <img src="{{ asset('assets/icons-admin/trash.svg') }}"
                                                        alt="delete" loading="lazy" />
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
