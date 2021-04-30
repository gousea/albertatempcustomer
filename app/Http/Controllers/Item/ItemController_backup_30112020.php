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
use App\Model\ItemMovement;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index($show_items, $sort_items, Request $request)
    {
        
        $input = $request->all();
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => 'Home',
            'href' => url('/dashboard')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => 'Items',
            'href' => url('/items/item_list')
        );
        
        if(isset($show_items)){
            $disable_items =  $show_items;
        }else{
            $disable_items = 'Active';
        }
        $data['show_items'] = $disable_items;
        
        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        
        $departments_html ="";
        $departments_html = "<select class='form-control' name='dept_code' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach($departments as $department){
            if(isset($vdepcode) && $vdepcode == $department['vdepcode']){
                $departments_html .= "<option value='".$department['vdepcode']."' selected='selected'>".$department['vdepartmentname']."</option>";
            } else {
                $departments_html .= "<option value='".$department['vdepcode']."'>".$department['vdepartmentname']."</option>";
            }
        }
        $departments_html .="</select>";
        
        $data['departments'] = $departments_html;
        
        
        $itemListings = WebAdminSetting::where('variablename', 'ItemListing')->get('variablevalue')->toArray();
        
        $data['itemListings'] = array();
            
        // if(!empty($itemListings[0]['variablevalue']) && count(json_decode($itemListings[0]['variablevalue'])) > 0){
        if(!empty($itemListings[0]['variablevalue'])){
            $data['itemListings'] = json_decode($itemListings[0]['variablevalue']);
        }

        if(isset($sort_items)){
            $sort_items =  $sort_items;
        }else{
            $sort_items = 'DESC';
        }

        $data['add'] = url('/item/add');
        $data['edit'] = url('/item/edit');
        $data['delete'] = url('/item/delete');
        // $data['edit_list'] = url('/items/edit_list');
        $data['searchitem'] = url('/item/search/'.$disable_items.'/'.$sort_items);
        // $data['parent_child_search'] = url('/items/parent_child_search');
        $data['current_url'] = url('/item/item_list');
        $data['import_items'] = url('/item/import_items');
        $data['get_barcode'] = url('/item/get_barcode');
        $data['reorder_point_url'] = url('/item/calculateROP');
        
        $data['get_categories_url'] = url('/item/get_department_categories');
        $data['get_department_items_url'] = url('/item/get_department_items');

        if(isset($sort_items) && $sort_items != ''){
            // if($sort_items == 'DESC'){
            //     $sort_items = 'ASC';
            // }else{
            //     $sort_items = 'DESC';
            // }
            $sort_items = 'DESC';
        }else{
            $sort_items = 'DESC';
        }

        // dd($disable_items);
        $data['item_sorting'] = url('/item/item_list/'.$disable_items.'/'.$sort_items);
        

        $data['title_arr'] = array(
                                'webstore' => 'Web Store',
                                'vitemtype' => 'Item Type',
                                'vitemcode' => 'Item Code',
                                'vitemname' => 'Item Name',
                                'vunitcode' => 'Unit',
                                'vbarcode' => 'SKU',
                                'vpricetype' => 'Price Type',
                                'vcategorycode' => 'Category',
                                'vdepcode' => 'Dept.',
                                'vsuppliercode' => 'Supplier',
                                'iqtyonhand' => 'Qty. on Hand',
                                'ireorderpoint' => 'Reorder Point',
                                'dcostprice' => 'Case Cost',
                                'dunitprice' => 'Price',
                                'nsaleprice' => 'Sale Price',
                                'nlevel2' => 'Level 2 Price',
                                'nlevel3' => 'Level 3 Price',
                                'nlevel4' => 'Level 4 Price',
                                'iquantity' => 'Quantity',
                                'ndiscountper' => 'Discount(%)',
                                'ndiscountamt' => 'Discount Amt',
                                'vtax1' => 'Tax 1',
                                'vtax2' => 'Tax 2',
                                'vtax3' => 'Tax 3',
                                'vfooditem' => 'Food Item',
                                'vdescription' => 'Description',
                                'dlastsold' => 'Last Old',
                                'visinventory' => 'Inventory Item',
                                'dpricestartdatetime' => 'Price Start Time',
                                'dpriceenddatetime' => 'Price End Time',
                                'estatus' => 'Status',
                                'nbuyqty' => 'Buy Qty',
                                'ndiscountqty' => 'Discount Qty',
                                'nsalediscountper' => 'Sales Discount',
                                'vshowimage' => 'Show Image',
                                'itemimage' => 'Image',
                                'vageverify' => 'Age Verification',
                                'ebottledeposit' => 'Bottle Deposit',
                                'nbottledepositamt' => 'Bottle Deposit Amt',
                                'vbarcodetype' => 'Barcode Type',
                                'ntareweight' => 'Tare Weight',
                                'ntareweightper' => 'Tare Weight Per',
                                'dcreated' => 'Created',
                                'dlastupdated' => 'Last Updated',
                                'dlastreceived' => 'Last Received',
                                'dlastordered' => 'Last Ordered',
                                'nlastcost' => 'Last Cost',
                                'nonorderqty' => 'On Order Qty',
                                'vparentitem' => 'Parent Item',
                                'nchildqty' => 'Child Qty',
                                'vsize' => 'Size',
                                'npack' => 'Unit Per Case',
                                'nunitcost' => 'Unit Cost',
                                'ionupload' => 'On upload',
                                'nsellunit' => 'Selling Unit',
                                'ilotterystartnum' => 'Lottery Start Num',
                                'ilotteryendnum' => 'Lottery End Num',
                                'etransferstatus' => 'Transfer status',
                                'vsequence' => 'Sequence',
                                'vcolorcode' => 'Color Code',
                                'vdiscount' => 'Discount',
                                'norderqtyupto' => 'Order Qty Upto',
                                'vshowsalesinzreport' => 'Sales Item',
                                'iinvtdefaultunit' => 'Invt. Default Unit',
                                'stationid' => 'Station',
                                'shelfid' => 'Shelf',
                                'aisleid' => 'Aisle',
                                'shelvingid' => 'Shelving',
                                'rating' => 'Rating',
                                'vintage' => 'Vintage',
                                'PrinterStationId' => 'Printer Station Id',
                                'liability' => 'Liability',
                                'isparentchild' => 'is parent child',
                                'parentid' => 'parent id',
                                'parentmasterid' => 'parent master id',
                                'wicitem' => 'wic item',
                                'gross_profit'=>'Gross Profit',
                                );

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
                        
                
        return view('items.item_list',compact('data'));
                
    }

    public function search($show_items, $sort_items, Request $request)
    {
        ini_set('memory_limit', '256M');
        
        $input = $request->all();
        
        $return = $datas = array();
        $sort = "mi.LastUpdate DESC";
        
        if(isset($sort_items) && !empty(trim($sort_items)))
        {
            $sort_by = trim($sort_items);
            $sort = "mi.LastUpdate $sort_by";
        }
        else
        {
            $sort = "mi.LastUpdate DESC";
        }
        
        if(isset($input['sorting_value']) && $input['sorting_value'] != 'default'){
            $sort = "mi.vitemname ".$input['sorting_value'];
        }else{
            $sort = $sort;
        }
        
        $show_condition = "WHERE mi.estatus='Active'";
        if(isset($show_items) && !empty(trim($show_items)))
        {
            $show_items = trim($show_items);
            if($show_items == "All")
            {
                $show_condition = "WHERE mi.estatus !=''";
            }
            else
            {
                $show_condition = "WHERE mi.estatus='".$show_items."'";
            }
            
        }
        
        $search_value = $input['columns'];
        
        $search_itmes = [];
        foreach($search_value as $value)
        {
            if($value["data"] == "vitemname")
            {
                // $search_items['vitemname'] = $value['search']['value'];
                
                $search_items['vitemname'] = htmlspecialchars_decode($value['search']['value']);
                
            }
            else if($value["data"] == "vbarcode")
            {
                $search_items['vbarcode'] = $value['search']['value'];
            }
            else if($value["data"] == "vcategoryname")
            {
                $search_items['vcategoryname'] = $value['search']['value'];
            }
            else if($value["data"] == "vdepartmentname")
            {
                $search_items['vdepartmentname'] = $value['search']['value'];
            }
            else if($value["data"] == "vcompanyname")
            {
                $search_items['vcompanyname'] = $value['search']['value'];
                
                //print_r($search_items['vcompanyname']);die;
            }
            else if($value["data"] == "iitemid_v")
            {
                $search_items['iitemid_v'] = $value['search']['value'];
                
                //print_r($search_items['vitemcode']);die;
            }
        }
        
        if(empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && isset($search_items['vcompanyname']) && empty(trim($search_items['vcompanyname'])) && empty(trim($search_items['vcategoryname'])) && empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && empty(trim($search_items['vcompanyname'])) && empty(trim($search_items['vcategoryname'])) &&  empty(trim($search_items['vdepartmentname'])))
        {
            $limit = 20;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length'];
      
            // $select_query = "SELECT mi.iitemid, mi.vbarcode, mi.vitemname, mi.vitemtype, mi.dcostprice,
            // mi.npack, mi.new_costprice, mi.nunitcost, mi.webstore, mi.vitemcode, mi.vpricetype,
            // mi.ireorderpoint,
            // GROUP_CONCAT(DISTINCT miv.vvendoritemcode) vvendoritemcode, 
            // MAX(md.vdepartmentname) vdepartmentname, MAX(mc.vcategoryname) vcategoryname, mi.nsaleprice, 
            // mi.dunitprice, mi.iqtyonhand, mi.LastUpdate,MAX(msupp.vcompanyname) vcompanyname,
            $select_query = "SELECT mi.*,
            GROUP_CONCAT(DISTINCT miv.vvendoritemcode) vvendoritemcode, 
            md.vdepartmentname vdepartmentname, mc.vcategoryname vcategoryname, msupp.vcompanyname vcompanyname,
            case isparentchild when 0 then mi.vitemname 
                when 1 then Concat(mi.vitemname,' [Child]') 
                when 2 then  Concat(mi.vitemname,' [Parent]') 
            end   as VITEMNAME FROM mst_item as mi 
            
            LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
            LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
            LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
            LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
            LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
            
            $show_condition 
            group by mi.iitemid
            ORDER BY $sort LIMIT ". $start_from.", ".$limit;
     
            // echo $select_query;exit;
            // DB::connection('mysql_dynamic')->select("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
            $query = DB::connection('mysql_dynamic')->select($select_query);
            
            
            $count_query = "SELECT COUNT(DISTINCT(iitemid)) as total_rows FROM mst_item mi $show_condition";
            
            $run_count_query = DB::connection('mysql_dynamic')->select($count_query);
            
            $count_records = $count_total = $run_count_query[0]->total_rows;
            // dd($count_records);
        }
        else
        {
            
           $limit = 20;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length']; 
            $condition = "";
            if(isset($search_items['vitemname']) && !empty(trim($search_items['vitemname']))){
                $search = $search_items['vitemname'];
                //$condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
                
                $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search' ");
                
                
                 if(isset($parentid) && !empty($parentid)){
                 $parentid_data=$parentid[0]->parentid;
                 $child_data=$parentid[0]->iitemid;
                 $isparentchild=$parentid[0]->isparentchild;
                 $condition .= " AND mi.vitemname = '$search'  OR mi.iitemid=$parentid_data OR mi.parentid=$child_data";
                 if($parentid_data>0 && $isparentchild == 1)
                 {
                 $sort = " mi.parentid DESC";
                 }
                 else{
                  $sort = " mi.isparentchild DESC";   
                 }
                 
                 }
                 else{
                     $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'"; 
                 }
                
                 
            }
            
            if(isset($search_items['vbarcode']) && !empty(trim($search_items['vbarcode']))){
            
                $search = $search_items['vbarcode'];
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%' OR mia.valiassku LIKE '%" . $search . "%' OR miv.vvendoritemcode LIKE  '%" . $search . "%'";

            }
             if(isset($search_items['iitemid_v']) && !empty(trim($search_items['iitemid_v']))){
            
                $search = $search_items['iitemid_v'];
                
                $condition .= " AND miv.vvendoritemcode LIKE  '%" . $search . "%'";
                //  $condition .= " AND  mv.vvendoritemcode ='".$search."'";

            }
            
            if(isset($search_items['vdepartmentname']) && !empty(trim($search_items['vdepartmentname']))){
                $search = $search_items['vdepartmentname'];
                if($search != 'all')
                {
                    $condition .= " AND mi.vdepcode ='".$search."'";
                }
                
            }
            
            if(isset($search_items['vcategoryname']) && !empty($search_items['vcategoryname'])){
                $search = $search_items['vcategoryname'];
                if($search != 'all')
                {
                    $condition .= " AND mi.vcategorycode ='".$search."'";
                }
                
            }
            if(isset($search_items['vcompanyname']) && !empty($search_items['vcompanyname'])){
                $search = $search_items['vcompanyname'];
                if($search != 'all')
                {
                    $condition .= " AND msupp.vcompanyname LIKE  '%" . $search . "%'";
                    //echo $condition;die;
                }
                
            }
            
                // $select_query = "SELECT mi.iitemid,mi.vbarcode,mi.vitemname, mi.vitemtype,mi.dcostprice,mi.npack,
                //                 mi.new_costprice,mi.nunitcost, md.vdepcode, MAX(md.vdepartmentname) vdepartmentname, 
                //                 MAX(mc.vcategoryname) vcategoryname, mi.nsaleprice, mi.vitemcode, mi.vpricetype,
                //                 mi.dunitprice, mi.iqtyonhand, mi.LastUpdate, mi.webstore, mi.ireorderpoint,
                // $select_query = "SELECT mi.*,
                //             GROUP_CONCAT(DISTINCT miv.vvendoritemcode) vvendoritemcode,
                //             MAX(msupp.vcompanyname) vcompanyname, md.vdepartmentname vdepartmentname, mc.vcategoryname vcategoryname,
                //             case 
                //                 isparentchild when 0 then mi.vitemname 
                //                 when 1 then Concat(mi.vitemname,' [Child]') 
                //                 when 2 then Concat(mi.vitemname,' [Parent]') 
                //             end as VITEMNAME 
                            
                //             FROM mst_item as mi 
                            
                //             LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode)
                //             LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                //             LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid)
                //             LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
                //             LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                                
                //             $show_condition "." $condition 
                //             group by mi.iitemid
                //             ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
                
                  $select_query = "SELECT mi.*,
                            GROUP_CONCAT(DISTINCT miv.vvendoritemcode) vvendoritemcode,
                            MAX(msupp.vcompanyname) vcompanyname, md.vdepartmentname vdepartmentname, mc.vcategoryname vcategoryname,
                            case 
                                mi.isparentchild when 0 then mi.vitemname 
                                when 1 then Concat(mi.vitemname,' [Child]') 
                                when 2 then Concat(mi.vitemname,' [Parent]') 
                            end as VITEMNAME ,
                           
                            case 
                                mi.isparentchild when 1 then p.iqtyonhand 
                                
                                end as PQOH
                            
                            FROM mst_item as mi 
                            
                            LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode)
                            LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
                            LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid)
                            LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
                            LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
                            LEFT JOIN mst_item p ON mi.parentid = p.iitemid
                            
                            
                           
        
                            $show_condition "." $condition 
                            group by mi.iitemid
                            ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
            
            
            $query = DB::connection('mysql_dynamic')->select($select_query);
            
            $count_select_query = "SELECT COUNT(mi.iitemid) as count FROM mst_item as mi 
            LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
            LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
            LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
            LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
            LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
            
            $show_condition "." $condition group by mi.iitemid";
            
             
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            
            $count_records = $count_total = (int)count($count_query);
        }
        
        $search = $input['search']['value'];

        $itemListings = WebAdminSetting::where('variablename', 'ItemListing')->get('variablevalue')->toArray();
        
        $data['itemListings'] = array();
    
        // if(!empty($itemListings[0]['variablevalue']) && count(json_decode($itemListings[0]['variablevalue'])) > 0){
        if(!empty($itemListings[0]['variablevalue'])){
            $data['itemListings'] = json_decode($itemListings[0]['variablevalue']);
        }
        
        if(count($query) > 0){
        
            $query = array_map(function ($value) {
                return (array)$value;
            }, $query);
            
            foreach ($query as $key => $value) {
                
                $temp = array();
                $temp['iitemid'] = $value['iitemid'];
                $temp['iitemid_v'] = $value['vvendoritemcode'];
                $temp['vitemname'] = $value['VITEMNAME'];
                $temp['vitemtype'] = $value['vitemtype'];
                $temp['vbarcode'] = $value['vbarcode'];
               
                
                if(count($itemListings) > 0){
                    foreach($data['itemListings'] as $m => $v){ 
                        if($m == 'vdepcode'){
                            $temp['vdepcode'] = isset($value['vdepcode'])?$value['vdepcode']:'';
                        }else if($m == 'vcategorycode'){
                            $temp['vcategorycode'] = isset($value['vcategorycode'])?$value['vcategorycode']:'';
                        }else if($m == 'vunitcode'){
                           $temp['vunitcode'] = $value['vunitcode'];
                        }else if($m == 'vsuppliercode'){
                            $temp['vsuppliercode'] = isset($value['vsuppliercode'])?$value['vsuppliercode']:'';
                        }else if($m == 'stationid'){
                            $temp['stationid'] = $value['stationid'];
                        }else if($m == 'aisleid'){
                            $temp['aisleid'] = $value['aisleid'];
                        }else if($m == 'shelfid'){
                            $temp['shelfid'] = $value['shelfid'];
                        }else if($m == 'shelvingid'){
                            $temp['shelvingid'] = $value['shelvingid'];
                        }elseif($m == 'gross_profit'){
                            
                            if(isset($value['new_costprice']) && $value['new_costprice'] >0 && isset($value['dunitprice'])){    
                                 
                                $nunit_cost = $value['new_costprice']/$value['nsellunit'];
                                $nunit_cost = round($nunit_cost, 2);
                                
                                if(isset($value['ndiscountper'])){
                                    $percent = $value['dunitprice'] - ($nunit_cost-$value['ndiscountper']);
                                }else{
                                    $percent = $value['dunitprice'] - $nunit_cost;
                                }
                                
                                $percent = $percent > 0?$percent:0;
                                
                                if($percent > 0){
                                    $percent = $percent;
                                }else{
                                    $percent = 0;
                                }
                                
                                if($value['dunitprice'] == 0 || $value['dunitprice'] == '0.0000'){
                                    $dunitprice = 1;
                                }else{
                                    $dunitprice = $value['dunitprice'];
                                }
                                
                                $percent = (($percent/$dunitprice) * 100);
                                $percent = number_format((float)$percent, 2, '.', '');
            
                                }else{
                                  $percent = 0.00;
                                }
                            $temp[$m] = $percent;
                        }else{ 
                            $temp[$m] = isset($value[$m])?$value[$m]:'';
                        }
                    }
                }
                $temp['vcompanyname'] = $value['vcompanyname'];
                $temp['vdepartmentname'] = $value['vdepartmentname'];
                $temp['vcategoryname'] = $value['vcategoryname'];
                $temp['nsaleprice'] = $value['nsaleprice'];
                $temp['dunitprice'] = $value['dunitprice'];
                //$temp['iqtyonhand'] = $value['iqtyonhand'];
                // if($value['npack']==0){
                //     dd($value['iitemid']);
                // }
                if($value['PQOH'] !='NULL' && isset($value['PQOH'])){
                 $temp['iqtyonhand'] = ($value['PQOH'] % $value['npack']);   
                }
                else{
                    // if($value['npack']!=0){
                    //     $quotient = (int)($value['iqtyonhand'] / $value['npack']);
                    //     $remainder = $value['iqtyonhand'] % $value['npack'];
                    //     $qty_on_hand = ''.$quotient .' ('.$remainder.')';
                        
                    //     $temp['iqtyonhand'] = $qty_on_hand;
                        
                    // }
                    // else{
                    //     $quotient = (int)($value['iqtyonhand']);
                    //     $remainder = $value['iqtyonhand'] % $value['npack'];
                    //     $qty_on_hand = ''.$quotient .' ('.$remainder.')';
                        
                    //     $temp['iqtyonhand'] = $qty_on_hand;   
                    // }
                    
                    //To ensure that the code does not break when npack is zero
                    $quotient = (int)$value['npack'] !== 0?(int)($value['iqtyonhand'] / $value['npack']):0;
                    $remainder = (int)$value['npack'] !== 0?$value['iqtyonhand'] % $value['npack']:0;
                    $qty_on_hand = ''.$quotient .' ('.$remainder.')';
                    
                    $temp['iqtyonhand'] = $qty_on_hand;
                    //$temp['iqtyonhand'] = $value['iqtyonhand'];
                }
                // $temp['dcostprice']=number_format(($value['new_costprice']*$value['npack']),2);
                $temp['dcostprice']=number_format(($value['nunitcost']*$value['npack']),2);
                // $temp['nunitcost']=number_format($value['nunitcost'],2);
                $temp['nunitcost']=number_format(($value['new_costprice']/$value['nsellunit']),2);
                
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

    public function get_department_categories(Request $request)
    {
        $input = $request->all();
        
        if (isset($input['dep_code'])  && $input['dep_code'] != "") {
            $mst_category = new Category();
            $categories = $mst_category->get_department_categories($input['dep_code']);
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

    // public function delete(Request $request) {
        
    //     $data = array();

    //     if ($request->isMethod('post')) {

    //         $temp_arr = json_decode(file_get_contents('php://input'), true);
            
    //         $item = new Item();
            
    //         $data = $item->deleteItems($temp_arr);
            
    //         return response(json_encode($data), 200)
    //               ->header('Content-Type', 'application/json');
            
    //         exit;
            
    //     }else{
    //         $data['error'] = 'Something went wrong';
    //         // http_response_code(401);
    //         return response(json_encode($data), 401)
    //               ->header('Content-Type', 'application/json');
    //         exit;
    //     }
    // }
    
    public function delete(Request $request) {
        $input = $request->all();
        $data = array();
        if ($request->isMethod('post')) {
            $temp_arr = $input['itemid'];
            $stores_hq = $input['stores_hq'];
            $item = new Item();
            $data = $item->deleteItems($temp_arr, $stores_hq);
            return response(json_encode($data), 200)
                ->header('Content-Type', 'application/json');
            exit;  
        }else{
            $data['error'] = 'Something went wrong';
            // http_response_code(401);
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
            exit;
        }
    }
    
    public function checkVendorExists(Request $request){
        $input = $request->all();
        $vsuppliercode = $input[0];
        if($vsuppliercode){
            $vcompanyNmae = DB::connection('mysql_dynamic')->select("Select vcompanyname From mst_supplier where vsuppliercode = '".$vsuppliercode."' ");
            foreach($vcompanyNmae as $comp){
                $companyname = $comp->vcompanyname;
            }
            $stores = session()->get('stores_hq');
            $notExistStore = [];
            foreach($stores as $store){
                $dup_query = "Select vcompanyname from u".$store->id.".mst_supplier where  vcompanyname = '".$companyname."' ";
                $dup_entry = DB::connection("mysql")->select($dup_query);
                if(count($dup_entry) == 0){
                    array_push($notExistStore, $store->id); 
                }
            }
        }
        return $notExistStore;
    }
    
    public function addVendorsFromitems(Request $request){
        $input = $request->all();
        $stores = $input['stores_hq'];
        $vsuppliercode = $input['vendorecode'];
        if($vsuppliercode){
            $vendor_details = DB::connection('mysql_dynamic')->select("Select * From mst_supplier where vsuppliercode = '".$vsuppliercode."' ");
            foreach($stores as $store){
                $insert_vendor_sql = "INSERT INTO u".$store.".mst_supplier (vcompanyname, vvendortype, vfnmae, vlname, vcode,
                                vaddress1, vcity, vstate, vphone, vzip, vcountry, vemail, plcbtype, edi, estatus, SID) 
                                VALUES ( '".$vendor_details[0]->vcompanyname."',
                                '".$vendor_details[0]->vvendortype."', '".$vendor_details[0]->vfnmae."', '".$vendor_details[0]->vlname."', '".$vendor_details[0]->vcode."', '".$vendor_details[0]->vaddress1."', 
                                '".$vendor_details[0]->vcity."', '".$vendor_details[0]->vstate."', '".$vendor_details[0]->vphone."', '".$vendor_details[0]->vzip."', '".$vendor_details[0]->vcountry."', '".$vendor_details[0]->vemail."', '".$vendor_details[0]->plcbtype."',
                                '".$vendor_details[0]->edi."', '".$vendor_details[0]->estatus."', '".$store."' ) ";
                            
                $vendor = DB::connection("mysql")->select($insert_vendor_sql);
                $sup_query = "Select isupplierid from u".$store.".mst_supplier ORDER BY  isupplierid DESC LIMIT 1";
                $sup_id = DB::connection("mysql")->select($sup_query);
                
                for($j=0; $j<count($sup_id); $j++){
                    $isuppliercode = $sup_id[$j]->isupplierid;   
                }
                $update_query = "UPDATE u".$store.".mst_supplier SET vsuppliercode = '".$isuppliercode."' WHERE isupplierid = '".$isuppliercode."' ";
                DB::connection("mysql")->select($update_query);
            }
            // $msg = "sucessfully added the vendors";
        }
            
        return true;
        
    }
    
    public function duplicatehqitemvendorassign(Request $request){
        $input = $request->all();
        
        $vsuppliercode = $input['ivendorid'];
        if($vsuppliercode){
            $vcompanyNmae = DB::connection('mysql_dynamic')->select("Select vcompanyname From mst_supplier where vsuppliercode = '".$vsuppliercode."' ");
            
            $stores = session()->get('stores_hq');
            
            $notExistStore = [];
            foreach($stores as $store){
                $dup_query = "Select vcompanyname from u".$store->id.".mst_supplier where  vcompanyname = '".$vcompanyNmae[0]->vcompanyname."' ";
                $dup_entry = DB::connection("mysql")->select($dup_query);
                if(count($dup_entry) == 0){
                    array_push($notExistStore, $store->id); 
                }
            }
        }
        return $notExistStore;
    }
    
    
    public function hqItems(Request $request){
        $input = $request->all();
        $avalable = array();
        $stores = session()->get('stores_hq');
        foreach($stores as $store){
           for($i=0; $i<count($input['dataItemOrders']); $i++){
                $sku = DB::connection('mysql_dynamic')->select("Select vbarcode FROM mst_item WHERE iitemid='".$input['dataItemOrders'][$i]."' ");
                for($a=0; $a<count($sku); $a++){
                    $dup_query = "Select vbarcode from u".$store->id.".mst_item where  vbarcode = '".$sku[$a]->vbarcode."' ";
                    $dup_entry = DB::connection("mysql")->select($dup_query);
                    if(count($dup_entry) == 0){
                        array_push($avalable, $store->id); 
                    }
                }      
           }
          
        }
       return $avalable;
    }
    
    public function checkVendorExistsFromlist(Request $request){
        $input = $request->all();
        $vendors = $input['vendor_ids'];
        $notExistStore = [];
        $stores = session()->get('stores_hq');
        foreach($stores as $store){
            foreach($vendors as $vendor_id){
                $vendoritemcode = DB::connection('mysql_dynamic')->select("Select vvendoritemcode From mst_itemvendor where Id = '".$vendor_id."' ");
              
                foreach($vendoritemcode as $ivendoritemcode){
                    $dup_query = "Select Id from u".$store->id.".mst_itemvendor where  vvendoritemcode = '".$ivendoritemcode->vvendoritemcode."' ";
                    $dup_entry = DB::connection("mysql")->select($dup_query);
                }    
            }
            if(count($dup_entry) == 0){
                array_push($notExistStore, $store->id); 
            }
        }
        return $notExistStore;
    }


    public function add_form(Request $request)
    {        
        $input = $request->all();
                
        $data = $this->getForm($input);

        return view('items.item_form',compact('data'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            
            $input = $request->all();
            $data = $this->getForm($input);
            $check = true;
            $new_database = session()->get('new_database');
            if($new_database === false){
                // =============================================================== OLD DATABASE ====================================

                // if (!$this->user->hasPermission('modify', 'administration/items')) {
                //     $this->error['warning'] = $this->language->get('error_permission');
                // }

                if (($input['vbarcode'] == '')) {
                    $data['error_vbarcode'] = 'Please Enter SKU';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_vbarcode'] = '';
                }

                if (($input['new_costprice'] == '')) {
                    $data['error_new_costprice'] = 'Please Enter Cost';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_new_costprice'] = '';
                }

                if (($input['dunitprice'] == '')) {
                    $data['error_dunitprice'] = 'Please Enter Selling Price';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_dunitprice'] = '';
                }
                
                            
                if($input['vitemtype'] != 'Instant'){
                    
                    if (($input['vitemname'] == '')) {
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $data['error_vitemname'] = 'Please Enter Item Name';
                        $check = false;
                    } else {
                        $data['error_vitemname'] = '';
                    }
                    
                    if (($input['vunitcode'] == '')) {
                        $data['error_vunitcode'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vunitcode'] = '';
                    }
                    
                    if (($input['vsuppliercode'] == '')) {
                        $data['error_vsuppliercode'] = 'Please Select Supplier';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vsuppliercode'] = '';
                    }
                    
                    if (($input['vdepcode'] == '')) {
                        $data['error_vdepcode']= 'Please Select Department';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }else{
                        $data['error_vdepcode'] = '';
                    }
                        
                    if (isset($input['vcategorycode']) && ($input['vcategorycode'] == '' || $input['vcategorycode'] == '--Select Category--')) {
                        $data['error_vcategorycode'] = 'Please Select Category';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vcategorycode'] = '';
                    }
                }else{
                    
                    if (($input['ticket_name'] == '')) {
                        $data['error_ticket_name'] = 'Please Enter Item Name';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_name'] = '';
                    }
                    
                    if (($input['book_cost'] == '')) {
                        $data['error_book_cost'] = 'Please Enter Book Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_book_cost'] = '';
                    }
                    
                    if (($input['ticket_price'] == '')) {
                        $data['error_ticket_price'] = 'Please Item Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_price'] = '';
                    }
                }
                    
                if($input['vbarcode']){

                    $olditem = new Olditem();
                    $unique_sku = $olditem->getSKU($input['vbarcode']);
                                //   print_r($unique_sku); die;

                        if(count($unique_sku) > 0){
                            $data['error_vbarcode'] = 'SKU Already Exist';
                            $data['error_warning'] = 'Please check the form carefully for errors!';
                            $check = false;
                        }
                }

                if (isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1) {
                    
                    if (($input['unit_id'] == '')) {
                        $data['error_unit_id'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_id'] = '';
                    }

                    if (($input['unit_value'] == '')) {
                        $data['error_unit_value'] = 'Please Enter Unit Value';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_value'] = '';
                    }

                    if (($input['bucket_id'] == '')) {
                        $data['error_bucket_id'] = 'Please Select Bucket';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_bucket_id'] = '';
                    }
                }

                
                // if ($this->error && !isset($this->error['warning'])) {
                //     $this->error['warning'] = $this->language->get('error_warning');
                // }
                
            
            } else {
                // =============================================================== NEW DATABASE ====================================
                
                // if (!$this->user->hasPermission('modify', 'administration/items')) {
                //     $this->error['warning'] = $this->language->get('error_permission');
                // }

                if (($input['vbarcode'] == '')) {
                    $data['error_vbarcode'] = 'Please Enter SKU';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_vbarcode'] = '';
                }
                
                            
                if($input['vitemtype'] != 'Instant'){
                    
                    if (($input['vitemname'] == '')) {
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $data['error_vitemname'] = 'Please Enter Item Name';
                        $check = false;
                    } else {
                        $data['error_vitemname'] = '';
                    }
                    
                    if (($input['vunitcode'] == '')) {
                        $data['error_vunitcode'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vunitcode'] = '';
                    }
                    
                    if (($input['vsuppliercode'] == '')) {
                        $data['error_vsuppliercode'] = 'Please Select Supplier';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vsuppliercode'] = '';
                    }
                    
                    if (($input['vdepcode'] == '')) {
                        $data['error_vdepcode']= 'Please Select Department';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }else{
                        $data['error_vdepcode'] = '';
                    }
                        
                    if (isset($input['vcategorycode']) && ($input['vcategorycode'] == '' || $input['vcategorycode'] == '--Select Category--')) {
                        $data['error_vcategorycode'] = 'Please Select Category';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vcategorycode'] = '';
                    }
                    
                    // if (($input['subcat_id'] == '')) {
                    //     $data['error_subcat_id'] = 'Please Select Sub Category';
                    //     $data['error_warning'] = 'Please check the form carefully for errors!';
                    //     $check = false;
                    // } else {
                    //     $data['error_subcat_id'] = '';
                    // }
                    
                    if (($input['new_costprice'] == '')) {
                        $data['error_new_costprice'] = 'Please Enter Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_new_costprice'] = '';
                    }
    
                    if (($input['dunitprice'] == '')) {
                        $data['error_dunitprice'] = 'Please Enter Selling Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_dunitprice'] = '';
                    }
                }else{
                    
                    if (($input['ticket_name'] == '')) {
                        $data['error_ticket_name'] = 'Please Enter Ticket Name';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_name'] = '';
                    }
                    
                    if (($input['book_cost'] == '')) {
                        $data['error_book_cost'] = 'Please Enter Book Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_book_cost'] = '';
                    }
                    
                    if (($input['ticket_price'] == '')) {
                        $data['error_ticket_price'] = 'Please Ticket Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_price'] = '';
                    }
                }
                    
                if($input['vbarcode']){

                    $item = new Item();
                    $unique_sku = $item->getSKU($input['vbarcode']);
                                //   print_r($unique_sku); die;

                        if(count($unique_sku) > 0){
                            $data['error_vbarcode'] = 'SKU Already Exist';
                            $data['error_warning'] = 'Please check the form carefully for errors!';
                            $check = false;
                        }
                }

                if (isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1) {
                    
                    if (($input['unit_id'] == '')) {
                        $data['error_unit_id'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_id'] = '';
                    }

                    if (($input['unit_value'] == '')) {
                        $data['error_unit_value'] = 'Please Enter Unit Value';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_value'] = '';
                    }

                    if (($input['bucket_id'] == '')) {
                        $data['error_bucket_id'] = 'Please Select Bucket';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_bucket_id'] = '';
                    }
                }
                
            }
                
            if($check == false){
                // print_r("aa");
                // dd($data);
                return view('items.item_form',compact('data'));

            }elseif($check == true){
                // print_r("bb");
                // dd($data);
                $new_database = session()->get('new_database');

                if($new_database === false){
                    // =============================================================== OLD DATABASE ======================================================
                    if($request->hasFile('itemimage') && $request->file('itemimage')->getClientOriginalName() != ''){
                        $img_string = file_get_contents($request->file('itemimage')->getRealPath());
                        // $img_string = base64_encode($img_string);
                    }else{
                        $img_string = NULL;
                    }
        
                    
                    if(isset($input['vtax']) && $input['vtax'] != ''){
                        
                        $vtax = $input['vtax'];
                        
                        if($vtax == 'vtax1'){
                            $vtax1 = 'Y';
                        }else{
                            $vtax1 = 'N';
                        }
                        
                        if($vtax == 'vtax2'){
                            $vtax2 = 'Y';
                        }else{
                            $vtax2 = 'N';
                        }
                        
                        if($vtax == 'vtax3'){
                            $vtax3 = 'Y';
                        }else{
                            $vtax3 = 'N';
                        }
                        
                        if($vtax == 'vnotax'){
                            $vtax1 = 'N';
                            $vtax2 = 'N';
                            $vtax3 = 'N';
                        }
                        
                    }else{
                        $vtax1 = 'N';
                        $vtax2 = 'N';
                        $vtax3 = 'N';
                    }
                    

                    if(isset($input['npack']) && $input['npack'] == ''){
                        $npack = '1';
                    }else{
                        $npack = $input['npack'];
                    }
        
                    if(isset($input['nsellunit']) && $input['nsellunit'] == ''){
                        $nsellunit = '1';
                    }else{
                        $nsellunit = $input['nsellunit'];
                    }
        

        
                    if(isset($input['nbottledepositamt']) && ($input['nbottledepositamt'] == '0.00' || $input['nbottledepositamt'] == '')){
                        $nbottledepositamt = '0.00';
                        $ebottledeposit = 'No';
                    }else{
                        $nbottledepositamt = (float)$input['nbottledepositamt'];
                        $ebottledeposit = 'Yes';
                    }
        
                    if(isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1){
        
                        $options_data['unit_id'] = $input['unit_id'];
                        $options_data['unit_value'] = $input['unit_value'];
                        $options_data['bucket_id'] = $input['bucket_id'];
                        if(isset($input['malt']) && $input['malt'] == 1){
                            $options_data['malt'] = $input['malt'];
                        }else{
                            $options_data['malt'] = 0;
                        }
        
                    }else{
                        $options_data = array();
                    }
        
                    if($input['vitemtype'] == 'Instant'){
                        
                        if(isset($input['games_per_book']) && $input['games_per_book'] == ''){
                            $npack = '1';
                        }else{
                            $npack = $input['games_per_book'];
                        }
                        
                        if(isset($input['book_qoh']) && $input['book_qoh'] == ''){
                            $iqtyonhand = '0';
                        }else{
                            $iqtyonhand = $input['book_qoh'];
                        }
                        
                        $liability = 'N';
                        $estatus = 'Active';
                        $visinventory = 'Yes';
                        
                        $temp_arr = array(
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['ticket_name']),
                                        "vbarcode" => $input['vbarcode'],
                                        "iqtyonhand" => $iqtyonhand,
                                        "dcostprice" => $input['book_cost'],
                                        "dunitprice" => $input['ticket_price'],
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "npack" => $npack,
                                        "visinventory" => $visinventory,
                                        "liability" => $liability,
                                        "vtax1" => 'N',
                                        "vtax2" => 'N',
                                        "vtax3" => 'N',
                                        "estatus" => $estatus
                                    );
                                
                        // $this->model_api_olditems->addLotteryItems($temp_arr);
                        $olditem = new Olditem();   
                        $check_error = $olditem->addLotteryItems($temp_arr);
                                    
                    }else{
                        
                        $liability = 'Y';
                        $visinventory = 'Yes';
                        
                        $dcostprice = $input['new_costprice'];
                        // $nunitcost = $dcostprice/$npack;
                        $nunitcost = $dcostprice/$nsellunit;
                        
                        $temp_arr = array(
                                        // "iitemgroupid" => $input['iitemgroupid'],''
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        "webstore" => "0",
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['vitemname']),
                                        "vunitcode" => $input['vunitcode'],
                                        "vbarcode" => $input['vbarcode'],
                                        "vpricetype" => "",
                                        "vcategorycode" => $input['vcategorycode'],
                                        "vdepcode" => $input['vdepcode'],
                                        "subcat_id" => $input['subcat_id'],
                                        "vsuppliercode" => $input['vsuppliercode'],
                                        "iqtyonhand" => $input['iqtyonhand'],
                                        "ireorderpoint" => $input['ireorderpoint'],
                                        "reorder_duration" => $input['reorder_duration'],
                                        "manufacturer_id" => $input['manufacturer_id'],
                                        // "ireorderpointdays" => $input['ireorderpointdays'],
                                        "new_costprice" => $input['new_costprice'],
                                        // "dcostprice" => $input['dcostprice'],
                                        "dcostprice" => $dcostprice,
                                        "dunitprice" => $input['dunitprice'],
                                        "nsaleprice" => '',
                                        "nlevel2" => $input['nlevel2'],
                                        "nlevel3" => $input['nlevel3'],
                                        "nlevel4" => $input['nlevel4'],
                                        "iquantity" => "0",
                                        "ndiscountper" => $input['ndiscountper'],
                                        "ndiscountamt" => "0.00",
                                        "vtax1" => $vtax1,
                                        "vtax2" => $vtax2,
                                        "vtax3" => $vtax3,
                                        "vfooditem" => $input['vfooditem'],
                                        "vdescription" => htmlspecialchars_decode($input['vdescription']),
                                        "dlastsold" => null,
                                        "visinventory" => $visinventory,
                                        "dpricestartdatetime" => null,
                                        "dpriceenddatetime" => null,
                                        "estatus" => $input['estatus'],
                                        "nbuyqty" => "0",
                                        "ndiscountqty" => "0",
                                        "nsalediscountper" => "0.00",
                                        "vshowimage" => $input['vshowimage'],
                                        "itemimage" => $img_string,
                                        "vageverify" => $input['vageverify'],
                                        "ebottledeposit" => $ebottledeposit,
                                        "nbottledepositamt" => $nbottledepositamt,
                                        "vbarcodetype" => $input['vbarcodetype'],
                                        "ntareweight" => "0.00",
                                        "ntareweightper" => "0.00",
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "dlastreceived" => null,
                                        "dlastordered" => null,
                                        "nlastcost" => "0.00",
                                        "nonorderqty" => "0",
                                        "vparentitem" => "0",
                                        "nchildqty" => "0.00",
                                        "vsize" => $input['vsize'],
                                        "npack" => $npack,
                                        "nunitcost" => $nunitcost,
                                        "ionupload" => "0",
                                        "nsellunit" => $nsellunit,
                                        "ilotterystartnum" => "0",
                                        "ilotteryendnum" => "0",
                                        "etransferstatus" => "",
                                        // "vsequence" => $input['vsequence'],
                                        // "vcolorcode" => $input['vcolorcode'],
                                        "vcolorcode" => 'None',
                                        "vdiscount" => $input['vdiscount'],
                                        "norderqtyupto" => $input['norderqtyupto'],
                                        // "vshowsalesinzreport" => $input['vshowsalesinzreport'],
                                        "iinvtdefaultunit" => "0",
                                        "stationid" => $input['stationid'],
                                        "shelfid" => $input['shelfid'],
                                        "aisleid" => $input['aisleid'],
                                        "shelvingid" => $input['shelvingid'],
                                        // "rating" => $input['rating'],
                                        // "vintage" => $input['vintage'],
                                        "PrinterStationId" => "0",
                                        "liability" => $liability,
                                        "isparentchild" => "0",
                                        "parentid" => "0",
                                        "parentmasterid" => "0",
                                        "wicitem" => $input['wicitem'],
                                        "options_data" => $options_data,
                                        
                                    );
                                    
                        
                        $olditem = new Olditem();   
                        $check_error = $olditem->addItems($temp_arr);
                    }
        
                    // 			$this->model_api_olditems->addItems($temp_arr);
                    if(isset($check_error['error_tax3'])){
                        session()->put('error_warning', $check_error['error_tax3']);
                        // echo 1525; die;
                        $data = $this->getForm($input);

                        return view('items.item_form',compact('data'));
                    }else{
                        session()->put('success', 'Successfully Added Item');
                    }
        
                                       
                    if(isset($check_error['error_tax3'])){
                        session()->put('error_warning', $check_error['error_tax3']);
                        // echo 1525; die;
                        $data = $this->getForm($input);

                        return view('items.item_form',compact('data'));
                    }else{
                        $url = '/item/item_list/Active/DESC';
                        return redirect($url);
                    }

                } else { 
                    // =============================================================== NEW DATABASE ======================================================
                    if($request->hasFile('itemimage') && $request->file('itemimage')->getClientOriginalName() != ''){
                        $img_string = file_get_contents($request->file('itemimage')->getRealPath());
                        // $img_string = base64_encode($img_string);
                    }else{
                        $img_string = NULL;
                    }
                    
                                        
                    if(isset($input['vtax']) && $input['vtax'] != ''){
                        
                        $vtax = $input['vtax'];
                        
                        if($vtax == 'vtax1'){
                            $vtax1 = 'Y';
                        }else{
                            $vtax1 = 'N';
                        }
                        
                        if($vtax == 'vtax2'){
                            $vtax2 = 'Y';
                        }else{
                            $vtax2 = 'N';
                        }
                        
                        if($vtax == 'vtax3'){
                            $vtax3 = 'Y';
                        }else{
                            $vtax3 = 'N';
                        }
                        
                        if($vtax == 'vnotax'){
                            $vtax1 = 'N';
                            $vtax2 = 'N';
                            $vtax3 = 'N';
                        }
                        
                    }else{
                        $vtax1 = 'N';
                        $vtax2 = 'N';
                        $vtax3 = 'N';
                    }
                    
                
                    if(isset($input['npack']) && $input['npack'] == ''){
                        $npack = '1';
                    }else{
                        $npack = $input['npack'];
                    }
        
                    if(isset($input['nsellunit']) && $input['nsellunit'] == ''){
                        $nsellunit = '1';
                    }else{
                        $nsellunit = $input['nsellunit'];
                    }
        
                    
                    if(isset($input['nbottledepositamt']) && ($input['nbottledepositamt'] == '0.00' || $input['nbottledepositamt'] == '')){
                        $nbottledepositamt = '0.00';
                        $ebottledeposit = 'No';
                    }else{
                        $nbottledepositamt = (float)$input['nbottledepositamt'];
                        $ebottledeposit = 'Yes';
                    }
        
                    if(isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1){
        
                        $options_data['unit_id'] = $input['unit_id'];
                        $options_data['unit_value'] = $input['unit_value'];
                        $options_data['bucket_id'] = $input['bucket_id'];
                        if(isset($input['malt']) && $input['malt'] == 1){
                            $options_data['malt'] = $input['malt'];
                        }else{
                            $options_data['malt'] = 0;
                        }
                        
                    }else{
                        $options_data = array();
                    }
                    
                    $check_error='';

                    if($input['vitemtype'] == 'Instant'){
                        
                        /*====== games_per_book = npack and book_qoh = QOH only for Lotter items =========
                            In this case if book_qoh = 5 and games_per_book = 50
                            then total games = 250
                        */
                        if(isset($input['games_per_book']) && $input['games_per_book'] == ''){
                            $npack = '1';
                        }else{
                            $npack = $input['games_per_book'];
                        }
                        
                        if(isset($input['book_qoh']) && $input['book_qoh'] == ''){
                            $iqtyonhand = '0';
                        }else{
                            $iqtyonhand = $input['book_qoh'];
                        }
                        
                        $liability = 'N';
                        $estatus = 'Active';
                        $visinventory = 'Yes';
                        
                        $temp_arr = array(
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['ticket_name']),
                                        "vbarcode" => $input['vbarcode'],
                                        "iqtyonhand" => $iqtyonhand,
                                        "dcostprice" => $input['book_cost'],
                                        "dunitprice" => $input['ticket_price'],
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "npack" => $npack,
                                        "visinventory" => $visinventory,
                                        "liability" => $liability,
                                        "vtax1" => 'N',
                                        "vtax2" => 'N',
                                        "vtax3" => 'N',
                                        "estatus" => $estatus
                                    );
                        // dd($temp_arr);     
                        $item = new Item();
                        $item->addLotteryItems($temp_arr);
                                    
                    }else{
                        
                        // $liability = 'Y';
                        $liability = 'N';
                        $visinventory = 'Yes';
                        // dd($input['new_costprice']);
                        $dcostprice = $input['new_costprice'];
                        // $nunitcost = $dcostprice/$npack;
                        $nunitcost = $dcostprice/$nsellunit;
                        
                        $temp_arr = array(
                                        // "iitemgroupid" => $input['iitemgroupid'],
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        
                                        "webstore" => "0",
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['vitemname']),
                                        "vunitcode" => $input['vunitcode'],
                                        "vbarcode" => $input['vbarcode'],
                                        "vpricetype" => "",
                                        "vcategorycode" => $input['vcategorycode'],
                                        "vdepcode" => $input['vdepcode'],
                                        "subcat_id" => $input['subcat_id'],
                                        "vsuppliercode" => $input['vsuppliercode'],
                                        "iqtyonhand" => $input['iqtyonhand'],
                                        "ireorderpoint" => $input['ireorderpoint'],
                                        "reorder_duration" => $input['reorder_duration'],
                                        "manufacturer_id" => $input['manufacturer_id'],
                                        // "ireorderpointdays" => $input['ireorderpointdays'],
                                        "new_costprice" => $input['new_costprice'],
                                        // "dcostprice" => $input['dcostprice'],
                                        "dcostprice" => $dcostprice,
                                        "dunitprice" => $input['dunitprice'],
                                        "nsaleprice" => 0,
                                        "nlevel2" => $input['nlevel2'] ?? 0,
                                        "nlevel3" => $input['nlevel3'] ?? 0,
                                        "nlevel4" => $input['nlevel4'] ?? 0,
                                        "iquantity" => "0",
                                        "ndiscountper" => $input['ndiscountper'],
                                        "ndiscountamt" => "0.00",
                                        "vtax1" => $vtax1,
                                        "vtax2" => $vtax2,
                                        "vtax3" => $vtax3,
                                        "vfooditem" => $input['vfooditem'],
                                        "vdescription" => htmlspecialchars_decode($input['vdescription']),
                                        "dlastsold" => null,
                                        "visinventory" => $visinventory,
                                        "dpricestartdatetime" => null,
                                        "dpriceenddatetime" => null,
                                        "estatus" => $input['estatus'],
                                        "nbuyqty" => "0",
                                        "ndiscountqty" => "0",
                                        "nsalediscountper" => "0.00",
                                        "vshowimage" => $input['vshowimage'],
                                        "itemimage" => $img_string,
                                        "vageverify" => $input['vageverify'],
                                        "ebottledeposit" => $ebottledeposit,
                                        "nbottledepositamt" => $nbottledepositamt,
                                        "vbarcodetype" => $input['vbarcodetype'],
                                        "ntareweight" => "0.00",
                                        "ntareweightper" => "0.00",
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "dlastreceived" => null,
                                        "dlastordered" => null,
                                        "nlastcost" => "0.00",
                                        "nonorderqty" => "0",
                                        "vparentitem" => "0",
                                        "nchildqty" => "0.00",
                                        "vsize" => $input['vsize'],
                                        "npack" => $npack,
                                        "nunitcost" => $nunitcost,
                                        "ionupload" => "0",
                                        "nsellunit" => $nsellunit,
                                        "ilotterystartnum" => "0",
                                        "ilotteryendnum" => "0",
                                        "etransferstatus" => "",
                                        // "vsequence" => $input['vsequence'],
                                        // "vcolorcode" => $input['vcolorcode'],
                                        "vcolorcode" => 'None',
                                        "vdiscount" => $input['vdiscount'],
                                        "norderqtyupto" => $input['norderqtyupto'],
                                        // "vshowsalesinzreport" => $input['vshowsalesinzreport'],
                                        "iinvtdefaultunit" => "0",
                                        "stationid" => $input['stationid'],
                                        "shelfid" => $input['shelfid'],
                                        "aisleid" => $input['aisleid'],
                                        "shelvingid" => $input['shelvingid'],
                                        // "rating" => $input['rating'],
                                        // "vintage" => $input['vintage'],
                                        "PrinterStationId" => "0",
                                        "liability" => $liability,
                                        "isparentchild" => "0",
                                        "parentid" => "0",
                                        "parentmasterid" => "0",
                                        "wicitem" => $input['wicitem'],
                                        "options_data" => $options_data,
                                        
                                    );
                        // dd($temp_arr);      
                        $item = new Item();   
                        $check_error = $item->addItems($temp_arr);
                    }
                    // dd($check_error);
                    // $this->model_api_items->addItems($temp_arr,$taxlists);
                    
                    if(isset($check_error['error_tax3'])){
                        session()->put('error_warning', $check_error['error_tax3']);
                        // echo 1525; die;
                        $data = $this->getForm($input);

                        return view('items.item_form',compact('data'));
                    }elseif(isset($check_error['error'])){
                        session()->put('warning', $check_error['error']);
                    
                    }else{
                        session()->put('success', 'Successfully Added Item');
                    }
        
                                       
                    if(isset($check_error['error_tax3'])){
                        session()->put('error_warning', $check_error['error_tax3']);
                        
                        $data = $this->getForm($input);

                        return view('items.item_form',compact('data'));
                    }else{
                        $url = '/item/item_list/Active/DESC';
                        
                        return redirect($url);
                    }
                    
                }
                
            }
        }
    }


    public function edit_form($iitemid, Request $request) {
        
        //==========checking itemid is available or not========
        if(isset($iitemid) & !empty($iitemid)){
            $check_itemid = Item::where('iitemid', $iitemid)->count();
            if($check_itemid < 1){
                
                $url = '/item/item_list/Active/DESC';
                return redirect($url);   
            }
        }
        
        session()->put('vendoritemid', $iitemid);
        
        $input = $request->all();
        
        $data = $this->getForm($input, $iitemid);
        // dd($data);
        session()->forget('error_warning');
        
        $get_input = DB::connection('mysql_dynamic')->select("select iitemid as search_iitemid , vbarcode as search_vbarcode from mst_item where iitemid='". $iitemid ."'" );
        $get_input = (array)$get_input[0];
        $ItemMovement = new ItemMovement;
        $reports = $ItemMovement->getItemMovementReport($get_input);
        
        $child_detail="select iitemid , vbarcode,vitemname from mst_item where parentid='". $iitemid ."'" ;     
        $Child_id = DB::connection('mysql_dynamic')->select($child_detail);
        
        
        $parent_detail="SELECT p.iitemid ,p.vbarcode FROM mst_item c
                        join mst_item p ON c.parentid = p.iitemid
                        where  c.iitemid='". $iitemid ."'" ;
        $parent_id = DB::connection('mysql_dynamic')->select($parent_detail);   
       
        
        if(isset($Child_id) && !empty($Child_id)){
          $childreports= $ItemMovement->getItemMovementReport_child($Child_id);
          
        }
        
        else{
          $childreports=array();
          
        }
        
        //parent data start--------------------------
        if(isset($parent_id) && !empty($parent_id)){
            $parentreports= $ItemMovement->getItemMovementReport_child($parent_id);
           
        }
        else{
            $parentreports=array();
            
        }
       //------------------------------------
        
        return view('items.item_form',compact('data', 'reports', 'childreports', 'parentreports'));
    }

    public function edit($iitemid, Request $request)
    {
        if ($request->isMethod('post')) {
            
            $input = $request->all();
            // dd($input);
            $data = $this->getForm($input, $iitemid);
            $check = true;
            $new_database = session()->get('new_database');
            if($new_database === false){
                // =============================================================== OLD DATABASE ====================================

                // if (!$this->user->hasPermission('modify', 'administration/items')) {
                //     $this->error['warning'] = $this->language->get('error_permission');
                // }

                if (($input['vbarcode'] == '')) {
                    $data['error_vbarcode'] = 'Please Enter SKU';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_vbarcode'] = '';
                }

                if (($input['new_costprice'] == '')) {
                    $data['error_new_costprice'] = 'Please Enter Cost';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_new_costprice'] = '';
                }

                if (($input['dunitprice'] == '')) {
                    $data['error_dunitprice'] = 'Please Enter Selling Price';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_dunitprice'] = '';
                }
                
                            
                if($input['vitemtype'] != 'Instant'){
                    
                    if (($input['vitemname'] == '')) {
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $data['error_vitemname'] = 'Please Enter Item Name';
                        $check = false;
                    } else {
                        $data['error_vitemname'] = '';
                    }
                    
                    if (($input['vunitcode'] == '')) {
                        $data['error_vunitcode'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vunitcode'] = '';
                    }
                    
                    if (($input['vsuppliercode'] == '')) {
                        $data['error_vsuppliercode'] = 'Please Select Supplier';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vsuppliercode'] = '';
                    }
                    
                    if (($input['vdepcode'] == '')) {
                        $data['error_vdepcode']= 'Please Select Department';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }else{
                        $data['error_vdepcode'] = '';
                    }
                        
                    if (isset($input['vcategorycode']) && ($input['vcategorycode'] == '' || $input['vcategorycode'] == '--Select Category--')) {
                        $data['error_vcategorycode'] = 'Please Select Category';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vcategorycode'] = '';
                    }
                    
                    
                }else{
                    
                    if (($input['ticket_name'] == '')) {
                        $data['error_ticket_name'] = 'Please Enter Item Name';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_name'] = '';
                    }
                    
                    if (($input['book_cost'] == '')) {
                        $data['error_book_cost'] = 'Please Enter Book Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_book_cost'] = '';
                    }
                    
                    if (($input['ticket_price'] == '')) {
                        $data['error_ticket_price'] = 'Please Item Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_price'] = '';
                    }
                }
                    
                
                if(isset($input['vbarcode']) && (!isset($iitemid) || empty($iitemid) )){
                
                    $olditem = new Olditem();
                    $unique_sku = $olditem->getSKU($input['vbarcode']);
                                //   print_r($unique_sku); die;

                        if(count($unique_sku) > 0){
                            $data['error_vbarcode'] = 'SKU Already Exist';
                            $data['error_warning'] = 'Please check the form carefully for errors!';
                            $check = false;
                        }
                }elseif(isset($input['vbarcode'])){
                    $olditem = new Olditem();
                    $item_info = $olditem->getItem($iitemid);
                    
                    if($item_info['vbarcode'] != $input['vbarcode']){

                        $olditem = new Olditem();
                        $unique_sku = $olditem->getSKU($input['vbarcode']);

                        if(count($unique_sku) > 0){
                            $data['error_vbarcode'] = 'SKU Already Exist';
                            $data['error_warning'] = 'Please check the form carefully for errors!';
                            $check = false;
                        }
                    }
                }

                if (isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1) {
                    
                    if (($input['unit_id'] == '')) {
                        $data['error_unit_id'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_id'] = '';
                    }

                    if (($input['unit_value'] == '')) {
                        $data['error_unit_value'] = 'Please Enter Unit Value';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_value'] = '';
                    }

                    if (($input['bucket_id'] == '')) {
                        $data['error_bucket_id'] = 'Please Select Bucket';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_bucket_id'] = '';
                    }
                }

                
                // if ($this->error && !isset($this->error['warning'])) {
                //     $this->error['warning'] = $this->language->get('error_warning');
                // }
                
            
            } else {
                // =============================================================== NEW DATABASE ====================================
                
                
                if (($input['vbarcode'] == '')) {
                    $data['error_vbarcode'] = 'Please Enter SKU';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_vbarcode'] = '';
                }
                
                            
                if($input['vitemtype'] != 'Instant'){
                    
                    if (($input['vitemname'] == '')) {
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $data['error_vitemname'] = 'Please Enter Item Name';
                        $check = false;
                    } else {
                        $data['error_vitemname'] = '';
                    }
                    
                    if (($input['vunitcode'] == '')) {
                        $data['error_vunitcode'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vunitcode'] = '';
                    }
                    
                    if (($input['vsuppliercode'] == '')) {
                        $data['error_vsuppliercode'] = 'Please Select Supplier';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vsuppliercode'] = '';
                    }
                    
                    if (($input['vdepcode'] == '')) {
                        $data['error_vdepcode']= 'Please Select Department';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }else{
                        $data['error_vdepcode'] = '';
                    }
                        
                    if (isset($input['vcategorycode']) && ($input['vcategorycode'] == '' || $input['vcategorycode'] == '--Select Category--')) {
                        $data['error_vcategorycode'] = 'Please Select Category';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }elseif(empty($input['vcategorycode'])){
                        
                        $data['error_vcategorycode'] = 'Please Select Category';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vcategorycode'] = '';
                    }
                    
                    // if (($input['subcat_id'] == '')) {
                    //     $data['error_subcat_id'] = 'Please Select Sub Category';
                    //     $data['error_warning'] = 'Please check the form carefully for errors!';
                    //     $check = false;
                    // } else {
                    //     $data['error_subcat_id'] = '';
                    // }
                    
                    if (($input['new_costprice'] == '')) {
                        $data['error_new_costprice'] = 'Please Enter Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_new_costprice'] = '';
                    }
    
                    if (($input['dunitprice'] == '')) {
                        $data['error_dunitprice'] = 'Please Enter Selling Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_dunitprice'] = '';
                    }
                }else{
                    
                    if (($input['ticket_name'] == '')) {
                        $data['error_ticket_name'] = 'Please Enter Ticket Name';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_name'] = '';
                    }
                    
                    if (($input['book_cost'] == '')) {
                        $data['error_book_cost'] = 'Please Enter Book Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_book_cost'] = '';
                    }
                    
                    if (($input['ticket_price'] == '')) {
                        $data['error_ticket_price'] = 'Please Ticket Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_price'] = '';
                    }
                }
                    
                if(isset($input['vbarcode']) && (!isset($iitemid) || empty($iitemid) )){
                    
                    $item = new Item();
                    $unique_sku = $item->getSKU($input['vbarcode']);
                                //   print_r($unique_sku); die;

                    if(count($unique_sku) > 0){
                        $data['error_vbarcode'] = 'SKU Already Exist';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }
                }elseif(isset($input['vbarcode'])){
                    
                    $item = new Item();
                    $item_info = $item->getItem($iitemid);
                    // dd($$item_info['vbarcode']);
                    if($item_info['vbarcode'] != $input['vbarcode']){
                        
                        $item = new Item();
                        $unique_sku = $item->getSKU($input['vbarcode']);

                        if(count($unique_sku) > 0){
                            $data['error_vbarcode'] = 'SKU Already Exist';
                            $data['error_warning'] = 'Please check the form carefully for errors!';
                            $check = false;
                        }
                    }
                }

                if (isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1) {
                    
                    if (($input['unit_id'] == '')) {
                        $data['error_unit_id'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_id'] = '';
                    }

                    if (($input['unit_value'] == '')) {
                        $data['error_unit_value'] = 'Please Enter Unit Value';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_value'] = '';
                    }

                    if (($input['bucket_id'] == '')) {
                        $data['error_bucket_id'] = 'Please Select Bucket';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_bucket_id'] = '';
                    }
                }
                
            }
                
            if($check == false){
                // print_r("aa");
                // dd($data);
                return view('items.item_form',compact('data'));
                // return redirect('/item/edit/'.$iitemid, compact('data'));
                
            }elseif($check == true){
                // print_r("bb");
                // dd($data);
                $new_database = session()->get('new_database');
            }
            if($new_database === false){
                
                // =================================================== OLD DATABASE ==========================================
    			if($request->hasFile('itemimage') && $request->file('itemimage')->getClientOriginalName() != ''){
                    $img_string = file_get_contents($request->file('itemimage')->getRealPath());
                    // $img_string = base64_encode($img_string);
    			}elseif($input['pre_itemimage'] != ''){
                    $img_string = base64_decode($input['pre_itemimage']);
    			}else{
    			  $img_string =NULL;   
    			}
    
                
                
                if(isset($input['vtax']) && $input['vtax'] != ''){
                    
                    $vtax = $input['vtax'];
                    
                    if($vtax == 'vtax1'){
                        $vtax1 = 'Y';
                    }else{
                    	$vtax1 = 'N';
                    }
                    
                    if($vtax == 'vtax2'){
                        $vtax2 = 'Y';
                    }else{
                    	$vtax2 = 'N';
                    }
                    
                    if($vtax == 'vtax3'){
                        $vtax3 = 'Y';
                    }else{
                    	$vtax3 = 'N';
                    }
                    
                    if($vtax == 'vnotax'){
                        $vtax1 = 'N';
                    	$vtax2 = 'N';
                    	$vtax3 = 'N';
                    }
                    
                }else{
                	$vtax1 = 'N';
                	$vtax2 = 'N';
                	$vtax3 = 'N';
                }
                
                if(isset($input['npack']) && $input['npack'] == ''){
                	$npack = '1';
                }else{
                	$npack = $input['npack'];
                }
    
    			if(isset($input['nsellunit']) && $input['nsellunit'] == ''){
    				$nsellunit = '1';
    			}else{
    				$nsellunit = $input['nsellunit'];
    			}
    
    
    			if(isset($input['nbottledepositamt']) && ($input['nbottledepositamt'] == '0.00' || $input['nbottledepositamt'] == '')){
    				$nbottledepositamt = '0.00';
    				$ebottledeposit = 'No';
    			}else{
    				$nbottledepositamt = $input['nbottledepositamt'];
    				$ebottledeposit = 'Yes';
    			}
    
    			if(isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1){
    
    				$options_data['unit_id'] = $input['unit_id'];
    				$options_data['unit_value'] = $input['unit_value'];
    				$options_data['bucket_id'] = $input['bucket_id'];
    				if(isset($input['malt']) && $input['malt'] == 1){
    					$options_data['malt'] = $input['malt'];
    				}else{
    					$options_data['malt'] = 0;
    				}
    
    			}else{
    				$options_data = array();
                }
                
                if(isset($input['ireorderpointdays']))
                {
                    $ireorderpointdays  = $input['ireorderpointdays'];
                    $vitemcode            = $input['vbarcode'];
                    $reorderPoint = 0;
                    if($ireorderpointdays > 0 && !empty($vitemcode))
                    {
                        $reorderPoint = $this->calculateReorderPoint($vitemcode,$ireorderpointdays);
                    }
                 
                }
                
                $reorderPoint = ($input['ireorderpoint'] > 0) ? $input['ireorderpoint'] : $reorderPoint ;

    			
    			if($input['vitemtype'] == 'Instant'){
                    
                    /*====== games_per_book = npack and book_qoh = QOH only for Lotter items =========
                        In this case if book_qoh = 5 and games_per_book = 50
                        then total games = 250
                    */
                    if(isset($input['games_per_book']) && $input['games_per_book'] == ''){
                        $npack = '1';
                    }else{
                        $npack = $input['games_per_book'];
                    }
                    
                    if(isset($input['book_qoh']) && $input['book_qoh'] == ''){
                        $iqtyonhand = '0';
                    }else{
                        $iqtyonhand = $input['book_qoh'];
                    }
                    
                    $liability = 'N';
                    $estatus = 'Active';
                    $visinventory = 'Yes';
                    
                    $temp_arr = array(
                                    "stores_hq"     => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid') ,
                                    "iitemid" => $input['iitemid'],
                                    "vitemtype" => $input['vitemtype'],
                                    "vitemname" => htmlspecialchars_decode($input['ticket_name']),
                                    "vbarcode" => $input['vbarcode'],
                                    "iqtyonhand" => $iqtyonhand,
                                    "dcostprice" => $input['book_cost'],
                                    "dunitprice" => $input['ticket_price'],
                                    // "dcreated" => date('Y-m-d'),
                                    "dlastupdated" => date('Y-m-d'),
                                    "npack" => $npack,
                                    "visinventory" => $visinventory,
                                    "liability" => $liability,
                                    "vtax1" => 'N',
                                    "vtax2" => 'N',
                                    "vtax3" => 'N',
                                    "estatus" => $estatus
                                );
                              
                    $items = new Item;
        
                    $items->editLotteryItems($temp_arr['iitemid'], $temp_arr);
                                
                }else{
                    
                    $liability = 'Y';
                    $dcostprice = $input['new_costprice'];
                    // $nunitcost = $dcostprice/$npack;
                    $nunitcost = $dcostprice/$nsellunit;
                    
                    $temp_arr = array(  
                                    "stores_hq"     => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid') ,
                                    'iitemid' => $input['iitemid'],
                                    "iitemgroupid" => $input['iitemgroupid'],
                                    "webstore" => "0",
                                    "vitemtype" => $input['vitemtype'],
                                    "vitemname" => htmlspecialchars_decode($input['vitemname']),
                                    "vunitcode" => $input['vunitcode'],
                                    "vbarcode" => $input['vbarcode'],
                                    "vpricetype" => "",
                                    "vcategorycode" => $input['vcategorycode'],
                                    "subcat_id" => $input['subcat_id'],
                                    "vdepcode" => $input['vdepcode'],
                                    "vsuppliercode" => $input['vsuppliercode'],
                                    "iqtyonhand" => $input['iqtyonhand'],
                                    "ireorderpoint" => $reorderPoint,
                                    // "ireorderpointdays" => isset($input['ireorderpointdays'])? $input['ireorderpointdays']:'',
                                    "reorder_duration" => $input['reorder_duration'],
                                    "manufacturer_id" => $input['manufacturer_id'],
                                    "new_costprice" => $input['new_costprice'],
                                    // "dcostprice" => $input['dcostprice'],
                                    "dcostprice" => $dcostprice,
                                    "dunitprice" => $input['dunitprice'],
                                    "nsaleprice" => '',
                                    "nlevel2" => $input['nlevel2'],
                                    "nlevel3" => $input['nlevel3'],
                                    "nlevel4" => $input['nlevel4'],
                                    "iquantity" => "0",
                                    "ndiscountper" => $input['ndiscountper'],
                                    "ndiscountamt" => "0.00",
                                    "vtax1" => $vtax1,
                                    "vtax2" => $vtax2,
                                    "vtax3" => $vtax3,
                                    "vfooditem" => $input['vfooditem'],
                                    "vdescription" => htmlspecialchars_decode($input['vdescription']),
                                    "dlastsold" => null,
                                    "visinventory" => $input['visinventory'],
                                    "dpricestartdatetime" => null,
                                    "dpriceenddatetime" => null,
                                    "estatus" => $input['estatus'],
                                    "nbuyqty" => "0",
                                    "ndiscountqty" => "0",
                                    "nsalediscountper" => "0.00",
                                    "vshowimage" => $input['vshowimage'],
                                    "itemimage" => $img_string,
                                    "vageverify" => $input['vageverify'],
                                    "ebottledeposit" => $ebottledeposit,
                                    "nbottledepositamt" => $nbottledepositamt,
                                    "vbarcodetype" => $input['vbarcodetype'],
                                    "ntareweight" => "0.00",
                                    "ntareweightper" => "0.00",
                                    "dlastupdated" => date('Y-m-d'),
                                    "dlastreceived" => null,
                                    "dlastordered" => null,
                                    "nlastcost" => "0.00",
                                    "nonorderqty" => "0",
                                    "vparentitem" => "0",
                                    "nchildqty" => "0.00",
                                    "vsize" => $input['vsize'],
                                    "npack" => $npack,
                                    "nunitcost" => $nunitcost,
                                    "ionupload" => "0",
                                    "nsellunit" => $nsellunit,
                                    "ilotterystartnum" => "0",
                                    "ilotteryendnum" => "0",
                                    "etransferstatus" => "",
                                    "vsequence" => $input['vsequence'],
                                    "vcolorcode" => 'None',
                                    "vdiscount" => $input['vdiscount'],
                                    "norderqtyupto" => $input['norderqtyupto'],
                                    "vshowsalesinzreport" => $input['vshowsalesinzreport'],
                                    "iinvtdefaultunit" => "0",
                                    "stationid" => $input['stationid'],
                                    "shelfid" => $input['shelfid'],
                                    "aisleid" => $input['aisleid'],
                                    "shelvingid" => $input['shelvingid'],
                                    "rating" => $input['rating'],
                                    "vintage" => $input['vintage'],
                                    "PrinterStationId" => "0",
                                    // "liability" => $input['liability'],
                                    "liability" => $liability,
                                    "isparentchild" => $input['isparentchild'],
                                    "parentid" => $input['parentid'],
                                    "parentmasterid" => $input['parentmasterid'],
                                    "wicitem" => $input['wicitem'],
                                    "options_data" => $options_data
                                );
                    
                    $olditems = new Olditem;
                    
                    $olditems->editlistItems($temp_arr['iitemid'],$temp_arr);
                }
    
                session()->put('success', 'Successfully Updated');

                session()->put('tab_selected', 'item_tab'); 
                
    			$item_page_search = session()->get('item_page_search');
    			if(isset($item_page_search)){
                    session()->put('item_page_search_id', $input['iitemid']);
                    
                    $url = '/item/item_list/Active/DESC';
                    return redirect($url);
                    session()->forget('item_page_search');
    			}else{
                    if(isset($input['visited_zero_movement_report'])){
                        $url = '/item/edit/'.$iitemid.'?visited_zero_movement_report=Yes';
                        
                    }elseif(isset($input['visited_below_cost_report'])){
                        $url = '/item/edit/'.$iitemid.'?visited_below_cost_report=Yes';
                        
                    }else{
                        $url = '/item/edit/'.$iitemid;
                    }
                        
                    return redirect($url);
                        
                    return redirect($url);
    			}               
                
                
            } else {
               
                //================================================= NEW DATABASE ==========================================
                // dd($request->file('itemimage')->getPathName());
                if($request->hasFile('itemimage') && $request->file('itemimage')->getClientOriginalName() != ''){
                    $img_string = file_get_contents($request->file('itemimage')->getRealPath());
                    // $img_string = base64_encode($img_string);
                }elseif($input['pre_itemimage'] != ''){
                    $img_string = base64_decode($input['pre_itemimage']);
                }else{
                  $img_string =NULL;   
                }
                
                if(isset($input['vtax']) && $input['vtax'] != ''){
                    
                    $vtax = $input['vtax'];
                    
                    if($vtax == 'vtax1'){
                        $vtax1 = 'Y';
                    }else{
                    	$vtax1 = 'N';
                    }
                    
                    if($vtax == 'vtax2'){
                        $vtax2 = 'Y';
                    }else{
                    	$vtax2 = 'N';
                    }
                    
                    if($vtax == 'vtax3'){
                        $vtax3 = 'Y';
                    }else{
                    	$vtax3 = 'N';
                    }
                    
                    if($vtax == 'vnotax'){
                        $vtax1 = 'N';
                    	$vtax2 = 'N';
                    	$vtax3 = 'N';
                    }
                    
                }else{
                	$vtax1 = 'N';
                	$vtax2 = 'N';
                	$vtax3 = 'N';
                }
                
                if(isset($input['npack']) && $input['npack'] == ''){
                    $npack = '1';
                }else{
                    $npack = $input['npack'];
                }
                
                if(isset($input['nsellunit']) && $input['nsellunit'] == ''){
                    $nsellunit = '1';
                }else{
                    $nsellunit = $input['nsellunit'];
                }
                    
                // if(isset($input['visinventory']) && $input['visinventory'] == 'No'){
                //     $iqtyonhand = '0';
                // }else{
                //     $iqtyonhand = $input['iqtyonhand'];
                // }
                    
                if(isset($input['nbottledepositamt']) && ($input['nbottledepositamt'] == '0.00' || $input['nbottledepositamt'] == '')){
                    $nbottledepositamt = '0.00';
                    $ebottledeposit = 'No';
                }else{
                    $nbottledepositamt = $input['nbottledepositamt'];
                    $ebottledeposit = 'Yes';
                }
                    
                if(isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1){
                    
                    $options_data['unit_id'] = $input['unit_id'];
                    $options_data['unit_value'] = $input['unit_value'];
                    $options_data['bucket_id'] = $input['bucket_id'];
                    if(isset($input['malt']) && $input['malt'] == 1){
                        $options_data['malt'] = $input['malt'];
                    }else{
                        $options_data['malt'] = 0;
                    }
                    
                }else{
                    $options_data = array();
                }
                
                $reorderPoint = '';
                if(isset($input['ireorderpointdays']))
                {
                    $ireorderpointdays  = $input['ireorderpointdays'];
                    $vitemcode            = $input['vbarcode'];
                    $reorderPoint = 0;
                    if($ireorderpointdays > 0 && !empty($vitemcode))
                    {
                        $reorderPoint = $this->calculateReorderPoint($vitemcode,$ireorderpointdays);
                    }
                 
                }
                
                $reorderPoint = ($input['ireorderpoint'] > 0) ? $input['ireorderpoint'] : $reorderPoint ;

                if($input['vitemtype'] == 'Instant'){
                    
                    /*====== games_per_book = npack and book_qoh = QOH only for Lotter items =========
                        In this case if book_qoh = 5 and games_per_book = 50
                        then total games = 250
                    */
                    if(isset($input['games_per_book']) && $input['games_per_book'] == ''){
                        $npack = '1';
                    }else{
                        $npack = $input['games_per_book'];
                    }
                    
                    if(isset($input['book_qoh']) && $input['book_qoh'] == ''){
                        $iqtyonhand = '0';
                    }else{
                        $iqtyonhand = $input['book_qoh'];
                    }
                    
                    $liability = 'N';
                    $estatus = 'Active';
                    $visinventory = 'Yes';
                    
                    $temp_arr = array(
                                    "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid') ,
                                    "iitemid" => $input['iitemid'],
                                    "vitemtype" => $input['vitemtype'],
                                    "vitemname" => htmlspecialchars_decode($input['ticket_name']),
                                    "vbarcode" => $input['vbarcode'],
                                    "iqtyonhand" => $iqtyonhand,
                                    "dcostprice" => $input['book_cost'],
                                    "dunitprice" => $input['ticket_price'],
                                    // "dcreated" => date('Y-m-d'),
                                    "dlastupdated" => date('Y-m-d'),
                                    "npack" => $npack,
                                    "visinventory" => $visinventory,
                                    "liability" => $liability,
                                    "vtax1" => 'N',
                                    "vtax2" => 'N',
                                    "vtax3" => 'N',
                                    "estatus" => $estatus
                                );
                            //   dd($temp_arr);
                    $items = new Item;
                    
                    $items->editLotteryItems($temp_arr['iitemid'], $temp_arr);
                                
                }else{
                    // $liability = 'Y';
                    $liability = 'N';
                    
                    
                    $dcostprice = $input['new_costprice'];
                    // $nunitcost = $dcostprice/$npack;
                    $nunitcost = $dcostprice/$nsellunit;
                    
                    $is_inventory = isset($input['visinventory'])?$input['visinventory']:'Yes';
                    $vfooditem = isset($input['vfooditem'])?$input['vfooditem']:'N';
                    
                    $temp_arr = array(
                                    "stores_hq"     => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid') ,
                                    'iitemid' => $input['iitemid'],
                                    // "iitemgroupid" => $input['iitemgroupid'],
                                    "webstore" => "0",
                                    "vitemtype" => $input['vitemtype'],
                                    "vitemname" => htmlspecialchars_decode($input['vitemname']),
                                    "vunitcode" => $input['vunitcode'],
                                    "vbarcode" => $input['vbarcode'],
                                    "vpricetype" => "",
                                    "vcategorycode" => $input['vcategorycode'],
                                    "subcat_id" => $input['subcat_id'],
                                    "vdepcode" => $input['vdepcode'],
                                    "vsuppliercode" => $input['vsuppliercode'],
                                    "iqtyonhand" => $input['iqtyonhand'],
                                    "ireorderpoint" => $reorderPoint,
                                    // "ireorderpointdays" => isset($input['ireorderpointdays'])? $input['ireorderpointdays']:'',
                                    "reorder_duration" => $input['reorder_duration'],
                                    "manufacturer_id" => $input['manufacturer_id'],
                                    "new_costprice" => $input['new_costprice'],
                                    // "dcostprice" => $input['dcostprice'],
                                    "dcostprice" => $dcostprice,
                                    "dunitprice" => $input['dunitprice'],
                                    "nsaleprice" => 0,
                                    "nlevel2" => $input['nlevel2'] ?? 0,
                                    "nlevel3" => $input['nlevel3'] ?? 0,
                                    "nlevel4" => $input['nlevel4'] ?? 0,
                                    "iquantity" => "0",
                                    "ndiscountper" => $input['ndiscountper'],
                                    "ndiscountamt" => "0.00",
                                    "vtax1" => $vtax1,
                                    "vtax2" => $vtax2,
                                    "vtax3" => $vtax3,
                                    "vfooditem" => $vfooditem,
                                    "vdescription" => htmlspecialchars_decode($input['vdescription']),
                                    "dlastsold" => null,
                                    "visinventory" => $is_inventory,
                                    "dpricestartdatetime" => null,
                                    "dpriceenddatetime" => null,
                                    "estatus" => $input['estatus'],
                                    "nbuyqty" => "0",
                                    "ndiscountqty" => "0",
                                    "nsalediscountper" => "0.00",
                                    "vshowimage" => $input['vshowimage'],
                                    "itemimage" => $img_string,
                                    "vageverify" => $input['vageverify'],
                                    "ebottledeposit" => $ebottledeposit,
                                    "nbottledepositamt" => $nbottledepositamt,
                                    "vbarcodetype" => $input['vbarcodetype'],
                                    "ntareweight" => "0.00",
                                    "ntareweightper" => "0.00",
                                    "dlastupdated" => date('Y-m-d'),
                                    "dlastreceived" => null,
                                    "dlastordered" => null,
                                    "nlastcost" => "0.00",
                                    "nonorderqty" => "0",
                                    "vparentitem" => "0",
                                    "nchildqty" => "0.00",
                                    "vsize" => $input['vsize'],
                                    "npack" => $npack,
                                    "nunitcost" => $nunitcost,
                                    "ionupload" => "0",
                                    "nsellunit" => $nsellunit,
                                    "ilotterystartnum" => "0",
                                    "ilotteryendnum" => "0",
                                    "etransferstatus" => "",
                                    // "vsequence" => $input['vsequence'],
                                    "vcolorcode" => 'None',
                                    "vdiscount" => $input['vdiscount'],
                                    "norderqtyupto" => $input['norderqtyupto'],
                                    // "vshowsalesinzreport" => $input['vshowsalesinzreport'],
                                    "iinvtdefaultunit" => "0",
                                    "stationid" => $input['stationid'],
                                    "shelfid" => $input['shelfid'],
                                    "aisleid" => $input['aisleid'],
                                    "shelvingid" => $input['shelvingid'],
                                    // "rating" => $input['rating'],
                                    // "vintage" => $input['vintage'],
                                    "PrinterStationId" => "0",
                                    // "liability" => $input['liability'],
                                    "liability" => $liability,
                                    "isparentchild" => $input['isparentchild'],
                                    "parentid" => $input['parentid'],
                                    "parentmasterid" => $input['parentmasterid'],
                                    "wicitem" => $input['wicitem'],
                                    "options_data" => $options_data
                                );
                    // dd($temp_arr);
                    $item = new Item();   
                    $check_error = $item->editlistItems($temp_arr['iitemid'],$temp_arr);
                }
                // dd($check_error);
                
                if(isset($check_error['error_tax3'])){
                    session()->put('error_warning', $check_error['error_tax3']);
                        
                    $data = $this->getForm($input);

                    return view('items.item_form',compact('data'));
                    // echo 1525;
                }else{
                    session()->put('success', 'Successfully Updated');
                    
                }
                
                session()->put('tab_selected', 'item_tab'); 
                
    			$item_page_search = session()->get('item_page_search');
    			if(isset($item_page_search)){
    				session()->put('item_page_search_id', $input['iitemid']);
    				$url = '';
                    session()->forget('item_page_search');
                    
                    $url = '/item/item_list/Active/DESC';
                        
                    return redirect($url);
                    
                }else{
                    if(isset($input['visited_zero_movement_report'])){
                        $url = '/item/edit/'.$iitemid.'?visited_zero_movement_report=Yes';
                        
                    }elseif(isset($input['visited_below_cost_report'])){
                        $url = '/item/edit/'.$iitemid.'?visited_below_cost_report=Yes';
                        
                    }else{
                        $url = '/item/edit/'.$iitemid;
                    }
                        
                    return redirect($url);
                        
                    return redirect($url);            
                }               
               
               
            }
            
        }
    }

    
    public function getForm($input, $iitemid = null)
    {
        $new_database = session()->get('new_database');
		
        //============================================== OLD DATABASE STARTS (Line No. 1144 to 1968)========================================
        if($new_database === false){
            
            $tab_selected = session()->get('tab_selected');
            if(isset($tab_selected)){
            	$data['tab_selected'] = session()->get('tab_selected');
            }else{
            	$data['tab_selected'] = '';
            }
            
            $data['text_form'] = !isset($this->request->get['iitemid']) ? 'Add Items' : 'Edit Items';
            
            $data['arr_y_n'][] = 'No';
            $data['arr_y_n'][] = 'Yes';
            
            $data['array_yes_no']['Y'] = 'Yes'; 
            $data['array_yes_no']['N'] = 'No';
            
            $data['array_status']['Active'] = 'Active'; 
            $data['array_status']['Inactive'] = 'Inactive';  
            
            $data['item_colors'] = array("None","AliceBlue","AntiqueWhite","Aqua","Aquamarine","Azure","Beige","Bisque","Black","BlanchedAlmond","Blue","BlueViolet","Brown","BurlyWood","CadetBlue","Chartreuse","Chocolate","Coral","CornflowerBlue","Cornsilk","Crimson","Cyan","DarkBlue","DarkCyan","DarkGoldenRod","DarkGray","DarkGrey","DarkGreen","DarkKhaki","DarkMagenta","DarkOliveGreen","Darkorange","DarkOrchid","DarkRed","DarkSalmon","DarkSeaGreen","DarkSlateBlue","DarkSlateGray","DarkSlateGrey","DarkTurquoise","DarkViolet","DeepPink","DeepSkyBlue","DimGray","DimGrey","DodgerBlue","FireBrick","FloralWhite","ForestGreen","Fuchsia","Gainsboro","GhostWhite","Gold","GoldenRod","Gray","Grey","Green","GreenYellow","HoneyDew","HotPink","IndianRed","Indigo","Ivory","Khaki","Lavender","LavenderBlush","LawnGreen","LemonChiffon","LightBlue","LightCoral","LightCyan","LightGoldenRodYellow","LightGray","LightGrey","LightGreen","LightPink","LightSalmon","LightSeaGreen","LightSkyBlue","LightSlateGray","LightSlateGrey","LightSteelBlue","LightYellow","Lime","LimeGreen","Linen","Magenta","Maroon","MediumAquaMarine","MediumBlue","MediumOrchid","MediumPurple","MediumSeaGreen","MediumSlateBlue","MediumSpringGreen","MediumTurquoise","MediumVioletRed","MidnightBlue","MintCream","MistyRose","Moccasin","NavajoWhite","Navy","OldLace","Olive","OliveDrab","Orange","OrangeRed","Orchid","PaleGoldenRod","PaleGreen","PaleTurquoise","PaleVioletRed","PapayaWhip","PeachPuff","Peru","Pink","Plum","PowderBlue","Purple","Red","RosyBrown","RoyalBlue","SaddleBrown","Salmon","SandyBrown","SeaGreen","SeaShell","Sienna","Silver","SkyBlue","SlateBlue","SlateGray","SlateGrey","Snow","SpringGreen","SteelBlue","Tan","Teal","Thistle","Tomato","Turquoise","Violet","Wheat","White","WhiteSmoke","Yellow","YellowGreen");
            
            $data['item_types'][] = 'Standard';
            $data['item_types'][] = 'Kiosk';
            $data['item_types'][] = 'Lot Matrix';
            // $data['item_types'][] = 'Gasoline';
            $data['item_types'][] = 'Instant';
            
            $data['barcode_types'][] = 'Code 128';
            $data['barcode_types'][] = 'Code 39';
            $data['barcode_types'][] = 'Code 93';
            $data['barcode_types'][] = 'UPC E';
            $data['barcode_types'][] = 'EAN 8';
            $data['barcode_types'][] = 'EAN 13';
            $data['barcode_types'][] = 'UPC A';
            

            $error_warning = session()->get('warning');
            if (isset($error_warning)) {
                $data['error_warning'] = session()->get('warning');

                session()->forget('warning');
            } else {
                $data['error_warning'] = '';
            }
            
            
            $session_success = session()->get('success');
            if (isset($session_success)) {
                $data['success'] = session()->get('success');

                session()->forget('success');
            } else {
                $data['success'] = '';
            }
            
            
            $data['breadcrumbs'] = array();
            
            $data['breadcrumbs'][] = array(
                'text' => 'Home',
                'href' => url('/dashboard')
            );
            
            $data['breadcrumbs'][] = array(
                'text' => 'Items',
                'href' => url('/items/item_list')
            );
            
            if (!isset($this->request->get['iitemid'])) {
                $data['action'] = url('/item/add');
            	$data['clone_item'] = '';
            } else {
                
                $visited_zero_movement_report = session()->get('visited_zero_movement_report');
                $visited_below_cost_report = session()->get('visited_below_cost_report');
                if(isset($visited_zero_movement_report) || ( isset($input['visited_zero_movement_report']) && $input['visited_zero_movement_report'] =='Yes' )){
                    
                    $data['zero_movement_item_link']= url('/zeromovementreport?visited_zero_movement_report=Yes');
                    session()->put('visited_zero_movement_report', 'Yes');
                    $data['visited_zero_movement_report'] = session()->get('visited_zero_movement_report');	
                    $data['action'] = url('/item/edit/'. $iitemid.'?visited_zero_movement_report=Yes');
                   
                }elseif(isset($visited_below_cost_report) || ( isset($input['visited_below_cost_report']) && $input['visited_below_cost_report'] =='Yes' )){
                    
                    $data['below_cost_item_link']= url('/belowcostreport/getlist_belowcostreport?visited_below_cost_report=Yes');
                    $visited_below_cost_report = 'Yes';
                    $data['visited_below_cost_report'] = $visited_below_cost_report;
                    $data['action'] = url('/item/edit/'. $iitemid.'?visited_below_cost_report=Yes');
                    
                }else{
                    $data['action'] = url('/item/edit/'. $iitemid);    
                }
    			
    			$data['clone_item'] = url('/item/clone_item/'.$iitemid);
    		}
            
    		$data['unset_visited_below_zero'] = url('/item/unset_visited_below_zero');
            $data['reorder_point_calculate_url'] = url('/item/calculateReorderPointAjax');

            $data['delete'] = url('/item/delete');
            $data['delete_vendor_code'] = url('/item/delete_vendor_code');
            $data['current_url'] = url('/item');
            
            $data['action_vendor'] = url('/item/action_vendor');
            $data['action_vendor_editlist'] = url('/item/action_vendor_editlist');
            $data['add_alias_code'] = url('/item/add_alias_code');
            $data['alias_code_deletelist'] = url('/item/delete_alias_code');
            $data['add_lot_matrix'] = url('/item/add_lot_matrix');
            $data['lot_matrix_editlist'] = url('/item/lot_matrix_editlist');
            $data['lot_matrix_deletelist'] = url('/item/delete_lot_matrix');
            
            $data['check_vendor_item_code'] = url('/item/check_vendor_item_code');
            $data['get_categories'] = url('item/get_categories');
            $data['get_subcategories_url'] = url('item/get_subcategories');        

            // $data['searchitem'] = url('/item/search');
            $data['parent_child_search'] = url('/item/parent_child_search');
    		// urls for adding category, dept, etc
    		$data['Sales'] = 'Sales';
            $data['MISC'] = 'MISC';
            
    		$data['add_new_category'] = url('/item/category/add');
        
            $data['add_new_department'] = url('/item/department/add');
            $data['get_new_department'] = url('/item/department');
            
            $data['add_new_subcategory'] = url('/item/sub_category/add');

            $data['add_new_size'] = url('/item/size/add');
            $data['get_new_size'] = url('/item/size');

            $data['add_new_group'] = url('/item/group/add');
            $data['get_new_group'] = url('/item/group');

            $data['add_new_supplier'] = url('/item/supplier/add');
            $data['get_new_supplier'] = url('/item/supplier');
            
            $data['add_new_manufacturer'] = url('/item/manufacturer/add_manufacturer');
            $data['get_new_manufacturer'] = url('/item/manufacturer/get_manufacturer');
    		// urls for adding category, dept, etc
                        
            $data['sid'] = session()->get('sid');

            $data['cancel'] = url('/item/item_list/Active/DESC');
            
            if (isset($iitemid)) {
    			$olditem = new Olditem();
                $item_info = $olditem->getItem($iitemid);
                // $item_info = Item::where('iitemid', $iitemid)->get()->toArray();
                
                
                // $promotion_data = $item->get_promotion_by_item($item_info['vbarcode']);
                // $data['itemPromotion'] = $promotion_data;
                $sid= session()->get('sid'); 
            
                $tax_info = $olditem->gettaxinfo();
                $data['tax_info']=$tax_info;
    			
    			$data['iitemid'] = $this->request->get['iitemid'];
    			$data['edit_page'] = 'edit_page';
    			$data['itemvendors'] = $item_info['itemvendors'];
    			$data['itemalias'] = $item_info['itemalias'];
    			$data['itempacks'] = $item_info['itempacks'];
    			$data['itemslabprices'] = $item_info['itemslabprices'];
    			$data['itemchilditems'] = $item_info['itemchilditems'];
    			$data['itemparentitems'] = $item_info['itemparentitems'];
    			$data['remove_parent_item'] = $item_info['remove_parent_item'];
    			
    
    			$unit_data = $olditem->getItemUnitData($iitemid);
                $bucket_data = $olditem->getItemBucketData($iitemid);
    		}
    
    		if (isset($this->request->get['iitemid']) && ($this->request->server['REQUEST_METHOD'] == 'POST')) {
    			$data['edit_page'] = 'edit_page';
    		}
    
    		if (isset($input['iitemid'])) {
    			$data['iitemid'] = $input['iitemid'];
    		} elseif (!empty($item_info)) {
    			$data['iitemid'] = $item_info['iitemid'];
    		} else {
    			$data['iitemid'] = '';
    		}	
    
    		if (isset($input['vitemtype'])) {
    			$data['vitemtype'] = $input['vitemtype'];
    		} elseif (!empty($item_info)) {
    			$data['vitemtype'] = $item_info['vitemtype'];
    		} else {
    			$data['vitemtype'] = '';
    		}
    
    		if (isset($input['vitemcode'])) {
    			$data['vitemcode'] = $input['vitemcode'];
    		} elseif (!empty($item_info)) {
    			$data['vitemcode'] = $item_info['vitemcode'];
    		} else {
    			$data['vitemcode'] = '';
    		}
    
    		if (isset($input['vbarcode'])) {
    			$data['vbarcode'] = $input['vbarcode'];
    		} elseif (!empty($item_info)) {
    			$data['vbarcode'] = $item_info['vbarcode'];
    		} else {
    			$data['vbarcode'] = '';
    		}
    
    		if (isset($input['vitemname'])) {
    			$data['vitemname'] = $input['vitemname'];
    		} elseif (!empty($item_info)) {
    			$data['vitemname'] = $item_info['vitemname'];
    		} else {
    			$data['vitemname'] = '';
    		}
    
    		if (isset($input['vdescription'])) {
    			$data['vdescription'] = $input['vdescription'];
    		} elseif (!empty($item_info)) {
    			$data['vdescription'] = $item_info['vdescription'];
    		} else {
    			$data['vdescription'] = '';
    		}
    
    		if (isset($input['vunitcode'])) {
    			$data['vunitcode'] = $input['vunitcode'];
    		} elseif (!empty($item_info)) {
    			$data['vunitcode'] = $item_info['vunitcode'];
    		} else {
    			$data['vunitcode'] = '';
    		}
    
    		if (isset($input['vsuppliercode'])) {
    			$data['vsuppliercode'] = $input['vsuppliercode'];
    		} elseif (!empty($item_info)) {
    			$data['vsuppliercode'] = $item_info['vsuppliercode'];
    		} else {
    			$data['vsuppliercode'] = '';
    		}
    
    		if (isset($input['vdepcode'])) {
    			$data['vdepcode'] = $input['vdepcode'];
    		} elseif (!empty($item_info)) {
    			$data['vdepcode'] = $item_info['vdepcode'];
    		} else {
    			$data['vdepcode'] = '';
    		}
    
    		if (isset($input['vcategorycode'])) {
    			$data['vcategorycode'] = $input['vcategorycode'];
    		} elseif (!empty($item_info)) {
    			$data['vcategorycode'] = $item_info['vcategorycode'];
    		} else {
    			$data['vcategorycode'] = '';
    		}
    
    		if (isset($input['vsize'])) {
    			$data['vsize'] = $input['vsize'];
    		} elseif (!empty($item_info)) {
    			$data['vsize'] = $item_info['vsize'];
    		} else {
    			$data['vsize'] = '';
    		}
    
    		if (isset($input['iitemgroupid'])) {
    			$data['iitemgroupid'] = $input['iitemgroupid'];
    		} elseif (!empty($item_info)) {
    			$data['iitemgroupid'] = isset($item_info['iitemgroupid']['iitemgroupid']) ? $item_info['iitemgroupid']['iitemgroupid']: '';
    		} else {
    			$data['iitemgroupid'] = '';
    		}
    
    		if (isset($input['wicitem'])) {
    			$data['wicitem'] = $input['wicitem'];
    		} elseif (!empty($item_info)) {
    			$data['wicitem'] = $item_info['wicitem'];
    		} else {
    			$data['wicitem'] = '';
    		}
    
    		if (isset($input['vsequence'])) {
    			$data['vsequence'] = $input['vsequence'];
    		} elseif (!empty($item_info)) {
    			$data['vsequence'] = $item_info['vsequence'];
    		} else {
    			$data['vsequence'] = '';
    		}
    
    		if (isset($input['vcolorcode'])) {
    			$data['vcolorcode'] = $input['vcolorcode'];
    		} elseif (!empty($item_info)) {
    			$data['vcolorcode'] = $item_info['vcolorcode'];
    		} else {
    			$data['vcolorcode'] = '';
    		}
    
    		if (isset($input['npack'])) {
    			$data['npack'] = $input['npack'];
    		} elseif (!empty($item_info)) {
    			$data['npack'] = $item_info['npack'];
    		} else {
    			$data['npack'] = '';
    		}
    
    		if (isset($input['dcostprice'])) {
    			$data['dcostprice'] = $input['dcostprice'];
    		} elseif (!empty($item_info)) {
    			$data['dcostprice'] = $item_info['dcostprice'];
    		} else {
    			$data['dcostprice'] = '';
    		}
    
    		if (isset($input['nunitcost'])) {
    			$data['nunitcost'] = $input['nunitcost'];
    		} elseif (!empty($item_info)) {
    			$data['nunitcost'] = $item_info['nunitcost'];
    		} else {
    			$data['nunitcost'] = '';
    		}
    
    		if (isset($input['nsellunit'])) {
    			$data['nsellunit'] = $input['nsellunit'];
    		} elseif (!empty($item_info)) {
    			$data['nsellunit'] = $item_info['nsellunit'];
    		} else {
    			$data['nsellunit'] = '';
    		}
    
    		if (isset($input['nsaleprice'])) {
    			$data['nsaleprice'] = $input['nsaleprice'];
    		} elseif (!empty($item_info)) {
    			$data['nsaleprice'] = $item_info['nsaleprice'];
    		} else {
    			$data['nsaleprice'] = '';
    		}
    
    		if (isset($input['dunitprice'])) {
    			$data['dunitprice'] = $input['dunitprice'];
    		} elseif (!empty($item_info)) {
    			$data['dunitprice'] = $item_info['dunitprice'];
    		} else {
    			$data['dunitprice'] = '';
    		}
    
    		if (isset($input['profit_margin'])) {
    			$data['profit_margin'] = $input['profit_margin'];
    		} else {
    			$data['profit_margin'] = '';
    		}
    
    		if (isset($input['liability'])) {
    			$data['liability'] = $input['liability'];
    		} elseif (!empty($item_info)) {
    			$data['liability'] = $item_info['liability'];
    		} else {
                // setting default of Liability as N
                $data['liability'] = 'N';
    		}
    
    		if (isset($input['vshowsalesinzreport'])) {
    			$data['vshowsalesinzreport'] = $input['vshowsalesinzreport'];
    		} elseif (!empty($item_info)) {
    			$data['vshowsalesinzreport'] = $item_info['vshowsalesinzreport'];
    		} else {
    			$data['vshowsalesinzreport'] = '';
    		}
    
    		if (isset($input['stationid'])) {
    			$data['stationid'] = $input['stationid'];
    		} elseif (!empty($item_info)) {
    			$data['stationid'] = $item_info['stationid'];
    		} else {
    			$data['stationid'] = '';
    		}
    
    		if (isset($input['aisleid'])) {
    			$data['aisleid'] = $input['aisleid'];
    		} elseif (!empty($item_info)) {
    			$data['aisleid'] = $item_info['aisleid'];
    		} else {
    			$data['aisleid'] = '';
    		}
    
    		if (isset($input['shelfid'])) {
    			$data['shelfid'] = $input['shelfid'];
    		} elseif (!empty($item_info)) {
    			$data['shelfid'] = $item_info['shelfid'];
    		} else {
    			$data['shelfid'] = '';
    		}
    
    		if (isset($input['shelvingid'])) {
    			$data['shelvingid'] = $input['shelvingid'];
    		} elseif (!empty($item_info)) {
    			$data['shelvingid'] = $item_info['shelvingid'];
    		} else {
    			$data['shelvingid'] = '';
    		}
    
    		if (isset($input['iqtyonhand'])) {
    			$data['iqtyonhand'] = $input['iqtyonhand'];
    		} elseif (!empty($item_info)) {
    			$data['iqtyonhand'] = $item_info['iqtyonhand'];
    		} else {
    			$data['iqtyonhand'] = '';
    		}
    
    		if (isset($input['QOH'])) {
    			$data['QOH'] = $input['QOH'];
    		} elseif (!empty($item_info)) {
    			$data['QOH'] = $item_info['QOH'];
    		} else {
    			$data['QOH'] = '';
    		}
    
    		if (isset($input['ireorderpoint'])) {
    			$data['ireorderpoint'] = $input['ireorderpoint'];
    		} elseif (!empty($item_info)) {
    			$data['ireorderpoint'] = $item_info['ireorderpoint'];
    		} else {
    			$data['ireorderpoint'] = '';
    		}
    
    		if (isset($input['norderqtyupto'])) {
    			$data['norderqtyupto'] = $input['norderqtyupto'];
    		} elseif (!empty($item_info)) {
    			$data['norderqtyupto'] = $item_info['norderqtyupto'];
    		} else {
    			$data['norderqtyupto'] = '';
    		}
    
    		if (isset($input['nlevel2'])) {
    			$data['nlevel2'] = $input['nlevel2'];
    		} elseif (!empty($item_info)) {
    			$data['nlevel2'] = $item_info['nlevel2'];
    		} else {
    			$data['nlevel2'] = '';
    		}
    
    		if (isset($input['nlevel4'])) {
    			$data['nlevel4'] = $input['nlevel4'];
    		} elseif (!empty($item_info)) {
    			$data['nlevel4'] = $item_info['nlevel4'];
    		} else {
    			$data['nlevel4'] = '';
    		}
    
    		if (isset($input['visinventory'])) {
    			$data['visinventory'] = $input['visinventory'];
    		} elseif (!empty($item_info)) {
    			$data['visinventory'] = $item_info['visinventory'];
    		} else {
    			$data['visinventory'] = 'Yes';
    		}
    
    		if (isset($input['vageverify'])) {
    			$data['vageverify'] = $input['vageverify'];
    		} elseif (!empty($item_info)) {
    			$data['vageverify'] = $item_info['vageverify'];
    		} else {
    			$data['vageverify'] = '';
    		}
    
    		if (isset($input['nlevel3'])) {
    			$data['nlevel3'] = $input['nlevel3'];
    		} elseif (!empty($item_info)) {
    			$data['nlevel3'] = $item_info['nlevel3'];
    		} else {
    			$data['nlevel3'] = '';
    		}
    
    		if (isset($input['ndiscountper'])) {
    			$data['ndiscountper'] = $input['ndiscountper'];
    		} elseif (!empty($item_info)) {
    			$data['ndiscountper'] = $item_info['ndiscountper'];
    		} else {
    			$data['ndiscountper'] = '';
    		}
    
    		if (isset($input['vfooditem'])) {
    			$data['vfooditem'] = $input['vfooditem'];
    		} elseif (!empty($item_info)) {
    			$data['vfooditem'] = $item_info['vfooditem'];
    		} else {
    			$data['vfooditem'] = 'N';
    		}
            
            
            if (isset($input['vtax'])) {
            	$data['vtax'] = $input['vtax'];
            } elseif (!empty($item_info)) {
                
                if(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'Y'){
                    $data['vtax'] = 'vtax1';
                }elseif(!empty($item_info['vtax2']) && $item_info['vtax2'] == 'Y'){
            	    $data['vtax'] = 'vtax2';
                }elseif(!empty($item_info['vtax3']) && $item_info['vtax3'] == 'Y'){
            	    $data['vtax'] = 'vtax3';
                }elseif(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N'){
            	    $data['vtax'] = 'vnotax';
                }else{
                    $data['vtax'] = '';
                }
            } else {
            	$data['vtax'] = '';
            }
            
            $all_taxes = [
                            ['value'=>'vtax1', 'name'=>'Tax1'],
                            ['value'=>'vtax2', 'name'=>'Tax2'],
                            ['value'=>'vtax3', 'name'=>'Tax3'],
                            ['value'=>'vnotax', 'name'=>'Non Taxable']
                        ];

            $data['all_taxes'] = $all_taxes;
    
    		if (isset($input['itemimage'])) {
    			$data['itemimage'] = $input['itemimage'];
    		} elseif (!empty($item_info)) {
    			$data['itemimage'] = base64_encode($item_info['itemimage']);
    		} else {
    			$data['itemimage'] = '';
    		}
    
    		if (isset($input['vshowimage'])) {
    			$data['vshowimage'] = $input['vshowimage'];
    		} elseif (!empty($item_info)) {
    			$data['vshowimage'] = $item_info['vshowimage'];
    		} else {
    			$data['vshowimage'] = '';
    		}
    
    		if (isset($input['estatus'])) {
    			$data['estatus'] = $input['estatus'];
    		} elseif (!empty($item_info)) {
    			$data['estatus'] = $item_info['estatus'];
    		} else {
    			$data['estatus'] = '';
    		}
    
    		if (isset($input['ebottledeposit'])) {
    			$data['ebottledeposit'] = $input['ebottledeposit'];
    		} elseif (!empty($item_info)) {
    			$data['ebottledeposit'] = $item_info['ebottledeposit'];
    		} else {
    			$data['ebottledeposit'] = '';
    		}
    
    		if (isset($input['nbottledepositamt'])) {
    			$data['nbottledepositamt'] = $input['nbottledepositamt'];
    		} elseif (!empty($item_info)) {
    			$data['nbottledepositamt'] = $item_info['nbottledepositamt'];
    		} else {
    			$data['nbottledepositamt'] = '0.00';
    		}
    
    		if (isset($input['vbarcodetype'])) {
    			$data['vbarcodetype'] = $input['vbarcodetype'];
    		} elseif (!empty($item_info)) {
    			$data['vbarcodetype'] = $item_info['vbarcodetype'];
    		} else {
    			$data['vbarcodetype'] = '';
    		}
    
    		if (isset($input['vintage'])) {
    			$data['vintage'] = $input['vintage'];
    		} elseif (!empty($item_info)) {
    			$data['vintage'] = $item_info['vintage'];
    		} else {
    			$data['vintage'] = '';
    		}
    
    		if (isset($input['vdiscount'])) {
    			$data['vdiscount'] = $input['vdiscount'];
    		} elseif (!empty($item_info)) {
    			$data['vdiscount'] = $item_info['vdiscount'];
    		} else {
    			$data['vdiscount'] = '';
    		}
            
    
    		if (!empty($item_info)) {
    			$data['isparentchild'] = $item_info['isparentchild'];
    		} else {
    			$data['isparentchild'] = '';
    		}
    
    		if (!empty($item_info)) {
    			$data['parentid'] = $item_info['parentid'];
    		} else {
    			$data['parentid'] = '';
    		}
    
    		if (!empty($item_info)) {
    			$data['parentmasterid'] = $item_info['parentmasterid'];
    		} else {
    			$data['parentmasterid'] = '';
    		}
    
    		if (isset($input['unit_id']) || isset($input['unit_id'])) {
    			$data['unit_id'] = $input['unit_id'];
    			$data['unit_value'] = $input['unit_value'];
    		}else if (!empty($item_info) && !empty($unit_data)) {
    			$data['unit_id'] = $unit_data['unit_id'];
    			$data['unit_value'] = $unit_data['unit_value'];
    		} else {
    			$data['unit_id'] = '';
    			$data['unit_value'] = '';
    		}
    
    		if (isset($input['bucket_id']) ) {
    			$data['bucket_id'] = $input['bucket_id'];
    		} else if (!empty($item_info) && !empty($bucket_data)) {
    			$data['bucket_id'] = $bucket_data['bucket_id'];
    		} else {
    			$data['bucket_id'] = '';
    		}
    
    		if (isset($input['malt'])) {
    			$data['malt'] = $input['malt'];
    		} else if (!empty($item_info) && !empty($bucket_data)) {
    			$data['malt'] = $bucket_data['malt'];
    		} else {
    			$data['malt'] = '';
    		}
    
    		if (isset($input['plcb_options_checkbox'])) {
    			$data['plcb_options_checkbox'] = $input['plcb_options_checkbox'];
    		}else if (!empty($item_info) && !empty($bucket_data)) {
    			$data['plcb_options_checkbox'] = 1;
    		}else{
    			$data['plcb_options_checkbox'] = 0;
    		}
    		
    		//=============== Include new_costprice = New Cost ==============================
    		if (isset($input['new_costprice'])) {
    			$data['new_costprice'] = $input['new_costprice'];
    		} elseif (!empty($item_info)) {
    			$data['new_costprice'] = $item_info['new_costprice'];
    		} else {
    			$data['new_costprice'] = '';
    		}
    		
    		
    		//=============== Include lastcost = Last Cost ==============================
    		if (isset($input['last_costprice'])) {
    			$data['last_costprice'] = $input['last_costprice'];
    		} elseif (!empty($item_info)) {
    			$data['last_costprice'] = $item_info['last_costprice'];
    		} else {
    			$data['last_costprice'] = '';
    		}
    
    
    		$departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        
            $data['departments'] = $departments;



            $units = Unit::all()->toArray();
            
            $data['units'] = $units;

            $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
            
            $data['suppliers'] = $suppliers;

            $sizes = Size::all()->toArray();
            
            $data['sizes'] = $sizes;

            // $itemGroups = $this->model_administration_items->getItemGroups();
            
            // $data['itemGroups'] = $itemGroups;

            $ageVerifications = DB::connection('mysql_dynamic')->table('mst_ageverification')->get()->toArray();
            
            $data['ageVerifications'] = $ageVerifications;

            $stations = DB::connection('mysql_dynamic')->table('mst_station')->get()->toArray();
            
            $data['stations'] = $stations;

            $aisles = DB::connection('mysql_dynamic')->table('mst_aisle')->get()->toArray();
            
            $data['aisles'] = $aisles;

            $shelfs = DB::connection('mysql_dynamic')->table('mst_shelf')->get()->toArray();
            
            $data['shelfs'] = $shelfs;

            $shelvings = DB::connection('mysql_dynamic')->table('mst_shelving')->get()->toArray();
            
            $data['shelvings'] = $shelvings;

            if (!empty($item_info)) {
                // $loadChildProducts = $this->model_api_items->getChildProductsLoad();
                // $data['loadChildProducts'] = $loadChildProducts;
                $data['loadChildProducts'] = [];
            }

            $itemsUnits = DB::connection('mysql_dynamic')->table('mst_item_unit')->get()->toArray();
            
            $data['itemsUnits'] = $itemsUnits;

            $buckets = DB::connection('mysql_dynamic')->table('mst_item_bucket')->get()->toArray();

            $data['buckets'] = $buckets;
            
            
            $taxlist = DB::connection('mysql_dynamic')->table('mst_tax')->get()->toArray();
            
            $data['taxlist']=$taxlist;
            
    
    		return $data;
	
        }
        
        //============================================== OLD DATABASE ENDS ========================================
        // $tax_info = $this->model_api_items->gettaxinfo();
        // $data['tax_info']=$tax_info;

        $tab_selected = session()->get('tab_selected');
        if(isset($tab_selected)){
            $data['tab_selected'] = session()->get('tab_selected');
            session()->forget('tab_selected');
        }else{
            $data['tab_selected'] = '';
        }
        
        $data['text_form'] = !isset($this->request->get['iitemid']) ? 'Add Items' : 'Edit Items';
                
        $data['arr_y_n'][] = 'No';
        $data['arr_y_n'][] = 'Yes';

        $data['array_yes_no']['Y'] = 'Yes'; 
        $data['array_yes_no']['N'] = 'No';

        $data['array_status']['Active'] = 'Active'; 
        $data['array_status']['Inactive'] = 'Inactive';  

        $data['item_colors'] = array("None","AliceBlue","AntiqueWhite","Aqua","Aquamarine","Azure","Beige","Bisque","Black","BlanchedAlmond","Blue","BlueViolet","Brown","BurlyWood","CadetBlue","Chartreuse","Chocolate","Coral","CornflowerBlue","Cornsilk","Crimson","Cyan","DarkBlue","DarkCyan","DarkGoldenRod","DarkGray","DarkGrey","DarkGreen","DarkKhaki","DarkMagenta","DarkOliveGreen","Darkorange","DarkOrchid","DarkRed","DarkSalmon","DarkSeaGreen","DarkSlateBlue","DarkSlateGray","DarkSlateGrey","DarkTurquoise","DarkViolet","DeepPink","DeepSkyBlue","DimGray","DimGrey","DodgerBlue","FireBrick","FloralWhite","ForestGreen","Fuchsia","Gainsboro","GhostWhite","Gold","GoldenRod","Gray","Grey","Green","GreenYellow","HoneyDew","HotPink","IndianRed","Indigo","Ivory","Khaki","Lavender","LavenderBlush","LawnGreen","LemonChiffon","LightBlue","LightCoral","LightCyan","LightGoldenRodYellow","LightGray","LightGrey","LightGreen","LightPink","LightSalmon","LightSeaGreen","LightSkyBlue","LightSlateGray","LightSlateGrey","LightSteelBlue","LightYellow","Lime","LimeGreen","Linen","Magenta","Maroon","MediumAquaMarine","MediumBlue","MediumOrchid","MediumPurple","MediumSeaGreen","MediumSlateBlue","MediumSpringGreen","MediumTurquoise","MediumVioletRed","MidnightBlue","MintCream","MistyRose","Moccasin","NavajoWhite","Navy","OldLace","Olive","OliveDrab","Orange","OrangeRed","Orchid","PaleGoldenRod","PaleGreen","PaleTurquoise","PaleVioletRed","PapayaWhip","PeachPuff","Peru","Pink","Plum","PowderBlue","Purple","Red","RosyBrown","RoyalBlue","SaddleBrown","Salmon","SandyBrown","SeaGreen","SeaShell","Sienna","Silver","SkyBlue","SlateBlue","SlateGray","SlateGrey","Snow","SpringGreen","SteelBlue","Tan","Teal","Thistle","Tomato","Turquoise","Violet","Wheat","White","WhiteSmoke","Yellow","YellowGreen");

        $data['item_types'][] = 'Standard';
        $data['item_types'][] = 'Kiosk';
        $data['item_types'][] = 'Lot Matrix';
        // $data['item_types'][] = 'Gasoline';
        $data['item_types'][] = 'Instant';

        $data['barcode_types'][] = 'Code 128';
        $data['barcode_types'][] = 'Code 39';
        $data['barcode_types'][] = 'Code 93';
        $data['barcode_types'][] = 'UPC E';
        $data['barcode_types'][] = 'EAN 8';
        $data['barcode_types'][] = 'EAN 13';
        $data['barcode_types'][] = 'UPC A';

        
        // $error_warning_1= session()->get('error_warning_1');
        // if (isset($error_warning_1)) {
        //     $data['error_warning'] = session()->get('error_warning_1');

        //     session()->forget('error_warning_1');
        // } else {
        //     $data['error_warning'] = '';
        // }
        
        $error_warning= session()->get('error_warning');
        if (isset($error_warning)) {
            $data['error_warning'] = session()->get('error_warning');

            session()->forget('error_warning');
        } else {
            $data['error_warning'] = '';
        }
        
        
        
        $session_success = session()->get('success');
        if (isset($session_success)) {
            $data['success'] = session()->get('success');

            session()->forget('success');
        } else {
            $data['success'] = '';
        }

        
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => 'Home',
            'href' => url('/dashboard')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => 'Items',
            'href' => url('/items/item_list')
        );

        
        if (!isset($iitemid)) {
            $data['action'] = url('/item/add');

            $data['clone_item'] = '';
        } else { 
            $visited_zero_movement_report = session()->get('visited_zero_movement_report');
            $visited_below_cost_report = session()->get('visited_below_cost_report');
            if(isset($visited_zero_movement_report) || ( isset($input['visited_zero_movement_report']) && $input['visited_zero_movement_report'] =='Yes' )){
                
                $data['zero_movement_item_link']= url('/zeromovementreport?visited_zero_movement_report=Yes');
                session()->put('visited_zero_movement_report', 'Yes');
                $data['visited_zero_movement_report'] = session()->get('visited_zero_movement_report');	
                $data['action'] = url('/item/edit/'. $iitemid.'?visited_zero_movement_report=Yes');
               
            }elseif(isset($visited_below_cost_report) || ( isset($input['visited_below_cost_report']) && $input['visited_below_cost_report'] =='Yes' )){
                
                $data['below_cost_item_link']= url('/belowcostreport/getlist_belowcostreport?visited_below_cost_report=Yes');
                $visited_below_cost_report = 'Yes';
                $data['visited_below_cost_report'] = $visited_below_cost_report;
                $data['action'] = url('/item/edit/'. $iitemid.'?visited_below_cost_report=Yes');
                
            }else{
			    $data['action'] = url('/item/edit/'. $iitemid);
            }
            $data['clone_item'] = url('/item/clone_item/'.$iitemid);
        }
        
        $data['unset_visited_below_zero'] = url('/item/unset_visited_below_zero');
        $data['reorder_point_calculate_url'] = url('/item/calculateReorderPointAjax');

        $data['delete'] = url('/item/delete');
        $data['delete_vendor_code'] = url('/item/delete_vendor_code');
        $data['current_url'] = url('/item/item_list/Active/DESC');
        
        $data['action_vendor'] = url('/item/action_vendor');
        $data['action_vendor_editlist'] = url('/item/action_vendor_editlist');
        $data['add_alias_code'] = url('/item/add_alias_code');
        $data['alias_code_deletelist'] = url('/item/delete_alias_code');
        $data['add_lot_matrix'] = url('/item/add_lot_matrix');
        $data['lot_matrix_editlist'] = url('/item/lot_matrix_editlist');
        $data['lot_matrix_deletelist'] = url('/item/delete_lot_matrix');
        // $data['add_slab_price'] = url('api/items/add_slab_price');
        // $data['slab_price_editlist'] = url('/item/slab_price_editlist');
        // $data['slab_price_deletelist'] = url('api/items/slab_price_deletelist');
        // $data['add_parent_item'] = url('api/items/add_parent_item');
        // $data['action_remove_parent_item'] = url('api/items/remove_parent_item');
        $data['check_vendor_item_code'] = url('/item/check_vendor_item_code');
        $data['get_categories'] = url('item/get_categories');
        $data['get_subcategories_url'] = url('item/get_subcategories');        

        // $data['searchitem'] = url('/item/search');
        $data['parent_child_search'] = url('/item/parent_child_search');

        // urls for adding category, dept, etc
        $data['Sales'] = 'Sales';
        $data['MISC'] = 'MISC';
        $data['add_new_category'] = url('/item/category/add');
        
        $data['add_new_department'] = url('/item/department/add');
        $data['get_new_department'] = url('/item/department');
        
        $data['add_new_subcategory'] = url('/item/sub_category/add');

        $data['add_new_size'] = url('/item/size/add');
        $data['get_new_size'] = url('/item/size');

        $data['add_new_group'] = url('/item/group/add');
        $data['get_new_group'] = url('/item/group');

        $data['add_new_supplier'] = url('/item/supplier/add');
        $data['get_new_supplier'] = url('/item/supplier');
        
        $data['add_new_manufacturer'] = url('/item/manufacturer/add_manufacturer');
        $data['get_new_manufacturer'] = url('/item/manufacturer/get_manufacturer');
        
        // urls for adding category, dept, etc
        
        $data['sid'] = session()->get('sid');
        
        $data['cancel'] = url('/item/item_list/Active/DESC');
        
        $data['item_movement_data'] = url('/item/item_movement_report/item_movement_data');
        
        if (isset($iitemid)) {
            
            $item = new Item();
            $item_info = $item->getItem($iitemid);
            // $item_info = Item::where('iitemid', $iitemid)->get()->toArray();
            
            
            $promotion_data = $item->get_promotion_by_item($item_info['vbarcode']);
            $data['itemPromotion'] = $promotion_data;
            $sid= session()->get('sid'); 
            
            $tax_info = $item->gettaxinfo();
            $data['tax_info']=$tax_info;
           
            $data['iitemid'] = $iitemid;
            $data['edit_page'] = 'edit_page';
            $data['itemvendors'] = $item_info['itemvendors'];
            $data['itemalias'] = $item_info['itemalias'];
            $data['itempacks'] = $item_info['itempacks'];
            $data['itemslabprices'] = $item_info['itemslabprices'];
            $data['itemchilditems'] = $item_info['itemchilditems'];
            $data['itemparentitems'] = $item_info['itemparentitems'];
            $data['remove_parent_item'] = $item_info['remove_parent_item'];
            

            $unit_data = $item->getItemUnitData($iitemid);
            $bucket_data = $item->getItemBucketData($iitemid);
        }
        
        // if (isset($iitemid) && ($request->isMethod('post'))) {
        if (isset($iitemid)) {
            $data['edit_page'] = 'edit_page';
        }
        
        
        if (isset($input['iitemid'])) {
            $data['iitemid'] = $input['iitemid'];
        } elseif (!empty($item_info)) {
            $data['iitemid'] = $item_info['iitemid'];
        } else {
            $data['iitemid'] = '';
        }   
        
        if (isset($input['vitemtype'])) {
            $data['vitemtype'] = $input['vitemtype'];
        } elseif (!empty($item_info)) {
            $data['vitemtype'] = $item_info['vitemtype'];
        } else {
            $data['vitemtype'] = '';
        }
        
        if (isset($input['vitemcode'])) {
            $data['vitemcode'] = $input['vitemcode'];
        } elseif (!empty($item_info)) {
            $data['vitemcode'] = $item_info['vitemcode'];
        } else {
            $data['vitemcode'] = '';
        }
        
        if (isset($input['vbarcode'])) {
            $data['vbarcode'] = $input['vbarcode'];
        } elseif (!empty($item_info)) {
            $data['vbarcode'] = $item_info['vbarcode'];
            
            //get the buydown from the barcode
            // error_reporting(0);
            $bd_query = "SELECT tbd.buydown_amount tba FROM trn_buydown tb JOIN trn_buydown_details tbd on tb.buydown_id = tbd.buydown_id  
                        WHERE tbd.vbarcode = '".$item_info['vbarcode']."' AND tb.status = 'Active'";
            
            $run_bd_query = DB::connection('mysql_dynamic')->select($bd_query);
            // $data = isset($run_bd_query[0])?$run_bd_query[0]:[];

            $data['buydown'] = count($run_bd_query) > 0?$run_bd_query[0]->tba:0;
            
            
        } else {
            $data['vbarcode'] = '';
            $data['buydown'] = 0;
        }

        if (isset($input['vitemname'])) {
            $data['vitemname'] = $input['vitemname'];
        } elseif (!empty($item_info)) {
            $data['vitemname'] = $item_info['vitemname'];
        } else {
            $data['vitemname'] = '';
        }

        if (isset($input['vdescription'])) {
            $data['vdescription'] = $input['vdescription'];
        } elseif (!empty($item_info)) {
            $data['vdescription'] = $item_info['vdescription'];
        } else {
            $data['vdescription'] = '';
        }

        if (isset($input['vunitcode'])) {
            $data['vunitcode'] = $input['vunitcode'];
        } elseif (!empty($item_info)) {
            $data['vunitcode'] = $item_info['vunitcode'];
        } else {
            $data['vunitcode'] = '';
        }

        if (isset($input['vsuppliercode'])) {
            $data['vsuppliercode'] = $input['vsuppliercode'];
        } elseif (!empty($item_info)) {
            $data['vsuppliercode'] = $item_info['vsuppliercode'];
        } else {
            $data['vsuppliercode'] = '';
        }
        
        if (isset($input['vcategorycode'])) {
            $data['vcategorycode'] = $input['vcategorycode'];
        } elseif (!empty($item_info)) {
            $data['vcategorycode'] = $item_info['vcategorycode'];
        } else {
            $data['vcategorycode'] = '';
        }
         //taxlist return
        // if(isset($input['taxlist'])){
        //   $data['taxlist']=$input['taxlist'];
        // }elseif(!empty($item_tax_info)){
        // //   echo "<pre>"; print_r($item_tax_info);
        //   $data['taxlist11'] =$item_tax_info;
        // //   echo "<pre>"; print_r($data['taxlist']);die;
        // }
        // else{
        //     $data['taxlist']='';
        // }
         //done
            
        if((isset($item_info['vitemtype']) && $item_info['vitemtype'] != 'Instant') || (isset($input['vitemtype']) && $input['vitemtype'] != 'Instant')){
            
            if (isset($input['vdepcode']) && !empty($input['vdepcode'])) {
                
                $data['vdepcode'] = $input['vdepcode'];
                $categories = Category::where('dept_code', $input['vdepcode'])->orderBy('vcategoryname', 'ASC')->get()->toArray();
                $data['categories'] = $categories;
            } elseif (!empty($item_info)) {
                    
                $data['vdepcode'] = $item_info['vdepcode'];
                $categories = Category::where('dept_code', $item_info['vdepcode'])->orderBy('vcategoryname', 'ASC')->get()->toArray();
                $data['categories'] = $categories;
            } else {
                $data['vdepcode'] = '';
            }
        
            $data['subcategories'] = [];
            if (isset($input['subcat_id']) && !empty($input['subcat_id'])) {
                $data['subcat_id'] = $input['subcat_id'];
                $cat_id = Category::where('vcategorycode', $input['vcategorycode'])->get()->toArray();
                
                if(!empty($cat_id[0]))
                {
                    $subcategories = SubCategory::where('cat_id', $cat_id[0]['icategoryid'])->orderBy('subcat_name', 'ASC')->get()->toArray();
                    $data['cat_id'] = $cat_id[0]['icategoryid'];
                    $data['subcategories'] = $subcategories;
                }
                
            } elseif (!empty($item_info)) {
                $data['subcat_id'] = $item_info['subcat_id'];
                $cat_id = Category::where('vcategorycode', $item_info['vcategorycode'])->get()->toArray();
                if(!empty($cat_id))
                {
                    $subcategories = SubCategory::where('cat_id', $cat_id[0]['icategoryid'])->orderBy('subcat_name', 'ASC')->get()->toArray();
                    $data['cat_id'] = $cat_id[0]['icategoryid'];
                    $data['subcategories'] = $subcategories;
                }
                
            } else {
                $data['subcat_id'] = '';
            }
            
            
            if (isset($input['ireorderpoint']) && !empty($input['ireorderpoint'])) {
                $data['ireorderpoint'] = $input['ireorderpoint'];
            } elseif (!empty($item_info)) {
                if($item_info['ireorderpoint'] > 0)
                {
                    $data['ireorderpoint'] = $item_info['ireorderpoint'];
                }
                else
                {
                    $data['ireorderpoint'] = $this->calculateReorderPoint($data['vitemcode'],91);
                }
                
            } else {
                $data['ireorderpoint'] = '';
            }
        }
        
        // dd($item_info['vitemtype']);
        
        
        $data['manufacturers'] = [];
        if (isset($input['manufacturer_id'])) {
            $data['manufacturer_id'] = $input['manufacturer_id'];
        } elseif (!empty($item_info)) {
            $data['manufacturer_id'] = $item_info['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = '';
        }
        $manufacturers = Manufacturer::all()->toArray();
        
        $data['manufacturers'] = $manufacturers;
        // $data['subcategories'] = $subcategories;
        // $data['categories'] = $categories;

        if (isset($input['vsize'])) {
            $data['vsize'] = $input['vsize'];
        } elseif (!empty($item_info)) {
            $data['vsize'] = $item_info['vsize'];
        } else {
            $data['vsize'] = '';
        }

        // if (isset($input['iitemgroupid'])) {
        //     $data['iitemgroupid'] = $input['iitemgroupid'];
        // } elseif (!empty($item_info)) {
        //     $data['iitemgroupid'] = isset($item_info['iitemgroupid']['iitemgroupid']) ? $item_info['iitemgroupid']['iitemgroupid']: '';
        // } else {
        //     $data['iitemgroupid'] = '';
        // }

        if (isset($input['wicitem'])) {
            $data['wicitem'] = $input['wicitem'];
        } elseif (!empty($item_info)) {
            $data['wicitem'] = $item_info['wicitem'];
        } else {
            $data['wicitem'] = '';
        }

        // if (isset($input['vsequence'])) {
        //     $data['vsequence'] = $input['vsequence'];
        // } elseif (!empty($item_info)) {
        //     $data['vsequence'] = $item_info['vsequence'];
        // } else {
        //     $data['vsequence'] = '';
        // }

        if (isset($input['vcolorcode'])) {
            $data['vcolorcode'] = $input['vcolorcode'];
        } elseif (!empty($item_info)) {
            $data['vcolorcode'] = $item_info['vcolorcode'];
        } else {
            $data['vcolorcode'] = '';
        }

        if (isset($input['npack'])) {
            $data['npack'] = $input['npack'];
        } elseif (!empty($item_info)) {
            $data['npack'] = $item_info['npack'];
        } else {
            $data['npack'] = 1;
        }

        if (isset($input['dcostprice'])) {
            $data['dcostprice'] = $input['dcostprice'];
        } elseif (!empty($item_info)) {
            $data['dcostprice'] = $item_info['dcostprice'];
        } else {
            $data['dcostprice'] = '';
        }

        if (isset($input['nunitcost'])) {
            $data['nunitcost'] = $input['nunitcost'];
        } elseif (!empty($item_info)) {
            $data['nunitcost'] = $item_info['nunitcost'];
        } else {
            $data['nunitcost'] = '';
        }
        // dd($data['nunitcost']);
        if (isset($input['nsellunit'])) {
            $data['nsellunit'] = $input['nsellunit'];
        } elseif (!empty($item_info)) {
            $data['nsellunit'] = $item_info['nsellunit'];
        } else {
            $data['nsellunit'] = '';
        }

        if (isset($input['nsaleprice'])) {
            $data['nsaleprice'] = $input['nsaleprice'];
        } elseif (!empty($item_info)) {
            $data['nsaleprice'] = $item_info['nsaleprice'];
        } else {
            $data['nsaleprice'] = '';
        }

        if (isset($input['dunitprice'])) {
            $data['dunitprice'] = $input['dunitprice'];
        } elseif (!empty($item_info)) {
            $data['dunitprice'] = number_format($item_info['dunitprice'], 2);
        } else {
            $data['dunitprice'] = '0.00';
        }

        if (isset($input['profit_margin'])) {
            $data['profit_margin'] = $input['profit_margin'];
        } else {
            $data['profit_margin'] = '';
        }

        // if (isset($input['liability'])) {
        //     $data['liability'] = $input['liability'];
        // } elseif (!empty($item_info)) {
        //     $data['liability'] = $item_info['liability'];
        // } else {
        //     $data['liability'] = 'N';
        // }

        // if (isset($input['vshowsalesinzreport'])) {
        //     $data['vshowsalesinzreport'] = $input['vshowsalesinzreport'];
        // } elseif (!empty($item_info)) {
        //     $data['vshowsalesinzreport'] = $item_info['vshowsalesinzreport'];
        // } else {
        //     $data['vshowsalesinzreport'] = '';
        // }

        if (isset($input['stationid'])) {
            $data['stationid'] = $input['stationid'];
        } elseif (!empty($item_info)) {
            $data['stationid'] = $item_info['stationid'];
        } else {
            $data['stationid'] = '';
        }

        if (isset($input['aisleid'])) {
            $data['aisleid'] = $input['aisleid'];
        } elseif (!empty($item_info)) {
            $data['aisleid'] = $item_info['aisleid'];
        } else {
            $data['aisleid'] = '';
        }

        if (isset($input['shelfid'])) {
            $data['shelfid'] = $input['shelfid'];
        } elseif (!empty($item_info)) {
            $data['shelfid'] = $item_info['shelfid'];
        } else {
            $data['shelfid'] = '';
        }

        if (isset($input['shelvingid'])) {
            $data['shelvingid'] = $input['shelvingid'];
        } elseif (!empty($item_info)) {
            $data['shelvingid'] = $item_info['shelvingid'];
        } else {
            $data['shelvingid'] = '';
        }

        if (isset($input['iqtyonhand'])) {
            $data['iqtyonhand'] = $input['iqtyonhand'];
        } elseif (!empty($item_info)) {
            $data['iqtyonhand'] = $item_info['iqtyonhand'];
        } else {
            $data['iqtyonhand'] = '';
        }

        if (isset($input['QOH'])) {
            $data['QOH'] = $input['QOH'];
        } elseif (!empty($item_info)) {
            $data['QOH'] = $item_info['QOH'];
        } else {
            $data['QOH'] = '';
        }
        
        
        
        if (isset($input['reorder_duration'])) {
            $data['reorder_duration'] = $input['reorder_duration'];
        } elseif (!empty($item_info)) {
            $data['reorder_duration'] = $item_info['reorder_duration'] > 0 ? $item_info['reorder_duration'] : 91;
        } else {
            $data['reorder_duration'] = '';
        }
        
        // if (isset($input['ireorderpointdays'])) {
        //     $data['ireorderpointdays'] = $input['ireorderpointdays'];
        // } elseif (!empty($item_info)) {
        //     $data['ireorderpointdays'] = $item_info['ireorderpointdays'] > 0 ? $item_info['ireorderpointdays'] : 91;
        // } else {
        //     $data['ireorderpointdays'] = '';
        // }

        if (isset($input['norderqtyupto'])) {
            $data['norderqtyupto'] = $input['norderqtyupto'];
        } elseif (!empty($item_info)) {
            $data['norderqtyupto'] = $item_info['norderqtyupto'];
        } else {
            $data['norderqtyupto'] = '';
        }

        if (isset($input['nlevel2'])) {
            $data['nlevel2'] = $input['nlevel2'];
        } elseif (!empty($item_info)) {
            $data['nlevel2'] = $item_info['nlevel2'];
        } else {
            $data['nlevel2'] = '';
        }

        if (isset($input['nlevel4'])) {
            $data['nlevel4'] = $input['nlevel4'];
        } elseif (!empty($item_info)) {
            $data['nlevel4'] = $item_info['nlevel4'];
        } else {
            $data['nlevel4'] = '';
        }

        // if (isset($input['visinventory'])) {
        //     $data['visinventory'] = $input['visinventory'];
        // } elseif (!empty($item_info)) {
        //     $data['visinventory'] = $item_info['visinventory'];
        // } else {
        //     $data['visinventory'] = 'Yes';
        // }

        if (isset($input['vageverify'])) {
            $data['vageverify'] = $input['vageverify'];
        } elseif (!empty($item_info)) {
            $data['vageverify'] = $item_info['vageverify'];
        } else {
            $data['vageverify'] = '';
        }

        if (isset($input['nlevel3'])) {
            $data['nlevel3'] = $input['nlevel3'];
        } elseif (!empty($item_info)) {
            $data['nlevel3'] = $item_info['nlevel3'];
        } else {
            $data['nlevel3'] = '';
        }

        if (isset($input['ndiscountper'])) {
            $data['ndiscountper'] = $input['ndiscountper'];
        } elseif (!empty($item_info) && isset($item_info['ndiscountper'])) {
            $data['ndiscountper'] = $item_info['ndiscountper'];
        } else {
            $data['ndiscountper'] = '';
        }

        if (isset($input['vfooditem'])) {
            $data['vfooditem'] = $input['vfooditem'];
        } elseif (!empty($item_info)) {
            $data['vfooditem'] = $item_info['vfooditem'];
        } else {
            $data['vfooditem'] = 'N';
        }
        
        // dd($item_info);
            if (isset($input['vtax'])) {
            	$data['vtax'] = $input['vtax'];
            } elseif (!empty($item_info)) {
                
                if(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'Y'){
                    $data['vtax'] = 'vtax1';
                }elseif(!empty($item_info['vtax2']) && $item_info['vtax2'] == 'Y'){
            	    $data['vtax'] = 'vtax2';
                }elseif(!empty($item_info['vtax3']) && $item_info['vtax3'] == 'Y'){
            	    $data['vtax'] = 'vtax3';
                }elseif(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N' && !empty($item_info['vtax3']) && $item_info['vtax3'] == 'N'){
            	    $data['vtax'] = 'vnotax';
                }elseif(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N' && empty($item_info['vtax3'])){
            	    $data['vtax'] = 'vnotax';
                }elseif(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'N' && !empty($item_info['vtax3']) && $item_info['vtax3'] == 'N' && empty($item_info['vtax2'])){
            	    $data['vtax'] = 'vnotax';
                }elseif(empty($item_info['vtax1']) && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N' && !empty($item_info['vtax3']) && $item_info['vtax3'] == 'N'){
            	    $data['vtax'] = 'vnotax';
                }else{
                    $data['vtax'] = '';
                }
            } else {
            	$data['vtax'] = '';
            }
            
            $all_taxes = [
                            ['value'=>'vtax1', 'name'=>'Tax1'],
                            ['value'=>'vtax2', 'name'=>'Tax2'],
                            ['value'=>'vtax3', 'name'=>'Tax3'],
                            ['value'=>'vnotax', 'name'=>'Non Taxable']
                        ];
                        
        if (isset($input['ndiscountper'])) {
            $data['buydown'] = $input['ndiscountper'];
        } elseif (!empty($item_info['ndiscountper'])) {
            $data['buydown'] = $item_info['ndiscountper'];
        } else {
            $data['buydown'] = '';
        }
        
        $data['all_taxes'] = $all_taxes;
        
        if (isset($input['itemimage'])) {
            $data['itemimage'] = $input['itemimage'];
        } elseif (!empty($item_info)) {
            $data['itemimage'] = base64_encode($item_info['itemimage']);
        } else {
            $data['itemimage'] = '';
        }
        
        if (isset($input['vshowimage'])) {
            $data['vshowimage'] = $input['vshowimage'];
        } elseif (!empty($item_info)) {
            $data['vshowimage'] = $item_info['vshowimage'];
        } else {
            $data['vshowimage'] = '';
        }
        
        if (isset($input['estatus'])) {
            $data['estatus'] = $input['estatus'];
        } elseif (!empty($item_info)) {
            $data['estatus'] = $item_info['estatus'];
        } else {
            $data['estatus'] = '';
        }

        if (isset($input['ebottledeposit'])) {
            $data['ebottledeposit'] = $input['ebottledeposit'];
        } elseif (!empty($item_info)) {
            $data['ebottledeposit'] = $item_info['ebottledeposit'];
        } else {
            $data['ebottledeposit'] = '';
        }

        if (isset($input['nbottledepositamt'])) {
            $data['nbottledepositamt'] = $input['nbottledepositamt'];
        } elseif (!empty($item_info)) {
            $data['nbottledepositamt'] = $item_info['nbottledepositamt'];
        } else {
            $data['nbottledepositamt'] = '0.00';
        }

        if (isset($input['vbarcodetype'])) {
            $data['vbarcodetype'] = $input['vbarcodetype'];
        } elseif (!empty($item_info)) {
            $data['vbarcodetype'] = $item_info['vbarcodetype'];
        } else {
            $data['vbarcodetype'] = '';
        }

        // if (isset($input['vintage'])) {
        //     $data['vintage'] = $input['vintage'];
        // } elseif (!empty($item_info)) {
        //     $data['vintage'] = $item_info['vintage'];
        // } else {
        //     $data['vintage'] = '';
        // }

        if (isset($input['vdiscount'])) {
            $data['vdiscount'] = $input['vdiscount'];
        } elseif (!empty($item_info)) {
            $data['vdiscount'] = $item_info['vdiscount'];
        } else {
            $data['vdiscount'] = '';
        }

        if (isset($input['rating'])) {
            $data['rating'] = $input['rating'];
        } elseif (!empty($item_info)) {
            $data['rating'] = $item_info['rating'];
        } else {
            $data['rating'] = '';
        }

        if (!empty($item_info)) {
            $data['isparentchild'] = $item_info['isparentchild'];
        } else {
            $data['isparentchild'] = '';
        }

        if (!empty($item_info)) {
            $data['parentid'] = $item_info['parentid'];
        } else {
            $data['parentid'] = '';
        }

        if (!empty($item_info)) {
            $data['parentmasterid'] = $item_info['parentmasterid'];
        } else {
            $data['parentmasterid'] = '';
        }

        if (isset($input['unit_id']) || isset($input['unit_id'])) {
            $data['unit_id'] = $input['unit_id'];
            $data['unit_value'] = $input['unit_value'];
        }else if (!empty($item_info) && !empty($unit_data)) {
            $data['unit_id'] = $unit_data['unit_id'];
            $data['unit_value'] = $unit_data['unit_value'];
        } else {
            $data['unit_id'] = '';
            $data['unit_value'] = '';
        }

        if (isset($input['bucket_id']) ) {
            $data['bucket_id'] = $input['bucket_id'];
        } else if (!empty($item_info) && !empty($bucket_data)) {
            $data['bucket_id'] = $bucket_data['bucket_id'];
        } else {
            $data['bucket_id'] = '';
        }
        
        if (isset($input['malt'])) {
            $data['malt'] = $input['malt'];
        } else if (!empty($item_info) && !empty($bucket_data)) {
            $data['malt'] = $bucket_data['malt'];
        } else {
            $data['malt'] = '';
        }

        if (isset($input['plcb_options_checkbox'])) {
            $data['plcb_options_checkbox'] = $input['plcb_options_checkbox'];
        }else if (!empty($item_info) && !empty($bucket_data)) {
            $data['plcb_options_checkbox'] = 1;
        }else{
            $data['plcb_options_checkbox'] = 0;
        }
        
        //=============== Include new_costprice = New Cost ==============================
        if (isset($input['new_costprice'])) {
            $data['new_costprice'] = $input['new_costprice'];
        } elseif (!empty($item_info)) {
            $data['new_costprice'] = number_format($item_info['new_costprice'], 2);
        } else {
            $data['new_costprice'] = '0.00';
        }
        
        //print_r($item_info); exit;
        
        
        //=============== Include lastcost = Last Cost ==============================
        if (isset($input['last_costprice'])) {
            $data['last_costprice'] = $input['last_costprice'];
        } elseif (!empty($item_info)) {
            $data['last_costprice'] = $item_info['last_costprice'];
        } else {
            $data['last_costprice'] = '';
        }
        
        

        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        
        $data['departments'] = $departments;



        $units = Unit::all()->toArray();
        
        $data['units'] = $units;

        $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
        
        $data['suppliers'] = $suppliers;
        
        $sizes = Size::all()->toArray();
        
        $data['sizes'] = $sizes;

        // $itemGroups = $this->model_administration_items->getItemGroups();
        
        // $data['itemGroups'] = $itemGroups;

        $ageVerifications = DB::connection('mysql_dynamic')->table('mst_ageverification')->get()->toArray();
        
        $data['ageVerifications'] = $ageVerifications;

        $stations = DB::connection('mysql_dynamic')->table('mst_station')->get()->toArray();
        
        $data['stations'] = $stations;

        $aisles = DB::connection('mysql_dynamic')->table('mst_aisle')->get()->toArray();
        
        $data['aisles'] = $aisles;

        $shelfs = DB::connection('mysql_dynamic')->table('mst_shelf')->get()->toArray();
        
        $data['shelfs'] = $shelfs;

        $shelvings = DB::connection('mysql_dynamic')->table('mst_shelving')->get()->toArray();
        
        $data['shelvings'] = $shelvings;

        if (!empty($item_info)) {
            // $loadChildProducts = $this->model_api_items->getChildProductsLoad();
            // $data['loadChildProducts'] = $loadChildProducts;
            $data['loadChildProducts'] = [];
        }

        $itemsUnits = DB::connection('mysql_dynamic')->table('mst_item_unit')->get()->toArray();
        
        $data['itemsUnits'] = $itemsUnits;
        
        $buckets = DB::connection('mysql_dynamic')->table('mst_item_bucket')->get()->toArray();

        $data['buckets'] = $buckets;
        
        
        $taxlist = DB::connection('mysql_dynamic')->table('mst_tax')->get()->toArray();
        
        $data['taxlist']=$taxlist;
        // dd(compact('data'));
        return $data;

    }
    
    public function clone_item_form($iitemid, Request $request) {     
        
        session()->forget('error_warning');
        
        $input = $request->all();
        
        
        $data = $this->getCloneForm($input, $iitemid);

        return view('items.clone_item_form',compact('data'));

    }
    
    public function clone_item(Request $request)
    {
        if ($request->isMethod('post')) {
            
            $input = $request->all();
            $data = $this->getCloneForm($input, $input['clone_item_id']);
            $check = true;
            $new_database = session()->get('new_database');
            if($new_database === false){
                // =============================================================== OLD DATABASE ====================================

                // if (!$this->user->hasPermission('modify', 'administration/items')) {
                //     $this->error['warning'] = $this->language->get('error_permission');
                // }

                if (($input['vbarcode'] == '')) {
                    $data['error_vbarcode'] = 'Please Enter SKU';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_vbarcode'] = '';
                }
                
                            
                if($input['vitemtype'] != 'Instant'){
                    
                    if (($input['vitemname'] == '')) {
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $data['error_vitemname'] = 'Please Enter Item Name';
                        $check = false;
                    } else {
                        $data['error_vitemname'] = '';
                    }
                    
                    if (($input['vunitcode'] == '')) {
                        $data['error_vunitcode'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vunitcode'] = '';
                    }
                    
                    if (($input['vsuppliercode'] == '')) {
                        $data['error_vsuppliercode'] = 'Please Select Supplier';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vsuppliercode'] = '';
                    }
                    
                    if (($input['vdepcode'] == '')) {
                        $data['error_vdepcode']= 'Please Select Department';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }else{
                        $data['error_vdepcode'] = '';
                    }
                        
                    if (($input['vcategorycode'] == '')) {
                        $data['error_vcategorycode'] = 'Please Select Category';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vcategorycode'] = '';
                    }
                    
                    if (($input['new_costprice'] == '')) {
                        $data['error_new_costprice'] = 'Please Enter Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_new_costprice'] = '';
                    }
    
                    if (($input['dunitprice'] == '')) {
                        $data['error_dunitprice'] = 'Please Enter Selling Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_dunitprice'] = '';
                    }
                }else{
                    
                    if (($input['ticket_name'] == '')) {
                        $data['error_ticket_name'] = 'Please Enter Item Name';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_name'] = '';
                    }
                    
                    if (($input['book_cost'] == '')) {
                        $data['error_book_cost'] = 'Please Enter Book Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_book_cost'] = '';
                    }
                    
                    if (($input['ticket_price'] == '')) {
                        $data['error_ticket_price'] = 'Please Item Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_price'] = '';
                    }
                }
                    
                if($input['vbarcode']){

                    $olditem = new Olditem();
                    $unique_sku = $olditem->getSKU($input['vbarcode']);
                                //   print_r($unique_sku); die;

                        if(count($unique_sku) > 0){
                            $data['error_vbarcode'] = 'SKU Already Exist';
                            $data['error_warning'] = 'Please check the form carefully for errors!';
                            $check = false;
                        }
                }

                if (isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1) {
                    
                    if (($input['unit_id'] == '')) {
                        $data['error_unit_id'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_id'] = '';
                    }

                    if (($input['unit_value'] == '')) {
                        $data['error_unit_value'] = 'Please Enter Unit Value';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_value'] = '';
                    }

                    if (($input['bucket_id'] == '')) {
                        $data['error_bucket_id'] = 'Please Select Bucket';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_bucket_id'] = '';
                    }
                }

                
                // if ($this->error && !isset($this->error['warning'])) {
                //     $this->error['warning'] = $this->language->get('error_warning');
                // }
                
            
            } else {
                // =============================================================== NEW DATABASE ====================================
                
                // if (!$this->user->hasPermission('modify', 'administration/items')) {
                //     $this->error['warning'] = $this->language->get('error_permission');
                // }

                if (($input['vbarcode'] == '')) {
                    $data['error_vbarcode'] = 'Please Enter SKU';
                    $data['error_warning'] = 'Please check the form carefully for errors!';
                    $check = false;
                } else {
                    $data['error_vbarcode'] = '';
                }
                
                          
                if($input['vitemtype'] != 'Instant'){
                    
                    if (($input['vitemname'] == '')) {
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $data['error_vitemname'] = 'Please Enter Item Name';
                        $check = false;
                    } else {
                        $data['error_vitemname'] = '';
                    }
                    
                    if (($input['vunitcode'] == '')) {
                        $data['error_vunitcode'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vunitcode'] = '';
                    }
                    
                    if (($input['vsuppliercode'] == '')) {
                        $data['error_vsuppliercode'] = 'Please Select Supplier';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vsuppliercode'] = '';
                    }
                    
                    if (($input['vdepcode'] == '')) {
                        $data['error_vdepcode']= 'Please Select Department';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    }else{
                        $data['error_vdepcode'] = '';
                    }
                        
                    if (($input['vcategorycode'] == '')) {
                        $data['error_vcategorycode'] = 'Please Select Sub Category';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_vcategorycode'] = '';
                    }
                    
                    // if (($input['subcat_id'] == '')) {
                    //     $data['error_subcat_id'] = 'Please Select Category';
                    //     $data['error_warning'] = 'Please check the form carefully for errors!';
                    //     $check = false;
                    // } else {
                    //     $data['error_subcat_id'] = '';
                    // }
                    
                    if (($input['new_costprice'] == '')) {
                        $data['error_new_costprice'] = 'Please Enter Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_new_costprice'] = '';
                    }
    
                    if (($input['dunitprice'] == '')) {
                        $data['error_dunitprice'] = 'Please Enter Selling Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_dunitprice'] = '';
                    }
                }else{
                    
                    if (($input['ticket_name'] == '')) {
                        $data['error_ticket_name'] = 'Please Enter Ticket Name';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_name'] = '';
                    }
                    
                    if (($input['book_cost'] == '')) {
                        $data['error_book_cost'] = 'Please Enter Book Cost';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_book_cost'] = '';
                    }
                    
                    if (($input['ticket_price'] == '')) {
                        $data['error_ticket_price'] = 'Please Ticket Price';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_ticket_price'] = '';
                    }
                }
                    
                if($input['vbarcode']){

                    $item = new Item();
                    $unique_sku = $item->getSKU($input['vbarcode']);
                                //   print_r($unique_sku); die;

                        if(count($unique_sku) > 0){
                            $data['error_vbarcode'] = 'SKU Already Exist';
                            $data['error_warning'] = 'Please check the form carefully for errors!';
                            $check = false;
                        }
                }

                if (isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1) {
                    
                    if (($input['unit_id'] == '')) {
                        $data['error_unit_id'] = 'Please Select Unit';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_id'] = '';
                    }

                    if (($input['unit_value'] == '')) {
                        $data['error_unit_value'] = 'Please Enter Unit Value';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_unit_value'] = '';
                    }

                    if (($input['bucket_id'] == '')) {
                        $data['error_bucket_id'] = 'Please Select Bucket';
                        $data['error_warning'] = 'Please check the form carefully for errors!';
                        $check = false;
                    } else {
                        $data['error_bucket_id'] = '';
                    }
                }
                
            }
            
            if($check == false){
                // print_r("aa");
                // dd($data);
                return view('items.clone_item_form',compact('data'));

            }elseif($check == true){
                // print_r("bb");
                // dd($data);
                $new_database = session()->get('new_database');

                if($new_database === false){
                    // =============================================================== OLD DATABASE ======================================================
                    if(isset($input['itemimage']) && $input['itemimage']['name'] != ''){
                        $img_string = file_get_contents($input['itemimage']['tmp_name']);
                    }else{
                        $img_string = NULL;
                    }
        
                    
                    if(isset($input['vtax']) && $input['vtax'] != ''){
                        
                        $vtax = $input['vtax'];
                        
                        if($vtax == 'vtax1'){
                            $vtax1 = 'Y';
                        }else{
                            $vtax1 = 'N';
                        }
                        
                        if($vtax == 'vtax2'){
                            $vtax2 = 'Y';
                        }else{
                            $vtax2 = 'N';
                        }
                        
                        if($vtax == 'vtax3'){
                            $vtax3 = 'Y';
                        }else{
                            $vtax3 = 'N';
                        }
                        
                        if($vtax == 'vnotax'){
                            $vtax1 = 'N';
                            $vtax2 = 'N';
                            $vtax3 = 'N';
                        }
                        
                    }else{
                        $vtax1 = 'N';
                        $vtax2 = 'N';
                        $vtax3 = 'N';
                    }
                    

                    if(isset($input['npack']) && $input['npack'] == ''){
                        $npack = '1';
                    }else{
                        $npack = $input['npack'];
                    }
        
                    if(isset($input['nsellunit']) && $input['nsellunit'] == ''){
                        $nsellunit = '1';
                    }else{
                        $nsellunit = $input['nsellunit'];
                    }
        

        
                    if(isset($input['nbottledepositamt']) && ($input['nbottledepositamt'] == '0.00' || $input['nbottledepositamt'] == '')){
                        $nbottledepositamt = '0.00';
                        $ebottledeposit = 'No';
                    }else{
                        $nbottledepositamt = (float)$input['nbottledepositamt'];
                        $ebottledeposit = 'Yes';
                    }
        
                    if(isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1){
        
                        $options_data['unit_id'] = $input['unit_id'];
                        $options_data['unit_value'] = $input['unit_value'];
                        $options_data['bucket_id'] = $input['bucket_id'];
                        if(isset($input['malt']) && $input['malt'] == 1){
                            $options_data['malt'] = $input['malt'];
                        }else{
                            $options_data['malt'] = 0;
                        }
        
                    }else{
                        $options_data = array();
                    }
        
                    if($input['vitemtype'] == 'Instant'){
                        
                        if(isset($input['games_per_book']) && $input['games_per_book'] == ''){
                            $npack = '1';
                        }else{
                            $npack = $input['games_per_book'];
                        }
                        
                        if(isset($input['book_qoh']) && $input['book_qoh'] == ''){
                            $iqtyonhand = '0';
                        }else{
                            $iqtyonhand = $input['book_qoh'];
                        }
                        
                        $liability = 'N';
                        $estatus = 'Active';
                        $visinventory = 'Yes';
                        
                        $temp_arr = array(
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['ticket_name']),
                                        "vbarcode" => $input['vbarcode'],
                                        "iqtyonhand" => $iqtyonhand,
                                        "dcostprice" => $input['book_cost'],
                                        "dunitprice" => $input['ticket_price'],
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "npack" => $npack,
                                        "visinventory" => $visinventory,
                                        "liability" => $liability,
                                        "vtax1" => 'N',
                                        "vtax2" => 'N',
                                        "vtax3" => 'N',
                                        "estatus" => $estatus
                                    );
                                
                        // $this->model_api_olditems->addLotteryItems($temp_arr);
                        $olditem = new Olditem();   
                        $last_iitemid = $olditem->addLotteryItems($temp_arr);
                                    
                    }else{
                        
                        $liability = 'Y';
                        $visinventory = 'Yes';
                        
                        $dcostprice = $input['new_costprice'];
                        // $nunitcost = $dcostprice/$npack;
                        $nunitcost = $dcostprice/$nsellunit;
                        
                        $temp_arr = array(
                                        // "iitemgroupid" => $input['iitemgroupid'],
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        "webstore" => "0",
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['vitemname']),
                                        "vunitcode" => $input['vunitcode'],
                                        "vbarcode" => $input['vbarcode'],
                                        "vpricetype" => "",
                                        "vcategorycode" => $input['vcategorycode'],
                                        "vdepcode" => $input['vdepcode'],
                                        "subcat_id" => $input['subcat_id'],
                                        "vsuppliercode" => $input['vsuppliercode'],
                                        "iqtyonhand" => $input['iqtyonhand'],
                                        "ireorderpoint" => $input['ireorderpoint'],
                                        "reorder_duration" => $input['reorder_duration'],
                                        "manufacturer_id" => $input['manufacturer_id'],
                                        // "ireorderpointdays" => $input['ireorderpointdays'],
                                        "new_costprice" => $input['new_costprice'],
                                        // "dcostprice" => $input['dcostprice'],
                                        "dcostprice" => $dcostprice,
                                        "dunitprice" => $input['dunitprice'],
                                        "nsaleprice" => 0,
                                        "nlevel2" => $input['nlevel2'] ?? 0,
                                        "nlevel3" => $input['nlevel3'] ?? 0,
                                        "nlevel4" => $input['nlevel4'] ?? 0,
                                        "iquantity" => "0",
                                        "ndiscountper" => $input['ndiscountper'],
                                        "ndiscountamt" => "0.00",
                                        "vtax1" => $vtax1,
                                        "vtax2" => $vtax2,
                                        "vtax3" => $vtax3,
                                        "vfooditem" => $input['vfooditem'],
                                        "vdescription" => htmlspecialchars_decode($input['vdescription']),
                                        "dlastsold" => null,
                                        "visinventory" => $visinventory,
                                        "dpricestartdatetime" => null,
                                        "dpriceenddatetime" => null,
                                        "estatus" => $input['estatus'],
                                        "nbuyqty" => "0",
                                        "ndiscountqty" => "0",
                                        "nsalediscountper" => "0.00",
                                        "vshowimage" => $input['vshowimage'],
                                        "itemimage" => $img_string,
                                        "vageverify" => $input['vageverify'],
                                        "ebottledeposit" => $ebottledeposit,
                                        "nbottledepositamt" => $nbottledepositamt,
                                        "vbarcodetype" => $input['vbarcodetype'],
                                        "ntareweight" => "0.00",
                                        "ntareweightper" => "0.00",
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "dlastreceived" => null,
                                        "dlastordered" => null,
                                        "nlastcost" => "0.00",
                                        "nonorderqty" => "0",
                                        "vparentitem" => "0",
                                        "nchildqty" => "0.00",
                                        "vsize" => $input['vsize'],
                                        "npack" => $npack,
                                        "nunitcost" => $nunitcost,
                                        "ionupload" => "0",
                                        "nsellunit" => $nsellunit,
                                        "ilotterystartnum" => "0",
                                        "ilotteryendnum" => "0",
                                        "etransferstatus" => "",
                                        // "vsequence" => $input['vsequence'],
                                        // "vcolorcode" => $input['vcolorcode'],
                                        "vcolorcode" => 'None',
                                        "vdiscount" => $input['vdiscount'],
                                        "norderqtyupto" => $input['norderqtyupto'],
                                        // "vshowsalesinzreport" => $input['vshowsalesinzreport'],
                                        "iinvtdefaultunit" => "0",
                                        "stationid" => $input['stationid'],
                                        "shelfid" => $input['shelfid'],
                                        "aisleid" => $input['aisleid'],
                                        "shelvingid" => $input['shelvingid'],
                                        // "rating" => $input['rating'],
                                        // "vintage" => $input['vintage'],
                                        "PrinterStationId" => "0",
                                        "liability" => $liability,
                                        "isparentchild" => "0",
                                        "parentid" => "0",
                                        "parentmasterid" => "0",
                                        "wicitem" => $input['wicitem'],
                                        "options_data" => $options_data,
                                        
                                    );
                                    
                        
                        $olditem = new Olditem();   
                        $last_iitemid = $olditem->addItems($temp_arr);
                    }
        
                    // 			$this->model_api_olditems->addItems($temp_arr);
                    if(isset($last_iitemid['error_tax3'])){
                        session()->put('error_warning', $last_iitemid['error_tax3']);
                        // echo 1525; die;
                        $this->getForm($input);
                    }else{
                        session()->put('success', 'Successfully Added Item');
                    }
                    
                    $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$input['clone_item_id'] . "'");
                    $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
                    unset($old_item_values['itemimage']);
        
                    $x_general = new \stdClass();
                    $x_general->old_item_values = $old_item_values;
                    
                    $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$last_iitemid['iitemid'] . "'");
                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                    unset($new_item_values['itemimage']);
                    $x_general->new_item_values = $new_item_values;
        
                    $x_general = json_encode($x_general);
        
                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $last_iitemid['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($new_item_values['vbarcode']) . "', type = 'Clone', oldamount = '0', newamount = '0',general = '" . $x_general . "', source = 'CloneItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                    
                    $url = '/item/item_list/Active/DESC';
                        
                    return redirect($url);
                    

                } else { 
                    // =============================================================== NEW DATABASE ======================================================
                    if(isset($input['itemimage']) && $input['itemimage']['name'] != ''){
                        $img_string = file_get_contents($input['itemimage']['tmp_name']);
                    }else{
                        $img_string = NULL;
                    }
                    
                                        
                    if(isset($input['vtax']) && $input['vtax'] != ''){
                        
                        $vtax = $input['vtax'];
                        
                        if($vtax == 'vtax1'){
                            $vtax1 = 'Y';
                        }else{
                            $vtax1 = 'N';
                        }
                        
                        if($vtax == 'vtax2'){
                            $vtax2 = 'Y';
                        }else{
                            $vtax2 = 'N';
                        }
                        
                        if($vtax == 'vtax3'){
                            $vtax3 = 'Y';
                        }else{
                            $vtax3 = 'N';
                        }
                        
                        if($vtax == 'vnotax'){
                            $vtax1 = 'N';
                            $vtax2 = 'N';
                            $vtax3 = 'N';
                        }
                        
                    }else{
                        $vtax1 = 'N';
                        $vtax2 = 'N';
                        $vtax3 = 'N';
                    }
                    
                
                    if(isset($input['npack']) && $input['npack'] == ''){
                        $npack = '1';
                    }else{
                        $npack = $input['npack'];
                    }
        
                    if(isset($input['nsellunit']) && $input['nsellunit'] == ''){
                        $nsellunit = '1';
                    }else{
                        $nsellunit = $input['nsellunit'];
                    }
        
                    
                    if(isset($input['nbottledepositamt']) && ($input['nbottledepositamt'] == '0.00' || $input['nbottledepositamt'] == '')){
                        $nbottledepositamt = '0.00';
                        $ebottledeposit = 'No';
                    }else{
                        $nbottledepositamt = (float)$input['nbottledepositamt'];
                        $ebottledeposit = 'Yes';
                    }
        
                    if(isset($input['plcb_options_checkbox']) && $input['plcb_options_checkbox'] == 1){
        
                        $options_data['unit_id'] = $input['unit_id'];
                        $options_data['unit_value'] = $input['unit_value'];
                        $options_data['bucket_id'] = $input['bucket_id'];
                        if(isset($input['malt']) && $input['malt'] == 1){
                            $options_data['malt'] = $input['malt'];
                        }else{
                            $options_data['malt'] = 0;
                        }
                        
                    }else{
                        $options_data = array();
                    }
                    
                    $check_error='';

                    if($input['vitemtype'] == 'Instant'){
                        
                        /*====== games_per_book = npack and book_qoh = QOH only for Lotter items =========
                            In this case if book_qoh = 5 and games_per_book = 50
                            then total games = 250
                        */
                        if(isset($input['games_per_book']) && $input['games_per_book'] == ''){
                            $npack = '1';
                        }else{
                            $npack = $input['games_per_book'];
                        }
                        
                        if(isset($input['book_qoh']) && $input['book_qoh'] == ''){
                            $iqtyonhand = '0';
                        }else{
                            $iqtyonhand = $input['book_qoh'];
                        }
                        
                        $liability = 'N';
                        $estatus = 'Active';
                        $visinventory = 'Yes';
                        
                        $temp_arr = array(
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['ticket_name']),
                                        "vbarcode" => $input['vbarcode'],
                                        "iqtyonhand" => $iqtyonhand,
                                        "dcostprice" => $input['book_cost'],
                                        "dunitprice" => $input['ticket_price'],
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "npack" => $npack,
                                        "visinventory" => $visinventory,
                                        "liability" => $liability,
                                        "vtax1" => 'N',
                                        "vtax2" => 'N',
                                        "vtax3" => 'N',
                                        "estatus" => $estatus
                                    );
                        // dd($temp_arr);     
                        $item = new Item();
                        $last_iitemid = $item->addLotteryItems($temp_arr);
                                    
                    }else{
                        
                        // $liability = 'Y';
                        $liability = 'N';
                        $visinventory = 'Yes';
                        // dd($input['new_costprice']);
                        $dcostprice = $input['new_costprice'];
                        // $nunitcost = $dcostprice/$npack;
                        $nunitcost = $dcostprice/$nsellunit;
                        
                        $vfooditem = isset($input['vfooditem'])?$input['vfooditem']:'N';

                        
                        $temp_arr = array(
                                        // "iitemgroupid" => $input['iitemgroupid'],
                                        "stores_hq" => isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid'),
                                        "webstore" => "0",
                                        "vitemtype" => $input['vitemtype'],
                                        "vitemname" => htmlspecialchars_decode($input['vitemname']),
                                        "vunitcode" => $input['vunitcode'],
                                        "vbarcode" => $input['vbarcode'],
                                        "vpricetype" => "",
                                        "vcategorycode" => $input['vcategorycode'],
                                        "vdepcode" => $input['vdepcode'],
                                        "subcat_id" => $input['subcat_id'],
                                        "vsuppliercode" => $input['vsuppliercode'],
                                        "iqtyonhand" => $input['iqtyonhand'],
                                        "ireorderpoint" => $input['ireorderpoint'],
                                        "reorder_duration" => $input['reorder_duration'],
                                        "manufacturer_id" => $input['manufacturer_id'],
                                        // "ireorderpointdays" => $input['ireorderpointdays'],
                                        "new_costprice" => $input['new_costprice'],
                                        // "dcostprice" => $input['dcostprice'],
                                        "dcostprice" => $dcostprice,
                                        "dunitprice" => $input['dunitprice'],
                                        "nsaleprice" => 0,
                                        "nlevel2" => $input['nlevel2'] ?? 0,
                                        "nlevel3" => $input['nlevel3'] ?? 0,
                                        "nlevel4" => $input['nlevel4'] ?? 0,
                                        "iquantity" => "0",
                                        "ndiscountper" => $input['ndiscountper'],
                                        "ndiscountamt" => "0.00",
                                        "vtax1" => $vtax1,
                                        "vtax2" => $vtax2,
                                        "vtax3" => $vtax3,
                                        "vfooditem" => $vfooditem,
                                        "vdescription" => htmlspecialchars_decode($input['vdescription']),
                                        "dlastsold" => null,
                                        "visinventory" => $visinventory,
                                        "dpricestartdatetime" => null,
                                        "dpriceenddatetime" => null,
                                        "estatus" => $input['estatus'],
                                        "nbuyqty" => "0",
                                        "ndiscountqty" => "0",
                                        "nsalediscountper" => "0.00",
                                        "vshowimage" => $input['vshowimage'],
                                        "itemimage" => $img_string,
                                        "vageverify" => $input['vageverify'],
                                        "ebottledeposit" => $ebottledeposit,
                                        "nbottledepositamt" => $nbottledepositamt,
                                        "vbarcodetype" => $input['vbarcodetype'],
                                        "ntareweight" => "0.00",
                                        "ntareweightper" => "0.00",
                                        "dcreated" => date('Y-m-d'),
                                        "dlastupdated" => date('Y-m-d'),
                                        "dlastreceived" => null,
                                        "dlastordered" => null,
                                        "nlastcost" => "0.00",
                                        "nonorderqty" => "0",
                                        "vparentitem" => "0",
                                        "nchildqty" => "0.00",
                                        "vsize" => $input['vsize'],
                                        "npack" => $npack,
                                        "nunitcost" => $nunitcost,
                                        "ionupload" => "0",
                                        "nsellunit" => $nsellunit,
                                        "ilotterystartnum" => "0",
                                        "ilotteryendnum" => "0",
                                        "etransferstatus" => "",
                                        // "vsequence" => $input['vsequence'],
                                        // "vcolorcode" => $input['vcolorcode'],
                                        "vcolorcode" => 'None',
                                        "vdiscount" => $input['vdiscount'],
                                        "norderqtyupto" => $input['norderqtyupto'],
                                        // "vshowsalesinzreport" => $input['vshowsalesinzreport'],
                                        "iinvtdefaultunit" => "0",
                                        "stationid" => $input['stationid'],
                                        "shelfid" => $input['shelfid'],
                                        "aisleid" => $input['aisleid'],
                                        "shelvingid" => $input['shelvingid'],
                                        // "rating" => $input['rating'],
                                        // "vintage" => $input['vintage'],
                                        "PrinterStationId" => "0",
                                        "liability" => $liability,
                                        "isparentchild" => "0",
                                        "parentid" => "0",
                                        "parentmasterid" => "0",
                                        "wicitem" => $input['wicitem'],
                                        "options_data" => $options_data,
                                        
                                    );
                        // dd($temp_arr);      
                        $item = new Item();   
                        $last_iitemid = $item->addItems($temp_arr);
                    }
                    
                    // dd($last_iitemid);
                    
                    if(isset($last_iitemid['error_tax3'])){
                        session()->put('error_warning', $last_iitemid['error_tax3']);
                        // echo 1525; die;
                        $this->getForm($input);
                    }elseif(isset($last_iitemid['error'])){
                        session()->put('error_warning', $last_iitemid['error']);
                        $this->getForm($input);
                    
                    }else{
                        session()->put('success', 'Successfully Added Item');
                        
                        $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$input['clone_item_id'] . "'");
                        $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
                        unset($old_item_values['itemimage']);
                        
                        $x_general = new \stdClass();
                        $x_general->old_item_values = $old_item_values;
                        
                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$last_iitemid['iitemid'] . "'");
                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                        unset($new_item_values['itemimage']);
                        $x_general->new_item_values = $new_item_values;
                        
                        $x_general = json_encode($x_general);
                        
                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $last_iitemid['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($new_item_values['vbarcode']) . "', type = 'Clone', oldamount = '0', newamount = '0',general = '" . $x_general . "', source = 'CloneItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                    }
                    $url = '/item/item_list/Active/DESC';
                        
                    return redirect($url);
                    
                }

            }
        }
    }
        
    
    protected function getCloneForm($input, $iitemid) {
        
        $new_database = session()->get('new_database');
        
        if($new_database === false){
            // =================================================== OLD DATABASE ===================================================
            $tab_selected = session()->get('tab_selected');
            // if(isset($tab_selected)){
            //     $data['tab_selected'] = $tab_selected;
            // }else{
            //     $data['tab_selected'] = '';
            // }

            $tab_selected = session()->get('tab_selected');
    		if(isset($tab_selected)){
    			$data['tab_selected'] = session()->get('tab_selected');
    		}else{
    			$data['tab_selected'] = '';
    		}
            
            $data['text_form'] = 'Clone Items';
            
            $data['arr_y_n'][] = 'No';
            $data['arr_y_n'][] = 'Yes';

            $data['array_yes_no']['Y'] = 'Yes'; 
            $data['array_yes_no']['N'] = 'No';

            $data['array_status']['Active'] = 'Active'; 
            $data['array_status']['Inactive'] = 'Inactive';  

            $data['item_colors'] = array("None","AliceBlue","AntiqueWhite","Aqua","Aquamarine","Azure","Beige","Bisque","Black","BlanchedAlmond","Blue","BlueViolet","Brown","BurlyWood","CadetBlue","Chartreuse","Chocolate","Coral","CornflowerBlue","Cornsilk","Crimson","Cyan","DarkBlue","DarkCyan","DarkGoldenRod","DarkGray","DarkGrey","DarkGreen","DarkKhaki","DarkMagenta","DarkOliveGreen","Darkorange","DarkOrchid","DarkRed","DarkSalmon","DarkSeaGreen","DarkSlateBlue","DarkSlateGray","DarkSlateGrey","DarkTurquoise","DarkViolet","DeepPink","DeepSkyBlue","DimGray","DimGrey","DodgerBlue","FireBrick","FloralWhite","ForestGreen","Fuchsia","Gainsboro","GhostWhite","Gold","GoldenRod","Gray","Grey","Green","GreenYellow","HoneyDew","HotPink","IndianRed","Indigo","Ivory","Khaki","Lavender","LavenderBlush","LawnGreen","LemonChiffon","LightBlue","LightCoral","LightCyan","LightGoldenRodYellow","LightGray","LightGrey","LightGreen","LightPink","LightSalmon","LightSeaGreen","LightSkyBlue","LightSlateGray","LightSlateGrey","LightSteelBlue","LightYellow","Lime","LimeGreen","Linen","Magenta","Maroon","MediumAquaMarine","MediumBlue","MediumOrchid","MediumPurple","MediumSeaGreen","MediumSlateBlue","MediumSpringGreen","MediumTurquoise","MediumVioletRed","MidnightBlue","MintCream","MistyRose","Moccasin","NavajoWhite","Navy","OldLace","Olive","OliveDrab","Orange","OrangeRed","Orchid","PaleGoldenRod","PaleGreen","PaleTurquoise","PaleVioletRed","PapayaWhip","PeachPuff","Peru","Pink","Plum","PowderBlue","Purple","Red","RosyBrown","RoyalBlue","SaddleBrown","Salmon","SandyBrown","SeaGreen","SeaShell","Sienna","Silver","SkyBlue","SlateBlue","SlateGray","SlateGrey","Snow","SpringGreen","SteelBlue","Tan","Teal","Thistle","Tomato","Turquoise","Violet","Wheat","White","WhiteSmoke","Yellow","YellowGreen");

            $data['item_types'][] = 'Standard';
            $data['item_types'][] = 'Kiosk';
            $data['item_types'][] = 'Lot Matrix';
            // $data['item_types'][] = 'Gasoline';
            $data['item_types'][] = 'Instant';

            $data['barcode_types'][] = 'Code 128';
            $data['barcode_types'][] = 'Code 39';
            $data['barcode_types'][] = 'Code 93';
            $data['barcode_types'][] = 'UPC E';
            $data['barcode_types'][] = 'EAN 8';
            $data['barcode_types'][] = 'EAN 13';
            $data['barcode_types'][] = 'UPC A';

            $error_warning = session()->get('warning');
            if (isset($error_warning)) {
                $data['error_warning'] = session()->get('warning');
    
                session()->forget('warning');
            } else {
                $data['error_warning'] = '';
            }
            
            
            $session_success = session()->get('success');
            if (isset($session_success)) {
                $data['success'] = session()->get('success');
    
                session()->forget('success');
            } else {
                $data['success'] = '';
            }
    
            
            
            $data['breadcrumbs'] = array();
    
            $data['breadcrumbs'][] = array(
                'text' => 'Home',
                'href' => url('/dashboard')
            );
            
            $data['breadcrumbs'][] = array(
                'text' => 'Items',
                'href' => url('/items/item_list')
            );

            $data['action_vendor'] = url('/item/action_vendor');
            $data['action_vendor_editlist'] = url('/item/action_vendor_editlist');
            $data['add_alias_code'] = url('/item/add_alias_code');
            $data['alias_code_deletelist'] = url('/item/delete_alias_code');
            $data['add_lot_matrix'] = url('/item/add_lot_matrix');
            $data['lot_matrix_editlist'] = url('/item/lot_matrix_editlist');
            $data['lot_matrix_deletelist'] = url('/item/delete_lot_matrix');
            // $data['add_slab_price'] = url('api/items/add_slab_price');
            // $data['slab_price_editlist'] = url('/item/slab_price_editlist');
            // $data['slab_price_deletelist'] = url('api/items/slab_price_deletelist');
            // $data['add_parent_item'] = url('api/items/add_parent_item');
            // $data['action_remove_parent_item'] = url('api/items/remove_parent_item');
            $data['check_vendor_item_code'] = url('/item/check_vendor_item_code');
            $data['get_categories'] = url('item/get_categories');
            $data['get_subcategories_url'] = url('item/get_subcategories');        

            // $data['searchitem'] = url('/item/search');
            $data['parent_child_search'] = url('/item/parent_child_search');


            $data['action'] = url('/item/clone_item');
            
            // urls for adding category, dept, etc
            $data['Sales'] = 'Sales';
            $data['MISC'] = 'MISC';
            $data['add_new_category'] = url('/item/category/add');
        
            $data['add_new_department'] = url('/item/department/add');
            $data['get_new_department'] = url('/item/department');
            
            
            $data['add_new_size'] = url('/item/size/add');
            $data['get_new_size'] = url('/item/size');

            $data['add_new_group'] = url('/item/group/add');
            $data['get_new_group'] = url('/item/group');

            $data['add_new_supplier'] = url('/item/vendor/add');
            $data['get_new_supplier'] = url('api/vendor');
            
            $data['add_new_manufacturer'] = url('/item/manufacturer/add_manufacturer');
            $data['get_new_manufacturer'] = url('/item/manufacturer/get_manufacturer');
            // urls for adding category, dept, etc

            $data['sid'] = session()->get('sid');

            $data['cancel'] = url('item/item_list/Active/DESC');

            $this->load->model('api/olditems');

            if(isset($input['clone_item_id'])){
                $clone_item_id = $input['clone_item_id'];
            }else{
                $clone_item_id = $iitemid;
            }
            
            $olditem = new Olditem();
            $item_info = $olditem->getItem($clone_item_id);
            
            
            $unit_data = $olditem->getItemUnitData($clone_item_id);
            $bucket_data = $olditem->getItemBucketData($clone_item_id);
            
            $item = new Item();
            $tax_info = $item->gettaxinfo();
            $data['tax_info']=$tax_info;

            $data['itemvendors'] = array();
            $data['itemalias'] = array();
            $data['itempacks'] = array();
            $data['itemslabprices'] = array();
            $data['itemchilditems'] = array();
            $data['itemparentitems'] = array();
            $data['remove_parent_item'] = array();
            
            // $data['token'] = $this->session->data['token'];

            $data['iitemid'] = '';

            $data['clone_item_id'] = $clone_item_id;
        
            if (isset($input['vitemtype'])) {
                $data['vitemtype'] = $input['vitemtype'];
            } elseif (!empty($item_info)) {
                $data['vitemtype'] = $item_info['vitemtype'];
            } else {
                $data['vitemtype'] = '';
            }

            if (isset($input['vitemcode'])) {
                $data['vitemcode'] = $input['vitemcode'];
            } else {
                $data['vitemcode'] = '';
            }

            if (isset($input['vbarcode'])) {
                $data['vbarcode'] = $input['vbarcode'];
            } else {
                $data['vbarcode'] = '';
            }

            if (isset($input['vitemname'])) {
                $data['vitemname'] = $input['vitemname'];
            } elseif (!empty($item_info)) {
                $data['vitemname'] = $item_info['vitemname'];
            } else {
                $data['vitemname'] = '';
            }

            if (isset($input['vdescription'])) {
                $data['vdescription'] = $input['vdescription'];
            } else {
                $data['vdescription'] = '';
            }

            if (isset($input['vunitcode'])) {
                $data['vunitcode'] = $input['vunitcode'];
            } elseif (!empty($item_info)) {
                $data['vunitcode'] = $item_info['vunitcode'];
            } else {
                $data['vunitcode'] = '';
            }

            if (isset($input['vsuppliercode'])) {
                $data['vsuppliercode'] = $input['vsuppliercode'];
            } elseif (!empty($item_info)) {
                $data['vsuppliercode'] = $item_info['vsuppliercode'];
            } else {
                $data['vsuppliercode'] = '';
            }

            if (isset($input['vdepcode'])) {
                $data['vdepcode'] = $input['vdepcode'];
            } elseif (!empty($item_info)) {
                $data['vdepcode'] = $item_info['vdepcode'];
            } else {
                $data['vdepcode'] = '';
            }

            if (isset($input['vcategorycode'])) {
                $data['vcategorycode'] = $input['vcategorycode'];
            } elseif (!empty($item_info)) {
                $data['vcategorycode'] = $item_info['vcategorycode'];
            } else {
                $data['vcategorycode'] = '';
            }
            
        

            if (isset($input['vsize'])) {
                $data['vsize'] = $input['vsize'];
            } elseif (!empty($item_info)) {
                $data['vsize'] = $item_info['vsize'];
            } else {
                $data['vsize'] = '';
            }

            if (isset($input['iitemgroupid'])) {
                $data['iitemgroupid'] = $input['iitemgroupid'];
            } elseif (!empty($item_info)) {
                $data['iitemgroupid'] = isset($item_info['iitemgroupid']['iitemgroupid']) ? $item_info['iitemgroupid']['iitemgroupid']: '';
            } else {
                $data['iitemgroupid'] = '';
            }

            if (isset($input['wicitem'])) {
                $data['wicitem'] = $input['wicitem'];
            } else {
                $data['wicitem'] = '0';
            }

            if (isset($input['vsequence'])) {
                $data['vsequence'] = $input['vsequence'];
            } elseif (!empty($item_info)) {
                $data['vsequence'] = $item_info['vsequence'];
            } else {
                $data['vsequence'] = '';
            }

            if (isset($input['vcolorcode'])) {
                $data['vcolorcode'] = $input['vcolorcode'];
            } elseif (!empty($item_info)) {
                $data['vcolorcode'] = $item_info['vcolorcode'];
            } else {
                $data['vcolorcode'] = '';
            }

            if (isset($input['npack'])) {
                $data['npack'] = $input['npack'];
            } elseif (!empty($item_info)) {
                $data['npack'] = $item_info['npack'];
            } else {
                $data['npack'] = '';
            }

            if (isset($input['dcostprice'])) {
                $data['dcostprice'] = $input['dcostprice'];
            } elseif (!empty($item_info)) {
                $data['dcostprice'] = $item_info['dcostprice'];
            } else {
                $data['dcostprice'] = '';
            }

            if (isset($input['nunitcost'])) {
                $data['nunitcost'] = $input['nunitcost'];
            } elseif (!empty($item_info)) {
                $data['nunitcost'] = $item_info['nunitcost'];
            } else {
                $data['nunitcost'] = '';
            }

            if (isset($input['nsellunit'])) {
                $data['nsellunit'] = $input['nsellunit'];
            } elseif (!empty($item_info)) {
                $data['nsellunit'] = $item_info['nsellunit'];
            } else {
                $data['nsellunit'] = '';
            }

            if (isset($input['nsaleprice'])) {
                $data['nsaleprice'] = $input['nsaleprice'];
            } elseif (!empty($item_info)) {
                $data['nsaleprice'] = $item_info['nsaleprice'];
            } else {
                $data['nsaleprice'] = '';
            }

            if (isset($input['dunitprice'])) {
                $data['dunitprice'] = $input['dunitprice'];
            } elseif (!empty($item_info)) {
                $data['dunitprice'] = $item_info['dunitprice'];
            } else {
                $data['dunitprice'] = '';
            }

            if (isset($input['profit_margin'])) {
                $data['profit_margin'] = $input['profit_margin'];
            } else {
                $data['profit_margin'] = '';
            }

            if (isset($input['liability'])) {
                $data['liability'] = $input['liability'];
            } else {
                $data['liability'] = 'N';
            }

            if (isset($input['vshowsalesinzreport'])) {
                $data['vshowsalesinzreport'] = $input['vshowsalesinzreport'];
            } else {
                $data['vshowsalesinzreport'] = 'No';
            }

            if (isset($input['stationid'])) {
                $data['stationid'] = $input['stationid'];
            } elseif (!empty($item_info)) {
                $data['stationid'] = $item_info['stationid'];
            } else {
                $data['stationid'] = '';
            }

            if (isset($input['aisleid'])) {
                $data['aisleid'] = $input['aisleid'];
            } elseif (!empty($item_info)) {
                $data['aisleid'] = $item_info['aisleid'];
            } else {
                $data['aisleid'] = '';
            }

            if (isset($input['shelfid'])) {
                $data['shelfid'] = $input['shelfid'];
            } elseif (!empty($item_info)) {
                $data['shelfid'] = $item_info['shelfid'];
            } else {
                $data['shelfid'] = '';
            }

            if (isset($input['shelvingid'])) {
                $data['shelvingid'] = $input['shelvingid'];
            } elseif (!empty($item_info)) {
                $data['shelvingid'] = $item_info['shelvingid'];
            } else {
                $data['shelvingid'] = '';
            }
                
            if (isset($input['iqtyonhand'])) {
                $data['iqtyonhand'] = $input['iqtyonhand'];
            } else {
                $data['iqtyonhand'] = '';
            }

            if (isset($input['QOH'])) {
                $data['QOH'] = $input['QOH'];
            } else {
                $data['QOH'] = '';
            }
            
            if (isset($input['ireorderpoint'])) {
                $data['ireorderpoint'] = $input['ireorderpoint'];
            } elseif (!empty($item_info)) {
                $data['ireorderpoint'] = $item_info['ireorderpoint'];
            } else {
                $data['ireorderpoint'] = '';
            }

            if (isset($input['norderqtyupto'])) {
                $data['norderqtyupto'] = $input['norderqtyupto'];
            } elseif (!empty($item_info)) {
                $data['norderqtyupto'] = $item_info['norderqtyupto'];
            } else {
                $data['norderqtyupto'] = '';
            }

            if (isset($input['nlevel2'])) {
                $data['nlevel2'] = $input['nlevel2'];
            } elseif (!empty($item_info)) {
                $data['nlevel2'] = $item_info['nlevel2'];
            } else {
                $data['nlevel2'] = '';
            }

            if (isset($input['nlevel4'])) {
                $data['nlevel4'] = $input['nlevel4'];
            } elseif (!empty($item_info)) {
                $data['nlevel4'] = $item_info['nlevel4'];
            } else {
                $data['nlevel4'] = '';
            }

            if (isset($input['visinventory'])) {
                $data['visinventory'] = $input['visinventory'];
            } else {
                $data['visinventory'] = 'Yes';
            }

            if (isset($input['vageverify'])) {
                $data['vageverify'] = $input['vageverify'];
            } elseif (!empty($item_info)) {
                $data['vageverify'] = $item_info['vageverify'];
            } else {
                $data['vageverify'] = '';
            }

            if (isset($input['nlevel3'])) {
                $data['nlevel3'] = $input['nlevel3'];
            } elseif (!empty($item_info)) {
                $data['nlevel3'] = $item_info['nlevel3'];
            } else {
                $data['nlevel3'] = '';
            }

            if (isset($input['ndiscountper'])) {
                $data['ndiscountper'] = $input['ndiscountper'];
            } elseif (!empty($item_info)) {
                $data['ndiscountper'] = $item_info['ndiscountper'];
            } else {
                $data['ndiscountper'] = '';
            }

            if (isset($input['vfooditem'])) {
                $data['vfooditem'] = $input['vfooditem'];
            } else {
                $data['vfooditem'] = 'N';
            }



            if (isset($input['vtax'])) {
                $data['vtax'] = $input['vtax'];
            } elseif (!empty($item_info)) {
                
                if(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'Y'){
                    $data['vtax'] = 'vtax1';
                }elseif(!empty($item_info['vtax2']) && $item_info['vtax2'] == 'Y'){
                    $data['vtax'] = 'vtax2';
                }elseif(!empty($item_info['vtax3']) && $item_info['vtax3'] == 'Y'){
                    $data['vtax'] = 'vtax3';
                }elseif(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N'){
            	    $data['vtax'] = 'vnotax';
                }else{
                    $data['vtax'] = '';
                }
            } else {
                $data['vtax'] = '';
            }
            
            $all_taxes = [
                            ['value'=>'vtax1', 'name'=>'Tax1'],
                            ['value'=>'vtax2', 'name'=>'Tax2'],
                            ['value'=>'vtax3', 'name'=>'Tax3'],
                            ['value'=>'vnotax', 'name'=>'Non Taxable']
                        ];

        
            $data['all_taxes'] = $all_taxes;

            if (isset($input['itemimage'])) {
                $data['itemimage'] = $input['itemimage'];
            } else {
                $data['itemimage'] = '';
            }

            if (isset($input['vshowimage'])) {
                $data['vshowimage'] = $input['vshowimage'];
            } else {
                $data['vshowimage'] = '';
            }

            if (isset($input['estatus'])) {
                $data['estatus'] = $input['estatus'];
            } elseif (!empty($item_info)) {
                $data['estatus'] = $item_info['estatus'];
            } else {
                $data['estatus'] = '';
            }

            if (isset($input['ebottledeposit'])) {
                $data['ebottledeposit'] = $input['ebottledeposit'];
            } else {
                $data['ebottledeposit'] = 'No';
            }

            if (isset($input['nbottledepositamt'])) {
                $data['nbottledepositamt'] = $input['nbottledepositamt'];
            } else {
                $data['nbottledepositamt'] = '0.00';
            }

            if (isset($input['vbarcodetype'])) {
                $data['vbarcodetype'] = $input['vbarcodetype'];
            } elseif (!empty($item_info)) {
                $data['vbarcodetype'] = $item_info['vbarcodetype'];
            } else {
                $data['vbarcodetype'] = '';
            }

            if (isset($input['vintage'])) {
                $data['vintage'] = $input['vintage'];
            } elseif (!empty($item_info)) {
                $data['vintage'] = $item_info['vintage'];
            } else {
                $data['vintage'] = '';
            }

            if (isset($input['vdiscount'])) {
                $data['vdiscount'] = $input['vdiscount'];
            } elseif (!empty($item_info)) {
                $data['vdiscount'] = $item_info['vdiscount'];
            } else {
                $data['vdiscount'] = '';
            }

            if (isset($input['rating'])) {
                $data['rating'] = $input['rating'];
            } elseif (!empty($item_info)) {
                $data['rating'] = $item_info['rating'];
            } else {
                $data['rating'] = '';
            }

            if (isset($input['unit_id']) || isset($input['unit_id'])) {
                $data['unit_id'] = $input['unit_id'];
                $data['unit_value'] = $input['unit_value'];
            }else if (!empty($item_info) && !empty($unit_data)) {
                $data['unit_id'] = $unit_data['unit_id'];
                $data['unit_value'] = $unit_data['unit_value'];
            } else {
                $data['unit_id'] = '';
                $data['unit_value'] = '';
            }

            if (isset($input['bucket_id']) ) {
                $data['bucket_id'] = $input['bucket_id'];
            } else if (!empty($item_info) && !empty($bucket_data)) {
                $data['bucket_id'] = $bucket_data['bucket_id'];
            } else {
                $data['bucket_id'] = '';
            }

            if (isset($input['malt'])) {
                $data['malt'] = $input['malt'];
            } else if (!empty($item_info) && !empty($bucket_data)) {
                $data['malt'] = $bucket_data['malt'];
            } else {
                $data['malt'] = '';
            }

            if (isset($input['plcb_options_checkbox'])) {
                $data['plcb_options_checkbox'] = $input['plcb_options_checkbox'];
            }else if (!empty($item_info) && !empty($bucket_data)) {
                $data['plcb_options_checkbox'] = 1;
            }else{
                $data['plcb_options_checkbox'] = 0;
            }

            
            $data['isparentchild'] = '';
        
            $data['parentid'] = '';

            $data['parentmasterid'] = '';

            $departments = $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
            
            $data['departments'] = $departments;

            $categories = SubCategory::orderBy('subcat_name', 'ASC')->get()->toArray();
            
            $data['categories'] = $categories;

            $units = Unit::all()->toArray();
            
            $data['units'] = $units;

            $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
            
            $data['suppliers'] = $suppliers;

            $sizes = Size::all()->toArray();
            
            $data['sizes'] = $sizes;

            // $itemGroups = $this->model_administration_olditems->getItemGroups();
            
            // $data['itemGroups'] = $itemGroups;

            $ageVerifications = DB::connection('mysql_dynamic')->table('mst_ageverification')->get()->toArray();
            
            $data['ageVerifications'] = $ageVerifications;

            $stations = DB::connection('mysql_dynamic')->table('mst_station')->get()->toArray();
            
            $data['stations'] = $stations;

            $aisles = DB::connection('mysql_dynamic')->table('mst_aisle')->get()->toArray();
            
            $data['aisles'] = $aisles;

            $shelfs = DB::connection('mysql_dynamic')->table('mst_shelf')->get()->toArray();
            
            $data['shelfs'] = $shelfs;

            $shelvings = DB::connection('mysql_dynamic')->table('mst_shelving')->get()->toArray();
            
            $data['shelvings'] = $shelvings;

            // $loadChildProducts = $this->model_api_olditems->getChildProductsLoad();

            // $data['loadChildProducts'] = $loadChildProducts;

            $itemsUnits = DB::connection('mysql_dynamic')->table('mst_item_unit')->get()->toArray();

            $data['itemsUnits'] = $itemsUnits;

            $buckets = DB::connection('mysql_dynamic')->table('mst_item_bucket')->get()->toArray();

            $data['buckets'] = $buckets;

            $this->response->setOutput($this->load->view('administration/clone_item_form', $data));            
        } else {
            // =================================================== NEW DATABASE ===================================================
            // $tab_selected = session()->get('tab_selected');
            // if(isset($tab_selected)){
            //     $data['tab_selected'] = $tab_selected;
            // }else{
            //     $data['tab_selected'] = '';
            // }
            

            $tab_selected = session()->get('tab_selected');
    		if(isset($tab_selected)){
    			$data['tab_selected'] = session()->get('tab_selected');
    		}else{
    			$data['tab_selected'] = '';
    		}
            
            $data['text_form'] = 'Clone Items';
            
            $data['arr_y_n'][] = 'No';
            $data['arr_y_n'][] = 'Yes';

            $data['array_yes_no']['Y'] = 'Yes'; 
            $data['array_yes_no']['N'] = 'No';

            $data['array_status']['Active'] = 'Active'; 
            $data['array_status']['Inactive'] = 'Inactive';  

            $data['item_colors'] = array("None","AliceBlue","AntiqueWhite","Aqua","Aquamarine","Azure","Beige","Bisque","Black","BlanchedAlmond","Blue","BlueViolet","Brown","BurlyWood","CadetBlue","Chartreuse","Chocolate","Coral","CornflowerBlue","Cornsilk","Crimson","Cyan","DarkBlue","DarkCyan","DarkGoldenRod","DarkGray","DarkGrey","DarkGreen","DarkKhaki","DarkMagenta","DarkOliveGreen","Darkorange","DarkOrchid","DarkRed","DarkSalmon","DarkSeaGreen","DarkSlateBlue","DarkSlateGray","DarkSlateGrey","DarkTurquoise","DarkViolet","DeepPink","DeepSkyBlue","DimGray","DimGrey","DodgerBlue","FireBrick","FloralWhite","ForestGreen","Fuchsia","Gainsboro","GhostWhite","Gold","GoldenRod","Gray","Grey","Green","GreenYellow","HoneyDew","HotPink","IndianRed","Indigo","Ivory","Khaki","Lavender","LavenderBlush","LawnGreen","LemonChiffon","LightBlue","LightCoral","LightCyan","LightGoldenRodYellow","LightGray","LightGrey","LightGreen","LightPink","LightSalmon","LightSeaGreen","LightSkyBlue","LightSlateGray","LightSlateGrey","LightSteelBlue","LightYellow","Lime","LimeGreen","Linen","Magenta","Maroon","MediumAquaMarine","MediumBlue","MediumOrchid","MediumPurple","MediumSeaGreen","MediumSlateBlue","MediumSpringGreen","MediumTurquoise","MediumVioletRed","MidnightBlue","MintCream","MistyRose","Moccasin","NavajoWhite","Navy","OldLace","Olive","OliveDrab","Orange","OrangeRed","Orchid","PaleGoldenRod","PaleGreen","PaleTurquoise","PaleVioletRed","PapayaWhip","PeachPuff","Peru","Pink","Plum","PowderBlue","Purple","Red","RosyBrown","RoyalBlue","SaddleBrown","Salmon","SandyBrown","SeaGreen","SeaShell","Sienna","Silver","SkyBlue","SlateBlue","SlateGray","SlateGrey","Snow","SpringGreen","SteelBlue","Tan","Teal","Thistle","Tomato","Turquoise","Violet","Wheat","White","WhiteSmoke","Yellow","YellowGreen");

            $data['item_types'][] = 'Standard';
            $data['item_types'][] = 'Kiosk';
            $data['item_types'][] = 'Lot Matrix';
            // $data['item_types'][] = 'Gasoline';
            $data['item_types'][] = 'Instant';

            $data['barcode_types'][] = 'Code 128';
            $data['barcode_types'][] = 'Code 39';
            $data['barcode_types'][] = 'Code 93';
            $data['barcode_types'][] = 'UPC E';
            $data['barcode_types'][] = 'EAN 8';
            $data['barcode_types'][] = 'EAN 13';
            $data['barcode_types'][] = 'UPC A';

            $error_warning = session()->get('warning');
            if (isset($error_warning)) {
                $data['error_warning'] = session()->get('warning');
    
                session()->forget('warning');
            } else {
                $data['error_warning'] = '';
            }
            
            
            $session_success = session()->get('success');
            if (isset($session_success)) {
                $data['success'] = session()->get('success');
    
                session()->forget('success');
            } else {
                $data['success'] = '';
            }
    
            
            
            $data['breadcrumbs'] = array();
    
            $data['breadcrumbs'][] = array(
                'text' => 'Home',
                'href' => url('/dashboard')
            );
            
            $data['breadcrumbs'][] = array(
                'text' => 'Items',
                'href' => url('/items/item_list')
            );

            $data['action_vendor'] = url('/item/action_vendor');
            $data['action_vendor_editlist'] = url('/item/action_vendor_editlist');
            $data['add_alias_code'] = url('/item/add_alias_code');
            $data['alias_code_deletelist'] = url('/item/delete_alias_code');
            $data['add_lot_matrix'] = url('/item/add_lot_matrix');
            $data['lot_matrix_editlist'] = url('/item/lot_matrix_editlist');
            $data['lot_matrix_deletelist'] = url('/item/delete_lot_matrix');
            // $data['add_slab_price'] = url('api/items/add_slab_price');
            // $data['slab_price_editlist'] = url('/item/slab_price_editlist');
            // $data['slab_price_deletelist'] = url('api/items/slab_price_deletelist');
            // $data['add_parent_item'] = url('api/items/add_parent_item');
            // $data['action_remove_parent_item'] = url('api/items/remove_parent_item');
            $data['check_vendor_item_code'] = url('/item/check_vendor_item_code');
            $data['get_categories'] = url('item/get_categories');
            $data['get_subcategories_url'] = url('item/get_subcategories');        

            // $data['searchitem'] = url('/item/search');
            $data['parent_child_search'] = url('/item/parent_child_search');


            $data['action'] = url('/item/clone_item');
            
            // urls for adding category, dept, etc
            $data['Sales'] = 'Sales';
            $data['MISC'] = 'MISC';
            
            $data['get_new_category'] = url('item/category');
            $data['add_new_category'] = url('/item/category/add');
        
            $data['add_new_department'] = url('/item/department/add');
            $data['get_new_department'] = url('/item/department');
            
            $data['add_new_subcategory'] = url('/item/sub_category/add');

            $data['add_new_size'] = url('/item/size/add');
            $data['get_new_size'] = url('/item/size');

            $data['add_new_group'] = url('/item/group/add');
            $data['get_new_group'] = url('/item/group');

            $data['add_new_supplier'] = url('/item/vendor/add');
            $data['get_new_supplier'] = url('api/vendor');
            
            $data['add_new_manufacturer'] = url('/item/manufacturer/add_manufacturer');
            $data['get_new_manufacturer'] = url('/item/manufacturer/get_manufacturer');
            // urls for adding category, dept, etc

            $data['sid'] = session()->get('sid');

            $data['cancel'] = url('item/item_list/Active/DESC');
            
            if(isset($input['clone_item_id'])){
                $clone_item_id = $input['clone_item_id'];
            }else{
                $clone_item_id = $iitemid;
            }
            
            $item = new Item();
            $item_info = $item->getItem($clone_item_id);

            $unit_data = $item->getItemUnitData($clone_item_id);
            $bucket_data = $item->getItemBucketData($clone_item_id);
            
            $tax_info = $item->gettaxinfo();
            $data['tax_info']=$tax_info;

            $data['itemvendors'] = array();
            $data['itemalias'] = array();
            $data['itempacks'] = array();
            $data['itemslabprices'] = array();
            $data['itemchilditems'] = array();
            $data['itemparentitems'] = array();
            $data['remove_parent_item'] = array();
            
            $data['iitemid'] = '';

            $data['clone_item_id'] = $clone_item_id;
        
            if (isset($input['vitemtype'])) {
                $data['vitemtype'] = $input['vitemtype'];
            } elseif (!empty($item_info)) {
                $data['vitemtype'] = $item_info['vitemtype'];
            } else {
                $data['vitemtype'] = '';
            }

            if (isset($input['vitemcode'])) {
                $data['vitemcode'] = $input['vitemcode'];
            } else {
                $data['vitemcode'] = '';
            }

            if (isset($input['vbarcode'])) {
                $data['vbarcode'] = $input['vbarcode'];
            } else {
                $data['vbarcode'] = '';
            }

            if (isset($input['vitemname'])) {
                $data['vitemname'] = $input['vitemname'];
            } elseif (!empty($item_info)) {
                $data['vitemname'] = $item_info['vitemname'];
            } else {
                $data['vitemname'] = '';
            }

            if (isset($input['vdescription'])) {
                $data['vdescription'] = $input['vdescription'];
            // }elseif (!empty($item_info)) {
            //     $data['vdescription'] = $item_info['vdescription'];
            } else {
                $data['vdescription'] = '';
            }

            if (isset($input['vunitcode'])) {
                $data['vunitcode'] = $input['vunitcode'];
            } elseif (!empty($item_info)) {
                $data['vunitcode'] = $item_info['vunitcode'];
            } else {
                $data['vunitcode'] = '';
            }

            if (isset($input['vsuppliercode'])) {
                $data['vsuppliercode'] = $input['vsuppliercode'];
            } elseif (!empty($item_info)) {
                $data['vsuppliercode'] = $item_info['vsuppliercode'];
            } else {
                $data['vsuppliercode'] = '';
            }

            if (isset($input['vdepcode'])) {
                $data['vdepcode'] = $input['vdepcode'];
            } elseif (!empty($item_info)) {
                $data['vdepcode'] = $item_info['vdepcode'];
            } else {
                $data['vdepcode'] = '';
            }

            
            if (isset($input['subcat_id']) && !empty($input['subcat_id'])) {
                $data['subcat_id'] = $input['subcat_id'];
                $cat_id = Category::where('vcategorycode', $input['vcategorycode'])->get()->toArray();
                
                if(!empty($cat_id[0]))
                {
                    $subcategories = SubCategory::where('cat_id', $cat_id[0]['icategoryid'])->orderBy('subcat_name', 'ASC')->get()->toArray();
                    $data['cat_id'] = $cat_id[0]['icategoryid'];
                    $data['subcategories'] = $subcategories;
                }
                
            } elseif (!empty($item_info)) {
                $data['subcat_id'] = $item_info['subcat_id'];
                $cat_id = Category::where('vcategorycode', $item_info['vcategorycode'])->get()->toArray();
                if(!empty($cat_id))
                {
                    $subcategories = SubCategory::where('cat_id', $cat_id[0]['icategoryid'])->orderBy('subcat_name', 'ASC')->get()->toArray();
                    $data['cat_id'] = $cat_id[0]['icategoryid'];
                    $data['subcategories'] = $subcategories;
                }
                
            } else {
                $data['subcat_id'] = '';
            }
            
            $data['manufacturers'] = [];
            if (isset($input['manufacturer_id'])) {
                $data['manufacturer_id'] = $input['manufacturer_id'];
            } elseif (!empty($item_info)) {
                $data['manufacturer_id'] = $item_info['manufacturer_id'];
            } else {
                $data['manufacturer_id'] = '';
            }
            $manufacturers =  Manufacturer::all()->toArray();
            
            $data['manufacturers'] = $manufacturers;
            
            
            if (isset($input['vcategorycode'])) {
                $data['vcategorycode'] = $input['vcategorycode'];
            } elseif (!empty($item_info)) {
                $data['vcategorycode'] = $item_info['vcategorycode'];
            } else {
                $data['vcategorycode'] = '';
            }

            if (isset($input['vsize'])) {
                $data['vsize'] = $input['vsize'];
            } elseif (!empty($item_info)) {
                $data['vsize'] = $item_info['vsize'];
            } else {
                $data['vsize'] = '';
            }

            if (isset($input['iitemgroupid'])) {
                $data['iitemgroupid'] = $input['iitemgroupid'];
            } elseif (!empty($item_info)) {
                $data['iitemgroupid'] = isset($item_info['iitemgroupid']['iitemgroupid']) ? $item_info['iitemgroupid']['iitemgroupid']: '';
            } else {
                $data['iitemgroupid'] = '';
            }

            if (isset($input['wicitem'])) {
                $data['wicitem'] = $input['wicitem'];
            } else {
                $data['wicitem'] = '0';
            }

            if (isset($input['vsequence'])) {
                $data['vsequence'] = $input['vsequence'];
            } elseif (isset($item_info['vsequence']) && !empty($item_info)) {
                $data['vsequence'] = $item_info['vsequence'];
            } else {
                $data['vsequence'] = '';
            }

            if (isset($input['vcolorcode'])) {
                $data['vcolorcode'] = $input['vcolorcode'];
            } elseif (!empty($item_info)) {
                $data['vcolorcode'] = $item_info['vcolorcode'];
            } else {
                $data['vcolorcode'] = '';
            }

            if (isset($input['npack'])) {
                $data['npack'] = $input['npack'];
            } elseif (!empty($item_info)) {
                $data['npack'] = $item_info['npack'];
            } else {
                $data['npack'] = '';
            }

            if (isset($input['dcostprice'])) {
                $data['dcostprice'] = $input['dcostprice'];
            } elseif (!empty($item_info)) {
                $data['dcostprice'] = $item_info['dcostprice'];
            } else {
                $data['dcostprice'] = '';
            }

            if (isset($input['nunitcost'])) {
                $data['nunitcost'] = $input['nunitcost'];
            } elseif (!empty($item_info)) {
                $data['nunitcost'] = $item_info['nunitcost'];
            } else {
                $data['nunitcost'] = '';
            }

            if (isset($input['nsellunit'])) {
                $data['nsellunit'] = $input['nsellunit'];
            } elseif (!empty($item_info)) {
                $data['nsellunit'] = $item_info['nsellunit'];
            } else {
                $data['nsellunit'] = '';
            }

            if (isset($input['nsaleprice'])) {
                $data['nsaleprice'] = $input['nsaleprice'];
            } elseif (!empty($item_info)) {
                $data['nsaleprice'] = $item_info['nsaleprice'];
            } else {
                $data['nsaleprice'] = '';
            }

            if (isset($input['dunitprice'])) {
                $data['dunitprice'] = $input['dunitprice'];
            } elseif (!empty($item_info)) {
                $data['dunitprice'] = $item_info['dunitprice'];
            } else {
                $data['dunitprice'] = '';
            }

            if (isset($input['profit_margin'])) {
                $data['profit_margin'] = $input['profit_margin'];
            } else {
                $data['profit_margin'] = '';
            }

            if (isset($input['liability'])) {
                $data['liability'] = $input['liability'];
            } else {
                $data['liability'] = 'N';
            }

            if (isset($input['vshowsalesinzreport'])) {
                $data['vshowsalesinzreport'] = $input['vshowsalesinzreport'];
            } else {
                $data['vshowsalesinzreport'] = 'No';
            }

            if (isset($input['stationid'])) {
                $data['stationid'] = $input['stationid'];
            } elseif (!empty($item_info)) {
                $data['stationid'] = $item_info['stationid'];
            } else {
                $data['stationid'] = '';
            }

            if (isset($input['aisleid'])) {
                $data['aisleid'] = $input['aisleid'];
            } elseif (!empty($item_info)) {
                $data['aisleid'] = $item_info['aisleid'];
            } else {
                $data['aisleid'] = '';
            }

            if (isset($input['shelfid'])) {
                $data['shelfid'] = $input['shelfid'];
            } elseif (!empty($item_info)) {
                $data['shelfid'] = $item_info['shelfid'];
            } else {
                $data['shelfid'] = '';
            }

            if (isset($input['shelvingid'])) {
                $data['shelvingid'] = $input['shelvingid'];
            } elseif (!empty($item_info)) {
                $data['shelvingid'] = $item_info['shelvingid'];
            } else {
                $data['shelvingid'] = '';
            }

            if (isset($input['iqtyonhand'])) {
                $data['iqtyonhand'] = $input['iqtyonhand'];
            // } elseif (!empty($item_info)) {
            //     $data['iqtyonhand'] = $item_info['iqtyonhand'];
            } else {
                $data['iqtyonhand'] = '';
            }
    
            if (isset($input['QOH'])) {
                $data['QOH'] = $input['QOH'];
            } elseif (!empty($item_info)) {
                $data['QOH'] = $item_info['QOH'];
            } else {
                $data['QOH'] = '';
            }
    
            if (isset($input['ireorderpoint'])) {
                $data['ireorderpoint'] = $input['ireorderpoint'];
            } elseif (!empty($item_info)) {
                if($item_info['ireorderpoint'] > 0)
                {
                    $data['ireorderpoint'] = $item_info['ireorderpoint'];
                }
                else
                {
                    $data['ireorderpoint'] = $this->calculateReorderPoint($data['vitemcode'],91);
                }
                
            } else {
                $data['ireorderpoint'] = '';
            }
            
            if (isset($input['reorder_duration'])) {
                $data['reorder_duration'] = $input['reorder_duration'];
            } elseif (!empty($item_info)) {
                $data['reorder_duration'] = $item_info['reorder_duration'] > 0 ? $item_info['reorder_duration'] : 91;
            } else {
                $data['reorder_duration'] = '';
            }
            
            // if (isset($input['ireorderpointdays'])) {
            //     $data['ireorderpointdays'] = $input['ireorderpointdays'];
            // } elseif (!empty($item_info)) {
            //     $data['ireorderpointdays'] = $item_info['ireorderpointdays'] > 0 ? $item_info['ireorderpointdays'] : 91;
            // } else {
            //     $data['ireorderpointdays'] = '';
            // }


            if (isset($input['norderqtyupto'])) {
                $data['norderqtyupto'] = $input['norderqtyupto'];
            } elseif (!empty($item_info)) {
                $data['norderqtyupto'] = $item_info['norderqtyupto'];
            } else {
                $data['norderqtyupto'] = '';
            }

            if (isset($input['nlevel2'])) {
                $data['nlevel2'] = $input['nlevel2'];
            } elseif (!empty($item_info)) {
                $data['nlevel2'] = $item_info['nlevel2'];
            } else {
                $data['nlevel2'] = '';
            }

            if (isset($input['nlevel4'])) {
                $data['nlevel4'] = $input['nlevel4'];
            } elseif (!empty($item_info)) {
                $data['nlevel4'] = $item_info['nlevel4'];
            } else {
                $data['nlevel4'] = '';
            }

            if (isset($input['visinventory'])) {
                $data['visinventory'] = $input['visinventory'];
            } else {
                $data['visinventory'] = 'Yes';
            }

            if (isset($input['vageverify'])) {
                $data['vageverify'] = $input['vageverify'];
            } elseif (!empty($item_info)) {
                $data['vageverify'] = $item_info['vageverify'];
            } else {
                $data['vageverify'] = '';
            }

            if (isset($input['nlevel3'])) {
                $data['nlevel3'] = $input['nlevel3'];
            } elseif (!empty($item_info)) {
                $data['nlevel3'] = $item_info['nlevel3'];
            } else {
                $data['nlevel3'] = '';
            }

            if (isset($input['ndiscountper'])) {
                $data['ndiscountper'] = $input['ndiscountper'];
            } elseif (isset($item_info['ndiscountper']) && !empty($item_info)) {
                $data['ndiscountper'] = $item_info['ndiscountper'];
            } else {
                $data['ndiscountper'] = '';
            }

            if (isset($input['vfooditem'])) {
                $data['vfooditem'] = $input['vfooditem'];
            } elseif (!empty($item_info)) {
                $data['vfooditem'] = $item_info['vfooditem'];
            } else {
                $data['vfooditem'] = 'N';
            }



            if (isset($input['vtax'])) {
                $data['vtax'] = $input['vtax'];
            } elseif (!empty($item_info)) {
                
                if(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'Y'){
                    $data['vtax'] = 'vtax1';
                }elseif(!empty($item_info['vtax2']) && $item_info['vtax2'] == 'Y'){
                    $data['vtax'] = 'vtax2';
                }elseif(!empty($item_info['vtax3']) && $item_info['vtax3'] == 'Y'){
                    $data['vtax'] = 'vtax3';
                }elseif(!empty($item_info['vtax1']) && $item_info['vtax1'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N' && !empty($item_info['vtax2']) && $item_info['vtax2'] == 'N'){
            	    $data['vtax'] = 'vnotax';
                }else{
                    $data['vtax'] = '';
                }
            } else {
                $data['vtax'] = '';
            }
            
            $all_taxes = [
                            ['value'=>'vtax1', 'name'=>'Tax1'],
                            ['value'=>'vtax2', 'name'=>'Tax2'],
                            ['value'=>'vtax3', 'name'=>'Tax3'],
                            ['value'=>'vnotax', 'name'=>'Non Taxable']
                        ];

        
            $data['all_taxes'] = $all_taxes;

            if (isset($input['itemimage'])) {
                $data['itemimage'] = $input['itemimage'];
            } else {
                $data['itemimage'] = '';
            }
            
            if (isset($input['vshowimage'])) {
                $data['vshowimage'] = $input['vshowimage'];
            } else {
                $data['vshowimage'] = '';
            }
            
            if (isset($input['estatus'])) {
                $data['estatus'] = $input['estatus'];
            } elseif (!empty($item_info)) {
                $data['estatus'] = $item_info['estatus'];
            } else {
                $data['estatus'] = '';
            }

            // if (isset($input['ebottledeposit'])) {
        
    
            if (isset($input['nbottledepositamt'])) {
                $data['nbottledepositamt'] = $input['nbottledepositamt'];
            } elseif (!empty($item_info)) {
                $data['nbottledepositamt'] = $item_info['nbottledepositamt'];
            } else {
                $data['nbottledepositamt'] = '0.00';
            }

            if (isset($input['vbarcodetype'])) {
                $data['vbarcodetype'] = $input['vbarcodetype'];
            } elseif (!empty($item_info)) {
                $data['vbarcodetype'] = $item_info['vbarcodetype'];
            } else {
                $data['vbarcodetype'] = '';
            }

            if (isset($input['vintage'])) {
                $data['vintage'] = $input['vintage'];
            } elseif (!empty($item_info)) {
                $data['vintage'] = $item_info['vintage'];
            } else {
                $data['vintage'] = '';
            }

            if (isset($input['vdiscount'])) {
                $data['vdiscount'] = $input['vdiscount'];
            } elseif (!empty($item_info)) {
                $data['vdiscount'] = $item_info['vdiscount'];
            } else {
                $data['vdiscount'] = '';
            }

            if (isset($input['rating'])) {
                $data['rating'] = $input['rating'];
            } elseif (!empty($item_info)) {
                $data['rating'] = $item_info['rating'];
            } else {
                $data['rating'] = '';
            }

            if (isset($input['unit_id']) || isset($input['unit_id'])) {
                $data['unit_id'] = $input['unit_id'];
                $data['unit_value'] = $input['unit_value'];
            }else if (!empty($item_info) && !empty($unit_data)) {
                $data['unit_id'] = $unit_data['unit_id'];
                $data['unit_value'] = $unit_data['unit_value'];
            } else {
                $data['unit_id'] = '';
                $data['unit_value'] = '';
            }

            if (isset($input['bucket_id']) ) {
                $data['bucket_id'] = $input['bucket_id'];
            } else if (!empty($item_info) && !empty($bucket_data)) {
                $data['bucket_id'] = $bucket_data['bucket_id'];
            } else {
                $data['bucket_id'] = '';
            }

            if (isset($input['malt'])) {
                $data['malt'] = $input['malt'];
            } else if (!empty($item_info) && !empty($bucket_data)) {
                $data['malt'] = $bucket_data['malt'];
            } else {
                $data['malt'] = '';
            }

            if (isset($input['plcb_options_checkbox'])) {
                $data['plcb_options_checkbox'] = $input['plcb_options_checkbox'];
            }else if (!empty($item_info) && !empty($bucket_data)) {
                $data['plcb_options_checkbox'] = 1;
            }else{
                $data['plcb_options_checkbox'] = 0;
            }
            
                //=============== Include new_costprice = New Cost ==============================
            if (isset($input['new_costprice'])) {
                $data['new_costprice'] = $input['new_costprice'];
            } elseif (!empty($item_info)) {
                $data['new_costprice'] = $item_info['new_costprice'];
            } else {
                $data['new_costprice'] = '';
            }
            
            //print_r($item_info); exit;
            
            
            //=============== Include lastcost = Last Cost ==============================
            if (isset($input['last_costprice'])) {
                $data['last_costprice'] = $input['last_costprice'];
            } elseif (!empty($item_info)) {
                $data['last_costprice'] = $item_info['last_costprice'];
            } else {
                $data['last_costprice'] = '';
            }

            
            $data['isparentchild'] = '';
        
            $data['parentid'] = '';

            $data['parentmasterid'] = '';

            $departments = $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
            
            $data['departments'] = $departments;

            $categories = Category::orderBy('vcategoryname', 'ASC')->get()->toArray();
            
            $data['categories'] = $categories;

            $units = Unit::all()->toArray();
            
            $data['units'] = $units;

            $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
            
            $data['suppliers'] = $suppliers;

            $sizes = Size::all()->toArray();
            
            $data['sizes'] = $sizes;

            // $itemGroups = $this->model_administration_olditems->getItemGroups();
            
            // $data['itemGroups'] = $itemGroups;

            $ageVerifications = DB::connection('mysql_dynamic')->table('mst_ageverification')->get()->toArray();
            
            $data['ageVerifications'] = $ageVerifications;

            $stations = DB::connection('mysql_dynamic')->table('mst_station')->get()->toArray();
            
            $data['stations'] = $stations;

            $aisles = DB::connection('mysql_dynamic')->table('mst_aisle')->get()->toArray();
            
            $data['aisles'] = $aisles;

            $shelfs = DB::connection('mysql_dynamic')->table('mst_shelf')->get()->toArray();
            
            $data['shelfs'] = $shelfs;

            $shelvings = DB::connection('mysql_dynamic')->table('mst_shelving')->get()->toArray();
            
            $data['shelvings'] = $shelvings;

            // $loadChildProducts = $this->model_api_olditems->getChildProductsLoad();

            // $data['loadChildProducts'] = $loadChildProducts;

            $itemsUnits = DB::connection('mysql_dynamic')->table('mst_item_unit')->get()->toArray();

            $data['itemsUnits'] = $itemsUnits;

            $buckets = DB::connection('mysql_dynamic')->table('mst_item_bucket')->get()->toArray();

            $data['buckets'] = $buckets;
            unset($buckets);
            // dd($data['categories']);
            return $data;
        }
        
        
    }

    
    public function get_categories(Request $request) {

		$data = array();
		$input = $request->all();
        // dd($input);
		if($request->isMethod('post')) {

			
			$data = Category::where('dept_code', $input['dept_code'])->orderBy('vcategoryname', 'ASC')->get()->toArray();
			
            $response = [];
            $obj = new \stdClass();
		    $obj->id = "";
		    $obj->text = "--Select Category--";
		    array_push($response, $obj);
		    
			foreach($data as $k => $v){
			    
			    $obj = new \stdClass();
			    $obj->id = $v['vcategorycode'];
			    $obj->text = $v['vcategoryname'];
			    
			    array_push($response, $obj);
			}
			// http_response_code(200);
			
           return response(json_encode($response), 200)
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			// http_response_code(401);
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }
    
    public function get_subcategories(Request $request) {

		$input = $request->all();

		if($request->isMethod('post')) {

			
			$data = SubCategory::where('cat_id', $input['cat_id'])->orderBy('subcat_name', 'ASC')->get()->toArray();
			
            $response = [];
            $obj = new \stdClass();
		    $obj->id = "";
		    $obj->text = "--Select SubCategory--";
		    array_push($response, $obj);
		    
			foreach($data as $k => $v){
			    
			    $obj = new \stdClass();
			    $obj->id = $v['subcat_id'];
			    $obj->text = $v['subcat_name'];
			    
			    array_push($response, $obj);
			}
			return response(json_encode($response), 200)
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }
    
    public function calculateReorderPoint($vitemcode,$days)
    {
        
        $end_date = date('Y-m-d',strtotime("-1 days"));
        $start_date= date('Y-m-d', strtotime("-$days days", strtotime($end_date)));

        $item = new Item();
        $get_average = $item->get_average_sales($vitemcode,$start_date,$end_date);
        $get_average = isset($get_average[0])?$get_average[0]:[];
        
        if(isset($get_average->sum) && $get_average->sum > 0)
        {
            $avg = $get_average->sum / $days;
            return  $avg > 0 ? ceil($avg) : 0;
        }
        return 0;
    }
    

    public function action_vendor(Request $request) {

        $input = $request->all();
        // dd($input);
        if ($request->isMethod('post')) {
            
            $new_database = session()->get('new_database');
            if($new_database === false){
                //===================================== OLD DATABASE ===================================
    			$temp_arr = array();
    			$temp_arr['iitemid'] = $input['iitemid'];
    			$temp_arr['ivendorid'] = $input['ivendorid'];
    			$temp_arr['vvendoritemcode'] = $input['vvendoritemcode'];
    			$temp_arr['Id'] = 0;
    			$storeshq = isset($input['hiddenvendorAssignsave']) ? $input['hiddenvendorAssignsave'] : [session()->get('sid')];
                
                $olditem = new Olditem();
                $result = $olditem->addUpdateItemVendor($temp_arr, $storeshq);
                
                if(isset($result['success'])){
                session()->put('success', $result['success']);
                }
                session()->put('tab_selected', 'vendor_tab');
                
                $url = '/item/edit/'.$input['iitemid'];
                        
                return redirect($url);
                
            } else {
                //========================================================== NEW DATABASE ==================================================
                $temp_arr = array();
                $temp_arr['iitemid'] = $input['iitemid'];
                $temp_arr['ivendorid'] = $input['ivendorid'];
                $temp_arr['vvendoritemcode'] = $input['vvendoritemcode'];
                $temp_arr['Id'] = 0;
                $storeshq = isset($input['hiddenvendorAssignsave']) ? $input['hiddenvendorAssignsave'] : [session()->get('sid')];
                
                $item = new Item();
                $result = $item->addUpdateItemVendor($temp_arr, $storeshq);
    
                if(isset($result['success'])){
                session()->put('success', $result['success']);
                }
                if(isset($result['error'])){
	             session()->put('error_warning', 'Vendor already Exist');
	            }
                
                session()->put('tab_selected', 'vendor_tab');
                
                $url = '/item/edit/'.$input['iitemid'];
                        
                return redirect($url);
            }
            

        }
    }


    public function unset_visited_below_zero(Request $request){
        
        if($request->isMethod('post')){
            session()->forge('visited_zero_movement_report');
            session()->forget('visited_below_cost_report');
            return "true";
        }
    }

    public function calculateReorderPointAjax(Request $request)
    {
        $input = $request->all();
        if(isset($input['vitemcode']))
        {
           $vitemcode = $input['vitemcode'] ? $input['vitemcode'] : '';
           $days = $input['days'] ? $input['days'] : 0;
           if($days > 0  && !empty($vitemcode))
           {
               echo $this->calculateReorderPoint($vitemcode,$days);exit;
           }
        }
        echo 0;exit;
    }
    
    public function action_vendor_editlist(Request $request) {
        $input = $request->all();
        // dd($input);
        $stores_hq = isset($input['vendor_update_stores']) ? $input['vendor_update_stores'] : [session()->get('sid')];
        if ($request->isMethod('post')) {
            $new_database = session()->get('new_database');
            if($new_database === false){
	            // ========================================== OLD DATABASE ======================================
				$iitemid = $input['itemvendors'][0]['iitemid'];

				if(isset($input['itemvendors']) && count($input['itemvendors']) > 0){
					foreach ($input['itemvendors'] as $key => $value) {
						$olditem = new Olditem();
					    if(isset($stores_hq)){
					         
					        $result = $olditem->hqStoreUpdateItemVendor($value, $stores_hq);  
					    }else {
    			            $result = $olditem->addUpdateItemVendor($value, $stores_hq);
					    }
					}
				}
				
				session()->put('success', $result['success']);
                
                session()->put('tab_selected', 'vendor_tab');
                
                $url = '/item/edit/'.$iitemid;
                        
                return redirect($url);
                	            
	        } else {
	            // ========================================== NEW DATABASE ======================================
                for($z=0; $z < count($input['itemvendors']); $z++){
                    $iitemid = $input['itemvendors'][$z]['iitemid'];
                }
                
                if(isset($input['itemvendors']) && count($input['itemvendors']) > 0){
                    foreach ($input['itemvendors'] as $key => $value) {
                        $item = new Item();
                        if(isset($stores_hq)){
                           
					        $result = $item->hqStoreUpdateItemVendor($value, $stores_hq);  
					    }else {
    			            $result = $item->addUpdateItemVendor($value, $stores_hq);
					    }
                    }
                }
                if(isset($result['success'])){
                session()->put('success', $result['success']);
	            }
	            //dd($result['error']);
	            if(isset($result['error'])){
	             session()->put('error_warning', $result['error']);
	            }
                session()->put('tab_selected', 'vendor_tab');
                
                $url = '/item/edit/'.$iitemid;
                        
                return redirect($url);
	        }
            
        }
    }


    public function add_alias_code(Request $request) 
    {   
		$data = array();
		if ($request->isMethod('post')) {
			$temp_arr = json_decode(file_get_contents('php://input'), true);
			if (($temp_arr['vitemcode'] == '')) {
				$data['validation_error'][] = 'Item Code Required';
			}

			if (($temp_arr['vsku'] == '')) {
				$data['validation_error'][] = 'SKU Required';
			}

			if (($temp_arr['valiassku'] == '')) {
				$data['validation_error'][] = 'Alias Code Required';
			}

			if(!array_key_exists("validation_error",$data)){

                $item = new Item();
				$data = $item->addItemAliasCode($temp_arr);

				if(array_key_exists("validation_error",$data)){
					if(isset($data['success'])){
						unset($data['success']);
					}
				}

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(401);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			// http_response_code(401);
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }
    
    public function delete_alias_code(Request $request) {

		$data = array();
		if ($request->isMethod('post')) {
			$temp_arr = json_decode(file_get_contents('php://input'), true);
		
			if(!array_key_exists("validation_error",$data)){

				$item = new Item();
				$data = $item->deleteItemAliasCode($temp_arr);

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(401);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }
    
    public function add_lot_matrix(Request $request) {

		$data = array();
		
		if ($request->isMethod('post')) {
			$temp_arr = $request->all();
				
			if (($temp_arr['iitemid'] == '')) {
				$data['validation_error'][] = 'Item id Required';
			}

			if (($temp_arr['vbarcode'] == '')) {
				$data['validation_error'][] = 'Barcode Required';
			}

			if (($temp_arr['vpackname'] == '')) {
				$data['validation_error'][] = 'Pack Name Required';
			}

			if (($temp_arr['ipack'] == '')) {
				$data['validation_error'][] = 'Pack Qty Required';
			}

			if (($temp_arr['npackcost'] == '')) {
				$data['validation_error'][] = 'Cost Price Required';
			}
                
			if (($temp_arr['npackprice'] == '')) {
				$data['validation_error'][] = 'Price Required';
			}
                
			if(!array_key_exists("validation_error",$data)){
                
				$item = new Item();
				$data = $item->addItemLotMatrix($temp_arr);
                
				if(array_key_exists("validation_error",$data)){
					if(isset($data['success'])){
						unset($data['success']);
					}
				}
                                    
				return response(json_encode($data), 200)
                    ->header('Content-Type', 'application/json');
                                
			}else{
				return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
			}
                
		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }
    

    public function lot_matrix_editlist(Request $request) {

        $input = $request->all();
        
        if ($request->isMethod('post')) {
            
            $new_database = session()->get('new_database');
            if($new_database === false){
                //=============================================== OLD DATABASE ====================================
                $temp_arr = $input['itempacks'];
                
                $iitemid = $input['itempacks'][0]['iitemid'];
                
                $olditem = new Olditem();
                $result = $olditem->editlistLotMatrixItems($temp_arr);
                
                session()->put('success', $result['success']);
                
                session()->put('tab_selected', 'lot_matrix_tab');
                
                $url = '/item/edit/'.$iitemid;
                        
                return redirect($url);
                
            } else {
                //=============================================== NEW DATABASE ====================================
                $temp_arr = $input['itempacks'];
    
                $iitemid = $input['itempacks'][0]['iitemid'];
                
                $item = new Item();
                $result = $item->editlistLotMatrixItems($temp_arr);
                
                session()->put('success', $result['success']);
    
                session()->put('tab_selected', 'lot_matrix_tab');
                
                $url = '/item/edit/'.$iitemid;
                        
                return redirect($url);
            }


        }
    }


    public function delete_lot_matrix(Request $request) {

		$data = array();
		
        if ($request->isMethod('post')) {
			$temp_arr = json_decode(file_get_contents('php://input'), true);
			
			if(!array_key_exists("validation_error",$data)){

                $item = new Item();
				$data = $item->deleteItemLotmatrix($temp_arr);

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(401);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
	}
    

    public function check_vendor_item_code(Request $request) {

        $data =array();
        
        if ($request->isMethod('post')) {

            $temp_arr = json_decode(file_get_contents('php://input'), true);

            $item = new Item();
            $data = $item->checkVendorItemCode($temp_arr);

            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');
            exit;

        }else{
            $data['error'] = 'Something went wrong';
            // http_response_code(401);
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
            exit;
        }
    }

    public function parent_child_search(){
        
        $term = $input = $this->request->get['term'];
        
        $select_query = "SELECT DISTINCT(iitemid), vitemname FROM mst_item WHERE vbarcode LIKE '%".$term."%' OR vitemname LIKE '%" . $term . "%' LIMIT 20";
     
        $query = DB::connection('mysql_dynamic')->select($select_query);
        $query = isset($query[0])?(array)$query[0]:[];

        echo json_encode($query);
        
        die;
        
    }

    public function getCategories(Request $request)
    {
        $data =array();
        
        if ($request->isMethod('get')) {

            
            $data = Category::orderBy('icategoryid', 'DESC')->get()->toArray();

            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');

        }else{
            $data['error'] = 'Something went wrong';
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
        }

    }

    public function addCategory(Request $request) {

        $data = array();
        
		if ($request->isMethod('post')) {

			$temp_arr = json_decode(file_get_contents('php://input'), true);
			
            // dd($temp_arr[0]['vcategoryname']);
			foreach ($temp_arr as $key => $value) {
				if($value['vcategoryname'] == ''){
					$data['validation_error'][] = 'Category Name Required';
					break;
				} else {
				    $check_name = Category::where( 'vcategoryname', html_entity_decode($value['vcategoryname']))->get()->toArray();
				    
				    if(count($check_name) > 0){
                        
				        $data['validation_error'][] = 'Category ('.$value['vcategoryname'].') already exists';
                        
                        $data['error'] = 'Category ('.$value['vcategoryname'].') already exists';
                        return response(json_encode($data), 401)
                            ->header('Content-Type', 'application/json');
				    }
				    
				}
			}

			if(!array_key_exists("validation_error",$data)){

                $temp_arr = isset($temp_arr[0])?$temp_arr[0]:[];

                $category = new Category;
                $category->vcategoryname = $temp_arr['vcategoryname'];
                $category->vdescription = $temp_arr['vdescription'];
                $category->vcategorttype = $temp_arr['vcategorttype'];
                $category->isequence = $temp_arr['isequence'];
                $category->dept_code = $temp_arr['dept_code'];
                $category->estatus = 'Active';
                $category->SID = session()->get('sid');
                
                $category->save();
                $last_id = $category->icategoryid;
                
                $updateCategory = Category::find($last_id);
                
                $updateCategory->vcategorycode = $last_id;
                $updateCategory->save();

                $data['success'] = 'Successfully Added Category';
                $data['category_id'] = $last_id;

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(500);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }
    
    public function getDepartments(Request $request)
    {
        $data =array();
        
        if ($request->isMethod('get')) {

            
            $data = Department::orderBy('idepartmentid', 'DESC')->get()->toArray();

            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');

        }else{
            $data['error'] = 'Something went wrong';
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
        }

    }

    public function addDepartment(Request $request) {

        $data = array();
        
		if ($request->isMethod('post')) {

			$temp_arr = json_decode(file_get_contents('php://input'), true);
			
            // dd($temp_arr[0]);
			foreach ($temp_arr as $key => $value) {
				if($value['vdepartmentname'] == ''){
					$data['validation_error'][] = 'Department Name Required';
					break;
				} else {
				    $check_name = Department::where( 'vdepartmentname', html_entity_decode($value['vdepartmentname']))->get()->toArray();
				    
				    if(count($check_name) > 0){
                        
				        $data['validation_error'][] = 'Department ('.$value['vdepartmentname'].') already exists';
				        $data['error'] = 'Department ('.$value['vdepartmentname'].') already exists';
                        return response(json_encode($data), 401)
                            ->header('Content-Type', 'application/json');
                                }
				    
				}
			}

			if(!array_key_exists("validation_error",$data)){

                $temp_arr = isset($temp_arr[0])?$temp_arr[0]:[];

                $department = new Department;
                $department->vdepartmentname = $temp_arr['vdepartmentname'];
                $department->vdescription = $temp_arr['vdescription'];
                $department->isequence = $temp_arr['isequence'];
                $department->estatus = 'Active';
                $department->SID = session()->get('sid');
                
                $department->save();
                $last_id = $department->idepartmentid;
                
                $updateDepartment = Department::find($last_id);
                
                $updateDepartment->vdepcode = $last_id;
                $updateDepartment->save();

                $data['success'] = 'Successfully Added Department';
                $data['department_id'] = $last_id;

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(500);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }


    public function getSubcategories(Request $request)
    {
        $data =array();
        
        if ($request->isMethod('get')) {

            
            $data = SubCategory::orderBy('subcat_id', 'DESC')->get()->toArray();

            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');

        }else{
            $data['error'] = 'Something went wrong';
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
        }

    }

    public function addSubcategory(Request $request) {

        $data = array();
        
		if ($request->isMethod('post')) {

			$temp_arr = json_decode(file_get_contents('php://input'), true);
			
            // dd($temp_arr[0]);
			foreach ($temp_arr as $key => $value) {
				if($value['subcat_name'] == ''){
					$data['validation_error'][] = 'Sub Category Name Required';
					break;
				} else {
				    $check_name = SubCategory::where( 'subcat_name', html_entity_decode($value['subcat_name']))->get()->toArray();
				    
				    if(count($check_name) > 0){
                        
				        $data['validation_error'][] = 'Sub Category ('.$value['subcat_name'].') already exists';
                        
                        $data['error'] = 'Sub Category ('.$value['subcat_name'].') already exists';
                        return response(json_encode($data), 401)
                            ->header('Content-Type', 'application/json');
				    }
				    
				}
			}

			if(!array_key_exists("validation_error",$data)){

                $temp_arr = isset($temp_arr[0])?$temp_arr[0]:[];

                $subcategory = new SubCategory;
                $subcategory->subcat_name = $temp_arr['subcat_name'];
                $subcategory->cat_id = $temp_arr['cat_id'];
                $subcategory->status = 'Yes';
                $subcategory->SID = session()->get('sid');
                
                $subcategory->save();
                $last_id = $subcategory->subcat_id;
                
                $updateCategory = SubCategory::find($last_id);
                
                $updateCategory->subcat_id = $last_id;
                $updateCategory->save();
                
                $data['success'] = 'Successfully Added Sub Category';
                $data['subcat_id'] = $last_id;
                
				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(500);
				}
                
			}else{
				http_response_code(401);
			}
                
			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');
                
		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }
    
    public function getSize(Request $request)
    {
        $data =array();
        
        if ($request->isMethod('get')) {

            
            $data = Size::orderBy('isizeid', 'DESC')->get()->toArray();

            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');

        }else{
            $data['error'] = 'Something went wrong';
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
        }

    }

    public function addSize(Request $request) {

        $data = array();
        
		if ($request->isMethod('post')) {

			$temp_arr = json_decode(file_get_contents('php://input'), true);
			
            // dd($temp_arr[0]);
			foreach ($temp_arr as $key => $value) {
				if($value['vsize'] == ''){
					$data['validation_error'][] = 'Size Name Required';
					break;
				} else {
				    $check_name = Size::where( 'vsize', html_entity_decode($value['vsize']))->get()->toArray();
				    
				    if(count($check_name) > 0){
                        
				        $data['validation_error'][] = 'Size ('.$value['vsize'].') already exists';
                        
                        $data['error'] = 'Size ('.$value['vsize'].') already exists';
                        return response(json_encode($data), 401)
                            ->header('Content-Type', 'application/json');
				    }
				    
				}
			}

			if(!array_key_exists("validation_error",$data)){

                $temp_arr = isset($temp_arr[0])?$temp_arr[0]:[];

                $size = new Size;
                $size->vsize = $temp_arr['vsize'];
                $size->SID = session()->get('sid');
                
                $size->save();
                
                $data['success'] = 'Size Added Successfully';
                

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(500);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }

    public function getManufacturer(Request $request)
    {
        $data =array();
        
        if ($request->isMethod('get')) {

            
            $data = Manufacturer::orderBy('mfr_id', 'DESC')->get()->toArray();

            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');

        }else{
            $data['error'] = 'Something went wrong';
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
        }

    }

    public function addManufacturer(Request $request) {

        $data = array();
        
		if ($request->isMethod('post')) {

			$temp_arr = json_decode(file_get_contents('php://input'), true);
			
            // dd($temp_arr[0]);
			foreach ($temp_arr as $key => $value) {
				if($value['mfr_name'] == ''){
					$data['validation_error'][] = 'Manufacturer Name Required';
					break;
				}
				if($value['mfr_code'] == ''){
					$data['validation_error'][] = 'Manufacturer Code Required';
					break;
				} 
				    
			}

			if(!array_key_exists("validation_error",$data)){

                $temp_arr = isset($temp_arr[0])?$temp_arr[0]:[];

                $manufacturer = new Manufacturer;
                $manufacturer->mfr_code = $temp_arr['mfr_code'];
                $manufacturer->mfr_name = $temp_arr['mfr_name'];
                $manufacturer->status = 'Active';
                $manufacturer->SID = session()->get('sid');
                
                $manufacturer->save();
                
                $data['success'] = 'Successfully Added Manufacuturer';
                

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(500);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }


    public function getSupplier(Request $request)
    {
        $data =array();
        
        if ($request->isMethod('get')) {

            
            $data = Supplier::orderBy('isupplierid', 'DESC')->get()->toArray();
            
            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');

        }else{
            $data['error'] = 'Something went wrong';
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
        }

    }

    public function addSupplier(Request $request) {

        $data = array();
        
		if ($request->isMethod('post')) {

			$temp_arr = json_decode(file_get_contents('php://input'), true);
			
            
			foreach ($temp_arr as $key => $value) {
				if($value['vcompanyname'] == ''){
					$data['validation_error'][] = 'Supplier Name Required';
					break;
				} else {
				    $check_name = Supplier::where( 'vcompanyname', html_entity_decode($value['vcompanyname']))->get()->toArray();
				    
				    if(count($check_name) > 0){
                        
				        $data['validation_error'][] = 'Supplier ('.$value['vcompanyname'].') already exists';
                        
                        $data['error'] = 'Supplier ('.$value['vcompanyname'].') already exists';
                        return response(json_encode($data), 401)
                            ->header('Content-Type', 'application/json');
				    }
				    
				}
				    
			}

			if(!array_key_exists("validation_error",$data)){

                $temp_arr = isset($temp_arr[0])?$temp_arr[0]:[];

                $supplier = new Supplier;
                $supplier->vcompanyname = $temp_arr['vcompanyname'];
                $supplier->vvendortype = $temp_arr['vvendortype'];
                $supplier->vfnmae = $temp_arr['vfnmae'];
                $supplier->vlname = $temp_arr['vlname'];
                $supplier->vcode = $temp_arr['vcode'];
                $supplier->vaddress1 = $temp_arr['vaddress1'];
                $supplier->vcity = $temp_arr['vcity'];
                $supplier->vstate = $temp_arr['vstate'];
                $supplier->vphone = $temp_arr['vphone'];
                $supplier->vzip = $temp_arr['vzip'];
                $supplier->vcountry = $temp_arr['vcountry'];
                $supplier->vemail = $temp_arr['vemail'];
                $supplier->plcbtype = $temp_arr['plcbtype'];
                $supplier->estatus = $temp_arr['estatus'];
                $supplier->SID = session()->get('sid');
                
                $supplier->save();

                $last_id = $supplier->isupplierid;
                
                $updateSupplier = Supplier::find($last_id);
                
                $updateSupplier->vsuppliercode = $last_id;
                $updateSupplier->save();
                
                $data['success'] = 'Successfully Added Supplier';
                

				if(array_key_exists("success",$data)){
					http_response_code(200);
				}else{
					http_response_code(500);
				}

			}else{
				http_response_code(401);
			}

			return response(json_encode($data))
                  ->header('Content-Type', 'application/json');

		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
    }

    public function import_items(Request $request){
        $return = array();
        
        $input = $request->all();
        // dd();
        $file_path1 = storage_path("logs/items/error_log_import_item.txt");
                
        //open a file in public folder
        $myfile1 = fopen($file_path1, 'a');

        // $file_path1 = DIR_TEMPLATE."/administration/error_log_import_item.txt";

        // $myfile1 = fopen( DIR_TEMPLATE."/administration/error_log_import_item.txt", "a"); $content = "";
        
        if ($request->isMethod('post') && isset($input['import_item_file']) && $request->hasFile('import_item_file')) {
            //itemcode|itemname|catname|depname|size|price|tax|npack|costprice|iqoh

            if($input['separated_by'] == 'pipe'){
                $seperatBy = "|";
            }else{
                $seperatBy = ",";
            }
            
            
            
            $import_item_file = $request->file('import_item_file')->getPathName();
            $handle = fopen($import_item_file, "r");
            $msg_exist = '';
            $line_row_index=1;
            
            $new_database = session()->get('new_database');
            
            if ($handle) {
                
                while (($strline = fgets($handle)) !== false) {
                    
                    $values = explode($seperatBy,$strline);
                    
                    if($line_row_index >= 1){
                        
                        if(count($values) != 19){
                            
                            $return['code'] = 0;
                            $return['error'] = count($values)." Your csv file is not valid.".json_encode($values);
                            response()->header('Content-Type', 'application/json');
                            echo json_encode($return);
                            exit;
                        }else{
                            
                            $itemtype = str_replace('"', '', $values[0]);
                            $itemcode = str_replace('"', '', $values[1]);
                            $itemname = str_replace('"', '', $values[2]);
                            $itemdescription = str_replace('"', '', $values[3]);
                            $unit = str_replace('"', '', $values[4]);
                            $depname = str_replace('"', '', $values[5]);
                            $catname = str_replace('"', '', $values[6]);
                            $supplier = str_replace('"', '', $values[7]);
                            $groupname = str_replace('"', '', $values[8]);
                            $size = str_replace('"', '', $values[9]); 
                            $costprice = str_replace('"', '', $values[10]);
                            $price = str_replace('"', '', $values[11]);
                            $iqoh = str_replace('"', '', $values[12]);
                            $tax1 = str_replace('"', '', $values[13]);
                            $tax2 = str_replace('"', '', $values[14]);
                            $sellingunit = str_replace('"', '', $values[15]);
                            $foodstamp = str_replace('"', '', $values[16]);
                            $wicitem = str_replace('"', '', $values[17]);
                            $ageverification = str_replace(['"','\n','\r','\r\n','PHP_EOL'], '', $values[18]);
                            $itemimage = '';
                            
                            
                            if(!isset($ageverification) || empty($ageverification)){
                                $ageverification = 0;
                            }
                            
                            
                            if(strlen($itemcode) > 0 && strlen($itemname)){
                                
                                
                                if($new_database === false){
                                    //==================================================== OLD DATABASE =================================================
                                    
                                    $olditem = new Olditem;
                                    $checkItemCode = $olditem->getSKU($itemcode);
                                } else {
                                    
                                    $item = new Item;
                                    $checkItemCode = $item->getSKU($itemcode);
                                }
                                
                                // dd(($checkItemCode));
                                if(count($checkItemCode) == 0){
                                    $vcatcode = '';
                                    $vdepcode = '';
                                    $unitcode = '';
                                    $vsubcatcode='';
                                    
                                    
                                    $vdepcodecount = Department::where('vdepartmentname', $depname)->get()->toArray();
                                    $vdepcodecount = isset($vdepcodecount[0])?$vdepcodecount[0]:[];
                                    
                                    if(count($vdepcodecount) > 0){
                                        $vdepcode = $vdepcodecount['vdepcode'];
                                    }else{
                                        $insert_vdepcode = new Department;
                                        $insert_vdepcode->vdepartmentname = html_entity_decode($depname);
                                        $insert_vdepcode->isequence = 0;
                                        $insert_vdepcode->estatus = 'Active';
                                        $insert_vdepcode->SID = session()->get('sid');
                                        
                                        $insert_vdepcode->save();
                                        $vdepcode = $insert_vdepcode->idepartmentid;
                                        
                                        Department::find($vdepcode)->update(['vdepcode' => vdepcode]);
                                    }
                                    
                                    $vcatcodecount = Category::where('vcategoryname', $catname)->get()->toArray();
                                    $vcatcodecount = isset($vcatcodecount[0])?$vcatcodecount[0]:[];
                                    
                                    if(count($vcatcodecount) > 0){
                                        $vcatcode = $vcatcodecount['vcategorycode']; 
                                    }else{
                                        
                                        $insert_vcatcode = new Category;
                                        // $vcatcode = $this->model_api_category->addCategoryByName($catname);
                                        $insert_vcatcode->vcategoryname = html_entity_decode($catname);
                                        $insert_vcatcode->isequence = 0;
                                        $insert_vcatcode->estatus = 'Active';
                                        $insert_vcatcode->SID = session()->get('sid');
                                        $insert_vcatcode->dept_code = $vdepcode;
                                        
                                        $insert_vcatcode->save();
                                        $vcatcode = $insert_vcatcode->icategoryid;
                                        
                                        Category::find($vcatcode)->update(['vcategorycode' => $vcatcode]);
                                        
                                    }
                                    
                                    $sizecount = Size::where('vsize', $size)->get()->toArray();
                                    $sizecount = isset($sizecount[0])?$sizecount[0]:[];
                                    
                                    if(count($sizecount) == 0){
                                        
                                        $insert_size= new Size;
                                        $insert_size->vsize = $size;
                                        $insert_size->SID = session()->get('sid');
                                        $insert_size->save();
                                        
                                        $last_id = $insert_size->isizeid;
                                        $result = Size::where('isizeid', $last_id)->get()->toArray(); dd($size);
                                        $size = $result[0]['vsize'];
                                    }
                                    
                                    $unitcount = Unit::where('vunitname', $unit)->get()->toArray();
                                    $unitcount = isset($unitcount[0])?$unitcount[0]:[];
                                    
                                    if(count($unitcount) == 0){
                                        // $unit = $this->model_api_units->addUnitByName($unit);
                                        $insert_unit = new Unit;
                                        $insert_unit->vunitname = $unit;
                                        $insert_unit->vunitdesc = $unit;
                                        $insert_unit->estatus = 'Active';
                                        $insert_unit->SID = session()->get('sid');
                                        $insert_unit->save();
                                        
                                        $iunitid = $insert_unit->iunitid;
                                        $vunitcode = "UNT00".$iunitid;
                                        Unit::find($iunitid)->update(['vunitcode' => $vunitcode]);
                                        
                                        $result = Unit::where('iunitid', $iunitid)->get()->toArray();
                                        $unit = $result['vunitcode'];
                                        
                                    } else {
                                        $unit = $unitcount['vunitcode'];
                                    }
                                    
                                    //Supplier Info
                                    $suppliercount = Supplier::where('vcompanyname', $supplier)->get()->toArray();
                                    $suppliercount = isset($suppliercount[0])?$suppliercount[0]:[];
                                    
                                    if(count($suppliercount) == 0){
                                        // $supplier = $this->model_api_vendor->addVendorByName($supplier);
                                        $insert_supplier = new Supplier;
                                        $insert_supplier->vcompanyname = $unit;
                                        $insert_supplier->vvendortype = 'Vendor';
                                        $insert_supplier->vfnmae = '';
                                        $insert_supplier->vlname = '';
                                        $insert_supplier->vcode = '';
                                        $insert_supplier->vaddress1 = '';
                                        $insert_supplier->vcity = '';
                                        $insert_supplier->vstate = '';
                                        $insert_supplier->vphone = '';
                                        $insert_supplier->vzip = '';
                                        $insert_supplier->vcountry = '';
                                        $insert_supplier->vemail = '';
                                        $insert_supplier->plcbtype = '';
                                        $insert_supplier->estatus = 'Active';
                                        $insert_supplier->SID = session()->get('sid');
                                        $insert_supplier->save();
                                        
                                        $isupplierid = $insert_supplier->isupplierid;
                                        
                                        Supplier::find($isupplierid)->update(['vsuppliercode' => $isupplierid]);
                                        
                                        $supplier = $isupplierid;
                                    } else {
                                        $supplier = $suppliercount['vsuppliercode'];
                                    }
                                    
                                    //Group Info
                                    $groupcount = ItemGroup::where('vitemgroupname', $groupname)->get()->toArray();
                                    $groupcount = isset($groupcount[0])?$groupcount[0]:[];
                                    
                                    if(count($groupcount) == 0){
                                        // $group = $this->model_api_group->addGroupByName($groupname,$itemcode);
                                        $insert_group = new ItemGroup;
                                        $insert_group->vitemgroupname = $groupname;
                                        $insert_group->etransferstatus= '';
                                        $insert_group->SID = session()->get('sid');
                                        $insert_group->save();
                                        
                                        $group = $iitemgroupid = $insert_group->iitemgroupid;
                                        
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO itemgroupdetail SET  `iitemgroupid` = '" . (int)$iitemgroupid . "',`vsku` = '" . ($itemcode) . "',`isequence` = '',`vtype` = '',SID = '" . (int)(session()->get('sid'))."',`Id` = '". (int)$iitemgroupid . "'");
                                    } else {
                                        $group = isset($groupcount['iitemgroupid'])? $groupcount['iitemgroupid']:'';
                                    }

                                    $price = str_replace('$','',$price);

                                    $dunitprice = $price;
                                    if($price == ''){
                                        $dunitprice = '0.00';
                                    }

                                    $costprice = str_replace('$','',$costprice);

                                    $dcostPrice = $costprice;
                                    if($costprice == ''){
                                        $dcostPrice = '0.00';
                                    }
                                    
     
                                    $vtax1 = 'N';
                                    if($tax1 == 'Y'){
                                        $vtax1 = 'Y';
                                    }
                                    
                                    $vtax2 = 'N';
                                    if($tax2 == 'Y'){
                                        $vtax2 = 'Y';
                                    }
                                    
                                    $vtax3 = 'N';
                                    if(isset($tax3) && $tax3 == 'Y'){
                                        $vtax3 = 'Y';
                                    }
                                    
                                    //Food stamp
                                    $foodstamp = 'N';
                                    if($foodstamp == 'Y'){
                                        $foodstamp = 'Y';
                                    }
                                    
                                    //WIC Item
                                    $wicitem = 'N';
                                    if($wicitem == 'Y'){
                                        $wicitem = 'Y';
                                    }

                                    if(strlen($iqoh) == 0){
                                        $iqoh = "0";
                                    }

                                    if(isset($npack) && (strlen($npack) == '0' || $npack == '0')){
                                        $npack = 1;
                                    }elseif(isset($npack)){
                                        $npack = $npack;
                                    }else{
                                        $npack = 1;
                                    }


                                    //Age Verification Info
                                    $ageverificationcount = AgeVerification::where('vvalue', $ageverification)->get()->toArray();
                                    
                                    if(count($ageverificationcount) == 0){
                                        // $ageverification_query = $this->model_api_age_verification->addAgeVerificationByValue($ageverification);
                                        $addAgeVerification = new AgeVerification;
                                        $addAgeVerification->vname = $ageverification;
                                        $addAgeVerification->vvalue = $ageverification;
                                        $addAgeVerification->SID = session()->get('sid');
                                        $addAgeVerification->save();
                                    }
                                

                                    if($dcostPrice == '0.00' || $dcostPrice == '0.0000'){
                                        $nunitcost = sprintf("%.4f", $dcostPrice);
                                    }

                                    if(($dcostPrice != '0.00' || $dcostPrice != '0.0000') && $npack != '0'){
                                        $nunitcost = $dcostPrice / $npack;
                                        $nunitcost = sprintf("%.4f", $nunitcost);
                                    }else{
                                        $nunitcost = '0.0000';
                                    }
                            
                                    
                                    //Selling Unit
                                    $sellunit = $sellingunit == ''?1:(int)$sellingunit;


                                    $data = array();
                                    
                                    
                                    $data['dlastupdated'] = date('Y-m-d');
                                    $data['dcreated'] = date('Y-m-d');
                                    $data['vbarcode'] = $itemcode;
                                    $data['estatus'] = 'Active';
                                    $data['vshowimage'] = 'No';
                                    $data['iquantity'] = '0';
                                    $data['ireorderpoint'] = '0';
                                    $data['reorder_duration'] = '0';
                                    $data['npack'] = $npack;
                                    $data['nunitcost'] = $nunitcost;
                                    $data['ionupload'] = '0';
                                    $data['vcolorcode'] = '';

                                    //Order according to Maun
                                    $data['vitemtype'] = $itemtype;
                                    $data['vitemcode'] = $itemcode;
                                    $data['vitemname'] = $itemname;
                                    $data['vdescription'] = $itemdescription;
                                    $data['vunitcode'] = $unit;
                                    $data['vdepcode'] = $vdepcode;
                                    $data['vcategorycode'] = $vcatcode;
                                    $data['subcat_id'] = $vsubcatcode;
                                    $data['vsuppliercode'] = $supplier;

                                    $data['vsize'] = $size;
                                    $data['dcostPrice'] = $dcostPrice;
                                    $data['dunitprice'] = $dunitprice;
                                    $data['iqtyonhand'] = $iqoh;
                                    $data['vtax1'] = $vtax1;
                                    $data['vtax2'] = $vtax2;
                                    $data['vtax3'] = $vtax3;
                                    
                                    $data['nsellunit'] = $sellunit;
                                    $data['vfooditem'] = $foodstamp;
                                    $data['wicitem'] = $wicitem;
                                    $data['vageverify'] = $ageverification;
                                    $data['itemimage'] = isset($picture)? $picture:'';
                                    
                                    $new_database = session()->get('new_database');
                                    if($new_database === false){
                                        //==================================================== OLD DATABASE =================================================
                                        $Olditem = new Olditem;
                                        $Olditem->addImportItems($data);
                                    } else {
                                        $Item = new Item;
                                        $Item->addImportItems($data);
                                    }

                                    // $this->model_api_items->addImportItems($data);

                                    $msg_exist .= 'Item: '.$itemcode.' inserted.'.PHP_EOL;
                                }else{
                                    $msg_exist .= 'Item: '.$itemcode.' already exist.'.PHP_EOL;
                                    
                                }
                            }
                        }   
                    }
                    $line_row_index++;
                }
                
                $file_path = storage_path("logs/items/import-item-status-report.txt");
                
                //open a file in public folder
                $myfile = fopen($file_path, 'w');
                
                
                // $file_path = DIR_TEMPLATE."/administration/import-item-status-report.txt";

                // $myfile = fopen( DIR_TEMPLATE."/administration/import-item-status-report.txt", "w");
                
                fwrite($myfile,$msg_exist);
                fclose($myfile);

                $return['code'] = 1;
                $return['success'] = "Imported successfully!";
                return response(json_encode($return))
                  ->header('Content-Type', 'application/json');
                exit;
                
            }else{
                $return['code'] = 0;
                $return['error'] = "file not found!";
            }
        }else{
            $return['code'] = 0;
            $return['error'] = "Please select file!";
        }
        return response(json_encode($return), 401)
                  ->header('Content-Type', 'application/json');
        exit;
    }
    
    
    public function delete_vendor_code(Request $request) {
        
        $json =array();
        
        if ($request->isMethod('post')) {

            $temp_arr = json_decode(file_get_contents('php://input'), true);
            
            $item = new Item();
            $vendoritemid = session()->get('vendoritemid');
            $data = $item->deleteVendorItemCode($temp_arr,$vendoritemid);
            
            session()->put('tab_selected', 'vendor_tab');
            
            if(isset($data['error'])){
               $data['error'] = $data['error']; 
               return response(json_encode($data),403)
               ->header('Content-Type', 'application/json');
             exit;
            }
            else{
            return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');
            exit;
            }

        }else{
            $data['error'] = 'Something went wrong';
            // http_response_code(401);
            return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
            exit;
        }
    }
    
    public function get_status_import(){
        
        $file_path = storage_path("logs/items/import-item-status-report.txt");
        return response()->download($file_path);
    }

}

?>