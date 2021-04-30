<?php

namespace App\Http\Controllers;
use App\Model\Department;
use App\Model\Category;
use App\Model\Item;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

class DepartmentController extends Controller{
    
    public function delete(Request $request) {
        // ini_set('max_execution_time', 0);
        // ini_set("memory_limit", "2G");
		$temp_arr = $request->all();
		$data = [];
        $data['error_msg'] = [];
		$sid = $request->session()->get('sid');
		
		if(isset($temp_arr['stores_hq'])){
    		foreach($temp_arr['data'] as $dept){
    		    $deptcode = $dept['vdepcode'];
    		    $stores= $temp_arr['stores_hq'];
    		    foreach($stores as $store){
    		        $dep_code = DB::connection("mysql")->select("Select vdepcode from u".$store.".mst_department where  vdepartmentname = '".$dept['vdepartmentname']."' ");
    		        foreach($dep_code as $dep_id){
    		           $departmetn_id =  $dep_id->vdepcode;
    		        }
    		      
    		        if($departmetn_id == 101){
    		            $data['error_msg'][] = 'In '.$store.' General Department cannot be deleted. Please unselect it.';
    		            break;
    		        }
    		        
    		        $item = DB::connection("mysql")->select("Select * from  u".$store.".mst_item where vdepcode = '".$departmetn_id."' ");
            		if(count($item) > 0){
            		    $data['error_msg'][] = $dept['vdepartmentname'].' Department is already assigned to  item(s). Please unselect it.';
            		}
            		
                    // 	check if the department is attached to any category
        		    $category = DB::connection("mysql")->select("Select * from  u".$store.".mst_category where dept_code = '".$departmetn_id."' ");
                    if(count($category) > 0){
            		    $data['error_msg'][] = "In the store ".$store." the".$dept['vdepartmentname'].' Department is already assigned to  category(s). Please unselect it.';
            		}
    		        
    		    }
    		}
            $counter = 0;
     	    if(count($data['error_msg'])==0){
     	        foreach($temp_arr['data'] as $dept){
     	            $stores= $temp_arr['stores_hq'];
    		        foreach($stores as $store){
    		            $dep_code = DB::connection("mysql")->select("Select idepartmentid from u".$store.".mst_department where  vdepartmentname = '".$dept['vdepartmentname']."' ");
        		        foreach($dep_code as $dep_id){
        		           $departmetn_id =  $dep_id->idepartmentid;
        		        } 
        		        
        		        DB::connection("mysql")->delete("DELETE FROM u".$store.".mst_department WHERE idepartmentid = '".$departmetn_id."' ");
    		        }
     	          //  Department::find($dept['vdepcode'])->delete();
     	            $counter++;
     	        }
     	        $data['success'] = 'Deleted '.$counter.' departments successfully.';
     	    }
    		return $data;
		    
		}else {
		    foreach($temp_arr['data'] as $dept){
		      //  dd($temp_arr['data']);
    		    $deptcode = $dept['vdepcode'];
		        $dep_code = DB::connection("mysql_dynamic")->select("Select vdepcode from mst_department where  vdepartmentname = '".$dept['vdepartmentname']."' ");
		        foreach($dep_code as $dep_id){
		           $departmetn_id =  $dep_id->vdepcode;
		        }
		      
		        if($departmetn_id == 101){
		            $data['error_msg'][] = 'In This General Department cannot be deleted. Please unselect it.';
		            break;
		        }
		        
		        //  $item = item::where ('vdepcode' , $deptcode)->count();
		        $item = DB::connection("mysql_dynamic")->select("Select * from  mst_item where vdepcode = '".$departmetn_id."' ");
        		if(count($item) > 0){
        		    $data['error_msg'][] = $dept['vdepartmentname'].' Department is already assigned to  item(s). Please unselect it.';
        		}
        		
                // 	check if the department is attached to any category
    		    //$category = category::where('dept_code' , $deptcode)->count();
    		    $category = DB::connection("mysql_dynamic")->select("Select * from mst_category where dept_code = '".$departmetn_id."' ");
                if(count($category) > 0){
        		    $data['error_msg'][] = "In this store the".$dept['vdepartmentname'].' Department is already assigned to  category(s). Please unselect it.';
        		}
    		}
            $counter = 0;
     	    if(count($data['error_msg'])==0){
     	        foreach($temp_arr['data'] as $dept){
     	          //  dd(__LINE__);
		            $dep_code = DB::connection("mysql_dynamic")->select("Select idepartmentid from mst_department where  vdepartmentname = '".$dept['vdepartmentname']."' ");
    		        foreach($dep_code as $dep_id){
    		           $departmetn_id =  $dep_id->idepartmentid;
    		        } 
    		        DB::connection("mysql_dynamic")->delete("DELETE FROM mst_department WHERE idepartmentid = '".$departmetn_id."' ");
    		        $counter++;
     	        }
     	        $data['success'] = 'Deleted '.$counter.' departments successfully.';
     	    }
    		return $data;
		}

    }

