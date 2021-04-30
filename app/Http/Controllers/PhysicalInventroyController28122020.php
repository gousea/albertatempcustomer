<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\PhysicalInventory;
use App\Model\Category;
use App\Model\Department;
use App\Model\Vendor;
use App\Model\Store;
use App\Model\SubCategory;
use Illuminate\Support\Facades\DB;
use App\Model\WebAdminSetting;
use Laravel\Ui\Presets\React;
use Pagination;
use PDF;
use PDO;
use Session;

class PhysicalInventroyController extends Controller
{
    public function index()
    {
        $physicalInventrorylists = PhysicalInventory::where('vtype', 'Physical')->orderBy('ipiid', 'Desc')->paginate(25);
        return view('inventory.physicalInventroy.index', compact('physicalInventrorylists'));
    }
    
    public function search_inventory_list(Request $request)
    {
        $input = $request->all();
        $physicalInventrorylists = PhysicalInventory::where('vtype', '=' ,'Physical')
                                                        ->where('vrefnumber', 'like', '%'.$input['automplete-product'].'%')
                                                        ->orderBy('ipiid', 'Desc')
                                                        ->paginate(25);
        return view('inventory.physicalInventroy.index', compact('physicalInventrorylists'));
    }

    public function create()
    {
        $items = DB::connection('mysql_dynamic')->select('SELECT mst_item.vitemname, mst_item.vbarcode, mst_item.dunitprice, mst_item.dcostprice, mst_department.vdepartmentname, mst_category.vcategoryname, mst_supplier.vcompanyname,mst_subcategory.subcat_name, mst_item.iqtyonhand FROM mst_item join mst_department on mst_item.vdepcode = mst_department.idepartmentid join mst_category on mst_item.vcategorycode = mst_category.icategoryid join mst_supplier on mst_item.vsuppliercode = mst_supplier.isupplierid join mst_subcategory on mst_item.subcat_id = mst_subcategory.subcat_id');
        
        $departments = Department::orderBy('vdepartmentname', 'ASC')->get();
        $categories = Category::orderBy('vcategoryname', 'ASC')->get();
        $subcategories = Subcategory::orderBy('subcat_name', 'ASC')->get();
        $vendors = Vendor::orderBy('vcompanyname', 'ASC')->get();

        // dd($vendors);

        return view('inventory.physicalInventroy.create', compact('items', 'departments', 'categories', 'subcategories', 'vendors'));
    }

    public function get_item_list(Request $request)
    {
        
        $input = $request->all();
        
        if (isset($input['sort'])) {
            $sort = $input['sort'];
        } else {
            $sort = 'iitemid';
        }
        
        
        if (isset($input['searchbox'])) {
            $searchbox =  $input['searchbox'];
            session()->put('item_page_search', $input['searchbox']);
        } elseif ($request->session()->has('item_page_search') && isset($input['cancel_btn'])) {
            $searchbox =  session()->get('item_page_search');
        } elseif ($request->session()->has('item_page_search_id')) {
            $searchbox =  session()->get('item_page_search_id');
            session()->forget('item_page_search_id');
        } else {
            $searchbox = '';
            session()->forget('item_page_search');
        }
        
        
        $disable_items = 'Active';
        
        
        if (isset($input['sort_items'])) {
            $sort_items =  $input['sort_items'];
        } else {
            $sort_items = '';
        }
        
        
        $data['show_items'] = $disable_items;
        
        $data['sort_items'] = $sort_items;
        
        $data['url_next'] = url('inventory/physicalInventroy/snapshot');
        $data['searchitem'] = url('inventory/physicalInventroy/search');
        $data['parent_child_search'] = url('administration/items/parent_child_search');
        $data['current_url'] = url('inventory/physicalInventroy');
        $data['session_url'] = url('inventory/physicalInventroy/create_session');
        $data['get_data_by_barcode'] = url('inventory/physicalInventroy/get_barcode');
        $data['scanned_session_url'] = url('inventory/physicalInventroy/create_scanned_session');
        $data['get_scanned_data'] = url('inventory/physicalInventroy/get_scanned_data');
        $data['remove_session_scanned_data'] = url('inventory/physicalInventroy/remove_session_scanned_data');
        $data['unset_session_scanned_data'] = url('inventory/physicalInventroy/unset_session_scanned_data');
        
        $data['get_categories_url'] = url('inventory/physicalInventroy/get_categories_by_department');
        $data['get_subcategories_url'] = url('inventory/physicalInventroy/get_subcat_by_categories');
        
        $data['cancel'] = url('inventory/physicalInventroy');
        
        $data['items'] = array();
        
        $limit  = 25;   
        $filter_data = array(
            'searchbox'  => $searchbox,
            'sort_items'  => $sort_items,
            'show_items' => $disable_items,
        );
        
        //============= Start Department Data=====================================================
        
        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        $departments_html = "";
        $departments_html = "<select class='form-control' multiple='true' name='dept_code[]' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($departments as $department) {
            if (isset($vdepcode) && $vdepcode == $department['vdepcode']) {
                $departments_html .= "<option value='" . $department['vdepcode'] . "' selected='selected'>" . $department['vdepartmentname'] . "</option>";
            } else {
                $departments_html .= "<option value='" . $department['vdepcode'] . "'>" . $department['vdepartmentname'] . "</option>";
            }
        }
        $departments_html .= "</select>";
        
        $data['departments'] = $departments_html;
        
        //=============End Department Data=====================================================
        
        //=============Start Category Data=====================================================
        
        $category = Category::orderBy('vcategoryname', 'ASC')->get()->toArray();
        $category_html = "";
        $category_html = "<select class='form-control' multiple='true' name='category_code[]' id='category_code' style='width: 100px;'>'<option value='all'>All</option>";
        // foreach($category as $category){
        //     if(isset($vcategorycode) && $vcategorycode == $category['vcategorycode']){
        //         $category_html .= "<option value='".$category['vcategorycode']."' selected='selected'>".$category['vcategoryname']."</option>";
        //     } else {
        //         $category_html .= "<option value='".$category['vcategorycode']."'>".$category['vcategoryname']."</option>";
        //     }
        // }
        $category_html .= "</select>";

        $data['category'] = $category_html;
        //=============End Category Data=====================================================

        //=============Start Sub Category Data=====================================================

        $subcategory = SubCategory::orderBy('subcat_name', 'ASC')->get()->toArray();
        $subcategory_html = "";
        $subcategory_html = "<select class='form-control' multiple='true' name='subcat_id[]' id='subcat_id' style='width: 100px;'>'<option value='all'>All</option>";
        // foreach($subcategory as $subcategory){
        //     if(isset($subcat_id) && $subcat_id == $subcategory['subcat_id']){
        //         $subcategory_html .= "<option value='".$subcategory['subcat_id']."' selected='selected'>".$subcategory['subcat_name']."</option>";
        //     } else {
        //         $subcategory_html .= "<option value='".$subcategory['subcat_id']."'>".$subcategory['subcat_name']."</option>";
        //     }
        // }
        $subcategory_html .= "</select>";

        $data['subcategory'] = $subcategory_html;
        //=============End Sub Category Data=====================================================

        //=============Start Supplier Data=====================================================

        $supplier = Vendor::orderBy('vcompanyname', 'ASC')->get()->toArray();

        // echo "<pre>"; print_r($supplier); echo "</pre>"; die;

