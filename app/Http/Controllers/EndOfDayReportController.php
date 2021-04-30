<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
class EndOfDayReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        
        ini_set('max_execution_time', -1);
      
        $date=date("m-d-Y");
        
        $Reports = new Reports;

        $data = $Reports->getEodReport($date);
        $paidout=$Reports->getEodpaidout($date);
        $hourly=$Reports->getEodhourly($date);
        $report_new_dept=$Reports->getEoddepartment($date);
        $store=$Reports->getStore();
        //return $store;
         
        session()->put('session_data',  $data);
        session()->put('session_paidout',  $paidout);
        session()->put('session_hourly',  $hourly);
        session()->put('session_report_new_dept',  $report_new_dept);
        session()->put('session_store',  $store);
        session()->put('session_date',  $date);
        
        
        return view('EoDReport.Endofday', compact('data','paidout','hourly','report_new_dept','store','date'));
        
    }
    public function getlist(Request $request){
        

       ini_set('max_execution_time', -1);
        $input = $request->all();
        $date=$input['start_date'];
        $Reports = new Reports;

        $data = $Reports->getEodReport($date);
        $paidout=$Reports->getEodpaidout($date);
        $hourly=$Reports->getEodhourly($date);
        $report_new_dept=$Reports->getEoddepartment($date);
        
        
        $store=$Reports->getStore();

        $url_print = url('/eodreport/print');
        session()->put('session_data',  $data);
        session()->put('session_paidout',  $paidout);
        session()->put('session_hourly',  $hourly);
        session()->put('session_report_new_dept',  $report_new_dept);
        session()->put('session_store',  $store);
        session()->put('session_date',  $date);

        
        
        
        return view('EoDReport.Endofday', compact('data','paidout','hourly','report_new_dept','store','date','url_print'));
       
    }
    public function eodPdf()
    {
       
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '-1');
        
        $data= session()->get('session_data') ;
        $paidout= session()->get('session_paidout') ;
        $hourly= session()->get('session_hourly') ;
        $report_new_dept= session()->get('session_report_new_dept') ;
        $store= session()->get('session_store') ;
        $date= session()->get('session_date') ;

        $data = (array)$data[0];
       
        if(isset($store) && !empty($store)){
            $store = (array)$store[0];
        }
       
       // return $hourly;
       $store['store'] = $store;
       $store['report_new_hourly'] = $hourly;
       $store['report_paidout_new'] = $paidout;
       $store['report_new_dept']=$report_new_dept;
       $store['data']=$data;
        $store['date']=$date;
       //return $store;

       $pdf = PDF::loadview('EoDReport.Eodofdaypdf',$store);
        

        //$pdf = PDF::loadview('EoDReport.Endofdaypdf', compact('data','paidout','hourly','report_new_dept','store','date'));  
        return $pdf->download('eodreport.pdf');

        
    }

    public function print()
    
    {
        ini_set('max_execution_time', -1);
        $report_sale_new= session()->get('session_data') ;
        $report_paidout_new= session()->get('session_paidout') ;
        $report_new_hourly= session()->get('session_hourly') ;
        $report_new_dept= session()->get('session_report_new_dept') ;
        $store= session()->get('session_store') ;
        $date= session()->get('session_date') ;
       
        return view('EoDReport.Endofdayprint', compact('report_sale_new','report_paidout_new','report_new_hourly','report_new_dept','store','date'));  

    }
    public function csv(){
        ini_set('max_execution_time', -1);
        $data_row = '';
        
        $data_row .= PHP_EOL;


        $report_sale_new= session()->get('session_data') ;
        $report_paidout_new= session()->get('session_paidout') ;
        $report_new_hourly= session()->get('session_hourly') ;
        $report_new_dept= session()->get('session_report_new_dept') ;
        $store= session()->get('session_store') ;
        $date= session()->get('session_date') ;
        
         $data_row .= "Store Name : ,";
        $data_row .= session()->get('storeName')  ;
        $data_row .= PHP_EOL;
        
        $data_row .= "Store Address:: ,";
        $data_row .= $store[0]->vaddress1;
        $data_row .= PHP_EOL;
        
        $data_row .= "Store Phone ,";
        $data_row .= $store[0]->vphone1;
        $data_row .= PHP_EOL;
        
        $data_row .= "Date: ,";
        $data_row .= $date;
        $data_row .= PHP_EOL;

        $data_row .= "SALES DETAIL: ,";
        $data_row .= PHP_EOL;
        
        
        $data_row .= PHP_EOL;
        $data_row .= "Store Sales ( Excluding Tax): ,";
        $data_row .= "$".$report_sale_new[0]->StoreSalesExclTax;
        $data_row .= PHP_EOL;
        
        $data_row .= "Taxable Sales: ,";
        $data_row .= "$".$report_sale_new[0]->TaxableSales;
        $data_row .= PHP_EOL;
        
        $data_row .= "Non-Taxable Sales: ,";
        $data_row .= "$".$report_sale_new[0]->NonTaxableSales;
        $data_row .= PHP_EOL;
        
        $data_row .= "Total  Tax: ,";
        $data_row .= "$".$report_sale_new[0]->TotalTax;
        $data_row .= PHP_EOL;
        
        if($report_sale_new[0]->Tax1!=0){
        $data_row .= "Tax1: ,";
        $data_row .= "$".$report_sale_new[0]->Tax1;
        $data_row .= PHP_EOL;
        }
        
        
        if($report_sale_new[0]->Tax2!=0){
        $data_row .= "Tax2: ,";
        $data_row .= "$".$report_sale_new[0]->Tax2;
        $data_row .= PHP_EOL;
        }
        
        if($report_sale_new[0]->Tax3!=0){
        $data_row .= "Tax3: ,";
        $data_row .= "$".$report_sale_new[0]->Tax3;
        $data_row .= PHP_EOL;
        }
        
        $data_row .= "Total Store Sales: ,";
        $data_row .= "$".$report_sale_new[0]->StoreSalesInclTax;
        $data_row .= PHP_EOL;
        
        if($report_sale_new[0]->LottoSales!=0){
        $data_row .= "Lotto Sales: ,";
        $data_row .= "$".$report_sale_new[0]->LottoSales;
        $data_row .= PHP_EOL;
        }
        
        if($report_sale_new[0]->LiabilitySales!=0){
        $data_row .= "Liablity Sales: ,";
        $data_row .= "$".$report_sale_new[0]->LiabilitySales;
        $data_row .= PHP_EOL;
        }
        
        if($report_sale_new[0]->TotalBottleDeposit!=0){
        $data_row .= "Total Bottle Deposit: ,";
        $data_row .= "$".$report_sale_new[0]->TotalBottleDeposit;
        $data_row .= PHP_EOL;
        }
        
        
         if($report_sale_new[0]->HouseAcctPayments!=0){
        $data_row .= "HOUSE ACCOUNT PAYMENTS   : ,";
        $data_row .= "$".$report_sale_new[0]->HouseAcctPayments;
        $data_row .= PHP_EOL;
        }
        
        
      
        $data_row .= "Grand Total: ,";
        $data_row .= "$".$report_sale_new[0]->GrandTotal;
        $data_row .= PHP_EOL;
        $data_row .= PHP_EOL;
        
        //------------------------ LOTTERY part
        $data_row .= "LOTTERY SALES DETAILS: ,";
        $data_row .= PHP_EOL;
        
       if($report_sale_new[0]->LotterySales!=0){
        $data_row .= "Lottery Sales: ,";
        $data_row .= "$".$report_sale_new[0]->LotterySales;
        $data_row .= PHP_EOL;
        }
        
        if($report_sale_new[0]->instantSales!=0){
        $data_row .= "Instant Sales:: ,";
        $data_row .= "$".$report_sale_new[0]->instantSales;
        $data_row .= PHP_EOL;
        }
        
        
        if($report_sale_new[0]->LotteryRedeem!=0){
        $data_row .= "Lottery Redeem: ,";
        $data_row .= "$".$report_sale_new[0]->LotteryRedeem;
        $data_row .= PHP_EOL;
        }
     
        if($report_sale_new[0]->InstantRedeem!=0){
        $data_row .= "Instant Redeem: ,";
        $data_row .= "$".$report_sale_new[0]->InstantRedeem;
        $data_row .= PHP_EOL;
        }
        
        
         $data_row .= PHP_EOL;
        //----------------  end lotto  
        $data_row .= "BOTTLE DEPOSIT DETAILS: ,";
        $data_row .= PHP_EOL;
       
        
        
        if($report_sale_new[0]->bottledeposit!=0){
        $data_row .= "Bottle Deposit: ,";
        $data_row .= "$".$report_sale_new[0]->bottledeposit;
        $data_row .= PHP_EOL;
        }
        
        if($report_sale_new[0]->bottledepositredeem!=0){
        $data_row .= "Bottle Deposit Redeem: ,";
        $data_row .= "$".$report_sale_new[0]->bottledepositredeem;
        $data_row .= PHP_EOL;
        }
        
         if($report_sale_new[0]->bottledeposittax!=0){
        $data_row .= "Bottle Deposit Tax: ,";
        $data_row .= "$".$report_sale_new[0]->bottledeposittax;
        $data_row .= PHP_EOL;
        }
        
         if($report_sale_new[0]->bottledepositredeemtax!=0){
        $data_row .= "Bottle Deposit Redeem Tax: ,";
        $data_row .= "$".$report_sale_new[0]->bottledepositredeemtax;
        $data_row .= PHP_EOL;
        }
        
      
      
    
        
        
        //tender start
        $data_row .= PHP_EOL;
        $data_row .= "Tender Summary: ,";
        $data_row .= PHP_EOL;
        
        if($report_sale_new[0]->CashTender!=0){
        $data_row .= "Cash: ,";
        $data_row .= "$".$report_sale_new[0]->CashTender;
        $data_row .= PHP_EOL;
        }
        
        if($report_sale_new[0]->CouponTender!=0){
        $data_row .= "Coupon: ,";
        $data_row .= "$".$report_sale_new[0]->CouponTender;
        $data_row .= PHP_EOL;
        }
        
         if($report_sale_new[0]->CreditCardTender!=0){
        $data_row .= "Credit Card: ,";
        $data_row .= "$".$report_sale_new[0]->CreditCardTender;
        $data_row .= PHP_EOL;
        }
        
        
        if($report_sale_new[0]->CheckTender!=0){
        $data_row .= "Check: ,";
        $data_row .= "$".$report_sale_new[0]->CheckTender;
        $data_row .= PHP_EOL;
        }
        
        if($report_sale_new[0]->HouseAcctCharged!=0){
        $data_row .= "HOUSE ACCTOUNT CHARGED: ,";
        $data_row .= "$".$report_sale_new[0]->HouseAcctCharged;
        $data_row .= PHP_EOL;
        }
        if($report_sale_new[0]->HouseAcctCash!=0){
        $data_row .= "HOUSE ACCT PAYMENT CASH: ,";
        $data_row .= "$".$report_sale_new[0]->HouseAcctCash;
        $data_row .= PHP_EOL;
        }
        if($report_sale_new[0]->HouseAcctCard!=0){
        $data_row .= "HOUSE ACCT PAYMENT CREDITCARD: ,";
        $data_row .= "$".$report_sale_new[0]->HouseAcctCard;
        $data_row .= PHP_EOL;
        }
        if($report_sale_new[0]->HouseAcctCheck!=0){
        $data_row .= "HOUSE ACCT PAYMENT CHECK: ,";
        $data_row .= "$".$report_sale_new[0]->HouseAcctCheck;
        $data_row .= PHP_EOL;
        }
        
        
        
        $data_row .= PHP_EOL;
        
        //  Performance Statstic: start 
        
        $data_row .= "Performance Statstic: ,";
        $data_row .= PHP_EOL;
        
        if($report_sale_new[0]->Discounted_Trns!=0){
        $data_row .= "Discounted Transactions: ,";
        $data_row .= "$".$report_sale_new[0]->Discounted_Trns;
        $data_row .= PHP_EOL;
        }
        
        
        if($report_sale_new[0]->Discounted_Amount!=0){
        $data_row .= "Discounted Amount: ,";
        $data_row .= "$".$report_sale_new[0]->Discounted_Amount;
        $data_row .= PHP_EOL;
        }
        
         if($report_sale_new[0]->Void_Trns!=0){
        $data_row .= "Voided Transactions: ,";
        $data_row .= "$".$report_sale_new[0]->Void_Trns;
        $data_row .= PHP_EOL;
        }
        
         if($report_sale_new[0]->Void_Amount!=0){
        $data_row .= "Voided  Amount: ,";
        $data_row .= "$".$report_sale_new[0]->Void_Amount;
        $data_row .= PHP_EOL;
        }
        
         if($report_sale_new[0]->Deleted_Trns!=0){
        $data_row .= "Deleted Transactions: ,";
        $data_row .= "$".$report_sale_new[0]->Deleted_Trns;
        $data_row .= PHP_EOL;
        }
        
         if($report_sale_new[0]->Deleted_Amount!=0){
        $data_row .= "Deleted Total: ,";
        $data_row .= "$".$report_sale_new[0]->Deleted_Amount;
        $data_row .= PHP_EOL;
        }
        
       
        $data_row .= "Return Transactions: ,";
        $data_row .= $report_sale_new[0]->Return_Trns;
        $data_row .= PHP_EOL;
   
        $data_row .= "Return Total: ,";
        $data_row .= "$".$report_sale_new[0]->Return_Amount;
        $data_row .= PHP_EOL;
        
        if($report_sale_new[0]->NoSale_Count!=0){
        $data_row .= "No Sale Transactions: ,";
        $data_row .= "$".$report_sale_new[0]->NoSale_Count;
        $data_row .= PHP_EOL;
        }
        
        if(isset($report_sale_new[0]->Surcharges) && $report_sale_new[0]->Surcharges!=0){
        $data_row .= "Surcharges Collected: ,";
        $data_row .= "$".$report_sale_new[0]->Surcharges;
        $data_row .= PHP_EOL;
        }
        
        if(isset($report_sale_new[0]->EbtTaxExempted) && $report_sale_new[0]->EbtTaxExempted!=0){
        $data_row .= "EBT Tax Exempted: ,";
        $data_row .= "$".$report_sale_new[0]->EbtTaxExempted;
        $data_row .= PHP_EOL;
        }
        
        $data_row .= PHP_EOL;
        $data_row .= "Productivity,";
        $data_row .= PHP_EOL;
        
        $data_row .= "Transaction Count: ,";
        $data_row .= $report_sale_new[0]->Trns_Count;
        $data_row .= PHP_EOL;
        
        $data_row .= "Average Sales: ,";
        $data_row .= "$".$report_sale_new[0]->Avg_Sales;
        $data_row .= PHP_EOL;
        
        $data_row .= "Gross Cost: ,";
        $data_row .= "$".$report_sale_new[0]->Gross_Cost;
        $data_row .= PHP_EOL;
        
        $data_row .= "Gross Profit: ,";
        $data_row .= "$".$report_sale_new[0]->Gross_Profit;
        $data_row .= PHP_EOL;
        
        $data_row .= "Gross Profit(%):,";
        $data_row .= $report_sale_new[0]->Gross_Profit_Per;
        
        $data_row .= PHP_EOL;
        
        $data_row .= PHP_EOL;
        
        //-------------------------
        
        $data_row .= "Payouts Total:,";
        $ptotal=0;
        foreach($report_paidout_new as $v){
                if($v->Vendor ==="Total"){
                           
                                $ptotal=$v->Amount;
                             
                }
        }
        
        $data_row .= "$".$ptotal;
        $data_row .= PHP_EOL;
        
        $data_row .= "Vendor Name,Amount,Register No	,Time,User ID";
        $data_row .= PHP_EOL;
        
        foreach($report_paidout_new as $val){     
            if($val->Vendor =="Total"){
                       
                         continue;
                         
            }
          
          $data_row .=$val->Vendor.','.$val->Amount.','.$val->RegNo.','.$val->ttime.','.$val->UserId.PHP_EOL;
          
        }
        
        
        $data_row .= PHP_EOL;
        
        $data_row .= "Hourly Sales :,";
        $htotal=$num= 0;
        foreach($report_new_hourly as $v){
                            
                           
                                $htotal=$htotal+$v->Amount;
                                $num=$num+$v->Number;
                             
                            
        }
        
        $data_row .= "$".$htotal.','.$num.PHP_EOL;
        
        // $data_row .= PHP_EOL;
        
        $data_row .= "Hourly Sales,Amount,Sales Transactions";
        $data_row .= PHP_EOL;
        
        foreach($report_new_hourly as $val){     
           
          
          $data_row .=$val->Hours.','.$val->Amount.','.$val->Number.PHP_EOL;
          
        }
        
        
        $data_row .= PHP_EOL;
        
        
          
        $data_row .= "DEPARTMENT SALES SUMMARY    :,";
         $QUANTITY=$SALES=$COST=$GP=0;
          foreach($report_new_dept as $v){
                         if($v->vdepatname === "BOTTLE DEPOSIT"){
                                 continue;
                                 }
                           $QUANTITY=+$QUANTITY+$v->qty;
                           $SALES=+$SALES+$v->saleamount;
                           $COST=+$COST+$v->cost;
                           $GP=+$GP+$v->gpp;
          }              
                           
        $data_row .= $QUANTITY.','."$".$num.','."$".$COST.','.number_format($GP,2).PHP_EOL;             
       
        // $data_row .= PHP_EOL;
        
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
