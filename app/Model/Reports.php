<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
class Reports extends Model
{
    protected $connection = 'mysql_dynamic';

    //EOD REPORT SECTION STRAT-----------------------------------------------------------------EOD

    public function getEodReport($date = null){
       
        $sql=" Call rpt_EOD('".$date."')";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }

    public function getEodpaidout($date = null){
       
        $sql=" Call rp_eofpaidout('".$date."','".$date."', 'All','All',100000,'All')";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }

    public function getEodhourly($date = null){
       
        $sql="CALL rp_eofhourlysales('".$date."')";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }
    public function getEoddepartment($date = null){
       
        $sql="CALL rp_eofdepartment('".$date."')";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }
    public function getStore($date = null){
       
        $sql="SELECT * FROM mst_store";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }

    //EOD REPORT SECTION END-----------------------------------------------------------------EOD
    public function getEodshiftReport($batch_id = null){
       
        $sql=" Call rp_Zreport('".$batch_id."')";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }
    public function getEodshiftdepartment($batch_id = null){
       
        
        $sql="select vdepname as vdepatname, sum(a.ndebitqty) qty, sum(ifnull(nExtunitPrice,0)) as saleamount,
        sum(ifnull(nextcostprice,0)) as cost, (sum(ifnull(nExtunitPrice,0)) -  sum(ifnull(nextcostprice,0)))/sum(ifnull(nExtunitPrice,0)) gpp

        from trn_salesdetail a,trn_sales b
        where a.isalesid = b.isalesid 
        and  b.vtrntype='Transaction' 
        and  b.ibatchid ='".$batch_id."'
        group by vdepname";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }

    public function getTaxReport($p_start_date,$p_end_date){
        $sql = "CALL rp_taxcollection('".$p_start_date."','".$p_end_date."')";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;

    }
    
    public function getSalesReportuserid(){
        $sql = "select  distinct vterminalid from trn_sales";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;

    }

    // public function getSalesReport($request,$start_date,$end_date, $selectvalue,$amounttype,$amount,$registerid){
      
    //     $start_date_new = DateTime::createFromFormat('m-d-Y H:i', $start_date);
    //     $d1 = $start_date_new->format('Y-m-d H:i');
        

    //     $end_date_new = DateTime::createFromFormat('m-d-Y H:i', $end_date);
    //     $d2 = $end_date_new->format('Y-m-d H:i');

    //     $sql="SELECT dtrandate as dtrandate,vterminalid,concat('TRN',isalesid) as trnsalesid,isalesid,nnettotal,
    //     nchange,ntaxtotal,vtrntype ,replace(vtendertype ,'On Account', 'House Account') as tendername
    //     FROM trn_sales 
    //     WHERE date_format(dtrandate,'%Y-%m-%d %H:%i') >= '".$d1."' and date_format(dtrandate,'%Y-%m-%d %H:%i') 
    //     <=  '".$d2."'AND 
    //     vtrntype='Transaction'";

        

    //     if(isset($selectvalue) && $selectvalue!="All")
    //     {
            
    //         $sql .= "AND vtendertype Like '%".$selectvalue."%'";
            
    //     }
    //     if(isset($amounttype))
    //     {
    //         if($amounttype=='less'){
    //             $sql .= "AND nnettotal < '".$amount."'";
                
    //         }
    //       if($amounttype=='greater'){
    //             $sql .= "AND nnettotal > '".$amount."'";
                
    //         }
    //       if($amounttype=='equal'){
    //             $sql .= "AND nnettotal = '".$amount."'";
                
    //         }
    //     }
    //     if(isset($registerid) && $registerid!="All")
    //     {
    //         $sql .= "AND vterminalid = '".$registerid."'";
            
    //     }
       

        
        
    //     $result = DB::connection('mysql_dynamic')->select($sql);
    //     //  return  $result; 
    //     $request_data=$request;
    //     $results = $this->arrayPaginator($result, $request_data);
    //     return $results;

    // }
    //for new filter option        sales transcation report start
    
   public function getSalesReport($request,$start_date,$end_date, $selectvalue,$amounttype,$amount,$registerid){
      
        $start_date_new = DateTime::createFromFormat('m-d-Y H:i', $start_date);
        $d1 = $start_date_new->format('Y-m-d H:i');
        

        $end_date_new = DateTime::createFromFormat('m-d-Y H:i', $end_date);
        $d2 = $end_date_new->format('Y-m-d H:i');

        $sql="SELECT DISTINCT s.isalesid, s.dtrandate as dtrandate,s.vterminalid,concat('TRN',s.isalesid) as trnsalesid,s.nnettotal,
        s.nchange,s.ntaxtotal,s.vtrntype ,replace(s.vtendertype ,'On Account', 'House Account') as tendername
        FROM trn_sales s join trn_salesdetail d on s.isalesid=d.isalesid
        WHERE date_format(dtrandate,'%Y-%m-%d %H:%i') >= '".$d1."' and date_format(dtrandate,'%Y-%m-%d %H:%i') 
        <=  '".$d2."'";
        
    
        if(isset($selectvalue) && $selectvalue!="All" &&  $selectvalue !='void'&&  $selectvalue !='Discount' &&  $selectvalue !='Price' &&  $selectvalue !='Coupon')
        {
           
           $sql .= "AND s.vtendertype Like '%".$selectvalue."%' ";
            
            
            
        }
        if(isset($selectvalue) && $selectvalue =='Discount'){
           
           $sql .= "AND s.ndiscountamt > 0  ";  
        }
        if(isset($selectvalue) && $selectvalue =='void') {
        
            $sql .= "AND s.vtrntype Like '%".$selectvalue."%'";
        }
        
        if(isset($amounttype) && $selectvalue=='Price')
        {
            if($amounttype=='less'){
                $sql .= "AND d.nunitprice < '".$amount."'";
                
            }
           if($amounttype=='greater'){
                $sql .= "AND d.nunitprice > '".$amount."'";
                
            }
          if($amounttype=='equal'){
                $sql .= "AND d.nunitprice = '".$amount."'";
                
            }
        }
        if(isset($amounttype) && $selectvalue=='Coupon')
        {
           
           if(isset($amounttype) && $amounttype !=''){
               
               if($amounttype=='less'){
                    $sql .= " AND d.vitemcode='18'  AND  Abs(d.nunitprice) < '".$amount."'";
                 
                    
                }
               if($amounttype=='greater'){
                    $sql .= "AND d.vitemcode='18' AND Abs(d.nunitprice)  > '".$amount."'";
                    
                }
              if($amounttype=='equal'){
                    $sql .= "AND d.vitemcode='18' AND Abs(d.nunitprice) = '".$amount."'";
                    
                }
           }  
           else{
               $sql .= " AND d.vitemcode ='18' ";
               
           }
            
        }
        
        elseif(isset($amounttype))
        {
            if($amounttype=='less'){
                $sql .= "AND s.nnettotal < '".$amount."'";
                
            }
           if($amounttype=='greater'){
                $sql .= "AND s.nnettotal > '".$amount."'";
                
            }
          if($amounttype=='equal'){
                $sql .= "AND s.nnettotal = '".$amount."'";
                
            }
        }
        
        if(isset($registerid) && $registerid!="All")
        {
            $sql .= "AND s.vterminalid = '".$registerid."'";
            
        }
       
        $sql.="ORDER BY  s.dtrandate  DESC";
        
       
        $result = DB::connection('mysql_dynamic')->select($sql);
        
        $request_data=$request;
        $results = $this->arrayPaginator($result, $request_data);
        return $results;

    }
    public function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = 25;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
	public function getSalesById($salesid) {
		$sql = "SELECT *,date_format(dtrandate,'%m-%d-%Y %h:%i %p') as trandate FROM trn_sales WHERE isalesid = ". $salesid;
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;


    }
        public function getSalesPerview($isalesid){
                
            $sql="select * from trn_salesdetail a  where a.isalesid=".$isalesid;	
            $result = DB::connection('mysql_dynamic')->select($sql);
            return $result;	
        }
        public function getSalesByTender($salesid) {
            $sql = "SELECT * FROM trn_salestender WHERE isalesid = ". $salesid;
            $result = DB::connection('mysql_dynamic')->select($sql);
            return $result;	

        }
        public function getSalesByCustomer($icustomerid) {
          $sql="SELECT * FROM mst_customer WHERE icustomerid='" .(int)$icustomerid. "'";
          $result = DB::connection('mysql_dynamic')->select($sql);
          return $result;	
    
        }


        //-------------------- credit card report

        public function getCreditCardReport($p_start_date,$p_end_date,$credit_card_number,$credit_card_amount) {
		  //  $data['start_date']=$p_start_date;
    //         $data['end_date']=$p_end_date;
            
            $sql = "SELECT count(trn_mps.nauthamount) as transaction_number,ifnull(SUM(trn_mps.nauthamount),0) as nauthamount, trn_mps.vcardtype as vcardtype FROM trn_mpstender trn_mps WHERE trn_mps.vcardtype !='' AND trn_mps.nauthamount !=0 ";
    
            if (!empty($p_start_date) && !empty($p_end_date)) {
    
                $start_date = DateTime::createFromFormat('m-d-Y', $p_start_date);
                $data['start_date'] = $start_date->format('Y-m-d');
    
                $end_date = DateTime::createFromFormat('m-d-Y', $p_end_date);
                $data['end_date'] = $end_date->format('Y-m-d');
    
                $sql .= " AND date_format(trn_mps.dtrandate,'%Y-%m-%d') >= '".$data['start_date']."'AND date_format(trn_mps.dtrandate,'%Y-%m-%d') <= '".$data['end_date']."'";
            }
    
            if (!empty($credit_card_number)) {
                $sql .= " AND trn_mps.vcardusage='". $credit_card_number ."'";
            }
    
            if (!empty($credit_card_amount)) {
                $sql .= " AND trn_mps.nauthamount='". $credit_card_amount ."'";
            }
    
                $sql .= " GROUP BY trn_mps.vcardtype";
                $result = DB::connection('mysql_dynamic')->select($sql);
                return $result;	
        } 

        public function ajaxDataCreditCardReport($p_start_date,$p_end_date,$credit_card_number,$credit_card_amount,$pullby) {
            // $data['start_date']=$p_start_date;
            // $data['end_date']=$p_end_date;
            
            $sql = "SELECT trn_mps.impstenderid as id, date_format(trn_mps.dtrandate,'%m-%d-%Y') as
            date, date_format(trn_mps.dtrandate,'%h:%i %p') as time, trn_mps.nauthamount as amount,
            trn_mps.vauthcode as approvalcode, trn_mps.vcardusage as last_four_of_cc, trn_mps.vcardtype as vcardtype 
            FROM trn_mpstender trn_mps WHERE trn_mps.vcardtype !='' 
            AND trn_mps.nauthamount!=0 AND trn_mps.vcardtype='".$pullby."' ";
    
            if (!empty($p_start_date) && !empty($p_end_date)) {
    
                $start_date = DateTime::createFromFormat('m-d-Y', $p_start_date);
                $data['start_date'] = $start_date->format('Y-m-d');
    
                $end_date = DateTime::createFromFormat('m-d-Y', $p_end_date);
                $data['end_date'] = $end_date->format('Y-m-d');
    
                $sql .= " AND date_format(trn_mps.dtrandate,'%Y-%m-%d') >= '".$data['start_date']."'AND date_format(trn_mps.dtrandate,'%Y-%m-%d') <= '".$data['end_date']."'";
            }
    
            if (!empty($credit_card_number)) {
                $sql .= " AND trn_mps.vcardusage='". $credit_card_number ."'";
            }
    
            if (!empty($credit_card_amount)) {
                $sql .= " AND trn_mps.nauthamount='". $credit_card_amount ."'";
            }
    
                
                $result = DB::connection('mysql_dynamic')->select($sql);
                return $result;	
        } 
        public function data_for_file($p_start_date,$p_end_date,$credit_card_number,$credit_card_amount) {
            
            
            $sql = "SELECT trn_mps.impstenderid as id, date_format(trn_mps.dtrandate,'%m-%d-%Y') as
            date, date_format(trn_mps.dtrandate,'%h:%i %p') as time, trn_mps.nauthamount as amount,
            trn_mps.vauthcode as approvalcode, trn_mps.vcardusage as last_four_of_cc, trn_mps.vcardtype as vcardtype 
            FROM trn_mpstender trn_mps WHERE trn_mps.vcardtype !='' 
            AND trn_mps.nauthamount!=0 ";
    
            if (!empty($p_start_date) && !empty($p_end_date)) {
    
                $start_date = DateTime::createFromFormat('m-d-Y', $p_start_date);
                $data['start_date'] = $start_date->format('Y-m-d');
    
                $end_date = DateTime::createFromFormat('m-d-Y', $p_end_date);
                $data['end_date'] = $end_date->format('Y-m-d');
    
                $sql .= " AND date_format(trn_mps.dtrandate,'%Y-%m-%d') >= '".$data['start_date']."'AND date_format(trn_mps.dtrandate,'%Y-%m-%d') <= '".$data['end_date']."'";
            }
    
            if (!empty($credit_card_number)) {
                $sql .= " AND trn_mps.vcardusage='". $credit_card_number ."'";
            }
    
            if (!empty($credit_card_amount)) {
                $sql .= " AND trn_mps.nauthamount='". $credit_card_amount ."'";
            }
    
             $sql.="    ORDER BY  trn_mps.vcardtype ASC,trn_mps.dtrandate DESC" ;
          
           $result = DB::connection('mysql_dynamic')->select($sql);
           return $result;	
        } 
          
        public function getReceiptData($id,$by) {
		
            $sql = "SELECT trn_mps.impstenderid as id, date_format(trn_mps.dtrandate,'%m-%d-%Y') as date, date_format(trn_mps.dtrandate,'%h:%i %p') as time, trn_mps.nauthamount as amount, trn_mps.vauthcode as approvalcode, trn_mps.vcardusage as last_four_of_cc, trn_mps.vcardtype as vcardtype, trn_mps.itranid as isalesid FROM trn_mpstender trn_mps WHERE trn_mps.impstenderid='". (int)$id ."' ";
    
            $result = DB::connection('mysql_dynamic')->select($sql);
            return $result;	
        }
        public function getauthcode($id,$by) {
		
            $sql = "SELECT vauthcode FROM trn_mpstender trn_mps WHERE trn_mps.impstenderid='". (int)$id ."' ";
    
            $result = DB::connection('mysql_dynamic')->select($sql);
            return $result;	
        }
        
        public function getimage($id,$by) {
            
            $sql = "SELECT vsignaturedata FROM trn_mpstender trn_mps WHERE trn_mps.impstenderid='". (int)$id ."' ";
    
            $result = DB::connection('mysql_dynamic')->select($sql);
            return $result;	
        }
        public function getVendors() {
            $sql = "SELECT isupplierid, vsuppliercode, vcompanyname FROM mst_supplier";
    
            $result = DB::connection('mysql_dynamic')->select($sql);
            return $result;	
        }


        //Po history report start
//         public function getPoHistoryReportAll($data) {
             
//             $start_date = DateTime::createFromFormat('m-d-Y', $data['start_date']);
//             $data['start_date'] = $start_date->format('Y-m-d');
    
//             $end_date = DateTime::createFromFormat('m-d-Y', $data['end_date']);
//             $data['end_date'] = $end_date->format('Y-m-d');
    
//             $sql = "SELECT MAX(po.ipoid) as ipoid, MAX(po.vvendorid) as vvendorid, date_format(po.dcreatedate,'%m-%d-%Y') as dcreatedate, MAX(po.vvendorname) as vvendorname, ifnull(SUM(po.nnettotal),0) as nnettotal FROM trn_purchaseorder as po WHERE estatus='Close' AND date_format(po.dcreatedate,'%Y-%m-%d') >= '".$data['start_date']."' AND date_format(po.dcreatedate,'%Y-%m-%d') <= '".$data['end_date']."' GROUP BY po.dcreatedate ";
            
//             $query = DB::connection('mysql_dynamic')->select($sql);
//             //return count($query);
//             $return = array();
    
//             if(count($query) > 0){
//                 foreach ($query as $key => $value) {
//                     $return[$key]['ipoid'] = $value->ipoid;
//                     $return[$key]['vvendorid'] = $value->vvendorid;
//                     $return[$key]['dcreatedate'] = $value->dcreatedate;
//                     $return[$key]['vvendorname'] = $value->vvendorname;
//                     $return[$key]['nnettotal'] = $value->nnettotal;
//                     $tot_rip = 0.00;
//                     $rip_totals = $this->getViewItems($value->vvendorid,$value->dcreatedate,$value->ipoid);
    
//                     foreach ($rip_totals as $rip_total) {
//                         $tot_rip = $tot_rip + $rip_total->nripamount;
//                     }
//                     $return[$key]['rip_total'] =  number_format((float)$tot_rip, 2, '.', '');
//                 }
//             }
    
//             return $return;
//         }
//         public function getPoHistoryReport($data) {
		
//             $vvendorids = implode(',', $data['report_by']);
//             $start_date = DateTime::createFromFormat('m-d-Y', $data['start_date']);
//             $data['start_date'] = $start_date->format('Y-m-d');
    
//             $end_date = DateTime::createFromFormat('m-d-Y', $data['end_date']);
//             $data['end_date'] = $end_date->format('Y-m-d');
    
//             $sql = "SELECT MAX(po.ipoid) as ipoid, MAX(po.vvendorid) as vvendorid, date_format(po.dcreatedate,'%m-%d-%Y') as dcreatedate, MAX(po.vvendorname) as vvendorname, ifnull(SUM(po.nnettotal),0) as nnettotal FROM trn_purchaseorder as po WHERE estatus='Close' AND po.vvendorid in($vvendorids) AND date_format(po.dcreatedate,'%Y-%m-%d') >= '".$data['start_date']."' AND date_format(po.dcreatedate,'%Y-%m-%d') <= '".$data['end_date']."' GROUP BY po.dcreatedate ";
            
//             $query = DB::connection('mysql_dynamic')->select($sql);
//             //return count($query);
//             $return = array();
    
//             if(count($query) > 0){
//                 foreach ($query as $key => $value) {
//                     $return[$key]['ipoid'] = $value->ipoid;
//                     $return[$key]['vvendorid'] = $value->vvendorid;
//                     $return[$key]['dcreatedate'] = $value->dcreatedate;
//                     $return[$key]['vvendorname'] = $value->vvendorname;
//                     $return[$key]['nnettotal'] = $value->nnettotal;
//                     $tot_rip = 0.00;
//                     $rip_totals = $this->getViewItems($value->vvendorid,$value->dcreatedate,$value->ipoid);
    
//                     foreach ($rip_totals as $rip_total) {
//                         $tot_rip = $tot_rip + $rip_total->nripamount;
//                     }
//                     $return[$key]['rip_total'] =  number_format((float)$tot_rip, 2, '.', '');
//                 }
//             }
    
//             return $return;
//         }


// 		public function getViewItems($vendor_id,$vendor_date,$vendor_ipoid) {
		
//             $vendor_date = DateTime::createFromFormat('m-d-Y', $vendor_date);
    
//             $vendor_date = $vendor_date->format('Y-m-d');
            
//             // $sql = "SELECT po.vvendorid as vvendorid, po.vvendorname as vvendorname, pod.vitemid as vitemid, pod.vitemname as vitemname, ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' AND po.ipoid=pod.ipoid AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') >= '".$vendor_date." 00:00:00' AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') <= '".$vendor_date." 23:59:59' AND po.vvendorid='".$vendor_id."' GROUP BY pod.vitemid ";
    
//             $sql = "SELECT max(pod.vbarcode),max(pod.vitemname) as vitemname,max(pod.vsize),
//             max(ifnull(pod.nordqty,0)) as nordqty,max(ifnull(pod.npackqty,0)) 
//             as npackqty,max(ifnull(pod.itotalunit,0)) as itotalunit,max(ifnull(pod.nunitcost,0))as 
//             nunitcost,max(pod.nripamount) as nripamount,max(po.vvendorid) as vvendorid, 
//             max(po.vvendorname) as vvendorname, max(pod.vitemid) as vitemid, 
//             ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as
//              po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' 
//              AND po.ipoid=pod.ipoid AND po.ipoid='".$vendor_ipoid."' GROUP BY pod.vitemid ";
    
//             $query =  DB::connection('mysql_dynamic')->select($sql);
//             return $query;
//         }
//         public function getViewItemspre($vendor_id,$vendor_date,$vendor_ipoid) {
		
//             $vendor_date = DateTime::createFromFormat('m-d-Y', $vendor_date);
    
//             $vendor_date = $vendor_date->format('Y-m-d');
            
//             // $sql = "SELECT po.vvendorid as vvendorid, po.vvendorname as vvendorname, pod.vitemid as vitemid, pod.vitemname as vitemname, ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' AND po.ipoid=pod.ipoid AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') >= '".$vendor_date." 00:00:00' AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') <= '".$vendor_date." 23:59:59' AND po.vvendorid='".$vendor_id."' GROUP BY pod.vitemid ";
    
//             $sql = "SELECT max(pod.vbarcode) as vbarcode,max(pod.vitemname) as vitemname,max(pod.vsize) as vsize ,
//             max(ifnull(pod.nordqty,0)) as nordqty,max(ifnull(pod.npackqty,0)) 
//             as npackqty,max(ifnull(pod.itotalunit,0)) as itotalunit,max(ifnull(pod.nunitcost,0))as 
//             nunitcost,max(pod.nripamount) as nripamount,max(po.vvendorid) as vvendorid, 
//             max(po.vvendorname) as vvendorname, max(pod.vitemid) as vitemid, 
//             ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as
//              po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' 
//              AND po.ipoid=pod.ipoid AND po.ipoid='".$vendor_ipoid."' GROUP BY pod.vitemid ";
    
//             $query =  DB::connection('mysql_dynamic')->select($sql);
//             return $query;
//         }
        // new po histroy
         public function getPoHistoryReportAll($data) {
             
            $start_date = DateTime::createFromFormat('m-d-Y', $data['start_date']);
            $data['start_date'] = $start_date->format('Y-m-d');
    
            $end_date = DateTime::createFromFormat('m-d-Y', $data['end_date']);
            $data['end_date'] = $end_date->format('Y-m-d');
    
            // $sql = "SELECT MAX(po.ipoid) as ipoid, MAX(po.vvendorid) as vvendorid, 
            // date_format(po.dcreatedate,'%m-%d-%Y') as dcreatedate, MAX(po.vvendorname) as vvendorname,
            // ifnull(SUM(po.nnettotal),0) as nnettotal  FROM trn_purchaseorder as po WHERE estatus='Close' 
            // AND date_format(po.dcreatedate,'%Y-%m-%d') >= '".$data['start_date']."' 
            // AND date_format(po.dcreatedate,'%Y-%m-%d') <= '".$data['end_date']."' GROUP BY po.dcreatedate ";
            
            $sql="SElECT max(ipoid) as ipoid,MAX(vvendorid) as vvendorid,date_format(dcreatedate,'%m-%d-%Y') as dcreatedate, dcreatedate date2,
            MAX(vvendorname) as vvendorname, 
            ifnull(SUM(nnettotal),0) as nnettotal
            FROM trn_receivingorder 
            WHERE estatus='Close'
            AND date_format(dcreatedate,'%Y-%m-%d') >= '".$data['start_date']."' 
            AND date_format(dcreatedate,'%Y-%m-%d') <= '".$data['end_date']."' GROUP BY dcreatedate ";
            
            $query = DB::connection('mysql_dynamic')->select($sql);
            //return count($query);
           // dd($query);
            $return = array();
    
            if(count($query) > 0){
                foreach ($query as $key => $value) {
                    $return[$key]['ipoid'] = $value->ipoid;
                    $return[$key]['vvendorid'] = $value->vvendorid;
                    $return[$key]['dcreatedate'] = $value->dcreatedate;
                    $return[$key]['date2'] = $value->date2;
                    $return[$key]['vvendorname'] = $value->vvendorname;
                    $return[$key]['nnettotal'] = $value->nnettotal;
                    $tot_rip = 0.00;
                    $rip_totals = $this->getViewItems($value->vvendorid,$value->dcreatedate,$value->ipoid);
    
                    foreach ($rip_totals as $rip_total) {
                        $tot_rip = $tot_rip + $rip_total->nripamount;
                    }
                    $return[$key]['rip_total'] =  number_format((float)$tot_rip, 2, '.', '');
                }
            }
    
            return $return;
            //dd($return);
        }
        public function getPoHistoryReport($data) {
		
            $vvendorids = implode(',', $data['report_by']);
            $start_date = DateTime::createFromFormat('m-d-Y', $data['start_date']);
            $data['start_date'] = $start_date->format('Y-m-d');
    
            $end_date = DateTime::createFromFormat('m-d-Y', $data['end_date']);
            $data['end_date'] = $end_date->format('Y-m-d');
    
            // $sql = "SELECT MAX(po.ipoid) as ipoid, MAX(po.vvendorid) as vvendorid, date_format(po.dcreatedate,'%m-%d-%Y') as dcreatedate,
            // MAX(po.vvendorname) as vvendorname, ifnull(SUM(po.nnettotal),0) as nnettotal FROM trn_purchaseorder as po WHERE estatus='Close'
            // AND po.vvendorid in($vvendorids) AND date_format(po.dcreatedate,'%Y-%m-%d') >= '".$data['start_date']."'
            // AND date_format(po.dcreatedate,'%Y-%m-%d') <= '".$data['end_date']."' GROUP BY po.dcreatedate ";
            
            $sql="SElECT max(ipoid) as ipoid,MAX(vvendorid) as vvendorid,date_format(dcreatedate,'%m-%d-%Y') as dcreatedate,dcreatedate date2,
            MAX(vvendorname) as vvendorname, 
            ifnull(SUM(nnettotal),0) as nnettotal
            FROM trn_receivingorder 
            WHERE estatus='Close' AND vvendorid in($vvendorids) 
            AND date_format(dcreatedate,'%Y-%m-%d') >= '".$data['start_date']."' 
            AND date_format(dcreatedate,'%Y-%m-%d') <= '".$data['end_date']."' GROUP BY dcreatedate ";
            
            $query = DB::connection('mysql_dynamic')->select($sql);
            //return count($query);
            $return = array();
    
            if(count($query) > 0){
                foreach ($query as $key => $value) {
                    $return[$key]['ipoid'] = $value->ipoid;
                    $return[$key]['vvendorid'] = $value->vvendorid;
                    $return[$key]['dcreatedate'] = $value->dcreatedate;
                    $return[$key]['date2'] = $value->date2;
                    $return[$key]['vvendorname'] = $value->vvendorname;
                    $return[$key]['nnettotal'] = $value->nnettotal;
                    $tot_rip = 0.00;
                    $rip_totals = $this->getViewItems($value->vvendorid,$value->dcreatedate,$value->ipoid);
    
                    foreach ($rip_totals as $rip_total) {
                        $tot_rip = $tot_rip + $rip_total->nripamount;
                    }
                    $return[$key]['rip_total'] =  number_format((float)$tot_rip, 2, '.', '');
                }
            }
    
            return $return;
            
        }


		public function getViewItems($vendor_id,$vendor_date,$vendor_ipoid) {
		   
		
            $vendor_date = DateTime::createFromFormat('m-d-Y', $vendor_date);
    
            $vendor_date = $vendor_date->format('Y-m-d');
            
            // $sql = "SELECT po.vvendorid as vvendorid, po.vvendorname as vvendorname, pod.vitemid as vitemid, pod.vitemname as vitemname, ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' AND po.ipoid=pod.ipoid AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') >= '".$vendor_date." 00:00:00' AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') <= '".$vendor_date." 23:59:59' AND po.vvendorid='".$vendor_id."' GROUP BY pod.vitemid ";
    
            $sql = "SELECT max(pod.vbarcode),
            max(pod.vitemname) as vitemname,max(pod.vsize),
            max(ifnull(pod.nordqty,0)) as nordqty,max(ifnull(pod.npackqty,0)) 
            as npackqty,max(ifnull(pod.itotalunit,0)) as itotalunit,max(ifnull(pod.nunitcost,0))as 
            nunitcost,max(pod.nripamount) as nripamount,max(po.vvendorid) as vvendorid, 
            max(po.vvendorname) as vvendorname, max(pod.vitemid) as vitemid, 
            ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as
             po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' 
             AND po.ipoid=pod.ipoid AND po.ipoid='".$vendor_ipoid."' GROUP BY pod.vitemid ";
    
            $query =  DB::connection('mysql_dynamic')->select($sql);
            return $query;
        }
        public function getViewItemspre($vendor_id,$vendor_date,$vendor_ipoid) {
		
            // $vendor_date = DateTime::createFromFormat('m-d-Y', $vendor_date);
    
            // $vendor_date = $vendor_date->format('Y-m-d');
            
            // $sql = "SELECT po.vvendorid as vvendorid, po.vvendorname as vvendorname, pod.vitemid as vitemid, pod.vitemname as vitemname, ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' AND po.ipoid=pod.ipoid AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') >= '".$vendor_date." 00:00:00' AND date_format(po.dcreatedate,'%Y-%m-%d %h:%i:%s') <= '".$vendor_date." 23:59:59' AND po.vvendorid='".$vendor_id."' GROUP BY pod.vitemid ";
    
            // $sql = "SELECT max(pod.vbarcode) as vbarcode,max(pod.vitemname) as vitemname,max(pod.vsize) as vsize ,
            // max(ifnull(pod.nordqty,0)) as nordqty,max(ifnull(pod.npackqty,0)) 
            // as npackqty,max(ifnull(pod.itotalunit,0)) as itotalunit,max(ifnull(pod.nunitcost,0))as 
            // nunitcost,max(pod.nripamount) as nripamount,max(po.vvendorid) as vvendorid, 
            // max(po.vvendorname) as vvendorname, max(pod.vitemid) as vitemid, 
            // ifnull(SUM(pod.nordextprice),0) as nordextprice FROM trn_purchaseorder as
            //  po, trn_purchaseorderdetail as pod WHERE po.estatus='Close' 
            //  AND po.ipoid=pod.ipoid AND po.ipoid='".$vendor_ipoid."' GROUP BY pod.vitemid ";AND mi.iitemid=tpd.vitemid 
             
            $sql="SELECT max(tpd.vbarcode) as vbarcode,
            case mi.isparentchild when 0
            then mi.VITEMNAME  when 1 then Concat(mi.VITEMNAME,' [Child]') when 2 then  Concat(mi.VITEMNAME,' [Parent]') end   as vitemname,
            max(ifnull(tpd.vsize,'')) as vsize,
            max(tp.vvendorid) as vvendorid, 
            max(tp.vvendorname) as vvendorname, max(tpd.vitemid) as vitemid ,
            max(ifnull(tpd.nunitcost,0))as nunitcost, max(ifnull(tpd.npackqty,0))  as npackqty,ifnull(SUM(tpd.nordextprice),0) as nordextprice,
            max(ifnull(tpd.nordqty,0)) as nordqty,
            max(ifnull(tpd.itotalunit,0)) as itotalunit,
            max(tpd.nripamount) as nripamount,
            
            
            case mi.isparentchild  when 1 then  ifnull(Mod(tpd.before_rece_qoh,mi.NPACK),'') else  
           ifnull((Concat(cast(((tpd.before_rece_qoh div mi.NPACK )) as signed), '  (', Mod(tpd.before_rece_qoh,mi.NPACK) ,')') ),'')end as before_rece_qoh,
           
            case mi.isparentchild  when 1 then   ifnull(Mod(tpd.after_rece_qoh,mi.NPACK),'')  else  
           ifnull((Concat(cast(((tpd.after_rece_qoh div mi.NPACK )) as signed), '  (', Mod(tpd.after_rece_qoh,mi.NPACK) ,')') ),'')end as after_rece_qoh
            
            FROM trn_receivingorderdetail as tpd,trn_receivingorder  as tp ,mst_item as mi
            
            WHERE tp.estatus='Close' 
            AND tpd.iroid = tp.iroid  AND tp.vvendorid='".$vendor_id."' 
            AND mi.iitemid=tpd.vitemid 
            AND dcreatedate = '".$vendor_date."'
            GROUP BY tp.dcreatedate,tpd.vitemid ";
            
            $query =  DB::connection('mysql_dynamic')->select($sql);
            
            return $query;
        }
         //--------------------- ProfitLossReport
        public function getProfitLossReport($data = null) {
            if(isset($data)){
                $start_date = $data['start_date'];
                $end_date = $data['end_date'];
                $report_by   = $data['report_by'];
            }else{
                $start_date = date("m-d-Y");
                $end_date = date("m-d-Y");
                $report_by = "Category";
            }
            $sql = "CALL rp_profitloss('".$start_date."','".$end_date."','".$report_by."','ALL')";
            
            $query =  DB::connection('mysql_dynamic')->select($sql);
            return $query;
    
            
        }
        


        //Paid out Reports
        public function  getvendorlist(){
            $sql = "select distinct vpaidoutname from .trn_paidoutdetail ORDER BY vpaidoutname ";
            
            $query =  DB::connection('mysql_dynamic')->select($sql);
            return $query;
    
        }
        public function paidOut($from_date = null, $to_date=null,$vendor,$amounttype,$amount,$tender) {
            if($vendor=='All'){
                $vendor_name='All';
            }
            else{
                $vendor_name=$vendor;
            }
            if($amounttype==''){
                $amounttype='All';
            } 
            if($amount==''){
                $amount='100000000000000000';
            }
            
            $fDateTime = DateTime::createFromFormat('Y-m-d', $from_date);
            $fDateString = $fDateTime->format('m-d-Y');
            //echo $fDateString;die;
            
            $eDateTime = DateTime::createFromFormat('Y-m-d', $to_date);
            $eDateString = $eDateTime->format('m-d-Y');
          
          
            $sql ="CALL rp_eofpaidout('".$fDateString."','".$eDateString."', '".$vendor_name."','".$amounttype."','".$amount."','".ucfirst($tender)."')";
           
            $query =  DB::connection('mysql_dynamic')->select($sql);
            return $query;
        }
        
        //below cost reports start
        public function getDepartments() {

            $sql = "SELECT idepartmentid, vdepcode, vdepartmentname FROM mst_department";
            
            $query =  DB::connection('mysql_dynamic')->select($sql);
            return $query;
        }
        public function getBelowCostReport($data) {
	    
            if(in_array('ALL', $data['report_by'])){
                $vdepcodes = 'ALL';
            }else{
                $vdepcodes = implode(',', $data['report_by']);
            }
  
            $sql ="CALL rp_webbelowcost('".$vdepcodes."')";
            $query =  DB::connection('mysql_dynamic')->select($sql);
            return $query;
        }
        
    //inv report start@@@@@@@@@@@@@@@@ **************************************************************************************************************
        public function getDepartments_inv() {
        $sql = "SELECT * FROM mst_department ORDER BY vdepartmentname";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
        
       }
       
       // Department with Today date ---START
       
		
		
		public function getDepartmentsReportNew($data, $qoh = "Z") {
	    if($qoh === "Z"){
		    $qtyCondition   = " = 0";
		}else if($qoh === "P"){
		    $qtyCondition   = " > 0";
		}else if($qoh === "N"){
		    $qtyCondition   = " < 0";
		}
		
		$dept_ids       = implode(',', $data['vdepcode']);
		$divCondition   = " AND a.vdepcode in($dept_ids)";
		$date           = $data['qoh_date'];
		$query          = $this->getQueryResult($qtyCondition, $divCondition,$date);
		return $query;
	}
	// Department with Today date ---END  
       
       //subcategory data 
       
       public function getSubCategoriesReport($data) {
		
		if(in_array('ALL', $data['subcat_id'])){
			$subcat_id = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as vname,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0 and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname ASC";
		}else{
			$subcat_id = implode(',', $data['subcat_id']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as vname,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND > 0   and vitemname is not null AND a.visinventory='Yes' AND a.subcat_id in($subcat_id) ORDER BY b.vdepartmentname ASC";
		}

		
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	 }
	 public function getSubCategoriesReportN($data) {
		
		if(in_array('ALL', $data['subcat_id'])){
			$subcat_id = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0 and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname ASC";
		}else{
			$subcat_id = implode(',', $data['subcat_id']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND < 0   and vitemname is not null AND a.visinventory='Yes' AND a.subcat_id in($subcat_id) ORDER BY b.vdepartmentname ASC";
		}

		$query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}
	public function getSubCategoriesReportZero($data) {
		
		if(in_array('ALL', $data['subcat_id'])){
			$subcat_id = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0 and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname ASC";
		}else{
			$subcat_id = implode(',', $data['subcat_id']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0   and vitemname is not null AND a.visinventory='Yes' AND a.subcat_id in($subcat_id) ORDER BY b.vdepartmentname ASC";
		}

		

		$query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}
       
     
       
	public function getSubCategoriesReportNew($data, $qoh = "Z") {
		if($qoh === "Z"){
		    $qtyCondition   = " = 0";
		}else if($qoh === "P"){
		    $qtyCondition   = " > 0";
		}else if($qoh === "N"){
		    $qtyCondition   = " < 0";
		}
		
		$subcat_ids     = implode(',', $data['subcat_id']);
		$divCondition   = " AND a.subcat_id in($subcat_ids)";
		$date           = $data['qoh_date'];
		$query          = $this->getQueryResult($qtyCondition, $divCondition, $date);
		
		return $query;
	}
	
		public function getQueryResult($qtyCondition, $divCondition, $date){
	    if(empty($date)){
	        $date = date('m-d-Y');
	    }
	    $day = date_parse_from_format('m-d-Y', $date)['day'];
        $monthYear = date("M-Y", strtotime(str_replace('-', '/', $date)));
        
        $sql = "SELECT 
        	a.nsellunit,
            a.vdepcode as search_id, 
            a.vitemname as itemname,
            a.dUnitPrice as price,
            a.iqtyonhand,
            b.vcategoryname as category_name,
            d.vdepartmentname as vname,
        	c.vsuppliercode as vsuppliercode,
            d.vdepartmentname as department_name,
            e.subcat_name as subcat_name,
            f.dt$day as snapshot_qty,
        
        	CASE 
        		WHEN NPACK = 1 or (npack is null) then IQTYONHAND 
        		else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), ' (' , Mod(a.IQTYONHAND,a.NPACK) , ')') ) 
        	end as vqty,
        	
        	Case 
        		When (nUnitCost) is null then 0 
        		else nUnitCost 
        	end as cost 
        FROM 	mst_item a, mst_category b, mst_supplier c, mst_department d, mst_subcategory e, trn_inv_snapshot f
        where 	a.vcategorycode=b.vcategorycode and 
        		a.vsuppliercode =c.vsuppliercode and 
                a.vdepcode = d.vdepcode and
                a.subcat_id = e.subcat_id and
                a.iitemid = f.itemid and f.month_year = '$monthYear' and
        		a.vitemtype != 'Kiosk' and 
        		f.dt$day$qtyCondition and 
        		vitemname is not null AND 
        		a.visinventory='Yes'  
        		$divCondition
        ORDER BY b.vcategoryname ASC";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}

    //CAT DATA
    	public function getCategoriesReport($data) {

		if(in_array('ALL', $data['vcategorycode'])){
			$vcatcodes = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null) then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price 
			FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' and IQTYONHAND !=0 and IQTYONHAND > 0  and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname  ASC";
		}else{
			$vcatcodes = implode(',', $data['vcategorycode']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null) then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price 
			FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' and IQTYONHAND !=0 and IQTYONHAND > 0  and vitemname is not null AND a.visinventory='Yes' AND a.vcategorycode in($vcatcodes) ORDER BY b.vdepartmentname  ASC";
		}

		 $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}
	public function getCategoriesReportN($data) {

		if(in_array('ALL', $data['vcategorycode'])){
			$vcatcodes = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null) then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price 
			FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND < 0   and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname  ASC";
		}else{
			$vcatcodes = implode(',', $data['vcategorycode']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null) then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price 
		    FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND < 0   and vitemname is not null AND a.visinventory='Yes' AND a.vcategorycode in($vcatcodes) ORDER BY b.vdepartmentname  ASC";
		
		   // echo $sql;die;
		}
         $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}
	
