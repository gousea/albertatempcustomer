<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\ZeroMovement;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use PDF;
use DateTime;

class ZeroMovementController extends Controller{

    public function index(Request $request) {
        $input = $request->all();
        // echo "<pre>";
        // print_r($input);
        // die;
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $zeromovement = new ZeroMovement();
        
		$url = '';
        $data = array();
        $data['list'] = array();
        if ($request->isMethod('get')) {
            
            if(isset($input['vdepcode'])){
                
                $data['vdepcode'] = $input['vdepcode'];
                // Session::put('visited_vdepcode', $input['vdepcode']);
                if($input['vdepcode'] == 'All'){
                    
                    $data['categories'] = $zeromovement->getCategories();
                }else{
                     $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
                }
            }else{
                $data['vdepcode'] = '';
            }
            
            if(isset($input['vcategorycode'])){
                // Session::put('visited_vcategorycode', $input['vcategorycode']);
                $data['vcategorycode'] = $input['vcategorycode'];
                
                if($input['vcategorycode'] == 'All'){
			        $data['subcategories'] = $zeromovement->getSubCategories2();
                }else{
                    $data['subcategories'] = $zeromovement->getcat_SubCategories(isset($input['subcat_id']));
                }
                
            }else{
                $data['vcategorycode'] = '';
            }
            
            if(isset($input['subcat_id'])){
                // Session::put('visited_subcat_id', $input['subcat_id']);
                $data['subcat_id'] = $input['subcat_id'];
            }else{
                $data['subcat_id'] = '';
            }
            
                $start_date_date = date("m-d-Y");
                $start_date_date = strtotime(date("m-d-Y", strtotime($start_date_date)) . "-3 months");
                $start_date_date = date("m-d-Y",$start_date_date);
                $end_date_date = date("m-d-Y");
        
            $data['p_start_date'] = $start_date_date;
            $data['p_end_date'] = $end_date_date;
            
            $param_data = array(
                    'p_start_date'  => $data['p_start_date'],
                    'p_end_date'    => $data['p_end_date'],
                    'vdepcode'      => $data['vdepcode'],
                    'vcategorycode' => $data['vcategorycode'],
                    'subcat_id'     => $data['subcat_id']
                );
            
            $param = http_build_query($param_data);
           
             $reportsdata = $zeromovement->get_zero_movement_report($param_data);
             foreach ($reportsdata as $result) {
    			
    			$data['list'][]= array(
    				'ItemName'     => $result['ItemName'],
    				'SKU'   => $result['SKU'],
    				'QOH'=>$result['QOH'],
    				'Cost'=>number_format($result['Cost'],2),
    				'Price'=>number_format($result['Price'],2),
    				'TotalCost'=>number_format($result['TotalCost'],2),
    				'TotalPrice'=>number_format($result['TotalPrice'],2),
    				'GPP'=>number_format($result['GPP'],2),
    				'itemid'=>$result['iitemid'],
    				'item_link'  => url('/item/edit/'. $result['iitemid'].'?visited_zero_movement_report=Yes'),  
    			);
                
                Session::put('inputs', $param_data);
                $data['zeromovement_reports'] = $data['list'];
                Session::put('zeromovement_reports', $data['zeromovement_reports']);
                Session::put('p_start_date', $data['p_start_date']);
                Session::put('p_end_date', $data['p_end_date']);
                $request->session()->forget('visited_zero_movement_report');
    		}
            
            
            //Pagination add
            $zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
    		$data['results'] = $zero_movement_report_total;            
          
        }elseif(isset($input['p_start_date']) && isset($input['p_end_date']) && isset($input['vdepcode'])){

			if(isset($input['vdepcode'])){
                $data['vdepcode'] = $input['vdepcode'];                
                if($input['vdepcode'] == 'All'){                    
                    $data['categories'] = $zeromovement->getCategories();
                }else{
                     $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
                }
                
            }else{
                $data['vdepcode'] = '';
            }
            
            if(isset($input['vcategorycode'])){
                    
                $data['vcategorycode'] = $input['vcategorycode'];
                
                if($input['vcategorycode'] == 'All'){
			        $data['subcategories'] = $zeromovement->getSubCategories2();
                }else{
                    $data['subcategories'] = $zeromovement->getSubCategories($input['subcat_id']);
                }
            }else{
                $data['vcategorycode'] = '';
            }
            
            if(isset($input['subcat_id'])){
                
                $data['subcat_id'] = $input['subcat_id'];
            }else{
                $data['subcat_id'] = '';
            }
            
            $data['p_start_date'] = $input['p_start_date'];
            $data['p_end_date'] = $input['p_end_date'];
            
			$param_data = array(
                    'p_start_date'  => $data['p_start_date'],
                    'p_end_date'    => $data['p_end_date'],
                    'vdepcode'      => $data['vdepcode'],
                    'vcategorycode' => $data['vcategorycode'],
                    'subcat_id'     => $data['subcat_id']
                );
            
            $param = http_build_query($param_data);
            $reportsdata = $zeromovement->get_zero_movement_report($param_data);
            
            foreach ($reportsdata as $result) {
    			
                $data['list'][]= array(
                	'ItemName'     => $result['ItemName'],
                	'SKU'   => $result['SKU'],
                	'QOH'=>$result['QOH'],
                	'Cost'=>number_format($result['Cost'],2),
                	'Price'=>number_format($result['Price'],2),
                	'TotalCost'=>number_format($result['TotalCost'],2),
                	'TotalPrice'=>number_format($result['TotalPrice'],2),
                	'GPP'=>number_format($result['GPP'],2),
                	'itemid'=>$result['iitemid'],
                	'item_link'  => url('/item/edit/'. $result['iitemid'].'?visited_zero_movement_report=Yes'),  
                );
                
                Session::put('inputs', $param_data);
                $data['zeromovement_reports'] = $data['list'];
                Session::put('zeromovement_reports', $data['zeromovement_reports']);
                Session::put('p_start_date', $data['p_start_date']);
                Session::put('p_end_date', $data['p_end_date']);
                $request->session()->forget('visited_zero_movement_report');
    		}
                
            $input = $param_data;

    		$zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
    		$data['results'] = $zero_movement_report_total;      
            
	    }elseif(isset($input['p_start_date']) && isset($input['p_end_date']) && isset($input['vdepcode'])){
            
            if(isset($input['vdepcode'])){
                
                $data['vdepcode'] = $input['vdepcode'];                
                if($input['vdepcode'] == 'All'){                    
                    $data['categories'] = $zeromovement->getCategories();
                }else{
                     $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
                }
            }else{
                $data['vdepcode'] = '';
            }
            
            if(isset($input['vcategorycode'])){
                    
                $data['vcategorycode'] = $input['vcategorycode'];
                
                if($input['vcategorycode'] == 'All'){
			        $data['subcategories'] = $zeromovement->getSubCategories2();
                }else{
                    $data['subcategories'] = $zeromovement->getSubCategories($input['subcat_id']);
                }
            }else{
                $data['vcategorycode'] = '';
            }
            
            if(isset($input['subcat_id'])){                
                $data['subcat_id'] = $input['subcat_id'];
            }else{
                $data['subcat_id'] = '';
            }
            
            $data['p_start_date'] = $input['p_start_date'];
            $data['p_end_date'] = $input['p_end_date'];
            
			$param_data = array(
                    'p_start_date'  => $data['p_start_date'],
                    'p_end_date'    => $data['p_end_date'],
                    'vdepcode'      => $data['vdepcode'],
                    'vcategorycode' => $data['vcategorycode'],
                    'subcat_id'     => $data['subcat_id']
                );
            
            $param = http_build_query($param_data);
            
            $reportsdata = $zeromovement->get_zero_movement_report($param_data);
            
            foreach ($reportsdata as $result) {
    			
                $data['list'][]= array(
                	'ItemName'     => $result['ItemName'],
                	'SKU'   => $result['SKU'],
                	'QOH'=>$result['QOH'],
                	'Cost'=>number_format($result['Cost'],2),
                	'Price'=>number_format($result['Price'],2),
                	'TotalCost'=>number_format($result['TotalCost'],2),
                	'TotalPrice'=>number_format($result['TotalPrice'],2),
                	'GPP'=>number_format($result['GPP'],2),
                	'itemid'=>$result['iitemid'],
                	'item_link'  => url('/item/edit/'. $result['iitemid'].'?visited_zero_movement_report=Yes'),  
                );
                
                
                Session::put('inputs', $param_data);
                $data['zeromovement_reports'] = $data['list'];
                Session::put('zeromovement_reports', $data['zeromovement_reports']);
                Session::put('p_start_date', $data['p_start_date']);
                Session::put('p_end_date', $data['p_end_date']);
                $request->session()->forget('visited_zero_movement_report');
    		}
                
            $input = $param_data;
            
            $zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
    		$data['results'] = $zero_movement_report_total;      
                
	    }elseif(isset($input['visited_zero_movement_report'])=="Yes"){
            
            $input = $request->session()->get('inputs');
            
            $param_data = array(
                    'p_start_date'  => $input['p_start_date'],
                    'p_end_date'    => $input['p_end_date'],
                    'vdepcode'      => $input['vdepcode'],
                    'vcategorycode' => $input['vcategorycode'],
                    'subcat_id'     => $input['subcat_id']
                );
            
            $param = http_build_query($param_data);

            $reportsdata = $zeromovement->get_zero_movement_report($param_data);
            foreach ($reportsdata as $result) {
			
                $data['list'][]= array(
                	'ItemName'     => $result['ItemName'],
                	'SKU'   => $result['SKU'],
                	'QOH'=>$result['QOH'],
                	'Cost'=>number_format($result['Cost'],2),
                	'Price'=>number_format($result['Price'],2),
                	'TotalCost'=>number_format($result['TotalCost'],2),
                	'TotalPrice'=>number_format($result['TotalPrice'],2),
                	'GPP'=>number_format($result['GPP'],2),
                	'itemid'=>$result['iitemid'],
                	'item_link'  => url('/item/edit/'. $result['iitemid'].'?visited_zero_movement_report=Yes'),  
                );
                        
                Session::put('inputs', $param_data);
                $data['zeromovement_reports'] = $data['list'];
                Session::put('zeromovement_reports', $data['zeromovement_reports']);
                $data_list= $request->session()->get('inputs');               
                $data['p_start_date'] = $data_list['start_date'];
                $data['p_end_date'] = $data_list['end_date'];
                
                Session::put('p_start_date', $data['p_start_date']);
                Session::put('p_end_date', $data['p_end_date']);
                $request->session()->forget('visited_zero_movement_report');                
            }
            
            $zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
    		$data['results'] = $zero_movement_report_total;      

            if(isset($input['vdepcode'])){
                
                $data['vdepcode'] = $input['vdepcode'];
                
                if($input['vdepcode'] == 'All'){
                    
                    $data['categories'] = $zeromovement->getCategories();
                }else{
                     $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
                }
            }else{
                $data['vdepcode'] = '';
            }
            
            if(isset($input['vcategorycode'])){
                    
                $data['vcategorycode'] = $input['vcategorycode'];
                
                if($input['vcategorycode'] == 'All'){
			        $data['subcategories'] = $zeromovement->getSubCategories2();
                }else{
                    $data['subcategories'] = $zeromovement->getSubCategories($input['subcat_id']);
                }
            }else{
                $data['vcategorycode'] = '';
            }
            
            if(isset($input['subcat_id'])){
                
                $data['subcat_id'] = $input['subcat_id'];
            }else{
                $data['subcat_id'] = '';
            }
        }      
        
        Session::put('desc_title', isset($data['desc_title']));
        $data['byreports'] = array(
                        1 => 'Category',
                        2 => 'Department',
                        3 => 'Item Group'
                      );
                      
        $store_data=$zeromovement->getStore();
        $store = $store_data[0];
        $request->session()->put('storename', $store['vstorename']);
        $request->session()->put('storeaddress', $store['vaddress1']);
        $request->session()->put('storephone', $store['vphone1']);
      
        $data['store_name'] = $request->session()->get('storename'); 
        $data['storename'] = $request->session()->get('storename'); 
        $data['storeaddress'] = $request->session()->get('storeaddress'); 
        $data['storephone'] = $request->session()->get('storephone'); 
        
		//new code 
		$departments = $zeromovement->getDepartments();       
        $data['departments'] = $departments;
        
        // echo "<pre>";
        // print_r($data);
        // die;
        // $visited_vdepcode = $request->session()->get('visited_vdepcode'); 
        // $visited_vcategorycode = $request->session()->get('visited_vcategorycode'); 
        // $visited_subcat_id = $request->session()->get('visited_subcat_id'); 
        
        // if($data['vdepcode'] == '' || $data['vdepcode'] == null){
        //     $data['vdepcode'] = $visited_vdepcode;
        //     $data['vcategorycode'] = $visited_vcategorycode;
        //     $data['subcat_id'] = $visited_subcat_id;
        // }else{
        //     $request->session()->forget('vdepcode');
        //     $request->session()->forget('vcategorycode');
        //     $request->session()->forget('subcat_id');
        // }
        
        // $url_data = $request->path();
        // if($request->path() == 'zeromovementreport'){
        //     $request->session()->forget('vdepcode');
        //     $request->session()->forget('vcategorycode');
        //     $request->session()->forget('subcat_id');
        // }else{
        // if($request->path() == '?visited_zero_movement_report=Yes'){
        //     $departments = $zeromovement->getDepartments();       
        //     $data['departments'] = $departments;
        //     $data['subcategories'] = $zeromovement->getSubCategories2();
        //     $data['categories'] = $zeromovement->getCategories();
        // }
        if($data['list']){
            $report_data_pagination = $data['list'];
            $data['zeromovement_reports'] = $this->arrayPaginator($report_data_pagination, $request);    
        }
        return view('zeroreport.zeroreport')->with($data);
    }
    
