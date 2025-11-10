<!--- Dashboard --->
<li>
    <a class="menu {{ $request->routeIs('shop.dashboard.*') ? 'active' : '' }}"
        href="{{ route('shop.dashboard.index') }}">
        <span>
            <img class="menu-icon" src="{{ asset('assets/icons-admin/overview.svg') }}" alt="icon" loading="lazy" />
            {{ __('Overview') }}
        </span>
    </a>
</li>
@php
    use Nwidart\Modules\Facades\Module;
@endphp

@if ($generaleSetting?->business_based_on == 'subscription')
    @hasPermission('shop.subscription.index')
        <!--- subscription --->
        <li>
            <a href="{{ route('shop.subscription.index') }}"
                class="menu {{ request()->routeIs('shop.subscription.*') ? 'active' : '' }}">
                <span>
                    <img class="menu-icon" src="{{ asset('assets/icons-admin/crown.svg') }}" alt="icon" loading="lazy" />
                    {{ __('Subscription') }}
                </span>
            </a>
        </li>
    @endhasPermission
@endif


@hasPermission(['shop.pos.index', 'shop.pos.draft', 'shop.pos.sales'])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Point Of Sale') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@hasPermission(['shop.pos.index', 'shop.pos.draft', 'shop.pos.sales'])
    <li>
        <a class="menu {{ request()->routeIs('shop.pos.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#posMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/pos.svg') }}" alt="icon" loading="lazy" />
                {{ __('POS') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.pos.*') ? 'show' : '' }}" id="posMenu">
            <div class="listBar">
                @hasPermission('shop.pos.index')
                    <a href="{{ route('shop.pos.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.pos.index') ? 'active' : '' }}">
                        {{ __('Sale Offline') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.pos.sales')
                    <a href="{{ route('shop.pos.sales') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.pos.sales') ? 'active' : '' }}">
                        {{ __('Offline Sales') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.pos.draft')
                    <a href="{{ route('shop.pos.draft') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.pos.draft') ? 'active' : '' }}">
                        {{ __('Drafts Orders') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission


@hasPermission('shop.order.index')
    <li class="menu-divider">
        <span class="menu-title">{{ __(' Online Orders') }}</span>
        <div class="devider_line"></div>
    </li>

    <!--- Orders --->
    <li>
        <a class="menu {{ $request->routeIs('shop.order.*') ? 'active' : '' }}" href="{{ route('shop.order.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/orders.svg') }}" alt="icon" loading="lazy" />
                {{ __('Orders') }}
            </span>
        </a>
    </li>
@endhasPermission


@hasPermission(['shop.category.index', 'shop.product.index', 'shop.brand.index'])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Product Management') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@hasPermission('shop.brand.index')
    <!--- brand --->
    <li>
        <a class="menu {{ $request->routeIs('shop.brand.*') ? 'active' : '' }}" href="{{ route('shop.brand.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/brand.svg') }}" alt="icon" loading="lazy" />
                {{ __('Brand') }}
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission(['shop.product.index', 'shop.product.create'])
    <!--- Products--->
    <li>
        <a class="menu {{ request()->routeIs('shop.product.*', 'admin.product.index') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#productMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/product.svg') }}" alt="icon" loading="lazy" />
                {{ __('Product Management') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.product.*', 'admin.product.index') ? 'show' : '' }}"
            id="productMenu">
            <div class="listBar">
                @hasPermission('shop.product.index')
                    <a href="{{ route('shop.product.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.product.index') ? 'active' : '' }}">
                        {{ __('List Of Products') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.product.create')
                    <a href="{{ route('shop.product.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.product.create') ? 'active' : '' }}">
                        {{ __('Add Product') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.product.trashedList')
                    <a href="{{ route('shop.product.trashedList') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.product.trashedList') ? 'active' : '' }}">
                        {{ __('Trash') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
@if (module_exists('purchase') )
    @include('purchase::layouts.purchaseSidebar')
@endif
@hasPermission(['shop.voucher.index', 'shop.voucher.create', 'shop.flashSale.index'])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Sale Management') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@hasPermission('admin.flashSale.index')
    <li>
        <a href="{{ route('shop.flashSale.index') }}"
            class="menu {{ request()->routeIs('shop.flashSale.*') ? 'active' : '' }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/flash.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Flash Sales') }}
            </span>
        </a>
    </li>
@endhasPermission


@hasPermission(['shop.voucher.index', 'shop.voucher.create'])
    <!--- coupon--->
    <li>
        <a class="menu {{ request()->routeIs('shop.voucher.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#couponMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/coupon-percent.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Coupon') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.voucher.*') ? 'show' : '' }}"
            id="couponMenu">
            <div class="listBar">
                @hasPermission('shop.voucher.index')
                    <a href="{{ route('shop.voucher.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.voucher.index') ? 'active' : '' }}">
                        {{ __('List Of Coupons') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.voucher.create')
                    <a href="{{ route('shop.voucher.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.voucher.create') ? 'active' : '' }}">
                        {{ __('Add Coupon') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission



@hasPermission(['shop.employee.index', 'shop.employee.create', 'shop.withdraw.index'])
    <li class="menu-divider">
        <span class="menu-title">{{ __('STORE MANAGEMENT') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission
@hasPermission(['shop.employee.index', 'shop.employee.create'])
    <li>
        <a class="menu {{ request()->routeIs('shop.employee.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#employeeMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/employee.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Moderators') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.employee.*') ? 'show' : '' }}"
            id="employeeMenu">
            <div class="listBar">
                @hasPermission('shop.employee.index')
                    <a href="{{ route('shop.employee.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.employee.index') ? 'active' : '' }}">
                        {{ __('List Of Moderators') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.employee.create')
                    <a href="{{ route('shop.employee.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.employee.create') ? 'active' : '' }}">
                        {{ __('Add New Moderator') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
@if (module_exists('purchase') )
    @include('purchase::layouts.supplierSidebar')
@endif
@if (!auth()->user()->hasRole('root'))
    @hasPermission('shop.withdraw.index')
        <li>
            <a class="menu {{ $request->routeIs('shop.withdraw.*') ? 'active' : '' }}"
                href="{{ route('shop.withdraw.index') }}">
                <span>
                    <img class="menu-icon" src="{{ asset('assets/icons-admin/withdraw.svg') }}" alt="icon"
                        loading="lazy" />
                    {{ __('Withdraws') }}
                </span>
            </a>
        </li>
    @endhasPermission
@endif

<!--- Conversations --->
<li class="menu-divider">
    <span class="menu-title">{{ __('Messages') }}</span>
    <div class="devider_line"></div>
</li>
@hasPermission('shop.customer.chat.index')
    <li>
        <a class="menu {{ $request->routeIs('shop.customer.chat.index') ? 'active' : '' }}"
            href="{{ route('shop.customer.chat.index') }}">
            <span class="position-relative">
                <img class="menu-icon" src="{{ asset('assets/icons-admin/message.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Messages') }}
                <span id="unread-message-badge" class="bg-success text-white ms-2 position-absolute d-none"
                    style="top: 0; transform: translateY(-50%); left: 5px; height: 16px; width: 16px; border-radius: 50%; font-size: 10px; display: flex; align-items: center; justify-content: center;">
                    0
                </span>
            </span>
        </a>
    </li>
@endhasPermission


@hasPermission([
    'shop.profile.index',
    'shop.bulk-product-export.index',
    'shop.bulk-product-import.index',
    'shop.gallery.index'
])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Settings') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@hasPermission(['shop.profile.index'])
    <!--- Profile --->
    <li>
        <a class="menu {{ $request->routeIs('shop.profile.*') ? 'active' : '' }}"
            href="{{ route('shop.profile.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/user-circle.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Profile') }}
            </span>
        </a>
    </li>
@endhasPermission

@if ($businessModel == 'multi')
    <!--- banner--->
    @hasPermission('shop.banner.index')
        {{-- <li>
            <a class="menu {{ $request->routeIs('shop.banner.*') ? 'active' : '' }}"
                href="{{ route('shop.banner.index') }}">
                <span>
                    <img class="menu-icon" src="{{ asset('assets/icons-admin/promotional.svg') }}" alt="icon"
                        loading="lazy" />
                    {{ __('Shop Banners') }}
                </span>
            </a>
        </li> --}}
        <li>
            <a class="menu {{ request()->routeIs('shop.banner.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                href="#bannerMenu">
                <span>
                    <img class="menu-icon" src="{{ asset('assets/icons-admin/promotional.svg') }}" alt="icon"
                        loading="lazy" />
                    {{ __('Shop Banners') }}
                </span>
                <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
            </a>
            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.banner.*') ? 'show' : '' }}"
                id="bannerMenu">
                <div class="listBar">
                    @hasPermission('shop.banner.index')
                        <a href="{{ route('shop.banner.index') }}"
                            class="subMenu hasCount {{ request()->routeIs('shop.banner.index') ? 'active' : '' }}">
                            {{ __('List Of Banners') }}
                        </a>
                    @endhasPermission
                    @hasPermission('shop.voucher.create')
                        <a href="{{ route('shop.banner.create') }}"
                            class="subMenu hasCount {{ request()->routeIs('shop.banner.create') ? 'active' : '' }}">
                            {{ __('Add Banner') }}
                        </a>
                    @endhasPermission
                </div>
            </div>
        </li>
    @endhasPermission
@endif

@hasPermission(['shop.bulk-product-export.index', 'shop.bulk-product-import.index', 'shop.gallery.index'])
    <!--- Import / Export --->
    <li>
        <a class="menu {{ request()->routeIs('shop.bulk-product-export.*', 'shop.bulk-product-import.*', 'shop.gallery.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#exportImportMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/download.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Import/Export') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.bulk-product-export.*', 'shop.bulk-product-import.*', 'shop.gallery.*') ? 'show' : '' }}"
            id="exportImportMenu">
            <div class="listBar">
                @hasPermission('shop.bulk-product-export.index')
                    <a href="{{ route('shop.bulk-product-export.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.bulk-product-export.*') ? 'active' : '' }}">
                        {{ __('Product Export') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.bulk-product-import.index')
                    <a href="{{ route('shop.bulk-product-import.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.bulk-product-import.*') ? 'active' : '' }}">
                        {{ __('Product Import') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.gallery.index')
                    <a href="{{ route('shop.gallery.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.gallery.*') ? 'active' : '' }}">
                        {{ __('Gallery Import') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
