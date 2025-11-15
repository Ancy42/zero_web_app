@hasPermission(['shop.supplier.index', 'shop.supplier.create'])
    <li>
        <a class="menu {{ request()->routeIs('shop.supplier.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#supplierMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/supplier.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Suppliers') }}
            </span>
            @if (app()->environment('local'))
            <span>
                <i class="fas fa-gift"></i>
                <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
            </span>
            @else
                <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
            @endif
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.supplier.*') ? 'show' : '' }}"
            id="supplierMenu">
            <div class="listBar">
                @hasPermission('shop.supplier.index')
                    <a href="{{ route('shop.supplier.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.supplier.index') ? 'active' : '' }}">
                        {{ __('List Of Suppliers') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.supplier.create')
                    <a href="{{ route('shop.supplier.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.supplier.create') ? 'active' : '' }}">
                        {{ __('Add New Supplier') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
