<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
class POHistoryReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        
        ini_set('max_execution_time', -1);
        $Reports = new Reports;

        $data['byreports']= $Reports->getVendors();
       

        return view('Reports.POHistoryReport',$data);
        
    }

   

    public function getlist(Request $request){
        ini_set('max_execution_time', -1);
        $Reports = new Reports;
        $input = $request->all();
       
       if( in_array('ALL',$input['report_by']))
       {
        $reportsdata = $Reports->getPoHistoryReportAll($input);

       }
       else{
        $reportsdata = $Reports->getPoHistoryReport($input);
       }
       
        $p_start_date=$input['start_date'];
        $p_end_date=$input['end_date'];
        
        
        $store=$Reports->getStore();
       
        $byreports= $Reports->getVendors();

        session()->put('session_data',  $reportsdata);
        session()->put('session_sdate',  $p_start_date);
        session()->put('session_edate',  $p_end_date);
        session()->put('session_store',  $store);

        
        
        $reports = $reportsdata;
      
        return view('Reports.POHistoryReport', compact('reports','store','p_start_date','p_end_date','byreports'));
       
    }
    public function printpreview(Request $request)
    {
        ini_set('max_execution_time', -1);
        $input = $request->all();
        $Reports = new Reports;
        
        $return = array();

        $datas = $Reports->getViewItemspre($input['vendor_id'],$input['vendor_date'], $input['vendor_ipoid']);

        $return['code'] = 1;
        $return['data'] = $datas;
       
        echo json_encode($return);
        exit;  
    }
        
    

    public function eodPdf()
    {
       
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        
        $data= session()->get('session_data') ;
        
        $p_end_date= session()->get('session_edate') ;
        $p_start_date= session()->get('session_sdate') ;
        $store= session()->get('session_store') ;

        // $data = (array)$data[0];
       
       
        $store['p_end_date'] = $p_end_date;
        $store['p_start_date']=$p_start_date;
        $store['reports']=$data;
        $store['store']=$store; 
        

        $pdf = PDF::loadView('Reports.POHistoryReportpdf',$store);
        
        unset($store);

        //$pdf = PDF::loadView('Reports.Endofdaypdf', compact('data','paidout','hourly','report_new_dept','store','date'));  
        return $pdf->download('POHistoryReportpdf.pdf');

        
    }

    public function print()
    {
        
        ini_set('max_execution_time', -1);
        $data= session()->get('session_data') ;
        
        $p_end_date= session()->get('session_edate') ;
        $p_start_date= session()->get('session_sdate') ;
        $store= session()->get('session_store') ;

        // $data = (array)$data[0];
       
       
        $store['p_end_date'] = $p_end_date;
        $store['p_start_date']=$p_start_date;
        $store['reports']=$data;
        $store['store']=$store; 
       
        return view('Reports.POHistoryReportpdf',$store);  

    }
    public function csv(){
        
        ini_set('max_execution_time', -1);
        
        $data_row = '';
        
        $data_row .= PHP_EOL;

        
        $data= session()->get('session_data') ;
        
        $p_end_date= session()->get('session_edate') ;
        $p_start_date= session()->get('session_sdate') ;
        $store= session()->get('session_store') ;
        $data['reports'] = $data;
        
       
        $data_row .= PHP_EOL;
        $data_row = '';
        $data_row .= "RO History Report ".PHP_EOL;
        $data_row .= "From : ". $p_start_date. ' ' . "To : ".$p_end_date.PHP_EOL;
        
        $data_row .= "Store Address:  : ". session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address:  : ". $store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone : ". $store[0]->vphone1.PHP_EOL;
       
        $data_row .= PHP_EOL;
        $data['net_tot']=$data['rip_tot']=0;

        if(count($data['reports']) > 0){
            $data_row .= 'Vendor,Date,Net Total,RIP Total Amt'.PHP_EOL;

            foreach ($data['reports'] as $key => $value) {
              
                $data_row .= str_replace(',',' ',$value['vvendorname']).','.$value['dcreatedate'].','.number_format((float)$value['nnettotal'], 2, '.', '').','.number_format((float)$value['rip_total'], 2, '.', '').PHP_EOL;
                $data['rip_tot']=$data['rip_tot']+$value['rip_total'];
                $data['net_tot']=$data['net_tot']+$value['nnettotal'];
            }

            $data_row .= ',Total,$'.$data['net_tot'].',$'.$data['rip_tot'].PHP_EOL;

        }else{
            $data_row = 'Sorry no data found!';
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
