<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Reports;
use PDF;
use DateTime;

class PromotionReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');                                              // AUTHENTICATION 
    }
    
    
    public function index(){

      
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
      
        // PROMOTION NAME AND PROMOTION  ID DATA 
        $promocode = DB::connection("mysql_dynamic")->select("SELECT  prom_id , prom_name FROM trn_promotions");
        $data['promo_list']=$promocode;
        
        return view('Reports.Promotion.Promotion',$data);                       // VIEW FILE 
        
    }
    
    public function get_data(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $input = $request->all();
        
        $prom_id=$input['prom_id'] ??'';
        $sdate=$input['start_date'];
        $edate=$input['end_date'];
        $data['selected_promid'] =$prom_id ?? '';
        //for selected date disply
        $startdate= DateTime::createFromFormat('Y-m-d',$input['start_date']);
        $data['p_start_date'] =$startdate->format('m-d-Y');
        
        $enddate= DateTime::createFromFormat('Y-m-d',$input['end_date']);
        $data['p_end_date'] =$enddate->format('m-d-Y');
    
        $SQL="select tsd.idettrnid TRANSACTION_NO, tsd.LastUpdate TDATE ,p.barcode SKU ,tsd.vitemname ITEMNAME ,p.unit_price PRICE,
                tsd.ndiscountamt DISCOUNTED_AMOUNT,p.discounted_price DISCOUNTED_PRICE , ndebitqty QTY from trn_sales ts
                join trn_salesdetail tsd on  ts.isalesid=tsd.isalesid
                join (select prom_name, barcode ,discounted_price,tpd.unit_price from trn_promotions tp
                	join trn_prom_details tpd on tp.prom_id = tpd.prom_id 
                	where date(start_date) >= '".$sdate."' and (end_date is null or end_date <= '".$edate."') 
                	and tp.prom_id = $prom_id) p on tsd.vitemcode = p.barcode
                where vtrntype='Transaction' and date(dtrandate) between '".$sdate."' and '".$edate."'";
        //dd($SQL);       
        $promodata= DB::connection("mysql_dynamic")->select($SQL);
        $data['promo_data']=$promodata;
        
        $promocode = DB::connection("mysql_dynamic")->select("SELECT  prom_id , prom_name FROM trn_promotions");
        $data['promo_list']=$promocode;
        
        //CSV PRINT AND PDF DATA
        
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data['store']=$store;
        session()->put('session_data',  $data);
        
        //END
        
        return view('Reports.Promotion.Promotion',$data); 
        
    }
    public function csv(){
        
        ini_set('max_execution_time', -1);
        $Reports = new Reports;
        $store=$Reports->getStore();
        $data= session()->get('session_data') ;
        $data_row = '';
        //dd($data);
        $data_row .= PHP_EOL;

        
        $data_row .= "From date: ".$data['p_start_date'].' '."To date: ".$data['p_end_date'].PHP_EOL;
        $data_row .= "Store Name: ".session()->get('storeName').PHP_EOL;
        $data_row .= "Store Address: ".$store[0]->vaddress1.PHP_EOL;
        $data_row .= "Store Phone: ".$store[0]->vphone1.PHP_EOL;
        $data_row .= PHP_EOL;
        
        $data_row .= "DATE,SKU,ITEM NAME,PRICE,DISCOUNTED AMOUNT,DISCOUNTED PRICE,QTY,TRANSACTION #".PHP_EOL;

      
        foreach($data['promo_data'] as $v){
      
              
              $data_row .= isset($v->TDATE) ? $v->TDATE:"";
              $data_row .= ",";
                      
              $data_row .= isset($v->SKU) ? $v->SKU:"";
              $data_row .= ",";
        
              $data_row .= "$".$v->ITEMNAME;
              $data_row .= ",";
              
              $data_row .= isset($v->PRICE) ? $v->PRICE:"";
              $data_row .= ",";
              
              $data_row .= isset($v->DISCOUNTED_AMOUNT) ? $v->DISCOUNTED_AMOUNT:"";
              $data_row .= ",";
              
              $data_row .= isset($v->DISCOUNTED_PRICE) ? $v->DISCOUNTED_PRICE:"";
              $data_row .= ",";
              
              $data_row .= isset($v->QTY) ? $v->QTY:"";
              $data_row .= ",";
              
              $data_row .= isset($v->TRANSACTION_NO) ? $v->TRANSACTION_NO :"";
             
           
             
            $data_row .= PHP_EOL;

            }
        $file_name_csv = 'Promotionreport.csv';

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

?>