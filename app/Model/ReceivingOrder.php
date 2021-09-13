<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReceivingOrder extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'trn_receivingorder';
    protected $primaryKey = 'iroid';
    public $timestamps = false;
    
    public function getMissingItems($data = array()) 
    {
        $sql = "SELECT * FROM mst_missingitem as mm, trn_receivingorder as tp WHERE mm.iinvoiceid=tp.iroid ORDER BY mm.imitemid DESC";
        
        $query = DB::connection('mysql_dynamic')->select($sql);
        
        $query = array_map(function ($value) {
                return (array)$value;
            }, $query);
            
        return $query;
    }
    
    public function getReceivingOrder($iroid) 
    {
        $return = array();
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_receivingorder WHERE iroid='". (int)$iroid ."'");
        $query = isset($query[0])?(array)$query[0]:[];
        $return = $query;
        
        // $query1 = DB::connection('mysql_dynamic')->select("SELECT tpod.*, mi.nsellunit, mi.last_costprice,mi.iqtyonhand as iqtyonhand,mi.dcostprice as dcostprice, mi.dunitprice as dunitprice,mi.ireorderpoint as ireorderpoint, mi.npack as npack, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand else 0 end as case_qty, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint else cast(((mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then  (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) else 0 end as total_case_qty FROM trn_receivingorderdetail as tpod, mst_item as mi WHERE mi.estatus='Active' AND tpod.vitemid=mi.iitemid AND iroid='". (int)$iroid ."' ORDER BY tpod.irodetid DESC");
        $sql = "SELECT tpod.*, mi.nsellunit, mi.last_costprice,
                            case mi.isparentchild  when 1 then   Mod(tpod.before_rece_qoh,p.NPACK)  else  
                            (Concat(cast(((tpod.before_rece_qoh div mi.NPACK )) as signed), '  (', Mod(tpod.before_rece_qoh,mi.NPACK) ,')') )end as before_rece_qoh1,
                            case mi.isparentchild  when 1 then   Mod(tpod.after_rece_qoh,p.NPACK)  else  
                            (Concat(cast(((tpod.after_rece_qoh div mi.NPACK )) as signed), '  (', Mod(tpod.after_rece_qoh,mi.NPACK) ,')') )end as after_rece_qoh1,
                            case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                            (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as iqtyonhand,
                            case mi.isparentchild when 0 then mi.vitemname 
                            when 1 then Concat(mi.vitemname,' [Child]') 
                            when 2 then  Concat(mi.vitemname,' [Parent]') 
                            end   as VITEMNAME ,
                            mi.dcostprice as dcostprice, mi.dunitprice as dunitprice,mi.ireorderpoint as ireorderpoint, mi.npack as npack, 
                            case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 
                            WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then mi.ireorderpoint 
                            WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) 
                            then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 
                            and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand 
                            WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) 
                            then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 
                            and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand else 0 end as case_qty, 
                            case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 
                            WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) 
                            then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint else 
                            cast(((mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and 
                            mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) 
                            then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) 
                            WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) 
                            then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand 
                            else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) 
                            WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) 
                            then  (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint 
                            else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>=0 
                            and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) 
                            then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) 
                            else 0 end as total_case_qty FROM trn_receivingorderdetail as tpod, mst_item as mi 
                            LEFT JOIN mst_item p ON mi.parentid = p.iitemid
                            WHERE mi.estatus='Active' AND tpod.vitemid=mi.iitemid AND iroid='". (int)$iroid ."' ORDER BY tpod.irodetid ASC";
        $query1 = DB::connection('mysql_dynamic')->select($sql);
        
        $query1 = array_map(function ($value) {
                return (array)$value;
            }, $query1);
       
        $return['items'] = $query1;
        
        return $return;
    }
    
    public function checkExistInvoice($invoice)
    {
        $return = array();
        $query = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_receivingorder WHERE vinvoiceno='". $invoice ."'");
        
        if(count($query) > 0){
            $return['error'] = 'Invoice Already Exist';
        }else{
            $return['success'] = 'Invoice Not Exist';
        }
        return $return;
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
        }else if(!empty($start_date) && !empty($end_date) && $start_date != 'undefined' && $end_date != 'undefined'){
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
            
            $query1 = DB::connection('mysql_dynamic')->select("SELECT tp.vvendorname as vvendorname, tpd.nunitcost as nunitcost, ifnull(SUM(tpd.nordextprice),0) as total_cost_price, date_format(tp.dcreatedate,'%m-%d-%Y') as receiving_date, ifnull(SUM(tpd.itotalunit),0) as total_quantity, DATE_FORMAT(tp.dcreatedate,'%M, %Y') as month_year FROM trn_receivingorderdetail tpd LEFT JOIN trn_receivingorder tp ON (tpd.iroid = tp.iroid) WHERE tpd.vitemid='". $iitemid ."' GROUP BY tp.vvendorname, date_format(tp.dcreatedate,'%m-%d-%Y')");
            
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
            
            $return['receiving_items'] = $temp_datas;
            
        }else{
            $query = DB::connection('mysql_dynamic')->select("SELECT ifnull(SUM(tsd.ndebitqty),0) as items_sold,ifnull(SUM(tsd.nextunitprice),0) as total_selling_price FROM trn_salesdetail tsd LEFT JOIN trn_sales ts ON (tsd.isalesid = ts.isalesid) WHERE tsd.vitemcode='". $vitemcode ."' AND (date_format(ts.dtrandate,'%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."') ");
            $query = isset($query[0])?(array)$query[0]:[];
            $return['item_detail'] = $query;
            
            $items_query = "SELECT tp.vvendorname as vvendorname, tpd.nunitcost as nunitcost, ifnull(SUM(tpd.nordextprice),0) as total_cost_price,date_format(tp.dcreatedate,'%m-%d-%Y') as receiving_date, ifnull(SUM(tpd.itotalunit),0) as total_quantity  FROM trn_receivingorderdetail tpd LEFT JOIN trn_receivingorder tp ON (tpd.iroid = tp.iroid) WHERE tpd.vitemid='". $iitemid ."' AND (date_format(tp.dcreatedate,'%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY tp.vvendorname, date_format(tp.dcreatedate,'%m-%d-%Y')";
            
            $query1 = DB::connection('mysql_dynamic')->select($items_query);

            $return['receiving_items'] = $query1;
        }

        return $return;

    }
    
    public function getSearchItem($iitemid,$ivendorid) 
    {
        // $query = DB::connection('mysql_dynamic')->select("SELECT mi.new_costprice,mi.last_costprice,mi.iitemid, mi.vitemcode, mi.vitemname, mi.vunitcode, mi.vbarcode, mi.dcostprice, mi.dunitprice, mi.vsize, mi.npack, mi.iqtyonhand, mi.ireorderpoint, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand else 0 end as case_qty, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint else cast(((mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then  (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) else 0 end as total_case_qty, mi.vsuppliercode as ivendorid, mu.vunitname FROM mst_item mi LEFT JOIN mst_unit mu ON (mu.vunitcode = mi.vunitcode) WHERE mi.estatus='Active' AND mi.iitemid='". (int)$iitemid ."'");
        
        $mysql_query = "SELECT ms.vcompanyname vendor_name, mi.nsellunit, mi.new_costprice,mi.iitemid, mi.vitemcode, 
                            case mi.isparentchild when 0 then mi.vitemname 
                            when 1 then Concat(mi.vitemname,' [Child]') 
                            when 2 then  Concat(mi.vitemname,' [Parent]') 
                            end   as vitemname , 
                            mi.vunitcode, mi.vbarcode, mi.dcostprice, mi.dunitprice, case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as iqtyonhand, mi.vsize, mi.npack, mi.ireorderpoint, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then mi.iqtyonhand-mi.ireorderpoint WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then mi.ireorderpoint-mi.iqtyonhand else 0 end as case_qty, case WHEN (mi.iqtyonhand <= 0 and mi.ireorderpoint <=0 or mi.ireorderpoint=null) then 0 WHEN (mi.iqtyonhand<=0 and mi.ireorderpoint > 0 or mi.ireorderpoint!=null) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint else cast(((mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand > mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>0 and mi.ireorderpoint >= 0 and mi.iqtyonhand > mi.ireorderpoint) then  (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.iqtyonhand-mi.ireorderpoint else cast(((mi.iqtyonhand-mi.ireorderpoint)/mi.npack ) as signed) end) WHEN (mi.iqtyonhand>=0 and mi.ireorderpoint > 0 and mi.iqtyonhand < mi.ireorderpoint) then (CASE WHEN mi.npack = 1 or (mi.npack is null) then mi.ireorderpoint-mi.iqtyonhand else cast(((mi.ireorderpoint-mi.iqtyonhand)/mi.npack ) as signed) end) else 0 end as total_case_qty, mi.vsuppliercode as ivendorid, mu.vunitname FROM mst_item mi LEFT JOIN mst_unit mu ON (mu.vunitcode = mi.vunitcode) LEFT JOIN mst_supplier ms ON (mi.vsuppliercode = ms.vsuppliercode) LEFT JOIN mst_item p ON mi.parentid = p.iitemid WHERE mi.estatus='Active' AND mi.iitemid='". (int)$iitemid ."'";
        
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
    
    public function getCheckInvoice($invoice) 
    {
        $invoices = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseitems WHERE invoiceid='" .($invoice). "'");
        $invoices = isset($invoices[0])?(array)$invoices[0]:[];
        
        $return = array();
        if(count($invoices) > 0){
            $return['error'] = 'Invoice Already Exist!';
        }else{
            $return['success'] = 'Not Exist!';
        }

        return $return;

    }
    
    public function getReceivingData($vitemcode) 
    {
        $return = array();
        
        $sid = (int)(session()->get('sid'));
        
        $db = 'u'.$sid;
        
        $query = 'SELECT tpd.nunitcost as cost, tpd.nordunitprice as selling_price, tpd.nordextprice as total_cost, 
                    
                    (((tpd.nordunitprice - tpd.nunitcost)/tpd.nordunitprice)*100) as profit_percent, 
                    
                    tp.estatus as status  FROM '. $db .'.trn_receivingorderdetail tpd
                    
                    LEFT JOIN ' . $db . '.trn_receivingorder tp ON tpd.iroid = tp.iroid
                    
                    WHERE tpd.vbarcode = "'.$vitemcode.'" AND tp.estatus = "Close" ORDER BY tp.dreceiveddate DESC';
                    
        $run_query = DB::connection('mysql_dynamic')->select($query);
        
        $run_query = array_map(function ($value) {
                        return (array)$value;
                    }, $run_query);
                    
        $temp_datas['purchase_history'] = $run_query;

        return $temp_datas;

    }
    
    public function addReceivingOrder($data = array(), $close = null, $ordertype)
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
                    $query->vconfirmby = $data['vconfirmby'] ?? ''; 
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
                    $iroid = $query->iroid;
                    
                    // // $iroid = '198765456';
                    $trn_receivingorder_id = $iroid;
                    
                    $file_path = storage_path("logs/receiving_orders/open_receiving_orders.csv");
                    
                    $fp = fopen($file_path, 'a');
                    
                    $sid = (int)(session()->get('sid'));
                    
                    if($sid == 100247 || $sid == 100636 || $sid == 100154){
                        
                        $text = PHP_EOL."========STORE(".$sid.") START NEW INVOICE(".$data['vinvoiceno'].") Date: ".date('Y-m-d')." ".date('H:i:s')." (SAVE)Not Receive=========".PHP_EOL;
                        
                        fwrite($fp, $text);
                        
                        // fwrite($fp,$log_present."\n");
                        // fclose($fp);
                    }
                    
                    if(isset($data['items']) && count($data['items']) > 0){
                    
                        // $data['items'] = array_reverse($data['items']);
						
						$riptotalamount =0;
						
                        foreach ($data['items'] as $key => $item) {
                            
                            $isItemExist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                            $isItemExist = isset($isItemExist[0])?(array)$isItemExist[0]:[];
                            
                            $unit_price = (isset($item['dunitprice']) && $item['dunitprice'] != 0)?($item['dunitprice']):($item['nordunitprice']);
                            
                            $insert_pod_query = "INSERT INTO trn_receivingorderdetail SET  iroid = '" . (int)$iroid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . $unit_price . "', po_last_costprice = '" . ($isItemExist['last_costprice']) . "', po_new_costprice = '" . ($isItemExist['new_costprice']) . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', vsize = '" . ($item['vsize']) . "', po_order_by = '" . ($item['po_order_by']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npackqty']) . "', itotalunit = '" . ($item['itotalunit']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost']) . "',SID = '" . (int)(session()->get('sid'))."',nripamount= '" . ($item['nripamount']) . "'";
                            
                            DB::connection('mysql_dynamic')->insert($insert_pod_query);
							
							if($sid == 100247 || $sid == 100636 || $sid == 100154 ){
                                $text = "(Item Name)".$isItemExist['vitemname'].", (SKU)".$isItemExist['vbarcode'].", (QoH Before Receiving)".$isItemExist['iqtyonhand'].", (Qty Ordered)".$item['nordqty'].", (npack)".$item['npackqty']." ".PHP_EOL;
                                fwrite($fp, $text);
                            }
							
							$amt  = $item['nripamount'] ?? 0;
							$riptotalamount=$riptotalamount+$amt;								
							// Price Change
                            
                            // $isItemExist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                            
                            if($item['dunitprice'] != $isItemExist['dunitprice']){
                            
                                $sql_mst_item = "UPDATE mst_item SET dunitprice = '" . ($item['dunitprice']) . "' WHERE iitemid='".(int)$item['vitemid']."'";
                                DB::connection('mysql_dynamic')->update($sql_mst_item);
                                
                                if($isItemExist['vitemtype'] == 'Lottery'){
                                    $type_name = 'POPriceLott';
                                }else if($isItemExist['vitemtype'] == 'Kiosk'){
                                    $type_name = 'POPriceKio';
                                }else if($isItemExist['vitemtype'] == 'Lot Matrix'){
                                    $type_name = 'POPriceLot';
                                }else if($isItemExist['vitemtype'] == 'Gasoline'){
                                    $type_name = 'POPriceGas';
                                }else{
                                    $type_name = 'POPriceStd';
                                }
                                
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $item['vitemid'] . "',vbarcode = '" . ($isItemExist['vbarcode']) . "', vtype = '". $type_name ."', noldamt = '" . ($isItemExist['dunitprice']) . "', nnewamt = '" . ($item['dunitprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                
                                //trn_webadmin_history
                                $trn_webadmin_history = DB::connection('mysql_dynamic')->statement("SHOW tables LIKE 'trn_webadmin_history' ");
                                $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                                
                                if(count($trn_webadmin_history)){
                                    
                                    $old_item_values = $isItemExist;
                                    unset($old_item_values['itemimage']);
                                    
                                    $x_general = new \stdClass();
                                    $x_general->trn_receivingorder_id = $trn_receivingorder_id;
                                    $x_general->current_po_item_values = $item;
                                    $x_general->old_item_values = $old_item_values;
                                    
                                    $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($item['vitemid']) ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                    
                                    unset($new_item_values['itemimage']);
                                    $x_general->new_item_values = $new_item_values;
                                    
                                    $x_general = addslashes(json_encode($x_general));
                                    
                                    try{
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $item['vitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($isItemExist['vbarcode']) . "', type = 'Price', oldamount = '" . ($isItemExist['dunitprice']) . "', newamount = '". ($item['dunitprice']) ."', general = '" . $x_general . "', source = 'PO', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                    }
                                    catch (Exception $e) {
                                          Log::error($e);
                                    }
                                
                                
                                }
                                //trn_webadmin_history
                                
                                if(($isItemExist['vitemtype']) == 'Lot Matrix'){
                                
                                    $itempackexist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE vbarcode='". ($isItemExist['vbarcode']) ."' AND iitemid='". (int)$isItemExist['iitemid'] ."' AND iparentid=1");
                                    $itempackexist = isset($itempackexist[0])?(array)$itempackexist[0]:[];
                                    
                                    if(count($itempackexist) > 0){
                                        
                                        $vpackname = $itempackexist['vpackname'];
                                        $vdesc = $itempackexist['vdesc'];
                                        
                                        $nunitcost = $itempackexist['nunitcost'];
                                        if($nunitcost == ''){
                                            $nunitcost = 0;
                                        }
                                        
                                        $ipack = $itempackexist['ipack'];
                                        if($itempackexist['ipack'] == ''){
                                            $ipack = 0;
                                        }
                                        
                                        $npackprice = ($item['dunitprice']);
                                        if(($item['dunitprice']) == ''){
                                            $npackprice = 0;
                                        }
                                        
                                        $npackcost = (int)$ipack * $nunitcost;
                                        $iparentid = 1;
                                        $npackmargin = $npackprice - $npackcost;
                                        
                                        if($npackprice == 0){
                                            $npackprice = 1;
                                        }
                                        
                                        if($npackmargin > 0){
                                            $npackmargin = $npackmargin;
                                        }else{
                                            $npackmargin = 0;
                                        }
                                        
                                        $npackmargin = (($npackmargin/$npackprice) * 100);
                                        $npackmargin = number_format((float)$npackmargin, 2, '.', '');
                                        
                                        
                                        DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='". ($isItemExist['vbarcode']) ."' AND iitemid='". (int)$isItemExist['iitemid'] ."' AND iparentid=1");
                                    }else{
                                        
                                                            $vpackname = 'Case';
                                        $vdesc = 'Case';
                                        
                                        $nunitcost = ($isItemExist['nunitcost']);
                                        if($nunitcost == ''){
                                            $nunitcost = 0;
                                        }
                                        
                                        $ipack = ($isItemExist['nsellunit']);
                                        if(($isItemExist['nsellunit']) == ''){
                                            $ipack = 0;
                                        }
                                        
                                        $npackprice = ($item['dunitprice']);
                                        if(($item['dunitprice']) == ''){
                                            $npackprice = 0;
                                        }
                                        
                                        $npackcost = (int)$ipack * $nunitcost;
                                        $iparentid = 1;
                                        $npackmargin = $npackprice - $npackcost;
                                        
                                        if($npackprice == 0){
                                            $npackprice = 1;  
                                        }
                                        
                                        if($npackmargin > 0){
                                            $npackmargin = $npackmargin;
                                        }else{
                                            $npackmargin = 0;
                                        }
                                        
                                        $npackmargin = (($npackmargin/$npackprice) * 100);
                                        $npackmargin = number_format((float)$npackmargin, 2, '.', '');
                                        
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itempackdetail SET  iitemid = '" . (int)$iitemid . "',`vbarcode` = '" . ($isItemExist['vbarcode']) . "',`vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`nunitcost` = '" . $nunitcost . "',`ipack` = '" . (int)$ipack . "',`iparentid` = '" . (int)$iparentid . "',`npackcost` = '" . $npackcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "', SID = '" . (int)(session()->get('sid')) . "'");
                                    }
                                    
                                }
                                    
                            }
                            
                            // Price Change
                        }
						
						if($riptotalamount > 0)
						{
							$sql= "INSERT INTO trn_rip_header SET ponumber = '" . ($data['vinvoiceno']) . "', vendorid = '" . ($data['vvendorid']) . "', riptotal = '" . ($riptotalamount) . "', receivedtotalamt = '0.00', pendingtotalamt = '" . ($riptotalamount) . "',SID = '" . (int)(session()->get('sid'))."'";
							DB::connection('mysql_dynamic')->insert($sql);
						}
                    }
                    
                    fclose($fp);
                    
                }
                
                catch (QueryException $e) {
                    // other mysql exception (not duplicate key entry)
                    
                    $error['error'] = $e->getMessage().'2'; 
                    return $error; 
                }
                // catch (\Exception $e) {
                //     // not a MySQL exception
                   
                //     $error['error'] = $e->getMessage().'3'; 
                //     return $error; 
                // }
        }
        
        $success['success'] = 'Successfully Added Receiving Order';
        $success['iroid'] = $iroid;
        return $success;
    }
    
    public function addSaveReceiveItem($data = array()) 
    {
        $success =array();
        $error =array();
        $not_updated_items = array();
        
        // print_r($data); die;
        
        //Adarsh: get the store id
        $get_store_id = DB::connection('mysql_dynamic')->select("SELECT SID FROM mst_store");
        $get_store_id = isset($get_store_id[0])?(array)$get_store_id[0]:[];
        
        if(isset($data['items']) && count($data['items']) > 0){
            
            if($data['receive_po'] == 'POtoWarehouse'){
                $ordertype = 'POtoWarehouse';
            }else{
                $ordertype = 'PO';
            }
            
            if(isset($data['iroid'])){
                $purchaseorder_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM  trn_receivingorder WHERE iroid='" . (int)($data['iroid']) . "'");
                $purchaseorder_exist = isset($purchaseorder_exist[0])?(array)$purchaseorder_exist[0]:[];
                
                if(trim($purchaseorder_exist['vinvoiceno']) != $data['vinvoiceno']){
                    $query_vinvoiceno = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_receivingorder WHERE vinvoiceno='". $data['vinvoiceno'] ."'");
                    if(count($query_vinvoiceno) > 0){
                        $error['error'] = 'Invoice Already Exist';
                        return $error;
                    }
                }
                $close = 'Close';
                $check = $this->editReceivingOrder($data,$close,$ordertype);
                
                if(array_key_exists("error",$check)){
                    return $check;
                }
                    
                $trn_receivingorder_id = $data['iroid'];
            }else{
                $query_vinvoiceno = DB::connection('mysql_dynamic')->select("SELECT vinvoiceno FROM trn_receivingorder WHERE vinvoiceno='". $data['vinvoiceno'] ."'");
                    if(count($query_vinvoiceno) > 0){
                        $error['error'] = 'Invoice Already Exist';
                        return $error;
                    }
                    $close = 'Close';
                    $purchase_order_id = $this->addReceivingOrder($data,$close,$ordertype);
                    
                    if(array_key_exists("error",$purchase_order_id)){
                        return $purchase_order_id;
                    }
                    $trn_receivingorder_id = $purchase_order_id['iroid'];
            }
            
            //=======START Log creation for stores 100247, 100636, 100154 ============
            
            $file_path = storage_path("logs/receiving_orders/receiving_orders.csv");
            
            $fp = fopen($file_path, 'a');
            
            $sid = (int)(session()->get('sid'));
            
            if($sid == 100247 || $sid == 100636 || $sid == 100154 ){
                
                $text = PHP_EOL."========STORE(".$sid.") START NEW INVOICE(".$data['vinvoiceno'].") Date: ".date('Y-m-d')." ".date('H:i:s')." Received at(".$data['receive_po'].")=========".PHP_EOL;
                
                fwrite($fp, $text);
                
                // fwrite($fp,$log_present."\n");
                // fclose($fp);
            }
            
            foreach ($data['items'] as $key => $item) {
                
                try {
                    
                    // update in mst_itemvendor table
                    if(isset($item['vvendoritemcode']) && $item['vvendoritemcode'] != '' && strlen(trim($item['vvendoritemcode'])) > 0){
                        
                        $itemvendor_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_itemvendor WHERE iitemid='" . (int)($item['vitemid']) . "' AND ivendorid='" . (int)($data['vvendorid']) . "' AND vvendoritemcode='". ($item['vvendoritemcode']) ."' ");
                        
                        if(count($itemvendor_exist) > 0){
                            DB::connection('mysql_dynamic')->update("UPDATE mst_itemvendor SET vvendoritemcode = '" . ($item['vvendoritemcode']) . "' WHERE iitemid='" . (int)($item['vitemid']) . "' AND ivendorid='" . (int)($data['vvendorid']) . "'");
                        }else{
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)($item['vitemid']) . "',ivendorid = '" . (int)($data['vvendorid']) . "',vvendoritemcode = '" . ($item['vvendoritemcode']) . "',SID = '" . (int)(session()->get('sid'))."'");
                        }
                    }
                    
                    // update in mst_itemvendor table
                    $current_item = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($item['vitemid']) . "'");
                    $current_item = isset($current_item[0])?(array)$current_item[0]:[];
                        
                    $total_cost = $item['nordextprice'] + ($current_item['iqtyonhand'] * $current_item['nunitcost']);
                    
                    
                    $total_qty = $current_item['iqtyonhand'];
                    // if($total_qty == 0 || $total_qty == '0'){
                    //     $new_nunitcost = $total_cost;
                    // }else{
                    //     $new_nunitcost = $total_cost / $total_qty;
                    // }
                    

                    if($data['receive_po'] == 'POtoWarehouse'){
                        $total_qty = $current_item['iqtyonhand'];
                        // $total_qty = $current_item['iqtyonhand'] + $item['itotalunit'];
                    }else{
                        $total_qty = $current_item['iqtyonhand'] + $item['itotalunit'];
                    }
                    
                    $overallqty = $current_item['iqtyonhand']+ $item['itotalunit'];
                   
                    
                    // if($total_qty == 0 || $total_qty == '0'){
                    //     $new_nunitcost = $total_cost;
                    // }else{
                    //     $new_nunitcost = $total_cost / $overallqty;
                    // }
                    
                    // if($new_nunitcost < 0){
                    //     $new_nunitcost = $item['nunitcost'];
                    // }
                    $new_nunitcost = $item['nunitcost'];
                    
                    $new_nunitcost = number_format((float)$new_nunitcost, 4, '.', '');
                    
                    // if(isset($data['update_pack_qty']) && $data['update_pack_qty'] == 'Yes'){ // npack not updated AP-835
                    if(isset($data['advance_update']) && $data['advance_update'] == 'Yes'){ 
                        $npack = $item['npackqty'];
                    }else{
                        $npack = $current_item['npack'];
                    }
                    
                    //=====added on 15-05-2020=======
                    $nsellunit = DB::connection('mysql_dynamic')->select("SELECT nsellunit FROM mst_item WHERE iitemid ='" . (int)($item['vitemid']) . "'");
                    $nsellunit = isset($nsellunit[0])?(array)$nsellunit[0]:[];
                    
                    $new_dcostprice = ($item['nordextprice'] / $item['itotalunit']) * $nsellunit['nsellunit'];
                    // $new_dcostprice = $npack * $new_nunitcost;
                    
                    /*if($current_item['dcostprice'] > 0)
                    {
                        $new_dcostprice = ($new_dcostprice + $current_item['dcostprice']) / 2;
                    }*/
                    
                    $new_dcostprice = number_format((float)$new_dcostprice, 4, '.', '');
                    
                    
                    // $item -> user input | $current_item -> from database
                    if((isset($item['nunitcost']) && isset($current_item['nunitcost']) && isset($item['nordextprice'])) && $item['nunitcost'] != $current_item['nunitcost'] && $item['nunitcost'] > 0 && $item['nordextprice'] > 0){
                        $new_nunitcost = $new_nunitcost;
                        $new_dcostprice = $new_dcostprice;
                    }else{
                        $new_nunitcost = $current_item['nunitcost'];
                        $new_dcostprice = $current_item['dcostprice'];
                    }
                    
                    //update dcostprice,npack and nunitcost
                    if(count($current_item) > 0 && $data['receive_po'] == 'receivetostore'){
                        if($current_item['isparentchild'] == 1){
                            // DB::connection('mysql_dynamic')->select("UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '0',nunitcost = '" . $new_nunitcost . "' WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                            
                            $get_purchase_details = DB::connection('mysql_dynamic')->select("SELECT nunitcost FROM trn_receivingorderdetail WHERE vitemid='". (int)$item['vitemid'] ."' ORDER BY LastUpdate DESC LIMIT 2");
                            //====converting object data into array=====
                            $get_purchase_details = array_map(function ($value) {
                                return (array)$value;
                            }, $get_purchase_details);
                            //=====added on 15-05-2020=======
                            
                            // if(count($get_purchase_details) == 2){
                            //     $new_costprice = $get_purchase_details[1]['nunitcost'];
                            // } else {
                            //     $new_costprice = $get_purchase_details[0]['nunitcost'];
                            // }
                            $new_costprice =  $new_dcostprice;
                            
                            if($sid == 100247 || $sid == 100636 || $sid == 100154 ){
                                $text = "(Item Name)".$current_item['vitemname'].", (SKU)".$current_item['vbarcode'].", (QoH Before Receiving)".$current_item['iqtyonhand'].", (Qty Received)".$item['nordqty'].", (QoH after receiving)0, (npack)".$npack." ".PHP_EOL;
                                fwrite($fp, $text);
                            }
                            
                            $parent_item_qoh = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($current_item['parentmasterid']) . "'");
                            $parent_item_qoh = isset($parent_item_qoh[0])?(array)$parent_item_qoh[0]:[];
                            
                            $before_rece_qoh = $parent_item_qoh['iqtyonhand'] % $parent_item_qoh['npack'];
                            //==============Update before_rece_qoh column in trn_receivingorderdetail table================
                            DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorderdetail SET before_rece_qoh = '".(int)$before_rece_qoh."' WHERE iroid = '".$trn_receivingorder_id."' AND vitemid = '".(int)($item['vitemid'])."' ");
                            
                            //============================================= New query to insert last cost ======================================================================
                                // existing query
                                // DB::connection('mysql_dynamic')->select("UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '0',nunitcost = '" . $new_nunitcost . "' WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                                DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '0',nunitcost = '" . $new_nunitcost . "',last_costprice='" . $current_item['nunitcost'] . "',new_costprice= '". $new_costprice ."' WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                                
                            //trn_itempricecosthistory
                                
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $item['vitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'POQOH', noldamt = '" . ($current_item['iqtyonhand']) . "', nnewamt = '0', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                
                                if($current_item['dcostprice'] != $new_dcostprice){
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $item['vitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'POCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . $new_dcostprice . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                
                            //trn_itempricecosthistory
                                
                            $result = DB::connection('mysql_dynamic')->statement("SHOW tables LIKE 'trn_webadmin_history' ");
                            $result = isset($result[0])?(array)$result[0]:[];
                            
                            if(count($result)){ 
                                $old_item_values = $current_item;
                                unset($old_item_values['itemimage']);
                                    
                                $x_general = new \stdClass();
                                $x_general->trn_receivingorder_id = $trn_receivingorder_id;
                                $x_general->is_child = 'Yes';
                                $x_general->parentmasterid = $old_item_values['parentmasterid'];
                                $x_general->current_po_item_values = $item;
                                $x_general->old_item_values = $old_item_values;
                                
                                $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($item['vitemid']) ."' ");
                                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                
                                unset($new_item_values['itemimage']);
                                
                                $x_general->new_item_values = $new_item_values;
                                
                                $x_general = addslashes(json_encode($x_general));
                                try{
                                    
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $item['vitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($current_item['iqtyonhand']) . "', newamount = '0', general = '" . $x_general . "', source = 'PO', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                    catch (\Exception $e) {
                                      Log::error($e);
                                }
                            }
                            //trn_webadmin_history

                            //email
                            $check_item_qoh1 = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($item['vitemid']) . "'");
                            $check_item_qoh1 = isset($check_item_qoh1[0])?(array)$check_item_qoh1[0]:[];
                            
                            if($check_item_qoh1['iqtyonhand'] != 0){
                                $not_updated_items[] = 'iitemid[child]:'. $item['vitemid'] .' previous:'.$current_item['iqtyonhand'].'  new:0';
                            }
                            //email
                            
                            //trn_itempricecosthistory
                            
                            $parent_item = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($current_item['parentmasterid']) . "'");
                            $parent_item = isset($parent_item[0])?(array)$parent_item[0]:[];
                            
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'POQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '". (($parent_item['iqtyonhand']) + $total_qty) ."', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                            
                            //trn_itempricecosthistory
                            
                            //trn_webadmin_history
                            $result = DB::connection('mysql_dynamic')->statement("SHOW tables LIKE 'trn_webadmin_history' ");
                            $result = isset($result[0])?(array)$result[0]:[];
                            
                            if(count($result)){ 
                                $old_item_values = $parent_item;
                                unset($old_item_values['itemimage']);

                                $x_general = new \stdClass();
                                $x_general->trn_receivingorder_id = $trn_receivingorder_id;
                                $x_general->is_parent = 'Yes';
                                $x_general->current_po_item_values = $item;
                                $x_general->old_item_values = $old_item_values;

                                try{

                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". (($parent_item['iqtyonhand']) + $total_qty) ."', source = 'PO', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                    catch (\Exception $e) {
                                      Log::error($e);
                                }
                                $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                            
                                $trn_webadmin_history_last_id = $return[0]->historyid;
                                // $trn_webadmin_history_last_id = $this->db2->getLastId();
                            }
                            //trn_webadmin_history
                                
                            DB::connection('mysql_dynamic')->update("UPDATE mst_item SET iqtyonhand = iqtyonhand+'" . $total_qty . "' WHERE iitemid='" . (int)($current_item['parentmasterid']) . "' ");
                            
                            //==============Update after_rece_qoh column in trn_receivingorderdetail table================
                            $parent_item_qoh = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($current_item['parentmasterid']) . "'");
                            $parent_item_qoh = isset($parent_item_qoh[0])?(array)$parent_item_qoh[0]:[];
                            
                            $after_rece_qoh = $parent_item_qoh['iqtyonhand'] % $parent_item_qoh['npack'];
                            
                            DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorderdetail SET after_rece_qoh = '".$after_rece_qoh."' WHERE iroid = '".$trn_receivingorder_id."' AND vitemid = '".(int)($item['vitemid'])."' ");
                            //========================END=================================
                        //trn_webadmin_history
                            $result = DB::connection('mysql_dynamic')->statement("SHOW tables LIKE 'trn_webadmin_history' ");
                            $result = isset($result[0])?(array)$result[0]:[];
                            
                            if(count($result)){ 
                                $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['parentmasterid']) ."' ");
                                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                
                                unset($new_item_values['itemimage']);
                                $x_general->new_item_values = $new_item_values;
                                $x_general = addslashes(json_encode($x_general));
                                try{
    
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                }
                                catch (\Exception $e) {
                                    Log::error($e);
                                }
                            }
                            //trn_webadmin_history

                            //email
                            $check_item_qoh = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($current_item['parentmasterid']) . "'");
                            $check_item_qoh = isset($check_item_qoh[0])?(array)$check_item_qoh[0]:[];
                            
                            if((($parent_item['iqtyonhand']) + $total_qty) != $check_item_qoh['iqtyonhand']){
                                $not_updated_items[] = 'iitemid[parent]:'. $current_item['parentmasterid'] .' previous:'.$parent_item['iqtyonhand'].'  new:'.(($parent_item['iqtyonhand']) + $total_qty);
                            }
                            //email
                            
                        }else{
                            //Original query: Disabled
                            // DB::connection('mysql_dynamic')->select("UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '" . $total_qty . "',nunitcost = '" . $new_nunitcost . "' WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                            
                            //=============================== New query to insert last cost: Written by Adarsh --- Starts ==========================================================
                            $get_purchase_details = DB::connection('mysql_dynamic')->select("SELECT nunitcost FROM trn_receivingorderdetail WHERE vitemid='". (int)($item['vitemid']) ."' ORDER BY LastUpdate DESC LIMIT 2");
                            $get_purchase_details = array_map(function ($value) {
                                return (array)$value;
                            }, $get_purchase_details);
                            
                            // $new_costprice = (count($get_purchase_details)>0)?$get_purchase_details[0]['nunitcost']:0.00;
                            $new_costprice =  $new_dcostprice;
                            
                            //=====Add Items to Logs====
                            if($sid == 100247 || $sid == 100636 || $sid == 100154){
                                $text = "(Item Name)".$current_item['vitemname'].", (SKU)".$current_item['vbarcode'].", (QoH Before Receiving)".$current_item['iqtyonhand'].", (Qty Received)".$item['nordqty'].", (QoH after receiving)".$total_qty.", (npack)".$npack." ".PHP_EOL;
                                fwrite($fp, $text);
                            }
                            
                            //==============Update before_rece_qoh column in trn_receivingorderdetail table================
                            DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorderdetail SET before_rece_qoh = '".$current_item['iqtyonhand']."' WHERE iroid = '".$trn_receivingorder_id."' AND vitemid = '".(int)($item['vitemid'])."' ");
                            
                            $query_new_costprice = "UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '".$total_qty."',nunitcost = '" . $new_nunitcost . "',last_costprice='" . $current_item['nunitcost'] . "',new_costprice= '". $new_costprice ."' WHERE iitemid='" . (int)($item['vitemid']) . "' ";
                            
                            
                            
                            $a = DB::connection('mysql_dynamic')->update($query_new_costprice);
                            
                                // existing
                                //   DB::connection('mysql_dynamic')->select("UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '".$total_qty."',nunitcost = '" . $new_nunitcost . "' WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                            
                            //============================== New query to insert last cost: Written by Adarsh --- Ends ============================================================
                            
                            //==============Update after_rece_qoh column in trn_receivingorderdetail table================
                            $item_qoh = DB::connection('mysql_dynamic')->select("SELECT iqtyonhand FROM  mst_item WHERE iitemid='" . (int)($item['vitemid']) . "'");
                            $item_qoh = isset($item_qoh[0])?(array)$item_qoh[0]:[];
                            
                            DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorderdetail SET after_rece_qoh = '".$item_qoh['iqtyonhand']."' WHERE iroid = '".$trn_receivingorder_id."' AND vitemid = '".(int)($item['vitemid'])."' ");
                            //========================END=================================
                            
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'POQOH', noldamt = '" . ($current_item['iqtyonhand']) . "', nnewamt = '". $total_qty ."', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");

                            if($current_item['dcostprice'] != $new_dcostprice){
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'POCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '". $new_dcostprice ."', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                            }

                            //trn_webadmin_history
                            $result = DB::connection('mysql_dynamic')->statement("SHOW tables LIKE 'trn_webadmin_history' ");
                            $result = isset($result[0])?(array)$result[0]:[];
                            
                            if(count($result)){ 
                                $old_item_values = $current_item;
                                unset($old_item_values['itemimage']);

                                $x_general = new \stdClass();
                                $x_general->trn_receivingorder_id = $trn_receivingorder_id;
                                $x_general->current_po_item_values = $item;
                                $x_general->old_item_values = $old_item_values;
                                $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
                                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                
                                unset($new_item_values['itemimage']);
                                $x_general->new_item_values = $new_item_values;
                                $x_general = addslashes(json_encode($x_general));
                                try{

                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($current_item['iqtyonhand']) . "', newamount = '". $total_qty ."', general = '" . $x_general . "', source = 'PO', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                catch (\Exception $e) {
                                      Log::error($e);
                                }
                            }
                            //trn_webadmin_history

                            //email
                            $check_item_qoh = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($item['vitemid']) . "'");
                            $check_item_qoh = isset($check_item_qoh[0])?(array)$check_item_qoh[0]:[];
                            
                            if($total_qty != $check_item_qoh['iqtyonhand']){
                                $not_updated_items[] = 'iitemid:'. $item['vitemid'] .'  previous:'.$current_item['iqtyonhand'].'  new:'.$total_qty;
                            }
                            //email

                        }
                        
                    }
                    
                    /* venkat: This else block is closed to stop updating the dcost price when po send to wherehouse */
                    else{
                        //old query
                        // DB::connection('mysql_dynamic')->select("UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '" . $total_qty . "',nunitcost = '" . $new_nunitcost . "' WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                        //end old query
                        $get_purchase_details = DB::connection('mysql_dynamic')->select("SELECT nunitcost FROM trn_receivingorderdetail WHERE vitemid='". (int)($item['vitemid']) ."' ORDER BY LastUpdate DESC LIMIT 2");
                        $get_purchase_details = array_map(function ($value) {
                                return (array)$value;
                            }, $get_purchase_details);
                            
                        // $new_costprice = (count($get_purchase_details)>0)?$get_purchase_details[0]['nunitcost']:0.00;
                        $new_costprice =  $new_dcostprice;
                        
                        if($sid == 100247 || $sid == 100636 || $sid == 100154){
                            $text = "(Item Name)".$current_item['vitemname'].", (SKU)".$current_item['vbarcode'].", (QoH Before Receiving)".$current_item['iqtyonhand'].", (Qty Received)".$item['nordqty'].", (QoH after receiving)".$total_qty.", (npack)".$npack." ".PHP_EOL;
                            fwrite($fp, $text);
                        }
                        
                        //==============Update before_rece_qoh column in trn_receivingorderdetail table================
                            DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorderdetail SET before_rece_qoh = '".$current_item['iqtyonhand']."' WHERE iroid = '".$trn_receivingorder_id."' AND vitemid = '".(int)($item['vitemid'])."' ");
                        
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dcostprice = '" . $new_dcostprice . "',npack = '" . $npack . "',iqtyonhand = '" . $total_qty . "',nunitcost = '" . $new_nunitcost . "', last_costprice='" . $current_item['nunitcost'] . "',new_costprice= '". $new_costprice ."'  WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                        
                        //==============Update after_rece_qoh column in trn_receivingorderdetail table================
                            $item_qoh = DB::connection('mysql_dynamic')->select("SELECT iqtyonhand FROM  mst_item WHERE iitemid='" . (int)($item['vitemid']) . "'");
                            $item_qoh = isset($item_qoh[0])?(array)$item_qoh[0]:[];
                            
                            DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorderdetail SET after_rece_qoh = '".$item_qoh['iqtyonhand']."' WHERE iroid = '".$trn_receivingorder_id."' AND vitemid = '".(int)($item['vitemid'])."' ");
                        //========================END=================================
                         
                    }
                    
                    
                    
                    //update dcostprice,npack and nunitcost

                    //update child item dcostprice,npack and nunitcost
                    $isParentCheck = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($item['vitemid']) . "'");
                    $isParentCheck = isset($isParentCheck[0])?(array)$isParentCheck[0]:[];
                    
            
                    
                    //=======commented on 23/06/2020 because issue raise in jira AP-470======
                    // if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                    //     $child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`dcostprice` FROM mst_item WHERE parentmasterid= '". (int)($item['vitemid']) ."' ");
                        
                    //     if(count($child_items) > 0){
                    //         foreach($child_items as $chi_item){
                    
                    //             $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                
                    //             $update_query = "UPDATE mst_item SET dcostprice=(npack*". ($isParentCheck['nunitcost']) ."),nunitcost='". ($isParentCheck['nunitcost']) ."' WHERE iitemid= '". (int)($chi_item['iitemid']) ."'";
                                
                 
                    //             $a = DB::connection('mysql_dynamic')->select($update_query);
                                
                    //             //trn_itempricecosthistory
                    //             $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$chi_item['iitemid'] ."' ");
                    //             if($chi_item['dcostprice'] != $new_update_values['dcostprice']){

                    //                 DB::connection('mysql_dynamic')->select("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'POCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                    //             }

                    //             //trn_itempricecosthistory

                    //             $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");

                    //             if($old_item_values['dcostprice'] != $new_item_values['dcostprice']){

                    //                 unset($old_item_values['itemimage']);
                    //                 unset($new_item_values['itemimage']);
                    //                 $x_general = new stdClass();
                    //                 $x_general->trn_receivingorder_id = $trn_receivingorder_id;
                    //                 $x_general->is_child = 'Yes';
                    //                 $x_general->parentmasterid = $new_item_values['parentmasterid'];
                    //                 $x_general->current_po_item_values = $item;
                    //                 $x_general->old_item_values = $old_item_values;
                    //                 $x_general->new_item_values = $new_item_values;

                    //                 $x_general = addslashes(json_encode($x_general));
                    //                 if(DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")->num_rows){
                    //                     try{

                    //                 DB::connection('mysql_dynamic')->select("INSERT INTO trn_webadmin_history SET  itemid = '" . $new_item_values['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($new_item_values['vbarcode']) . "', type = 'Cost', oldamount = '" . ($old_item_values['dcostprice']) . "', newamount = '". ($new_item_values['dcostprice']) ."', general = '" . $x_general . "', source = 'PO', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                    //                     }
                    //                     catch (Exception $e) {
                    //                         $this->log->write($e);
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                    //update child item dcostprice,npack and nunitcost

                    //update into mst_itempackdetail
                    if($isParentCheck['vitemtype'] == 'Lot Matrix'){
                            
                        if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                            $lot_child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentmasterid= '". (int)($item['vitemid']) ."' ");
                            $lot_child_items = array_map(function ($value) {
                                return (array)$value;
                            }, $lot_child_items);
                            
                            if(count($lot_child_items) > 0){ 
                                foreach($lot_child_items as $chi){
                                    $pack_lot_child_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid= '". (int)($chi['iitemid']) ."' ");
                                    //====converting object data into array=====
                                    $pack_lot_child_item = array_map(function ($value) {
                                        return (array)$value;
                                    }, $pack_lot_child_item);
                                    
                                    if(count($pack_lot_child_item) > 0){
                                        foreach ($pack_lot_child_item as $k => $v) {
                                            $parent_nunitcost = $isParentCheck['nunitcost'];
                                            
                                            if($parent_nunitcost == ''){
                                                $parent_nunitcost = 0;
                                            }
                                            
                                            $parent_ipack = (int)$v['ipack'];
                                            $parent_npackprice = $v['npackprice'];
                                            
                                            $parent_npackcost = (int)$parent_ipack * $parent_nunitcost;
                                            
                                            $parent_npackmargin = $parent_npackprice - $parent_npackcost;
                                            
                                            if($parent_npackprice == 0){
                                                $parent_npackprice = 1;
                                            }
                                            
                                            if($parent_npackmargin > 0){
                                                $parent_npackmargin = $parent_npackmargin;
                                            }else{
                                                $parent_npackmargin = 0;
                                            }
                                            
                                            $parent_npackmargin = (($parent_npackmargin/$parent_npackprice) * 100);
                                            $parent_npackmargin = number_format((float)$parent_npackmargin, 2, '.', '');
                                            
                                            DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `npackcost` = '" . $parent_npackcost . "',`nunitcost` = '" . $parent_nunitcost . "',`npackmargin` = '" . $parent_npackmargin . "' WHERE idetid='". (int)($v['idetid']) ."'");
                                        }
                                    }
                                }
                            }
                        }
                        
                        $itempackexists = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE vbarcode='". ($isParentCheck['vbarcode']) ."' AND iitemid='". (int)$isParentCheck['iitemid'] ."'");
                        //====converting object data into array=====
                        $itempackexists = array_map(function ($value) {
                            return (array)$value;
                        }, $itempackexists);
                        
                        if(count($itempackexists) > 0){

                            foreach($itempackexists as $itempackexist){

                                $nunitcost = $isParentCheck['nunitcost'];
                                if($nunitcost == ''){
                                    $nunitcost = 0;
                                }

                                $ipack = $itempackexist['ipack'];
                                if($itempackexist['ipack'] == ''){
                                    $ipack = 0;
                                }

                                $npackprice = $itempackexist['npackprice'];
                                if($itempackexist['npackprice'] == ''){
                                    $npackprice = 0;
                                }

                                $npackcost = (int)$ipack * $nunitcost;
                                $npackmargin = $npackprice - $npackcost;

                                if($npackprice == 0){
                                    $npackprice = 1;
                                }

                                if($npackmargin > 0){
                                    $npackmargin = $npackmargin;
                                }else{
                                    $npackmargin = 0;
                                }

                                $npackmargin = (($npackmargin/$npackprice) * 100);
                                $npackmargin = number_format((float)$npackmargin, 2, '.', '');


                                DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE idetid='". (int)$itempackexist['idetid'] ."'");
                            }
                        }
                        
                    }
                    //update into mst_itempackdetail
                        
                    //POtoWarehouse order type
                    if($data['receive_po'] == 'POtoWarehouse'){
                        
                        $trn_data = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseitems WHERE vvendorid='" .($data['vvendorid']). "' AND vtransfertype='" .($data['receive_po']). "' AND vbarcode='" .($item['vbarcode']). "' AND invoiceid='" .($data['vinvoiceno']). "'");
                       
                        $new_dreceivedate = date("Y-m-d", strtotime($data['dreceiveddate']));
                        
                        if(count($trn_data) > 0){
                            DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseitems SET  vwhcode = 'WH101', vitemname = '" . ($item['vitemname']) . "', dreceivedate = '" . ($new_dreceivedate) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = 'Open', vvendortype = 'Vendor', ntransferqty = '" . ($item['itotalunit']) . "', vsize = '" . ($item['vsize']) . "' WHERE vvendorid='" .($data['vvendorid']). "' AND vtransfertype='" .($data['receive_po']). "' AND vbarcode='" .($item['vbarcode']). "' AND invoiceid='" .($data['vinvoiceno']). "' ");
                        }else{
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_warehouseitems SET  vwhcode = 'WH101',`invoiceid` = '" . ($data['vinvoiceno']) . "', vvendorid = '" . (int)($data['vvendorid']) . "',`dreceivedate` = '" . ($new_dreceivedate) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = 'Open', vvendortype = 'Vendor', vtransfertype = '" . ($data['receive_po']) . "', ntransferqty = '" . ($item['itotalunit']) . "', vsize = '" . ($item['vsize']) . "',SID = '" . (int)(session()->get('sid'))."'");
                        }
                        
                        $trn_qoh_data = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseqoh WHERE ivendorid='" .(int)($data['vvendorid']). "' AND vbarcode='" .($item['vbarcode']). "'");
                        
                        if($item['nordqty'] != '0.00' || $item['nordqty'] != '0'){
                            if(count($trn_qoh_data) > 0){
                                DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseqoh SET  npack = '" . ($item['npackqty']) . "', onhandcaseqty =onhandcaseqty + '" . ($item['nordqty']) . "' WHERE ivendorid='" .(int)($data['vvendorid']). "' AND vbarcode='" .($item['vbarcode']). "'");
                            }else{
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_warehouseqoh SET  ivendorid = '" . (int)($data['vvendorid']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', npack = '" . ($item['npackqty']) . "', onhandcaseqty = '" . ($item['nordqty']) . "',SID = '" . (int)(session()->get('sid'))."'");
                            }
                        }
                        
                    }
                    //POtoWarehouse order type
                    
                }
                
                catch (QueryException $e) {
                    // other mysql exception (not duplicate key entry)
                    
                    $error['error'] = $e->getMessage();
                    //email
                    $not_updated_items[] = 'iitemid:'. $item['vitemid'] .'  Error:'.$e->getMessage();
                    //email 
                    return $error; 
                }
                // catch (\Exception $e) {
                //     // not a MySQL exception
                   
                //     $error['error'] = $e->getMessage();
                //     //email
                //     $not_updated_items[] = 'iitemid:'. $item['vitemid'] .'  Error:'.$e->getMessage();
                //     //email 
                //     return $error; 
                // }
            }
            
            //email
            
                if(count($not_updated_items) > 0){
                    $send_arr = array();
                    $send_arr['store_id'] = session()->get('sid');

                    if(isset($data['iroid'])){
                        $send_arr['PO_id'] = $data['iroid'];
                    }else{
                        $po_last_id = DB::connection('mysql_dynamic')->select("SELECT iroid FROM trn_receivingorder ORDER BY iroid DESC");
                        $po_last_id = isset($po_last_id[0])?(array)$po_last_id[0]:[];
                        
                        $send_arr['PO_id'] = $po_last_id['iroid'];
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
            
            fclose($fp);
        }
        $success['success'] = 'Successfully Saved/Received Items';
        return $success;
    }
    
    public function deleteItemReceive($data = array()) 
    {
        $success =array();
        $error =array();
        
        if(isset($data) && count($data) > 0){
            
            foreach($data as $value){
                try {
                    
                    $exist_ipodetid = DB::connection('mysql_dynamic')->select("SELECT `irodetid` FROM  trn_receivingorderdetail WHERE irodetid='" . (int)$value . "' ");
                    
                    if(count($exist_ipodetid) > 0){
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_receivingorderdetail',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)(session()->get('sid'))."'");
                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorderdetail WHERE irodetid='" . (int)$value . "'");
                    }
                    
                }
                
                catch (QueryException $e) {
                    // other mysql exception (not duplicate key entry)
                    
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                catch (\Exception $e) {
                    // not a MySQL exception
                   
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
            }  
        }
        $success['success'] = 'Successfully Deleted Receiving Order Item';
        return $success;
    }
    
   public function editReceivingOrder($data = array(), $close = null, $ordertype) 
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
                    $vvendorname = addslashes($data['vvendorname']);
                    // $vordertitle = addslashes($data['vordertitle']);
                    $update_po_query = "UPDATE trn_receivingorder SET  vinvoiceno = '" . ($data['vinvoiceno']) . "',vrefnumber = '" . ($data['vinvoiceno']) . "',dcreatedate = '" . $dcreatedate . "', vponumber = '" . ($data['vponumber']) . "',`dreceiveddate` = '" . $dreceiveddate . "', estatus = '" . $close . "', vvendorid = '" . ($data['vvendorid']) . "', vvendorname = '" . ($vvendorname) . "', vvendoraddress1 = '" . ($data['vvendoraddress1']) . "', vvendoraddress2 = '" . ($data['vvendoraddress2']) . "', vvendorstate = '" . ($data['vvendorstate']) . "', vvendorzip = '" . ($data['vvendorzip']) . "', nsubtotal = '" . ($data['nsubtotal']) . "',ntaxtotal = '" . ($data['ntaxtotal']) . "',nfreightcharge = '" . ($data['nfreightcharge']) . "',ndeposittotal = '" . ($data['ndeposittotal']) . "',nfuelcharge = '" . ($data['nfuelcharge']) . "',ndeliverycharge = '" . ($data['ndeliverycharge']) . "',nreturntotal = '" . ($data['nreturntotal']) . "',ndiscountamt = '" . ($data['ndiscountamt']) . "',nripsamt = '" . ($data['nripsamt']) . "',nnettotal = '" . ($data['nnettotal']) . "', vordertype = '" . $ordertype . "' WHERE iroid='". (int)($data['iroid']) ."'";
                    
                    DB::connection('mysql_dynamic')->update($update_po_query);
                    
                    $iroid = (int)($data['iroid']);
                    $trn_receivingorder_id = $iroid;
                    
                    $file_path = storage_path("logs/receiving_orders/open_receiving_orders.csv");
                    
                    $fp = fopen($file_path, 'a');
                    
                    $sid = (int)(session()->get('sid'));
                    
                    if($sid == 100247 || $sid == 100636 || $sid == 100154 ){
                        
                        $text = PHP_EOL."========STORE(".$sid.") START NEW INVOICE(".$data['vinvoiceno'].") Date: ".date('Y-m-d')." ".date('H:i:s')." (SAVE)Not Receive=========".PHP_EOL;
                        
                        fwrite($fp, $text);
                        
                        // fwrite($fp,$log_present."\n");
                        // fclose($fp);
                    }
                    
                    if(isset($data['items']) && count($data['items']) > 0){
                        
                        // $data['items'] = array_reverse($data['items']);
						
						$riptotalamount =0;
                            
						foreach ($data['items'] as $key => $item) {
                            $query = "SELECT `irodetid` FROM  trn_receivingorderdetail WHERE irodetid='" . (int)($item['irodetid']) . "' ";
                            
                            $ipodetid_ids = DB::connection('mysql_dynamic')->select($query);							
                            
							if(count($ipodetid_ids) > 0){
                                
                                $update_pod_query = "UPDATE trn_receivingorderdetail SET  iroid = '" . (int)$iroid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . ($item['nordunitprice']) . "', po_last_costprice = '" . ($item['po_last_costprice']) . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', vsize = '" . ($item['vsize']) . "', po_order_by = '" . ($item['po_order_by']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npackqty']) . "', itotalunit = '" . ($item['itotalunit']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost']) . "',nripamount= '" . ($item['nripamount']) . "' WHERE irodetid='" . (int)($item['irodetid']) . "' ";
                               
                                DB::connection('mysql_dynamic')->update($update_pod_query);
                            }else{
                                
                                // echo $insert_pod_query = "INSERT INTO trn_receivingorderdetail SET  iroid = '" . (int)$iroid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . ($item['nordunitprice']) . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', vsize = '" . ($item['vsize']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npackqty']) . "', itotalunit = '" . ($item['itotalunit']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost']) . "',SID = '" . (int)(session()->get('sid'))."',nripamount= '" . ($item['nripamount']) . "'";
                                
                                $isItemExist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                                $isItemExist = isset($isItemExist[0])?(array)$isItemExist[0]:[];
                                
                                $insert_pod_query = "INSERT INTO trn_receivingorderdetail SET  iroid = '" . (int)$iroid . "',vitemid = '" . ($item['vitemid']) . "',nordunitprice = '" . ($item['nordunitprice']) . "', po_last_costprice = '" . ($isItemExist['last_costprice']) . "', po_new_costprice = '" . ($isItemExist['new_costprice']) . "', vunitcode = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', vsize = '" . ($item['vsize']) . "', po_order_by = '" . ($item['po_order_by']) . "', nordqty = '" . ($item['nordqty']) . "', npackqty = '" . ($item['npackqty']) . "', itotalunit = '" . ($item['itotalunit']) . "', nordextprice = '" . ($item['nordextprice']) . "', nunitcost = '" . ($item['nunitcost']) . "',SID = '" . (int)(session()->get('sid'))."',nripamount= '" . ($item['nripamount']) . "'";                                
                                
                                DB::connection('mysql_dynamic')->insert($insert_pod_query);
                            }
							
							$riptotalamount=$riptotalamount+$item['nripamount'];
                                
                            // Price Change
                                
                            $isItemExist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$item['vitemid'] ."'");
                            $isItemExist = isset($isItemExist[0])?(array)$isItemExist[0]:[];
                            
                            if($sid == 100247 || $sid == 100636 || $sid == 100154 ){
                                $text = "(Item Name)".$isItemExist['vitemname'].", (SKU)".$isItemExist['vbarcode'].", (QoH Before Receiving)".$isItemExist['iqtyonhand'].", (Qty Ordered)".$item['nordqty'].", (npack)".$item['npackqty']." ".PHP_EOL;
                                fwrite($fp, $text);
                            }
							
                            if($item['dunitprice'] != $isItemExist['dunitprice']){
                                
                                $sql_mst_item = "UPDATE mst_item SET dunitprice = '" . ($item['dunitprice']) . "' WHERE iitemid='".(int)$item['vitemid']."'";
                                DB::connection('mysql_dynamic')->update($sql_mst_item);
                                
                                if($isItemExist['vitemtype'] == 'Lottery'){
                                    $type_name = 'POPriceLott';
                                }else if($isItemExist['vitemtype'] == 'Kiosk'){
                                    $type_name = 'POPriceKio';
                                }else if($isItemExist['vitemtype'] == 'Lot Matrix'){
                                    $type_name = 'POPriceLot';
                                }else if($isItemExist['vitemtype'] == 'Gasoline'){
                                    $type_name = 'POPriceGas';
                                }else{
                                    $type_name = 'POPriceStd';
                                }

                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $item['vitemid'] . "',vbarcode = '" . ($isItemExist['vbarcode']) . "', vtype = '". $type_name ."', noldamt = '" . ($isItemExist['dunitprice']) . "', nnewamt = '" . ($item['dunitprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");

                                //trn_webadmin_history
                                $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                                
                                if(count($trn_webadmin_history)){
                                    $old_item_values = $isItemExist;
                                    unset($old_item_values['itemimage']);
    
                                    $x_general = new \stdClass();
                                    $x_general->trn_receivingorder_id = $trn_receivingorder_id;
                                    $x_general->current_po_item_values = $item;
                                    $x_general->old_item_values = $old_item_values;
                                    $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($item['vitemid']) ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                    
                                    unset($new_item_values['itemimage']);
                                    $x_general->new_item_values = $new_item_values;
    
                                    $x_general = addslashes(json_encode($x_general));
                                    try{
    
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $item['vitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($isItemExist['vbarcode']) . "', type = 'Price', oldamount = '" . ($isItemExist['dunitprice']) . "', newamount = '". ($item['dunitprice']) ."', general = '" . $x_general . "', source = 'PO', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                    }
                                    catch (\Exception $e) {
                                          Log::error($e);
                                    }
                                }
                                //trn_webadmin_history
                                    
                                if(($isItemExist['vitemtype']) == 'Lot Matrix'){
                                    
                                    $itempackexist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE vbarcode='". ($isItemExist['vbarcode']) ."' AND iitemid='". (int)$isItemExist['iitemid'] ."' AND iparentid=1");
                                    $itempackexist = isset($itempackexist[0])?(array)$itempackexist[0]:[];
                                    
                                    if(count($itempackexist) > 0){

                                        $vpackname = $itempackexist['vpackname'];
                                        $vdesc = $itempackexist['vdesc'];

                                        $nunitcost = $itempackexist['nunitcost'];
                                        if($nunitcost == ''){
                                            $nunitcost = 0;
                                        }

                                        $ipack = $itempackexist['ipack'];
                                        if($itempackexist['ipack'] == ''){
                                            $ipack = 0;
                                        }

                                        $npackprice = ($item['dunitprice']);
                                        if(($item['dunitprice']) == ''){
                                            $npackprice = 0;
                                        }

                                        $npackcost = (int)$ipack * $nunitcost;
                                        $iparentid = 1;
                                        $npackmargin = $npackprice - $npackcost;

                                        if($npackprice == 0){
                                            $npackprice = 1;
                                        }

                                        if($npackmargin > 0){
                                            $npackmargin = $npackmargin;
                                        }else{
                                            $npackmargin = 0;
                                        }

                                        $npackmargin = (($npackmargin/$npackprice) * 100);
                                        $npackmargin = number_format((float)$npackmargin, 2, '.', '');


                                        DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='". ($isItemExist['vbarcode']) ."' AND iitemid='". (int)$isItemExist['iitemid'] ."' AND iparentid=1");
                                    }else{

                                        $vpackname = 'Case';
                                        $vdesc = 'Case';

                                        $nunitcost = ($isItemExist['nunitcost']);
                                        if($nunitcost == ''){
                                            $nunitcost = 0;
                                        }

                                        $ipack = ($isItemExist['nsellunit']);
                                        if(($isItemExist['nsellunit']) == ''){
                                            $ipack = 0;
                                        }

                                        $npackprice = ($item['dunitprice']);
                                        if(($item['dunitprice']) == ''){
                                            $npackprice = 0;
                                        }

                                        $npackcost = (int)$ipack * $nunitcost;
                                        $iparentid = 1;
                                        $npackmargin = $npackprice - $npackcost;

                                        if($npackprice == 0){
                                            $npackprice = 1;
                                        }

                                        if($npackmargin > 0){
                                            $npackmargin = $npackmargin;
                                        }else{
                                            $npackmargin = 0;
                                        }

                                        $npackmargin = (($npackmargin/$npackprice) * 100);
                                        $npackmargin = number_format((float)$npackmargin, 2, '.', '');

                                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itempackdetail SET  iitemid = '" . (int)$iitemid . "',`vbarcode` = '" . ($isItemExist['vbarcode']) . "',`vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`nunitcost` = '" . $nunitcost . "',`ipack` = '" . (int)$ipack . "',`iparentid` = '" . (int)$iparentid . "',`npackcost` = '" . $npackcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "', SID = '" . (int)(session()->get('sid')) . "'");
                                    }
                                    
                                }
                                
                            }
                                
                            // Price Change
                            
                        }
						
						$rip_row_count = DB::connection('mysql_dynamic')->select("SELECT id, receivedtotalamt FROM  trn_rip_header WHERE ponumber='".($data['vinvoiceno'])."'");	
						$rip_row_count = isset($rip_row_count[0])?(array)$rip_row_count[0]:[];
						
						if(count($rip_row_count) > 0)
						{
							$pendingtotalamt = $riptotalamount - $rip_row_count['receivedtotalamt']; 
							$sql_rip= "UPDATE trn_rip_header SET ponumber = '" . ($data['vinvoiceno']) . "', vendorid = '" . ($data['vvendorid']) . "', riptotal = '" . ($riptotalamount) . "', receivedtotalamt = '0.00', pendingtotalamt = '" . $pendingtotalamt . "',SID = '" . (int)(session()->get('sid'))."' WHERE id='".$rip_row_count['id']."'";
							DB::connection('mysql_dynamic')->update($sql_rip);
							
						}else{
							if($riptotalamount > 0)
							{
								$sql_rip= "INSERT INTO trn_rip_header SET ponumber = '" . ($data['vinvoiceno']) . "', vendorid = '" . ($data['vvendorid']) . "', riptotal = '" . ($riptotalamount) . "', receivedtotalamt = '0.00', pendingtotalamt = '" . ($riptotalamount) . "',SID = '" . (int)(session()->get('sid'))."'";
								DB::connection('mysql_dynamic')->insert($sql_rip);
							}
						}							
                    }
                    
                    fclose($fp);
                }
                
                catch (QueryException $e) {
                    
                    $error['error'] = $e->getMessage().'2'; 
                    return $error; 
                }
                // catch (\Exception $e) {
                //     // not a MySQL exception
                   
                //     $error['error'] = $e->getMessage().'3'; 
                //     return $error; 
                // }
        }
        
        $success['success'] = 'Successfully Updated Receiving Order';
        return $success;
    }
    
    public function importMissingItems($data = array()) 
    {
        $success =array();
        $error =array();
        
        if(isset($data) && count($data) > 0){
            
            try {
                foreach ($data as $key => $value) {
                    
                    $miss_i = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_missingitem WHERE imitemid= '". $value ."'");
                    $miss_i = isset($miss_i[0])?(array)$miss_i[0]:[];
                    
                    if(count($miss_i) > 0){
                        
                        $miss_items = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_missingitem WHERE vbarcode= '". $miss_i['vbarcode'] ."'");
                        
                        //====converting object data into array=====
                        $miss_items = array_map(function ($value) {
                            return (array)$value;
                        }, $miss_items);
                        
                        if(count($miss_items) > 0){
                            
                            foreach ($miss_items as $key => $miss_item) {
                                
                                $vbarcode = $miss_item['vbarcode'];
                                
                                $query1 = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemalias WHERE valiassku= '". $vbarcode ."'");
                                
                                if(count($query1) == 0){
                                    $query2 = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE vbarcode= '". $vbarcode ."'");
                                }
                                
                                if(count($query2) <= 0){
                                    
                                    //insert mst item
                                    $nsellunit = $miss_item['nsellunit'];
                                    if($nsellunit < 1){
                                        $nsellunit = 1;
                                    }
                                    
                                    $new_costprice = $miss_item['nunitcost'] * $nsellunit;
                                    $vageverify = 0;
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_item SET  vbarcode = '" . ($miss_item['vbarcode']) . "',vitemname = '" . ($miss_item['vitemname']) . "',vitemcode = '" . ($miss_item['vitemcode']) . "',vitemtype = '" . ($miss_item['vitemtype']) . "',vunitcode = '" . ($miss_item['vunitcode']) . "',vtax1 = '" . ($miss_item['vtax1']) . "',vcategorycode = '" . ($miss_item['vcatcode']) . "',vdepcode = '" . ($miss_item['vdepcode']) . "',vsuppliercode = '" . ($miss_item['vsuppcode']) . "',dunitprice = '" . ($miss_item['dunitprice']) . "',dcostprice = '" . ($miss_item['dcostprice']) . "', new_costprice = '".$new_costprice."', nunitcost = '" . ($miss_item['nunitcost']) . "',nsellunit = '" . ($nsellunit) . "',npack = '" . ($miss_item['npack']) . "',nonorderqty = '" . ($miss_item['norderqty']) . "', vageverify = '" .$vageverify. "', estatus = 'Active', SID = '" . (int)(session()->get('sid')) . "'");
                                    
                                    // $last_iitemid = $this->db2->getLastId();
                                    $return = DB::connection('mysql_dynamic')->select("SELECT iitemid FROM mst_item ORDER BY iitemid DESC LIMIT 1");
                                 
                                    $last_iitemid = $return[0]->iitemid;
                                    //insert mst item
                                    
                                    //insert vendor item code
                                    $dtI = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemvendor WHERE vvendoritemcode='". $miss_item['vvendoritemcode'] ."'");
                                    if(count($dtI) == 0){
                                        $mstItemVendorDto = array();
                                        $mstItemVendorDto['iitemid'] = $last_iitemid;
                                        $mstItemVendorDto['ivendorid'] = $miss_item['vsuppcode'];
                                        $mstItemVendorDto['vvendoritemcode'] = $miss_item['vvendoritemcode'];
                                        
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)($mstItemVendorDto['iitemid']) . "',`ivendorid` = '" . (int)($mstItemVendorDto['ivendorid']) . "',`vvendoritemcode` = '" . ($mstItemVendorDto['vvendoritemcode']) . "', SID = '" . (int)(session()->get('sid')) . "'");
                                    }
                                    //insert vendor item code
                                    
                                }else{
                                    // $return = DB::connection('mysql_dynamic')->select("SELECT iitemid FROM mst_item ORDER BY iitemid DESC LIMIT 1");
                               
                                    $last_iitemid = $query2[0]->iitemid;
                                }
                                
                                //insert trn purchaseorderdetail
                                $trnPurchaseOrderDetaildto = array();
                                $trnPurchaseOrderDetaildto['npackqty'] = (int)$miss_item['npack'];
                                $trnPurchaseOrderDetaildto['vbarcode'] = $miss_item['vbarcode'];
                                
                                $trnPurchaseOrderDetaildto['iroid'] = (int)$miss_item['iinvoiceid'];
                                $trnPurchaseOrderDetaildto['vitemid'] = (string)$last_iitemid;
                                $trnPurchaseOrderDetaildto['vitemname'] = $miss_item['vitemname'];
                                $trnPurchaseOrderDetaildto['vunitname'] = "Each";
                                $trnPurchaseOrderDetaildto['nordqty'] = $miss_item['norderqty'];
                                
                                $nsellunit = $miss_item['nsellunit'];
                                if($nsellunit < 1){
                                    $nsellunit = 1;
                                }
                                
                                $new_costprice = $miss_item['nunitcost'] * $nsellunit;
                                
                                $nCost = $miss_item['dcostprice'];
                                $unitcost = $miss_item['nunitcost'];
                                $itotalunit = (int)$miss_item['norderqty'] * (int)$miss_item['npack'];
                                $totAmt = (int)$miss_item['norderqty'] * $nCost;
                                
                                $trnPurchaseOrderDetaildto['nordunitprice'] = $nCost;
                                $trnPurchaseOrderDetaildto['nordextprice'] = $totAmt;
                                $trnPurchaseOrderDetaildto['nordtax'] = 0;
                                $trnPurchaseOrderDetaildto['nordtextprice'] = 0;
                                $trnPurchaseOrderDetaildto['vvendoritemcode'] = (string)$miss_item['vvendoritemcode'];
                                $trnPurchaseOrderDetaildto['nunitcost'] = $unitcost;
                                
                                $trnPurchaseOrderDetaildto['itotalunit'] = (int)$itotalunit;
                                $trnPurchaseOrderDetaildto['vsize'] = "";
                                
                                $exist_po = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_receivingorder WHERE iroid='". (int)($trnPurchaseOrderDetaildto['iroid']) ."'");
                                $exist_po = isset($exist_po[0])?(array)$exist_po[0]:[];
                                
                                if(count($exist_po) > 0 && $exist_po['estatus'] != 'Close'){
                                    
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_receivingorderdetail SET  iroid = '" . (int)($trnPurchaseOrderDetaildto['iroid']) . "',vitemid = '" . ($trnPurchaseOrderDetaildto['vitemid']) . "',npackqty = '" . ($trnPurchaseOrderDetaildto['npackqty']) . "',vbarcode = '" . ($trnPurchaseOrderDetaildto['vbarcode']) . "',vitemname = '" . ($trnPurchaseOrderDetaildto['vitemname']) . "',vunitname = '" . ($trnPurchaseOrderDetaildto['vunitname']) . "',nordqty = '" . ($trnPurchaseOrderDetaildto['nordqty']) . "',nordunitprice = '" . ($trnPurchaseOrderDetaildto['nordunitprice']) . "',nordextprice = '" . ($trnPurchaseOrderDetaildto['nordextprice']) . "',nordtax = '" . ($trnPurchaseOrderDetaildto['nordtax']) . "',nordtextprice = '" . ($trnPurchaseOrderDetaildto['nordtextprice']) . "',vvendoritemcode = '" . ($trnPurchaseOrderDetaildto['vvendoritemcode']) . "',nunitcost = '" . ($trnPurchaseOrderDetaildto['nunitcost']) . "',itotalunit = '" . ($trnPurchaseOrderDetaildto['itotalunit']) . "',vsize = '" . ($trnPurchaseOrderDetaildto['vsize']) . "', po_new_costprice = '".$new_costprice."',SID = '" . (int)(session()->get('sid'))."'");
                                    //===update total amount of receiving order ====
                                    $total_amount = DB::connection('mysql_dynamic')->select("SELECT  SUM(nordextprice) as total_amount FROM trn_receivingorderdetail WHERE iroid = '".(int)($trnPurchaseOrderDetaildto['iroid'])."' ");
                                    if($total_amount[0]->total_amount > 0 || $total_amount[0]->total_amount < 0){
                                        $totamount = $total_amount[0]->total_amount;
                                    }else{
                                        $totamount = 0;
                                    }
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  nsubtotal = '" . $totamount . "', nnettotal = '" . $totamount . "' WHERE iroid='". (int)($trnPurchaseOrderDetaildto['iroid']) ."'");
                                    
                                }
                                
                                //insert trn purchaseorderdetail
                                
                                
                                //delete item from missing item table
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_missingitem',`Action` = 'delete',`TableId` = '" . (int)$miss_item['imitemid'] . "',SID = '" . (int)(session()->get('sid'))."'");
                                
                                    DB::connection('mysql_dynamic')->statement("DELETE FROM mst_missingitem WHERE imitemid='" . (int)$miss_item['imitemid'] . "'");
                                //delete item from missing item table
                                
                            }
                            
                        }
                        
                    }
                    
                }
            }
            
            catch (QueryException $e) {
                // other mysql exception (not duplicate key entry)
                
                $error['error'] = $e->getMessage(); 
                return $error; 
            }
            // catch (\Exception $e) {
            //     // not a MySQL exception
               
            //     $error['error'] = $e->getMessage(); 
            //     return $error; 
            // }  
        }
        $success['success'] = 'Successfully Imported Missing Items';
        return $success;
    }
    
    public function checkcreateCategory()
    {
        $check = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_category WHERE vcategorycode = '3' ");
        
        if(empty($check) || $check < 1){
            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_category SET icategoryid = '3', vcategorycode = '3', vcategoryname = 'EDI ITEMS', dept_code = '3', estatus = 'Active', SID = '" . (int)(session()->get('sid'))."' ");
        }
    }
    
    public function checkcreateDepartment()
    {
        $check = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_department WHERE vdepcode = '3' ");
        
        if(empty($check) || $check < 1){
            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_department SET idepartmentid = '3', vdepcode = '3', vdepartmentname = 'EDI ITEMS', estatus = 'Active', SID = '" . (int)(session()->get('sid'))."' ");
        }
    }
    
    public function insertReceivingOrder($data = array()) 
    {
        $success =array();
        $error =array();
        
        date_default_timezone_set('US/Eastern');
        
        if(isset($data) && count($data) > 0){
            
            try {
                
                $query = new self;  
                $query->vinvoiceno = $data['vinvoiceno'];
                $query->vrefnumber = $data['vinvoiceno'];
                $query->dcreatedate = $data['dcreatedate']; 
                $query->vponumber = $data['vponumber'] ?? '';
                $query->vordertitle = $data['vordertitle'] ?? '';
                $query->vorderby = $data['vorderby'] ?? '';
                $query->vnotes = $data['vnotes'] ?? '';
                $query->vshipvia = $data['vshipvia'] ?? '';
                // $query->dreceiveddate = $dreceiveddate;
                $query->estatus = $data['estatus']; 
                $query->vconfirmby = $data['vconfirmby'] ?? ''; 
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
                $query->nfuelcharge = $data['nfuelcharge'] ?? '';
                $query->ndeliverycharge = $data['ndeliverycharge'] ?? '';
                $query->nreturntotal = $data['nreturntotal'] ?? '';
                $query->ndiscountamt = $data['ndiscountamt'] ?? '';
                $query->nripsamt = $data['nripsamt'] ?? '';
                $query->nnettotal = $data['nnettotal'] ?? ''; 
                // $query->vordertype = $ordertype;
                $query->SID = (int)(session()->get('sid'));
                
                $query->save();   
                $iroid = $query->iroid;
                
                    // $iroid = $this->db2->getLastId();
                
            }
            
            catch (QueryException $e) {
                // other mysql exception (not duplicate key entry)
                
                $error['error'] = $e->getMessage(); 
                return $error; 
            }
            // catch (\Exception $e) {
            //     // not a MySQL exception
               
            //     $error['error'] = $e->getMessage(); 
            //     return $error; 
            // }
        }
        
        return $iroid;
    }
    
    public function calculate_upc_check_digit($upc_code) 
    {
         $checkDigit = -1; // -1 == failure
         $upc = substr($upc_code,0,11);
         // send in a 11 or 12 digit upc code only
        if (strlen($upc) == 11 && strlen($upc_code) <= 12) { 
            $oddPositions = $upc[0] + $upc[2] + $upc[4] + $upc[6] + $upc[8] + $upc[10]; 
            $oddPositions *= 3; 
            $evenPositions= $upc[1] + $upc[3] + $upc[5] + $upc[7] + $upc[9]; 
            $sumEvenOdd = $oddPositions + $evenPositions; 
            $checkDigit = (10 - ($sumEvenOdd % 10)) % 10; 
        }
        return $checkDigit; 
    }
    
    public function getItemByBarCode($vCode) 
    {
        $sql = "SELECT * FROM mst_item WHERE vbarcode='". $vCode ."'";
        $query = DB::connection('mysql_dynamic')->select($sql);
        $query = isset($query[0])?(array)$query[0]:[];
        
        if(count($query) == 0){
            $query1 = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemalias WHERE valiassku= '". $vCode ."'");
            $query1 = isset($query1[0])?(array)$query1[0]:[];
            
            // $sql = "SELECT * FROM mst_item WHERE vbarcode='". $vCode ."'";
            // $query = DB::connection('mysql_dynamic')->select($sql);
            // $query = isset($query[0])?(array)$query[0]:[];
            if(count($query1)){
                return array('aliascodeAvilable');
            }
        }
        
        return $query;
    }
    
    public function updateBarcode($vCode,$old_vCode) 
    {
        //mst_item
        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vitemcode = '" . ($vCode) . "', vbarcode = '" . ($vCode) . "' WHERE vbarcode= '". ($old_vCode) ."'");
        //mst_item

        //mst_itemalias
        DB::connection('mysql_dynamic')->update("UPDATE mst_itemalias SET vitemcode = '" . ($vCode) . "', vsku = '" . ($vCode) . "' WHERE vsku= '". ($old_vCode) ."'");
        //mst_itemalias

        //mst_itempackdetail
        DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET vbarcode = '" . ($vCode) . "' WHERE vbarcode= '". ($old_vCode) ."'");
        //mst_itempackdetail

        //trn_quickitem
        DB::connection('mysql_dynamic')->update("UPDATE trn_quickitem SET vitemcode = '" . ($vCode) . "' WHERE vitemcode= '". ($old_vCode) ."'");
        //trn_quickitem

        //itemgroupdetail
        DB::connection('mysql_dynamic')->update("UPDATE itemgroupdetail SET vsku = '" . ($vCode) . "' WHERE vsku= '". ($old_vCode) ."'");
        //itemgroupdetail

        //mst_itemslabprice
        DB::connection('mysql_dynamic')->update("UPDATE mst_itemslabprice SET vsku = '" . ($vCode) . "' WHERE vsku= '". ($old_vCode) ."'");
        //mst_itemslabprice

        //trn_salesdetail
        DB::connection('mysql_dynamic')->update("UPDATE trn_salesdetail SET vitemcode = '" . ($vCode) . "' WHERE vitemcode= '". ($old_vCode) ."'");
        //trn_salesdetail

        //trn_receivingorderdetail
        DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorderdetail SET vbarcode = '" . ($vCode) . "' WHERE vbarcode= '". ($old_vCode) ."'");
        //trn_receivingorderdetail

        //trn_physicalinventorydetail
        DB::connection('mysql_dynamic')->update("UPDATE trn_physicalinventorydetail SET vbarcode = '" . ($vCode) . "' WHERE vbarcode= '". ($old_vCode) ."'");
        //trn_physicalinventorydetail

        //trn_warehouseitems
        DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseitems SET vbarcode = '" . ($vCode) . "' WHERE vbarcode= '". ($old_vCode) ."'");
        //trn_warehouseitems

        //trn_warehouseqoh
        DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseqoh SET vbarcode = '" . ($vCode) . "' WHERE vbarcode= '". ($old_vCode) ."'");
        //trn_warehouseqoh

        //trn_itempricecosthistory
        DB::connection('mysql_dynamic')->update("UPDATE trn_itempricecosthistory SET vbarcode = '" . ($vCode) . "' WHERE vbarcode= '". ($old_vCode) ."'");
        //trn_itempricecosthistory

        //trn_holditem
        DB::connection('mysql_dynamic')->update("UPDATE trn_holditem SET vitemcode = '" . ($vCode) . "' WHERE vitemcode= '". ($old_vCode) ."'");
        //trn_holditem

        //trn_salepricedetail
        DB::connection('mysql_dynamic')->update("UPDATE trn_salepricedetail SET vitemcode = '" . ($vCode) . "' WHERE vitemcode= '". ($old_vCode) ."'");
        //trn_salepricedetail
    }
    
    public function updateItemStatus($iitemid) 
    {
        
        if(isset($iitemid) && !empty($iitemid)){
            
            $item_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid = '" . (int)$iitemid . "'");
            $item_exist = isset($item_exist[0])?(array)$item_exist[0]:[];
            
            if(count($item_exist) > 0 && $item_exist['estatus'] == 'Inactive'){
                DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  `estatus` = 'Active' WHERE iitemid='". (int)$iitemid ."'");
            }
            
        }
        
        return true;
        
    }
    
    public function insertItemVendor($data = array()) 
    {
        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)($data['iitemid']) . "',`ivendorid` = '" . (int)($data['ivendorid']) . "',`vvendoritemcode` = '" . ($data['vvendoritemcode']) . "', SID = '" . (int)(session()->get('sid')) . "'");
        
        return 'Successfully added vendor item code';
    }
    
    public function InsertPurchaseDetail($data = array()) 
    {
        $mst_item = DB::connection('mysql_dynamic')->select("SELECT new_costprice FROM mst_item WHERE iitemid = '".$data['vitemid']."' ");
        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_receivingorderdetail SET  iroid = '" . (int)($data['iroid']) . "',vitemid = '" . ($data['vitemid']) . "',npackqty = '" . ($data['npackqty']) . "',vbarcode = '" . ($data['vbarcode']) . "',vitemname = '" . ($data['vitemname']) . "',vunitname = '" . ($data['vunitname']) . "',nordqty = '" . ($data['nordqty']) . "',nordunitprice = '" . ($data['nordunitprice']) . "',nordextprice = '" . ($data['nordextprice']) . "',nordtax = '" . ($data['nordtax']) . "',nordtextprice = '" . ($data['nordtextprice']) . "',vvendoritemcode = '" . ($data['vvendoritemcode']) . "',nunitcost = '" . ($data['nunitcost']) . "',itotalunit = '" . ($data['itotalunit']) . "',vsize = '" . ($data['vsize']) . "', po_new_costprice = '".$mst_item[0]->new_costprice."',SID = '" . (int)(session()->get('sid'))."'");
        
        return 'Successfully added items';
    }
    
    public function createMissingitem($data = array())
    {   
        $sql= "INSERT INTO mst_missingitem SET  norderqty = '" . ($data['norderqty']) . "',vvendoritemcode = '" . ($data['vvendoritemcode']) . "',iinvoiceid = '" . ($data['iinvoiceid']) . "',vbarcode = '" . ($data['vbarcode']) . "',vitemname = '" . ($data['vitemname']) . "',nsellunit = '" . ($data['nsellunit']) . "',dcostprice = '" . ($data['dcostprice']) . "',dunitprice = '" . ($data['dunitprice']) . "',vcatcode = '" . ($data['vcatcode']) . "',vdepcode = '" . ($data['vdepcode']) . "',vsuppcode = '" . ($data['vsuppcode']) . "',vtax1 = '" . ($data['vtax1']) . "',vitemtype = '" . ($data['vitemtype']) . "',npack = '" . ($data['npack']) . "',vitemcode = '" . ($data['vitemcode']) . "',vunitcode = '" . ($data['vunitcode']) . "',nunitcost = '" . ($data['nunitcost']) . "',SID = '" . (int)(session()->get('sid'))."'";
        
        DB::connection('mysql_dynamic')->insert($sql);
            
        return 'Successfully added missing items';
    }
    
    public function updatePurchaseOrderReturnTotal($nReturnTotal,$poid)
    {
        
        DB::connection('mysql_dynamic')->update("UPDATE trn_receivingorder SET  nreturntotal = '" . $nReturnTotal . "', nnettotal = nnettotal - " . $nReturnTotal . "  WHERE iroid='". (int)$poid ."'");
            
        return 'Successfully updated return total';
    }
    
    public function deletePurchaseOrder($data) {
        
        if(isset($data) && count($data) > 0){

            foreach ($data as $key => $value) {
                $trn_receivingorder = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_receivingorder WHERE iroid = '" . ($value) . "'");
                
                if(count($trn_receivingorder) > 0){
                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_receivingorder',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)(session()->get('sid'))."'");
                    
                    $trn_receivingorderdetail = DB::connection('mysql_dynamic')->select("SELECT irodetid FROM trn_receivingorderdetail WHERE iroid = '" . ($value) . "'");
                    $trn_receivingorderdetail = array_map(function ($value) {
                        return (array)$value;
                    }, $trn_receivingorderdetail);
                    
                    if(count($trn_receivingorderdetail)){
                        foreach ($trn_receivingorderdetail as $k => $v) {
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_receivingorderdetail',`Action` = 'delete',`TableId` = '" . (int)$v['irodetid'] . "',SID = '" . (int)(session()->get('sid'))."'");
                            
                            DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorderdetail WHERE irodetid='" . (int)$v['irodetid'] . "'");
                        }
                    }
                    
                    DB::connection('mysql_dynamic')->delete("DELETE FROM trn_receivingorder WHERE iroid='" . (int)$value . "'");
                    
                }
                
            }  
            
        }

        $return['success'] = 'RO Deleted Successfully';

        return $return;

    }
    
    public function getSearchItemAll($search_items,$ivendorid,$pre_items_id)
    {   
        $condition = "WHERE mi.estatus='Active'";
            
        if (isset($search_items['item_name']) && !empty(trim($search_items['item_name']))) {
            $search = $search_items['item_name'];
            $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
        }

        if (isset($search_items['sku']) && !empty(trim($search_items['sku']))) {
            $search = $search_items['sku'];
            $condition .= " AND (mi.vbarcode LIKE  '%" . $search . "%' OR mia.valiassku LIKE '%" .$search. "%')";
        }
        
        if (isset($search_items['size']) && !empty($search_items['size'])) {
            $search = $search_items['size'];
            if ($search != 'all') {
                $condition .= " AND mi.vsize LIKE  '%" . $search . "%'";
            }
        }
        
        // if (isset($search_items['dept_code']) && !empty(trim($search_items['dept_code']))) {
        //     $search = $search_items['dept_code'];
        //     if ($search != 'all') {
        //         $condition .= " AND mi.vdepcode ='" . $search . "'";
        //     }
        // }

        if (isset($search_items['dept_code']) && !empty(trim($search_items['dept_code']))) {
            $search = $search_items['dept_code'];
            if ($search != 'all') {
                $condition .= " AND md.vdepartmentname LIKE  '%" . $search . "%'";
            }
        }

        // if (isset($search_items['category_code']) && !empty(trim($search_items['category_code']))) {
        //     $search = $search_items['category_code'];
        //     if ($search != 'All' && $search != 'all') {
        //         $condition .= " AND mi.vcategorycode ='" . $search . "'";
        //     }
        // }

        if (isset($search_items['category_code']) && !empty(trim($search_items['category_code']))) {
            $search = $search_items['category_code'];
            if ($search != 'All' && $search != 'all') {
                $condition .= " AND mc.vcategoryname LIKE  '%" . $search . "%'";
            }
        }
        
        // if (isset($search_items['supplier_code']) && !empty($search_items['supplier_code'])) {
        //     $search = $search_items['supplier_code'];
        //     if ($search != 'all') {
        //         $condition .= " AND mi.vsuppliercode ='" . $search . "'";
        //     }
        // }

        if (isset($search_items['supplier_code']) && !empty($search_items['supplier_code'])) {
            $search = $search_items['supplier_code'];
            if ($search != 'all') {
                $condition .= " AND msupp.vcompanyname LIKE  '%" . $search . "%'";
            }
        }
        
        // if (isset($search_items['price_select_by']) && !empty(trim($search_items['price_select_by'])) && $search_items['select_by_value1'] != null && !empty($search_items['select_by_value1'])) {
        //     $search_conditions = $search_items['price_select_by'];
            
        //     $select_by_value1 = $search_items['select_by_value1'];
        //     $select_by_value2 = $search_items['select_by_value2'];
            
        //     if ($search_conditions == 'greater' && isset($select_by_value1)) {
        //         $condition .= " AND mi.dunitprice > $select_by_value1 ";
        //     } elseif ($search_conditions == 'less' && isset($select_by_value1)) {
        //         $condition .= " AND mi.dunitprice < $select_by_value1 ";
        //     } elseif ($search_conditions == 'equal' && isset($select_by_value1)) {
        //         $condition .= " AND mi.dunitprice = $select_by_value1 ";
        //     } elseif ($search_conditions == 'between' && isset($select_by_value1) && isset($select_by_value2)) {

        //         $condition .= " AND mi.dunitprice BETWEEN $select_by_value1 AND $select_by_value2 ";
        //     }
        // }
        
        if (isset($search_items['price']) && !empty(trim($search_items['price'])) && $search_items['price'] != null) {
            $search = $search_items['price'];
            if (isset($search)) {
                $condition .= " AND mi.dunitprice = $search ";
            } 
        }

        if(!empty(trim($search_items['item_name'])) || !empty(trim($search_items['sku'])) || $search_items['size'] != 'all' || $search_items['dept_code'] != 'all' || $search_items['category_code'] != 'All' || $search_items['supplier_code'] != 'all' || !empty($search_items['price'])){
            if(count($pre_items_id) > 0){
                $sort='';
                $pre_items_id = implode(',', $pre_items_id);
                
                if(isset($search_items['item_name']) && !empty($search_items['item_name'])){
                    $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '".$search_items['item_name']."' ");
                    if(isset($parentid) && !empty($parentid)){
                         $parentid_data=$parentid[0]->parentid;
                         $child_data=$parentid[0]->iitemid;
                         $isparentchild=$parentid[0]->isparentchild;
                          if($parentid_data>0 && $isparentchild == 1)
                             {
                             $sort = " mi.parentid DESC";
                             }
                             else{
                              $sort = " mi.isparentchild DESC";   
                             }
                        
                         $query = "SELECT DISTINCT mi.iitemid,mi.vitemcode, md.vdepartmentname, mc.vcategoryname,msupp.vcompanyname,
                         case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                         when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                         mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,
                         mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,
                         case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                         (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                         
                         mi.new_costprice FROM mst_item as mi  
                         LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode)
                         LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
                         LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
                         LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                         LEFT JOIN mst_item p ON mi.parentid = p.iitemid 
                         " .$condition." OR mi.iitemid=$parentid_data OR mi.parentid=$child_data ORDER BY ". $sort;
                    }
                    else{
                        $query = "SELECT DISTINCT mi.iitemid,mi.vitemcode, md.vdepartmentname, mc.vcategoryname,msupp.vcompanyname,
                        case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                        when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                        mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,
                        mi.dunitprice,mi.nunitcost,mi.npack,
                        case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                        (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                        mi.new_costprice FROM mst_item as mi  
                        LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
                        LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
                        LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
                        LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                        LEFT JOIN mst_item p ON mi.parentid = p.iitemid 
                        " .$condition." AND mi.iitemid NOT IN(".$pre_items_id.")";
                    }
                    
                }else{
                    $query = "SELECT DISTINCT mi.iitemid,mi.vitemcode, md.vdepartmentname, mc.vcategoryname,msupp.vcompanyname,
                        case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                        when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                        mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,
                        mi.dunitprice,mi.nunitcost,mi.npack,
                        case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                        (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                        mi.new_costprice FROM mst_item as mi  
                        LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
                        LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
                        LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
                        LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                        LEFT JOIN mst_item p ON mi.parentid = p.iitemid 
                        " .$condition." AND mi.iitemid NOT IN(".$pre_items_id.")";
                }
                         
            }else{
                
                if(isset($search_items['item_name']) && !empty($search_items['item_name'])){
                    $parentid=DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item mi where mi.vitemname = '".$search_items['item_name']."' ");
                    if(isset($parentid) && !empty($parentid)){
                         $parentid_data=$parentid[0]->parentid;
                         $child_data=$parentid[0]->iitemid;
                         $isparentchild=$parentid[0]->isparentchild;
                         
                          if($parentid_data>0 && $isparentchild == 1)
                             {
                                $sort = " mi.parentid DESC";
                             }
                             else{
                                $sort = " mi.isparentchild DESC";   
                             }
                        $query = "SELECT DISTINCT mi.iitemid,mi.vitemcode, md.vdepartmentname, mc.vcategoryname,msupp.vcompanyname,
                            case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                            when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                            mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,
                            mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,
                            case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                            (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                            mi.new_costprice FROM mst_item as mi  
                            LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
                            LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
                            LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
                            LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode)
                            LEFT JOIN mst_item p ON mi.parentid = p.iitemid  
                            " .$condition." OR mi.iitemid=$parentid_data OR mi.parentid=$child_data ORDER BY ". $sort;
                     }
                     else{
                        $query = "SELECT DISTINCT mi.iitemid,mi.vitemcode, md.vdepartmentname, mc.vcategoryname,msupp.vcompanyname,
                        case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                         when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                        mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,
                         case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                         (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                        mi.new_costprice FROM mst_item as mi  
                        LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
                        LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
                        LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
                        LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
                        LEFT JOIN mst_item p ON mi.parentid = p.iitemid
                        " .$condition;
                     }
                }else{
                    $query = "SELECT DISTINCT(mi.iitemid),mi.vitemcode, md.vdepartmentname, mc.vcategoryname,msupp.vcompanyname,
                        case mi.isparentchild when 0 then trim(mi.vitemname)  when 1 then Concat(mi.vitemname,' [Child]')
                         when 2 then  Concat(mi.vitemname,' [Parent]') end   as vitemname,
                        mi.vbarcode,mi.nsellunit,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.nunitcost,mi.npack,
                         case mi.isparentchild  when 1 then   Mod(p.IQTYONHAND,p.NPACK)  else  
                         (Concat(cast(((mi.IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(mi.IQTYONHAND,mi.NPACK) ,')') )end as QOH,
                        mi.new_costprice FROM mst_item as mi  
                        LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) 
                        LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode)
                        LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode)
                        LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) 
                        LEFT JOIN mst_item p ON mi.parentid = p.iitemid
                        " .$condition;
                }
            }
            
            // echo $query; die;
            $run_query = DB::connection('mysql_dynamic')->select($query);
            
            return $run_query;
        }else{
            return array();
        }
    }
    
}

?>