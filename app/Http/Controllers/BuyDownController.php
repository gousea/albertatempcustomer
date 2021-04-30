<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\BuyDown;
use App\Model\BuyDownDetail;
use App\Model\Promotion;
use App\Model\promotiontype;
use App\Model\Department;
use App\Model\Item;
use App\Model\Unit;
use App\Model\Size;
use App\Model\Category;
use App\Model\SubCategory;
use App\Model\WebAdminSetting;
use Session;
use App\Model\ItemGroup;
use App\Model\Vendor;
use App\Model\Manufacturer;
use Illuminate\Support\Facades\DB;


class BuyDownController extends Controller
{
    public function buydown(){
        
        // $buydown = BuyDown::orderBy('LastUpdate', 'desc')->paginate(10);
        $data['delete_buydown'] = url('/deletebuydown');
        $data['search_url']     = url('/buydownsearch');
        // return view('items.buydown', compact('buydown', 'data'));
        return view('items.buydown', compact('data'));
    }
    
    public function addbuydown(){
        return view('items.buydownadd');
    }
    
    public function buydownsearch(Request $request) 
    {
        $return = $datas = array();
        
        $input = $request->all();
        $search = $input['columns'];
        $bydown_records = [];
        $total_record_count = 0;
        if(!$search[1]['search']['value'] && !$search[2]['search']['value'] && !$search[3]['search']['value'] && !$search[4]['search']['value'] && !$search[5]['search']['value'])
        {
            $BuyDown = new BuyDown;
            $results = $BuyDown->getAllBuydown($input['start'],$input['length']);
            $bydown_records = $results['records'];
            $total_record_count = $results['total_count'];
        }
        else
        {   
            $BuyDown = new BuyDown;
            $results = $BuyDown->searchBuydown($search,$input['start'],$input['length']);
            $bydown_records = $results['records'];
            $total_record_count = $results['total_count'];
        }
        $count = $count_records = count($results);
        $promotions = $results;
        
        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $total_record_count;
        $return['recordsFiltered'] = $total_record_count;
        $return['data'] = $bydown_records;
        
        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
        
    }	
    
    // public function buydownsearch(Request $request)
    // {
        
    //     $input = $request->all();
    //     $buydownname = $request->input('buydownname') !== null && !empty(trim($request->input('buydownname'))) ? $request->input('buydownname') : '';
    //     $buydowncode = $request->input('buydowncode') !== null && !empty(trim($request->input('buydowncode'))) ? $request->input('buydowncode') : '';
    //     $buydownamount = $request->input('buydowncode') !== null && !empty(trim($request->input('buydowncode'))) ? $request->input('buydowncode') : '';

    //     $buydown = BuyDown::where('buydown_name', 'LIKE', '%' . $buydownname . '%')
    //         ->where('buydown_code', 'LIKE', '%' . $buydowncode . '%')->get();

    //     return response()->json(['buydown' => $buydown], 200);
    // }
    
    protected function getForm(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $input = $request->all();

        $url = '';

        // $data['breadcrumbs']    = array();
        // $data['breadcrumbs'][]  = array(
        //         'text' => $this->language->get('text_home'),
        //         'href' => $this->url->link('common/dashboard')
        //     );

        // $data['breadcrumbs'][] = array(
        //         'text' => $this->language->get('heading_title'),
        //         'href' => $this->url->link('administration/buydown', 'token=' . $this->session->data['token'] . $url, true)
        //     );

        $item_groups = ItemGroup::orderBy('vitemgroupname', 'ASC')->get()->toArray();
        $data['itemGroups'] = $item_groups;

        // $data['heading_title'] = $this->language->get('heading_title');



        // $data['text_form']          = $this->language->get('text_form');
        // $data['text_no_results']    = $this->language->get('text_no_results');
        // $data['text_confirm']       = $this->language->get('text_confirm');

        $data['get_customers_url']                  = url('buydown/get_customers');
        $data['get_items_url']                      = url('buydown/get_items');
        $data['get_categories_url']                 = url('buydown/get_item_categories');
        $data['get_sub_categories_url']             = url('buydown/get_sub_categories_url');
        $data['get_department_items_url']           = url('buydown/get_department_items');
        $data['get_category_items_url']             = url('buydown/get_category_items');
        $data['get_sub_category_items_url']         = url('buydown/get_sub_category_items');
        $data['get_selected_buy_items_url']         = url('buydown/getSelectedBuyItems');
        $data['get_saved_buy_items_ajax_url']       = url('buydown/getSavedItemsAjax');

        $data['get_selected_discounted_items_url']  = url('buydown/getSelectedDiscountedItems');
        $data['get_saved_discounted_items_ajax_url'] = url('buydown/getSelectedDiscountedItemsAjax');
        $data['get_group_items_url']                = url('buydown/get_group_items');

        $data['validate_item_url']                  = url('buydown/validate_item');
        $data['cancel']                             = url('buydown_list');
        $data['searchitem']                         = url('buydown/search');
        
        /*Items table details*/
        
        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        $data['departments'] = $departments;
        $departments_html = "";
        $departments_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 85px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($departments as $department) {
            if (isset($vdepcode) && $vdepcode == $department['vdepcode']) {
                $departments_html .= "<option value='" . $department['vdepcode'] . "' selected='selected'>" . $department['vdepartmentname'] . "</option>";
            } else {
                $departments_html .= "<option value='" . $department['vdepcode'] . "'>" . $department['vdepartmentname'] . "</option>";
            }
        }
        $departments_html .= "</select>";

        $data['departments'] = $departments_html;

        $item_types = ['Standard', 'Kiosk', 'Lot Matrix', 'Lottery'];

        $item_type_html = "";
        $item_type_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($item_types as $item_type) {
            $item_type_html .= "<option value='" . $item_type . "'>" . $item_type . "</option>";
        }
        $item_type_html .= "</select>";

        $data['item_types'] = $item_type_html;


        $suppliers = Vendor::orderBy('vcompanyname', 'ASC')->get()->toArray();
        $supplier_html = "";
        $supplier_html = "<select class='form-control' name='supplier_code' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($suppliers as $supplier) {
            $supplier_html .= "<option value='" . $supplier['vsuppliercode'] . "'>" . $supplier['vcompanyname'] . "</option>";
        }
        $supplier_html .= "</select>";

        $data['suppliers'] = $supplier_html;

