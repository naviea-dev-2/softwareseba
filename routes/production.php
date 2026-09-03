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
use App\Http\Controllers\Production\WorkerController;
use Illuminate\Support\Facades\Route;

Route::prefix('business')->middleware(['auth', "is_active", 'permission'])->group(function () {
    Route::prefix('production')->name('production.')->middleware(['auth'])->group(function () {
        Route::resource('workers', WorkerController::class);
        Route::resource('materials', RawMaterialController::class);
        Route::resource('material-stock', RawMaterialStockController::class);
        Route::resource('production-plans', ProductionPlanController::class);
        Route::resource('production-orders', ProductionOrderController::class);
        Route::resource('production-capacities', ProductionCapacityController::class);
        Route::resource('boms', BomController::class);
        Route::resource('machines', MachineController::class);
        Route::resource('machine-maintenances', MachineMaintenanceController::class);
        Route::resource('quality-inspections', QualityInspectionController::class);
        Route::resource('quality-reports', QualityReportController::class);
        Route::resource('production-history', ProductionHistoryController::class)->only(['index', 'show']);
    });
});