	public function getCategoriesReportZero($data) {

		if(in_array('ALL', $data['vcategorycode'])){
			$vcatcodes = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null) then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price 
			FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0   and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname ASC";
		}else{
			$vcatcodes = implode(',', $data['vcategorycode']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null) then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price
			FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0   and vitemname is not null AND a.visinventory='Yes' AND a.vcategorycode in($vcatcodes) ORDER BY b.vdepartmentname  ASC";
		
		   // echo $sql;die;
		}
       $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}
	
	public function getCategoriesReportNew($data, $qoh = "Z") {
	    if($qoh === "Z"){
		    $qtyCondition   = " = 0";
		}else if($qoh === "P"){
		    $qtyCondition   = " > 0";
		}else if($qoh === "N"){
		    $qtyCondition   = " < 0";
		}
		
		$cat_ids        = implode(',', $data['vcategorycode']);
		$divCondition   = " AND a.vcategorycode in($cat_ids)";
		$date           = $data['qoh_date'];
		$query          = $this->getQueryResult($qtyCondition, $divCondition,$date);
		
		return $query;
	}
	
	public function getDepartmentsReport($data) {
	 
		//if(in_array('ALL', $data['vdepcode'])){
		if($data=='ALL'){
		   
			$vdepcodes = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' and IQTYONHAND !=0 and IQTYONHAND > 0  and vitemname is not null AND a.visinventory='Yes' ORDER BY a.vitemname ASC";
		}else{
			$vdepcodes = implode(',', $data['vdepcode']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' and IQTYONHAND !=0 and IQTYONHAND > 0  and vitemname is not null AND a.visinventory='Yes' AND a.vdepcode in($vdepcodes) ORDER BY a.vitemname ASC";
		}

	  $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
		
	}
	public function getDepartmentsReportN($data) {
		
		//if(in_array('ALL', $data['vdepcode'])){
		if($data=='ALL'){
			$vdepcodes = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else
			(Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as 
			vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else 
			nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND < 0 and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname ASC";
		}else{
			$vdepcodes = implode(',', $data['vdepcode']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND < 0   and vitemname is not null AND a.visinventory='Yes' AND a.vdepcode in($vdepcodes) ORDER BY b.vdepartmentname ASC";
		}

	    $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}
	public function getDepartmentsReportZero($data) {
		

            if($data=='ALL'){
			$vdepcodes = 'ALL';

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0 and vitemname is not null AND a.visinventory='Yes' ORDER BY b.vdepartmentname ASC";
		}else{
			$vdepcodes = implode(',', $data['vdepcode']);

			$sql = "SELECT a.nsellunit,b.vdepartmentname as department_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand as snapshot_qty,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
			and IQTYONHAND = 0   and vitemname is not null AND a.visinventory='Yes' AND a.vdepcode in($vdepcodes) ORDER BY b.vdepartmentname ASC";
		}

	   $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
	}
		public function getAllReportNew($data, $qoh = "Z") {
	    if($qoh === "Z"){
		    $qtyCondition   = " = 0";
		}else if($qoh === "P"){
		    $qtyCondition   = " > 0";
		}else if($qoh === "N"){
		    $qtyCondition   = " < 0";
		}
		
		$date           = $data['qoh_date'];
		$divCondition='';
		$query          = $this->getQueryResult($qtyCondition, $divCondition,$date);
		return $query;
	}



    	public function ajaxDataReportDepartmentnew_p($data,$fdate,$filter_data) {
	    //print_r("test");die;
	    
	    if(!empty($filter_data['subcat_id'])){
		   
		  $subcat_id       = implode(',', $filter_data['subcat_id']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.subcat_id  in($subcat_id) ";
		}
	    else if(!empty($filter_data['vcategorycode'])){
		   
		  $cat_ids       = implode(',', $filter_data['vcategorycode']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.vcategorycode  in($cat_ids) ";
		}

		else{
		  $dept_ids       = $data['report_pull_id'];   
		  $divCondition   = " AND a.vdepcode in($dept_ids)";
		}
		
		$dept_id=$data['report_pull_id'];
	
    	$sql = "SELECT a.nsellunit,b.vdepartmentname as search_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
        and IQTYONHAND > 0  and vitemname is not null AND a.visinventory='Yes' $divCondition ORDER BY a.vitemname ASC ";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        return $query;
		
	}   
	public function ajaxDataReportDepartmentp($data,$fdate,$filter_data) {
      
		if(!empty($filter_data['subcat_id'])){
		   
		  $subcat_id       = implode(',', $filter_data['subcat_id']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.subcat_id  in($subcat_id) ";
		}
	    else if(!empty($filter_data['vcategorycode'])){
		   
		  $cat_ids       = implode(',', $filter_data['vcategorycode']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.vcategorycode  in($cat_ids) ";
		}

		else{
		  $dept_ids       = $data['report_pull_id'];  
		  $divCondition   = " AND a.vdepcode in($dept_ids)";
		}
		$qtyCondition   = " > 0";
		
    // 		$dept_ids       = $data['report_pull_id'];
    // 		$divCondition   = " AND a.vdepcode in($dept_ids)";
		$date           = $data['qoh_date'];
		$query          = $this->getQueryResult($qtyCondition, $divCondition,$fdate);
		return $query->rows;

	}
	public function ajaxDataReportDepartmentnew_Z($data,$fdate,$filter_data) {
	   // print_r("test");die;
	    
	    if(!empty($filter_data['subcat_id'])){
		   
		  $subcat_id       = implode(',', $filter_data['subcat_id']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.subcat_id  in($subcat_id) ";
		}
	    else if(!empty($filter_data['vcategorycode'])){
		   
		  $cat_ids       = implode(',', $filter_data['vcategorycode']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.vcategorycode  in($cat_ids) ";
		}

		else{
		  $dept_ids       = $data['report_pull_id'];   
		  $divCondition   = " AND a.vdepcode in($dept_ids)";
		}
		
		$dept_id=$data['report_pull_id'];
	
    	$sql = "SELECT a.nsellunit,b.vdepartmentname as search_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
        and IQTYONHAND = 0  and vitemname is not null AND a.visinventory='Yes' $divCondition ";

		$query =  DB::connection('mysql_dynamic')->select($sql);
        return $query;
	}
	public function ajaxDataReportDepartmentZ($data,$fdate,$filter_data) {
        if(!empty($filter_data['subcat_id'])){
		   
		  $subcat_id       = implode(',', $filter_data['subcat_id']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.subcat_id  in($subcat_id) ";
		}
	    else if(!empty($filter_data['vcategorycode'])){
		   
		  $cat_ids       = implode(',', $filter_data['vcategorycode']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.vcategorycode  in($cat_ids) ";
		}

		else{
		  $dept_ids       = $data['report_pull_id'];   
		  $divCondition   = " AND a.vdepcode in($dept_ids)";
		}
		
		$qtyCondition   = " = 0";
		
		$dept_ids       = $data['report_pull_id'];
		$divCondition   = " AND a.vdepcode in($dept_ids)";
		$date           = $data['qoh_date'];
		$query          = $this->getQueryResult($qtyCondition, $divCondition,$fdate);
		return $query->rows;

	}
		public function ajaxDataReportDepartmentnew_N($data,$fdate,$filter_data) {
	   // print_r("test");die;
	    
	    if(!empty($filter_data['subcat_id'])){
		   
		  $subcat_id       = implode(',', $filter_data['subcat_id']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.subcat_id  in($subcat_id) ";
		}
	    else if(!empty($filter_data['vcategorycode'])){
		   
		  $cat_ids       = implode(',', $filter_data['vcategorycode']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.vcategorycode  in($cat_ids) ";
		}

		else{
		  $dept_ids       = $data['report_pull_id'];   
		  $divCondition   = " AND a.vdepcode in($dept_ids)";
		}
		
		$dept_id=$data['report_pull_id'];
    	
    	$sql = "SELECT a.nsellunit,b.vdepartmentname as search_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
        and IQTYONHAND < 0  and vitemname is not null AND a.visinventory='Yes' $divCondition ";

		$query =  DB::connection('mysql_dynamic')->select($sql);
        return $query;
	}
	
		public function ajaxDataReportDepartmentN($data,$fdate,$filter_data) {
		//print_r($data['report_pull_id']);die;
// 		$sql = "SELECT a.nsellunit,b.vdepartmentname as search_name,a.vdepcode as search_id, c.vsuppliercode as vsuppliercode ,a.vitemname as itemname,CASE WHEN NPACK = 1 or (npack is null)   then IQTYONHAND else (Concat(cast((a.IQTYONHAND/a.NPACK ) as signed), '  (' , Mod(a.IQTYONHAND,a.NPACK) , ')') )  end as vqty,iqtyonhand,b.vdepartmentname as vname,Case When (nUnitCost) is null then 0 else  nUnitCost end as cost ,a.dUnitPrice as price FROM mst_item a,mst_department b,mst_supplier c  where a.vdepcode=b.vdepcode  and a.vsuppliercode =c.vsuppliercode and a.vitemtype != 'Kiosk' 
// 		and IQTYONHAND <=0  and vitemname is not null AND a.visinventory='Yes' AND a.vdepcode='". $this->db->escape($data['report_pull_id']) ."'";

// 		$query = $this->db2->query($sql);
		 if(!empty($filter_data['subcat_id'])){
		   
		  $subcat_id       = implode(',', $filter_data['subcat_id']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.subcat_id  in($subcat_id) ";
		}
	    else if(!empty($filter_data['vcategorycode'])){
		   
		  $cat_ids       = implode(',', $filter_data['vcategorycode']); 
	      $dept_ids       = $data['report_pull_id'];
		  $divCondition   = " AND a.vdepcode in($dept_ids) AND a.vcategorycode  in($cat_ids) ";
		}

		else{
		  $dept_ids       = $data['report_pull_id'];   
		  $divCondition   = " AND a.vdepcode in($dept_ids)";
		}
		$qtyCondition   = " < 0";
		
		$dept_ids       = $data['report_pull_id'];
		$divCondition   = " AND a.vdepcode in($dept_ids)";
		$date           = $data['qoh_date'];
		$query          = $this->getQueryResult($qtyCondition, $divCondition,$fdate);
		return $query->rows;

	}
	
	//hr slaes report
	 public function getManufacturers(){

        $sql = "SELECT * FROM mst_manufacturer";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
    }
     public function getSuppliers() {
        $sql = "SELECT * FROM mst_supplier ORDER BY vcompanyname";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
    }
     public function getSubcategories($categoryID = null) {
        $condition = "";
        if($categoryID)
        {
            $condition = " WHERE cat_id = $categoryID ";
        }
       
        $sql="SELECT * FROM mst_subcategory $condition ORDER BY subcat_name";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
    }
    public function getCategories($departmentID = null) {
        $condition = "";
        if($departmentID)
        {
            $condition = " WHERE dept_code = $departmentID ";
        }
        $sql ="SELECT * FROM mst_category $condition ORDER BY vcategoryname";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
    }
    
    	public function hourlyReport($array_dept,$array_cat,$array_sub,$array_sup,$array_manu,$start_date,$end_date) {


        if(!isset($array_dept) || empty($array_dept)){
            
            $array_dept = 'All';
        }
        
        if(!isset($array_cat) || empty($array_cat)){
            
            $array_cat = 'All';
        }
        
        if(!isset($array_sub) || empty($array_sub)){
            
            $array_sub = 'All';
        }
        
        if(!isset($array_sup) || empty($array_sup)){
            
            $array_sup = 'All';
        }
        if(!isset($array_manu) || empty($array_manu)){
            
            $array_manu = 'All';
        }
        
        
        $dt = new DateTime($start_date);

        $todaydate= $dt->format('Y-m-d');
        
        $dt2 = new DateTime($end_date);

        $todaydateend= $dt->format('Y-m-d');
        $deptcon= $catcon=$subcon='';
        
        if(isset($array_dept) && $array_dept !='All')
        {
            
            $deptcon=" AND m.vdepcode IN($array_dept)";
         
        }
       
        if(isset($array_cat) && $array_cat !='All')
        {
            
            $catcon=" AND m.vcategorycode IN($array_cat)";
        }
        
         if(isset($array_sub) && $array_sub !='All')
        {
            
            $subcon=" AND m.vcategorycode IN($array_sub)";
        }
       
       if($deptcon==""){
           $deptcon='';
          
       }
       if($subcon==""){
           $subcon='';
       }
       if($catcon==""){
           $catcon='';
       }
       
        if($todaydate==date("Y-m-d") && $todaydateend ==date("Y-m-d")){
            
            
            $sql=" SELECT date(dtrandate) as trn_date, 
            CONCAT(date_format(dtrandate,'%h:00 %p to '), date_format(date_add(dtrandate, interval 1 hour),'%h:00 %p')) Hours, 
            round(sum(nextunitprice + case when vtax = 'Y' then (nextunitprice * itemtaxrateone/100 + 
            nextunitprice * itemtaxratetwo/100 + nextunitprice*itemtaxratethree/100) else 0 end),2) Amount, count(distinct d.isalesid) Number, GROUP_CONCAT(DISTINCT s.isalesid) as salesids
                        
            FROM trn_sales s join trn_salesdetail d on s.isalesid=d.isalesid left join mst_item m on d.vitemcode=m.vitemcode
            Where dtrandate between '".$start_date."' and '".$end_date."' $deptcon $catcon $subcon and vtrntype='Transaction'
            
            group by date(dtrandate),CONCAT(date_format(dtrandate,'%h:00 %p to ') , date_format(date_add(dtrandate, interval 1 hour),'%h:00 %p')),date_format(dtrandate,'%H') 
            order by date(dtrandate), date_format(dtrandate,'%H')";
            // dd($sql);
            $query =  DB::connection('mysql_dynamic')->select($sql);
            $result = json_decode(json_encode($query), true); 
    		return $result;
           
        
            
        }
    else{
     
        $sql ="CALL rp_hourlysales('".$start_date."','".$end_date."','".$array_dept."', '".$array_cat."','".$array_sub."','".$array_sup."','".$array_manu."')";
   
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
       } 

 	}
 	
 	//ITEM AUDIT Reports
 	
   public function users() {
        $sql="SELECT * FROM mst_user ORDER BY vfname";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
		return $result;
        
    }
    public function item_list() {
       $sql="SELECT vitemname FROM mst_item where estatus='Active' ORDER BY vitemname LIMIT 20000";
       $query =  DB::connection('mysql_dynamic')->select($sql);
       $result = json_decode(json_encode($query), true); 
	   return $result;
    }
    public function getItems_list($start_date,$end_date,$userid,$itemname,$mtype){
        
        $sql_string = '';
        if($userid !='All' && $itemname !='All'){
          // $selectvalue='old_item_values';     AND general like '%".$selectvalue."%'; 
          $sql_string = " WHERE userid='" .$userid."'  AND mi.vitemname='" .$itemname."' AND date_format(historydatetime,'%Y-%m-%d') >= '".$start_date."' 
          AND date_format(historydatetime,'%Y-%m-%d') <= '".$end_date."'
         ";
        }
        elseif($userid !='All' && $itemname =='All')
        {
           $selectvalue='old_item_values';     
           $sql_string = " WHERE userid='" .$userid."' AND date_format(historydatetime,'%Y-%m-%d') >= '".$start_date."' AND date_format(historydatetime,'%Y-%m-%d') <= '".$end_date."'
            AND general like '%".$selectvalue."%'";
           
        }
        elseif($userid =='All' && $itemname!='All')
        {
           $selectvalue='old_item_values';     
           $sql_string = " WHERE mi.vitemname='" .$itemname."' AND date_format(historydatetime,'%Y-%m-%d') >= '".$start_date."' AND date_format(historydatetime,'%Y-%m-%d') <= '".$end_date."'
            AND general like '%".$selectvalue."%'"; 
           
            
        }
        else{
             $selectvalue='old_item_values'; 
             $sql_string .= " WHERE date_format(historydatetime,'%Y-%m-%d') >= '".$start_date."' AND date_format(historydatetime,'%Y-%m-%d') <= '".$end_date."' 
             
             AND general like '%".$selectvalue."%'";

        }
        
        //modification condtions  start
        if($mtype=='Item Type'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vitemtype')) beforem,
            mi.vitemtype as afterm, 
            
            
            md.vdepartmentname, historydatetime	,mu.vemail as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        if($mtype=='Item Name'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vitemname'))  beforem,
            mi.vitemname as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        if($mtype=='Unit'){
            $sql ="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vunitcode'))  beforem,
            mi.vunitcode as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        if($mtype=='Cost'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
           JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.dcostprice'))  beforem,
            mi.dcostprice as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
         
        if($mtype=='Price'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
           JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.dunitprice')) beforem,
            mi.dunitprice as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
         if($mtype=='Selling Unit'){
            $sql ="SELECT mi.vbarcode, mi.vitemname, 
            
           JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.nsellunit')) beforem,
            mi.nsellunit as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        if($mtype=='Size'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
           JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vsize')) beforem,
            mi.vsize as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        if($mtype=='Unit Per Case'){
            $sql ="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.npack')) beforem,
            mi.npack as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
         
        
        
        if($mtype=='Qty on Hand'){
            $sql ="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.iqtyonhand')) beforem,
            mi.iqtyonhand as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string ORDER BY historydatetime ASC";
        }
        
        
         if($mtype=='Bottle Deposit'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.nbottledepositamt')) beforem,
            mi.nbottledepositamt as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
         if($mtype=='Food Item'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vfooditem')) beforem,
            mi.vfooditem as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        
         if($mtype=='Age Verification'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vageverify')) beforem,
            mi.vageverify as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        
         if($mtype=='WIC'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.wicitem')) beforem,
            mi.wicitem as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
         if($mtype=='Status'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.estatus')) beforem,
            mi.estatus as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
         if($mtype=='Department'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vdepcode')) beforem,
            mi.vdepcode as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        if($mtype=='Category'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vcategorycode')) beforem,
            mi.vcategorycode as afterm, 
            
            
            md.vdepartmentname, historydatetime	,userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=userid) 
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
          if($mtype=='Supplier'){
            $sql="SELECT mi.vbarcode, mi.vitemname, 
            
            JSON_UNQUOTE(JSON_EXTRACT(general, '$.old_item_values.vsuppliercode')) beforem,
            mi.vsuppliercode as afterm, 
            
            
            md.vdepartmentname, historydatetime	, userid as userid
            FROM trn_webadmin_history twh 
            join mst_item mi on twh.itemid=mi.iitemid
            LEFT JOIN mst_user mu ON(mu.iuserid=twh.userid) 
            
            LEFT JOIN mst_department md ON(mi.vdepcode=md.vdepcode) $sql_string";
        }
        
        
    
       $query =  DB::connection('mysql_dynamic')->select($sql);
       
       $result = json_decode(json_encode($query), true); 
       
        $unique = array();

        foreach ($result as $value)
        {
            $unique[$value['beforem']] = $value;
        }
        
          
	   return $unique;
   
    }
    public function getdeptmodi($dept_code) {
        $sql = "SELECT * FROM mst_department WHERE vdepcode='".$dept_code."' ORDER BY vdepartmentname";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
    	return $result;
    }
    
    public function getcatmodi($dept_code) {
        $sql = "SELECT * FROM mst_category WHERE vcategorycode='".$dept_code."' ORDER BY vcategoryname";
        $query =  DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($query), true); 
	    return $result;
    }
    
    public function getuser_item_audit($id) {
        $sql = "SELECT * FROM mst_user where  iuserid ='".$id."' Limit 1";
        
        $query =  DB::connection('mysql_dynamic')->select($sql);
       
        if(count($query)>0){
            $result = json_decode(json_encode($query), true); 
	        return $result;
        }
        else{
        $sql = "SELECT * FROM inslocdb.store_mw_users where  iuserid ='".$id."' Limit 1";
        $query =  DB::connection('mysql')->select($sql);
        
        $result = json_decode(json_encode($query), true); 
	    return $result;
        }
    }
    //new paid out report
    public function newpaidOut($from_date = null, $to_date=null,$vendor,$amounttype,$amount,$tender) {
        $condition = "";
       if($vendor=='All'){
           $vendor_name='All';
       }
       else{
           $vendor_name=$vendor;
         
             $condition =  " AND vpaidoutname =  '".$vendor."' ";
  
       }
       if($amounttype==''){
           $amounttype='All';
       } 
       if($amount==''){
           $amount='100000000000000000';
       }
       
       $fDateTime = DateTime::createFromFormat('Y-m-d', $from_date);
       $fDateString = $fDateTime->format('m-d-Y');
       //echo $fDateString;die;
       
       $eDateTime = DateTime::createFromFormat('Y-m-d', $to_date);
       $eDateString = $eDateTime->format('m-d-Y');
     
     
       // $sql ="CALL rp_eofpaidout('".$fDateString."','".$eDateString."', '".$vendor_name."','".$amounttype."','".$amount."','".ucfirst($tender)."')";
      $SQL="select ifnull(Vendor,'Total') Vendor, Amount , RegNo, UserId, TenderType, dt, ttime
       from ( 	SELECT vpaidoutname Vendor, namount Amount , vterminalid RegNo, ilogid UserId, 'Cash' TenderType, date_format(ddate, '%m-%d-%Y') dt, time_format(ddate, '%r') ttime
               FROM trn_paidoutdetail tpd join trn_paidout tp 
               on tpd.ipaidouttrnid=tp.ipaidouttrnid 
               where tp.ibatchid in 
                       (select d.batchid 
                       from trn_endofday e 
                       join trn_endofdaydetail d on e.id=d.eodid 
                       where date(e.dstartdatetime) 
                     between '".$from_date."'  and  '".$to_date."'
                      )
           $condition
           ) t " ;
           
           ///dd($SQL);

   
       $query =  DB::connection('mysql_dynamic')->select($SQL);
       return $query;
   }
}   