    private function arrayPaginator($array, $request){
        $page = $request->get('page', 1);
        $perPage = 30;
        $offset = ($page * $perPage) - $perPage;
        
        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]);
    }
    
//     protected static function getList(Request $request) {
//         $input = $request->all();
//         ini_set('max_execution_time', 0);
//         ini_set("memory_limit", "2G");
//         $zeromovement = new ZeroMovement();
        
// 		$url = '';
//         $data = array();
//         if ($request->isMethod('post')) {
            
//             if(isset($input['vdepcode'])){
                
//                 $data['vdepcode'] = $input['vdepcode'];
                
//                 if($input['vdepcode'] == 'All'){
                    
//                     $data['categories'] = $zeromovement->getCategories();
//                 }else{
//                      $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
//                 }
//             }else{
//                 $data['vdepcode'] = '';
//             }
            
//             if(isset($input['vcategorycode'])){
                    
//                 $data['vcategorycode'] = $input['vcategorycode'];
                
//                 if($input['vcategorycode'] == 'All'){
// 			        $data['subcategories'] = $zeromovement->getSubCategories2();
//                 }else{
//                     $data['subcategories'] = $zeromovement->getcat_SubCategories($input['subcat_id']);
//                 }
                
//             }else{
//                 $data['vcategorycode'] = '';
//             }
            
