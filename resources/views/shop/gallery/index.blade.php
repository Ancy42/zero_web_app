@extends('layouts.app')
@section('header-title', __('Gallery Images'))
@section('content')

    <iframe src="{{ url('/admin/laravel-filemanager?from=gallery') }}"
        style="width: 100%; height: 700px; overflow: hidden; border: none;">
    </iframe>

@endsection
