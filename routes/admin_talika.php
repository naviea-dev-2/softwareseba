<?php

use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PackageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ThemeOptionsController;
use App\Http\Controllers\Auth\RegisterController;

 Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    //Theme Option
    Route::get('/theme-options', [ThemeOptionsController::class, 'getThemeOptionsPageLoad'])->name('backend.theme_options');
	Route::post('/saveThemeLogo', [ThemeOptionsController::class, 'saveThemeLogo'])->name('backend.saveThemeLogo');

    //Custom css
	Route::get('/custom-css', [ThemeOptionsController::class, 'getCustomCSSPageLoad'])->name('backend.custom-css');
	Route::post('/saveCustomCSS', [ThemeOptionsController::class, 'saveCustomCSS'])->name('backend.saveCustomCSS');

	//Free user Limit
	Route::get('/free_user_limit', [ThemeOptionsController::class, 'getUserLimit'])->name('backend.free_user_limit');
	Route::post('/free_user_limit', [ThemeOptionsController::class, 'userLimitSave'])->name('backend.free_user_limit');
	//Custom js
	Route::get('/custom-js', [ThemeOptionsController::class, 'getCustomJSPageLoad'])->name('backend.custom-js');
	Route::post('/saveCustomJS', [ThemeOptionsController::class, 'saveCustomJS'])->name('backend.saveCustomJS');

	//Theme Options Color
	Route::get('/theme-options-color', [ThemeOptionsController::class, 'getThemeOptionsColorPageLoad'])->name('backend.theme-options-color');
	Route::post('/saveThemeOptionsColor', [ThemeOptionsController::class, 'saveThemeOptionsColor'])->name('backend.saveThemeOptionsColor');

    //Theme Options SEO
	Route::get('/theme-options-seo', [ThemeOptionsController::class, 'getThemeOptionsSEOPageLoad'])->name('backend.theme-options-seo');
	Route::post('/saveThemeOptionsSEO', [ThemeOptionsController::class, 'saveThemeOptionsSEO'])->name('backend.saveThemeOptionsSEO');

	//Theme Options Facebook
	Route::get('/theme-options-facebook', [ThemeOptionsController::class, 'getThemeOptionsFacebookPageLoad'])->name('backend.theme-options-facebook');
	Route::post('/saveThemeOptionsFacebook', [ThemeOptionsController::class, 'saveThemeOptionsFacebook'])->name('backend.saveThemeOptionsFacebook');

	//Theme Options Facebook Pixel
	Route::get('/theme-options-facebook-pixel', [ThemeOptionsController::class, 'getThemeOptionsFacebookPixelLoad'])->name('backend.theme-options-facebook-pixel');
	Route::post('/saveThemeOptionsFacebookPixel', [ThemeOptionsController::class, 'saveThemeOptionsFacebookPixel'])->name('backend.saveThemeOptionsFacebookPixel');

	//Theme Options Twitter
	Route::get('/theme-options-twitter', [ThemeOptionsController::class, 'getThemeOptionsTwitterPageLoad'])->name('backend.theme-options-twitter');
	Route::post('/saveThemeOptionsTwitter', [ThemeOptionsController::class, 'saveThemeOptionsTwitter'])->name('backend.saveThemeOptionsTwitter');

	//Theme Options Google Analytics
	Route::get('/google-analytics', [ThemeOptionsController::class, 'getGoogleAnalytics'])->name('backend.google-analytics');
	Route::post('/saveGoogleAnalytics', [ThemeOptionsController::class, 'saveGoogleAnalytics'])->name('backend.saveGoogleAnalytics');

	//Theme Options Google Tag Manager
	Route::get('/google-tag-manager', [ThemeOptionsController::class, 'getGoogleTagManager'])->name('backend.google-tag-manager');
	Route::post('/saveGoogleTagManager', [ThemeOptionsController::class, 'saveGoogleTagManager'])->name('backend.saveGoogleTagManager');

	//Theme Options Whatsapp
	Route::get('/theme-options-whatsapp', [ThemeOptionsController::class, 'getThemeOptionsWhatsappPageLoad'])->name('backend.theme-options-whatsapp');
	Route::post('/saveThemeOptionsWhatsapp', [ThemeOptionsController::class, 'saveThemeOptionsWhatsapp'])->name('backend.saveThemeOptionsWhatsapp');

    //Social Media
	Route::get('/social-media', [ThemeOptionsController::class, 'getSocialMediaPageLoad'])->name('backend.social-media');
	Route::post('/saveSocialMediaData', [ThemeOptionsController::class, 'saveSocialMediaData'])->name('backend.saveSocialMediaData');


    //Mail Settings
	Route::get('/mail-settings', [ThemeOptionsController::class, 'loadMailSettingsPage'])->name('backend.mail-settings');
	Route::post('/saveMailSettings', [ThemeOptionsController::class, 'saveMailSettings'])->name('backend.saveMailSettings');

    //software serivce
    Route::get('service-list',[DashboardController::class,'serviceList'])->name('backend.service_list');
    Route::get('service-create',[DashboardController::class,'serviceCreate'])->name('backend.create_soft_service');
    Route::post('service-store',[DashboardController::class,'serviceStore'])->name('backend.store_soft_service');
    Route::get('service-edit/{id}',[DashboardController::class,'serviceEdit'])->name('backend.edit_soft_service');
    Route::post('service-edit/{id}',[DashboardController::class,'serviceUpdate'])->name('backend.update_soft_service');
    Route::get('service-delete/{id}',[DashboardController::class,'serviceDelete'])->name('backend.delete_soft_service');

    Route::get('logout',[RegisterController::class,'getAdminLogout'])->name('admin.logout');
    Route::get('dashboard', [DashboardController::class,'index'])->name('admin.dashboard');
    Route::get('site_setting', [DashboardController::class,'siteSetting'])->name('site_setting');
    Route::post('site_setting-post', [DashboardController::class,'setSiteSetting'])->name('backend.setting.update');
    //business
	Route::get('/select2-business',[BusinessController::class,'select2BusinessList'])->name('admin.select2.businesses');
    Route::get('all-business',[BusinessController::class,'index'])->name('admin.all_business');
	Route::post('business/ajax',[BusinessController::class,'ajaxBusiness'])->name('admin.business.ajax');
    Route::get('add-business',[BusinessController::class,'create'])->name('admin.add_business');
    Route::get('edit-business/{id}',[BusinessController::class,'edit'])->name('admin.edit_business');
    Route::post('add-business',[BusinessController::class,'store'])->name('admin.add_business_post');
    Route::post('edit-business/{id}',[BusinessController::class,'update'])->name('admin.edit_business_post');
    Route::get('delete-business/{id}',[BusinessController::class,'delete'])->name('admin.delete_business');
	Route::get('business/change_password/{id}',[BusinessController::class,'ChangePass'])->name('admin.business.change_password');
	Route::post('business/change_password/{id}',[BusinessController::class,'ChangePassPost'])->name('admin.business.change_password');
	//user
	Route::get('user/index',[UserController::class,'index'])->name('admin.user.index');
    Route::get('user/create',[UserController::class,'create'])->name('admin.user.add');
    Route::get('user/edit/{id}',[UserController::class,'edit'])->name('admin.user.edit');
    Route::post('user/create',[UserController::class,'store'])->name('admin.user.add');
    Route::post('user/edit/{id}',[UserController::class,'update'])->name('admin.user.edit');
    Route::post('user/ajax',[UserController::class,'ajaxUser'])->name('admin.user.ajax');
    Route::get('user/status/{id}',[UserController::class,'updateStatus'])->name('admin.user.status');
    Route::get('user/delete/{id}',[UserController::class,'delete'])->name('admin.user.delete');
    Route::get('user/change_password/{id}',[UserController::class,'ChangePass'])->name('admin.user.change_password');
    Route::get('user/profile/{id}',[UserController::class,'ChangePassP'])->name('admin.profile.change_password');
    Route::post('user/change_password/{id}',[UserController::class,'ChangePassPost'])->name('admin.user.change_password');

	//package 
	Route::get('package/index',[PackageController::class,'index'])->name('admin.package.index');
    Route::get('package/create',[PackageController::class,'create'])->name('admin.package.add');
    Route::get('package/edit/{id}',[PackageController::class,'edit'])->name('admin.package.edit');
    Route::get('package/delete/{id}',[PackageController::class,'delete'])->name('admin.package.delete');
    Route::post('package/create',[PackageController::class,'store'])->name('admin.package.add');
    Route::post('package/edit/{id}',[PackageController::class,'update'])->name('admin.package.edit');
    Route::post('package/ajax',[PackageController::class,'ajaxPackage'])->name('admin.package.ajax');
	Route::get('select2/package',[PackageController::class,'select2Package'])->name('select2.package');
	Route::get('package/add_new/{id}',[PackageController::class,'addNew'])->name('admin.package.add_pack');
	Route::get('package/get_end_date/{id}',[PackageController::class,'getEndDate'])->name('admin.package.get_end_date');
	Route::post('store-package-order/{id}',[PackageController::class,'packOrder'])->name('admin.package.order');
	Route::get('package-order/index',[PackageController::class,'indexOrder'])->name('admin.package.order.all');
	Route::post('package-order/ajax',[PackageController::class,'packageOrderAjax'])->name('admin.package-order.ajax');

	//Country
	Route::get('/country-list','App\Http\Controllers\Admin\CountryController@index')->name('admin.country.index');
	Route::post('/country-ajax','App\Http\Controllers\Admin\CountryController@ajaxCountry')->name('admin.country.ajax');
	Route::post('/country-store','App\Http\Controllers\Admin\CountryController@store')->name('admin.country.store');
	Route::get('/country-edit','App\Http\Controllers\Admin\CountryController@edit')->name('admin.country.edit');
	Route::get('/country-delete/{id}','App\Http\Controllers\Admin\CountryController@destroy')->name('admin.country.delete');
	Route::get('/select2/countries','App\Http\Controllers\Admin\CountryController@select2Countries')->name('admin.select2.countries');
	//State
	Route::get('/state-list','App\Http\Controllers\Admin\StateController@index')->name('admin.state.index');
	Route::post('/state-ajax','App\Http\Controllers\Admin\StateController@ajaxState')->name('admin.state.ajax');
	Route::post('/state-store','App\Http\Controllers\Admin\StateController@store')->name('admin.state.store');
	Route::get('/state-edit','App\Http\Controllers\Admin\StateController@edit')->name('admin.state.edit');
	Route::get('/state-delete/{id}','App\Http\Controllers\Admin\StateController@destroy')->name('admin.state.delete');
	Route::get('/select2/state-by-country','App\Http\Controllers\Admin\StateController@select2StateByCountry')->name('admin.select2.states.bycountry');
	//City
	Route::get('/city-list','App\Http\Controllers\Admin\CityController@index')->name('admin.city.index');
	Route::post('/city-ajax','App\Http\Controllers\Admin\CityController@ajaxCity')->name('admin.city.ajax');
	Route::post('/city-store','App\Http\Controllers\Admin\CityController@store')->name('admin.city.store');
	Route::get('/city-edit','App\Http\Controllers\Admin\CityController@edit')->name('admin.city.edit');
	Route::get('/city-delete/{id}','App\Http\Controllers\Admin\CityController@destroy')->name('admin.city.delete');
	Route::get('/select2/city-by-state','App\Http\Controllers\Admin\CityController@select2CitiesByState')->name('admin.select2.cities.byState');

	//currency
	Route::get('/currency-list','App\Http\Controllers\Admin\CurrencyController@index')->name('admin.currency.index');
	Route::post('/currency-ajax','App\Http\Controllers\Admin\CurrencyController@ajaxCategory')->name('admin.currency.ajax');
	Route::post('/currency-store','App\Http\Controllers\Admin\CurrencyController@store')->name('admin.currency.store');
	Route::get('/currency-edit','App\Http\Controllers\Admin\CurrencyController@edit')->name('admin.currency.edit');
	Route::get('/currency-delete/{id}','App\Http\Controllers\Admin\CurrencyController@destroy')->name('admin.currency.delete');
	Route::get('/select2-currency','App\Http\Controllers\Admin\CurrencyController@select2CurrencyList')->name('admin.select2.currency');
	//Tax
	Route::get('/tax-list','App\Http\Controllers\Admin\TaxController@index')->name('admin.tax.index');
	Route::post('/tax-ajax','App\Http\Controllers\Admin\TaxController@ajaxTax')->name('admin.tax.ajax');
	Route::post('/tax-store','App\Http\Controllers\Admin\TaxController@store')->name('admin.tax.store');
	Route::get('/tax-edit','App\Http\Controllers\Admin\TaxController@edit')->name('admin.tax.edit');
	Route::get('/tax-delete/{id}','App\Http\Controllers\Admin\TaxController@destroy')->name('admin.tax.delete');
});