//             if(isset($input['subcat_id'])){
                
//                 $data['subcat_id'] = $input['subcat_id'];
//             }else{
//                 $data['subcat_id'] = '';
//             }
            
//             $data['p_start_date'] = $input['start_date'];
//             $data['p_end_date'] = $input['end_date'];
            
//             $param_data = array(
//                     'p_start_date'  => $data['p_start_date'],
//                     'p_end_date'    => $data['p_end_date'],
//                     'vdepcode'      => $data['vdepcode'],
//                     'vcategorycode' => $data['vcategorycode'],
//                     'subcat_id'     => $data['subcat_id']
//                 );
            
//             $param = http_build_query($param_data);
           
//              $reportsdata = $zeromovement->get_zero_movement_report($param_data);
//              foreach ($reportsdata as $result) {
    			
//     			$data['list'][]= array(
//     				'ItemName'     => $result['ItemName'],
//     				'SKU'   => $result['SKU'],
//     				'QOH'=>$result['QOH'],
//     				'Cost'=>number_format($result['Cost'],2),
//     				'Price'=>number_format($result['Price'],2),
//     				'TotalCost'=>number_format($result['TotalCost'],2),
//     				'TotalPrice'=>number_format($result['TotalPrice'],2),
//     				'GPP'=>number_format($result['GPP'],2),
//     				'itemid'=>$result['iitemid'],
//     				'item_link'=> '/item/edit/'.$result['iitemid'],
//     			);    		   
                
