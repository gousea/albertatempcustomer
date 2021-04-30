<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;
class ProfitLossReportController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $data['byreports'] = array(
                        1 => 'Category',
                        2 => 'Department',
                        ///3 => 'Item Group'
                      );
        return view('Reports.ProfitLoss',$data);
        
    }

   

    public function getlist(Request $request){

        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $Reports = new Reports;
        $store=$Reports->getStore();
        $input = $request->all();
       
        $reportsdatas = $Reports->getProfitLossReport($input);
        
        
        $data['byreports'] = array(
            1 => 'Category',
            2 => 'Department',
            //3 => 'Item Group'
          );
        $out=array();
        foreach($reportsdatas as  $x){
          $out[$x->vname]['vname']=$x->vname;
          $out[$x->vname]['details'][]=array('vITemName'=>$x->vITemName,'DCOSTPRICE'=>$x->DCOSTPRICE,'dUnitPrice'=>$x->dUnitPrice,'TOTUNITPRICE'=>$x->TOTUNITPRICE,'TotCostPrice'=>$x->TotCostPrice,'Amount'=>$x->Amount,'AmountPer'=>$x->AmountPer,'TotalQty'=>$x->TotalQty);
        }
      
        $p_start_date=$input['start_date'];
        $p_end_date=$input['end_date'];
        $data['selected_report_by'] = $input['report_by'];
        $data['p_start_date']=$p_start_date;
        $data['p_end_date']=$p_end_date;
        $data['store']=$store;
        $data['reports'] = $reportsdatas;
        $data['out'] = $out;

        session()->put('session_data',  $data);
     
        return view('Reports.ProfitLoss', $data);
       
    }
    
        
    

    public function eodPdf()
    {
       
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        $data= session()->get('session_data') ;
        

        $pdf = PDF::loadView('Reports.ProfitLosspdf',$data);
        
        unset($data);

        //$pdf = PDF::loadView('Reports.Endofdaypdf', compact('data','paidout','hourly','report_new_dept','store','date'));  
        return $pdf->download('ProfitLosspdf.pdf');

        
    }

    public function print()
    {
        
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $data= session()->get('session_data') ;
    
        return view('Reports.ProfitLosspdf',$data);  

    }
    public function csv(){
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data_row = '';
        
        $data_row .= PHP_EOL;

        
        $data= session()->get('session_data') ;
        $data_row = '';
        $grand_total_qty_sold = 0;
        $grand_total_total_cost = 0;
        $grand_total_total_price = 0;
        $grand_total_mark_up = 0;
        $grand_total_gross_profit = 0;
        $grand_total_number_of_rows = 0;
        $grand_g_p_percent = 0;
        
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;

        if(count($data['reports']) > 0){
            $data_row .= 'Name,Unit Cost,Price,Qty Sold,Total Cost,Total Price,Mark Up(%),Gross Profit,Gross Profit(%)'.PHP_EOL;

              $total_qty_sold1=$total_total_cost1=$total_total_price1=$total_markup1=$total_gross_profit1=$total_gross_profit_percentage=0;
              foreach($data['out'] as $data){
                  
                $data_row .= str_replace(',',' ',$data['vname']).PHP_EOL;
                $total_qty_sold = $total_total_cost = $total_total_price = $total_markup = $total_gross_profit = 0;
                foreach($data['details'] as $items)
                {
                    $total_qty_sold += $items['TotalQty']; $total_total_cost += $items['TotCostPrice']; $total_total_price += $items['TOTUNITPRICE']; $total_markup += $items['AmountPer']; $total_gross_profit += $items['Amount'];
                    
                    $data_row .= str_replace(',',' ',$items['vITemName']) . ', ';
                    $data_row .= str_replace(',',' ',$items['DCOSTPRICE']) . ', ';
                    $data_row .= str_replace(',',' ',$items['dUnitPrice']) . ', ';
                    $data_row .= str_replace(',',' ',$items['TotalQty']) . ', ';
                    $data_row .= str_replace(',',' ',$items['TotCostPrice']) . ', ';
                    $data_row .= str_replace(',',' ',$items['TOTUNITPRICE']) . ', ';
                    $data_row .= str_replace(',',' ',number_format(($items['AmountPer']),2)). ', ';
                    $data_row .= str_replace(',',' ',$items['Amount']) . ', ';
                    $data_row .= str_replace(',',' ',number_format(($items['Amount']/$items['TOTUNITPRICE'])*100,2)).PHP_EOL;
                    
                }
                 if($total_total_cost != '0.00'){
                                    $subtotal_markup = (($total_gross_profit/$total_total_cost) * 100);
                                    }
                                    else{
                                    $subtotal_markup=100.00;
                                    }
                $subtotalgrossprofitpercentage=number_format(($total_gross_profit/$total_total_price)*100,2);    
                
                $data_row .= "Sub Total : ".',,,'.$total_qty_sold.','.$total_total_cost.','.$total_total_price.','.number_format($subtotal_markup,2).','.$total_gross_profit.' ,'.$subtotalgrossprofitpercentage.PHP_EOL;
                
                $total_qty_sold1+=$total_qty_sold;
                $total_total_cost1+=$total_total_cost;
                $total_total_price1+=$total_total_price;
                $total_gross_profit1+=$total_gross_profit;
                if($total_total_cost1 !=0 &&  $total_total_cost1 !=0)
                {
                   $total_markup1=(($total_gross_profit1)/$total_total_cost1)*100;
                }
                else{
                   $total_markup1=0;
                }
                if($total_gross_profit1 !=0 &&  $total_total_price1 !=0)
                {
                $total_gross_profit_percentage=(($total_gross_profit1/$total_total_price1)*100);
                }
                else{
                   $total_gross_profit_percentage=0;
                }
                
            }
              $data_row .= "Total : ".',,,'.$total_qty_sold1.','.$total_total_cost1.','.$total_total_price1.','.number_format($total_markup1,2).','.$total_gross_profit1.' ,'.number_format($total_gross_profit_percentage,2).PHP_EOL;
                

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
