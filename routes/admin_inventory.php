<?php
use Illuminate\Support\Facades\Route;
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    //Product
    Route::get('/product-list','App\Http\Controllers\Admin\Inventory\ProductController@index')->name('admin.product.index');
    Route::post('/ajax-products','App\Http\Controllers\Admin\Inventory\ProductController@ajaxProduct')->name('admin.ajax.products');
    Route::get('/product-create','App\Http\Controllers\Admin\Inventory\ProductController@create')->name('admin.product.create');
    Route::post('/product-ajax','App\Http\Controllers\Admin\Inventory\ProductController@ajaxProduct')->name('admin.product.ajax');
    Route::post('/product-store','App\Http\Controllers\Admin\Inventory\ProductController@store')->name('admin.product.store');
    Route::post('/product-update','App\Http\Controllers\Admin\Inventory\ProductController@update')->name('admin.product.update');
    Route::get('/product-edit/{id}','App\Http\Controllers\Admin\Inventory\ProductController@edit')->name('admin.product.edit');
    Route::get('/product-delete/{id}','App\Http\Controllers\Admin\Inventory\ProductController@destroy')->name('admin.product.delete');
    Route::get('/select2/products/by_category','App\Http\Controllers\Admin\Inventory\ProductController@select2ProductbyCat')->name('admin.select2.products.by_category');
    Route::get('/product-details-by-id','App\Http\Controllers\Admin\Inventory\ProductController@productDetailsbyId')->name('admin.get_product_details_by_id');

    Route::get("import/products",[App\Http\Controllers\Admin\Inventory\BulkImportController::class,"index"])->name("admin.import.product");
    Route::post("import/products",[App\Http\Controllers\Admin\Inventory\BulkImportController::class,"postImport"])->name("admin.product.import");
    Route::post("import/downaload",[App\Http\Controllers\Admin\Inventory\BulkImportController::class,"downloadTemplate"])->name("admin.import_download_template");
    
    //category
    Route::get('/category-list','App\Http\Controllers\Admin\Inventory\CategoryController@index')->name('admin.category.index');
    Route::post('/category-ajax','App\Http\Controllers\Admin\Inventory\CategoryController@ajaxCategory')->name('admin.category.ajax');
    Route::post('/category-store','App\Http\Controllers\Admin\Inventory\CategoryController@store')->name('admin.category.store');
    Route::get('/category-edit','App\Http\Controllers\Admin\Inventory\CategoryController@edit')->name('admin.category.edit');
    Route::get('/category-delete/{id}','App\Http\Controllers\Admin\Inventory\CategoryController@destroy')->name('admin.category.delete');
    Route::get('/select2-product-categories','App\Http\Controllers\Admin\Inventory\CategoryController@select2CategoryList')->name('admin.select2.product.categories');
    
    //Attribute
    Route::get('/attributes-list','App\Http\Controllers\Admin\Inventory\AttributesController@getAttributesPageLoad')->name('admin.attributes.index');
    Route::post('/attributes-ajax','App\Http\Controllers\Admin\Inventory\AttributesController@ajaxAttribute')->name('admin.attributes.ajax');
    Route::get('/getNewAttributeData', [App\Http\Controllers\Admin\Inventory\AttributesController::class, 'getNewAttributeData'])->name('admin.attributes.getNewAttributeData');
    Route::get('/getAttributesById', [App\Http\Controllers\Admin\Inventory\AttributesController::class, 'getAttributesById'])->name('admin.attributes.getAttributesById');
    Route::post('/saveAttributesData', [App\Http\Controllers\Admin\Inventory\AttributesController::class, 'saveAttributesData'])->name('admin.attributes.saveAttributesData');
    Route::get('/deleteAttributes', [App\Http\Controllers\Admin\Inventory\AttributesController::class, 'deleteAttributes'])->name('admin.attributes.deleteAttributes');

    //product variant
    Route::get('/addNewProductAttribute', [App\Http\Controllers\Admin\Inventory\ProductVariationController::class, 'addNewProductAttribute'])->name('admin.product.addNewProductAttribute');
    Route::get('/getAttributeValue', [App\Http\Controllers\Admin\Inventory\ProductVariationController::class, 'getAttributeValue'])->name('admin.product.getAttributeValue');
    Route::post('/saveNewVariationsData', [App\Http\Controllers\Admin\Inventory\ProductVariationController::class, 'saveNewVariationsData'])->name('admin.product.saveNewVariationsData');
    Route::get('/getVariationData', [App\Http\Controllers\Admin\Inventory\ProductVariationController::class, 'getVariationData'])->name('admin.product.getVariationData');
    Route::post('/saveEditVariationsData', [App\Http\Controllers\Admin\Inventory\ProductVariationController::class, 'saveEditVariationsData'])->name('admin.product.saveEditVariationsData');
    
    //Generic
    Route::get('/generic-list','App\Http\Controllers\Admin\Inventory\GenericController@index')->name('admin.generic.index');
    Route::post('/generic-ajax','App\Http\Controllers\Admin\Inventory\GenericController@ajaxGeneric')->name('admin.generic.ajax');
    Route::post('/generic-store','App\Http\Controllers\Admin\Inventory\GenericController@store')->name('admin.generic.store');
    Route::get('/generic-edit','App\Http\Controllers\Admin\Inventory\GenericController@edit')->name('admin.generic.edit');
    Route::get('/generic-delete/{id}','App\Http\Controllers\Admin\Inventory\GenericController@destroy')->name('admin.generic.delete');
    Route::get('/select2-generic','App\Http\Controllers\Admin\Inventory\GenericController@select2GenericList')->name('admin.select2.generics');
    
    //Product Type
    Route::get('/p_type-list','App\Http\Controllers\Admin\Inventory\ProductTypeController@index')->name('admin.p_type.index');
    Route::post('/p_type-ajax','App\Http\Controllers\Admin\Inventory\ProductTypeController@ajaxPType')->name('admin.p_type.ajax');
    Route::post('/p_type-store','App\Http\Controllers\Admin\Inventory\ProductTypeController@store')->name('admin.p_type.store');
    Route::get('/p_type-edit','App\Http\Controllers\Admin\Inventory\ProductTypeController@edit')->name('admin.p_type.edit');
    Route::get('/p_type-delete/{id}','App\Http\Controllers\Admin\Inventory\ProductTypeController@destroy')->name('admin.p_type.delete');
    Route::get('/select2-p_type','App\Http\Controllers\Admin\Inventory\ProductTypeController@select2PTypeList')->name('admin.select2.p_types');

    //Brand

    Route::get('/brand-list','App\Http\Controllers\Admin\Inventory\BrandController@index')->name('admin.brand.index');
    Route::post('/brand-ajax','App\Http\Controllers\Admin\Inventory\BrandController@ajaxBrand')->name('admin.brand.ajax');
    Route::post('/brand-store','App\Http\Controllers\Admin\Inventory\BrandController@store')->name('admin.brand.store');
    Route::get('/brand-edit','App\Http\Controllers\Admin\Inventory\BrandController@edit')->name('admin.brand.edit');
    Route::get('/brand-delete/{id}','App\Http\Controllers\Admin\Inventory\BrandController@destroy')->name('admin.brand.delete');
    Route::get('/select2-product-brands','App\Http\Controllers\Admin\Inventory\BrandController@select2BrandList')->name('admin.select2.product.brands');
    //Unit
    Route::get('/unit-list','App\Http\Controllers\Admin\Inventory\UnitController@index')->name('admin.unit.index');
    Route::post('/unit-ajax','App\Http\Controllers\Admin\Inventory\UnitController@ajaxUnit')->name('admin.unit.ajax');
    Route::post('/unit-store','App\Http\Controllers\Admin\Inventory\UnitController@store')->name('admin.unit.store');
    Route::get('/unit-edit','App\Http\Controllers\Admin\Inventory\UnitController@edit')->name('admin.unit.edit');
    Route::get('/unit-delete/{id}','App\Http\Controllers\Admin\Inventory\UnitController@destroy')->name('admin.unit.delete');
    Route::get('select2/category/units','App\Http\Controllers\Admin\Inventory\UnitController@select2unit')->name('admin.select2.category.units');
    Route::get('select2/product/units','App\Http\Controllers\Admin\Inventory\UnitController@select2unitProductBy')->name('admin.select2.product.units');
    

    //Manufacture
    Route::get('/manufacture-list','App\Http\Controllers\Admin\Inventory\ManufatureController@index')->name('admin.manufacture.index');
    Route::post('/manufacture-ajax','App\Http\Controllers\Admin\Inventory\ManufatureController@ajaxManufacture')->name('admin.manufacture.ajax');
    Route::post('/manufacture-store','App\Http\Controllers\Admin\Inventory\ManufatureController@store')->name('admin.manufacture.store');
    Route::get('/manufacture-edit','App\Http\Controllers\Admin\Inventory\ManufatureController@edit')->name('admin.manufacture.edit');
    Route::get('/manufacture-delete/{id}','App\Http\Controllers\Admin\Inventory\ManufatureController@destroy')->name('admin.manufacture.delete');
    Route::get('/select2-manufacture','App\Http\Controllers\Admin\Inventory\ManufatureController@select2ManufaturerList')->name('admin.select2.manufactures');


});