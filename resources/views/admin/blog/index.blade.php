@extends('layouts.app')
@section('header-title', __('Product List'))

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <h4 class="mb-0">
                {{ __('Blog List') }}
            </h4>
            @hasPermission('admin.blog.create')
                <a href="{{ route('admin.blog.create') }}" class="btn py-2.5 btn-primary">
                    <i class="fa fa-plus-circle"></i>
                    {{ __('Create New') }}
                </a>
            @endhasPermission
        </div>

        <div class="my-3 card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Thumbnail') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th class="text-center">{{ __('views') }}</th>
                                <th>{{ __('Created Date') }}</th>
                                @hasPermission('admin.blog.toggle')
                                <th class="text-center">{{ __('Status') }}</th>
                                @endhasPermission
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($blogs as $key => $blog)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>

                                <td>
                                    <div class="product-image">
                                        <img src="{{ $blog->thumbnail }}">
                                    </div>
                                </td>

                                <td>{{ Str::limit($blog->title, 50, '...') }}</td>

                                <td>
                                    {{ $blog->category?->name }}
                                </td>

                                <td class="text-center">
                                    {{ $blog->views?->count() ?? 0 }}
                                </td>

                                <td>
                                    {{ $blog->created_at->format('d M, Y') }} <br>
                                    <small>{{ $blog->created_at->diffForHumans() }}</small>
                                </td>

                                @hasPermission('admin.blog.toggle')
                                    <td class="text-center">
                                        <label class="switch mb-0" data-bs-toggle="tooltip" data-bs-placement="left"
                                            data-bs-title="{{ __('Status Update') }}">
                                            <a href="{{ route('admin.blog.toggle', $blog->id) }}">
                                                <input type="checkbox" {{ $blog->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </a>
                                        </label>
                                    </td>
                                @endhasPermission

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">

                                        <a href="/blog/{{ $blog->slug }}" target="_blank" class="svg-bg btn-outline-primary circleIcon" data-bs-toggle="tooltip" data-bs-placement="left"
                                            data-bs-title="{{ __('view details') }}">
                                            <img src="{{ asset('assets/icons-admin/eye.svg') }}" alt="icon"
                                                loading="lazy" />
                                        </a>

                                        @hasPermission('admin.blog.edit')
                                            <a href="{{ route('admin.blog.edit', $blog->id) }}"
                                                class="btn-outline-info circleIcon" data-bs-toggle="tooltip"
                                                data-bs-placement="left" data-bs-title="{{ __('Edit') }}">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="icon"
                                                    loading="lazy" />
                                            </a>
                                        @endhasPermission

                                        @hasPermission('admin.blog.delete')
                                            <a href="{{ route('admin.blog.destroy', $blog->id) }}" class="btn-outline-danger deleteConfirm circleIcon"
                                                data-bs-toggle="tooltip" data-bs-placement="left"
                                                data-bs-title="{{ __('Delete') }}">
                                                <img src="{{ asset('assets/icons-admin/trash.svg') }}" alt="icon"
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
            {{ $blogs->links() }}
        </div>

    </div>
@endsection
