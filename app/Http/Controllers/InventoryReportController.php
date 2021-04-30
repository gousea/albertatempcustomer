<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
use App\Model\Department;
use App\Model\Category;
class InventoryReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        ini_set('max_execution_time', -1);
        ini_set("memory_limit", "2G");
        $Reports = new Reports;
        
   
        $departments = $Reports->getDepartments_inv();
	    $data['departments'] = $departments;
	  // $data['vdepcode']='YES';
      
        return view('Reports.InventoryReport',$data);
        
    }

   

    public function getlist(Request $request){
        $input = $request->all();
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $Reports = new Reports;
        $departments = $Reports->getDepartments_inv();
	    $data['departments'] = $departments;
	    
	   
	    if(isset($input['vdepcode'])){
	         $data['vdepcode']=$input['vdepcode']; 
	         $categories = $Reports->getCategories();
        	 $data['categories'] = $categories;
	        
	    }
	    
	    else{
            // $data['category'] = '';
            $data['vdepcode'] = '';
        //     $categories = $Reports->getCategories();
    	   // $data['categories'] = $categories;
           
        }
        
        
        if(isset($input['vcategorycode'])){
	        $data['catcode']=$input['vcategorycode']; 
	        //dd( $data['catcode']);
	    }
	    
	    else{
            // $data['category'] = '';
           $data['catcode'] = '';
           
        }
        
        if(isset($input['subcat_id'])){
	        $data['subcode']=$input['subcat_id']; 
	        $subcategories = $Reports->getSubcategories();
	        $data['subcategories'] = $subcategories; 
	        //dd( $data['catcode']);
	    }
	    
	    else{
            // $data['category'] = '';
           $data['subcode'] = '';
        //   $subcategories = $Reports->getSubcategories();
	       //$data['subcategories'] = $subcategories;
           
        }
        
        

        $store=$Reports->getStore();
        
        session()->put('input_data',  $input);
        
        // return  $input['subcat_id'][0] ;
        
        if(!empty($input['subcat_id']) && $input['subcat_id'][0] != 'All'){
            if($input['qoh_date']==date("m-d-Y")){
            $reportsdata        = $Reports->getSubCategoriesReport($input);
            $reportsdataN       = $Reports->getSubCategoriesReportN($input);
            $reportsdataZero    = $Reports->getSubCategoriesReportZero($input);
            }
            else{
            $reportsdata        = $Reports->getSubCategoriesReportNew($input, "P");
            $reportsdataN       = $Reports->getSubCategoriesReportNew($input, "N");
            $reportsdataZero    = $Reports->getSubCategoriesReportNew($input, "Z");
            }
        }
        
        else if(!empty($input['vcategorycode']) && $input['vcategorycode'][0] != 'All'){
            
            if($input['qoh_date']==date("m-d-Y")){
              $reportsdata = $Reports->getCategoriesReport($input);
              $reportsdataN= $Reports->getCategoriesReportN($input);
              $reportsdataZero= $Reports->getCategoriesReportZero($input);
            }
            else{
           
            $reportsdata        = $Reports->getCategoriesReportNew($input, "P");
            $reportsdataN       = $Reports->getCategoriesReportNew($input, "N");
            $reportsdataZero    = $Reports->getCategoriesReportNew($input, "Z");
            }
        }
        
        else if(!empty($input['vdepcode']) && $input['vdepcode'][0]!='All'){
            
            if($input['qoh_date']==date("m-d-Y"))
            {
                $reportsdata = $Reports->getDepartmentsReport($input);
                $reportsdataN = $Reports->getDepartmentsReportN($input);
                $reportsdataZero = $Reports->getDepartmentsReportZero($input);
            }
            else{
                
                
                $reportsdata        = $Reports->getDepartmentsReportNew($input, "P");
                $reportsdataN       = $Reports->getDepartmentsReportNew($input, "N");
                $reportsdataZero    = $Reports->getDepartmentsReportNew($input, "Z");
            }
        }
       
     else{
             if($input['qoh_date']==date("m-d-Y"))
             
            {   
                $all='ALL';
             
                $reportsdata = $Reports->getDepartmentsReport($all);
                $reportsdataN = $Reports->getDepartmentsReportN($all);
                $reportsdataZero = $Reports->getDepartmentsReportZero($all);
            }
            else{
           
            $reportsdata        = $Reports->getAllReportNew($input, "P");
            $reportsdataN       = $Reports->getAllReportNew($input, "N");
            $reportsdataZero    = $Reports->getAllReportNew($input, "Z");
            }
        }
        
        
        
        
        $data['vdepcode']       = $input['vdepcode'] ?? '';
        $data['vcategorycode']  = $input['vcategorycode'] ?? '';
        $data['subcat_id']      = $input['subcat_id'] ?? '';
        $data['qoh_date']       = $input['qoh_date'] ?? '';
       // print_r($data['qoh_date']);die;
       //   echo "<pre>";print_r($input);exit;
       
    //  $this->session->data['fdate'] = $data['qoh_date'] ;
    //  $this->session->data['filter_data'] = $input;
        
      $data['selected_report_data'] = $input['report_data'] ?? '';

      $report_datas = array();
      $report_datas_print = array();
      $total_qoh = 0;
      $toal_cost_price = 0.00;
      $toal_value = 0.00;
      $total_retail_value = 0.00;
      $total_retail = 0.00;
    //negative qoh 
    
      $report_datasN = array();
      $report_datas_printN = array();
      $total_qohN = 0;
      $toal_cost_priceN = 0.00;
      $toal_valueN = 0.00;
      $total_retail_valueN = 0.00;
      $total_retailN = 0.00;
      
    //negative items
      $report_datasZero = array();
      $report_datas_printZero = array();
      $total_qohZero = 0;
      $toal_cost_priceZero = 0.00;
      $toal_valueZero = 0.00;
      $total_retail_valueZero= 0.00;
      $total_retailZero= 0.00;
    
	  //$nsellunit = 0.00;
      
      foreach ($reportsdata as $k => $v) {

        if(array_key_exists($v['search_id'],$report_datas_print)){
          $report_datas_print[$v['search_id']][] = $v;
        }else{
          $report_datas_print[$v['search_id']][] = $v;
        }

        if(array_key_exists($v['search_id'],$report_datas)){
            $report_datas[$v['search_id']]['key_name'] = $v['department_name'];
            $report_datas[$v['search_id']]['key_id'] = $v['search_id'];
            $report_datas[$v['search_id']]['search_total_qoh'] = $report_datas[$v['search_id']]['search_total_qoh'] + $v['snapshot_qty'];

            $report_datas[$v['search_id']]['search_total_cost_price'] = $report_datas[$v['search_id']]['search_total_cost_price'] + $v['cost'];
             //dd((double)$v['cost']);
            $report_datas[$v['search_id']]['search_total_total_cost_price'] = $report_datas[$v['search_id']]['search_total_total_cost_price'] + ($v['snapshot_qty'] * (double)$v['cost']);
            
            // $report_datas[$v['search_id']]['search_total_retail_value'] = $report_datas[$v['search_id']]['search_total_retail_value'] + ($v['price']/$v['nsellunit']);
            // $report_datas[$v['search_id']]['search_total_total_retail_value'] = $report_datas[$v['search_id']]['search_total_total_retail_value'] + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
            if($v['nsellunit']!=0){
            $report_datas[$v['search_id']]['search_total_total_retail_value'] = $report_datas[$v['search_id']]['search_total_total_retail_value'] + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
            
            $report_datas[$v['search_id']]['search_total_retail_value'] = $report_datas[$v['search_id']]['search_total_retail_value'] + ($v['price']/$v['nsellunit']);
            
            }
            else{
              $report_datas[$v['search_id']]['search_total_retail_value']=$report_datas[$v['search_id']]['search_total_total_retail_value']+0;
              $report_datas[$v['search_id']]['search_total_total_retail_value']=$report_datas[$v['search_id']]['search_total_retail_value'] +0;  
            }
        }else{
            $report_datas[$v['search_id']]['key_name'] = $v['department_name'];
            $report_datas[$v['search_id']]['key_id'] = $v['search_id'];
            $report_datas[$v['search_id']]['search_total_qoh'] = $v['snapshot_qty'];

            $report_datas[$v['search_id']]['search_total_cost_price'] = $v['cost'];
            $report_datas[$v['search_id']]['search_total_total_cost_price'] = ($v['snapshot_qty'] * (double)$v['cost']);
            $report_datas[$v['search_id']]['search_total_retail_value'] = ($v['price']/$v['nsellunit']);
            $report_datas[$v['search_id']]['search_total_total_retail_value'] = ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
        }

        $total_qoh = $total_qoh + $v['snapshot_qty'];
        $toal_cost_price = $toal_cost_price + $v['cost'];
        $toal_value = $toal_value + ($v['snapshot_qty'] * (double)$v['cost']);
        
        if($v['nsellunit']!=0){
        $total_retail = $total_retail + ($v['price']/$v['nsellunit']);
        $total_retail_value = $total_retail_value + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
        }
        else{
            $total_retail = $total_retail +0;
            $total_retail_value = $total_retail_value +0;
        }
        // $total_retail = $total_retail + ($v['price']/$v['nsellunit']);
        // $total_retail_value = $total_retail_value + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
      }
      
      
      //negative qoh
       foreach ($reportsdataN as $k => $v) {

        if(array_key_exists($v['search_id'],$report_datas_printN)){
          $report_datas_printN[$v['search_id']][] = $v;
        }else{
          $report_datas_printN[$v['search_id']][] = $v;
        }

        if(array_key_exists($v['search_id'],$report_datasN)){
            $report_datasN[$v['search_id']]['key_name'] = $v['department_name'];
            $report_datasN[$v['search_id']]['key_id'] = $v['search_id'];
            
            $report_datasN[$v['search_id']]['search_total_qoh'] = $report_datasN[$v['search_id']]['search_total_qoh'] + $v['snapshot_qty'];

            $report_datasN[$v['search_id']]['search_total_cost_price'] = $report_datasN[$v['search_id']]['search_total_cost_price'] + $v['cost'];
            $report_datasN[$v['search_id']]['search_total_total_cost_price'] = $report_datasN[$v['search_id']]['search_total_total_cost_price'] + ($v['snapshot_qty'] * (double)$v['cost']);
            $report_datasN[$v['search_id']]['search_total_retail_value'] = $report_datasN[$v['search_id']]['search_total_retail_value'] + ($v['price']/$v['nsellunit']);
            $report_datasN[$v['search_id']]['search_total_total_retail_value'] = $report_datasN[$v['search_id']]['search_total_total_retail_value'] + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));

        }else{
            $report_datasN[$v['search_id']]['key_name'] = $v['department_name'];
            $report_datasN[$v['search_id']]['key_id'] = $v['search_id'];
            $report_datasN[$v['search_id']]['search_total_qoh'] = $v['snapshot_qty'];

            $report_datasN[$v['search_id']]['search_total_cost_price'] = $v['cost'];
            $report_datasN[$v['search_id']]['search_total_total_cost_price'] = ($v['snapshot_qty'] * (double)$v['cost']);
            $report_datasN[$v['search_id']]['search_total_retail_value'] = ($v['price']/$v['nsellunit']);
            $report_datasN[$v['search_id']]['search_total_total_retail_value'] = ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
        }
       // print_r( $report_datasN[$v['search_id']]['search_total_qoh']);die;
        $total_qohN = $total_qohN + $v['snapshot_qty'];
        $toal_cost_priceN = $toal_cost_priceN + $v['cost'];
        $toal_valueN = $toal_valueN + ($v['snapshot_qty'] * (double)$v['cost']);

        $total_retailN = $total_retailN + ($v['price']/$v['nsellunit']);
        $total_retail_valueN = $total_retail_valueN + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
      }
   
      //negative qoh end
      
     
      //zero qoh
       foreach ($reportsdataZero as $k => $v) {

        if(array_key_exists($v['search_id'],$report_datas_printZero)){
          $report_datas_printZero[$v['search_id']][] = $v;
        }else{
          $report_datas_printZero[$v['search_id']][] = $v;
        }

        if(array_key_exists($v['search_id'],$report_datasZero)){
            $report_datasZero[$v['search_id']]['key_name'] = $v['department_name'];
            $report_datasZero[$v['search_id']]['key_id'] = $v['search_id'];
            
            // $report_datasZero[$v['search_id']]['search_total_qoh'] = $report_datasZero[$v['search_id']]['search_total_qoh'] + $v['iqtyonhand'];
            $report_datasZero[$v['search_id']]['search_total_qoh'] = $report_datasZero[$v['search_id']]['search_total_qoh'] + $v['snapshot_qty'];

            $report_datasZero[$v['search_id']]['search_total_cost_price'] = $report_datasZero[$v['search_id']]['search_total_cost_price'] + $v['cost'];
            $report_datasZero[$v['search_id']]['search_total_total_cost_price'] = $report_datasZero[$v['search_id']]['search_total_total_cost_price'] + ($v['snapshot_qty'] * (double)$v['cost']);
            
            if($v['nsellunit']!=0){
            $report_datasZero[$v['search_id']]['search_total_retail_value'] = $report_datasZero[$v['search_id']]['search_total_retail_value'] + ($v['price']/$v['nsellunit']);
            $report_datasZero[$v['search_id']]['search_total_total_retail_value'] = $report_datasZero[$v['search_id']]['search_total_total_retail_value'] + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
            }
            else{
                $report_datasZero[$v['search_id']]['search_total_retail_value']=0;
                $report_datasZero[$v['search_id']]['search_total_total_retail_value'] =0;
            }
            

        }else{
            $report_datasZero[$v['search_id']]['key_name'] = $v['department_name'];
            $report_datasZero[$v['search_id']]['key_id'] = $v['search_id'];
            $report_datasZero[$v['search_id']]['search_total_qoh'] = $v['snapshot_qty'];

            $report_datasZero[$v['search_id']]['search_total_cost_price'] = $v['cost'];
            $report_datasZero[$v['search_id']]['search_total_total_cost_price'] = ($v['snapshot_qty'] * (double)$v['cost']);
           if($v['nsellunit']!=0){
            $report_datasZero[$v['search_id']]['search_total_retail_value'] = ($v['price']/$v['nsellunit']);
            $report_datasZero[$v['search_id']]['search_total_total_retail_value'] = ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
           }
           else{
                $report_datasZero[$v['search_id']]['search_total_retail_value'] = 0;
                $report_datasZero[$v['search_id']]['search_total_total_retail_value'] = 0;
           }
        }
       // print_r( $report_datasN[$v['search_id']]['search_total_qoh']);die;
        $total_qohZero = $total_qohZero + $v['snapshot_qty'];
        $toal_cost_priceZero = $toal_cost_priceZero + $v['cost'];
        $toal_valueZero = $toal_valueZero + ($v['snapshot_qty'] * (double)$v['cost']);
        if($v['nsellunit']!=0){
        $total_retailZero = $total_retailZero + ($v['price']/$v['nsellunit']);
        $total_retail_valueZero = $total_retail_valueZero + ($v['snapshot_qty'] * ($v['price']/$v['nsellunit']));
        }
        else{
             $total_retailZero = $total_retailZero + 0;
             $total_retail_valueZero = $total_retail_valueZero + 0;
        }
      }
   
      //zero qoh end


      $data['reports'] = $report_datas;
      $data['report_datas_print'] = $report_datas_print;
      $data['total_qoh'] = $total_qoh;
      $data['toal_cost_price'] = $toal_cost_price;
      $data['toal_value'] = $toal_value;
      $data['total_retail'] = $total_retail;
      $data['total_retail_value'] = $total_retail_value;

    //   $this->session->data['reports'] = $data['reports'];
    //   $this->session->data['report_datas_print'] = $data['report_datas_print'];
    //   $this->session->data['total_qoh'] = $data['total_qoh'];
    //   $this->session->data['toal_cost_price'] = $data['toal_cost_price'];
    //   $this->session->data['toal_value'] = $data['toal_value'];
    //   $this->session->data['total_retail'] = $data['total_retail'];
    //   $this->session->data['total_retail_value'] = $data['total_retail_value'];
    //   $this->session->data['selected_report'] = $data['selected_report'];
      
      //negative qoh session data set
      $data['reportsN'] = $report_datasN;
      $data['report_datas_printN'] = $report_datas_printN;
      $data['total_qohN'] = $total_qohN;
     // print_r($data['total_qohN']);die;
      $data['toal_cost_priceN'] = $toal_cost_priceN;
      $data['toal_valueN'] = $toal_valueN;
      $data['total_retailN'] = $total_retailN;
      $data['total_retail_valueN'] = $total_retail_valueN;

    //   $this->session->data['reportsN'] = $data['reportsN'];
    //   $this->session->data['report_datas_printN'] = $data['report_datas_printN'];
    //   $this->session->data['total_qohN'] = $data['total_qohN'];
    //   $this->session->data['toal_cost_priceN'] = $data['toal_cost_priceN'];
    //   $this->session->data['toal_valueN'] = $data['toal_valueN'];
    //   $this->session->data['total_retailN'] = $data['total_retailN'];
    //   $this->session->data['total_retail_valueN'] = $data['total_retail_valueN'];
      
      
      //end _ve qoh
      
      //zero qoh session data set
      $data['reportsZero'] = $report_datasZero;
      $data['report_datas_printZero'] = $report_datas_printZero;
      $data['total_qohZero'] = $total_qohZero;

      $data['toal_cost_priceZero'] = $toal_cost_priceZero;
      $data['toal_valueZero'] = $toal_valueZero;
      $data['total_retailZero'] = $total_retailZero;
      $data['total_retail_valueZero'] = $total_retail_valueZero;

    //   $this->session->data['reportsZero'] = $data['reportsZero'];
    //   $this->session->data['report_datas_printZero'] = $data['report_datas_printZero'];
    //   $this->session->data['total_qohZero'] = $data['total_qohZero'];
    //   $this->session->data['toal_cost_priceZero'] = $data['toal_cost_priceZero'];
    //   $this->session->data['toal_valueZero'] = $data['toal_valueZero'];
    //   $this->session->data['total_retailZero'] = $data['total_retailZero'];
    //   $this->session->data['total_retail_valueZero'] = $data['total_retail_valueZero'];
      
        
       $store=$Reports->getStore();
       $data['store']=$store;

        session()->put('session_data',  $data);
      
        
     
        return view('Reports.InventoryReport', $data);
       
    }
    
   public function report_ajax_data(Request $request) {
    ini_set('max_execution_time', -1);
    $temp_arr = $request->all();
    $Reports = new Reports; 
    
    $value= session()->get('input_data') ;
    $fdate=$value['qoh_date'];
    $filter_data=$value;
    
   
     $json =array();
    if($fdate==date("m-d-Y")){
           
        $data =$Reports->ajaxDataReportDepartmentnew_p($temp_arr,$fdate,$filter_data);
        }  
        else{
        $data = $this->$Reports->ajaxDataReportDepartmentp($temp_arr,$fdate,$filter_data);
        }
      

      
      echo json_encode($data);
      exit;

    
  }     
  
  public function report_ajax_data_zero(Request $request) {
    ini_set('max_execution_time', 0);
    $temp_arr = $request->all();
    $Reports = new Reports; 
    
    $value= session()->get('input_data') ;
    $fdate=$value['qoh_date'];
    $filter_data=$value;
    
   
     $json =array();
    if($fdate==date("m-d-Y")){
           
        $data =$Reports->ajaxDataReportDepartmentnew_Z($temp_arr,$fdate,$filter_data);
        }  
        else{
        $data = $this->$Reports->ajaxDataReportDepartmentZ($temp_arr,$fdate,$filter_data);
        }
      

      
      echo json_encode($data);
      exit;

    
  }     
  
  public function report_ajax_data_N(Request $request) {
    ini_set('max_execution_time', -1);
    $temp_arr = $request->all();
    $Reports = new Reports; 
    
    $value= session()->get('input_data') ;
    $fdate=$value['qoh_date'];
    $filter_data=$value;
    
   
     $json =array();
    if($fdate==date("m-d-Y")){
           
        $data =$Reports->ajaxDataReportDepartmentnew_N($temp_arr,$fdate,$filter_data);
        }  
        else{
        $data = $this->$Reports->ajaxDataReportDepartmentN($temp_arr,$fdate,$filter_data);
        }
      

      
      echo json_encode($data);
      exit;

    
  }     
    
       
    public function cat(Request $request){
        $input = $request->all();
		$data = array();
	
        $Category = new Category();            
        $data = $Category->getCategoriesInDepartment1($input);			
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
	       echo json_encode($response);
    }  
    
    public function get_subcategories(Request $request){
        $input = $request->all();
        $data = array();
        $Category = new Category();     
        $data = $Category->getSubCategories1($input);
            $response = [];
            $obj= new \stdClass();
		    $obj->id = "All";
		    $obj->text = "All";
		    array_push($response, $obj);
			foreach($data as $k => $v){			    
			    $obj= new \stdClass();
			    $obj->id = $v->subcat_id;
			    $obj->text = $v->subcat_name;
			    array_push($response, $obj);
			}
	    echo json_encode($response);
    }
    
    public function print_page() {

    
    ini_set("memory_limit", "2G");
    ini_set('max_execution_time', 0);
    $data1= session()->get('session_data') ;
    
    $data['reports'] = $data1['report_datas_print'];
    $data['total_qoh'] = $data1['total_qoh'];
    $data['toal_cost_price'] = $data1['toal_cost_price'];
    $data['toal_value'] = $data1['toal_value'];
    $data['total_retail'] = $data1['total_retail'];
    $data['total_retail_value'] = $data1['total_retail_value'];
    $data['store']=$data1['store'];
    
    //print_r($data);
    return view('Reports.InventoryReportpdf',$data);  
    
   
    
        
    }
    public function print_page_N() {

    
    ini_set("memory_limit", "2G");
    ini_set('max_execution_time', 0);
    $data1= session()->get('session_data') ;
    
    $data['reports'] = $data1['report_datas_printN'];
    $data['total_qoh'] = $data1['total_qohN'];
    $data['toal_cost_price'] = $data1['toal_cost_priceN'];
    $data['toal_value'] = $data1['toal_valueN'];
    $data['total_retail'] = $data1['total_retailN'];
    $data['total_retail_value'] = $data1['total_retail_valueN'];
    $data['store']=$data1['store'];
    
    //print_r($data);
    return view('Reports.InventoryReportpdf',$data);  

        
    }
     public function print_page_Z() {

    
    ini_set("memory_limit", "2G");
    ini_set('max_execution_time', 0);
    $data1= session()->get('session_data') ;
    
    $data['reports'] = $data1['report_datas_printZero'];
    $data['total_qoh'] = $data1['total_qohZero'];
    $data['toal_cost_price'] = $data1['toal_cost_priceZero'];
    $data['toal_value'] = $data1['toal_valueZero'];
    $data['total_retail'] = $data1['total_retail'];
    $data['total_retail_value'] = $data1['total_retail_valueZero'];
    $data['store']=$data1['store'];
    
    //print_r($data);
    return view('Reports.InventoryReportpdf',$data);  

        
    }
    public function eodPdf()
    {
       
       ini_set("memory_limit", "2G");
       ini_set('max_execution_time', 0);
      
        $data1= session()->get('session_data') ;
        $data['store']=$data1['store'];
        $data['reports'] = $data1['report_datas_print'];
        $data['total_qoh'] = $data1['total_qoh'];
        $data['toal_cost_price'] = $data1['toal_cost_price'];
        $data['toal_value'] = $data1['toal_value'];
        $data['total_retail'] = $data1['total_retail'];
        $data['total_retail_value'] = $data1['total_retail_value'];

        //return "hi";

        $pdf = PDF::loadView('Reports.InventoryReportpdf',$data);
        

        
        return $pdf->download('InventoryReportpdf.pdf');

        
    }
     public function eodPdf_N()
    {
       
       ini_set("memory_limit", "2G");
       ini_set('max_execution_time', 0);
      
        $data1= session()->get('session_data') ;
        
        $data['reports'] = $data1['report_datas_printN'];
        $data['total_qoh'] = $data1['total_qohN'];
        $data['toal_cost_price'] = $data1['toal_cost_priceN'];
        $data['toal_value'] = $data1['toal_valueN'];
        $data['total_retail'] = $data1['total_retailN'];
        $data['total_retail_value'] = $data1['total_retail_valueN'];
        $data['store']=$data1['store'];

        //return "hi";

        $pdf = PDF::loadView('Reports.InventoryReportpdf',$data);
        

        
        return $pdf->download('InventoryReportpdf.pdf');

        
    }
     public function eodPdf_Z()
    {
       
       ini_set("memory_limit", "2G");
       ini_set('max_execution_time', 0);
      
        $data1= session()->get('session_data') ;
        $data['reports'] = $data1['report_datas_printZero'];
        $data['total_qoh'] = $data1['total_qohZero'];
        $data['toal_cost_price'] = $data1['toal_cost_priceZero'];
        $data['toal_value'] = $data1['toal_valueZero'];
        $data['total_retail'] = $data1['total_retail'];
        $data['total_retail_value'] = $data1['total_retail_valueZero'];
        $data['store']=$data1['store'];

        //return "hi";

        $pdf = PDF::loadView('Reports.InventoryReportpdf',$data);
        

        
        return $pdf->download('InventoryReportpdf.pdf');

        
    }

   
    public function csv(){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data_row='';
       
        $data1= session()->get('session_data') ;
        $data['store']=$data1['store'];
        $data['reports'] = $data1['report_datas_print'];
        $data['total_qoh'] = $data1['total_qoh'];
        $data['toal_cost_price'] = $data1['toal_cost_price'];
        $data['toal_value'] = $data1['toal_value'];
        $data['total_retail'] = $data1['total_retail'];
        $data['total_retail_value'] = $data1['total_retail_value'];
        
        $data_row .= PHP_EOL;

        
        // $data_row .= "EOD From date: ".$data['p_start_date'].' '."EOD To date: ".$data['p_end_date'].PHP_EOL;
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
        
        $tot_qoh = 0;
        $tot_cost = 0;
        $tot_price = 0;
         if(count($data['reports']) > 0){
            // if($data['selected_report'] == 1){
            //   $data_row .= 'Category,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else if($data['selected_report'] == 2){
            //   $data_row .= 'Department,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else if($data['selected_report'] == 3){
            //   $data_row .= 'Item Group,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else{
            //   $data_row .= 'category,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }
            $data_row .= 'Department,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;

            foreach ($data['reports'] as $key => $value) {
             // $data_row .= $value[0]['search_name'].',,,,'.PHP_EOL;

              $total_qty = 0;
              $total_total_cost = 0;
              $total_total_value = 0;
              $total_total_retail_value = 0;

              foreach ($value as $k => $v){
                $tot_value = $v['snapshot_qty'] * number_format((float)$v['cost'], 2, '.', '');
                $tot_ret_value = $v['snapshot_qty'] * number_format((float)($v['price']/$v['nsellunit']), 2, '.', '');

                $total_total_retail_value = $total_total_retail_value + $tot_ret_value;

                $total_qty = $total_qty + $v['snapshot_qty'];
                $total_total_cost = $total_total_cost + number_format((float)$v['cost'], 2, '.', '');
                $total_total_value = $total_total_value + $tot_value;

                $data_row .= str_replace(',',' ',$v['vname']).','.str_replace(',',' ',$v['itemname']).','.$v['snapshot_qty'].','.number_format((float)$v['cost'], 2, '.', '').','. number_format((float)$tot_value, 2, '.', '').','.number_format((float)($v['price']/$v['nsellunit']), 2, '.', '').','.number_format((float)$tot_ret_value, 2, '.', '') .PHP_EOL;
              }

              $data_row .= ',Total,'. $total_qty .',,$'.$total_total_value.',,$'.$total_total_retail_value.PHP_EOL;
            }
            
            $data_row .= ',Grand Total,'. $data['total_qoh'] .',,$'.number_format((float)$data['toal_value'], 2, '.', '') .',,$'. number_format((float)$data['total_retail_value'], 2, '.', '') .PHP_EOL;

        }else{
            $data_row = 'Sorry no data found!';
        }

        
        $file_name_csv = 'invreport.csv';

        $file_path =public_path('image/end-of-day-report.csv');

        $myfile = fopen(public_path('image/end-of-day-report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    
    
    }
    public function csv_Z(){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data_row='';
       
        $data1= session()->get('session_data') ;
        $data['reports'] = $data1['report_datas_printZero'];
        $data['total_qoh'] = $data1['total_qohZero'];
        $data['toal_cost_price'] = $data1['toal_cost_priceZero'];
        $data['toal_value'] = $data1['toal_valueZero'];
        $data['total_retail'] = $data1['total_retail'];
        $data['total_retail_value'] = $data1['total_retail_valueZero'];
        $data['store']=$data1['store'];
        
        $data_row .= PHP_EOL;

        
        // $data_row .= "EOD From date: ".$data['p_start_date'].' '."EOD To date: ".$data['p_end_date'].PHP_EOL;
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
        
        $tot_qoh = 0;
        $tot_cost = 0;
        $tot_price = 0;
         if(count($data['reports']) > 0){
            // if($data['selected_report'] == 1){
            //   $data_row .= 'Category,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else if($data['selected_report'] == 2){
            //   $data_row .= 'Department,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else if($data['selected_report'] == 3){
            //   $data_row .= 'Item Group,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else{
            //   $data_row .= 'category,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }
            $data_row .= 'Department,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;

            foreach ($data['reports'] as $key => $value) {
             // $data_row .= $value[0]['search_name'].',,,,'.PHP_EOL;

              $total_qty = 0;
              $total_total_cost = 0;
              $total_total_value = 0;
              $total_total_retail_value = 0;

              foreach ($value as $k => $v){
                $tot_value = $v['snapshot_qty'] * number_format((float)$v['cost'], 2, '.', '');
                $tot_ret_value = $v['snapshot_qty'] * number_format((float)($v['price']/$v['nsellunit']), 2, '.', '');

                $total_total_retail_value = $total_total_retail_value + $tot_ret_value;

                $total_qty = $total_qty + $v['snapshot_qty'];
                $total_total_cost = $total_total_cost + number_format((float)$v['cost'], 2, '.', '');
                $total_total_value = $total_total_value + $tot_value;

                $data_row .= str_replace(',',' ',$v['vname']).','.str_replace(',',' ',$v['itemname']).','.$v['snapshot_qty'].','.number_format((float)$v['cost'], 2, '.', '').','. number_format((float)$tot_value, 2, '.', '').','.number_format((float)($v['price']/$v['nsellunit']), 2, '.', '').','.number_format((float)$tot_ret_value, 2, '.', '') .PHP_EOL;
              }

              $data_row .= ',Total,'. $total_qty .',,$'.$total_total_value.',,$'.$total_total_retail_value.PHP_EOL;
            }
            
            $data_row .= ',Grand Total,'. $data['total_qoh'] .',,$'.number_format((float)$data['toal_value'], 2, '.', '') .',,$'. number_format((float)$data['total_retail_value'], 2, '.', '') .PHP_EOL;

        }else{
            $data_row = 'Sorry no data found!';
        }

        
        $file_name_csv = 'invreport.csv';

        $file_path =public_path('image/end-of-day-report.csv');

        $myfile = fopen(public_path('image/end-of-day-report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    
    
    }
    public function csv_N(){
       ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data_row='';
       
        $data1= session()->get('session_data') ;
        $data['store']=$data1['store'];
        $data['reports'] = $data1['report_datas_printN'];
        $data['total_qoh'] = $data1['total_qohN'];
        $data['toal_cost_price'] = $data1['toal_cost_priceN'];
        $data['toal_value'] = $data1['toal_valueN'];
        $data['total_retail'] = $data1['total_retailN'];
        $data['total_retail_value'] = $data1['total_retail_valueN'];
        
        $data_row .= PHP_EOL;

        
        // $data_row .= "EOD From date: ".$data['p_start_date'].' '."EOD To date: ".$data['p_end_date'].PHP_EOL;
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
        
        $tot_qoh = 0;
        $tot_cost = 0;
        $tot_price = 0;
         if(count($data['reports']) > 0){
            // if($data['selected_report'] == 1){
            //   $data_row .= 'Category,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else if($data['selected_report'] == 2){
            //   $data_row .= 'Department,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else if($data['selected_report'] == 3){
            //   $data_row .= 'Item Group,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }else{
            //   $data_row .= 'category,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;
            // }
            $data_row .= 'Department,Item,QOH,Cost Value,Total Cost Value,Retail Value,Total Retail Value'.PHP_EOL;

            foreach ($data['reports'] as $key => $value) {
             // $data_row .= $value[0]['search_name'].',,,,'.PHP_EOL;

              $total_qty = 0;
              $total_total_cost = 0;
              $total_total_value = 0;
              $total_total_retail_value = 0;

              foreach ($value as $k => $v){
                $tot_value = $v['snapshot_qty'] * number_format((float)$v['cost'], 2, '.', '');
                $tot_ret_value = $v['snapshot_qty'] * number_format((float)($v['price']/$v['nsellunit']), 2, '.', '');

                $total_total_retail_value = $total_total_retail_value + $tot_ret_value;

                $total_qty = $total_qty + $v['snapshot_qty'];
                $total_total_cost = $total_total_cost + number_format((float)$v['cost'], 2, '.', '');
                $total_total_value = $total_total_value + $tot_value;

                $data_row .= str_replace(',',' ',$v['vname']).','.str_replace(',',' ',$v['itemname']).','.$v['snapshot_qty'].','.number_format((float)$v['cost'], 2, '.', '').','. number_format((float)$tot_value, 2, '.', '').','.number_format((float)($v['price']/$v['nsellunit']), 2, '.', '').','.number_format((float)$tot_ret_value, 2, '.', '') .PHP_EOL;
              }

              $data_row .= ',Total,'. $total_qty .',,$'.$total_total_value.',,$'.$total_total_retail_value.PHP_EOL;
            }
            
            $data_row .= ',Grand Total,'. $data['total_qoh'] .',,$'.number_format((float)$data['toal_value'], 2, '.', '') .',,$'. number_format((float)$data['total_retail_value'], 2, '.', '') .PHP_EOL;

        }else{
            $data_row = 'Sorry no data found!';
        }

        
        $file_name_csv = 'invreport.csv';

        $file_path =public_path('image/end-of-day-report.csv');

        $myfile = fopen(public_path('image/end-of-day-report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    
    
    }
     
}
