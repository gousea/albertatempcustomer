<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
class TaxReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
      
        return view('Reports.TaxReport');
        
    }

   
    public function getlist(Request $request){

        $input = $request->all();
        $p_start_date=$input['start_date'];
        $p_end_date=$input['end_date'];
        
        $Reports = new Reports;

        $data = $Reports->getTaxReport($p_start_date,$p_end_date);
        
        
        $store=$Reports->getStore();
        

        session()->put('session_data',  $data);
        session()->put('session_sdate',  $p_start_date);
        session()->put('session_edate',  $p_end_date);
        session()->put('session_store',  $store);

        
        
        $reports = (array)$data[0];
        return view('Reports.TaxReport', compact('reports','store','p_start_date','p_end_date'));
       
    }
    
    public function eodPdf()
    {
       
       
        $data= session()->get('session_data') ;
        
        $p_end_date= session()->get('session_edate') ;
        $p_start_date= session()->get('session_sdate') ;
        $store= session()->get('session_store') ;

        $data = (array)$data[0];
       
       
        $store['p_end_date'] = $p_end_date;
        $store['p_start_date']=$p_start_date;
        $store['reports']=$data;
        $store['store']=$store; 
        

        $pdf = PDF::loadView('Reports.TaxReportPdf',$store);
        

        //$pdf = PDF::loadView('Reports.Endofdaypdf', compact('data','paidout','hourly','report_new_dept','store','date'));  
        return $pdf->download('taxreport.pdf');

        
    }

    public function print()
    {
        
        $data= session()->get('session_data') ;
        
        $p_end_date= session()->get('session_edate') ;
        $p_start_date= session()->get('session_sdate') ;
        $store= session()->get('session_store') ;

        $data = (array)$data[0];
       
       
        $store['p_end_date'] = $p_end_date;
        $store['p_start_date']=$p_start_date;
        $store['reports']=$data;
        $store['store']=$store; 
        
       
        return view('Reports.TaxReportPdf',$store);  

    }
    
    public function csv(){
        $data_row = '';
        
        $data_row .= PHP_EOL;

        
        $data= session()->get('session_data') ;
        
        $p_end_date= session()->get('session_edate') ;
        $p_start_date= session()->get('session_sdate') ;
        $store= session()->get('session_store') ;
        $reports = (array)$data[0];
        
       
        $data_row .= PHP_EOL;
        $data_row = '';
        $data_row .= "Tax Collection Summary".PHP_EOL;
        $data_row .= "From : ". $p_start_date. ' ' . "To : ".$p_end_date.PHP_EOL;
       
        $data_row .= PHP_EOL;

      $data_row .= 'Non-Taxable Sales,'.'$'.number_format((float)$reports['NONTAX'], 2, '.', '').PHP_EOL;
      $data_row .= 'Taxable Sales (Tax1),'.'$'.number_format((float)$reports['Tax1Sales'], 2, '.', '').PHP_EOL;
      $data_row .= 'Taxable Sales (Tax2),'.'$'.number_format((float)$reports['Tax2Sales'], 2, '.', '').PHP_EOL;
      if(isset($reports['Tax3Sales'])){
      $data_row .= 'Taxable Sales (Tax3),'.'$'.number_format((float)$reports['Tax3Sales'], 2, '.', '').PHP_EOL;
      }
      $data_row .= 'Total Taxable Sales,'.'$'.number_format((float)$reports['Tax1Sales'] + (float)$reports['Tax2Sales'] + (float)$reports['Tax3Sales'], 2, '.', '').PHP_EOL;
      $data_row .= 'Net Sales,'.'$'.number_format((float)$reports['Tax1Sales'] + (float)$reports['Tax2Sales'] + (float)$reports['Tax3Sales']+$reports['NONTAX'], 2, '.', '').PHP_EOL;
      
             
      $data_row .= 'Tax1,'. '$'.number_format((float)$reports['tax1'], 2, '.', '').PHP_EOL;
      $data_row .= 'Tax2,'.'$'.number_format((float)$reports['tax2'], 2, '.', '').PHP_EOL;
      
      if(isset($reports['tax3'])){
      $data_row .= 'Tax3,'.'$'.number_format((float)$reports['tax3'], 2, '.', '').PHP_EOL;
      }
       
      $data_row .= 'Total Tax,'.'$'.number_format((float)$reports['TAX'], 2, '.', '').PHP_EOL;
      
      $data_row .= 'Gross Sales,'.'$'.number_format((float)($reports['Tax1Sales'] + (float)$reports['Tax2Sales'] + (float)$reports['Tax3Sales']+$reports['NONTAX'] + $reports['TAX']), 2, '.', '').PHP_EOL;

        $file_name_csv = 'end-of-day-report.csv';

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
