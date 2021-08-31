<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use PDF;

use App\Model\Item;
use App\Model\PurchaseOrder;
use App\Model\SalesHistoryReport;
use App\Model\WebAdminSetting;
use App\Model\Department;
use App\Model\Category;
use App\Model\Manufacturer;
use App\Model\Unit;
use App\Model\Supplier;
use App\Model\Size;
use App\Model\SubCategory;
use App\Model\AgeVerification;
use App\Model\Store;


class PurchaseOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    protected function getList(Request $request) 
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
        
		$data['current_url'] = url('/PurchaseOrder');
		$data['add'] = url('/PurchaseOrder/add_manual');
		$data['edit'] = url('/PurchaseOrder/edit');
		$data['delete'] = url('/PurchaseOrder/delete');
		$data['po_sales_history'] = url('/PurchaseOrder/add_po_sales_history');
		$data['po_par_level'] = url('/PurchaseOrder/add_po_par_level');
		$data['edit_list'] = url('/PurchaseOrder/edit_list');
		
        if($order == 'DESC'){
            $data['sort_estatus'] = url('/PurchaseOrder?sort=estatus&order=ASC');
            $data['sort_vponumber'] = url('/PurchaseOrder?sort=vponumber&order=ASC');
            $data['sort_vvendorname'] = url('/PurchaseOrder?sort=vvendorname&order=ASC');
            $data['sort_vordertype'] = url('/PurchaseOrder?sort=vordertype&order=ASC');
            $data['sort_dcreatedate'] = url('/PurchaseOrder?sort=dcreatedate&order=ASC');
            $data['sort_dreceiveddate'] = url('/PurchaseOrder?sort=dreceiveddate&order=ASC');
            $data['sort_LastUpdate'] = url('/PurchaseOrder?sort=LastUpdate&order=ASC');
        }else{
            $data['sort_estatus'] = url('/PurchaseOrder');
            $data['sort_vponumber'] = url('/PurchaseOrder');
            $data['sort_vvendorname'] = url('/PurchaseOrder');
            $data['sort_vordertype'] = url('/PurchaseOrder');
            $data['sort_dcreatedate'] = url('/PurchaseOrder');
            $data['sort_dreceiveddate'] = url('/PurchaseOrder');
            $data['sort_LastUpdate'] = url('/PurchaseOrder');
        }
		
		$data['SID'] = session()->get('sid');
		
		
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
        
        
		return view('purchase_order.purchase_order_list',compact('data'));
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

        if(empty(trim($search_items['estatus'])) && empty(trim($search_items['vponumber'])) && empty(trim($search_items['nnettotal'])) && empty(trim($search_items['vinvoiceno'])) &&  empty(trim($search_items['vvendorname'])) && empty(trim($search_items['vordertype']))  )
        {
            $limit = 20;
            
            $start_from = ($input['start']);

            $select_query = "SELECT * FROM trn_purchaseorder LIMIT ". $input['start'].", ".$limit;

            $count_select_query = "SELECT COUNT(distinct ipoid) as count FROM trn_purchaseorder";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            $count_query = isset($count_query[0])?(array)$count_query[0]:[];
            
            $count_records = $count_total = (int)$count_query['count'];

        }else{
            $limit = 20;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length']; 
            $condition = "WHERE 1=1";
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
            
            $select_query = "SELECT * FROM trn_purchaseorder ".$condition." LIMIT ". $input['start'].", ".$limit;

            $count_select_query = "SELECT COUNT(distinct ipoid) as count FROM trn_purchaseorder ".$condition;
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            $count_query = isset($count_query[0])?(array)$count_query[0]:[];
            
            $count_records = $count_total = (int)$count_query['count'];
        }
        
        $query = DB::connection('mysql_dynamic')->select($select_query);
        
		$data['SID'] = session()->get('sid');
		$purchase_orders = array();
        
        $datas = array();
        if(count($query) > 0){
            foreach ($query as $key => $value) {

                // if($value->estatus == 'Close'){
                //     $view_edit = url('/PurchaseOrder/info' . '?ipoid=' . $value->ipoid );
                // }else{
                //     $view_edit = url('/PurchaseOrder/edit' . '?ipoid=' . $value->ipoid );
                // }
                
                $view_edit = url('/PurchaseOrder/edit' . '?ipoid=' . $value->ipoid );
                
                $temp = array();
                $temp['ipoid']          = $value->ipoid;
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
				$temp['delete']         = url('/PurchaseOrder/delete' . '?ipoid=' . $value->ipoid );

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
        session()->forget('purchase_order_info');
        
        $input = $request->all();
        
        $data = $this->getFormManual($input);
        
        return view('purchase_order.purchase_order_form_manual',compact('data'));
    }
    
    public function add_manual(Request $request)
    {
        session()->forget('purchase_order_info');
        
        $input = $request->all();
        
        $data = $this->getFormManual($input);
        
        return view('purchase_order.purchase_order_form_manual',compact('data'));
    }
    
    public function add_manual_post(Request $request) 
    {
        $input = $request->all();
        
		if ($request->isMethod('post')){
            
            $PurchaseOrder = new PurchaseOrder;
			$data_response = $PurchaseOrder->addPurchaseOrder($input,null,'PO');
            
			if(array_key_exists("success",$data_response)){
				// http_response_code(200);
				
				$ipoid = (int)$data_response['ipoid'];
				$purchase_order_info = $PurchaseOrder->getPurchaseOrder($ipoid);
                session()->put('purchase_order_info', $purchase_order_info);
                
                return response(json_encode($data_response), 200)
                    ->header('Content-Type', 'application/json');
                  
			}else{
				return response(json_encode($data_response), 401)
                    ->header('Content-Type', 'application/json');
			}
                
		}
	}
	
	public function post_po_sales_history(Request $request)
	{
        
		if ($request->isMethod('post')){
            
            $input = $request->all();
            $input = $input['data'];
            
            $data['recd_inputs'] = 'yes';
            
            if(isset($input['include_current_week']) && !empty(trim($input['include_current_week']))){
                $data['include_current_week'] = $input['include_current_week'];
            }
            
            if(isset($input['include_current_month'])){
                $data['include_current_month'] = $input['include_current_month'];
            }
            
            if(isset($input['item_name']) && !empty(trim($input['item_name'])) ){
                $data['item_name'] = $input['item_name'];
            }
            
            if(isset($input['barcode']) && !empty(trim($input['barcode']))){
                $data['barcode'] = $input['barcode'];
            }
            
            if(isset($input['size']) && !empty(trim($input['size']))){
                $data['size'] = $input['size'];
            }
            
            if(isset($input['supplier_code']) && $input['supplier_code'] !== 'all'){
                $data['vendors'] = $input['supplier_code'];
            }
            
            if(isset($input['dept_code']) && $input['dept_code'] !== 'all'){
                $data['departments'] = $input['dept_code'];
            }
            
            /*if(isset($input['supplier_code'])){
                $data['vendors'] = $input['supplier_code'];
            }*/
            
            if(isset($input['category_code']) && $input['category_code'] !== 'all'){
                $data['categories'] = $input['category_code'];
            }
            
            if(isset($input['sub_category_id']) && $input['sub_category_id'] !== 'all'){
                $data['sub_categories'] = $input['sub_category_id'];
            }
            
            // print_r($input); die;
            
            if(isset($input['price_select_by'])){

                if($input['price_select_by'] === 'between'){
                    
                    $data['price_select_by'] = $input['price_select_by'];
                    
                    if(isset($input['select_by_value_1']) && (!empty(trim($input['select_by_value_1'])) || $input['select_by_value_1'] !== 0)){
                        // echo 277;
                        $data['select_by_value_1'] = $input['select_by_value_1'];
                    }
                    
                    if(isset($input['select_by_value_2']) && !empty(trim($input['select_by_value_2']))){
                        // echo 282;
                        $data['select_by_value_2'] = $input['select_by_value_2'];
                    }
                    
                   
                } elseif(($input['price_select_by'] === 'greater') || ($input['price_select_by'] === 'less') || ($input['price_select_by'] === 'equal')){
                   
                   $data['price_select_by'] = $input['price_select_by'];
                   
                   if(isset($input['select_by_value_1']) && !empty(trim($input['select_by_value_1']))){
                        $data['select_by_value_1'] = $input['select_by_value_1'];
                    }
                    
                }
                
            }
            
            if(isset($input['select_by'])){
                $data['selected_select_by'] = $input['select_by'];
            }
            if(isset($input['select_by']) && $input['select_by'] === 'm'){
                $data['months'] = $input['input_month'];
                
                $PurchaseOrder = new PurchaseOrder;
                $result = $PurchaseOrder->getMonthlyBreakup($data);
                
            } elseif( isset($input['select_by']) && $input['select_by'] === 'w') {
                
                $data['weeks'] = $input['input_week'];
                
                $PurchaseOrder = new PurchaseOrder;
                $result = $PurchaseOrder->getWeeklyBreakup($data);
                
                
            } elseif( isset($input['select_by']) && $input['select_by'] === 'y') {
                $data['year'] = $input['input_year'];
                
                $PurchaseOrder = new PurchaseOrder;
                $result = $PurchaseOrder->getYearlyBreakup($data);
                
            } elseif(isset($input['select_by']) && $input['select_by'] === 'c'){
                
                $data['custom_date_range'] = $input['custom_date_range'];
                
                $date_range = explode('-', $input['custom_date_range']);
                
                $data['from'] = $from = trim($date_range[0]);
                $from_date = \DateTime::createFromFormat('m/d/Y h:i a', $from);
                
                $data['from_date'] = $from_date->format('Y-m-d H:i:s');
                
                $data['to'] = $to = trim($date_range[1]);
                $to = \DateTime::createFromFormat('m/d/Y h:i a', $to);
                $data['to_date'] = $to->format('Y-m-d H:i:s');
                
                $PurchaseOrder = new PurchaseOrder;
                $result = $PurchaseOrder->getCustomBreakup($data);
                
            }       
            
        }
        
        if(count($result['result']) !== 0) {
            
            $html = '<thead class="button-blue text-white text-uppercase font-weight-bold" style="font-size:12px";><tr><th></th>';
            $html .= '<th>Item Name</th>';
            $html .= '<th>Odr By</th>';
            $html .= '<th>Odr Qty</th>';
            
            $html .= '<th>Size</th>';
            
            foreach($result['header'] as $v){
                $html .= '<th>'.$v.'</th>';
            }
            
            $html .= '</tr></thead><div style="overflow-y: auto; overflow-x: hidden;">';
            
            $html .= '<tbody>';
            foreach($result['result'] as $row){
                $html .= '<tr>';
                
                $count = 0;
                
                foreach($row as $k => $v){
                    
                    $count++;
                    
                    if($count === 3){
                        $html .= '<td class="tdOrderBy"><select class="orderBy"><option value="case">Case</option><option value="unit">Unit</option></select></td>';
                        $html .= '<td class="tdOrderQty"><input type="text" class="orderQty" style="width:35px;"></td>';
                    }
                    
                    if($k === 'iitemid'){
                        // $html .= '<td><input class="selectItemId" type="checkbox" id="'.$v.'" ></td>';
                        
                        $html .= '<td><input type="checkbox" name="selected_search_history_items[]" value="'. $v .'"></td>';
                    } else {
                        $html .= '<td>'.$v.'</td>';
                    }
                    
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></div>';
        } else {
            $html = '';
        }
        
        echo json_encode($html);
	}
	
	public function add_po_sales_history(Request $request)
    {
        session()->forget('purchase_order_info');
        
        $input = $request->all();
        
        $data = $this->getFormSalesHistory($input);
        
        return view('purchase_order.purchase_order_form_sales_history',compact('data'));
    }
    
    public function add_po_par_level(Request $request)
    {
        session()->forget('purchase_order_info');
        
        $input = $request->all();
        
        $data = $this->getFormParLevel($input);
        
        return view('purchase_order.purchase_order_form_par_level',compact('data'));
    }
    
    protected function getFormManual($input) 
    {
		
		$error_warning = session()->get('warning');
        if (isset($error_warning)) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        
		if (!isset($input['ipoid'])) {
			$data['action'] = url('/PurchaseOrder/add_manual_post');
			
		} else {
			$data['action'] = url('/PurchaseOrder/edit_post?ipoid=' . $input['ipoid']);
		}
        
		$data['cancel'] = url('/PurchaseOrder');
            
        $data['redirect_edit_url'] = url('/PurchaseOrder/redirect_edit_manual');
		$data['get_vendor'] = url('/PurchaseOrder/get_vendor');
		$data['get_search_items'] = url('/PurchaseOrder/get_search_items');
		$data['check_invoice'] = url('/PurchaseOrder/check_invoice');
		$data['purchase_order_list'] = url('/PurchaseOrder');
		$data['delete_purchase_order_item'] = url('/PurchaseOrder/delete_purchase_order_item');
		$data['save_receive_item'] = url('/PurchaseOrder/save_receive_item');
		$data['add_purchase_order_item'] = url('/PurchaseOrder/add_purchase_order_item');

		$data['get_search_item_history'] = url('/PurchaseOrder/get_search_item_history');
		$data['search_vendor_item_code'] = url('/PurchaseOrder/search_vendor_item_code');
		$data['get_item_history'] = url('/PurchaseOrder/get_item_history');
		$data['get_item_history_date'] = url('/PurchaseOrder/get_item_history_date');
		$data['get_vendor_item'] = url('/PurchaseOrder/get_vendor_item');
		$data['get_vendor_item_code_data'] = url('/PurchaseOrder/get_vendor_item_code_data');

		$data['check_warehouse_invoice'] = url('administration/transfer/check_invoice');
		
		$data['get_purchase_history'] = url('/PurchaseOrder/get_purchase_history');
		$data['export_as_pdf'] = url('/PurchaseOrder/export_as_pdf');
		$data['export_as_csv'] = url('/PurchaseOrder/export_as_csv');
		$data['export_as_email'] = url('/PurchaseOrder/export_as_email');
		$data['export_as_excel'] = url('/PurchaseOrder/export_as_excel');
		
		$data['get_categories_url']    = url('buydown/get_item_categories');

		if (isset($input['ipoid'])) {
            
            $PurchaseOrder = new PurchaseOrder;
			$purchase_order_info = $PurchaseOrder->getPurchaseOrder($input['ipoid']);
			
			session()->put('purchase_order_info', $purchase_order_info);
			    
			$data['ipoid'] = $input['ipoid'];
		}
		
		$data['sid'] = session()->get('sid');
        
		if (isset($input['vponumber'])) {
			$data['vponumber'] = $input['vponumber'];
		} elseif (!empty($purchase_order_info)) {
			$data['vponumber'] = $purchase_order_info['vponumber'];
		} else {
                $temp_vponumber = PurchaseOrder::orderBy('ipoid', 'desc')->first();
                
			if( !empty($temp_vponumber) && isset($temp_vponumber->ipoid)){
				$data['vponumber'] = str_pad($temp_vponumber->ipoid+1,9,"0",STR_PAD_LEFT);
			}else{
				$data['vponumber'] = str_pad(1,9,"0",STR_PAD_LEFT);
			}
			
		}
		
		if (isset($input['nnettotal'])) {
			$data['nnettotal'] = $input['nnettotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nnettotal'] = $purchase_order_info['nnettotal'];
		}

		if (isset($input['ntaxtotal'])) {
			$data['ntaxtotal'] = $input['ntaxtotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['ntaxtotal'] = $purchase_order_info['ntaxtotal'];
		}

		if (isset($input['dcreatedate'])) {
			$data['dcreatedate'] = $input['dcreatedate'];
		} elseif (!empty($purchase_order_info)) {
			$data['dcreatedate'] = $purchase_order_info['dcreatedate'];
		}

		if (isset($input['dreceiveddate'])) {
			$data['dreceiveddate'] = $input['dreceiveddate'];
		} elseif (!empty($purchase_order_info)) {
			$data['dreceiveddate'] = $purchase_order_info['dreceiveddate'];
		}

		if (isset($input['estatus'])) {
			$data['estatus'] = $input['estatus'];
		} elseif (!empty($purchase_order_info)) {
			$data['estatus'] = $purchase_order_info['estatus'];
		}
        
		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfreightcharge'] = $purchase_order_info['nfreightcharge'];
		}
        
		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfreightcharge'] = $purchase_order_info['nfreightcharge'];
		}
        
		if (isset($input['vordertitle'])) {
			$data['vordertitle'] = $input['vordertitle'];
		} elseif (!empty($purchase_order_info)) {
			$data['vordertitle'] = $purchase_order_info['vordertitle'];
		}
        
		if (isset($input['vorderby'])) {
			$data['vorderby'] = $input['vorderby'];
		} elseif (!empty($purchase_order_info)) {
			$data['vorderby'] = $purchase_order_info['vorderby'];
		}
            
		if (isset($input['vconfirmby'])) {
			$data['vconfirmby'] = $input['vconfirmby'];
		} elseif (!empty($purchase_order_info)) {
			$data['vconfirmby'] = $purchase_order_info['vconfirmby'];
		}
        
		if (isset($input['vnotes'])) {
			$data['vnotes'] = $input['vnotes'];
		} elseif (!empty($purchase_order_info)) {
			$data['vnotes'] = $purchase_order_info['vnotes'];
		}
        
		if (isset($input['vvendorid'])) {
			$data['vvendorid'] = $input['vvendorid'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorid'] = $purchase_order_info['vvendorid'];
		}else{
			$data['vvendorid'] = "";
		}
        
		if (isset($input['vvendorname'])) {
			$data['vvendorname'] = $input['vvendorname'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorname'] = $purchase_order_info['vvendorname'];
		}else{
		    $data['vvendorname'] = '';
		}
        
		if (isset($input['vvendoraddress1'])) {
			$data['vvendoraddress1'] = $input['vvendoraddress1'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendoraddress1'] = $purchase_order_info['vvendoraddress1'];
		}
        
		if (isset($input['vvendoraddress2'])) {
			$data['vvendoraddress2'] = $input['vvendoraddress2'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendoraddress2'] = $purchase_order_info['vvendoraddress2'];
		}
        
		if (isset($input['vvendorstate'])) {
			$data['vvendorstate'] = $input['vvendorstate'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorstate'] = $purchase_order_info['vvendorstate'];
		}
        
		if (isset($input['vvendorzip'])) {
			$data['vvendorzip'] = $input['vvendorzip'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorzip'] = $purchase_order_info['vvendorzip'];
		}
        
		if (isset($input['vvendorphone'])) {
			$data['vvendorphone'] = $input['vvendorphone'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorphone'] = $purchase_order_info['vvendorphone'];
		}
        
		if (isset($input['vshipvia'])) {
			$data['vshipvia'] = $input['vshipvia'];
		} elseif (!empty($purchase_order_info)) {
			$data['vshipvia'] = $purchase_order_info['vshipvia'];
		}
        
		if (isset($input['nrectotal'])) {
			$data['nrectotal'] = $input['nrectotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nrectotal'] = $purchase_order_info['nrectotal'];
		}
        
		if (isset($input['nsubtotal'])) {
			$data['nsubtotal'] = $input['nsubtotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nsubtotal'] = $purchase_order_info['nsubtotal'];
		}
        
		if (isset($input['ndeposittotal'])) {
			$data['ndeposittotal'] = $input['ndeposittotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndeposittotal'] = $purchase_order_info['ndeposittotal'];
		}
		
		if (isset($input['nfuelcharge'])) {
			$data['nfuelcharge'] = $input['nfuelcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfuelcharge'] = $purchase_order_info['nfuelcharge'];
		}
		
		if (isset($input['ndeliverycharge'])) {
			$data['ndeliverycharge'] = $input['ndeliverycharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndeliverycharge'] = $purchase_order_info['ndeliverycharge'];
		}
        
		if (isset($input['nreturntotal'])) {
			$data['nreturntotal'] = $input['nreturntotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nreturntotal'] = $purchase_order_info['nreturntotal'];
		}
        
		if (isset($input['vinvoiceno'])) {
			$data['vinvoiceno'] = $input['vinvoiceno'];
		} elseif (!empty($purchase_order_info)) {
			$data['vinvoiceno'] = $purchase_order_info['vinvoiceno'];
		}
        
		if (isset($input['ndiscountamt'])) {
			$data['ndiscountamt'] = $input['ndiscountamt'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndiscountamt'] = $purchase_order_info['ndiscountamt'];
		}
        
		if (isset($input['nripsamt'])) {
			$data['nripsamt'] = $input['nripsamt'];
		} elseif (!empty($purchase_order_info)) {
			$data['nripsamt'] = $purchase_order_info['nripsamt'];
		}
        
		if (!empty($purchase_order_info)) {
			$data['items'] = $purchase_order_info['items'];
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
        // $data['store'] = $this->model_api_purchase_order->getStore();
        
        $data['sizes'] = Size::orderBy('vsize', 'ASC')->get()->toArray();
        
        $data['departments'] = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        
		return $data;
	}
	
	protected function getFormSalesHistory($input)
	{
		$error_warning = session()->get('warning');
        if (isset($error_warning)) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        
		if (!isset($input['ipoid'])) {
			$data['action'] = url('/PurchaseOrder/add_manual_post');
			
		} else {
			$data['action'] = url('/PurchaseOrder/edit_post?ipoid=' . $input['ipoid']);
		}
        
        $data['cancel'] = url('/PurchaseOrder');
            
        $data['redirect_edit_url'] = url('/PurchaseOrder/redirect_edit_sales_history');
		$data['get_vendor'] = url('/PurchaseOrder/get_vendor');
		$data['get_search_items'] = url('/PurchaseOrder/get_search_items');
		$data['check_invoice'] = url('/PurchaseOrder/check_invoice');
		$data['purchase_order_list'] = url('/PurchaseOrder');
		$data['delete_purchase_order_item'] = url('/PurchaseOrder/delete_purchase_order_item');
		$data['save_receive_item'] = url('/PurchaseOrder/save_receive_item');
		$data['add_purchase_order_item'] = url('/PurchaseOrder/add_purchase_order_item');

		$data['get_search_item_history'] = url('/PurchaseOrder/get_search_item_history');
		$data['search_vendor_item_code'] = url('/PurchaseOrder/search_vendor_item_code');
		$data['get_item_history'] = url('/PurchaseOrder/get_item_history');
		$data['get_item_history_date'] = url('/PurchaseOrder/get_item_history_date');
		$data['get_vendor_item'] = url('/PurchaseOrder/get_vendor_item');
		$data['get_vendor_item_code_data'] = url('/PurchaseOrder/get_vendor_item_code_data');
        
		$data['check_warehouse_invoice'] = url('administration/transfer/check_invoice');
		
		$data['get_purchase_history'] = url('/PurchaseOrder/get_purchase_history');
		$data['export_as_pdf'] = url('/PurchaseOrder/export_as_pdf');
		$data['export_as_csv'] = url('/PurchaseOrder/export_as_csv');
		$data['export_as_email'] = url('/PurchaseOrder/export_as_email');
		$data['export_as_excel'] = url('/PurchaseOrder/export_as_excel');
        
		$data['get_search_item'] = url('administration/new_purchase_order/get_search_item');
        
        if (isset($input['ipoid'])) {
            
            $PurchaseOrder = new PurchaseOrder;
			$purchase_order_info = $PurchaseOrder->getPurchaseOrder($input['ipoid']);
			
			session()->put('purchase_order_info', $purchase_order_info);
			    
			$data['ipoid'] = $input['ipoid'];
		}
		
		$data['sid'] = session()->get('sid');
        
		if (isset($input['vponumber'])) {
			$data['vponumber'] = $input['vponumber'];
		} elseif (!empty($purchase_order_info)) {
			$data['vponumber'] = $purchase_order_info['vponumber'];
		} else {
			$temp_vponumber = PurchaseOrder::orderBy('ipoid', 'desc')->first();
            if( !empty($temp_vponumber) && isset($temp_vponumber->ipoid)){
				$data['vponumber'] = str_pad($temp_vponumber->ipoid+1,9,"0",STR_PAD_LEFT);
			}else{
				$data['vponumber'] = str_pad(1,9,"0",STR_PAD_LEFT);
			}
			
		}
		
		if (isset($input['nnettotal'])) {
			$data['nnettotal'] = $input['nnettotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nnettotal'] = $purchase_order_info['nnettotal'];
		}

		if (isset($input['ntaxtotal'])) {
			$data['ntaxtotal'] = $input['ntaxtotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['ntaxtotal'] = $purchase_order_info['ntaxtotal'];
		}

		if (isset($input['dcreatedate'])) {
			$data['dcreatedate'] = $input['dcreatedate'];
		} elseif (!empty($purchase_order_info)) {
			$data['dcreatedate'] = $purchase_order_info['dcreatedate'];
		}

		if (isset($input['dreceiveddate'])) {
			$data['dreceiveddate'] = $input['dreceiveddate'];
		} elseif (!empty($purchase_order_info)) {
			$data['dreceiveddate'] = $purchase_order_info['dreceiveddate'];
		}

		if (isset($input['estatus'])) {
			$data['estatus'] = $input['estatus'];
		} elseif (!empty($purchase_order_info)) {
			$data['estatus'] = $purchase_order_info['estatus'];
		}

		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfreightcharge'] = $purchase_order_info['nfreightcharge'];
		}

		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfreightcharge'] = $purchase_order_info['nfreightcharge'];
		}

		if (isset($input['vordertitle'])) {
			$data['vordertitle'] = $input['vordertitle'];
		} elseif (!empty($purchase_order_info)) {
			$data['vordertitle'] = $purchase_order_info['vordertitle'];
		}

		if (isset($input['vorderby'])) {
			$data['vorderby'] = $input['vorderby'];
		} elseif (!empty($purchase_order_info)) {
			$data['vorderby'] = $purchase_order_info['vorderby'];
		}

		if (isset($input['vconfirmby'])) {
			$data['vconfirmby'] = $input['vconfirmby'];
		} elseif (!empty($purchase_order_info)) {
			$data['vconfirmby'] = $purchase_order_info['vconfirmby'];
		}

		if (isset($input['vnotes'])) {
			$data['vnotes'] = $input['vnotes'];
		} elseif (!empty($purchase_order_info)) {
			$data['vnotes'] = $purchase_order_info['vnotes'];
		}

		if (isset($input['vvendorid'])) {
			$data['vvendorid'] = $input['vvendorid'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorid'] = $purchase_order_info['vvendorid'];
		}else{
			$data['vvendorid'] = "";
		}

		if (isset($input['vvendorname'])) {
			$data['vvendorname'] = $input['vvendorname'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorname'] = $purchase_order_info['vvendorname'];
		}else{
		    $data['vvendorname'] = '';
		}

		if (isset($input['vvendoraddress1'])) {
			$data['vvendoraddress1'] = $input['vvendoraddress1'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendoraddress1'] = $purchase_order_info['vvendoraddress1'];
		}

		if (isset($input['vvendoraddress2'])) {
			$data['vvendoraddress2'] = $input['vvendoraddress2'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendoraddress2'] = $purchase_order_info['vvendoraddress2'];
		}

		if (isset($input['vvendorstate'])) {
			$data['vvendorstate'] = $input['vvendorstate'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorstate'] = $purchase_order_info['vvendorstate'];
		}

		if (isset($input['vvendorzip'])) {
			$data['vvendorzip'] = $input['vvendorzip'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorzip'] = $purchase_order_info['vvendorzip'];
		}

		if (isset($input['vvendorphone'])) {
			$data['vvendorphone'] = $input['vvendorphone'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorphone'] = $purchase_order_info['vvendorphone'];
		}

		if (isset($input['vshipvia'])) {
			$data['vshipvia'] = $input['vshipvia'];
		} elseif (!empty($purchase_order_info)) {
			$data['vshipvia'] = $purchase_order_info['vshipvia'];
		}

		if (isset($input['nrectotal'])) {
			$data['nrectotal'] = $input['nrectotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nrectotal'] = $purchase_order_info['nrectotal'];
		}

		if (isset($input['nsubtotal'])) {
			$data['nsubtotal'] = $input['nsubtotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nsubtotal'] = $purchase_order_info['nsubtotal'];
		}

		if (isset($input['ndeposittotal'])) {
			$data['ndeposittotal'] = $input['ndeposittotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndeposittotal'] = $purchase_order_info['ndeposittotal'];
		}
		
		if (isset($input['nfuelcharge'])) {
			$data['nfuelcharge'] = $input['nfuelcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfuelcharge'] = $purchase_order_info['nfuelcharge'];
		}
		
		if (isset($input['ndeliverycharge'])) {
			$data['ndeliverycharge'] = $input['ndeliverycharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndeliverycharge'] = $purchase_order_info['ndeliverycharge'];
		}

		if (isset($input['nreturntotal'])) {
			$data['nreturntotal'] = $input['nreturntotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nreturntotal'] = $purchase_order_info['nreturntotal'];
		}

		if (isset($input['vinvoiceno'])) {
			$data['vinvoiceno'] = $input['vinvoiceno'];
		} elseif (!empty($purchase_order_info)) {
			$data['vinvoiceno'] = $purchase_order_info['vinvoiceno'];
		}

		if (isset($input['ndiscountamt'])) {
			$data['ndiscountamt'] = $input['ndiscountamt'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndiscountamt'] = $purchase_order_info['ndiscountamt'];
		}

		if (isset($input['nripsamt'])) {
			$data['nripsamt'] = $input['nripsamt'];
		} elseif (!empty($purchase_order_info)) {
			$data['nripsamt'] = $purchase_order_info['nripsamt'];
		}

		if (!empty($purchase_order_info)) {
			$data['items'] = $purchase_order_info['items'];
		}else{
			$data['items'] = array();
		}

		$data['items_id'] = array();

		if(count($data['items']) > 0){
			foreach ($data['items'] as $k => $v) {
				array_push($data['items_id'], $v['vitemid']);
			}
		}
		
		
        // ==================================== Drop down for departments ============================================
        
        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
                
        $data['departments'] = $departments;
        
        
        // ==================================== Drop down for vendors ============================================
        
        $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
        
        $data['vendors'] = $suppliers;
		$data['store'] = Store::All()->toArray();
        
        
        if(isset($input['select_by'])){
            $data['selected_select_by'] = $input['select_by'];
        }else{
            $data['selected_select_by'] = '';
        }
        
        if(isset($input['input_year'])){
            $data['year'] = $input['input_year'];
        }else{
            $data['year'] = '';
        }
        
        $data['select_by_list'] = array(
                        'w' => 'By Week',
                        'm' => 'By Month',
                        'y' => 'By Year',
                        'c' => 'Custom'
                     );
            
       
        $data['get_categories_url'] = url('/PurchaseOrder/sales_history/get_categories');
        $data['get_subcategories_url'] = url('/PurchaseOrder/sales_history/get_subcategories');
        
        $data['get_skus_url'] = url('/PurchaseOrder/sales_history/get_skus');
        $data['get_item_names_url'] = url('/PurchaseOrder/sales_history/get_item_names');
        $data['get_barcodes_url'] = url('/PurchaseOrder/sales_history/get_barcodes');
        $data['get_sizes_url'] = url('/PurchaseOrder/sales_history/get_sizes');
        
        $data['post_po_sales_history'] = url('/PurchaseOrder/post_po_sales_history');
        
		return $data;
	}
	
	protected function getFormParLevel($input)
	{
		$error_warning = session()->get('warning');
        if (isset($error_warning)) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        
		if (!isset($input['ipoid'])) {
			$data['action'] = url('/PurchaseOrder/add_manual_post');
			
		} else {
			$data['action'] = url('/PurchaseOrder/edit_post?ipoid=' . $input['ipoid']);
		}
        
		$data['cancel'] = url('/PurchaseOrder');
            
        $data['redirect_edit_url'] = url('/PurchaseOrder/redirect_edit_par_level');
		$data['get_vendor'] = url('/PurchaseOrder/get_vendor');
		$data['get_search_items'] = url('/PurchaseOrder/get_search_items');
		$data['check_invoice'] = url('/PurchaseOrder/check_invoice');
		$data['purchase_order_list'] = url('/PurchaseOrder');
		$data['delete_purchase_order_item'] = url('/PurchaseOrder/delete_purchase_order_item');
		$data['save_receive_item'] = url('/PurchaseOrder/save_receive_item');
		$data['add_purchase_order_item'] = url('/PurchaseOrder/add_purchase_order_item');

		$data['get_search_item_history'] = url('/PurchaseOrder/get_search_item_history');
		$data['search_vendor_item_code'] = url('/PurchaseOrder/search_vendor_item_code');
		$data['get_item_history'] = url('/PurchaseOrder/get_item_history');
		$data['get_item_history_date'] = url('/PurchaseOrder/get_item_history_date');
		$data['get_vendor_item'] = url('/PurchaseOrder/get_vendor_item');
		$data['get_vendor_item_code_data'] = url('/PurchaseOrder/get_vendor_item_code_data');
        
		$data['check_warehouse_invoice'] = url('administration/transfer/check_invoice');
		
		$data['get_purchase_history'] = url('/PurchaseOrder/get_purchase_history');
		$data['export_as_pdf'] = url('/PurchaseOrder/export_as_pdf');
		$data['export_as_csv'] = url('/PurchaseOrder/export_as_csv');
		$data['export_as_email'] = url('/PurchaseOrder/export_as_email');
		$data['export_as_excel'] = url('/PurchaseOrder/export_as_excel');
        
		$data['get_search_item'] = url('administration/new_purchase_order/get_search_item');
        
		if (isset($input['ipoid'])) {
            
            $PurchaseOrder = new PurchaseOrder;
			$purchase_order_info = $PurchaseOrder->getPurchaseOrder($input['ipoid']);
			
			session()->put('purchase_order_info', $purchase_order_info);
			    
			$data['ipoid'] = $input['ipoid'];
		}
		
		$data['sid'] = session()->get('sid');
        
        
		if (isset($input['vponumber'])) {
			$data['vponumber'] = $input['vponumber'];
		} elseif (!empty($purchase_order_info)) {
			$data['vponumber'] = $purchase_order_info['vponumber'];
		} else {
			$temp_vponumber = PurchaseOrder::orderBy('ipoid', 'desc')->first();
            if( !empty($temp_vponumber) && isset($temp_vponumber->ipoid)){
				$data['vponumber'] = str_pad($temp_vponumber->ipoid+1,9,"0",STR_PAD_LEFT);
			}else{
				$data['vponumber'] = str_pad(1,9,"0",STR_PAD_LEFT);
			}
			
		}
		
		if (isset($input['nnettotal'])) {
			$data['nnettotal'] = $input['nnettotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nnettotal'] = $purchase_order_info['nnettotal'];
		}

		if (isset($input['ntaxtotal'])) {
			$data['ntaxtotal'] = $input['ntaxtotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['ntaxtotal'] = $purchase_order_info['ntaxtotal'];
		}

		if (isset($input['dcreatedate'])) {
			$data['dcreatedate'] = $input['dcreatedate'];
		} elseif (!empty($purchase_order_info)) {
			$data['dcreatedate'] = $purchase_order_info['dcreatedate'];
		}

		if (isset($input['dreceiveddate'])) {
			$data['dreceiveddate'] = $input['dreceiveddate'];
		} elseif (!empty($purchase_order_info)) {
			$data['dreceiveddate'] = $purchase_order_info['dreceiveddate'];
		}

		if (isset($input['estatus'])) {
			$data['estatus'] = $input['estatus'];
		} elseif (!empty($purchase_order_info)) {
			$data['estatus'] = $purchase_order_info['estatus'];
		}

		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfreightcharge'] = $purchase_order_info['nfreightcharge'];
		}

		if (isset($input['nfreightcharge'])) {
			$data['nfreightcharge'] = $input['nfreightcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfreightcharge'] = $purchase_order_info['nfreightcharge'];
		}

		if (isset($input['vordertitle'])) {
			$data['vordertitle'] = $input['vordertitle'];
		} elseif (!empty($purchase_order_info)) {
			$data['vordertitle'] = $purchase_order_info['vordertitle'];
		}

		if (isset($input['vorderby'])) {
			$data['vorderby'] = $input['vorderby'];
		} elseif (!empty($purchase_order_info)) {
			$data['vorderby'] = $purchase_order_info['vorderby'];
		}

		if (isset($input['vconfirmby'])) {
			$data['vconfirmby'] = $input['vconfirmby'];
		} elseif (!empty($purchase_order_info)) {
			$data['vconfirmby'] = $purchase_order_info['vconfirmby'];
		}

		if (isset($input['vnotes'])) {
			$data['vnotes'] = $input['vnotes'];
		} elseif (!empty($purchase_order_info)) {
			$data['vnotes'] = $purchase_order_info['vnotes'];
		}

		if (isset($input['vvendorid'])) {
			$data['vvendorid'] = $input['vvendorid'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorid'] = $purchase_order_info['vvendorid'];
		}else{
			$data['vvendorid'] = "";
		}

		if (isset($input['vvendorname'])) {
			$data['vvendorname'] = $input['vvendorname'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorname'] = $purchase_order_info['vvendorname'];
		}else{
			$data['vvendorname'] = "";
		}

		if (isset($input['vvendoraddress1'])) {
			$data['vvendoraddress1'] = $input['vvendoraddress1'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendoraddress1'] = $purchase_order_info['vvendoraddress1'];
		}

		if (isset($input['vvendoraddress2'])) {
			$data['vvendoraddress2'] = $input['vvendoraddress2'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendoraddress2'] = $purchase_order_info['vvendoraddress2'];
		}

		if (isset($input['vvendorstate'])) {
			$data['vvendorstate'] = $input['vvendorstate'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorstate'] = $purchase_order_info['vvendorstate'];
		}

		if (isset($input['vvendorzip'])) {
			$data['vvendorzip'] = $input['vvendorzip'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorzip'] = $purchase_order_info['vvendorzip'];
		}

		if (isset($input['vvendorphone'])) {
			$data['vvendorphone'] = $input['vvendorphone'];
		} elseif (!empty($purchase_order_info)) {
			$data['vvendorphone'] = $purchase_order_info['vvendorphone'];
		}

		if (isset($input['vshipvia'])) {
			$data['vshipvia'] = $input['vshipvia'];
		} elseif (!empty($purchase_order_info)) {
			$data['vshipvia'] = $purchase_order_info['vshipvia'];
		}

		if (isset($input['nrectotal'])) {
			$data['nrectotal'] = $input['nrectotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nrectotal'] = $purchase_order_info['nrectotal'];
		}

		if (isset($input['nsubtotal'])) {
			$data['nsubtotal'] = $input['nsubtotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nsubtotal'] = $purchase_order_info['nsubtotal'];
		}

		if (isset($input['ndeposittotal'])) {
			$data['ndeposittotal'] = $input['ndeposittotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndeposittotal'] = $purchase_order_info['ndeposittotal'];
		}
		
		if (isset($input['nfuelcharge'])) {
			$data['nfuelcharge'] = $input['nfuelcharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['nfuelcharge'] = $purchase_order_info['nfuelcharge'];
		}
		
		if (isset($input['ndeliverycharge'])) {
			$data['ndeliverycharge'] = $input['ndeliverycharge'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndeliverycharge'] = $purchase_order_info['ndeliverycharge'];
		}

		if (isset($input['nreturntotal'])) {
			$data['nreturntotal'] = $input['nreturntotal'];
		} elseif (!empty($purchase_order_info)) {
			$data['nreturntotal'] = $purchase_order_info['nreturntotal'];
		}

		if (isset($input['vinvoiceno'])) {
			$data['vinvoiceno'] = $input['vinvoiceno'];
		} elseif (!empty($purchase_order_info)) {
			$data['vinvoiceno'] = $purchase_order_info['vinvoiceno'];
		}

		if (isset($input['ndiscountamt'])) {
			$data['ndiscountamt'] = $input['ndiscountamt'];
		} elseif (!empty($purchase_order_info)) {
			$data['ndiscountamt'] = $purchase_order_info['ndiscountamt'];
		}

		if (isset($input['nripsamt'])) {
			$data['nripsamt'] = $input['nripsamt'];
		} elseif (!empty($purchase_order_info)) {
			$data['nripsamt'] = $purchase_order_info['nripsamt'];
		}

		if (!empty($purchase_order_info)) {
			$data['items'] = $purchase_order_info['items'];
		}else{
			$data['items'] = array();
		}

		$data['items_id'] = array();

		if(count($data['items']) > 0){
			foreach ($data['items'] as $k => $v) {
				array_push($data['items_id'], $v['vitemid']);
			}
		}
				
        // ==================================== Drop down for departments ============================================
        
        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        
        $data['departments'] = $departments;
              
        
        // ==================================== Drop down for vendors ============================================
        
        $suppliers = Supplier::orderBy('vcompanyname', 'ASC')->get()->toArray();
        
        $data['vendors'] = $suppliers;
		$data['store'] = Store::All()->toArray();
        
        
        if(isset($input['select_by'])){
            $data['selected_select_by'] = $input['select_by'];
        }else{
            $data['selected_select_by'] = '';
        }
        
        $data['select_by_list'] = array(
                        'w' => 'By Week',
                        'm' => 'By Month',
                        'y' => 'By Year',
                        'c' => 'Custom'
                     );

		$data['get_categories_url'] = url('/PurchaseOrder/sales_history/get_categories');
        $data['get_subcategories_url'] = url('/PurchaseOrder/sales_history/get_subcategories');
        
        $data['get_skus_url'] = url('/PurchaseOrder/sales_history/get_skus');
        $data['get_item_names_url'] = url('/PurchaseOrder/sales_history/get_item_names');
        $data['get_barcodes_url'] = url('/PurchaseOrder/sales_history/get_barcodes');
        $data['get_sizes_url'] = url('/PurchaseOrder/sales_history/get_sizes'); 
		$data['search_items'] = url('/PurchaseOrder/search');
		
		$data['post_po_sales_history'] = url('/PurchaseOrder/post_po_sales_history');
        
		return $data;
	}
	
	
	public function search(Request $request) 
	{
        $input = $request->all();
        $new_database = session()->get('new_database');
        if($new_database === false){
            // echo 'Old database'; die;
            // ======================================================= OLD DATABASE ===========================================================
            $return = array();
            
            if(isset($input['term']) && !empty($input['term'])){
                
                $PurchaseOrder = new PurchaseOrder;
                $datas = $PurchaseOrder->getItemsSearchResult($input['term']);
                
                foreach ($datas as $key => $value) {
                    $temp = array();
                    $temp['iitemid'] = $value['iitemid'];
                    $temp['vitemname'] = $value['vitemname'];
                    $return[] = $temp;
                }
            }
            return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
            return;
        }
        
        $return = $datas = array();
        
        $sort = "mi.LastUpdate DESC";
        if(isset($input['sort_items']) && !empty(trim($input['sort_items'])))
        {
            $sort_by = trim($input['sort_items']);
            $sort = "mi.vitemname $sort_by";
        }elseif(isset($input['order'][0]) && !empty($input['order'][0])){
            
            if($input['order'][0]['column'] == 1 && !empty($input['order'][0]['dir'])){
                // dd($input['order'][0]['dir']);
                $sort_by = $input['order'][0]['dir'];
                $sort = "mi.vitemname $sort_by";
                // dd($sort);
            }
        }else{
            $sort = "mi.LastUpdate DESC";
        }
        
        $show_condition = "WHERE mi.estatus='Active' AND mi.iqtyonhand < mi.ireorderpoint";
        if(isset($input['show_items']) && !empty(trim($input['show_items'])))
        {
            $show_items = trim($input['show_items']);
            if($show_items == "All")
            {
                $show_condition = "WHERE mi.estatus !='' AND imi.qtyonhand < mi.ireorderpoint";
            }
            else
            {
                $show_condition = "WHERE mi.estatus='".$show_items."' AND mi.iqtyonhand < mi.ireorderpoint";
            }
            
        }
        $input = $input;
        
        // print_r($input['columns']); die;
        
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
            else if($value["data"] == "category")
            {
                $search_items['vcategoryname'] = $value['search']['value'];
            }
            else if($value["data"] == "department")
            {
                $search_items['vdepartmentname'] = $value['search']['value'];
            }
            else if($value["data"] == "sub_category")
            {
                $search_items['sub_category_name'] = $value['search']['value'];
                
                //print_r($search_items['vcompanyname']);die;
            }else if($value["data"] == "vendor")
            {
                $search_items['vendor'] = $value['search']['value'];
                
            }
        }
        
        if(empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && empty(trim($search_items['vendor'])) && empty(trim($search_items['vcategoryname'])) &&  empty(trim($search_items['vdepartmentname'])))
        {
            $limit = 10;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length'];
            
            // $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vitemtype, md.vdepartmentname department, mc.vcategoryname category,
            // msub.subcat_name sub_category,mi.nsaleprice, mi.dunitprice price, mi.dcostprice cost, mi.ireorderpoint par, mi.iqtyonhand qoh, 
            // mi.LastUpdate,msupp.vcompanyname vendor, case isparentchild when 0 then mi.vitemname 
            // when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME FROM mst_item as mi 
            // LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
            // LEFT JOIN mst_subcategory as msub ON(mi.subcat_id = msub.subcat_id)
            // LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
            // LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) $show_condition ORDER BY $sort LIMIT ". $start_from.", ".$limit;
            
            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vitemtype, md.vdepartmentname department, mc.vcategoryname category,
            msub.subcat_name sub_category,mi.nsaleprice, mi.dunitprice price, mi.dcostprice cost, mi.ireorderpoint par, mi.iqtyonhand qoh,mi.npack,
            case 
            mi.isparentchild when 1 then p.iqtyonhand 
            end as PQOH,
            mi.LastUpdate,msupp.vcompanyname vendor, case mi.isparentchild when 0 then mi.vitemname 
            when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME FROM mst_item as mi 
            LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
            LEFT JOIN mst_subcategory as msub ON(mi.subcat_id = msub.subcat_id)
            LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
            LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
            LEFT JOIN mst_item p ON mi.parentid = p.iitemid $show_condition ORDER BY $sort LIMIT ". $start_from.", ".$limit;
            
           
            $query = DB::connection('mysql_dynamic')->select($select_query);
            
            
            $count_query = "SELECT COUNT(DISTINCT(iitemid)) as total_rows FROM mst_item mi $show_condition";
            // dd($count_query);
            $run_count_query = DB::connection('mysql_dynamic')->select($count_query);
            
            $count_records = $count_total = $run_count_query[0]->total_rows;
        }
        else
        {
            
            $limit = 10;
            
            $start_from = ($input['start']);
            
            $offset = $input['start']+$input['length']; 
            $condition = "";
            if(isset($search_items['vitemname']) && !empty(trim($search_items['vitemname']))){
                $search = ($search_items['vitemname']);
                // $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
                $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search' AND mi.iqtyonhand < mi.ireorderpoint");
                
                //dd($parentid_data=$parentid[0]->parentid);
                 if(isset($parentid) && !empty($parentid)){
                 $parentid_data=$parentid[0]->parentid;
                 $child_data=$parentid[0]->iitemid;
                 $isparentchild=$parentid[0]->isparentchild;
                 $condition .= " AND mi.vitemname = '$search'  OR mi.iitemid=$parentid_data OR mi.parentid=$child_data AND mi.iqtyonhand < mi.ireorderpoint";
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
                $search = ($search_items['vbarcode']);
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%' OR mia.valiassku LIKE '%" . $search . "%'";

            }
            
            if(isset($search_items['vdepartmentname']) && !empty(trim($search_items['vdepartmentname']))){
                $search = ($search_items['vdepartmentname']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vdepcode ='".$search."'";
                }
                
            }
            
            if(isset($search_items['vcategoryname']) && !empty($search_items['vcategoryname'])){
                $search = ($search_items['vcategoryname']);
                if($search != 'all')
                {
                    $condition .= " AND mi.vcategorycode ='".$search."'";
                }
                
            }
            if(isset($search_items['sub_category_name']) && !empty($search_items['sub_category_name'])){
                $search = ($search_items['sub_category_name']);
                if($search != 'all')
                {
                    $condition .= " AND mi.subcat_id =  '" . $search . "'";
                    //echo $condition;die;
                }
            
            }
            
            if(isset($search_items['vendor']) && !empty($search_items['vendor'])){
                $search = ($search_items['vendor']);
                if($search != 'all')
                {
                    $condition .= " AND msupp.vsuppliercode =  '" . $search . "'";
                    //echo $condition;die;
                }
            
            }
            
             //$condition .= "  AND mi.iqtyonhand < mi.ireorderpoint"; 
            
            // $select_query = "SELECT DISTINCT(mi.iitemid) iitemid,mi.vbarcode vbarcode,mi.vitemname vitemname, mi.vitemtype, md.vdepartmentname department, mc.vcategoryname category, 
            // msub.subcat_name sub_category, mi.dunitprice price, mi.dcostprice cost, mi.ireorderpoint par, mi.iqtyonhand qoh, mi.LastUpdate,msupp.vcompanyname vendor,
            // case isparentchild when 0 then mi.vitemname when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME 
            // FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
            // LEFT JOIN mst_subcategory as msub ON(mi.subcat_id = msub.subcat_id) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
            // LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)  LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
            // $show_condition "." $condition ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
            $select_query = "SELECT DISTINCT(mi.iitemid) iitemid,mi.vbarcode vbarcode,mi.vitemname vitemname, mi.vitemtype, md.vdepartmentname department, mc.vcategoryname category, 
            msub.subcat_name sub_category, mi.dunitprice price, mi.dcostprice cost, mi.ireorderpoint par, mi.IQTYONHAND as qoh,mi.npack,
            case 
            mi.isparentchild when 1 then p.iqtyonhand 
            end as PQOH,
            mi.LastUpdate,msupp.vcompanyname vendor,
            case mi.isparentchild when 0 then mi.vitemname when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME 
            FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) 
            LEFT JOIN mst_subcategory as msub ON(mi.subcat_id = msub.subcat_id) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
            LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
            LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
            LEFT JOIN mst_item p ON mi.parentid = p.iitemid
            $show_condition "." $condition AND mi.iqtyonhand < mi.ireorderpoint ORDER BY $sort LIMIT ". $input['start'].", ".$limit;
            //echo $select_query; die;
           
            $query = DB::connection('mysql_dynamic')->select($select_query);
            
            // print_r($query); die;
            
            
            
            
            $count_select_query = "SELECT COUNT(DISTINCT(mi.iitemid)) as count FROM mst_item as mi 
            LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
            LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
            LEFT JOIN mst_subcategory as msub ON(mi.subcat_id = msub.subcat_id)
            LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) 
            LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
            LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) 
            LEFT JOIN mst_item p ON mi.parentid = p.iitemid
            $show_condition "." $condition";
                
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);
            
            $count_records = $count_total = (int)$count_query[0]->count;
        }
        
        $search = $input['search']['value'];
        


        
        $itemListings = WebAdminSetting::where('variablename', 'ItemListing')->get('variablevalue')->toArray();
        
        $data['itemListings'] = array();
        
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
                $temp['vitemname'] = $value['VITEMNAME'];
                $temp['vitemtype'] = $value['vitemtype'];
                $temp['vbarcode'] = $value['vbarcode'];
                /*if(count($itemListings) > 0){
                    foreach($itemListings as $m => $v){
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
                        }else{
                            $temp[$m] = isset($value[$m])?$value[$m]:'';
                        }
                    }
                }*/
                
                
                $temp['vendor'] = $value['vendor'];
                $temp['department'] = $value['department'];
                $temp['category'] = $value['category'];
                $temp['sub_category'] = $value['sub_category'];
                $temp['price'] = $value['price'];
                $temp['cost'] = $value['cost'];
                $temp['par'] = $value['par'];
                //$temp['qoh'] = $value['qoh'];
                   if($value['PQOH'] !='NULL' && isset($value['PQOH'])){
                 $temp['qoh'] = ($value['PQOH'] % $value['npack']);   
                }
                else{
                    $quotient = (int)($value['qoh'] / $value['npack']);
                    $remainder = $value['qoh'] % $value['npack'];
                    $qty_on_hand = ''.$quotient .' ('.$remainder.')';
                    
                    $temp['qoh'] = $qty_on_hand;
                    //$temp['iqtyonhand'] = $value['iqtyonhand'];
                }
                $temp['sug_qty'] = $value['par'] - $value['qoh'];
                
                $datas[] = $temp;
            }
        }
        
        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $datas;
        
        // print_r($return); die;
        return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
        
    }
    
    public function edit_post(Request $request) 
    {
        
		$input = $request->all();
        
		if ($request->isMethod('post')){
            
            $PurchaseOrder = new PurchaseOrder;
			$data_response = $PurchaseOrder->editPurchaseOrder($input,null,'PO');
            
			if(array_key_exists("success",$data_response)){
				
				$ipoid = (int)($input['ipoid']);
				$purchase_order_info = $PurchaseOrder->getPurchaseOrder($ipoid);
                
                return response(json_encode($data_response), 200)
                    ->header('Content-Type', 'application/json');
                  
			}else{
				return response(json_encode($data_response), 401)
                    ->header('Content-Type', 'application/json');
			}
		}

		
	}
	
	public function get_purchase_history(Request $request) 
	{
	    $input = $request->all();
		$json = array();
        
		if (isset($input['vitemcode'])) {
		    
		    $query = 'SELECT tpd.nunitcost as cost, tpd.nordunitprice as selling_price, tpd.nordextprice as total_cost, 
                    
                    (((tpd.nordunitprice - tpd.nunitcost)/tpd.nordunitprice)*100) as profit_percent, 
                    
                    tp.estatus as status  FROM trn_purchaseorderdetail tpd
                    
                    LEFT JOIN trn_purchaseorder tp ON tpd.ipoid = tp.ipoid
                    
                    WHERE tpd.vbarcode = "'.$input['vitemcode'].'" AND tp.estatus = "Close" ORDER BY tp.dreceiveddate DESC';
                    
            $data = DB::connection('mysql_dynamic')->select($query);
            dd($data);
			$json = $this->model_api_purchase_order->getPurchaseData($input['vitemcode']);
            
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function get_search_item_history(Request $request)
	{
		$input = $request->all();
		$json = array();
		
        //removed ivendorid on 6th April 2020: To search items regardless of selected vendor
        if (isset($input) && $request->isMethod('post')) {		    
			
	        $pre_items_id = json_decode(file_get_contents('php://input'), true);
            
            // comment out the following line to get search results with vendor filter			
            // $items = $this->model_api_purchase_order->getSearchItemHistory($input['search_item'],$input['ivendorid'],$pre_items_id);
            // new search result function without vendor filter 			
			
			$PurchaseOrder = new PurchaseOrder;
			$items = $PurchaseOrder->getSearchItemHistoryAll($input,$input['ivendorid'],$pre_items_id);
			$json['items'] = $items;
		}
            
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
	public function get_item_history(Request $request)
	{
		$input = $request->all();
        
		$json = array();
        
		if (isset($input['iitemid']) && isset($input['radio_search_by']) && isset($input['vitemcode'])) {
                
            $PurchaseOrder = new PurchaseOrder;
			$json = $PurchaseOrder->getSearchItemData($input['iitemid'],$input['radio_search_by'],$input['vitemcode'],null,null);
            
		}
		
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}

	public function get_item_history_date(Request $request) 
	{
		$input = $request->all();
        
		$json = array();
        
		if (isset($input['iitemid']) && isset($input['start_date']) && isset($input['end_date']) && isset($input['vitemcode'])) {
			
			$PurchaseOrder = new PurchaseOrder;
			$json = $PurchaseOrder->getSearchItemData($input['iitemid'],null,$input['vitemcode'],$input['start_date'],$input['end_date']);
            
		}
		
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
// 	public function search_vendor_item_code(Request $request) 
// 	{
	    
// 		$input = $request->all();
		
// 		$json = array('items' => array());
        
// 		if (isset($input['search_item']) && $input['ivendorid']) {
// 			$pre_items_id = json_decode(file_get_contents('php://input'), true);
			
// 			$PurchaseOrder = new PurchaseOrder;
// 			$items = $PurchaseOrder->getSearchVendorItemCode($input['search_item'],$input['ivendorid'],$pre_items_id);
			
// 			$json['items'] = $items;
// 		}

// 		return response(json_encode($json), 200)
//                   ->header('Content-Type', 'application/json');
// 	}
	
	public function search_vendor_item_code(Request $request) 
	{
		$input = $request->all();
		
		$json = array('items' => array());
        

        // if (isset($input['ivendorid'])  && $request->isMethod('post') ){
		if (isset($input['search_item'])  && $request->isMethod('post') ){  
			$pre_items_id = json_decode(file_get_contents('php://input'), true);
			
			$PurchaseOrder = new PurchaseOrder;
			$items = $PurchaseOrder->getSearchVendorItemCode($input['search_item'],$input['ivendorid'],$pre_items_id);
			
			$json['items'] = $items;
		}

		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	public function add_purchase_order_item(Request $request) 
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
                        
                        $PurchaseOrder = new PurchaseOrder;
                        $json['items'][] = $PurchaseOrder->getSearchItem($item_id,$ivendorid);
                    }
				}
			}
		}
        
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
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
                
                $PurchaseOrder = new PurchaseOrder;
				$data = $PurchaseOrder->checkExistInvoice($temp_arr['invoice']);
                
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
	
	public function get_vendor(Request $request) 
	{
		ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 300);
        
        $input = $request->all();
		$json = array();
        
		if (isset($input['isupplierid'])) {
			$supplier = Supplier::where('isupplierid', $input['isupplierid'])->get()->toArray();
			$supplier = isset($supplier[0]) ? (array)$supplier[0] : [];
			$json['vendor'] = $supplier;
		}

		return response(json_encode($json))
            ->header('Content-Type', 'application/json');
	}
	
	public function delete(Request $request) 
	{
		$json =array();
		
		if ($request->isMethod('post')) {
            
			$temp_arr = json_decode(file_get_contents('php://input'), true);
            
            $PurchaseOrder = new PurchaseOrder;
			$data = $PurchaseOrder->deletePurchaseOrder($temp_arr);
            
            return response(json_encode($data))
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
	
	public function delete_purchase_order_item(Request $request)
	{
        
		if ($request->isMethod('post')) {
            
			$temp_arr = json_decode(file_get_contents('php://input'), true);
            
            $PurchaseOrder = new PurchaseOrder;
			$data = $PurchaseOrder->deleteItemPurchase($temp_arr);
            
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
	
	public function save_receive_item(Request $request) 
	{
		$json = array();
		
		if ($request->isMethod('post')) {
            
            $input = $request->all();
            
			if(isset($input['items']) && count($input['items']) > 0){
                
                $PurchaseOrder = new PurchaseOrder;
				$item_response = $PurchaseOrder->addSaveReceiveItem($input);
                
			}
			$json = $item_response;
		}
            
		if(array_key_exists("success",$json)){
			return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
		}else{
			return response(json_encode($json), 401)
                  ->header('Content-Type', 'application/json');
		}
        
	}
	
	public function export_as_pdf() {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $data['storename'] = session()->get('storename');
        $data['storeaddress'] = session()->get('storeaddress');
        $data['storephone'] = session()->get('storephone');
        $data['purchase_info'] =  session()->get('purchase_order_info');
        
        // $image = DIR_IMAGE.'/logoreport.jpg';
        $image = public_path('image/logoreport.jpg');
        $image_handle = fopen ($image, 'rb');
        $size=filesize ($image);
        $contents= fread ($image_handle, $size);
        fclose ($image_handle);
    
        $data['image'] = base64_encode($contents);        
        $data['heading_title'] = 'Purchase Order';

        $pdf = PDF::loadView('purchase_order.print_purchase_order_page', $data)->setPaper('a4', 'landscape');;
        
        unset($data);
        
        // $this->dompdf->loadHtml($html);
        // $this->dompdf->setPaper('A4', 'landscape');
        // $this->dompdf->render();
    
        // Output the generated PDF to Browser
        return $pdf->download('Purchase_Order.pdf'); 
    }
    
    public function export_as_csv(){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $purchase_info = session()->get('purchase_order_info');
        $data_row = '';
        
        // $data_row .= "Store Name:, ".session()->get['storename'].PHP_EOL;
        // $data_row .= "Store Address:, ".session()->get['storeaddress'].PHP_EOL;
        // $data_row .= "Store Phone:, ".session()->get['storephone'].PHP_EOL;
        
        $data_row .= "Vendor Name:, ".$purchase_info['vvendorname'].PHP_EOL;
        $data_row .= "Invoice Number:, ".$purchase_info['vrefnumber'].PHP_EOL;
        
        $data_row .= "Sl.No.,Vendor,SKU#,Item,Size,New Cost,Unit Per Case,Order By,Suggested Cost, Total Amt".PHP_EOL;
        
        $counter = 1;
        foreach($purchase_info['items'] as $item){
            $data_row .= $counter.','.$item['vendor_name'].','.$item['vbarcode'].','.$item['vitemname'].','.$item['vsize'].','.$item['new_costprice'].',';
            $data_row .= $item['npack'].','.$item['po_order_by'].','.$item['po_total_suggested_cost'].','.$item['nordextprice'].PHP_EOL;
            
            $counter++;
        }
              
        $data_row .= ",,,,,,,,Sub Total,".$purchase_info['nsubtotal'].PHP_EOL;
        $data_row .= ",,,,,,,,Tax(+),".$purchase_info['ntaxtotal'].PHP_EOL;
        $data_row .= ",,,,,,,,Freight(+),".$purchase_info['nfreightcharge'].PHP_EOL;
        $data_row .= ",,,,,,,,Deposit(+),".$purchase_info['ndeposittotal'].PHP_EOL;
        $data_row .= ",,,,,,,,Fuel(+),".$purchase_info['nfuelcharge'].PHP_EOL;
        $data_row .= ",,,,,,,,Delivery(+),".$purchase_info['ndeliverycharge'].PHP_EOL;
        $data_row .= ",,,,,,,,Return(-),".$purchase_info['nreturntotal'].PHP_EOL;
        $data_row .= ",,,,,,,,Discount(-),".$purchase_info['ndiscountamt'].PHP_EOL;
        $data_row .= ",,,,,,,,Rips(-),".$purchase_info['nripsamt'].PHP_EOL;
        $data_row .= ",,,,,,,,Net Total,".$purchase_info['nnettotal'].PHP_EOL;
        
        
        $file_name = 'purchase_order.csv';
        $file_path = storage_path("logs/purchaseOrder/purchase_order.csv");
        
        $myfile = fopen($file_path, "w");
        
        fwrite($myfile,$data_row);
        
        fclose($myfile);
        unset($data_row);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name));
        echo $content;
        exit;        
        
    }
    
    public function export_as_email(){
        // $to = session()->get('logged_email');
        $to = Auth::user()->vemail;
        $subject = "Purchase Order";
        
        $purchase_info = session()->get('purchase_order_info');
        
        $message = "<p><b>Vendor Name:</b>".$purchase_info['vvendorname']."</p>";
        $message .= "<p><b>Invoice Number:</b>".$purchase_info['vrefnumber']."</p>";
        
        $message .= '<table style="border-collapse: collapse;"><thead><tr><th style="border: 1px solid black;">Sl. No.</th><th style="border: 1px solid black;">Vendor</th><th style="border: 1px solid black;">SKU #</th><th style="border: 1px solid black;">Item</th><th style="border: 1px solid black;">Size</th><th style="border: 1px solid black;">New Cost</th><th style="border: 1px solid black;">Units/Case</th>';
        $message .= '<th style="border: 1px solid black;">Order By</th><th style="border: 1px solid black;">Suggested Cost</th><th style="border: 1px solid black;">Total Cost</th></tr></thead><tbody>';
        
        foreach($purchase_info['items'] as $k=>$item) {
            $message .= '<tr>';
            $message .= '<td style="border: 1px solid black;">'.++$k.'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['vendor_name'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['vbarcode'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['vitemname'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['vsize'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['new_costprice'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['npack'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['po_order_by'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['po_total_suggested_cost'].'</td>';
            $message .= '<td style="border: 1px solid black;">'.$item['nordextprice'].'</td>';
            $message .= '</tr>';
        }
        $message .= "<tr>
                    <td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td>
                    <td style='border: 1px solid black;' colspan='2'><b>Sub Total</b></td><td style='border: 1px solid black;'><b>{$purchase_info['nsubtotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Tax (+)</b></td><td><b>{$purchase_info['ntaxtotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Freight (+)</b></td><td><b>{$purchase_info['nfreightcharge']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Deposit (+)</b></td><td><b>{$purchase_info['ndeposittotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Fuel (+)</b></td><td><b>{$purchase_info['nfuelcharge']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Delivery (+)</b></td><td><b>{$purchase_info['ndeliverycharge']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Return (-)</b></td><td><b>{$purchase_info['nreturntotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Discount (-)</b></td><td><b>{$purchase_info['ndiscountamt']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Rips (-)</b></td><td><b>{$purchase_info['nripsamt']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Net Total</b></td><td><b>{$purchase_info['nnettotal']}</b></td>
                </tr>
            </tbody>
        </table>";
        
        
         
        $header = "From:mail@albertapayments.com \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";
         
        $retval = mail ($to,$subject,$message,$header);
        // $retval = mail ('amanpreet@aroha.co.in',$subject,$message,$header);
        
        $response = [];
        if( $retval == true ) {
            $response['status'] = 'true';
            $response['message'] = 'The purchase order has been sent successfully to your regsitered email id (<i>' . $to . '</i>).';
        }else {
            $response['status'] = 'false';
            $response['message'] = 'There seems to be problem while sending a mail. If this persists after several attempts please contact technical support.';
        }
        
        echo json_encode($response);
    }
    
    
    public function export_as_excel(){
        
        $purchase_info = session()->get('purchase_order_info');
        
        $data_row = '';
        
        $data_row .= "<p><b>Vendor Name:</b>".$purchase_info['vvendorname']."</p>";
        $data_row .= "<p><b>Invoice Number:</b>".$purchase_info['vrefnumber']."</p>";
        
        $data_row .= '<table style="border-collapse: collapse;"><thead><tr><th style="border: 1px solid black;">Sl. No.</th><th style="border: 1px solid black;">Vendor</th><th style="border: 1px solid black;">SKU #</th><th style="border: 1px solid black;">Item</th><th style="border: 1px solid black;">Size</th><th style="border: 1px solid black;">New Cost</th><th style="border: 1px solid black;">Units/Case</th>';
        $data_row .= '<th style="border: 1px solid black;">Order By</th><th style="border: 1px solid black;">Suggested Cost</th><th style="border: 1px solid black;">Total Cost</th></tr></thead><tbody>';
        
        foreach($purchase_info['items'] as $k=>$item) {
            $data_row .= '<tr>';
            $data_row .= '<td style="border: 1px solid black;">'.++$k.'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['vendor_name'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['vbarcode'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['vitemname'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['vsize'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['new_costprice'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['npack'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['po_order_by'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['po_total_suggested_cost'].'</td>';
            $data_row .= '<td style="border: 1px solid black;">'.$item['nordextprice'].'</td>';
            $data_row .= '</tr>';
        }
        $data_row .= "<tr>
                    <td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td><td style='border: 1px solid black;'></td>
                    <td style='border: 1px solid black;' colspan='2'><b>Sub Total</b></td><td style='border: 1px solid black;'><b>{$purchase_info['nsubtotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Tax (+)</b></td><td><b>{$purchase_info['ntaxtotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Freight (+)</b></td><td><b>{$purchase_info['nfreightcharge']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Deposit (+)</b></td><td><b>{$purchase_info['ndeposittotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Fuel (+)</b></td><td><b>{$purchase_info['nfuelcharge']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Delivery (+)</b></td><td><b>{$purchase_info['ndeliverycharge']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Return (-)</b></td><td><b>{$purchase_info['nreturntotal']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Discount (-)</b></td><td><b>{$purchase_info['ndiscountamt']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Rips (-)</b></td><td><b>{$purchase_info['nripsamt']}</b></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td colspan='2'><b>Net Total</b></td><td><b>{$purchase_info['nnettotal']}</b></td>
                </tr>
            </tbody>
        </table>";
        
        $file_name = 'purchase_order.xls';
        $file_path = storage_path("logs/purchaseOrder/".$file_name);
        // $file_path = DIR_TEMPLATE."/administration/".$file_name;

        $myfile = fopen($file_path, "w");
        fwrite($myfile, $data_row);
        fclose($myfile);
        
        unset($data_row);
        $content = file_get_contents ($file_path);
        
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$file_name}");
        
        // header ('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    }
    
}