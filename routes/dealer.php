<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperDepotController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\DealerSecurityMoneyController;
use App\Http\Controllers\DealerPurchaseOrderController;
use App\Http\Controllers\DealerDeliveryController;

Route::resource('super-depots', SuperDepotController::class);
Route::resource('depots', DepotController::class);
Route::resource('dealers', DealerController::class);
Route::get('dealers/{dealer}/security-money',[DealerSecurityMoneyController::class, 'index'])->name('dealers.security-money.index');
Route::post('dealers/{dealer}/security-money',[DealerSecurityMoneyController::class, 'store'])->name('dealers.security-money.store');


Route::resource('dealer-purchase-orders',DealerPurchaseOrderController::class);
Route::resource('dealer-deliveries',DealerDeliveryController::class);

Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/submit',[DealerPurchaseOrderController::class, 'submit'])->name('dealer-purchase-orders.submit');
Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/approve',[DealerPurchaseOrderController::class, 'approve'])->name('dealer-purchase-orders.approve');
Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/reject',[DealerPurchaseOrderController::class, 'reject'])->name('dealer-purchase-orders.reject');
Route::post('dealer-purchase-orders/{dealerPurchaseOrder}/cancel',[DealerPurchaseOrderController::class, 'cancel'])->name('dealer-purchase-orders.cancel');
Route::get('dealer-deliveries/{dealerDelivery}/status',[DealerDeliveryController::class, 'statusForm'])->name('dealer-deliveries.status.form');
Route::put('dealer-deliveries/{dealerDelivery}/status',[DealerDeliveryController::class, 'updateStatus'])->name('dealer-deliveries.status.update');