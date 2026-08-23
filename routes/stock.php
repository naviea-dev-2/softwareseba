<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Stock\WarehouseController;

Route::prefix('stock')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('stock.index');
    Route::get('/dashboard', [StockController::class, 'dashboard'])->name('stock.dashboard');

    Route::get('/ledger', [StockController::class, 'ledger'])->name('stock.ledger');
    Route::get('/ledger/{productId}/{warehouseId}/history', [StockController::class, 'productHistory'])->name('stock.history');

    Route::get('/adjust', [StockController::class, 'adjustmentForm'])->name('stock.adjust');
    Route::post('/adjust', [StockController::class, 'adjustStock'])->name('stock.adjust.store');

    Route::get('/reservations', [StockController::class, 'reservations'])->name('stock.reservations');
    Route::get('/reservations/create', [StockController::class, 'createReservation'])->name('stock.reservations.create');
    Route::post('/reservations', [StockController::class, 'storeReservation'])->name('stock.reservations.store');
    Route::post('/reservations/{id}/fulfill', [StockController::class, 'fulfillReservation'])->name('stock.reservations.fulfill');
    Route::post('/reservations/{id}/cancel', [StockController::class, 'cancelReservation'])->name('stock.reservations.cancel');

    Route::get('/movements', [StockController::class, 'movements'])->name('stock.movements');

    Route::get('/analytics', [StockController::class, 'analytics'])->name('stock.analytics');

    Route::get('/transfers', [StockController::class, 'transfers'])->name('stock.transfers');
    Route::get('/transfers/create', [StockController::class, 'createTransfer'])->name('stock.transfers.create');
    Route::post('/transfers', [StockController::class, 'storeTransfer'])->name('stock.transfers.store');
    Route::post('/transfers/{id}/approve', [StockController::class, 'approveTransfer'])->name('stock.transfers.approve');
    Route::post('/transfers/{id}/ship', [StockController::class, 'shipTransfer'])->name('stock.transfers.ship');
    Route::get('/transfers/{id}/receive', [StockController::class, 'receiveForm'])->name('stock.transfers.receive.form');
    Route::post('/transfers/{id}/receive', [StockController::class, 'receiveTransfer'])->name('stock.transfers.receive');

    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('stock.warehouses.index');
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('stock.warehouses.create');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('stock.warehouses.store');
    Route::get('/warehouses/{id}', [WarehouseController::class, 'show'])->name('stock.warehouses.show');
    Route::get('/warehouses/{id}/edit', [WarehouseController::class, 'edit'])->name('stock.warehouses.edit');
    Route::put('/warehouses/{id}', [WarehouseController::class, 'update'])->name('stock.warehouses.update');
    Route::delete('/warehouses/{id}', [WarehouseController::class, 'destroy'])->name('stock.warehouses.destroy');
    Route::patch('/warehouses/{id}/toggle-status', [WarehouseController::class, 'toggleStatus'])->name('stock.warehouses.toggle');
    Route::get('/warehouses-distribution', [WarehouseController::class, 'distribution'])->name('stock.warehouses.distribution');
});