    public function index(Request $request) {
		$data['departments'] = array();
		$input = $request->all();
		$sid = $request->session()->get('sid');
		
		if (isset($input['filter_menuid'])) {
			$filter_menuid = $input['filter_menuid'];
			$data['filter_menuid'] = $input['filter_menuid'];
		}else if (isset($input['filter_menuid'])) {
			$filter_menuid = $input['filter_menuid'];
			$data['filter_menuid'] = $input['filter_menuid'];
		}else {
			$filter_menuid = null;
			$data['filter_menuid'] = null;
		}

        if (isset($input['searchbox'])) {
			$searchbox =  $input['searchbox'];
            $results = Department::where('vdepcode', $searchbox)                
						->paginate(10);
        }else{
            $results = Department::where('sid', $sid)
            ->where('estatus','Active')
            ->orderBy('vdepartmentname', 'ASC')
			->paginate(10);
		}  		
		$data['departments'] = $results;
		
		if(count($results)==0){ 
			$data['departments'] =array();
			$department_total = 0;
			$data['department_row'] =1;
		}

		$data['hours'] = array(
							'00' => '00 am',
							'01' => '01 am',
							'02' => '02 am',
							'03' => '03 am',
							'04' => '04 am',
							'05' => '05 am',
							'06' => '06 am',
							'07' => '07 am',
							'08' => '08 am',
							'09' => '09 am',
							'10' => '10 am',
							'11' => '11 am',
							'12' => '12 pm',
							'13' => '01 pm',
							'14' => '02 pm',
							'15' => '03 pm',
							'16' => '04 pm',
							'17' => '05 pm',
							'18' => '06 pm',
							'19' => '07 pm',
							'20' => '08 pm',
							'21' => '09 pm',
							'22' => '10 pm',
							'23' => '11 pm'
						);

		if (isset($input['selected'])) {
			$data['selected'] = (array)$input['selected'];
		} else {
			$data['selected'] = array();
		}        
		return view('department.department')->with($data);
        // return Self::getlist($request);
	}
	
