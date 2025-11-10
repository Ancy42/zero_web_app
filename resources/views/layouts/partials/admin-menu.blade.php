<!--- Dashboard --->
<li>
    <a class="menu {{ $request->routeIs('admin.dashboard.*') ? 'active' : '' }}"
        href="{{ route('admin.dashboard.index') }}">
        <span>
            <img class="menu-icon" src="{{ asset('assets/icons-admin/overview.svg') }}" alt="icon" loading="lazy" />
            {{ __('Overview') }}
        </span>
    </a>
</li>

@php
   use \Nwidart\Modules\Facades\Module;
@endphp


@hasPermission(['shop.pos.index', 'shop.pos.draft', 'shop.pos.sales'])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Point Of Sale') }}</span>
        <div class="devider_line"></div>
    </li>

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


@hasPermission('admin.order.index')
    <li class="menu-divider">
        <span class="menu-title">{{ __(' Online Orders') }}</span>
        <div class="devider_line"></div>
    </li>

    <!--- Orders --->
    <li>
        <a class="menu {{ $request->routeIs('admin.order.*') ? 'active' : '' }}" href="{{ route('admin.order.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/orders.svg') }}" alt="icon" loading="lazy" />
                {{ __('Orders') }}
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission(['admin.category.index', 'admin.product.index', 'admin.brand.index'])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Product Management') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@hasPermission(['shop.product.index', 'shop.product.create', 'admin.product.index', 'shop.product.trashedList'])
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
                @hasPermission('admin.product.index')
                <a href="{{ route('admin.product.index', 'approve=true') }}"
                    class="subMenu {{ request()->filled('approve') && request()->approve == 'true' ? 'active' : '' }}"
                    title="{{ __('Accepted Item') }}">
                    {{ __('List Of Products') }}
                </a>
                @endhasPermission
                @hasPermission('shop.product.create')
                    <a href="{{ route('shop.product.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.product.create') ? 'active' : '' }}">
                        {{ __('Add Product') }}
                    </a>
                @endhasPermission

                @hasPermission('shop.product.index')
                    <a href="{{ route('shop.product.index', 'view_type=grid') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.product.index') ? 'active' : '' }}">
                        {{ __('My Products') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.product.index')
                @if ($generaleSetting?->new_product_approval)
                    <a href="{{ route('admin.product.index', 'status=0') }}"
                        class="subMenu {{ request()->filled('status') && request()->status == 0 ? 'active' : '' }}"
                        title="{{ __('New Product Request') }}">
                        {{ __('New Product Request') }}
                    </a>
                @endif

                @if ($generaleSetting?->update_product_approval)
                    <a href="{{ route('admin.product.index', 'status=1') }}"
                        class="subMenu {{ request()->filled('status') && request()->status == 1 ? 'active' : '' }}"
                        title="{{ __('Update Product Request') }}">
                        {{ __('Update Product Request') }}
                    </a>
                @endif
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

@hasPermission('admin.category.index')
    <!--- categories--->
    <li>
        <a class="menu {{ $request->routeIs('admin.category.*') ? 'active' : '' }}"
            href="{{ route('admin.category.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/category.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Categories') }}
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission(['admin.categoryAttribute.index'])
    <li>
        <a class="menu {{ $request->routeIs('admin.categoryAttribute.*') ? 'active' : '' }}"
            href="{{ route('admin.categoryAttribute.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/attribute.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Attributes') }}
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission('admin.brand.index')
    <!--- brand --->
    <li>
        <a class="menu {{ $request->routeIs('admin.brand.*') ? 'active' : '' }}" href="{{ route('admin.brand.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/brand.svg') }}" alt="icon" loading="lazy" />
                {{ __('Brands') }}
            </span>
        </a>
    </li>
@endhasPermission



@hasPermission([
    'admin.flashSale.index',
    'admin.flashSale.create',
    'admin.ad.index',
    'admin.ad.create',
    'admin.coupon.index',
    'admin.coupon.create'
])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Sale Management') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@hasPermission(['admin.flashSale.index', 'admin.flashSale.create'])
    <!--- flash sale --->
    <li>
        <a class="menu {{ request()->routeIs('admin.flashSale.*', 'shop.flashSale.show') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#flashSaleMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/flash.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Flash Sales') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.flashSale.*', 'shop.flashSale.show') ? 'show' : '' }}"
            id="flashSaleMenu">
            <div class="listBar">
                @hasPermission('admin.flashSale.index')
                    <a href="{{ route('admin.flashSale.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.flashSale.index', 'shop.flashSale.show') ? 'active' : '' }}">
                        {{ __('List Of Flash Sales') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.flashSale.create')
                    <a href="{{ route('admin.flashSale.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.flashSale.create') ? 'active' : '' }}">
                        {{ __('Add Flash Sale') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission






@hasPermission(['admin.ad.index', 'admin.ad.create'])
    <!--- ads--->
    <li>
        <a class="menu {{ request()->routeIs('admin.ad.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#adMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/ads.svg') }}" alt="icon" loading="lazy" />
                {{ __('Ads') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.ad.*') ? 'show' : '' }}" id="adMenu">
            <div class="listBar">
                @hasPermission('admin.ad.index')
                    <a href="{{ route('admin.ad.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.ad.index') ? 'active' : '' }}">
                        {{ __('List Of Ads') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.ad.create')
                    <a href="{{ route('admin.ad.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.ad.create') ? 'active' : '' }}">
                        {{ __('Add Ads') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
@hasPermission(['admin.coupon.index', 'admin.coupon.create'])
    <!--- coupon--->
    <li>
        <a class="menu {{ request()->routeIs('admin.coupon.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#couponMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/coupon-percent.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Coupon') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.coupon.*') ? 'show' : '' }}"
            id="couponMenu">
            <div class="listBar">
                @hasPermission('admin.coupon.index')
                    <a href="{{ route('admin.coupon.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.coupon.index') ? 'active' : '' }}">
                        {{ __('List Of Coupons') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.coupon.create')
                    <a href="{{ route('admin.coupon.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.coupon.create') ? 'active' : '' }}">
                        {{ __('Add Coupon') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
@hasPermission('admin.customerNotification.index')
    <!--- notification--->
    <li>
        <a class="menu {{ $request->routeIs('admin.customerNotification.*') ? 'active' : '' }}"
            href="{{ route('admin.customerNotification.index') }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/notification.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Push Notification') }}
            </span>
        </a>
    </li>
@endhasPermission
@hasPermission(['admin.blog.index', 'admin.blog.create'])
    <li>
        <a class="menu {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#blogMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/blog.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Blogs') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.blog.*') ? 'show' : '' }}" id="blogMenu">
            <div class="listBar">
                @hasPermission('admin.blog.index')
                    <a href="{{ route('admin.blog.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.blog.index') ? 'active' : '' }}">
                        {{ __('List Of Blogs') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.blog.create')
                    <a href="{{ route('admin.blog.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.blog.create') ? 'active' : '' }}">
                        {{ __('Add New Blog') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
@if ($businessModel == 'multi')
    @hasPermission([
        'admin.shop.index',
        'admin.shop.create',
        'admin.subscription-plan.index',
        'admin.subscription-plan.create'
    ])
        <li class="menu-divider">
            <span class="menu-title">{{ __('Vendor management') }}</span>
            <div class="devider_line"></div>
        </li>
    @endhasPermission

    @hasPermission(['admin.shop.index', 'admin.shop.create'])
        <!--- shop management--->
        <li>
            <a class="menu {{ request()->routeIs('admin.shop.*', 'admin.withdraw.index') ? 'active' : '' }}"
                data-bs-toggle="collapse" href="#shopMenu">
                <span>
                    <img class="menu-icon" src="{{ asset('assets/icons-admin/vendor.svg') }}" alt="icon"
                        loading="lazy" />
                    {{ __('Vendors') }}
                </span>
                <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
            </a>
            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.shop.*', 'admin.withdraw.index') ? 'show' : '' }}"
                id="shopMenu">
                <div class="listBar">

                    @hasPermission('admin.shop.index')
                        <a href="{{ route('admin.shop.index') }}"
                            class="subMenu hasCount {{ request()->routeIs('admin.shop.index') ? 'active' : '' }}">
                            {{ __('List Of Vendors') }}
                        </a>
                    @endhasPermission
                    @hasPermission('admin.shop.create')
                        <a href="{{ route('admin.shop.create') }}"
                            class="subMenu hasCount {{ request()->routeIs('admin.shop.create') ? 'active' : '' }}">
                            {{ __('Add Vendor') }}
                        </a>
                    @endhasPermission
                    @hasPermission('admin.withdraw.index')
                        <a href="{{ route('admin.withdraw.index') }}"
                            class="subMenu hasCount {{ request()->routeIs('admin.withdraw.*') ? 'active' : '' }}">
                            {{ __('Withdraw Request') }}
                        </a>
                    @endhasPermission
                </div>
            </div>
        </li>
    @endhasPermission

    @hasPermission(['admin.subscription-plan.index', 'admin.subscription-plan.create'])
        <!--- subscription plans --->
        <li>
            <a class="menu {{ request()->routeIs('admin.subscription-plan.*') ? 'active' : '' }}"
                data-bs-toggle="collapse" href="#subscriptionMenu">
                <span>
                    <img class="menu-icon" src="{{ asset('assets/icons-admin/crown.svg') }}" alt="icon"
                        loading="lazy" />
                    {{ __('Subscription') }}
                </span>
                <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
            </a>
            <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.subscription-plan.*') ? 'show' : '' }}"
                id="subscriptionMenu">
                <div class="listBar">
                    @hasPermission('admin.subscription-plan.index')
                        <a href="{{ route('admin.subscription-plan.index') }}"
                            class="subMenu hasCount {{ request()->routeIs('admin.subscription-plan.index') ? 'active' : '' }}">
                            {{ __('List Of Plans') }}
                        </a>
                    @endhasPermission
                    @hasPermission('admin.employee.create')
                        <a href="{{ route('admin.subscription-plan.create') }}"
                            class="subMenu hasCount {{ request()->routeIs('admin.subscription-plan.create') ? 'active' : '' }}">
                            {{ __('Add New Plan') }}
                        </a>
                    @endhasPermission
                </div>
            </div>
        </li>
    @endhasPermission
@endif

@hasPermission([
    'admin.supportTicket.index',
    'admin.support.index',
    'admin.whatsAppChat.index',
    'shop.customer.chat.index'
])
    <!--- Conversations --->
    <li class="menu-divider">
        <span class="menu-title">{{ __('Messages') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@hasPermission('shop.customer.chat.index')
    <li>
        <a class="menu {{ $request->routeIs('shop.customer.chat.index') ? 'active' : '' }}"
            href="{{ route('shop.customer.chat.index') }}">
            <span class="position-relative">
                <img class="menu-icon" src="{{ asset('assets/icons-admin/message.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Live Messages') }}
                <span id="unread-message-badge" class="bg-success text-white ms-2 position-absolute d-none"
                    style="top: 0; transform: translateY(-50%); left: 5px; height: 16px; width: 16px; border-radius: 50%; font-size: 10px; display: flex; align-items: center; justify-content: center;">
                    0
                </span>
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission('admin.whatsAppChat.index')
    <li>
        <a class="menu {{ $request->routeIs('admin.whatsAppChat.index') ? 'active' : '' }}"
            href="{{ route('admin.whatsAppChat.index') }}">
            <span class="position-relative">
                <img class="menu-icon" src="{{ asset('assets/icons-admin/whatsapp.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('WhatsApp') }}
                <span id="unread-message-badge" class="bg-success text-white ms-2 position-absolute d-none"
                    style="top: 0; transform: translateY(-50%); left: 5px; height: 16px; width: 16px; border-radius: 50%; font-size: 10px; display: flex; align-items: center; justify-content: center;">
                    0
                </span>
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission(['admin.supportTicket.index'])
    <!--- Help Requests --->
    <li>
        <a href="{{ route('admin.supportTicket.index') }}"
            class="menu {{ request()->routeIs('admin.supportTicket.*') ? 'active' : '' }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/query.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Customer Query') }}
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission(['admin.support.index'])
    <!--- Help Notes --->
    <li>
        <a href="{{ route('admin.support.index') }}"
            class="menu {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/notes.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Notes') }}
            </span>
        </a>
    </li>
@endhasPermission

@hasPermission(['admin.rider.index', 'admin.customer.index', 'admin.employee.index', 'admin.role.index'])
    <li class="menu-divider">
        <span class="menu-title">{{ __('User Management') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@if (module_exists('purchase') )
    @include('purchase::layouts.supplierSidebar')
@endif
@hasPermission(['admin.rider.index', 'admin.rider.create'])
    <li>
        <a class="menu {{ request()->routeIs('admin.rider.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#riderMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/truck.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Drivers') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.rider.*') ? 'show' : '' }}"
            id="riderMenu">
            <div class="listBar">
                @hasPermission('admin.rider.index')
                    <a href="{{ route('admin.rider.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.rider.index') ? 'active' : '' }}">
                        {{ __('List Of Drivers') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.employee.create')
                    <a href="{{ route('admin.rider.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.rider.create') ? 'active' : '' }}">
                        {{ __('Add New Driver') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission

@hasPermission(['admin.customer.index', 'admin.customer.create'])
    <li>
        <a class="menu {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#customerMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/customer.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Customers') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.customer.*') ? 'show' : '' }}"
            id="customerMenu">
            <div class="listBar">
                @hasPermission('admin.customer.index')
                    <a href="{{ route('admin.customer.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.customer.index') ? 'active' : '' }}">
                        {{ __('List Of Customers') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.employee.create')
                    <a href="{{ route('admin.customer.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.customer.create') ? 'active' : '' }}">
                        {{ __('Add New Customer') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>

@endhasPermission
@hasPermission(['admin.employee.index', 'admin.employee.create'])

    <li>
        <a class="menu {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#employeeMenu">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/employee.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Moderators') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="icon" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.employee.*') ? 'show' : '' }}"
            id="employeeMenu">
            <div class="listBar">
                @hasPermission('admin.employee.index')
                    <a href="{{ route('admin.employee.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.employee.index') ? 'active' : '' }}">
                        {{ __('List Of Moderators') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.employee.create')
                    <a href="{{ route('admin.employee.create') }}"
                        class="subMenu hasCount {{ request()->routeIs('admin.employee.create') ? 'active' : '' }}">
                        {{ __('Add New Moderator') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
@hasPermission([
    'admin.generale-setting.index',
    'admin.business-setting.index',
    'admin.socialLink.index',
    'admin.themeColor.index',
    'admin.deliveryCharge.index',
    'admin.ticketIssueType.index',
    'admin.contactUs.index',
    'admin.pusher.index',
    'admin.mailConfig.index',
    'admin.paymentGateway.index',
    'admin.sms-gateway.index',
    'admin.firebase.index',
    'admin.verification.index',
    'admin.role.index'
])
    <li class="menu-divider">
        <span class="menu-title">{{ __('Settings') }}</span>
        <div class="devider_line"></div>
    </li>
@endhasPermission

@if ($businessModel != 'single')
    @hasPermission(['shop.profile.index'])
        <!--- Profile --->
        <li>
            <a class="menu {{ $request->routeIs('shop.profile.*') ? 'active' : '' }}"
                href="{{ route('shop.profile.index') }}">
                <span>
                    <img class="menu-icon" src="{{ asset('assets/icons-admin/user-circle.svg') }}" alt="icon"
                        loading="lazy" />
                    {{ __('My Profile') }}
                </span>
            </a>
        </li>
    @endhasPermission
@endif

@hasPermission([
    'admin.business-setting.index',
    'admin.deliveryCharge.index',
    'admin.vatTax.index',
    'admin.currency.index'
])
    <!---Business Settings --->
    <li>
        <a class="menu {{ request()->routeIs('admin.business-setting.*', 'admin.deliveryCharge.*', 'admin.vatTax.*', 'admin.currency.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#businessSettings">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/business.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Business Setting') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.business-setting.*', 'admin.deliveryCharge.*', 'admin.vatTax.*', 'admin.currency.*') ? 'show' : '' }}"
            id="businessSettings">
            <div class="listBar">

                @hasPermission('admin.business-setting.index')
                    <a href="{{ route('admin.business-setting.index') }}"
                        class="subMenu {{ request()->routeIs('admin.business-setting.*') ? 'active' : '' }}">
                        {{ __('Business Setup') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.vatTax.index')
                    <a href="{{ route('admin.vatTax.index') }}"
                        class="subMenu {{ request()->routeIs('admin.vatTax.*') ? 'active' : '' }}">
                        {{ __('VAT & Tax') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.deliveryCharge.index')
                    <a href="{{ route('admin.deliveryCharge.index') }}"
                        class="subMenu {{ request()->routeIs('admin.deliveryCharge.*') ? 'active' : '' }}">
                        {{ __('Delivery Charge') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.currency.index')
                    <a href="{{ route('admin.currency.index') }}"
                        class="subMenu {{ request()->routeIs('admin.currency.*') ? 'active' : '' }}">
                        {{ __('Currency') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission

<!--- third party configuration --->
@hasPermission([
    'admin.pusher.index',
    'admin.mailConfig.index',
    'admin.paymentGateway.index',
    'admin.sms-gateway.index',
    'admin.firebase.index',
    'admin.googleReCaptcha.index',
    'admin.aiPrompt.configure'
])
    <li>
        <a class="menu {{ request()->routeIs('admin.socialAuth.*', 'admin.pusher.*', 'admin.mailConfig.*', 'admin.paymentGateway.*', 'admin.sms-gateway.*', 'admin.firebase.*', 'admin.googleReCaptcha.*', 'admin.aiPrompt.configure') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#thirdPartConfig" title="Third Party configuration">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/tool.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Configure Dependence') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.pusher.*', 'admin.mailConfig.*', 'admin.paymentGateway.*', 'admin.sms-gateway.*', 'admin.firebase.*', 'admin.googleReCaptcha.*', 'admin.socialAuth.*', 'admin.aiPrompt.configure') ? 'show' : '' }}"
            id="thirdPartConfig">
            <div class="listBar">
                @hasPermission('admin.paymentGateway.index')
                    <a href="{{ route('admin.paymentGateway.index') }}"
                        class="subMenu {{ request()->routeIs('admin.paymentGateway.*') ? 'active' : '' }}">
                        {{ __('Payment Gateway') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.sms-gateway.index')
                    <a href="{{ route('admin.sms-gateway.index') }}"
                        class="subMenu {{ request()->routeIs('admin.sms-gateway.*') ? 'active' : '' }}">
                        {{ __('SMS Gateway') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.socialAuth.index')
                    <a href="{{ route('admin.socialAuth.index') }}"
                        class="subMenu {{ request()->routeIs('admin.socialAuth.*') ? 'active' : '' }}">
                        {{ __('Social Authentication') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.pusher.index')
                    <a href="{{ route('admin.pusher.index') }}"
                        class="subMenu {{ request()->routeIs('admin.pusher.*') ? 'active' : '' }}">
                        {{ __('Pusher Setup') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.mailConfig.index')
                    <a href="{{ route('admin.mailConfig.index') }}"
                        class="subMenu {{ request()->routeIs('admin.mailConfig.*') ? 'active' : '' }}">
                        {{ __('Mail Config') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.firebase.index')
                    <a href="{{ route('admin.firebase.index') }}"
                        class="subMenu {{ request()->routeIs('admin.firebase.*') ? 'active' : '' }}">
                        {{ __('Firebase Notification') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.googleReCaptcha.index')
                    <a href="{{ route('admin.googleReCaptcha.index') }}"
                        class="subMenu {{ request()->routeIs('admin.googleReCaptcha.*') ? 'active' : '' }}">
                        {{ __('Google ReCaptcha') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.aiPrompt.configure')
                    <a href="{{ route('admin.aiPrompt.configure') }}"
                        class="subMenu {{ request()->routeIs('admin.aiPrompt.configure') ? 'active' : '' }}">
                        {{ __('OpenAI Config') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission

@hasPermission(['admin.banner.index', 'admin.themeColor.index'])
    <!--- Settings --->
    <li>
        <a class="menu {{ request()->routeIs('admin.banner.*', 'admin.themeColor.*', 'admin.offerBanner.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#appearance">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/palette.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Appearance') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.banner.*', 'admin.themeColor.*', 'admin.offerBanner.*') ? 'show' : '' }}"
            id="appearance">
            <div class="listBar">
                @hasPermission('admin.banner.index')
                    <a href="{{ route('admin.banner.index') }}"
                        class="subMenu {{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
                        {{ __('Promotional Banner') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.themeColor.index')
                    <a href="{{ route('admin.themeColor.index') }}"
                        class="subMenu {{ request()->routeIs('admin.themeColor.*', 'admin.offerBanner.*') ? 'active' : '' }}">
                        {{ __('Color & Home Screen') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
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
                        {{ __('Products Export') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.bulk-product-import.index')
                    <a href="{{ route('shop.bulk-product-import.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.bulk-product-import.*') ? 'active' : '' }}">
                        {{ __('Products Import') }}
                    </a>
                @endhasPermission
                @hasPermission('shop.gallery.index')
                    <a href="{{ route('shop.gallery.index') }}"
                        class="subMenu hasCount {{ request()->routeIs('shop.gallery.*') ? 'active' : '' }}">
                        {{ __('File Manager') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission
<!--- cms --->
@hasPermission(['admin.menu.index', 'admin.page.index', 'admin.footer.index'])
    <li>
        <a class="menu {{ request()->routeIs('admin.menu.index*', 'admin.page.*', 'admin.footer.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#cms">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/file-settings.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('Manage Content') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.menu.*', 'admin.page.*', 'admin.footer.*') ? 'show' : '' }}"
            id="cms">
            <div class="listBar">

                @hasPermission('admin.menu.index')
                    <a href="{{ route('admin.menu.index') }}"
                        class="subMenu {{ request()->routeIs('admin.menu.index') ? 'active' : '' }}">
                        {{ __('Menus') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.page.index')
                    <a href="{{ route('admin.page.index') }}"
                        class="subMenu {{ request()->routeIs('admin.page.*') ? 'active' : '' }}">
                        {{ __('Legal') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.footer.index')
                    <a href="{{ route('admin.footer.index') }}"
                        class="subMenu {{ request()->routeIs('admin.footer.index') ? 'active' : '' }}">
                        {{ __('Footer') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission

@hasPermission([
    'admin.generale-setting.index',
    'admin.socialLink.index',
    'admin.ticketIssueType.index',
    'admin.verification.index',
    'admin.contactUs.index',
    'admin.country.index',
    'admin.role.index',
    'admin.aiPrompt.index'
])
    <!--- Settings --->
    <li>
        <a class="menu {{ request()->routeIs('admin.generale-setting.*', 'admin.socialLink.*', 'admin.ticketIssueType.*', 'admin.verification.*', 'admin.contactUs.*', 'admin.country.*', 'admin.role.*', 'admin.aiPrompt.index', 'admin.language.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#settings">
            <span>
                <img class="menu-icon" src="{{ asset('assets/icons-admin/settings.svg') }}" alt="icon"
                    loading="lazy" />
                {{ __('System Settings') }}
            </span>
            <img src="{{ asset('assets/icons-admin/caret-down.svg') }}" alt="" class="downIcon">
        </a>
        <div class="collapse dropdownMenuCollapse {{ $request->routeIs('admin.generale-setting.*', 'admin.socialLink.*', 'admin.ticketIssueType.*', 'admin.verification.*', 'admin.contactUs.*', 'admin.country.*', 'admin.role.*', 'admin.aiPrompt.index', 'admin.language.*') ? 'show' : '' }}"
            id="settings">
            <div class="listBar">
                @hasPermission('admin.generale-setting.index')
                    <a href="{{ route('admin.generale-setting.index') }}"
                        class="subMenu {{ request()->routeIs('admin.generale-setting.index') ? 'active' : '' }}">
                        {{ __('General') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.aiPrompt.index')
                    <a href="{{ route('admin.aiPrompt.index') }}"
                        class="subMenu {{ request()->routeIs('admin.aiPrompt.index') ? 'active' : '' }}">
                        {{ __('Ai Prompt') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.verification.index')
                    <a href="{{ route('admin.verification.index') }}"
                        class="subMenu {{ request()->routeIs('admin.verification.*') ? 'active' : '' }}">
                        {{ __('Auth Verification') }}
                    </a>
                @endhasPermission

                @hasPermission('admin.ticketIssueType.index')
                    <a href="{{ url('/admin/ticket-issue-types') }}"
                        class="subMenu {{ request()->routeIs('admin.ticketIssueType.index') ? 'active' : '' }}">
                        {{ __('Ticket Issue Types') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.role.index')
                    <a href="{{ route('admin.role.index') }}"
                        class="subMenu {{ request()->routeIs('admin.role.*') ? 'active' : '' }}">
                        {{ __('Role & Permissions') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.contactUs.index')
                    <a href="{{ route('admin.contactUs.index') }}"
                        class="subMenu {{ request()->routeIs('admin.contactUs.index') ? 'active' : '' }}">
                        {{ __('Contact Configure') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.socialLink.index')
                    <a href="{{ route('admin.socialLink.index') }}"
                        class="subMenu {{ request()->routeIs('admin.socialLink.index') ? 'active' : '' }}">
                        {{ __('Configure Social Links') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.country.index')
                    <a href="{{ route('admin.country.index') }}"
                        class="subMenu {{ request()->routeIs('admin.country.index') ? 'active' : '' }}">
                        {{ __('Configure Country') }}
                    </a>
                @endhasPermission
                @hasPermission('admin.language.index')
                    <a href="{{ route('admin.language.index') }}"
                        class="subMenu {{ request()->routeIs('admin.language.*') ? 'active' : '' }}">
                        {{ __('Configure Language') }}
                    </a>
                @endhasPermission
            </div>
        </div>
    </li>
@endhasPermission

