<?php

use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\Shop\Auth\LoginController;
use App\Http\Controllers\Shop\BannerController;
use App\Http\Controllers\Shop\BrandController;
use App\Http\Controllers\Shop\BulkProductExportController;
use App\Http\Controllers\Shop\BulkProductImportController;
use App\Http\Controllers\Shop\DashboardController;
use App\Http\Controllers\Shop\EmployeeController;
use App\Http\Controllers\Shop\FlashSaleController;
use App\Http\Controllers\Shop\GalleryController;
use App\Http\Controllers\Shop\NotificationController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\POSController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\SubscriptionController;
use App\Http\Controllers\Shop\VoucherController;
use App\Http\Controllers\Shop\WithdrawController;
use Illuminate\Support\Facades\Route;
use Twilio\Rest\Chat;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::name('shop.')->group(function () {

    Route::controller(OrderController::class)->group(function () {
        Route::get('/download-invoice/{id}', 'downloadInvoice')->name('download-invoice');
        Route::get('/payment-slip/{id}/download', 'paymentSlip')->name('payment-slip');
    });

    // Login
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login')->middleware('guest');
        Route::post('/login', 'login')->name('login.submit')->middleware('throttle:5,5');
        Route::get(('/register'), 'create')->name('register')->middleware('throttle:5,5');
        Route::post(('/register'), 'store')->name('register.submit');
    });

    Route::middleware(['authShop', 'checkPermission'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

        // Subscription
        Route::controller(SubscriptionController::class)->group(function () {
            Route::get('/subscription', 'index')->name('subscription.index');
            Route::post('/subscription/purchase', 'purchase')->name('subscription.purchase');
        });

        // banner
        Route::controller(BannerController::class)->group(function () {
            Route::get('/banners', 'index')->name('banner.index');
            Route::get('/banner/create', 'create')->name('banner.create');
            Route::post('/banner/store', 'store')->name('banner.store');
            Route::get('/banner/{banner}/edit', 'edit')->name('banner.edit');
            Route::put('/banner/{banner}/update', 'update')->name('banner.update');
            Route::get('/banner/{banner}/toggle', 'statusToggle')->name('banner.toggle');
            Route::get('/banner/{banner}/destroy', 'destroy')->name('banner.destroy');
        });

        // Orders
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders/{status?}', 'index')->name('order.index');
            Route::get('/orders/{order}/show', 'show')->name('order.show');
            Route::get('/orders/{order}/status-change', 'statusChange')->name('order.status.change');
            Route::get('/orders/{order}/payment-status-toggle', 'paymentStatusToggle')->name('order.payment.status.toggle');
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
            Route::get('/product/create', 'create')->name('product.create');
            Route::post('/product/store', 'store')->name('product.store');
            Route::get('/product/{product}/edit', 'edit')->name('product.edit');
            Route::put('/product/{product}/update', 'update')->name('product.update');
            Route::get('/product/{product}/show', 'show')->name('product.show');
            Route::get('/product/{product}/toggle', 'statusToggle')->name('product.toggle');
            Route::get('/product/{product}/destroy', 'destroy')->name('product.destroy');
            Route::get('/product/thumbnail/{productThumbnail}/delete', 'thumbnailDestroy')->name('product.remove.thumbnail');
            Route::get('/product/{product}/generate-barcode', 'generateBarcode')->name('product.barcode');
            Route::get('/trashed-products', 'onlyTrashedProduct')->name('product.trashedList');
            Route::get('/product/{id}/restore', 'restore')->name('product.restore');
            Route::post('/generate-ai-data', 'generateAIData')->name('product.generate.AI.data');
        });

        // profile
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::put('/profile/update', 'update')->name('profile.update');
            Route::get('/profile/change-password', 'changePassword')->name('profile.change-password');
            Route::put('/profile/change-password/update', 'updatePassword')->name('profile.change-password.update');
        });

        // Promo Codes
        Route::controller(VoucherController::class)->group(function () {
            Route::get('/vouchers', 'index')->name('voucher.index');
            Route::get('/voucher/create', 'create')->name('voucher.create');
            Route::post('/voucher/store', 'store')->name('voucher.store');
            Route::get('/voucher/{coupon}/edit', 'edit')->name('voucher.edit');
            Route::put('/voucher/{coupon}/update', 'update')->name('voucher.update');
            Route::get('/voucher/{coupon}/destroy', 'destroy')->name('voucher.destroy');
            Route::get('/voucher/{coupon}/toggle', 'statusToggle')->name('voucher.toggle');
        });

        // Logout
        Route::controller(LoginController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });

        // notification
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/new-notifications', 'index')->name('dashboard.notification');
            Route::get('/notifications', 'show')->name('notification.show');
            Route::get('/notification/{notification}/read', 'markAsRead')->name('notification.read');
            Route::get('/notification/{notification}/destroy', 'destroy')->name('notification.destroy');
            Route::get('/notification/read-all', 'markAllAsRead')->name('notification.readAll');
        });

        // withdrawal route
        Route::controller(WithdrawController::class)->group(function () {
            Route::get('/withdraw', 'index')->name('withdraw.index');
            Route::post('/withdraw/store', 'store')->name('withdraw.store');
            Route::get('/withdraw/{withdraw}/delete', 'delete')->name('withdraw.delete');
            Route::get('/withdraw/{withdraw}/show', 'show')->name('withdraw.show');
        });
        // bulk product route
        Route::controller(BulkProductImportController::class)->group(function () {
            Route::get('/bulk-product-import', 'index')->name('bulk-product-import.index');
            Route::post('/bulk-product-import/store', 'store')->name('bulk-product-import.store');
            Route::get('/bulk-product-format-export', 'formatExport')->name('bulk-product-import.formatExport');
            Route::post('/bulk-product-import/export', 'export')->name('bulk-product-import.export');
        });

        // bulk product export route
        Route::controller(BulkProductExportController::class)->group(function () {
            Route::get('/bulk-product-export', 'index')->name('bulk-product-export.index');
            Route::post('/bulk-product-export/export', 'export')->name('bulk-product-export.export');
            Route::get('/bulk-product-export/demo', 'demoExport')->name('bulk-product-export.demo');
        });

        // gallery route
        Route::controller(GalleryController::class)->group(function () {
            Route::get('/gallery', 'index')->name('gallery.index');
            Route::get('/gallery/create', 'create')->name('gallery.create');
            Route::post('/gallery/store', 'store')->name('gallery.store');
        });

        // POS routes
        Route::controller(POSController::class)->group(function () {
            Route::get('/offline-sales-panel', 'index')->name('pos.index');
            Route::get('/offline-sales', 'sales')->name('pos.sales');
            Route::get('/offline-draft-sales', 'draft')->name('pos.draft');
            Route::get('/offline-draft-sales/draft/{posCart}/delete', 'draftDelete')->name('pos.draft.delete');

            // others
            Route::get('/pos/{order}/invoice', 'invoice')->name('pos.invoice');
            Route::post('/fetch-products', 'getProduct')->name('pos.product');
            Route::post('/add-to-cart', 'addToCart')->name('pos.addToCart');
            Route::post('/fetch-cart', 'getCart')->name('pos.getCart');
            Route::post('/update-cart', 'updateCart')->name('pos.updateCart');
            Route::post('/remove-cart', 'removeCart')->name('pos.removeCart');
            Route::post('/apply-coupon', 'applyCoupon')->name('pos.applyCoupon');
            Route::post('/remove-coupon', 'removeCoupon')->name('pos.removeCoupon');
            Route::post('/store-order', 'storeOrder')->name('pos.submitOrder');
            Route::post('/customer-store', 'storeCustomer')->name('pos.customerStore');
            Route::post('/pos/product/detail', 'getProductDetail')->name('pos.product.detail');
        });

        // employee management route
        Route::controller(EmployeeController::class)->group(function () {
            Route::get('/employees', 'index')->name('employee.index');
            Route::get('/employee/create', 'create')->name('employee.create');
            Route::post('/employee/store', 'store')->name('employee.store');
            Route::put('/employee/{user}/update', 'update')->name('employee.update');
            Route::get('/employee/{user}/destroy', 'destroy')->name('employee.destroy');
            Route::post('employee/{user}/reset-password', 'resetPassword')->name('employee.reset-password');
            Route::get('/employee/{user}/permission', 'permission')->name('employee.permission');
            Route::post('/employee/{user}/permission', 'updatePermission')->name('employee.permission.update');
        });

        // flash sale route
        Route::controller(FlashSaleController::class)->group(function () {
            Route::get('/flash-sale', 'index')->name('flashSale.index');
            Route::get('/flash-sale/{flashSale}/show', 'show')->name('flashSale.show');
            Route::post('/flash-sale/{flashSale}/product-store', 'productStore')->name('flashSale.productStore');
            Route::get('/flash-sale/{flashSale}/product/{product}/remove', 'productRemove')->name('flashSale.productRemove');
            Route::put('/flash-sale/{flashSale}/product/{product}/edit', 'update')->name('flashSale.product.edit');
        });

        // customer messages route
        Route::controller(ChatController::class)->group(function () {
            Route::get('/customer-chats', 'index')->name('customer.chat.index');
            Route::get('/get-users', 'getUsers')->name('customer.chat.getUsers');
            Route::get('/get-message', 'getMessageAdmin')->name('customer.chat.getMessage');
            Route::post('/send-message', 'sendMessageAdmin')->name('customer.chat.sendMessage');
        });
    });
});
