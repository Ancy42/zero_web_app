<div class="app-sidebar pe-0">
    <div class="scrollbar-sidebar pe-1" style=" overflow-y: auto;
    overflow-x: hidden;">
        <div class="branding-logo">
            @php
                $request = request();

                $shop = generaleSetting('shop');
                $rootShop = generaleSetting('rootShop');
                $isAdmin = $shop->id == $rootShop->id ? true : false;

                $url = $isAdmin ? route('admin.dashboard.index') : route('shop.dashboard.index');
            @endphp
            <a href="{{ $url }}">
                <img src="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}" alt="logo" loading="lazy" />
            </a>
        </div>
        <div class="branding-logo-forMobile">
            <a href="{{ $generaleSetting?->logo ?? asset('assets/logo.png') }}"></a>
        </div>
        <div class="app-sidebar-inner">
            <ul class="vertical-nav-menu">
                @if ($isAdmin)
                    @include('layouts.partials.admin-menu')
                @else
                    @include('layouts.partials.shop-menu')
                @endif
            </ul>
        </div>
        <div class="sideBarfooter">
            <button type="button" class="fullbtn hite-icon" onclick="toggleFullScreen(document.body)">
                <img src="{{ asset('assets/icons-admin/expand.svg') }}" alt="icon" loading="lazy" />
            </button>
            @if ($isAdmin)
                <a href="{{ route('marketplace.index') }}" class="fullbtn hite-icon">
                    <img src="{{ asset('assets/icons-admin/user-circle.svg') }}" alt="">
                </a>

            @else
                @hasPermission('shop.profile.index')
                    <a href="{{ route('shop.profile.index') }}" class="fullbtn hite-icon">
                        <img src="{{ asset('assets/icons-admin/user-circle.svg') }}" alt="">
                    </a>
                @endhasPermission
            @endif

            @role('root')
            <a href="{{ route('marketplace.index') }}">
                <img src="{{ asset('assets/icons-admin/shop.svg') }}" alt="icon" loading="lazy" />
            </a>
            <a href="{{ route('marketplace.upgrade') }}" class="fullbtn hite-icon">
                <small style="font-size: 10px; color: #888;">
                    {{ config('app.version') }}
                </small>
            </a>
            @else
            <a href="javascript:void(0)" class="fullbtn hite-icon logout">
                <img src="{{ asset('assets/icons-admin/log-out.svg') }}" alt="icon" loading="lazy" />
            </a>
            <a href="javascript:void(0)" class="fullbtn hite-icon">
                <small style="font-size: 10px; color: #888;">
                    {{ config('app.version') }}
                </small>
            </a>
            @endrole
        </div>
    </div>
</div>
