<!---################################--->
<!-- ////// Shop Header Navbar  ////// -->
<!---################################--->

<ul class="nav nav-tabs">
    @hasPermission('admin.shop.show')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.show') ? 'active' : '' }}"
                href="{{ route('admin.shop.show', $shop->id) }}">
                {{ __('Shop overview') }}
            </a>
        </li>
    @endhasPermission

    @hasPermission('admin.shop.orders')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.orders') ? 'active' : '' }}"
                href="{{ route('admin.shop.orders', $shop->id) }}">
                {{ __('Order') }}
            </a>
        </li>
    @endhasPermission

    @hasPermission('admin.shop.products')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.products') ? 'active' : '' }}"
                href="{{ route('admin.shop.products', $shop->id) }}">
                {{ __('Product') }}
            </a>
        </li>
    @endhasPermission

    @hasPermission('admin.shop.reviews')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.shop.reviews') ? 'active' : '' }}"
                href="{{ route('admin.shop.reviews', $shop->id) }}">
                {{ __('Review') }}
            </a>
        </li>
    @endhasPermission
</ul>
