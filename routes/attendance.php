<?php

use App\Http\Controllers\AttendanceImportController;
Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    Route::get(
        '/attendance/import',
        [AttendanceImportController::class, 'index']
    )->name('attendance.import');

    Route::post(
        '/attendance/import',
        [AttendanceImportController::class, 'store']
    )->name('attendance.import.store');

    Route::get(
        '/attendance/import/template',
        [AttendanceImportController::class, 'template']
    )->name('attendance.import.template');

    Route::get(
        '/attendance/import/{id}/errors',
        [AttendanceImportController::class, 'showErrors']
    )->name('attendance.import.errors');
});