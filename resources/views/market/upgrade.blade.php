@extends('layouts.app')

@section('header-title', __('Support & Updates'))
@section('header-subtitle', __('Manage Support & Updates'))
@include('joynala.maker::market.css')
@section('content')
<section class="support_container">
    <header class="page_header">
        <div class=" border-b">
            <p class="header_title">{{ __('Product Support') }}</p>
            <a target="_blank" href="https://web.whatsapp.com/send?text=I%20need%20support+&phone=8801336909483" class="common_btn">{{ __('Get Support') }}</a>
        </div>

        <!-- tabs  -->
        <div class="tab_container">
            <button class="btn_tab btn_tab_active">{{ __('Product Update') }}</button>
            <a href="{{ route('marketplace.index') }}" class="btn_tab">{{ __('Marketplace') }} </a>
            <a href="{{ route('marketplace.addons') }}" class="btn_tab">{{ __("My Addon's") }}</a>
        </div>
    </header>
    @include('joynala.maker::market.upgrade')
</section>
@endsection
