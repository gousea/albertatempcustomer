<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
class ItemAuditListController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        
        ini_set('max_execution_time', -1);
       
        $Reports = new Reports;
        
        $users = $Reports->users();
	    $data['users'] = $users;
	    $itemname = $Reports->item_list();
	    $data['itemname'] = $itemname;
	   
      
        return view('ItemAudit.ItemAudit_list',$data);
        
    }
    public function getlist(Request $request){
        
     
        ini_set('max_execution_time', -1);
        $input = $request->all();
      
        $start_date=$input['start_date'];
        $end_date=$input['end_date'];
           
                if(isset($start_date) && !empty(($start_date))){
                $startTime = DateTime::createFromFormat('m-d-Y', $start_date);
                $fstarttime = $startTime->format('Y-m-d');
                
                $endTime = DateTime::createFromFormat('m-d-Y',$end_date);
                $fendttime = $endTime->format('Y-m-d');
                }
                else{
                    $fstarttime=date('Y-m-d');
                    $fendttime=date('Y-m-d');
                }
                
            $userid=$input['userid'];
            $itemname=$input['itemname'];
            $mtype=$input['mtype'];
         
        $Reports = new Reports;
        $store=$Reports->getStore();

        
        $item_data = $Reports->getItems_list($fstarttime,$fendttime,$userid,$itemname,$mtype);
        
        $data['iuserid']=$input['userid'];
	    $data['vitemname']=$input['itemname'];
	    
	    
	    if(isset($start_date) && !empty(($start_date))){
	    $startTime = DateTime::createFromFormat('m-d-Y', $start_date);
        $fstarttime = $startTime->format('m-d-Y');
        
        $endTime = DateTime::createFromFormat('m-d-Y',$end_date);
        $fendttime = $endTime->format('m-d-Y');
        
        
	    $data['start_date']=$fstarttime;
	    $data['end_date']=$fendttime;
	    }
	 
	    $data['itemname'] = $itemname;
        $data['mtype']=$mtype;
		$data['list'] = $item_data;
	
        $users = $Reports->users();
	    $data['users'] = $users;
	    $itemname = $Reports->item_list();
	    $data['itemname'] = $itemname;
         $count=0;
         foreach ($data['list']as $key => $value) {
              if($value['beforem'] != $value['afterm']){
                  $count++;
              }
         }
         $data['count']=$count;
	    
	    session()->put('session_data',  $data);
        return view('ItemAudit.ItemAudit_list',$data);
       
    }
    
    public function getDepartment(Request $request){
	    
	    $input = $request->all();
        $Reports = new Reports;
	    $department_code = $input['department_code'];
	   
	   // echo json_encode($department_code); die;
	    
	    if($department_code)
	    {
	    
	    $results = $Reports->getdeptmodi($department_code);
	    
	    //$this->response->addHeader('Content-Type: application/json');
	   
	    
	    if(count($results)>0){
	        $html ="<ol class='list-group' style='margin:15px;'>";
    	    foreach($results as $result){
    	        $html .="<h3 style='font-size:15px;'>".$result['vdepartmentname']."</h3>";
    	    }
	    }

	    $html .="</ol>";
	    
	    $data['html'] = $html;
	 
	   echo json_encode($data);
		   
	    }
	    else{
			$data['error'] = 'Something went wrong';
			// http_response_code(401);
			$this->response->addHeader('Content-Type: application/json');
		    echo json_encode($data);
			exit;
		}
	}
 public function getcat(Request $request){
	    $input = $request->all();
        $Reports = new Reports;
	    //$this->load->model('api/items/last_modify_items');
	    $department_code =$input['department_code'];
	   
	   //echo json_encode($department_code); die;
	    
	    if($department_code)
	    {
	    
	    $results =$Reports->getcatmodi($department_code);
	    
	    //$this->response->addHeader('Content-Type: application/json');
	   
	    
	    if(count($results)>0){
	        $html ="<ol class='list-group' style='margin:15px;'>";
    	    foreach($results as $result){
    	        $html .="<h3 style='font-size:15px;'>".$result['vcategoryname']."</h3>";
    	    }
	    }

	    $html .="</ol>";
	    
	    $data['html'] = $html;
	 
	   echo json_encode($data);
		   
	    }
	    else{
			$data['error'] = 'Something went wrong';
			// http_response_code(401);
			$this->response->addHeader('Content-Type: application/json');
		    echo json_encode($data);
			exit;
		}
	}
public function getuseremail(Request $request){
	    $input = $request->all();
        $Reports = new Reports;
	    //$this->load->model('api/items/last_modify_items');
	    $id =$input['department_code'];
	   
	   //echo json_encode($department_code); die;
	    //dd($id);
	    if($id)
	    {
	    
	    $results =$Reports->getuser_item_audit($id);
	    
	    //$this->response->addHeader('Content-Type: application/json');
	   
	    
	    if(count($results)>0){
	        $html ="<ol class='list-group' style='margin:15px;'>";
    	    foreach($results as $result){
    	        $html .="<h3 style='font-size:15px;'>".$result['vemail']."</h3>";
    	    }
	    }
	     else{
	        $html ="<ol class='list-group' style='margin:15px;'>";
    	   // foreach($results as $result){
    	        $html .="<h3 style='font-size:15px;'>Email Not Found</h3>";
    	   // }
	    }

	    $html .="</ol>";
	    
	    $data['html'] = $html;
	 
	   echo json_encode($data);
		   
	    }
	    else{
			$data['error'] = 'Something went wrong';
			// http_response_code(401);
			$this->response->addHeader('Content-Type: application/json');
		    echo json_encode($data);
			exit;
		}
	}

    public function Pdf()
    {
       
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        
        $data= session()->get('session_data') ;
        

        $pdf = PDF::loadView('ItemAudit.print',$data);
        

        return $pdf->download('ItemAudit.pdf');


        
    }

    public function print()
    
    {
       ini_set('max_execution_time', 0);
        $data= session()->get('session_data') ;
    
        return view('ItemAudit.print',$data);  

    }
    public function csv(){
      
        ini_set('max_execution_time', -1);
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data= session()->get('session_data') ;
        $data_row = '';
        
        $data_row .= PHP_EOL;

        
        // $data_row .= "EOD From date: ".$data['p_start_date'].' '."EOD To date: ".$data['p_end_date'].PHP_EOL;
        // $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        // $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        // $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
          $data_row .= '	SKU,Item Name,Modification,Before,Current,Location, Date,Time,User id'.PHP_EOL;
          $loc="Web";


         

          foreach ($data['list']as $key => $value) {
              if($value['beforem'] != $value['afterm']){
               $date = date('m-d-Y',strtotime($value['historydatetime']));
               $time = date('H:i:s',strtotime($value['historydatetime']));
               if(is_numeric($value['beforem'])){
                  $before=number_format(($value['beforem']),2) ;
                  $after=number_format(($value['afterm']),2) ;
               }
               else{
                   $before=$value['beforem'] ;
                   $after=$value['afterm'] ;
               }
               
              $data_row .= str_replace(',',' ',$value['vbarcode']).','.str_replace(',',' ',$value['vitemname']).','.str_replace(',',' ',$data['mtype']).', '.$before.','.$after.','.$loc.','.$date.','.$time.','.$value['userid'].PHP_EOL;
              }
          }
         

      
        
        $file_name_csv = 'aduit.csv';

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
