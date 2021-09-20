<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
class PaidOutReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $Reports = new Reports;
        $vendor_list =$Reports->getvendorlist();
        
         $data['vendor_list']=$vendor_list;
      
        return view('Reports.PaidOutReport',$data);
        
    }

   

    public function getlist(Request $request){

        ini_set('max_execution_time', -1);
        $Reports = new Reports;

        $input = $request->all();
        $vendorid=$input['vendorid'] ??'';
        $amounttype= $input['amount_by'] ??'';
        $amount= $input['amount'] ?? '';
        $tender= $input['tender_by']?? '';

        $vendor_list =$Reports->getvendorlist();
        
        $data['vendor_list']=$vendor_list;

        $store=$Reports->getStore();
        
        
        $data['report_paid_out'] = $Reports->paidOut($input['start_date'], $input['end_date'],$vendorid,$amounttype,$amount,$tender);
        
              
        //new report modification 
         $preport=$Reports->newpaidOut($input['start_date'], $input['end_date'],$vendorid,$amounttype,$amount,$tender);
         
         
         	  
         $reportsdata = json_decode(json_encode($preport), true); 
         $out=array();
         foreach($reportsdata as  $x){
             
         $out[$x['Vendor']]['Vendorname']=$x['Vendor'];
         $out[$x['Vendor']]['details'][]=array('Vendor'=>$x['Vendor'],'Amount'=>$x['Amount'], 'RegNo'=>$x['RegNo'], 'UserId'=>$x['UserId'], 'TenderType'=>$x['TenderType'], 'dt'=>$x['dt'], 'ttime'=>$x['ttime']);
        
         }           
        //dd($out);
        $data['out']=$out;
        
        
        
             
        $startdate= DateTime::createFromFormat('Y-m-d',$input['start_date']);
        $data['p_start_date'] =$startdate->format('m-d-Y');
        
        $enddate= DateTime::createFromFormat('Y-m-d',$input['end_date']);
      
        $data['p_end_date'] =$enddate->format('m-d-Y');

        
        $data['store']=$store;
        $data['selected_reg'] =$vendorid;
       

        session()->put('session_data',  $data);
       // session()->put('report_paid_out',   $data['report_paid_out']);
        
     
        return view('Reports.PaidOutReport', $data);
       
    }
    
        
    

    public function eodPdf()
    {
       
        ini_set('max_execution_time', 0);
        $data= session()->get('session_data') ;
        

        $pdf = PDF::loadView('Reports.PaidOutReportPdf',$data);
        

        //$pdf = PDF::loadView('Reports.Endofdaypdf', compact('data','paidout','hourly','report_new_dept','store','date'));  
        return $pdf->download('PaidOutReportPdf.pdf');

        
    }

    public function print()
    {
        
        ini_set('max_execution_time', 0);
        $data= session()->get('session_data') ;
    
        return view('Reports.PaidOutReportPdf',$data);  

    }
    public function csv(){
        ini_set('max_execution_time', -1);
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data= session()->get('session_data') ;
        $data_row = '';
        
        $data_row .= PHP_EOL;

        
        $data_row .= "EOD From date: ".$data['p_start_date'].' '."EOD To date: ".$data['p_end_date'].PHP_EOL;
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
        $data_row .= "Sl. No,Paid Date,Vendor Name,Amount,Tender Type,Register No,Time,User ID".PHP_EOL;

        $count = 0; 
        foreach($data['report_paid_out'] as $v){
            
            if($v->Vendor == "Total") {
                continue;
              } 
              $data_row .= ++$count.",";
              
              $data_row .= isset($v->dt) ? $v->dt:"";
              $data_row .= ",";
                      
              $data_row .= isset($v->Vendor) ? $v->Vendor:"";
              $data_row .= ",";
        
              $data_row .= "$".$v->Amount;
              $data_row .= ",";
              
              $data_row .= isset($v->TenderType) ? $v->TenderType:"";
              $data_row .= ",";
              
              $data_row .= isset($v->RegNo) ? $v->RegNo:"";
              $data_row .= ",";
              
              $data_row .= isset($v->ttime) ? $v->ttime:"";
              $data_row .= ",";
              
              $data_row .= isset($v->UserId) ? $v->UserId:"";
             
            $data_row .= PHP_EOL;

            }
        $file_name_csv = 'PHreport.csv';

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