//                 Session::put('inputs', $param_data);
//                 $data['reports'] = $data['list'];
//                 Session::put('reports', $data['reports']);
//                 Session::put('p_start_date', $data['p_start_date']);
//                 Session::put('p_end_date', $data['p_end_date']);
//                 $request->session()->forget('visited_zero_movement_report');
//     		}
            
            
//             //Pagination add
//             $zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
//     		$data['results'] = $zero_movement_report_total;            
          
//         }elseif(isset($input['p_start_date']) && isset($input['p_end_date']) && isset($input['vdepcode'])){

// 			if(isset($input['vdepcode'])){
//                 $data['vdepcode'] = $input['vdepcode'];                
//                 if($input['vdepcode'] == 'All'){                    
//                     $data['categories'] = $zeromovement->getCategories();
//                 }else{
//                      $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
//                 }
                
//             }else{
//                 $data['vdepcode'] = '';
//             }
            
//             if(isset($input['vcategorycode'])){
                    
//                 $data['vcategorycode'] = $input['vcategorycode'];
                
//                 if($input['vcategorycode'] == 'All'){
// 			        $data['subcategories'] = $zeromovement->getSubCategories2();
//                 }else{
//                     $data['subcategories'] = $zeromovement->getSubCategories($input['subcat_id']);
//                 }
//             }else{
//                 $data['vcategorycode'] = '';
//             }
            
