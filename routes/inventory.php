<?php
use Illuminate\Support\Facades\Route;


Route::prefix('business')->middleware(['auth',"is_active",'permission'])->group(function () {
// Route::get('/dashboard-inventory','App\Http\Controllers\superAdmin\basicController@inventory')->name('inventory');
//branch
Route::get('/branch-list','App\Http\Controllers\Inventory\BranchController@index')->name('branch.index');
Route::post('/branch-ajax','App\Http\Controllers\Inventory\BranchController@ajaxBranch')->name('branch.ajax');
Route::post('/branch-store','App\Http\Controllers\Inventory\BranchController@store')->name('branch.store');
Route::get('/branch-delete/{id}','App\Http\Controllers\Inventory\BranchController@destroy')->name('branch.delete');
Route::get('/branch-edit','App\Http\Controllers\Inventory\BranchController@edit')->name('branch.edit');
Route::get('/select2-branches','App\Http\Controllers\Inventory\BranchController@select2BranchList')->name('select2.branches');
//currency
Route::get('/currency-list','App\Http\Controllers\Inventory\CurrencyController@index')->name('currency.index');
Route::post('/currency-ajax','App\Http\Controllers\Inventory\CurrencyController@ajaxCategory')->name('currency.ajax');
Route::post('/currency-store','App\Http\Controllers\Inventory\CurrencyController@store')->name('currency.store');
Route::get('/currency-edit','App\Http\Controllers\Inventory\CurrencyController@edit')->name('currency.edit');
Route::get('/currency-delete/{id}','App\Http\Controllers\Inventory\CurrencyController@destroy')->name('currency.delete');
Route::get('/select2-currency','App\Http\Controllers\Inventory\CurrencyController@select2CurrencyList')->name('select2.currency');

//category
Route::get('/category-list','App\Http\Controllers\Inventory\CategoryController@index')->name('category.index');
Route::post('/category-ajax','App\Http\Controllers\Inventory\CategoryController@ajaxCategory')->name('category.ajax');
Route::post('/category-store','App\Http\Controllers\Inventory\CategoryController@store')->name('category.store');
Route::get('/category-edit','App\Http\Controllers\Inventory\CategoryController@edit')->name('category.edit');
Route::get('/category-delete/{id}','App\Http\Controllers\Inventory\CategoryController@destroy')->name('category.delete');
Route::get('/select2-product-categories','App\Http\Controllers\Inventory\CategoryController@select2CategoryList')->name('select2.product.categories');
//Attribute
Route::get('/attributes-list','App\Http\Controllers\Inventory\AttributesController@getAttributesPageLoad')->name('attributes.index');
Route::get('/getNewAttributeData', [App\Http\Controllers\Inventory\AttributesController::class, 'getNewAttributeData'])->name('attributes.getNewAttributeData');
Route::get('/getAttributesById', [App\Http\Controllers\Inventory\AttributesController::class, 'getAttributesById'])->name('attributes.getAttributesById');
Route::post('/saveAttributesData', [App\Http\Controllers\Inventory\AttributesController::class, 'saveAttributesData'])->name('attributes.saveAttributesData');
Route::get('/deleteAttributes', [App\Http\Controllers\Inventory\AttributesController::class, 'deleteAttributes'])->name('attributes.deleteAttributes');

//product variant

Route::get('/addNewProductAttribute', [App\Http\Controllers\Inventory\ProductVariationController::class, 'addNewProductAttribute'])->name('product.addNewProductAttribute');
Route::get('/getAttributeValue', [App\Http\Controllers\Inventory\ProductVariationController::class, 'getAttributeValue'])->name('product.getAttributeValue');

Route::post('/saveNewVariationsData', [App\Http\Controllers\Inventory\ProductVariationController::class, 'saveNewVariationsData'])->name('product.saveNewVariationsData');

 Route::get('/getVariationData', [App\Http\Controllers\Inventory\ProductVariationController::class, 'getVariationData'])->name('product.getVariationData');
Route::post('/saveEditVariationsData', [App\Http\Controllers\Inventory\ProductVariationController::class, 'saveEditVariationsData'])->name('product.saveEditVariationsData');
Route::post('/deleteVariation', [App\Http\Controllers\Inventory\ProductVariationController::class, 'deleteVariation'])->name('product.deleteVariation');
Route::post('/editProdeuctAttribute', [App\Http\Controllers\Inventory\ProductVariationController::class, 'editProdeuctAttribute'])->name('product.editProdeuctAttribute');
//territory
Route::get('/territory-list','App\Http\Controllers\Inventory\TerritoryController@index')->name('territory.index');
Route::post('/territory-ajax','App\Http\Controllers\Inventory\TerritoryController@ajaxTeritory')->name('territory.ajax');
Route::post('/territory-store','App\Http\Controllers\Inventory\TerritoryController@store')->name('territory.store');
Route::get('/territory-edit','App\Http\Controllers\Inventory\TerritoryController@edit')->name('territory.edit');
Route::get('/territory-delete/{id}','App\Http\Controllers\Inventory\TerritoryController@destroy')->name('territory.delete');
Route::get('/select2-territory','App\Http\Controllers\Inventory\TerritoryController@select2TerritoryList')->name('select2.territories');
//road
Route::get('/road-list','App\Http\Controllers\Inventory\RoadController@index')->name('road.index');
Route::post('/road-ajax','App\Http\Controllers\Inventory\RoadController@ajaxRoad')->name('road.ajax');
Route::post('/road-store','App\Http\Controllers\Inventory\RoadController@store')->name('road.store');
Route::get('/road-edit','App\Http\Controllers\Inventory\RoadController@edit')->name('road.edit');
Route::get('/road-delete/{id}','App\Http\Controllers\Inventory\RoadController@destroy')->name('road.delete');
Route::get('/select2-road','App\Http\Controllers\Inventory\RoadController@select2RoadList')->name('select2.roads');
//Generic
Route::get('/generic-list','App\Http\Controllers\Inventory\GenericController@index')->name('generic.index');
Route::post('/generic-ajax','App\Http\Controllers\Inventory\GenericController@ajaxGeneric')->name('generic.ajax');
Route::post('/generic-store','App\Http\Controllers\Inventory\GenericController@store')->name('generic.store');
Route::get('/generic-edit','App\Http\Controllers\Inventory\GenericController@edit')->name('generic.edit');
Route::get('/generic-delete/{id}','App\Http\Controllers\Inventory\GenericController@destroy')->name('generic.delete');
Route::get('/select2-generic','App\Http\Controllers\Inventory\GenericController@select2GenericList')->name('select2.generics');
//Product Type
Route::get('/p_type-list','App\Http\Controllers\Inventory\ProductTypeController@index')->name('p_type.index');
Route::post('/p_type-ajax','App\Http\Controllers\Inventory\ProductTypeController@ajaxPType')->name('p_type.ajax');
Route::post('/p_type-store','App\Http\Controllers\Inventory\ProductTypeController@store')->name('p_type.store');
Route::get('/p_type-edit','App\Http\Controllers\Inventory\ProductTypeController@edit')->name('p_type.edit');
Route::get('/p_type-delete/{id}','App\Http\Controllers\Inventory\ProductTypeController@destroy')->name('p_type.delete');
Route::get('/select2-p_type','App\Http\Controllers\Inventory\ProductTypeController@select2PTypeList')->name('select2.p_types');
//Color
Route::get('/manufacture-list','App\Http\Controllers\Inventory\ManufatureController@index')->name('manufacture.index');
Route::post('/manufacture-ajax','App\Http\Controllers\Inventory\ManufatureController@ajaxManufacture')->name('manufacture.ajax');
Route::post('/manufacture-store','App\Http\Controllers\Inventory\ManufatureController@store')->name('manufacture.store');
Route::get('/manufacture-edit','App\Http\Controllers\Inventory\ManufatureController@edit')->name('manufacture.edit');
Route::get('/manufacture-delete/{id}','App\Http\Controllers\Inventory\ManufatureController@destroy')->name('manufacture.delete');
Route::get('/select2-manufacture','App\Http\Controllers\Inventory\ManufatureController@select2ManufaturerList')->name('select2.manufactures');
//Size
Route::get('/size-list','App\Http\Controllers\Inventory\SizeController@index')->name('size.index');
Route::post('/size-ajax','App\Http\Controllers\Inventory\SizeController@ajaxSize')->name('size.ajax');
Route::post('/size-store','App\Http\Controllers\Inventory\SizeController@store')->name('size.store');
Route::get('/size-edit','App\Http\Controllers\Inventory\SizeController@edit')->name('size.edit');
Route::get('/size-delete/{id}','App\Http\Controllers\Inventory\SizeController@destroy')->name('size.delete');
Route::get('/select2-size','App\Http\Controllers\Inventory\SizeController@select2SizeList')->name('select2.size');
//Brand
Route::get('/brand-list','App\Http\Controllers\Inventory\BrandController@index')->name('brand.index');
Route::post('/brand-ajax','App\Http\Controllers\Inventory\BrandController@ajaxBrand')->name('brand.ajax');
Route::post('/brand-store','App\Http\Controllers\Inventory\BrandController@store')->name('brand.store');
Route::get('/brand-edit','App\Http\Controllers\Inventory\BrandController@edit')->name('brand.edit');
Route::get('/brand-delete/{id}','App\Http\Controllers\Inventory\BrandController@destroy')->name('brand.delete');
Route::get('/select2-product-brands','App\Http\Controllers\Inventory\BrandController@select2BrandList')->name('select2.product.brands');
//Unit
Route::get('/unit-list','App\Http\Controllers\Inventory\UnitController@index')->name('unit.index');
Route::post('/unit-ajax','App\Http\Controllers\Inventory\UnitController@ajaxUnit')->name('unit.ajax');
Route::post('/unit-store','App\Http\Controllers\Inventory\UnitController@store')->name('unit.store');
Route::get('/unit-edit','App\Http\Controllers\Inventory\UnitController@edit')->name('unit.edit');
Route::get('/unit-delete/{id}','App\Http\Controllers\Inventory\UnitController@destroy')->name('unit.delete');
Route::get('select2/category/units','App\Http\Controllers\Inventory\UnitController@select2unit')->name('select2.category.units');
Route::get('select2/product/units','App\Http\Controllers\Inventory\UnitController@select2unitProductBy')->name('select2.product.units');
//Tax
Route::get('/tax-list','App\Http\Controllers\Inventory\TaxController@index')->name('tax.index');
Route::post('/tax-ajax','App\Http\Controllers\Inventory\TaxController@ajaxTax')->name('tax.ajax');
Route::post('/tax-store','App\Http\Controllers\Inventory\TaxController@store')->name('tax.store');
Route::get('/tax-edit','App\Http\Controllers\Inventory\TaxController@edit')->name('tax.edit');
Route::get('/tax-delete/{id}','App\Http\Controllers\Inventory\TaxController@destroy')->name('tax.delete');

//plot land
Route::get('/user/land-plot-list','App\Http\Controllers\RealState\PropertyController@indexUser')->name('property.index_user');
Route::post('/user/land-plot-ajax','App\Http\Controllers\RealState\PropertyController@ajaxPropertyUser')->name('property.ajax_user');
Route::get('/land-plot-list','App\Http\Controllers\RealState\PropertyController@index')->name('property.index');
Route::post('/land-plot-ajax','App\Http\Controllers\RealState\PropertyController@ajaxProperty')->name('property.ajax');
Route::get('/land-plot-create','App\Http\Controllers\RealState\PropertyController@create')->name('property.create');
Route::post('/land-plot-store','App\Http\Controllers\RealState\PropertyController@store')->name('property.store');
Route::get('/land-plot-edit/{id}','App\Http\Controllers\RealState\PropertyController@edit')->name('property.edit');
Route::post('/land-plot-update/{id}','App\Http\Controllers\RealState\PropertyController@update')->name('property.update');
Route::get('/land-plot-delete/{id}','App\Http\Controllers\RealState\PropertyController@destroy')->name('property.delete');
Route::get('/select2-land-plot','App\Http\Controllers\RealState\PropertyController@select2PropertyList')->name('select2.property');

//member
Route::get('/member-list','App\Http\Controllers\RealState\MemberController@index')->name('member.index');
Route::post('/member-ajax','App\Http\Controllers\RealState\MemberController@ajaxMember')->name('member.ajax');
Route::get('/member-create','App\Http\Controllers\RealState\MemberController@create')->name('member.create');
Route::post('/member-store','App\Http\Controllers\RealState\MemberController@store')->name('member.store');
Route::get('/member-edit/{id}','App\Http\Controllers\RealState\MemberController@edit')->name('member.edit');
Route::post('/member-update/{id}','App\Http\Controllers\RealState\MemberController@update')->name('member.update');
Route::get('/member-delete/{id}','App\Http\Controllers\RealState\MemberController@destroy')->name('member.delete');
Route::get('/select2-member','App\Http\Controllers\RealState\MemberController@select2MemberList')->name('select2.member');
Route::get('/user-member-edit/{id}','App\Http\Controllers\RealState\MemberController@editMember')->name('user.member.edit');
Route::post('/user-member-update/{id}','App\Http\Controllers\RealState\MemberController@updateMember')->name('user.member.update');

//member type
Route::get('/member-type-list','App\Http\Controllers\RealState\MemberTypeController@index')->name('member_type.index');
Route::post('/member-type-ajax','App\Http\Controllers\RealState\MemberTypeController@ajaxAmenity')->name('member_type.ajax');
Route::post('/member-type-store','App\Http\Controllers\RealState\MemberTypeController@store')->name('member_type.store');
Route::get('/member-type-edit','App\Http\Controllers\RealState\MemberTypeController@edit')->name('member_type.edit');
Route::get('/member-type-delete/{id}','App\Http\Controllers\RealState\MemberTypeController@destroy')->name('member_type.delete');
Route::get('/select2-member-type','App\Http\Controllers\RealState\MemberTypeController@select2MemberTypeList')->name('select2.member_type');

//deposit payment
Route::get('/deposit-list','App\Http\Controllers\RealState\DepositController@index')->name('deposit.index');
Route::post('/deposit-ajax','App\Http\Controllers\RealState\DepositController@ajaxDepositPayment')->name('deposit.ajax');
Route::get('/deposit-list-export','App\Http\Controllers\RealState\DepositController@export')->name('deposit.list.export');
Route::get('/deposit-create','App\Http\Controllers\RealState\DepositController@create')->name('deposit.create');
Route::post('/deposit-store','App\Http\Controllers\RealState\DepositController@store')->name('deposit.store');
Route::get('/deposit-delete/{id}','App\Http\Controllers\RealState\DepositController@destroy')->name('deposit.delete');

//online payment 
Route::get('/online-payment-setting','App\Http\Controllers\RealState\OnlinePaymentSettingConttroller@index')->name('online_payemnt.index');
Route::post('/online-payment-setting/store','App\Http\Controllers\RealState\OnlinePaymentSettingConttroller@store')->name('online_payemnt.store');


//user deposit payment
Route::get('/user/deposit-list','App\Http\Controllers\RealState\UserDepositController@index')->name('user_deposit.index');
Route::post('/user/deposit-ajax','App\Http\Controllers\RealState\UserDepositController@ajaxDepositPayment')->name('user_deposit.ajax');
Route::get('/user/deposit-list-export','App\Http\Controllers\RealState\UserDepositController@export')->name('user_deposit.list.export');

Route::get('/user/deposit-create','App\Http\Controllers\RealState\UserDepositController@create')->name('user_deposit.create');
Route::post('/user/deposit-store','App\Http\Controllers\RealState\UserDepositController@store')->name('user_deposit.store');
Route::get('/user-deposit-delete/{id}','App\Http\Controllers\RealState\UserDepositController@destroy')->name('user_deposit.delete');

Route::post('/user/deposit/success', [App\Http\Controllers\RealState\UserDepositController::class, 'success'])->name('user_deposit.success');
Route::post('/user/deposit/fail', [App\Http\Controllers\RealState\UserDepositController::class, 'fail'])->name('user_deposit.fail');
Route::post('/user/deposit/cancel', [App\Http\Controllers\RealState\UserDepositController::class, 'cancel'])->name('user_deposit.cancel');
Route::post('/user/deposit/pay-via-ajax', [App\Http\Controllers\RealState\UserDepositController::class, 'payViaAjax'])->name('user_deposit.pay-via-ajax');


//property Amenity
Route::get('/property-amenity-list','App\Http\Controllers\RealState\PropertyAmenityController@index')->name('property_amenity.index');
Route::post('/property-amenity-ajax','App\Http\Controllers\RealState\PropertyAmenityController@ajaxAmenity')->name('property_amenity.ajax');
Route::post('/property-amenity-store','App\Http\Controllers\RealState\PropertyAmenityController@store')->name('property_amenity.store');
Route::get('/property-amenity-edit','App\Http\Controllers\RealState\PropertyAmenityController@edit')->name('property_amenity.edit');
Route::get('/property-amenity-delete/{id}','App\Http\Controllers\RealState\PropertyAmenityController@destroy')->name('property_amenity.delete');
Route::get('/select2-amenity-amenities','App\Http\Controllers\RealState\PropertyAmenityController@select2AmenityList')->name('select2.property_amenity');

//property Advantage
Route::get('/property-advantage-list','App\Http\Controllers\RealState\PropertyAdvantageController@index')->name('property_advantage.index');
Route::post('/property-advantage-ajax','App\Http\Controllers\RealState\PropertyAdvantageController@ajaxAmenity')->name('property_advantage.ajax');
Route::post('/property-advantage-store','App\Http\Controllers\RealState\PropertyAdvantageController@store')->name('property_advantage.store');
Route::get('/property-advantage-edit','App\Http\Controllers\RealState\PropertyAdvantageController@edit')->name('property_advantage.edit');
Route::get('/property-advantage-delete/{id}','App\Http\Controllers\RealState\PropertyAdvantageController@destroy')->name('property_advantage.delete');
Route::get('/select2-property-advantage','App\Http\Controllers\RealState\PropertyAdvantageController@select2AdvantageList')->name('select2.property_advantage');

//Product
Route::get('/product-list','App\Http\Controllers\Inventory\ProductController@index')->name('product.index');
Route::post('/ajax-products','App\Http\Controllers\Inventory\ProductController@ajaxProduct')->name('ajax.products');

Route::get('/product-create','App\Http\Controllers\Inventory\ProductController@create')->name('product.create');
Route::post('/product-ajax','App\Http\Controllers\Inventory\ProductController@ajaxProduct')->name('product.ajax');
Route::post('/product-store','App\Http\Controllers\Inventory\ProductController@store')->name('product.store');
Route::post('/product-update','App\Http\Controllers\Inventory\ProductController@update')->name('product.update');
Route::get('/product-edit/{id}','App\Http\Controllers\Inventory\ProductController@edit')->name('product.edit');
Route::get('/product-delete/{id}','App\Http\Controllers\Inventory\ProductController@destroy')->name('product.delete');
Route::get('/select2/products/by_category','App\Http\Controllers\Inventory\ProductController@select2ProductbyCat')->name('select2.products.by_category');
Route::get('/product-details-by-id','App\Http\Controllers\Inventory\ProductController@productDetailsbyId')->name('get_product_details_by_id');
Route::get('/auto-search/product','App\Http\Controllers\Inventory\ProductController@autoSearch')->name('auto-search.product');
Route::get('select2/product/color','App\Http\Controllers\Inventory\ProductController@select2ProductColor')->name('select2.product.color');
Route::get('select2/product/size','App\Http\Controllers\Inventory\ProductController@select2ProductSize')->name('select2.product.size');

 Route::get("import/products",[App\Http\Controllers\Inventory\BulkImportController::class,"index"])->name("import.product");
Route::post("import/products",[App\Http\Controllers\Inventory\BulkImportController::class,"postImport"])->name("product.import");
Route::post("import/downaload",[App\Http\Controllers\Inventory\BulkImportController::class,"downloadTemplate"])->name("import_download_template");
//barcode
Route::get('/generate-barcode','App\Http\Controllers\Inventory\ProductController@generateBarcode')->name('product.generate_barcode');
Route::post('/generate-barcode','App\Http\Controllers\Inventory\ProductController@generateBarcodePost')->name('product.generate_barcode');
//pos
Route::get('/pos','App\Http\Controllers\Inventory\PosController@create')->name('pos.create');
Route::get('/pos-product/search','App\Http\Controllers\Inventory\PosController@searchProduct')->name('pos.product_search');
Route::get('/pos-product/details','App\Http\Controllers\Inventory\PosController@ProductDetails')->name('pos.product_details');
Route::get('/pos-customer/search','App\Http\Controllers\Inventory\PosController@searchCustomer')->name('pos.customer_search');
Route::get('/pos-customer/add','App\Http\Controllers\Inventory\PosController@addCustomer')->name('pos.add_customer');
Route::post('/pos-sale','App\Http\Controllers\Inventory\PosController@salePos')->name('pos.sale');
Route::get('/pos-sale/list','App\Http\Controllers\Inventory\PosController@index')->name('pos.sale.index');
Route::post('/pos-sale/ajax','App\Http\Controllers\Inventory\PosController@ajaxPos')->name('pos.sale.ajax');
Route::get('/pos-sale/invoice/{id}','App\Http\Controllers\Inventory\PosController@saleInvoice')->name('pos.sale.invoice');
Route::get('/pos-invoice-detail/{id}','App\Http\Controllers\Inventory\PosController@saleDedtails')->name('pos.sale_details');
Route::get('/pos-invoice-print/{id}','App\Http\Controllers\Inventory\PosController@salePrint')->name('pos.sale_print');
//Country
Route::get('/country-list','App\Http\Controllers\Inventory\CountryController@index')->name('country.index');
Route::post('/country-ajax','App\Http\Controllers\Inventory\CountryController@ajaxCountry')->name('country.ajax');
Route::post('/country-store','App\Http\Controllers\Inventory\CountryController@store')->name('country.store');
Route::get('/country-edit','App\Http\Controllers\Inventory\CountryController@edit')->name('country.edit');
Route::get('/country-delete/{id}','App\Http\Controllers\Inventory\CountryController@destroy')->name('country.delete');
Route::get('/select2/countries','App\Http\Controllers\Inventory\CountryController@select2Countries')->name('select2.countries');
//State
Route::get('/state-list','App\Http\Controllers\Inventory\StateController@index')->name('state.index');
Route::post('/state-ajax','App\Http\Controllers\Inventory\StateController@ajaxState')->name('state.ajax');
Route::post('/state-store','App\Http\Controllers\Inventory\StateController@store')->name('state.store');
Route::get('/state-edit','App\Http\Controllers\Inventory\StateController@edit')->name('state.edit');
Route::get('/state-delete/{id}','App\Http\Controllers\Inventory\StateController@destroy')->name('state.delete');
Route::get('/select2/state-by-country','App\Http\Controllers\Inventory\StateController@select2StateByCountry')->name('select2.states.bycountry');
//City
Route::get('/city-list','App\Http\Controllers\Inventory\CityController@index')->name('city.index');
Route::post('/city-ajax','App\Http\Controllers\Inventory\CityController@ajaxCity')->name('city.ajax');
Route::post('/city-store','App\Http\Controllers\Inventory\CityController@store')->name('city.store');
Route::get('/city-edit','App\Http\Controllers\Inventory\CityController@edit')->name('city.edit');
Route::get('/city-delete/{id}','App\Http\Controllers\Inventory\CityController@destroy')->name('city.delete');
Route::get('/select2/city-by-state','App\Http\Controllers\Inventory\CityController@select2CitiesByState')->name('select2.cities.byState');

//Vendor
Route::get('/vendor-list','App\Http\Controllers\Inventory\VendorController@index')->name('vendor.index');
Route::post('/vendor-ajax','App\Http\Controllers\Inventory\VendorController@ajaxVendor')->name('vendor.ajax');
Route::post('/vendor-store','App\Http\Controllers\Inventory\VendorController@store')->name('vendor.store');
Route::get('/vendor-edit','App\Http\Controllers\Inventory\VendorController@edit')->name('vendor.edit');
Route::get('/vendor-delete/{id}','App\Http\Controllers\Inventory\VendorController@destroy')->name('vendor.delete');
Route::get('/auto-search/vendor','App\Http\Controllers\Inventory\VendorController@autoSearch')->name('auto-search.vendor');
Route::get('/select2/vendor','App\Http\Controllers\Inventory\VendorController@select2Vendors')->name('select2.vendors');
//Customer
Route::get('/customer-list','App\Http\Controllers\Inventory\CustomerController@index')->name('customer.index');
Route::post('/customer-ajax','App\Http\Controllers\Inventory\CustomerController@ajaxCustomer')->name('customer.ajax');
Route::post('/customer-store','App\Http\Controllers\Inventory\CustomerController@store')->name('customer.store');
Route::get('/customer-edit','App\Http\Controllers\Inventory\CustomerController@edit')->name('customer.edit');
Route::get('/customer-delete/{id}','App\Http\Controllers\Inventory\CustomerController@destroy')->name('customer.delete');
Route::get('/auto-search/cutomer','App\Http\Controllers\Inventory\CustomerController@autoSearch')->name('auto-search.customer');
Route::get('/select2/customer','App\Http\Controllers\Inventory\CustomerController@select2Customers')->name('select2.customer');
//Damage
Route::get('damage/product-details-by-id','App\Http\Controllers\Inventory\DamageController@productDetailsbyId')->name('damage.get_product_by');
Route::get('/damage-list','App\Http\Controllers\Inventory\DamageController@index')->name('damage.index');
Route::post('/damage-ajax','App\Http\Controllers\Inventory\DamageController@ajaxPurchase')->name('damage.ajax');
Route::get('/damage-create','App\Http\Controllers\Inventory\DamageController@create')->name('damage.create');
Route::post('/damage-store','App\Http\Controllers\Inventory\DamageController@store')->name('damage.store');
Route::get('/damage-edit/{id}','App\Http\Controllers\Inventory\DamageController@edit')->name('damage.edit');
Route::post('/damage-update/{id}','App\Http\Controllers\Inventory\DamageController@update')->name('damage.update');
Route::post('/damage-delete/{id}','App\Http\Controllers\Inventory\DamageController@destroy')->name('damage.delete');

//Purchase
Route::get('/purchase-list','App\Http\Controllers\Inventory\PurchaseController@index')->name('purchase.index');
Route::post('/purchase-ajax','App\Http\Controllers\Inventory\PurchaseController@ajaxPurchase')->name('purchase.ajax');
Route::get('/purchase-create','App\Http\Controllers\Inventory\PurchaseController@create')->name('purchase.create');
Route::post('/purchase-store','App\Http\Controllers\Inventory\PurchaseController@store')->name('purchase.store');
Route::get('/purchase-edit/{id}','App\Http\Controllers\Inventory\PurchaseController@edit')->name('purchase.edit');
Route::post('/purchase-update/{id}','App\Http\Controllers\Inventory\PurchaseController@update')->name('purchase.update');
Route::post('/purchase-delete/{id}','App\Http\Controllers\Inventory\PurchaseController@destroy')->name('purchase.delete');
Route::get('purchase-detail/{id}','App\Http\Controllers\Inventory\PurchaseController@purchaseDetail')->name('purchase.view');
Route::post('purchase/add-payment','App\Http\Controllers\Inventory\PurchaseController@storePayment')->name('purchase.add-payment');
Route::get('purchase-payment-show/{id}','App\Http\Controllers\Inventory\PurchaseController@paymentList')->name('purchase.payment_show');
Route::get('purchase_print/{id}','App\Http\Controllers\Inventory\PurchaseController@printPurchase')->name('purchase.print');
//Purchase Return
Route::get('/purchase-return-list','App\Http\Controllers\Inventory\PurchaseReturnController@index')->name('purchase_return.index');
Route::post('/purchase-return-ajax','App\Http\Controllers\Inventory\PurchaseReturnController@ajaxPurchaseReturn')->name('purchase_return.ajax');
Route::get('/purchase-return/add/{id}','App\Http\Controllers\Inventory\PurchaseReturnController@addReturn')->name('purchase_return.add');
Route::post('/purchase-return-post/add/{id}','App\Http\Controllers\Inventory\PurchaseReturnController@addReturnPost')->name('purchase_return.add_post');
Route::get('/purchase-return/add/{id}/edit','App\Http\Controllers\Inventory\PurchaseReturnController@addReturnEdit')->name('purchase_return.add_edit');
Route::post('/purchase-return/add/{id}/edit','App\Http\Controllers\Inventory\PurchaseReturnController@addReturnUpdate')->name('purchase_return.add_edit_post');

Route::post('purchase-return/add-payment','App\Http\Controllers\Inventory\PurchaseReturnController@storePayment')->name('purchase_return.add-payment');
Route::get('purchase-return-payment-show/{id}','App\Http\Controllers\Inventory\PurchaseReturnController@paymentList')->name('purchase_return.payment_show');

Route::get('purchase-return-detail/{id}','App\Http\Controllers\Inventory\PurchaseReturnController@purchaseDetail')->name('purchase_return.view');
Route::get('/purchase-return-create','App\Http\Controllers\Inventory\PurchaseReturnController@create')->name('purchase_return.create');
Route::post('/purchase-return-store','App\Http\Controllers\Inventory\PurchaseReturnController@store')->name('purchase_return.store');
Route::post('/purchase-return-delete/{id}','App\Http\Controllers\Inventory\PurchaseReturnController@destroy')->name('purchase_return.delete');
Route::get('purchase_return_print/{id}','App\Http\Controllers\Inventory\PurchaseReturnController@printPurchaseReturn')->name('purchase_return.print');
//Invoice
Route::get('/invoice-list','App\Http\Controllers\Inventory\InvoiceController@index')->name('invoice.index');
Route::post('/invoice-ajax','App\Http\Controllers\Inventory\InvoiceController@ajaxInvoice')->name('invoice.ajax');
Route::get('/invoice-create','App\Http\Controllers\Inventory\InvoiceController@create')->name('invoice.create');
Route::post('/invoice-store','App\Http\Controllers\Inventory\InvoiceController@store')->name('invoice.store');
Route::get('/invoice-edit/{id}','App\Http\Controllers\Inventory\InvoiceController@edit')->name('invoice.edit');
Route::post('/invoice-update/{id}','App\Http\Controllers\Inventory\InvoiceController@update')->name('invoice.update');
Route::post('/invoice-delete/{id}','App\Http\Controllers\Inventory\InvoiceController@destroy')->name('invoice.delete');
Route::get('invoice-detail/{id}','App\Http\Controllers\Inventory\InvoiceController@invoiceDetail')->name('invoice.view');
Route::post('invoice/add-payment','App\Http\Controllers\Inventory\InvoiceController@storePayment')->name('invoice.add-payment');
Route::get('invoice-payment-show/{id}','App\Http\Controllers\Inventory\InvoiceController@paymentList')->name('invoice.payment_show');
Route::post('invoice/delete-payment','App\Http\Controllers\Inventory\InvoiceController@deletePayment')->name('invoice.delete-payment');
Route::get('invoice_print/{id}','App\Http\Controllers\Inventory\InvoiceController@printInvoice')->name('invoice.print');
//instant invoice
Route::get('/invoice-create-instant','App\Http\Controllers\Inventory\InvoiceController@createInstant')->name('invoice.create_instant');
Route::post('/invoice-store-instant','App\Http\Controllers\Inventory\InvoiceController@storeInstant')->name('invoice.store_instant');
Route::get('instant_invoice_print/{id}','App\Http\Controllers\Inventory\InvoiceController@printInvoiceInstant')->name('invoice.print_instant');
//Invoice Return
Route::get('/invoice-return-list','App\Http\Controllers\Inventory\InvoiceReturnController@index')->name('invoice_return.index');
Route::post('/invoice-return-ajax','App\Http\Controllers\Inventory\InvoiceReturnController@ajaxInvoiceReturn')->name('invoice_return.ajax');
Route::get('/invoice-return/add/{id}','App\Http\Controllers\Inventory\InvoiceReturnController@addReturn')->name('invoice_return.add');
Route::post('/invoice-return/add/{id}','App\Http\Controllers\Inventory\InvoiceReturnController@addReturnPost')->name('invoice_return.add_post');
Route::get('/invoice-return/add/{id}/edit','App\Http\Controllers\Inventory\InvoiceReturnController@addReturnEdit')->name('invoice_return.add_edit');
Route::post('/invoice-return/add/{id}/edit','App\Http\Controllers\Inventory\InvoiceReturnController@addReturnUpdate')->name('invoice_return.add_edit_post');

Route::post('invoice-return/add-payment','App\Http\Controllers\Inventory\InvoiceReturnController@storePayment')->name('invoice_return.add-payment');
Route::get('invoice-return-payment-show/{id}','App\Http\Controllers\Inventory\InvoiceReturnController@paymentList')->name('invoice_return.payment_show');

Route::get('invoice-return-detail/{id}','App\Http\Controllers\Inventory\InvoiceReturnController@invoiceReturnDetail')->name('invoice_return.view');
Route::get('/invoice-return-create','App\Http\Controllers\Inventory\InvoiceReturnController@create')->name('invoice_return.create');
Route::post('/invoice-return-store','App\Http\Controllers\Inventory\InvoiceReturnController@store')->name('invoice_return.store');
Route::post('/invoice-return-delete/{id}','App\Http\Controllers\Inventory\InvoiceReturnController@destroy')->name('invoice_return.delete');
Route::get('invoice_return_print/{id}','App\Http\Controllers\Inventory\InvoiceReturnController@printInvoiceReturn')->name('invoice_return.print');
//Quotation
Route::get('/quotation-list','App\Http\Controllers\Inventory\QuotationController@index')->name('quotation.index');
Route::post('/quotation-ajax','App\Http\Controllers\Inventory\QuotationController@ajaxQuotation')->name('quotation.ajax');
Route::get('/quotation-create','App\Http\Controllers\Inventory\QuotationController@create')->name('quotation.create');
Route::post('/quotation-store','App\Http\Controllers\Inventory\QuotationController@store')->name('quotation.store');
Route::get('/quotation-edit/{id}','App\Http\Controllers\Inventory\QuotationController@edit')->name('quotation.edit');
Route::post('/quotation-update/{id}','App\Http\Controllers\Inventory\QuotationController@update')->name('quotation.update');
Route::post('/quotation-delete/{id}','App\Http\Controllers\Inventory\QuotationController@destroy')->name('quotation.delete');
Route::get('quotation-detail/{id}','App\Http\Controllers\Inventory\QuotationController@quotationDetail')->name('quotation.view');
Route::get('quotation_print/{id}','App\Http\Controllers\Inventory\QuotationController@printQuotation')->name('quotation.print');
//instant invoice
Route::get('/instant-invoice','App\Http\Controllers\Inventory\InstantInvoiceController@create')->name('instant_invoice.create');

});
