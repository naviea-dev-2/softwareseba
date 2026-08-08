<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('data-queue-data/{b_id}/{m_id}', [App\Http\Controllers\Hr\DeviceIDMappingController::class,"getDataQueueData"]);
Route::post('data-queue-mark-done/{b_id}/{u_id}', [App\Http\Controllers\Hr\DeviceIDMappingController::class,"dataMarkDone"]);
Route::post('data-attenance-store/{b_id}', [App\Http\Controllers\Hr\EmployeeController::class,"apiStoreAtteandance"]);
Route::post('data-login-user', [App\Http\Controllers\Hr\EmployeeController::class,"apiLoginUser"]);

Route::post('/{b_id}/{m_id}/data-push', [App\Http\Controllers\Hr\DeviceIDMappingController::class, 'dataPushAttendance']); // device sends logs
Route::get('/{b_id}/{m_id}/getrequest', [App\Http\Controllers\Hr\DeviceIDMappingController::class, 'getUserRequest']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
