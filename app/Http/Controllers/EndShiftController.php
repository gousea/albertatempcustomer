<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
class EndShiftController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
      
        return view('EoSReport.EndofShift');
        
    }

    public function batchdata()
    {
            ini_set('max_execution_time', -1);
             $start_date = date('Y-m-d', strtotime($_GET['start_date']));
        
             $fDateTime = DateTime::createFromFormat('Y-m-d', $start_date);
             $fDateString = $fDateTime->format('m-d-Y');
	         $sql="select batchid AS ibatchid  
                    from trn_endofday e 
                    join trn_endofdaydetail d on e.id=d.eodid 
    				where date_format(e.dstartdatetime,'%m-%d-%Y') 
                    between '". $fDateString ."' and  '". $fDateString ."'";
          
            $result = DB::connection('mysql_dynamic')->select($sql);

            // dd($result);
            return response()->json($result);
        
    }

    public function getlist(Request $request){
        ini_set('max_execution_time', -1); 
        $input = $request->all();
        $p_batch_id=$input['batch_id'];
        $p_start_date=$input['start_date'];
        
        $Reports = new Reports;

        $data = $Reports->getEodshiftReport($p_batch_id);
        $report_new_dept=$Reports->getEodshiftdepartment($p_batch_id);
        $store=$Reports->getStore();
        

        session()->put('session_data',  $data);
        session()->put('session_report_new_dept',  $report_new_dept);
        session()->put('session_date',  $p_start_date);
        session()->put('session_batch',  $p_batch_id);

        
        
        
        return view('EoSReport.EndofShift', compact('data','report_new_dept','store','p_start_date','p_batch_id'));
       
    }
    public function eodPdf()
    {
        
        ini_set('max_execution_time', -1);
        $data= session()->get('session_data') ;
        $p_batch_id= session()->get('session_batch') ;
        $report_new_dept= session()->get('session_report_new_dept') ;
        $date= session()->get('session_date') ;

        $data = (array)$data[0];
       
       
        $store['p_batch_id'] = $p_batch_id;
        $store['report_new_dept']=$report_new_dept;
        $store['data']=$data;
        
       //return $store;
        $pdf = PDF::loadview('EoSReport.EndofShiftpdf',$store);
        

        //$pdf = PDF::loadview('EoSReport.Endofdaypdf', compact('data','paidout','hourly','report_new_dept','store','date'));  
        return $pdf->download('endofshift.pdf');

        
    }

    public function print()
    {
        ini_set('max_execution_time', -1);
        $data= session()->get('session_data') ;
        $p_batch_id= session()->get('session_batch') ;
        $report_new_dept= session()->get('session_report_new_dept') ;
        $date= session()->get('session_date') ;

        $data = (array)$data[0];
       
       
        $store['p_batch_id'] = $p_batch_id;
        $store['report_new_dept']=$report_new_dept;
        $store['data']=$data;
       
        return view('EoSReport.EndofShiftpdf',$store);  

    }
    public function csv(){
        
        ini_set('max_execution_time', -1);
        $data_row = '';
        
        $data_row .= PHP_EOL;
        $p_batch_id= session()->get('session_batch') ;

        $data= session()->get('session_data') ;
        
        $report_new_dept= session()->get('session_report_new_dept') ;
        $store= session()->get('session_store') ;
        $date= session()->get('session_date') ;
        $value = (array)$data[0];

        $data_row .= 'Store Name'.',' .''.session()->get('storeName').PHP_EOL;
        $data_row .= 'SID'.',' .''.session()->get('sid').PHP_EOL;

        $data_row .= 'Batch  '.',' .''.$p_batch_id.PHP_EOL;
        $data_row .= 'SHIFT START '.',' .''.$value['BatchStartTime'].PHP_EOL;
        $data_row .= 'SHIFT END'.',' .''.$value['BatchEndTime'].PHP_EOL;
        $data_row .= 'Register No'.',' .''.$value['TerminalId'].PHP_EOL;
        $data_row .= PHP_EOL;

        
        
        $data_row.='SALES TOTALS'.PHP_EOL;
              
        $data_row .= 'SALES(excluding Tax)'.',' .'$'.$value['SalesExclTax'].PHP_EOL;
        $data_row .= 'TAXABLE SALES'.',' .'$'.$value['TotalTaxable'].PHP_EOL;
        $data_row .= 'NON-TAXABLE'.',' .'$'.$value['TotalNonTaxable'].PHP_EOL;
        $data_row .= 'TOTAL TAX'.',' .'$'.$value['TotalTax'].PHP_EOL;
        
        if($value['TotalLottery'] !=0){
        $data_row .= 'TOTAL LOTTO SALES'.',' .'$'.$value['TotalLottery'].PHP_EOL;
        }
        
        if($value['liabilitysales'] !=0){
        $data_row .= 'LIABILITY SALES'.',' .'$'.$value['liabilitysales'].PHP_EOL;
        }
        
        if($value['BottleDeposit'] !=0){
        $data_row .= 'BOTTLE DEPOSITE'.',' .'$'.$value['BottleDeposit'].PHP_EOL;
        }
        
        if($value['BottleDepositRedeem'] !=0){
        $data_row .= 'BOTTLE DEPOSITE REDEEM'.',' .'$'.$value['BottleDepositRedeem'].PHP_EOL;
        }
        
        if($value['BottleDepositTax'] !=0){
        $data_row .= 'BOTTLE DEPOSITE TAX'.',' .'$'.$value['BottleDepositTax'].PHP_EOL;
        }
        
        if($value['BottleDepositRedeemTax'] !=0){
        $data_row .= 'BOTTLE DEPOSITE REDEEM TAX'.',' .'$'.$value['BottleDepositRedeemTax'].PHP_EOL;
        }
        
        if($value['HouseAcctPay'] !=0){
        $data_row .= 'HOUSE ACCOUNT PAYMENTS'.',' .'$'.$value['HouseAcctPay'].PHP_EOL;
        }
        
        
        $data_row .= 'GRAND TOTAL'.',' .'$'.$value['NetSales'].PHP_EOL;
     
       
      
      
      
      
      
       $data_row .= PHP_EOL;
         
       $data_row .= "LOTTO SALES DETAILS".PHP_EOL;
          
        if($value['LotterySales'] !=0){
        $data_row .= 'LOTTERY SALES '.',' .'$'.$value['LotterySales'].PHP_EOL;
        }
        
        if($value['InstantSales'] !=0){
        $data_row .= 'INSTANT SALES'.',' .'$'.$value['InstantSales'].PHP_EOL;
        }
        
        if($value['LotteryRedeem'] !=0){
        $data_row .= 'LOTTERY REDEEM '.',' .'$'.$value['LotteryRedeem'].PHP_EOL;
        }
        
        
        if($value['InstantRedeem'] !=0){
        $data_row .= 'INSTANT REDEEM'.',' .'$'.$value['InstantRedeem'].PHP_EOL;
        }
        
          
          
        //new tender detail  
      
          $data_row .= PHP_EOL;
         
   
          $data_row .= "TENDER DETAILS".PHP_EOL;
         
        if($value['CashTender'] !=0){
        $data_row .= 'CASH'.',' .'$'.$value['CashTender'].PHP_EOL;
        }
        
        if($value['CreditCardTender'] !=0){
        $data_row .= 'CREDIT CARD '.',' .'$'.$value['CreditCardTender'].PHP_EOL;
        }
        
        
        if($value['CheckTender'] !=0){
        $data_row .= 'CHECK'.',' .'$'.$value['CheckTender'].PHP_EOL;
        }
        
        if($value['EBTTender'] !=0){
        $data_row .= 'EBT'.',' .'$'.$value['EBTTender'].PHP_EOL;
        }
        
        if($value['HouseAcctTender'] !=0){
        $data_row .= 'ON ACCT  '.',' .'$'.$value['HouseAcctTender'].PHP_EOL;
        }
        
        if($value['HouseAcctCash'] !=0){
        $data_row .= 'HOUSE ACCT PAYMENT CASH  '.',' .'$'.$value['HouseAcctCash'].PHP_EOL;
        }
        
        if($value['HouseAcctCard'] !=0){
        $data_row .= 'OUSE ACCT PAYMENT CREDITCARD '.',' .'$'.$value['HouseAcctCard'].PHP_EOL;
        }
        
         if($value['HouseAcctCheck'] !=0){
        $data_row .= 'HOUSE ACCT PAYMENT CHECK '.',' .'$'.$value['HouseAcctCheck'].PHP_EOL;
        }
        $data_row.='PERFORMANCE STATISTICS'.PHP_EOL;
              if($value['TotalReturns']!=0){
              $data_row .= '#RETURNED ITEMS'.',' .'$'.$value['TotalReturns'].PHP_EOL;
              }
              
              
              $data_row .= '#OF TRANSACTION '.',' .$value['NoOfTransactions'].PHP_EOL;
              $data_row .= '#AVG TRANSACTIONS'.',' .'$'.$value['AvgSaleTrn'].PHP_EOL;
              
              if(isset($value['Surcharges']) && $value['Surcharges'] !=0){
                $data_row .= '#Surcharges Collected'.',' .'$'.$value['Surcharges'].PHP_EOL;
                }
                
               if(isset($value['EbtTaxExempted'] ) && $value['EbtTaxExempted'] !=0){
                $data_row .= '#EBT Tax Exempted'.',' .'$'.$value['EbtTaxExempted'].PHP_EOL;
                } 
              
              $data_row .= PHP_EOL;
              
              $data_row.='CASH COUNT'.PHP_EOL;
              
              $data_row .= 'OPENING CASH'.',' .'$'.$value['OpeningBalance'].PHP_EOL;
              $data_row .= 'CASH SALES'.',' .'$'.$value['CashTender'].PHP_EOL;
              $data_row .= 'CASH ADD'.',' .'$'.$value['NetAddCash'].PHP_EOL;
              
              if($value['HouseAcctPay']!=0){
              $data_row .= 'ON ACCOUNT'.',' .'$'.$value['HouseAcctPay'].PHP_EOL;
              }
              
              $data_row .= 'CASH PAIDOUT'.',' .'$'.$value['NetPaidout'].PHP_EOL;
              $data_row .= 'CASH  PICKUP'.',' .'$'.$value['NetCashPickup'].PHP_EOL;
              
              
              $data_row.=''.PHP_EOL;
              
              $data_row .= 'EXPECTED CASH'.',' .'$'.$value['ClosingBalance'].PHP_EOL;
              $data_row .= 'ACTUAL CASH'.',' .'$'.$value['UserClosingBalance'].PHP_EOL;
              $data_row .= 'CASH SHORT'.',' .'$'.$value['CashShortOver'].PHP_EOL;
              
            $data_row .= PHP_EOL;
     
      
        $data_row .= PHP_EOL;
        
        
          
        $data_row .= "DEPARTMENT SALES SUMMARY    :,";
       
        $data_row .= PHP_EOL;
        
        $data_row .= "Department Name,Quantity,Sales,Cost,GP%";
        $data_row .= PHP_EOL;
        
        foreach($report_new_dept as $val){     
           
          
            $data_row .=$val->vdepatname.','.$val->qty.','.$val->saleamount.','.$val->cost.','.$val->gpp.PHP_EOL;
        }
        
        
        $data_row .= PHP_EOL;

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