//             if(isset($input['subcat_id'])){
                
//                 $data['subcat_id'] = $input['subcat_id'];
//             }else{
//                 $data['subcat_id'] = '';
//             }
            
//             $data['p_start_date'] = $input['p_start_date'];
//             $data['p_end_date'] = $input['p_end_date'];
            
// 			$param_data = array(
//                     'p_start_date'  => $data['p_start_date'],
//                     'p_end_date'    => $data['p_end_date'],
//                     'vdepcode'      => $data['vdepcode'],
//                     'vcategorycode' => $data['vcategorycode'],
//                     'subcat_id'     => $data['subcat_id']
//                 );
            
//             $param = http_build_query($param_data);
//             $reportsdata = $zeromovement->get_zero_movement_report($param_data);
            
//             foreach ($reportsdata as $result) {
    			
//                 $data['list'][]= array(
//                 	'ItemName'     => $result['ItemName'],
//                 	'SKU'   => $result['SKU'],
//                 	'QOH'=>$result['QOH'],
//                 	'Cost'=>number_format($result['Cost'],2),
//                 	'Price'=>number_format($result['Price'],2),
//                 	'TotalCost'=>number_format($result['TotalCost'],2),
//                 	'TotalPrice'=>number_format($result['TotalPrice'],2),
//                 	'GPP'=>number_format($result['GPP'],2),
//                 	'itemid'=>$result['iitemid'],
//                 	'item_link'=> '/item/edit/'.$result['iitemid'],
//                 );
                
//                 Session::put('inputs', $param_data);
//                 $data['reports'] = $data['list'];
//                 Session::put('reports', $data['reports']);
//                 Session::put('p_start_date', $data['p_start_date']);
//                 Session::put('p_end_date', $data['p_end_date']);
//                 $request->session()->forget('visited_zero_movement_report');
//     		}
                
//             $input = $param_data;

//     		$zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
//     		$data['results'] = $zero_movement_report_total;      
            
// 	    }elseif(isset($input['p_start_date']) && isset($input['p_end_date']) && isset($input['vdepcode'])){
            
//             if(isset($input['vdepcode'])){
                
//                 $data['vdepcode'] = $input['vdepcode'];                
//                 if($input['vdepcode'] == 'All'){                    
//                     $data['categories'] = $zeromovement->getCategories();
//                 }else{
//                      $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
//                 }
//             }else{
//                 $data['vdepcode'] = '';
//             }
            
//             if(isset($input['vcategorycode'])){
                    
//                 $data['vcategorycode'] = $input['vcategorycode'];
                
//                 if($input['vcategorycode'] == 'All'){
// 			        $data['subcategories'] = $zeromovement->getSubCategories2();
//                 }else{
//                     $data['subcategories'] = $zeromovement->getSubCategories($input['subcat_id']);
//                 }
//             }else{
//                 $data['vcategorycode'] = '';
//             }
            
//             if(isset($input['subcat_id'])){                
//                 $data['subcat_id'] = $input['subcat_id'];
//             }else{
//                 $data['subcat_id'] = '';
//             }
            
//             $data['p_start_date'] = $input['p_start_date'];
//             $data['p_end_date'] = $input['p_end_date'];
            
