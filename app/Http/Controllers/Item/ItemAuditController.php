<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Items\itemAudit;

class ItemAuditController extends Controller
{
    public function index(Request $request) {
        
		return $this->getList($request);
    }
    
    public function edit_list(Request $request) {
        $model = new itemAudit();
		if ($request->isMethod('get')) {
			$post_data = $request->all();
			$items_id_array = rawurldecode($post_data['items_id_array']);
			$items_id_array = unserialize($items_id_array);
			$post_data['item_ids'] = $items_id_array;
			$model->editlistItems($post_data);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
            return redirect(route('item_audit'))->with('message','Success: You have modified Items!');
		}
    }

    public function getList(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        
        $model = new itemAudit();
        if (isset($input['sort'])) {
            $sort = $input['sort'];
        } else {
            $sort = 'iitemid';
        }
        if (isset($tinput['page'])) {
            $page = $input['page'];
        } else {
            $page = 1;
        }
        $url = '';
       if (isset($input['search_radio'])) {
            $search_radio =  $input['search_radio'];
            $page = 1;
        } else if (session()->exists('search_radio')) {
            $search_radio = session()->get('search_radio');
        } else {
            $search_radio = '';
        }
        $search_find = '';
        if (isset($input['seach_start_date']) && isset($input['seach_end_date']) && isset($input['search_radio'])  && $input['search_radio'] == 'by_dates') {
            $search_find_dates['seach_start_date'] =  $input['seach_start_date'];
            $search_find_dates['seach_end_date'] =  $input['seach_end_date'];
            session()->put('seach_start_date',$input['seach_start_date']);
            session()->put('seach_end_date',$input['seach_end_date']);
            $search_find = '';
            $page = 1;
        } else if (isset($input['search_item']) && isset($input['search_radio']) && $input['search_radio'] == 'search') {
            $search_find =  $input['search_item'];
            $search_find_dates = array();
            session()->put('search_item', $input['search_item']);
            $page = 1;
        } else if (isset($input['search_find'])) {
            $search_find =  $input['search_find'];
        } else if (isset($input['seach_start_date']) && isset($input['seach_end_date'])) {
            $search_find_dates['seach_start_date'] =  $input['seach_start_date'];
            $search_find_dates['seach_end_date'] =  $input['seach_end_date'];
            $search_find = '';
        } else {
            $search_find = '';
            $search_find_dates = array();
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
        $data['breadcrumbs'][] = array(
            'text' => 'Item Audit',
            'href' => url('/items/getlist')
        );
        $data['add'] = url('/items/add');
        $data['edit'] =  url('/items/edit');
        $data['delete'] =  url('/items/delete');
        $data['edit_list'] =  url('/items/edit_list');
        $data['searchitem'] = url('/items/search');
        $data['current_url'] = url('/item_audit/getlist');
        $data['items'] = array();
        $filter_data = array(
            'search_radio'  => $search_radio,
            'search_find_dates' => $search_find_dates,
            'search_find'  => $search_find,
            // 'start' => ($page - 1) * 20,
            // 'limit' => 20
        );
        if (!empty($search_radio)) {
            $data['search_radio'] = $search_radio;
            session()->put('search_radio',$data['search_radio']);
        } else {
             $data['search_radio'] = session()->get('search_radio');
        }
        
        // if(session()->exists('seach_start_date')){
        //     $search_find_dates['seach_start_date'] = session()->get('seach_start_date');
        // }
        // if(session()->exists('seach_end_date')){
        //     $search_find_dates['seach_end_date'] = session()->get('seach_end_date');
        // }
        
        if(isset($input['page'])){
            
            $search_find_dates['seach_start_date'] = session()->get('seach_start_date');
            $search_find_dates['seach_end_date'] = session()->get('seach_end_date');
            // $search_find = session()->get('search_item');
            $data['search_radio'] = session()->get('search_radio');
        }
        
        $data['search_find'] = $search_find;
        $data['search_find_dates'] = $search_find_dates;
        $item_data_total = $model->getTotalItems($filter_data);
        $item_total = $item_data_total['total'];
        $data['items_id_array'] = $item_data_total['iitemid'];
        $item_data = $model->getItems($filter_data);
        $results = json_decode(json_encode($item_data), true);
        $resultArray = [];
        foreach ($results as $result) {
            $resultArray[] = array(
                'iitemid'         => $result['iitemid'],
                'vitemtype'       => $result['vitemtype'],
                'vitemname'     => $result['vitemname'],
                'VITEMNAME'     => $result['VITEMNAME'],
                'vbarcode'            => $result['vbarcode'],
                'vcategorycode' => $result['vcategorycode'],
                'vcategoryname' => $result['vcategoryname'],
                'vdepcode'      => $result['vdepcode'],
                'vdepartmentname'      => $result['vdepartmentname'],
                'vsuppliercode' => $result['vsuppliercode'],
                'vcompanyname' => $result['vcompanyname'],
                'iqtyonhand'      => $result['iqtyonhand'],
                'vtax1'          => $result['vtax1'],
                'vtax2'          => $result['vtax2'],
                'QOH'              => $result['IQTYONHAND'],
                'dcostprice'      => $result['dcostprice'],
                'dunitprice'      => $result['dunitprice'],
                'visinventory'  => $result['visinventory'],
                'isparentchild' => $result['isparentchild'],
                'LastUpdate' => $result['LastUpdate'],
            );
        }
        $data['items'] = $this->arrayPaginator($resultArray, $request);
        if (count($item_data) == 0) {
            $data['items'] = array();
            $item_total = 0;
            $data['item_row'] = 1;
        }
        $data['heading_title'] = "Last Modified Items";
        $data['text_list'] = "Last Modified Items";
        $data['text_no_results'] = "No Reccords found";
        $data['text_confirm'] = "text_confirm";
        $data['column_itemname'] = "Item Name";
        $data['column_itemtype'] = "Item Type";
        $data['column_action'] = "Action";
        $data['column_deptcode'] = "Dept.";
        $data['column_sku'] = "SKU";
        $data['column_categorycode'] = "Category";
        $data['column_price'] = "Price";
        $data['column_qtyonhand'] = "Qty. on Hand";
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
            $data['error_warning'] = "Warning: Please check the form carefully for errors!";
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = "Success: You have modified Items!";
            Session::forget('success');
        } else {
            $data['success'] = '';
        }
        if (isset($input['selected'])) {
            $data['selected'] = (array)$input['selected'];
        } else {
            $data['selected'] = array();
        }
        $data['array_yes_no']['Y'] = 'Yes';
        $data['array_yes_no']['N'] = 'No';
        if (!empty($search_radio)) {
            $url .= '&search_radio=' . $search_radio;
        }
        if (count($search_find_dates) > 0) {
            $url .= '&seach_start_date=' . $search_find_dates['seach_start_date'] . '&seach_end_date=' . $search_find_dates['seach_end_date'];
        }
        $data['pagination'] = "";
        $data['results'] = "";
        
        return view('items.itemAuditList', $data);
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