        $supplier_html = "";
        $supplier_html = "<select class='form-control' multiple='true' name='supplier_code[]' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($supplier as $supplier) {
            if (isset($vsuppliercode) && $vsuppliercode == $supplier['vsuppliercode']) {
                $supplier_html .= "<option value='" . $supplier['vsuppliercode'] . "' selected='selected'>" . $supplier['vcompanyname'] . "</option>";
            } else {
                $supplier_html .= "<option value='" . $supplier['vsuppliercode'] . "'>" . $supplier['vcompanyname'] . "</option>";
            }
        }
        $supplier_html .= "</select>";

        $data['supplier'] = $supplier_html;
        //=============End Supplier Data=====================================================

        //==============Start Price select By========================================================

        $price_select_by_list = array(
            ''          => 'Select',
            'greater'   => 'Greater than',
            'less'      => 'Less than',
            'equal'     => 'Equal to',
            'between'   => 'Between'
        );

        $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='width:70px;color:black;border-radius: 4px;height:28px;'>";
        foreach ($price_select_by_list as $k => $v) {
            $price_select_by_html .= "<option value='" . $k . "'";

            if (isset($data['price_select_by']) && $k === $data['price_select_by']) {
                $price_select_by_html .= " selected";
            }

            $price_select_by_html .= ">" . $v . "</option>";
        }
        $price_select_by_html .= "</select>";
        $price_select_by_html .= "<span id='selectByValuesSpan'>";

        // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_1']."'/>";



        if (isset($input['price_select_by']) && $input['price_select_by'] === 'between') {
            // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
            $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
            $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value='" . $data['select_by_value_2'] . "'/>";
        } else {
            $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
        }

        $price_select_by_html .= "</span>";

        $data['price_select_by'] = $price_select_by_html;

        //==============End Price select By========================================================
            
        $data['itemListings'] = array("vdepcode" => "Department", "vcategorycode" => "Category", "subcat_id" => "Sub Category", "vsuppliercode" => "Supplier");
            
            
        // $item_data = PhysicalInventory::first()->getItemsResult($filter_data);
            
        // if (count($item_data) == 0) {
        //     $data['items'] = array();
        //     $item_total = 0;
        //     $data['item_row'] = 1;
        // }
        
        if (isset($input['selected'])) {
            $data['selected'] = (array)$input['selected'];
        } else {
            $data['selected'] = array();
        }
        
        
        // $data['item_sorting'] = url('administration/items', 'token=' . $this->session->data['token'] . $url1, true);
        
        $data['title_arr'] = array(
            'webstore' => 'Web Store',
            'dunitprice' => 'Price',
            'vitemcode' => 'Item Code',
            'vitemname' => 'Item Name',
            'vunitcode' => 'Unit',
            'vbarcode' => 'SKU',
            'vpricetype' => 'Price Type',
            'vcategorycode' => 'Category',
            'subcat_id'     => 'Sub Category',
            'vdepcode' => 'Dept.',
            'vsuppliercode' => 'Supplier',
            'iqtyonhand' => 'Qty. on Hand',
            'estatus' => 'Status',
            'isparentchild' => 'is parent child',
            'parentid' => 'parent id',
            'parentmasterid' => 'parent master id',
            'wicitem' => 'wic item',
        );
        // dd($data);
        return view('inventory.physicalInventroy.get_item_list', compact('data'));
    }


    public function search(Request $request)
    {
        ini_set('memory_limit', '256M');

        $input = $request->all();
        $return = $datas = array();

        $sort = "mi.LastUpdate DESC";
        if (isset($input['sort_items']) && !empty(trim($input['sort_items']))) {
            $sort_by = trim($input['sort_items']);
            $sort = "mi.vitemname $sort_by";
        }

        $show_condition = "WHERE mi.visinventory = 'Yes' AND mi.estatus='Active'";




        $search_value = $input['columns'];


        $search_itmes = [];
        foreach ($search_value as $value) {
            if ($value["data"] == "vitemname") {
                $search_items['vitemname'] = htmlspecialchars_decode($value['search']['value']);
            } else if ($value["data"] == "dunitprice") {
                $search_items['dunitprice'] = $value['search']['value'];
            } else if ($value["data"] == "vbarcode") {
                $search_items['vbarcode'] = $value['search']['value'];
            } else if ($value["data"] == "vcategoryname") {
                $search_items['vcategoryname'] = $value['search']['value'];
            } else if ($value["data"] == "subcat_name") {
                $search_items['subcat_name'] = $value['search']['value'];
            } else if ($value["data"] == "vcompanyname") {
                $search_items['vcompanyname'] = $value['search']['value'];
            } else if ($value["data"] == "vdepartmentname") {
                $search_items['vdepartmentname'] = $value['search']['value'];
            }
        }



        if (empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && empty(trim($search_items['dunitprice'])) && empty(trim($search_items['vcategoryname'])) && empty(trim($search_items['subcat_name'])) && empty(trim($search_items['vcompanyname'])) &&  empty(trim($search_items['vdepartmentname']))) {
            $limit = 20;

            $start_from = ($input['start']);

            $offset = $input['start'] + $input['length'];

            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vdepcode, mi.subcat_id,mi.vsuppliercode, mi.vcategorycode, mi.vitemtype, md.vdepartmentname, mc.vcategoryname, (mi.dcostprice/mi.npack) as unitcost, mi.dunitprice, mi.nsaleprice, mi.subcat_id, msc.subcat_name, mi.iqtyonhand, mi.LastUpdate,msupp.vcompanyname, case isparentchild when 0 then mi.vitemname when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id=msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) $show_condition ORDER BY $sort LIMIT " . $start_from . ", " . $limit;

            $query = DB::connection('mysql_dynamic')->select($select_query);
            
            $count_query = "SELECT COUNT(DISTINCT(iitemid)) as total_rows FROM mst_item mi $show_condition";
            
            $run_count_query = DB::connection('mysql_dynamic')->select($count_query);
            
            $count_records = $count_total = $run_count_query[0]->total_rows;
        } else {

            $limit = 20;

            $start_from = ($input['start']);

            $offset = $input['start'] + $input['length'];
            $condition = "";
            if (isset($search_items['vitemname']) && !empty(trim($search_items['vitemname']))) {
                $search = $search_items['vitemname'];
                $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
            }

            if (isset($search_items['vbarcode']) && !empty(trim($search_items['vbarcode']))) {
                $search = $search_items['vbarcode'];
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%'";
            }

            if (isset($search_items['dunitprice']) && !empty(trim($search_items['dunitprice']))) {
                $search = $search_items['dunitprice'];
                $search_conditions = explode("|", $search);

                if ($search_conditions[0] == 'greater' && isset($search_conditions[1])) {
                    $condition .= " AND mi.dunitprice > $search_conditions[1] ";
                } elseif ($search_conditions[0] == 'less' && isset($search_conditions[1])) {
                    $condition .= " AND mi.dunitprice < $search_conditions[1] ";
                } elseif ($search_conditions[0] == 'equal' && isset($search_conditions[1])) {
                    $condition .= " AND mi.dunitprice = $search_conditions[1] ";
                } elseif ($search_conditions[0] == 'between' && isset($search_conditions[1]) && isset($search_conditions[2])) {
                    $condition .= " AND mi.dunitprice BETWEEN $search_conditions[1] AND $search_conditions[2] ";
                }
            }

            if (isset($search_items['vdepartmentname']) && !empty(trim($search_items['vdepartmentname']))) {
                $search = $search_items['vdepartmentname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vdepcode IN ('" . stripslashes($search) . "')";
                }
            }

            if (isset($search_items['vcategoryname']) && !empty($search_items['vcategoryname'])) {
                $search = $search_items['vcategoryname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vcategorycode IN ('" . stripslashes($search) . "')";
                }
            }

            if (isset($search_items['vcompanyname']) && !empty($search_items['vcompanyname'])) {
                $search = $search_items['vcompanyname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vsuppliercode IN ('" . stripslashes($search) . "')";
                }
            }

            if (isset($search_items['subcat_name']) && !empty($search_items['subcat_name'])) {
                $search = $search_items['subcat_name'];
                if ($search != 'all') {
                    $condition .= " AND mi.subcat_id IN ('" . stripslashes($search) . "')";
                }
            }

            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname,  mi.vdepcode, mi.subcat_id,mi.vsuppliercode, mi.vcategorycode, mi.vitemtype, md.vdepartmentname, mc.vcategoryname, (mi.dcostprice/mi.npack) as unitcost, mi.nsaleprice, mi.iqtyonhand, mi.dunitprice, mi.subcat_id, msc.subcat_name, mi.LastUpdate,msupp.vcompanyname,case isparentchild when 0 then mi.vitemname when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id=msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) $show_condition " . " $condition ORDER BY $sort LIMIT " . $input['start'] . ", " . $limit;

            $query = DB::connection('mysql_dynamic')->select($select_query);

            $count_select_query = "SELECT COUNT(DISTINCT(mi.iitemid)) as count FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id=msc.subcat_id) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) $show_condition " . " $condition";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);

            $count_records = $count_total = (int)$count_query[0]->count;
        }

        $search = $input['search']['value'];


        // $this->load->model('api/items');

        $itemListings = array("vdepcode" => "Department", "vcategorycode" => "Category", "vsuppliercode" => "Supplier", "subcat_id" => "Sub Category", "iqtyonhand" => "Qty. on Hand");

        if (count($query) > 0) {
            foreach ($query as $key => $value) {

                $temp = array();
                $temp['iitemid'] = $value->iitemid;
                $temp['vbarcode'] = $value->vbarcode;
                $temp['vitemname'] = $value->VITEMNAME;
                $temp['dunitprice'] = $value->dunitprice;
                $temp['unitcost'] = number_format($value->unitcost, 2);

                if (count($itemListings) > 0) {
                    foreach ($itemListings as $m => $v) {
                        if ($m == 'vdepcode') {
                            $temp['vdepcode'] = $value->vdepcode;
                        } else if ($m == 'vcategorycode') {
                            $temp['vcategorycode'] = $value->vdepcode;
                        } else if ($m == 'vunitcode') {
                            $temp['subcat_id'] = $value->subcat_id;
                        } else if ($m == 'vsuppliercode') {
                            $temp['vsuppliercode'] = $value->vsuppliercode;
                        } else {
                            $temp[$m] = $value->$m;
                        }
                    }
                }
                $temp['vcompanyname'] = $value->vcompanyname;
                $temp['vdepartmentname'] = $value->vdepartmentname;
                $temp['vcategoryname'] = $value->vcategoryname;
                $temp['subcat_name'] = $value->subcat_name;
                $temp['nsaleprice'] = $value->nsaleprice;
                $temp['iqtyonhand'] = $value->iqtyonhand;
                // $temp['costtotal'] = number_format(($value['iqtyonhand'] * $value['unitcost']), 2);
                // $temp['pricetotal'] = number_format(($value['iqtyonhand'] * $value['dunitprice']), 2);

                $datas[] = $temp;
            }
        }

        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $datas;

        return response(json_encode($return), 200)
            ->header('Content-Type', 'application/json');
    }

    public function get_categories_by_department(Request $request)
    {
        $input = $request->all();
        if (isset($input['dep_code'])  && $input['dep_code'] != "") {
            $all_departments = $input['dep_code'][0];
            $departments = array();
            for ($i = 0; $i < count($all_departments); $i++) {
                $departments[] = $all_departments[$i];
            }
            $categories = Category::whereIn('dept_code', $departments)->get()->toArray();
            $cat_list = "<option value='all'>All</option>";
            foreach ($categories as $category) {
                if (isset($category['vcategorycode'])) {
                    $cat_code = $category['vcategorycode'];
                    $cat_name = $category['vcategoryname'];
                    $cat_list .= "<option value=" . $cat_code . ">" . $cat_name . "</option>";
                }
            }
            echo $cat_list;
        }
    }

    public function get_subcat_by_categories(Request $request)
    {
        $input = $request->all();
        if (isset($input['category_code'])  && $input['category_code'] != "") {
            $all_categories = $input['category_code'][0];
            $categories = array();
            for ($i = 0; $i < count($all_categories); $i++) {
                $categories[] = $all_categories[$i];
            }
            $subcategories = Subcategory::whereIn('cat_id', $categories)->get()->toArray();
            $subcat_list = "<option value='all'>All</option>";
            foreach($subcategories as $subcategory){
                if(isset($subcategory['subcat_name'])){
                    $sub_cat_id = $subcategory['subcat_id'];
                    $sub_cat_name = $subcategory['subcat_name'];
                    $subcat_list .= "<option value=".$sub_cat_id.">".$sub_cat_name."</option>";
                }
            }
            echo $subcat_list;
        }
    }

    public function create_session(Request $request){

        $input = $request->all();

        $selected_itemid = $input['itemid'];

        $selected_itemid = array_unique($selected_itemid);

        $selected_itemid = array_filter($selected_itemid);

        session()->forget('selected_itemid');
        session()->forget('scanned_selected_itemid');


        session()->put('selected_itemid', $selected_itemid);

        $data['item_id'] = $selected_itemid;

        $data['success'] = true;
        $data['success_msg'] = 'session created';

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }


    public function create_scanned_session(Request $request){

        $input = $request->all();

        $scanned_selected_itemid = $input['itemid'];

        $scanned_selected_itemid = array_unique($scanned_selected_itemid);

        $scanned_selected_itemid = array_filter($scanned_selected_itemid);

        session()->forget('selected_itemid');
        session()->forget('scanned_selected_itemid');

        session()->put('scanned_selected_itemid', $scanned_selected_itemid);

        $data['item_id'] = $scanned_selected_itemid;

        $data['success'] = true;
        $data['success_msg'] = 'session created';

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }

    public function get_scanned_data(){

        $json =array();

        $scanned_selected_itemid = session()->get('scanned_selected_itemid');

        $ids = join("','", $scanned_selected_itemid);

	    $select_query = "SELECT mi.iitemid, mi.npack, mi.vbarcode, mi.iqtyonhand, mi.vitemname, mi.vitemcode, (mi.dcostprice/mi.npack) as unitcost FROM mst_item as mi WHERE mi.iitemid IN ('".$ids."') ORDER BY mi.iitemid ";

        $itemdata = DB::connection('mysql_dynamic')->select($select_query);

        if(count($itemdata) > 0){
            $data['success'] = true;
            $data['scanned_data'] = $itemdata;
        }else{
            $data['success'] = false;
            $data['message'] = 'No Data Found';
        }

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }

    public function remove_session_scanned_data(Request $request){
        $input = $request->all();
	    $remove_scanned_itemid = $input['itemid'];
        $scanned_selected_itemid = session()->get('scanned_selected_itemid');
        session()->forget('scanned_selected_itemid');

	    foreach($remove_scanned_itemid as $itemid){
	        if (($key = array_search($itemid, $scanned_selected_itemid)) !== false) {
                unset($scanned_selected_itemid[$key]);
            }
        }

        session()->put('scanned_selected_itemid', $scanned_selected_itemid);
	    $data['item_id'] = $scanned_selected_itemid;

        $data['success'] = true;
        $data['success_msg'] = 'session Recreated';

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }

    public function unset_session_scanned_data() 
    {
        $json =array();

        session()->forget('scanned_selected_itemid');

        if(!session()->has('scanned_selected_itemid')){
            $data['success'] = true;
            $data['success_msg'] = 'Session Cleared';
        }

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
        exit;
    }

    public function snapshot(Request $request)
    {
        $input = $request->all();
        
        $scanned_selected_itemid = session()->get('scanned_selected_itemid');
        $selected_itemid = session()->get('selected_itemid');
        $session_ipiid = session()->get('ipiid');
        
        if (isset($input['conditions']) && !empty($input['conditions']) && $input['conditions'] == 'all') {
            $all_itemid = DB::connection('mysql_dynamic')->select("SELECT iitemid FROM mst_item as mi WHERE mi.visinventory = 'Yes' AND mi.estatus='Active' ");
            
            $all_itemid = array_map(function ($value) {
                return (array)$value;
            }, $all_itemid);
            
            $selected_itemid = array();
            foreach ($all_itemid as $k => $itemid) {
                $selected_itemid[$k] = $itemid['iitemid'];
            }
            
            $result = PhysicalInventory::first()->snapshot($selected_itemid);
            if (isset($result)) {
                $data = $this->get_details_of_selected_data($result);
                
                return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
                unset($selected_itemid);
            } else {
                dd('Snapshot not taken');
            }
        } elseif (isset($scanned_selected_itemid) && count($scanned_selected_itemid) > 0 && $input['conditions'] == 'scanned_data') {
            
            $scanned_selected_itemid = $scanned_selected_itemid;
            $ids = join("','", $scanned_selected_itemid);
            $result = PhysicalInventory::first()->snapshot($scanned_selected_itemid);
                
            if (isset($result)) {
                
                $data = $this->get_details_of_selected_data($result);
                return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
            } else {
                dd('Snapshot not taken');
            }
        } elseif (isset($selected_itemid) && count($selected_itemid) > 0 && $input['conditions'] == 'session_filters_data') {
            
            $selected_itemid = $selected_itemid;
            $ids = join("','", $selected_itemid);
            $result = PhysicalInventory::first()->snapshot($selected_itemid);
            // dd($result);
            if (isset($result)) {
                
                $data = $this->get_details_of_selected_data($result);
                return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
            } else {
                dd('Snapshot not taken');
            }
        } elseif (!empty($input['item_search']) || !empty($input['sku_search']) || !empty(trim($input['price_select_by'])) || isset($input['dept_code']) || isset($input['category_code']) || isset($input['subcat_id']) || isset($input['supplier_code'])) {
            
            // print_r($input['price_select_by']); dd($input['select_by_value_1']);
            $show_condition = "WHERE mi.visinventory = 'Yes' AND mi.estatus='Active'";
            $condition = "";
                
            if (isset($input['item_search']) && !empty(trim($input['item_search']))) {
                $search = ($input['item_search']);
                $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
            }
                
            if (isset($input['sku_search']) && !empty(trim($input['sku_search']))) {
                $search = ($input['sku_search']);
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%'";
            }
                
            if (isset($input['price_select_by']) && !empty(trim($input['price_select_by'])) && isset($input['select_by_value_1']) && !empty($input['select_by_value_1'])) {
                $price_select_by = ($input['price_select_by']);
                $select_by_value_1 = ($input['select_by_value_1']);
                $select_by_value_2 = ($input['select_by_value_2']);
                
                if (empty($select_by_value_1)) {
                    $select_by_value_1 = 0;
                }
                
                if ($price_select_by == 'greater') {
                    $condition .= " AND mi.dunitprice > $select_by_value_1 ";
                } elseif ($price_select_by == 'less') {
                    $condition .= " AND mi.dunitprice < $select_by_value_1 ";
                } elseif ($price_select_by == 'equal') {
                    $condition .= " AND mi.dunitprice = $select_by_value_1 ";
                } elseif ($price_select_by == 'between') {
                    if (empty($select_by_value_2)) {
                        $select_by_value_2 = 0;
                    }
                    $condition .= " AND mi.dunitprice BETWEEN $select_by_value_1 AND $select_by_value_2 ";
                }
            }

            if (isset($input['dept_code']) && count($input['dept_code']) > 0) {
                $search = $input['dept_code'];
                $search = join("','", $search);
                $condition .= " AND mi.vdepcode IN ('" . ($search) . "')";
            }

            if (isset($input['category_code']) && count($input['category_code']) > 0) {
                $search = $input['category_code'];
                $search = join("','", $search);
                $condition .= " AND mi.vcategorycode IN ('" . ($search) . "')";
            }

            if (isset($input['supplier_code']) && count($input['supplier_code']) > 0) {
                $search = $input['supplier_code'];
                $search = join("','", $search);
                $condition .= " AND mi.vsuppliercode IN ('" . ($search) . "')";
            }

            if (isset($input['subcat_id']) && count($input['subcat_id']) > 0) {
                $search = $input['subcat_id'];
                $search = join("','", $search);
                $condition .= " AND mi.subcat_id IN ('" . stripslashes($search) . "')";
            }

            // dd("SELECT iitemid FROM mst_item as mi ".$show_condition.$condition);
            $all_itemid = DB::connection('mysql_dynamic')->select("SELECT iitemid FROM mst_item as mi " . $show_condition . $condition);
            
            $all_itemid = array_map(function ($value) {
                return (array)$value;
            }, $all_itemid);
            
            $selected_itemid = array();
            foreach ($all_itemid as $k => $itemid) {
                $selected_itemid[$k] = $itemid['iitemid'];
            }
            
            $result = PhysicalInventory::first()->snapshot($selected_itemid);
            
            if (isset($result)) {
                
                $data = $this->get_details_of_selected_data($result);
                
                return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
            } else {
                dd('Snapshot not taken');
            }
        } elseif (isset($session_ipiid) && !empty($session_ipiid)) {
            
            $data = $this->get_details_of_selected_data($session_ipiid);
            
            return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
        } else {
            return redirect(url('inventory/physicalInventroy'));
        }
    }
    
    public function show(Request $request)
    {
        
        ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);
		
		$input = $request->all();
		
        if (isset($input['ipiid'])){
            
            $ipiid = $input['ipiid'];
            
            $PhysicalInventory = new PhysicalInventory;
            $physical_inventory_detail_info = $PhysicalInventory->getPhysicalInventoryByIpiid($ipiid);
			
            $data['ipiid'] = $ipiid; 
            
            // if (isset($this->error['warning'])) {
            //     $data['error_warning'] = $this->error['warning'];
            // } else {
            // 	$data['error_warning'] = '';
            // }
            
            // if (isset($this->error['vrefnumber'])) {
            // 	$data['error_vrefnumber'] = $this->error['vrefnumber'];
            // } else {
            // 	$data['error_vrefnumber'] = '';
            // }
            
            // if (isset($this->error['vordertitle'])) {
            // 	$data['error_vordertitle'] = $this->error['vordertitle'];
            // } else {
            // 	$data['error_vordertitle'] = '';
            // }
            
            // if (isset($this->error['createdate'])) {
            // 	$data['error_dcreatedate'] = $this->error['createdate'];
            // } else {
            // 	$data['error_dcreatedate'] = '';
            // }
            
            // if (isset($this->error['calculatedate'])) {
            // 	$data['error_dcalculatedate'] = $this->error['dcalculatedate'];
            // } else {
            // 	$data['error_dcalculatedate'] = '';
            // }
            
            
            $data['url_commit'] = url('inventory/physicalInventroy/commit?ipiid='.$ipiid);
            $data['edit_calculate'] = url('inventory/physicalInventroy/edit_calculate_redirect?ipiid='.$ipiid);
            $data['selected_edit_calculate'] = url('inventory/physicalInventroy/selected_edit_calculate_redirect?ipiid='.$ipiid);
            $data['url_index'] = url('inventory/physicalInventroy');
            $data['getshow_data'] = url('inventory/physicalInventroy/getshow_data?ipiid='.$ipiid);
            $data['addshow_data'] = url('inventory/physicalInventroy/addshow_data?ipiid='.$ipiid);
            $data['pdf_summery'] = url('inventory/physicalInventroy/pdf_summery?ipiid='.$ipiid);
            $data['pdf_details'] = url('inventory/physicalInventroy/pdf_details?ipiid='.$ipiid);
            
            if (isset($input['vrefnumber'])) {
            	$data['vrefnumber'] = $input['vrefnumber'];
            } elseif (!empty($physical_inventory_detail_info)) {
            	$data['vrefnumber'] = $physical_inventory_detail_info['vrefnumber'];
            } 
            
            if (isset($input['vordertitle'])) {
            	$data['vordertitle'] = $input['vordertitle'];
            } elseif (!empty($physical_inventory_detail_info)) {
            	$data['vordertitle'] = $physical_inventory_detail_info['vordertitle'];
            } else {
            	$data['vordertitle'] = '';
            }
            
            if (isset($input['createdate'])) {
            	$data['dcreatedate'] = $input['createdate'];
            } elseif (count($physical_inventory_detail_info) > 0) {
            	$data['dcreatedate'] = $physical_inventory_detail_info['dcreatedate'];
            } else {
            	$data['dcreatedate'] = '';
            }
            
            if (isset($input['calculatedate'])) {
            	$data['dcalculatedate'] = $input['calculatedate'];
            } elseif (!empty($physical_inventory_detail_info)) {
            	$data['dcalculatedate'] = $physical_inventory_detail_info['dcalculatedate'];
            } else {
            	$data['dcalculatedate'] = '';
            }
            
            if (isset($input['dclosedate'])) {
            	$data['dclosedate'] = $input['dclosedate'];
            } elseif (!empty($physical_inventory_detail_info)) {
            	$data['dclosedate'] = $physical_inventory_detail_info['dclosedate'];
            } else {
            	$data['dclosedate'] = '';
            }
            
            if (isset($input['estatus'])) {
            	$data['estatus'] = $input['estatus'];
            } elseif (!empty($physical_inventory_detail_info)) {
            	$data['estatus'] = $physical_inventory_detail_info['estatus'];
            }
            
            $sql = "SELECT SUM(tpid.ndebitqty) as total_counted, SUM(tpid.ndebitqty + tpid.ndiffqty) as total_expected, SUM(tpid.ndiffqty) as total_difference,
                    SUM(tpid.ndiffqty * (mi.dcostprice/mi.npack)) as total_difference_cost,
                    SUM(tpid.ndiffqty * mi.dunitprice) as total_difference_price,
                    SUM(tpid.ndebitqty * (mi.dcostprice/mi.npack)) as total_counted_cost,
                    SUM(tpid.ndebitqty * mi.dunitprice) as total_counted_price,
                    SUM((tpid.ndebitqty + tpid.ndiffqty) * (mi.dcostprice/mi.npack)) as total_expected_cost,
                    SUM((tpid.ndebitqty + tpid.ndiffqty) * mi.dunitprice) as total_expected_price,
                    SUM((tpid.ndebitqty - (tpid.ndebitqty + tpid.ndiffqty)) * (mi.dunitprice - (mi.dcostprice/mi.npack))) as total_profit_loss_difference
                    FROM trn_physicalinventorydetail as tpid
                    LEFT JOIN mst_item as mi ON(mi.iitemid = tpid.vitemid)
                    WHERE tpid.ipiid = '".$ipiid."' ORDER BY tpid.vbarcode";
            $items = DB::connection('mysql_dynamic')->select($sql);
            $items = isset($items[0])?(array)$items[0]:[];
            
            $data['total_counted'] = $items['total_counted'];
            $data['total_counted_cost'] = ($items['total_counted_cost']);
            $data['total_counted_price'] = ($items['total_counted_price']);
            $data['total_expected'] = $items['total_expected'];
            $data['total_expected_cost'] = ($items['total_expected_cost']);
            $data['total_expected_price'] = ($items['total_expected_price']);
            $data['total_difference'] = $items['total_difference'];
            $data['total_difference_cost'] = ($items['total_difference_cost']);
            $data['total_difference_price'] = ($items['total_difference_price']);
            $data['total_profit_loss_difference'] =$items['total_profit_loss_difference'];
            
            return view('inventory.physicalInventroy.physical_inventory_calculate', compact('data'));
        }
    }
    
    public function getshow_data(Request $request)
    {
        
        ini_set('max_execution_time', 0);
        
        $input = $request->all();
        
        $ipiid = $input['ipiid'];
        
         $result = array();
        
        $limit = 00;
         
        $start_from = ($input['start']);
            
        // $offset = $input['start']+$input['length'];
        $length = $input['length'];
        
        $PhysicalInventory = new PhysicalInventory;
        
        if((isset($input['columns'][4]['search']['value']) && $input['columns'][4]['search']['value'] == 'show') || (isset($input['type']) && $input['type'] == 'show') ){
               
            $items = $PhysicalInventory->getPhyInventoryItemByIpiid($ipiid, $start_from, $length);
            $count_select_query = "SELECT count(vitemid) as count FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' ";
                
        }else{
            $condition =  0;
            $items = $PhysicalInventory->getPhyInventoryItemByIpiid($ipiid, $start_from, $length, $condition);
            $count_select_query = "SELECT count(vitemid) as count FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' AND ndiffqty != 0 ";
        }
        
        $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
        $count_query = isset($count_query[0])?(array)$count_query[0]:[];
        
        $count_records = $count_total = (int)$count_query['count'];
             
        foreach($items as $k => $item){
            
            $iqtyonhand = DB::connection('mysql_dynamic')->select("SELECT iqtyonhand From mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' AND iitemid = '".$item['vitemid']."' ");
            $iqtyonhand = isset($iqtyonhand[0])?(array)$iqtyonhand[0]:[];
            
            $cost_price_vitemcode = DB::connection('mysql_dynamic')->select("SELECT (dcostprice/npack) as unitcost, dunitprice, vitemcode FROM mst_item WHERE iitemid = '".$item['vitemid']."' ");
            $cost_price_vitemcode = isset($cost_price_vitemcode[0])?(array)$cost_price_vitemcode[0]:[];
            
            //             $from = $physical_inventory_detail_info['dcreatedate'];
            //             $to = $physical_inventory_detail_info['dcalculatedate'];
            //             $soldqty_query = "SELECT ifnull(SUM(tsd.ndebitqty), 0) as soldqty FROM trn_salesdetail as tsd LEFT JOIN trn_sales as ts ON(ts.isalesid = tsd.isalesid) 
            //                               WHERE ts.vtrntype='Transaction' AND tsd.vitemcode = '".$cost_price_vitemcode['vitemcode']."' AND ts.dtrandate >= '".$from."' AND ts.dtrandate <= '".$to."' 
            //                               GROUP BY vitemcode ";
            // 			$soldqty = DB::connection('mysql_dynamic')->select($soldqty_query)->row;
            			
            // 			$expected = ($iqtyonhand['iqtyonhand'] - $soldqty['soldqty']);
            
            $difference = $item['ndiffqty'];
            $expected = $difference + $item['ndebitqty'];
            
            if($expected == 0){
                $difference_percentage = '-';
            }else{
                $difference_percentage = number_format(($difference / ($expected) * 100), 2);
            }
            //item data not found changed  HGB
            
            $cost_price_vitemcode['unitcost']=isset($cost_price_vitemcode['unitcost'])? $cost_price_vitemcode['unitcost']:0;
            $cost_price_vitemcode['dunitprice']=isset($cost_price_vitemcode['dunitprice'])?$cost_price_vitemcode['dunitprice']:0;
            
            
            $diff_total_cost    = $difference * $cost_price_vitemcode['unitcost'];
            $diff_total_price   = $difference * $cost_price_vitemcode['dunitprice'];
            $diff_profit_loss   = (((int)$item['ndebitqty'] - $expected) * ($cost_price_vitemcode['dunitprice'] - $cost_price_vitemcode['unitcost']));
			
            $result[] = array(
                            'ipidetid'  => $item['ipidetid'],
                            'vitemid'   => $item['vitemid'],
                            'vbarcode'  => $item['vbarcode'],
                            'vitemname' => $item['vitemname'],
                            'iqtyonhand'=> $iqtyonhand['iqtyonhand'] ?? 0,
                            'ndebitqty' => (int)$item['ndebitqty'],
                            'ndiffqty'  => $item['ndiffqty'],
                            'unitcost'  => $cost_price_vitemcode['unitcost'],
                            'dunitprice'=> $cost_price_vitemcode['dunitprice'],
                            'expected'  => $expected,
                            'difference'=> (int)$difference,
                            'difference_percentage' => $difference_percentage,
                            'diff_total_cost'       => number_format($diff_total_cost, 2),
                            'diff_total_price'      => number_format($diff_total_price, 2),
                            // 'diff_profit_loss'      => number_format(($diff_total_price - $diff_total_cost), 2),
                            'diff_profit_loss'      => number_format($diff_profit_loss, 2),
                            'counted_cost'          => number_format(((int)$item['ndebitqty'] * $cost_price_vitemcode['unitcost']), 2),
                            'counted_price'         => number_format(((int)$item['ndebitqty'] * $cost_price_vitemcode['dunitprice']), 2),
                            'expected_cost'         => number_format(($expected * $cost_price_vitemcode['unitcost']), 2),
                            'expected_price'        => number_format(($expected * $cost_price_vitemcode['dunitprice']), 2),
                            );
        
        }  
        
        // $data['phy_inventory_items'] = $reault;
        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $result;
        
        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
    }
    
    public function addshow_data(Request $request)
    {
        ini_set('max_execution_time', 0);
        
        $input = $request->all();
        
        $ipiid = $input['ipiid'];
        
        // $input = $input;
        
        $length = $limit = 50;
        
        $start_from = ($input['start']);
            
        // $length = $input['length'];
        
        $PhysicalInventory = new PhysicalInventory;
        
        if((isset($input['columns'][4]['search']['value']) && $input['columns'][4]['search']['value'] == 'show') || (isset($input['type']) && $input['type'] == 'show') ){
               
            $items = $PhysicalInventory->getPhyInventoryItemByIpiid($ipiid, $start_from, $length);
            $count_select_query = "SELECT count(vitemid) as count FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' ";
            
        }else{
            $condition =  0;
            $items = $PhysicalInventory->getPhyInventoryItemByIpiid($ipiid, $start_from, $length, $condition);
            $count_select_query = "SELECT count(vitemid) as count FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' AND ndiffqty != 0 ";
        }
        
        $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
        $count_query = isset($count_query[0])?(array)$count_query[0]:[];
        
        $count_records = $count_total = (int)$count_query['count'];
            // dd($count_records);
        
        $row = '';
        foreach($items as $k => $item){
            $iqtyonhand = DB::connection('mysql_dynamic')->select("SELECT iqtyonhand From mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' AND iitemid = '".$item['vitemid']."' ");
            $iqtyonhand = isset($iqtyonhand[0])?(array)$iqtyonhand[0]:[];
            
            $cost_price_vitemcode = DB::connection('mysql_dynamic')->select("SELECT (dcostprice/npack) as unitcost, dunitprice, vitemcode FROM mst_item WHERE iitemid = '".$item['vitemid']."' ");
            $cost_price_vitemcode = isset($cost_price_vitemcode[0])?(array)$cost_price_vitemcode[0]:[];
            
            $difference = $item['ndiffqty'];
            $expected = $difference + $item['ndebitqty'];
            
            // $difference_percentage = number_format(($difference / ($expected) * 100), 2);
            if($expected == 0){
                $difference_percentage = '-';
            }else{
                $difference_percentage = number_format(($difference / ($expected) * 100), 2);
            }
            
            $diff_total_cost    = $difference * $cost_price_vitemcode['unitcost'];
            $diff_total_price   = $difference * $cost_price_vitemcode['dunitprice'];
            
            // $diff_profit_loss   = ($diff_total_price - $diff_total_cost);
            $diff_profit_loss   = (((int)$item['ndebitqty'] - $expected) * ($cost_price_vitemcode['dunitprice'] - $cost_price_vitemcode['unitcost']));
			
            $row .= "<tr id='".$k."'>";
            $row .= "<td>".$item['vbarcode']."</td>";
            $row .= "<td style='width:125px !important;'>".$item['vitemname']."</td>";
            $row .= "<td style='width:41px !important;'>".(int)$item['ndebitqty']."</td>";
            $row .= "<td style='width:55px !important;'>".number_format(((int)$item['ndebitqty'] * $cost_price_vitemcode['unitcost']), 2)."</td>";
            $row .= "<td style='width:55px !important;'>".number_format(((int)$item['ndebitqty'] * $cost_price_vitemcode['dunitprice']), 2)."</td>";
            $row .= "<td style='width:90px !important;'>".$expected."</td>";
            $row .= "<td style='width:58px !important;'>".number_format(($expected * $cost_price_vitemcode['unitcost']), 2)."</td>";
            $row .= "<td style='width:60px !important;'>".number_format(($expected * $cost_price_vitemcode['dunitprice']), 2)."</td>";
            
            if($difference > 0){
                $row .= "<td style='background-color:red; color:white; width:63px !important;'>".(int)$difference."</td>";
                $row .= "<td style='background-color:red; color:white; width:105px !important;'>".$difference_percentage."</td>";
                $row .= "<td style='background-color:red; color:white; width:64px !important;'>".number_format($diff_total_cost, 2)."</td>";
                $row .= "<td style='background-color:red; color:white; width:83px !important;'>".number_format($diff_total_price, 2)."</td>";
                $row .= "<td style='background-color:red; color:white; width:102px !important;'>".number_format($diff_profit_loss, 2)."</td>";
            
            }elseif($difference < 0){
                $row .= "<td style='background-color:green; color:white; width:63px !important;'>".(int)$difference."</td>";
                $row .= "<td style='background-color:green; color:white; width:105px !important;'>".$difference_percentage."</td>";
                $row .= "<td style='background-color:green; color:white; width:64px !important;'>".number_format($diff_total_cost, 2)."</td>";
                $row .= "<td style='background-color:green; color:white; width:83px !important;'>".number_format($diff_total_price, 2)."</td>";
                $row .= "<td style='background-color:green; color:white; width:102px !important;'>".number_format($diff_profit_loss, 2)."</td>";
            
            }else{
                $row .= "<td style='width:63px !important;'>".(int)$difference."</td>";
                $row .= "<td style='width:105px !important;'>".$difference_percentage."</td>";
                $row .= "<td style='width:64px !important;'>".number_format($diff_total_cost, 2)."</td>";
                $row .= "<td style='width:83px !important;'>".number_format($diff_total_price, 2)."</td>";
                $row .= "<td style='width:102px !important;'>".number_format(($diff_total_price - $diff_total_cost), 2)."</td>";
            
            }
            
            $row .= "</tr>";
            // $reault[] = $row;
        
        }  
        // dd($reault);$count_records
        $return['totalData'] = $count_records;
        $return['data'] = $row;
        
        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
    }
    
    public function pdf_summery(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $input = $request->all();
        
        $ipiid = $input['ipiid'];
        
        $sql = "SELECT SUM(tpid.ndebitqty) as total_counted, SUM(tpid.ndebitqty + tpid.ndiffqty) as total_expected, SUM(tpid.ndiffqty) as total_difference,
                    SUM(tpid.ndiffqty * (mi.dcostprice/mi.npack)) as total_difference_cost,
                    SUM(tpid.ndiffqty * mi.dunitprice) as total_difference_price,
                    SUM(tpid.ndebitqty * (mi.dcostprice/mi.npack)) as total_counted_cost,
                    SUM(tpid.ndebitqty * mi.dunitprice) as total_counted_price,
                    SUM((tpid.ndebitqty + tpid.ndiffqty) * (mi.dcostprice/mi.npack)) as total_expected_cost,
                    SUM((tpid.ndebitqty + tpid.ndiffqty) * mi.dunitprice) as total_expected_price,
                    SUM((tpid.ndebitqty - (tpid.ndebitqty + tpid.ndiffqty)) * (mi.dunitprice - (mi.dcostprice/mi.npack))) as total_profit_loss_difference
                    FROM trn_physicalinventorydetail as tpid
                    LEFT JOIN mst_item as mi ON(mi.iitemid = tpid.vitemid)
                    WHERE tpid.ipiid = '".$ipiid."' ORDER BY tpid.vbarcode";
                    
            $items = DB::connection('mysql_dynamic')->select($sql);
            $items = isset($items[0])?(array)$items[0]:[];
            
            $data['total_counted'] = $items['total_counted'];
            $data['total_counted_cost'] = ($items['total_counted_cost']);
            $data['total_counted_price'] = ($items['total_counted_price']);
            $data['total_expected'] = $items['total_expected'];
            $data['total_expected_cost'] = ($items['total_expected_cost']);
            $data['total_expected_price'] = ($items['total_expected_price']);
            $data['total_difference'] = $items['total_difference'];
            $data['total_difference_cost'] = ($items['total_difference_cost']);
            $data['total_difference_price'] = ($items['total_difference_price']);
            $data['total_profit_loss_difference'] = $items['total_profit_loss_difference'];
            
        $PhysicalInventory = new PhysicalInventory;
        $physical_inventory_detail_info = $PhysicalInventory->getPhysicalInventoryByIpiid($ipiid);
        
        $data['vrefnumber'] = $physical_inventory_detail_info['vrefnumber'];
        $data['estatus'] = $physical_inventory_detail_info['estatus'];
        $data['vordertitle'] = $physical_inventory_detail_info['vordertitle'];
        $data['dcreatedate'] = $physical_inventory_detail_info['dcreatedate'];
        $data['dcalculatedate'] = $physical_inventory_detail_info['dcalculatedate'];
        $data['dclosedate'] = $physical_inventory_detail_info['dclosedate'];
			
		  //  $store_data = Store::first();
        $data['storename'] = session()->get('storeName');
        $data['storeaddress'] = session()->get('address');
        $data['storephone'] = session()->get('phone');
        $data['heading_title'] = 'Physical Inventory Report(Summary)';
       
        // $this->response->setOutput($this->load->view('administration/pdf_phy_inventory_summery_report', $data));
        // $html = $this->load->view('administration/pdf_phy_inventory_summery_report', $data);
        
        // $this->dompdf->loadHtml($html);
        
        //(Optional) Setup the paper size and orientation
        // $this->dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        // $this->dompdf->render();
        
        // Output the generated PDF to Browser
        // $this->dompdf->stream('phy-inv-summery-Report.pdf');
        
        $pdf = PDF::loadView('inventory.physicalInventroy.pdf_phy_inventory_summery_report',$data)->setPaper('a4', 'landscape');
        
        return $pdf->stream('phy-inv-summery-Report.pdf');
    }
    
    public function pdf_details(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $input = $request->all();
        
        $ipiid = $input['ipiid'];
        
        $sql = "SELECT SUM(tpid.ndebitqty) as total_counted, SUM(tpid.ndebitqty + tpid.ndiffqty) as total_expected, SUM(tpid.ndiffqty) as total_difference,
                    SUM(tpid.ndiffqty * (mi.dcostprice/mi.npack)) as total_difference_cost,
                    SUM(tpid.ndiffqty * mi.dunitprice) as total_difference_price,
                    SUM(tpid.ndebitqty * (mi.dcostprice/mi.npack)) as total_counted_cost,
                    SUM(tpid.ndebitqty * mi.dunitprice) as total_counted_price,
                    SUM((tpid.ndebitqty + tpid.ndiffqty) * (mi.dcostprice/mi.npack)) as total_expected_cost,
                    SUM((tpid.ndebitqty + tpid.ndiffqty) * mi.dunitprice) as total_expected_price,
                    SUM((tpid.ndebitqty - (tpid.ndebitqty + tpid.ndiffqty)) * (mi.dunitprice - (mi.dcostprice/mi.npack))) as total_profit_loss_difference
                    FROM trn_physicalinventorydetail as tpid
                    LEFT JOIN mst_item as mi ON(mi.iitemid = tpid.vitemid)
                    WHERE tpid.ipiid = '".$ipiid."' ORDER BY tpid.vbarcode";
                    
            $items = DB::connection('mysql_dynamic')->select($sql);
            $items = isset($items[0])?(array)$items[0]:[];
            
            $data['total_counted'] = $items['total_counted'];
            $data['total_counted_cost'] = ($items['total_counted_cost']);
            $data['total_counted_price'] = ($items['total_counted_price']);
            $data['total_expected'] = $items['total_expected'];
            $data['total_expected_cost'] = ($items['total_expected_cost']);
            $data['total_expected_price'] = ($items['total_expected_price']);
            $data['total_difference'] = $items['total_difference'];
            $data['total_difference_cost'] = ($items['total_difference_cost']);
            $data['total_difference_price'] = ($items['total_difference_price']);
            $data['total_profit_loss_difference'] = $items['total_profit_loss_difference'];
        
        $condition = 0;
        $sql_items = "SELECT tpid.ipidetid, tpid.ipiid, tpid.vitemid, tpid.vitemname, tpid.vbarcode, tpid.npackqty, tpid.ndebitqty, tpid.ndiffqty FROM trn_physicalinventorydetail as tpid WHERE tpid.ipiid = '".$ipiid."' AND tpid.ndiffqty != '".$condition."' ORDER BY tpid.vbarcode";
        $items = DB::connection('mysql_dynamic')->select($sql_items);
        
        $items = array_map(function ($value) {
            return (array)$value;
        }, $items);
        
        foreach($items as $k => $item){
            
            $qty_query = "SELECT iqtyonhand From mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' AND iitemid = '".$item['vitemid']."' ";
            $iqtyonhand = DB::connection('mysql_dynamic')->select($qty_query);
            $iqtyonhand = isset($iqtyonhand[0])?(array)$iqtyonhand[0]:[];
            
            $cost_price_vitemcode = DB::connection('mysql_dynamic')->select("SELECT (dcostprice/npack) as unitcost, dunitprice, vitemcode FROM mst_item WHERE iitemid = '".$item['vitemid']."' ");
            $cost_price_vitemcode = isset($cost_price_vitemcode[0])?(array)$cost_price_vitemcode[0]:[];
            
            $difference = $item['ndiffqty'];
            $expected = $difference + $item['ndebitqty'];
            
            if($expected == 0){
                $difference_percentage = '-';
            }else{
                $difference_percentage = number_format(($difference / ($expected) * 100), 2);
            }
            
            $diff_total_cost    = $difference * $cost_price_vitemcode['unitcost'];
            $diff_total_price   = $difference * $cost_price_vitemcode['dunitprice'];
			
            $reault[] = array(
                            'vbarcode'  => $item['vbarcode'],
                            'vitemname' => $item['vitemname'],
                            'iqtyonhand'=> $iqtyonhand['iqtyonhand'],
                            'ndebitqty' => (int)$item['ndebitqty'],
                            'ndiffqty'  => $item['ndiffqty'],
                            'unitcost'  => $cost_price_vitemcode['unitcost'],
                            'dunitprice'=> $cost_price_vitemcode['dunitprice'],
                            'expected'  => $expected,
                            'difference'=> $difference,
                            'difference_percentage' => $difference_percentage,
                            'counted_cost'          => number_format(($item['ndebitqty'] * $cost_price_vitemcode['unitcost']), 2),
                            'counted_price'         => number_format(($item['ndebitqty'] * $cost_price_vitemcode['dunitprice']), 2),
                            'expected_cost'         => number_format(($expected * $cost_price_vitemcode['unitcost']), 2),
                            'expected_price'        => number_format(($expected * $cost_price_vitemcode['dunitprice']), 2),
                            'diff_total_cost'       => number_format($diff_total_cost, 2),
                            'diff_total_price'      => number_format($diff_total_price, 2),
                            'diff_profit_loss'      => number_format(($diff_total_price - $diff_total_cost), 2)
                            );
        // print_r("echo".$item['vbarcode']);
        }
        
        $PhysicalInventory = new PhysicalInventory;
        $physical_inventory_detail_info = $PhysicalInventory->getPhysicalInventoryByIpiid($ipiid);
        
        $data['vrefnumber'] = $physical_inventory_detail_info['vrefnumber'];
        $data['estatus'] = $physical_inventory_detail_info['estatus'];
        $data['vordertitle'] = $physical_inventory_detail_info['vordertitle'];
        $data['dcreatedate'] = $physical_inventory_detail_info['dcreatedate'];
        $data['dcalculatedate'] = $physical_inventory_detail_info['dcalculatedate'];
        $data['dclosedate'] = $physical_inventory_detail_info['dclosedate'];
        
        $data['items'] = $reault;
			
        // $store_data = Store::first();
        $data['storename'] = session()->get('storeName');
        $data['storeaddress'] = session()->get('address');
        $data['storephone'] = session()->get('phone');
        $data['heading_title'] = 'Physical Inventory Report(Details)';
       
        // $this->response->setOutput($this->load->view('administration/pdf_phy_inventory_details_report', $data));
        // $html = $this->load->view('administration/pdf_phy_inventory_details_report', $data);
                
        // $this->dompdf->loadHtml($html);

        //(Optional) Setup the paper size and orientation
        // $this->dompdf->setPaper('A4', 'landscape');
    
        // Render the HTML as PDF
        // $this->dompdf->render();
    
        // Output the generated PDF to Browser
        // $this->dompdf->stream('Phy-Inventory-Report.pdf');
        
        $pdf = PDF::loadView('inventory.physicalInventroy.pdf_phy_inventory_details_report',$data)->setPaper('a4', 'landscape');
        
        return $pdf->download('Phy-Inventory-Report.pdf');
        
    }
    
    public function commit(Request $request)
    {
        ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);
        
		$json = array();
		
		if ($request->isMethod('post')) {
            
            $input = $request->all();
            
			$items = array();
			$ipiid = $input['ipiid'];
			
			if(isset($input['ipiid']) && !empty($input['ipiid'])){
                
                $query_items = "SELECT vitemid, ndebitqty, itotalunit FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' ";
                $items = DB::connection('mysql_dynamic')->select($query_items);
                
                $items = array_map(function ($value) {
                    return (array)$value;
                }, $items);
                
                $temp_array = array(
                                        'ipiid' => $ipiid,
                                        'dclosedate' => date('Y-m-d H:i:s'),
                                        'items' => $items
                                       );
                
                $PhysicalInventory = new PhysicalInventory;
                $result = $PhysicalInventory->commit($temp_array);
                unset($temp_array);
			}
		}
		
		return response(json_encode($result), 200)
                  ->header('Content-Type', 'application/json');
    }
    
    public function get_details_of_selected_data($ipiid, $action = null, $error = null)
    {
        
        // if (isset($this->error['warning'])) {
        // 	$data['error_warning'] = $this->error['warning'];
        // } else {
        // 	$data['error_warning'] = '';
        // }
        
        // if (isset($this->error['vrefnumber'])) {
        // 	$data['error_vrefnumber'] = $this->error['vrefnumber'];
        // } else {
        // 	$data['error_vrefnumber'] = '';
        // }
        
        // if (isset($this->error['vordertitle'])) {
        // 	$data['error_vordertitle'] = $this->error['vordertitle'];
        // } else {
        // 	$data['error_vordertitle'] = '';
        // }
        
        // if (isset($this->error['dcreatedate'])) {
        // 	$data['error_dcreatedate'] = $this->error['dcreatedate'];
        // } else {
        // 	$data['error_dcreatedate'] = '';
        // }
        
        if($action === 'edit_calculate') {
                
                $data['edit_calculate'] = $action;
                $data['action'] = $action;
                $data['url_calculate'] = url('inventory/physicalInventroy/edit_calculate?ipiid='.$ipiid);
		        $data['url_calculate_commit'] = url('inventory/physicalInventroy/edit_calculate?ipiid='.$ipiid . '&action=edit_calculate_commit');
                $data['url_view_calculate'] = url('inventory/physicalInventroy/show?ipiid='.$ipiid);      
	            $data['import_inventory_count'] = url('inventory/physicalInventroy/import_inventory_count?ipiid='.$ipiid);      
	            $data['display_selected_data'] = url('inventory/physicalInventroy/display_selected_data?ipiid='.$ipiid);      
                $data['export_csv'] = url('inventory/physicalInventroy/export_csv?ipiid='.$ipiid);  
                $data['bulk_import'] = url('inventory/physicalInventroy/bulk_import?ipiid='.$ipiid . '&action=' . $action);      
                $data['create_inventory_session'] = url('inventory/physicalInventroy/create_inventory_session?ipiid='.$ipiid);      
                $data['unset_session_inventory_count'] = url('inventory/physicalInventroy/unset_session_inventory_count');
                $data['assign_inventory_users_url'] = url('inventory/physicalInventroy/assign_inventory_users');
                
        }elseif($action === 'selected_edit_calculate'){
                $data['selected_edit_calculate'] = $action;
                $data['action'] = $action;
                $data['url_calculate'] = url('inventory/physicalInventroy/selected_edit_calculate');
		        //$data['url_calculate_commit'] = url('inventory/physicalInventroy/edit_calculate', 'token=' . $this->session->data['token'].'&ipiid=' . $ipiid . '&action=edit_calculate_commit' . $url, true);
                $data['url_view_calculate'] = url('inventory/physicalInventroy/show?ipiid='.$ipiid);      
	            $data['display_selected_data'] = url('inventory/physicalInventroy/display_selected_edit_calculate_data?ipiid='.$ipiid);      
                $data['export_csv'] = url('inventory/physicalInventroy/export_csv?ipiid='.$ipiid);  
                $data['bulk_import'] = url('inventory/physicalInventroy/bulk_import?ipiid='.$ipiid . '&action=' . $action);      
                $data['create_inventory_session'] = url('inventory/physicalInventroy/create_inventory_session?ipiid='.$ipiid);      
                $data['unset_session_inventory_count'] = url('inventory/physicalInventroy/unset_session_inventory_count');
                $data['assign_inventory_users_url'] = url('inventory/physicalInventroy/assign_inventory_users');
                
        }else{
                
                $data['action'] = 'calculate';
                // $data['url_action'] = url('inventory/physicalInventroy/calculate'?ipiid='.$ipiid);
                $data['url_calculate'] = url('inventory/physicalInventroy/calculate?ipiid='.$ipiid);
                $data['url_calculate_commit'] = url('inventory/physicalInventroy/calculate_commit?ipiid='.$ipiid);
                $data['url_view_calculate'] = url('inventory/physicalInventroy/show?ipiid='.$ipiid);      
                //$data['import_inventory_count'] = url('inventory/physicalInventroy/import_inventory_count?ipiid='.$ipiid);      
	            $data['display_selected_data'] = url('inventory/physicalInventroy/display_selected_data?ipiid='.$ipiid);      
                $data['export_csv'] = url('inventory/physicalInventroy/export_csv?ipiid='.$ipiid);      
                $data['bulk_import'] = url('inventory/physicalInventroy/bulk_import?ipiid='.$ipiid);      
                $data['create_inventory_session'] = url('inventory/physicalInventroy/create_inventory_session?ipiid='.$ipiid);      
                $data['unset_session_inventory_count'] = url('inventory/physicalInventroy/unset_session_inventory_count');
                $data['assign_inventory_users_url'] = url('inventory/physicalInventroy/assign_inventory_users');
                
        }    
        
        $data['cancel'] = url('inventory/physicalInventroy');
        //$data['items'] = $itemdata;
        $phy_inventory_data = DB::connection('mysql_dynamic')->select("SELECT vrefnumber, vordertitle, estatus, dcreatedate, dcalculatedate FROM trn_physicalinventory WHERE ipiid = '".$ipiid."' ");
        $phy_inventory_data = isset($phy_inventory_data[0])?(array)$phy_inventory_data[0]:[];
        
        $data['vrefnumber'] = $phy_inventory_data['vrefnumber'];
        $data['vordertitle'] = $phy_inventory_data['vordertitle'];
        $data['dcreatedate'] = $phy_inventory_data['dcreatedate'];
        $data['dcalculatedate'] = $phy_inventory_data['dcalculatedate'];
        $data['estatus'] = $phy_inventory_data['estatus'];
        $data['ipiid'] = $ipiid;
        
        $users = DB::connection('mysql_dynamic')->select("SELECT iuserid, vfname, vlname, vemail FROM mst_user Where SID = '".session()->get('sid')."' ");
        $users = array_map(function ($value) {
                return (array)$value;
            }, $users);
        
        $data['users'] = $users;
        
        $check = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM mst_physical_inventory_assign_users WHERE ipiid = '".$ipiid."' ");
        
        if(isset($check) && count($check)){
            
            $assigned_users = DB::connection('mysql_dynamic')->select("SELECT user_id FROM mst_physical_inventory_assign_users WHERE ipiid = '".$ipiid."'");
            $assigned_users = array_map(function ($value) {
                return (array)$value;
            }, $assigned_users);
            $data['assigned_users'] = $assigned_users;
        }else{
            $data['assigned_users'] = '';
        }
        
        $data['error'] = $error;
        unset($itemdata);
        
        return $data;
    }
    
    public function edit_open(Request $request)
    {
        $input = $request->all();
        if ($request->isMethod('post')) {
            
        }
        // dd($input['ipiid']);
        $action = 'edit_open';
        $data = $this->get_details_of_selected_data($input['ipiid'], $action);
        
        return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
    }
    
    public function delete_physical(Request $request){
       $input = $request->all();
         $ipiid=$input['ipiid'];
        
         
        if(isset($ipiid)){
             DB::connection('mysql_dynamic')->delete("DELETE FROM trn_physicalinventory  WHERE ipiid = '".$ipiid."' ");
             DB::connection('mysql_dynamic')->delete("DELETE FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' "); 
             Session::flash('message','Physical Inventory Deleted Successfully!');
         }
         else{
         Session::flash('message','Something Went Wrong!');
         }
    
    
        return redirect()->route('inventory.physicalInventroy');
         
    }
    
    public function calculate(Request $request)
    {
        $input = $request->all();
        
        if ($request->isMethod('post')) {
            
			$items = array();
			$ndebitextprice = 0;
			$totalndebitextprice = 0;
			
			if(isset($input['ipiid']) && $input['ipiid'] != ''){
                   
			    $ipiid = $input['ipiid'];
                if(isset($input['action']) && $input['action'] == 'import'){
                    
                    $items_query = "SELECT mpis.iitemid, mpis.vbarcode, mpis.iqtyonhand, ipic.inventorycount as ndebitqty FROM mst_physical_inventory_snapshot mpis LEFT JOIN import_physical_inventory_csv_table as ipic ON(mpis.vbarcode = ipic.vbarcode) WHERE ipiid = '".$ipiid."' ";
                    
                    $items = DB::connection('mysql_dynamic')->select($items_query);
                    
                    $items = array_map(function ($value) {
                        return (array)$value;
                    }, $items);
                    
                }else{
                    
                    $snapshot_items_query = "SELECT mpis.iitemid, mpis.vbarcode, mpis.iqtyonhand FROM mst_physical_inventory_snapshot mpis WHERE ipiid = '".$ipiid."' ";
                    
                    $snapshot_items = DB::connection('mysql_dynamic')->select($snapshot_items_query);
                    
                    $snapshot_items = array_map(function ($value) {
                        return (array)$value;
                    }, $snapshot_items);
                    
                    $selected_vbarcode_arr = session()->get('selected_vbarcode_arr');
                    
                    foreach($snapshot_items as $value){
                        if(isset($selected_vbarcode_arr) && !empty($selected_vbarcode_arr)){
                            foreach($selected_vbarcode_arr as $key => $vbarcode){
                                
                               if($vbarcode[0]['vbarcode'] == $value['vbarcode']){
                                   $ndebitqty = $vbarcode[0]['invtcount'];
                                   break;
                               }else{
                                   $ndebitqty = 0;
                               }
                            }
                        }else{
                            $ndebitqty = 0;
                        }
                        $items[] =  array(
                                            'iitemid'   =>  $value['iitemid'],
                                            'vbarcode'  =>  $value['vbarcode'],
                                            'ndebitqty' =>  $ndebitqty,
                                            'iqtyonhand'=>  $value['iqtyonhand']
                                        );
                    }
                }
                
                foreach ($items as $k => $item) 
                {
                    $itemdetail = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid = '".$item['iitemid']."' ");
                    $itemdetail = isset($itemdetail[0])?(array)$itemdetail[0]:[];
                    
                    $itotalunit = $item['ndebitqty'];
                    $ndebitextprice = $itotalunit * $itemdetail['nunitcost'];
                    
                    //========for sold item========
                    //======vitemcode = vbarcode both same=======
                    $from = $input['dcreatedate'];
                    $to = date('Y-m-d H:i:s');
                    $soldqty = DB::connection('mysql_dynamic')->select("SELECT ifnull(SUM(tsd.ndebitqty), 0) as soldqty FROM trn_salesdetail as tsd LEFT JOIN trn_sales as ts ON(ts.isalesid = tsd.isalesid) WHERE ts.vtrntype='Transaction' AND tsd.vitemcode = '".$item['vbarcode']."' AND ts.dtrandate >= '".$from."' AND ts.dtrandate <= '".$to."' GROUP BY vitemcode ");
                    $soldqty = isset($soldqty[0])?(array)$soldqty[0]:[];
                    
                    $ndiffqty = $item['iqtyonhand'] - ($soldqty['soldqty'] ?? 0) - $item['ndebitqty'];
                    
                    
                    //=====creating values for bulk insert===================
					$sql[] = "( '" . (int)$ipiid . "', '" . ($itemdetail['iitemid']) . "', \"" . ($itemdetail['vitemname']) . "\", ' ', ' ', '" . ($item['ndebitqty']) . "', '0.00', '0.00', '0.00', '0.00', '" . ($ndebitextprice) . "', '0.00', '0.00', '0.00', '" . ($itemdetail['vbarcode']) . "', ' ', '" . ($ndiffqty) . "', ' ', '" . ($itemdetail['npack']) . "', '" . ($itemdetail['nunitcost']) . "', '" . ($itotalunit) . "', '" . (int)(session()->get('sid'))."', '". $itemdetail['iqtyonhand']."', '".$item['ndebitqty'] ."' )";
                        
					$totalndebitextprice = $totalndebitextprice + $ndebitextprice;
					
				} 
			}
            
			$temp_arr[0] = array(
			                    'ipiid' => $input['ipiid'],
								'vpinvtnumber' => '',
								'vrefnumber' => $input['vrefnumber'],
								// 'nnettotal' => $ndebitextprice,
								'nnettotal' => $totalndebitextprice,
								'ntaxtotal' => '0.00',
								'dcreatedate' => \DateTime::createFromFormat('m-d-Y H:i:s', $input['dcreatedate'])->format('Y-m-d H:i:s'),
								'estatus' => 'Calculated',
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'] ?? '',
								'dlastupdate' => '',
								'vtype' => 'Physical',
								'ilocid' => $input['ilocid'] ?? '',
								'dcalculatedate' => date('Y-m-d H:i:s'),
								'dclosedate' => '',
								'items' => $sql,
								'detail_name' => 'physical'
									
							);
							
			$result = PhysicalInventory::first()->addNewPhysicalInventory($temp_arr);
			
            return response(json_encode($result), 200)
                            ->header('Content-Type', 'application/json');
        }
		else{
		    
		    $ipiid = $input['ipiid'];
			
			$physical_inventory_detail_info = $this->model_administration_physical_inventory_detail->getPhysicalInventoryByIpiid($ipiid);
			
            
            $data['phy_inventory_items'] = $this->model_administration_physical_inventory_detail->getPhyInventoryItemByIpiid($ipiid);
            
            $data['ipiid'] = $ipiid; 
            
            if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
    		} else {
    			$data['error_warning'] = '';
    		}
    
    		if (isset($this->error['vrefnumber'])) {
    			$data['error_vrefnumber'] = $this->error['vrefnumber'];
    		} else {
    			$data['error_vrefnumber'] = '';
    		}
    
    		if (isset($this->error['vordertitle'])) {
    			$data['error_vordertitle'] = $this->error['vordertitle'];
    		} else {
    			$data['error_vordertitle'] = '';
    		}
    
    		if (isset($this->error['createdate'])) {
    			$data['error_dcreatedate'] = $this->error['createdate'];
    		} else {
    			$data['error_dcreatedate'] = '';
    		}
    
    		if (isset($this->error['calculatedate'])) {
    			$data['error_dcalculatedate'] = $this->error['dcalculatedate'];
    		} else {
    			$data['error_dcalculatedate'] = '';
    		}

            
            $data['breadcrumbs'] = array();
    
    		$data['breadcrumbs'][] = array(
    			'text' => $this->language->get('text_home'),
    			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
    		);
    
    		$data['breadcrumbs'][] = array(
    			'text' => 'Items',
    			'href' => $this->url->link('administration/new_physical_inventory_detail', 'token=' . $this->session->data['token'] . $url, true)
    		);
    
    		$data['breadcrumbs'][] = array(
    			'text' => $this->language->get('heading_title'),
    			'href' => $this->url->link('administration/new_physical_inventory_detail', 'token=' . $this->session->data['token'] . $url, true)
    		);
    		
    		$data['url_commit'] = $this->url->link('administration/new_physical_inventory_detail/commit', 'token=' . $this->session->data['token'], true);
    		
    
    		$data['token'] = $this->session->data['token'];	
    
    		if (isset($input['vrefnumber'])) {
    			$data['vrefnumber'] = $input['vrefnumber'];
    		} elseif (!empty($physical_inventory_detail_info)) {
    			$data['vrefnumber'] = $physical_inventory_detail_info['vrefnumber'];
    		} 
    
    		if (isset($input['vordertitle'])) {
    			$data['vordertitle'] = $input['vordertitle'];
    		} elseif (!empty($physical_inventory_detail_info)) {
    			$data['vordertitle'] = $physical_inventory_detail_info['vordertitle'];
    		} else {
    			$data['vordertitle'] = '';
    		}
    
    		if (isset($input['createdate'])) {
    			$data['dcreatedate'] = $input['createdate'];
    		} elseif (count($physical_inventory_detail_info) > 0) {
    			$data['dcreatedate'] = $physical_inventory_detail_info['dcreatedate'];
    		} else {
    			$data['dcreatedate'] = '';
    		}
    
    		if (isset($input['calculatedate'])) {
    			$data['dcalculatedate'] = $input['calculatedate'];
    		} elseif (!empty($physical_inventory_detail_info)) {
    			$data['dcalculatedate'] = $physical_inventory_detail_info['dcalculatedate'];
    		} else {
    			$data['dcalculatedate'] = '';
    		}
    
    		if (isset($input['status'])) {
    			$data['estatus'] = $input['status'];
    		} elseif (!empty($physical_inventory_detail_info)) {
    			$data['estatus'] = $physical_inventory_detail_info['estatus'];
    		}

    		
    		$data['header'] = $this->load->controller('common/header');
    		$data['column_left'] = $this->load->controller('common/column_left');
    		$data['footer'] = $this->load->controller('common/footer');
    		
            $this->response->setOutput($this->load->view('administration/new_physical_inventory_calculate', $data));
		}
		
    }
    
    public function edit_calculate(Request $request)
    {
        $input = $request->all();
        
        if ($request->isMethod('post')) {
            
			$items = array();
			$ndebitextprice = 0;
			$totalndebitextprice = 0;
			$results = array();
            
			if(isset($input['ipiid']) && $input['ipiid'] != ''){
			    
			    $ipiid = $input['ipiid'];
                if(isset($input['action']) && $input['action'] == 'import'){
                    
                    $items_query = "SELECT mpis.iitemid, mpis.vbarcode, mpis.iqtyonhand, ipic.inventorycount as ndebitqty FROM mst_physical_inventory_snapshot mpis LEFT JOIN import_physical_inventory_csv_table as ipic ON(mpis.vbarcode = ipic.vbarcode) WHERE ipiid = '".$ipiid."' ";
                    
                    $results = DB::connection('mysql_dynamic')->select($items_query);
                    
                    $results = array_map(function ($value) {
                        return (array)$value;
                    }, $results);
                    
                }else{
                    
                    // $items = [];
                    $selected_vbarcode_arr = session()->get('selected_vbarcode_arr');
                    if(isset($selected_vbarcode_arr) && !empty($selected_vbarcode_arr)){
                        foreach($selected_vbarcode_arr as $key => $vbarcode){
                            
                            $snapshot_item_query = "SELECT iqtyonhand FROM mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' AND iitemid = '".$vbarcode[0]['iitemid']."' ";
                            
                            $snapshot_item = DB::connection('mysql_dynamic')->select($snapshot_item_query);
                            $snapshot_item = isset($snapshot_item[0])?(array)$snapshot_item[0]:[];
                            
                            $results[] =  array(
                                            'iitemid'   =>  $vbarcode[0]['iitemid'],
                                            'vbarcode'  =>  $vbarcode[0]['vbarcode'],
                                            'ndebitqty' =>  $vbarcode[0]['invtcount'],
                                            'iqtyonhand'=>  $snapshot_item['iqtyonhand']
                                        );
                        }
                    }
                }
                
                //==========for bulk update=================
                $sql_ndebitqty = '';
                $sql_ndebitextprice = '';
                $sql_ndiffqty ='';
                $sql_itotalunit ='';
                $sql_beforeQOH = '';
                $sql_afterQOH = '';
                
                if(isset($results) && $results != null){
                    foreach ($results as $k => $item) {
                        
                        $itemdetail = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid = '".$item['iitemid']."' ");
                        $itemdetail = isset($itemdetail[0])?(array)$itemdetail[0]:[];
                        
                        $itotalunit = $item['ndebitqty'];
                        $ndebitextprice = $itotalunit * $itemdetail['nunitcost'];
                        
                        //========for sold item========
                        $from = $input['dcreatedate'];
                        $to = date('Y-m-d H:i:s');
                        $soldqty_sql = "SELECT ifnull(SUM(tsd.ndebitqty), 0) as soldqty FROM trn_salesdetail as tsd LEFT JOIN trn_sales as ts ON(ts.isalesid = tsd.isalesid) WHERE ts.vtrntype='Transaction' AND tsd.vitemcode = '".$item['vbarcode']."' AND ts.dtrandate >= '".$from."' AND ts.dtrandate <= '".$to."' GROUP BY vitemcode ";
                        
                        $soldqty = DB::connection('mysql_dynamic')->select($soldqty_sql);
                        $soldqty = isset($soldqty[0])?(array)$soldqty[0]:[];
                        
                        $ndiffqty = $item['iqtyonhand'] - ($soldqty['soldqty'] ?? 0) - $item['ndebitqty'];
                        
                        $sql_ndebitqty .= "WHEN ".$item['iitemid']." THEN ".$item['ndebitqty']." ";
                        $sql_ndebitextprice .= "WHEN ".$item['iitemid']." THEN ".(float)$ndebitextprice." ";
                        $sql_ndiffqty .= "WHEN ".$item['iitemid']." THEN ".$ndiffqty." ";
                        $sql_itotalunit .= "WHEN ".$item['iitemid']." THEN ".$itotalunit." ";
                        $sql_beforeQOH .= "WHEN ".$item['iitemid']." THEN ".$itemdetail['iqtyonhand']." ";
                        $sql_afterQOH  .= "WHEN ".$item['iitemid']." THEN ".$item['ndebitqty']." ";
                        $arr_vitemid[] = $item['iitemid'];
                        
                        // $sql .= "UPDATE trn_physicalinventorydetail SET ndebitqty = '" . ($item['ndebitqty']) . "', ndebitextprice = '" . ((float)$ndebitextprice) . "', ndiffqty = '" . ($ndiffqty) . "', itotalunit = '" . ($itotalunit) . "' WHERE ipiid = '".$ipiid."' AND vitemid = '".$item['iitemid']."' ;";
                          
                        // $sql[] = "('".$input['ipiid']."', '".($item['iitemid'])."' '" . ($item['ndebitqty']) . "', '" . ((float)$ndebitextprice) . "', '" . ($ndiffqty) . "', '" . ($itotalunit) . "')";
                    
                    	$totalndebitextprice = $totalndebitextprice + $ndebitextprice;
                    
                    } 
                }
			}
			
			if(isset($results) && $results != null){
			//==========Query for bulk update=================
            $arr_vitemid = join("','",$arr_vitemid);
            $sql = "UPDATE trn_physicalinventorydetail SET ndebitqty = (CASE vitemid ".$sql_ndebitqty." END), ndebitextprice = (CASE vitemid ".$sql_ndebitextprice." END), ndiffqty = (CASE vitemid ".$sql_ndiffqty." END), itotalunit = (CASE vitemid ".$sql_itotalunit." END), beforeQOH =  (CASE vitemid ".$sql_beforeQOH." END), afterQOH = (CASE vitemid ".$sql_afterQOH." END) WHERE vitemid IN('".$arr_vitemid."') AND ipiid = ".$ipiid." ";
			}else{
			    $sql='';
			}
            unset($results);
            
			$temp_arr[0] = array(
			                    'ipiid' => $input['ipiid'],
								// 'nnettotal' => $ndebitextprice,
								'nnettotal' => $totalndebitextprice,
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'] ?? '',
								'ilocid' => $input['ilocid'] ?? '',
								'dcalculatedate' => date('Y-m-d H:i:s'),
								'dclosedate' => '',
								'items' => $sql,
									
							);
            
            $result = PhysicalInventory::first()->editNewPhysicalInventory($temp_arr);
            unset($temp_arr);
            
            if(isset($input['action']) && $input['action'] == 'edit_calculate_commit'){
                
                if(isset($result['success']) && isset($result['ipiid'])){
                   
                        $query_items = "SELECT vitemid, ndebitqty, itotalunit FROM trn_physicalinventorydetail WHERE ipiid = '".$result['ipiid']."' ";
                        $items = DB::connection('mysql_dynamic')->select($query_items)->rows;
                        
                        $temp_array = array(
                                            'ipiid' => $result['ipiid'],
                                            'items' => $items,
                                            'dclosedate' => date('Y-m-d H:i:s')
                                           );
                                           
                        $result1 = PhysicalInventory::first()->commit($temp_array);
                        
                        return response(json_encode($result1), 200)
                            ->header('Content-Type', 'application/json');
                            
                    }
                }else{
			
                return response(json_encode($result), 200)
                            ->header('Content-Type', 'application/json');
            }
        }else{
            
            $action = 'edit_calculate';
            $data = $this->get_details_of_selected_data($input['ipiid'], $action);
            
            return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
        }
        
        
    }
    
    public function selected_edit_calculate(Request $request)
    {
        $input = $request->all();
        
        if ($request->isMethod('post')) {
            
			$items = array();
			$ndebitextprice = 0;
			$totalndebitextprice = 0;
            
			if(isset($input['ipiid']) && $input['ipiid'] != ''){
                
                $ipiid = $input['ipiid'];
                if(isset($input['action']) && $input['action'] == 'import'){
                    
                    $items_query = "SELECT mpis.iitemid, mpis.vbarcode, mpis.iqtyonhand, ipic.inventorycount as ndebitqty FROM mst_physical_inventory_snapshot mpis Right JOIN import_physical_inventory_csv_table as ipic ON(mpis.vbarcode = ipic.vbarcode) WHERE ipiid = '".$ipiid."' ";
                    
                    $results = DB::connection('mysql_dynamic')->select($items_query);
                    
                    $results = array_map(function ($value) {
                        return (array)$value;
                    }, $results);
                    
                }else{
                    
                    // $items = [];
                    $selected_vbarcode_arr = session()->get('selected_vbarcode_arr');
                    if(isset($selected_vbarcode_arr) && !empty($selected_vbarcode_arr)){
                        foreach($selected_vbarcode_arr as $key => $vbarcode){
                            
                            $snapshot_item_query = "SELECT iqtyonhand FROM mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' AND iitemid = '".$vbarcode[0]['iitemid']."' ";
                            
                            $snapshot_item = DB::connection('mysql_dynamic')->select($snapshot_item_query);
                            $snapshot_item = isset($snapshot_item[0])?(array)$snapshot_item[0]:[];
                            
                            $results[] =  array(
                                            'iitemid'   =>  $vbarcode[0]['iitemid'],
                                            'vbarcode'  =>  $vbarcode[0]['vbarcode'],
                                            'ndebitqty' =>  $vbarcode[0]['invtcount'],
                                            'iqtyonhand'=>  $snapshot_item['iqtyonhand']
                                        );
                        }
                    }
                }
                // dd($results);
                //==========for bulk update=================
                $sql_ndebitqty = '';
                $sql_ndebitextprice = '';
                $sql_ndiffqty ='';
                $sql_itotalunit ='';
                $sql_beforeQOH = '';
                $sql_afterQOH = '';
                if(isset($results) && $results != null){
                    foreach ($results as $k => $item) {
                        
                        $itemdetail = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid = '".$item['iitemid']."' ");
                        $itemdetail = isset($itemdetail[0])?(array)$itemdetail[0]:[];
                        
                        $itotalunit = $item['ndebitqty'];
                        $ndebitextprice = $itotalunit * $itemdetail['nunitcost'];
                        
                        //========for sold item========
                        $from = $input['dcreatedate'];
                        $to = date('Y-m-d H:i:s');
                        $soldqty_sql = "SELECT ifnull(SUM(tsd.ndebitqty), 0) as soldqty FROM trn_salesdetail as tsd LEFT JOIN trn_sales as ts ON(ts.isalesid = tsd.isalesid) WHERE ts.vtrntype='Transaction' AND tsd.vitemcode = '".$item['vbarcode']."' AND ts.dtrandate >= '".$from."' AND ts.dtrandate <= '".$to."' GROUP BY vitemcode ";
                        
                        $soldqty = DB::connection('mysql_dynamic')->select($soldqty_sql);
                        $soldqty = isset($soldqty[0])?(array)$soldqty[0]:[];
                        
                        $ndiffqty = $item['iqtyonhand'] - ($soldqty['soldqty'] ?? 0) - $item['ndebitqty'];
                        
                        $sql_ndebitqty .= "WHEN ".$item['iitemid']." THEN ".$item['ndebitqty']." ";
                        $sql_ndebitextprice .= "WHEN ".$item['iitemid']." THEN ".(float)$ndebitextprice." ";
                        $sql_ndiffqty .= "WHEN ".$item['iitemid']." THEN ".$ndiffqty." ";
                        $sql_itotalunit .= "WHEN ".$item['iitemid']." THEN ".$itotalunit." ";
                        $sql_beforeQOH .= "WHEN ".$item['iitemid']." THEN ".$itemdetail['iqtyonhand']." ";
                        $sql_afterQOH  .= "WHEN ".$item['iitemid']." THEN ".$item['ndebitqty']." ";
                        
                        $arr_vitemid[] = $item['iitemid'];
                        
                    	$totalndebitextprice = $totalndebitextprice + $ndebitextprice;
                    
                    } 
                }
			}
			
			if(isset($results) && $results != null){
			//==========Query for bulk update=================
                $arr_vitemid = join("','",$arr_vitemid);
                $sql = "UPDATE trn_physicalinventorydetail SET ndebitqty = (CASE vitemid ".$sql_ndebitqty." END), ndebitextprice = (CASE vitemid ".$sql_ndebitextprice." END), ndiffqty = (CASE vitemid ".$sql_ndiffqty." END), itotalunit = (CASE vitemid ".$sql_itotalunit." END), beforeQOH =  (CASE vitemid ".$sql_beforeQOH." END), afterQOH = (CASE vitemid ".$sql_afterQOH." END) WHERE vitemid IN('".$arr_vitemid."') AND ipiid = ".$ipiid." ";
			}else{
			    $sql='';
			}
            unset($results);
            
			$temp_arr[0] = array(
			                    'ipiid' => $input['ipiid'],
								// 'nnettotal' => $ndebitextprice,
								'nnettotal' => $totalndebitextprice,
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'] ?? '',
								'ilocid' => $input['ilocid'] ?? '',
								'dcalculatedate' => date('Y-m-d H:i:s'),
								'dclosedate' => '',
								'items' => $sql,
									
							);
            
            $result = PhysicalInventory::first()->editNewPhysicalInventory($temp_arr);
            unset($temp_arr);
            
            return response(json_encode($result), 200)
                            ->header('Content-Type', 'application/json');
        }else{
            
            $action = 'selected_edit_calculate';
            $data = $this->get_details_of_selected_data($input['ipiid'], $action);
            
            return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
        }
    }
    
    public function import_inventory_count(Request $request)
    {
        ini_set('max_execution_time', 0); // for infinite time of execution
        set_time_limit(0);
        
        //======queue declare================
        
        // $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $connection = $this->Amqpconnection->AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel(); 
        $channel->queue_declare('hello', false, false, false, false);
                    
        $input = $request->all();
        
        if ($request->isMethod('post')) {
			if(isset($input['import_physical_inventory_file']) && $request->hasFile('import_physical_inventory_file')){
		
                // $import_physical_inventory_file = $input['import_physical_inventory_file']['tmp_name'];
                $import_physical_inventory_file = $request->file('import_physical_inventory_file')->getPathName();
                // dd($import_physical_inventory_file);
                $ipiid = $input['ipiid'];
                   
                $handle = fopen($import_physical_inventory_file, "r");
                $msg_exist = '';
                $line_row_index=1;
                
                
                if ($handle) {
                    
                    $sid = (int)(session()->get('sid'));
                    $filename = "$sid.csv";
                    $filepath = storage_path("logs/physicalInventory/".$filename);
                    
                    $csvPhy = fopen($filepath, 'w');
                    
                    //========Queue============
					$msg = new AMQPMessage('good morning');
                    $channel->basic_publish($msg, '', 'hello');
                
                    while (($strline = fgets($handle)) !== false) {
                        
                        $values = explode("," ,$strline);
                        
                        // print_r(intval($values[3]));
                        
                        if($line_row_index > 1){
                            
                            $itemdetail_query = "SELECT iitemid, vitemcode, (dcostprice/npack) as unitcost, dunitprice FROM mst_item WHERE vbarcode  = '".strval($values[0])."' ";
                            
                            $itemdetail = DB::connection('mysql_dynamic')->select($itemdetail_query);
                            $itemdetail = isset($itemdetail[0])?(array)$itemdetail[0]:[];
                            
                            $inv_count = intval($values[3]);
                            $barcode = trim($values[0], '\r');
                            $itemname = trim($values[1], '\r');
                            $qtyonhand = trim($values[2], '\r');
                            $item = $itemdetail['iitemid'].",".$itemdetail['vitemcode'].",".$barcode.",".$itemname.",".$qtyonhand.",".$itemdetail['dunitprice'].",".$itemdetail['unitcost'].",".$inv_count.PHP_EOL;
                            
                            fwrite($csvPhy, $item);
                            
                            $check_snapshot_item = DB::connection('mysql_dynamic')->select("SELECT iitemid FROM mst_physical_inventory_snapshot WHERE vbarcode = '".$barcode."' AND ipiid = '".$ipiid."'  ");
                            $check_snapshot_item = isset($check_snapshot_item[0])?(array)$check_snapshot_item[0]:[];
                            
                            if(!isset($check_snapshot_item['iitemid']) && count($check_snapshot_item) <= 0){
                            
                                $count++;
                            }
                        }
                        $line_row_index++;
                    }
                    fclose($csvPhy);
                    
                    
                    $physical_inventory_detail_info = PhysicalInventory::first()->getPhysicalInventoryByIpiid($ipiid);
                    
                    if($physical_inventory_detail_info['estatus'] == 'Open'){
                        
                        $data['url_calculate'] = url('inventory/physicalInventroy/calculate?ipiid='.$ipiid);
                        $data['url_calculate_commit'] = url('inventory/physicalInventroy/calculate_commit?ipiid='.$ipiid);
                        $data['url_view_calculate'] = url('inventory/physicalInventroy/show?ipiid='.$ipiid);      
                        $data['import_inventory_count'] = url('inventory/physicalInventroy/import_inventory_count?ipiid='.$ipiid);      
                        $data['display_selected_data'] = url('inventory/physicalInventroy/display_selected_data?ipiid='.$ipiid);      
                        $data['export_csv'] = url('inventory/physicalInventroy/export_csv?ipiid='.$ipiid);      
                        $data['import_display'] = url('inventory/physicalInventroy/import_display?ipiid='.$ipiid);      
                    
                    }elseif($physical_inventory_detail_info['estatus'] == 'Calculated'){
                        
                        $data['edit_calculate'] = $action;
                        $data['url_calculate'] = url('inventory/physicalInventroy/edit_calculate');
                        $data['url_calculate_commit'] = url('inventory/physicalInventroy/edit_calculate?ipiid='.$ipiid . '&action=edit_calculate_commit');
                        $data['url_view_calculate'] = url('inventory/physicalInventroy/show?ipiid='.$ipiid);      
                        $data['display_selected_data'] = url('inventory/physicalInventroy/display_selected_data?ipiid='.$ipiid);      
                        $data['export_csv'] = url('inventory/physicalInventroy/export_csv?ipiid='.$ipiid);      
                        $data['import_display'] = url('inventory/physicalInventroy/import_display?ipiid='.$ipiid);      
                    
                    }
                            
                    // $data['items'] = $items;
                    $data['vrefnumber'] = $physical_inventory_detail_info['vrefnumber'];
                    $data['vordertitle'] = $physical_inventory_detail_info['vordertitle'];
                    $data['dcreatedate'] = $physical_inventory_detail_info['dcreatedate'];
                    $data['estatus'] = $physical_inventory_detail_info['estatus'];
                    $data['ipiid'] = $ipiid;
                    $data['action'] = 'import';
                    
                    unset($items);
                    
                    if($count > 0){
                        $data['code'] = 0;
                        $data['success'] = 'Physical Inventory Imported Successfully and '.$count.' Items not matched with Physical Inventory Items and Import Items!';
                    }else{
                        $data['code'] = 1;
                    	$data['success'] = 'Physical Inventory Imported Successfully';
                    }
                            
                            // $count_iitemid = DB::connection('mysql_dynamic')->select("SELECT COUNT(iitemid) as count_iitemid FROM mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' ")->row;
                            
                            // if($line_row_index != $count_iitemid['count_iitemid']){
                            //     $difference = $count_iitemid['count_iitemid'] - $line_row_index;
                            //     $data['code'] = 0;
                            //     $data['error_warning'] = 'Difference between Export Items and Import Items is '.$difference ;
                            // }
                }
                else{
                    $return['code'] = 0;
                    $return['error'] = "file not found!";
                }
			}
			else{
				$data['code'] = 0;
				$data['error'] = 'Please select file';

			}
			  
			$data['ipiid'] = $ipiid;
        
            return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
		}else{

			$data['error'] = 'Something went wrong';
		    $data['action'] = 'error_import';
		    
            $data = $this->get_details_of_selected_data($input['ipiid'], $action, $error);
            return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
            //=====Queue close======
            $channel->close();
            $connection->close();
		}
        
    }
    
    public function display_selected_data(Request $request)
    {
        $input = $request->all();
        
        $ipiid = $input['ipiid'];
        
        $datas= array();
        
        $check = DB::connection('mysql_dynamic')->select("select count(ipiid) as count from trn_physicalinventorydetail where ipiid = '".$ipiid."' ");
        $check = isset($check[0])?(array)$check[0]:[];
        
        $input = $input;
        
        $limit = 20;
        
        $start_from = ($input['start']);
            
        $offset = $input['start']+$input['length'];
        
        if($check['count'] >= 1){
            $ipiid = $input['ipiid'];
            
            $check_status = DB::connection('mysql_dynamic')->select("SELECT ipiid, estatus FROM trn_physicalinventory WHERE ipiid = '".$ipiid."' ");
            $check_status = isset($check_status[0])?(array)$check_status[0]:[];
            
            if($check_status['estatus'] == 'Open'){
                
                $select_query = "SELECT mpis.iitemid, mpis.npack, mpis.vbarcode, mpis.iqtyonhand, mi.vitemname, 
                mi.vitemcode, mi.dunitprice, (mi.dcostprice/mi.npack) as unitcost, tpd.ndebitqty FROM 
                mst_physical_inventory_snapshot as mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) LEFT JOIN trn_physicalinventorydetail as tpd ON(mpis.iitemid = tpd.vitemid AND mpis.ipiid = tpd.ipiid)
                WHERE mpis.ipiid = '".$ipiid."' ORDER BY mpis.vbarcode LIMIT ". $input['start'].", ".$limit;
                $itemdata = DB::connection('mysql_dynamic')->select($select_query);
                
                $itemdata = array_map(function ($value) {
                    return (array)$value;
                }, $itemdata);
            
                $count_select_query = "SELECT COUNT(mpis.iitemid) as count FROM mst_physical_inventory_snapshot as mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' ORDER BY mpis.iitemid ";
                $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
        
                $count_records = $count_total = (int)$count_query[0]->count;
                
                if(count($itemdata) > 0){
                    foreach ($itemdata as $key => $value) {
                        if(isset($value['ndebitqty'])){
                            $ndebitqty = $value['ndebitqty'];
                        }else{
                            $ndebitqty = 0;
                        }
                        $temp = array();
                        $temp['iitemid'] = $value['iitemid'];
                        $temp['vbarcode'] = $value['vbarcode'];
                        $temp['vitemname'] = $value['vitemname'];
                        $temp['dunitprice'] = $value['dunitprice'];
                        $temp['unitcost'] = number_format($value['unitcost'], 2);
                        $temp['iqtyonhand'] = $value['iqtyonhand'];
                        $temp['ndebitqty'] = "<input type='number' class='text-center' name='ndebitqty' id='ndebitqty_".$value['iitemid']."' data-vbarcode =".$value['vbarcode']." oninput='getinventorycount($(this).val(), ".$value['iitemid'].")' value=".(int)$ndebitqty.">";
                        $temp['costtotal'] = number_format(($value['iqtyonhand'] * $value['unitcost']), 2);
                        $temp['pricetotal'] = number_format(($value['iqtyonhand'] * $value['dunitprice']), 2);
                        $temp['inv_count'] = $ndebitqty;
                        
                        $datas[] = $temp;
                    }
                }
            }else{
                
                $select_query = "SELECT vbarcode, vitemname, vitemid as iitemid, ndebitqty  FROM trn_physicalinventorydetail 
                                where ipiid = '".$ipiid."' ORDER BY vbarcode LIMIT ". $input['start'].", ".$limit;
                
                $itemdata = DB::connection('mysql_dynamic')->select($select_query);
                
                $itemdata = array_map(function ($value) {
                    return (array)$value;
                }, $itemdata);
                
                $count_select_query = "SELECT COUNT(tpid.vitemid) as count FROM trn_physicalinventorydetail as tpid where tpid.ipiid = '".$ipiid."'";
                
                
                $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
        
                $count_records = $count_total = (int)$count_query[0]->count;
                
                if(count($itemdata) > 0){
                    foreach ($itemdata as $key => $value) {
                        
                        $itemdata_query = "SELECT mpis.npack, mpis.iqtyonhand, mi.vitemcode, mi.dunitprice, (mi.dcostprice/mi.npack) as unitcost FROM mst_physical_inventory_snapshot AS mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' AND mpis.iitemid = '".$value['iitemid']."' ";
                        $itemdata1 = DB::connection('mysql_dynamic')->select($itemdata_query);
                        $itemdata1 = isset($itemdata1[0])?(array)$itemdata1[0]:[];
                        
                        if(isset($value['ndebitqty'])){
                            $ndebitqty = $value['ndebitqty'];
                        }else{
                            $ndebitqty = 0;
                        }
                        $temp = array();
                        $temp['iitemid'] = $value['iitemid'];
                        $temp['vbarcode'] = $value['vbarcode'];
                        $temp['vitemname'] = $value['vitemname'];
                        $temp['dunitprice'] = $itemdata1['dunitprice'];
                        $temp['unitcost'] = number_format($itemdata1['unitcost'], 2);
                        $temp['iqtyonhand'] = $itemdata1['iqtyonhand'];
                        $temp['ndebitqty'] = "<input type='number' class='text-center' name='ndebitqty' id='ndebitqty_".$value['iitemid']."' data-vbarcode =".$value['vbarcode']." oninput='getinventorycount($(this).val(), ".$value['iitemid'].")' value=".(int)$ndebitqty.">";
                        $temp['costtotal'] = number_format(($itemdata1['iqtyonhand'] * $itemdata1['unitcost']), 2);
                        $temp['pricetotal'] = number_format(($itemdata1['iqtyonhand'] * $itemdata1['dunitprice']), 2);
                        $temp['inv_count'] = $ndebitqty;
                        
                        $datas[] = $temp;
                    }
                }
            }
            
            
        }else{
            
            $ipiid = $input['ipiid'];
            
            
            $select_query = "SELECT mpis.iitemid, mpis.npack, mpis.vbarcode, mpis.iqtyonhand, mi.vitemname, mi.vitemcode, mi.dunitprice, (mi.dcostprice/mi.npack) as unitcost FROM mst_physical_inventory_snapshot as mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' ORDER BY mpis.vbarcode LIMIT ". $input['start'].", ".$limit;
            $itemdata = DB::connection('mysql_dynamic')->select($select_query);
            
            $itemdata = array_map(function ($value) {
                return (array)$value;
            }, $itemdata);
            
            $count_select_query = "SELECT COUNT(mpis.iitemid) as count FROM mst_physical_inventory_snapshot as mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' ORDER BY mpis.iitemid ";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
    
            $count_records = $count_total = (int)$count_query[0]->count;
            
            if(count($itemdata) > 0){
                foreach ($itemdata as $key => $value) {
                    if(isset($value['ndebitqty'])){
                        $ndebitqty = $value['ndebitqty'];
                    }else{
                        $ndebitqty = 0;
                    }
                    $temp = array();
                    $temp['iitemid'] = $value['iitemid'];
                    $temp['vbarcode'] = $value['vbarcode'];
                    $temp['vitemname'] = $value['vitemname'];
                    $temp['dunitprice'] = $value['dunitprice'];
                    $temp['unitcost'] = number_format($value['unitcost'], 2);
                    $temp['iqtyonhand'] = $value['iqtyonhand'];
                    $temp['ndebitqty'] = "<input type='number' class='text-center' name='ndebitqty' id='ndebitqty_".$value['iitemid']."' data-vbarcode =".$value['vbarcode']." oninput='getinventorycount($(this).val(), ".$value['iitemid'].")' value=".(int)$ndebitqty.">";
                    $temp['costtotal'] = number_format(($value['iqtyonhand'] * $value['unitcost']), 2);
                    $temp['pricetotal'] = number_format(($value['iqtyonhand'] * $value['dunitprice']), 2);
                    $temp['inv_count'] = $ndebitqty;
                    
                    $datas[] = $temp;
                }
            }
        }
        
        
        // dd($datas);
        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $datas;
        return response(json_encode($return))
                  ->header('Content-Type', 'application/json');
    
    }
    
    public function display_selected_edit_calculate_data(Request $request)
    {
        $input = $request->all();
        $ipiid = $input['ipiid'];
        
        $check = DB::connection('mysql_dynamic')->select("select count(ipiid) as count from trn_physicalinventorydetail where ipiid = '".$ipiid."' ");
        $check = isset($check[0])?(array)$check[0]:[];
        
        $input = $input;
        
        $limit = 20;
        $datas = array();

        $start_from = ($input['start']);
            
        $offset = $input['start']+$input['length'];
        
        if($check['count'] >= 1){
            $ipiid = $input['ipiid'];
            
            
            $select_query = "SELECT vbarcode, vitemname, vitemid as iitemid, ndebitqty  FROM trn_physicalinventorydetail 
                            where ipiid = '".$ipiid."' AND ndiffqty != '0' ORDER BY vbarcode LIMIT ". $input['start'].", ".$limit;
            
            $itemdata = DB::connection('mysql_dynamic')->select($select_query);
            
            $itemdata = array_map(function ($value) {
                return (array)$value;
            }, $itemdata);
            
            $count_select_query = "SELECT COUNT(tpid.vitemid) as count FROM trn_physicalinventorydetail as tpid where tpid.ipiid = '".$ipiid."' AND ndiffqty != '0'";
            
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            
            $count_records = $count_total = (int)$count_query[0]->count;
            
            if(count($itemdata) > 0){
                foreach ($itemdata as $key => $value) {
                    
                    $itemdata_query = "SELECT mpis.npack, mpis.iqtyonhand, mi.vitemcode, mi.dunitprice, (mi.dcostprice/mi.npack) as unitcost FROM mst_physical_inventory_snapshot AS mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' AND mpis.iitemid = '".$value['iitemid']."' ";
                    $itemdata1 = DB::connection('mysql_dynamic')->select($itemdata_query);
                    $itemdata1 = isset($itemdata1[0])?(array)$itemdata1[0]:[];
                    
                    if(isset($value['ndebitqty'])){
                        $ndebitqty = $value['ndebitqty'];
                    }else{
                        $ndebitqty = 0;
                    }
                    $temp = array();
                    $temp['iitemid'] = $value['iitemid'];
                    $temp['vbarcode'] = $value['vbarcode'];
                    $temp['vitemname'] = $value['vitemname'];
                    $temp['dunitprice'] = $itemdata1['dunitprice'];
                    $temp['unitcost'] = number_format($itemdata1['unitcost'], 2);
                    $temp['iqtyonhand'] = $itemdata1['iqtyonhand'];
                    $temp['ndebitqty'] = "<input type='number' class='text-center' name='ndebitqty' id='ndebitqty_".$value['iitemid']."' data-vbarcode =".$value['vbarcode']." oninput='getinventorycount($(this).val(), ".$value['iitemid'].")' value=".(int)$ndebitqty.">";
                    $temp['costtotal'] = number_format(($itemdata1['iqtyonhand'] * $itemdata1['unitcost']), 2);
                    $temp['pricetotal'] = number_format(($itemdata1['iqtyonhand'] * $itemdata1['dunitprice']), 2);
                    
                    $datas[] = $temp;
                }
            }
            $return = [];
            $return['draw'] = (int)$input['draw'];
            $return['recordsTotal'] = $count_total;
            $return['recordsFiltered'] = $count_records;
            $return['data'] = $datas;
            return response(json_encode($return))
                  ->header('Content-Type', 'application/json');
        }
    }
    
    public function export_csv(Request $request)
    {
        ini_set('max_execution_time', '0'); // for infinite time of execution
        set_time_limit(0);
        
        $input = $request->all();
        
        $ipiid = $input['ipiid'];
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        $output = fopen("php://output", "w");
        fputcsv($output, array('SKU', 'Item Name', 'Qty on hand', 'Invt. Count'));
        
        if(isset($input['action']) && $input['action'] == 'edit_calculate'){
            
            $query = "SELECT vbarcode, iitemid, iqtyonhand FROM mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' ORDER BY vbarcode";
            $result = DB::connection('mysql_dynamic')->select($query);
            
            $result = array_map(function ($value) {
                return (array)$value;
            }, $result);
            
            foreach($result as $row){
                $detail = DB::connection('mysql_dynamic')->select("SELECT vitemname, ndebitqty FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' AND vitemid = '".$row['iitemid']."' ");
                $detail = isset($detail[0])?(array)$detail[0]:[];
                
                $row_data = array($row['vbarcode'], $detail['vitemname'], $row['iqtyonhand'], $detail['ndebitqty']);
                $row_data = implode(",", $row_data);
                $row_data = $row_data.PHP_EOL;
                // fputcsv($output, $row);
                fwrite($output, $row_data);
            }
        }elseif(isset($input['action']) && $input['action'] == 'selected_edit_calculate'){
            
            // $query = "SELECT vitemid, vitemname, ndebitqty FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' AND ndiffqty != '0' ORDER BY vitemid";
            $query = "SELECT mpis.vbarcode, tpd.vitemname, mpis.iqtyonhand, tpd.ndebitqty FROM mst_physical_inventory_snapshot mpis LEFT JOIN trn_physicalinventorydetail tpd ON(mpis.iitemid = tpd.vitemid AND mpis.ipiid = tpd.ipiid)  WHERE tpd.ipiid = '".$ipiid."' AND tpd.ndiffqty != '0' ORDER BY tpd.vitemid";
            
            $result = DB::connection('mysql_dynamic')->select($query);
            
            $result = array_map(function ($value) {
                return (array)$value;
            }, $result);
            
            foreach($result as $row){
                
                $row_data = implode(",", $row);
                $row_data = $row_data.PHP_EOL;
                // fputcsv($output, $row);
                fwrite($output, $row_data);
                
                // $detail = DB::connection('mysql_dynamic')->select("SELECT iqtyonhand, vbarcode FROM mst_physical_inventory_snapshot WHERE ipiid = '".$ipiid."' AND iitemid= '".$row['vitemid']."' ");
                // $detail = isset($detail[0])?(array)$detail[0]:[];
                
                // $row_data = array($detail['vbarcode'], $row['vitemname'], $detail['iqtyonhand'], $row['ndebitqty']);
                // $row_data = implode(",", $row_data);
                // $row_data = $row_data.PHP_EOL;
                // // fputcsv($output, $row);
                // fwrite($output, $row_data);
            }
            
        }else{
            $query = "SELECT mpis.vbarcode, mi.vitemname, mpis.iqtyonhand FROM mst_physical_inventory_snapshot as mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' ORDER BY mpis.vbarcode";
            $result = DB::connection('mysql_dynamic')->select($query);
            
            $result = array_map(function ($value) {
                return (array)$value;
            }, $result);
            
            foreach($result as $row){
                array_push($row,"0");
                $row_data = implode(",", $row);
                $row_data = $row_data.PHP_EOL;
                // fputcsv($output, $row);
                fwrite($output, $row_data);
            }
        }
        fclose($output);
        
    }
    
    public function bulk_import(Request $request)
    {
        $input = $request->all();
        
		ini_set('max_execution_time', '0'); // for infinite time of execution
        set_time_limit(0);
        
        $data = array();
		$json_return = array();
        
        $ipiid = $input['ipiid'];
        
		if ($request->isMethod('post')) {
			if(isset($input['import_physical_inventory_file']) && $request->hasFile('import_physical_inventory_file')){
			    
			    $filetype = array('application/vnd.ms-excel', 'text/csv', 'text/tsv'); 
			    
			    if(in_array($request->file('import_physical_inventory_file')->getClientMimeType(), $filetype)){
			        
			        $import_physical_inventory_file = $request->file('import_physical_inventory_file')->getRealPath();
                    //dd($import_physical_inventory_file);
                    // query will return false if the table does not exist.
                    $val = DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'import_physical_inventory_csv_table'");
                    
                    if(empty($val) || $val == FALSE)
                    {
                       $table_create_query = "CREATE TABLE `import_physical_inventory_csv_table` (
                                              `vbarcode` varchar(50) DEFAULT NULL,
                                              `iitemname` varchar(150) DEFAULT NULL,
                                              `iqtyonhand` varchar(50) DEFAULT NULL,
                                              `inventorycount` varchar(50) DEFAULT NULL
                                            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
                        DB::connection('mysql_dynamic')->select($table_create_query);
                    }
                    
                    DB::connection('mysql_dynamic')->delete("DELETE FROM import_physical_inventory_csv_table");
                    
                    $query =    "LOAD DATA LOCAL INFILE '".$import_physical_inventory_file."' INTO TABLE import_physical_inventory_csv_table FIELDS TERMINATED BY ',' LINES TERMINATED BY '".PHP_EOL."' ";
                    
                    $dsn  = "mysql:host=".\Session::get('dbhost').";
                    dbname=".$request->session()->get('dbname');
                    $user = session()->get('dbuser');
                    $passwd = session()->get('dbpassword');
                    
                    $conn = new \PDO($dsn, $user, $passwd, array(
                        PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                    ));
                   
                    $conn->query($query);
                     //DB::connection('mysql_dynamic')->getpdo()->exec($query);
                    
                    $physical_inventory_detail_info = PhysicalInventory::first()->getPhysicalInventoryByIpiid($ipiid);
                     //dd($physical_inventory_detail_info);
					if($physical_inventory_detail_info['estatus'] == 'Open'){
                        
                        $data['edit_import_inventory_count'] = url('inventory/physicalInventroy/edit_import_inventory_count?ipiid='. $ipiid);      
                        
                        $data['url_calculate'] = url('inventory/physicalInventroy/calculate?ipiid='.$ipiid);
                        $data['url_calculate_commit'] = url('inventory/physicalInventroy/calculate_commit?ipiid='.$ipiid);
                        $data['url_view_calculate'] = url('inventory/physicalInventroy/show?ipiid='.$ipiid);      
                        $data['import_inventory_count'] = url('inventory/physicalInventroy/import_inventory_count?ipiid='.$ipiid);      
                        $data['display_selected_data'] = url('inventory/physicalInventroy/display_selected_data?ipiid='.$ipiid);      
                        $data['export_csv'] = url('inventory/physicalInventroy/export_csv?ipiid='.$ipiid);      
                        $data['import_display'] = url('inventory/physicalInventroy/import_display?ipiid='.$ipiid);
                        
                    }elseif($physical_inventory_detail_info['estatus'] == 'Calculated'){
                        
                        $data['edit_calculate'] = $input['action'];
                        $data['url_view_calculate'] = url('inventory/physicalInventroy/show?ipiid='.$ipiid);      
                        $data['display_selected_data'] = url('inventory/physicalInventroy/display_selected_data?ipiid='.$ipiid);      
                        $data['export_csv'] = url('inventory/physicalInventroy/export_csv?ipiid='.$ipiid);      
                        $data['edit_import_inventory_count'] = url('inventory/physicalInventroy/edit_import_inventory_count?ipiid='. $ipiid);      
                    
                        if(isset($input['action']) && $input['action'] == 'edit_calculate'){
                            
                            $data['url_calculate'] = url('inventory/physicalInventroy/edit_calculate');
                            $data['url_calculate_commit'] = url('inventory/physicalInventroy/edit_calculate?ipiid='.$ipiid . '&action=edit_calculate_commit');
                            $data['import_display'] = url('inventory/physicalInventroy/import_display?ipiid='.$ipiid);
                            
                        }elseif(isset($input['action']) && $input['action'] == 'selected_edit_calculate'){
                            
                            $data['import_display'] = url('inventory/physicalInventroy/import_display_selected_edit?ipiid='.$ipiid);
                            $data['url_calculate'] = url('inventory/physicalInventroy/selected_edit_calculate');
                        }
                    }
                       
                            // $data['items'] = $items;
                            $data['vrefnumber'] = $physical_inventory_detail_info['vrefnumber'];
                            $data['vordertitle'] = $physical_inventory_detail_info['vordertitle'] ?? $input['ptitle'];
                            $data['dcreatedate'] = $physical_inventory_detail_info['dcreatedate'];
                            $data['estatus'] = $physical_inventory_detail_info['estatus'];
                            $data['ipiid'] = $ipiid;
                            $data['action'] = 'import';
                            $data['cancel'] = url('inventory/physicalInventroy');
                            $data['create_inventory_session'] = url('inventory/physicalInventroy/create_inventory_session?ipiid='.$ipiid);      
                            $data['unset_session_inventory_count'] = url('inventory/physicalInventroy/unset_session_inventory_count');
                            $data['assign_inventory_users_url'] = url('inventory/physicalInventroy/assign_inventory_users');
                            $data['bulk_import'] = url('inventory/physicalInventroy/bulk_import?ipiid='.$ipiid); 
                            
                            $users = DB::connection('mysql_dynamic')->select("SELECT iuserid, vfname, vlname, vemail FROM mst_user Where SID = '".session()->get('sid')."' ");
                            $users = array_map(function ($value) {
                                    return (array)$value;
                                }, $users);
                            
                            $data['users'] = $users;
                            
                            $check = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM mst_physical_inventory_assign_users WHERE ipiid = '".$ipiid."' ");
                            
                            if(isset($check) && count($check)){
                                
                                $assigned_users = DB::connection('mysql_dynamic')->select("SELECT user_id FROM mst_physical_inventory_assign_users WHERE ipiid = '".$ipiid."'");
                                $assigned_users = array_map(function ($value) {
                                    return (array)$value;
                                }, $assigned_users);
                                $data['assigned_users'] = $assigned_users;
                            }else{
                                $data['assigned_users'] = '';
                            }
        
                    unset($items);
            
			    }else{
                    $data['error'] = 'You select the incorrect format, Please select the csv format';
			    }
			}else{
				$data['code'] = 0;
				$data['error'] = 'Please select file';
                
			}
			  
			$data['ipiid'] = $ipiid;
            
            return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
		}else{
            
			$data['error'] = 'Something went wrong';
		    $data['action'] = 'error_import';
            
            $physical_inventory_detail_info = PhysicalInventory::first()->getPhysicalInventoryByIpiid($ipiid);
            
			if($physical_inventory_detail_info['estatus'] == 'Open'){
			    $action = 'edit_open';
			}else{
			    $action = $input['action'];
			}
            
            $data = $this->get_details_of_selected_data($input['ipiid'], $action);
            
            return view('inventory.physicalInventroy.physical_inventory_detail_list', compact('data'));
		}
    }
    
    public function create_inventory_session(Request $request)
    {
        $input = $request->all();
        $selected_vbarcode_arr = $input['vbarcode_arr'];
        
        // unset($this->session->data['selected_vbarcode_arr']);
        session()->forget('selected_vbarcode_arr');
        
        session()->put('selected_vbarcode_arr' ,$selected_vbarcode_arr);
        
        $data['selected_vbarcode_arr'] = $selected_vbarcode_arr;
        
        $data['success'] = true;
        $data['success_msg'] = 'session created';
        
        return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');
    }
    
    public function unset_session_inventory_count() 
    {
        $json =array();
        
        session()->forget('selected_vbarcode_arr');
        
        if(session()->get('selected_vbarcode_arr') == null){
            $data['success'] = true;
            $data['success_msg'] = 'Session Cleared'; 
        }
        // $this->response->addHeader('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public function assign_inventory_users(Request $request)
    {
        $input = $request->all();
        $data = array();
        
        if ($request->isMethod('post')) {
            
            $ipiid = $input['ipiid'];
            $selected_userid = $input['selected_userid'];
            
            foreach($selected_userid as $id){
                
                $sql[] = "('".$ipiid."', '".$id."', '".session()->get('sid')."', 'Active')";
            }
            
            $result = PhysicalInventory::first()->assign_user_phyinventory($sql, $ipiid, $selected_userid);
            
            if($result == true){
                
                $data['result'] = $result;
                $data['assigned_ids'] = $selected_userid;
                $data['msg'] = 'Users assigned successfully';
            }
            
            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');
        }
        
    }
    
    public function import_display(Request $request)
    {
        
        $input = $request->all();
        $ipiid = $input['ipiid'];
        
        $check = DB::connection('mysql_dynamic')->select("select count(ipiid) as count from trn_physicalinventorydetail where ipiid = '".$ipiid."' ");
        $check = isset($check[0])?(array)$check[0]:[];
        
        $input = $input;
        
        $limit = 20;

        $start_from = ($input['start']);
            
        $offset = $input['start']+$input['length'];
        
        if($check['count'] >= 1){
            $ipiid = $input['ipiid'];
            
            // $select_query = "SELECT mpis.vbarcode,mi.vitemname, mi.vitemcode, mi.dunitprice, mpis.npack, (mi.dcostprice/mi.npack) as unitcost, 
            //                 mpis.iqtyonhand, tpid.vitemid as iitemid, tpid.ndebitqty  FROM trn_physicalinventorydetail as tpid 
            //                 LEFT JOIN mst_item as mi ON(tpid.vitemid = mi.iitemid) 
            //                 LEFT JOIN mst_physical_inventory_snapshot as mpis ON(tpid.vitemid = mpis.iitemid AND tpid.ipiid = mpis.ipiid) 
            //                 where tpid.ipiid = '".$ipiid."' ORDER BY tpid.vbarcode LIMIT ". $input['start'].", ".$limit;
            
            $select_query = "SELECT vbarcode, vitemname, vitemid as iitemid, ndebitqty  FROM trn_physicalinventorydetail 
                            where ipiid = '".$ipiid."' ORDER BY vbarcode LIMIT ". $input['start'].", ".$limit;
            
            $itemdata = DB::connection('mysql_dynamic')->select($select_query);
           
            $itemdata = array_map(function ($value) {
                return (array)$value;
            }, $itemdata);
            
            $count_select_query = "SELECT COUNT(tpid.vitemid) as count FROM trn_physicalinventorydetail as tpid where tpid.ipiid = '".$ipiid."'";
            
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            
            $count_records = $count_total = (int)$count_query[0]->count;
            
            if(count($itemdata) > 0){
                foreach ($itemdata as $key => $value) {
                    
                    $itemdata_query = "SELECT mpis.npack, mpis.iqtyonhand, mi.vitemcode, mi.dunitprice, (mi.dcostprice/mi.npack) as unitcost FROM mst_physical_inventory_snapshot AS mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' AND mpis.iitemid = '".$value['iitemid']."' ";
                    $itemdata1 = DB::connection('mysql_dynamic')->select($itemdata_query);
                    $itemdata1 = isset($itemdata1[0])?(array)$itemdata1[0]:[];
                    
                    $inventorycount_query = DB::connection('mysql_dynamic')->select("SELECT inventorycount FROM import_physical_inventory_csv_table WHERE vbarcode = '".$value['vbarcode']."' ");
                    $inventorycount_query = isset($inventorycount_query[0])?(array)$inventorycount_query[0]:[];
                    
                    if(isset($inventorycount_query['inventorycount'])){
                        $ndebitqty = $inventorycount_query['inventorycount'];
                    }else{
                        $ndebitqty = 0;
                    }
                    $temp = array();
                    $temp['iitemid'] = $value['iitemid'];
                    $temp['vbarcode'] = $value['vbarcode'];
                    $temp['vitemname'] = $value['vitemname'];
                    $temp['dunitprice'] = $itemdata1['dunitprice'];
                    $temp['unitcost'] = number_format($itemdata1['unitcost'], 2);
                    $temp['iqtyonhand'] = $itemdata1['iqtyonhand'];
                    $temp['ndebitqty'] = "<input type='number' class='text-center' name='ndebitqty' id='ndebitqty_".$value['iitemid']."' data-vbarcode =".$value['vbarcode']." oninput='getinventorycount($(this).val(), ".$value['iitemid'].")' value=".(int)$ndebitqty.">";
                    $temp['costtotal'] = number_format(($itemdata1['iqtyonhand'] * $itemdata1['unitcost']), 2);
                    $temp['pricetotal'] = number_format(($itemdata1['iqtyonhand'] * $itemdata1['dunitprice']), 2);
                    
                    $datas[] = $temp;
                }
            }
        }else{
            
            $ipiid = $input['ipiid'];
            $select_query = "SELECT mpis.iitemid, mpis.npack, mpis.vbarcode, mpis.iqtyonhand, mi.vitemname, mi.vitemcode, mi.dunitprice, (mi.dcostprice/mi.npack) as unitcost FROM mst_physical_inventory_snapshot as mpis LEFT JOIN mst_item as mi ON(mpis.iitemid = mi.iitemid) WHERE mpis.ipiid = '".$ipiid."' ORDER BY mpis.vbarcode LIMIT ". $input['start'].", ".$limit;
            $itemdata = DB::connection('mysql_dynamic')->select($select_query);
            
            $itemdata = array_map(function ($value) {
                return (array)$value;
            }, $itemdata);
            
            $count_select_query = "SELECT COUNT(mpis.iitemid) as count FROM mst_physical_inventory_snapshot as mpis WHERE mpis.ipiid = '".$ipiid."' ORDER BY mpis.iitemid ";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            
            $count_records = $count_total = (int)$count_query[0]->count;
            
            if(count($itemdata) > 0){
                foreach ($itemdata as $key => $value) {
                    $inventorycount_query = DB::connection('mysql_dynamic')->select("SELECT inventorycount FROM import_physical_inventory_csv_table WHERE vbarcode = '".$value['vbarcode']."' ");
                    $inventorycount_query = isset($inventorycount_query[0])?(array)$inventorycount_query[0]:[];
                    
                    if(isset($inventorycount_query['inventorycount'])){
                        $ndebitqty = $inventorycount_query['inventorycount'];
                    }else{
                        $ndebitqty = 0;
                    }
                    $temp = array();
                    $temp['iitemid'] = $value['iitemid'];
                    $temp['vbarcode'] = $value['vbarcode'];
                    $temp['vitemname'] = $value['vitemname'];
                    $temp['dunitprice'] = $value['dunitprice'];
                    $temp['unitcost'] = number_format($value['unitcost'], 2);
                    $temp['iqtyonhand'] = $value['iqtyonhand'];
                    $temp['ndebitqty'] = "<input type='number' class='text-center' name='ndebitqty' id='ndebitqty_".$value['iitemid']."' data-vbarcode =".$value['vbarcode']." oninput='getinventorycount($(this).val(), ".$value['iitemid'].")' value=".(int)$ndebitqty.">";
                    $temp['costtotal'] = number_format(($value['iqtyonhand'] * $value['unitcost']), 2);
                    $temp['pricetotal'] = number_format(($value['iqtyonhand'] * $value['dunitprice']), 2);
                    
                    $datas[] = $temp;
                }
            }
        }
        
        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $datas;
        
        return response(json_encode($return))
                  ->header('Content-Type', 'application/json');
    }
    
    public function calculate_commit(Request $request)
    {
        $input = $request->all();
        if ($request->isMethod('post')) {
            
			$items = array();
			$ndebitextprice = 0;
			$totalndebitextprice = 0;
			
			if(isset($input['ipiid']) && $input['ipiid'] != ''){
			    
			    $ipiid = $input['ipiid'];
                if(isset($input['action']) && $input['action'] == 'import'){
                    
                    $items_query = "SELECT mpis.iitemid, mpis.vbarcode, mpis.iqtyonhand, ipic.inventorycount as ndebitqty FROM mst_physical_inventory_snapshot mpis LEFT JOIN import_physical_inventory_csv_table as ipic ON(mpis.vbarcode = ipic.vbarcode) WHERE ipiid = '".$ipiid."' ";
                    
                    $items = DB::connection('mysql_dynamic')->select($items_query);
                    
                    $items = array_map(function ($value) {
                        return (array)$value;
                    }, $items);
                    
                }else{
                    
                    $snapshot_items_query = "SELECT mpis.iitemid, mpis.vbarcode, mpis.iqtyonhand FROM mst_physical_inventory_snapshot mpis WHERE ipiid = '".$ipiid."' ";
                    
                    $snapshot_items = DB::connection('mysql_dynamic')->select($snapshot_items_query);
                    
                    $snapshot_items = array_map(function ($value) {
                        return (array)$value;
                    }, $snapshot_items);
                    
                    // $items = [];
                    $selected_vbarcode_arr = session()->get('selected_vbarcode_arr');
                    foreach($snapshot_items as $value){
                        if(isset($selected_vbarcode_arr) && !empty($selected_vbarcode_arr)){
                            foreach($selected_vbarcode_arr as $key => $vbarcode){
                                
                               if($vbarcode[0]['vbarcode'] == $value['vbarcode']){
                                   $ndebitqty = $vbarcode[0]['invtcount'];
                                   break;
                               }else{
                                   $ndebitqty = 0;
                               }
                            }
                        }else{
                            $ndebitqty = 0;
                        }
                        $items[] =  array(
                                            'iitemid'   =>  $value['iitemid'],
                                            'vbarcode'  =>  $value['vbarcode'],
                                            'ndebitqty' =>  $ndebitqty,
                                            'iqtyonhand'=>  $value['iqtyonhand']
                                        );
                    }
                }
                
                foreach ($items as $k => $item) {
				
				// echo $k." "; print_r($vbarcode[$k]); echo "<br>";
				
				    $itemdetail = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid = '".$item['iitemid']."' ");
				    $itemdetail = isset($itemdetail[0])?(array)$itemdetail[0]:[];
				    
				    $itotalunit = number_format(floatval($item['ndebitqty']), 2);
				    $format1 = number_format(floatval($itemdetail['nunitcost']), 2);
				    
				    $ndebitextprice = $itotalunit * $format1;
				    
				    //========for sold item========
				    $from = $input['dcreatedate'];
				    $to = date('Y-m-d H:i:s');
				    $soldqty = DB::connection('mysql_dynamic')->select("SELECT ifnull(SUM(tsd.ndebitqty), 0) as soldqty FROM trn_salesdetail as tsd LEFT JOIN trn_sales as ts ON(ts.isalesid = tsd.isalesid) WHERE ts.vtrntype='Transaction' AND tsd.vitemcode = '".$item['vbarcode']."' AND ts.dtrandate >= '".$from."' AND ts.dtrandate <= '".$to."' GROUP BY vitemcode ");
				    $soldqty = isset($soldqty[0])?(array)$soldqty[0]:[];
				    
				    $ndiffqty = $item['iqtyonhand'] - ($soldqty['soldqty'] ?? 0) - (number_format(floatval($item['ndebitqty']), 2)* $itemdetail['npack']);
				    
					//=====creating values for bulk insert===================
					$sql[] = "( '" . (int)$ipiid . "', '" . ($itemdetail['iitemid']) . "', '" . ($itemdetail['vitemname']) . "', ' ', ' ', '" . ($item['ndebitqty']) . "', '0.00', '0.00', '0.00', '0.00', '" . ($ndebitextprice) . "', '0.00', '0.00', '0.00', '" . ($itemdetail['vbarcode']) . "', ' ', '" . ($ndiffqty) . "', ' ', '" . ($itemdetail['npack']) . "', '" . ($itemdetail['nunitcost']) . "', '" . ($itotalunit) . "', '" . (int)(session()->get('sid'))."', '". $itemdetail['iqtyonhand']."', '".$item['ndebitqty'] ."'  )";
                     
					$totalndebitextprice = $totalndebitextprice + $ndebitextprice;
				} 
			}
            
			$temp_arr[0] = array(
			                    'ipiid' => $input['ipiid'],
								'vpinvtnumber' => '',
								'vrefnumber' => $input['vrefnumber'],
								// 'nnettotal' => $ndebitextprice,
								'nnettotal' => $totalndebitextprice,
								'ntaxtotal' => '0.00',
								'dcreatedate' => \DateTime::createFromFormat('m-d-Y H:i:s', $input['dcreatedate'])->format('Y-m-d H:i:s'),
								'estatus' => 'Calculated',
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'] ?? '',
								'dlastupdate' => '',
								'vtype' => 'Physical',
								'ilocid' => $input['ilocid'] ?? '',
								'dcalculatedate' => date('Y-m-d H:i:s'),
								'dclosedate' => '',
								'items' => $sql,
								'detail_name' => 'physical'
									
							);
            
			$result = PhysicalInventory::first()->addNewPhysicalInventory($temp_arr);
			if(isset($result['success']) && isset($result['ipiid'])){
                     
                $query_items = "SELECT vitemid, ndebitqty, itotalunit FROM trn_physicalinventorydetail WHERE ipiid = '".$result['ipiid']."' ";
                $items = DB::connection('mysql_dynamic')->select($query_items);
                
                $items = array_map(function ($value) {
                    return (array)$value;
                }, $items);
                
                $temp_array = array(
                                    'ipiid' => $ipiid,
                                    'items' => $items,
                                    'dclosedate' => date('Y-m-d H:i:s')
                                   );
                                   
                $PhysicalInventory = new PhysicalInventory;
                $result1 = $PhysicalInventory->commit($temp_array);
                unset($temp_array);
            }
			    
            
			return response(json_encode($result1), 200)
                  ->header('Content-Type', 'application/json');
        }
    }
    
    public function check_status(Request $request)
    {
        $input = $request->all();
        if ($request->isMethod('get')) {
            
            $result = PhysicalInventory::first()->check_status();
            
            if(isset($result) && count($result)){
                
                $data['result'] = 'notclossed';
                $data['msg'] = 'Previous Physical Inventory Not Clossed Yet, So Creating New Physical Inventory Not Allowed';
                return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');
            }
        }
    }
    
}
