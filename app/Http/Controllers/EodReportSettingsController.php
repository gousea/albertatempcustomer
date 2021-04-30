<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\EodSettings;
use PDF;
use DateTime;
class EodReportSettingsController extends Controller

{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        
        $query = "SELECT * FROM information_schema.tables WHERE table_schema = 'u".session()->get('sid')."' AND table_name = 'trn_manual_sales' LIMIT 1";
        $run_query = DB::connection('mysql')->select($query);

        if(count($run_query) === 0){
            
            // Changed the table name to trn_manual_sales on 08 Dec 2020
            /*$create_query = "CREATE TABLE trn_manual_sales (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `bid` bigint(30) NOT NULL,
                  `type` enum('Dollar','Percent') DEFAULT NULL,
                  `non` decimal(15,2) DEFAULT NULL,
                  `ts1` decimal(15,2) DEFAULT NULL,
                  `ts2` decimal(15,2) DEFAULT NULL,
                  `ts3` decimal(15,2) DEFAULT NULL,
                  `status` varchar(10) DEFAULT NULL,
                  `SID` int(11) NOT NULL DEFAULT '".session()->get('sid')."',
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `LastUpdate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`)
                )";*/
            
            $create_query = "CREATE TABLE trn_manual_sales ( 
                	`id` int(11) NOT NULL AUTO_INCREMENT, 
                    `bid` bigint(30) NOT NULL, 
                    `type` enum('Dollar','Percent') DEFAULT 'Dollar', 
                    `non` decimal(15,2) DEFAULT 0.00, 
                    `ts1` decimal(15,2) DEFAULT 0.00, 
                    `ts2` decimal(15,2) DEFAULT 0.00, 
                    `ts3` decimal(15,2) DEFAULT 0.00, 
                    `status` varchar(10) DEFAULT 'Active', 
                    `SID` int(11) NOT NULL DEFAULT '".session()->get('sid')."', 
                    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                    `LastUpdate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                    PRIMARY KEY (`id`),
                	UNIQUE KEY `bid_UNIQUE` (`bid`)
                )";

            $run_query = DB::connection('mysql_dynamic')->statement($create_query);
        }
        return view('StoreSettings.eod_report_settings');
    }

   

    public function get_list(Request $request){

        $input = $request->all();

        $start_date = $input['start_date'].' 00:00:00';
        $end_date = $input['end_date'].' 23:59:59';

        $query = 'SELECT date_format(teod.dstartdatetime, "%m/%d/%Y") eod_date, tb.ibatchid ibatchid, tb.ntotaltaxable total_taxable_sales, tb.ntotalnontaxable total_nontaxable_sales, tb.ntotaltax total_tax, tb.nnetsales net_sales FROM trn_batch tb
                LEFT JOIN trn_endofdaydetail teodid on tb.ibatchid = teodid.batchid
                LEFT JOIN trn_endofday teod on teodid.eodid=teod.id 
                WHERE tb.dbatchendtime BETWEEN \''.$start_date.'\' AND \''.$end_date.'\'';
        $data = DB::connection('mysql_dynamic')->select($query);
//  dd($data);       
        $start_date = DateTime::createFromFormat('Y-m-d', $input['start_date']);
        $p_start_date = $start_date->format('m/d/Y');
        
        $end_date = DateTime::createFromFormat('Y-m-d', $input['end_date']);
        $p_end_date = $end_date->format('m/d/Y');
        
        return view('StoreSettings.eod_report_settings')
                    ->with('data', $data)
                    ->with('p_start_date', $p_start_date)
                    ->with('p_end_date', $p_end_date);
    }
    
    
    public function submit_values(Request $request){
        $input = $request->all();
// dd($input);
        // loop through the array of batch_ids and insert into table
        foreach($input['batch_ids'] as $bid){
            
            $existing_eod_collection = EodSettings::where('bid', '=', $bid);
            
            // if percentage convert it into dollars
            if($input['type'] === 'Percent'){
                
                $query = "select batchid,
                            sum(NonTaxableSales) + sum(CouponTender) NonTaxableSales,
                            sum(TaxableSales) TaxableSales,
                            sum(Tax1Sales) Tax1Sales,
                            sum(Tax2Sales) Tax2Sales,
                            sum(Tax3Sales) Tax3Sales,
                            sum(TotalTax) TotalTax,
                            sum(tax1) Tax1,
                            sum(tax2) Tax2,
                            sum(tax3) Tax3,
                            sum(NonTaxableSales) + sum(CouponTender) + sum(TaxableSales) + sum(TotalTax) TotalStoreSales
                        from
                        (
                        	select b.isalesid, 
                        	max(ibatchid) batchid,
                        	max(b.nnettotal) netsales,
                        	max(b.NNONTAXABLETOTAL) NonTaxableSales,
                        	max(b.NTAXABLETOTAL) TaxableSales,
                        	max(b.NTAXTOTAL) TotalTax,
                        	max(b.NDISCOUNTAMT) Discounted_Amount,
                        
                            
                            sum(round((nextunitprice * itemtaxrateone / 100),2)) tax1,
                        	sum(round((nextunitprice * itemtaxratetwo / 100),2)) tax2, 
                        	sum(round((nextunitprice * itemtaxratethree / 100),2)) tax3,
                            sum(case when itemtaxrateone>0 then nextunitprice else 0 end) Tax1Sales,
                            sum(case when itemtaxrateone=0 and itemtaxrateTwo>0 then nextunitprice else 0 end) Tax2Sales,
                            sum(case when itemtaxrateone=0 and itemtaxrateTwo=0 and itemtaxratethree>0 then nextunitprice else 0 end) Tax3Sales,
                        	
                        	sum(case when vitemcode='1' then ifnull(nExtunitPrice,0.00) else 0.00 end) bottledeposit, 
                        	sum(case when vitemcode='10' then ifnull(nExtunitPrice,0.00) else 0.00 end) bottledepositredeem,
                        	sum(case when vitemcode='1' then ifnull(nitemtax,0.00) else 0.00 end) bottledeposittax,
                        	sum(case when vitemcode='10' then ifnull(nitemtax,0.00) else 0.00 end) bottledepositredeemtax,
                           
                            sum(case when a.vitemcode = '20' then ifnull(nExtunitPrice,0.00) else 0.00 end) LotterySales,
                            sum(case when a.vitemcode = '21' and b.vtrntype='Transaction' then ifnull(nExtunitPrice,0.00) else 0.00 end) InstantSales,
                        	sum(case when a.vitemcode in ('6','22') then ifnull(nExtunitPrice,0.00) else 0.00 end) LotteryRedeem,
                        	sum(case when a.vitemcode = '23' then ifnull(nExtunitPrice,0.00) else 0.00 end) InstantRedeem,
                            sum(case when a.vitemcode = '18' then ifnull(Abs(ndebitamt),0) else 0.00 end) CouponTender
                            
                        	from trn_salesdetail a join trn_sales b on a.isalesid = b.isalesid 
                            where b.vtrntype='Transaction' and b.ibatchid  = '".$bid."'
                            group by  b.isalesid 
                            ) s GROUP BY 
                            batchid";
                
            
                $run_query = DB::connection('mysql_dynamic')->select($query);    
                
                // dd(($input['tax1']/100)*$run_query[0]->Tax1Sales);
                
                if(!isset($run_query[0])){
                    $type      = $input['type'];
                    $ts1       = 0;
                    $ts2       = 0;
                    $ts3       = 0;
                    $non       = 0;
                } else {
                    // echo ($input['tax1']/100)*($run_query[0]->Tax1Sales); die;
                    $type      = $input['type'];
                    $ts1       = ($input['tax1']/100)*($run_query[0]->Tax1Sales);
                    $ts2       = ($input['tax2']/100)*($run_query[0]->Tax2Sales);
                    $ts3       = ($input['tax3']/100)*($run_query[0]->Tax3Sales);
                    $non       = ($input['non_tax']/100)*($run_query[0]->NonTaxableSales);
                }
            

            } elseif($input['type'] === 'Dollar'){
                $type      = $input['type'];
                $ts1       = $input['tax1'];
                $ts2       = $input['tax2'];
                $ts3       = $input['tax3'];
                $non       = $input['non_tax'];
            } else {
                break;
            }
            
            
            /*print_r([
                    'type'      => $type,
                    'ts1'       => $ts1,
                    'ts2'       => $ts2,
                    'ts3'       => $ts3,
                    'non'       => $non,
                    'status'    => 'Active'
                ]);*/
                
            
            
            if($existing_eod_collection->count() > 0){
                // echo __LINE__.'<br>';
                $existing_eod_collection->update(
                    [
                        'type'      => $type,
                        'ts1'       => $ts1,
                        'ts2'       => $ts2,
                        'ts3'       => $ts3,
                        'non'       => $non,
                        'status'    => 'Active'
                    ]
                );
            } else {
                // echo __LINE__.'<br>';
                $eod_settings = new EodSettings();
                $eod_settings->bid      = $bid;
                $eod_settings->type     = $type;
                $eod_settings->ts1      = $ts1;
                $eod_settings->ts2      = $ts2;
                $eod_settings->ts3      = $ts3;
                $eod_settings->non      = $non;
                $eod_settings->status   = 'Active';
                $eod_settings->save();  
            }
        }
        // die();
        return response()->json(['message' => 'Settings saved successfully']);
    }
    
        
    


     
}
