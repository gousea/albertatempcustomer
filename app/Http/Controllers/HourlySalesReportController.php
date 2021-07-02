<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
use App\Model\Department;
use App\Model\Category;
use App\Model\ZeroMovement;
class HourlySalesReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $Reports = new Reports;
      
        
   
	    
	    $department = new Department();
        $departments_data = $department->getDepartments();
        $data['departments'] = json_decode(json_encode($departments_data), true); 
	    
	    $categories = $Reports->getCategories();
	    $data['categories'] = $categories;
	    
	    $subcategories = $Reports->getSubcategories();
	    $data['subcategories'] = $subcategories;
	    
	    
        $suppliers = $Reports->getSuppliers();
        $data['suppliers'] = $suppliers;
        
        $manufacturers =$Reports->getManufacturers();
        $data['manufacturers'] = $manufacturers;
        
      
        return view('Reports.HourlySalesReport',$data);
        
    }


    public function getlist(Request $request){
        
        
        
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");  
        $input = $request->all();
        $Reports = new Reports;
         
	    
	    $suppliers = $Reports->getSuppliers();
        $data['suppliers'] = $suppliers;
        
        $manufacturers =$Reports->getManufacturers();
        $data['manufacturers'] = $manufacturers;
        
	    $department = new Department();
        $departments_data = $department->getDepartments();
        $data['departments'] = json_decode(json_encode($departments_data), true); 
        
        $categories = $Reports->getCategories();
	    $data['categories'] = $categories;
	    
	    $subcategories = $Reports->getSubcategories();
	    $data['subcategories'] = $subcategories;
	    
	    
	    
            $value = array("All");
            
            if ( isset($input['vdepcode']) && in_array("All", $input['vdepcode'] ))
            {
               $data['dept_code']= $value;
            }
            else{
               $data['dept_code']=$input['vdepcode'] ?? $value; 
            }
            
            $array_dept = implode(',', $data['dept_code']); 
         
         
            if ( isset($input['vcategorycode']) && in_array("All", $input['vcategorycode'])){
             $data['cat_code']= $value;
            }
            else{
              $data['cat_code']=$input['vcategorycode'] ?? $value;   
            }
           
            $array_cat = implode(',', $data['cat_code']); 
            
            
            
            if (isset($input['subcat_id']) && in_array("All", $input['subcat_id'])){
             $data['sub_code']= $value;
            }
            else{
               $data['sub_code']=$input['subcat_id'] ?? $value; 
            }
            $array_sub = implode(',', $data['sub_code']); 
            
            
            if (isset($input['manufacturer_id']) && in_array("All", $input['manufacturer_id'])){
             $data['manu_code']= $value;
            }
            else{
              $data['manu_code']=$input['manufacturer_id'] ??$value;   
            }
           
            $array_manu = implode(',', $data['manu_code']); 
            
            if (isset($input['ivendorid']) &&in_array("All", $input['ivendorid'])){
                  $data['sup_code']= $value; 
            }
            else{
               $data['sup_code']=$input['ivendorid'] ??$value;   
            }
            
          
            $array_sup = implode(',', $data['sup_code']); 
            
            
            
            $start_date=$input['start_date'];
            $end_date=$input['end_date'];
            
             $startTime = DateTime::createFromFormat('Y-m-d H:i:s', $input['start_date']);
             $fstarttime = $startTime->format('m-d-Y H:i:s');
             
             $endTime = DateTime::createFromFormat('Y-m-d H:i:s', $input['end_date']);
             $fendttime = $endTime->format('m-d-Y H:i:s');
             
             $data['start_date']=$fstarttime;
             $data['end_date']=$fendttime;
             
            $data['dates_selected'] = $input['dates'];
            
            $data['p_start_date'] = $input['start_date'];
           
            
            $data['p_end_date'] = $input['end_date'];
            
            
            $data['vdepcode'] = $input['vdepcode'] ?? $value;
          
            $data['category_list'] = $input['vcategorycode'] ??$value;
          
            $data['subcategory_list'] = $input['subcat_id'] ??$value;
         
            $data['sup_list'] = $input['ivendorid'] ??$value;
            $data['manu_list'] = $input['manufacturer_id'] ??$value;
            
            
       
            $data['report_hourly'] = $Reports->hourlyReport($array_dept,$array_cat,$array_sub,$array_sup,$array_manu,$start_date,$end_date);
            $graph_data = $splitted_data = [];
            
            if(!empty($data['report_hourly']))
            {
                
                foreach($data['report_hourly'] as $report)
                {
                    
                    $graph_data[]     = ["data"=> $report['Amount'],"label" => $report['trn_date']." ".$report['Hours']];
                }
                
                if(!empty($graph_data))
                {
                    $splitted_data['lable'] = array_column($graph_data,'label');
                    $splitted_data['data'] = array_column($graph_data,'data');
                }
                
            }
           $data['graph_data'] = $splitted_data;
            

            $store=$Reports->getStore();
        
   

        
            $data['store']=$store;
      
       

          session()->put('session_data',  $data);
        
     
          return view('Reports.HourlySalesReport', $data);
       
    }
    
        
    

    public function eodPdf()
    {
       
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G"); 
        $data= session()->get('session_data') ;
        

        $pdf = PDF::loadView('Reports.HourlySalesReportPdf',$data);
        

        //$pdf = PDF::loadView('Reports.Endofdaypdf', compact('data','paidout','hourly','report_new_dept','store','date'));  
        return $pdf->download('HourlySalesReportPdf.pdf');

        
    }

    public function print()
    {
        
        ini_set('max_execution_time', 0);
        $data= session()->get('session_data') ;
    
        return view('Reports.HourlySalesReportPdf',$data);  

    }
    public function csv(){
        ini_set('max_execution_time', -1);
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data= session()->get('session_data') ;
        $data_row = '';
        
        $data_row .= PHP_EOL;

        
       // $data_row .= "EOD From date: ".$data['p_start_date'].' '."EOD To date: ".$data['p_end_date'].PHP_EOL;
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= "Date: ".$data['dates_selected'].PHP_EOL;
        $data_row .= PHP_EOL;
        $data_row .= 'Sales by Hours'.','.'Amount'.','.'Transactions'. PHP_EOL;

        if(isset($data['report_hourly']) && count($data['report_hourly']) > 0){
            foreach($data['report_hourly']as $report_hourly_sale){
                $data_row .= str_replace(',',' ',$report_hourly_sale['Hours']).','."$".$report_hourly_sale['Amount'].','.$report_hourly_sale['Number'].PHP_EOL;
            }
        }else{
            $data_row .= 'Sorry no data found!'.PHP_EOL;
        }
        $file_name_csv = 'Hours.csv';

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
      public function getcategories(Request $request){
        $input = $request->all();
		$data = array();
        $category = new Category();          
        $data = $category->getCategoriesInDepartment1($input);			
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
        $zeromovement = new ZeroMovement();     
        $data = $zeromovement->getSubCategories1($input);
            $response = [];
            $obj= new \stdClass();
		    $obj->id = "All";
		    $obj->text = "All";
		    array_push($response, $obj);
			foreach($data as $k => $v){			    
			    $obj= new \stdClass();
			    $obj->id = $v['subcat_id'];
			    $obj->text = $v['subcat_name'];
			    array_push($response, $obj);
			}
	    echo json_encode($response);
    }
   
     public function getslotitem(Request $request){
         
          
        $input = $request->all();
                
        
            $salesid_arr = explode(",", $input['salesids']);
            $salesids = join("','", $salesid_arr);
            
            $sql = "SELECT trn_sd.vitemname as vitemname, COUNT(trn_sd.vitemname) as no_of_times, SUM(trn_sd.ndebitqty) as ndebitqty, date_format(trn_s.dtrandate,'%m-%d-%Y %H:%i:%s') as trn_date_time
                            FROM trn_sales trn_s left join trn_salesdetail trn_sd on trn_s.isalesid=trn_sd.isalesid 
                            WHERE trn_s.isalesid IN ('".$salesids."') GROUP BY trn_sd.vitemname ";
            
            
	       $result1 = DB::connection('mysql_dynamic')->select($sql);
           $result = json_decode(json_encode($result1), true); 
    	   return $result;
	
	   
	    
	    
	}
}
