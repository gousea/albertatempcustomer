<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    
    if(Auth::check()) {
        return redirect('/dashboard');
    } else {
        return view('auth.login');
    }
});

/*Route::get('/', ['middleware' => 'guest', function()
{
    return view('auth.login');
}]);*/ 

Auth::routes();

Route::get('/api/convertupca2upce', 'Admin\ProductController@convert_upca_2_upce');
Route::get('/api/convertupce2upca', 'Admin\ProductController@convert_upce_2_upca');

// Route::get('/users', 'AllUserController@index' )->name('users');

Route::group(['middleware' => ['auth', 'StoreDatabaseSelection']],function(){
	Route::get('/dashboard', 'HomeController@index')->name('dashboard');
	Route::post('/dashboard', 'HomeController@dashContent')->name('dashboard');
	
	Route::get('/dashboard_quick_links', 'HomeController@dashboard_quick_links')->name('dashboard_quick_links');

	Route::get('/manual_sales_entry', 'EodReportSettingsController@index')->name('eodsetting');
    Route::post('/manual_sales_entry', 'EodReportSettingsController@get_list')->name('eodsetting_getlist');
    Route::post('/manual_sales_submit_values', 'EodReportSettingsController@submit_values');

    /*================= Vendor Module Routes =========================*/
    Route::group(['middleware' => ['Permission:PER1002']], function(){
        //vendor MOdel Route
        Route::get('vendors', 'VendorController@index')->name('vendors');
        Route::post('vendors', 'VendorController@search')->name('vendors');
        Route::post('vendors/edit_list', 'VendorController@edit_list');
        Route::get('vendors/create', 'VendorController@create')->name('vendors.create');
        Route::post('vendors/store', 'VendorController@store')->name('vendors.store');
        Route::post('vendors/remove', 'VendorController@remove')->name('vendors.remove');
        Route::get('vendors/{isupplierid}/edit', 'VendorController@edit' )->name('vendors.edit');
        Route::patch('vendors/update/{isupplierid}', 'VendorController@update' )->name('vendors.update');
        
        // HeadQuaters Routes
        Route::post('vendors/duplicatehqvendors', 'VendorController@hqVendors');
        Route::post('vendors/dupilcateHQvendor', 'VendorController@dupilcateHQvendor');
    });
    
    /*================= Customer Module Routes =========================*/  
    Route::group(['middleware' => ['Permission:PER1003']], function(){
        //customer Module route
        Route::get('customers', 'CustomerController@index')->name('customers');
        Route::post('customers', 'CustomerController@search')->name('customers');
        Route::post('customers/remove', 'CustomerController@remove')->name('customers.remove');
        Route::get('customers/create', 'CustomerController@create')->name('customers.create');
        Route::post('customers/store', 'CustomerController@store')->name('customers.store');
        Route::get('customers/{icustomerid}/edit', 'CustomerController@edit' )->name('customers.edit');
        Route::patch('customers/update/{icustomerid}', 'CustomerController@update' )->name('customers.update');
    });
    
    /*================= User Module Routes =========================*/
    Route::group(['middleware' => ['Permission:PER1004']], function(){
        //User Module Routes
    	Route::get('/users', 'AllUserController@index' )->name('users');
        Route::get('/users/create', 'AllUserController@create' )->name('users.create');
        Route::post('/users/store', 'AllUserController@store' )->name('users.store');
        Route::get('/users/{iuserid}/edit', 'AllUserController@edit' )->name('users.edit');
        Route::patch('/users/update/{iuserid}', 'AllUserController@update' )->name('users.update');
        Route::post('/users/remove', 'AllUserController@remove')->name('users.remove');
    });
    
    /*================= Store Module Routes =========================*/
    Route::group(['middleware' => ['Permission:PER1005']], function(){
        Route::get('/store/edit', 'StoreController@edit')->name('store.edit');
        Route::put('/store/update', 'StoreController@update')->name('store.update');
       	Route::get('/getdepartment', 'DashboardController@get_department');    
    });
        
        
    
    
    
    /*================= Item Module Routes =========================*/ 
    Route::group(['middleware' => ['Permission:PER1006']], function(){
        
        // this is for head quaters
        Route::post('/item/duplicatehqitems', 'Item\ItemController@hqItems');
        Route::post('/item/checkVendorExists','Item\ItemController@checkVendorExists');
        Route::post('/item/addVendorsFromitems','Item\ItemController@addVendorsFromitems');
        Route::post('/item/duplicatehqitemvendorassign','Item\ItemController@duplicatehqitemvendorassign');
        Route::post('/item/checkVendorExistsFromlist', 'Item\ItemController@checkVendorExistsFromlist');
        Route::post('/item/duplicatehqlotitems', 'Item\ItemController@duplicatehqlotitems');
        Route::post('/item/duplicatehqlotdeleteitems', 'Item\ItemController@duplicatehqlotdeleteitems');
        Route::post('/item/duplicatehqlotedititems', 'Item\ItemController@duplicatehqlotedititems');
        Route::post('/item/duplicatehqaliasitems', 'Item\ItemController@duplicatehqaliasitems');
        
      
        
        
        
         //=====ITEMS related routes========
        Route::get('/item/item_list/{show_items}/{sort_items}', 'Item\ItemController@index');
        Route::get('/item/add', 'Item\ItemController@add_form');
        Route::post('/item/add', 'Item\ItemController@add');
        Route::get('/item/edit/{iitemid}', 'Item\ItemController@edit_form');
        Route::post('/item/edit/{iitemid}', 'Item\ItemController@edit');
        Route::post('/item/delete', 'Item\ItemController@delete');
        
        Route::post('/item/search/{show_items}/{sort_items}', 'Item\ItemController@search');
        
        Route::post('/item/import_items', 'Item\ItemController@import_items');
        Route::get('/item/get_barcode', 'Item\ItemController@get_barcode');
        Route::post('/item/calculateROP', 'Item\ItemController@calculateROP');
        Route::post('/item/get_department_categories', 'Item\ItemController@get_department_categories');
        Route::get('/item/get_department_items', 'Item\ItemController@get_department_items');
        Route::get('/item/clone_item/{iitemid}', 'Item\ItemController@clone_item_form');
        Route::post('/item/clone_item', 'Item\ItemController@clone_item');
    
        Route::post('/item/unset_visited_below_zero', 'Item\ItemController@unset_visited_below_zero');
        Route::post('/item/calculateReorderPointAjax', 'Item\ItemController@calculateReorderPointAjax');
        Route::post('/item/action_vendor_editlist', 'Item\ItemController@action_vendor_editlist');
        Route::post('/item/action_vendor', 'Item\ItemController@action_vendor');
        Route::post('/item/delete_vendor_code', 'Item\ItemController@delete_vendor_code');
        Route::post('/item/add_alias_code', 'Item\ItemController@add_alias_code');
        Route::post('/item/delete_alias_code', 'Item\ItemController@delete_alias_code');
        Route::post('/item/add_lot_matrix', 'Item\ItemController@add_lot_matrix');
        Route::post('/item/lot_matrix_editlist', 'Item\ItemController@lot_matrix_editlist');
        Route::post('/item/delete_lot_matrix', 'Item\ItemController@delete_lot_matrix');
        Route::post('/item/check_vendor_item_code', 'Item\ItemController@check_vendor_item_code');
        Route::post('/item/parent_child_search', 'Item\ItemController@parent_child_search');
    
        Route::post('/item/get_categories', 'Item\ItemController@get_categories');
        Route::post('/item/get_subcategories', 'Item\ItemController@get_subcategories');
    
        Route::get('/item/getCategories', 'Item\ItemController@getCategories');
        Route::post('/item/category/add', 'Item\ItemController@addCategory');
        Route::post('/item/department/add', 'Item\ItemController@addDepartment');
        Route::get('/item/department', 'Item\ItemController@getDepartments');
        Route::post('/item/sub_category/add', 'Item\ItemController@addSubcategory');
        Route::post('/item/size/add', 'Item\ItemController@addSize');
        Route::get('/item/size', 'Item\ItemController@getSize');
        // Route::post('/item/group/add', 'item\ItemController@get_categories');
        // Route::post('/item/group', 'item\ItemController@get_subcategories');
        Route::post('/item/supplier/add', 'Item\ItemController@addSupplier');
        Route::get('/item/supplier', 'Item\ItemController@getSupplier');
        Route::post('/item/manufacturer/add_manufacturer', 'Item\ItemController@addManufacturer');
        Route::get('/item/manufacturer/get_manufacturer', 'Item\ItemController@getManufacturer');
        
        Route::get('/item/get_status_import', 'Item\ItemController@get_status_import');
        Route::get('/item/checkforduplicateBarcode', 'Item\ItemController@checkforduplicateBarcode');
        
        //=====END ITEMS related routes========    
        
        //=======Quick Item==================
        Route::get('/item/quick_item_list', 'Item\QuickItemController@index');
        Route::post('/item/quick_item/update', 'Item\QuickItemController@edit_list');
    
        //=======Parent Child================
        Route::get('/item/parent_child_list', 'Item\ParentChildController@index')->name('parent_child_list');
        Route::get('/item/parent_child/searchitem', 'Item\ParentChildController@searchitem');
        Route::get('/item/parent_child/searchitem_child', 'Item\ParentChildController@searchitem');
        Route::post('/item/parent_child/add', 'Item\ParentChildController@add');
        Route::post('/item/parent_child/save', 'Item\ParentChildController@save');
        Route::post('/item/parent_child/delete', 'Item\ParentChildController@delete');
        
        // HQ Routes
        Route::post('/item/parent_child/checkduplicateparentchild', 'Item\ParentChildController@checkduplicateparentchild');
        Route::post('/item/parent_child/parentChildDuplication', 'Item\ParentChildController@parentChildDuplication');
        Route::post('/item/parent_child/parentChildDuplicationfordelete', 'Item\ParentChildController@parentChildDuplicationfordelete');
        
        
        //==================================Items/Itemgroup==================================
    	Route::get('/itemgroup','ItemGroupController@itemgroup')->name('itemgroup');
    	Route::get('/additemgroup','ItemGroupController@additemgroup')->name('additemgroup');
    	Route::post('/insertgroupname','ItemGroupController@savegroupname')->name('insertgroupname');
    	Route::post('/itemformgroupsearch','ItemGroupController@itemformgroupsearch')->name('itemformgroupsearch');
    	Route::post('/deleteitemgroup','ItemGroupController@deletegroupname')->name('deletegroupname');
    	Route::post('/itemgroupsearch','ItemGroupController@itemsearch')->name('itemgroupsearch');
    	Route::post('/itemnamesearch','ItemGroupController@itemnamesearch')->name('itemnamesearch');
    	Route::get('/edititemgroup','ItemGroupController@edititemgroup')->name('edititemgroup');
    	Route::post('/savedata','ItemGroupController@savegroupname')->name('savegroupname');
    	Route::get('/edititem/{iitemgroupid}','ItemGroupController@editgroupdetail')->name('editgroupdetail');
    	Route::post('/updatedata','ItemGroupController@updategroupdetail')->name('updategroupdetail');

        // 	hq routes for duplicate item groups
        Route::post('/duplicatehqitemgroups','ItemGroupController@duplicatehqitemgroups')->name('duplicatehqitemgroups');
        Route::post('/duplicateitemgroup','ItemGroupController@duplicateitemgroup')->name('duplicateitemgroup');
        
    
        /* ************* Quick Update Items Mdoule ************* */
        
        Route::match(['GET','POST'],'/quick_update_item/{search_item_type?}', 'Item\QuickUpdateItemController@index')->name('quick_update_item');
        Route::post('/quick_update_item_edit', 'Item\QuickUpdateItemController@editList')->name('quick_update_item_edit');
        Route::post('/quick_update_set_session', 'Item\QuickUpdateItemController@setSession')->name('quick_update_set_session');
        Route::post('/quick_update_get_categories', 'Item\QuickUpdateItemController@getCategories')->name('quick_update_get_categories');
        Route::post('/quick_update_get_categories_selected', 'Item\QuickUpdateItemController@getCategories_selected')->name('quick_update_get_categories_selected');


    
        //=======Edit Multiple Item===============
        // Route::get('/item/edit_multiple_item', 'Item\EditmultipleController@getList');
        // Route::post('/item/edit_multiple_item/searchItems', 'Item\EditmultipleController@searchItems');
        // Route::post('/item/edit_multiple_item/get_department_categories', 'Item\EditmultipleController@get_department_categories');
        // Route::post('/item/edit_multiple_item/get_sub_categories_url', 'Item\EditmultipleController@get_sub_categories_url');
        // Route::post('/item/edit_multiple_item/edit_list', 'Item\EditmultipleController@edit_list');
        // Route::get('/item/edit_multiple_item/get_session_value', 'Item\EditmultipleController@get_session_value');
        // Route::get('/item/edit_multiple_item/set_unset_session_value', 'Item\EditmultipleController@set_unset_session_value');
        // Route::get('/item/edit_multiple_item/unset_session_value', 'Item\EditmultipleController@unset_session_value');
        // Route::post('/item/edit_multiple_item/getCategories', 'Item\EditmultipleController@getCategories');
        // Route::post('/item/edit_multiple_item/getSubcategories', 'Item\EditmultipleController@getSubcategories');
        // Route::post('/item/edit_multiple_item/set_itemids_final', 'Item\EditmultipleController@set_itemids_final');
        // Route::post('/item/edit_multiple_item/add_remove_ids', 'Item\EditmultipleController@add_remove_ids');
        
        
        Route::get('/item/edit_multiple_item', 'Item\EditmultipleController@getList');
        Route::post('/item/edit_multiple_item/searchItems', 'Item\EditmultipleController@searchItems');
        Route::post('/item/edit_multiple_item/get_department_categories', 'Item\EditmultipleController@get_department_categories');
        Route::post('/item/edit_multiple_item/get_sub_categories_url', 'Item\EditmultipleController@get_sub_categories_url');
        Route::post('/item/edit_multiple_item/edit_list', 'Item\EditmultipleController@edit_list');
        Route::post('/item/edit_multiple_item/get_session_value', 'Item\EditmultipleController@get_session_value');
        Route::get('/item/edit_multiple_item/set_unset_session_value', 'Item\EditmultipleController@set_unset_session_value');
        Route::get('/item/edit_multiple_item/unset_session_value', 'Item\EditmultipleController@unset_session_value');
        Route::post('/item/edit_multiple_item/getCategories', 'Item\EditmultipleController@getCategories');
        Route::post('/item/edit_multiple_item/getSubcategories', 'Item\EditmultipleController@getSubcategories');
        Route::post('/item/edit_multiple_item/set_itemids_final', 'Item\EditmultipleController@set_itemids_final');
        Route::post('/item/edit_multiple_item/add_remove_ids', 'Item\EditmultipleController@add_remove_ids');
        Route::post('/item/edit_multiple_item/set_final_session', 'Item\EditmultipleController@set_final_session');
        
        // HQ Route
        Route::get('/item/edit_multiple_item/checkforduplicate', 'Item\EditmultipleController@checkforduplicate');


        /* ************** Item Audit************************** */
        Route::get('/item_audit', 'Item\ItemAuditController@index')->name('item_audit');
        Route::match(['get','post'],'/item_audit/getlist', ['as' => 'ItemAuditList', 'uses'=>'Item\ItemAuditController@getlist']);
    
    
        //=================Item Movement===============
        Route::get('/item/ItemMovement', 'Item\ItemMovementController@index');
        Route::post('/item/ItemMovement', 'Item\ItemMovementController@getlist');
        Route::get('/item/item_movement_report/searchitem', 'Item\ItemMovementController@searchitem');
        Route::get('/item/item_movement_report/item_movement_data', 'Item\ItemMovementController@item_movement_data');
        Route::get('/item/item_movement_report/pdf_save_page', 'Item\ItemMovementController@pdf_save_page');
        Route::get('/item/item_movement_report/print_page', 'Item\ItemMovementController@print_page');
        Route::get('/item/item_movement_report/item_movement_print_data', 'Item\ItemMovementController@item_movement_print_data');
        Route::get('/item/item_movement_report/printpage', 'Item\ItemMovementController@printpage');
        
        
        //items/pramotions
        Route::get('/promotion','PromotionController@promotion')->name('promotion');
        Route::get('/addpromotion','PromotionController@getForm')->name('addpromotion');
        Route::post('/savepromotion','PromotionController@savepromotion')->name('savepromotion');
        Route::post('/deletepromotion','PromotionController@deletePromotions')->name('deletepromotion');
        Route::get('/editpromotion','PromotionController@editForm')->name('editpromotion');
        Route::post('/updatepromotion','PromotionController@updatepromotion')->name('updatepromotion');
        Route::post('/searchpromotion','PromotionController@searchpromotion')->name('searchpromotion');
        Route::post('/searchPromotionitems','PromotionController@searchPromotionitems')->name('searchPromotionitems');
        
        Route::post('/duplicatehqPermission','PromotionController@duplicatehqPermission');
        Route::post('/checkItemExist','PromotionController@checkItemExist');
        
        Route::post('/checkPromoIdExists','PromotionController@checkPromoIdExists');
        
        Route::get('/promotion/check_promocode','PromotionController@check_promocode');
        
        Route::post('/promotion/get_item_categories','PromotionController@get_item_categories')->name('promotion.get_item_categories');
        
        Route::post('/promotion/get_items_url','PromotionController@get_items_url')->name('promotion.get_items');
        Route::post('/promotion/get_customers_url','PromotionController@get_customers_url')->name('promotion.get_customers');
        Route::post('/promotion/get_categories_url','PromotionController@get_categories_url')->name('promotion.get_item_categories');
        Route::post('/promotion/get_sub_categories_url','PromotionController@get_sub_categories_url')->name('promotion.get_sub_categories_url');
        Route::post('/promotion/get_department_items_url','PromotionController@get_department_items_url')->name('promotion.get_department_items');
        Route::post('/promotion/get_category_items_url','PromotionController@get_category_items_url')->name('promotion.get_category_items');
        Route::post('/promotion/get_sub_category_items_url','PromotionController@get_sub_category_items_url')->name('promotion.get_sub_category_items');
        Route::post('/promotion/getSelectedBuyItems','PromotionController@getSelectedBuyItems')->name('promotion.getSelectedBuyItems');
        Route::post('/promotion/getSavedItemsAjax','PromotionController@getSavedItemsAjax')->name('promotion.getSavedItemsAjax');
        Route::post('/promotion/get_selected_discounted_items_url','PromotionController@get_selected_discounted_items_url')->name('promotion.getSelectedDiscountedItems');
        Route::post('/promotion/get_saved_discounted_items_ajax_url','PromotionController@getSelectedDiscountedItemsAjax')->name('promotion.getSelectedDiscountedItemsAjax');
        Route::post('/promotion/get_group_items_url','PromotionController@get_group_items_url')->name('promotion.get_group_items');
        
        //version 3.2.0 routes for promotion
        Route::group(['prefix' => '320', 'namespace' => '\App\_320\Http\Controllers'], function(){
            Route::get('/promotion','PromotionController@promotion')->name('promotion');
            Route::get('/addpromotion','PromotionController@getForm')->name('addpromotion');
            Route::post('/savepromotion','PromotionController@savepromotion')->name('savepromotion');
            Route::post('/deletepromotion','PromotionController@deletePromotions')->name('deletepromotion');
            Route::get('/editpromotion','PromotionController@editForm')->name('editpromotion');
            Route::post('/updatepromotion','PromotionController@updatepromotion')->name('updatepromotion');
            
            Route::post('/searchpromotion','PromotionController@searchpromotion')->name('searchpromotion');
            
            Route::post('/searchPromotionitems','PromotionController@searchPromotionitems')->name('searchPromotionitems');
            
            Route::post('/duplicatehqPermission','PromotionController@duplicatehqPermission');
            Route::post('/checkItemExist','PromotionController@checkItemExist');
            
            Route::post('/checkPromoIdExists','PromotionController@checkPromoIdExists');
            
            Route::get('/promotion/check_promocode','PromotionController@check_promocode');
            
            Route::post('/promotion/get_item_categories','PromotionController@get_item_categories')->name('promotion.get_item_categories');
            
            Route::post('/promotion/get_items_url','PromotionController@get_items_url')->name('promotion.get_items');
            Route::post('/promotion/get_customers_url','PromotionController@get_customers_url')->name('promotion.get_customers');
            Route::post('/promotion/get_categories_url','PromotionController@get_categories_url')->name('promotion.get_item_categories');
            Route::post('/promotion/get_sub_categories_url','PromotionController@get_sub_categories_url')->name('promotion.get_sub_categories_url');
            Route::post('/promotion/get_department_items_url','PromotionController@get_department_items_url')->name('promotion.get_department_items');
            Route::post('/promotion/get_category_items_url','PromotionController@get_category_items_url')->name('promotion.get_category_items');
            Route::post('/promotion/get_sub_category_items_url','PromotionController@get_sub_category_items_url')->name('promotion.get_sub_category_items');
            Route::post('/promotion/getSelectedBuyItems','PromotionController@getSelectedBuyItems')->name('promotion.getSelectedBuyItems');
            Route::post('/promotion/getSavedItemsAjax','PromotionController@getSavedItemsAjax')->name('promotion.getSavedItemsAjax');
            Route::post('/promotion/get_selected_discounted_items_url','PromotionController@get_selected_discounted_items_url')->name('promotion.getSelectedDiscountedItems');
            Route::post('/promotion/get_saved_discounted_items_ajax_url','PromotionController@getSelectedDiscountedItemsAjax')->name('promotion.getSelectedDiscountedItemsAjax');
            Route::post('/promotion/get_group_items_url','PromotionController@get_group_items_url')->name('promotion.get_group_items');
            
            
        });
        
        //======================items/BuyDown modules routes===========================
        Route::get('/buydown','BuyDownController@buydown')->name('buydown');
        Route::post('/buydownsearch','BuyDownController@buydownsearch')->name('searchbuydown');
        Route::get('/buydownadd','BuyDownController@getForm')->name('addbuydown');
        Route::post('/deletebuydown','BuyDownController@deletebuydown')->name('deletebuydown');
        Route::post('/buydown/getSelectedBuyItems','BuyDownController@getSelectedBuyItems')->name('buydown.getSelectedBuyItems');
        
        
        Route::post('/buydown/search','BuyDownController@search');
        Route::post('/buydown/get_item_categories','BuyDownController@get_item_categories');
        Route::post('/buydown/get_sub_categories_url','BuyDownController@get_sub_categories_url');
        Route::post('/buydown/validate_item','BuyDownController@validate_item');
        Route::get('/buydown/getSelectedBuyItems','BuyDownController@getSelectedBuyItems');
        Route::post('/buydown/add', 'BuyDownController@add');
        Route::get('/buydown/{buydown_id}/edit', 'BuyDownController@edit')->name('buydown.edit');
        Route::post('/buydown/getSavedItemsAjax','BuyDownController@getSavedItemsAjax');
        Route::post('/buydown/update', 'BuyDownController@update')->name('buydown.update');
        
        
        Route::get('/buydown/get_items','BuyDownController@get_items');
        Route::get('/buydown/get_department_items','BuyDownController@get_department_items');
        Route::get('/buydown/get_category_items','BuyDownController@get_category_items');
        Route::get('/buydown/get_sub_category_items','BuyDownController@get_sub_category_items');
        Route::get('/buydown/getSelectedDiscountedItems','BuyDownController@getSelectedDiscountedItems');
        Route::get('/buydown/getSelectedDiscountedItemsAjax','BuyDownController@getSelectedDiscountedItemsAjax');
        Route::get('/buydown/get_group_items','BuyDownController@get_group_items');
        
        
        //Hq routes
        Route::post('/duplicatehqbuydown','BuyDownController@duplicatehqbuydown')->name('duplicatehqbuydown');
        Route::post('/editbuydownhqcheck','BuyDownController@editbuydownhqcheck')->name('editbuydownhqcheck');
        
    });
    
    
    /*================= Inventory Module Routes =========================*/ 
    Route::group(['middleware' => ['Permission:PER1007']], function(){
         /* **************  Adjustment************************** */
        Route::match(['get','post'],'/adjustment', 'AdjustmentController@index')->name('adjustment');
        Route::match(['GET','POST'],'/adjustment/edit/{ipiid}', 'AdjustmentController@edit')->name('edit');
        Route::get('/adjustment/search', 'AdjustmentController@search');
        Route::match(['GET','POST'],'/adjustment/add', 'AdjustmentController@add');
        Route::post('/adjustment/display_items_search', 'AdjustmentController@display_items_search');
        Route::post('/adjustment/add_items', 'AdjustmentController@add_items');
        Route::post('/adjustment/calculate_post', 'AdjustmentController@calculate_post');
        Route::get('/adjustment/display/{ipiid?}', 'AdjustmentController@display_items');
        Route::post('/adjustment/remove', 'AdjustmentController@remove_items');


        /* **************  Transfer ************************** */
        Route::get('/transfer/listing', 'TransferController@listing')->name('transfer');
        Route::get('/transfer', 'TransferController@index')->name('add_transfer');
        Route::get('/transfer/display_items', ['as' => 'display_items', 'uses' => 'TransferController@display_items']);
        Route::post('/transfer/add_items', 'TransferController@add_items');
        Route::post('/transfer/remove_items', 'TransferController@remove_items');
        Route::post('/transfer/edit', 'TransferController@edit');
        Route::post('/transfer/check_invoice', 'TransferController@check_invoice');

 
        //Inventory/AdustmentReason
    	Route::get('/reason','ReasonController@reason')->name('reason');
    	Route::post('/addreason','ReasonController@insertreason')->name('insertreason');
    	Route::post('/updatereason','ReasonController@update')->name('updatereason');
    	Route::post('/reasonsearch','ReasonController@reasonsearch')->name('reasonsearch');
    	
    	//=================PURCHASE ORDER=================
        Route::get('/PurchaseOrder', 'PurchaseOrderController@getList')->name('PurchaseOrder');
        Route::post('/PurchaseOrder', 'PurchaseOrderController@getSearchList');
        Route::get('/PurchaseOrder/edit', 'PurchaseOrderController@edit_form');
        Route::get('/PurchaseOrder/get_purchase_history', 'PurchaseOrderController@get_purchase_history');
        Route::get('/PurchaseOrder/get_item_history', 'PurchaseOrderController@get_item_history');
        Route::get('/PurchaseOrder/get_item_history_date', 'PurchaseOrderController@get_item_history_date');
        Route::post('/PurchaseOrder/get_search_item_history', 'PurchaseOrderController@get_search_item_history');
        Route::post('/PurchaseOrder/search_vendor_item_code', 'PurchaseOrderController@search_vendor_item_code');
        Route::post('/PurchaseOrder/add_purchase_order_item', 'PurchaseOrderController@add_purchase_order_item');
        Route::get('/PurchaseOrder/get_vendor', 'PurchaseOrderController@get_vendor');
        Route::post('/PurchaseOrder/check_invoice', 'PurchaseOrderController@check_invoice');
        Route::post('/PurchaseOrder/delete_purchase_order_item', 'PurchaseOrderController@delete_purchase_order_item');
        
        Route::post('/PurchaseOrder/edit_post', 'PurchaseOrderController@edit_post');
        Route::post('/PurchaseOrder/save_receive_item', 'PurchaseOrderController@save_receive_item');
        
        Route::get('/PurchaseOrder/export_as_pdf', 'PurchaseOrderController@export_as_pdf');
        Route::get('/PurchaseOrder/export_as_csv', 'PurchaseOrderController@export_as_csv');
        Route::get('/PurchaseOrder/export_as_email', 'PurchaseOrderController@export_as_email');
        Route::get('/PurchaseOrder/export_as_excel', 'PurchaseOrderController@export_as_excel');
        
        Route::get('/PurchaseOrder/add_manual', 'PurchaseOrderController@add_manual');
        Route::post('/PurchaseOrder/add_manual_post', 'PurchaseOrderController@add_manual_post');
        Route::post('/PurchaseOrder/delete', 'PurchaseOrderController@delete');
        Route::get('/PurchaseOrder/redirect_edit_manual', 'PurchaseOrderController@edit_form');
        
        Route::get('/PurchaseOrder/add_po_sales_history', 'PurchaseOrderController@add_po_sales_history');
        // Route::post('/PurchaseOrder/sales_history/get_item_names', 'PurchaseOrderController@get_item_names');
        Route::post('/PurchaseOrder/sales_history/get_item_names', 'SalesHistoryReportController@get_item_names');
        Route::post('/PurchaseOrder/sales_history/get_sizes', 'SalesHistoryReportController@get_sizes');
        Route::post('/PurchaseOrder/post_po_sales_history', 'PurchaseOrderController@post_po_sales_history');
        Route::get('/PurchaseOrder/redirect_edit_sales_history', 'PurchaseOrderController@add_po_sales_history');
        
        Route::get('/PurchaseOrder/add_po_par_level', 'PurchaseOrderController@add_po_par_level');
        Route::post('/PurchaseOrder/search', 'PurchaseOrderController@search');
        Route::get('/PurchaseOrder/redirect_edit_par_level', 'PurchaseOrderController@add_po_par_level');
        Route::post('/PurchaseOrder/sales_history/get_categories', 'SalesHistoryReportController@get_categories');
        Route::post('/PurchaseOrder/sales_history/get_subcategories', 'SalesHistoryReportController@get_subcategories');
        Route::post('/PurchaseOrder/sales_history/get_barcodes', 'SalesHistoryReportController@get_barcodes');
        Route::post('/PurchaseOrder/sales_history/get_skus', 'SalesHistoryReportController@get_skus');
    
    
        //=================RECEIVING ORDER=================
        Route::get('/ReceivingOrder', 'ReceivingOrderController@getList')->name('ReceivingOrder');
        Route::post('/ReceivingOrder', 'ReceivingOrderController@getSearchList');
        Route::get('/ReceivingOrder/edit', 'ReceivingOrderController@edit_form');
        Route::get('/ReceivingOrder/add', 'ReceivingOrderController@edit_form');
        Route::post('/ReceivingOrder/add_receiving_order_item', 'ReceivingOrderController@add_receiving_order_item');
        Route::post('/ReceivingOrder/transfer/check_invoice', 'ReceivingOrderController@check_invoice_transfer');
        Route::post('/ReceivingOrder/check_invoice', 'ReceivingOrderController@check_invoice');
        Route::post('/ReceivingOrder/add_post', 'ReceivingOrderController@add_post');
        Route::post('/ReceivingOrder/edit_post', 'ReceivingOrderController@edit_post');
        Route::post('/ReceivingOrder/delete_receiving_order_item', 'ReceivingOrderController@delete_receiving_order_item');
        Route::post('/ReceivingOrder/save_receive_item', 'ReceivingOrderController@save_receive_item');
        Route::get('/ReceivingOrder/get_receiving_history', 'ReceivingOrderController@get_receiving_history');
        Route::get('/ReceivingOrder/get_item_history', 'ReceivingOrderController@get_item_history');
        Route::get('/ReceivingOrder/get_item_history_date', 'ReceivingOrderController@get_item_history_date');
        Route::post('/ReceivingOrder/import_invoice_new', 'ReceivingOrderController@import_invoice_new');
        Route::post('/ReceivingOrder/import_missing_items', 'ReceivingOrderController@import_missing_items');
        Route::post('/ReceivingOrder/delete', 'ReceivingOrderController@delete');
        Route::get('/ReceivingOrder/get_vendor_data', 'ReceivingOrderController@get_vendor_data');
        Route::post('/ReceivingOrder/get_search_item_history', 'ReceivingOrderController@get_search_item_history');
            
            //==============Physical Inventroy Module Route==================
        Route::get('inventory/physicalInventroy', 'PhysicalInventroyController@index')->name('inventory.physicalInventroy');
        Route::post('inventory/physicalInventroy', 'PhysicalInventroyController@search_inventory_list');
        Route::get('inventory/physicalInventroy/create', 'PhysicalInventroyController@create')->name('inventory.physicalInventroy.create');
        Route::get('inventory/physicalInventroy/get_item_list', 'PhysicalInventroyController@get_item_list')->name('inventory.physicalInventroy.get_item_list');
        Route::post('inventory/physicalInventroy/search', 'PhysicalInventroyController@search');
        Route::get('inventory/physicalInventroy/unset_session_scanned_data', 'PhysicalInventroyController@unset_session_scanned_data');
        Route::post('inventory/physicalInventroy/remove_session_scanned_data', 'PhysicalInventroyController@remove_session_scanned_data');
        Route::get('inventory/physicalInventroy/get_scanned_data', 'PhysicalInventroyController@get_scanned_data');
        Route::post('inventory/physicalInventroy/create_session', 'PhysicalInventroyController@create_session');
        Route::post('inventory/physicalInventroy/create_scanned_session', 'PhysicalInventroyController@create_scanned_session');
        Route::post('inventory/physicalInventroy/get_categories_by_department', 'PhysicalInventroyController@get_categories_by_department');
        Route::post('inventory/physicalInventroy/get_subcat_by_categories', 'PhysicalInventroyController@get_subcat_by_categories');
        
        Route::get('inventory/physicalInventroy/edit_open', 'PhysicalInventroyController@edit_open');
        Route::get('inventory/physicalInventroy/show', 'PhysicalInventroyController@show');
        Route::get('inventory/physicalInventroy/edit_calculate_redirect', 'PhysicalInventroyController@edit_calculate');
        Route::post('inventory/physicalInventroy/getshow_data', 'PhysicalInventroyController@getshow_data');
        Route::post('inventory/physicalInventroy/addshow_data', 'PhysicalInventroyController@addshow_data');
        Route::post('inventory/physicalInventroy/commit', 'PhysicalInventroyController@commit');
        Route::post('inventory/physicalInventroy/calculate_commit', 'PhysicalInventroyController@calculate_commit');
        Route::post('inventory/physicalInventroy/snapshot', 'PhysicalInventroyController@snapshot');
        
        Route::get('inventory/physicalInventroy/selected_edit_calculate_redirect', 'PhysicalInventroyController@selected_edit_calculate');
        Route::post('inventory/physicalInventroy/selected_edit_calculate', 'PhysicalInventroyController@selected_edit_calculate');
        
        Route::get('inventory/physicalInventroy/check_status_url', 'PhysicalInventroyController@check_status');
        
        Route::get('inventory/physicalInventroy/pdf_summery', 'PhysicalInventroyController@pdf_summery');
        Route::get('inventory/physicalInventroy/pdf_details', 'PhysicalInventroyController@pdf_details');
        
        Route::post('inventory/physicalInventroy/calculate', 'PhysicalInventroyController@calculate');
        Route::post('inventory/physicalInventroy/edit_calculate', 'PhysicalInventroyController@edit_calculate');
        Route::post('inventory/physicalInventroy/import_inventory_count', 'PhysicalInventroyController@import_inventory_count');
        Route::post('inventory/physicalInventroy/display_selected_data', 'PhysicalInventroyController@display_selected_data');
        Route::post('inventory/physicalInventroy/display_selected_edit_calculate_data', 'PhysicalInventroyController@display_selected_edit_calculate_data');
        Route::post('inventory/physicalInventroy/import_display', 'PhysicalInventroyController@import_display');
        Route::post('inventory/physicalInventroy/export_csv', 'PhysicalInventroyController@export_csv');
        Route::post('inventory/physicalInventroy/bulk_import', 'PhysicalInventroyController@bulk_import');
        Route::post('inventory/physicalInventroy/create_inventory_session', 'PhysicalInventroyController@create_inventory_session');
        Route::get('inventory/physicalInventroy/unset_session_inventory_count', 'PhysicalInventroyController@unset_session_inventory_count');
        Route::post('inventory/physicalInventroy/assign_inventory_users', 'PhysicalInventroyController@assign_inventory_users');
        
        Route::get('inventory/physicalInventroy/delete_physical', 'PhysicalInventroyController@delete_physical');
        //==============End Physical Inventroy Module Route==================
        
     });
     
    /*================= Adminstration Module Routes =========================*/ 
    Route::group(['middleware' => ['Permission:PER1008']], function(){
        /* ************ Adminstration End of Day */
        Route::match(['get','post'],'/end_of_day','EndOfDayShiftController@getList')->name('end_of_day');
        Route::post('/end_of_day/batchdata_assoiciated','EndOfDayShiftController@batchdata_assoiciated');
        Route::post('/end_of_day/reopen_close_batch','EndOfDayShiftController@reopen_close_batch');
    
        /* ************ Adminstration Unit */
        Route::post('/unit/duplicateunit', 'UnitController@duplicateunit');
        
        Route::post('/unit/duplicateunitforedit', 'UnitController@duplicateunitforedit');
        
        Route::match(['get','post'],'/unit','UnitController@index')->name('unit');
        Route::match(['get','post'],'/unit/edit_list','UnitController@edit_list')->name('unit.edit_list');
        Route::post('/unit/delete','UnitController@delete')->name('unit.delete');
        Route::get('/unit/search','UnitController@search');
    
    
        //==================================Administration/Department==================================
    	Route::post('/department/duplicatedepartment', 'DepartmentController@duplicatedepartment');
        
    	Route::get('/department', 'DepartmentController@index')->name('department');
        Route::post('/department/delete', 'DepartmentController@delete')->name('department.delete');
        //Route::get('/department/search', 'DepartmentController@search')->name('department.search');
        Route::post('/departmentsearch', 'DepartmentController@departmentsearch')->name('department.search');
        Route::post('/department/edit_list', 'DepartmentController@edit_list')->name('department.edit_list');
        Route::post('/department/add_list', 'DepartmentController@add_list')->name('department.add_list');
        
        
        // Route::get('/category', 'CategoryController@index')->name('category');
        // Route::post('/category/edit_list', 'CategoryController@edit_list')->name('category.edit_list');
        // Route::post('/category/delete', 'CategoryController@delete')->name('category.delete');
        // Route::post('/category/store', 'CategoryController@store')->name('category.store');
        // Route::get('/category/search', 'CategoryController@search')->name('category.search');
        // Route::post('/category/show', 'CategoryController@show')->name('category.show');
        // Route::get('/category/getsubcat', 'CategoryController@getSubCategoriesByCatId')->name('category.getsubcat');
        
        Route::post('/category/duplicatecategory', 'CategoryController@duplicatecategory');
        
        Route::get('/category', 'CategoryController@index')->name('category');
        Route::post('/category/edit_list', 'CategoryController@edit_list')->name('category.edit_list');
        Route::post('/category/delete', 'CategoryController@delete')->name('category.delete');
        //Route::post('/category/store', 'CategoryController@store')->name('category.store');
        Route::post('/category/add_list','CategoryController@add_list')->name('category.add_list');
        Route::get('/category/search', 'CategoryController@search')->name('category.search');
        Route::post('/category/show', 'CategoryController@show')->name('category.show');
        Route::get('/category/getsubcat', 'CategoryController@getSubCategoriesByCatId')->name('category.getsubcat');
    
        Route::post('/subcategory/duplicatesubcategory', 'SubCategoryController@duplicatesubcategory');
    
        Route::get('/subcategory', 'SubCategoryController@index')->name('subcategory');
       // Route::post('/subcategory/store', 'SubCategoryController@store')->name('subcategory.store');
        Route::post('/subcategory/add_list', 'SubCategoryController@add_list')->name('subcategory.add_list');
        // Route::post('/deletesubcategory', 'SubCategoryController@delete')->name('subcategory.delete');
        Route::get('/subcategory/search', 'SubCategoryController@search')->name('subcategory.search');
        Route::post('/subcategory/show', 'SubCategoryController@show')->name('subcategory.show');
        Route::post('/subcategory/edit_list', 'SubCategoryController@edit_list')->name('subcategory.edit_list');  
        Route::post('/subcategory/delete', 'SubCategoryController@delete')->name('subcategory.delete');  
        
    
    	// Administration/Manufacturer
    	Route::post('/manufacturer/duplicatemanufacurer', 'ManufacturerController@duplicatemanufacturer');
    	
    	Route::get('/manufacturer','ManufacturerController@manufacturer')->name('manufacturer');
    	Route::post('/addmanufacturer','ManufacturerController@insertmanufacturer')->name('insertmanufacturer');
    	Route::post('/manufacturer/edit_list','ManufacturerController@edit_list')->name('manufacturer.edit_list');
        // Route::post('/manufacturer/updatemanufacturer','ManufacturerController@update')->name('updatemanufacturer');
    	Route::post('/manufacturersearch','ManufacturerController@manufacturersearch')->name('manufacturersearch');
    	Route::post('/deletemanufacturer','ManufacturerController@deletemanufacturer')->name('manufacturer.delete');
    
    
        //Administration/Aisle 
    	Route::get('/aisle','AisleController@aisle')->name('aisle');
    	Route::post('/addaisle','AisleController@insertaisle')->name('insertaisle');
    	Route::post('/updateaisle','AisleController@update')->name('updateaisle');
    	Route::post('/aislesearch','AisleController@aislesearch')->name('aislesearch');
    	Route::post('/deleteaisle','AisleController@deleteaisle')->name('deleteaisle');
    
        // Administration/Shelf
    	Route::get('/shelf','ShelfController@shelf')->name('shelf');
    	Route::post('/addshelf','ShelfController@insertshelf')->name('addshelf');
    	Route::post('/deleteshelf','ShelfController@deleteshelf')->name('deleteshelf');
    	Route::post('/updateshelf','ShelfController@update')->name('updateshelf');
    	Route::post('/shelfsearch','ShelfController@shelfsearch');
    	
    
    	// Administration/Shelving 
    	Route::get('/shelving','ShelvingController@shelving')->name('shelving');
    	Route::post('/addshelving','ShelvingController@insertshelving')->name('insertshelving');
    	Route::post('/updateshelving','ShelvingController@update')->name('updateshelving');
    	Route::post('/shelvingsearch','ShelvingController@shelvingsearch')->name('shelvingsearch');
        Route::post('/deleteshelving','ShelvingController@deleteshelving')->name('deleteshelving');
        
        
    	/// Administration/Size 
    	Route::post('/size/duplicatesize', 'SizeController@duplicatesize');
    
    	Route::get('/size','SizeController@size')->name('size');
    	Route::post('/addsize','SizeController@insertsize')->name('insertsize');
    	Route::post('/size/edit_list','SizeController@edit_list')->name('size.edit_list');
    	Route::post('sizesearch','SizeController@sizesearch')->name('sizesearch');
    	Route::post('/deletesize','SizeController@delete')->name('size.delete');
        
    
    	Route::get('/tax','TaxController@tax')->name('tax');
    	Route::post('/updatetax','TaxController@updatetax')->name('updatetax');
    	Route::post('/duplicatetax','TaxController@duplicatetax' );
    
    
        // Administration/Paidout 
    	Route::get('/paidout','PaidoutController@paidout')->name('paidout');
    	Route::post('/addpaidout','PaidoutController@insertpaidout')->name('insertpaidout');
    	Route::post('/updatepaidout','PaidoutController@update')->name('updatepaidout');
    	Route::post('/paidoutsearch','PaidoutController@paidoutsearch')->name('paidoutsearch');
    	Route::post('/deletepaidout','PaidoutController@deletepaidout')->name('deletepaidout');
    
    
        /* *********** Administration Store Setting ******** */
        Route::get('/store_setting','StoreSettingsController@index')->name('store_setting');
        Route::get('/store_setting/get_time','StoreSettingsController@get_time');
        Route::post('/store_setting/edit_list','StoreSettingsController@edit_list');
    
    
    	Route::get('/ageverify','AgeVerificationController@getlist')->name('ageverify');
    	Route::get('/ageverifyupdate','AgeVerificationController@ageverifyupdate')->name('ageverifyupdate');
    	Route::post('/ageverification', 'AgeVerificationController@update');
    	Route::post('/ageverifysearch','AgeVerificationController@ageverifysearch');
    	
    	Route::post('/duplicateage', 'AgeVerificationController@duplicateage');
    	
    	
    });
    
    /*================= Reports item AUdit new dev opencart to laravel =========================*/ 
      Route::get('/item_audit_list', 'ItemAuditListController@index')->name('ItemAuditList');
      Route::get('/item_audit_list/getlist', 'ItemAuditListController@getlist')->name('ItemAuditListForm');
      Route::get('/item_audit_list/department', 'ItemAuditListController@getDepartment')->name('ItemAuditListdepartment');
      Route::get('/item_audit_list/cat', 'ItemAuditListController@getcat')->name('ItemAuditListcat');
      Route::get('/item_audit_list/user_email', 'ItemAuditListController@getuseremail')->name('ItemAuditListUserID');
      
      Route::get('/item_audit_list/pdf','ItemAuditListController@Pdf')->name('ItemAuditListpdf');
      Route::get('/item_audit_list/csv','ItemAuditListController@csv')->name('ItemAuditListcsv');
      Route::get('/item_audit_list/print','ItemAuditListController@print')->name('ItemAuditListprint');
      
     
    /*================= Reports Module Routes =========================*/ 
    Route::group(['middleware' => ['Permission:PER1009']], function(){
        Route::get('/eodreport', 'EndOfDayReportController@index')->name('EodReport');
        Route::post('/eodreport/getlist', 'EndOfDayReportController@getlist')->name('EodForm');
        Route::get('/eodreport/print_pdf','EndOfDayReportController@eodPdf')->name('Eodpdf');
        Route::get('/eodreport/csv','EndOfDayReportController@csv')->name('Eodcsv');
        Route::get('/eodreport/print','EndOfDayReportController@print')->name('Eodprint');
    
        // end of shift report
        Route::get('/eodshift', 'EndShiftController@index')->name('EodShift');
        Route::get('/eodshift/batch', 'EndShiftController@batchdata')->name('EodBatch');
        Route::post('/eodshift/getlist', 'EndShiftController@getlist')->name('ShiftForm');
    
        Route::get('/eodshift/print_pdf','EndShiftController@eodPdf')->name('Eodshiftpdf');
        Route::get('/eodshift/print','EndShiftController@print')->name('Eodshiftprint');
        Route::get('/eodshift/csv','EndShiftController@csv')->name('Eodshiftcsv');
        
        
        //CreditCardReport Routes start
        Route::get('/cardreport', 'CreditCardReportController@index')->name('CardReport');
        Route::get('/cardreport/getlist', 'CreditCardReportController@getlist')->name('CardReportForm');
        Route::get('/saleid_card', 'CreditCardReportController@getsaleid')->name('Cardvalue');
        Route::get('/cardreport/print','CreditCardReportController@print')->name('Cardprint');
        Route::get('/cardreport/printview','CreditCardReportController@printpreview')->name('CardprintView');

        Route::get('/cardreport/print_pdf','CreditCardReportController@pdf')->name('ccpdf');
        Route::get('/cardreport/ccprint','CreditCardReportController@ccprint')->name('ccprint');
        Route::get('/cardreport/csv','CreditCardReportController@csv')->name('cccsv');
        
        
    
        /* *************Scan Data Report ************* */
        Route::match(['get','post'],'/scan_data_report','ScanDataReportController@index')->name('scan_data_report');
        Route::post('/scan_data_report/reconcile_scan_data','ScanDataReportController@reconcile_scan_data');
        Route::post('/scan_data_report/get_categories','ScanDataReportController@get_categories');
    
    
         // SalesTransaction Reports Routes  start
        Route::get('/salestransaction', 'SalesTransactionController@index')->name('SalesTransaction');
        Route::get('/salestransaction/getlist', 'SalesTransactionController@getlist')->name('SalesTransactionForm');
        Route::get('/saleid', 'SalesTransactionController@getsaleid')->name('Salevalue');
        Route::get('/salestransaction/print','SalesTransactionController@print')->name('saleprint');
    
        //Tax Report Route Start
        Route::get('/taxreport', 'TaxReportController@index')->name('TaxReport');
        Route::post('/taxreport/getlist', 'TaxReportController@getlist')->name('TaxReportForm');
        Route::get('/taxreport/print_pdf','TaxReportController@eodPdf')->name('Taxpdf');
        Route::get('/taxreport/print','TaxReportController@print')->name('Taxprint');
        Route::get('/taxreport/csv','TaxReportController@csv')->name('Taxcsv');
        
          //hourly slaes report
        Route::get('/hrsalesreport', 'HourlySalesReportController@index')->name('HourlySalesReport');
        Route::post('/hrsalesreport/getlist', 'HourlySalesReportController@getlist')->name('HourlySalesForm');
        
        Route::get('/hrsalesreport/print_pdf','HourlySalesReportController@eodPdf')->name('HourlySalespdf');
        Route::get('/hrsalesreport/print','HourlySalesReportController@print')->name('HourlySalesprint');
        Route::get('/hrsalesreport/csv','HourlySalesReportController@csv')->name('HourlySalescsv');
        
        Route::get('/hrsalesreport/getcategories','HourlySalesReportController@getcategories')->name('Hourlygetcategories');
        Route::get('/hrsalesreport/get_subcategories','HourlySalesReportController@get_subcategories')->name('Hourlyget_subcategories');
        Route::get('/hrsalesreport/getslotitem','HourlySalesReportController@getslotitem')->name('Getslotitem');
        
        
       //POHistoryReport Route Start
        Route::get('/poreport', 'POHistoryReportController@index')->name('POReport');
        Route::post('/poreport/getlist', 'POHistoryReportController@getlist')->name('POForm');
        Route::get('/poreport/printview','POHistoryReportController@printpreview')->name('view_item_url');
        Route::get('/poreport/print_pdf','POHistoryReportController@eodPdf')->name('POpdf');
        Route::get('/poreport/print','POHistoryReportController@print')->name('POprint');
        Route::get('/poreport/csv','POHistoryReportController@csv')->name('POcsv');
        
    
        //InventoryReport Routes********************************************************************
         Route::get('/inventoryreport', 'InventoryReportController@index')->name('InventoryReport');
         Route::get('/inventoryreport/getlist_inventoryreport', 'InventoryReportController@getlist')->name('InventoryReportForm');
         
         
         Route::get('/inventoryreport/print','InventoryReportController@print')->name('InventoryReportprint');
         
         Route::get('/inventoryreport/csv','InventoryReportController@csv')->name('InventoryReportcsv');
         Route::get('/inventoryreport/cat', 'InventoryReportController@cat')->name('InvCategory');
         Route::get('/inventoryreport/sub', 'InventoryReportController@get_subcategories')->name('InvSubCategory');
         
         Route::get('/inventoryreport/invpositive', 'InventoryReportController@report_ajax_data')->name('InvReport_Positive');
          Route::get('/inventoryreport/invzero', 'InventoryReportController@report_ajax_data_zero')->name('InvReport_zero');
         
         Route::get('/inventoryreport/invnegative', 'InventoryReportController@report_ajax_data_N')->name('InvReport_N');
         
         Route::get('/inventoryreport/print_page', 'InventoryReportController@print_page')->name('print_page_inv');
         Route::get('/inventoryreport/print_pdf','InventoryReportController@eodPdf')->name('InventoryReportpdf');
         Route::get('/inventoryreport/csv','InventoryReportController@csv')->name('InventoryReportcsv');
         
         
         Route::get('/inventoryreport/print_page_n', 'InventoryReportController@print_page_N')->name('print_page_inv_N');
         Route::get('/inventoryreport/print_pdf_n','InventoryReportController@eodPdf_N')->name('InventoryReportpdf_N');
         Route::get('/inventoryreport/csv_n','InventoryReportController@csv_N')->name('InventoryReportcsv_N');
         
         Route::get('/inventoryreport/print_page_z', 'InventoryReportController@print_page_Z')->name('print_page_inv_Z');
         Route::get('/inventoryreport/print_pdf_z','InventoryReportController@eodPdf_Z')->name('InventoryReportpdf_Z');
         Route::get('/inventoryreport/csv_z','InventoryReportController@csv_Z')->name('InventoryReportcsv_Z');
    
        
        //ProfitLossReport Route Start
        Route::get('/profitreport', 'ProfitLossReportController@index')->name('ProfitReport');
        Route::post('/profitreport/getlist', 'ProfitLossReportController@getlist')->name('ProfitForm');
        
        Route::get('/profitreport/print_pdf','ProfitLossReportController@eodPdf')->name('Profitpdf');
        Route::get('/profitreport/print','ProfitLossReportController@print')->name('Profitprint');
        Route::get('/profitreport/csv','ProfitLossReportController@csv')->name('Profitcsv');
        
        
        //PaidoutReport Route Start
        Route::get('/paidoutreport', 'PaidOutReportController@index')->name('PaidoutReport');
        Route::post('/paidoutreport/getlist_paidout', 'PaidOutReportController@getlist')->name('PaidoutForm');
        
        Route::get('/paidoutreport/print_pdf','PaidOutReportController@eodPdf')->name('Paidoutpdf');
        Route::get('/paidoutreport/print','PaidOutReportController@print')->name('Paidoutprint');
        Route::get('/paidoutreport/csv','PaidOutReportController@csv')->name('Paidoutcsv');
        
        
        //******************* BelowCostReporRoute Start belowcostreport*************************************
        Route::get('/belowcostreport', 'BelowCostReportController@index')->name('BelowCostReport');
        Route::get('/belowcostreport/getlist_belowcostreport', 'BelowCostReportController@getlist')->name('BelowCostReportForm');
         
        Route::get('/belowcostreport/print_pdf','BelowCostReportController@eodPdf')->name('BelowCostReportpdf');
        Route::get('/belowcostreport/print','BelowCostReportController@print')->name('BelowCostReportprint');
        Route::get('/belowcostreport/csv','BelowCostReportController@csv')->name('BelowCostReportcsv');
    
    
        // Report/Sales Report
        Route::get('/salesreport', 'SalesReportController@index')->name('salesreport');
        Route::post('/salesreport', 'SalesReportController@getlist')->name('salesreport');
        Route::get('/salesreportcsv_export', 'SalesReportController@csv_export')->name('salesreportcsv_export');
        Route::get('/salesreportprint_page', 'SalesReportController@print_page')->name('salesreportprint_page');
        Route::get('/salesreportpdf_save_page', 'SalesReportController@pdf_save_page')->name('salesreportpdf_save_page');
        Route::get('/salesreportgetreportdata', 'SalesReportController@reportdata')->name('salesreportgetreportdata');    
        
    
        // ProductListing
        Route::get('/productlisting', 'ProductListingController@index')->name('productlisting');
        Route::post('/productlisting', 'ProductListingController@getlist')->name('productlisting');
        Route::post('/productlisting/checkpassword', 'ProductListingController@check_password')->name('productlisting_checkpassword');
        
    
        //===============Epmloyee  loss report=================
        Route::get('/employeereport', 'EmployeeReportController@index')->name('employeereport');
        // Route::post('/employeereport', 'EmployeeReportController@getlist')->name('employeereport');
        Route::get('/csv_export', 'EmployeeReportController@csv_export')->name('csv_export');
        Route::get('/print_page', 'EmployeeReportController@print_page')->name('print_page');
        Route::get('/pdf_save_page', 'EmployeeReportController@pdf_save_page')->name('pdf_save_page');
        Route::get('/send_mail', 'EmployeeReportController@send_mail')->name('send_mail');
    
    
        // Report/ItemSummary Report
        Route::get('/itemsummary', 'ItemSummaryController@index')->name('itemsummary');
        Route::post('/itemsummary', 'ItemSummaryController@getlist')->name('itemsummary');
        Route::get('/itemsummarycsv_export', 'ItemSummaryController@csv_export')->name('itemsummarycsv_export');
        Route::get('/itemsummaryprint_page', 'ItemSummaryController@print_page')->name('itemsummaryprint_page');
        Route::get('/itemsummarypdf_save_page', 'ItemSummaryController@pdf_save_page')->name('itemsummarypdf_save_page');
          //=============Zero Movement Report=====================
        Route::get('/zeromovementreport', 'ZeroMovementController@index')->name('zeromovementreport');
        Route::post('/zeromovementreport', 'ZeroMovementController@index');
    
        // Route::post('/zeromovementreport', 'ZeroMovementController@getlist')->name('zeromovementreport');
        Route::get('/zeromovementreportreport_ajax_data', 'ZeroMovementController@report_ajax_data')->name('zeromovementreportreport_ajax_data');
        Route::get('/zeromovementreportreportdata', 'ZeroMovementController@reportdata')->name('zeromovementreportreportdata');
        Route::get('/zeromovementreportprint_page', 'ZeroMovementController@print_page')->name('zeromovementreportprint_page');
        Route::get('/zeromovementreportpdf_save_page', 'ZeroMovementController@pdf_save_page')->name('zeromovementreportpdf_save_page');
        Route::get('/zeromovementreportgetcategories', 'ZeroMovementController@getcategories')->name('zeromovementreportgetcategories');
        Route::get('/zeromovementreportget_subcategories', 'ZeroMovementController@get_subcategories')->name('zeromovementreportget_subcategories');
        Route::post('/zeromovementreportdelete', 'ZeroMovementController@delete')->name('zeromovementreportdelete');
        Route::post('/zeromovementreportupdate_dectivate', 'ZeroMovementController@Update_dectivate')->name('zeromovementreportupdate_dectivate');
        Route::get('/zeromovementreportcsv_export', 'ZeroMovementController@csv_export')->name('zeromovementreportcsv_export');
        
        
                //delete all functinality URL
        Route::get('/zeromovementreport_delete_all', 'ZeroMovementController@deleteAll')->name('zeromovement_DeleteAll');
    
        // Report/Saleshistory Report
        Route::get('/saleshistoryreport', 'SalesHistoryReportController@index')->name('saleshistoryreport');
        Route::post('/saleshistoryreport', 'SalesHistoryReportController@getlist')->name('saleshistoryreport');
        Route::post('/saleshistoryreport_gettingsku', 'SalesHistoryReportController@get_skus')->name('saleshistoryreport_gettingsku');
        Route::get('/saleshistoryreport_getcategory', 'SalesHistoryReportController@get_categories')->name('saleshistoryreport_getcategory');
        Route::get('/saleshistoryreport_getsubcategory', 'SalesHistoryReportController@get_subcategories')->name('saleshistoryreport_getsubcategory');
        Route::get('/saleshistoryreport_getreportdata', 'SalesHistoryReportController@reportdata')->name('saleshistoryreport_getreportdata');
        Route::get('/saleshistoryreport_getiteamname', 'SalesHistoryReportController@get_item_names')->name('saleshistoryreport_getiteamname');
        Route::get('/saleshistoryreport_getbarcode', 'SalesHistoryReportController@get_barcodes')->name('saleshistoryreport_getbarcode');
        Route::get('/saleshistoryreport_getsize', 'SalesHistoryReportController@get_sizes')->name('saleshistoryreport_getsize');
        Route::get('/saleshistoryreport_gettingCSV', 'SalesHistoryReportController@csv_export')->name('saleshistoryreport_gettingCSV');
        Route::get('/saleshistoryreport_savePDF', 'SalesHistoryReportController@pdf_save_page')->name('saleshistoryreport_savePDF');  
        Route::get('/saleshistoryreport_getprintpage', 'SalesHistoryReportController@print_page')->name('saleshistoryreport_getprintpage');
        
        
        Route::get('/SalesHistoryReportController/csv','SalesHistoryReportController@csv')->name('salehistorycsv');
       
        Route::get('/SalesHistoryReportController/email','SalesHistoryReportController@send_email')->name('salehistoryemail');
    
    
    
        // Report/Salesanalytics Report
        Route::get('/salesanalyticsreport', 'SalesAnalyticsReportController@index')->name('salesanalyticsreport');
        Route::post('/salesanalyticsreport', 'SalesAnalyticsReportController@getlist')->name('salesanalyticsreport');
        Route::post('/salesanalyticsreport_getting', 'SalesAnalyticsReportController@get_reports')->name('salesanalyticsreport_getting');
        Route::get('/salesanalyticsreport_gettingCSV', 'SalesAnalyticsReportController@get_csv')->name('salesanalyticsreport_gettingCSV');
        Route::get('/salesanalyticsreport_savePDF', 'SalesAnalyticsReportController@pdf_save_page')->name('salesanalyticsreport_savePDF');
        Route::get('/salesanalyticsreport_getcategory', 'SalesAnalyticsReportController@getcategories')->name('salesanalyticsreport_getcategory');
        Route::get('/salesanalyticsreport_getsubcategory', 'SalesAnalyticsReportController@get_subcategories')->name('salesanalyticsreport_getsubcategory');
        Route::get('/salesanalyticsreport_getprintpage', 'SalesAnalyticsReportController@print_page')->name('salesanalyticsreport_getprintpage');

    });
    
    /*================= General Module Routes =========================*/ 
    Route::group(['middleware' => ['Permission:PER1010']], function(){
        // General/Download
        Route::get('/download', 'DownloadController@index')->name('download');
        
        // General/UpcConversion
        Route::get('/upc_conversion', 'UpcConversionController@index')->name('upc_conversion');
        Route::post('/upc_conversion/store', 'UpcConversionController@store')->name('upc_conversion.add');
    });
    
    Route::group(['middleware' => ['Permission:PER1012']], function(){
        
        // Settings/Item List Display
        Route::get('/itemlistdisplay', 'ItemListDisplaysController@index')->name('itemlistdisplay');
        Route::post('/itemlistdisplay/edit', 'ItemListDisplaysController@edit')->name('itemlistdisplay.edit');

         // Settings/POS Settings
        Route::get('/end_of_shift_printing', 'EndOfShiftPrintingController@index')->name('end_of_shift_printing');
        Route::post('/end_of_shift_printing/store', 'EndOfShiftPrintingController@store')->name('end_of_shift_printing.store');

        
        // Settings/FTP
        Route::get('/ftpsetting', 'FTPSettingController@index')->name('ftpsetting');
        Route::post('/ftpsetting/create', 'FTPSettingController@create')->name('ftpsetting.create');
        Route::post('/ftpsetting/edit', 'FTPSettingController@edit')->name('ftpsetting.edit');
        Route::get('/ftpsetting/delete', 'FTPSettingController@destroy')->name('ftpsetting.delete');
        Route::get('/ftpsetting/search', 'FTPSettingController@search')->name('ftpsetting.search');
        Route::get('/ftpsetting/getftpsettings', 'FTPSettingController@get_ftp_settings')->name('ftpsetting.getftpsettings');
        Route::get('/ftpsetting/getcategories', 'FTPSettingController@getcategories')->name('ftpsetting.getcategories');
    });
    
    //vendor MOdel Route
    // Route::get('vendors', 'VendorController@index')->name('vendors');
    // Route::get('vendors/create', 'VendorController@create')->name('vendors.create');
    // Route::post('vendors/store', 'VendorController@store')->name('vendors.store');
    // Route::post('vendors/remove', 'VendorController@remove')->name('vendors.remove');
    // Route::get('vendors/{isupplierid}/edit', 'VendorController@edit' )->name('vendors.edit');
    // Route::patch('vendors/update/{isupplierid}', 'VendorController@update' )->name('vendors.update');

    //customer Module route
    // Route::get('customers', 'CustomerController@index')->name('customers');
    // Route::post('customers/remove', 'CustomerController@remove')->name('customers.remove');
    // Route::get('customers/create', 'CustomerController@create')->name('customers.create');
    // Route::post('customers/store', 'CustomerController@store')->name('customers.store');
    // Route::get('customers/{icustomerid}/edit', 'CustomerController@edit' )->name('customers.edit');
    // Route::patch('customers/update/{icustomerid}', 'CustomerController@update' )->name('customers.update');
    
});
Route::get('whatsnew', 'WhatsnewController@index')->name('whatsnew');








