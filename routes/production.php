<?php

use App\Http\Controllers\Production\BomController;
use App\Http\Controllers\Production\MachineController;
use App\Http\Controllers\Production\MachineMaintenanceController;
use App\Http\Controllers\Production\ProductionCapacityController;
use App\Http\Controllers\Production\ProductionHistoryController;
use App\Http\Controllers\Production\ProductionOrderController;
use App\Http\Controllers\Production\ProductionPlanController;
use App\Http\Controllers\Production\QualityInspectionController;
use App\Http\Controllers\Production\QualityReportController;
use App\Http\Controllers\Production\RawMaterialController;
use App\Http\Controllers\Production\RawMaterialStockController;
use App\Http\Controllers\Production\WheatPurchaseController;
use App\Http\Controllers\Production\WorkerController;
use Illuminate\Support\Facades\Route;

Route::prefix('business')->middleware(['auth', "is_active", 'permission'])->group(function () {
    Route::prefix('production')->name('production.')->middleware(['auth'])->group(function () {
        Route::resource('workers', WorkerController::class);
        Route::resource('materials', RawMaterialController::class);
        Route::resource('material-stock', RawMaterialStockController::class);
        Route::resource('plans', ProductionPlanController::class);
        Route::resource('orders', ProductionOrderController::class);
        Route::resource('capacities', ProductionCapacityController::class);
        Route::resource('boms', BomController::class);
        Route::resource('machines', MachineController::class);
        Route::resource('machine-maintenances', MachineMaintenanceController::class);
        Route::resource('quality-inspections', QualityInspectionController::class);
        Route::resource('quality-reports', QualityReportController::class);
        Route::resource('history', ProductionHistoryController::class)->only(['index', 'show']);

        Route::get('wheat-purchases/report', [WheatPurchaseController::class, 'report'])->name('wheat-purchases.report');
        Route::get('wheat-purchases/export', [WheatPurchaseController::class, 'export'])->name('wheat-purchases.export');
        Route::resource('wheat-purchases', WheatPurchaseController::class);
        //Route::post('wheat-purchases/{wheat_purchase}/pay', [WheatPurchaseController::class, 'pay'])->name('wheat-purchases.pay');

        Route::get('wheat-purchase/supplier-payments', [WheatPurchaseController::class, 'supplierPayments'])->name('wheat-purchases.supplier-payments.index');
        Route::get('wheat-purchase/supplier-payments/{supplier}', [WheatPurchaseController::class, 'supplierPaymentsShow'])->name('wheat-purchases.supplier-payments.show');
        Route::post('wheat-purchase/supplier-payments/{supplier}/pay', [WheatPurchaseController::class, 'supplierPaymentsPay'])->name('wheat-purchases.supplier-payments.pay');
        Route::get('wheat-purchase/supplier-payments-export', [WheatPurchaseController::class, 'supplierPaymentsExport'])->name('wheat-purchases.supplier-payments.export');
    });
});