	public function arrayPaginator($array, $request){
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
    
    // protected static function getList(Request $request) {
	// 	$input = $request->all();
	// 	$department = new Department();
    //     if (isset($input['filter_menuid'])) {
	// 		$filter_menuid = $input['filter_menuid'];
	// 		$data['filter_menuid'] = $input['filter_menuid'];
	// 	}else if (isset($input['filter_menuid'])) {
	// 		$filter_menuid = $input['filter_menuid'];
	// 		$data['filter_menuid'] = $input['filter_menuid'];
	// 	}else {
	// 		$filter_menuid = null;
	// 		$data['filter_menuid'] = null;
	// 	}

	// 	if (isset($input['sort'])) {
	// 		$sort = $input['sort'];
	// 	} else {
	// 		$sort = 'idepartmentid';
	// 	}

	// 	// $data['add'] = $this->url->link('administration/department/add', 'token=' . $this->session->data['token'] . $url, true);
	// 	// $data['edit'] = $this->url->link('administration/department/edit', 'token=' . $this->session->data['token'] . $url, true);
	// 	// $data['delete'] = $this->url->link('administration/department/delete', 'token=' . $this->session->data['token'] . $url, true);
	// 	// $data['edit_list'] = $this->url->link('administration/department/edit_list', 'token=' . $this->session->data['token'] . $url, true);

	// 	// $data['current_url'] = $this->url->link('administration/department', 'token=' . $this->session->data['token'], true);
	// 	// $data['searchdepartment'] = $this->url->link('administration/department/search', 'token=' . $this->session->data['token'], true);
		
	// 	$data['departments'] = array();

	// 	$filter_data = array(
	// 		'filter_menuid'  => $filter_menuid,
	// 	);
		
	// 	$department_total = $department->getTotalDepartments($filter_data);

	// 	$results = $department->getDepartments($filter_data);

	// 	foreach ($results as $result) {
			
	// 		$data['departments'][] = array(
	// 			'idepartmentid'   => $result['idepartmentid'],
	// 			'vdepcode' 		  => $result['vdepcode'],
	// 			'vdepartmentname' => $result['vdepartmentname'],
	// 			'vdescription' 	  => $result['vdescription'],
	// 			'starttime' 	  => $result['starttime'],
	// 			'endtime' 	      => $result['endtime'],
	// 			'isequence'       => $result['isequence'],
	// 		);
	// 	}
		
	// 	if(count($results)==0){ 
	// 		$data['departments'] =array();
	// 		$department_total = 0;
	// 		$data['department_row'] =1;
	// 	}

	// 	$data['hours'] = array(
	// 						'00' => '00 am',
	// 						'01' => '01 am',
	// 						'02' => '02 am',
	// 						'03' => '03 am',
	// 						'04' => '04 am',
	// 						'05' => '05 am',
	// 						'06' => '06 am',
	// 						'07' => '07 am',
	// 						'08' => '08 am',
	// 						'09' => '09 am',
	// 						'10' => '10 am',
	// 						'11' => '11 am',
	// 						'12' => '12 pm',
	// 						'13' => '01 pm',
	// 						'14' => '02 pm',
	// 						'15' => '03 pm',
	// 						'16' => '04 pm',
	// 						'17' => '05 pm',
	// 						'18' => '06 pm',
	// 						'19' => '07 pm',
	// 						'20' => '08 pm',
	// 						'21' => '09 pm',
	// 						'22' => '10 pm',
	// 						'23' => '11 pm'
	// 					);

	// 	if (isset($input['selected'])) {
	// 		$data['selected'] = (array)$input['selected'];
	// 	} else {
	// 		$data['selected'] = array();
	// 	}

	// 	if (isset($input['filter_menuid'])) {
	// 		$url .= '&filter_menuid=' . urlencode(html_entity_decode($input['filter_menuid'], ENT_QUOTES, 'UTF-8'));
	// 	}
	// 	// echo "<pre>";
	// 	// print_r($data);
	// 	// die;
	// 	// $data['results'] = sprintf(($department_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($department_total - $this->config->get('config_limit_admin'))) ? $department_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $department_total, ceil($department_total / $this->config->get('config_limit_admin')));

	// 	return view('department.department')->with($data);
	// }
	
	public function edit_list(Request $request) {
	    
		$sid = $request->session()->get('sid');
		$inputs = $request->all();
        // dd($inputs);
		//$inputs['sid'] = $sid;
		
        if ($request->isMethod('post')) {
            foreach($inputs['department'] as $k => $v){ 
    	        $dept_id = $v['idepartmentid']; 
    	        $dept_name = $v['vdepartmentname'];
    	        if(empty(trim($dept_name))){
          		    return redirect(route('department'))->with('error_message','The department name should not be blank');
    	        }
    
    	        $query = "SELECT * FROM mst_department WHERE vdepartmentname = '".$dept_name."'";
    	        $return_data = DB::connection('mysql_dynamic')->select($query);
                $run_query = json_decode(json_encode($return_data), true); 
                
                $dataCount = count($run_query);
    	        
    	       // check if the department id = 0 
    	        if($dept_id == 0){
    	            //======= insert =======
    	            if($dataCount > 0){
    	                return redirect(route('department'))->with('error_message','The name of that department already exists.');
    	            }
    	            
    	        } else {
    	            //======= update ======
    	            if($dataCount == 1 && $dept_id != $run_query[0]['idepartmentid']){
    	                return redirect(route('department'))->with('error_message','One of the selected departments has a name that is already existing.');
    	            }
    	        }
	        }
            
            
			$department = new Department();
			$department->editDepartmentList($inputs);
			return redirect(route('department'))->with('message','Successfully Updated!!');
        }
    }
    
	public function add_list(Request $request) {
		$sid = $request->session()->get('sid');
		$input = $request->all();
		
		//dd($input);
		$input['sid'] = $sid;
		$dept_name = $input['department'][0]['vdepartmentname'];
		
        if ($request->isMethod('post')) {
             if(empty(trim($dept_name))){
      		    return redirect(route('department'))->with('error_message','The department name should not be blank');
	        }
			$department = new Department();
			$query = "SELECT * FROM mst_department WHERE vdepartmentname = '{$dept_name}'";
	        $return_data = DB::connection('mysql_dynamic')->select($query);
            $run_query = json_decode(json_encode($return_data), true); 
            $dataCount = count($run_query);
            if($dataCount > 0){
	           return redirect(route('department'))->with('error_message','The name of that department already exists.');
	        }else{
	            $department->addDeparmentList($input);
			    return redirect(route('department'))->with('message','Deparment Added Successfully!!');    
	        }
        }
    }

    public function departmentsearch(Request $req){    
        $input = $req->all();
        if(isset($input['q']) && !empty($input['q'])){
          $department = Department::where('vdepartmentname','LIKE','%'.$req->input('q').'%')->get();
          return response()->json(compact('department'));
         }
         else{
              $search='';
              $department = Department::where('vdepartmentname','LIKE','%'.$search.'%')->get();
		}  		
          return response()->json(compact('department'));
	} 
	
    //     public function search(Request $request){
    // 		$input = $request->all();
    // 		$return = array();
    // 		$department = new Department();
    // 		if(isset($input['term']) && !empty($input['term'])){
    // 			$datas = $department->getDepartmentSearch($input['term']);
    // 			foreach ($datas as $key => $value) {
    // 				$temp = array();
    // 				$temp['idepartmentid'] = $value['idepartmentid'];
    // 				$temp['vdepartmentname'] = $value['vdepartmentname'];
    // 				$return[] = $temp;
    // 			}
    // 		}
    // 		return response()
    //             ->json($return);
    //     }
    
    // public function get_categories(Request $request){
	// 	$input = $request->all();
	// 	$this->load->model('api/department');
    //     $department_id = $input['department_id'];
        
    //     $data = $this->model_api_department->get_categories($department_id);
        
    //     echo json_encode($data);
	    
    // }
    
    public function check_for_duplicates($inputs){
		 foreach($inputs['department'] as $k => $v){
	        $dept_id = $v['idepartmentid'];
	        $dept_name = $v['vdepartmentname'];
	        
	        if(empty(trim($dept_name))){
      		    return redirect(route('department'))->with('message','The department name should not be blank');
	        }

	        $query = "SELECT * FROM mst_department WHERE vdepartmentname = '{$dept_name}'";
	        $return_data = DB::connection('mysql_dynamic')->select($query);
            $run_query = json_decode(json_encode($return_data), true); 
            
            $dataCount = count($run_query);
	        
	       // check if the department id = 0 
	        if($dept_id == 0){
	            //======= insert =======
	            if($dataCount > 0){
	                return redirect(route('department'))->with('message','The name of that department already exists.');
	            }
	            
	        } else {
	            //======= update ======
	            if($dataCount == 1 && $dept_id != $run_query[0]['idepartmentid']){
	                return redirect(route('department'))->with('message','One of the selected departments has a name that is already existing.');
	            }
	        }
	    }
    }
    
    public function duplicatedepartment(Request $request){
        $input = $request->all(); 
        $depvalues = $input['avArr'];
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        $department = '';
        foreach($depvalues as $k => $value ){
            $department_name = DB::connection("mysql_dynamic")->select("Select * from mst_department where vdepcode = '".$value['vdepcode']."' "); 
            foreach($department_name as $vdep){
                $department = $vdep->vdepartmentname;
            }
            
            foreach($stores as $store){
                $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_department where vdepartmentname = '".$department."' "); 
                if(count($query) == 0){
                  array_push($notExistStore, $store->id); 
                }
            }
        }
        return $notExistStore;
    }

}
