<?php


use Illuminate\Support\Facades\Route;
use Modules\Purchase\App\Http\Controllers\OrderController;
use Modules\Purchase\App\Http\Controllers\PurchaseController;
use Modules\Purchase\App\Http\Controllers\SupplierController;
use Modules\Purchase\App\Http\Controllers\PurchaseReturnController;

Route::middleware(['auth', 'checkPermission'])->name('shop.')->prefix('shop')->group(function () {

    Route::controller(PurchaseController::class)->prefix('purchase')->group(function () {
        Route::get('/', 'index')->name('purchase.index');
        Route::get('/create', 'create')->name('purchase.create');
        Route::post('/store', 'store')->name('purchase.store');
        Route::post('/{purchase}/attach-product', 'attachProduct')->name('purchase.attach.product');
        Route::get('/{purchase}/edit', 'edit')->name('purchase.edit');
        Route::get('/{purchase}/show', 'show')->name('purchase.show');
        Route::get('/{purchase}/purchase-invoice', 'purchaseInvoice')->name('purchase.purchaseInvoice');
        Route::put('/{purchase}/update', 'update')->name('purchase.update');
        Route::get('/{purchase}/destroy', 'destroy')->name('purchase.destroy');
        Route::get('/products', 'getProducts')->name('purchase.products');
        Route::get('/{purchase}/make-received', 'makeReceived')->name('purchase.makeReceived');
        Route::post('/{purchase}/delete-product-barcode', 'deleteProductBarcode')->name('purchase.product.delete.barcode');
        Route::get('/invoice/search', 'invoiceSearch')->name('purchase.invoice.search');
        Route::get('/invoice/add', 'invoiceAdd')->name('purchase.invoice.add');
        Route::get('/summary', 'purchaseStockSummary')->name('purchase.summary');
        Route::get('/stock-summary', 'productStockSummary')->name('purchase.allProduct.stockSummary');
    });

    Route::controller(PurchaseReturnController::class)->prefix('purchase-return')->group(function () {
        Route::get('/', 'index')->name('purchaseReturn.index');
        Route::get('/create', 'create')->name('purchaseReturn.create');
        Route::get('/invoice/search', 'invoiceSearch')->name('purchase.invoice.search');
        Route::get('/purchase/invoice/add', 'invoiceAdd')->name('purchase.invoice.add');
        Route::post('/store/{purchase}', 'store')->name('purchaseReturn.store');
        Route::get('/{id}/return-invoice', 'returnInvoice')->name('purchaseReturn.Invoice');
    });

    Route::controller(SupplierController::class)->prefix('supplier')->group(function () {
        Route::get('/', 'index')->name('supplier.index');
        Route::get('/create', 'create')->name('supplier.create');
        Route::post('/store', 'store')->name('supplier.store');
        Route::get('/{supplier}/show', 'show')->name('supplier.show');
        Route::get('/{supplier}/edit', 'edit')->name('supplier.edit');
        Route::put('/{supplier}/update', 'update')->name('supplier.update');
        Route::get('/{supplier}/toggle', 'statusToggle')->name('supplier.toggle');
        Route::get('/{supplier}/destroy', 'destroy')->name('supplier.destroy');
        Route::get('/{supplier}/get-statistic', 'getStatistic')->name('supplier.statistic');
        Route::post('/{supplier}/payment', 'makePayment')->name('supplier.payment');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::post('/order/fetch-order-products', 'fetchOrderProducts')->name('order.fetch.products');
        Route::post('/order-attach-barcode', 'attachBarcode')->name('order.attach.barcode');
        Route::post('/pos/update-or-add/sku', 'addOrUpdateSKU')->name('pos.addOrUpdateSKU');
    });
});
