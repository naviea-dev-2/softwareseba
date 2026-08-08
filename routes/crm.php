<?php
use Illuminate\Support\Facades\Route;

Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    //lead
    Route::get('/lead-list','App\Http\Controllers\CRM\leadController@index')->name('lead.index');
    Route::get('/lead-create','App\Http\Controllers\CRM\leadController@create')->name('lead.create');
    Route::post('/lead-ajax','App\Http\Controllers\CRM\leadController@ajaxLead')->name('lead.ajax');
    Route::post('/lead-store','App\Http\Controllers\CRM\leadController@store')->name('lead.store');
    Route::post('/lead-update','App\Http\Controllers\CRM\leadController@update')->name('lead.update');
    Route::get('/lead-edit/{id}','App\Http\Controllers\CRM\leadController@edit')->name('lead.edit');
    Route::get('/lead-delete/{id}','App\Http\Controllers\CRM\leadController@destroy')->name('lead.delete');
});