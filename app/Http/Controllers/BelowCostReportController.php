<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
use Illuminate\Support\Facades\Session;
class BelowCostReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $Reports = new Reports;
        
        $data['byreports'] = $Reports->getDepartments();
      
        return view('Reports.BelowCostReport',$data);
        
    }

   

    public function getlist(Request $request){

        ini_set('max_execution_time', -1);
        ini_set("memory_limit", "2G");

        $input = $request->all();
        $Reports = new Reports;
        $data['byreports'] = $Reports->getDepartments();
        $store=$Reports->getStore();
        $data['store']=$store;
        $data['selected_byreports'] = $input['report_by'] ?? '';
        $visted= $input['visited_below_cost_report'] ?? '';
        //return $visted;
        //$data['pitemncitem'][]=array();
        if ($request->isMethod('get') && $visted!='Yes') {
           
                
                $reportsdata = $Reports->getBelowCostReport($input );
                
               if(count($reportsdata)>0){
                foreach ($reportsdata as $result) {
                    
                    $data['pitemncitem'][]= array(
                        'itemname'     => $result->itemname,
                        'cost'   => $result->cost,
                        'price'=>$result->price,
                        'vbarcode'=>$result->vbarcode,
                        'item_link'=> url('/item/edit/'. $result->iitemid.'?visited_below_cost_report=Yes'),
                        
                        
                    );
                }
           
            $data['reports'] = $data['pitemncitem'];
            }
            
            Session::put('inputs', $input);
            session()->put('session_data',  $data);
            $request->session()->forget('visited_below_cost_report');
        }
        elseif(isset($input['visited_below_cost_report']) && $input['visited_below_cost_report'] =="Yes")
        {   
            
            $input = $request->session()->get('inputs');
            $reportsdata = $Reports->getBelowCostReport($input );
                foreach ($reportsdata as $result) {
                    
                    $data['pitemncitem'][]= array(
                        'itemname'     => $result->itemname,
                        'cost'   => $result->cost,
                        'price'=>$result->price,
                        'vbarcode'=>$result->vbarcode,
                        // 'item_link'=> '/item/edit/'.$result->iitemid.'?visited_below_cost_report=Yes',
                        'item_link' => url('/item/edit/'. $result->iitemid.'?visited_below_cost_report=Yes'),
                        
                    );

                }
            $data['reports'] = $data['pitemncitem'];
            $data['selected_byreports'] = $input['report_by'] ?? '';
            Session::put('inputs', $input);
            session()->put('session_data',  $data);
            $request->session()->forget('visited_below_cost_report');
        }        
  
     
      return view('Reports.BelowCostReport', $data);
       
    }
    
        
    

    public function eodPdf()
    {
        ini_set('max_execution_time', -1);
        ini_set("memory_limit", "2G");
        $data= session()->get('session_data') ;
        

        $pdf = PDF::loadView('Reports.BelowCostReportPdf',$data);
        unset($data);
        
        return $pdf->download('BelowCostReportPdf.pdf');

        
    }

    public function print()
    {
        
        ini_set('max_execution_time', 0);
        $data= session()->get('session_data') ;
    
        return view('Reports.BelowCostReportPdf',$data);  

    }
    public function csv(){
        ini_set('max_execution_time', 0);
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data= session()->get('session_data') ;
        $data_row = '';
        
        $data_row .= PHP_EOL;

        
        
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
         if(count($data['reports']) > 0){
            $data_row .= 'Item name,SKU,Cost,Price'.PHP_EOL;

            foreach ($data['reports'] as $key => $value) {
                $data_row .= str_replace(',',' ',$value['itemname']).','.str_replace(',',' ',$value['vbarcode']).','.number_format((float)$value['cost'], 2, '.', '').','.number_format((float)$value['price'], 2, '.', '').PHP_EOL;
            }

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
