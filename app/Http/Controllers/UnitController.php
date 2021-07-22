<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Unit;
use Svg\Tag\Rect;
use DB;


class UnitController extends Controller
{
	private $error = array();
	public function index(Request $request) {
		return $this->getList($request);
	}
	public function edit_list(Request $request) {
        $input = $request->all();
    	//   dd($input);
        $model = new Unit();
		// if (($request->isMethod('POST')) && $this->validateEditList()) {
		if (($request->isMethod('POST'))) {
            
			$arr_with_iunitid= array();
			$arr_without_iunitid = array();
			$arr_with_stores_hq = array();
			foreach ($input['unit'] as $key => $value) {
				if(isset($value['iunitid']) && $value['iunitid'] == 0){
					$arr_without_iunitid[] = $value;
				}else{
					$arr_with_iunitid[] = $value;
				}
				
			}
			
            $already_exists = [];
            $error_string = "";
            $counter = 0;
            
			foreach ($arr_without_iunitid as $k => $v) {
			    $v['stores_hq'] = isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid')  ;
			    $response = $model->addUnits($v);
                $response = json_decode(json_encode($response), true);
			    if(isset($response['error'])){
			        $counter++;
			        $error_string .= $counter.". ".$v['vunitname'].PHP_EOL;
		        }
			} 
			if($error_string !== ""){
			 //   $this->error['warning']="The following unit(s) already exist: ".PHP_EOL.$error_string;
			    $this->error['warning']="This unit already exists ";
			}
			else{
			    
			    $stores = isset($input['stores_hq']) ? $input['stores_hq'] : session()->get('sid')  ;
    			foreach ($arr_with_iunitid as $k => $v) {
    			    
    				$return = $model->editlistUnits($v['iunitid'],$v, $stores);

					if(isset($return['error']) && $return['error'] !== ""){
						
						session()->put('error_warning', $return['error']);
						return redirect(route('unit'));
					}
    			}
    			
                session()->put('success', "unit updated successfully!");
                return redirect(route('unit'));
    		}
		}
    	return $this->getList($request);
  	}
  	