// 			$param_data = array(
//                     'p_start_date'  => $data['p_start_date'],
//                     'p_end_date'    => $data['p_end_date'],
//                     'vdepcode'      => $data['vdepcode'],
//                     'vcategorycode' => $data['vcategorycode'],
//                     'subcat_id'     => $data['subcat_id']
//                 );
            
//             $param = http_build_query($param_data);
            
//             $reportsdata = $zeromovement->get_zero_movement_report($param_data);
            
//             foreach ($reportsdata as $result) {
    			
//                 $data['list'][]= array(
//                 	'ItemName'     => $result['ItemName'],
//                 	'SKU'   => $result['SKU'],
//                 	'QOH'=>$result['QOH'],
//                 	'Cost'=>number_format($result['Cost'],2),
//                 	'Price'=>number_format($result['Price'],2),
//                 	'TotalCost'=>number_format($result['TotalCost'],2),
//                 	'TotalPrice'=>number_format($result['TotalPrice'],2),
//                 	'GPP'=>number_format($result['GPP'],2),
//                 	'itemid'=>$result['iitemid'],
//                 	'item_link'=> '/item/edit/'.$result['iitemid'],
//                 );
                
                
//                 Session::put('inputs', $param_data);
//                 $data['reports'] = $data['list'];
//                 Session::put('reports', $data['reports']);
//                 Session::put('p_start_date', $data['p_start_date']);
//                 Session::put('p_end_date', $data['p_end_date']);
//                 $request->session()->forget('visited_zero_movement_report');
//     		}
                
//             $input = $param_data;
            
//             $zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
//     		$data['results'] = $zero_movement_report_total;      
                
// 	    }elseif(isset($input['visited_zero_movement_report'])=="Yes"){
            
//             $input = $request->session()->get('inputs');
            
//             $param_data = array(
//                     'p_start_date'  => $input['p_start_date'],
//                     'p_end_date'    => $input['p_end_date'],
//                     'vdepcode'      => $input['vdepcode'],
//                     'vcategorycode' => $input['vcategorycode'],
//                     'subcat_id'     => $input['subcat_id']
//                 );
            
//             $param = http_build_query($param_data);

//             $reportsdata = $zeromovement->get_zero_movement_report($param_data);
//             foreach ($reportsdata as $result) {
			
//                 $data['list'][]= array(
//                 	'ItemName'     => $result['ItemName'],
//                 	'SKU'   => $result['SKU'],
//                 	'QOH'=>$result['QOH'],
//                 	'Cost'=>number_format($result['Cost'],2),
//                 	'Price'=>number_format($result['Price'],2),
//                 	'TotalCost'=>number_format($result['TotalCost'],2),
//                 	'TotalPrice'=>number_format($result['TotalPrice'],2),
//                 	'GPP'=>number_format($result['GPP'],2),
//                 	'itemid'=>$result['iitemid'],
//                 	'item_link'=> '/item/edit/'.$result['iitemid'],
//                 );
                        
//                 Session::put('inputs', $param_data);
//                 $data['reports'] = $data['list'];
//                 Session::put('reports', $data['reports']);
//                 $data_list= $request->session()->get('inputs');               
//                 $data['p_start_date'] = $data_list['start_date'];
//                 $data['p_end_date'] = $data_list['end_date'];
                
//                 Session::put('p_start_date', $data['p_start_date']);
//                 Session::put('p_end_date', $data['p_end_date']);
//                 $request->session()->forget('visited_zero_movement_report');                
//             }
            
//             $zero_movement_report_total  = $zeromovement->get_zero_movement_report_total($param_data);
//     		$data['results'] = $zero_movement_report_total;      

//             if(isset($input['vdepcode'])){
                
//                 $data['vdepcode'] = $input['vdepcode'];
                
//                 if($input['vdepcode'] == 'All'){
                    
//                     $data['categories'] = $zeromovement->getCategories();
//                 }else{
//                      $data['categories'] = $zeromovement->getCategoriesInDepartment($input['vdepcode']);
//                 }
//             }else{
//                 $data['vdepcode'] = '';
//             }
            
//             if(isset($input['vcategorycode'])){
                    
//                 $data['vcategorycode'] = $input['vcategorycode'];
                
