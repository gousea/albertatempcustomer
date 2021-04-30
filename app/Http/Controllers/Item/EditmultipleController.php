<?php

namespace App\Http\Controllers\Item;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use App\Model\Item;
use App\Model\Olditem;
use App\Model\WebAdminSetting;
use App\Model\Department;
use App\Model\Category;
use App\Model\Manufacturer;
use App\Model\Unit;
use App\Model\Supplier;
use App\Model\Size;
use App\Model\SubCategory;
use App\Model\ItemGroup;
use App\Model\AgeVerification;
use App\Model\EditMultipleItems;

class EditmultipleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getList(Request $request) 
    {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        
        $input = $request->all();
        
        $data['items'] = array();
        // $data['search_radio'] = $search_radio;
        // $data['search_find'] = $search_find;
        
 
        $error_warning = session()->get('warning');
        if (isset($error_warning)) {
            $data['error_warning'] = session()->get('warning');

            session()->forget('warning');
        } else {
            $data['error_warning'] = '';
        }
        
        $success = session()->get('success');
        if (isset($success)) {
            $data['success'] = session()->get('success');

            session()->forget('success');
        } else {
            $data['success'] = '';
        }

        if (isset($input['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
         
        $new_database = session()->get('new_database');
        $data['new_database'] = $new_database;
        //dd($data['new_database']);
        if($new_database === false){
             // =============================================================== OLD DATABASE ======================================================
            $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        
            $data['departments'] = $departments;
             
            $categories = Category::orderBy('vcategoryname', 'ASC')->get()->toArray();
            $data['categories'] = $categories;
             
             /*units added for edit multiple itme*/
            $units = Unit::all()->toArray();
            $data['units'] = $units;
     
            $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
             
            $data['suppliers'] = $suppliers;

            $sizes = Size::all()->toArray();
        
            $data['sizes'] = $sizes;
     
            $itemGroups = ItemGroup::orderBy('vitemgroupname')->get()->toArray();
     
            $ageVerifications = AgeVerification::all()->toArray();
             
            $data['ageVerifications'] = $ageVerifications;
             
            $data['itemGroups'] = $itemGroups;
     
            $itemsUnits = DB::connection('mysql_dynamic')->table('mst_item_unit')->get()->toArray();
            $itemsUnits = array_map(function ($value) {
                return (array)$value;
            }, $itemsUnits);
            $data['itemsUnits'] = $itemsUnits;
     
            $buckets = DB::connection('mysql_dynamic')->table('mst_item_bucket')->get()->toArray();
            $buckets = array_map(function ($value) {
                return (array)$value;
            }, $buckets);
            $data['buckets'] = $buckets;
        } else {
           
            $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        
            $data['departments'] = $departments;
             
            $categories = Category::orderBy('vcategoryname', 'ASC')->get()->toArray();
            $data['categories'] = $categories;
                   
            $subcategories = SubCategory::orderBy('subcat_name')->get()->toArray();
            $data['subcategories'] = $subcategories;
             
            $manufacturers = Manufacturer::orderBy('mfr_name')->get()->toArray();
            $data['manufacturers'] = $manufacturers;
             
            $aisles = DB::connection('mysql_dynamic')->table('mst_aisle')->get()->toArray();
            //====converting object data into array=====
            $aisles = array_map(function ($value) {
                return (array)$value;
            }, $aisles);
            $data['aisles'] = $aisles;
             
            $shelfs = DB::connection('mysql_dynamic')->table('mst_shelf')->get()->toArray();
            //====converting object data into array=====
            $shelfs = array_map(function ($value) {
                return (array)$value;
            }, $shelfs);
            $data['shelfs'] = $shelfs;
             
            $shelvings = DB::connection('mysql_dynamic')->table('mst_shelving')->get()->toArray();
            //====converting object data into array=====
            $shelvings = array_map(function ($value) {
                return (array)$value;
            }, $shelvings);
            $data['shelvings'] = $shelvings;
     
             
            /*units added for edit multiple itme*/
            $units = Unit::all()->toArray();
            $data['units'] = $units;
     
            $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
             
            $data['suppliers'] = $suppliers;

            $sizes = Size::all()->toArray();
        
            $data['sizes'] = $sizes;
     
            $itemGroups = ItemGroup::orderBy('vitemgroupname')->get()->toArray();
     
            $ageVerifications = AgeVerification::all()->toArray();
             
            $data['ageVerifications'] = $ageVerifications;
             
            $data['itemGroups'] = $itemGroups;
     
            $itemsUnits = DB::connection('mysql_dynamic')->table('mst_item_unit')->get()->toArray();
            $itemsUnits = array_map(function ($value) {
                return (array)$value;
            }, $itemsUnits);
            $data['itemsUnits'] = $itemsUnits;
     
            $buckets = DB::connection('mysql_dynamic')->table('mst_item_bucket')->get()->toArray();
            $buckets = array_map(function ($value) {
                return (array)$value;
            }, $buckets);
            $data['buckets'] = $buckets; 
        }
        
        $data['array_yes_no']['Y'] = 'Yes'; 
        $data['array_yes_no']['N'] = 'No';
 
        $data['arr_y_n'][] = 'No';
        $data['arr_y_n'][] = 'Yes';  

        $data['array_updates']['No'] = '-- No Update --'; 
        $data['array_updates']['Yes'] = 'Update';
 
        $data['array_status']['no-update'] = '-- No Update --'; 
        $data['array_status']['Active'] = 'Active'; 
        $data['array_status']['Inactive'] = 'Inactive'; 
 
        $data['item_types'][] = 'Standard';
        $data['item_types'][] = 'Kiosk';
        $data['item_types'][] = 'Lot Matrix';
        $data['item_types'][] = 'Gasoline';
        $data['item_types'][] = 'Instant';
 
        $data['barcode_types'][] = 'Code 128';
        $data['barcode_types'][] = 'Code 39';
        $data['barcode_types'][] = 'Code 93';
        $data['barcode_types'][] = 'UPC E';
        $data['barcode_types'][] = 'EAN 8';
        $data['barcode_types'][] = 'EAN 13';
        $data['barcode_types'][] = 'UPC A'; 
 
        
        $departments_html ="";
        $departments_html = "<select class='' name='dept_code' id='dept_code' style='padding: 0px;font-size: 9px;width: 80px;height:28px;color:#000000'>'<option value='all'>All</option>";
        foreach($departments as $department){
            if(isset($vdepcode) && $vdepcode == $department['vdepcode']){
                $departments_html .= "<option value='".$department['vdepcode']."' selected='selected'>".$department['vdepartmentname']."</option>";
            } else {
                $departments_html .= "<option value='".$department['vdepcode']."'>".$department['vdepartmentname']."</option>";
            }
        }
        $departments_html .="</select>";
         
        $data['departments_html'] = $departments_html;
        
        //====Item Group===============
        $itemgroups_html ="";
        $itemgroups_html = "<select class='' name='item_group' id='item_group_id' style='padding: 0px;font-size: 9px;width: 80px;height:28px;color:#000000'>'<option value='all'>All</option>";
        foreach($data['itemGroups'] as $itemgroup){
             
            $itemgroups_html .= "<option value='".$itemgroup['iitemgroupid']."'>".$itemgroup['vitemgroupname']."</option>";
        }
        $itemgroups_html .="</select>";
         
        $data['itemgroups_html'] = $itemgroups_html;
         
        //=====Supplier=========
        $supplier_html ="";
        $supplier_html = "<select class='' name='supplier_code' id='supplier_code' style='padding: 0px; font-size: 9px;width: 80px;color:#000000;height:28px;'>'<option value='all'>All</option>";
        foreach($suppliers as $supplier){
            $supplier_html .= "<option value='".$supplier['vsuppliercode']."'>".$supplier['vcompanyname']."</option>";
        }
        $supplier_html .="</select>";

        $data['suppliers_html'] = $supplier_html;
        // dd($supplier_html);

        $food_item_html = "<select class='' name='food_item' id='food_item' style='padding: 0px; font-size: 9px;width: 80px;color:#000000;height:28px;'>";
        $food_item_html .= "<option value='all'>All</option>";
        $food_item_html .= "<option value='Y'>Yes</option>";
        $food_item_html .= "<option value='N'>No</option>";
        $food_item_html .= "</select>";
         
        $data['food_item_html'] = $food_item_html;


        $tax_html = "<select class='' name='tax' id='tax' style='padding: 0px; font-size: 9px;width: 80px;color:#000000;height:28px;'>";
        $tax_html .= "<option value='all'>All Taxes</option>";
        $tax_html .= "<option value='tax1'>Tax 1</option>";
        $tax_html .= "<option value='tax2'>Tax 2</option>";
        $tax_html .= "<option value='tax3'>Tax 3</option>";
        $tax_html .= "<option value='no'>No Tax</option>";
        $tax_html .= "</select>";

        $data['tax_html'] = $tax_html;
         
         
        //==================================== for price filter ====================================================
         
        $price_select_by_list = array(
                                'greater'   => 'Greater than',
                                'less'      => 'Less than',
                                'equal'     => 'Equal to',
                                'between'   => 'Between'
                            );        

        // if($input['price_select_by'] === 'between'){
        //     $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='color:#000000; width:77px;'>";
        // } else {
        //     $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='color:#000000; width:100px;'>";
        // }

        $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='color:#000000; width:100px;'>";
        

        foreach($price_select_by_list as $k => $v){
            $price_select_by_html .= "<option value='".$k."'";
            
            if(isset($data['price_select_by']) && $k === $data['price_select_by']){
                $price_select_by_html .= " selected";
            }
            
            $price_select_by_html .= ">".$v."</option>";
        }
        $price_select_by_html .= "</select>";
        $price_select_by_html .= "<span id='selectByValuesSpan'>";

        // if($input['price_select_by'] === 'between'){
        //     // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
        //     $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='' style='color:#000000;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;width:43px;' value='".$data['select_by_value_1']."'/>";
        //     $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='' style='color:#000000;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;width:43px;' value='".$data['select_by_value_2']."'/>";
        // } else {
        //     $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='' style='color:#000000;height:28px;margin-left:5px;width:68px;' value='".$data['select_by_value_1']."'/>";
        // }
        $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='' style='color:#000000;height:28px;margin-left:5px;width:68px;' value=''/>";
        

        $price_select_by_html .= "</span>"; 

        $data['price'] = $price_select_by_html;

 
        $data['searchitem'] = url('/item/edit_multiple_item/searchItems');
        $data['get_categories_url_index'] = url('/item/edit_multiple_item/get_department_categories');
        $data['get_sub_categories_url'] = url('/item/edit_multiple_item/get_sub_categories_url');


        // $data['add'] = url('/item/edit_multiple_item/add');
        // $data['edit'] = url('/item/edit_multiple_item/edit');
        // $data['delete'] = url('/item/edit_multiple_item/delete');
        $data['edit_list'] = url('/item/edit_multiple_item/edit_list');
        $data['current_url'] = url('/item/edit_multiple_item');
        $data['add_remove_ids_url'] = url('/item/edit_multiple_item/add_remove_ids');
        $data['unset_session_value'] = url('/item/edit_multiple_item/unset_session_value');
        $data['get_session_value'] = url('/item/edit_multiple_item/get_session_value');
        $data['set_unset_session_value'] = url('/item/edit_multiple_item/set_unset_session_value');
        $data['get_categories_url'] = url('/item/edit_multiple_item/getCategories');
        $data['get_subcategories_url'] = url('/item/edit_multiple_item/getSubcategories');        
        // $data['get_item_ids_list'] = url('/item/edit_multiple_item/get_item_ids_list');
        $data['set_itemids_final_url'] = url('/item/edit_multiple_item/set_itemids_final');    
        $data['set_pagination_session_url'] = url('/item/edit_multiple_item/set_pagination_session_url');
        $data['set_final_session'] = url('/item/edit_multiple_item/set_final_session');
        
        session()->forget('clicked_pagination');    

        return view('items.editmultiple_items',compact('data'));
    }

    public function searchItems(Request $request) 
    {
       
        $return = $datas = array();
        $input = $request->all();
        
        $tax3_info_schema_query = 'select count(*) as tax3_exists from information_schema.columns where table_schema="u'.session()->get('sid').'" and Table_Name="mst_item" 
                                  and column_name="vtax3"';
        $tax3_exists = DB::select($tax3_info_schema_query);
        $tax3_exists = isset($tax3_exists[0])?(array)$tax3_exists[0]:[];
        $tax3_exists = $tax3_exists['tax3_exists'];
        
        $subcat_id_info_schema_query = 'select count(*) as subcat_id_exists from information_schema.columns where table_schema="u'.session()->get('sid').'" and Table_Name="mst_item" 
                                  and column_name="subcat_id"';
        $subcat_id_exists = DB::select($subcat_id_info_schema_query);
        $subcat_id_exists = isset($subcat_id_exists[0])?(array)$subcat_id_exists[0]:[];
        $subcat_id_exists = $subcat_id_exists['subcat_id_exists'];
        
        $sort = "mi.LastUpdate DESC";
        if(isset($input['sort_items']) && !empty(trim($input['sort_items'])))
        {
            $sort_by = trim($input['sort_items']);
            $sort = "mi.vitemname $sort_by";
        }
        
        $show_condition = "WHERE mi.estatus='Active'";
        if(isset($input['show_items']) && !empty(trim($input['show_items'])))
        {
            $show_items = trim($input['show_items']);
            if($show_items == "All")
            {
                $show_condition = "WHERE mi.estatus !=''";
            }
            else
            {
                $show_condition = "WHERE mi.estatus='".$show_items."'";
            }
            
        }
        // $input = $this->request->post;
        
        
        // echo '<pre>'; print_r($input); echo '</pre>'; die;
        
        $search_value = $input['columns'];
        
        $search_items = [];
        foreach($search_value as $value)
        {
            if($value["data"] == "itemname")
            {
                $search_items['itemname'] = $value['search']['value'];
            }
            else if($value["data"] == "barcode")
            {
                $search_items['barcode'] = $value['search']['value'];
            }
            else if($value["data"] == "unitprice")
            {
                $search_items['unitprice'] = $value['search']['value'];
            }
            else if($value["data"] == "category")
            {
                $search_items['category'] = $value['search']['value'];
            }
            else if($value["data"] == "department")
            {
                $search_items['department'] = $value['search']['value'];
            }
            else if($value["data"] == "sub_category")
            {
                $search_items['sub_category'] = $value['search']['value'];
            }
            else if($value["data"] == "item_group")
            {
                $search_items['item_group'] = $value['search']['value'];
            }
            else if($value["data"] == "taxes")
            {
                $search_items['taxes'] = $value['search']['value'];
            }
            else if($value["data"] == "unitprice")
            {
                $search_items['unitprice'] = $value['search']['value'];
            }
            else if($value["data"] == "supplier")
            {
                $search_items['supplier'] = $value['search']['value'];
            }
            else if($value["data"] == "fooditem")
            {
                $search_items['fooditem'] = $value['search']['value'];
            }
        }
        
        
        
//         if ($request->isMethod('post')) {
            
// 			// unset($this->session->data['items_total_ids']);
//             // unset($this->session->data['session_items_total_ids']);
//             session()->forget('items_total_ids');
//             session()->forget('session_items_total_ids');
           
           
            
// 		}
		
		$items_total_ids = array();
		
	
        
        if(empty(trim($search_items['itemname'])) && empty(trim($search_items['barcode'])) && empty(trim($search_items['unitprice'])) && empty(trim($search_items['category'])) &&  empty(trim($search_items['department'])) && empty(trim($search_items['sub_category'])) && empty(trim($search_items['supplier'])) && empty(trim($search_items['unitprice'])) && empty(trim($search_items['taxes'])) && empty(trim($search_items['fooditem'])) && empty(trim($search_items['item_group'])) )
        {
            $limit = 20;
            
            $sid = "u".session()->get('sid');

            $start_from = ($input['start']);
            
            if($tax3_exists > 0){
                $select_query = "SELECT distinct mi.iitemid, mi.vbarcode barcode,mi.vitemname itemname, md.vdepartmentname department,
                                (SELECT vitemgroupname FROM ".$sid.".itemgroup WHERE iitemgroupid = itg.iitemgroupid) as itemgroupname,itg.iitemgroupid iitemgroupid,
                                  mc.vcategoryname category, ifnull(msc.subcat_name, '') sub_category, mi.dunitprice unitprice, msupp.vcompanyname supplier, 
                                  concat(
                                          case when mi.vtax1='Y' then 'Tax1' else '' end, 
                                          case when mi.vtax1='Y' and mi.vtax2='Y' then ',' else '' end,
                                          case when mi.vtax1='Y' and mi.vtax2 <> 'Y' and mi.vtax3='Y' then ',' else '' end,
                                          case when mi.vtax2='Y' then 'Tax2' else '' end,
                                          case when mi.vtax2='Y' and mi.vtax3='Y' then ',' else '' end,
                                          case when mi.vtax3='Y' then 'Tax3' else '' end
                                  )  as taxes, mi.vfooditem fooditem, mi.LastUpdate FROM ".$sid.".mst_item as mi 
                                  LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) 
                                  LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                                  LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) 
                                  LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                                  LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
                                  LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                                  LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition 
                                  ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
                
            }else{
                $select_query = "SELECT distinct mi.iitemid, mi.vbarcode barcode,mi.vitemname itemname, md.vdepartmentname department,
                                (SELECT vitemgroupname FROM ".$sid.".itemgroup WHERE iitemgroupid = itg.iitemgroupid) as itemgroupname,itg.iitemgroupid iitemgroupid,
                                  mc.vcategoryname category, ifnull(msc.subcat_name, '') sub_category, mi.dunitprice unitprice, msupp.vcompanyname supplier, 
                                  concat(
                                          case when mi.vtax1='Y' then 'Tax1' else '' end, 
                                          case when mi.vtax1='Y' and mi.vtax2='Y' then ',' else '' end,
                                          case when mi.vtax2='Y' then 'Tax2' else '' end
                                  )  as taxes, mi.vfooditem fooditem, mi.LastUpdate FROM ".$sid.".mst_item as mi 
                                  LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) 
                                  LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                                  LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) 
                                  LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                                  LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
                                  LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                                  LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition 
                                  ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
                
            }
            // print_r($select_query); die;
            $query = DB::connection('mysql_dynamic')->select($select_query);
                        
            $complete_select_query = "SELECT distinct mi.iitemid FROM ".$sid.".mst_item as mi 
                                  LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) 
                                  LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                                  LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) 
                                  LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                                  LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
                                  LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                                  LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition ";

            $complete_result = DB::connection('mysql_dynamic')->select($complete_select_query);
            
            $items_total_ids = array_column($complete_result, 'iitemid');
            
            $sesion_set = session()->get('items_total_ids');
            
            
              
            // if(!isset($sesion_set) && $input['m_check'] == 'true'){ 
            //     session()->forget('items_total_ids');
            //     session()->forget('session_items_total_ids');
            //     session()->put('items_total_ids', $items_total_ids);
            //     session()->put('session_items_total_ids', $items_total_ids);
                
            // }
            // else{
            //     session()->forget('session_items_total_ids');
            // 	session()->put('session_items_total_ids', $items_total_ids);
            // }
            
            
            
            $count_select_query = "SELECT COUNT(distinct mi.iitemid) as count FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition ";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            $count_query = isset($count_query[0])?(array)$count_query[0]:[];
            
            $count_records = $count_total = (int)$count_query['count'];

        } else {
            
           
            $limit = 20;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length']; 
            $condition = "";
            $check_condition = 0;
            
            if(isset($search_items['itemname']) && !empty(trim($search_items['itemname']))){
                $search = ($search_items['itemname']);
                $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }
            
            if(isset($search_items['barcode']) && !empty(trim($search_items['barcode']))){
                $search = ($search_items['barcode']);
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }
            
            if(isset($search_items['unitprice']) && !empty(trim($search_items['unitprice']))){
                $search = ($search_items['unitprice']); 
                $search_conditions =explode("|",$search);
                
                if($search_conditions[0] == 'greater' && isset($search_conditions[1])){
                    $condition .= " AND mi.dunitprice > $search_conditions[1] ";
                    
                }elseif($search_conditions[0] == 'less' && isset($search_conditions[1])){
                    $condition .= " AND mi.dunitprice < $search_conditions[1] ";
                    
                }elseif($search_conditions[0] == 'equal' && isset($search_conditions[1])){
                    $condition .= " AND mi.dunitprice = $search_conditions[1] ";
                    
                }elseif($search_conditions[0] == 'between' && isset($search_conditions[1]) && isset($search_conditions[2])){
                    
                    $condition .= " AND mi.dunitprice BETWEEN $search_conditions[1] AND $search_conditions[2] ";
                    
                }
                $check_condition = 1;
            }
            
            
            if(isset($search_items['department']) && !empty(trim($search_items['department']))){
                $search = ($search_items['department']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vdepcode ='".$search."'";
                    $check_condition = 1;
                }
            }
            
            if(isset($search_items['category']) && !empty(trim($search_items['category']))){
                $search = ($search_items['category']);
                if($search_items['department'] != 'all' && $search != 'all')
                {
                    $condition .= " AND mi.vcategorycode ='".$search."'";
                    $check_condition = 1;
                    
                }
                
            }
            
            if(isset($search_items['sub_category']) && !empty($search_items['sub_category'])){
                $search = ($search_items['sub_category']);
                if($search_items['category'] != 'all' && $search_items['department'] != 'all' && $search != 'all')
                {
                    $condition .= " AND mi.subcat_id ='".$search."'";
                    $check_condition = 1;
                    
                }
                
            }
            
            if(isset($search_items['supplier']) && !empty($search_items['supplier'])){
                $search = ($search_items['supplier']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vsuppliercode ='".$search."'";
                    $check_condition = 1;
                    
                }
            }
            
            
            if(isset($search_items['taxes']) && !empty($search_items['taxes'])){
                $search = ($search_items['taxes']);
              
                switch($search){
                    case 'tax1':
                        $condition .= " AND mi.vtax1 ='Y'";
                        break;
                    
                    case 'tax2':
                        $condition .= " AND mi.vtax2 ='Y'";
                        break;
                    
                    case 'tax3':
                        if($tax3_exists > 0){
                        $condition .= " AND mi.vtax3 ='Y'";
                        }
                        break;
                    
                    case 'no':
                        if($tax3_exists > 0){
                        $condition .= " AND (mi.vtax1 ='N' AND mi.vtax2 ='N' AND mi.vtax3 ='N')";
                        } else {
                        $condition .= " AND (mi.vtax1 ='N' AND mi.vtax2 ='N')";
                        }
                        break;
                    
                    case 'all':
                        if($tax3_exists > 0){
                        $condition .= " AND (mi.vtax1 ='Y' OR mi.vtax2 ='Y' OR mi.vtax3 ='Y')";
                        } else {
                        $condition .= " AND (mi.vtax1 ='Y' OR mi.vtax2 ='Y')";
                        }
                        break;
                
                }
                $check_condition = 1;

            }
            
            if(isset($search_items['fooditem']) && !empty($search_items['fooditem'])){
                $search = ($search_items['fooditem']);
                if($search === 'Y')
                {
                    $condition .= " AND mi.vfooditem ='Y'";
                    $check_condition = 1;

                } elseif($search === 'N')
                {
                    $condition .= " AND mi.vfooditem ='N'";
                    $check_condition = 1;
                }
            }
            
            if(isset($search_items['item_group']) && !empty($search_items['item_group'])){
                $search = ($search_items['item_group']);
                if($search != 'all')
                {
                    $condition .= " AND itg.iitemgroupid ='".$search."'";
                    $check_condition = 1;
                }
                
            }
            
            $sid = "u".session()->get('sid');
                        
            if($tax3_exists > 0){
                
                $select_query = "SELECT distinct mi.iitemid, mi.vbarcode barcode,mi.vitemname itemname, md.vdepartmentname department,
                                (SELECT vitemgroupname FROM ".$sid.".itemgroup WHERE iitemgroupid = itg.iitemgroupid) as itemgroupname,itg.iitemgroupid iitemgroupid,
                                  mc.vcategoryname category, ifnull(msc.subcat_name, '') sub_category, mi.dunitprice unitprice, msupp.vcompanyname supplier, 
                                  concat(
                                          case when mi.vtax1='Y' then 'Tax1' else '' end, 
                                          case when mi.vtax1='Y' and mi.vtax2='Y' then ',' else '' end,
                                          case when mi.vtax1='Y' and mi.vtax2 <> 'Y' and mi.vtax3='Y' then ',' else '' end,
                                          case when mi.vtax2='Y' then 'Tax2' else '' end,
                                          case when mi.vtax2='Y' and mi.vtax3='Y' then ',' else '' end,
                                          case when mi.vtax3='Y' then 'Tax3' else '' end
                                  )  as taxes, mi.vfooditem fooditem, mi.LastUpdate FROM ".$sid.".mst_item as mi 
                                  LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) 
                                  LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                                  LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) 
                                  LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                                  LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
                                  LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                                  LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition "." $condition 
                                  ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
                
            }else{
                
                $select_query = "SELECT distinct mi.iitemid, mi.vbarcode barcode,mi.vitemname itemname, md.vdepartmentname department,
                                (SELECT vitemgroupname FROM ".$sid.".itemgroup WHERE iitemgroupid = itg.iitemgroupid) as itemgroupname,itg.iitemgroupid iitemgroupid,
                                  mc.vcategoryname category, ifnull(msc.subcat_name, '') sub_category, mi.dunitprice unitprice, msupp.vcompanyname supplier, 
                                  concat(
                                          case when mi.vtax1='Y' then 'Tax1' else '' end, 
                                          case when mi.vtax1='Y' and mi.vtax2='Y' then ',' else '' end,
                                          case when mi.vtax2='Y' then 'Tax2' else '' end
                                  )  as taxes, mi.vfooditem fooditem, mi.LastUpdate FROM ".$sid.".mst_item as mi 
                                  LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) 
                                  LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                                  LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) 
                                  LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                                  LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
                                  LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                                  LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition "." $condition 
                                  ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
                
            }
            
            $query = DB::connection('mysql_dynamic')->select($select_query);
            
            
            $complete_select_query = "SELECT distinct mi.iitemid FROM ".$sid.".mst_item as mi 
                                  LEFT JOIN ".$sid.".mst_department as md ON(mi.vdepcode = md.vdepcode) 
                                  LEFT JOIN ".$sid.".mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                                  LEFT JOIN ".$sid.".mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) 
                                  LEFT JOIN ".$sid.".mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                                  LEFT JOIN ".$sid.".mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
                                  LEFT JOIN ".$sid.".mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                                  LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition "." $condition";
            
            // die;
            $complete_result = DB::connection('mysql_dynamic')->select($complete_select_query);
            
            
            $items_total_ids = array_column($complete_result, 'iitemid');
            
            
            // if(empty(session()->get('items_total_ids')) && $input['m_check'] == 'true'){
                
            //     session()->forget('items_total_ids');
            //     session()->forget('session_items_total_ids');
            // 	session()->put('items_total_ids', $items_total_ids);
            // 	session()->put('session_items_total_ids', $items_total_ids);
                
            // }
            // else{
            //     session()->forget('session_items_total_ids');
            // 	session()->put('session_items_total_ids', $items_total_ids);
            // }
            // $items_total = session()->get('items_total_ids');
            
            $count_select_query = "SELECT COUNT(distinct mi.iitemid) as count FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN ".$sid.".itemgroupdetail as itg ON(itg.vsku=mi.vbarcode) $show_condition "." $condition";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            $count_query = isset($count_query[0])?(array)$count_query[0]:[];
            
            $count_records = $count_total = (int)$count_query['count'];
            
        }
        
        $search = $input['search']['value'];
        
        //====converting object data into array=====
        $query = array_map(function ($value) {
            return (array)$value;
        }, $query);
		
        if(count($query) > 0){
            foreach ($query as $key => $value) {
            $temp = array();
            $temp['iitemid']        = $value['iitemid'];
            $temp['itemname']       = $value['itemname'];
            $temp['barcode']        = $value['barcode'];
            $temp['department']     = $value['department'];
            $temp['category']       = $value['category'];
            $temp['sub_category']   = $value['sub_category'];
            $temp['unitprice']      = $value['unitprice'];
            
            if(isset($value['taxes']) && !empty($value['taxes'])){
                $temp['taxes']      = $value['taxes'];
            }else{
                $temp['taxes']      = 'No Tax';
            }
            
            $temp['supplier']       = $value['supplier'];
            $temp['fooditem']       = $value['fooditem'];
            $temp['item_group']    = $value['itemgroupname'];
            
            $datas[]                = $temp;
            }
        }
        
        
        //==========commented and added new code on 07/04/2020================
        
        //     if(!isset($this->session->data['items_total_ids'])){
        // 			$this->session->data['items_total_ids'] = $items_total_ids;
        // 			$this->session->data['session_items_total_ids'] = $items_total_ids;
        //  		$data['items_total_ids'] = $items_total_ids;
        // 		}
		/*else{
			$data['items_total_ids'] = $this->session->data['items_total_ids'];
		}*/        
        
        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $datas;
        $return['session_items_total_ids'] = $items_total_ids;
        if($input['m_check'] == 'true'){
            $return['items_total_ids'] = $items_total_ids;
        }
        
        unset($items_total_ids);
        
        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
        
    }
    
    public function get_department_categories(Request $request)
    {
        $input = $request->all();
        if(isset($input['dep_code'])  && $input['dep_code'] != "") {
            
            $categories = Category::whereIn('dept_code', $input['dep_code'])->orderBy('vcategoryname')->get()->toArray();
            
            $cat_list = "<option value='all'>All</option>";
            foreach($categories as $category){
                if(isset($category['vcategorycode'])){
                    $cat_code = $category['vcategorycode'];
                    $cat_name = $category['vcategoryname'];
                    $cat_list .= "<option value=".$cat_code.">".$cat_name."</option>";
                } 
            }
            echo $cat_list;
        }
    }

    public function get_sub_categories_url(Request $request)
    {
        $input = $request->all();
        if (isset($input['cat_code'])  && $input['cat_code'] != "") {
            
            $subCategories = SubCategory::whereIn('cat_id', $input['cat_code'])->orderBy('subcat_name')->get()->toArray();
            $subcat_list = "<option value='all'>All</option>";
            
            foreach($subCategories as $subCategory){
                if(isset($subCategory['subcat_id'])){
                    $sub_cat_id     = $subCategory['subcat_id'];
                    $sub_cat_name   = $subCategory['subcat_name'];
                    $subcat_list   .= "<option value=".$sub_cat_id.">".$sub_cat_name."</option>";
                } 
            }
            echo $subcat_list;
        }
    }
    
    public function edit_list(Request $request) 
    {
		if($request->isMethod('post')) {
			
			$input_data = $request->all();
			
			if(isset($input_data['options_checkbox']) && $input_data['options_checkbox'] != ''){
				$input_data['options_data']['unit_id'] = $input_data['update_unit_id'];
				$input_data['options_data']['unit_value'] = $input_data['update_unit_value'];
				$input_data['options_data']['bucket_id'] = $input_data['update_bucket_id'];
				if(isset($input_data['update_malt']) && $input_data['update_malt'] == 1){
					$input_data['options_data']['malt'] = $input_data['update_malt'];
				}else{
					$input_data['options_data']['malt'] = 0;
				}

			}else{
				$input_data['options_data'] = array();
			}
			
			$input_data['item_ids'] = session()->get('items_total_ids');
			
			$EditMultipleItems = new EditMultipleItems;
			$result = $EditMultipleItems->editlistItems($input_data);
            
            if(isset($result['success'])){
                session()->put('success', $result['success']);
            }else{
                session()->put('error', $result['error']);
            }
			
			session()->forget('items_total_ids');
			
			$url = '/item/edit_multiple_item';
            return redirect($url);
		}
	}
	
	public function get_session_value(Request $request)
	{
        $input = $request->all();
        $items_total_ids = session()->get('items_total_ids');
        // $items_total_ids = $input['items_total_ids'];
        
      	$json['total_items'] = count($items_total_ids);
        
      	return response(json_encode($json), 200)
                        ->header('Content-Type', 'application/json');
		exit;
    }
    
    public function set_unset_session_value(Request $request) 
    {
        $input = $request->all();
      	if(isset($input['checkbox_value']) && !empty($input['checkbox_value'])){
            
            $get_seession='';
            
      		// if($input['checkbox_value'] == 'unset'){
      		// 	session()->put('items_total_ids', array());
      		// }else{
      		//     $get_seession = session()->get('session_items_total_ids'); 
      		// 	session()->put('items_total_ids', $get_seession);
      		// }
      		
      		if($input['checkbox_value'] == 'unset'){
      			session()->put('items_total_ids', array());
      		}
        
      	}
    }
    
    public function unset_session_value()
    {
        //  unset($this->session->data['items_total_ids']);
        //  unset($this->session->data['session_items_total_ids']);
        session()->forget('items_total_ids');
        session()->forget('session_items_total_ids');
    }
    
    public function getCategories(Request $request)
	{
        $input = $request->all();
        if($request->isMethod('post') && isset($input['depcode']))
        {
            
            $depcode = $input['depcode'];
            $categories = Category::where('dept_code', $depcode)->orderBy('vcategoryname')->get()->toArray();
            $options = "<option value=''>--Select Category--</option>";
            if(empty($categories))
            {
                echo $options;exit;
            }
            foreach($categories as $value)
            {
                $code = $value['vcategorycode'];
                $name = $value['vcategoryname'];
                $options .= "<option value=$code> $name </option>";
            }
            echo $options;exit;
        }
	    
	}

    public function getSubcategories(Request $request)
	{
        $input = $request->all();
        if($request->isMethod('post') && $input['category_code'])
        {
            
            $category_code = $input['category_code'];
            $subcategories = SubCategory::where('cat_id', $category_code)->orderBy('subcat_name')->get()->toArray();
            $options = "<option>--Select Subcategory--</option>";
            if(empty($subcategories))
            {
                echo $options;exit;
            }
            foreach($subcategories as $value)
            {
                $code = $value['subcat_id'];
                $name = $value['subcat_name'];
                $options .= "<option value=$code> $name </option>";
            }
            echo $options;exit;
        }
        
	}
	
	public function get_item_ids_list()
	{
      	$item_total_ids_list = session()->get('items_total_ids');
      	$this->response->addHeader('Content-Type: application/json');
    	  echo json_encode($item_total_ids_list);
    	  exit;
    }
    
    public function set_itemids_final(Request $request)
    {   
        $all_inputs = $request->all();
        if(isset($all_inputs) && !empty($all_inputs)){
            $input = $all_inputs['keys'];   
        }
      
        $temp_session_item_ids = session()->get('items_total_ids');
      
        if(isset($input) && $input != null && $input != ''){
         
            foreach($input as $k => $v){ 
                
                if($v == 'false'){
                    
                    if ((in_array($k, $temp_session_item_ids)) == false) { 
                        $temp_session_item_ids[] = $k;        // if not availabl e add to the session_item_ids. ($v =='false', here false in  means checked)
                    }
                    
                }elseif($v == 'true'){
                    print_r('done');
                    if ((in_array($k, $temp_session_item_ids)) == true) {
                        $key = array_search($k, $temp_session_item_ids);
                        unset($temp_session_item_ids[$key]);    // if available add to the session_item_ids. ($v =='true', here true means unchecked)
                    }
                }
                
            }
            
        }
       
            //   foreach($input as $v){
            //     if (($key = array_search($v, $temp_session_item_ids)) !== false) {
            //       unset($temp_session_item_ids[$key]);
            //     }
            //   }
      
        session()->put('items_total_ids', $temp_session_item_ids);
      
        return response(json_encode($temp_session_item_ids), 200)
                        ->header('Content-Type', 'application/json');
		
		  exit;
    }
    
    public function add_remove_ids(Request $request) 
    {
        $input = $request->all();
        
        if ($request->isMethod('post')) {
            
            $posted_arr = ($input['main_arr']);
            if(isset($input['session_items_total_ids'])){
                
                if(!isset($input['items_total_ids'])){
                    $input['items_total_ids'] = array();
                }
                
                $session_data = session()->get('items_total_ids');
                if(!isset($session_data)){
                    $session_data = $input['items_total_ids'];
                }
                
                if(isset($posted_arr['add']) && count($posted_arr['add']) > 0){
                    foreach ($posted_arr['add'] as $key => $value) {
                        if(!in_array($value, $session_data)){
                            $session_data[] = $value;
                        }
                    }
                }
                
                if(isset($posted_arr['remove']) && count($posted_arr['remove']) > 0){
                    foreach ($posted_arr['remove'] as $key => $value) {
                        if (($key = array_search($value, $session_data)) !== false) {
                            unset($session_data[$key]);
                        }
                    }
                }
                
                $new_session_data = array_values(array_unique($session_data));
                
                $items_total_ids = $new_session_data;
                
                session()->put('items_total_ids', $items_total_ids);
            }
        }
        
        return response(json_encode($new_session_data), 200)
                        ->header('Content-Type', 'application/json');
		exit;
    }
    
    public function checkforduplicate(Request $request){
        $item_ids = session()->get('items_total_ids');
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        foreach($item_ids as $item){
            $vbarcode = DB::connection("mysql_dynamic")->select("Select vbarcode from mst_item where iitemid = '".$item."' ");
            
            foreach($stores as $store){
                $dup_query = "Select * from u".$store->id.".mst_item where  vbarcode = '".$vbarcode[0]->vbarcode."' ";
                $dup_entry = DB::connection("mysql")->select($dup_query);
                if(count($dup_entry) == 0){
                    array_push($notExistStore, $store->id); 
                }
            }
        }
        
        return $notExistStore;
    }
    
    public function set_final_session(Request $request)
    {
        $input = $request->all();
        
        session()->put('items_total_ids', $input['items_total_ids']);
        
        return response(json_encode(session()->get('items_total_ids')), 200)
                        ->header('Content-Type', 'application/json');
		
		  exit;
    }
}