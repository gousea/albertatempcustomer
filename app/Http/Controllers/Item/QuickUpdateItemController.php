<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Model\Items\quickUpdateItem;
use Illuminate\Support\Facades\Session;

class QuickUpdateItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
       return $this->getlist($request);
    }

    public function editList(Request $request) {
        $editModel      = new quickUpdateItem();
        $updateList     = $request->all();
        $editModel->updateItem($updateList);
        return redirect(route('quick_update_item'))->with('message','Success: You have modified Items Price!');
	}

    public function getlist(Request $request){

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $model      = new quickUpdateItem();
        $input      = $request->all();

        $data       = [];
        $item_types = [ 'Standard', 'Kiosk', 'Lot Matrix', 'Lottery'];
        $current_url    = url('/quick_update_item');
        $categories     = $model->getCategories();
        $departments    = $model->getAllDepartments();
        $itemGroups     = $model->getItemGroups();
        $array_yes_no   = array('Y'=>'Yes','N' => 'NO');
        $set_selected_option_session    = url('/quick_update_set_session');
        $get_categories_url             = url('/quick_update_get_categories');


        /****************** setting the session  for Pagination ***************/
        if(array_key_exists('search_radio', $input)){
            session()->put('page_search_radio', $input['search_radio']);
            session()->put('page_search_vcategorycode', isset($input['search_vcategorycode']) ? $input['search_vcategorycode'] : '');
            session()->put('page_search_vdepcode', isset($input['search_vdepcode']) ? $input['search_vdepcode'] : '' );
            session()->put('page_search_vitem_group_id', isset($input['search_vitem_group_id']) ? $input['search_vitem_group_id'] : '');
            session()->put('page_search_item', isset($input['search_item']) ? $input['search_item'] : '' );
            session()->put('page_search_filter', isset($input['search_filter']) ? $input['search_filter'] : '');
        }
        if(array_key_exists('search_item_type', $input)){
            session()->put('page_search_item_type', $input['search_item_type']);
        }

        if(array_key_exists('page', $input )){

           if (session()->has('page_search_item_type')){
    			$search_item_type = session()->get('page_search_item_type');
    		}else{
    			$search_item_type = 'All';
            }

            if ( session()->has('page_search_radio') ) {
    			$search_radio = session()->get('page_search_radio');
    		}else{
    			$search_radio = 'category';
            }

            if ( session()->has('page_search_item') ){
    			$search_find =  session()->get('page_search_item');
    		}else{
                $search_find = '';
            }

            $search_vcategorycode = $search_vdepcode = $search_vitem_group_id = $search_item = "";

            if(session()->has('page_search_vcategorycode')){
                $search_vcategorycode = session()->get('page_search_vcategorycode');
            }

            if(session()->has('page_search_vdepcode')){
                $search_vdepcode = session()->get('page_search_vdepcode');
            }

            if(session()->has('page_search_vitem_group_id')){
                $search_vitem_group_id = session()->get('page_search_vitem_group_id');
            }

            if(session()->has('page_search_item')){
                $search_item = session()->get('page_search_item');
            }

            if ( session()->has('page_search_vcategorycode') && session()->has('page_search_radio') && session()->get('page_search_radio') == 'category') {
                $search_vcategorycode =  session()->get('page_search_vcategorycode');
                $search_vdepcode = '';
                $search_vitem_group_id = "";
                $search_item = "";

            }else if (session()->has('page_search_vdepcode')  && session()->has('page_search_radio') && session()->get('page_search_radio')  == 'department'){
                $search_vdepcode = session()->get('page_search_vdepcode') ;
                $search_vcategorycode = '';
                $search_vitem_group_id = "";
                $search_item = "";

            }else if ( session()->has('page_search_vitem_group_id')  && session()->has('page_search_radio') && session()->get('page_search_radio') == 'item_group'){
                $search_vitem_group_id = session()->get('page_search_vitem_group_id');
                $search_vcategorycode = "";
                $search_vdepcode = '';
                $search_item = "";

            }elseif(session()->has('page_search_item') && session()->has('page_search_radio') && session()->get('page_search_radio') == 'search' ) {

                $search_item = session()->get('page_search_item');
                $search_vcategorycode = '';
                $search_vdepcode = '';
                $search_vitem_group_id = "";
            }else{
                $search_item = "";
                $search_vcategorycode = '';
                $search_vdepcode = '';
                $search_vitem_group_id = "";
            }

        }else{
            if(session()->exists('quickupdate_search_item_type')){
                $search_item_type = session()->get('quickupdate_search_item_type');
    		}elseif (isset($input['search_item_type'])){
    			$search_item_type = $input['search_item_type'];
    		}else{
    			$search_item_type = 'All';
            }
            if ( isset($input['search_radio']) ) {
    			$search_radio =  $input['search_radio'];
    		}
            elseif( session()->exists('selected_option') ) {
                $search_radio = session()->get('selected_option') ;
            }
    		elseif ( session()->exists('search_radio') ) {
    		    $search_radio = session()->get('search_radio');
    		}
    		else{
    			$search_radio = 'category';
            }


            session()->put('search_radio',  $search_radio);

            if (isset($input['search_item'])){
    			$search_find =  $input['search_item'];
                session()->put('search_item', $search_find);
    		}
            elseif(session()->exists('selected_option_value')){
                $search_find = session()->get('selected_option_value');
            }elseif(session()->exists('search_item')){
    		    $search_find = 	session()->get('search_item');
    		}else{
                $search_find = '';
            }

            $search_vcategorycode = $search_vdepcode = $search_vitem_group_id = $search_item = "";

            if(session()->exists('search_vcategorycode')){
                $search_vcategorycode = session()->get('search_vcategorycode');
            }

            if(session()->exists('selected_option_value')){
                $search_vdepcode = session()->get('selected_option_value');
            }

            if(session()->exists('search_vitem_group_id')){
                $search_vitem_group_id = session()->get('search_vitem_group_id');
            }

            if(session()->exists('search_item')){
                $search_item = session()->get('search_item');
            }

            if ( isset($input['search_vcategorycode']) && isset($input['search_radio']) && $input['search_radio'] == 'category') {
                session()->put('search_vcategorycode', $input['search_vcategorycode']);
                $search_find =   $input['search_vcategorycode'];
                $page = 1;
                $search_vcategorycode = $search_find;
                $search_vdepcode = '';
                $search_vitem_group_id = "";
                $search_item = "";
            }else if (isset($input['search_vdepcode']) && isset($input['search_radio']) && $input['search_radio'] == 'department'){
                session()->put('search_vdepcode', $input['search_vdepcode']);
                $search_find = $input['search_vdepcode'];
                $page = 1;
                $search_vdepcode = $search_find;
                $search_vitem_group_id = "";
                $search_item = "";
                $search_vcategorycode = "";
            }else if (isset($input['search_vitem_group_id']) && isset($input['search_radio']) && $input['search_radio'] == 'item_group'){
                session()->put('search_vitem_group_id', $input['search_vitem_group_id']);
                $search_find = $input['search_vitem_group_id'];
                $page = 1;
                $search_vitem_group_id = $search_find;
                $search_item = "";
                $search_vcategorycode = "";
                $search_vdepcode = "";
            }elseif(isset($input['search_item']) && !empty($input['search_item'])) {
                $search_item = $input['search_item'];
                $search_vcategorycode = '';
                $search_vdepcode = '';
                $search_vitem_group_id = "";
            }else{
                $search_item = "";
                $search_vcategorycode = '';
                $search_vdepcode = '';
                $search_vitem_group_id = "";
            }
        }



        if( session()->exists('quickupdate_search_item_type') ) {
            $filter_data = array(
    		    'search_radio'  => $search_radio,
                // 	'search_find'  => $search_find,
    			'search_item_type' => $search_item_type,
    			'search_item' => $search_item ? $search_item : '',
    			'search_vitem_group_id' => $search_vitem_group_id ? $search_vitem_group_id : '',
    			'search_vcategorycode' => $search_vcategorycode ? $search_vcategorycode : '' ,
    			'search_vdepcode' => $search_vdepcode ? $search_vdepcode : '' ,

    		);
    		$quickupdate_search_item_type = $search_item_type;
        }elseif(isset($input['page'])){
            $filter_data = array(
    		    'search_radio'  => session()->get('search_radio'),
    // 			'search_find'  => $search_find,
    			'search_item_type' => $search_item_type,
    			'search_item' => $search_item ? $search_item : '',
    			'search_vitem_group_id' => $search_vitem_group_id ? $search_vitem_group_id : '',
    			'search_vcategorycode' => $search_vcategorycode ? $search_vcategorycode : '' ,
    			'search_vdepcode' => $search_vdepcode ? $search_vdepcode : '' ,

    		);
    		$quickupdate_search_item_type = $search_item_type;
        }else{
            $filter_data = array(
    		    'search_radio'  => $search_radio,
    // 			'search_find'  => $search_find,
    			'search_item_type' => $search_item_type,
    			'search_item' => $search_item ? $search_item : '',
    			'search_vitem_group_id' => $search_vitem_group_id ? $search_vitem_group_id : '',
    			'search_vcategorycode' => $search_vcategorycode ? $search_vcategorycode : '' ,
    			'search_vdepcode' => $search_vdepcode ? $search_vdepcode : '' ,
    		);
            $quickupdate_search_item_type = $search_item_type;
        }

        $items = $model->getItems($filter_data);
        $items = $this->arrayPaginator(json_decode(json_encode($items), true), $request);
        if( session()->exists('selected_option') ) {
            $search_radio = session()->get('selected_option') ;
        } elseif ( isset($input['search_radio']) ) {
			$search_radio =  $input['search_radio'];
			$page = 1;
		}  elseif ( session()->exists('search_radio') ) {
		    $search_radio = session()->get('search_radio');
		}
		else{
			$search_radio = 'category';
        }
        session()->put('search_radio',  $search_radio);

        $new_database = '';
        if ( session()->exists('new_database') ) {
		    $new_database = session()->get('new_database');
        }
        Session::forget('selected_option');
        Session::forget('selected_option_value');
        Session::forget('quickupdate_search_item_type');
        Session::forget('page_no');
        Session::forget('quickupdate_start');
        Session::forget('quickupdate_limit');



        $data = array(
                    'item_types', 'current_url','categories',
                    'departments', 'itemGroups', 'search_radio',
                    'search_item_type', 'items', 'new_database',
                    'array_yes_no', 'search_vcategorycode', 'search_vdepcode',
                    'search_vitem_group_id', 'search_item', 'set_selected_option_session',
                    'quickupdate_search_item_type','get_categories_url'
                );
        return view('items.quickUpdateItemList',compact($data));
    }

    public function getCategories(Request $request)
	{
	    if($request && $request['depcode'])
	    {
            $model      = new quickUpdateItem();
	        $depcode = $request['depcode'];
	        $categories = $model->getCategories($depcode);
	        $options = "<option value=''>--Select Category--</option>";
	        if(empty($categories))
	        {
	            echo $options;exit;
	        }
	        foreach($categories as $value)
	        {
	            $code = $value->vcategorycode;
	            $name = $value->vcategoryname;
	            $options .= "<option value=$code> $name </option>";
	        }
	        echo $options;exit;
	    }
	}

	public function getCategories_selected(Request $request){
	    if($request && $request['depcode']){
            $model      = new quickUpdateItem();
	        $depcode = $request['depcode'];
	        $catcode = $request['catcode'];
	        $categories = $model->getCategories($depcode);
	        $options = "<option value=''>--Select Category--</option>";
	        if(empty($categories)){
	            echo $options;exit;
	        }
	        foreach($categories as $value){
	            $code = $value->vcategorycode;
	            $name = $value->vcategoryname;
	            if($code == $catcode){
	                $options .= "<option value=$code selected> $name </option>";
	            }else{
	                $options .= "<option value=$code> $name </option>";
	            }
	           // $options .= "<option value=$code> $name </option>";
	        }
	        echo $options;exit;
	    }
	}

    public function setSession(Request $request){
        $data = $request->all();

        session()->put('selected_option', $data['selected_option']);
        session()->put('selected_option_value', $data['selected_option_value']);
        session()->put('quickupdate_search_item_type', $data['quickupdate_search_item_type']);

        //==setting session for pagination filter===============
        session()->put('page_selected_option', $data['selected_option']);
        session()->put('page_selected_option_value', $data['selected_option_value']);
        session()->put('page_quickupdate_search_item_type', $data['quickupdate_search_item_type']);

        echo 'done';
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
