<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PurchaseOrder extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'trn_purchaseorder';
    protected $primaryKey = 'ipoid';
    public $timestamps = false;
    
    public function getPurchaseOrder($ipoid)
    {
        $return = array();
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_purchaseorder WHERE ipoid='". (int)$ipoid ."'");
        $query = isset($query[0])?(array)$query[0]:[];
        $return = $query;
        
        $sql_query = "SELECT ms.vcompanyname vendor_name, mi.vsize, mi.new_costprice, tpod.*,mi.last_costprice,mi.iqtyonhand as iqtyonhand,mi.dcostprice as dcostprice,
                        mi.dunitprice as dunitprice,mi.ireorderpoint as ireorderpoint, tpod.npackqty as npack, 
                        case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 
                        WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then mi.ireorderpoint 
                        WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint 
                        WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand 
                        WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint 
                        WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand else 0 end as case_qty, 
                        case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 
                        WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint else cast(((mi.ireorderpoint)/mi.npack ) as signed) end) 
                        WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) 
                        WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) 
                        WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then  (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) 
                        WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) else 0 end as total_case_qty 
                        FROM trn_purchaseorderdetail as tpod, mst_item as mi  LEFT JOIN mst_supplier ms ON (mi.vsuppliercode = ms.vsuppliercode) WHERE mi.estatus='Active' AND tpod.vitemid=mi.iitemid AND ipoid='". (int)$ipoid ."' ORDER BY tpod.ipodetid DESC";
        
        $query1 = DB::connection('mysql_dynamic')->select($sql_query);
        
        $query1 = array_map(function ($value) {
                return (array)$value;
            }, $query1);

        $return['items'] = $query1;
        
        return $return;
    }
    
    // public function getSearchItemHistoryAll($search_item,$ivendorid,$pre_items_id)
    // {
    //     if(count($pre_items_id) > 0){
    //         $pre_items_id = implode(',', $pre_items_id);
    //         $query = "SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,mi.iqtyonhand QOH,mi.new_costprice FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE (mi.vitemname LIKE  '%" .($search_item). "%' OR mi.vbarcode LIKE  '%" .($search_item). "%' OR mi.vsize LIKE  '%" .($search_item). "%' OR mia.valiassku LIKE  '%" .($search_item). "%' OR mi.dcostprice LIKE  '%" .($search_item). "%' OR mi.dunitprice LIKE  '%" .($search_item). "%') AND mi.iitemid NOT IN($pre_items_id) AND mi.estatus='Active'";
    //     }else{
            
    //         $query = "SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,mi.iqtyonhand QOH,mi.new_costprice FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE (mi.vitemname LIKE  '%" .($search_item). "%' OR mi.vbarcode LIKE  '%" .($search_item). "%' OR mi.vsize LIKE  '%" .($search_item).  "%' OR mia.valiassku LIKE  '%" .($search_item). "%' OR mi.dcostprice LIKE  '%" .($search_item). "%' OR mi.dunitprice LIKE  '%" .($search_item). "%') AND mi.estatus='Active'";
    //     }
        
    //     // echo $query; die;
    //     $run_query = DB::connection('mysql_dynamic')->select($query);
        
    //     return $run_query;
    // }
    public function getSearchItemHistoryAll($search_item,$ivendorid,$pre_items_id)
    {   
       //parent-child Search changed 05-10(oct)-2020
        if(count($pre_items_id) > 0){
            $sort='';
            $pre_items_id = implode(',', $pre_items_id);
            //$query = "SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,mi.iqtyonhand QOH,mi.new_costprice FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE (mi.vitemname LIKE  '%" .($search_item). "%' OR mi.vbarcode LIKE  '%" .($search_item). "%' OR mi.vsize LIKE  '%" .($search_item). "%' OR mia.valiassku LIKE  '%" .($search_item). "%' OR mi.dcostprice LIKE  '%" .($search_item). "%' OR mi.dunitprice LIKE  '%" .($search_item). "%') AND mi.iitemid NOT IN($pre_items_id) AND mi.estatus='Active'";
            
                 $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search_item' ");
                 if(isset($parentid) && !empty($parentid)){
                     $parentid_data=$parentid[0]->parentid;
                     $child_data=$parentid[0]->iitemid;
                     $isparentchild=$parentid[0]->isparentchild;
                      if($parentid_data>0 && $isparentchild == 1)
                         {
                         $sort = " ORDER BY mi.parentid DESC";
                         }
                         else{
                          $sort = " ORDER BY mi.isparentchild DESC";   
                         }
                     
                     $query = "SELECT mi.iitemid,mi.vitemcode,
                     case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                     when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                     mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,
                     mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,
                     case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                     (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                     
                     mi.new_costprice FROM mst_item as mi  
                     LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                     LEFT JOIN mst_item p ON mi.parentid = p.iitemid 
                     WHERE mi.vitemname = '$search_item'  OR mi.iitemid=$parentid_data OR mi.parentid=$child_data
                     AND mi.iitemid NOT IN($pre_items_id) 
                     AND mi.estatus='Active' $sort ";
                 }
                 else{
                  $query = "SELECT mi.iitemid,mi.vitemcode,
                  case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                  when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                  mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,
                  mi.dunitprice,mi.nunitcost,mi.npack,
                  case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                  (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                  mi.new_costprice FROM mst_item as mi  
                  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                  LEFT JOIN mst_item p ON mi.parentid = p.iitemid 
                  WHERE (mi.vitemname LIKE  '%" .($search_item). "%' OR mi.vbarcode LIKE  '%" .($search_item). "%' OR mi.vsize LIKE  '%" .($search_item). "%' OR mia.valiassku LIKE  '%" .($search_item). "%' OR mi.dcostprice LIKE  '%" .($search_item). "%' OR mi.dunitprice LIKE  '%" .($search_item). "%') AND mi.iitemid NOT IN($pre_items_id) AND mi.estatus='Active'";
                }
                     
        }else{
            
            // $query = "SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,mi.iqtyonhand QOH,
            // mi.new_costprice FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE 
            // (mi.vitemname LIKE  '%" .($search_item). "%' OR mi.vbarcode LIKE  '%" .($search_item). "%' OR mi.vsize LIKE  '%" .($search_item).  "%' 
            // OR mia.valiassku LIKE  '%" .($search_item). "%' OR mi.dcostprice LIKE  '%" .($search_item). "%' OR mi.dunitprice LIKE  '%" .($search_item). "%') 
            // AND mi.estatus='Active'";
            
                 $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search_item' ");
                 if(isset($parentid) && !empty($parentid)){
                     $parentid_data=$parentid[0]->parentid;
                     $child_data=$parentid[0]->iitemid;
                     $isparentchild=$parentid[0]->isparentchild;
                     
                      if($parentid_data>0 && $isparentchild == 1)
                         {
                         $sort = " ORDER BY mi.parentid DESC";
                         }
                         else{
                          $sort = " ORDER BY mi.isparentchild DESC";   
                         }
                     $query = "SELECT DISTINCT mi.iitemid,mi.vitemcode,
                     case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                     when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                     mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,
                     mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,
                     case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                     (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                     mi.new_costprice FROM mst_item as mi  
                     LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                     LEFT JOIN mst_item p ON mi.parentid = p.iitemid WHERE 
                     mi.iitemid=$parentid_data OR mi.parentid=$child_data
                     OR mi.vitemname = '$search_item'
                     AND mi.estatus='Active' $sort";
                 }
                 else{
                    $query = "SELECT mi.iitemid,mi.vitemcode,
                    case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                     when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                    mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,
                     case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                     (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                    mi.new_costprice FROM mst_item as mi  
                    LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
                    LEFT JOIN mst_item p ON mi.parentid = p.iitemid WHERE 
                    (mi.vitemname LIKE  '%" .($search_item). "%' OR mi.vbarcode LIKE  '%" .($search_item). "%' OR mi.vsize LIKE  '%" .($search_item).  "%' 
                    OR mia.valiassku LIKE  '%" .($search_item). "%' OR mi.dcostprice LIKE  '%" .($search_item). "%' OR mi.dunitprice LIKE  '%" .($search_item). "%') 
                    AND mi.estatus='Active'";
                 }
        }
        
        // echo $query; die;
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        return $run_query;
    }
    
    
    public function getSearchItemData($iitemid,$radio_search_by=null,$vitemcode,$start_date = null,$end_date = null) 
    {
        
        if($radio_search_by == 'pre_month'){
            $end_date = date('Y-m-d');
            $start_date = date("Y-m-d", strtotime("-1 month"));
        }else if($radio_search_by == 'pre_quarter'){
            $end_date = date('Y-m-d');
            $start_date = date("Y-m-d", strtotime("-6 month"));
        }else if($radio_search_by == 'pre_year'){
            $end_date = date('Y-m-d');
            $start_date = date("Y-m-d", strtotime("-1 year"));
        }else if($radio_search_by == 'pre_ytd'){
            $end_date = date('Y-m-d');
            $start_date = date("Y-m-d", strtotime("-1 year"));
        }else if(!empty($start_date) && !empty($end_date)){
            $start_date = \DateTime::createFromFormat('m-d-Y', $start_date);
            $start_date = $start_date->format('Y-m-d');

            $end_date = \DateTime::createFromFormat('m-d-Y', $end_date);
            $end_date = $end_date->format('Y-m-d');
        }else{
            $end_date = date('Y-m-d');
            $start_date = date("Y-m-d", strtotime("-1 week"));
        }
        
        $return = array();
        
        if($radio_search_by == 'pre_ytd'){
            $query = DB::connection('mysql_dynamic')->select("SELECT ifnull(SUM(tsd.ndebitqty),0) as items_sold,ifnull(SUM(tsd.nextunitprice),0) as total_selling_price FROM trn_salesdetail tsd LEFT JOIN trn_sales ts ON (tsd.isalesid = ts.isalesid) WHERE tsd.vitemcode='". $vitemcode ."'");
            $query = isset($query[0])?(array)$query[0]:[];
            $return['item_detail'] = $query;
            
            $query1 = DB::connection('mysql_dynamic')->select("SELECT tp.vvendorname as vvendorname, tpd.nunitcost as nunitcost, ifnull(SUM(tpd.nordextprice),0) as total_cost_price, date_format(tp.dcreatedate,'%m-%d-%Y') as purchase_date, ifnull(SUM(tpd.itotalunit),0) as total_quantity, DATE_FORMAT(tp.dcreatedate,'%M, %Y') as month_year FROM trn_purchaseorderdetail tpd LEFT JOIN trn_purchaseorder tp ON (tpd.ipoid = tp.ipoid) WHERE tpd.vitemid='". $iitemid ."' GROUP BY tp.vvendorname, date_format(tp.dcreatedate,'%m-%d-%Y')");
            
            $temp_datas = $query1;
            $temp_datas = array_map(function ($value) {
                return (array)$value;
            }, $temp_datas);
            
            if(count($temp_datas) > 0){
                $temp = array();
                foreach ($temp_datas as $key => $temp_data) {
                    if(array_key_exists($temp_data['month_year'],$temp)){
                        $temp[$temp_data['month_year']][] = $temp_data;
                    }else{
                        $temp[$temp_data['month_year']][] = $temp_data;
                    }
                }
                
                $temp_datas = $temp;
            }
            
            $return['purchase_items'] = $temp_datas;
            
        }else{
            $query = DB::connection('mysql_dynamic')->select("SELECT ifnull(SUM(tsd.ndebitqty),0) as items_sold,ifnull(SUM(tsd.nextunitprice),0) as total_selling_price FROM trn_salesdetail tsd LEFT JOIN trn_sales ts ON (tsd.isalesid = ts.isalesid) WHERE tsd.vitemcode='". $vitemcode ."' AND (date_format(ts.dtrandate,'%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."') ");
            $query = isset($query[0])?(array)$query[0]:[];
            $return['item_detail'] = $query;
            
            $items_query = "SELECT tp.vvendorname as vvendorname, tpd.nunitcost as nunitcost, ifnull(SUM(tpd.nordextprice),0) as total_cost_price,date_format(tp.dcreatedate,'%m-%d-%Y') as purchase_date, ifnull(SUM(tpd.itotalunit),0) as total_quantity  FROM trn_purchaseorderdetail tpd LEFT JOIN trn_purchaseorder tp ON (tpd.ipoid = tp.ipoid) WHERE tpd.vitemid='". $iitemid ."' AND (date_format(tp.dcreatedate,'%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY tp.vvendorname, date_format(tp.dcreatedate,'%m-%d-%Y')";
            // dd($items_query);
            $query1 = DB::connection('mysql_dynamic')->select($items_query);

            $return['purchase_items'] = $query1;
        }

        return $return;

    }
    
    // public function getSearchVendorItemCode($search_item,$ivendorid,$pre_items_id)
    // {
    //     if(count($pre_items_id) > 0){
    //         $pre_items_id = implode(',', $pre_items_id);
    //         $query = DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH, mitv.vvendoritemcode, mitv.ivendorid, mi.new_costprice FROM mst_itemvendor as mitv, mst_item as mi WHERE mitv.iitemid=mi.iitemid AND mitv.vvendoritemcode LIKE  '%" .($search_item). "%' AND mitv.iitemid NOT IN($pre_items_id) AND mitv.ivendorid='". $ivendorid ."' AND mi.estatus='Active'");
    //     }else{
    //         $query = DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH, mitv.vvendoritemcode,mitv.ivendorid, mi.new_costprice FROM mst_itemvendor as mitv , mst_item as mi WHERE mitv.iitemid=mi.iitemid AND mitv.vvendoritemcode LIKE  '%" .($search_item). "%' AND mitv.ivendorid='". $ivendorid ."' AND mi.estatus='Active'");
    //     }
        
    //     return $query;
    // }
    public function getSearchVendorItemCode($search_item,$ivendorid,$pre_items_id)
    {
        
        if(count($pre_items_id) > 0){
            $pre_items_id = implode(',', $pre_items_id);
            $sql_query = "SELECT mi.iitemid, mi.vitemcode ,mi.vitemname, mi.vbarcode , mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH, mitv.vvendoritemcode, mitv.ivendorid, mi.new_costprice FROM mst_itemvendor as mitv, mst_item as mi WHERE mitv.iitemid=mi.iitemid AND mitv.vvendoritemcode LIKE  '%" .($search_item). "%' AND mitv.iitemid NOT IN($pre_items_id) AND mitv.ivendorid='". $ivendorid ."' AND mi.estatus='Active'";
            // $query = DB::connection('mysql_dynamic')->select($sql_query);
        }else{
            // $query = DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as
            // QOH, mitv.vvendoritemcode,mitv.ivendorid,
            // mi.new_costprice FROM mst_itemvendor as mitv ,
            // mst_item as mi WHERE mitv.iitemid=mi.iitemid AND mitv.vvendoritemcode 
            // LIKE  '%" .($search_item). "%' AND mitv.ivendorid='". $ivendorid ."' AND mi.estatus='Active'");
            
            $sql_query = "SELECT mi.iitemid, mi.vitemcode, mi.vitemname, mi.vbarcode , mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as
            QOH, mitv.vvendoritemcode,mitv.ivendorid,
            mi.new_costprice FROM mst_itemvendor as mitv ,
            mst_item as mi WHERE mitv.iitemid=mi.iitemid AND mitv.vvendoritemcode='". $search_item ."' AND mitv.ivendorid='". $ivendorid ."' AND mi.estatus='Active'";
            
        }
        
        $query = DB::connection('mysql_dynamic')->select($sql_query);
        
        return $query;
    }
    public function getSearchItem($iitemid,$ivendorid) 
    {
        // $query = DB::connection('mysql_dynamic')->select("SELECT mi.new_costprice,mi.last_costprice,mi.iitemid, mi.vitemcode, mi.vitemname, mi.vunitcode, mi.vbarcode, mi.dcostprice, mi.dunitprice, mi.vsize, mi.npack, mi.iqtyonhand, mi.ireorderpoint, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand else 0 end as case_qty, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint else cast(((mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then  (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) else 0 end as total_case_qty, mi.vsuppliercode as ivendorid, mu.vunitname FROM mst_item mi LEFT JOIN mst_unit mu ON (mu.vunitcode = mi.vunitcode) WHERE mi.estatus='Active' AND mi.iitemid='". (int)$iitemid ."'");
        
        $mysql_query = "SELECT ms.vcompanyname vendor_name, mi.new_costprice,mi.iitemid, mi.vitemcode, mi.vitemname, mi.dunitprice, mi.vunitcode, mi.vbarcode, mi.dcostprice, mi.vsize, mi.npack, mi.ireorderpoint, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand else 0 end as case_qty, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint else cast(((mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then  (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) else 0 end as total_case_qty, mi.vsuppliercode as ivendorid, mu.vunitname FROM mst_item mi LEFT JOIN mst_unit mu ON (mu.vunitcode = mi.vunitcode) LEFT JOIN mst_supplier ms ON (mi.vsuppliercode = ms.vsuppliercode) WHERE mi.estatus='Active' AND mi.iitemid='". (int)$iitemid ."'";
        
        $query = DB::connection('mysql_dynamic')->select($mysql_query);
        $query = isset($query[0])?(array)$query[0]:[];
        $item_arr = $query;
        
        if(count($item_arr) > 0){
            $item_ve = DB::connection('mysql_dynamic')->select("SELECT vvendoritemcode FROM mst_itemvendor WHERE iitemid='". (int)$iitemid ."' AND ivendorid='". (int)$ivendorid ."'");
            $item_ve = isset($item_ve[0])?(array)$item_ve[0]:[];
            if(count($item_ve) > 0){
                $item_arr['vvendoritemcode'] = $item_ve['vvendoritemcode'];
            }else{
                $item_arr['vvendoritemcode'] = '';
            }
        }
        
        return $item_arr;
    }
    
    public function checkExistInvoice($invoice)
    {
        $return = array();
        $query = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_purchaseorder WHERE vinvoiceno='". $invoice ."'");
        
        if(count($query) > 0){
            $return['error'] = 'Invoice Already Exist';
        }else{
            $return['success'] = 'Invoice Not Exist';
        }
        return $return;
    }
    
    public function addPurchaseOrder($data = array(), $close = null, $ordertype) 
    {
        $success =array();
        $error =array();
        
        date_default_timezone_set('US/Eastern');
        
        $currenttime = date('h:i:s');
        
        if(isset($data) && count($data) > 0){
            if(!empty($close)){
                $close = $close;
            }else{
                $close = $data['estatus'];
            }
            
            $dcreatedate = \DateTime::createFromFormat('m-d-Y', $data['dcreatedate']);
            $dcreatedate = $dcreatedate->format('Y-m-d').' '.$currenttime;
            
            $dreceiveddate = \DateTime::createFromFormat('m-d-Y', $data['dreceiveddate']);
            $dreceiveddate = $dreceiveddate->format('Y-m-d').' '.$currenttime;
                
               try {
                    $query = new self;  
                    $query->vinvoiceno = $data['vinvoiceno'];
                    $query->vrefnumber = $data['vinvoiceno'];
                    $query->dcreatedate = $dcreatedate; 
                    $query->vponumber = $data['vponumber'] ?? '';
                    $query->vordertitle = $data['vordertitle'] ?? '';
                    $query->vorderby = $data['vorderby'] ?? '';
                    $query->vnotes = $data['vnotes'] ?? '';
                    $query->vshipvia = $data['vshipvia'] ?? '';
                    $query->dreceiveddate = $dreceiveddate;
                    $query->estatus = $close; 
                    $query->vconfirmby = $data['vconfirmby']; 
                    $query->vvendorid = $data['vvendorid']; 
                    $query->vvendorname = $data['vvendorname']; 
                    $query->vvendoraddress1 = $data['vvendoraddress1'] ?? ''; 
                    $query->vvendoraddress2 = $data['vvendoraddress2'] ?? ''; 
                    $query->vvendorstate = $data['vvendorstate'] ?? ''; 
                    $query->vvendorzip = $data['vvendorzip'] ?? ''; 
                    $query->vvendorphone = $data['vvendorphone'] ?? '';
                    $query->vshpid = $data['vshpid'] ?? '';
                    $query->vshpname = $data['vshpname'] ?? '';
                    $query->vshpaddress1 = $data['vshpaddress1'] ?? '';
                    $query->vshpaddress2 = $data['vshpaddress2'] ?? '';
                    $query->vshpstate = $data['vshpstate'] ?? '';
                    $query->vshpzip = $data['vshpzip'] ?? '';
                    $query->vshpphone = $data['vshpphone'] ?? '';
                    $query->nsubtotal = $data['nsubtotal'];
                    $query->ntaxtotal = $data['ntaxtotal'];
                    $query->nfreightcharge = $data['nfreightcharge'];
                    $query->ndeposittotal = $data['ndeposittotal'];
                    $query->nfuelcharge = $data['nfuelcharge'];
                    $query->ndeliverycharge = $data['ndeliverycharge'];
                    $query->nreturntotal = $data['nreturntotal'];
                    $query->ndiscountamt = $data['ndiscountamt'];
                    $query->nripsamt = $data['nripsamt'];
                    $query->nnettotal = $data['nnettotal']; 
                    $query->vordertype = $ordertype;
                    $query->SID = (int)(session()->get('sid'));
                    
                    $query->save();
                    
                    $ipoid = $query->ipoid;
                    
                    // $ipoid = '198765456';
                    $trn_purchaseorder_id = $ipoid;
                    
                    if(isset($data['items']) && count($data['items']) > 0){
                        
                        $data['items'] = array_reverse($data['items']);
						
						$riptotalamount =0;
						
                        foreach ($data['items'] as $key => $item) {
                            
                            $isItemExist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                            $isItemExist = isset($isItemExist[0])?(array)$isItemExist[0]:[];
                            
                            $unit_price = (isset($item['dunitprice']) && $item['dunitprice'] != 0)?($item['dunitprice']):($item['nordunitprice']);
                            
                            $insert_pod_query = "INSERT INTO trn_purchaseorderdetail SET  ipoid = '" . (int)$ipoid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . $unit_price . "', po_last_costprice = '" . ($isItemExist['last_costprice']) . "', po_new_costprice = '" . ($isItemExist['new_costprice']) . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode'] ?? '') . "', vsize = '" . ($item['vsize']) . "', po_order_by = '" . ($item['po_order_by']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npackqty']) . "', itotalunit = '" . ($item['itotalunit']) . "', po_total_suggested_cost = '" . ($item['po_total_suggested_cost']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost'] ?? 0) . "',SID = '" . (int)(session()->get('sid'))."',nripamount= '" . ($item['nripamount'] ?? 0) . "'";
                            
                            DB::connection('mysql_dynamic')->insert($insert_pod_query);
							$amt  = $item['nripamount'] ?? 0;
							$riptotalamount=$riptotalamount+$amt;							
							
                        }
						
						if($riptotalamount > 0)
						{
							$sql= "INSERT INTO trn_rip_header SET ponumber = '" . ($data['vinvoiceno']) . "', vendorid = '" . ($data['vvendorid']) . "', riptotal = '" . ($riptotalamount) . "', receivedtotalamt = '0.00', pendingtotalamt = '" . ($riptotalamount) . "',SID = '" . (int)(session()->get('sid'))."'";
							DB::connection('mysql_dynamic')->insert($sql);
						}
                    }
                    
                }
                
                catch (QueryException $e) {
                    // other mysql exception (not duplicate key entry)
                    
                    $error['error'] = $e->getMessage().'2'; 
                    return $error; 
                }
                catch (\Exception $e) {
                    // not a MySQL exception
                   
                    $error['error'] = $e->getMessage().'3'; 
                    return $error; 
                }
        }

        $success['success'] = 'Successfully Added Purchase Order';
        $success['ipoid'] = $ipoid;
        return $success;
    }
    
    public function deletePurchaseOrder($data) 
    {
        if(isset($data) && count($data) > 0){
            
            foreach ($data as $key => $value) {
                $trn_purchaseorder = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_purchaseorder WHERE ipoid = '" . ($value) . "'");
                
                if(count($trn_purchaseorder) > 0){
                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_purchaseorder',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)(session()->get('sid'))."'");
                    
                    $trn_purchaseorderdetail = DB::connection('mysql_dynamic')->select("SELECT ipodetid FROM trn_purchaseorderdetail WHERE ipoid = '" . ($value) . "'");
                    $trn_purchaseorderdetail = array_map(function ($value) {
                        return (array)$value;
                    }, $trn_purchaseorderdetail);
                    
                    if(count($trn_purchaseorderdetail)){
                        foreach ($trn_purchaseorderdetail as $k => $v) {
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_purchaseorderdetail',`Action` = 'delete',`TableId` = '" . (int)$v['ipodetid'] . "',SID = '" . (int)(session()->get('sid'))."'");
                            
                            DB::connection('mysql_dynamic')->select("DELETE FROM trn_purchaseorderdetail WHERE ipodetid='" . (int)$v['ipodetid'] . "'");
                        }
                    }
                    
                    DB::connection('mysql_dynamic')->select("DELETE FROM trn_purchaseorder WHERE ipoid='" . (int)$value . "'");

                }
                
            }  
            
        }

        $return['success'] = 'PO Deleted Successfully';

        return $return;

    }
     public function getYearlyBreakup($data)
    {
         
        // $query = "select mi.iitemid iitemid,trim(mi.vitemname) itemname, mi.vsize, ";
        $query = "select mi.iitemid iitemid, case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
         when 2 then  Concat(mi.vitemname,' [Parent]') end   as itemname, mi.vsize, ";
        
        $header[] = 'Sale';
        $header[] = 'Pur';
        $header[] = 'Adj';
        $header[] = 'QoH';
        
        $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
        
        // $query .= 'mi.iqtyonhand as qoh ';
        $query .= " case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
        (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as qoh  ";
        
        
        $from = $data['year'].'-01-01';
        if($data['year'] == date('Y', strtotime('now'))){
           //current year
           $to = date('Y-m-d', strtotime('now'));
        } else {
           //previous year
           $to = $data['year'].'-12-31';
        }
        
        
        $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
        
        $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
                                trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
                        WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                GROUP by vbarcode
		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
			                 
		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
			                    FROM trn_physicalinventory tpi 
			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
			                    WHERE tpi.estatus = 'close' and
                                date(tpi.dcreatedate) between '{$from}' and '{$to}'
			                    GROUP BY tpid.vbarcode
			                 ) pa on tsd.vitemcode=pa.vbarcode ";
            
            
        $join_query = false;
                    
        if(isset($data['vendors'])){
            // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
            $join_query = true;
            
            $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
            
            $where_query_vendor = " mi.vsuppliercode ='".$data['vendors']."' and ";

        } else {
            $join_query_vendor = $where_query_vendor = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['departments'])){
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            }
            // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
            $where_query_department = " mi.vdepcode='".$data['departments']."' and ";
            
        } else {
            $join_query_department = $where_query_department = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
                
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
        } else {
            $join_query_category = $where_query_category = '';
        }
        
        
        if(isset($data['sub_categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
                
                $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
        } else {
            $join_query_subcategory = $where_query_subcategory = '';
        }
        
        $sort='';
        
        if(isset($data['item_name'])){
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            $search=$data['item_name'];
                $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search' ");
                
                $sort=''; 
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                 if(isset($parentid) && !empty($parentid)){
                 $parentid_data=$parentid[0]->parentid;
                 $child_data=$parentid[0]->iitemid;
                 $isparentchild=$parentid[0]->isparentchild;
                 $where_query_itemname = "mi.vitemname = '$search'  OR mi.iitemid=$parentid_data OR mi.parentid=$child_data AND";
                     if($parentid_data>0 && $isparentchild == 1)
                     {
                     $sort = " ORDER BY mi.parentid DESC";
                     }
                     else{
                      $sort = " ORDER BY mi.isparentchild DESC";   
                     }
                 
                 }
                else{
                $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                } 
                
                //$where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                
        } else {
            $join_query_itemname = $where_query_itemname = '';
        }
        
        if(isset($data['barcode'])){
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
                
                $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
        } else {
            $join_query_barcode = $where_query_barcode = '';
        }
        
        if(isset($data['size'])){
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
        } else {
            $join_query_size = $where_query_size = '';
        }  
        
        if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
            switch($data['price_select_by']){
                
                case 'greater':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
                    break;
                
                case 'less':
                    $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'equal':
                    $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'between':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
                    break;
            }
                
                
        } else {
            $join_query_barcode = $where_query_price = '';
        }
        
        
        $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
        $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
        $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
            
              
        $where_query .= " date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";
        
        if($join_query === false){
            $join_query = true;
            $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
        }
         $query .= " LEFT JOIN mst_item p ON mi.parentid = p.iitemid ";                
        $query = $query.$where_query.$sort;
        
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        $run_query = array_map(function ($value) {
                return (array)$value;
            }, $run_query);
            
        $return = [];
        
        $return['result'] = $run_query;
        
        $return['header'] = $header;
        
		return $return;
	}
	
	public function getMonthlyBreakup($data)
	{
	    //dd("hi");
        // $query = "select mi.iitemid iitemid, trim(mi.vitemname) itemname, mi.vsize, ";
        $query = "select mi.iitemid iitemid, case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
        when 2 then  Concat(mi.vitemname,' [Parent]') end   as itemname, mi.vsize, ";
        $current_month = date('m');
        
        $list_of_months = $header = [];
        
        
        if(isset($data['include_current_month']) && $data['include_current_month'] === 'yes'){
            $start_from = $data['months']-1;
            $finish_at = -1;
        } else {
            $start_from = $data['months'];
            $finish_at = 0;
        }
        
        $prev_val = date('M', strtotime('now'));
        $present_date = date('d', strtotime('now'));
        
        for($x=$start_from;$x > $finish_at; $x--){
            
            
            $month = $current_month - $x;
            
            $str_to_time = '-'.$x.' month';
            
            if($present_date === '30' && $prev_val === "Jan"){
                $m_y = '02-'.date('y', strtotime($str_to_time));
                $M_y = 'Feb-'.date('y', strtotime($str_to_time));
                $F_Y = 'February '.date('Y', strtotime($str_to_time));
            } else {
                $m_y = date('m-y', strtotime($str_to_time));
                $M_y = date('M-y', strtotime($str_to_time));
                $F_Y = date('F Y', strtotime($str_to_time));
            }
            
            
            $prev_val = date('M', strtotime($str_to_time));
            
            $query .=  "TRIM(sum(case when date_format(dtrandate,'%m-%y')='".$m_y."' then ndebitqty else 0 end))+0 as '".$M_y."', ";
            
            $header[] = $M_y;
            $list_of_months[] = $F_Y;
        }
        
        $header[] = 'Sale';
        $header[] = 'Pur';
        $header[] = 'Adj';
        $header[] = 'QoH';
            
        $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
                    
        // $query .= 'mi.iqtyonhand as qoh ';
        $query .= " case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
        (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as qoh  ";
                    
        
        $to = date('Y-m-d', strtotime('last day of '.$list_of_months[(count($list_of_months)-1)]));
        
        $from = date('Y-m-d', strtotime('first day of '.$list_of_months[0]));
        
        
        $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
        
        $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
                                trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
                                WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                GROUP by vbarcode
		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
		                      
		/*$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
			                    FROM trn_physicalinventory tpi 
			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
			                    WHERE tpi.vtype = 'Adjustment' and tpi.estatus = 'close' and
                                date(tpi.dcreatedate) between '{$from}' and '{$to}'
			                    GROUP BY tpid.vbarcode
			                 ) pa on tsd.vitemcode=pa.vbarcode ";*/
			                 
		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
			                    FROM trn_physicalinventory tpi 
			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
			                    WHERE tpi.estatus = 'close' and
                                date(tpi.dcreatedate) between '{$from}' and '{$to}'
			                    GROUP BY tpid.vbarcode
			                 ) pa on tsd.vitemcode=pa.vbarcode ";
			                 
		$query .= "";
        $sort='';    
        // return 66;
        $join_query = false;
                    
        if(isset($data['vendors'])){
            // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
            $join_query = true;
            
            $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode LEFT JOIN mst_itemvendor miv ON mi.iitemid = miv.iitemid';
            // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
             dd($where_query_vendor);
            $where_query_vendor = " (mi.vsuppliercode ='".$data['vendors']."' or miv.ivendorid = '".$data['vendors']."') and ";
           
        } else {
            $join_query_vendor = $where_query_vendor = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['departments'])){
            // $string_department = "'" . implode ( "', '", $data['departments'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            }
            // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
            $where_query_department = " mi.vdepcode='".$data['departments']."' and ";

        } else {
            $join_query_department = $where_query_department = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
        } else {
            $join_query_category = $where_query_category = '';
        }
        
        
        if(isset($data['sub_categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
                
                $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
        } else {
            $join_query_subcategory = $where_query_subcategory = '';
        }
        
        if(isset($data['item_name'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
                $search=$data['item_name'];
                $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search' ");
                
                $sort=''; 
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                 if(isset($parentid) && !empty($parentid)){
                 $parentid_data=$parentid[0]->parentid;
                 $child_data=$parentid[0]->iitemid;
                 $isparentchild=$parentid[0]->isparentchild;
                 $where_query_itemname = "mi.vitemname = '$search'  OR mi.iitemid=$parentid_data OR mi.parentid=$child_data AND";
                     if($parentid_data>0 && $isparentchild == 1)
                     {
                     $sort = " ORDER BY mi.parentid DESC";
                     }
                     else{
                      $sort = " ORDER BY mi.isparentchild DESC";   
                     }
                 
                 }
                else{
                $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                } 
               // $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                
        } else {
            $join_query_itemname = $where_query_itemname = '';
        }
        
        if(isset($data['barcode'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
                
                $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
        } else {
            $join_query_barcode = $where_query_barcode = '';
        }
        
        if(isset($data['size'])){

            if($join_query === false){
                $join_query = true;
                $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
        } else {
            $join_query_size = $where_query_size = '';
        }
        
        if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
            switch($data['price_select_by']){
                
                case 'greater':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
                    break;
                
                case 'less':
                    $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'equal':
                    $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'between':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
                    break;
            }
                
                
        } else {
            $join_query_barcode = $where_query_price = '';
        }
        
        $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
        $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
        $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
        
                    
        $where_query .= " date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";

        if($join_query === false){
            $join_query = true;
            $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
        }
         $query .= " LEFT JOIN mst_item p ON mi.parentid = p.iitemid ";                
        $query = $query.$where_query.$sort;
        
        // return $query; die;
        
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        $run_query = array_map(function ($value) {
                return (array)$value;
            }, $run_query);
            
        $return = [];
        
        $return['result'] = $run_query;
        
        $return['header'] = $header;
        
		return $return;
	}
	
	
	public function getWeeklyBreakup($data)
	{
        $query = "select mi.iitemid iitemid, case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
         when 2 then  Concat(mi.vitemname,' [Parent]') end   as itemname, mi.vsize, ";
        
        $current_week = date('W');
        
        $list_of_weeks = $header = [];
        
        if(isset($data['include_current_week']) && $data['include_current_week'] === 'yes'){
            $start_from = $data['weeks']-1;
            $finish_at = -1;
        } else {
            $start_from = $data['weeks'];
            $finish_at = 0;
        }
        
        for($x=$start_from;$x > $finish_at ; $x--){
            
            $week = $current_week - $x;
            
            
            $str_to_time = '-'.$x.' week';
            
            //yearweek('2019-09-04', 3)
            
            $query .= " TRIM(sum(case when yearweek(dtrandate, 3)='".date('YW', strtotime($str_to_time))."' then ndebitqty else 0 end))+0 as 'Week ".date('W-y', strtotime($str_to_time))."', ";
            
            /*if($x != $data['weeks']){
                $query .= ", ";
            } else {
                $query .= " ";
            }*/
            
            // echo $week_query.'<br>';
            
            $header[] = 'Wk '.date('W-y', strtotime($str_to_time));
            
            $list_of_weeks[] = array('week' => date('W', strtotime($str_to_time)), 'year' => date('Y', strtotime($str_to_time)));
            
        }
        
        
        $header[] = 'Sale';
        $header[] = 'Pur';
        $header[] = 'Adj';
        $header[] = 'QoH';
        
        $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
        
        //$query .= 'mi.iqtyonhand as qoh ';
         $query .= " case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
        (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as qoh  ";
                    
        	    
        $to_index = count($list_of_weeks)-1;
        
        $to = date('Y-m-d', strtotime(sprintf('%d-W%02d-7', $list_of_weeks[$to_index]['year'], $list_of_weeks[$to_index]['week'])));
        
        $from = date('Y-m-d', strtotime(sprintf('%d-W%02d-1', $list_of_weeks[0]['year'], $list_of_weeks[0]['week'])));    
        
        $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
       
        
        $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
                        trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
                                WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                GROUP by vbarcode
		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
		                             
		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
			                    FROM trn_physicalinventory tpi 
			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
			                    WHERE tpi.estatus = 'close' and
                                date(tpi.dcreatedate) between '{$from}' and '{$to}'
			                    GROUP BY tpid.vbarcode
			                 ) pa on tsd.vitemcode=pa.vbarcode ";
            
        $join_query = false;
        
        $sort='';
        
        if(isset($data['vendors'])){
            // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
            $join_query = true;
            
            $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode LEFT JOIN mst_itemvendor miv ON mi.iitemid = miv.iitemid';
            // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
            
            $where_query_vendor = " (mi.vsuppliercode ='".$data['vendors']."' or miv.ivendorid = '".$data['vendors']."') and ";

        } else {
            $join_query_vendor = $where_query_vendor = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['departments'])){
            // $string_department = "'" . implode ( "', '", $data['departments'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            }
            // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
            $where_query_department = " mi.vdepcode='".$data['departments']."' and ";

        } else {
            $join_query_department = $where_query_department = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
        } else {
            $join_query_category = $where_query_category = '';
        }
        
        
        if(isset($data['sub_categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
        } else {
            $join_query_subcategory = $where_query_subcategory = '';
        }
        
        if(isset($data['item_name'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
                $search=$data['item_name'];
                $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search' ");
                
                $sort=''; 
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                 if(isset($parentid) && !empty($parentid)){
                 $parentid_data=$parentid[0]->parentid;
                 $child_data=$parentid[0]->iitemid;
                 $isparentchild=$parentid[0]->isparentchild;
                 $where_query_itemname = "mi.vitemname = '$search'  OR mi.iitemid=$parentid_data OR mi.parentid=$child_data AND";
                     if($parentid_data>0 && $isparentchild == 1)
                     {
                     $sort = " ORDER BY mi.parentid DESC";
                     }
                     else{
                      $sort = " ORDER BY mi.isparentchild DESC";   
                     }
                 
                 }
                else{
                $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                } 
        } else {
            $join_query_itemname = $where_query_itemname = '';
        }
        
        if(isset($data['barcode'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
        } else {
            $join_query_barcode = $where_query_barcode = '';
        }
        
        if(isset($data['size'])){

            if($join_query === false){
                $join_query = true;
                $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
        } else {
            $join_query_size = $where_query_size = '';
        }
        
        if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
            switch($data['price_select_by']){
                
                case 'greater':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
                    break;
                
                case 'less':
                    $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'equal':
                    $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'between':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
                    break;
            }
                
                
        } else {
            $join_query_barcode = $where_query_price = '';
        }
        
        
        $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
        $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
        $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
            
                    
        $where_query .= " date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize ";
        
        if($join_query === false){
            $join_query = true;
            $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            
        }
        $query .= " LEFT JOIN mst_item p ON mi.parentid = p.iitemid ";            
        $query = $query.$where_query.$sort;
        
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        $run_query = array_map(function ($value) {
                return (array)$value;
            }, $run_query);
            
        $return = [];
        
        $return['result'] = $run_query;
        
        $return['header'] = $header;
        
		return $return;
                   
	}
	
    public function getCustomBreakup($data)
    {
        // $query = "select mi.iitemid iitemid,trim(mi.vitemname) itemname, mi.vsize, ";
        $query = "select mi.iitemid iitemid, case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
         when 2 then  Concat(mi.vitemname,' [Parent]') end   as itemname, mi.vsize, ";
        
        $header[] = 'Sale';
        $header[] = 'Pur';
        $header[] = 'Adj';
        $header[] = 'QoH';
        
        $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
                    
        // $query .= 'mi.iqtyonhand as qoh ';
        $query .= " case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
        (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as qoh  ";
        
        // return $to_string = 'last day of '.date('Y-m-t', strtotime('-1 month')).' '.$data['year'];
        
        $to = $data['to_date'];
	    
	    $from = $data['from_date'];
	    
	   // return $from.' '.$to;
        
        $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
	    
	    $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
	                            trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
                                WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                GROUP by vbarcode
		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
			                 
// 		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
// 			                    FROM trn_physicalinventory tpi 
// 			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
// 			                    WHERE tpi.estatus = 'close' and
//                                 date(tpi.dcreatedate) between '{$from}' and '{$to}'
// 			                    GROUP BY tpid.vbarcode
// 			                 ) pa on tsd.vitemcode=pa.vbarcode ";
			                 
		//==========Added on 27/01/2020===============
		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
			                    FROM trn_physicalinventory tpi 
			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
			                    WHERE tpi.estatus = 'close' and
                                tpi.dcreatedate between '{$from}' and '{$to}'
			                    GROUP BY tpid.vbarcode
			                 ) pa on tsd.vitemcode=pa.vbarcode ";
		//==============================================
		
		
        $join_query = false;
                    
        if(isset($data['vendors'])){
            // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
            $join_query = true;
            
            $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
            
            $where_query_vendor = " mi.vsuppliercode ='".$data['vendors']."' and ";

        } else {
            $join_query_vendor = $where_query_vendor = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['departments'])){
            // $string_department = "'" . implode ( "', '", $data['departments'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            }
            // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
            $where_query_department = " mi.vdepcode='".$data['departments']."' and ";

        } else {
            $join_query_department = $where_query_department = '';
            //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
        }
        
        if(isset($data['categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
        } else {
            $join_query_category = $where_query_category = '';
        }
        
        
        if(isset($data['sub_categories'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
        } else {
            $join_query_subcategory = $where_query_subcategory = '';
        }
        
        if(isset($data['item_name'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                // $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                $search=$data['item_name'];
                $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '$search' ");
                
                $sort=''; 
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                 if(isset($parentid) && !empty($parentid)){
                 $parentid_data=$parentid[0]->parentid;
                 $child_data=$parentid[0]->iitemid;
                 $isparentchild=$parentid[0]->isparentchild;
                 $where_query_itemname = "mi.vitemname = '$search'  OR mi.iitemid=$parentid_data OR mi.parentid=$child_data AND";
                     if($parentid_data>0 && $isparentchild == 1)
                     {
                     $sort = " ORDER BY mi.parentid DESC";
                     }
                     else{
                      $sort = " ORDER BY mi.isparentchild DESC";   
                     }
                 
                 }
                else{
                $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                } 
                
        } else {
            $join_query_itemname = $where_query_itemname = '';
        }
        
        if(isset($data['barcode'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
                $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
        } else {
            $join_query_barcode = $where_query_barcode = '';
        }
        
        if(isset($data['size'])){

            if($join_query === false){
                $join_query = true;
                $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
                $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
        } else {
            $join_query_size = $where_query_size = '';
        }
        
        if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
            // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
            if($join_query === false){
                $join_query = true;
                $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            }
            
            // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
            
            switch($data['price_select_by']){
                
                case 'greater':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
                    break;
                
                case 'less':
                    $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'equal':
                    $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
                    break;
                    
                case 'between':
                    $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
                    break;
            }
                
                
        } else {
            $join_query_barcode = $where_query_price = '';
        }
        
        
        /*if(isset(data['sub_categories'])){
            $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            $where_query_category = ' mi.vsuppliercode IN('.$string_vendor."' and ";
        } else {
            $join_query_category = $where_query_category = '';
        }*/
        
        
        
        
        $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
        $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
        $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
        
                    
        // $where_query .= "date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";
        
        //=======27/21/2020======
         $where_query .= " dtrandate between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";

        if($join_query === false){
            $join_query = true;
            $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
        }
        $query .= " LEFT JOIN mst_item p ON mi.parentid = p.iitemid ";                
        $query = $query.$where_query.$sort;
        
        // return $query; die;
        // print_r($query); die;
        
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        $run_query = array_map(function ($value) {
                return (array)$value;
            }, $run_query);
            
        $return = [];
        
        $return['result'] = $run_query;
        
        $return['header'] = $header;

		return $return;
	}
//     public function getYearlyBreakup($data)
//     {
         
//         $query = "select mi.iitemid iitemid,trim(mi.vitemname) itemname, mi.vsize, ";
        
//         $header[] = 'Sale';
//         $header[] = 'Pur';
//         $header[] = 'Adj';
//         $header[] = 'QoH';
        
//         $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
        
//         $query .= 'mi.iqtyonhand as qoh ';
        
        
//         $from = $data['year'].'-01-01';
//         if($data['year'] == date('Y', strtotime('now'))){
//           //current year
//           $to = date('Y-m-d', strtotime('now'));
//         } else {
//           //previous year
//           $to = $data['year'].'-12-31';
//         }
        
        
//         $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
        
//         $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
//                                 trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
//                         WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
//                                 GROUP by vbarcode
// 		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
			                 
// 		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
// 			                    FROM trn_physicalinventory tpi 
// 			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
// 			                    WHERE tpi.estatus = 'close' and
//                                 date(tpi.dcreatedate) between '{$from}' and '{$to}'
// 			                    GROUP BY tpid.vbarcode
// 			                 ) pa on tsd.vitemcode=pa.vbarcode ";
            
            
//         $join_query = false;
                    
//         if(isset($data['vendors'])){
//             // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
//             $join_query = true;
            
//             $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//             // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
            
//             $where_query_vendor = " mi.vsuppliercode ='".$data['vendors']."' and ";

//         } else {
//             $join_query_vendor = $where_query_vendor = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['departments'])){
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//             }
//             // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
//             $where_query_department = " mi.vdepcode='".$data['departments']."' and ";
            
//         } else {
//             $join_query_department = $where_query_department = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
                
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_category = $where_query_category = '';
//         }
        
        
//         if(isset($data['sub_categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
                
//                 $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_subcategory = $where_query_subcategory = '';
//         }
        
//         if(isset($data['item_name'])){
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
                
//                 $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                
//         } else {
//             $join_query_itemname = $where_query_itemname = '';
//         }
        
//         if(isset($data['barcode'])){
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
                
//                 $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
//         } else {
//             $join_query_barcode = $where_query_barcode = '';
//         }
        
//         if(isset($data['size'])){
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
//         } else {
//             $join_query_size = $where_query_size = '';
//         }  
        
//         if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//             switch($data['price_select_by']){
                
//                 case 'greater':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
//                     break;
                
//                 case 'less':
//                     $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'equal':
//                     $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'between':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
//                     break;
//             }
                
                
//         } else {
//             $join_query_barcode = $where_query_price = '';
//         }
        
//         $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
//         $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
//         $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
            
              
//         $where_query .= "date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";
        
//         if($join_query === false){
//             $join_query = true;
//             $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//         }
                    
//         $query = $query.$where_query;
        
//         $run_query = DB::connection('mysql_dynamic')->select($query);
        
//         $run_query = array_map(function ($value) {
//                 return (array)$value;
//             }, $run_query);
            
//         $return = [];
        
//         $return['result'] = $run_query;
        
//         $return['header'] = $header;
        
// 		return $return;
// 	}
	
// 	public function getMonthlyBreakup($data)
// 	{
//         $query = "select mi.iitemid iitemid, trim(mi.vitemname) itemname, mi.vsize, ";
        
//         $current_month = date('m');
        
//         $list_of_months = $header = [];
        
        
//         if(isset($data['include_current_month']) && $data['include_current_month'] === 'yes'){
//             $start_from = $data['months']-1;
//             $finish_at = -1;
//         } else {
//             $start_from = $data['months'];
//             $finish_at = 0;
//         }
        
//         $prev_val = date('M', strtotime('now'));
//         $present_date = date('d', strtotime('now'));
        
//         for($x=$start_from;$x > $finish_at; $x--){
            
            
//             $month = $current_month - $x;
            
//             $str_to_time = '-'.$x.' month';
            
//             if($present_date === '30' && $prev_val === "Jan"){
//                 $m_y = '02-'.date('y', strtotime($str_to_time));
//                 $M_y = 'Feb-'.date('y', strtotime($str_to_time));
//                 $F_Y = 'February '.date('Y', strtotime($str_to_time));
//             } else {
//                 $m_y = date('m-y', strtotime($str_to_time));
//                 $M_y = date('M-y', strtotime($str_to_time));
//                 $F_Y = date('F Y', strtotime($str_to_time));
//             }
            
            
//             $prev_val = date('M', strtotime($str_to_time));
            
//             $query .=  "TRIM(sum(case when date_format(dtrandate,'%m-%y')='".$m_y."' then ndebitqty else 0 end))+0 as '".$M_y."', ";
            
//             $header[] = $M_y;
//             $list_of_months[] = $F_Y;
//         }
        
//         $header[] = 'Sale';
//         $header[] = 'Pur';
//         $header[] = 'Adj';
//         $header[] = 'QoH';
            
//         $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
                    
//         $query .= 'mi.iqtyonhand as qoh ';
                    
        
//         $to = date('Y-m-d', strtotime('last day of '.$list_of_months[(count($list_of_months)-1)]));
        
//         $from = date('Y-m-d', strtotime('first day of '.$list_of_months[0]));
        
        
//         $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
        
//         $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
//                                 trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
//                                 WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
//                                 GROUP by vbarcode
// 		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
		                      
// 		/*$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
// 			                    FROM trn_physicalinventory tpi 
// 			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
// 			                    WHERE tpi.vtype = 'Adjustment' and tpi.estatus = 'close' and
//                                 date(tpi.dcreatedate) between '{$from}' and '{$to}'
// 			                    GROUP BY tpid.vbarcode
// 			                 ) pa on tsd.vitemcode=pa.vbarcode ";*/
			                 
// 		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
// 			                    FROM trn_physicalinventory tpi 
// 			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
// 			                    WHERE tpi.estatus = 'close' and
//                                 date(tpi.dcreatedate) between '{$from}' and '{$to}'
// 			                    GROUP BY tpid.vbarcode
// 			                 ) pa on tsd.vitemcode=pa.vbarcode ";
			                 
// 		$query .= "";
                    
//         // return 66;
//         $join_query = false;
                    
//         if(isset($data['vendors'])){
//             // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
//             $join_query = true;
            
//             $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode LEFT JOIN mst_itemvendor miv ON mi.iitemid = miv.iitemid';
//             // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
            
//             $where_query_vendor = " (mi.vsuppliercode ='".$data['vendors']."' or miv.ivendorid = '".$data['vendors']."') and ";

//         } else {
//             $join_query_vendor = $where_query_vendor = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['departments'])){
//             // $string_department = "'" . implode ( "', '", $data['departments'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//             }
//             // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
//             $where_query_department = " mi.vdepcode='".$data['departments']."' and ";

//         } else {
//             $join_query_department = $where_query_department = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_category = $where_query_category = '';
//         }
        
        
//         if(isset($data['sub_categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
                
//                 $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_subcategory = $where_query_subcategory = '';
//         }
        
//         if(isset($data['item_name'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
                
//                 $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                
//         } else {
//             $join_query_itemname = $where_query_itemname = '';
//         }
        
//         if(isset($data['barcode'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
                
//                 $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
//         } else {
//             $join_query_barcode = $where_query_barcode = '';
//         }
        
//         if(isset($data['size'])){

//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
//         } else {
//             $join_query_size = $where_query_size = '';
//         }
        
//         if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//             switch($data['price_select_by']){
                
//                 case 'greater':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
//                     break;
                
//                 case 'less':
//                     $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'equal':
//                     $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'between':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
//                     break;
//             }
                
                
//         } else {
//             $join_query_barcode = $where_query_price = '';
//         }
        
//         $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
//         $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
//         $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
        
                    
//         $where_query .= "date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";

//         if($join_query === false){
//             $join_query = true;
//             $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//         }
                    
//         $query = $query.$where_query;
        
//         // return $query; die;
        
//         $run_query = DB::connection('mysql_dynamic')->select($query);
        
//         $run_query = array_map(function ($value) {
//                 return (array)$value;
//             }, $run_query);
            
//         $return = [];
        
//         $return['result'] = $run_query;
        
//         $return['header'] = $header;
        
// 		return $return;
// 	}
	
	
// 	public function getWeeklyBreakup($data)
// 	{
//         $query = "select mi.iitemid iitemid, trim(mi.vitemname) itemname, mi.vsize, ";
        
//         $current_week = date('W');
        
//         $list_of_weeks = $header = [];
        
//         if(isset($data['include_current_week']) && $data['include_current_week'] === 'yes'){
//             $start_from = $data['weeks']-1;
//             $finish_at = -1;
//         } else {
//             $start_from = $data['weeks'];
//             $finish_at = 0;
//         }
        
//         for($x=$start_from;$x > $finish_at ; $x--){
            
//             $week = $current_week - $x;
            
            
//             $str_to_time = '-'.$x.' week';
            
//             //yearweek('2019-09-04', 3)
            
//             $query .= "TRIM(sum(case when yearweek(dtrandate, 3)='".date('YW', strtotime($str_to_time))."' then ndebitqty else 0 end))+0 as 'Week ".date('W-y', strtotime($str_to_time))."', ";
            
//             /*if($x != $data['weeks']){
//                 $query .= ", ";
//             } else {
//                 $query .= " ";
//             }*/
            
//             // echo $week_query.'<br>';
            
//             $header[] = 'Wk '.date('W-y', strtotime($str_to_time));
            
//             $list_of_weeks[] = array('week' => date('W', strtotime($str_to_time)), 'year' => date('Y', strtotime($str_to_time)));
            
//         }
        
        
//         $header[] = 'Sale';
//         $header[] = 'Pur';
//         $header[] = 'Adj';
//         $header[] = 'QoH';
        
//         $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
        
//         $query .= 'mi.iqtyonhand as qoh ';
                    
        	    
//         $to_index = count($list_of_weeks)-1;
        
//         $to = date('Y-m-d', strtotime(sprintf('%d-W%02d-7', $list_of_weeks[$to_index]['year'], $list_of_weeks[$to_index]['week'])));
        
//         $from = date('Y-m-d', strtotime(sprintf('%d-W%02d-1', $list_of_weeks[0]['year'], $list_of_weeks[0]['week'])));    
        
//         $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
        
//         $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
//                         trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
//                                 WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
//                                 GROUP by vbarcode
// 		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
		                             
// 		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
// 			                    FROM trn_physicalinventory tpi 
// 			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
// 			                    WHERE tpi.estatus = 'close' and
//                                 date(tpi.dcreatedate) between '{$from}' and '{$to}'
// 			                    GROUP BY tpid.vbarcode
// 			                 ) pa on tsd.vitemcode=pa.vbarcode ";
            
//         $join_query = false;
        
//         if(isset($data['vendors'])){
//             // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
//             $join_query = true;
            
//             $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode LEFT JOIN mst_itemvendor miv ON mi.iitemid = miv.iitemid';
//             // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
            
//             $where_query_vendor = " (mi.vsuppliercode ='".$data['vendors']."' or miv.ivendorid = '".$data['vendors']."') and ";

//         } else {
//             $join_query_vendor = $where_query_vendor = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['departments'])){
//             // $string_department = "'" . implode ( "', '", $data['departments'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//             }
//             // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
//             $where_query_department = " mi.vdepcode='".$data['departments']."' and ";

//         } else {
//             $join_query_department = $where_query_department = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_category = $where_query_category = '';
//         }
        
        
//         if(isset($data['sub_categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_subcategory = $where_query_subcategory = '';
//         }
        
//         if(isset($data['item_name'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                
//         } else {
//             $join_query_itemname = $where_query_itemname = '';
//         }
        
//         if(isset($data['barcode'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
//         } else {
//             $join_query_barcode = $where_query_barcode = '';
//         }
        
//         if(isset($data['size'])){

//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
//         } else {
//             $join_query_size = $where_query_size = '';
//         }
        
//         if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//             switch($data['price_select_by']){
                
//                 case 'greater':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
//                     break;
                
//                 case 'less':
//                     $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'equal':
//                     $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'between':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
//                     break;
//             }
                
                
//         } else {
//             $join_query_barcode = $where_query_price = '';
//         }
        
        
//         $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
//         $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
//         $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
            
                    
//         $where_query .= "date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";
        
//         if($join_query === false){
//             $join_query = true;
//             $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//         }
                    
//         $query = $query.$where_query;
        
//         $run_query = DB::connection('mysql_dynamic')->select($query);
        
//         $run_query = array_map(function ($value) {
//                 return (array)$value;
//             }, $run_query);
            
//         $return = [];
        
//         $return['result'] = $run_query;
        
//         $return['header'] = $header;
        
// 		return $return;
                   
// 	}
	
//     public function getCustomBreakup($data)
//     {
//         $query = "select mi.iitemid iitemid,trim(mi.vitemname) itemname, mi.vsize, ";
        
//         $header[] = 'Sale';
//         $header[] = 'Pur';
//         $header[] = 'Adj';
//         $header[] = 'QoH';
        
//         $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
                    
//         $query .= 'mi.iqtyonhand as qoh ';
        
//         // return $to_string = 'last day of '.date('Y-m-t', strtotime('-1 month')).' '.$data['year'];
        
//         $to = $data['to_date'];
	    
// 	    $from = $data['from_date'];
	    
// 	   // return $from.' '.$to;
        
//         $query .= "from trn_sales sl join  trn_salesdetail tsd on sl.isalesid=tsd.isalesid ";
	    
// 	    $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_purchaseorderdetail
// 	                            trn_pod join trn_purchaseorder trn_po on trn_po.ipoid = trn_pod.ipoid 
//                                 WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
//                                 GROUP by vbarcode
// 		                      ) tpod on tsd.vitemcode=tpod.vbarcode ";
			                 
// // 		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
// // 			                    FROM trn_physicalinventory tpi 
// // 			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
// // 			                    WHERE tpi.estatus = 'close' and
// //                                 date(tpi.dcreatedate) between '{$from}' and '{$to}'
// // 			                    GROUP BY tpid.vbarcode
// // 			                 ) pa on tsd.vitemcode=pa.vbarcode ";
			                 
// 		//==========Added on 27/01/2020===============
// 		$query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
// 			                    FROM trn_physicalinventory tpi 
// 			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
// 			                    WHERE tpi.estatus = 'close' and
//                                 tpi.dcreatedate between '{$from}' and '{$to}'
// 			                    GROUP BY tpid.vbarcode
// 			                 ) pa on tsd.vitemcode=pa.vbarcode ";
// 		//==============================================
		
		
//         $join_query = false;
                    
//         if(isset($data['vendors'])){
//             // $string_vendor = "'" . implode ( "', '", $data['vendors'] ) . "'";
//             $join_query = true;
            
//             $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//             // $where_query_vendor = " mi.vsuppliercode IN(".$string_vendor.") and ";
            
//             $where_query_vendor = " mi.vsuppliercode ='".$data['vendors']."' and ";

//         } else {
//             $join_query_vendor = $where_query_vendor = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['departments'])){
//             // $string_department = "'" . implode ( "', '", $data['departments'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//             }
//             // $where_query_department = ' mi.vdepcode IN('.$string_department.") and ";
            
//             $where_query_department = " mi.vdepcode='".$data['departments']."' and ";

//         } else {
//             $join_query_department = $where_query_department = '';
//             //  ' mi.vsuppliercode IN('.$string_vendor.') and ';
//         }
        
//         if(isset($data['categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_category = $where_query_category = '';
//         }
        
        
//         if(isset($data['sub_categories'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
                
//         } else {
//             $join_query_subcategory = $where_query_subcategory = '';
//         }
        
//         if(isset($data['item_name'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                
//         } else {
//             $join_query_itemname = $where_query_itemname = '';
//         }
        
//         if(isset($data['barcode'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
                
//                 $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                
//         } else {
//             $join_query_barcode = $where_query_barcode = '';
//         }
        
//         if(isset($data['size'])){

//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//                 $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                
//         } else {
//             $join_query_size = $where_query_size = '';
//         }
        
//         if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
//             // $string_category = "'" . implode ( "', '", $data['categories'] ) . "'";
            
//             if($join_query === false){
//                 $join_query = true;
//                 $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
//             }
            
//             // $where_query_category = ' mi.vcategorycode IN('.$string_vendor.") and ";
            
//             switch($data['price_select_by']){
                
//                 case 'greater':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and ";
//                     break;
                
//                 case 'less':
//                     $where_query_price = " mi.dunitprice < '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'equal':
//                     $where_query_price = " mi.dunitprice = '".$data['select_by_value_1']."' and ";
//                     break;
                    
//                 case 'between':
//                     $where_query_price = " mi.dunitprice > '".$data['select_by_value_1']."' and mi.dunitprice < '".$data['select_by_value_2']."' and ";
//                     break;
//             }
                
                
//         } else {
//             $join_query_barcode = $where_query_price = '';
//         }
        
        
//         /*if(isset(data['sub_categories'])){
//             $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//             $where_query_category = ' mi.vsuppliercode IN('.$string_vendor."' and ";
//         } else {
//             $join_query_category = $where_query_category = '';
//         }*/
        
        
        
        
//         $where_query = " where mi.estatus = 'Active' and sl.vtrntype='Transaction' and ";
//         $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
//         $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
        
                    
//         // $where_query .= "date(dtrandate) between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";
        
//         //=======27/21/2020======
//          $where_query .= "dtrandate between '{$from}' and '{$to}' group by trim(mi.vitemname), vsize";

//         if($join_query === false){
//             $join_query = true;
//             $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
//         }
                    
//         $query = $query.$where_query;
        
//         // return $query; die;
//         // print_r($query); die;
        
//         $run_query = DB::connection('mysql_dynamic')->select($query);
        
//         $run_query = array_map(function ($value) {
//                 return (array)$value;
//             }, $run_query);
            
//         $return = [];
        
//         $return['result'] = $run_query;
        
//         $return['header'] = $header;

// 		return $return;
// 	}
    
    public function getItemsSearchResult($search) 
    {
        $datas = array();
        
        if(isset($search) && !empty(trim($search))){
            
            $limit = 20;
            
            $start_from = 0;
            
            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname FROM mst_item as mi LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE mi.estatus='Active' AND ( (mi.vitemname LIKE  '%" .($search). "%' OR mi.vbarcode LIKE  '%" .($search). "%') OR (miv.vvendoritemcode LIKE  '%" .($search). "%') OR (mia.valiassku LIKE  '%" .($search). "%')) ORDER BY vitemname ASC LIMIT ". $start_from.", ".$limit;
            
            $query = DB::connection('mysql_dynamic')->select($select_query);
            
        } else {
               
            $limit = 20;
            
            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vitemtype, mi.vdepcode, mi.vcategorycode, mi.nsaleprice, mi.dunitprice, mi.iqtyonhand FROM mst_item as mi LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) ORDER BY vitemname ASC LIMIT ". $datas['start_from'].", ".$limit;
            
            $query = DB::connection('mysql_dynamic')->select($select_query);
            
            
        }
        
        //====converting object data into array=====
        $query = array_map(function ($value) {
            return (array)$value;
        }, $query);
        
        if(count($query) > 0){
            foreach ($query as $key => $value) {
                
                $temp = array();
                $temp['iitemid'] = $value['iitemid'];
                $temp['vitemname'] = $value['vitemname'];
                $temp['vitemtype'] = $value['vitemtype'];
                $temp['vbarcode'] = $value['vbarcode'];
                $temp['vdepcode'] = $value['vdepcode'];
                $temp['vcategorycode'] = $value['vcategorycode'];
                $temp['nsaleprice'] = $value['nsaleprice'];
                $temp['iqtyonhand'] = $value['iqtyonhand'];
                
                $datas[] = $temp;
            }
        }
        
        return $datas;
    }
    
    public function deleteItemPurchase($data = array()) 
    {
        $success =array();
        $error =array();
            
        if(isset($data) && count($data) > 0){
            
            foreach($data as $value){
                try {
                        
                    $exist_ipodetid = DB::connection('mysql_dynamic')->select("SELECT `ipodetid` FROM  trn_purchaseorderdetail WHERE ipodetid='" . (int)$value . "' ");
                    $exist_ipodetid = isset($exist_ipodetid[0])?(array)$exist_ipodetid[0]:[];
                    
                    if(count($exist_ipodetid) > 0){
                        DB::connection('mysql_dynamic')->statement("INSERT INTO mst_delete_table SET  TableName = 'trn_purchaseorderdetail',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)(session()->get('sid'))."'");
                        DB::connection('mysql_dynamic')->statement("DELETE FROM trn_purchaseorderdetail WHERE ipodetid='" . (int)$value . "'");
                    }
                        
                }
                catch (QueryException $e) {
                    // other mysql exception (not duplicate key entry)
                    
                    $error['error'] = $e->getMessage().'2'; 
                    return $error; 
                }
            }  
        }
        $success['success'] = 'Successfully Deleted Purchase Order Item';
        return $success;
    }
    
    public function editPurchaseOrder($data = array(), $close = null, $ordertype) 
    {
        
        $success =array();
        $error =array();
        
        date_default_timezone_set('US/Eastern');
        
        $currenttime = date('h:i:s');
        
        if(isset($data) && count($data) > 0){
            if(!empty($close)){
                $close = $close;
            }else{
                $close = $data['estatus'];
            }
            
            $dcreatedate = \DateTime::createFromFormat('m-d-Y', $data['dcreatedate']);
            $dcreatedate = $dcreatedate->format('Y-m-d').' '.$currenttime;
            
            $dreceiveddate = \DateTime::createFromFormat('m-d-Y', $data['dreceiveddate']);
            $dreceiveddate = $dreceiveddate->format('Y-m-d').' '.$currenttime;
                
               try {
                    
                    $update_po_query = "UPDATE trn_purchaseorder SET  vinvoiceno = '" . ($data['vinvoiceno']) . "',vrefnumber = '" . ($data['vinvoiceno']) . "',dcreatedate = '" . $dcreatedate . "', vponumber = '" . ($data['vponumber']) . "',`dreceiveddate` = '" . $dreceiveddate . "', estatus = '" . $close . "', vconfirmby = '" . ($data['vconfirmby']) . "', vnotes = '" . ($data['vnotes'] ?? '') . "', vshipvia = '" . ($data['vshipvia'] ?? '') . "', vvendorid = '" . ($data['vvendorid']) . "', vvendorname = '" . ($data['vvendorname']) . "', vvendoraddress1 = '" . ($data['vvendoraddress1'] ?? '') . "', vvendoraddress2 = '" . ($data['vvendoraddress2'] ?? '') . "', vvendorstate = '" . ($data['vvendorstate'] ?? '') . "', vvendorzip = '" . ($data['vvendorzip'] ?? '') . "', vvendorphone = '" . ($data['vvendorphone'] ?? '') . "',vshpid = '" . ($data['vshpid'] ?? '') . "',vshpname = '" . ($data['vshpname'] ?? '') . "',vshpaddress1 = '" . ($data['vshpaddress1'] ?? '') . "',vshpaddress2 = '" . ($data['vshpaddress2'] ?? '') . "',vshpstate = '" . ($data['vshpstate'] ?? '') . "',vshpzip = '" . ($data['vshpzip'] ?? '') . "',vshpphone = '" . ($data['vshpphone'] ?? '') . "',nsubtotal = '" . ($data['nsubtotal']) . "',ntaxtotal = '" . ($data['ntaxtotal']) . "',nfreightcharge = '" . ($data['nfreightcharge']) . "',ndeposittotal = '" . ($data['ndeposittotal']) . "',nfuelcharge = '" . ($data['nfuelcharge']) . "',ndeliverycharge = '" . ($data['ndeliverycharge']) . "',nreturntotal = '" . ($data['nreturntotal']) . "',ndiscountamt = '" . ($data['ndiscountamt']) . "',nripsamt = '" . ($data['nripsamt']) . "',nnettotal = '" . ($data['nnettotal']) . "', vordertype = '" . $ordertype . "' WHERE ipoid='". (int)($data['ipoid']) ."'";
                    
                    DB::connection('mysql_dynamic')->update($update_po_query);
                        
                    $ipoid = (int)($data['ipoid']);
                    $trn_purchaseorder_id = $ipoid;
                    
                    if(isset($data['items']) && count($data['items']) > 0){
                        
                        $data['items'] = array_reverse($data['items']);
						
						$riptotalamount =0;
                        
						foreach ($data['items'] as $key => $item) {
                            
                            $ipodetid_ids = DB::connection('mysql_dynamic')->select("SELECT `ipodetid` FROM  trn_purchaseorderdetail WHERE ipodetid='" . (int)($item['ipodetid']) . "' ");							
                            
							if(count($ipodetid_ids) > 0){
							    
							    $update_pod_query = "UPDATE trn_purchaseorderdetail SET  ipoid = '" . (int)$ipoid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . ($item['nordunitprice']) . "', po_last_costprice = '" . ($item['po_last_costprice'] ?? '') . "', po_new_costprice = '" . ($item['new_costprice'] ?? '') . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode'] ?? '') . "', vsize = '" . ($item['vsize']) . "', po_order_by = '" . ($item['po_order_by']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npack']) . "', itotalunit = '" . ($item['itotalunit']) . "', po_total_suggested_cost = '" . ($item['po_total_suggested_cost']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost'] ?? 0) . "',nripamount= '" . ($item['nripamount'] ?? 0) . "' WHERE ipodetid='" . (int)($item['ipodetid']) . "' ";
							    
                                $a = DB::connection('mysql_dynamic')->update($update_pod_query);
                            }else{
                                
                                // echo $insert_pod_query = "INSERT INTO trn_purchaseorderdetail SET  ipoid = '" . (int)$ipoid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . ($item['nordunitprice']) . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', vsize = '" . ($item['vsize']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npackqty']) . "', itotalunit = '" . ($item['itotalunit']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost']) . "',SID = '" . (int)(session()->get('sid'))."',nripamount= '" . ($item['nripamount']) . "'";
                                
                                $isItemExist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                                $isItemExist = isset($isItemExist[0])?(array)$isItemExist[0]:[];
                                
                                $insert_pod_query = "INSERT INTO trn_purchaseorderdetail SET  ipoid = '" . (int)$ipoid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . ($item['nordunitprice']) . "', po_last_costprice = '" . ($isItemExist['last_costprice']) . "', po_new_costprice = '" . ($item['new_costprice']) . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode'] ?? '') . "', vsize = '" . ($item['vsize']) . "', po_order_by = '" . ($item['po_order_by']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npackqty']) . "', itotalunit = '" . ($item['itotalunit']) . "', po_total_suggested_cost = '" . ($item['po_total_suggested_cost']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost'] ?? 0) . "',SID = '" . (int)(session()->get('sid'))."',nripamount= '" . ($item['nripamount'] ?? 0) . "'";                                
                                
                                
                                // $insert_pod_query = 
                                
                                DB::connection('mysql_dynamic')->statement($insert_pod_query);
                            }
							$ripamt = $item['nripamount'] ?? 0;
							$riptotalamount=$riptotalamount+$ripamt;
                            
                            // Price Change
                            
                            $isItemExist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                            $isItemExist = isset($isItemExist[0])?(array)$isItemExist[0]:[];
                            // Price Change
                            
                        }
						
						$rip_row_count = DB::connection('mysql_dynamic')->select("SELECT id FROM  trn_rip_header WHERE ponumber='".($data['vinvoiceno'])."'");	
						$rip_row_count = isset($rip_row_count[0])?(array)$rip_row_count[0]:[];
						
						if(count($rip_row_count) > 0)
						{
							$sql_rip= "UPDATE trn_rip_header SET ponumber = '" . ($data['vinvoiceno']) . "', vendorid = '" . ($data['vvendorid']) . "', riptotal = '" . ($riptotalamount) . "', receivedtotalamt = '0.00', pendingtotalamt = '" . ((($riptotalamount)) - (receivedtotalamt)) . "',SID = '" . (int)(session()->get('sid'))."' WHERE id='".$rip_row_count['id']."'";
							DB::connection('mysql_dynamic')->update($sql_rip);
							
						}else{
							if($riptotalamount > 0)
							{
								$sql_rip= "INSERT INTO trn_rip_header SET ponumber = '" . ($data['vinvoiceno']) . "', vendorid = '" . ($data['vvendorid']) . "', riptotal = '" . ($riptotalamount) . "', receivedtotalamt = '0.00', pendingtotalamt = '" . ($riptotalamount) . "',SID = '" . (int)(session()->get('sid'))."'";
								DB::connection('mysql_dynamic')->insert($sql_rip);
							}
						}							
                    }
                }
                
                catch (QueryException $e) {
                    
                    $error['error'] = $e->getMessage().'3'; 
                    return $error; 
                }
                
        }
        
        $success['success'] = 'Successfully Updated Purchase Order';
        return $success;
    }
    
    public function addSaveReceiveItem($data = array()) 
    {
        $success =array();
        $error =array();
        $not_updated_items = array();
        
        //Adarsh: get the store id
        $get_store_id = DB::connection('mysql_dynamic')->select("SELECT SID FROM mst_store");
        
        if(isset($data['items']) && count($data['items']) > 0){
            
            if($data['receive_po'] == 'POtoWarehouse'){
                $ordertype = 'POtoWarehouse';
            }else{
                $ordertype = 'PO';
            }
            
            if(isset($data['ipoid'])){
                $purchaseorder_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM  trn_purchaseorder WHERE ipoid='" . (int)($data['ipoid']) . "'");
                $purchaseorder_exist = isset($purchaseorder_exist[0])?(array)$purchaseorder_exist[0]:[];
                
                if($purchaseorder_exist['vinvoiceno'] != $data['vinvoiceno']){
                    $query_vinvoiceno = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_purchaseorder WHERE vinvoiceno='". $data['vinvoiceno'] ."'");
                    if(count($query_vinvoiceno) > 0){
                        $error['error'] = 'Invoice Already Exist';
                        return $error;
                    }
                }
                $close = 'Close';
                $this->editPurchaseOrder($data,$close,$ordertype);
                $trn_purchaseorder_id = $data['ipoid'];
            }else{
                $query_vinvoiceno = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_purchaseorder WHERE vinvoiceno='". $data['vinvoiceno'] ."'");
                if(count($query_vinvoiceno) > 0){
                    $error['error'] = 'Invoice Already Exist';
                    return $error;
                }
                $close = 'Close';
                
                //Create PO
                $purchase_order_id = $this->addPurchaseOrder($data,$close,$ordertype);
                $trn_purchaseorder_id = $purchase_order_id['ipoid'];
            }
            
            //transfer to Receiving order
            $this->transfer_to_ro($trn_purchaseorder_id);
            
            //email
                
                if(count($not_updated_items) > 0){
                    $send_arr = array();
                    $send_arr['store_id'] = session()->get('sid');

                    if(isset($data['ipoid'])){
                        $send_arr['PO_id'] = $data['ipoid'];
                    }else{
                        $po_last_id = DB::connection('mysql_dynamic')->select("SELECT ipoid FROM trn_purchaseorder ORDER BY ipoid DESC");
                        $po_last_id = isset($po_last_id[0])?(array)$po_last_id[0]:[];
                        
                        $send_arr['PO_id'] = $po_last_id['ipoid'];
                    }

                    $send_arr['items'] = $not_updated_items;

                    //$to = "samaj.patel@gmail.com,mehul@dhvitiinfotech.com";
                    $to = "adarsh.s.chacko@gmail.com";
                    $subject = "Store [".session()->get('sid')."] PO Issue";
                   
                    $message = "<br>";
                    $message .= "<b>Details</b>";
                    $message .= "<br>";
                    $message .= "<pre>".print_r($send_arr,true);   
                   
                    $header = "From:sales@pos2020.com \r\n";
                    $header .= "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html\r\n";
                   
                    $retval = mail ($to,$subject,$message,$header);
                }

            //email

        }
        $success['success'] = 'Successfully Transferred to Receiving Order';
        return $success;
    }
    
    public function transfer_to_ro($ipoid)
    {
        
        // Insert into Receiving Order
        $query = "INSERT INTO `u" . (session()->get('sid')) . "`.`trn_receivingorder` 
                    (
                      `ipoid`, `vponumber`, `vrefnumber`, `nnettotal`, `ntaxtotal`, `dcreatedate`, `dreceiveddate`, `estatus`, `nfreightcharge`, 
                      `vconfirmby`, `vvendorid`, `vvendorname`, `nrectotal`, `nsubtotal`, `ndeposittotal`, `nreturntotal`, `vinvoiceno`, 
                      `ndiscountamt`, `nripsamt`,  `nfuelcharge`, `ndeliverycharge`, `vordertype`, `LastUpdate`, `SID`
                    )

                    (
                    	SELECT 
                    	  `ipoid`, `vponumber`, `vrefnumber`, `nnettotal`, `ntaxtotal`, `dcreatedate`, `dreceiveddate`, 'Open' as estatus, `nfreightcharge`, 
                    		`vconfirmby`, `vvendorid`, `vvendorname`, `nrectotal`, `nsubtotal`, `ndeposittotal`, `nreturntotal`, `vinvoiceno`, 
                    		`ndiscountamt`, `nripsamt`,  `nfuelcharge`, `ndeliverycharge`, `vordertype`, `LastUpdate`, `SID`
                    	FROM
                    		`u" . (session()->get('sid')) . "`.`trn_purchaseorder`
                    	WHERE
                    		ipoid='" . $ipoid . "'
                    )";
        
        $run_query = DB::connection('mysql_dynamic')->insert($query);
        
        $return = DB::connection('mysql_dynamic')->select("SELECT iroid FROM trn_receivingorder ORDER BY iroid DESC LIMIT 1");
                            
        $roid = $return[0]->iroid;
        // $roid = $this->db2->getLastId();
        
        // Insert into Receiving Order detail
        $query = "INSERT INTO `u" . (session()->get('sid')) . "`.`trn_receivingorderdetail`
                    (
                    	`iroid`, `vitemid`, `vitemname`, `vunitcode`, `vunitname`, `po_order_by`, `nordqty`, `nrceqty`, `nordunitprice`,
                    	`po_last_costprice`, `po_new_costprice`, `nreceunitprice`, `nordtax`, `nordextprice`, `nrceextprice`, `nnewunitprice`, `vbarcode`, `vvendoritemcode`,
                     	`npackqty`, `nunitcost`, `itotalunit`, `vsize`, `po_total_suggested_cost`, `LastUpdate`, `SID`, `nripamount`
                    )

                    (
                      SELECT 
                    	" . $roid . " as iroid, `vitemid`, `vitemname`, `vunitcode`, `vunitname`, `po_order_by`, `nordqty`, `nrceqty`, `nordunitprice`,
                    	`po_last_costprice`, `po_new_costprice`, `nreceunitprice`, `nordtax`, `nordextprice`, `nrceextprice`, `nnewunitprice`, `vbarcode`, `vvendoritemcode`,
                     	`npackqty`, `nunitcost`, `itotalunit`, `vsize`, `po_total_suggested_cost`, `LastUpdate`, `SID`, `nripamount`
                      FROM
                    	`u" . (session()->get('sid')) . "`.`trn_purchaseorderdetail`
                      WHERE
                    	ipoid='" . $ipoid . "'
                    )";
        
        $run_query = DB::connection('mysql_dynamic')->insert($query);
        
        return;
    }
}