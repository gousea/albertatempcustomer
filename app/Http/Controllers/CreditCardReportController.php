<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
class CreditCardReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        ini_set('max_execution_time', -1);
      
        return view('Reports.CreditCardReport');
        
    }

   

    public function getlist(Request $request){
        ini_set('max_execution_time', -1);

        $input = $request->all();
        $p_start_date=$input['start_date'] ?? '';
        $p_end_date=$input['end_date'] ?? '';
        $credit_card_number = $input['credit_card_number'] ?? '';
        $credit_card_amount= $input['credit_card_amount'] ?? '';


        $Reports = new Reports;

        $data = $Reports->getCreditCardReport($p_start_date,$p_end_date,$credit_card_number,$credit_card_amount);
        //return $data;
        
        $data_for_file = $Reports->data_for_file($p_start_date,$p_end_date,$credit_card_number,$credit_card_amount);
       
        $store=$Reports->getStore();
        

        session()->put('session_data_for_file',  $data_for_file);
        session()->put('session_data',  $data);
        session()->put('session_sdate',  $p_start_date);
        session()->put('session_edate',  $p_end_date);
        session()->put('session_store',  $store);
        session()->put('credit_card_number',  $credit_card_number);
        session()->put('credit_card_amount',  $credit_card_amount);
        
        
        $reports = $data;
        return view('Reports.CreditCardReport', compact('reports','store','p_start_date','p_end_date','credit_card_number','credit_card_amount'));
       
    }

    public function getsaleid(Request $request){
         
        ini_set('max_execution_time', -1);
         $input = $request->all();
         $Reports = new Reports;
        
         $json =array();
         $p_start_date=$input['start_date'] ?? '';
         $p_end_date=$input['end_date'] ?? '';
         $credit_card_number = session()->get('credit_card_number') ?? '';
         $credit_card_amount= session()->get('$credit_card_amount') ?? '';
         $pullby=$input['report_pull_by'];
         
         $data = $Reports->ajaxDataCreditCardReport($p_start_date,$p_end_date,$credit_card_number,$credit_card_amount,$pullby);
          
    
          
          echo json_encode($data);
          exit;
    
        
      }
      public function print(Request $request)
    {
        ini_set('max_execution_time', -1);
        $input = $request->all();
        $Reports = new Reports;
       
        $id=$input['id'] ?? '';
        $by=$input['by'] ?? '';

        $data = $Reports->getReceiptData($id,$by);
        $vauthcode= $Reports->getauthcode($id,$by);
        $image= $Reports->getimage($id,$by);
        $data = (array) $data;
        
        $salesid = $data[0]->isalesid;
        
        $vauthcode = $vauthcode[0]->vauthcode;

        $sales_header_data= $Reports->getSalesById($salesid);
        $sales_header =(array)$sales_header_data[0];

        $trn_date = DateTime::createFromFormat('m-d-Y h:i A', $sales_header['trandate']);
        $trn_date = $trn_date->format('m-d-Y');

        $sales_detail_data= $Reports->getSalesPerview($salesid);
        $sales_detail=$sales_detail_data;

        $sales_tender_data=$Reports->getSalesByTender($salesid);
        $sales_tender=$sales_tender_data;

        $sales_customer_data=$Reports->getSalesByCustomer($sales_header['icustomerid']);
        $sales_customer=(array)$sales_customer_data;

        $store_info_data=$Reports->getStore();
        $store_info=(array)$store_info_data[0];

        session()->put('store_info',  $store_info);
        session()->put('sales_customer',  $sales_customer);
        session()->put('sales_tender',  $sales_tender);
        session()->put('sales_detail',  $sales_detail);

        session()->put('trn_date',  $trn_date);
        session()->put('sales_header',  $sales_header);
        session()->put('salesid',  $salesid);
        session()->put('vauthcode',  $vauthcode);
        
       
        
        return view('Reports.CreditCardPrint', compact('store_info','sales_customer','sales_tender','sales_detail','trn_date','sales_header','salesid','vauthcode'));
       
       

    }
    public function printpreview(){
        
       ini_set('max_execution_time', -1);    
       $store_info= session()->get('store_info') ;
       $sales_customer= session()->get('sales_customer') ;
       $sales_tender= session()->get('sales_tender') ;
       $sales_detail= session()->get('sales_detail') ;

       $trn_date= session()->get('trn_date') ;
       $sales_header= session()->get('sales_header') ;
       $salesid= session()->get('salesid') ;
       return view('Reports.CreditCardPrint', compact('store_info','sales_customer','sales_tender','sales_detail','trn_date','sales_header','salesid'));
       

    }
     public function csv(){
        ini_set('max_execution_time', -1);
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data= session()->get('session_data_for_file') ;
        $data_row = '';
        //dd($data);
        $data_row .= PHP_EOL;

        
        $data_row .= "From : ".session()->get('session_sdate').' '." To : ".session()->get('session_edate').PHP_EOL;
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
        $data_row .= "Date ,Time ,LAST FOUR OF CC,APPROVAL CODE	,AMOUNT	,CARD TYPE".PHP_EOL;

       
        foreach($data as $v){
       
              $data_row .= isset($v->date) ? $v->date:"";
              $data_row .= ",";
                      
              $data_row .= isset($v->time) ? $v->time:"";
              $data_row .= ",";
        
              $data_row .= $v->last_four_of_cc;
              $data_row .= ",";
              
              $data_row .= isset($v->approvalcode) ? $v->approvalcode:"";
              $data_row .= ",";
              
              $data_row .= isset($v->amount) ? $v->amount:"";
              $data_row .= ",";
              
              $data_row .= isset($v->vcardtype) ? $v->vcardtype:"";
              $data_row .= ",";
              
             
             
            $data_row .= PHP_EOL;

            }
        $file_name_csv = 'CCreport.csv';

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
    public function pdf()
    {
       
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        
        $data_list['card_data']= session()->get('session_data_for_file') ;
        
        $data_list['start_date']= session()->get('session_sdate') ;
        $data_list['end_date']= session()->get('session_edate') ;
        $Reports = new Reports;
        $store=$Reports->getStore();
        
        $data_list['Store_Adress']= $store[0]->vaddress1;
        $data_list['Store_Phone']= $store[0]->vphone1;

        $pdf = PDF::loadView('Reports.ccReportPdf',$data_list);
     
        return $pdf->download('ccReportPdf.pdf');

        
    }

    public function ccprint()
    {
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        $data_list['card_data']= session()->get('session_data_for_file') ;
        $data_list['start_date']= session()->get('session_sdate') ;
        $data_list['end_date']= session()->get('session_edate') ;
        $Reports = new Reports;
        $store=$Reports->getStore();
        
        $data_list['Store_Adress']= $store[0]->vaddress1;
        $data_list['Store_Phone']= $store[0]->vphone1;

    
        return view('Reports.ccReportPdf',$data_list);  

    }
     
}
