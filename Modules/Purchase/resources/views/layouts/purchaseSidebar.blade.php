@hasPermission([
    'shop.purchase.create',
    'shop.purchase.index',
    'shop.purchase.stockSummary',
    'shop.purchaseReturn.index',
    'shop.purchaseReturn.create',
    'shop.product.stockSummary'
])
    <!--- flash sale --->
    <li>
        <a class="menu {{ request()->routeIs('shop.purchase.*', 'shop.purchaseReturn.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#purchaseMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/Inventory.svg') }}" alt="icon" loading="lazy" />
                {{ __('Purchase') }}
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
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('shop.purchase.*', 'shop.purchaseReturn.*') ? 'show' : '' }}"
            id="purchaseMenu">
            <div class="listBar">
                @hasPermission('shop.purchase.allProduct.stockSummary')
                    <a href="{{ route('shop.purchase.allProduct.stockSummary') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.purchase.allProduct.stockSummary') ? 'active' : '' }}">
                        {{ __('Stock Report') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.purchase.create')
                    <a href="{{ route('shop.purchase.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.purchase.create') ? 'active' : '' }}">
                        {{ __('Add New Purchase') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.purchase.index')
                    <a href="{{ route('shop.purchase.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.purchase.index', 'shop.purchase.show') ? 'active' : '' }}">
                        {{ __('Purchase Invoices ') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.purchase.summary')
                    <a href="{{ route('shop.purchase.summary') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.purchase.summary') ? 'active' : '' }}">
                        {{ __('Purchase Summary ') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.purchaseReturn.index')
                    <a href="{{ route('shop.purchaseReturn.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.purchaseReturn.index') ? 'active' : '' }}">
                        {{ __('Purchase Returns ') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.purchaseReturn.create')
                    <a href="{{ route('shop.purchaseReturn.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.purchaseReturn.create') ? 'active' : '' }}">
                        {{ __('Add Purchase Return ') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
