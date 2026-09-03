<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperDepotController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\DealerSecurityMoneyController;
use App\Http\Controllers\DealerPurchaseOrderController;
use App\Http\Controllers\DealerDeliveryController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderTypeController;


Route::resource('super-depots', SuperDepotController::class);
Route::resource('depots', DepotController::class);
Route::resource('dealers', DealerController::class);
Route::get('dealers/{dealer}/security-money', [DealerSecurityMoneyController::class, 'index'])->name('dealers.security-money.index');
Route::post('dealers/{dealer}/security-money', [DealerSecurityMoneyController::class, 'store'])->name('dealers.security-money.store');


Route::resource('dealer-purchase-orders', DealerPurchaseOrderController::class);
Route::resource('dealer-deliveries', DealerDeliveryController::class);

Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/submit', [DealerPurchaseOrderController::class, 'submit'])->name('dealer-purchase-orders.submit');
Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/approve', [DealerPurchaseOrderController::class, 'approve'])->name('dealer-purchase-orders.approve');
Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/reject', [DealerPurchaseOrderController::class, 'reject'])->name('dealer-purchase-orders.reject');
Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/cancel', [DealerPurchaseOrderController::class, 'cancel'])->name('dealer-purchase-orders.cancel');
Route::get('dealer-deliveries/{dealerDelivery}/status', [DealerDeliveryController::class, 'statusForm'])->name('dealer-deliveries.status.form');
Route::put('dealer-deliveries/{dealerDelivery}/status', [DealerDeliveryController::class, 'updateStatus'])->name('dealer-deliveries.status.update');


Route::prefix('work-order-types')->name('work-order-types.')->group(function () {
    Route::get('/', [WorkOrderTypeController::class, 'index'])->name('index');
    Route::get('/create', [WorkOrderTypeController::class, 'create'])->name('create');
    Route::post('/', [WorkOrderTypeController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [WorkOrderTypeController::class, 'edit'])->name('edit');
    Route::put('/{id}', [WorkOrderTypeController::class, 'update'])->name('update');
    Route::delete('/{id}', [WorkOrderTypeController::class, 'destroy'])->name('destroy');
    Route::post('/toggle-status/{id}', [WorkOrderTypeController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/reorder', [WorkOrderTypeController::class, 'reorder'])->name('reorder');
});


/********************* Work Order Routes *****************/
Route::resource('work-orders', WorkOrderController::class);
Route::patch('/work-orders/{workOrder}/start', [WorkOrderController::class, 'start'])->name('work-orders.start');
Route::patch('/work-orders/{workOrder}/hold', [WorkOrderController::class, 'hold'])->name('work-orders.hold');
Route::patch('/work-orders/{workOrder}/resume', [WorkOrderController::class, 'resume'])->name('work-orders.resume');
Route::patch('/work-orders/{workOrder}/progress', [WorkOrderController::class, 'progress'])->name('work-orders.progress');
Route::patch('/work-orders/{workOrder}/complete', [WorkOrderController::class, 'complete'])->name('work-orders.complete');
Route::patch('/work-orders/{workOrder}/cancel', [WorkOrderController::class, 'cancel'])->name('work-orders.cancel');
Route::patch('/work-orders/{workOrder}/close', [WorkOrderController::class, 'close'])->name('work-orders.close');
Route::patch('/work-orders/{workOrder}/reopen', [WorkOrderController::class, 'reopen'])->name('work-orders.reopen');
// Add these routes in your web.php
Route::get('/work-orders/{id}/print', [WorkOrderController::class, 'print'])->name('work-orders.print');
Route::get('/work-orders/{id}/download', [WorkOrderController::class, 'download'])->name('work-orders.download');
