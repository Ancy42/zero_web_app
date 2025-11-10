@extends('layouts.app')

@section('header-title', __('Edit') . ' ' . __($page->title))

@section('content')
    <div class="container-fluid mb-4">
        <div class="d-flex justify-content-between align-items-between flex-wrap gap-2 mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">
                <i class="fa fa-arrow-left"></i>
                {{ __('Back') }}
            </a>

            @hasPermission('admin.page.edit')
                <a href="{{ route('admin.page.edit', $page->id) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-edit"></i>
                    {{ __('Edit') }}
                </a>
            @endhasPermission
        </div>

        <div class="card border-0 rounded-12">
            <div class="card-body">
                <h3>{{ $page->title }}</h3>

                <div class="mt-4">
                    {!! $page->description !!}
                </div>

            </div>
        </div>
    </div>
@endsection