        $manufacturers = Manufacturer::orderBy('mfr_name', 'ASC')->get()->toArray();
        $manufacturer_html = "";
        $manufacturer_html = "<select class='form-control' name='manufacturer_id' id='manufacturer_id' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($manufacturers as $manufacurer) {
            $manufacturer_html .= "<option value='" . $manufacurer['mfr_id'] . "'>" . $manufacurer['mfr_name'] . "</option>";
        }
        $manufacturer_html .= "</select>";

        $data['manufacturers'] = $manufacturer_html;

        // $units = $this->model_administration_items->getItemUnits();
        $units = Unit::orderBy('vunitname', 'ASC')->get()->toArray();
        $units_html = "";
        $units_html = "<select class='form-control' name='unit_id' id='unit_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($units as $unit) {
            $units_html .= "<option value='" . $unit['vunitcode'] . "'>" . $unit['vunitname'] . "</option>";
        }
        $units_html .= "</select>";

        $data['units'] = $units_html;

        // $sizes = $this->model_administration_items->getItemSize();
        $sizes = Size::orderBy('vsize', 'ASC')->get()->toArray();
        $size_html = "";
        $size_html = "<select class='form-control' name='size_id' id='size_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($sizes as $size) {
            $size_html .= "<option value='" . $size['vsize'] . "'>" . $size['vsize'] . "</option>";
        }
        $size_html .= "</select>";

        $data['size'] = $size_html;


        //==================================== for price filter ====================================================

        $price_select_by_list = array(
            'greater'   => 'Greater than',
            'less'      => 'Less than',
            'equal'     => 'Equal to',
            'between'   => 'Between'
        );

        $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='width:90px; color:#000000'>";
        foreach ($price_select_by_list as $k => $v) {
            $price_select_by_html .= "<option value='" . $k . "'";

            if (isset($data['price_select_by']) && $k === $data['price_select_by']) {
                $price_select_by_html .= " selected";
            }

            $price_select_by_html .= ">" . $v . "</option>";
        }
        $price_select_by_html .= "</select>";
        $price_select_by_html .= "<span id='selectByValuesSpan'>";

        if (isset($input['price_select_by']) && $input['price_select_by'] === 'between') {
            // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Amt' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
        } else {
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter Amt' style='width:70px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
        }

        $price_select_by_html .= "</span>";


        $data['price'] = $price_select_by_html;
        
        /********************/

        //Error Messages
        if (isset($this->error['warning'])) {

            $data['error_warning'] = $this->error['warning'];
        } elseif (isset($this->session->data['error'])) {

            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['buydown_name'])) {
            $data['error_buydown_name'] = $this->error['buydown_name'];
        } else {
            $data['error_buydown_name'] = '';
        }

        if (isset($this->error['buydown_code'])) {
            $data['error_buydown_code'] = $this->error['buydown_code'];
        } else {
            $data['error_buydown_code'] = '';
        }

        if (isset($this->error['buydown_to_date'])) {
            $data['error_buydown_to_date'] = $this->error['buydown_to_date'];
        } else {
            $data['error_buydown_to_date'] = '';
        }

        if (isset($this->error['buydown_from_date'])) {
            $data['error_buydown_from_date'] = $this->error['buydown_from_date'];
        } else {
            $data['error_buydown_from_date'] = '';
        }


        //      echo "<pre>";print_r($data);exit;
        if (isset($input['buydown_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

            $buydown_info = $this->model_administration_buydown->getbuydown($input['buydown_id']);
            $buydown_items = $this->model_administration_buydown->getSavedBuyItems($input['buydown_id']);
        }


        if (isset($input['buydown_id'])) {
            $data['buydown_id'] = $input['buydown_id'];
        } elseif (!empty($buydown_info)) {
            $data['buydown_id'] = $buydown_info['buydown_id'];
        } else {
            $data['buydown_id'] = '';
        }

        if (isset($input['buydown_name'])) {
            $data['buydown_name'] = $input['buydown_name'];
        }
        // elseif (count($buydown_info) !== 0) {
        //     //print_r($buydown_info['buydown_name']);die;
        //     $data['buydown_name'] = $buydown_info['buydown_name'];
        // }
        else {
            $data['buydown_name'] = '';
        }

        if (isset($input['buydown_code'])) {
            $data['buydown_code'] = $input['buydown_code'];
        } elseif (!empty($buydown_info)) {
            $data['buydown_code'] = $buydown_info['buydown_code'];
        } else {
            $data['buydown_code'] = '';
        }

        if (isset($input['buydown_from_date'])) {
            $data['buydown_from_date'] = $input['buydown_from_date'];
        } elseif (!empty($buydown_info)) {
            $data['buydown_from_date'] = $buydown_info['start_date'];
            
        } else {
            $data['buydown_from_date'] = '';
        }

        if (isset($input['buydown_to_date'])) {
            $data['buydown_to_date'] = $input['buydown_to_date'];
        } elseif (!empty($buydown_info)) {
            $data['buydown_to_date'] = $buydown_info['end_date'];
          
        } else {
            $data['buydown_to_date'] = '';
        }


        if (isset($input['buydown_to_amt'])) {
            $data['buydown_to_amt'] = $input['buydown_to_amt'];
        }
        // elseif (count($buydown_info) !== 0) {
        //     $data['buydown_to_amt'] = $buydown_info['buydown_amount'];
        // }
        else {
            $data['buydown_to_amt'] = '';
        }

        if (!empty($buydown_info)) {
            $data['status'] = $buydown_info['status'];
        } else {
            $data['status'] = '';
        }

        if (!empty($buydown_items)) {
            $data['buydown_items'] = $buydown_items;
        } else {
            $data['buydown_items'] = '';
        }
        if (!isset($input['buydown_id'])) {
            $data['action'] = url('buydown/add');
        } else {
            $data['action'] = url('buydown/edit', [$input['buydown_id']]);
            // $data['buydown_id'] = $input['buydown_id'];
        }

        return view('items.buydownadd', compact('data'));
    }

    public function search(Request $request)
    {
        $return = $datas = array();

        $input = $request->all();

        $sort = "mi.LastUpdate DESC";
        if (isset($input['sort_items']) && !empty(trim($input['sort_items']))) {
            $sort_by = trim($input['sort_items']);
            $sort = "mi.vitemname $sort_by";
        }

        $show_condition = "WHERE mi.estatus='Active'";
        if (isset($input['show_items']) && !empty(trim($input['show_items']))) {
            $show_items = trim($input['show_items']);
            if ($show_items == "All") {
                $show_condition = "WHERE mi.estatus !=''";
            } else {
                $show_condition = "WHERE mi.estatus='" . $show_items . "'";
            }
        }

        $search_value = $input['columns'];

        $search_itmes = [];
        foreach ($search_value as $value) {
            if ($value["data"] == "vitemname") {
                $search_items['vitemname'] = $value['search']['value'];
            } else if ($value["data"] == "vbarcode") {
                $search_items['vbarcode'] = $value['search']['value'];
            } else if ($value["data"] == "dunitprice") {
                $search_items['dunitprice'] = $value['search']['value'];
            } else if ($value["data"] == "vcategoryname") {
                $search_items['vcategoryname'] = $value['search']['value'];
            } else if ($value["data"] == "vdepartmentname") {
                $search_items['vdepartmentname'] = $value['search']['value'];
            } else if ($value["data"] == "vcategoryname") {
                $search_items['vcategoryname'] = $value['search']['value'];
            } else if ($value["data"] == "subcat_name") {
                $search_items['subcat_name'] = $value['search']['value'];
            } else if ($value["data"] == "vcompanyname") {
                $search_items['vcompanyname'] = $value['search']['value'];
            } else if ($value["data"] == "mfr_name") {
                $search_items['mfr_name'] = $value['search']['value'];
            } else if ($value["data"] == "vitemtype") {
                $search_items['vitemtype'] = $value['search']['value'];
            } else if ($value["data"] == "vunitname") {
                $search_items['vunitname'] = $value['search']['value'];
            } else if ($value["data"] == "vsize") {
                $search_items['vsize'] = $value['search']['value'];
            }
        }

        if (empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && empty(trim($search_items['dunitprice'])) && empty(trim($search_items['vcategoryname'])) &&  empty(trim($search_items['vdepartmentname'])) && empty(trim($search_items['subcat_name'])) && empty(trim($search_items['vunitname'])) && empty(trim($search_items['vsize']))) {
            $limit = 20;
            $start_from = ($input['start']);

            $offset = $input['start'] + $input['length'];
            $show_condition = "WHERE mi.iitemid=0";
            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.*, md.vdepartmentname, mc.vcategoryname,msc.subcat_name, msupp.vcompanyname, mi.manufacturer_id, mfr.mfr_name, unit.vunitname, mi.vunitcode, mi.dunitprice, (SELECT prom_id FROM trn_prom_details where barcode = mi.vbarcode limit 1) as prom_id FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) LEFT JOIN mst_manufacturer as mfr ON(mi.manufacturer_id = mfr.mfr_id) LEFT JOIN mst_unit as unit ON(mi.vunitcode = unit.vunitcode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition ORDER BY $sort LIMIT " . $start_from . ", " . $limit;

            $query = DB::connection('mysql_dynamic')->select($select_query);

            $count_query = "SELECT DISTINCT(iitemid) FROM mst_item mi $show_condition";

            $run_count_query = DB::connection('mysql_dynamic')->select($count_query);

            $count_records = $count_total = count($run_count_query);
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
                    $condition .= " AND mi.vdepcode ='" . $search . "'";
                }
            }

            if (isset($search_items['vcategoryname']) && !empty(trim($search_items['vcategoryname']))) {
                $search = $search_items['vcategoryname'];
                if ($search_items['vdepartmentname'] != 'all' && $search != 'all') {
                    $condition .= " AND mi.vcategorycode ='" . $search . "'";
                }
            }

            if (isset($search_items['subcat_name']) && !empty($search_items['subcat_name'])) {
                $search = $search_items['subcat_name'];
                if ($search_items['vcategoryname'] != 'all' && $search_items['vdepartmentname'] != 'all' && $search != 'all') {
                    $condition .= " AND mi.subcat_id ='" . $search . "'";
                }
            }

            if (isset($search_items['vcompanyname']) && !empty($search_items['vcompanyname'])) {
                $search = $search_items['vcompanyname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vsuppliercode ='" . $search . "'";
                }
            }

            if (isset($search_items['mfr_name']) && !empty($search_items['mfr_name'])) {
                $search = $search_items['mfr_name'];
                if ($search != 'all') {
                    $condition .= " AND mi.manufacturer_id ='" . $search . "'";
                }
            }

            if (isset($search_items['vitemtype']) && !empty($search_items['vitemtype'])) {
                $search = $search_items['vitemtype'];
                if ($search != 'all') {
                    $condition .= " AND mi.vitemtype ='" . $search . "'";
                }
            }

            if (isset($search_items['vunitname']) && !empty($search_items['vunitname'])) {
                $search = $search_items['vunitname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vunitcode ='" . $search . "'";
                }
            }

            if (isset($search_items['vsize']) && !empty($search_items['vsize'])) {
                $search = $search_items['vsize'];
                if ($search != 'all') {
                    $condition .= " AND mi.vsize ='" . $search . "'";
                }
            }
            $sid = "u" . session()->get('sid');
            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vitemtype,mi.npack,mi.new_costprice, md.vdepartmentname, mc.vcategoryname,msc.subcat_name,msupp.vcompanyname, mi.nsaleprice, mi.iqtyonhand, mi.LastUpdate, mi.subcat_id,mi.manufacturer_id,mfr.mfr_name,unit.vunitname, mi.vunitcode, mi.dunitprice, (SELECT prom_id FROM trn_prom_details where barcode = mi.vbarcode limit 1) as prom_id, mi.* FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id = msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) LEFT JOIN mst_manufacturer as mfr ON(mi.manufacturer_id = mfr.mfr_id) LEFT JOIN mst_unit as unit ON(mi.vunitcode = unit.vunitcode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition " . " $condition ORDER BY $sort LIMIT " . $input['start'] . ", " . $limit;

            $query = DB::connection('mysql_dynamic')->select($select_query);

            $count_select_query = "SELECT COUNT(*) as count FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) $show_condition " . " $condition";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            $count_records = $count_total = (int)($count_query[0]->count);
        }

        $search = $input['search']['value'];

        if (count($query) > 0) {
            foreach ($query as $key => $value) {

                $temp = array();
                $temp['iitemid'] = $value->iitemid;
                $temp['vitemname'] = $value->vitemname;
                $temp['vitemtype'] = $value->vitemtype;
                $temp['vbarcode'] = $value->vbarcode;
                $temp['vdepartmentname'] = $value->vdepartmentname;
                $temp['vcategoryname'] = $value->vcategoryname;
                $temp['subcat'] = $value->subcat_id;
                $temp['subcat_name'] = $value->subcat_name;
                $temp['nunitcost'] = $value->nunitcost;
                $temp['vunitname'] = $value->vunitname;
                $temp['vsize'] = $value->vsize;
                $temp['vsuppliercode'] = $value->vsuppliercode;
                $temp['vcompanyname'] = $value->vcompanyname;
                $temp['manufacturer_id'] = $value->manufacturer_id;
                $temp['last_costprice'] = $value->last_costprice;
                $temp['nsaleprice'] = $value->nsaleprice;
                $temp['iqtyonhand'] = $value->iqtyonhand;
                $temp['mfr_name'] = $value->mfr_name;
                $temp['prom_id'] = $value->prom_id;
                $temp['dunitprice'] = $value->dunitprice;

                $temp['dcostprice'] = ($value->new_costprice * $value->npack);
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

    public function get_items()
    {
        $datas = array();
        $query = DB::connection('')->select("SELECT `iitemid`,`webstore`,`vitemtype`,`vitemcode`,`vitemname`,`vunitcode`,`vbarcode`,`vpricetype`,`vcategorycode`,`vdepcode`,`vsuppliercode`,`iqtyonhand`,`ireorderpoint`,`dcostprice`,`dunitprice`,`nsaleprice`,`nlevel2`,`nlevel3`,`nlevel4`,`iquantity`,`ndiscountper`,`ndiscountamt`,`vtax1`,`vtax2`,`vfooditem`,`vdescription`,`dlastsold`,`visinventory`,`dpricestartdatetime`,`dpriceenddatetime`,`estatus`,`nbuyqty`,`ndiscountqty`,`nsalediscountper`,`vshowimage`,`itemimage`,`vageverify`,`ebottledeposit`,`nbottledepositamt`,`vbarcodetype`,`ntareweight`,`ntareweightper`,`dcreated`,`dlastupdated`,`dlastreceived`,`dlastordered`,`nlastcost`,`nonorderqty`,`vparentitem`,`nchildqty`,`vsize`,`npack`,`nunitcost`,`ionupload`,`nsellunit`,`ilotterystartnum`,`ilotteryendnum`,`etransferstatus`,`vsequence`,`vcolorcode`,`vdiscount`,`norderqtyupto`,`vshowsalesinzreport`,`iinvtdefaultunit`,`LastUpdate`,`SID`,`stationid`,`shelfid`,`aisleid`,`shelvingid`,`rating`,`vintage`,`PrinterStationId`,`liability`,`isparentchild`,``parentid,`parentmasterid`,`wicitem` FROM mst_item");

        if (count($query) > 0) {
            foreach ($query as $key => $value) {
                $temp = array();
                $temp['iitemid'] = $value['iitemid'];
                $temp['webstore'] = $value['webstore'];
                $temp['vitemtype'] = $value['vitemtype'];
                $temp['vitemcode'] = $value['vitemcode'];
                $temp['vitemname'] = $value['vitemname'];
                $temp['vunitcode'] = $value['vunitcode'];
                $temp['vbarcode'] = $value['vbarcode'];
                $temp['vpricetype'] = $value['vpricetype'];
                $temp['vcategorycode'] = $value['vcategorycode'];
                $temp['vdepcode'] = $value['vdepcode'];
                $temp['vsuppliercode'] = $value['vsuppliercode'];
                $temp['iqtyonhand'] = $value['iqtyonhand'];
                $temp['ireorderpoint'] = $value['ireorderpoint'];
                $temp['dcostprice'] = $value['dcostprice'];
                $temp['dunitprice'] = $value['dunitprice'];
                $temp['nsaleprice'] = $value['nsaleprice'];
                $temp['nlevel2'] = $value['nlevel2'];
                $temp['nlevel3'] = $value['nlevel3'];
                $temp['nlevel4'] = $value['nlevel4'];
                $temp['iquantity'] = $value['iquantity'];
                $temp['ndiscountper'] = $value['ndiscountper'];
                $temp['ndiscountamt'] = $value['ndiscountamt'];
                $temp['vtax1'] = $value['vtax1'];
                $temp['vtax2'] = $value['vtax2'];
                $temp['vfooditem'] = $value['vfooditem'];
                $temp['vdescription'] = $value['vdescription'];
                $temp['dlastsold'] = $value['dlastsold'];
                $temp['visinventory'] = $value['visinventory'];
                $temp['dpricestartdatetime'] = $value['dpricestartdatetime'];
                $temp['dpriceenddatetime'] = $value['dpriceenddatetime'];
                $temp['estatus'] = $value['estatus'];
                $temp['nbuyqty'] = $value['nbuyqty'];
                $temp['ndiscountqty'] = $value['ndiscountqty'];
                $temp['nsalediscountper'] = $value['nsalediscountper'];
                $temp['vshowimage'] = $value['vshowimage'];
                if (isset($value['vshowimage']) && !empty($value['vshowimage'])) {
                    $temp['itemimage'] = $value['itemimage'];
                } else {
                    $temp['itemimage'] = '';
                }

                $temp['vageverify'] = $value['vageverify'];
                $temp['ebottledeposit'] = $value['ebottledeposit'];
                $temp['nbottledepositamt'] = $value['nbottledepositamt'];
                $temp['vbarcodetype'] = $value['vbarcodetype'];
                $temp['ntareweight'] = $value['ntareweight'];
                $temp['ntareweightper'] = $value['ntareweightper'];
                $temp['dcreated'] = $value['dcreated'];
                $temp['dlastupdated'] = $value['dlastupdated'];
                $temp['dlastreceived'] = $value['dlastreceived'];
                $temp['dlastordered'] = $value['dlastordered'];
                $temp['nlastcost'] = $value['nlastcost'];
                $temp['nonorderqty'] = $value['nonorderqty'];
                $temp['vparentitem'] = $value['vparentitem'];
                $temp['nchildqty'] = $value['nchildqty'];
                $temp['vsize'] = $value['vsize'];
                $temp['npack'] = $value['npack'];
                $temp['nunitcost'] = $value['nunitcost'];
                $temp['ionupload'] = $value['ionupload'];
                $temp['nsellunit'] = $value['nsellunit'];
                $temp['ilotterystartnum'] = $value['ilotterystartnum'];
                $temp['ilotteryendnum'] = $value['ilotteryendnum'];
                $temp['etransferstatus'] = $value['etransferstatus'];
                $temp['vsequence'] = $value['vsequence'];
                $temp['vcolorcode'] = $value['vcolorcode'];
                $temp['vdiscount'] = $value['vdiscount'];
                $temp['norderqtyupto'] = $value['norderqtyupto'];
                $temp['vshowsalesinzreport'] = $value['vshowsalesinzreport'];
                $temp['iinvtdefaultunit'] = $value['iinvtdefaultunit'];
                $temp['LastUpdate'] = $value['LastUpdate'];
                $temp['SID'] = $value['SID'];
                $temp['stationid'] = $value['stationid'];
                $temp['shelfid'] = $value['shelfid'];
                $temp['aisleid'] = $value['aisleid'];
                $temp['shelvingid'] = $value['shelvingid'];
                $temp['rating'] = $value['rating'];
                $temp['vintage'] = $value['vintage'];
                $temp['PrinterStationId'] = $value['PrinterStationId'];
                $temp['liability'] = $value['liability'];
                $temp['isparentchild'] = $value['isparentchild'];
                $temp['parentid'] = $value['parentid'];
                $temp['parentmasterid'] = $value['parentmasterid'];
                $temp['wicitem'] = $value['wicitem'];

                $datas[] = $temp;
            }
        }

        return $datas;
    }

    public function get_item_categories(Request $request)
    {

        $input = $request->all();
        if (isset($input['dep_code'])  && $input['dep_code'] != "") {
            $all_departments = $input['dep_code'][0];

            $categories = Category::where('dept_code', '=', $all_departments)->get()->toArray();
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

    public function get_sub_categories_url(Request $request)
    {
        $input = $request->all();
        if (isset($input['cat_code'])  && $input['cat_code'] != "") {
            $cat_id = $input['cat_code'][0];
            $subCategories = Subcategory::where('cat_id', "=", $cat_id)->get()->toArray();
            $subcat_list = "<option value='all'>All</option>";
            foreach ($subCategories as $subCategory) {
                if (isset($subCategory['subcat_id'])) {
                    $sub_cat_id     = $subCategory['subcat_id'];
                    $sub_cat_name   = $subCategory['subcat_name'];
                    $subcat_list   .= "<option value=" . $sub_cat_id . ">" . $sub_cat_name . "</option>";
                }
            }
            echo $subcat_list;
        }
    }

    public function validate_item(Request $request)
    {
        $input = $request->all();
        $msg = '';
        $item_ids = array();
        $itemids = $input['itemid'];
        $msg = [];
        $data = [];
        foreach ($itemids as $itemid) {
            $sql_query = "SELECT vbarcode FROM mst_item WHERE iitemid = '" . $itemid . "' ";
            $vbarcode = DB::connection('mysql_dynamic')->select($sql_query);
            $vbar = $vbarcode[0]->vbarcode;
            if (isset($vbar) && !empty($vbar)) {
                $check_sql_query = "SELECT CONCAT(bd.vitemname,'[',bd.vbarcode,']') item_name FROM trn_buydown b LEFT JOIN trn_buydown_details bd ON(b.buydown_id = bd.buydown_id) WHERE b.status = 'Active' AND bd.vbarcode = '" . $vbar . "' ";
                $mysql_result = DB::connection('mysql_dynamic')->select($check_sql_query);
                if (count($mysql_result) > 0) {
                    $item_name = $mysql_result[0]->item_name;
                } else {
                    $item_name = '';
                }
                if (isset($item_name) && ($item_name != '')) {
                    $msg[] = $item_name;
                    $item_ids[] = $itemid;
                    $data['result'] = 'available';
                }
            }
        }
        $data['msg'] = $msg;
        $data['item_ids'] = $item_ids;

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }

    public function getSelectedBuyItems(Request $request)
    {
    
        $input = $request->all();
        if (isset($input['buyItemids'])  && !empty($input['buyItemids'])) {

            $buydown_amount = $input['buydown_amount'];
            $query = "SELECT iitemid, vitemname, vbarcode, dunitprice, dcostprice, npack, isparentchild, parentid FROM mst_item WHERE iitemid = '".$input['buyItemids']."' ";
            $results = DB::connection('mysql_dynamic')->select($query);
       
            $row = "";
            if (!empty($results)) {
                
                $row .= "<tr>";
                $row .= "<td><input type='checkbox' class='buydown_items' name='added_buydown_items[]' data-vbarcode=" . $results[0]->vbarcode . " value=" . $results[0]->iitemid . "></td>";
                $row .= "<td>" . $results[0]->vitemname . "<input type='hidden' name='added_buydown_name[]' value='" . $results[0]->vitemname . "'></td>";
                $row .= "<td>" . $results[0]->vbarcode . " <input type='hidden' name='added_buydown_barcode[]' value=" . $results[0]->vbarcode . "></td>";
                $row .= "<td class='live_cost'>".number_format((float)$results[0]->dcostprice, 2, '.', '')."</td>";
                $row .= "<td class='live_price'>".number_format((float)$results[0]->dunitprice, 2, '.', '')."</td>";
                $row .= "<td class='invoce'>" . number_format((float)$results[0]->dcostprice, 2, '.', '')  . "<input type='hidden' name='added_buydown_before_inovice[]' value=" . number_format((float)$results[0]->dcostprice, 2, '.', '')  . "></td>";
                $row .= "<td class='price'>" . number_format((float)$results[0]->dunitprice, 2, '.', '') . " <input type='hidden' name='added_buydown_before_price[]' value=" . number_format((float)$results[0]->dunitprice, 2, '.', '')  . "></td>";
                $row .= "<td><input type='text' class='buydown_amt' id='buydown_row' name='added_buydown_amt[]' value=" . $buydown_amount . " ></td>";
                $row .= "<td class='cost_after_buydown'>" . number_format((float)($results[0]->dcostprice - $buydown_amount), 2, '.', '') . " </td>";
                $row .= "<td class='price_after_buydown'>" . number_format((float)($results[0]->dunitprice - $buydown_amount), 2, '.', '')   . "</td>";
                $row .= "</tr>";

                echo $row;
            }
        }
    }

    public function savebuydown(Request $req)
    {
        $input = $req->all();
        $buydown = new BuyDown;

        $buydown->buydown_name = $req->input('buydown_name');
        $buydown->buydown_code = $req->input('buydown_code');
        $buydown->start_date = $req->input('buydown_from_date');
        $buydown->end_date = $req->input('buydown_to_date');
        $buydown->buydown_amount = $req->input('buydown_to_amt');
        $buydown->save();

        $buydownid = $buydown->buydown_id;
        //$prom_id = $promotion->prom_id;

        echo 'inserted Successfully';
    }

    public function add(Request $request)
    {
        $input = $request->all();
        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            }
            foreach($stores as $store){
                if (isset($input['added_buydown_barcode']) && !empty($input['added_buydown_barcode'])) {
                    $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_from_date'] ))) ;
                    $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_to_date']))) ;
                    
                    $buydown = DB::connection('mysql')->statement("INSERT INTO u".$store.".trn_buydown (buydown_name, buydown_code, buydown_amount, start_date, end_date, status, SID) VALUES ('".$input['buydown_name']."', '".$input['buydown_code']."', '".$input['buydown_to_amt']."', '".$from_date."', '".$to_date."', 'Active', '".$store."') ");
                    $buydown_id = DB::connection('mysql')->select("select * from u".$store.".trn_buydown order by buydown_id DESC LIMIT 1  ");
                    if(isset($buydown_id[0])){
                        $buy_id = $buydown_id[0]->buydown_id;
                    }
                    for ($i = 0; $i < count($input['added_buydown_barcode']); $i++) {
                        $costafter  = $input['added_buydown_before_inovice'][$i] - $input['added_buydown_amt'][$i];
                        $priceafter = $input['added_buydown_before_price'][$i]   - $input['added_buydown_amt'][$i];
                        
                        $check_sql_query = "SELECT CONCAT(bd.vitemname,'[',bd.vbarcode,']') item_name FROM u".$store.".trn_buydown b LEFT JOIN u".$store.".trn_buydown_details bd ON(b.buydown_id = bd.buydown_id) WHERE b.status = 'Active' AND bd.vbarcode = '" .$input['added_buydown_barcode'][$i]. "' ";
                        $mysql_result = DB::connection('mysql')->select($check_sql_query);
                        if (count($mysql_result) > 0) {
                            $item_name = $mysql_result[0]->item_name;
                        } else {
                            $item_name = '';
                        } 
                        
                        if ($item_name == '') {
                            $items  = DB::connection('mysql')->select("select * from u".$store.".mst_item where vbarcode = '".$input['added_buydown_barcode'][$i]."' ");
                            
                            if(count($items) > 0){
                                DB::connection('mysql')->statement("INSERT INTO u".$store.".trn_buydown_details  
                                (buydown_id, vitemname, vbarcode, invoice_cost, price_before_buydown, buydown_amount, cost_after_buydown, price_after_buydown, SID) 
                                VALUES('".$buy_id."', '".$input['added_buydown_name'][$i]."', '".$input['added_buydown_barcode'][$i]."','".$input['added_buydown_before_inovice'][$i]."', '".$input['added_buydown_before_price'][$i]."',
                                '".$input['added_buydown_amt'][$i]."', '".$costafter."', '".$priceafter."', '".$store."') ");
                            }
                        }
                    }
                    
                } else {
                    session()->put('error', 'No Items are selected');
                }
            }
            return redirect('buydown')->with('message', 'New Buydown Created Successfully');
        }else {
            if (isset($input['added_buydown_barcode']) && !empty($input['added_buydown_barcode'])) {
    
                $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_from_date'] ))) ;
                $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_to_date']))) ;
    
                $buydown = BuyDown::create([
                    'buydown_name'      => $input['buydown_name'],
                    'buydown_code'      => $input['buydown_code'],
                    'buydown_amount'    => $input['buydown_to_amt'],
                    'start_date'        => $from_date,
                    'end_date'          => $to_date,
                    'status'            => 'Active',
                    'SID'               => session()->get('sid')
                ]);
    
                for ($i = 0; $i < count($input['added_buydown_barcode']); $i++) {
                    $costafter  = $input['added_buydown_before_inovice'][$i] - $input['added_buydown_amt'][$i];
                    $priceafter = $input['added_buydown_before_price'][$i]   - $input['added_buydown_amt'][$i];
                    $buydetails = BuyDownDetail::create([
                        'buydown_id'            => $buydown->buydown_id,
                        'vitemname'             => $input['added_buydown_name'][$i],
                        'vbarcode'              => $input['added_buydown_barcode'][$i],
                        'invoice_cost'          => $input['added_buydown_before_inovice'][$i],
                        'price_before_buydown'  => $input['added_buydown_before_price'][$i],
                        'buydown_amount'        => $input['added_buydown_amt'][$i],
                        'cost_after_buydown'    => $costafter,
                        'price_after_buydown'   => $priceafter,
                        'SID'                   => session()->get('sid')
                    ]);
                }
                return redirect('buydown')->with('message', 'New Buydown Created Successfully');
            } else {
                session()->put('error', 'No Items are selected');
            }
        }
    }

    public function edit(Request $request, $buydown_id)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();

        $url = '';
        $item_groups = ItemGroup::orderBy('vitemgroupname', 'ASC')->get()->toArray();
        $data['itemGroups'] = $item_groups;
        $data['get_customers_url']                  = url('buydown/get_customers');
        $data['get_items_url']                      = url('buydown/get_items');
        $data['get_categories_url']                 = url('buydown/get_item_categories');
        $data['get_sub_categories_url']             = url('buydown/get_sub_categories_url');
        $data['get_department_items_url']           = url('buydown/get_department_items');
        $data['get_category_items_url']             = url('buydown/get_category_items');
        $data['get_sub_category_items_url']         = url('buydown/get_sub_category_items');
        $data['get_selected_buy_items_url']         = url('buydown/getSelectedBuyItems');
        $data['get_saved_buy_items_ajax_url']       = url('buydown/getSavedItemsAjax');

        $data['get_selected_discounted_items_url']  = url('buydown/getSelectedDiscountedItems');
        $data['get_saved_discounted_items_ajax_url'] = url('buydown/getSelectedDiscountedItemsAjax');
        $data['get_group_items_url']                = url('buydown/get_group_items');

        $data['validate_item_url']                  = url('buydown/validate_item');
        $data['cancel']                             = url('buydown_list');
        $data['searchitem']                         = url('buydown/search');

        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        $data['departments'] = $departments;
        $departments_html = "";
        $departments_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 85px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($departments as $department) {
            if (isset($vdepcode) && $vdepcode == $department['vdepcode']) {
                $departments_html .= "<option value='" . $department['vdepcode'] . "' selected='selected'>" . $department['vdepartmentname'] . "</option>";
            } else {
                $departments_html .= "<option value='" . $department['vdepcode'] . "'>" . $department['vdepartmentname'] . "</option>";
            }
        }
        $departments_html .= "</select>";

        $data['departments'] = $departments_html;

        $item_types = ['Standard', 'Kiosk', 'Lot Matrix', 'Lottery'];

        $item_type_html = "";
        $item_type_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($item_types as $item_type) {
            $item_type_html .= "<option value='" . $item_type . "'>" . $item_type . "</option>";
        }
        $item_type_html .= "</select>";

        $data['item_types'] = $item_type_html;


        $suppliers = Vendor::orderBy('vcompanyname', 'ASC')->get()->toArray();
        $supplier_html = "";
        $supplier_html = "<select class='form-control' name='supplier_code' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($suppliers as $supplier) {
            $supplier_html .= "<option value='" . $supplier['vsuppliercode'] . "'>" . $supplier['vcompanyname'] . "</option>";
        }
        $supplier_html .= "</select>";

        $data['suppliers'] = $supplier_html;

        $manufacturers = Manufacturer::orderBy('mfr_name', 'ASC')->get()->toArray();
        $manufacturer_html = "";
        $manufacturer_html = "<select class='form-control' name='manufacturer_id' id='manufacturer_id' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($manufacturers as $manufacurer) {
            $manufacturer_html .= "<option value='" . $manufacurer['mfr_id'] . "'>" . $manufacurer['mfr_name'] . "</option>";
        }
        $manufacturer_html .= "</select>";

        $data['manufacturers'] = $manufacturer_html;

        // $units = $this->model_administration_items->getItemUnits();
        $units = Unit::orderBy('vunitname', 'ASC')->get()->toArray();
        $units_html = "";
        $units_html = "<select class='form-control' name='unit_id' id='unit_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($units as $unit) {
            $units_html .= "<option value='" . $unit['vunitcode'] . "'>" . $unit['vunitname'] . "</option>";
        }
        $units_html .= "</select>";

        $data['units'] = $units_html;

        // $sizes = $this->model_administration_items->getItemSize();
        $sizes = Size::orderBy('vsize', 'ASC')->get()->toArray();
        $size_html = "";
        $size_html = "<select class='form-control' name='size_id' id='size_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($sizes as $size) {
            $size_html .= "<option value='" . $size['vsize'] . "'>" . $size['vsize'] . "</option>";
        }
        $size_html .= "</select>";

        $data['size'] = $size_html;


        //==================================== for price filter ====================================================

        $price_select_by_list = array(
            'greater'   => 'Greater than',
            'less'      => 'Less than',
            'equal'     => 'Equal to',
            'between'   => 'Between'
        );

        $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='width:90px; color:#000000'>";
        foreach ($price_select_by_list as $k => $v) {
            $price_select_by_html .= "<option value='" . $k . "'";

            if (isset($data['price_select_by']) && $k === $data['price_select_by']) {
                $price_select_by_html .= " selected";
            }

            $price_select_by_html .= ">" . $v . "</option>";
        }
        $price_select_by_html .= "</select>";
        $price_select_by_html .= "<span id='selectByValuesSpan'>";

        if (isset($input['price_select_by']) && $input['price_select_by'] === 'between') {
            // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Amt' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
        } else {
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter Amt' style='width:70px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
        }

        $price_select_by_html .= "</span>";


        $data['price'] = $price_select_by_html;


        /********************/

        if (isset($this->error['warning'])) {

            $data['error_warning'] = $this->error['warning'];
        } elseif (isset($this->session->data['error'])) {

            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $buydown_inf = BuyDown::where('buydown_id','=', $buydown_id)->get();
        $buydown_items = BuyDownDetail::where('buydown_id', '=', $buydown_id)->get();
       

        $buydown_info = $buydown_inf[0];

        return view('items.buydownedit', compact('data', 'buydown_info', 'buydown_items'));

    }

    public function getSavedItemsAjax(Request $request)
    {
        //  $this->load->model('administration/buydown');
        $input = $request->all();
        $buydown_id= $input['buydown_id'];



        $results1 = BuyDown::first()->getSavedBuyItems($buydown_id);
      

        $row = '';
        foreach($results1 as $results ){
                $row .= "<tr>";
                $row .= "<td><input type='checkbox' class='buydown_items' name='added_buydown_items[]' data-vbarcode=".$results->vbarcode." value=".$results->iitemid."></td>";
                $row .= "<td>".$results->vitemname."<input type='hidden' name='added_buydown_name[]' value='".$results->vitemname."'></td>";
                $row .= "<td>".$results->vbarcode." <input type='hidden' name='added_buydown_barcode[]' value=".$results->vbarcode."></td>";
                $row .= "<td class='live_cost'>".number_format((float)$results->live_cost, 2, '.', '')."</td>";
                $row .= "<td class='live_price'>".number_format((float)$results->live_price, 2, '.', '')."</td>";
                $row .= "<td class='invoce'>".number_format((float)$results->invoice_cost, 2, '.', '')."<input type='hidden' name='added_buydown_before_inovice[]' value=".$results->invoice_cost."></td>";
                $row .= "<td class='price'>".number_format((float)$results->live_price, 2, '.', '')." <input type='hidden' name='added_buydown_before_price[]' value=".$results->price_before_buydown."></td>";
                $row .= "<td><input type='text' class='buydown_amt' id='buydown_row' name='added_buydown_amt[]' value=".$results->buydown_amount." ></td>";
                $row .= "<td class='cost_after_buydown'>". number_format((float)($results->live_cost-$results->buydown_amount), 2, '.', '') ." </td>";
                $row .= "<td class='price_after_buydown'>". number_format((float)($results->live_price-$results->buydown_amount), 2, '.', '') ."</td>";
                $row .= "</tr>";
        }
        echo $row;
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $buydown_id = $input['buydown_id'];
        $buydown = DB::connection("mysql_dynamic")->select("SELECT * FROM trn_buydown where buydown_id = '".(int)$buydown_id."' ");
        if(isset($buydown[0])){
            $buydown_name = $buydown[0]->buydown_name;
            $buydown_code = $buydown[0]->buydown_code;
        }
        $new_items = $old_items =  array();
        
        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            }
            foreach($stores as $store){
                $dup_query = "SELECT * FROM u".$store.".trn_buydown where buydown_name = '".$buydown_name."' AND buydown_code = '".$buydown_code."' ";
                
                $buy_down_id = DB::connection("mysql")->select($dup_query);
                if(isset($buy_down_id[0])){
                    $buyID = $buy_down_id[0]->buydown_id;
                }
               
                $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_from_date']))) ;
                $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_to_date'])));
                DB::connection('mysql')->statement("UPDATE u".$store.".trn_buydown SET 
                                        buydown_name =  '".$input['buydown_name']."',
                                        buydown_amount    = '".$input['buydown_to_amt']."',
                                        start_date        = '".$from_date."',
                                        end_date         = '".$to_date."',
                                        status            = '".$input['status']."'  
                                    where  buydown_id = '".$buyID."' ");
                
            
                $new_items = $input['added_buydown_barcode'];
                
                
                $old_items= DB::connection('mysql')->select("SELECT vbarcode FROM u".$store.".trn_buydown_details WHERE buydown_id = '".$buyID."'"); //old items barcode
    
                $old_items1 = array();
                foreach($old_items as $k => $v){
                    $old_items1[$k] = $v->vbarcode;
                }
    
                $result = array_diff($old_items1,$new_items);
                $result_new = array_diff($new_items,$old_items1);
                
                foreach($result as $key=>$val){
                    $query = DB::connection('mysql')->select("SELECT buydown_details_id FROM u".$store.".trn_buydown_details WHERE buydown_id = '".$buyID."' AND vbarcode ='".$val."'" );
                    $insert_delete_query = "INSERT INTO u".$store.".mst_delete_table (TableName, TableId, Action, LastUpdate, SID) 
                                                        VALUES('trn_buydown_details', '". (int)$query[0]->buydown_details_id . "', 'delete', '".date('Y-m-d H:i:s')."', '".$store."')";
                    $query =  DB::connection('mysql')->insert($insert_delete_query);
                    
                    $query = DB::connection('mysql')->delete("DELETE FROM u".$store.".trn_buydown_details WHERE buydown_id = '".$buyID."' AND vbarcode ='".$val."'" );
                }
                
                $invoice_cost_before = $selling_price_before=$buydown_amount=$barcodes=$itemnames=array();
    
                $invoice_cost_before=$input['added_buydown_before_inovice'];
                $selling_price_before=$input['added_buydown_before_price'];
                $buydown_amount=$input['added_buydown_amt'];
                $barcodes=$input['added_buydown_barcode'];
                $itemnames=$input['added_buydown_name'];
        
                $old_items_after_delete = DB::connection('mysql')->select("SELECT vbarcode FROM u".$store.".trn_buydown_details WHERE buydown_id = '".$buyID."'");
                $old_items_after_delete1 = array();
                foreach($old_items_after_delete as $k => $v){
                    $old_items_after_delete1[$k] = $v->vbarcode;
                }
        
        
                foreach($barcodes as $key=>$val){
                    $costafter=$invoice_cost_before[$key]-$buydown_amount[$key];
                    $priceafter=$selling_price_before[$key]-$buydown_amount[$key];
                    if(($k = array_search($val, $old_items_after_delete1) !== false)){
                        DB::connection('mysql')->statement("UPDATE u".$store.".trn_buydown_details SET 
                            invoice_cost          = '".$invoice_cost_before[$key]."',
                            price_before_buydown  = '".$selling_price_before[$key]."',
                            buydown_amount        = '".$buydown_amount[$key]."',
                            cost_after_buydown    = '".$costafter."',
                            price_after_buydown   = '".$priceafter."' WHERE vbarcode = '".$val."' AND buydown_id = '".$buyID."'  ");
                        
                    }else{
                        $check_sql_query = "SELECT CONCAT(bd.vitemname,'[',bd.vbarcode,']') item_name FROM u".$store.".trn_buydown b LEFT JOIN u".$store.".trn_buydown_details bd ON(b.buydown_id = bd.buydown_id) WHERE b.status = 'Active' AND bd.vbarcode = '" .$barcodes[$key]. "' ";
                        $mysql_result = DB::connection('mysql')->select($check_sql_query);
                        if (count($mysql_result) > 0) {
                            $item_name = $mysql_result[0]->item_name;
                        } else {
                            $item_name = '';
                        } 
                        
                        if ($item_name == '') {
                            $items  = DB::connection('mysql')->select("select * from u".$store.".mst_item where vbarcode = '".$barcodes[$key]."' ");
                            
                            if(count($items) > 0){
                                DB::connection('mysql')->statement("INSERT INTO u".$store.".trn_buydown_details  (buydown_id, vitemname, vbarcode, invoice_cost, price_before_buydown, buydown_amount, cost_after_buydown, price_after_buydown, SID) 
                                VALUES('".$buyID."', '".$itemnames[$key]."', '".$barcodes[$key]."','".$invoice_cost_before[$key]."', '".$selling_price_before[$key]."',
                                '".$buydown_amount[$key]."', '".$costafter."', '".$priceafter."', '".$store."') ");
                            }
                        }
                        // $buydetails = BuyDownDetail::create([
                        //     'buydown_id'            => $buydown_id,
                        //     'vitemname'             => $itemnames[$key],
                        //     'vbarcode'              => $barcodes[$key],
                        //     'invoice_cost'          => $invoice_cost_before[$key],
                        //     'price_before_buydown'  => $selling_price_before[$key],
                        //     'buydown_amount'        => $buydown_amount[$key],
                        //     'cost_after_buydown'    => $costafter,
                        //     'price_after_buydown'   => $priceafter,
                        //     'SID'                   => session()->get('sid')
                        // ]);
                    }
                }
        
                $update_query_time = "UPDATE u".$store.".trn_buydown_details SET LastUpdate =now()  WHERE buydown_id = '".$buyID."'";
               
                $query =  DB::connection('mysql')->update($update_query_time);
                
                $update_query_time2 = "UPDATE  u".$store.".trn_buydown SET LastUpdate =now()  WHERE buydown_id = '".$buyID."'";
                $query =  DB::connection('mysql')->update($update_query_time2);
                    
            }
            return redirect('buydown')->with('message', 'Buydown Updated Successfully');
            
            
        } else {
            $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_from_date']))) ;
            $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$input['buydown_to_date']))) ;
    
            $buydown_details = BuyDown::where('buydown_id', '=', $buydown_id)->update([
                                        'buydown_name'      => $input['buydown_name'],
                                        'buydown_amount'    => $input['buydown_to_amt'],
                                        'start_date'        => $from_date,
                                        'end_date'          => $to_date,
                                        'status'            => $input['status'],
                                ]);
            $new_items = $input['added_buydown_barcode'];
            $old_items= DB::connection('mysql_dynamic')->select("SELECT vbarcode FROM trn_buydown_details WHERE buydown_id = '".$buydown_id."'"); //old items barcode
    
            $old_items1 = array();
            foreach($old_items as $k => $v){
                $old_items1[$k] = $v->vbarcode;
            }
    
            $result = array_diff($old_items1,$new_items);
            $result_new = array_diff($new_items,$old_items1);
            
    
            foreach($result as $key=>$val){
                $query = DB::connection('mysql_dynamic')->select("SELECT buydown_details_id FROM trn_buydown_details WHERE buydown_id = '".$buydown_id."' AND vbarcode ='".$val."'" );
                $insert_delete_query = "INSERT INTO mst_delete_table (TableName, TableId, Action, LastUpdate, SID) 
                                                    VALUES('trn_buydown_details', '". (int)$query[0]->buydown_details_id . "', 'delete', '".date('Y-m-d H:i:s')."', '".session()->get('sid')."')";
                $query =  DB::connection('mysql_dynamic')->insert($insert_delete_query);
                
                $query = DB::connection('mysql_dynamic')->delete("DELETE FROM trn_buydown_details WHERE buydown_id = '".$buydown_id."' AND vbarcode ='".$val."'" );
            }
    
            $invoice_cost_before = $selling_price_before=$buydown_amount=$barcodes=$itemnames=array();
    
            $invoice_cost_before=$input['added_buydown_before_inovice'];
            $selling_price_before=$input['added_buydown_before_price'];
            $buydown_amount=$input['added_buydown_amt'];
            $barcodes=$input['added_buydown_barcode'];
            $itemnames=$input['added_buydown_name'];
    
            $old_items_after_delete = DB::connection('mysql_dynamic')->select("SELECT vbarcode FROM trn_buydown_details WHERE buydown_id = '".$buydown_id."'");
            $old_items_after_delete1 = array();
            foreach($old_items_after_delete as $k => $v){
                $old_items_after_delete1[$k] = $v->vbarcode;
            }
    
    
            foreach($barcodes as $key=>$val){
                $costafter=$invoice_cost_before[$key]-$buydown_amount[$key];
               $priceafter=$selling_price_before[$key]-$buydown_amount[$key];
               if(($k = array_search($val, $old_items_after_delete1) !== false)){
                    $buydetails = BuyDownDetail::where([['vbarcode', '=', $val], ['buydown_id', '=', $buydown_id]])->update([
                        'invoice_cost'          => $invoice_cost_before[$key],
                        'price_before_buydown'  => $selling_price_before[$key],
                        'buydown_amount'        => $buydown_amount[$key],
                        'cost_after_buydown'    => $costafter,
                        'price_after_buydown'   => $priceafter
                    ]);
                }else{
                    $buydetails = BuyDownDetail::create([
                        'buydown_id'            => $buydown_id,
                        'vitemname'             => $itemnames[$key],
                        'vbarcode'              => $barcodes[$key],
                        'invoice_cost'          => $invoice_cost_before[$key],
                        'price_before_buydown'  => $selling_price_before[$key],
                        'buydown_amount'        => $buydown_amount[$key],
                        'cost_after_buydown'    => $costafter,
                        'price_after_buydown'   => $priceafter,
                        'SID'                   => session()->get('sid')
                    ]);
                }
            }
    
            $update_query_time = "UPDATE trn_buydown_details SET LastUpdate =now()  WHERE buydown_id = '".$buydown_id."'";
           
            $query =  DB::connection('mysql_dynamic')->update($update_query_time);
            
            $update_query_time2 = "UPDATE trn_buydown SET LastUpdate =now()  WHERE buydown_id = '".$buydown_id."'";
            $query =  DB::connection('mysql_dynamic')->update($update_query_time2);
                
            return redirect('buydown')->with('message', 'Buydown Updated Successfully');
        }
        
    }

    // public function deletebuydown(Request $request)
    // {
    //     $delId = $request->all();
    //     
    //     for ($i = 0; $i < count($delId['selected']); $i++) {
            
    //         BuyDown::find($delId['selected'][$i])->delete();
    //         BuyDownDetail::where('buydown_details_id', $delId['selected'][$i])->delete();
    //     }
    //     return redirect('/buydown')->with('message', 'Buydown Deleted Successfully!');
    // }
    //completely Wrong  it will not delete trn_buydown_details items it wil effect sync and buydown items 
    
    public function deletebuydown(Request $request)
    {
        $delId = $request->all();
        if(isset($delId['stores_hq'])){
            if($delId['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $delId['stores_hq']);
            }
            foreach($delId['selected'] as $buydown){
                $itemgroupname = DB::connection("mysql_dynamic")->select("SELECT * FROM trn_buydown where buydown_id = '".$buydown."' ");
                if(isset($itemgroupname[0])){
                    $buydown_name = $itemgroupname[0]->buydown_name;
                    $buydown_code = $itemgroupname[0]->buydown_code;
                }
                
                foreach($stores as $store){
                    $dup_query = "SELECT * FROM u".$store.".trn_buydown where buydown_name = '".$buydown_name."' AND buydown_code = '".$buydown_code."' ";
                    $dup_entry = DB::connection("mysql")->select($dup_query);
                    if(isset($dup_entry[0])){
                        $buydown_id =  $dup_entry[0]->buydown_id;
                    }
                    
                    $select_query = "SELECT * FROM u".$store.".trn_buydown_details WHERE  buydown_id = '" . (int)$buydown_id . "'";
                    $query = \DB::connection('mysql')->select($select_query);
                    
                    if(count($query)>0){
                        foreach($query as $depromo){
                            $insert_delete_trn_buydown_details = "INSERT INTO u".$store.".mst_delete_table SET  TableName = 'trn_buydown_details', Action = 'delete', TableId = '" . (int)$depromo->buydown_details_id . "', SID = '" . (int)($store)."'";
                            $query =  DB::connection('mysql')->insert($insert_delete_trn_buydown_details);
                        }
                    }
                    DB::connection('mysql')->statement("DELETE FROM u".$store.".trn_buydown WHERE  buydown_id = '" . (int)$buydown_id . "' ");
                    DB::connection('mysql')->statement("DELETE FROM u".$store.".trn_buydown_details WHERE  buydown_id = '" . (int)$buydown_id . "' ");
                    $insert_delete_trn_buydown= "INSERT INTO u".$store.".mst_delete_table SET  TableName = 'trn_buydown', Action = 'delete', TableId = '" . (int)$buydown_id . "',SID = '" . (int)($store)."'";
                    $query =  DB::connection('mysql')->insert($insert_delete_trn_buydown);
                }
            }
            return redirect('/buydown')->with('message', 'Buydown Deleted Successfully!');
        }else{
            for ($i = 0; $i < count($delId['selected']); $i++) {
                //complete delete Buydown   every item PK(buydown_details_id) inserting in mst_delete_table
                $select_query = "SELECT * FROM trn_buydown_details WHERE  buydown_id = '" . (int)$delId['selected'][$i] . "'";
                $query = \DB::connection('mysql_dynamic')->select($select_query);
                
                if(count($query)>0){
                    foreach($query as $depromo){
                      
                      $insert_delete_trn_buydown_details = "INSERT INTO mst_delete_table SET  TableName = 'trn_buydown_details', Action  = 'delete', TableId = '" . (int)$depromo->buydown_details_id . "',SID = '" . (int)(session()->get('sid'))."'";
                     
                      $query =  DB::connection('mysql_dynamic')->insert($insert_delete_trn_buydown_details);
                      
                    }
                }
                BuyDown::find($delId['selected'][$i])->delete();
                BuyDownDetail::where('buydown_id', $delId['selected'][$i])->delete();
                $insert_delete_trn_buydown= "INSERT INTO mst_delete_table SET  TableName = 'trn_buydown', Action  = 'delete', TableId = '" . (int)$delId['selected'][$i] . "',SID = '" . (int)(session()->get('sid'))."'";
                $query =  DB::connection('mysql_dynamic')->insert($insert_delete_trn_buydown);
            }
            return redirect('/buydown')->with('message', 'Buydown Deleted Successfully!');
        }
    }
    
    
    public function editbuydownhqcheck(Request $request){
        $input = $request->all();
        $avalable = array();
        $stores = session()->get('stores_hq');
        $itemgroupname = DB::connection("mysql_dynamic")->select("SELECT * FROM trn_buydown where buydown_id = '".$input['buy_down']."' ");
        if(isset($itemgroupname[0])){
            $buydown_name = $itemgroupname[0]->buydown_name;
            $buydown_code = $itemgroupname[0]->buydown_code;
        }
        foreach($stores as $store){
            if(isset($itemgroupname[0])){
                $dup_query = "SELECT * FROM u".$store->id.".trn_buydown where buydown_name = '".$buydown_name."' AND buydown_code = '".$buydown_code."' ";
                $dup_entry = DB::connection("mysql")->select($dup_query);
                
                if(count($dup_entry) == 0){
                  
                    array_push($avalable, $store->id); 
                }
            }
        }
        
        return $avalable;
    }
    public function duplicatehqbuydown(Request $request){
        $input = $request->all();
        $avalable = array();
        $stores = session()->get('stores_hq');
        foreach($input as $buydown){
            $itemgroupname = DB::connection("mysql_dynamic")->select("SELECT * FROM trn_buydown where buydown_id = '".$buydown."' ");
            
            if(isset($itemgroupname[0])){
                $buydown_name = $itemgroupname[0]->buydown_name;
                $buydown_code = $itemgroupname[0]->buydown_code;
            }
            foreach($stores as $store){
                if(isset($itemgroupname[0])){
                    $dup_query = "SELECT * FROM u".$store->id.".trn_buydown where buydown_name = '".$buydown_name."' AND buydown_code = '".$buydown_code."' ";
                    $dup_entry = DB::connection("mysql")->select($dup_query);
                    
                    if(count($dup_entry) == 0){
                      
                        array_push($avalable, $store->id); 
                    }
                }
            }
        }
        return $avalable;
    }
       
}
