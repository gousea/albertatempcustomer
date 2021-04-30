<?php

namespace App\Http\Controllers;

use App\Model\PhysicalInventory;
use App\Model\Adjustment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class AdjustmentController extends Controller
{
    
	private $error = array();

	public function index(Request $request) {
		return $this->getList($request);
	}

	public function add(Request $request) {

        $input = $request->all();
        // dd($input);
        $physicalInventory = new PhysicalInventory();
		if ($request->isMethod('POST')) {

			$items = array();
			$nnettotal = 0;
			if(isset($input['items']) && count($input['items']) > 0){
				foreach ($input['items'] as $k => $item) {
					
					$items[$k] = array(
									'vitemid' => $item['vitemid'],
									'vitemname' => $item['vitemname'],
									'vunitcode' => '',
									'vunitname' => '',
									'ndebitqty' => $item['ndebitqty'],
									'ncreditqty' => '0.00',
									'ndebitunitprice' => '0.00',
									'ncrediteunitprice' => '0.00',
									'nordtax' => '0.00',
									'ndebitextprice' => '0.00',
									'ncrditextprice' => '0.00',
									'ndebittextprice' => '0.00',
									'ncredittextprice' => '0.00',
									'vbarcode' => $item['vbarcode'],
									'vreasoncode' => $item['vreasoncode'],
									'ndiffqty' => '0.00',
									'vvendoritemcode' => '',
									'npackqty' => $item['npackqty'],
									'nunitcost' => $item['nunitcost'],
									'itotalunit' => $item['itotalunit'],
									'beforeQOH' => $item['iqtyonhand'],
									'afterQOH' => $item['ndebitqty']
							
								);
					$nnettotal = $nnettotal + $item['nnettotal'];
				}
			}
			$temp_arr[0] = array(
								'vpinvtnumber' => '',
								'vrefnumber' => $input['vrefnumber'],
								'nnettotal' => $nnettotal,
								'ntaxtotal' => '0.00',
								'dcreatedate' => \DateTime::createFromFormat('m-d-Y', $input['dcreatedate'])->format('Y-m-d').' 00:00:00',
								'estatus' => $input['estatus'],
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'],
								'dlastupdate' => \DateTime::createFromFormat('m-d-Y', date('m-d-Y'))->format('Y-m-d').' 00:00:00',
								'vtype' => 'Adjustment',
								'ilocid' => '',
								'dcalculatedate' => \DateTime::createFromFormat('m-d-Y', date('m-d-Y'))->format('Y-m-d').' 00:00:00',
								'dclosedate' => \DateTime::createFromFormat('m-d-Y', date('m-d-Y'))->format('Y-m-d').' 00:00:00',
								'items' => $items,
								'detail_name' => 'adjustment'
									
							);
			$physicalInventory->addPhysicalInventory($temp_arr);
			session()->put('success', "Success: You have modified adjustment detail!");
			$url = '';
            return redirect()->route('adjustment');
		}

		return $this->getForm(null,$request);
	}

	public function edit($ipiid,Request $request) {
		$data['ipiid'] = $ipiid ? $ipiid : "";
        $physicalInventory = new PhysicalInventory();
		$input = $request->all();
		
		if ($request->isMethod('POST')) {
			$items = array();
			$nnettotal = 0;
			if(isset($input['items']) && count($input['items']) > 0){
				foreach ($input['items'] as $k => $item) {
					$items[$k] = array(
									'vitemid' => $item['vitemid'],
									'vitemname' => $item['vitemname'],
									'vunitcode' => '',
									'vunitname' => '',
									'ndebitqty' => $item['ndebitqty'],
									'ncreditqty' => '0.00',
									'ndebitunitprice' => '0.00',
									'ncrediteunitprice' => '0.00',
									'nordtax' => '0.00',
									'ndebitextprice' => '0.00',
									'ncrditextprice' => '0.00',
									'ndebittextprice' => '0.00',
									'ncredittextprice' => '0.00',
									'vbarcode' => $item['vbarcode'],
									'vreasoncode' => $item['vreasoncode'],
									'ndiffqty' => '0.00',
									'vvendoritemcode' => '',
									'npackqty' => $item['npackqty'],
									'nunitcost' => $item['nunitcost'],
									'itotalunit' => $item['itotalunit'],
									'beforeQOH' => $item['iqtyonhand'],
									'afterQOH' => $item['ndebitqty']
								);
					$nnettotal = $nnettotal + (int)$item['nnettotal'];
				}
			}

			$temp_arr[0] = array(
								'ipiid' => $input['ipiid'],
								'vpinvtnumber' => '',
								'vrefnumber' => $input['vrefnumber'],
								'nnettotal' => $nnettotal,
								'ntaxtotal' => '0.00',
								'dcreatedate' => \DateTime::createFromFormat('m-d-Y', $input['dcreatedate'])->format('Y-m-d').' 00:00:00',
								'estatus' => $input['estatus'],
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'],
								'dlastupdate' => \DateTime::createFromFormat('m-d-Y', date('m-d-Y'))->format('Y-m-d').' 00:00:00',
								'vtype' => 'Adjustment',
								'ilocid' => '',
								'dcalculatedate' => \DateTime::createFromFormat('m-d-Y', date('m-d-Y'))->format('Y-m-d').' 00:00:00',
								'dclosedate' => \DateTime::createFromFormat('m-d-Y', date('m-d-Y'))->format('Y-m-d').' 00:00:00',
								'items' => $items,
								'checked_items' => '',
								'detail_name' => 'adjustment'
							);
			$physicalInventory->editlistPhsicalInventory($temp_arr);
			session()->put('success',"Success: You have edited adjustment detail!");
			return redirect()->route('adjustment');
		}

		return $this->getForm($data, $request);
	}
	  
	protected function getList($request) {
        $input = $request->all();
        $physicalInventory = new PhysicalInventory();
		if (isset($input['sort'])) {
			$sort = $input['sort'];
		} else {
			$sort = 'ipiid';
		}

		if (isset($input['page'])) {
			$page = $input['page'];
		} else {
			$page = 1;
		}

		if (isset($input['searchbox'])) {
			$searchbox =  $input['searchbox'];
		}else{
			$searchbox = '';
		}

		$url = '';

		if (isset($input['page'])) {
			$url .= '&page=' . $input['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => url('/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Adjustment Detail",
			'href' => url('/adjustment')
		);

        $data['add'] = url('/adjustment/add');
        $data['edit'] =  url('/adjustment/edit');
        $data['delete'] =  url('/adjustment/delete');
        $data['edit_list'] =  url('/adjustment/edit_list');

		$data['current_url'] = url('/adjustment');
		$data['searchadjustment'] = url('/adjustment/search');
		
		$data['adjustment_details'] = array();

		$filter_data = array(
			'searchbox'  => $searchbox,
			'start' => ($page - 1) * 25,
			'limit' => 25
		);

		$adjustment_detail_data = $physicalInventory->getPhysicalInventoriesByType('Adjustment',$filter_data);
		
		$adjustment_detail_total = $physicalInventory->getPhysicalInventoriesByTypeTotal('Adjustment');

        $results = $adjustment_detail_data;
        $results = json_decode(json_encode($results), true);
        $resultArray = [];
		foreach ($results as $result) {
			
			$resultArray[] = array(
				'ipiid'     => $result['ipiid'],
				'vpinvtnumber'   => $result['vpinvtnumber'],
				'vrefnumber'   => $result['vrefnumber'],
				'nnettotal'  => $result['nnettotal'],
				'ntaxtotal'        => $result['ntaxtotal'],
				'dcreatedate'  	  => $result['dcreatedate'],
				'estatus'  	      => $result['estatus'],
				'vordertitle'  	      => $result['vordertitle'],
				'vnotes'  	      => $result['vnotes'],
				'vtype'  	      => $result['vtype'],
				'ilocid'  	      => $result['ilocid'],
				'dcalculatedate'  	      => $result['dcalculatedate'],
				'dclosedate'  	      => $result['dclosedate'],
				'edit'            => url('/adjustment/edit/'.$result['ipiid'])
				
			);
		}
		$data['adjustment_details'] = $this->arrayPaginator($resultArray, $request);
		if(count($adjustment_detail_data)==0){ 
			$data['adjustment_details'] =array();
			$adjustment_detail_total = 0;
			$data['adjustment_detail_row'] =1;
		}

		$data['heading_title'] = "Adjustment Detail";

		$data['text_list'] = "Adjustment Detail";
		$data['text_no_results'] = "No Records Found";
		$data['text_confirm'] = "";

		$data['text_number'] = "Number";
		$data['text_created'] = "Created";
		$data['text_title'] = "Title";
		$data['text_status'] = "Status";
		$data['text_notes'] = "Notes";

		$data['button_remove'] = "Remove";
        $data['button_save'] = "Save";
        $data['button_view'] = "View";
        $data['button_add'] = "Add";
        $data['button_edit'] = "Edit";
        $data['button_delete'] = "Delete";
        $data['button_rebuild'] = "Rebuild";
		
		$data['button_edit_list'] = 'Update Selected';
        $data['text_special'] = '<strong>Special:</strong>';
        $data['token'] = "";


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($input['selected'])) {
			$data['selected'] = (array)$input['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($input['page'])) {
			$url .= '&page=' . $input['page'];
		}

		$url = '';
		$data['pagination'] = "";
        $data['results'] ="";
		$data['header'] = "";
		$data['column_left'] = "";
		$data['footer'] = "";
        return view('adjustment.adjustmentList', $data);
	}

	public function getForm($data = null,Request $request) {

		$input = $data;
		
        $physicalInventory = new PhysicalInventory();
		$data['heading_title'] = "Adjustment Detail";

		$data['text_form'] = !isset($input['ipiid']) ? "Add Adjustment Detail" : "Edit Adjustment Detail";
		$data['text_none'] = "None";
		$data['text_default'] = "Default";

		$data['text_number'] = "Number";
		$data['text_created'] = "Created";
		$data['text_title'] = "Title";
		$data['text_status'] = "Status";
		$data['text_notes'] = "Notes";

		$data['column_sku'] = "SKU#";
		$data['column_item_name'] = "Item Name";
		$data['column_unit_cost'] = "Unit Cost";
		$data['column_pack_qty'] = "Pack Qty";
		$data['column_adj_qty'] = "Adj. Qty";
		$data['column_reason'] = "Reason";
		$data['column_total_unit'] = "Total Unit";
		$data['column_total_amt'] = "Total Amt";

		$data['entry_parent'] = "Parent";
		$data['entry_filter'] = "Filter";
		$data['entry_store'] = "Store";

		$data['button_save'] = "Save";
		$data['button_cancel'] = "Cancel";


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

		if (isset($this->error['dcreatedate'])) {
			$data['error_dcreatedate'] = $this->error['dcreatedate'];
		} else {
			$data['error_dcreatedate'] = '';
		}

		$url = '';

		$data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => url('/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'text' => "Adjustment Detail",
			'href' => url('/adjustment')
		);

		if (!isset($input['ipiid'])) {
			$data['action'] = url('adjustment/add');
		} else {
			$data['action'] = url("adjustment/edit/".$input['ipiid']); 
        }
		$data['cancel'] = url('/adjustment');
		$data['add_items'] = url('/adjustment/add_items');
		$data['remove_items'] = url('/adjustment/remove');

		$data['display_items'] = url('/adjustment/display');
		$data['display_items_search'] = url('adjustment/display_items_search');
		$data['calculate_post'] = url('adjustment/calculate_post');
		$data['adjustment_list'] =url('adjustment');

		if (isset($input['ipiid']) && (!$request->isMethod('POST') )) {
			$adjustment_detail_info = $physicalInventory->getPhysicalInventory($input['ipiid']);
			$adjustment_detail_info = json_decode(json_encode($adjustment_detail_info), true);
			$data['ipiid'] = $input['ipiid'];
		}
		
		
		$data['token'] = session()->get('token');	

		if (isset($input['vrefnumber'])) {
			$data['vrefnumber'] = $input['vrefnumber'];
		} elseif (!empty($adjustment_detail_info)) {
			$data['vrefnumber'] = $adjustment_detail_info[0]['vrefnumber'];
		} else {
			$temp_vrefnumber = $physicalInventory->getLastInsertedID();
			if(isset($temp_vrefnumber[0]->ipiid)){
				$data['vrefnumber'] = str_pad($temp_vrefnumber[0]->ipiid+1,9,"0",STR_PAD_LEFT);
			}else{
				$data['vrefnumber'] = str_pad(1,9,"0",STR_PAD_LEFT);
			}
		}

		if (isset($input['vordertitle'])) {
			$data['vordertitle'] = $input['vordertitle'];
		} elseif (!empty($adjustment_detail_info)) {
			$data['vordertitle'] = $adjustment_detail_info[0]['vordertitle'];
		} else {
			$data['vordertitle'] = '';
		}

		if (isset($input['dcreatedate'])) {
			$data['dcreatedate'] = $input['dcreatedate'];
		} elseif (!empty($adjustment_detail_info)) {
			$data['dcreatedate'] = $adjustment_detail_info[0]['dcreatedate'];
		} else {
			$data['dcreatedate'] = '';
		}

		if (isset($input['estatus'])) {
			$data['estatus'] = $input['estatus'];
		} elseif (!empty($adjustment_detail_info)) {
			$data['estatus'] = $adjustment_detail_info[0]['estatus'];
		}

		if (isset($input['vnotes'])) {
			$data['vnotes'] = $input['vnotes'];
		} elseif (!empty($adjustment_detail_info)) {
			$data['vnotes'] = $adjustment_detail_info[0]['vnotes'];
		} else {
			$data['vnotes'] = '';
		}

        
        $adjustmentModel = new Adjustment();

		$reasonData= $adjustmentModel->getReasons();
        $data['reasons'] = json_decode(json_encode($reasonData), true);
		$data['header'] = "";
		$data['column_left'] = "";
		$data['footer'] = "";
        return view('adjustment.adjustmentForm', $data);
		
	}
	
	protected function validateEditList() {
    	if(!$this->user->hasPermission('modify', 'administration/template')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

  	public function add_items(Request $request) {
        $input = $request->all();
        $adjustmentModel = new Adjustment();

		$json = array();

		if(count($input['checkbox_itemsort1']) > 0){
			$right_items_arr = $adjustmentModel->getRightItems($input['checkbox_itemsort1']);
			$json['right_items_arr'] = $right_items_arr;
		}
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}

	public function remove_items(Request $request) {

		$adjustmentModel = new Adjustment();
		$input = $request->all();

		$json = array();

		if(isset($input['checkbox_itemsort1'])){
			$data = $input['checkbox_itemsort1'];
		}else{
			$data = array();
		}

		$left_items_arr = $adjustmentModel->getLeftItems($data);
		
		$json['left_items_arr'] = $left_items_arr;

		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}

	public function display_items(Request $request) {

		$input = $request->all();
		$input['ipiid'] = $request->ipiid;

		$physicalInventory = new PhysicalInventory();
		$adjustmentModel = new Adjustment();

		$json = array();

		if (isset($input['ipiid'])) {
			$adjustment_detail_info = $physicalInventory->getPhysicalInventory($input['ipiid']);
			
			if(isset($adjustment_detail_info)){
				
				$itms = array();

				if(isset($adjustment_detail_info['items']) && count($adjustment_detail_info['items']) > 0){

					$itms = $adjustmentModel->getPrevRightItemIds($adjustment_detail_info['items']);
				}
				
				$edit_right_items =array();
				if(count($itms) > 0){
					$edit_right_items = $adjustmentModel->getEditRightItems($itms,$input['ipiid']);
				}

				$json['edit_right_items'] = $edit_right_items;
				$json['previous_items'] = $itms;

			}else{
				// $json['items'] = $this->model_administration_physical_inventory_detail->getlistItems();
			}
			
		}else{
			// $json['items'] = $this->model_administration_physical_inventory_detail->getlistItems();
		}
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}

	public function calculate_post(Request $request) {

		ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);

        $physicalInventory = new PhysicalInventory();
        $input = $request->all();
		$json = array();
		if ($request->isMethod('POST')) {
		
			$items = array();
			$nnettotal = 0;
			if(isset($input['items']) && count($input['items']) > 0){
				foreach ($input['items'] as $k => $item) {

					$query_item_qoh = DB::connection('mysql_dynamic')->select("SELECT iqtyonhand,isparentchild,parentid,parentmasterid FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                    $query_item_qoh = json_decode(json_encode($query_item_qoh), true);
			 
			    	if($query_item_qoh[0]['isparentchild'] == 1){
			    		$query_item_qoh = DB::connection('mysql_dynamic')->select("SELECT iqtyonhand,isparentchild,parentid,parentmasterid FROM mst_item WHERE iitemid='". (int)$query_item_qoh[0]['parentmasterid'] ."'");
			    	    $query_item_qoh = json_decode(json_encode($query_item_qoh), true);
			    	    
			    	}
			    	
					
					$items[$k] = array(
									'vitemid' => $item['vitemid'],
									'vitemname' => $item['vitemname'],
									'vunitcode' => '',
									'vunitname' => '',
									'ndebitqty' => $item['ndebitqty'],
									'ncreditqty' => '0.00',
									'ndebitunitprice' => '0.00',
									'ncrediteunitprice' => '0.00',
									'nordtax' => '0.00',
									'ndebitextprice' => '0.00',
									'ncrditextprice' => '0.00',
									'ndebittextprice' => '0.00',
									'ncredittextprice' => '0.00',
									'vbarcode' => $item['vbarcode'],
									'vreasoncode' => $item['vreasoncode'],
									'ndiffqty' => (int)$query_item_qoh[0]['iqtyonhand'] - $item['itotalunit'],
									'vvendoritemcode' => '',
									'npackqty' => $item['npackqty'],
									'nunitcost' => $item['nunitcost'],
									'itotalunit' => $item['itotalunit'],
									'beforeQOH' => $item['iqtyonhand'],
									'afterQOH' => $item['ndebitqty']
								);
					$nnettotal = $nnettotal + $item['nnettotal'];
				}
			}

			if(isset($input['ipiid'])){
				$temp_arr[0] = array(
								'ipiid' => $input['ipiid'],
								'vpinvtnumber' => '',
								'vrefnumber' => $input['vrefnumber'],
								'nnettotal' => $nnettotal,
								'ntaxtotal' => '0.00',
								'dcreatedate' => \DateTime::createFromFormat('m-d-Y', $input['dcreatedate'])->format('Y-m-d').' 00:00:00',
								'estatus' => $input['estatus'],
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'],
								'dlastupdate' => date('Y-m-d H:i:s'),
								'vtype' => 'Adjustment',
								'ilocid' => '',
								'dcalculatedate' => date('Y-m-d H:i:s'),
								'dclosedate' => date('Y-m-d H:i:s'),
								'items' => $items,
								'detail_name' => 'adjustment'
							);

			}else{
				$temp_arr[0] = array(
								'vpinvtnumber' => '',
								'vrefnumber' => $input['vrefnumber'],
								'nnettotal' => $nnettotal,
								'ntaxtotal' => '0.00',
								'dcreatedate' => \DateTime::createFromFormat('m-d-Y', $input['dcreatedate'])->format('Y-m-d').' 00:00:00',
								'estatus' => $input['estatus'],
								'vordertitle' => $input['vordertitle'],
								'vnotes' => $input['vnotes'],
								'dlastupdate' => date('Y-m-d H:i:s'),
								'vtype' => 'Adjustment',
								'ilocid' => '',
								'dcalculatedate' => date('Y-m-d H:i:s'),
								'dclosedate' => date('Y-m-d H:i:s'),
								'items' => $items,
								'detail_name' => 'adjustment'
							);

			}
			
			$item_response = $physicalInventory->calclulatePost($temp_arr);
			$json = $item_response;
			
		}
		
		if(array_key_exists("success",$json)){
			http_response_code(200);
		}else{
			http_response_code(401);
		}
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}

	public function search(Request $request){
        ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);
        $return = array();
        $input = $request->all();
        $physicalInventory = new PhysicalInventory();
		if(isset($input['term']) && !empty($input['term'])){

			$datas = $physicalInventory->getPhysicalInventorySearch($input['term']);

			foreach ($datas as $key => $value) {
				$temp = array();
				$temp['ipiid'] = $value['ipiid'];
				$temp['vordertitle'] = $value['vordertitle'];
				$return[] = $temp;
			}
		}
		return response(json_encode($return), 200)
                  ->header('Content-Type', 'application/json');
	}

	public function display_items_search(Request $request) {
		ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);

        $adjustmentModel = new Adjustment();

		$json = array();

		if ($request->isMethod('POST')) {
			$post_arr = json_decode(file_get_contents('php://input'), true);

			if(isset($post_arr['search_val']) && isset($post_arr['search_by']) && isset($post_arr['right_items'])){
				
					$json['items'] = $adjustmentModel->getSearchItems($post_arr);
			}
		}

        return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
    public function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = 25;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_slice($array, $offset, $perPage, true),
            count($array),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}
