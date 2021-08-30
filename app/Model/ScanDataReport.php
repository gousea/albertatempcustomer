<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ScanDataReport extends Model
{
    

// 	public function getScanDadtaReport($data = array()){
       
// 		// $week_ending_date = \DateTime::createFromFormat('m-d-Y', $data['week_ending_date']);
//         // $week_ending_date = $week_ending_date->format('Y-m-d');
// // echo "<pre>";print_r($data);echo "<pre>";die;
//         $data['week_ending_date'] =  str_replace('/','-',$data['week_ending_date']);
//         $week_ending_date = \DateTime::createFromFormat('m-d-Y', $data['week_ending_date'])->format('Y-m-d');
//         $week_starting_date = date('Y-m-d', strtotime('-6 days', strtotime($week_ending_date)));
        
//         $department_id = $category_id = "";
//         $deptQuery = 'mi.vdepcode <> ""';
//         $catQuery = 'mi.vcategorycode <> ""';
        
//         if($data['department_id'] != ""){
//             $array_department_id = array_filter($data['department_id'], function($value) { return !is_null($value) && $value !== ''; });

//             $department_id = implode(',', $array_department_id);
// // echo "<pre>";print_r($department_id);echo "<pre>";die;
            
//             $deptQuery = 'mi.vdepcode in('.$department_id.')' ;
//         }
// // echo "<pre>";echo($deptQuery);echo "<pre>";die;
        
//         if($data['category_id'] != ""){
            
//             $array_category_id = array_filter($data['category_id'], function($value) { return !is_null($value) && $value !== ''; });
            
//             $category_id = implode(',', $array_category_id);
//             $catQuery = 'mi.vcategorycode in('.$category_id.')' ;
//         }
        
        
//         if(session()->has('new_database') === false) {
            
//             // echo "OLD DATABASE"; die;

//             // $query = "SELECT tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,msupplier.vcompanyname, u.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";
            
//             //New query for Multi pack indicator
            
//             /*$query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,msupplier.vcompanyname, u.*, prom.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi left join   
//             (select vitemcode, 'Y' as 'is_multiple', nbuyqty,ndiscountper, vsaleby from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid where estatus='Active' and (dstartdatetime < current_date() or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
//     		and (denddatetime > current_date() or denddatetime='0000-00-00 00:00:00' or denddatetime is null))  as prom on mi.vbarcode	= prom.vitemcode, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";*/
            
            
//             /*$query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.vcustomername customer_name, ts.icustomerid customer_id, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description, msupplier.vcompanyname, u.*, prom.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi left join   
//             (select vitemcode, 'Y' as 'is_multiple', nbuyqty,sp.ndiscountper sale_ndiscountper, vsaleby, sp.vsalename sale_name, sp.ndiscountqty reg_cust_disc from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid where estatus='Active' and (dstartdatetime < current_date() or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
//     		and (denddatetime > current_date() or denddatetime='0000-00-00 00:00:00' or denddatetime is null))  as prom on mi.vbarcode	= prom.vitemcode, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";*/ 
    		
// 		    $query = "SELECT ts.isalesid, ms.istoreid, ts.dtrandate, ms.vstorename, ms.vaddress1, ms.vcity, ms.vstate, ms.vzip, 
//                     tsd.vcatname, msupplier.vcompanyname, tsd.vitemcode as upc_code, tsd.vitemname, u.vunitname, mi.npack,
//                     tsd.ndebitqty, if(tsd.nsaleamt>0,'Y','N') as is_multiple, prom.nbuyqty, prom.vsaleby, prom.sale_ndiscountper, tsd.nunitprice, prom.sale_name,
//                     prom.regd_cust_disc regd_cust_disc, tsd.ndebitamt, ms.vphone1, ms.vcontactperson, ms.vemail, 
//                     (select vaccountnumber from mst_customer where icustomerid=ts.icustomerid limit 1) cust_acct_no, 
//                     ts.vterminalid, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description,
//                     ts.vcustomername customer_name, ts.icustomerid customer_id ,mi.iitemid, mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down
                     
//                      FROM trn_salesdetail as tsd, 
//                      trn_sales as ts,
//                      mst_store as ms, 
//                      mst_item as mi left join 
                          
//                             (select vitemcode, nbuyqty,sp.ndiscountper sale_ndiscountper, vsaleby, sp.vsalename sale_name, 
//                             sp.ndiscountqty regd_cust_disc,dstartdatetime,denddatetime from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid 
//                             where estatus='Active' and (dstartdatetime <= '".$week_ending_date." 23:59:59' or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
//                     		and (denddatetime >= '".$week_starting_date." 00:00:00' or denddatetime='0000-00-00 00:00:00' or denddatetime is null)) as prom 
//                             on mi.vbarcode	= prom.vitemcode,
                            
//                             mst_supplier as msupplier, 
//                             mst_unit as u  
//                     WHERE u.vunitcode = mi.vunitcode 
//                     AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode 
//                     AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') >= '".$week_starting_date."' 
//                     AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' 
//                     AND ".$deptQuery." 
//                     AND ".$catQuery."
//                     and (dstartdatetime < ts.dtrandate or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
//                     and (denddatetime > ts.dtrandate or denddatetime='0000-00-00 00:00:00' or denddatetime is null)
//                     and (ts.vtrntype = 'Transaction')";
            
//         } else {
            
//             // echo "NEW DATABASE"; die;
            
//             // $query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down,msupplier.vcompanyname, u.*, ts.icustomerid customer_id, prom.*  
 
 
 
 
 
            
//             $query = "SELECT ts.isalesid, ms.istoreid, ts.dtrandate, ms.vstorename, ms.vaddress1, ms.vcity, ms.vstate, ms.vzip, 
//                     tsd.vcatname, msupplier.vcompanyname, tsd.vitemcode as upc_code, tsd.vitemname, u.vunitname, mi.npack,
//                     tsd.ndebitqty, if(tsd.nsaleamt>0,'Y','N') as is_multiple, prom.nbuyqty, prom.vsaleby, prom.sale_ndiscountper, tsd.nunitprice, prom.prom_name sale_name,
//                     prom.regd_cust_disc regd_cust_disc, tsd.ndebitamt, ms.vphone1, ms.vcontactperson, ms.vemail, 
//                     (select vaccountnumber from mst_customer where icustomerid=ts.icustomerid limit 1) cust_acct_no, 
//                     ts.vterminalid, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description,
//                     ts.vcustomername customer_name, ts.icustomerid customer_id ,mi.iitemid, mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down,
                    
//                     @row_number:=CASE
//                         WHEN @customer_no = tsd.isalesid THEN @row_number + 1
//                         ELSE 1
//                         END AS repetitions,
//                     @customer_no:=tsd.isalesid as sales_id

                        
//                         FROM trn_salesdetail as tsd, 
//                         trn_sales as ts, 
//                         mst_store as ms, 
//                         mst_item as mi left join
                        
//                         (select prom_name, tps.barcode vitemcode, buy_qty nbuyqty,discounted_value sale_ndiscountper,discount_type_id vsaleby, tp.mfg_prom_desc mfg_prom_desc,
//                             tp.mfg_buydown_desc mfg_buydown_desc, tp.mfg_multipack_desc mfg_multipack_desc, tp.mfg_discount mfg_discount, tp.addl_disc_cust regd_cust_disc from trn_promotions tp join trn_prom_details  
//                             tps on tp.prom_id=tps.prom_id where status='Active' and 
                            
//                             (date(start_date) <= '".$week_ending_date."' or start_date='0000-00-00' or start_date is null)
                            
// 		                    and 
		                    
// 		                    (date(end_date) >= '".$week_starting_date."' or end_date='0000-00-00 00:00:00' or end_date is null) 
		                    
// 		                    )  as prom 
//                         on mi.vbarcode	= prom.vitemcode,
                        
//                         mst_supplier as msupplier, mst_unit as u
//                         WHERE u.vunitcode = mi.vunitcode 
//                         AND tsd.isalesid=ts.isalesid 
//                         AND ms.istoreid=ts.istoreid 
//                         AND mi.vbarcode=tsd.vitemcode
//                         AND (ts.vtrntype = 'Transaction')
//                         AND msupplier.vsuppliercode=mi.vsuppliercode 
//                         AND date_format(ts.dtrandate,'%Y-%m-%d') >= '".$week_starting_date."' 
//                         AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' 
//                         AND ".$deptQuery." AND ".$catQuery." ";
            
//         }
        

        
// // echo $query; die;
//         $run_query = DB::connection('mysql_dynamic')->select($query);
        
//         // echo "<pre>";print_r($run_query->rows);echo "<pre>";
        
        

//         // $query = $this->db2->query("SELECT s.vcity vcity, s.vstate vstate, s.vzip vzip, t.dtrandate dtrandate, d.idettrnid idettrnid, t.vterminalid, d.ndebitqty, d.nunitprice, d.vitemcode, d.vitemname item_name, d.vsize, m.ndiscountper from trn_sales t join mst_store s on t.istoreid=s.istoreid join trn_salesdetail d on d.isalesid=t.isalesid join mst_item m on d.vitemcode=m.vitemcode where date_format(t.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(t.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND m.vdepcode in($department_id) AND m.vcategorycode in($category_id)");

//             /*$file_path1 = DIR_TEMPLATE."/administration/error_log_sql_debug.txt";

// 			$myfile1 = fopen( DIR_TEMPLATE."/administration/error_log_sql_debug.txt", "a");
// $data_row = json_encode($run_query);
// 			fwrite($myfile1,$data_row);
// 			fclose($myfile1);*/

// 		return $run_query;
// 	}

	public function getScanDadtaReport($data = array()){
	   
       
		// $week_ending_date = \DateTime::createFromFormat('m-d-Y', $data['week_ending_date']);
        // $week_ending_date = $week_ending_date->format('Y-m-d');
        // echo "<pre>";print_r($data);echo "<pre>";die;
        $data['week_ending_date'] =  str_replace('/','-',$data['week_ending_date']);
        $week_ending_date = \DateTime::createFromFormat('m-d-Y', $data['week_ending_date'])->format('Y-m-d');
        $week_starting_date = date('Y-m-d', strtotime('-6 days', strtotime($week_ending_date)));
        
        $department_id = $category_id = "";
        $deptQuery = 'mi.vdepcode <> ""';
        $catQuery = 'mi.vcategorycode <> ""';
        
        if($data['department_id'] != ""){
            $array_department_id = array_filter($data['department_id'], function($value) { return !is_null($value) && $value !== ''; });

            $department_id = implode(',', $array_department_id);
            // echo "<pre>";print_r($department_id);echo "<pre>";die;
            
            $deptQuery = 'mi.vdepcode in('.$department_id.')' ;
        }
            // echo "<pre>";echo($deptQuery);echo "<pre>";die;
        
        if($data['category_id'] != ""){
            
            $array_category_id = array_filter($data['category_id'], function($value) { return !is_null($value) && $value !== ''; });
            
            $category_id = implode(',', $array_category_id);
            $catQuery = 'mi.vcategorycode in('.$category_id.')' ;
        }
        
        
        if(session()->has('new_database') === false) {
            
            // echo "OLD DATABASE"; die;

            // $query = "SELECT tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,msupplier.vcompanyname, u.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";
            
            //New query for Multi pack indicator
            
            /*$query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,msupplier.vcompanyname, u.*, prom.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi left join   
            (select vitemcode, 'Y' as 'is_multiple', nbuyqty,ndiscountper, vsaleby from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid where estatus='Active' and (dstartdatetime < current_date() or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
    		and (denddatetime > current_date() or denddatetime='0000-00-00 00:00:00' or denddatetime is null))  as prom on mi.vbarcode	= prom.vitemcode, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";*/
            
            
            /*$query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.vcustomername customer_name, ts.icustomerid customer_id, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description, msupplier.vcompanyname, u.*, prom.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi left join   
            (select vitemcode, 'Y' as 'is_multiple', nbuyqty,sp.ndiscountper sale_ndiscountper, vsaleby, sp.vsalename sale_name, sp.ndiscountqty reg_cust_disc from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid where estatus='Active' and (dstartdatetime < current_date() or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
    		and (denddatetime > current_date() or denddatetime='0000-00-00 00:00:00' or denddatetime is null))  as prom on mi.vbarcode	= prom.vitemcode, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";*/ 
    		
		    $query = "SELECT ts.isalesid, ms.istoreid, ts.dtrandate, ms.vstorename, ms.vaddress1, ms.vcity, ms.vstate, ms.vzip, 
                    tsd.vcatname, msupplier.vcompanyname, tsd.vitemcode as upc_code, tsd.vitemname, u.vunitname, 
                    case when u.vunitname in ('CTN','CARTON') then 10 when u.vunitname in ('ROLL','SLEEVE') then 5 else 1 end as npack,
                    tsd.ndebitqty, if(tsd.nsaleamt>0,'Y','N') as is_multiple, prom.nbuyqty, prom.vsaleby, prom.sale_ndiscountper, tsd.nunitprice, prom.sale_name,
                    prom.regd_cust_disc regd_cust_disc, tsd.ndebitamt, ms.vphone1, ms.vcontactperson, ms.vemail, 
                    (select vaccountnumber from mst_customer where icustomerid=ts.icustomerid limit 1) cust_acct_no, 
                    ts.vterminalid, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description,
                    ts.vcustomername customer_name, ts.icustomerid customer_id ,mi.iitemid, mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down
                     
                     FROM trn_salesdetail as tsd, 
                     trn_sales as ts,
                     mst_store as ms, 
                     mst_item as mi left join 
                          
                            (select vitemcode, nbuyqty,sp.ndiscountper sale_ndiscountper, vsaleby, sp.vsalename sale_name, 
                            sp.ndiscountqty regd_cust_disc,dstartdatetime,denddatetime from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid 
                            where estatus='Active' and (dstartdatetime <= '".$week_ending_date." 23:59:59' or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
                    		and (denddatetime >= '".$week_starting_date." 00:00:00' or denddatetime='0000-00-00 00:00:00' or denddatetime is null)) as prom 
                            on mi.vbarcode	= prom.vitemcode,
                            
                            mst_supplier as msupplier, 
                            mst_unit as u  
                    WHERE CHAR_LENGTH(tsd.vitemcode) > 5 
                    AND u.vunitcode = mi.vunitcode 
                    AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode 
                    AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') >= '".$week_starting_date."' 
                    AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' 
                    AND ".$deptQuery." 
                    AND ".$catQuery."
                    and (dstartdatetime < ts.dtrandate or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
                    and (denddatetime > ts.dtrandate or denddatetime='0000-00-00 00:00:00' or denddatetime is null)
                    and (ts.vtrntype = 'Transaction')";
                    
                    
           
            
        } else {
            
            //  echo "NEW DATABASE"; die;
            
            // $query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down,msupplier.vcompanyname, u.*, ts.icustomerid customer_id, prom.*  
 
 
 
 
 
            //hanamant and mustafa changed 24-2-2021
            // $query = "SELECT ts.isalesid, ms.istoreid, ts.dtrandate, ms.vstorename, ms.vaddress1, ms.vcity, ms.vstate, ms.vzip, 
            //         tsd.vcatname, msupplier.vcompanyname, tsd.vitemcode as upc_code, tsd.vitemname, u.vunitname, 
            //         case when u.vunitname in ('CTN','CARTON') then 10 when u.vunitname in ('ROLL','SLEEVE') then 5 else 1 end as npack,
            //         tsd.ndebitqty, if(tsd.nsaleamt>0,'Y','N') as is_multiple, prom.nbuyqty, prom.vsaleby, prom.sale_ndiscountper, tsd.nunitprice, prom.prom_name sale_name,
            //         prom.regd_cust_disc regd_cust_disc, tsd.ndebitamt, ms.vphone1, ms.vcontactperson, ms.vemail, 
            //         (select vaccountnumber from mst_customer where icustomerid=ts.icustomerid limit 1) cust_acct_no, 
            //         ts.vterminalid, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description,
            //         ts.vcustomername customer_name, ts.icustomerid customer_id ,mi.iitemid, mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down,
                    
            //         @row_number:=CASE
            //             WHEN @customer_no = tsd.isalesid THEN @row_number + 1
            //             ELSE 1
            //             END AS repetitions,
            //         @customer_no:=tsd.isalesid as sales_id

                        
            //             FROM trn_salesdetail as tsd, 
            //             trn_sales as ts, 
            //             mst_store as ms, 
            //             mst_item as mi left join
                        
            //             (select prom_name, tps.barcode vitemcode, buy_qty nbuyqty,discounted_value sale_ndiscountper,discount_type_id vsaleby, tp.mfg_prom_desc mfg_prom_desc,
            //                 tp.mfg_buydown_desc mfg_buydown_desc, tp.mfg_multipack_desc mfg_multipack_desc, tp.mfg_discount mfg_discount, tp.addl_disc_cust regd_cust_disc from trn_promotions tp join trn_prom_details  
            //                 tps on tp.prom_id=tps.prom_id where status='Active' and 
                            
            //                 (date(start_date) <= '".$week_ending_date."' or start_date='0000-00-00' or start_date is null)
                            
		          //          and 
		                    
		          //          (date(end_date) >= '".$week_starting_date."' or end_date='0000-00-00 00:00:00' or end_date is null) 
		                    
		          //          )  as prom 
            //             on mi.vbarcode	= prom.vitemcode,
                        
            //             mst_supplier as msupplier, mst_unit as u
            //             WHERE CHAR_LENGTH(tsd.vitemcode) > 6 
            //             AND u.vunitcode = mi.vunitcode 
            //             AND tsd.isalesid=ts.isalesid 
            //             AND ms.istoreid=ts.istoreid 
            //             AND mi.vbarcode=tsd.vitemcode
            //             AND (ts.vtrntype = 'Transaction')
            //             AND msupplier.vsuppliercode=mi.vsuppliercode 
            //             AND date_format(ts.dtrandate,'%Y-%m-%d') >= '".$week_starting_date."' 
            //             AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' 
            //             AND ".$deptQuery." AND ".$catQuery." ";
            //             //hanamant and mustafa changed 24-2-2021
            
        $query="SELECT ts.isalesid, ms.istoreid, ts.dtrandate, ms.vstorename, ms.vaddress1, ms.vcity, ms.vstate, ms.vzip, tsd.vcatname, msupplier.vcompanyname, tsd.vitemcode as upc_code, tsd.vitemname, u.vunitname, case when u.vunitname in ('CTN','CARTON') then 10 when u.vunitname in ('ROLL','SLEEVE') then 5 else 1 end as npack, tsd.ndebitqty, if(tsd.nsaleamt>0,'Y','N') as is_multiple, prom.nbuyqty, prom.vsaleby, prom.sale_ndiscountper, tsd.nunitprice, prom.prom_name sale_name, 
        
                case when tsd.nsaleamt = (prom.prom_dicount_amt + prom.regd_cust_disc) * tsd.ndebitqty then prom.regd_cust_disc else '' end regd_cust_disc,
                tsd.nsaleamt , discounted_price , prom.regd_cust_disc  regd_cust_disc2 ,
                tsd.ndebitamt, ms.vphone1, ms.vcontactperson, ms.vemail, (select vaccountnumber from mst_customer where icustomerid=ts.icustomerid limit 1) cust_acct_no, ts.vterminalid, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description, ts.vcustomername customer_name, ts.icustomerid customer_id ,mi.iitemid, mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down, @row_number:=CASE WHEN @customer_no = tsd.isalesid THEN @row_number + 1 ELSE 1 END AS repetitions, @customer_no:=tsd.isalesid as sales_id
                FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi 
                left join 
                
                (select prom_name, tps.barcode vitemcode, buy_qty nbuyqty,unit_price, tps.discounted_price,discounted_value sale_ndiscountper,discount_type_id vsaleby, tp.mfg_prom_desc mfg_prom_desc, tp.mfg_buydown_desc mfg_buydown_desc, tp.mfg_multipack_desc mfg_multipack_desc, tp.mfg_discount mfg_discount, tp.addl_disc_cust regd_cust_disc,
                case when discount_type_id = 2 then discounted_value else unit_price * discounted_value /100 end prom_dicount_amt 
                
                from trn_promotions tp 
                join trn_prom_details tps on tp.prom_id=tps.prom_id 
                where status='Active' and 
                
                (date(start_date) <= '".$week_ending_date."' or start_date='0000-00-00' or start_date is null)
                            
		                  and 
		                    
		                  (date(end_date) >= '".$week_starting_date."' or end_date='0000-00-00 00:00:00' or end_date is null) 
		                    
		           )  as prom 
             
                on mi.vbarcode = prom.vitemcode, mst_supplier as msupplier, 
                mst_unit as u WHERE CHAR_LENGTH(tsd.vitemcode) > 5 
                AND u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid  
                AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode 
                AND (ts.vtrntype = 'Transaction') 
                AND msupplier.vsuppliercode=mi.vsuppliercode 
                AND date_format(ts.dtrandate,'%Y-%m-%d') >= '".$week_starting_date."' 
                AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' 
                AND ".$deptQuery." AND ".$catQuery." ";
                
             
                            
}
        

        
//   echo $query; die;
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        // echo "<pre>";print_r($run_query->rows);echo "<pre>";
        
        

        // $query = $this->db2->query("SELECT s.vcity vcity, s.vstate vstate, s.vzip vzip, t.dtrandate dtrandate, d.idettrnid idettrnid, t.vterminalid, d.ndebitqty, d.nunitprice, d.vitemcode, d.vitemname item_name, d.vsize, m.ndiscountper from trn_sales t join mst_store s on t.istoreid=s.istoreid join trn_salesdetail d on d.isalesid=t.isalesid join mst_item m on d.vitemcode=m.vitemcode where date_format(t.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(t.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND m.vdepcode in($department_id) AND m.vcategorycode in($category_id)");

            /*$file_path1 = DIR_TEMPLATE."/administration/error_log_sql_debug.txt";

			$myfile1 = fopen( DIR_TEMPLATE."/administration/error_log_sql_debug.txt", "a");
$data_row = json_encode($run_query);
			fwrite($myfile1,$data_row);
			fclose($myfile1);*/

		return $run_query;
	}


    public function get_rjr_data($data=array()){

		$week_ending_date = \DateTime::createFromFormat('m-d-Y', $data['week_ending_date']);
		$week_ending_date = $week_ending_date->format('Y-m-d');

        $week_starting_date = date('Y-m-d', strtotime('-6 days', strtotime($week_ending_date)));
        
        $department_id = array_filter($data['department_id'], function($value) { return !is_null($value) && $value !== ''; });

        $department_id = implode(',', $department_id);
        
        
        $category_id = array_filter($data['category_id'], function($value) { return !is_null($value) && $value !== ''; });
        
        $category_id = implode(',', $category_id);
        
        if(session()->has('new_database') === false) {
            
            // echo "OLD DATABASE"; die;

            // $query = "SELECT tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,msupplier.vcompanyname, u.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";
            
            //New query for Multi pack indicator
            
            /*$query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,msupplier.vcompanyname, u.*, prom.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi left join   
            (select vitemcode, 'Y' as 'is_multiple', nbuyqty,ndiscountper, vsaleby from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid where estatus='Active' and (dstartdatetime < current_date() or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
    		and (denddatetime > current_date() or denddatetime='0000-00-00 00:00:00' or denddatetime is null))  as prom on mi.vbarcode	= prom.vitemcode, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";*/
            
            
            /*$query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.vcustomername customer_name, ts.icustomerid customer_id, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description, msupplier.vcompanyname, u.*, prom.*  FROM trn_salesdetail as tsd, trn_sales as ts, mst_store as ms, mst_item as mi left join   
            (select vitemcode, 'Y' as 'is_multiple', nbuyqty,sp.ndiscountper sale_ndiscountper, vsaleby, sp.vsalename sale_name, sp.ndiscountqty reg_cust_disc from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid where estatus='Active' and (dstartdatetime < current_date() or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
    		and (denddatetime > current_date() or denddatetime='0000-00-00 00:00:00' or denddatetime is null))  as prom on mi.vbarcode	= prom.vitemcode, mst_supplier as msupplier, mst_unit as u  WHERE u.vunitcode = mi.vunitcode AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') > '".$week_starting_date."' AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' AND mi.vdepcode in($department_id) AND mi.vcategorycode in($category_id)";*/ 
    		
		    $query = "SELECT ts.isalesid, ms.istoreid, ts.dtrandate, ms.vstorename, ms.vaddress1, ms.vcity, ms.vstate, ms.vzip, 
                    tsd.vcatname, msupplier.vcompanyname, tsd.vitemcode as upc_code, tsd.vitemname, u.vunitname, mi.npack,
                    tsd.ndebitqty, if(tsd.nsaleamt>0,'Y','N') as is_multiple, prom.nbuyqty, prom.vsaleby, prom.sale_ndiscountper, tsd.nunitprice, prom.sale_name,
                    prom.regd_cust_disc regd_cust_disc, tsd.ndebitamt, ms.vphone1, ms.vcontactperson, ms.vemail, 
                    (select vaccountnumber from mst_customer where icustomerid=ts.icustomerid limit 1) cust_acct_no, 
                    ts.vterminalid, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description,
                    ts.vcustomername customer_name, ts.icustomerid customer_id ,mi.iitemid, mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down
                     
                     FROM trn_salesdetail as tsd, 
                     trn_sales as ts,
                     mst_store as ms, 
                     mst_item as mi left join 
                          
                            (select vitemcode, nbuyqty,sp.ndiscountper sale_ndiscountper, vsaleby, sp.vsalename sale_name, 
                            sp.ndiscountqty regd_cust_disc,dstartdatetime,denddatetime from trn_saleprice sp join trn_salepricedetail spd on sp.isalepriceid=spd.isalepriceid 
                            where estatus='Active' and (dstartdatetime <= '".$week_ending_date." 23:59:59' or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
                    		and (denddatetime >= '".$week_starting_date." 00:00:00' or denddatetime='0000-00-00 00:00:00' or denddatetime is null)) as prom 
                            on mi.vbarcode	= prom.vitemcode,
                            
                            mst_supplier as msupplier, 
                            mst_unit as u  
                    WHERE u.vunitcode = mi.vunitcode
                    AND length(tsd.vitemcode) > 5
                    AND tsd.isalesid=ts.isalesid AND ms.istoreid=ts.istoreid AND mi.vbarcode=tsd.vitemcode 
                    AND msupplier.vsuppliercode=mi.vsuppliercode AND date_format(ts.dtrandate,'%Y-%m-%d') >= '".$week_starting_date."' 
                    AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' 
                    AND mi.vdepcode in(".$department_id.") 
                    AND mi.vcategorycode in(".$category_id.")
                    AND  tsd.vitemcode REGEXP '^[0-9]+$'
                    and (dstartdatetime < ts.dtrandate or dstartdatetime='0000-00-00 00:00:00' or dstartdatetime is null)
                    and (denddatetime > ts.dtrandate or denddatetime='0000-00-00 00:00:00' or denddatetime is null)
                    and (ts.vtrntype = 'Transaction')";
            
        } else {
            
            // echo "NEW DATABASE"; die;
            
            // $query = "SELECT tsd.vitemcode as upc_code, tsd.*, ts.*,ms.*,mi.iitemid,mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down,msupplier.vcompanyname, u.*, ts.icustomerid customer_id, prom.*  
 
 
 
 
 
            
            $query = "SELECT ts.isalesid, ms.istoreid, ts.dtrandate, ms.vstorename, ms.vaddress1, ms.vcity, ms.vstate, ms.vzip, 
                    tsd.vcatname, msupplier.vcompanyname, tsd.vitemcode as upc_code, tsd.vitemname, u.vunitname, mi.npack,
                    tsd.ndebitqty, if(tsd.nsaleamt>0,'Y','N') as is_multiple, prom.nbuyqty, prom.vsaleby, prom.sale_ndiscountper, tsd.nunitprice, prom.prom_name sale_name,
                    prom.regd_cust_disc regd_cust_disc, tsd.ndebitamt, ms.vphone1, ms.vcontactperson, ms.vemail, 
                    (select vaccountnumber from mst_customer where icustomerid=ts.icustomerid limit 1) cust_acct_no, 
                    ts.vterminalid, mi.aisleid aisle_id, mi.shelfid shelf_id, mi.shelvingid shelving_id, mi.vdescription item_description,
                    ts.vcustomername customer_name, ts.icustomerid customer_id ,mi.iitemid, mi.vsuppliercode,mi.nunitcost,mi.ndiscountper buy_down,
                    
                    @row_number:=CASE
                        WHEN @customer_no = tsd.isalesid THEN @row_number + 1
                        ELSE 1
                        END AS repetitions,
                    @customer_no:=tsd.isalesid as sales_id

                        
                        FROM trn_salesdetail as tsd, 
                        trn_sales as ts, 
                        mst_store as ms, 
                        mst_item as mi left join
                        
                        (select prom_name, tps.barcode vitemcode, buy_qty nbuyqty,discounted_value sale_ndiscountper,discount_type_id vsaleby, tp.mfg_prom_desc mfg_prom_desc,
                            tp.mfg_buydown_desc mfg_buydown_desc, tp.mfg_multipack_desc mfg_multipack_desc, tp.mfg_discount mfg_discount, tp.addl_disc_cust regd_cust_disc from trn_promotions tp join trn_prom_details  
                            tps on tp.prom_id=tps.prom_id where status='Active' and 
                            
                            (date(start_date) <= '".$week_ending_date."' or start_date='0000-00-00' or start_date is null)
                            
		                    and 
		                    
		                    (date(end_date) >= '".$week_starting_date."' or end_date='0000-00-00 00:00:00' or end_date is null) 
		                    
		                    )  as prom 
                        on mi.vbarcode	= prom.vitemcode,
                        
                        mst_supplier as msupplier, mst_unit as u
                        WHERE u.vunitcode = mi.vunitcode
                        AND length(tsd.vitemcode) > 5
                        AND tsd.isalesid=ts.isalesid 
                        AND ms.istoreid=ts.istoreid 
                        AND mi.vbarcode=tsd.vitemcode
                        AND (ts.vtrntype = 'Transaction')
                        AND  tsd.vitemcode REGEXP '^[0-9]+$'
                        AND msupplier.vsuppliercode=mi.vsuppliercode 
                        AND date_format(ts.dtrandate,'%Y-%m-%d') >= '".$week_starting_date."' 
                        AND date_format(ts.dtrandate,'%Y-%m-%d') <= '".$week_ending_date."' 
                        AND mi.vdepcode in(".$department_id.") AND mi.vcategorycode in(".$category_id.")";
            
        }
        

       
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        return $run_query;
    }

    public function getVendors() {
        $query = $this->db2->query("SELECT * FROM mst_supplier ORDER BY isupplierid DESC");

        return $query->rows;
    }

    public function getManufacturers() {
        
        $query_settings = "SELECT * FROM information_schema.tables WHERE table_schema = 'u".session()->get('SID')."' AND table_name = 'mst_manufacturer' LIMIT 1";
        
		$run_query_settings = DB::connection('mysql_dynamic')->select($query_settings);
		
// 		print_r($run_query_settings); die;
		
		if(count($run_query_settings) !== 0){
            $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_manufacturer ORDER BY mfr_name ASC");
            
            // print_r($query); die;
            return $query;		    
		} else {
		    $error = 'Manufacturer records not associated with Items.';
		    return $error;
		}
    }

    public function getDepartments() {
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_department ORDER BY vdepartmentname ASC");

        return $query;
    }
    
    public function getCategories() {
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_category ORDER BY vcategoryname ASC");

        return $query;
    }

    public function getStore() {
        
        
        
        $sql = "SELECT * FROM stores WHERE id = ". (int)(session()->get('sid'));
        
                // echo '<pre>';echo $sql;echo '</pre>';die;


        $query = DB::connection('mysql')->select($sql);

        return (array)$query[0];
    }
    
    public function getAisle($aisle_id) {
        
        if($aisle_id === NULL || $aisle_id == '0' || $aisle_id == ''){
            
            
            
            
            $aislename =  "";
        } else{
            // dd("SELECT IFNULL(aislename,'') aislename FROM mst_aisle WHERE Id = ".$aisle_id);
            $query = DB::connection('mysql_dynamic')->select("SELECT IFNULL(aislename,'') aislename FROM mst_aisle WHERE Id = ".$aisle_id);
            $aislename = isset($query[0])?$query[0]->aislename:'';
             
        }
    
        // $query = DB::connection('mysql_dynamic')->select("SELECT IFNULL(aislename,'') aislename FROM mst_aisle WHERE Id = ".$aisle_id);
        
       
        // $aislename=isset($query->row['aislename'])?$query->row['aislename']:'';

        return $aislename;

        // return $query->row->aislename;
    }
    
    public function getShelf($shelf_id) {
        
        if(gettype($shelf_id) === "NULL"){
            
            return "";
        }
        
        $query = DB::connection('mysql_dynamic')->select("SELECT IFNULL(shelfname,'') shelfname FROM mst_shelf WHERE Id = ".$shelf_id);
        
  
        $shelfname=isset($query[0])?$query[0]->shelfname:'';
        return $shelfname;
        
        //return $query->row['shelfname'];
    }
    
    public function getShelving($shelving_id) {
        
        if(gettype($shelving_id) === "NULL"){
            
            return "";
        }
        
        $query = DB::connection('mysql_dynamic')->select("SELECT shelvingname FROM mst_shelving WHERE id = ".$shelving_id);

        return isset($query[0])?$query[0]->shelvingname:'';
    }
    
    
    public function get_categories($department_ids){
        $concatenate = implode("','", $department_ids);
        
        $query = "SELECT vcategorycode as value, vcategoryname as name FROM mst_category WHERE dept_code IN('".$concatenate."') AND `estatus` = 'Active' ORDER BY vcategoryname ASC";
        
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        return $run_query;
    }

}
