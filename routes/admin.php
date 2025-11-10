<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RiderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\VatTaxController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\FirebaseController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\SocialAuthController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\ThemeColorController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PusherConfigController;
use App\Http\Controllers\Admin\VerifyManageController;
use App\Http\Controllers\Admin\WhatsAppChatController;
use App\Http\Controllers\Admin\BusinessSetupController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\DeliveryChargeController;
use App\Http\Controllers\Admin\EmployeeManageController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\GeneraleSettingController;
use App\Http\Controllers\Admin\GoogleReCaptchaController;
use App\Http\Controllers\Admin\SMSGatewaySetupController;
use App\Http\Controllers\Admin\TicketIssueTypeController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\CategoryAttributeController;
use App\Http\Controllers\Admin\MailConfigurationController;
use App\Http\Controllers\Admin\CustomerNotificationController;
use App\Http\Controllers\Admin\MarketController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')->group(function () {
    // Login
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login')->middleware('guest');
        Route::post('/login', 'login')->name('login.submit')->middleware('throttle:5,5');
    });

    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['auth', 'checkPermission']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });


    Route::middleware(['auth', 'checkPermission'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::post('/statistics', [DashboardController::class, 'orderStatistics'])->name('dashboard.statistics');

        // banner
        Route::controller(BannerController::class)->group(function () {
            Route::get('/promotional-banners', 'index')->name('banner.index');
            Route::get('/promotional-banner/create', 'create')->name('banner.create');
            Route::post('/promotional-banner/store', 'store')->name('banner.store');
            Route::get('/promotional-banner/{banner}/edit', 'edit')->name('banner.edit');
            Route::put('/promotional-banner/{banner}/update', 'update')->name('banner.update');
            Route::get('/promotional-banner/{banner}/toggle', 'statusToggle')->name('banner.toggle');
            Route::get('/promotional-banner/{banner}/destroy', 'destroy')->name('banner.destroy');
        });

        // ads routes
        Route::controller(AdController::class)->group(function () {
            Route::get('/ads', 'index')->name('ad.index');
            Route::get('/ads/create', 'create')->name('ad.create');
            Route::post('/ads/store', 'store')->name('ad.store');
            Route::get('/ads/{ad}/edit', 'edit')->name('ad.edit');
            Route::put('/ads/{ad}/update', 'update')->name('ad.update');
            Route::get('/ads/{ad}/toggle', 'statusToggle')->name('ad.toggle');
            Route::get('/ads/{ad}/destroy', 'destroy')->name('ad.destroy');
        });

        // Shops
        Route::controller(ShopController::class)->group(function () {
            Route::get('/vendors', 'index')->name('shop.index');
            Route::get('/vendors/create', 'create')->name('shop.create');
            Route::post('/vendors/store', 'store')->name('shop.store');
            Route::get('/vendors/{shop}/edit', 'edit')->name('shop.edit');
            Route::post('/vendors/{shop}/update', 'update')->name('shop.update');
            Route::get('/vendors/{shop}', 'show')->name('shop.show');
            Route::get('/vendors/{shop}/status-toggle', 'statusToggle')->name('shop.status.toggle');
            Route::get('/vendors/{shop}/orders', 'orders')->name('shop.orders');
            Route::get('/vendors/{shop}/products', 'products')->name('shop.products');
            Route::get('/vendors/{shop}/categories', 'categories')->name('shop.category');
            Route::get('/vendors/{shop}/reviews', 'reviews')->name('shop.reviews');
            Route::post('/vendors/{shop}/reset-password', 'resetPassword')->name('shop.reset.password');
        });

        // Subscription plans
        Route::controller(SubscriptionPlanController::class)->group(function () {
            Route::get('/subscription-plans', 'index')->name('subscription-plan.index');
            Route::get('/subscription-plans/create', 'create')->name('subscription-plan.create');
            Route::post('/subscription-plans/store', 'store')->name('subscription-plan.store');
            Route::get('/subscription-plans/{subscriptionPlan}/edit', 'edit')->name('subscription-plan.edit');
            Route::put('/subscription-plans/{subscriptionPlan}/update', 'update')->name('subscription-plan.update');
            Route::get('/subscription-plans/{subscriptionPlan}/toggle', 'statusToggle')->name('subscription-plan.toggle');
            Route::delete('/subscription-plans/{subscriptionPlan}/destroy', 'destroy')->name('subscription-plan.destroy');
        });

        // reviews
        Route::controller(ReviewsController::class)->group(function () {
            Route::get('/reviews', 'index')->name('review.index');
            Route::get('/review/{review}/toggle', 'toggleReview')->name('review.toggle');
        });

        // Orders
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders/{status?}', 'index')->name('order.index');
            Route::get('/orders/{order}/show', 'show')->name('order.show');
            Route::get('/orders/{order}/status-change', 'statusChange')->name('order.status.change');
            Route::get('/orders/{order}/payment-status-toggle', 'paymentStatusToggle')->name('order.payment.status.toggle');
        });

        // Categories
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('category.index');
            Route::post('/category/store', 'store')->name('category.store');
            Route::get('/category/show', 'show')->name('category.show');
            Route::post('/category/{category}/update', 'update')->name('category.update');
            Route::delete('/category/{category}/destroy', 'destroy')->name('category.destroy');
            Route::get('/category/{category}/toggle', 'statusToggle')->name('category.toggle');
            Route::get('/category/menu-update', 'menuUpdate')->name('category.menu.update');
        });


        // Category Attribute
        Route::controller(CategoryAttributeController::class)->prefix('category-attributes')->group(function () {
           Route::get('/', 'index')->name('categoryAttribute.index');
            Route::post('/store', 'store')->name('categoryAttribute.store');
            Route::post('/update/{categoryAttribute}', 'update')->name('categoryAttribute.update');
            Route::get('/show', 'show')->name('categoryAttribute.show');
            Route::post('/menu-update', 'menuUpdate')->name('category.menu.update');
            Route::delete('/{categoryAttribute}/destroy', 'destroy')->name('categoryAttribute.destroy');
             Route::get('/category-attribute/{category}/add', 'getCategoryAttribute')->name('getCategoryAttribute');
        });


        // brand
        Route::controller(BrandController::class)->group(function () {
            Route::get('/brands', 'index')->name('brand.index');
            Route::post('/brand/store', 'store')->name('brand.store');
            Route::put('/brand/{brand}/update', 'update')->name('brand.update');
            Route::delete('/brand/{brand}/destroy', 'destroy')->name('brand.destroy');
            Route::get('/brand/{brand}/toggle', 'statusToggle')->name('brand.toggle');
        });

        // Products
        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('product.index');
            Route::get('/products/{product}/approve', 'approve')->name('product.approve');
            Route::get('/product/{product}/show', 'show')->name('product.show');
            Route::delete('/products/{product}/delete', 'destroy')->name('product.destroy');
        });

        // Generate Settings
        Route::controller(GeneraleSettingController::class)->group(function () {
            Route::get('/generale-setting', 'index')->name('generale-setting.index');
            Route::post('/generale-setting', 'update')->name('generale-setting.update')->middleware('demoMode');
            Route::post('/generale-update-command', 'updateCommand')->name('generale-setting.update.command');
            Route::get('/ai-prompts', 'aiPromptIndex')->name('aiPrompt.index');
            Route::post('/ai-prompts/update', 'aiPromptUpdate')->name('aiPrompt.update')->middleware('demoMode');
            Route::get('/ai-prompts/configure', 'aiPromptConfigure')->name('aiPrompt.configure');
            Route::post('/ai-prompts/configure/update', 'aiPromptConfigureUpdate')->name('aiPrompt.configure.update')->middleware('demoMode');
        });

        // business settings
        Route::controller(BusinessSetupController::class)->group(function () {
            Route::get('/business-setting', 'index')->name('business-setting.index');
            Route::post('/business-setting', 'update')->name('business-setting.update')->middleware('demoMode');

            Route::get('/business-shop', 'shop')->name('business-setting.shop');
            Route::post('/business-shop', 'shopUpdate')->name('business-setting.shop.update')->middleware('demoMode');

            Route::get('/business-withdraw', 'withdraw')->name('business-setting.withdraw');
            Route::post('/business-withdraw', 'withdrawUpdate')->name('business-setting.withdraw.update')->middleware('demoMode');

            Route::get('/business-shop/toggle-pos', 'togglePOS')->name('business-setting.shop.toggle-pos');
            Route::get('/business-shop/toggle-register', 'toggleRegister')->name('business-setting.shop.toggle-register');
        });

        // social links
        Route::controller(SocialLinkController::class)->group(function () {
            Route::get('/social-links', 'index')->name('socialLink.index');
            Route::post('/social-links/{socialLink}', 'update')->name('socialLink.update');
            Route::get('/social-links/{socialLink}/toggle', 'toggle')->name('socialLink.toggle');
        });

        // theme color
        Route::controller(ThemeColorController::class)->group(function () {
            Route::get('/home-screens', 'index')->name('themeColor.index');
            Route::post('/home-screens', 'update')->name('themeColor.update');
            Route::post('/home-screens/change', 'change')->name('themeColor.change');
            Route::get('/theme-status/{homeTheme}', 'themeStatus')->name('themeColor.status');
            Route::get('/offer-banners/{homeTheme}', 'offerBannerIndex')->name('offerBanner.index');
            Route::get('/offer-banner/{offerBanner}/edit', 'offerBannerEdit')->name('offerBanner.edit');
            Route::put('/offer-banner/{offerBanner}/update', 'offerBannerUpdate')->name('offerBanner.update')->middleware('demoMode');
        });

        // delivery charges
        Route::controller(DeliveryChargeController::class)->group(function () {
            Route::get('/delivery-charge', 'index')->name('deliveryCharge.index');
            Route::get('/delivery-charge/create', 'create')->name('deliveryCharge.create');
            Route::post('/delivery-charge/store', 'store')->name('deliveryCharge.store');
            Route::get('/delivery-charge/{deliveryCharge}/edit', 'edit')->name('deliveryCharge.edit');
            Route::put('/delivery-charge/{deliveryCharge}/update', 'update')->name('deliveryCharge.update');
            Route::get('/delivery-charge/{deliveryCharge}/destroy', 'destroy')->name('deliveryCharge.destroy');
        });

        // Coupons
        Route::controller(CouponController::class)->group(function () {
            Route::get('/coupons', 'index')->name('coupon.index');
            Route::get('/coupon/create', 'create')->name('coupon.create');
            Route::post('/coupon/store', 'store')->name('coupon.store');
            Route::get('/coupon/{coupon}/edit', 'edit')->name('coupon.edit');
            Route::put('/coupon/{coupon}/update', 'update')->name('coupon.update');
            Route::get('/coupon/{coupon}/destroy', 'destroy')->name('coupon.destroy');
            Route::get('/coupon/{coupon}/toggle', 'statusToggle')->name('coupon.toggle');
        });

        // Logout
        Route::controller(LoginController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });

        // notification route
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/new-notifications', 'index')->name('dashboard.notification');
            Route::get('/notifications', 'show')->name('notification.show');
            Route::get('/notification/{notification}/read', 'markAsRead')->name('notification.read');
            Route::get('/notification/{notification}/destroy', 'destroy')->name('notification.destroy');
            Route::get('/notification/read-all', 'markAllAsRead')->name('notification.readAll');
        });

        // Pusher Configuration
        Route::controller(PusherConfigController::class)->group(function () {
            Route::get('/pusher-configuration', 'index')->name('pusher.index');
            Route::post('/pusher-configuration', 'update')->name('pusher.update')->middleware('demoMode');
        });

        //  mail configuration
        Route::controller(MailConfigurationController::class)->group(function () {
            Route::get('/mail-configuration', 'index')->name('mailConfig.index');
            Route::put('/mail-configuration', 'update')->name('mailConfig.update');
            Route::post('/send-test-mail', 'sendTestMail')->name('mailConfig.sendTestMail');
        });

        // payment gateway
        Route::controller(PaymentGatewayController::class)->group(function () {
            Route::get('/payment-gateway', 'index')->name('paymentGateway.index');
            Route::post('/payment-gateway/{paymentGateway}/update', 'update')->name('paymentGateway.update')->middleware('demoMode');
            Route::get('/payment-gateway/{paymentGateway}/toggle', 'toggle')->name('paymentGateway.toggle')->middleware('demoMode');
        });

        //  SMS Gateway
        Route::controller(SMSGatewaySetupController::class)->group(function () {
            Route::get('/sms-gateway', 'index')->name('sms-gateway.index');
            Route::put('/sms-gateway', 'update')->name('sms-gateway.update');
        });

        // contact us
        Route::controller(ContactUsController::class)->group(function () {
            Route::get('/contact-us', 'index')->name('contactUs.index');
            Route::post('/contact-us/{contactUs?}', 'update')->name('contactUs.update');
        });

        // support route
        Route::controller(SupportController::class)->group(function () {
            Route::get('/supports', 'index')->name('support.index');
            Route::get('/support/{support}/delete', 'delete')->name('support.delete');
        });

        // withdrawal route
        Route::controller(WithdrawController::class)->group(function () {
            Route::get('/withdraws', 'index')->name('withdraw.index');
            Route::get('/withdraw/{withdraw}/show', 'show')->name('withdraw.show');
            Route::post('/withdraw/{withdraw}/update', 'update')->name('withdraw.update');
        });

        // profile
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::put('/profile/update', 'update')->name('profile.update');
            Route::get('/profile/change-password', 'changePassword')->name('profile.change-password');
            Route::put('/profile/change-password/update', 'updatePassword')->name('profile.change-password.update')->middleware('demoMode');
        });

        // rider route
        Route::controller(RiderController::class)->group(function () {
            Route::get('/drivers', 'index')->name('rider.index');
            Route::get('/drivers/create', 'create')->name('rider.create');
            Route::post('/drivers/store', 'store')->name('rider.store');
            Route::get('/drivers/{user}', 'show')->name('rider.show');
            Route::get('/drivers/{user}/edit', 'edit')->name('rider.edit');
            Route::put('/drivers/{user}/update', 'update')->name('rider.update')->middleware('demoMode');
            Route::get('/drivers/{user}/destroy', 'destroy')->name('rider.destroy')->middleware('demoMode');
            Route::get('/drivers/{user}/toggle', 'statusToggle')->name('rider.toggle')->middleware('demoMode');
            Route::post('/drivers/{order}/assign-order', 'assignOrder')->name('rider.assign.order');
        });

        // customer route
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/customers', 'index')->name('customer.index');
            Route::get('/customers/create', 'create')->name('customer.create');
            Route::post('/customers/store', 'store')->name('customer.store');
            Route::get('/customers/{user}', 'show')->name('customer.show');
            Route::get('/customers/{user}/edit', 'edit')->name('customer.edit');
            Route::put('/customers/{user}/update', 'update')->name('customer.update');
            Route::get('/customers/{user}/destroy', 'destroy')->name('customer.destroy');
            Route::get('/customers/{user}/toggle', 'statusToggle')->name('customer.toggle');
            Route::post('/customers/{user}/reset-password', 'resetPassword')->name('customer.reset-password')->middleware('demoMode');
        });

        // firebase route
        Route::controller(FirebaseController::class)->group(function () {
            Route::get('/firebase-config', 'index')->name('firebase.index');
            Route::post('/firebase-config', 'update')->name('firebase.update');
        });

        // language routes
        Route::controller(LanguageController::class)->group(function () {
            Route::get('/language', 'index')->name('language.index');
            Route::get('/language/create', 'create')->name('language.create');
            Route::post('/language/store', 'store')->name('language.store')->middleware('demoMode');
            Route::get('/language/{language}/edit', 'edit')->name('language.edit');
            Route::put('/language/{language}/update', 'update')->name('language.update')->middleware('demoMode');
            Route::get('/language/{language}/delete', 'delete')->name('language.delete')->middleware('demoMode');
            Route::post('/language/{language}/export', 'export')->name('language.export');
            Route::post('/language/{language}/import', 'import')->name('language.import')->middleware('demoMode');
            Route::get('/language/{language}/set-default', 'setDefault')->name('language.setDefault')->middleware('demoMode');
        });

        // Customer Notification route
        Route::controller(CustomerNotificationController::class)->group(function () {
            Route::get('/customer-notifications', 'index')->name('customerNotification.index');
            Route::get('/customer-notification/filter', 'filter')->name('customerNotification.filter');
            Route::post('/customer-notification-send', 'send')->name('customerNotification.send');
        });

        // employee management route
        Route::controller(EmployeeManageController::class)->group(function () {
            Route::get('/moderators', 'index')->name('employee.index');
            Route::get('/moderators/create', 'create')->name('employee.create');
            Route::post('/moderators/store', 'store')->name('employee.store');
            Route::put('/moderators/{user}/update', 'update')->name('employee.update');
            Route::get('/moderators/{user}/destroy', 'destroy')->name('employee.destroy');
            Route::post('moderators/{user}/reset-password', 'resetPassword')->name('employee.reset-password');
            Route::get('/moderators/{user}/permission', 'permission')->name('employee.permission');
            Route::post('/moderators/{user}/permission', 'updatePermission')->name('employee.permission.update');
        });

        // role permission route
        Route::controller(RolePermissionController::class)->group(function () {
            Route::get('/roles', 'index')->name('role.index');
            Route::post('/role/store', 'store')->name('role.store');
            Route::put('/role/{role}/update', 'update')->name('role.update');
            Route::get('/role/{role}/destroy', 'destroy')->name('role.destroy');
            Route::get('/role/{role}/permission', 'rolePermission')->name('role.permission');
            Route::post('/role/{role}/permission', 'updateRolePermission')->name('role.permission.update');
        });

        // verification management route
        Route::controller(VerifyManageController::class)->group(function () {
            Route::get('/verifications', 'index')->name('verification.index');
            Route::post('/verification/update', 'update')->name('verification.update');
        });

        // social login route
        Route::controller(SocialAuthController::class)->group(function () {
            Route::get('/social-auth', 'index')->name('socialAuth.index');
            Route::post('/social-auth/{socialAuth}/update', 'update')->name('socialAuth.update');
            Route::get('/social-auth/{socialAuth}/toggle', 'toggle')->name('socialAuth.toggle');
        });

        // flash sale route
        Route::controller(FlashSaleController::class)->group(function () {
            Route::get('/flash-sales', 'index')->name('flashSale.index');
            Route::get('/flash-sale/create', 'create')->name('flashSale.create');
            Route::post('/flash-sale/store', 'store')->name('flashSale.store');
            Route::get('/flash-sale/{flashSale}/edit', 'edit')->name('flashSale.edit');
            Route::put('/flash-sale/{flashSale}/update', 'update')->name('flashSale.update');
            Route::get('/flash-sale/{flashSale}/product', 'show')->name('flashSale.product');
            Route::get('/flash-sale/{flashSale}/destroy', 'destroy')->name('flashSale.destroy');
            Route::get('/flash-sale/{flashSale}/toggle', 'statusToggle')->name('flashSale.toggle');
        });

        // google reCaptcha route
        Route::controller(GoogleReCaptchaController::class)->group(function () {
            Route::get('/google-re-captcha', 'index')->name('googleReCaptcha.index');
            Route::post('/google-re-captcha', 'update')->name('googleReCaptcha.update');
        });

        // vat and tax route
        Route::controller(VatTaxController::class)->group(function () {
            Route::get('/vat-taxes', 'index')->name('vatTax.index');
            Route::post('/vat-tax/order-tax', 'orderTaxUpdate')->name('vatTax.order.update');
            Route::Post('/vat-tax/store', 'store')->name('vatTax.store');
            Route::put('/vat-tax/{vatTax}/update', 'update')->name('vatTax.update');
            Route::get('/vat-tax/{vatTax}/toggle', 'toggle')->name('vatTax.toggle');
            Route::get('/vat-tax/{vatTax}/destroy', 'destroy')->name('vatTax.destroy');
        });

        // blog route
        Route::controller(BlogController::class)->group(function () {
            Route::get('/blogs', 'index')->name('blog.index');
            Route::get('/blog/create', 'create')->name('blog.create');
            Route::post('/blog/store', 'store')->name('blog.store');
            Route::get('/blog/{blog}/edit', 'edit')->name('blog.edit');
            Route::put('/blog/{blog}/update', 'update')->name('blog.update');
            Route::get('/blog/{blog}/destroy', 'destroy')->name('blog.destroy');
            Route::get('/blog/{blog}/toggle', 'statusToggle')->name('blog.toggle');
            Route::post('/blog/generate-page-ai-data', 'generateAIData')->name('blog.generate.AI.data');
        });

        // currency route
        Route::controller(CurrencyController::class)->group(function () {
            Route::get('/currencies', 'index')->name('currency.index');
            Route::get('/currency/create', 'create')->name('currency.create');
            Route::post('/currency/store', 'store')->name('currency.store');
            Route::get('/currency/{currency}/edit', 'edit')->name('currency.edit');
            Route::put('/currency/{currency}/update', 'update')->name('currency.update');
            Route::get('/currency/{currency}/toggle', 'toggle')->name('currency.toggle');
            Route::get('/currency/{currency}/destroy', 'destroy')->name('currency.destroy')->middleware('demoMode');
        });

        // ticket issue types
        Route::controller(TicketIssueTypeController::class)->group(function () {
            Route::get('ticket-issue-types', 'index')->name('ticketIssueType.index'); // updated route
            Route::post('ticket-issue-type/store', 'store')->name('ticketIssueType.store'); // updated route
            Route::put('ticket-issue-type/{ticketIssueType}/update', 'update')->name('ticketIssueType.update'); // updated route
            Route::get('ticket-issue-type/{ticketIssueType}/status-toggle', 'toggleStatus')->name('ticketIssueType.toggle'); // updated route
            Route::get('ticket-issue-type/{ticketIssueType}/delete', 'destroy')->name('ticketIssueType.delete'); // updated route
        });

        // support ticket
        Route::controller(SupportTicketController::class)->group(function () {
            Route::get('/support-tickets', 'index')->name('supportTicket.index');
            Route::get('/support-tickets/{supportTicket}', 'show')->name('supportTicket.show');
            Route::post('/support-tickets/{supportTicket}/scheduled', 'setScheduled')->name('supportTicket.setScheduled');
            Route::post('/support-tickets/{supportTicket}/send-message', 'sendMessage')->name('supportTicket.sendMessage');
            Route::get('/support-tickets/{supportTicket}/fetch-messages', 'fetchMessages')->name('supportTicket.fetchMessages');
            Route::get('/support-tickets/{supportTicket}/update-status', 'updateStatus')->name('supportTicket.updateStatus');
            Route::get('/support-tickets/{supportTicketMessage}/pin', 'pinMessage')->name('supportTicket.pinMessage');
            Route::get('/support-tickets/{supportTicket}/chat-toggle', 'chatToggle')->name('supportTicket.chatToggle');
        });

        // cms menu route
        Route::controller(MenuController::class)->group(function () {
            Route::get('/menus', 'index')->name('menu.index');
            Route::get('/menu/create', 'create')->name('menu.create');
            Route::post('/menu/store', 'store')->name('menu.store');
            Route::get('/menu/{menu}/edit', 'edit')->name('menu.edit');
            Route::post('/menu/{menu}/update', 'update')->name('menu.update');
            Route::get('/menu/{menu}/remove', 'remove')->name('menu.remove');
            Route::get('/menu/{menu}/destroy', 'destroy')->name('menu.destroy')->middleware('demoMode');
            Route::post('/menu/sort', 'sort')->name('menu.sort');
            Route::post('/menu/drag', 'drag')->name('menu.drag');
        });

        // page route
        Route::controller(PageController::class)->group(function () {
            Route::get('/pages', 'index')->name('page.index');
            Route::get('/page/create', 'create')->name('page.create');
            Route::post('/page/store', 'store')->name('page.store');
            Route::get('/page/{page}/show', 'show')->name('page.show');
            Route::get('/page/{page}/edit', 'edit')->name('page.edit');
            Route::put('/page/{page}/update', 'update')->name('page.update');
            Route::get('/page/{page}/destroy', 'destroy')->name('page.destroy')->middleware('demoMode');
            Route::post('/generate-page-ai-data', 'generateAIData')->name('page.generate.AI.data');
        });

        // country route
        Route::controller(CountryController::class)->group(function () {
            Route::get('/countries', 'index')->name('country.index');
            Route::post('/country/store', 'store')->name('country.store');
            Route::put('/country/{country}/update', 'update')->name('country.update');
            Route::get('/country/{country}/toggle', 'toggle')->name('country.toggle');
            Route::get('/country/{country}/destroy', 'destroy')->name('country.destroy')->middleware('demoMode');
        });

        // footer route
        Route::controller(FooterController::class)->group(function () {
            Route::get('/footers', 'index')->name('footer.index');
            Route::post('/footer/{footer}/update', 'update')->name('footer.update');
            Route::put('/footer/{footerItem}/item-update', 'updateItem')->name('footer.update.item');
            Route::get('/footer/{footerItem}/destroy', 'destroy')->name('footer.destroy')->middleware('demoMode');
            Route::post('/footer/sort', 'itemSort')->name('footer.itemSort');
            Route::post('/footer/section-sort', 'sectionSort')->name('footer.sectionSort');
            Route::post('/footer/added-new', 'addedNew')->name('footer.addedNew');
            Route::post('/footer/disabled', 'disabled')->name('footer.disabled');
        });

        Route::controller(FooterController::class)->group(function () {
            Route::get('/footers', 'index')->name('footer.index');
        });

        Route::controller(WhatsAppChatController::class)->group(function () {
            Route::get('/whatsapp-chat', 'index')->name('whatsAppChat.index');
            Route::post('/send-message', 'sendMessage')->name('whatsAppChat.sendMessage');
            Route::get('/get-messages/{phone_number}', 'messageShow')->name('whatsAppChat.messageShow');
            Route::get('/get-phone-list', 'phoneList')->name('whatsAppChat.phoneList');
        });
    });
});

Route::controller(MarketController::class)->middleware('auth', 'role:root')->group(function () {
    Route::get('/upgrade-notice', 'upgrade')->name('marketplace.upgrade');
    Route::get('/marketplace', 'index')->name('marketplace.index');
    Route::get('/my-addons', 'addons')->name('marketplace.addons');

    Route::post('/upload-addons', 'upload')->name('upload.addons')->middleware('demoMode');
    Route::get('/addon-status/{addon}', 'updateStatus')->name('addon.status')->middleware('demoMode');
    Route::post('/verify-addons', 'verifyAddon')->name('verify.addons')->middleware('demoMode');
});
