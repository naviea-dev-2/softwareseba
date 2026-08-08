<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\SiteOptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
    //site_option
    Route::get('business_setting',[BusinessController::class,'getSetting'])->name('business_setting');
    Route::post('business_setting/{id}',[BusinessController::class,'setSetting'])->name('post.business_setting');
    Route::get('/theme-options-color', [SiteOptionController::class, 'getThemeOptionsColorPageLoad'])->name('theme-options-color');
    Route::post('/saveThemeOptionsColor', [SiteOptionController::class, 'saveThemeOptionsColor'])->name('saveThemeOptionsColor');

    
    Route::prefix('business')->group(function () {
        //User 
        Route::get('user/index',[UserController::class,'index'])->name('bussiness.user.index');
        Route::get('user/create',[UserController::class,'create'])->name('bussiness.user.add');
        Route::get('user/edit/{id}',[UserController::class,'edit'])->name('bussiness.user.edit');
        Route::post('user/create',[UserController::class,'store'])->name('bussiness.user.add');
        Route::post('user/edit/{id}',[UserController::class,'update'])->name('bussiness.user.edit');
        Route::post('user/ajax',[UserController::class,'ajaxUser'])->name('bussiness.user.ajax');
        Route::get('user/status/{id}',[UserController::class,'updateStatus'])->name('bussiness.user.status');
        Route::get('user/delete/{id}',[UserController::class,'delete'])->name('bussiness.user.delete');
        Route::get('user/change_password/{id}',[UserController::class,'ChangePass'])->name('bussiness.user.change_password');
        Route::get('user/profile/{id}',[UserController::class,'ChangePassP'])->name('bussiness.profile.change_password');
        Route::post('user/change_password/{id}',[UserController::class,'ChangePassPost'])->name('bussiness.user.change_password');
        //Role
        Route::get('role/index',[RoleController::class,'index'])->name('bussiness.role.index');
        Route::get('role/create',[RoleController::class,'create'])->name('bussiness.role.add');
        Route::get('role/edit/{id}',[RoleController::class,'edit'])->name('bussiness.role.edit');
        Route::get('role/delete/{id}',[RoleController::class,'delete'])->name('bussiness.role.delete');
        Route::post('role/create',[RoleController::class,'store'])->name('bussiness.role.add');
        Route::post('role/edit/{id}',[RoleController::class,'update'])->name('bussiness.role.edit');
        Route::post('role/ajax',[RoleController::class,'ajaxRole'])->name('bussiness.role.ajax');
       
    });
});