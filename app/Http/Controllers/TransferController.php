<?php
namespace App\Http\Controllers;
use App\Model\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
class TransferController extends Controller
{
	private $error = array();
	
	public function index(Request $request) {
		return $this->getList($request);
	}
	
	public function listing(Request $request) {
		return $this->getListing($request);
	}
	
	public function getListing(Request $request){
		$data['breadcrumbs'] = array();
		$url = '';
		$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => url('/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'text' => "Transfer",
			'href' => url('/transfer/listing')
		);
		$data['add'] = url('/transfer');
		$filter_data = array(
			// 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			// 'limit' => $this->config->get('config_limit_admin')
		);
        $model = new Transfer();
		$transfer_data = $model->getTransfersData($filter_data);
        $transfer_data = json_decode(json_encode($transfer_data), true);
		
		$data['transfers'] = $transfer_data;
		
		$data['heading_title'] = "Transfer";
		$data['text_list'] = "Transfer";
		$data['text_no_results'] = "No Records Found";
		$data['text_confirm'] = "";
		$data['Active'] = "Active";
		$data['Inactive'] = "Inactive";
		$data['button_remove'] = "Remove";
        $data['button_save'] = "Save";
        $data['button_view'] = "View";
        $data['button_add'] = "Add";
        $data['button_edit'] = "Edit";
        $data['button_delete'] = "Delete";
        $data['button_rebuild'] = "Rebuild";
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if ((session()->exists('success')) ) {
			$data['success'] = session()->get('success');
            Session::forget('success');
		} else {
			$data['success'] = '';
		}
        $data['results'] = "";
        $data['pagination'] = "";
        $data['results'] ="";
		$data['header'] = "";
		$data['column_left'] = "";
		$data['footer'] = "";
        return view('transfer.transfer_listing', $data);
	}
	
