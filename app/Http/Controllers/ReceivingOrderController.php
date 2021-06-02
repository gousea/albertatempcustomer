<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use App\Model\Item;
use App\Model\ReceivingOrder;
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
use App\Model\Store;
use App\Model\StoreSettings;
use App\Model\Vendor;


class ReceivingOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    protected function getList(Request $request) 
    {
        //check if the total_cost column exists in the table
	    $query_check_column = 'SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = \'u'.session()->get('sid').'\' AND TABLE_NAME = \'mst_missingitem\' 
	                            AND COLUMN_NAME = \'total_cost\'';
	    
	    
	    $check_column = DB::connection('mysql')->select($query_check_column);
	    
	    //if it does not create it
	    if(count($check_column) === 0){
	        $add_column_query = 'ALTER TABLE `u'.session()->get('sid').'`.`mst_missingitem` ADD COLUMN `total_cost` DECIMAL(15,2) NULL DEFAULT \'0.00\' AFTER `norderqty`';
	        DB::connection('mysql')->statement($add_column_query);
	    }
        
		$input = $request->all();
		
		if(isset($input['sort'])){
		    $sort = $input['sort'];
		}else{
		    $sort = 'LastUpdate';
		}
		
		if(isset($input['order'])){
		    $order = $input['order'];
		}else{
		    $order = 'DESC';
		}
        
		$data['current_url'] = url('/ReceivingOrder');
		$data['add'] = url('/ReceivingOrder/add');
		$data['edit'] = url('/ReceivingOrder/edit');
		$data['delete'] = url('/ReceivingOrder/delete');
		$data['edit_list'] = url('/ReceivingOrder/edit_list');
        
		$data['import_invoice_new'] = url('/ReceivingOrder/import_invoice_new');
		$data['import_missing_items'] = url('/ReceivingOrder/import_missing_items');
		$data['get_vendor_data'] = url('/ReceivingOrder/get_vendor_data');
		
        if($order == 'DESC'){
            $data['sort_estatus'] = url('/ReceivingOrder?sort=estatus&order=ASC');
            $data['sort_vponumber'] = url('/ReceivingOrder?sort=vponumber&order=ASC');
            $data['sort_vvendorname'] = url('/ReceivingOrder?sort=vvendorname&order=ASC');
            $data['sort_vordertype'] = url('/ReceivingOrder?sort=vordertype&order=ASC');
            $data['sort_dcreatedate'] = url('/ReceivingOrder?sort=dcreatedate&order=ASC');
            $data['sort_dreceiveddate'] = url('/ReceivingOrder?sort=dreceiveddate&order=ASC');
            $data['sort_LastUpdate'] = url('/ReceivingOrder?sort=LastUpdate&order=ASC');
        }else{
            $data['sort_estatus'] = url('/ReceivingOrder');
            $data['sort_vponumber'] = url('/ReceivingOrder');
            $data['sort_vvendorname'] = url('/ReceivingOrder');
            $data['sort_vordertype'] = url('/ReceivingOrder');
            $data['sort_dcreatedate'] = url('/ReceivingOrder');
            $data['sort_dreceiveddate'] = url('/ReceivingOrder');
            $data['sort_LastUpdate'] = url('/ReceivingOrder');
        }
		
		$data['SID'] = session()->get('sid');
		$receiving_orders = array();
        
		$results = ReceivingOrder::orderBy($sort, $order)->paginate(20);
		
		foreach ($results as $result) {
			
			$receiving_orders[] = array(
				'iroid'  		=> $result->iroid,
				'estatus'     	=> $result->estatus,
				'vponumber' 	=> $result->vponumber,
				'nnettotal' 	=> $result->nnettotal,
				'vinvoiceno' 	=> $result->vinvoiceno,
				'vvendorname'   => $result->vvendorname,
				'vordertype'  	=> $result->vordertype,
				'dcreatedate'  	=> $result->dcreatedate,
				'dreceiveddate' => $result->dreceiveddate,
				'dlastupdate'  	=> $result->LastUpdate,
				'view'          => url('/ReceivingOrder/info' . '?iroid=' . $result->iroid ),
				'edit'          => url('/ReceivingOrder/edit' . '?iroid=' . $result->iroid ),
				'delete'        => url('/ReceivingOrder/delete' . '?iroid=' . $result->iroid )
			);
		}
        
		$data['results'] = $results;
		
		$ReceivingOrder = new ReceivingOrder;
		$missing_item_results = $ReceivingOrder->getMissingItems();
        
		$missing_items = array();
        
		if(count($missing_item_results) > 0){
			foreach ($missing_item_results as $missing_item_result) {
			
				$missing_items[] = array(
					'imitemid'  		=> $missing_item_result['imitemid'],
					'estatus'     	=> $missing_item_result['estatus'],
					'vbarcode' 	=> $missing_item_result['vbarcode'],
					'vitemname' 	=> $missing_item_result['vitemname'],
					'vvendorname' 	=> $missing_item_result['vvendorname'],
					'vponumber'   => $missing_item_result['vponumber']
				);
			}
		}
		
		$data['vendors'] = $suppliers = Supplier::where('edi', '1')->orderBy('vcompanyname', 'ASC')->get()->toArray();
		
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
        
        $sid = session()->get('sid');
        $file_pa = storage_path("upc_log/error_import_barcode_log_file_".$sid.".csv");
        // dd(file_exists($file_pa));
        if(file_exists($file_pa)){            
            $fp = fopen($file_pa, 'r');
            
            $error_import_barcode = array();
            while (($strline = fgets($fp)) !== false){
                // $strline = chop($strline, '","');
                $error_import_barcode = json_decode($strline, TRUE);
                
            }
        }
        
        // dd($error_import_barcode);
        
        if(isset($error_import_barcode) && !empty($error_import_barcode)){
            return view('receiving_orders.receiving_orders_list',compact('data', 'receiving_orders', 'missing_items', 'error_import_barcode'));   
        }
        
		return view('receiving_orders.receiving_orders_list',compact('data', 'receiving_orders', 'missing_items'));
	}
	
	protected function getSearchList(Request $request) 
    {
		$input = $request->all();
		
		if(isset($input['sort'])){
		    $sort = $input['sort'];
		}else{
		    $sort = 'LastUpdate';
		}
		
		if(isset($input['order'])){
		    $order = $input['order'];
		}else{
		    $order = 'DESC';
		}
        
		$search_value = $input['columns'];
        
        $search_items = [];
        foreach($search_value as $value)
        {
            if($value["data"] == "estatus")
            {
                $search_items['estatus'] = $value['search']['value'];
            }
            else if($value["data"] == "vponumber")
            {
                $search_items['vponumber'] = $value['search']['value'];
            }
            else if($value["data"] == "nnettotal")
            {
                $search_items['nnettotal'] = $value['search']['value'];
            }
            else if($value["data"] == "vinvoiceno")
            {
                $search_items['vinvoiceno'] = $value['search']['value'];
            }
            else if($value["data"] == "vvendorname")
            {
                $search_items['vvendorname'] = $value['search']['value'];
            }
            else if($value["data"] == "vordertype")
            {
                $search_items['vordertype'] = $value['search']['value'];
            }
            
        }
		
		$data['SID'] = session()->get('sid');
		
        
        if(empty(trim($search_items['estatus'])) && empty(trim($search_items['vponumber'])) && empty(trim($search_items['nnettotal'])) && empty(trim($search_items['vinvoiceno'])) &&  empty(trim($search_items['vvendorname'])) && empty(trim($search_items['vordertype']))  )
        {
            $limit = 20;
            
            $start_from = ($input['start']);

            $select_query = "SELECT * FROM trn_receivingorder ORDER BY LastUpdate DESC LIMIT ". $input['start'].", ".$limit;

            $count_select_query = "SELECT COUNT(distinct iroid) as count FROM trn_receivingorder";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            $count_query = isset($count_query[0])?(array)$count_query[0]:[];
            
            $count_records = $count_total = (int)$count_query['count'];

        }else{
            $limit = 20;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length']; 
            $condition = "WHERE 1=1 ";
            $check_condition = 0;
            
            if(isset($search_items['estatus']) && !empty(trim($search_items['estatus']))){
                $search = ($search_items['estatus']);
                $condition .= " AND estatus LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }
            
            if(isset($search_items['vponumber']) && !empty(trim($search_items['vponumber']))){
                $search = ($search_items['vponumber']);
                $condition .= " AND vponumber LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }

            if(isset($search_items['nnettotal']) && !empty(trim($search_items['nnettotal']))){
                $search = ($search_items['nnettotal']);
                $condition .= " AND nnettotal LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }
            
            if(isset($search_items['vinvoiceno']) && !empty(trim($search_items['vinvoiceno']))){
                $search = ($search_items['vinvoiceno']);
                $condition .= " AND vinvoiceno LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }

            if(isset($search_items['vvendorname']) && !empty(trim($search_items['vvendorname']))){
                $search = ($search_items['vvendorname']);
                $condition .= " AND vvendorname LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }
            
            if(isset($search_items['vordertype']) && !empty(trim($search_items['vordertype']))){
                $search = ($search_items['vordertype']);
                $condition .= " AND vordertype LIKE  '%" . $search . "%'";
                $check_condition = 1;
            }
            
            $select_query = "SELECT * FROM trn_receivingorder ".$condition." ORDER BY LastUpdate DESC LIMIT ". $input['start'].", ".$limit;

            $count_select_query = "SELECT COUNT(distinct iroid) as count FROM trn_receivingorder ".$condition;
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            $count_query = isset($count_query[0])?(array)$count_query[0]:[];
            
            $count_records = $count_total = (int)$count_query['count'];
        }
        // dd($select_query);
        $query = DB::connection('mysql_dynamic')->select($select_query);
        
		$data['SID'] = session()->get('sid');
		$purchase_orders = array();
        
        $datas = array();
        if(count($query) > 0){
            foreach ($query as $key => $value) {

                if($value->estatus == 'Close'){
                    $view_edit = url('/ReceivingOrder/info' . '?iroid=' . $value->iroid );
                }else{
                    $view_edit = url('/ReceivingOrder/edit' . '?iroid=' . $value->iroid );
                }
                $temp = array();
                $temp['iroid']          = $value->iroid;
                $temp['estatus']        = $value->estatus;
                $temp['vponumber']      = $value->vponumber;
                $temp['nnettotal']      = $value->nnettotal;
                $temp['vinvoiceno']     = $value->vinvoiceno;
                $temp['vvendorname']    = $value->vvendorname;
                $temp['vordertype']     = $value->vordertype;
                $temp['dcreatedate']    = $value->dcreatedate;
                $temp['dreceiveddate']  = $value->dreceiveddate;
                $temp['LastUpdate']     = $value->LastUpdate;
                $temp['view_edit']      = $view_edit;
				$temp['delete']         = url('/ReceivingOrder/delete' . '?ipoid=' . $value->iroid );

                $datas[]                = $temp;
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
	
	public function edit_form(Request $request) 
	{
	    session()->forget('error_warning');
        session()->forget('receiving_order_info');
        
        $input = $request->all();
        
        $data = $this->getForm($input);
        
        return view('receiving_orders.receiving_order_form',compact('data'));
    }
    
    protected function getForm($input) 
    {
		$error_warning = session()->get('warning');
        if (isset($error_warning)) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        
		if (!isset($input['iroid'])) {
			$data['action'] = url('/ReceivingOrder/add_post');
			
		} else {
			$data['action'] = url('/ReceivingOrder/edit_post?iroid=' . $input['iroid']);
		}
        
		$data['cancel'] = url('/ReceivingOrder');
        
        $data['get_vendor'] = url('/PurchaseOrder/get_vendor');
		$data['get_search_items'] = url('/PurchaseOrder/get_search_items');
		$data['get_search_item'] = url('/PurchaseOrder/get_search_item');
		$data['check_invoice'] = url('/ReceivingOrder/check_invoice');
		$data['receiving_order_list'] = url('/ReceivingOrder');
		$data['delete_receiving_order_item'] = url('/ReceivingOrder/delete_receiving_order_item');
		$data['save_receive_item'] = url('/ReceivingOrder/save_receive_item');
		$data['add_receiving_order_item'] = url('/ReceivingOrder/add_receiving_order_item');

		$data['get_search_item_history'] = url('/ReceivingOrder/get_search_item_history');
		$data['search_vendor_item_code'] = url('/PurchaseOrder/search_vendor_item_code');
		$data['get_item_history'] = url('/ReceivingOrder/get_item_history');
		$data['get_item_history_date'] = url('/ReceivingOrder/get_item_history_date');
		
		$data['check_warehouse_invoice'] = url('/ReceivingOrder/transfer/check_invoice');
		
		$data['get_purchase_history'] = url('/ReceivingOrder/get_purchase_history');
		$data['get_receiving_history'] = url('/ReceivingOrder/get_receiving_history');
		
		//for new filters
		$data['searchitem']   = url('buydown/search');
		$data['get_categories_url']    = url('buydown/get_item_categories');
		$data['get_department_items_url']   = url('buydown/get_department_items');
		
		$departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        $data['departments'] = $departments;
        $departments_html = "";
        $departments_html = "<select class='form-control search_item_history' name='dept_code' id='dept_code' style='width: 85px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($departments as $department) {
            if (isset($vdepcode) && $vdepcode == $department['vdepcode']) {
                $departments_html .= "<option value='" . $department['vdepcode'] . "' selected='selected'>" . $department['vdepartmentname'] . "</option>";
            } else {
                $departments_html .= "<option value='" . $department['vdepcode'] . "'>" . $department['vdepartmentname'] . "</option>";
            }
        }
        $departments_html .= "</select>";

        $data['departments'] = $departments_html;
		
		
        $suppliers = Vendor::orderBy('vcompanyname', 'ASC')->get()->toArray();
        $supplier_html = "";
        $supplier_html = "<select class='form-control search_item_history' name='supplier_code' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($suppliers as $supplier) {
            $supplier_html .= "<option value='" . $supplier['vsuppliercode'] . "'>" . $supplier['vcompanyname'] . "</option>";
        }
        $supplier_html .= "</select>";

        $data['suppliers'] = $supplier_html;
        
        $sizes = Size::orderBy('vsize', 'ASC')->get()->toArray();
        $size_html = "";
        $size_html = "<select class='form-control search_item_history' name='size_id' id='size_id' style='width: 50px; padding: 0px; font-size: 9px;'>'<option value='all'>All</option>";
        foreach ($sizes as $size) {
            $size_html .= "<option value='" . addslashes($size['vsize']) . "'>" . addslashes($size['vsize']) . "</option>";
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

        $price_select_by_html = "<select class='search_item_history' id='price_select_by' name='price_select_by' style='width:90px; color:#000000'>";
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
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box search_item_history' placeholder='Enter' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box search_item_history' placeholder='Amt' style='width:40px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
        } else {
            $price_select_by_html .= "<input type='number' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box search_item_history' placeholder='Enter Amt' style='width:70px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
        }

        $price_select_by_html .= "</span>";


        $data['price'] = $price_select_by_html;
		
		if (isset($input['iroid'])) {
            
            $ReceivingOrder = new ReceivingOrder;
			$receiving_order_info = $ReceivingOrder->getReceivingOrder($input['iroid']);
			
			session()->put('receiving_order_info', $receiving_order_info);
			    
			$data['iroid'] = $input['iroid'];
		}
		
		$data['sid'] = session()->get('sid');
		
		if (isset($input['vponumber'])) {
			$data['vponumber'] = $input['vponumber'];
		} elseif (!empty($receiving_order_info)) {
			$data['vponumber'] = $receiving_order_info['vponumber'];
		} else {
				$temp_vponumber = ReceivingOrder::orderBy('iroid', 'desc')->first();
			
			if(isset($temp_vponumber)){
			    $temp_vponumber = $temp_vponumber->toArray();
                if(isset($temp_vponumber['iroid'])){
                	$data['vponumber'] = str_pad($temp_vponumber['iroid']+1,9,"0",STR_PAD_LEFT);
                }else{
                	$data['vponumber'] = str_pad(1,9,"0",STR_PAD_LEFT);
                }
			}else{
            	$data['vponumber'] = str_pad(1,9,"0",STR_PAD_LEFT);
            }
		}
		

		if (isset($input['nnettotal'])) {
			$data['nnettotal'] = $input['nnettotal'];
		} elseif (!empty($receiving_order_info)) {
			$data['nnettotal'] = $receiving_order_info['nnettotal'];
		}

		if (isset($input['ntaxtotal'])) {
			$data['ntaxtotal'] = $input['ntaxtotal'];
		} elseif (!empty($receiving_order_info)) {
			$data['ntaxtotal'] = $receiving_order_info['ntaxtotal'];
		}

		if (isset($input['dcreatedate'])) {
			$data['dcreatedate'] = $input['dcreatedate'];
		} elseif (!empty($receiving_order_info)) {
			$data['dcreatedate'] = $receiving_order_info['dcreatedate'];
		}
        
        if(isset($data['dcreatedate'])){
          $dcreatedate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['dcreatedate']);
          $data['dcreatedate'] = $dcreatedate->format('m-d-Y');
        }
        
		if (isset($input['dreceiveddate'])) {
			$data['dreceiveddate'] = $input['dreceiveddate'];
		} elseif (!empty($receiving_order_info)) {
			$data['dreceiveddate'] = $receiving_order_info['dreceiveddate'];
		}
        
        if(isset($data['dreceiveddate'])){
          $dreceiveddate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['dreceiveddate']);
          $data['dreceiveddate'] = $dreceiveddate->format('m-d-Y');
        }
        
		if (isset($input['estatus'])) {
			$data['estatus'] = $input['estatus'];
		} elseif (!empty($receiving_order_info)) {
			$data['estatus'] = $receiving_order_info['estatus'];
		}

		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($receiving_order_info)) {
			$data['nfreightcharge'] = $receiving_order_info['nfreightcharge'];
		}

		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($receiving_order_info)) {
			$data['nfreightcharge'] = $receiving_order_info['nfreightcharge'];
		}

		if (isset($input['vordertitle'])) {
			$data['vordertitle'] = $input['vordertitle'];
		} elseif (!empty($receiving_order_info)) {
			$data['vordertitle'] = $receiving_order_info['vordertitle'];
		}

		if (isset($input['vorderby'])) {
			$data['vorderby'] = $input['vorderby'];
		} elseif (!empty($receiving_order_info)) {
			$data['vorderby'] = $receiving_order_info['vorderby'];
		}

		if (isset($input['vconfirmby'])) {
			$data['vconfirmby'] = $input['vconfirmby'];
		} elseif (!empty($receiving_order_info)) {
			$data['vconfirmby'] = $receiving_order_info['vconfirmby'];
		}
        
		if (isset($input['vnotes'])) {
			$data['vnotes'] = $input['vnotes'];
		} elseif (!empty($receiving_order_info)) {
			$data['vnotes'] = $receiving_order_info['vnotes'];
		}
        
		if (isset($input['vvendorid'])) {
			$data['vvendorid'] = $input['vvendorid'];
		} elseif (!empty($receiving_order_info)) {
			$data['vvendorid'] = $receiving_order_info['vvendorid'];
		}else{
			$data['vvendorid'] = "";
		}
        
		if (isset($input['vvendorname'])) {
			$data['vvendorname'] = $input['vvendorname'];
			
		} elseif (!empty($receiving_order_info)) {
			$data['vvendorname'] = $receiving_order_info['vvendorname'];
		}else{
		    $data['vvendorname'] = '';
		}
        
		if (isset($input['vvendoraddress1'])) {
			$data['vvendoraddress1'] = $input['vvendoraddress1'];
		} elseif (!empty($receiving_order_info)) {
			$data['vvendoraddress1'] = $receiving_order_info['vvendoraddress1'];
		}
        
		if (isset($input['vvendoraddress2'])) {
			$data['vvendoraddress2'] = $input['vvendoraddress2'];
		} elseif (!empty($receiving_order_info)) {
			$data['vvendoraddress2'] = $receiving_order_info['vvendoraddress2'];
		}

		if (isset($input['vvendorstate'])) {
			$data['vvendorstate'] = $input['vvendorstate'];
		} elseif (!empty($receiving_order_info)) {
			$data['vvendorstate'] = $receiving_order_info['vvendorstate'];
		}

		if (isset($input['vvendorzip'])) {
			$data['vvendorzip'] = $input['vvendorzip'];
		} elseif (!empty($receiving_order_info)) {
			$data['vvendorzip'] = $receiving_order_info['vvendorzip'];
		}

		if (isset($input['vvendorphone'])) {
			$data['vvendorphone'] = $input['vvendorphone'];
		} elseif (!empty($receiving_order_info)) {
			$data['vvendorphone'] = $receiving_order_info['vvendorphone'];
		}

		if (isset($input['vshipvia'])) {
			$data['vshipvia'] = $input['vshipvia'];
		} elseif (!empty($receiving_order_info)) {
			$data['vshipvia'] = $receiving_order_info['vshipvia'];
		}

		if (isset($input['nrectotal'])) {
			$data['nrectotal'] = $input['nrectotal'];
		} elseif (!empty($receiving_order_info)) {
			$data['nrectotal'] = $receiving_order_info['nrectotal'];
		}

		if (isset($input['nsubtotal'])) {
			$data['nsubtotal'] = $input['nsubtotal'];
		} elseif (!empty($receiving_order_info)) {
			$data['nsubtotal'] = $receiving_order_info['nsubtotal'];
		}

		if (isset($input['ndeposittotal'])) {
			$data['ndeposittotal'] = $input['ndeposittotal'];
		} elseif (!empty($receiving_order_info)) {
			$data['ndeposittotal'] = $receiving_order_info['ndeposittotal'];
		}
		
		if (isset($input['nfuelcharge'])) {
			$data['nfuelcharge'] = $input['nfuelcharge'];
		} elseif (!empty($receiving_order_info)) {
			$data['nfuelcharge'] = $receiving_order_info['nfuelcharge'];
		}
		
		if (isset($input['ndeliverycharge'])) {
			$data['ndeliverycharge'] = $input['ndeliverycharge'];
		} elseif (!empty($receiving_order_info)) {
			$data['ndeliverycharge'] = $receiving_order_info['ndeliverycharge'];
		}

		if (isset($input['nreturntotal'])) {
			$data['nreturntotal'] = $input['nreturntotal'];
		} elseif (!empty($receiving_order_info)) {
			$data['nreturntotal'] = $receiving_order_info['nreturntotal'];
		}

		if (isset($input['vinvoiceno'])) {
			$data['vinvoiceno'] = $input['vinvoiceno'];
		} elseif (!empty($receiving_order_info)) {
			$data['vinvoiceno'] = $receiving_order_info['vinvoiceno'];
		}

		if (isset($input['ndiscountamt'])) {
			$data['ndiscountamt'] = $input['ndiscountamt'];
		} elseif (!empty($receiving_order_info)) {
			$data['ndiscountamt'] = $receiving_order_info['ndiscountamt'];
		}
        
		if (isset($input['nripsamt'])) {
			$data['nripsamt'] = $input['nripsamt'];
		} elseif (!empty($receiving_order_info)) {
			$data['nripsamt'] = $receiving_order_info['nripsamt'];
		}
        
		if (!empty($receiving_order_info)) {
			$data['items'] = $receiving_order_info['items'];
		}else{
			$data['items'] = array();
		}
        
		$data['items_id'] = array();
        
		if(count($data['items']) > 0){
			foreach ($data['items'] as $k => $v) {
				array_push($data['items_id'], $v['vitemid']);
			}
		}
        
		$data['vendors'] = $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
		$data['store'] = Store::All()->toArray();
        
		return $data;
	}
	
	public function add_post(Request $request) 
	{
		
		if ($request->isMethod('post')) {
            
            $input = $request->all();
            
			$ReceivingOrder = new ReceivingOrder;
			$data_response = $ReceivingOrder->addReceivingOrder($input, null, 'PO');
            
			if(array_key_exists("success",$data_response)){
                
                return response(json_encode($data_response), 200)
                  ->header('Content-Type', 'application/json');
                  
			}elseif(array_key_exists("error",$data_response)){
                
                return response(json_encode($data_response), 200)
                  ->header('Content-Type', 'application/json');
            }else{
                
                return response(json_encode($data_response), 401)
                  ->header('Content-Type', 'application/json');
			}
            
		}
	}
	
	public function edit_post(Request $request) 
	{
		
		if ($request->isMethod('post')) {
            
            $input = $request->all();
           
            $ReceivingOrder = new ReceivingOrder;
			$data_response = $ReceivingOrder->editReceivingOrder($input, null, 'PO');
            
			if(array_key_exists("success",$data_response)){
				
				return response(json_encode($data_response), 200)
                  ->header('Content-Type', 'application/json');
                
            }elseif(array_key_exists("error",$data_response)){
                
                return response(json_encode($data_response), 200)
                  ->header('Content-Type', 'application/json');
            }else{
                
                return response(json_encode($data_response), 401)
                  ->header('Content-Type', 'application/json');
			}
            
			
		}

		
	}
	
	public function check_invoice(Request $request) 
	{
		$data = array();
		
		if ($request->isMethod('post') ) {
            
			$temp_arr = json_decode(file_get_contents('php://input'), true);
            
			if($temp_arr['invoice'] == ''){
				$data['validation_error'] = 'Invoice Required';
			}
			
			if(!array_key_exists("validation_error",$data)){
                
                $ReceivingOrder = new ReceivingOrder;
				$data = $ReceivingOrder->checkExistInvoice($temp_arr['invoice']);
                
				if(array_key_exists("success",$data)){
                    
                    return response(json_encode($data), 200)
                        ->header('Content-Type', 'application/json');
				}else{
					return response(json_encode($data), 500)
                        ->header('Content-Type', 'application/json');
				}
                
			}else{
				return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
			}
            
		}else{
			$data['error'] = 'Something went wrong missing token or sid or search field';
			return response(json_encode($data), 401)
                  ->header('Content-Type', 'application/json');
		}
	}
	
	public function get_item_history(Request $request)
	{
		$input = $request->all();
        
		$json = array();
        
		if (isset($input['iitemid']) && isset($input['radio_search_by']) && isset($input['vitemcode'])) {
                
            $ReceivingOrder = new ReceivingOrder;
			$json = $ReceivingOrder->getSearchItemData($input['iitemid'],$input['radio_search_by'],$input['vitemcode'],null,null);
            
		}
		
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}

	public function get_item_history_date(Request $request) 
	{
		$input = $request->all();
        
		$json = array();
        
		if (isset($input['iitemid']) && isset($input['start_date']) && isset($input['end_date']) && isset($input['vitemcode'])) {
			
			$ReceivingOrder = new ReceivingOrder;
			$json = $ReceivingOrder->getSearchItemData($input['iitemid'],null,$input['vitemcode'],$input['start_date'],$input['end_date']);
            
		}
		
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
	public function add_receiving_order_item(Request $request) 
	{
		$json = array();
        
		if ($request->isMethod('post')) {
			$posted_arr = json_decode(file_get_contents('php://input'), true);
			
			$ivendorid = $posted_arr['ivendorid'];
			$items_id = $posted_arr['items_id'];
			$pre_items_id = $posted_arr['pre_items_id'];
            
			if(count($items_id) > 0){
				foreach ($items_id as $key => $item_id) {
					if(!in_array($item_id, $pre_items_id)){
                         
                        $ReceivingOrder = new ReceivingOrder;
                        $json['items'][] = $ReceivingOrder->getSearchItem($item_id,$ivendorid);
					}
				}
			}
		}
        
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
	public function check_invoice_transfer(Request $request)
	{
		$input = $request->all();
		$json = array();
		if($request->isMethod('post') && $input['invoice'] != '') {
            
            $ReceivingOrder = new ReceivingOrder;
            $json = $ReceivingOrder->getCheckInvoice($input['invoice']);
		}
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
    }
    
    public function get_receiving_history(Request $request) 
    {
        $input = $request->all();
		$json = array();
        
		if (isset($input['vitemcode'])) {
            
            $ReceivingOrder = new ReceivingOrder;
            $json = $ReceivingOrder->getReceivingData($input['vitemcode']);

		}
		
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
	public function save_receive_item(Request $request) 
	{
		$json = array();
		
		if ($request->isMethod('post')) {
            
            $input = $request->all();
            
            $ReceivingOrder = new ReceivingOrder;
			if(isset($input['items']) && count($input['items']) > 0){
                
				$item_response = $ReceivingOrder->addSaveReceiveItem($input);
                
			}
			$json = $item_response;
		}
        
		if(array_key_exists("success",$json)){
			
			return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
                  
		}elseif(array_key_exists("error",$json)){
			
			return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
		}else{
		    return response(json_encode($json), 401)
                  ->header('Content-Type', 'application/json');
		}
        
	}
	
	public function delete_receiving_order_item(Request $request)
	{
        
		if ($request->isMethod('post')) {
            
			$temp_arr = json_decode(file_get_contents('php://input'), true);
            
            $ReceivingOrder = new ReceivingOrder;
			$data = $ReceivingOrder->deleteItemReceive($temp_arr);
            
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
	
	public function import_missing_items(Request $request) 
	{
        $data = array();
		if ($request->isMethod('post')) {
                
			$temp_arr = json_decode(file_get_contents('php://input'), true);
                
			if(count($temp_arr) <= 0){
				$data['validation_error'] = 'Missing id of missing items';
			}
			
			if(!array_key_exists("validation_error",$data)){
                
                $ReceivingOrder = new ReceivingOrder;
				$data = $ReceivingOrder->importMissingItems($temp_arr);
                
				if(array_key_exists("success",$data)){
				    
				    // 	http_response_code(200);
				    return response(json_encode($data), 200)
                        ->header('Content-Type', 'application/json');
				}else{
					return response(json_encode($data), 401)
                        ->header('Content-Type', 'application/json');
				}
                
			}else{
				return response(json_encode($data), 401)
                        ->header('Content-Type', 'application/json');
			}
                
		}else{
			$data['error'] = 'Something went wrong missing token or sid';
			 //   http_response_code(401);
			return response(json_encode($data), 401)
                        ->header('Content-Type', 'application/json');
		}
	}
	
	
// 	Disabled and kept as backup on 01 Dec 2020 at 4:58 pm IST: Will be removed eventually
// 	public function import_invoice_new(Request $request) 
// 	{
		
// 		$data = array();
// 		$json_return = array();
// 		$log_array = array();
        
//         $input = $request->all();
        
// 		if ($request->isMethod('post')) {
// 			if(isset($input['vvendorid'])){
// 				$data['vvendorid'] = $input['vvendorid'];
                
// 				if(isset($input['import_invoice_file']) && $request->hasFile('import_invoice_file')&& $request->file('import_invoice_file')->getClientOriginalName() != ''){
                    
// 					//variables
// 					$orderCount = 0;
// 					$poid = 0;
// 					$vCompanyName = '';
// 					$invoicenumber = '';
// 					$datemonth = '';
// 					$dateday = '';
// 					$dateyear = '';
// 					$totalAmount = '';
// 					$d = '';
// 					$vCode = '';
// 					$vname = '';
// 					$unitcost = '';
//                     $qtyor = '';
//                     $rPrice = '';
//                     $iitemid = '';
//                     $vcatcode = "3";
//                     $vdepcode = "3";
//                     $vtax1 = "N";
//                     $vvendoritemcode = '';
//                     $npack = '';
//                     $itotalunit = '';
//                     $nCost = '';
//                     $vSign = '';
                    
//                     $ReceivingOrder = new ReceivingOrder;
                    
//                     $checkcreateCategory = $ReceivingOrder->checkcreateCategory();
//                     $checkcreateDepartment = $ReceivingOrder->checkcreateDepartment();
                    
//                     // $vcategory = $this->model_api_receiving_order->getCategories();
//                     // if(count($vcategory) > 0){
//                     //     $vcatcode = $vcategory[0]['vcategorycode'];
//                     // }
                    
//                     // $vdepartment = $this->model_api_receiving_order->getDepartments();
//                     // if(count($vdepartment) > 0){
//                     //     $vdepcode = $vdepartment[0]['vdepcode'];
//                     // }
                    
//                     $dtVend = StoreSettings::where([
//                                                     ['vsettingname', '=', 'Tax1seletedfornewItem'],
//                                                     ['vsettingvalue', '=', 'Yes']
//                                                     ])->get()->toArray();
//                     if(count($dtVend) > 0){
//                         $vtax1 = "Y";
//                     }
                    
//                     $DTVENDOR = Supplier::where('isupplierid', $input['vvendorid'])->get()->toArray();
//                     $DTVENDOR = isset($DTVENDOR[0])?$DTVENDOR[0]:[];
                    
// 					$import_invoice_file = $request->file('import_invoice_file')->getPathName();
                    
// 					$handle = fopen($import_invoice_file, "r");
                    
					
// 					$nReturnTotal = 0;
// 					$msg_inactive_item = '';
                    
// 					$import_invoice_file = $request->file('import_invoice_file')->getPathName();
// 					$handle1 = fopen($import_invoice_file, "r");
					
			        
//                     $headers = ["SKU", "Item Name", "Vendor Item Code","QOH", "Selling Price ", "Unit per case", "Total amount", "Unit Cost ", "vSign"];
                    
//                     $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                    
//                     $fp = fopen($file_path, 'w');
//                     fputcsv($fp, $headers);
                    
//                     fclose($fp);
                    
//                     $check_count;
					
// 					if ($handle1) {
					    
// 					    //=======We don't have exact id of particular vendor so we use NAME for condition(if)========
//                         if($DTVENDOR['vcompanyname'] == 'ALLEN BROTHERS'){
                            
//                             // if(strcasecmp($DTVENDOR['vcompanyname'], "ALLEN BROTHERS") == 0)
                            
//                             while (($strline = fgets($handle1)) !== false) {
//                                 $FirstChar = substr($strline,0,1);
                                
//                                 if ($FirstChar == "A"){
//                                     $orderCount = $orderCount + 1;
//                                     $vCompanyName = substr($strline, 1, 6);
//                                     $invoicenumber = substr($strline, 7, 10);
                                    
//                                     if($vCompanyName != 'ALLENB'){
//                                         $json_return['code'] = 0;
//                                         $json_return['error'] = 'Please Import Correct Vendor EDI Invoice Format';
//                                         echo json_encode($json_return);
//                                         exit;
//                                     }
                                    
//                                     $dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->get()->toArray();
                                    	
//                                     if(count($dtCh) > 0){
//                                         $json_return['code'] = 0;
//                                         $json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
//                                         echo json_encode($json_return);
//                                         exit;
//                                     }
//                                     $datemonth = substr($strline, 17, 2);
//                                     $dateday = substr($strline, 19, 2);
//                                     $dateyear = substr($strline, 21, 2);
//                                     $totalAmount = substr($strline, 24, 9);
                                    
//                                     if (strlen($totalAmount) > 0){
//                                         $totalAmount = (sprintf("%.2f", $totalAmount)/100);
//                                     }
                                    
//                                     $dt_year = \DateTime::createFromFormat('y', $dateyear);
//                                     $dt_year = $dt_year->format('Y');
                                    
//                                     $d = $datemonth .'-'. $dateday .'-'. $dt_year;
                                    
//                                     $strdue1 = $d.' '.date('H:i:s');
                                    
//                                     $strdue1 = \DateTime::createFromFormat('m-d-Y H:i:s', $strdue1);
//                                     $strdue1 = $strdue1->format('Y-m-d H:i:s');
                                    
//                                     $nReturnTotal = 0;
                                    
//                                     $trnPurchaseorderdto = array();
                                    
//                                     $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
//                                     $trnPurchaseorderdto['nripsamt'] = 0;
//                                     $trnPurchaseorderdto['dduedatetime'] = $strdue1;
//                                     $trnPurchaseorderdto['nsubtotal'] = 0;
//                                     $trnPurchaseorderdto['nreturntotal'] = 0;
//                                     $trnPurchaseorderdto['nrectotal'] = 0;
//                                     $trnPurchaseorderdto['ndeposittotal'] = 0;
//                                     $trnPurchaseorderdto['ndiscountamt'] = 0;
//                                     $trnPurchaseorderdto['vinvoiceno'] = $invoicenumber;
//                                     $trnPurchaseorderdto['vponumber'] = $invoicenumber;
//                                     $trnPurchaseorderdto['vrefnumber'] = $invoicenumber;
//                                     $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
//                                     $trnPurchaseorderdto['ntaxtotal'] = 0;
//                                     $trnPurchaseorderdto['dcreatedate'] = $strdue1;
//                                     $trnPurchaseorderdto['estatus'] = "Open";
//                                     $trnPurchaseorderdto['nfreightcharge'] = 0;
//                                     $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
//                                     $trnPurchaseorderdto['vvendoraddress2'] = '';
//                                     $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
//                                     $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
//                                     $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
//                                     $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
//                                     $trnPurchaseorderdto['vordertitle'] = '';
//                                     $trnPurchaseorderdto['vordertype'] = "";
//                                     $trnPurchaseorderdto['vconfirmby'] = "";
//                                     $trnPurchaseorderdto['vorderby'] = "";
//                                     $trnPurchaseorderdto['vshpid'] = "0";
//                                     $trnPurchaseorderdto['vshpname'] = "";
//                                     $trnPurchaseorderdto['vshpaddress1'] = "";
//                                     $trnPurchaseorderdto['vshpaddress2'] = "";
//                                     $trnPurchaseorderdto['vshpzip'] = "";
//                                     $trnPurchaseorderdto['vshpstate'] ="";
//                                     $trnPurchaseorderdto['vshpphone'] = "";
//                                     $trnPurchaseorderdto['vshipvia'] = "";
//                                     $trnPurchaseorderdto['vnotes'] = "";
                                    
//                                     $ReceivingOrder = new ReceivingOrder;
//                                     $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
//                                     $check_count = 0;
                                    
//                                 }
//                                 // $heading = implode(",",$headers );
//                                 // $fp = fopen('upc_log/Upc_conversion_log_file.csv', 'w');
//                                 // // fputcsv($fp, $headers);
//                                 // fwrite($fp,$heading."\n");
                                
                                
//                                 if ($FirstChar == "B"){
                                    
//                                 // 	$vCode = (substr($strline, 1, 12)); //// Already included check digit
//                                     $vCode = (substr($strline, 1, 11));
//                                     $vCode = trim($vCode," ");
//                                     $vname = substr($strline, 13, 25);
//                                     $vname = str_replace("'","",$vname);                  
//                                     $unitcost = substr($strline, 44, 6);
//                                     $qtyor = substr($strline, 59, 4);
//                                     $qtyor = (int)$qtyor;
//                                     $rPrice = substr($strline, 63, 6);
//                                     $vvendoritemcode = substr($strline, 38, 6);
//                                     $npack = substr($strline, 52, 6);
//                                     $npack = (int)$npack;
//                                     $vSign = substr($strline, 57, 1);
                                    
                                    	                            
                                    	                            
//                                     if (strlen($unitcost) == 0){
//                                         $unitcost = "0";
//                                     }
                                    
//                                     if (strlen($qtyor) == 0){
//                                         $qtyor = "0";
//                                     }
                                    
//                                     if (strlen($rPrice) == 0){
//                                         $rPrice = "0";
//                                     }
                                    
//                                     if ($unitcost != "0"){
//                                         $unitcost = (sprintf("%.2f", $unitcost)/100);
//                                     }
                                    
//                                     if ($rPrice != "0"){
//                                         $rPrice = (sprintf("%.2f", $rPrice)/100);
//                                     }
                                    
//                                     if (strlen($npack) == 0 || $npack == 0){
//                                         $npack = "1";
//                                     }
                                    
//                                     $nCost = $unitcost;
//                                     $unitcost = $unitcost / (int)$npack;
//                                     $itotalunit = (int)$qtyor * (int)$npack;
//                                     $totAmt = (int)$qtyor * $nCost;
                                    
//                                     if ($vSign == "-"){
//                                         $nReturnTotal += $totAmt;
                                        
//                                         $itotalunit = (int)$itotalunit *-1;
                                        
//                                         $qtyor = (int)$qtyor*-1;
//                                     }
                                    
                                    
//                                     $old_vCode = "";
//                                   /*Check digit and first digit*/
//                                   if(isset($input['check_digit']) && $input['check_digit'] == 'with_check_digit')
//                                   {
//                                         $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
//                                         if($check_digit_barcode != -1)
//                                         {
//                                             $old_vCode = $vCode;
//                                         	$vCode = $vCode.''.$check_digit_barcode;
//                                         }
                        
//                                   }
//                                   if(isset($input['check_digit']) && $input['check_digit'] == 'without_check_digit')
//                                   {
//                                         $vCode = (substr($strline, 1, 11));
//                                     	$vCode = trim($vCode," ");
//                                   }
//                                   else if(isset($input['check_digit']) && $input['check_digit'] == 'without_first_digit')
//                                   {
//                                         $vCode = (substr($strline, 2, 10));
//                                         $vCode = trim($vCode," ");
//                                   }
//                                   /*Check digit and first digit*/
                                  
//                                     //=====Limit 100 Items per invoice=========
//                                     $check_count = $check_count + 1;
                                            
//                                     if($check_count <= 100){
                                    
//                                         $ReceivingOrder = new ReceivingOrder;
//                                         $dtC = $ReceivingOrder->getItemByBarCode($vCode);
//                                         // print_r($dtC);
//                                         if (count($dtC) == 0){
//                                           // $vCode = substr($strline, 1, 11);
//                                           // $vCode = trim($vCode," ");
                                            
//                                             $barcode_11_digit = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                             if(!empty($barcode_11_digit))
//                                             {
//                                                 if(isset($input['check_digit']) && $input['check_digit'] == 'with_check_digit')
//                                                 {
//                                                 	$old_vCode = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                                     if(count($old_vCode) > 0)
//                                                     {
//                                                         $ReceivingOrder->updateBarcode($vCode,$old_vCode['vbarcode']);
//                                                     }
//                                                 } 
                                                
//                                                 else if(isset($input['check_digit']) && $input['check_digit'] == 'without_first_digit')
//                                                 {
//                                                  //  $vCode = (substr($strline, 2, 10));
//                                         	       // $vCode = trim($vCode," ");
                                        	        
//                                         	        $new_vCode = trim(substr($strline, 2, 11));
                                                    
//                                                 	$_vCode = $ReceivingOrder->getItemByBarCode($new_vCode);
//                                                     if(count($_vCode) > 0)
//                                                     {
//                                                     	$old_vCode = $_vCode['vbarcode'];
//                                                     	$dtC = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                                         if(count($dtC) > 0)
//                                                         {   
//                                                         	$ReceivingOrder->updateBarcode($new_vCode,$old_vCode);
//                                                         }
//                                                     }
//                                                     else
//                                                     {
//                                                     	$vCode = $new_vCode;
//                                                     }
                                                    
//                                                 } 
//                                             }
                                            
//                                         }
                                        
//                                         if(count($dtC) > 0){
//                                         	//update item status
//                                         	// if($dtC['estatus'] == 'Inactive'){
//                                         	// 	$msg_inactive_item .= 'Item Barcode: '.$dtC['vbarcode'].PHP_EOL;
//                                         	// }
//                                         	$ReceivingOrder->updateItemStatus($dtC["iitemid"]);
//                                         	//update item status
                                                
//                                         	$iitemid = $dtC["iitemid"];
//                                         // 	$dtI = $this->model_api_receiving_order->getItemVendorByVendorItemCode($vvendoritemcode);
//                                             $dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
//                                             if(count($dtI) == 0){
//                                                 $mstItemVendorDto = array();
//                                                 $mstItemVendorDto['iitemid'] = $iitemid;
//                                                 $mstItemVendorDto['ivendorid'] = $input['vvendorid'];
//                                                 $mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                                                
//                                                 $ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                                    
//                                             }
                                            
//                                         	$trnPurchaseOrderDetaildto = array();
//                                         	$trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
//                                             $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                            
//                                             // This is the code to covert upc and dowload log
//                                             if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                    
//                                                  $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
//                                             } 
//                                             //end========= This is the code to covert upc and dowload log
                                            
//                                             $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
//                                             $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
//                                             $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
//                                             $trnPurchaseOrderDetaildto['vunitname'] = "Each";
//                                             $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $qtyor);
//                                             $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
//                                             $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
//                                             $trnPurchaseOrderDetaildto['nordtax'] = 0;
//                                             $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
//                                             $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
//                                             $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                            
//                                             $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
//                                             $trnPurchaseOrderDetaildto['vsize'] = "";
                                            
                                            
//                                             if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
                                              
//                                                 $ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
//                                             }
//                                             if($trnPurchaseOrderDetaildto['vbarcode'] == "" ){
//                                                 $log['barcode'] = $vCode;
//                                                 $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
//                                                 $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
//                                                 $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
//                                                 $log['sp'] = $trnPurchaseOrderDetaildto[''];
//                                                 $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
//                                                 $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
//                                                 $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
//                                                 $log['Vsign'] = $vSign;
                                                
                                               
//                                                 $log_present = implode(",",$log );
                                                
//                                                 $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
//                                                 $fp = fopen($file_path, 'a');
//                                                 fwrite($fp,$log_present."\n");
//                                                 fclose($fp);
                                                
//                                                 $json_return['file_download']= 1;
//                                             }
//                                             //end========= This is the code to covert upc and dowload log
                                            
//                                           // $this->model_api_receiving_order->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            
//                                         }else{ 
                                            
//                                             $mst_missingitemDTO = array();
//                                             $mst_missingitemDTO['norderqty'] = (int)$qtyor;
//                                             $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
//                                             $mst_missingitemDTO['iinvoiceid'] = $poid;
//                                             $mst_missingitemDTO['vbarcode'] = $vCode;
//                                             // This is the code to covert upc and dowload log
//                                             if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                             
//                                                 $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                                
//                                             } 
//                                             //end========= This is the code to covert upc and dowload log
//                                             $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
//                                             $mst_missingitemDTO['nsellunit'] = 1;
//                                             $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
//                                             $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                                            
//                                             $mst_missingitemDTO['vcatcode'] = $vcatcode;
//                                             $mst_missingitemDTO['vdepcode'] = $vdepcode;
//                                             $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
//                                             $mst_missingitemDTO['vtax1'] = $vtax1;
//                                             $mst_missingitemDTO['vitemtype'] = "Standard";
//                                             $mst_missingitemDTO['npack'] = (int)$npack;
//                                             $mst_missingitemDTO['vitemcode'] = $vCode;
//                                             $mst_missingitemDTO['vunitcode'] = "UNT001";
//                                             $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
//                                             $mst_missingitemDTO['vageverify'] = 0;
                                            
//                                             if($mst_missingitemDTO['vbarcode'] != "" ){
//                                                 $result = $ReceivingOrder->createMissingitem($mst_missingitemDTO);
//                                                 // 	print_r($result);
//                                             }
                                            
//                                             if($mst_missingitemDTO['vbarcode'] == "" ){
                                               
//                                                 $log['barcode'] = $vCode;
//                                                 $log['itemname'] = $mst_missingitemDTO['vitemname'];
//                                                 $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
//                                                 $log['qoh'] = $mst_missingitemDTO['nordqty'];
//                                                 $log['sp'] = $mst_missingitemDTO['dunitprice'];
//                                                 $log['upc'] = $mst_missingitemDTO['npackqty'];
//                                                 $log['total'] = $mst_missingitemDTO['nordextprice'];
//                                                 $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
//                                                 $log['Vsign'] = $vSign;
                                               
//                                                 $log_missing = implode(",",$log );
                                                
//                                                 $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
//                                                 $fp = fopen($file_path, 'a');
//                                                 fwrite($fp,$log_missing."\n");
//                                                 fclose($fp);
                                                
//                                                 $json_return['file_download']= 1;
                                                
//                                             }
//                                           // $this->model_api_receiving_order->createMissingitem($mst_missingitemDTO);
                                            
//                                         }
//                                     }else{
//                                         continue;
//                                     }
//                                 }
                                
//                         }
                        
                        
//                         }elseif($DTVENDOR['vcompanyname'] == 'CORE MARK'){
                                
//                             while (($strline = fgets($handle1)) !== false) {
//                             	$FirstChar = substr($strline,0,1);
                        	    	
//                                 if ($FirstChar == "A"){
//                                 	$orderCount = $orderCount + 1;
                                
//                                 	$vCompanyName = substr($strline, 1, 5);
//                                 	$invoicenumber = substr($strline, 7, 10);
                                    
//                                     if($vCompanyName != 'CMARK'){
//                                         $json_return['code'] = 0;
//                                         $json_return['error'] = 'Please Import Correct Vendor EDI Invoice Format';
//                                         echo json_encode($json_return);
//                                         exit;
//                                     }
                                    
//                                 	$dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->get()->toArray();
                                	
//                                 	if(count($dtCh) > 0){
//                                 		$json_return['code'] = 0;
//                         				$json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
//                         				echo json_encode($json_return);
//                         				exit;
//                                 	}
//                             		$datemonth = substr($strline, 17, 2);
//                                     $dateday = substr($strline, 19, 2);
//                                     $dateyear = substr($strline, 21, 2);
//                                     $totalAmount = substr($strline, 24, 9);
                        
//                                     if (strlen($totalAmount) > 0){
//                                     	$totalAmount = (sprintf("%.2f", $totalAmount)/100);
//                                     }
                        
//                                     $dt_year = \DateTime::createFromFormat('y', $dateyear);
//                         			$dt_year = $dt_year->format('Y');
                                    
//                                     $d = $datemonth .'-'. $dateday .'-'. $dt_year;
                        
//                                     $strdue1 = $d.' '.date('H:i:s');
                        
//                                     $strdue1 = \DateTime::createFromFormat('m-d-Y H:i:s', $strdue1);
//                         			$strdue1 = $strdue1->format('Y-m-d H:i:s');
                        
//                                     $nReturnTotal = 0;
                        
//                                     $trnPurchaseorderdto = array();
                        
//                                     $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
//                                     $trnPurchaseorderdto['nripsamt'] = 0;
//                                     $trnPurchaseorderdto['dduedatetime'] = $strdue1;
//                                     $trnPurchaseorderdto['nsubtotal'] = 0;
//                                     $trnPurchaseorderdto['nreturntotal'] = 0;
//                                     $trnPurchaseorderdto['nrectotal'] = 0;
//                                     $trnPurchaseorderdto['ndeposittotal'] = 0;
//                                     $trnPurchaseorderdto['ndiscountamt'] = 0;
//                                     $trnPurchaseorderdto['vinvoiceno'] = $invoicenumber;
//                                     $trnPurchaseorderdto['vponumber'] = $invoicenumber;
//                                     $trnPurchaseorderdto['vrefnumber'] = $invoicenumber;
//                                     $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
//                                     $trnPurchaseorderdto['ntaxtotal'] = 0;
//                                     $trnPurchaseorderdto['dcreatedate'] = $strdue1;
//                                     $trnPurchaseorderdto['estatus'] = "Open";
//                                     $trnPurchaseorderdto['nfreightcharge'] = 0;
//                                     $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
//                                     $trnPurchaseorderdto['vvendoraddress2'] = '';
//                                     $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
//                                     $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
//                                     $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
//                                     $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
//                                     $trnPurchaseorderdto['vordertitle'] = '';
//                                     $trnPurchaseorderdto['vordertype'] = "";
//                                     $trnPurchaseorderdto['vconfirmby'] = "";
//                                     $trnPurchaseorderdto['vorderby'] = "";
//                                     $trnPurchaseorderdto['vshpid'] = "0";
//                                     $trnPurchaseorderdto['vshpname'] = "";
//                                     $trnPurchaseorderdto['vshpaddress1'] = "";
//                                     $trnPurchaseorderdto['vshpaddress2'] = "";
//                                     $trnPurchaseorderdto['vshpzip'] = "";
//                                     $trnPurchaseorderdto['vshpstate'] ="";
//                                     $trnPurchaseorderdto['vshpphone'] = "";
//                                     $trnPurchaseorderdto['vshipvia'] = "";
//                                     $trnPurchaseorderdto['vnotes'] = "";
                                    
//                                     $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
//                                     $check_count = 0;
//                                 }
//                                 // $heading = implode(",",$headers );
//                                 // $fp = fopen('upc_log/Upc_conversion_log_file.csv', 'w');
//                                 // // fputcsv($fp, $headers);
//                                 // fwrite($fp,$heading."\n");
                                    
                                    
//                                 if ($FirstChar == "B"){
                                    
//                                 	$vCode = (substr($strline, 1, 11));
//                                 	$vCode = trim($vCode," ");
//                                     $vname = substr($strline, 12, 25);
//                                     $vname = str_replace("'","",$vname);                           
//                                     $unitcost = substr($strline, 43, 6);
//                                     $qtyor = substr($strline, 58, 4);
//                                     $qtyor = (int)$qtyor;
//                                     $rPrice = substr($strline, 62, 5);
//                                     $vvendoritemcode = substr($strline, 37, 6);
//                                     $npack = substr($strline, 51, 6);
//                                     $npack = (int)$npack;
//                                     $vSign = substr($strline, 57, 1);
//                                     $vvendoritemcode = substr($strline, 37, 6);
                                        	                            
                                        	                            
                                        
//                                     if (strlen($unitcost) == 0){
//                                     	$unitcost = "0";
//                                     }
                                    
//                                     if (strlen($qtyor) == 0){
//                                     	$qtyor = "0";
//                                     }
                                    
//                                     if (strlen($rPrice) == 0){
//                                     	$rPrice = "0";
//                                     }
                                    
//                                     if ($unitcost != "0"){
//                                     	$unitcost = (sprintf("%.2f", $unitcost)/100);
//                                     }
                                    
//                                     if ($rPrice != "0"){
//                                     	$rPrice = (sprintf("%.2f", $rPrice)/100);
//                                     }
                                    
//                                     if (strlen($npack) == 0 || $npack == 0){
//                                     	$npack = "1";
//                                     }
                                    
//                                     $nCost = $unitcost;
//                                     $unitcost = $unitcost / (int)$npack;
//                                     $itotalunit = (int)$qtyor * (int)$npack;
//                                     $totAmt = (int)$qtyor * $nCost;
                                    
//                                     if ($vSign == "-"){
//                                         $nReturnTotal += $totAmt;
                                        
//                                         $itotalunit = (int)$itotalunit *-1;
                                        
//                                         $qtyor = (int)$qtyor*-1;
//                                     }
                                    
                                    
//                                     $old_vCode = "";
//                                   /*Check digit and first digit*/
//                                   if(isset($input['check_digit']) && $input['check_digit'] == 'with_check_digit')
//                                   {
//                                         $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
//                                         if($check_digit_barcode != -1)
//                                         {
//                                             $old_vCode = $vCode;
//                                         	$vCode = $vCode.''.$check_digit_barcode;
//                                         }
                                        
//                                   }
//                                   if(isset($input['check_digit']) && $input['check_digit'] == 'without_check_digit')
//                                   {
//                                         $vCode = (substr($strline, 1, 11));
//                                     	  $vCode = trim($vCode," ");
//                                   }
//                                   else if(isset($input['check_digit']) && $input['check_digit'] == 'without_first_digit')
//                                   {
//                                         $vCode = (substr($strline, 2, 10));
//                                         $vCode = trim($vCode," ");
//                                   }
//                                   /*Check digit and first digit*/
                                   
//                                     //=====Limit 100 Items per invoice=========
//                                     $check_count = $check_count + 1;
                                    
//                                     if($check_count <= 100){
                                       
//                                         $dtC = $ReceivingOrder->getItemByBarCode($vCode);
                                        
//                                         if (count($dtC) == 0){
//                                           // $vCode = substr($strline, 1, 11);
//                                           // $vCode = trim($vCode," ");
                                            
//                                             $barcode_11_digit = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                             if(!empty($barcode_11_digit))
//                                             {
//                                                 if(isset($input['check_digit']) && $input['check_digit'] == 'with_check_dgigit')
//                                                 {
//                                                 	$old_vCode = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                                     if(count($old_vCode) > 0)
//                                                     {
//                                                         $ReceivingOrder->updateBarcode($vCode,$old_vCode['vbarcode']);
//                                                     }
//                                                 } 
                                                
//                                                 else if(isset($input['check_digit']) && $input['check_digit'] == 'without_first_digit')
//                                                 {
//                                                   //  $vCode = (substr($strline, 2, 10));
//                                         	       // $vCode = trim($vCode," ");
                                        	        
//                                         	        $new_vCode = trim(substr($strline, 2, 10));
                                                    
//                                                 	$_vCode = $ReceivingOrder->getItemByBarCode($new_vCode);
//                                                     if(count($_vCode) > 0)
//                                                     {
//                                                     	$old_vCode = $_vCode['vbarcode'];
//                                                     	$dtC = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                                         if(count($dtC) > 0)
//                                                         {
//                                                         	$ReceivingOrder->updateBarcode($new_vCode,$old_vCode);
//                                                         }
//                                                     }
//                                                     else
//                                                     {
//                                                     	$vCode = $new_vCode;
//                                                     }
                                                    
//                                                 } 
//                                             }
                                            
//                                         }
                                        
//                                         if(count($dtC) > 0){
//                                         	//update item status
//                                         	// if($dtC['estatus'] == 'Inactive'){
//                                         	// 	$msg_inactive_item .= 'Item Barcode: '.$dtC['vbarcode'].PHP_EOL;
//                                         	// }
//                                         	$ReceivingOrder->updateItemStatus($dtC["iitemid"]);
//                                         	//update item status
                            
//                                         	$iitemid = $dtC["iitemid"];
//                                         	$dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
//                                         	if(count($dtI) == 0){
//                                         		$mstItemVendorDto = array();
//                                         		$mstItemVendorDto['iitemid'] = $iitemid;
//                                         		$mstItemVendorDto['ivendorid'] = $input['vvendorid'];
//                                         		$mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                            
//                                         		$ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                        		
                                        		
                                        		
//                                         	}
                                            
//                                         	$trnPurchaseOrderDetaildto = array();
//                                         	$trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
//                                             $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                            
//                                             // This is the code to covert upc and dowload log
//                                             if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                    
//                                                  $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
//                                             } 
//                                             //end========= This is the code to covert upc and dowload log
                                            
//                                             $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
//                                             $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
//                                             $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
//                                             $trnPurchaseOrderDetaildto['vunitname'] = "Each";
//                                             $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $qtyor);
//                                             $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
//                                             $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
//                                             $trnPurchaseOrderDetaildto['nordtax'] = 0;
//                                             $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
//                                             $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
//                                             $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                            
//                                             $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
//                                             $trnPurchaseOrderDetaildto['vsize'] = "";
                                            
//                                             if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
//                                               // echo strlen($trnPurchaseOrderDetaildto['vbarcode']);exit();
//                                                 $ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
//                                             }
//                                             if($trnPurchaseOrderDetaildto['vbarcode'] == "" ){
//                                                 $log['barcode'] = $vCode;
//                                                 $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
//                                                 $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
//                                                 $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
//                                                 $log['sp'] = $trnPurchaseOrderDetaildto[''];
//                                                 $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
//                                                 $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
//                                                 $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
//                                                 $log['Vsign'] = $vSign;
                                                
                                               
//                                                 $log_present = implode(",",$log );
                                                
//                                                 $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
//                                                 $fp = fopen($file_path, 'a');
//                                                 fwrite($fp,$log_present."\n");
//                                                 fclose($fp);
                                                
//                                                 $json_return['file_download']= 1;
//                                         	}
//                                         	//end========= This is the code to covert upc and dowload log
                                        
                                           
//                                         }else{
                            
//                                         	$mst_missingitemDTO = array();
//                                         	$mst_missingitemDTO['norderqty'] = (int)$qtyor;
//                                             $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
//                                             $mst_missingitemDTO['iinvoiceid'] = $poid;
//                                             $mst_missingitemDTO['vbarcode'] = $vCode;
//                                             // This is the code to covert upc and dowload log
//                                             if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                                    
//                                                  $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
//                                             } 
//                                             //end========= This is the code to covert upc and dowload log
//                                             $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
//                                             $mst_missingitemDTO['nsellunit'] = 1;
//                                             $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
//                                             $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                            
//                                             $mst_missingitemDTO['vcatcode'] = $vcatcode;
//                                             $mst_missingitemDTO['vdepcode'] = $vdepcode;
//                                             $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
//                                             $mst_missingitemDTO['vtax1'] = $vtax1;
//                                             $mst_missingitemDTO['vitemtype'] = "Standard";
//                                             $mst_missingitemDTO['npack'] = (int)$npack;
//                                             $mst_missingitemDTO['vitemcode'] = $vCode;
//                                             $mst_missingitemDTO['vunitcode'] = "UNT001";
//                                             $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
                            
//                                             if($mst_missingitemDTO['vbarcode'] != "" ){
//                                                 $ReceivingOrder->createMissingitem($mst_missingitemDTO);
//                                             }
//                                             if($mst_missingitemDTO['vbarcode'] == "" ){
                                                
//                                                 $log['barcode'] = $vCode;
//                                                 $log['itemname'] = $mst_missingitemDTO['vitemname'];
//                                                 $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
//                                                 $log['qoh'] = $mst_missingitemDTO['nordqty'];
//                                                 $log['sp'] = $mst_missingitemDTO['dunitprice'];
//                                                 $log['upc'] = $mst_missingitemDTO['npackqty'];
//                                                 $log['total'] = $mst_missingitemDTO['nordextprice'];
//                                                 $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
//                                                 $log['Vsign'] = $vSign;
                                               
//                                                 $log_missing = implode(",",$log );
                                                
//                                                 $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
//                                                 $fp = fopen($file_path, 'a');
//                                                 fwrite($fp,$log_missing."\n");
//                                                 fclose($fp);
                                                
//                                                 $json_return['file_download']= 1;
                                                
                                                
                                                
//                                             }
//                                           // $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                        	
//                                         }
//                                     }
//                                 }
                                
//                             }
//                         }else{
                            
//                             while (($strline = fgets($handle1)) !== false) {
//                                 $FirstChar = substr($strline,0,1);
                                
//                                 if ($FirstChar == "A"){
//                                     $orderCount = $orderCount + 1;
                                    
//                                     $vCompanyName = substr($strline, 1, 6);
//                                     $invoicenumber = substr($strline, 7, 10);
                                    
//                                     if($vCompanyName == 'CMARK' || $vCompanyName == 'ALLENB'){
//                                         $json_return['code'] = 0; 
//                                         $json_return['error'] = 'Please Import Correct Vendor EDI Invoice Format';
//                                         echo json_encode($json_return);
//                                         exit;
//                                     }
                                    
//                                     $dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->get()->toArray();
                                    
//                                     if(count($dtCh) > 0){
//                                         $json_return['code'] = 0;
//                                         $json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
//                                         echo json_encode($json_return);
//                                         exit;
//                                     }
//                                     $datemonth = substr($strline, 17, 2);
//                                     $dateday = substr($strline, 19, 2);
//                                     $dateyear = substr($strline, 21, 2);
//                                     $totalAmount = substr($strline, 24, 9);
                                    
//                                     if (strlen($totalAmount) > 0){
//                                     	$totalAmount = (sprintf("%.2f", $totalAmount)/100);
//                                     }
                                    
//                                     $dt_year = \DateTime::createFromFormat('y', $dateyear);
//                                     $dt_year = $dt_year->format('Y');
                                    
//                                     $d = $datemonth .'-'. $dateday .'-'. $dt_year;
                                    
//                                     $strdue1 = $d.' '.date('H:i:s');
                                    
//                                     $strdue1 = \DateTime::createFromFormat('m-d-Y H:i:s', $strdue1);
//                                     $strdue1 = $strdue1->format('Y-m-d H:i:s');
                                    
//                                     $nReturnTotal = 0;
                                    
//                                     $trnPurchaseorderdto = array();
                                    
//                                     $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
//                                     $trnPurchaseorderdto['nripsamt'] = 0;
//                                     $trnPurchaseorderdto['dduedatetime'] = $strdue1;
//                                     $trnPurchaseorderdto['nsubtotal'] = 0;
//                                     $trnPurchaseorderdto['nreturntotal'] = 0;
//                                     $trnPurchaseorderdto['nrectotal'] = 0;
//                                     $trnPurchaseorderdto['ndeposittotal'] = 0;
//                                     $trnPurchaseorderdto['ndiscountamt'] = 0;
//                                     $trnPurchaseorderdto['vinvoiceno'] = trim($invoicenumber);
//                                     $trnPurchaseorderdto['vponumber'] = trim($invoicenumber);
//                                     $trnPurchaseorderdto['vrefnumber'] = trim($invoicenumber);
//                                     $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
//                                     $trnPurchaseorderdto['ntaxtotal'] = 0;
//                                     $trnPurchaseorderdto['dcreatedate'] = $strdue1;
//                                     $trnPurchaseorderdto['estatus'] = "Open";
//                                     $trnPurchaseorderdto['nfreightcharge'] = 0;
//                                     $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
//                                     $trnPurchaseorderdto['vvendoraddress2'] = '';
//                                     $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
//                                     $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
//                                     $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
//                                     $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
//                                     $trnPurchaseorderdto['vordertitle'] = '';
//                                     $trnPurchaseorderdto['vordertype'] = "";
//                                     $trnPurchaseorderdto['vconfirmby'] = "";
//                                     $trnPurchaseorderdto['vorderby'] = "";
//                                     $trnPurchaseorderdto['vshpid'] = "0";
//                                     $trnPurchaseorderdto['vshpname'] = "";
//                                     $trnPurchaseorderdto['vshpaddress1'] = "";
//                                     $trnPurchaseorderdto['vshpaddress2'] = "";
//                                     $trnPurchaseorderdto['vshpzip'] = "";
//                                     $trnPurchaseorderdto['vshpstate'] ="";
//                                     $trnPurchaseorderdto['vshpphone'] = "";
//                                     $trnPurchaseorderdto['vshipvia'] = "";
//                                     $trnPurchaseorderdto['vnotes'] = "";
                                    
//                                     $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
//                                     $check_count = 0;
//                                 }
                                
//                                 if ($FirstChar == "B"){
                                    
//                                     $vCode = (substr($strline, 1, 11));
//                                         $vCode = trim($vCode," ");
//                                         $vname = substr($strline, 12, 25);
//                                         $vname = str_replace("'","",$vname);                           
//                                         $unitcost = substr($strline, 43, 6);
//                                         $qtyor = substr($strline, 58, 4);
//                                         $qtyor = (int)$qtyor;
//                                         $rPrice = substr($strline, 62, 5);
//                                         $vvendoritemcode = substr($strline, 37, 6);
//                                         $npack = substr($strline, 51, 6);
//                                         $npack = (int)$npack;
//                                         $vSign = substr($strline, 57, 1);
//                                         $vvendoritemcode = substr($strline, 37, 6);
                                        
                                        
                                        
//                                         if (strlen($unitcost) == 0){
//                                         $unitcost = "0";
//                                         }
                                        
//                                         if (strlen($qtyor) == 0){
//                                         $qtyor = "0";
//                                         }
                                        
//                                         if (strlen($rPrice) == 0){
//                                         $rPrice = "0";
//                                         }
                                        
//                                         if ($unitcost != "0"){
//                                         $unitcost = (sprintf("%.2f", $unitcost)/100);
//                                         }
                                        
//                                         if ($rPrice != "0"){
//                                         $rPrice = (sprintf("%.2f", $rPrice)/100);
//                                         }
                                        
//                                         if (strlen($npack) == 0 || $npack == 0){
//                                         $npack = "1";
//                                         }
                                        
//                                         $nCost = $unitcost;
//                                         $unitcost = $unitcost / (int)$npack;
//                                         $itotalunit = (int)$qtyor * (int)$npack;
//                                         $totAmt = (int)$qtyor * $nCost;
                                        
//                                         if ($vSign == "-"){
//                                         $nReturnTotal += $totAmt;
                                        
//                                         $itotalunit = (int)$itotalunit *-1;
                                        
//                                         $qtyor = (int)$qtyor*-1;
//                                         }
                                        
                                        
//                                         $old_vCode = "";
//                                         /*Check digit and first digit*/
//                                         if(isset($input['check_digit']) && $input['check_digit'] == 'with_check_digit')
//                                         {
//                                         $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
//                                         if($check_digit_barcode != -1)
//                                         {
//                                             $old_vCode = $vCode;
//                                         	$vCode = $vCode.''.$check_digit_barcode;
//                                         }
//                                         // print_r($vCode);
//                                         }
//                                         if(isset($input['check_digit']) && $input['check_digit'] == 'without_check_digit')
//                                         {
//                                         $vCode = (substr($strline, 1, 11));
//                                           $vCode = trim($vCode," ");
//                                         }
//                                         else if(isset($input['check_digit']) && $input['check_digit'] == 'without_first_digit')
//                                         {
//                                         $vCode = (substr($strline, 2, 10));
//                                           $vCode = trim($vCode," ");
//                                         }
//                                         /*Check digit and first digit*/
                                        
//                                         //=====Limit 100 Items per invoice=========
//                                         $check_count = $check_count + 1;
                                                
//                                         if($check_count <= 100){
//                                             $dtC = $ReceivingOrder->getItemByBarCode($vCode);
                                            
//                                             if (count($dtC) == 0){
//                                             // $vCode = substr($strline, 1, 11);
//                                             // $vCode = trim($vCode," ");
                                            
//                                             $barcode_11_digit = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                             if(!empty($barcode_11_digit))
//                                             {
//                                                 if(isset($input['check_digit']) && $input['check_digit'] == 'with_check_dgigit')
//                                                 {
//                                                 	$old_vCode = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                                     if(count($old_vCode) > 0)
//                                                     {
//                                                         $ReceivingOrder->updateBarcode($vCode,$old_vCode['vbarcode']);
//                                                     }
//                                                 } 
                                                
//                                                 else if(isset($input['check_digit']) && $input['check_digit'] == 'without_first_digit')
//                                                 {
//                                                   //  $vCode = (substr($strline, 2, 10));
//                                                   // $vCode = trim($vCode," ");
                                                    
//                                                     $new_vCode = trim(substr($strline, 2, 10));
                                                    
//                                                 	$_vCode = $ReceivingOrder->getItemByBarCode($new_vCode);
//                                                     if(count($_vCode) > 0)
//                                                     {
//                                                     	$old_vCode = $_vCode['vbarcode'];
//                                                     	$dtC = $ReceivingOrder->getItemByBarCode($old_vCode);
//                                                         if(count($dtC) > 0)
//                                                         {
//                                                         	$ReceivingOrder->updateBarcode($new_vCode,$old_vCode);
//                                                         }
//                                                     }
//                                                     else
//                                                     {
//                                                     	$vCode = $new_vCode;
//                                                     }
                                                    
//                                                 } 
//                                             }
                                            
//                                             }
                                            
//                                             if(count($dtC) > 0){
                                            
//                                                 $ReceivingOrder->updateItemStatus($dtC["iitemid"]);
//                                                 //update item status
                                                
//                                                 $iitemid = $dtC["iitemid"];
//                                                 $dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
//                                                 if(count($dtI) == 0){
//                                                 	$mstItemVendorDto = array();
//                                                 	$mstItemVendorDto['iitemid'] = $iitemid;
//                                                 	$mstItemVendorDto['ivendorid'] = $input['vvendorid'];
//                                                 	$mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                                                
//                                                 	$ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                                	
                                                	
                                                	
//                                                 }
                                                
//                                                 $trnPurchaseOrderDetaildto = array();
//                                                 $trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
//                                                 $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                                
//                                                 // This is the code to covert upc and dowload log
//                                                 if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                                
//                                                      $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                                 
//                                                 } 
//                                                 //end========= This is the code to covert upc and dowload log
                                                
//                                                 $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
//                                                 $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
//                                                 $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
//                                                 $trnPurchaseOrderDetaildto['vunitname'] = "Each";
//                                                 $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $qtyor);
//                                                 $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
//                                                 $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
//                                                 $trnPurchaseOrderDetaildto['nordtax'] = 0;
//                                                 $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
//                                                 $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
//                                                 $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                                                
//                                                 $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
//                                                 $trnPurchaseOrderDetaildto['vsize'] = "";
                                                
                                                
//                                                 if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
//                                                   // echo strlen($trnPurchaseOrderDetaildto['vbarcode']);exit();
//                                                 	$ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
//                                                 }
//                                                 if($trnPurchaseOrderDetaildto['vbarcode'] == "" ){
//                                                     $log['barcode'] = $vCode;
//                                                     $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
//                                                     $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
//                                                     $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
//                                                     // $log['sp'] = $trnPurchaseOrderDetaildto[''];
//                                                     $log['sp'] = '';
//                                                     $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
//                                                     $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
//                                                     $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
//                                                     $log['Vsign'] = $vSign;
                                                    
                                                    
//                                                     $log_present = implode(",",$log );
                                                    
//                                                     $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                    
//                                                     $fp = fopen($file_path, 'a');
//                                                     fwrite($fp,$log_present."\n");
//                                                     fclose($fp);
                                                    
//                                                     // exit();
                                                    
//                                                     //  $json_return['file_name'] = $file_name;
//                                                     $json_return['file_download']= 1;
//                                                 }
//                                                 //end========= This is the code to covert upc and dowload log
                                                
                                                
//                                             }else{
                                            
//                                             $mst_missingitemDTO = array();
//                                             $mst_missingitemDTO['norderqty'] = (int)$qtyor;
//                                             $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
//                                             $mst_missingitemDTO['iinvoiceid'] = $poid;
//                                             $mst_missingitemDTO['vbarcode'] = $vCode;
//                                             // This is the code to covert upc and dowload log
//                                             if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            
//                                                  $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
//                                             } 
//                                             //end========= This is the code to covert upc and dowload log
//                                             $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
//                                             $mst_missingitemDTO['nsellunit'] = 1;
//                                             $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
//                                             $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                                            
//                                             $mst_missingitemDTO['vcatcode'] = $vcatcode;
//                                             $mst_missingitemDTO['vdepcode'] = $vdepcode;
//                                             $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
//                                             $mst_missingitemDTO['vtax1'] = $vtax1;
//                                             $mst_missingitemDTO['vitemtype'] = "Standard";
//                                             $mst_missingitemDTO['npack'] = (int)$npack;
//                                             $mst_missingitemDTO['vitemcode'] = $vCode;
//                                             $mst_missingitemDTO['vunitcode'] = "UNT001";
//                                             $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
                                            
//                                             if($mst_missingitemDTO['vbarcode'] != "" ){
//                                             	$ReceivingOrder->createMissingitem($mst_missingitemDTO);
//                                             }
//                                             if($mst_missingitemDTO['vbarcode'] == "" ){
                                                
//                                                 $log['barcode'] = $vCode;
//                                                 $log['itemname'] = $mst_missingitemDTO['vitemname'];
//                                                 $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
//                                                 $log['qoh'] = $mst_missingitemDTO['nordqty'];
//                                                 $log['sp'] = $mst_missingitemDTO['dunitprice'];
//                                                 $log['upc'] = $mst_missingitemDTO['npackqty'];
//                                                 $log['total'] = $mst_missingitemDTO['nordextprice'];
//                                                 $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
//                                                 $log['Vsign'] = $vSign;
                                               
//                                                 $log_missing = implode(",",$log );
                                                
//                                                 $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
//                                                 $fp = fopen($file_path, 'a');
//                                                 fwrite($fp,$log_missing."\n");
//                                                 fclose($fp);
                                                
//                                                 $json_return['file_download']= 1;
                                                
                                                
                                                
//                                             }
//                                             // $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                        	
//                                         }
//                                         }
//                                 }
                                
//                             }
//                         }
//                         $ReceivingOrder->updatePurchaseOrderReturnTotal($nReturnTotal,$poid);
                        
                        
// 					    $json_return['code'] = 1;
// 						$json_return['success'] = 'Successfully Imported Invoice!';
// 						echo json_encode($json_return);
// 						exit;
						
// 					}else{
// 						$json_return['code'] = 0;
// 						$json_return['error'] = 'file not found';
// 						echo json_encode($json_return);
// 						exit;
// 					}
// 				}else{
// 					$json_return['code'] = 0;
// 					$json_return['error'] = 'Please select file';
// 					echo json_encode($json_return);
// 					exit;
// 				}
// 			}else{
// 				$json_return['code'] = 0;
// 				$json_return['error'] = 'Please select vendor';
// 				echo json_encode($json_return);
// 				exit;
// 			}
// 		}
		
// 	}


	public function import_invoice_new(Request $request) 
	{
		
		$data = array();
		$json_return = array();
		$log_array = array();
        
        $input = $request->all();
        
        // dd($input);
        
		if ($request->isMethod('post')) {
			if(isset($input['vvendorid'])){
				$data['vvendorid'] = $input['vvendorid'];
                
				if(isset($input['import_invoice_file']) && $request->hasFile('import_invoice_file')&& $request->file('import_invoice_file')->getClientOriginalName() != ''){
                    
					//variables
					$orderCount = 0;
					$poid = 0;
					$vCompanyName = '';
					$invoicenumber = '';
					$datemonth = '';
					$dateday = '';
					$dateyear = '';
					$totalAmount = '';
					$d = '';
					$vCode = '';
					$vname = '';
					$unitcost = '';
                    $qtyor = '';
                    $rPrice = '';
                    $iitemid = '';
                    $vcatcode = "3";
                    $vdepcode = "3";
                    $vtax1 = "N";
                    $vvendoritemcode = '';
                    $npack = '';
                    $itotalunit = '';
                    $nCost = '';
                    $vSign = '';
                    
                    $ReceivingOrder = new ReceivingOrder;
                    
                    $checkcreateCategory = $ReceivingOrder->checkcreateCategory();
                    $checkcreateDepartment = $ReceivingOrder->checkcreateDepartment();
                    
                    // $vcategory = $this->model_api_receiving_order->getCategories();
                    // if(count($vcategory) > 0){
                    //     $vcatcode = $vcategory[0]['vcategorycode'];
                    // }
                    
                    // $vdepartment = $this->model_api_receiving_order->getDepartments();
                    // if(count($vdepartment) > 0){
                    //     $vdepcode = $vdepartment[0]['vdepcode'];
                    // }
                    
                    $dtVend = StoreSettings::where([
                                                    ['vsettingname', '=', 'Tax1seletedfornewItem'],
                                                    ['vsettingvalue', '=', 'Yes']
                                                    ])->get()->toArray();
                    if(count($dtVend) > 0){
                        $vtax1 = "Y";
                    }
                    
                    // $DTVENDOR = Supplier::where('isupplierid', $input['vvendorid'])->get()->toArray();
                    $vendor_collection = Supplier::where('isupplierid', $input['vvendorid'])->first();
                    
                    // $DTVENDOR = isset($DTVENDOR[0])?$DTVENDOR[0]:[];
                    $DTVENDOR = $vendor_collection === null?[]:$vendor_collection->toArray();
                    
                    // $edi_predefined_vvendorid = Supplier::where('isupplierid', $input['edi_predefined_vvendorid'])->first();
                    // $edi_predefined_vvendorid = $edi_predefined_vvendorid === null?[]:$edi_predefined_vvendorid->toArray();
					
					$nReturnTotal = 0;
					$msg_inactive_item = '';
                    
					$import_invoice_file = $request->file('import_invoice_file')->getPathName();
					$handle1 = fopen($import_invoice_file, "r");
					
                    
                    $headers = ["SKU", "Item Name", "Vendor Item Code","QOH", "Selling Price ", "Unit per case", "Total amount", "Unit Cost ", "vSign"];
                    
                    $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                    
                    $fp = fopen($file_path, 'w');
                    fputcsv($fp, $headers);
                    
                    fclose($fp);
                    
                    $check_count;
                    $error_import_barcode = array();
					
					if ($handle1) {
					    
					    //=======We don't have exact id of particular vendor so we use NAME for condition(if)========
					    if($DTVENDOR['vendor_format'] == 'FEDWAY' || $DTVENDOR['vendor_format'] == 'fedway'){
                            
                            while (($strline = fgets($handle1)) !== false) {
                                
                                if(substr($strline,0,1) === 'B'){
                                    $rod_array = explode('/', $strline);
                                    $vCode = trim(substr($rod_array[0], 1, (strlen($rod_array[0])-1)));
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A' && strlen($vCode) > 8){
                                        
                                        $trn_receivingorderdetail = DB::connection('mysql_dynamic')->select("SELECT irodetid FROM trn_receivingorderdetail WHERE iroid = '" . ($poid) . "'");
                                        $trn_receivingorderdetail = array_map(function ($value) {
                                            return (array)$value;
                                        }, $trn_receivingorderdetail);
                                        
                                        if(count($trn_receivingorderdetail)){
                                            foreach ($trn_receivingorderdetail as $k => $v) {
                                                
                                                DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorderdetail WHERE irodetid='" . (int)$v['irodetid'] . "'");
                                            }
                                        }
                                        
                                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorder WHERE iroid='" . (int)$poid . "'");
                                        
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Incorrect Barcode Formate for convert UPC-E to UPC-A';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                }
                                
                                if (strpos($strline, 'FEDWAY') !== false) {
                                    
                                    $ro_array = explode('/', $strline);
                                    
                                    // Structure of the array: 
                                    // 0 => Vendor Name, 1 => Invoice Number, 2 => Invoice Date, 3 => Invoice total
                                    
                                    $orderCount = $orderCount + 1;
                                    $vCompanyName = trim($ro_array[0]);
                                    $invoicenumber = trim($ro_array[1]);
                                    
                                    if($vCompanyName != 'FEDWAY'){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'The EDI Uploaded does not follow the format of FEDWAY';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    
                                    $dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->first();
                                    	
                                    if($dtCh !== null){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    
                                    $date = \DateTime::createFromFormat('mdy', trim($ro_array[2]));
                                    $strdue1 = $date->format('Y-m-d H:i:s');
                                    
                                    $totalAmount = trim($ro_array[3]);
                                    
                                    if (strlen($totalAmount) > 0){
                                        // $totalAmount = (sprintf("%.2f", $totalAmount)/100);
                                        $totalAmount = number_format($totalAmount, 2, '.', '');
                                    }
                                    
                                    $nReturnTotal = 0;
                                    
                                    $nReturnTotal = 0;
                                    
                                    $trnPurchaseorderdto = array();
                                    
                                    $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
                                    $trnPurchaseorderdto['nripsamt'] = 0;
                                    $trnPurchaseorderdto['dduedatetime'] = $strdue1;
                                    $trnPurchaseorderdto['nsubtotal'] = 0;
                                    $trnPurchaseorderdto['nreturntotal'] = 0;
                                    $trnPurchaseorderdto['nrectotal'] = 0;
                                    $trnPurchaseorderdto['ndeposittotal'] = 0;
                                    $trnPurchaseorderdto['ndiscountamt'] = 0;
                                    $trnPurchaseorderdto['vinvoiceno'] = $invoicenumber;
                                    $trnPurchaseorderdto['vponumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['vrefnumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
                                    $trnPurchaseorderdto['ntaxtotal'] = 0;
                                    $trnPurchaseorderdto['dcreatedate'] = $strdue1;
                                    $trnPurchaseorderdto['estatus'] = "Open";
                                    $trnPurchaseorderdto['nfreightcharge'] = 0;
                                    $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
                                    $trnPurchaseorderdto['vvendoraddress2'] = '';
                                    $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
                                    $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
                                    $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
                                    $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
                                    $trnPurchaseorderdto['vordertitle'] = '';
                                    $trnPurchaseorderdto['vordertype'] = "";
                                    $trnPurchaseorderdto['vconfirmby'] = "";
                                    $trnPurchaseorderdto['vorderby'] = "";
                                    $trnPurchaseorderdto['vshpid'] = "0";
                                    $trnPurchaseorderdto['vshpname'] = "";
                                    $trnPurchaseorderdto['vshpaddress1'] = "";
                                    $trnPurchaseorderdto['vshpaddress2'] = "";
                                    $trnPurchaseorderdto['vshpzip'] = "";
                                    $trnPurchaseorderdto['vshpstate'] ="";
                                    $trnPurchaseorderdto['vshpphone'] = "";
                                    $trnPurchaseorderdto['vshipvia'] = "";
                                    $trnPurchaseorderdto['vnotes'] = "";
                                    
                                    // dd($trnPurchaseorderdto);
                                    
                                    $ReceivingOrder = new ReceivingOrder;
                                    $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
                                    $check_count_inserted_items = 0;
                                    
                                } elseif(substr($strline,0,1) === 'B'){
                                    
                                    // restrict inserting items to 500
                                    if($check_count_inserted_items > 500){
                                        continue;
                                    }
                                    
                                    $check_count_inserted_items++;
                                    $rod_array = explode('/', $strline);
                                    // print_r($rod_array);
                                    
                                    // B088110150631/HENNESSY VS ROUND/90570/2748.96/12/0/ 12
                                    /*upc 		---->088110150631
                                    name 		----> HENNESSY VS ROUND
                                    vendor code	---->90570
                                    total cost --->2748.96
                                    unit per case -->12
                                    total unit ----->0
                                    total case order--->12*12 =144
                                    cost --->2748.96/144*/
                                    
                                    // 0 => upc, 1 => Item Name, 2 => Vendor Code, 3 => Total Cost, 4 => Unit Per Case,
                                    // 5 => total unit, 6 => total case order, 7 => unit cost ([3] / [6])
                                    
                                    // $vCode = (substr($strline, 1, 11));
                                //     $vCode = trim($vCode," ");
                                //     $vname = substr($strline, 13, 25);
                                //     $vname = str_replace("'","",$vname);                  
                                //     $unitcost = substr($strline, 44, 6);
                                //     $qtyor = substr($strline, 59, 4);
                                //     $qtyor = (int)$qtyor;
                                //     $rPrice = substr($strline, 63, 6);
                                //     $vvendoritemcode = substr($strline, 38, 6);
                                //     $npack = substr($strline, 52, 6);
                                //     $npack = (int)$npack;
                                //     $vSign = substr($strline, 57, 1);
                                
                                        
                                    //   Changed on 30 Nov 2020 
                                    // 0 => upc, 1 => Item Name, 2 => Vendor Code, 3 => Total Cost, 4 => total case order,
                                    // 5 => total unit, 6 => Unit Per Case, 7 => unit cost ([3] / [6])    
                                        
                                        $vCode = trim(substr($rod_array[0], 1, (strlen($rod_array[0])-1)));
                                        $vname = str_replace("'","",$rod_array[1]);
                                        
                                        
                                        $vvendoritemcode = trim($rod_array[2]);
                                        $npack = (int) trim($rod_array[6]);
                                        
                                        $totAmt = trim($rod_array[3]);
                                        
                                        $units = (int)trim($rod_array[5]);
                                        $cases = (int)trim($rod_array[4]);
                                        $units_in_case = $cases * $npack;
                                        
                                        $itotalunit = $units + $units_in_case;
                                        
                                        $unitcost = $itotalunit === 0?0.00:($totAmt/$itotalunit);
                                
                                        // Assuming there will be no returns
                                        //     if ($vSign == "-"){
                                //         $nReturnTotal += $totAmt;
                                        
                                //         $itotalunit = (int)$itotalunit *-1;
                                        
                                //         $qtyor = (int)$qtyor*-1;
                                //     }
                                        
                                        $old_vCode = "";
                                        
                                        //===if upc_convert = 'E', it means need convert UPC-A to UPC-E===
                                        if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'E'){
                                            
                                            $check_vCode = $vCode;
                                            $vCode = $this->convert_upca_to_upce($vCode);
                                            
                                            if($vCode == false && trim($check_vCode) != ''){
                                                $error_import_barcode[] = array('error_barcode' => $check_vCode, 'invoice' => $invoicenumber);
                                            }
                                            
                                            if($DTVENDOR['check_digit'] == 'Y')
                                            {
                                                $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                                if($check_digit_barcode != -1)
                                                {
                                                    $old_vCode = $vCode; 
                                                    $vCode = $vCode.''.$check_digit_barcode;
                                                }
                                                
                                            }
                                            if($DTVENDOR['remove_first_digit'] == 'Y')
                                            {
                                                // if(strlen($vCode) === 8 && $DTVENDOR['check_digit'] != 'Y'){
                                                //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                                // }else{
                                                //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                                // }
                                                
                                                $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                                
                                            }
                                            
                                            if($DTVENDOR['remove_last_digit'] == 'Y')
                                            {
                                                $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                                $vCode = trim($vCode," ");
                                            }
                                            
                                            
                                        }
                                        
                                        if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A'){
                                            
                                            $vCode = $this->convert_upce_to_upca($vCode);
                                            
                                            // Adding check digit
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                            if( $DTVENDOR['remove_first_digit'] == 'Y')
                                            {
                                                $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                                $vCode = trim($vCode," ");
                                            }
                                            if( $DTVENDOR['remove_last_digit'] == 'Y')
                                            {
                                                $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                                $vCode = trim($vCode," ");
                                            }
                                            
                                        }
                                        
                                        if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] != 'E' && $DTVENDOR['upc_convert'] != 'A'){
                                            
                                            if($DTVENDOR['check_digit'] == 'Y')
                                            {
                                                $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                                if($check_digit_barcode != -1)
                                                {
                                                    $old_vCode = $vCode; 
                                                    $vCode = $vCode.''.$check_digit_barcode;
                                                }
                                                
                                            }
                                            if($DTVENDOR['remove_first_digit'] == 'Y')
                                            {
                                                // if(strlen($vCode) === 12 && $DTVENDOR['check_digit'] != 'Y'){
                                                //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                                // }else{
                                                //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                                // }
                                                $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                                $vCode = trim($vCode," ");
                                            }
                                            
                                            if($DTVENDOR['remove_last_digit'] == 'Y')
                                            {
                                                $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                                $vCode = trim($vCode," ");
                                            }
                                            
                                            
                                        }
                                        
                                        $ReceivingOrder = new ReceivingOrder;
                                        $dtC = $ReceivingOrder->getItemByBarCode($vCode);
                                        
                                        if(isset($dtC[0]) && $dtC[0] == 'aliascodeAvilable'){
                                            $log['barcode'] = $vCode;
                                            $log['itemname'] = str_replace("'","",$vname);
                                            $log['vendoritemcode'] = (string)$vvendoritemcode;
                                            $log['qoh'] = sprintf("%.2f", $qtyor);
                                            $log['sp'] = '';
                                            $log['upc'] = (int)$npack;
                                            $log['total'] = $totAmt;
                                            $log['unitcost'] = sprintf("%.4f", $unitcost);
                                            $log['Vsign'] = $vSign;
                                            
                                            $log_present = implode(",",$log );
                                            
                                            $sid = session()->get('sid');
                                            $file_path = storage_path("upc_log/edi_import_log_file.csv".$sid.".csv");
                                            
                                            $fp = fopen($file_path, 'a');
                                            fwrite($fp,$log_present."\n");
                                            fclose($fp);
                                            
                                            $json_return['file_download']= 1;
                                        }elseif(count($dtC) > 0){
                                         	//update item status to Active if Inactive
                                            if($dtC['estatus'] == 'Inactive'){
                                                
                                                $ReceivingOrder->updateItemStatus($dtC["iitemid"]);
                                                // 	$msg_inactive_item .= 'Item Barcode: '.$dtC['vbarcode'].PHP_EOL;
                                            }
                                            
                                            $iitemid = $dtC["iitemid"];
                                            // 	$dtI = $this->model_api_receiving_order->getItemVendorByVendorItemCode($vvendoritemcode);
                                            $dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
                                            if(count($dtI) == 0){
                                                $mstItemVendorDto = array();
                                                $mstItemVendorDto['iitemid'] = $iitemid;
                                                $mstItemVendorDto['ivendorid'] = $input['vvendorid'];
                                                $mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                                                
                                                $ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                                    
                                            }
                                            
                                        	$trnPurchaseOrderDetaildto = array();
                                        	$trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
                                            $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                            
                                            // Convert barcode from UPC A to UPC E
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                                
                                            //      $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
                                            // } 

                                            
                                            $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
                                            $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
                                            $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
                                            $trnPurchaseOrderDetaildto['vunitname'] = "Each";
                                            $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $cases);
                                            // $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
                                            $trnPurchaseOrderDetaildto['nordunitprice'] = '0.00';
                                            $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
                                            $trnPurchaseOrderDetaildto['nordtax'] = 0;
                                            $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
                                            $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
                                            $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                            
                                            $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
                                            $trnPurchaseOrderDetaildto['vsize'] = "";
                                            
                                            
                                            if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
                                              
                                                $ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            } else {
                                                
                                                // Prepare log fie
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
                                                $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
                                                $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
                                                $log['sp'] = $trnPurchaseOrderDetaildto[''];
                                                $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
                                                $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
                                                $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
                                                // $log['Vsign'] = $vSign;
                                                
                                               
                                                $log_present = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_present."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                            }
                                                //end========= This is the code to covert upc and dowload log
                                                
                                                // $this->model_api_receiving_order->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            
                                        }else{ 
                                            
                                            $mst_missingitemDTO = array();
                                            // $mst_missingitemDTO['norderqty'] = (int)$qtyor;
                                            $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
                                            $mst_missingitemDTO['iinvoiceid'] = $poid;
                                            $mst_missingitemDTO['vbarcode'] = $vCode;
                                            
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                             
                                            //     $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                                
                                            // } 
                                            
                                            //end========= This is the code to covert upc and dowload log
                                            $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
                                            $mst_missingitemDTO['nsellunit'] = 1;
                                            // $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
                                            // $mst_missingitemDTO['dcostprice'] = $unitcost*$npack;
                                            
                                            $mst_missingitemDTO['dcostprice'] = $unitcost*$mst_missingitemDTO['nsellunit'];
                                            $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                                            $mst_missingitemDTO['nordextprice'] = $totAmt;
                                            $mst_missingitemDTO['vcatcode'] = $vcatcode;
                                            $mst_missingitemDTO['vdepcode'] = $vdepcode;
                                            $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
                                            $mst_missingitemDTO['vtax1'] = $vtax1;
                                            $mst_missingitemDTO['vitemtype'] = "Standard";
                                            $mst_missingitemDTO['npack'] = (int)$npack;
                                            $mst_missingitemDTO['vitemcode'] = $vCode;
                                            $mst_missingitemDTO['vunitcode'] = "UNT001";
                                            $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
                                            
                                            $mst_missingitemDTO['norderqty'] = $itotalunit;
                                            // $mst_missingitemDTO['itotalunit'] = $itotalunit;
                                            
                                            $mst_missingitemDTO['total_cost'] = $totAmt;
                                            
                                            $mst_missingitemDTO['vageverify'] = 0;
                                            
                                            // Insert into missing item if the vbarcode is not empty
                                            if($mst_missingitemDTO['vbarcode'] != "" ){
                                                $result = $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                                // 	print_r($result);
                                            }
                                            else {   
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $mst_missingitemDTO['vitemname'];
                                                $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
                                                $log['qoh'] = $mst_missingitemDTO['norderqty'];
                                                $log['sp'] = $mst_missingitemDTO['dunitprice'];
                                                $log['npackqty'] = $mst_missingitemDTO['npack'];
                                                $log['total'] = $mst_missingitemDTO['nordextprice'];
                                                $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                               
                                                $log_missing = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_missing."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                                
                                            }
                                           
                                        }
                                    
                                }
                                
                                //===update total amount of receiving order ====
                                $total_amount = DB::connection('mysql_dynamic')->select("SELECT  if(SUM(nordextprice) >0, SUM(nordextprice), 0) as total_amount FROM trn_receivingorderdetail WHERE iroid = '".(int)$poid."' ");
                                
                                DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  nsubtotal = '" . $total_amount[0]->total_amount . "', nnettotal = '" . $total_amount[0]->total_amount . "' WHERE iroid='". (int)$poid ."'");
                                
                            }
					       
					    }elseif($DTVENDOR['vendor_format'] == 'ALLEN BROTHERS'){
                            
                            // if(strcasecmp($DTVENDOR['vcompanyname'], "ALLEN BROTHERS") == 0)
                            
                            while (($strline = fgets($handle1)) !== false) {
                                
                                $FirstChar = substr($strline,0,1);
                                
                                if($FirstChar == 'B'){
                                    
                                    $vCode = (substr($strline, 1, 12));
                                    $vCode = trim($vCode," ");
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A' && strlen($vCode) > 8){
                                        
                                        $trn_receivingorderdetail = DB::connection('mysql_dynamic')->select("SELECT irodetid FROM trn_receivingorderdetail WHERE iroid = '" . ($poid) . "'");
                                        $trn_receivingorderdetail = array_map(function ($value) {
                                            return (array)$value;
                                        }, $trn_receivingorderdetail);
                                        
                                        if(count($trn_receivingorderdetail)){
                                            foreach ($trn_receivingorderdetail as $k => $v) {
                                                
                                                DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorderdetail WHERE irodetid='" . (int)$v['irodetid'] . "'");
                                            }
                                        }
                                        
                                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorder WHERE iroid='" . (int)$poid . "'");
                                        
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Incorrect Barcode Formate for convert UPC-E to UPC-A';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                }
                                
                                if ($FirstChar == "A"){
                                    $orderCount = $orderCount + 1;
                                    $vCompanyName = substr($strline, 1, 6);
                                    $invoicenumber = substr($strline, 7, 10);
                                    
                                    if($vCompanyName != 'ALLENB'){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'The EDI Uploaded does not follow the format of ALLEN BROTHERS';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    
                                    $dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->get()->toArray();
                                    	
                                    if(count($dtCh) > 0){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    $datemonth = substr($strline, 17, 2);
                                    $dateday = substr($strline, 19, 2);
                                    $dateyear = substr($strline, 21, 2);
                                    $totalAmount = substr($strline, 24, 9);
                                    
                                    if (strlen($totalAmount) > 0){
                                        $totalAmount = (sprintf("%.2f", $totalAmount)/100);
                                    }
                                    
                                    $dt_year = \DateTime::createFromFormat('y', $dateyear);
                                    $dt_year = $dt_year->format('Y');
                                    
                                    $d = $datemonth .'-'. $dateday .'-'. $dt_year;
                                    
                                    $strdue1 = $d.' '.date('H:i:s');
                                    
                                    $strdue1 = \DateTime::createFromFormat('m-d-Y H:i:s', $strdue1);
                                    $strdue1 = $strdue1->format('Y-m-d H:i:s');
                                    
                                    $nReturnTotal = 0;
                                    
                                    $trnPurchaseorderdto = array();
                                    
                                    $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
                                    $trnPurchaseorderdto['nripsamt'] = 0;
                                    $trnPurchaseorderdto['dduedatetime'] = $strdue1;
                                    $trnPurchaseorderdto['nsubtotal'] = 0;
                                    $trnPurchaseorderdto['nreturntotal'] = 0;
                                    $trnPurchaseorderdto['nrectotal'] = 0;
                                    $trnPurchaseorderdto['ndeposittotal'] = 0;
                                    $trnPurchaseorderdto['ndiscountamt'] = 0;
                                    $trnPurchaseorderdto['vinvoiceno'] = $invoicenumber;
                                    $trnPurchaseorderdto['vponumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['vrefnumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
                                    $trnPurchaseorderdto['ntaxtotal'] = 0;
                                    $trnPurchaseorderdto['dcreatedate'] = $strdue1;
                                    $trnPurchaseorderdto['estatus'] = "Open";
                                    $trnPurchaseorderdto['nfreightcharge'] = 0;
                                    $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
                                    $trnPurchaseorderdto['vvendoraddress2'] = '';
                                    $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
                                    $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
                                    $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
                                    $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
                                    $trnPurchaseorderdto['vordertitle'] = '';
                                    $trnPurchaseorderdto['vordertype'] = "";
                                    $trnPurchaseorderdto['vconfirmby'] = "";
                                    $trnPurchaseorderdto['vorderby'] = "";
                                    $trnPurchaseorderdto['vshpid'] = "0";
                                    $trnPurchaseorderdto['vshpname'] = "";
                                    $trnPurchaseorderdto['vshpaddress1'] = "";
                                    $trnPurchaseorderdto['vshpaddress2'] = "";
                                    $trnPurchaseorderdto['vshpzip'] = "";
                                    $trnPurchaseorderdto['vshpstate'] ="";
                                    $trnPurchaseorderdto['vshpphone'] = "";
                                    $trnPurchaseorderdto['vshipvia'] = "";
                                    $trnPurchaseorderdto['vnotes'] = "";
                                    
                                    $ReceivingOrder = new ReceivingOrder;
                                    $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
                                    $check_count = 0;
                                    
                                }
                                // $heading = implode(",",$headers );
                                // $fp = fopen('upc_log/Upc_conversion_log_file.csv', 'w');
                                // // fputcsv($fp, $headers);
                                // fwrite($fp,$heading."\n");
                                
                                
                                if ($FirstChar == "B"){
                                    
                                    // 	$vCode = (substr($strline, 1, 12)); //// Already included check digit
                                    $vCode = (substr($strline, 1, 11));
                                    // $vCode = (substr($strline, 1, 12));
                                    $vCode = trim($vCode," ");
                                    $vname = substr($strline, 13, 25);
                                    $vname = str_replace("'","",$vname);                  
                                    $unitcost = substr($strline, 44, 6);
                                    $qtyor = substr($strline, 59, 4);
                                    $qtyor = (int)$qtyor;
                                    $rPrice = substr($strline, 63, 6);
                                    $vvendoritemcode = substr($strline, 38, 6);
                                    $npack = substr($strline, 52, 6);
                                    $npack = (int)$npack;
                                    $vSign = substr($strline, 57, 1);
                                    
                                    	                            
                                    if (strlen($unitcost) == 0){
                                        $unitcost = "0";
                                    }
                                    
                                    if (strlen($qtyor) == 0){
                                        $qtyor = "0";
                                    }
                                    
                                    if (strlen($rPrice) == 0){
                                        $rPrice = "0";
                                    }
                                    
                                    if ($unitcost != "0"){
                                        $unitcost = (sprintf("%.2f", $unitcost)/100);
                                    }
                                    
                                    if ($rPrice != "0"){
                                        $rPrice = (sprintf("%.2f", $rPrice)/100);
                                    }
                                    
                                    if (strlen($npack) == 0 || $npack == 0){
                                        $npack = "1";
                                    }
                                    
                                    $nCost = $unitcost;
                                    $unitcost = $unitcost / (int)$npack;
                                    $itotalunit = (int)$qtyor * (int)$npack;
                                    $totAmt = (int)$qtyor * $nCost;
                                    
                                    // if ($vSign == "-"){
                                    //     $nReturnTotal += $totAmt;
                                        
                                    //     $itotalunit = (int)$itotalunit *-1;
                                        
                                    //     $qtyor = (int)$qtyor*-1;
                                    // }
                                    
                                    
                                    $old_vCode = "";
                                    /*Check digit and first digit*/
                                    //======UPC-A has 11 data digits and one check digit, so 12 digits in total
                                    //======UPC-E has 7 data digits====
                                    
                                    //===if upc_convert = 'E', it means need convert UPC-A to UPC-E===
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'E'){
                                        
                                        $vCode = $this->convert_upca_to_upce($vCode);
                                        
                                        if($vCode == false && trim(substr($strline, 1, 12)) != ''){
                                            $error_import_barcode[] = array('error_barcode' => substr($strline, 1, 12), 'invoice' => $invoicenumber);
                                            $totAmt = 0;
                                        }
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 8 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A'){
                                        
                                        $vCode = $this->convert_upce_to_upca($vCode);
                                        
                                        // Adding check digit
                                        $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                        if($check_digit_barcode != -1)
                                        {
                                            $old_vCode = $vCode; 
                                            $vCode = $vCode.''.$check_digit_barcode;
                                        }
                                            
                                        if( $DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        if( $DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] != 'E' && $DTVENDOR['upc_convert'] != 'A'){
                                        
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 12 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    /*Check digit, first digit and last digit*/
                                    
                                    if ($vSign == "-"){
                                        $nReturnTotal += $totAmt;
                                        
                                        $itotalunit = (int)$itotalunit *-1;
                                        
                                        $qtyor = (int)$qtyor*-1;
                                    }
                                    
                                    //=====Limit 100 Items per invoice=========
                                    $check_count = $check_count + 1;
                                            
                                    if($check_count <= 500){
                                    
                                        $ReceivingOrder = new ReceivingOrder;
                                        $dtC = $ReceivingOrder->getItemByBarCode($vCode);
                                        
                                        if (count($dtC) == 0){
                                          // $vCode = substr($strline, 1, 11);
                                          // $vCode = trim($vCode," ");
                                            
                                            $barcode_11_digit = $ReceivingOrder->getItemByBarCode($old_vCode);
                                            if(!empty($barcode_11_digit))
                                            {
                                                if($DTVENDOR['check_digit'] == 'Y' && $DTVENDOR['remove_first_digit'] != 'Y' && $DTVENDOR['remove_last_digit'] != 'Y')
                                                {
                                                	$old_vCode = $ReceivingOrder->getItemByBarCode($old_vCode);
                                                    if(count($old_vCode) > 0)
                                                    {
                                                        $ReceivingOrder->updateBarcode($vCode,$old_vCode['vbarcode']);
                                                    }
                                                } 
                                                
                                            }
                                            
                                        }
                                        
                                        if(isset($dtC[0]) && $dtC[0] == 'aliascodeAvilable'){
                                            $log['barcode'] = $vCode;
                                            $log['itemname'] = str_replace("'","",$vname);
                                            $log['vendoritemcode'] = (string)$vvendoritemcode;
                                            $log['qoh'] = sprintf("%.2f", $qtyor);
                                            $log['sp'] = '';
                                            $log['upc'] = (int)$npack;
                                            $log['total'] = $totAmt;
                                            $log['unitcost'] = sprintf("%.4f", $unitcost);
                                            $log['Vsign'] = $vSign;
                                            
                                            $log_present = implode(",",$log );
                                            
                                            $sid = session()->get('sid');
                                            $file_path = storage_path("upc_log/edi_import_log_file.csv".$sid.".csv");
                                            
                                            $fp = fopen($file_path, 'a');
                                            fwrite($fp,$log_present."\n");
                                            fclose($fp);
                                            
                                            $json_return['file_download']= 1;
                                        }elseif(count($dtC) > 0){
                                        	//update item status
                                        	// if($dtC['estatus'] == 'Inactive'){
                                        	// 	$msg_inactive_item .= 'Item Barcode: '.$dtC['vbarcode'].PHP_EOL;
                                        	// }
                                        	$ReceivingOrder->updateItemStatus($dtC["iitemid"]);
                                        	//update item status
                                                
                                        	$iitemid = $dtC["iitemid"];
                                            // 	$dtI = $this->model_api_receiving_order->getItemVendorByVendorItemCode($vvendoritemcode);
                                            $dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
                                            if(count($dtI) == 0){
                                                $mstItemVendorDto = array();
                                                $mstItemVendorDto['iitemid'] = $iitemid;
                                                $mstItemVendorDto['ivendorid'] = $input['vvendorid'];
                                                $mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                                                
                                                $ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                                    
                                            }
                                            
                                        	$trnPurchaseOrderDetaildto = array();
                                        	$trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
                                            $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                            
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                                
                                            //      $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            
                                            $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
                                            $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
                                            $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
                                            $trnPurchaseOrderDetaildto['vunitname'] = "Each";
                                            $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $qtyor);
                                            $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
                                            $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
                                            $trnPurchaseOrderDetaildto['nordtax'] = 0;
                                            $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
                                            $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
                                            $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                            
                                            $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
                                            $trnPurchaseOrderDetaildto['vsize'] = "";
                                            
                                            
                                            if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
                                              
                                                $ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            }
                                            if($trnPurchaseOrderDetaildto['vbarcode'] == "" ){
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
                                                $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
                                                $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
                                                $log['sp'] = $trnPurchaseOrderDetaildto[''];
                                                $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
                                                $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
                                                $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                                
                                               
                                                $log_present = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_present."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                            }
                                            //end========= This is the code to covert upc and dowload log
                                            
                                          // $this->model_api_receiving_order->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            
                                        }else{ 
                                            
                                            $mst_missingitemDTO = array();
                                            $mst_missingitemDTO['norderqty'] = (int)$qtyor;
                                            $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
                                            $mst_missingitemDTO['iinvoiceid'] = $poid;
                                            $mst_missingitemDTO['vbarcode'] = $vCode;
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                             
                                            //     $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                                
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
                                            $mst_missingitemDTO['nsellunit'] = 1;
                                            $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
                                            $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                                            
                                            $mst_missingitemDTO['vcatcode'] = $vcatcode;
                                            $mst_missingitemDTO['vdepcode'] = $vdepcode;
                                            $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
                                            $mst_missingitemDTO['vtax1'] = $vtax1;
                                            $mst_missingitemDTO['vitemtype'] = "Standard";
                                            $mst_missingitemDTO['npack'] = (int)$npack;
                                            $mst_missingitemDTO['vitemcode'] = $vCode;
                                            $mst_missingitemDTO['vunitcode'] = "UNT001";
                                            $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
                                            $mst_missingitemDTO['vageverify'] = 0;
                                            
                                            if($mst_missingitemDTO['vbarcode'] != "" ){
                                                $result = $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                                // 	print_r($result);
                                            }
                                            
                                            if($mst_missingitemDTO['vbarcode'] == "" ){
                                               
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $mst_missingitemDTO['vitemname'];
                                                $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
                                                $log['qoh'] = $mst_missingitemDTO['norderqty'];
                                                $log['sp'] = $mst_missingitemDTO['dunitprice'];
                                                $log['upc'] = $mst_missingitemDTO['npack'];
                                                // $log['total'] = $mst_missingitemDTO['nordextprice'];
                                                $log['total'] = $mst_missingitemDTO['nunitcost'] * $mst_missingitemDTO['norderqty'];
                                                $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                               
                                                $log_missing = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_missing."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                                
                                            }
                                          // $this->model_api_receiving_order->createMissingitem($mst_missingitemDTO);
                                            
                                        }
                                    }else{
                                        continue;
                                    }
                                }
                                
                                //===update total amount of receiving order ====
                                $total_amount = DB::connection('mysql_dynamic')->select("SELECT  if(SUM(nordextprice) >0, SUM(nordextprice), 0) as total_amount FROM trn_receivingorderdetail WHERE iroid = '".(int)$poid."' ");
                                
                                DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  nsubtotal = '" . $total_amount[0]->total_amount . "', nnettotal = '" . $total_amount[0]->total_amount . "' WHERE iroid='". (int)$poid ."'");
                                
                                
                            }
                        
                        }elseif($DTVENDOR['vendor_format'] == 'CORE MARK'){
                                
                            while (($strline = fgets($handle1)) !== false) {
                            	$FirstChar = substr($strline,0,1);
                                 	
                                if($FirstChar == 'B'){
                                    
                                    $vCode = (substr($strline, 1, 11));
                                    $vCode = trim($vCode," ");
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A' && strlen($vCode) > 8){
                                        
                                        $trn_receivingorderdetail = DB::connection('mysql_dynamic')->select("SELECT irodetid FROM trn_receivingorderdetail WHERE iroid = '" . ($poid) . "'");
                                        $trn_receivingorderdetail = array_map(function ($value) {
                                            return (array)$value;
                                        }, $trn_receivingorderdetail);
                                        
                                        if(count($trn_receivingorderdetail)){
                                            foreach ($trn_receivingorderdetail as $k => $v) {
                                                
                                                DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorderdetail WHERE irodetid='" . (int)$v['irodetid'] . "'");
                                            }
                                        }
                                        
                                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorder WHERE iroid='" . (int)$poid . "'");
                                        
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Incorrect Barcode Formate for convert UPC-E to UPC-A';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                }
                                 	
                                if ($FirstChar == "A"){
                                	$orderCount = $orderCount + 1;
                                
                                	$vCompanyName = substr($strline, 1, 5);
                                	$invoicenumber = substr($strline, 7, 10);
                                    
                                    if($vCompanyName != 'CMARK'){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'The EDI Uploaded does not follow the format of CORE MARK';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    
                                	$dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->get()->toArray();
                                	
                                	if(count($dtCh) > 0){
                                		$json_return['code'] = 0;
                        				$json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
                        				echo json_encode($json_return);
                        				exit;
                                	}
                            		$datemonth = substr($strline, 17, 2);
                                    $dateday = substr($strline, 19, 2);
                                    $dateyear = substr($strline, 21, 2);
                                    $totalAmount = substr($strline, 24, 9);
                        
                                    if (strlen($totalAmount) > 0){
                                    	$totalAmount = (sprintf("%.2f", $totalAmount)/100);
                                    }
                        
                                    $dt_year = \DateTime::createFromFormat('y', $dateyear);
                        			$dt_year = $dt_year->format('Y');
                                    
                                    $d = $datemonth .'-'. $dateday .'-'. $dt_year;
                        
                                    $strdue1 = $d.' '.date('H:i:s');
                        
                                    $strdue1 = \DateTime::createFromFormat('m-d-Y H:i:s', $strdue1);
                        			$strdue1 = $strdue1->format('Y-m-d H:i:s');
                        
                                    $nReturnTotal = 0;
                        
                                    $trnPurchaseorderdto = array();
                        
                                    $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
                                    $trnPurchaseorderdto['nripsamt'] = 0;
                                    $trnPurchaseorderdto['dduedatetime'] = $strdue1;
                                    $trnPurchaseorderdto['nsubtotal'] = 0;
                                    $trnPurchaseorderdto['nreturntotal'] = 0;
                                    $trnPurchaseorderdto['nrectotal'] = 0;
                                    $trnPurchaseorderdto['ndeposittotal'] = 0;
                                    $trnPurchaseorderdto['ndiscountamt'] = 0;
                                    $trnPurchaseorderdto['vinvoiceno'] = $invoicenumber;
                                    $trnPurchaseorderdto['vponumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['vrefnumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
                                    $trnPurchaseorderdto['ntaxtotal'] = 0;
                                    $trnPurchaseorderdto['dcreatedate'] = $strdue1;
                                    $trnPurchaseorderdto['estatus'] = "Open";
                                    $trnPurchaseorderdto['nfreightcharge'] = 0;
                                    $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
                                    $trnPurchaseorderdto['vvendoraddress2'] = '';
                                    $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
                                    $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
                                    $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
                                    $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
                                    $trnPurchaseorderdto['vordertitle'] = '';
                                    $trnPurchaseorderdto['vordertype'] = "";
                                    $trnPurchaseorderdto['vconfirmby'] = "";
                                    $trnPurchaseorderdto['vorderby'] = "";
                                    $trnPurchaseorderdto['vshpid'] = "0";
                                    $trnPurchaseorderdto['vshpname'] = "";
                                    $trnPurchaseorderdto['vshpaddress1'] = "";
                                    $trnPurchaseorderdto['vshpaddress2'] = "";
                                    $trnPurchaseorderdto['vshpzip'] = "";
                                    $trnPurchaseorderdto['vshpstate'] ="";
                                    $trnPurchaseorderdto['vshpphone'] = "";
                                    $trnPurchaseorderdto['vshipvia'] = "";
                                    $trnPurchaseorderdto['vnotes'] = "";
                                    
                                    $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
                                    $check_count = 0;
                                }
                                // $heading = implode(",",$headers );
                                // $fp = fopen('upc_log/Upc_conversion_log_file.csv', 'w');
                                // // fputcsv($fp, $headers);
                                // fwrite($fp,$heading."\n");
                                    
                                    
                                if ($FirstChar == "B"){
                                    
                                    $vCode = (substr($strline, 1, 11));
                                    // $vCode = (substr($strline, 1, 12));
                                    $vCode = trim($vCode," ");
                                    $vname = substr($strline, 12, 25);
                                    $vname = str_replace("'","",$vname);                           
                                    $unitcost = substr($strline, 43, 6);
                                    $qtyor = substr($strline, 58, 4);
                                    $qtyor = (int)$qtyor;
                                    $rPrice = substr($strline, 62, 5);
                                    $vvendoritemcode = substr($strline, 37, 6);
                                    $npack = substr($strline, 51, 6);
                                    $npack = (int)$npack;
                                    $vSign = substr($strline, 57, 1);
                                    $vvendoritemcode = substr($strline, 37, 6);
                                        	                            
                                        
                                    if (strlen($unitcost) == 0){
                                    	$unitcost = "0";
                                    }
                                    
                                    if (strlen($qtyor) == 0){
                                    	$qtyor = "0";
                                    }
                                    
                                    if (strlen($rPrice) == 0){
                                    	$rPrice = "0";
                                    }
                                    
                                    if ($unitcost != "0"){
                                    	$unitcost = (sprintf("%.2f", $unitcost)/100);
                                    }
                                    
                                    if ($rPrice != "0"){
                                    	$rPrice = (sprintf("%.2f", $rPrice)/100);
                                    }
                                    
                                    if (strlen($npack) == 0 || $npack == 0){
                                    	$npack = "1";
                                    }
                                    
                                    $nCost = $unitcost;
                                    $unitcost = $unitcost / (int)$npack;
                                    $itotalunit = (int)$qtyor * (int)$npack;
                                    $totAmt = (int)$qtyor * $nCost;
                                    
                                    // if ($vSign == "-"){
                                    //     $nReturnTotal += $totAmt;
                                        
                                    //     $itotalunit = (int)$itotalunit *-1;
                                        
                                    //     $qtyor = (int)$qtyor*-1;
                                    // }
                                    
                                    
                                    $old_vCode = "";
                                    //======UPC-A has 11 data digits and one check digit, so 12 digits in total
                                    //======UPC-E has 7 data digits====
                                    
                                    //===if upc_convert = 'E', it means need convert UPC-A to UPC-E===
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'E'){
                                        
                                        $vCode = $this->convert_upca_to_upce($vCode);
                                        
                                        if($vCode == false && trim(substr($strline, 1, 11)) != ''){
                                            $error_import_barcode[] = array('error_barcode' => substr($strline, 1, 11), 'invoice' => $invoicenumber);
                                            $totAmt = 0;
                                        }
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 8 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A'){
                                        
                                        $vCode = $this->convert_upce_to_upca($vCode);
                                        
                                        // Adding check digit
                                        $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                        if($check_digit_barcode != -1)
                                        {
                                            $old_vCode = $vCode; 
                                            $vCode = $vCode.''.$check_digit_barcode;
                                        }
                                         
                                        if( $DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        if( $DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] != 'E' && $DTVENDOR['upc_convert'] != 'A'){
                                        
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 12 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    
                                    /*Check digit, first digit and last digit*/
                                    
                                    if ($vSign == "-"){
                                        $nReturnTotal += $totAmt;
                                        
                                        $itotalunit = (int)$itotalunit *-1;
                                        
                                        $qtyor = (int)$qtyor*-1;
                                    }
                                    
                                    //=====Limit 100 Items per invoice=========
                                    $check_count = $check_count + 1;
                                    
                                    if($check_count <= 500){
                                       
                                        $dtC = $ReceivingOrder->getItemByBarCode($vCode);
                                        
                                        if (count($dtC) == 0){
                                           // $vCode = substr($strline, 1, 11);
                                           // $vCode = trim($vCode," ");
                                            
                                            $barcode_11_digit = $ReceivingOrder->getItemByBarCode($old_vCode);
                                            if(!empty($barcode_11_digit))
                                            {
                                                if($DTVENDOR['check_digit'] == 'Y' && $DTVENDOR['remove_first_digit'] != 'Y' && $DTVENDOR['remove_last_digit'] != 'Y')
                                                {
                                                	$old_vCode = $ReceivingOrder->getItemByBarCode($old_vCode);
                                                    if(count($old_vCode) > 0)
                                                    {
                                                        $ReceivingOrder->updateBarcode($vCode,$old_vCode['vbarcode']);
                                                    }
                                                } 
                                                
                                                
                                            }
                                            
                                        }
                                        
                                        if(isset($dtC[0]) && $dtC[0] == 'aliascodeAvilable'){
                                            $log['barcode'] = $vCode;
                                            $log['itemname'] = str_replace("'","",$vname);
                                            $log['vendoritemcode'] = (string)$vvendoritemcode;
                                            $log['qoh'] = sprintf("%.2f", $qtyor);
                                            $log['sp'] = '';
                                            $log['upc'] = (int)$npack;
                                            $log['total'] = $totAmt;
                                            $log['unitcost'] = sprintf("%.4f", $unitcost);
                                            $log['Vsign'] = $vSign;
                                            
                                            $log_present = implode(",",$log );
                                            
                                            $sid = session()->get('sid');
                                            $file_path = storage_path("upc_log/edi_import_log_file.csv".$sid.".csv");
                                            
                                            $fp = fopen($file_path, 'a');
                                            fwrite($fp,$log_present."\n");
                                            fclose($fp);
                                            
                                            $json_return['file_download']= 1;
                                        }elseif(count($dtC) > 0){
                                        	//update item status
                                        	// if($dtC['estatus'] == 'Inactive'){
                                        	// 	$msg_inactive_item .= 'Item Barcode: '.$dtC['vbarcode'].PHP_EOL;
                                        	// }
                                        	$ReceivingOrder->updateItemStatus($dtC["iitemid"]);
                                        	//update item status
                                            
                                        	$iitemid = $dtC["iitemid"];
                                        	$dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
                                        	if(count($dtI) == 0){
                                        		$mstItemVendorDto = array();
                                        		$mstItemVendorDto['iitemid'] = $iitemid;
                                        		$mstItemVendorDto['ivendorid'] = $input['vvendorid'];
                                        		$mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                                        
                                        		$ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                        		
                                        	}
                                            
                                        	$trnPurchaseOrderDetaildto = array();
                                        	$trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
                                            $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                            
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                                
                                            //      $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            
                                            $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
                                            $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
                                            $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
                                            $trnPurchaseOrderDetaildto['vunitname'] = "Each";
                                            $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $qtyor);
                                            $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
                                            $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
                                            $trnPurchaseOrderDetaildto['nordtax'] = 0;
                                            $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
                                            $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
                                            $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                            
                                            $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
                                            $trnPurchaseOrderDetaildto['vsize'] = "";
                                            
                                            if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
                                               // echo strlen($trnPurchaseOrderDetaildto['vbarcode']);exit();
                                                $ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            }
                                            if($trnPurchaseOrderDetaildto['vbarcode'] == "" ){
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
                                                $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
                                                $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
                                                $log['sp'] = $trnPurchaseOrderDetaildto[''];
                                                $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
                                                $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
                                                $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                                
                                               
                                                $log_present = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_present."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                        	}
                                        	//end========= This is the code to covert upc and dowload log
                                        
                                           
                                        }else{
                            
                                        	$mst_missingitemDTO = array();
                                        	$mst_missingitemDTO['norderqty'] = (int)$qtyor;
                                            $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
                                            $mst_missingitemDTO['iinvoiceid'] = $poid;
                                            $mst_missingitemDTO['vbarcode'] = $vCode;
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                                    
                                            //      $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
                                            $mst_missingitemDTO['nsellunit'] = 1;
                                            $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
                                            $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                                            $mst_missingitemDTO['nordextprice'] = $totAmt;
                                            $mst_missingitemDTO['vcatcode'] = $vcatcode;
                                            $mst_missingitemDTO['vdepcode'] = $vdepcode;
                                            $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
                                            $mst_missingitemDTO['vtax1'] = $vtax1;
                                            $mst_missingitemDTO['vitemtype'] = "Standard";
                                            $mst_missingitemDTO['npack'] = (int)$npack;
                                            $mst_missingitemDTO['vitemcode'] = $vCode;
                                            $mst_missingitemDTO['vunitcode'] = "UNT001";
                                            $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
                                            
                                            if($mst_missingitemDTO['vbarcode'] != "" ){
                                                $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                            }
                                            if($mst_missingitemDTO['vbarcode'] == "" ){
                                                
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $mst_missingitemDTO['vitemname'];
                                                $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
                                                $log['qoh'] = $mst_missingitemDTO['norderqty'];
                                                $log['sp'] = $mst_missingitemDTO['dunitprice'];
                                                $log['upc'] = $mst_missingitemDTO['npack'];
                                                $log['total'] = $mst_missingitemDTO['nordextprice'];
                                                $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                               
                                                $log_missing = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_missing."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                                
                                                
                                                
                                            }
                                           // $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                        	
                                        }
                                    }
                                }
                                
                                //===update total amount of receiving order ====
                                $total_amount = DB::connection('mysql_dynamic')->select("SELECT  if(SUM(nordextprice) >0, SUM(nordextprice), 0) as total_amount FROM trn_receivingorderdetail WHERE iroid = '".(int)$poid."' ");
                                
                                DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  nsubtotal = '" . $total_amount[0]->total_amount . "', nnettotal = '" . $total_amount[0]->total_amount . "' WHERE iroid='". (int)$poid ."'");
                                
                                
                            }
                        }elseif($DTVENDOR['vendor_format'] == 'RESNICK'){
                                
                            while (($strline = fgets($handle1)) !== false) {
                                $FirstChar = substr($strline,0,1);
                                
                                if($FirstChar == 'B'){
                                    
                                    $vCode = (substr($strline, 1, 12));
                                    $vCode = trim($vCode," ");
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A' && strlen($vCode) > 8){
                                        
                                        $trn_receivingorderdetail = DB::connection('mysql_dynamic')->select("SELECT irodetid FROM trn_receivingorderdetail WHERE iroid = '" . ($poid) . "'");
                                        $trn_receivingorderdetail = array_map(function ($value) {
                                            return (array)$value;
                                        }, $trn_receivingorderdetail);
                                        
                                        if(count($trn_receivingorderdetail)){
                                            foreach ($trn_receivingorderdetail as $k => $v) {
                                                
                                                DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorderdetail WHERE irodetid='" . (int)$v['irodetid'] . "'");
                                            }
                                        }
                                        
                                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorder WHERE iroid='" . (int)$poid . "'");
                                        
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Incorrect Barcode Formate for convert UPC-E to UPC-A';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                }
                                
                                if ($FirstChar == "A"){
                                    $orderCount = $orderCount + 1;
                                    
                                    $vCompanyName = substr($strline, 0, 4);
                                    $invoicenumber = substr($strline, 7, 7);
                                    
                                    if($vCompanyName != 'ARES'){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'The EDI Uploaded does not follow the format of RESNICK';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                        
                                    $dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->get()->toArray();
                                        
                                    if(count($dtCh) > 0){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    $datemonth = substr($strline, 17, 2); 
                                    $dateday = substr($strline, 19, 2);
                                    $dateyear = substr($strline, 21, 2);
                                    $totalAmount = substr($strline, 24, 9);
                                    
                                    if (strlen($totalAmount) > 0){
                                    	$totalAmount = (sprintf("%.2f", $totalAmount)/100);
                                    }
                                    
                                    $dt_year = \DateTime::createFromFormat('y', $dateyear);
                        			$dt_year = $dt_year->format('Y');
                                    
                                    $d = $datemonth .'-'. $dateday .'-'. $dt_year;
                        
                                    $strdue1 = $d.' '.date('H:i:s');
                        
                                    $strdue1 = \DateTime::createFromFormat('m-d-Y H:i:s', $strdue1);
                        			$strdue1 = $strdue1->format('Y-m-d H:i:s');
                        
                                    $nReturnTotal = 0;
                        
                                    $trnPurchaseorderdto = array();
                        
                                    $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
                                    $trnPurchaseorderdto['nripsamt'] = 0;
                                    $trnPurchaseorderdto['dduedatetime'] = $strdue1;
                                    $trnPurchaseorderdto['nsubtotal'] = 0;
                                    $trnPurchaseorderdto['nreturntotal'] = 0;
                                    $trnPurchaseorderdto['nrectotal'] = 0;
                                    $trnPurchaseorderdto['ndeposittotal'] = 0;
                                    $trnPurchaseorderdto['ndiscountamt'] = 0;
                                    $trnPurchaseorderdto['vinvoiceno'] = $invoicenumber;
                                    $trnPurchaseorderdto['vponumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['vrefnumber'] = $invoicenumber;
                                    $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
                                    $trnPurchaseorderdto['ntaxtotal'] = 0;
                                    $trnPurchaseorderdto['dcreatedate'] = $strdue1;
                                    $trnPurchaseorderdto['estatus'] = "Open";
                                    $trnPurchaseorderdto['nfreightcharge'] = 0;
                                    $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
                                    $trnPurchaseorderdto['vvendoraddress2'] = '';
                                    $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
                                    $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
                                    $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
                                    $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
                                    $trnPurchaseorderdto['vordertitle'] = '';
                                    $trnPurchaseorderdto['vordertype'] = "";
                                    $trnPurchaseorderdto['vconfirmby'] = "";
                                    $trnPurchaseorderdto['vorderby'] = "";
                                    $trnPurchaseorderdto['vshpid'] = "0";
                                    $trnPurchaseorderdto['vshpname'] = "";
                                    $trnPurchaseorderdto['vshpaddress1'] = "";
                                    $trnPurchaseorderdto['vshpaddress2'] = "";
                                    $trnPurchaseorderdto['vshpzip'] = "";
                                    $trnPurchaseorderdto['vshpstate'] ="";
                                    $trnPurchaseorderdto['vshpphone'] = "";
                                    $trnPurchaseorderdto['vshipvia'] = "";
                                    $trnPurchaseorderdto['vnotes'] = "";
                                    
                                    $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
                                    $check_count = 0;
                                }
                                
                                if ($FirstChar == "B"){
                                    
                                    $vCode = (substr($strline, 1, 11)); 
                                    // $vCode = (substr($strline, 1, 12)); 
                                    $vCode = trim($vCode," ");
                                    $vname = substr($strline, 13, 25); 
                                    $vname = str_replace("'","",$vname);
                                    $vvendoritemcode = substr($strline, 38, 5); 
                                    $unitcost = substr($strline, 44, 6);
                                    // $rPrice = substr($strline, 59, 5); 
                                    $npack = substr($strline, 52, 6); 
                                    $npack = (int)$npack;
                                    $qtyor = substr($strline, 59, 4); 
                                    $qtyor = (int)$qtyor; 
                                    $rPrice = substr($strline, 63, 7); 
                                    // $vSign = substr($strline, 57, 1);
                                    // $vvendoritemcode = substr($strline, 37, 6);
                                        	                            
                                        	                            
                                        
                                    if (strlen($unitcost) == 0){
                                    	$unitcost = "0";
                                    }
                                    
                                    if (strlen($qtyor) == 0){
                                    	$qtyor = "0";
                                    }
                                    
                                    if (strlen($rPrice) == 0){
                                    	$rPrice = "0";
                                    }
                                    
                                    if ($unitcost != "0"){
                                    	$unitcost = (sprintf("%.2f", $unitcost)/100);
                                    }
                                    
                                    if ($rPrice != "0"){
                                    	$rPrice = (sprintf("%.2f", $rPrice)/100);
                                    }
                                    
                                    if (strlen($npack) == 0 || $npack == 0){
                                    	$npack = "1";
                                    }
                                    
                                    $nCost = $unitcost;
                                    $unitcost = $unitcost / (int)$npack;
                                    $itotalunit = (int)$qtyor * (int)$npack;
                                    $totAmt = (int)$qtyor * $nCost;
                                    
                                    // if ($vSign == "-"){
                                    //     $nReturnTotal += $totAmt;
                                        
                                    //     $itotalunit = (int)$itotalunit *-1;
                                        
                                    //     $qtyor = (int)$qtyor*-1;
                                    // }
                                    
                                    
                                    $old_vCode = "";
                                    /*Check digit and first digit*/
                                    
                                    //======UPC-A has 11 data digits and one check digit, so 12 digits in total
                                    //======UPC-E has 7 data digits====
                                    
                                    //===if upc_convert = 'E', it means need convert UPC-A to UPC-E===
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'E'){
                                        
                                        $vCode = $this->convert_upca_to_upce($vCode);
                                        
                                        if($vCode == false && !empty(trim(substr($strline, 1, 11))) && trim(substr($strline, 1, 11)) != ''){
                                            $error_import_barcode[] = array('error_barcode' => substr($strline, 1, 11), 'invoice' => $invoicenumber);
                                            $totAmt = 0;
                                        }
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 8 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A'){
                                        
                                        $vCode = $this->convert_upce_to_upca($vCode);
                                        
                                        // Adding check digit
                                        $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                        if($check_digit_barcode != -1)
                                        {
                                            $old_vCode = $vCode; 
                                            $vCode = $vCode.''.$check_digit_barcode;
                                        }
                                         
                                        if( $DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        if( $DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] != 'E' && $DTVENDOR['upc_convert'] != 'A'){
                                        
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 12 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    /*Check digit, first digit and last digit*/
                                    
                                    if ($vSign == "-"){
                                        $nReturnTotal += $totAmt;
                                        
                                        $itotalunit = (int)$itotalunit *-1;
                                        
                                        $qtyor = (int)$qtyor*-1;
                                    }
                                    
                                    //=====Limit 100 Items per invoice=========
                                    $check_count = $check_count + 1;
                                    
                                    if($check_count <= 500){
                                        
                                        $dtC = $ReceivingOrder->getItemByBarCode($vCode);
                                        
                                        if (count($dtC) == 0){
                                            
                                            $barcode_11_digit = $ReceivingOrder->getItemByBarCode($old_vCode);
                                            if(!empty($barcode_11_digit))
                                            {
                                                // if(isset($input['check_digit']) && $input['check_digit'] == 'with_check_dgigit')
                                                if($DTVENDOR['check_digit'] == 'Y' && $DTVENDOR['remove_first_digit'] != 'Y' && $DTVENDOR['remove_last_digit'] != 'Y')
                                                {
                                                	$old_vCode = $ReceivingOrder->getItemByBarCode($old_vCode);
                                                    if(count($old_vCode) > 0)
                                                    {
                                                        $ReceivingOrder->updateBarcode($vCode,$old_vCode['vbarcode']);
                                                    }
                                                } 
                                                
                                            }
                                            
                                        }
                                        
                                        if(isset($dtC[0]) && $dtC[0] == 'aliascodeAvilable'){
                                            $log['barcode'] = $vCode;
                                            $log['itemname'] = str_replace("'","",$vname);
                                            $log['vendoritemcode'] = (string)$vvendoritemcode;
                                            $log['qoh'] = sprintf("%.2f", $qtyor);
                                            $log['sp'] = '';
                                            $log['upc'] = (int)$npack;
                                            $log['total'] = $totAmt;
                                            $log['unitcost'] = sprintf("%.4f", $unitcost);
                                            $log['Vsign'] = $vSign;
                                            
                                            $log_present = implode(",",$log );
                                            
                                            $sid = session()->get('sid');
                                            $file_path = storage_path("upc_log/edi_import_log_file.csv".$sid.".csv");
                                            
                                            $fp = fopen($file_path, 'a');
                                            fwrite($fp,$log_present."\n");
                                            fclose($fp);
                                            
                                            $json_return['file_download']= 1;
                                        }elseif(count($dtC) > 0){
                                        	//update item status
                                        	// if($dtC['estatus'] == 'Inactive'){
                                        	// 	$msg_inactive_item .= 'Item Barcode: '.$dtC['vbarcode'].PHP_EOL;
                                        	// }
                                        	$ReceivingOrder->updateItemStatus($dtC["iitemid"]);
                                        	//update item status
                                        
                                        	$iitemid = $dtC["iitemid"];
                                        	$dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
                                        	if(count($dtI) == 0){
                                        		$mstItemVendorDto = array();
                                        		$mstItemVendorDto['iitemid'] = $iitemid;
                                        		$mstItemVendorDto['ivendorid'] = $input['vvendorid'];
                                        		$mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                            
                                        		$ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                        		
                                        		
                                        		
                                        	}
                                            
                                        	$trnPurchaseOrderDetaildto = array();
                                        	$trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
                                            $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                            
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                            //      $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            
                                            $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
                                            $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
                                            $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
                                            $trnPurchaseOrderDetaildto['vunitname'] = "Each";
                                            $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $qtyor);
                                            $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
                                            $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
                                            $trnPurchaseOrderDetaildto['nordtax'] = 0;
                                            $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
                                            $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
                                            $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                            
                                            $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
                                            $trnPurchaseOrderDetaildto['vsize'] = "";
                                            
                                            if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
                                               // echo strlen($trnPurchaseOrderDetaildto['vbarcode']);exit();
                                                $ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            }
                                            if($trnPurchaseOrderDetaildto['vbarcode'] == "" ){
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
                                                $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
                                                $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
                                                $log['sp'] = $trnPurchaseOrderDetaildto[''];
                                                $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
                                                $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
                                                $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                                
                                               
                                                $log_present = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_present."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                        	}
                                        	//end========= This is the code to covert upc and dowload log
                                        
                                           
                                        }else{
                                            
                                            $mst_missingitemDTO = array();
                                            $mst_missingitemDTO['norderqty'] = (int)$qtyor;
                                            $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
                                            $mst_missingitemDTO['iinvoiceid'] = $poid;
                                            $mst_missingitemDTO['vbarcode'] = $vCode;
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                                
                                            //     $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode); 
                                            //     // dd($mst_missingitemDTO['vbarcode']); 
                                             
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
                                            $mst_missingitemDTO['nsellunit'] = 1;
                                            $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
                                            $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                            
                                            $mst_missingitemDTO['vcatcode'] = $vcatcode;
                                            $mst_missingitemDTO['vdepcode'] = $vdepcode;
                                            $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
                                            $mst_missingitemDTO['vtax1'] = $vtax1;
                                            $mst_missingitemDTO['vitemtype'] = "Standard";
                                            $mst_missingitemDTO['npack'] = (int)$npack;
                                            $mst_missingitemDTO['vitemcode'] = $vCode;
                                            $mst_missingitemDTO['vunitcode'] = "UNT001";
                                            $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
                                            
                                            if($mst_missingitemDTO['vbarcode'] != "" ){
                                                $return = $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                            }
                                            if($mst_missingitemDTO['vbarcode'] == "" ){
                                                
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $mst_missingitemDTO['vitemname'];
                                                $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
                                                $log['qoh'] = $mst_missingitemDTO['norderqty'];
                                                $log['sp'] = $mst_missingitemDTO['dunitprice'];
                                                $log['upc'] = $mst_missingitemDTO['npack'];
                                                $log['total'] = $mst_missingitemDTO['nordextprice'] ?? 00;
                                                $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                               
                                                $log_missing = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_missing."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                                
                                                
                                                
                                            }
                                           
                                        }
                                    }
                                }
                                
                                //===update total amount of receiving order ====
                                $total_amount = DB::connection('mysql_dynamic')->select("SELECT  if(SUM(nordextprice) >0, SUM(nordextprice), 0) as total_amount FROM trn_receivingorderdetail WHERE iroid = '".(int)$poid."' ");
                                
                                DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  nsubtotal = '" . $total_amount[0]->total_amount . "', nnettotal = '" . $total_amount[0]->total_amount . "' WHERE iroid='". (int)$poid ."'");
                                
                                
                            }
                        }else{
                            
                            while (($strline = fgets($handle1)) !== false) {
                                $FirstChar = substr($strline,0,1);
                                
                                if($FirstChar == 'B'){
                                    
                                    $vCode = (substr($strline, 1, 11));
                                    $vCode = trim($vCode," ");
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A' && strlen($vCode) > 8){
                                        
                                        $trn_receivingorderdetail = DB::connection('mysql_dynamic')->select("SELECT irodetid FROM trn_receivingorderdetail WHERE iroid = '" . ($poid) . "'");
                                        $trn_receivingorderdetail = array_map(function ($value) {
                                            return (array)$value;
                                        }, $trn_receivingorderdetail);
                                        
                                        if(count($trn_receivingorderdetail)){
                                            foreach ($trn_receivingorderdetail as $k => $v) {
                                                
                                                DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorderdetail WHERE irodetid='" . (int)$v['irodetid'] . "'");
                                            }
                                        }
                                        
                                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorder WHERE iroid='" . (int)$poid . "'");
                                        
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Incorrect Barcode Formate for convert UPC-E to UPC-A';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                }
                                
                                if ($FirstChar == "A"){
                                    $orderCount = $orderCount + 1;
                                    
                                    $vCompanyName = substr($strline, 1, 6);
                                    $invoicenumber = substr($strline, 7, 10);
                                    
                                    if($vCompanyName == 'CMARK' || $vCompanyName == 'ALLENB' || $vCompanyName == 'FEDWAY' || $vCompanyName == 'ARES'){
                                        $json_return['code'] = 0; 
                                        $json_return['error'] = 'Please Import Correct Vendor EDI Invoice Format';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    
                                    $dtCh = ReceivingOrder::where('vinvoiceno', $invoicenumber)->get()->toArray();
                                    
                                    if(count($dtCh) > 0){
                                        $json_return['code'] = 0;
                                        $json_return['error'] = 'Invoice Number '.$invoicenumber.'already Exist';
                                        echo json_encode($json_return);
                                        exit;
                                    }
                                    $datemonth = substr($strline, 17, 2);
                                    $dateday = substr($strline, 19, 2);
                                    $dateyear = substr($strline, 21, 2);
                                    $totalAmount = substr($strline, 24, 9);
                                    
                                    if (strlen($totalAmount) > 0){
                                    	$totalAmount = (sprintf("%.2f", $totalAmount)/100);
                                    }
                                    
                                    $dt_year = \DateTime::createFromFormat('y', $dateyear);
                                    $dt_year = $dt_year->format('Y');
                                    
                                    $d = $datemonth .'-'. $dateday .'-'. $dt_year;
                                    
                                    $strdue1 = $d.' '.date('H:i:s');
                                    
                                    $strdue1 = \DateTime::createFromFormat('m-d-Y H:i:s', $strdue1);
                                    $strdue1 = $strdue1->format('Y-m-d H:i:s');
                                    
                                    $nReturnTotal = 0;
                                    
                                    $trnPurchaseorderdto = array();
                                    
                                    $trnPurchaseorderdto['vvendorname'] = $DTVENDOR['vcompanyname'];
                                    $trnPurchaseorderdto['nripsamt'] = 0;
                                    $trnPurchaseorderdto['dduedatetime'] = $strdue1;
                                    $trnPurchaseorderdto['nsubtotal'] = 0;
                                    $trnPurchaseorderdto['nreturntotal'] = 0;
                                    $trnPurchaseorderdto['nrectotal'] = 0;
                                    $trnPurchaseorderdto['ndeposittotal'] = 0;
                                    $trnPurchaseorderdto['ndiscountamt'] = 0;
                                    $trnPurchaseorderdto['vinvoiceno'] = trim($invoicenumber);
                                    $trnPurchaseorderdto['vponumber'] = trim($invoicenumber);
                                    $trnPurchaseorderdto['vrefnumber'] = trim($invoicenumber);
                                    $trnPurchaseorderdto['nnettotal'] = sprintf("%.2f", $totalAmount);
                                    $trnPurchaseorderdto['ntaxtotal'] = 0;
                                    $trnPurchaseorderdto['dcreatedate'] = $strdue1;
                                    $trnPurchaseorderdto['estatus'] = "Open";
                                    $trnPurchaseorderdto['nfreightcharge'] = 0;
                                    $trnPurchaseorderdto['vvendoraddress1'] = $DTVENDOR['vaddress1'];
                                    $trnPurchaseorderdto['vvendoraddress2'] = '';
                                    $trnPurchaseorderdto['vvendorid'] = $DTVENDOR['isupplierid'];
                                    $trnPurchaseorderdto['vvendorstate'] = $DTVENDOR['vstate'];
                                    $trnPurchaseorderdto['vvendorzip'] = $DTVENDOR['vzip'];
                                    $trnPurchaseorderdto['vvendorphone'] = $DTVENDOR['vphone'];
                                    $trnPurchaseorderdto['vordertitle'] = '';
                                    $trnPurchaseorderdto['vordertype'] = "";
                                    $trnPurchaseorderdto['vconfirmby'] = "";
                                    $trnPurchaseorderdto['vorderby'] = "";
                                    $trnPurchaseorderdto['vshpid'] = "0";
                                    $trnPurchaseorderdto['vshpname'] = "";
                                    $trnPurchaseorderdto['vshpaddress1'] = "";
                                    $trnPurchaseorderdto['vshpaddress2'] = "";
                                    $trnPurchaseorderdto['vshpzip'] = "";
                                    $trnPurchaseorderdto['vshpstate'] ="";
                                    $trnPurchaseorderdto['vshpphone'] = "";
                                    $trnPurchaseorderdto['vshipvia'] = "";
                                    $trnPurchaseorderdto['vnotes'] = "";
                                    
                                    $poid = $ReceivingOrder->insertReceivingOrder($trnPurchaseorderdto);
                                    
                                    $check_count = 0;
                                }
                                
                                if ($FirstChar == "B"){
                                    
                                    $vCode = (substr($strline, 1, 11));
                                    $vCode = trim($vCode," ");
                                    $vname = substr($strline, 12, 25);
                                    $vname = str_replace("'","",$vname);                           
                                    $unitcost = substr($strline, 43, 6);
                                    $qtyor = substr($strline, 58, 4);
                                    $qtyor = (int)$qtyor;
                                    $rPrice = substr($strline, 62, 5);
                                    $vvendoritemcode = substr($strline, 37, 6);
                                    $npack = substr($strline, 51, 6);
                                    $npack = (int)$npack;
                                    $vSign = substr($strline, 57, 1);
                                    $vvendoritemcode = substr($strline, 37, 6);
                                    
                                    
                                    if (strlen($unitcost) == 0){
                                    $unitcost = "0";
                                    }
                                    
                                    if (strlen($qtyor) == 0){
                                    $qtyor = "0";
                                    }
                                    
                                    if (strlen($rPrice) == 0){
                                    $rPrice = "0";
                                    }
                                    
                                    if ($unitcost != "0"){
                                    $unitcost = (sprintf("%.2f", $unitcost)/100);
                                    }
                                    
                                    if ($rPrice != "0"){
                                    $rPrice = (sprintf("%.2f", $rPrice)/100);
                                    }
                                    
                                    if (strlen($npack) == 0 || $npack == 0){
                                    $npack = "1";
                                    }
                                    
                                    $nCost = $unitcost;
                                    $unitcost = $unitcost / (int)$npack;
                                    $itotalunit = (int)$qtyor * (int)$npack;
                                    $totAmt = (int)$qtyor * $nCost;
                                    
                                    // if ($vSign == "-"){
                                    // $nReturnTotal += $totAmt;
                                    
                                    // $itotalunit = (int)$itotalunit *-1;
                                    
                                    // $qtyor = (int)$qtyor*-1;
                                    // }
                                    
                                    
                                    $old_vCode = "";
                                    /*Check digit and first digit*/
                                    //===if upc_convert = 'E', it means need convert UPC-A to UPC-E===
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'E'){
                                        
                                        $vCode = $this->convert_upca_to_upce($vCode);
                                        
                                        if($vCode == false && !empty(trim(substr($strline, 1, 11))) && trim(substr($strline, 1, 11)) != ''){
                                            $error_import_barcode[] = array('error_barcode' => substr($strline, 1, 11), 'invoice' => $invoicenumber);
                                            
                                            $totAmt = 0;
                                        }
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 8 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'A'){
                                        
                                        $vCode = $this->convert_upce_to_upca($vCode);
                                        
                                        // Adding check digit
                                        $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                        if($check_digit_barcode != -1)
                                        {
                                            $old_vCode = $vCode; 
                                            $vCode = $vCode.''.$check_digit_barcode;
                                        }
                                         
                                        if( $DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        if( $DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                    }
                                    
                                    if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] != 'E' && $DTVENDOR['upc_convert'] != 'A'){
                                        
                                        
                                        if($DTVENDOR['check_digit'] == 'Y')
                                        {
                                            $check_digit_barcode = $ReceivingOrder->calculate_upc_check_digit($vCode);
                                            if($check_digit_barcode != -1)
                                            {
                                                $old_vCode = $vCode; 
                                                $vCode = $vCode.''.$check_digit_barcode;
                                            }
                                            
                                        }
                                        if($DTVENDOR['remove_first_digit'] == 'Y')
                                        {
                                            // if(strlen($vCode) === 12 && $DTVENDOR['check_digit'] != 'Y'){
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 2)));
                                            // }else{
                                            //     $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            // }
                                            $vCode = (substr($vCode, 1, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        if($DTVENDOR['remove_last_digit'] == 'Y')
                                        {
                                            $vCode = (substr($vCode, 0, (strlen($vCode) - 1)));
                                            $vCode = trim($vCode," ");
                                        }
                                        
                                        
                                    }
                                    
                                    /*Check digit, first digit and last digit*/
                                    
                                    if ($vSign == "-"){
                                        $nReturnTotal += $totAmt;
                                        
                                        $itotalunit = (int)$itotalunit *-1;
                                        
                                        $qtyor = (int)$qtyor*-1;
                                    }
                                    
                                    //=====Limit 100 Items per invoice=========
                                    $check_count = $check_count + 1;
                                                
                                    if($check_count <= 500){
                                        $dtC = $ReceivingOrder->getItemByBarCode($vCode);
                                        
                                        // if($dtC == false){
                                        //     $vCode = '';
                                        // }
                                        
                                        if (count($dtC) == 0){
                                            // $vCode = substr($strline, 1, 11);
                                            // $vCode = trim($vCode," ");
                                            
                                            $barcode_11_digit = $ReceivingOrder->getItemByBarCode($old_vCode);
                                            if(!empty($barcode_11_digit))
                                            {
                                                if($DTVENDOR['check_digit'] == 'Y' && $DTVENDOR['remove_first_digit'] != 'Y' && $DTVENDOR['remove_last_digit'] != 'Y')
                                                {
                                                	$old_vCode = $ReceivingOrder->getItemByBarCode($old_vCode);
                                                    if(count($old_vCode) > 0)
                                                    {
                                                        $ReceivingOrder->updateBarcode($vCode,$old_vCode['vbarcode']);
                                                    }
                                                } 
                                                
                                            }
                                            
                                        }
                                            
                                        if(isset($dtC[0]) && $dtC[0] == 'aliascodeAvilable'){
                                            $log['barcode'] = $vCode;
                                            $log['itemname'] = str_replace("'","",$vname);
                                            $log['vendoritemcode'] = (string)$vvendoritemcode;
                                            $log['qoh'] = sprintf("%.2f", $qtyor);
                                            $log['sp'] = '';
                                            $log['upc'] = (int)$npack;
                                            $log['total'] = $totAmt;
                                            $log['unitcost'] = sprintf("%.4f", $unitcost);
                                            $log['Vsign'] = $vSign;
                                            
                                            $log_present = implode(",",$log );
                                            
                                            $sid = session()->get('sid');
                                            $file_path = storage_path("upc_log/edi_import_log_file.csv".$sid.".csv");
                                            
                                            $fp = fopen($file_path, 'a');
                                            fwrite($fp,$log_present."\n");
                                            fclose($fp);
                                            
                                            $json_return['file_download']= 1;
                                        }elseif(count($dtC) > 0){
                                        	//update item status
                                        	// if($dtC['estatus'] == 'Inactive'){
                                        	// 	$msg_inactive_item .= 'Item Barcode: '.$dtC['vbarcode'].PHP_EOL;
                                        	// }
                                        	$ReceivingOrder->updateItemStatus($dtC["iitemid"]);
                                        	//update item status
                                            
                                        	$iitemid = $dtC["iitemid"];
                                        	$dtI = DB::connection('mysql_dynamic')->table('mst_itemvendor')->where('vvendoritemcode', $vvendoritemcode)->get()->toArray();
                                        	if(count($dtI) == 0){
                                        		$mstItemVendorDto = array();
                                        		$mstItemVendorDto['iitemid'] = $iitemid;
                                        		$mstItemVendorDto['ivendorid'] = $input['vvendorid'];
                                        		$mstItemVendorDto['vvendoritemcode'] = $vvendoritemcode;
                                        
                                        		$ReceivingOrder->insertItemVendor($mstItemVendorDto);
                                        		
                                        	}
                                            
                                        	$trnPurchaseOrderDetaildto = array();
                                        	$trnPurchaseOrderDetaildto['npackqty'] = (int)$npack;
                                            $trnPurchaseOrderDetaildto['vbarcode'] = $vCode;
                                            
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                                
                                            //      $trnPurchaseOrderDetaildto['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            
                                            $trnPurchaseOrderDetaildto['iroid'] = (int)$poid;
                                            $trnPurchaseOrderDetaildto['vitemid'] = (string)$iitemid;
                                            $trnPurchaseOrderDetaildto['vitemname'] = str_replace("'","",$dtC["vitemname"]);
                                            $trnPurchaseOrderDetaildto['vunitname'] = "Each";
                                            $trnPurchaseOrderDetaildto['nordqty'] = sprintf("%.2f", $qtyor);
                                            $trnPurchaseOrderDetaildto['nordunitprice'] = sprintf("%.2f", $nCost);
                                            $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
                                            $trnPurchaseOrderDetaildto['nordtax'] = 0;
                                            $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
                                            $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$vvendoritemcode;
                                            $trnPurchaseOrderDetaildto['nunitcost'] = sprintf("%.4f", $unitcost);
                                            
                                            $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
                                            $trnPurchaseOrderDetaildto['vsize'] = "";
                                            
                                            if($trnPurchaseOrderDetaildto['vbarcode'] != "" ){
                                               // echo strlen($trnPurchaseOrderDetaildto['vbarcode']);exit();
                                                $ReceivingOrder->InsertPurchaseDetail($trnPurchaseOrderDetaildto);
                                            }
                                            if($trnPurchaseOrderDetaildto['vbarcode'] == "" ){
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $trnPurchaseOrderDetaildto['vitemname'];
                                                $log['vendoritemcode'] = $trnPurchaseOrderDetaildto['vvendoritemcode'];
                                                $log['qoh'] = $trnPurchaseOrderDetaildto['nordqty'];
                                                $log['sp'] = $trnPurchaseOrderDetaildto[''];
                                                $log['upc'] = $trnPurchaseOrderDetaildto['npackqty'];
                                                $log['total'] = $trnPurchaseOrderDetaildto['nordextprice'];
                                                $log['unitcost'] = $trnPurchaseOrderDetaildto['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                                
                                               
                                                $log_present = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_present."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                        	}
                                        	//end========= This is the code to covert upc and dowload log
                                        
                                           
                                        }else{
                            
                                        	$mst_missingitemDTO = array();
                                        	$mst_missingitemDTO['norderqty'] = (int)$qtyor;
                                            $mst_missingitemDTO['vvendoritemcode'] = $vvendoritemcode;
                                            $mst_missingitemDTO['iinvoiceid'] = $poid;
                                            $mst_missingitemDTO['vbarcode'] = $vCode;
                                            // This is the code to covert upc and dowload log
                                            // if(isset($input['check_digit']) && $input['check_digit'] == 'upc_conversion'){
                                            // if(isset($DTVENDOR['upc_convert']) && $DTVENDOR['upc_convert'] == 'Y'){
                                                    
                                            //      $mst_missingitemDTO['vbarcode'] = $this->convert_upca_to_upce($vCode);  
                                             
                                            // } 
                                            //end========= This is the code to covert upc and dowload log
                                            $mst_missingitemDTO['vitemname'] = str_replace("'","",$vname);
                                            $mst_missingitemDTO['nsellunit'] = 1;
                                            $mst_missingitemDTO['dcostprice'] = sprintf("%.2f", $nCost);
                                            $mst_missingitemDTO['dunitprice'] = sprintf("%.2f", $rPrice);
                                            $mst_missingitemDTO['nordextprice'] = $totAmt;
                                            $mst_missingitemDTO['vcatcode'] = $vcatcode;
                                            $mst_missingitemDTO['vdepcode'] = $vdepcode;
                                            $mst_missingitemDTO['vsuppcode'] = $input['vvendorid'];
                                            $mst_missingitemDTO['vtax1'] = $vtax1;
                                            $mst_missingitemDTO['vitemtype'] = "Standard";
                                            $mst_missingitemDTO['npack'] = (int)$npack;
                                            $mst_missingitemDTO['vitemcode'] = $vCode;
                                            $mst_missingitemDTO['vunitcode'] = "UNT001";
                                            $mst_missingitemDTO['nunitcost'] = sprintf("%.2f", $unitcost);
                                            
                                            if($mst_missingitemDTO['vbarcode'] != "" ){
                                                $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                            }
                                            if($mst_missingitemDTO['vbarcode'] == "" ){
                                                
                                                $log['barcode'] = $vCode;
                                                $log['itemname'] = $mst_missingitemDTO['vitemname'];
                                                $log['vendoritemcode'] = $mst_missingitemDTO['vvendoritemcode'];
                                                $log['qoh'] = $mst_missingitemDTO['norderqty'];
                                                $log['sp'] = $mst_missingitemDTO['dunitprice'];
                                                $log['upc'] = $mst_missingitemDTO['npack'];
                                                $log['total'] = $mst_missingitemDTO['nordextprice'];
                                                $log['unitcost'] = $mst_missingitemDTO['nunitcost'];
                                                $log['Vsign'] = $vSign;
                                               
                                                $log_missing = implode(",",$log );
                                                
                                                $file_path = storage_path("upc_log/Upc_conversion_log_file.csv");
                                                
                                                $fp = fopen($file_path, 'a');
                                                fwrite($fp,$log_missing."\n");
                                                fclose($fp);
                                                
                                                $json_return['file_download']= 1;
                                                
                                                
                                                
                                            }
                                           // $ReceivingOrder->createMissingitem($mst_missingitemDTO);
                                        	
                                        }
                                    }
                                }
                                
                                //===update total amount of receiving order ====
                                $total_amount = DB::connection('mysql_dynamic')->select("SELECT  if(SUM(nordextprice) >0, SUM(nordextprice), 0) as total_amount FROM trn_receivingorderdetail WHERE iroid = '".(int)$poid."' ");
                                
                                DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  nsubtotal = '" . $total_amount[0]->total_amount . "', nnettotal = '" . $total_amount[0]->total_amount . "' WHERE iroid='". (int)$poid ."'");
                                
                                
                            }
                        }
                        
                        $ReceivingOrder->updatePurchaseOrderReturnTotal($nReturnTotal,$poid);
                        
                        $sid = session()->get('sid');
                        $file_pa = storage_path("upc_log/error_import_barcode_log_file_".$sid.".csv");
                        $fp1 = fopen($file_pa, 'w');
                        fwrite($fp1, json_encode($error_import_barcode));
                        
                        fclose($fp1);
                        
                        // if(!empty($error_import_barcode)){
                        //     session()->put('error_import_barcode', $error_import_barcode);
                        // }
                        
                        $json_return['code'] = 1;
						$json_return['success'] = 'Successfully Imported Invoice!';
						echo json_encode($json_return);
						exit;
						
					}else{
						$json_return['code'] = 0;
						$json_return['error'] = 'file not found';
						echo json_encode($json_return);
						exit;
					}
				}else{
					$json_return['code'] = 0;
					$json_return['error'] = 'Please select file';
					echo json_encode($json_return);
					exit;
				}
			}else{
				$json_return['code'] = 0;
				$json_return['error'] = 'Please select vendor';
				echo json_encode($json_return);
				exit;
			}
		}
		
	}
	
	public function convert_upca_to_upce($upc) 
	{   
		if(!isset($upc)||!is_numeric($upc)) { return false; }
		if(strlen($upc)==11) { $upc = $upc.$this->validate_upca($upc,true); }
		if(strlen($upc)>12||strlen($upc)<12) { return false; }
		if($this->validate_upca($upc)===false) { return false; }
        if(!preg_match("/0(\d{11})/", $upc)) { return false; }
		$upce = null;
		if(preg_match("/0(\d{2})00000(\d{3})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."0";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{2})10000(\d{3})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."1";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{2})20000(\d{3})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."2";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{3})00000(\d{2})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."3";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{4})00000(\d{1})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."4";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{5})00005(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."5";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00006(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."6";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00007(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."7";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00008(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."8";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00009(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."9";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if($upce==null) { return false; }
	}
	
	public function validate_upca($upc,$return_check=false) 
	{
		if(!isset($upc)||!is_numeric($upc)) { return false; }
		if(strlen($upc)>12) { preg_match("/^(\d{12})/", $upc, $fix_matches); $upc = $fix_matches[1]; }
		if(strlen($upc)>12||strlen($upc)<11) { return false; }
		if(strlen($upc)==11) {
		preg_match("/^(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})/", $upc, $upc_matches); }
		if(strlen($upc)==12) {
		preg_match("/^(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})/", $upc, $upc_matches); }
		$OddSum = ($upc_matches[1] + $upc_matches[3] + $upc_matches[5] + $upc_matches[7] + $upc_matches[9] + $upc_matches[11]) * 3;
		$EvenSum = $upc_matches[2] + $upc_matches[4] + $upc_matches[6] + $upc_matches[8] + $upc_matches[10];
		$AllSum = $OddSum + $EvenSum;
		$CheckSum = $AllSum % 10;
		if($CheckSum>0) {
		$CheckSum = 10 - $CheckSum; }
		if($return_check==false&&strlen($upc)==12) {
		if($CheckSum!=$upc_matches[12]) { return false; }
		if($CheckSum==$upc_matches[12]) { return true; } }
		if($return_check==true) { return $CheckSum; } 
		if(strlen($upc)==11) { return $CheckSum; } 
	}
	
	public function delete(Request $request) 
	{
		
		if ($request->isMethod('post')) {
            
			$temp_arr = json_decode(file_get_contents('php://input'), true);
            
            $ReceivingOrder = new ReceivingOrder;
			$data = $ReceivingOrder->deletePurchaseOrder($temp_arr);

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
	
	public function convert_upce_to_upca($upc)
	{   
	    if(isset($upc[6])){
            switch ($upc[6]) {
                case '0': {
                    $data = substr($upc, 0, 3)."00000".substr($upc, 3, 3);
                    return $data;
                    break;
                }
                case '1': {
                    $data = substr($upc, 0, 3)."10000".substr($upc, 3, 3);
                    return $data;
                    break;
                }
                case '2': {
                    $data = substr($upc, 0, 3)."20000".substr($upc, 3, 3);
                    return $data;
                    break;
                }
                case '3': {
                    $data = substr($upc, 0, 4)."00000".substr($upc, 4, 2);
                    return $data;
                    break;
                }
                case '4': {
                    $data = substr($upc, 0, 5)."00000".$upc[5];
                    return $data;
                    break;
                }
                case '5': {
                    $data = substr($upc, 0, 6)."00005";
                    return $data;
                    break;
                }
                case '6': {
                    $data = substr($upc, 0, 6)."00006";
                    return $data;
                    break;
                }
                case '7': {
                    $data = substr($upc, 0, 6)."00007";
                    return $data;
                    break;
                }
                case '8': {
                    $data = substr($upc, 0, 6)."00008";
                    return $data;
                    break;
                }
                case '9': {
                    $data = substr($upc, 0, 6)."00009";
                    return $data;
                    break;
                }
            }
	    }
	}
	
	public function get_vendor_data(Request $request) 
	{
		$input = $request->all();
            
		if ($request->isMethod('get')) {
            
            $vendor_data = Supplier::where('isupplierid', $input['vendor'])->first();
            $vendor_data = $vendor_data === null?[]:$vendor_data->toArray();
            
            return response(json_encode($vendor_data), 200)
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
	
	public function get_search_item_history(Request $request)
	{
	    $input = $request->all();
		$json = array();
		
        if (isset($input) && $request->isMethod('post')) {		    
			
            $pre_items_id = json_decode(file_get_contents('php://input'), true);
            
			$ReceivingOrder = new ReceivingOrder;
			$items = $ReceivingOrder->getSearchItemAll($input,$input['ivendorid'],$pre_items_id);
			$json['items'] = $items;
		}
            
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
    
}