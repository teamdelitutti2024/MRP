<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home_example', function () {
    return view('home_example');
});

Route::get('/login', 'SessionsController@index')->name('login');
Route::post('/login', 'SessionsController@authenticate');
Route::get('/logout', 'SessionsController@logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function() {        
        return view('home_example');
    })->name('dashboard');

    // Supplies routes
    Route::get('/supplies', 'SuppliesController@index');
    Route::post('/supplies/store', 'SuppliesController@store');
    Route::post('/supplies/get_row', 'SuppliesController@get_row');
    Route::post('/supplies/update/{supply}', 'SuppliesController@update');
    Route::get('/supplies/detail/{supply}', 'SuppliesController@detail');
    Route::get('/supplies/change_status/{id}/{status}', 'SuppliesController@change_status');

    // Supply locations
    Route::get('/supply_locations', 'SupplyLocationsController@index');
    Route::post('/supply_locations/store', 'SupplyLocationsController@store');
    Route::post('/supply_locations/get_row', 'SupplyLocationsController@get_row');
    Route::post('/supply_locations/update/{supply_location}', 'SupplyLocationsController@update');

    // Supplier supplies routes
    Route::post('/supplier_supplies/store', 'SupplierSuppliesController@store');
    Route::post('/supplier_supplies/get_row', 'SupplierSuppliesController@get_row');
    Route::post('/supplier_supplies/update/{supplier_supply}', 'SupplierSuppliesController@update');
    Route::post('/supplier_supplies/get_supplier_supplies', 'SupplierSuppliesController@get_supplier_supplies');

    // Suppliers routes
    Route::get('/suppliers', 'SuppliersController@index');
    Route::get('/suppliers/download', 'SuppliersController@download');
    Route::get('/suppliers/add', 'SuppliersController@add');
    Route::post('/suppliers/store', 'SuppliersController@store');
    Route::post('/suppliers/get_row', 'SuppliersController@get_row');
    Route::get('/suppliers/edit/{supplier}', 'SuppliersController@edit');
    Route::post('/suppliers/update/{supplier}', 'SuppliersController@update');
    Route::get('/suppliers/detail/{supplier}', 'SuppliersController@detail');
    Route::post('/suppliers/get_supplies', 'SuppliersController@get_supplies');

    // Supplier contacts routes
    Route::post('/supplier_contacts/store', 'SupplierContactsController@store');
    Route::post('/supplier_contacts/get_row', 'SupplierContactsController@get_row');
    Route::post('/supplier_contacts/update/{supplier_contact}', 'SupplierContactsController@update');
    Route::get('/supplier_contacts/detail/{supplier_contact}', 'SupplierContactsController@detail');

    // Supplier bank data routes
    Route::post('/supplier_bank_data/store', 'SupplierBankDataController@store');
    Route::post('/supplier_bank_data/get_row', 'SupplierBankDataController@get_row');
    Route::post('/supplier_bank_data/update/{supplier_bank_data}', 'SupplierBankDataController@update');

    // Supplier tax data routes
    Route::get('/supplier_tax_data/add/{supplier}', 'SupplierTaxDataController@add');
    Route::post('/supplier_tax_data/store', 'SupplierTaxDataController@store');
    Route::get('/supplier_tax_data/edit/{supplier_tax_data}', 'SupplierTaxDataController@edit');
    Route::post('/supplier_tax_data/update/{supplier_tax_data}', 'SupplierTaxDataController@update');
    Route::get('/supplier_tax_data/detail/{supplier_tax_data}', 'SupplierTaxDataController@detail');

    // Supplier categories routes
    Route::get('/supplier_categories', 'SupplierCategoriesController@index');
    Route::post('/supplier_categories/store', 'SupplierCategoriesController@store');
    Route::post('/supplier_categories/get_row', 'SupplierCategoriesController@get_row');
    Route::post('/supplier_categories/update/{supplier_category}', 'SupplierCategoriesController@update');

    // Commercial terms routes
    Route::get('/commercial_terms', 'CommercialTermsController@index');
    Route::get('/commercial_terms/add', 'CommercialTermsController@add');
    Route::post('/commercial_terms/store', 'CommercialTermsController@store');
    Route::get('/commercial_terms/edit/{commercial_term}', 'CommercialTermsController@edit');
    Route::post('/commercial_terms/update/{commercial_term}', 'CommercialTermsController@update');
    Route::get('/commercial_terms/detail/{commercial_term}', 'CommercialTermsController@detail');

    // Users routes
    Route::get('/users', 'UsersController@index');
    Route::post('/users/store', 'UsersController@store');
    Route::post('/users/get_row', 'UsersController@get_row');
    Route::post('/users/update/{user}', 'UsersController@update');
    Route::get('/users/detail/{user}', 'UsersController@detail');

    // User contacts routes
    Route::post('/user_contacts/store', 'UserContactsController@store');
    Route::post('/user_contacts/get_row', 'UserContactsController@get_row');
    Route::post('/user_contacts/update/{user_contact}', 'UserContactsController@update');

    // Measurement units routes
    Route::get('/measurement_units', 'MeasurementUnitsController@index');
    Route::get('/measurement_units/add', 'MeasurementUnitsController@add');
    Route::post('/measurement_units/store', 'MeasurementUnitsController@store');
    Route::get('/measurement_units/edit/{measurement_unit}', 'MeasurementUnitsController@edit');
    Route::post('/measurement_units/update/{measurement_unit}', 'MeasurementUnitsController@update');
    Route::get('/measurement_units/detail/{measurement_unit}', 'MeasurementUnitsController@detail');

    // Bake bread sizes routes
    Route::get('/bake_bread_sizes', 'BakeBreadSizesController@index');
    Route::get('/bake_bread_sizes/add', 'BakeBreadSizesController@add');
    Route::post('/bake_bread_sizes/store', 'BakeBreadSizesController@store');
    Route::post('/bake_bread_sizes/get_row', 'BakeBreadSizesController@get_row');
    Route::post('/bake_bread_sizes/get_row_v2', 'BakeBreadSizesController@get_row_v2');
    Route::get('/bake_bread_sizes/edit/{bake_bread_size}', 'BakeBreadSizesController@edit');
    Route::post('/bake_bread_sizes/update/{bake_bread_size}', 'BakeBreadSizesController@update');

    // Products routes
    Route::get('/products', 'ProductsController@index');
    Route::get('/products/add', 'ProductsController@add');
    Route::post('/products/store', 'ProductsController@store');
    Route::get('/products/edit/{product}', 'ProductsController@edit');
    Route::post('/products/update/{product}', 'ProductsController@update');
    Route::get('/products/detail/{product}', 'ProductsController@detail');
    Route::get('/products/change_status/{id}/{status}', 'ProductsController@change_status');

    // Prepared products
    Route::get('/prepared_products', 'PreparedProductsController@index');
    Route::get('/prepared_products/add', 'PreparedProductsController@add');
    Route::post('/prepared_products/store', 'PreparedProductsController@store');
    Route::post('/prepared_products/get_row', 'PreparedProductsController@get_row');
    Route::get('/prepared_products/edit/{prepared_product}', 'PreparedProductsController@edit');
    Route::post('/prepared_products/update/{prepared_product}', 'PreparedProductsController@update');
    Route::get('/prepared_products/detail/{prepared_product}', 'PreparedProductsController@detail');

    // Product sizes routes
    Route::get('/product_sizes/add/{product}', 'ProductSizesController@add');
    Route::post('/product_sizes/store', 'ProductSizesController@store');
    Route::post('/product_sizes/get_row', 'ProductSizesController@get_row');
    Route::get('/product_sizes/edit/{product_size}', 'ProductSizesController@edit');
    Route::post('/product_sizes/update/{product_size}', 'ProductSizesController@update');
    Route::post('/product_sizes/get_product_sizes', 'ProductSizesController@get_product_sizes');

    // Resources routes
    Route::get('/resources', 'ResourcesController@index');
    Route::post('/resources/store', 'ResourcesController@store');
    Route::post('/resources/get_row', 'ResourcesController@get_row');
    Route::post('/resources/update/{resource}', 'ResourcesController@update');

    // Branches routes
    Route::get('/branches', 'BranchesController@index');
    Route::get('/branches/detail/{branch}', 'BranchesController@detail');

    // Orders routes
    Route::get('/orders', 'OrdersController@index');
    Route::get('/orders/detail/{order}', 'OrdersController@detail');
    Route::get('/orders/download/{order}', 'OrdersController@download');
    Route::get('/orders/projection/{order}', 'OrdersController@projection');
    Route::get('/orders/download_projection/{order}', 'OrdersController@download_projection');
    Route::get('/orders/fill_ingredients/{order_id}', 'OrdersController@fill_ingredients');
    Route::get('/orders/add', 'OrdersController@add');
    Route::post('/orders/store', 'OrdersController@store');

    // Products production routes
    Route::get('/products_production', 'ProductsProductionController@index');
    Route::get('/products_production/add', 'ProductsProductionController@add');
    Route::post('/products_production/store', 'ProductsProductionController@store');
    Route::get('/products_production/detail/{production}', 'ProductsProductionController@detail');
    Route::get('/products_production/download_projection/{production}', 'ProductsProductionController@download_projection');
    Route::get('/products_production/revert/{production}', 'ProductsProductionController@revert');

    // Bake breads production routes
    Route::get('/bake_breads_production', 'BakeBreadsProductionController@index');
    Route::get('/bake_breads_production/add', 'BakeBreadsProductionController@add');
    Route::post('/bake_breads_production/store', 'BakeBreadsProductionController@store');
    Route::get('/bake_breads_production/detail/{production}', 'BakeBreadsProductionController@detail');
    Route::get('/bake_breads_production/download_projection/{production}', 'BakeBreadsProductionController@download_projection');
    Route::get('/bake_breads_production/revert/{production}', 'BakeBreadsProductionController@revert');

    // Production projection routes
    Route::get('/projection/products', 'ProjectionController@index');
    Route::get('/projection/bake_breads', 'ProjectionController@bake_breads');
    Route::get('/projection/prepared', 'ProjectionController@prepared');
    Route::post('/projection/calculate/{type}', 'ProjectionController@calculate');
    Route::get('/projection/download/{type}', 'ProjectionController@download');

    // Supply orders routes
    Route::get('/supply_orders', 'SupplyOrdersController@index');
    Route::get('/supply_orders/add', 'SupplyOrdersController@add');
    Route::post('/supply_orders/store', 'SupplyOrdersController@store');
    Route::get('/supply_orders/edit/{supply_order}', 'SupplyOrdersController@edit');
    Route::post('/supply_orders/update/{supply_order}', 'SupplyOrdersController@update');
    Route::get('/supply_orders/download/{supply_order}', 'SupplyOrdersController@download');
    Route::post('/supply_orders/request/{supply_order}', 'SupplyOrdersController@request');
    Route::get('/supply_orders/cancel/{supply_order}', 'SupplyOrdersController@cancel');
    Route::get('/supply_orders/detail/{supply_order}', 'SupplyOrdersController@detail');
    Route::get('/supply_orders/receptions/{supply_order}', 'SupplyOrdersController@get_receptions');

    // Supply receptions routes
    Route::get('/supply_receptions', 'SupplyReceptionsController@index');
    Route::get('/supply_receptions/add/{id}', 'SupplyReceptionsController@add');
    Route::post('/supply_receptions/store', 'SupplyReceptionsController@store');
    Route::get('/supply_receptions/detail/{supply_reception}', 'SupplyReceptionsController@detail');

    // Supply transfers routes
    Route::get('/supply_transfers', 'SupplyTransfersController@index');
    Route::get('/supply_transfers/add', 'SupplyTransfersController@add');
    Route::post('/supply_transfers/store', 'SupplyTransfersController@store');
    Route::get('/supply_transfers/detail/{supply_transfer}', 'SupplyTransfersController@detail');

    // Declined supplies routes
    Route::get('/declined_supplies', 'DeclinedSuppliesController@index');
    Route::post('/declined_supplies/store', 'DeclinedSuppliesController@store');
    Route::post('/declined_supplies/get_row', 'DeclinedSuppliesController@get_row');
    Route::post('/declined_supplies/update/{declined_supply}', 'DeclinedSuppliesController@update');
    Route::get('/declined_supplies/detail/{declined_supply}', 'DeclinedSuppliesController@detail');
    Route::get('/declined_supplies/change_status/{id}/{status}', 'DeclinedSuppliesController@change_status');
    Route::get('/declined_supplies/revert/{declined_supply}', 'DeclinedSuppliesController@revert');

    // Declined products routes
    Route::get('/declined_products', 'DeclinedProductsController@index');
    Route::get('/declined_products/add', 'DeclinedProductsController@add');
    Route::post('/declined_products/store', 'DeclinedProductsController@store');
    Route::get('/declined_products/detail/{declined_product}', 'DeclinedProductsController@detail');
    Route::get('/declined_products/revert/{declined_product}', 'DeclinedProductsController@revert');

    // Declined bake breads routes
    Route::get('/declined_bake_breads', 'DeclinedBakeBreadsController@index');
    Route::get('/declined_bake_breads/add', 'DeclinedBakeBreadsController@add');
    Route::post('/declined_bake_breads/store', 'DeclinedBakeBreadsController@store');
    Route::get('/declined_bake_breads/detail/{declined_bake_bread}', 'DeclinedBakeBreadsController@detail');
    Route::get('/declined_bake_breads/revert/{declined_bake_bread}', 'DeclinedBakeBreadsController@revert');

    // Declined prepared products routes
    Route::get('/declined_prepared_products', 'DeclinedPreparedProductsController@index');
    Route::get('/declined_prepared_products/add', 'DeclinedPreparedProductsController@add');
    Route::post('/declined_prepared_products/store', 'DeclinedPreparedProductsController@store');
    Route::get('/declined_prepared_products/detail/{declined_prepared_product}', 'DeclinedPreparedProductsController@detail');
    Route::get('/declined_prepared_products/revert/{declined_prepared_product}', 'DeclinedPreparedProductsController@revert');

    // Quarantines routes
    Route::get('/quarantines', 'QuarantinesController@index');
    Route::post('/quarantines/store', 'QuarantinesController@store');
    Route::post('/quarantines/get_row', 'QuarantinesController@get_row');
    Route::post('/quarantines/update/{quarantine}', 'QuarantinesController@update');
    Route::get('/quarantines/detail/{quarantine}', 'QuarantinesController@detail');
    Route::get('/quarantines/change_status/{id}/{status}', 'QuarantinesController@change_status');
    Route::post('/quarantines/change_to_declined/{quarantine}', 'QuarantinesController@change_to_declined');

    // Stock routes
    Route::get('/general_stock', 'StockController@general');
    Route::get('/stock/upload_stock', 'StockController@upload_stock');
    Route::post('/stock/update_stock', 'StockController@update_stock');
    
    // Stock level 1
    Route::get('/stock/level_1', 'StockLevel1Controller@index');
    Route::get('/stock/level_1/detail/{stock_level_1}', 'StockLevel1Controller@detail');

    // Stock level 2
    Route::get('/stock/level_2', 'StockLevel2Controller@index');
    Route::get('/stock/level_2/detail/{stock_level_2}', 'StockLevel2Controller@detail');

    // Cycle counts routes
    Route::get('/cycle_counts', 'CycleCountsController@index');
    Route::get('/cycle_counts/add', 'CycleCountsController@add');
    Route::get('/cycle_counts/add_partial', 'CycleCountsController@add_partial');
    Route::get('/cycle_counts/edit/{cycle_count}', 'CycleCountsController@edit');
    Route::get('/cycle_counts/edit_partial/{cycle_count}', 'CycleCountsController@edit_partial');
    Route::post('/cycle_counts/store_partial', 'CycleCountsController@store_partial');
    Route::post('/cycle_counts/update/{cycle_count}', 'CycleCountsController@update');
    Route::post('/cycle_counts/update_partial/{cycle_count}', 'CycleCountsController@update_partial');
    Route::get('/cycle_counts/finish/{cycle_count}', 'CycleCountsController@finish');
    Route::get('/cycle_counts/detail/{cycle_count}', 'CycleCountsController@detail');
    Route::get('/cycle_counts/download/{cycle_count}', 'CycleCountsController@download');

    // Departure shipments routes
    Route::get('/departure_shipments', 'DepartureShipmentsController@index');
    Route::get('/departure_shipments/add/{id}', 'DepartureShipmentsController@add');
    Route::post('/departure_shipments/store', 'DepartureShipmentsController@store');
    Route::get('/departure_shipments/edit/{departure_shipment}', 'DepartureShipmentsController@edit');
    Route::post('/departure_shipments/update/{departure_shipment}', 'DepartureShipmentsController@update');
    Route::get('/departure_shipments/finish/{departure_shipment}', 'DepartureShipmentsController@finish');
    Route::get('/departure_shipments/detail/{departure_shipment}', 'DepartureShipmentsController@detail');
    Route::get('/departure_shipments/inbound_shipments/{departure_shipment}', 'DepartureShipmentsController@get_inbound_shipments');

    // Inbound shipments routes
    Route::get('/inbound_shipments', 'InboundShipmentsController@index');
    Route::get('/inbound_shipments/add/{id}', 'InboundShipmentsController@add');
    Route::post('/inbound_shipments/store', 'InboundShipmentsController@store');
    Route::get('/inbound_shipments/detail/{inbound_shipment}', 'InboundShipmentsController@detail');

    // Supply categories routes
    Route::get('/supply_categories', 'SupplyCategoriesController@index');
    Route::post('/supply_categories/store', 'SupplyCategoriesController@store');
    Route::post('/supply_categories/get_row', 'SupplyCategoriesController@get_row');
    Route::post('/supply_categories/update/{supply_category}', 'SupplyCategoriesController@update');

    // Log changes routes
    Route::get('/changes_log', 'ChangesLogController@index');

    // Report stock
    Route::get('/reports/stock', 'ReportStockController@index');
    Route::get('/reports/stock/download', 'ReportStockController@download');
    Route::get('/reports/stock/export', 'ReportStockController@export');

    // Report stock value
    Route::get('/reports/stock_value', 'ReportStockValueController@index');
    Route::get('/reports/stock_value/download', 'ReportStockValueController@download');
    Route::get('/reports/stock_value/export', 'ReportStockValueController@export');

    // Report declined supplies
    Route::get('/reports/declined_supplies', 'ReportDeclinedSuppliesController@index');
    Route::get('/reports/declined_supplies/download', 'ReportDeclinedSuppliesController@download');
    Route::get('/reports/declined_supplies/export', 'ReportDeclinedSuppliesController@export');
	
    // Report quarantines
    Route::get('/reports/quarantines', 'ReportQuarantinesController@index');
    Route::get('/reports/quarantines/download', 'ReportQuarantinesController@download');
    Route::get('/reports/quarantines/export', 'ReportQuarantinesController@export');

    // Report order products
    Route::get('/reports/product_orders', 'ReportProductOrdersController@index');
    Route::get('/reports/product_orders/download', 'ReportProductOrdersController@download');
    Route::get('/reports/product_orders/export', 'ReportProductOrdersController@export');
	
    // Report orders
    Route::get('/reports/orders', 'ReportOrdersController@index');
    Route::get('/reports/orders/download', 'ReportOrdersController@download');
    Route::get('/reports/orders/export', 'ReportOrdersController@export');

    // Report supply orders
    Route::get('/reports/supply_orders', 'ReportSupplyOrdersController@index');
    Route::get('/reports/supply_orders/download', 'ReportSupplyOrdersController@download');
    Route::get('/reports/supply_orders/export', 'ReportSupplyOrdersController@export');

    // Report supplier orders
    Route::get('/reports/supplier_orders', 'ReportSupplierOrdersController@index');
    Route::get('/reports/supplier_orders/download', 'ReportSupplierOrdersController@download');
    Route::get('/reports/supplier_orders/export', 'ReportSupplierOrdersController@export');

    // Report supply receptions
    Route::get('/reports/supply_receptions', 'ReportSupplyReceptionsController@index');
    Route::get('/reports/supply_receptions/download', 'ReportSupplyReceptionsController@download');
    Route::get('/reports/supply_receptions/export', 'ReportSupplyReceptionsController@export');

    // Report supplies
    Route::get('/reports/supplies', 'ReportSuppliesController@index');
    Route::get('/reports/supplies/download', 'ReportSuppliesController@download');
    Route::get('/reports/supplies/export', 'ReportSuppliesController@export');

    // Report products
    Route::get('/reports/products', 'ReportProductsController@index');
    Route::get('/reports/products/download', 'ReportProductsController@download');
    Route::get('/reports/products/export', 'ReportProductsController@export');

    // Report suppliers
    Route::get('/reports/suppliers', 'ReportSuppliersController@index');	
    Route::get('/reports/suppliers/download', 'ReportSuppliersController@download');
    Route::get('/reports/suppliers/export', 'ReportSuppliersController@export');
});