//                 if($input['vcategorycode'] == 'All'){
// 			        $data['subcategories'] = $zeromovement->getSubCategories2();
//                 }else{
//                     $data['subcategories'] = $zeromovement->getSubCategories($input['subcat_id']);
//                 }
//             }else{
//                 $data['vcategorycode'] = '';
//             }
            
//             if(isset($input['subcat_id'])){
                
//                 $data['subcat_id'] = $input['subcat_id'];
//             }else{
//                 $data['subcat_id'] = '';
//             }
//         }      
        
//         Session::put('desc_title', isset($data['desc_title']));
//         $data['byreports'] = array(
//                         1 => 'Category',
//                         2 => 'Department',
//                         3 => 'Item Group'
//                       );
                      
//         $store_data=$zeromovement->getStore();
//         $store = $store_data[0];
//         $request->session()->put('storename', $store['vstorename']);
//         $request->session()->put('storeaddress', $store['vaddress1']);
//         $request->session()->put('storephone', $store['vphone1']);
      
//         $data['store_name'] = $request->session()->get('storename'); 
//         $data['storename'] = $request->session()->get('storename'); 
//         $data['storeaddress'] = $request->session()->get('storeaddress'); 
//         $data['storephone'] = $request->session()->get('storephone'); 
        
// 		//new code 
// 		$departments = $zeromovement->getDepartments();       
//         $data['departments'] = $departments;	
//         return view('zeroreport.zeroreport')->with($data);
//     }

    public function csv_export(Request $request) {

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $data['zeromovement_reports'] = Session::get('zeromovement_reports');
        $data['desc_title'] = Session::get('desc_title');
        
        $data_row = '';
        
        $data_row .= "Store Name: ".Session::get('storename').PHP_EOL;
        $data_row .= "Store Address: ".Session::get('storeaddress').PHP_EOL;
        $data_row .= "Store Phone: ".Session::get('storephone').PHP_EOL;
        $data_row .= "Date: ".Session::get('p_start_date').'  To  '.Session::get('p_end_date').PHP_EOL;
        $data_row .= PHP_EOL;

        // echo "<pre>";
        // print_r($data['reports']);
        // die;

        if(count($data['zeromovement_reports']) > 0){
            $data_row .= '	Item Name,SKU,QOH,Cost,Price,Total cost,Total Price,GPP'.PHP_EOL;
            foreach ($data['zeromovement_reports']as $key => $value) {
                $data_row .= str_replace(',',' ',$value['ItemName']).','.str_replace(',',' ',$value['SKU']).','.str_replace(',',' ',$value['QOH']).','.$value['Cost'].','.$value['Price'].','.$value['TotalCost'].','.$value['TotalPrice'].','.$value['GPP'].PHP_EOL;
            }
        }else{
            $data_row = 'Sorry no data found!';
        }

        $file_name_csv  = 'zero-movement-report.csv';
        $file_path =public_path('image/zero-movement-report.csv');
        $myfile = fopen(public_path('image/zero-movement-report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    }

    public function print_page(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $data['zeromovement_reports'] = Session::get('zeromovement_reports');
        $data['p_start_date'] = Session::get('p_start_date');
        $data['p_end_date'] = Session::get('p_end_date');
        $data['desc_title'] = Session::get('desc_title');

        $data['storename'] = Session::get('storename');
        $data['storeaddress'] = Session::get('storeaddress');
        $data['storephone'] = Session::get('storephone');

        $data['heading_title'] = 'Zero Movement Report';
        return view('zeroreport.print_zeroreport')->with($data);
    }

    public function pdf_save_page(Request $request) {

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");

        $data['zeromovement_reports'] = $request->session()->get('zeromovement_reports');
        $data['p_start_date'] = $request->session()->get('p_start_date');
        $data['p_end_date'] = $request->session()->get('p_end_date');
        $data['desc_title'] = $request->session()->get('desc_title');

        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');

        $data['heading_title'] = 'Zero Movement Report';

        $pdf = PDF::loadView('zeroreport.print_zeroreport',$data);
        return $pdf->download('Zero-Movement-Report.pdf');
    }
	  
    public function reportdata(Request $request){
        $return = array();
    
        $this->load->model('administration/cash_sales_summary');
    
        if(!empty($input['report_by'])){
          if($input['report_by'] == 1){
            $datas = $this->model_administration_cash_sales_summary->getCategories();
          }elseif($input['report_by'] == 2){
            $datas = $this->model_administration_cash_sales_summary->getDepartments();
          }else{
            $datas = $this->model_administration_cash_sales_summary->getGroups();
          }
    
          $return['code'] = 1;
          $return['data'] = $datas;
        }else{
          $return['code'] = 0;
        }
        echo json_encode($return);
        exit;  
      }
    
    public function report_ajax_data(Request $request) {
    
        $json =array();
        $this->load->model('api/zero_movement_report');
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
    
          $temp_arr = json_decode(file_get_contents('php://input'), true);
    
          if(!empty($temp_arr['start_date']) && !empty($temp_arr['end_date']) && !empty($temp_arr['report_pull_by'])){
    
            if($temp_arr['report_by'] == 1){
              $data = $this->model_api_zero_movement_report->ajaxDataCategoryZeroMovementReport($temp_arr);
            }else if($temp_arr['report_by'] == 2){
              $data = $this->model_api_zero_movement_report->ajaxDataZeroMovementReport($temp_arr);
            }else{
              $data = $this->model_api_zero_movement_report->ajaxDataItemGroupZeroMovementReport($temp_arr);
            }
            
          }else{
            $data = array();
          }
    
          $this->response->addHeader('Content-Type: application/json');
          echo json_encode($data);
          exit;
    
        }
      }
      
    public function getDepartments(Request $request){
         
            $data = array();
            $this->load->model('administration/items');
    
    // 			$temp_arr = json_decode(file_get_contents('php://input'), true);
                
                $data = $this->model_administration_items->getDepartments();
                dd($data);
                $response = [];
                $obj= new \stdClass();
                $obj->id = "All";
                $obj->text = "All";
                
                array_push($response, $obj);
                        
                foreach($data as $k => $v){
                    
                    $obj= new \stdClass();
                    $obj->id = $v['vcategorycode'];
                    $obj->text = $v['vcategoryname'];
                    
                    array_push($response, $obj);
                }
                
                return $response;
        }
        
        public function getcategories(Request $request){
            $temp_arr = $request->all();
            $data = array();
            $zeromovement = new ZeroMovement();     

            if($temp_arr['dept_code'] == 'All'){                
                $data = $zeromovement->getCategories();
            }else{                
                $data = $zeromovement->getCategoriesInDepartment($temp_arr['dept_code']);
            }
            
            $response = [];
            $obj= new \stdClass();
            $obj->id = " ";
            $obj->text = "--Select Category--";
            array_push($response, $obj);
            
            $obj= new \stdClass();
            $obj->id = "All";
            $obj->text = "All";
            array_push($response, $obj);
            
            foreach($data as $k => $v){                
                $obj= new \stdClass();
                $obj->id = $v['vcategorycode'];
                $obj->text = $v['vcategoryname'];
                
                array_push($response, $obj);
            }
            echo json_encode($response);   
        }
        
        public function get_subcategories(Request $request){           
            $temp_arr = $request->all();
            $data = array();            
            $zeromovement = new ZeroMovement();        
            if($temp_arr['cat_id'] === 'All'){
                $data = $zeromovement->getSubCategories2();
            }else{
                $data = $zeromovement->getSubCategories($temp_arr['cat_id']);
            }
            $response = [];
            $obj= new \stdClass();
            $obj->id = "All";
            $obj->text = "--Select SubCategory--";
            array_push($response, $obj);
            
            foreach($data as $k => $v){
                $obj= new \stdClass();
                $obj->id = $v['subcat_id'];
                $obj->text = $v['subcat_name'];
                
                array_push($response, $obj);
            }
            echo json_encode($response);
        }
        
    public function delete(Request $request) {
        $zeromovement = new ZeroMovement();
        $sid = $request->session()->get('sid');
        $data_request = $request->input('selected_items');
        $data = $zeromovement->deleteItems($data_request,$sid);             
        echo json_encode($data);
        exit;
        
    }
    
    public function Update_dectivate(Request $request){
        $zeromovement = new ZeroMovement();
        $sid = $request->session()->get('sid');
        $data_request = $request->input('selected_items');
        $data = $zeromovement->update_status($data_request,$sid);
        echo json_encode($data);
        exit;
    }	 
}