	protected function getList(Request $request) {
        $input = $request->all();
        $model = new Unit();
		if (isset($input['sort'])) {
			$sort = $input['sort'];
		} else {
			$sort = 'iunitid';
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
			'text' => "Units",
			'href' => url('/unit')
		);
		$data['add'] = url('/unit/add');
		$data['edit'] = url('/unit/edit');
		$data['delete'] =url('/unit/delete');
		$data['edit_list'] = url('/unit/edit_list');
		$data['current_url'] = url('/unit');
		$data['searchunit'] = url('/unit/search');
		$data['units'] = array();
		$filter_data = array(
			'searchbox'  => $searchbox,
			// 'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			// 'limit' => $this->config->get('config_limit_admin')
		);
	
		$unit_data = $model->getUnits($filter_data);
		$unit_total = $model->getUnitsTotal($filter_data);
        $unit_data = json_decode(json_encode($unit_data), true);
		$data['units'] = $this->arrayPaginator($unit_data, $request);
		if(count($unit_data)==0){ 
			$data['units'] =array();
			$location_total = 0;
			$data['unit_row'] =1;
		}
		$data['heading_title'] = "Unit";
		$data['text_list'] = "Unit";
		$data['text_no_results'] = "No Results Found";
		$data['text_confirm'] = "";
		$data['column_unit_code'] = "Code";
		$data['column_unit_name'] = "Name";
		$data['column_unit_description'] = "Description";
		$data['column_unit_status'] = "Status";
		$data['Active'] = "Active";
		$data['Inactive'] = "Inactive";
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
		if (session()->exists('error_warning')) {
			$data['error_warning'] = session()->get('error_warning');
			Session::forget('error_warning');
		} else {
			$data['error_warning'] = '';
		}
		if (session()->exists('success')) {
			$data['success'] = session()->get('success');
            Session::forget('success');
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
		
		$data['pagination'] ="";
		
        $data['results'] ="";
		$data['header'] = "";
		$data['column_left'] = "";
		$data['footer'] = "";
        return view('unit.unit_list', $data);
	}
	
	protected function validateEditList() {
    	if(!$this->user->hasPermission('modify', 'administration/units')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
  	
  	public function search(Request $request){
        $return = array();
        $input = $request->all();
		$model = new Unit();
		if(isset($input['term']) && !empty($input['term'])){
			$datas = $model->getUnitSearch($input['term']);
            $datas = json_decode(json_encode($datas), true);
			foreach ($datas as $key => $value) {
				$temp = array();
				$temp['iunitid'] = $value['iunitid'];
				$temp['vunitname'] = $value['vunitname'];
				$return[] = $temp;
			}
		}
		
		return response(json_encode($return), 200)
        ->header('Content-Type', 'application/json');
	}
	
	public function delete(Request $request) {
	    $temp_arr = $request->all();
	   // dd($temp_arr['data']);
	    $data = [];
        $data['error_msg'] = [];
		$sid = $request->session()->get('sid');
		if(isset($temp_arr['stores_hq']) ){
		    foreach($temp_arr['data'] as $unit){
    		    $stores= $temp_arr['stores_hq'];
    		    foreach($stores as $store){
    		        $unit_code = DB::connection("mysql")->select("Select vunitcode from u".$store.".mst_unit where  vunitname = '".$unit['vunitname']."'");
    		        foreach($unit_code as $unit_id){
    		            $unitid = $unit_id->vunitcode;
    		        }
    		        //  $item = item::where ('vdepcode' , $deptcode)->count();
    		        $item = DB::connection("mysql")->select("Select * from  u".$store.".mst_item where vunitcode = '".$unitid."' ");
            		if(count($item) > 0){
            		    $data['error_msg'][] = $unit['vunitname'].' Department is already assigned to  item(s) in store (u'.$store.'). Please unselect it.';
            		}
    		    }
    		}
    			// check if the $data['error_msg'] is empty; if it is empty start deleting
    		$counter = 0;
     	    if(count($data['error_msg']) == 0){
     	      //  dd(__LINE__);
     	        foreach($temp_arr['data'] as $unit){
     	            $stores= $temp_arr['stores_hq'];
    		        foreach($stores as $store){
    		            $unit_code = DB::connection("mysql")->select("Select iunitid from u".$store.".mst_unit where  vunitname = '".$unit['vunitname']."'");
        		        foreach($unit_code as $unit_id){
        		            $unitid = $unit_id->iunitid;
        		        } 
        		        DB::connection("mysql")->delete("DELETE FROM u".$store.".mst_unit WHERE iunitid = '".$unitid."' ");
    		        }
     	          
     	            $counter++;
     	        }
     	        
     	        $data['success'] = 'Deleted '.$counter.' units (S) successfully.';
     	    }
		}else{
		    
		    foreach($temp_arr['data'] as $unit){
		        $unit_code = DB::connection("mysql_dynamic")->select("Select vunitcode from mst_unit where  vunitname = '".$unit['vunitname']."'");
		        foreach($unit_code as $unit_id){
		            $unitid = $unit_id->vunitcode;
		        }
		        //  $item = item::where ('vdepcode' , $deptcode)->count();
		        $item = DB::connection("mysql_dynamic")->select("Select * from  mst_item where vunitcode = '".$unitid."' ");
        		if(count($item) > 0){
        		    $data['error_msg'][] = $unit['vunitname'].' Department is already assigned to  item(s). Please unselect it.';
        		}
    		    
    		}
    			// check if the $data['error_msg'] is empty; if it is empty start deleting
    		$counter = 0;
     	    if($data['error_msg']==[]){
     	        foreach($temp_arr['data'] as $unit){
		            $unit_code = DB::connection("mysql_dynamic")->select("Select iunitid from mst_unit where  vunitname = '".$unit['vunitname']."'");
    		        foreach($unit_code as $unit_id){
    		            $unitid = $unit_id->iunitid;
    		        }
    		        DB::connection("mysql_dynamic")->delete("DELETE FROM mst_unit WHERE iunitid = '".$unitid."' ");
    		        
     	            $counter++;
     	        }
     	        
     	        $data['success'] = 'Deleted '.$counter.' units (S) successfully.';
     	    }
		}
		
		
	
		return $data;
        
    }
		
		
	public function add() {
	    
		$json =array();
		$this->load->model('api/units');
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		    
          $temp_arr = json_decode(file_get_contents('php://input'), true);
          
			$data = $this->model_api_units->addUnits($temp_arr);
		    echo json_encode($data);
			exit;
		}else{
			$data['error'] = 'Something went wrong';
			// http_response_code(401);
			$this->response->addHeader('Content-Type: application/json');
		    echo json_encode($data);
			exit;
		}
    }
    
    public function duplicateunit(Request $request){
        $input = $request->all(); 
        $depvalues = $input['avArr'];
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        foreach($depvalues as $k => $value ){
            foreach($stores as $store){
                $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_unit where vunitname = '".$value['vunitname']."' "); 
                if(count($query) == 0){
                  array_push($notExistStore, $store->id); 
                }
            }
        }
        return $notExistStore;
    }
    
    public function duplicateunitforedit(Request $request){
        $input = $request->all(); 
        $depvalues = $input['avArr'];
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        foreach($depvalues as $k => $value ){
            $units = DB::connection("mysql_dynamic")->select("select * from mst_unit where iunitid = '".$value['iunitid']."' ");
            foreach($units as $unit){
                $unit_name = $unit->vunitname;
            }
            foreach($stores as $store){ 
                $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_unit where vunitname = '".$unit_name."' "); 
                if(count($query) == 0){
                  array_push($notExistStore, $store->id); 
                }
            }
        }
        return $notExistStore;
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