	public function add() {
		$this->load->language('administration/transfer');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('api/transfer');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			echo "<pre>";
			print_r($this->request->post);
			exit;
			$datas[] = $this->request->post;
			$this->model_api_template->addTemplate($datas);
			$this->session->data['success'] = $this->language->get('text_success_add');
			$url = '';
			$this->response->redirect($this->url->link('administration/template', 'token=' . $this->session->data['token'] . $url, true));
		}
		$this->getForm();
	}
	
	public function edit(Request $request) {
		$input = $request->all();
		$model = new Transfer();
		if (($request->isMethod('POST'))) {
			$dreceivedate = \DateTime::createFromFormat('m-d-Y', $input['transfer']['dreceivedate'])->format('Y-m-d');
			$datas[0]['vvendortype'] = $input['transfer']['vvendortype'];
			$datas[0]['estatus'] = $input['transfer']['estatus'];
			$datas[0]['vwhcode'] = $input['transfer']['vwhcode'];
			$datas[0]['vtransfertype'] = $input['transfer']['vtransfertype'];
			$datas[0]['dreceivedate'] = $dreceivedate;
			$datas[0]['vvendorid'] = $input['transfer']['vvendorid'];
			$datas[0]['vinvnum'] = $input['transfer']['vinvnum'];
			$datas[0]['items'] = $input['items'];
			$data = $model->editlistTransfer($datas);
			session()->put('success', "Success: You have added new transfer!");
			return response(json_encode($data), 200)
                  ->header('Content-Type', 'application/json');
		}
		// $this->getForm();
	}
	
	protected function getList(Request $request) {
		$input = $request->all();
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'iwtrnid';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => "Home",
			'href' => url('/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'text' => "Transfer",
			'href' => url('/transfer/listing')
		);
        $data['add'] = url('/transfer');
		$data['edit'] = url('/transfer/edit');
		$data['delete'] = url('/transfer/delete');
		$data['edit_list'] = url('/transfer/edit_list');
		$data['check_invoice'] = url('/transfer/check_invoice');
		$data['filter'] = url('/transfer');
		$data['action'] = url('/transfer/edit');
		$data['cancel'] = url('/transfer/listing');
		$data['add_items'] = url('/transfer/add_items');
		$data['remove_items'] = url('/transfer/remove_items');
		$data['display_items'] = url('/transfer/display_items');
		$data['transfers'] = array();
		$filter_data = array(
			// 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			// 'limit' => $this->config->get('config_limit_admin')
		);
        $model = new Transfer();
		$vendors = json_decode(json_encode($model->getVendors()), true);
		$data['vendors'] = $this->arrayPaginator($vendors, $request);
		$transfer_data = array();
		if(count($transfer_data)==0){ 
			$data['transfers'] =array();
			$transfer_total = 0;
			$data['transfer_row'] =1;
		}
		$data['heading_title'] = 'Add Transfer';
		$data['text_list'] = 'Add Transfer';
		$data['text_list'] = "Transfer";
		$data['text_no_results'] = "No Records Found";
		$data['text_confirm'] = "";
		$data['Active'] = "Active";
		$data['Inactive'] = "Inactive";
		$data['button_remove'] = "Remove";
        $data['button_save'] = "Save";
        $data['button_view'] = "View";
        $data['button_add'] = "Add";
        $data['button_edit'] = "Edit";
        $data['button_delete'] = "Delete";
        $data['button_rebuild'] = "Rebuild";
		$data['text_transfer_type'] = "Transfer Type";
		$data['text_transfer_date'] = "Transfer Date";
		$data['text_vendor'] = "Vendor";
		$data['text_invoice'] = "Invoice";
		$data['transfer_types'][0]['value_transfer'] = "WarehouseToStore";
		$data['transfer_types'][0]['text_transfer'] = "Warehouse To Store";
		$data['transfer_types'][1]['value_transfer'] = "StoretoWarehouse";
		$data['transfer_types'][1]['text_transfer'] = "Store to Warehouse";
		// $data['transfer_types'][2]['value_transfer'] = $this->language->get('Storetostore');
		// $data['transfer_types'][2]['text_transfer'] = $this->language->get('text_Storetostore');
		$data['button_edit_list'] = 'Update Selected';
		$data['text_special'] = '<strong>Special:</strong>';
		$data['token'] = "";
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset(session()->exists['success'])) {
			$data['success'] = session()->get('success');
			session()->get('success');
		} else {
			$data['success'] = '';
		}
		if (session()->exists('selected')) {
			$data['selected'] = (array)$input['selected'];
		} else {
			$data['selected'] = array();
		}
		$url = '';
		if (isset($input['page'])) {
			$url .= '&page=' . $input['page'];
		}
		$url = '';
		return view('transfer.transfer_list', $data);
	}
	
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['itemplateid']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_template_type'] = $this->language->get('text_template_type');
		$data['text_inventory_type'] = $this->language->get('text_inventory_type');
		$data['text_template_name'] = $this->language->get('text_template_name');
		$data['text_template_sequence'] = $this->language->get('text_template_sequence');
		$data['text_template_status'] = $this->language->get('text_template_status');
		$data['Active'] = $this->language->get('Active');
		$data['Inactive'] = $this->language->get('Inactive');
		$data['temp_types'][] = $this->language->get('PO');
		$data['temp_types'][] = $this->language->get('PO1');
		$data['temp_invent_types'][] = $this->language->get('Daily');
		$data['temp_invent_types'][] = $this->language->get('Weekly');
		$data['temp_invent_types'][] = $this->language->get('Monthly');
		$data['entry_parent'] = $this->language->get('entry_parent');
		$data['entry_filter'] = $this->language->get('entry_filter');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['vtemplatename'])) {
			$data['error_vtemplatename'] = $this->error['vtemplatename'];
		} else {
			$data['error_vtemplatename'] = '';
		}
		if (isset($this->error['vtemplatetype'])) {
			$data['error_vtemplatetype'] = $this->error['vtemplatetype'];
		} else {
			$data['error_vtemplatetype'] = '';
		}
		$url = '';
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('administration/template', 'token=' . $this->session->data['token'] . $url, true)
		);
		if (!isset($this->request->get['itemplateid'])) {
			$data['action'] = $this->url->link('administration/template/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('administration/template/edit', 'token=' . $this->session->data['token'] . '&itemplateid=' . $this->request->get['itemplateid'] . $url, true);
		}
		$data['cancel'] = $this->url->link('administration/template', 'token=' . $this->session->data['token'] . $url, true);
		$data['add_items'] = $this->url->link('administration/template/add_items', 'token=' . $this->session->data['token'] . $url, true);
		$data['remove_items'] = $this->url->link('administration/template/remove_items', 'token=' . $this->session->data['token'] . $url, true);
		if (isset($this->request->get['itemplateid']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$template_info = $this->model_api_template->getTemplate($this->request->get['itemplateid']);
			$data['itemplateid'] = $this->request->get['itemplateid'];
		}
		$data['token'] = $this->session->data['token'];	
		if (isset($this->request->post['vtemplatename'])) {
			$data['vtemplatename'] = $this->request->post['vtemplatename'];
		} elseif (!empty($template_info)) {
			$data['vtemplatename'] = $template_info['vtemplatename'];
		} else {
			$data['vtemplatename'] = '';
		}
		if (isset($this->request->post['vtemplatetype'])) {
			$data['vtemplatetype'] = $this->request->post['vtemplatetype'];
		} elseif (!empty($template_info)) {
			$data['vtemplatetype'] = $template_info['vtemplatetype'];
		} else {
			$data['vtemplatetype'] = '';
		}
		if (isset($this->request->post['vinventorytype'])) {
			$data['vinventorytype'] = $this->request->post['vinventorytype'];
		} elseif (!empty($template_info)) {
			$data['vinventorytype'] = $template_info['vinventorytype'];
		} else {
			$data['vinventorytype'] = '';
		}
		if (isset($this->request->post['isequence'])) {
			$data['isequence'] = $this->request->post['isequence'];
		} elseif (!empty($template_info)) {
			$data['isequence'] = $template_info['isequence'];
		} else {
			$data['isequence'] = '';
		}
		if (isset($this->request->post['estatus'])) {
			$data['estatus'] = $this->request->post['estatus'];
		} elseif (!empty($template_info)) {
			$data['estatus'] = $template_info['estatus'];
		} else {
			$data['estatus'] = '';
		}
		if(isset($template_info)){
			$this->load->model('administration/template');
			$itms = array();
			if(isset($template_info['items']) && count($template_info['items']) > 0){
				$itms = $this->model_administration_template->getPrevRightItemIds($template_info['items']);
			}
			$edit_left_items = $this->model_administration_template->getEditLeftItems($itms);
			$edit_right_items =array();
			if(count($itms) > 0){
				$edit_right_items = $this->model_administration_template->getEditRightItems($itms,$this->request->get['itemplateid']);
			}
			$data['items'] = $edit_left_items;
			$data['edit_right_items'] = $edit_right_items;
			$data['previous_items'] = $itms;
		}else{
			$this->load->model('api/items');
			$data['items'] = $this->model_api_items->getItems();
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('administration/template_form', $data));
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
		$model = new Transfer();
		$json = array();
		$input = $request->all();
		if(count($input['checkbox_itemsort1']) > 0){
			$right_items_arr = $model->getRightItems($input['checkbox_itemsort2']);
			$left_items_arr = $model->getLeftItems($input['checkbox_itemsort1']);
			$json['right_items_arr'] = $right_items_arr;
			$json['left_items_arr'] = $left_items_arr;
		}
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
	public function remove_items() {
		$model = new Transfer();
		$json = array();
		if(isset($this->request->post['checkbox_itemsort1'])){
			$data = $this->request->post['checkbox_itemsort1'];
		}else{
			$data = array();
		}
		$left_items_arr = $model->getLeftItems($data);
		$json['left_items_arr'] = $left_items_arr;
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
	public function display_items(Request $request) {
		$iputData = $request->input();
		ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);
		$model = new Transfer();
		$json = array();
		$vendors = $model->getVendors();
		$vendors = json_decode(json_encode($vendors), true);
		if((isset($iputData['vtransfertype']) && isset($iputData['vvendorid'])) && ((!$request->isMethod('POST')))) {
			$json['vendor_id'] = $iputData['vvendorid'];
			$vendor_id = $iputData['vvendorid'];
			$json['vtransfertype'] = $iputData['vtransfertype'];
			$vtransfertype = $iputData['vtransfertype'];
		}else{
			$json['vendor_id'] = $vendors[0]['isupplierid'];
			$vendor_id = $vendors[0]['isupplierid'];
			$json['vtransfertype'] = 'WarehouseToStore';
			$vtransfertype = 'WarehouseToStore';
		}
		$transfer_data = $model->getTransfers($vtransfertype,$vendor_id);
		$transfer_data = json_decode(json_encode($transfer_data), true);
		if(isset($transfer_data)){
			$itms = array();
			if(isset($transfer_data) && count($transfer_data) > 0){
				$itms = $model->getPrevRightItemIds($transfer_data);
			}
			
			$edit_left_items = $model->getEditLeftItems($itms,$vendor_id);
			$edit_right_items =array();
			if(count($itms) > 0){
				$edit_right_items = $model->getEditRightItems($itms,$vtransfertype,$vendor_id);
				$edit_right_items = json_decode(json_encode($edit_right_items), true);
				if($vtransfertype == 'WarehouseToStore'){
					$json['vinvnum'] = "";
				}
			}
			$json['items'] = $edit_left_items;
			$json['edit_right_items'] = $edit_right_items;
			$json['previous_items'] = $itms;
		}else{
			$json['items'] = $model->getlistItems();
		}
		return response(json_encode($json), 200)
                  ->header('Content-Type', 'application/json');
	}
	
	public function check_invoice(Request $request) {
		$input = $request->all();
		$model = new Transfer();
		$json = array();
		if($request->isMethod('POST') && $input['invoice'] != '') {
			$json = $model->getCheckInvoice($input['invoice']);
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
