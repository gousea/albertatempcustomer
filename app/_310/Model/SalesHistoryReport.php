<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class SalesHistoryReport extends Model
{
    protected $connection = 'mysql_dynamic';

    public function getMonthlyBreakup($data){   
        // echo "asdfasdfasdf";die;
         $query = "select max(mi.iitemid) iitemid, trim(mi.vitemname) itemname, max(mi.vsize), ";         
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
         $query .= 'TRIM(sum(ndebitqty))+0 as sales, max(ifnull(TRIM(tpod.pur_qty)+0,0)) as purchase, max(ifnull(format(pa_qty, 0), 0)) as adj_qty, ';                     
         $query .= 'max(mi.iqtyonhand) as qoh ';                    
         $to = date('Y-m-d', strtotime('last day of '.$list_of_months[(count($list_of_months)-1)]));         
         $from = date('Y-m-d', strtotime('first day of '.$list_of_months[0]));         
        $query .= "from mst_item mi ";
        $query .= "left join (	select dtrandate, ndebitqty, vitemcode from trn_sales sl 
                                    join trn_salesdetail tsd on sl.isalesid=tsd.isalesid
                                    where sl.vtrntype='Transaction' and date(dtrandate) between '{$from}' and '{$to}' 
                                ) sale ON sale.vitemcode = mi.vbarcode ";
        $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_receivingorderdetail
                                 trn_pod join trn_receivingorder trn_po on trn_po.iroid = trn_pod.iroid 
                                 WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                 GROUP by vbarcode
                               ) tpod on mi.vitemcode=tpod.vbarcode "; 
         $query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
                                 FROM trn_physicalinventory tpi 
                                 join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
                                 WHERE tpi.estatus = 'close' and
                                 date(tpi.dcreatedate) between '{$from}' and '{$to}'
                                 GROUP BY tpid.vbarcode
                              ) pa on mi.vitemcode=pa.vbarcode ";                              
         $query .= "";                     
         $join_query = false;                     
         if(isset($data['vendors'])){
             $vendors = $data['vendors'];
             $join_query = true;             
             $query .= $join_query_vendor = ' LEFT JOIN mst_itemvendor miv ON mi.iitemid = miv.iitemid';             
             $where_query_vendor = " (mi.vsuppliercode =$vendors or miv.ivendorid = $vendors) and "; 
         } else {
             $join_query_vendor = $where_query_vendor = '';
         }         

         if(isset($data['departments'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            //  }
             $where_query_department = " mi.vdepcode='".$data['departments']."' and ";
         } else {
             $join_query_department = $where_query_department = '';
         }
         
         if(isset($data['categories'])){
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }                 
                 $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";                 
         } else {
             $join_query_category = $where_query_category = '';
         }
         
         if(isset($data['sub_categories'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }                 
                 $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";   
         } else {
             $join_query_subcategory = $where_query_subcategory = '';
         }
         
         if(isset($data['item_name'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
             //$term_data = implode(',', $data['item_name']);
                 $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
         } else {
             $join_query_itemname = $where_query_itemname = '';
         }
         
         if(isset($data['barcode'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
            //  $term_data = implode(',', $data['barcode']);
                 $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";                 
         } else {
             $join_query_barcode = $where_query_barcode = '';
         }
         
         if(isset($data['size'])){ 
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                // $term_data = implode(',', $data['size']);
                 $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";                 
         } else {
             $join_query_size = $where_query_size = '';
         }
         
         if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
             
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
         $where_query = " where ";
         $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
         $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;        
         $where_query .= " mi.estatus = 'Active' group by trim(mi.vitemname), mi.vsize";
        //  if($join_query === false){
        //      $join_query = true;
        //      $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
        //  }                     
         $query = $query.$where_query;         
         $return_data = DB::connection('mysql_dynamic')->select($query);
         $return = [];
         $return['result'] = json_decode(json_encode($return_data), true);
         $return['header'] = $header; 
         return $return;
    }

    public function getWeeklyBreakup($data){
        // dd($data);
        // echo "sadfdsaf";die;
        
        $query = "select trim(mi.vitemname) itemname, max(mi.vsize), max(mi.iitemid) iitemid,";
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
            $query .= "TRIM(sum(case when yearweek(dtrandate, 3)='".date('YW', strtotime($str_to_time))."' then ndebitqty else 0 end))+0 as 'Week ".date('W-y', strtotime($str_to_time))."', ";
            $header[] = 'Wk '.date('W-y', strtotime($str_to_time));            
            $list_of_weeks[] = array('week' => date('W', strtotime($str_to_time)), 'year' => date('Y', strtotime($str_to_time)));
            }
            
            $header[] = 'Sale';
            $header[] = 'Pur';
            $header[] = 'Adj';
            $header[] = 'QoH';
            
            $query .= 'TRIM(sum(ndebitqty))+0 as sales, max(ifnull(TRIM(tpod.pur_qty)+0,0)) as purchase, max(ifnull(format(pa_qty, 0), 0)) as adj_qty, ';                    
            $query .= 'max(mi.iqtyonhand) as qoh ';                   
            $to_index = count($list_of_weeks)-1;
            $to = date('Y-m-d', strtotime(sprintf('%d-W%02d-7', $list_of_weeks[$to_index]['year'], $list_of_weeks[$to_index]['week'])));
            $from = date('Y-m-d', strtotime(sprintf('%d-W%02d-1', $list_of_weeks[0]['year'], $list_of_weeks[0]['week'])));    
            $query .= "from mst_item mi ";
            $query .= "left join (	select dtrandate, ndebitqty, vitemcode from trn_sales sl 
                                    join trn_salesdetail tsd on sl.isalesid=tsd.isalesid
                                    where sl.vtrntype='Transaction' and date(dtrandate) between '{$from}' and '{$to}' 
                                ) sale ON sale.vitemcode = mi.vbarcode ";
		    $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_receivingorderdetail
                                 trn_pod join trn_receivingorder trn_po on trn_po.iroid = trn_pod.iroid 
                                 WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                 GROUP by vbarcode
                               ) tpod on mi.vitemcode=tpod.vbarcode "; 
		    $query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
			                    FROM trn_physicalinventory tpi 
			                    join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
			                    WHERE tpi.estatus = 'close' and
                                date(tpi.dcreatedate) between '{$from}' and '{$to}'
			                    GROUP BY tpid.vbarcode
			                 ) pa on mi.vitemcode=pa.vbarcode ";
            $join_query = false;   
        
        if(isset($data['vendors'])){
            $vendors = $data['vendors'];
            $join_query = true;            
            $query .= $join_query_vendor = ' LEFT JOIN mst_itemvendor miv ON mi.iitemid = miv.iitemid';            
            $where_query_vendor = " (mi.vsuppliercode =$vendors or miv.ivendorid = $vendors) and ";
        } else {
            $join_query_vendor = $where_query_vendor = '';
        }
        
        if(isset($data['departments'])){            
            // if($join_query === false){
            //     $join_query = true;
            //     $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            // }            
            $where_query_department = " mi.vdepcode='".$data['departments']."' and ";
        } else {
            $join_query_department = $where_query_department = '';
        }
        
        if(isset($data['categories'])){            
            // if($join_query === false){
            //     $join_query = true;
            //     $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            // }                
                $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";   
        } else {
            $join_query_category = $where_query_category = '';
        }
        
        if(isset($data['sub_categories'])){            
            // if($join_query === false){
            //     $join_query = true;
            //     $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            // }
                $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
        } else {
            $join_query_subcategory = $where_query_subcategory = '';
        }
        
        if(isset($data['item_name'])){
            // if($join_query === false){
            //     $join_query = true;
            //     $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            // }
                // $term_data = implode(',', $data['item_name']);
                $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
        } else {
            $join_query_itemname = $where_query_itemname = '';
        }
        
        if(isset($data['barcode'])){            
            // if($join_query === false){
            //     $join_query = true;
            //     $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            // }
                // $term_data = implode(',', $data['barcode']);
                $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
        } else {
            $join_query_barcode = $where_query_barcode = '';
        }
        
        if(isset($data['size'])){
            // if($join_query === false){
            //     $join_query = true;
            //     $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            // }
                // $term_data = implode(',', $data['size']);
                $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
        } else {
            $join_query_size = $where_query_size = '';
        }
        
        if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){            
            // if($join_query === false){
            //     $join_query = true;
            //     $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            // }
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
        $where_query = " where ";
        $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
        $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;                    
        $where_query .= " mi.estatus = 'Active' group by trim(mi.vitemname), mi.vsize";
        // if($join_query === false){
        //     $join_query = true;
        //     $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
        // }                    
        $query = $query.$where_query;        
        // echo $query;        die;
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $return = [];
        $return['result'] = json_decode(json_encode($return_data), true);
        $return['header'] = $header;
		return $return;
	}

    public function getYearlyBreakup($data){
      
         $query = "select trim(mi.vitemname) itemname, max(mi.vsize), max(mi.iitemid) iitemid, ";    
         $header[] = 'Sale';
         $header[] = 'Pur';
         $header[] = 'Adj';
         $header[] = 'QoH';         
         $query .= 'TRIM(sum(ndebitqty))+0 as sales, max(ifnull(TRIM(tpod.pur_qty)+0,0)) as purchase, max(ifnull(format(pa_qty, 0), 0)) as adj_qty, ';                     
         $query .= 'max(mi.iqtyonhand) as qoh ';
        
        $from = $data['year'].'-01-01';
        if($data['year'] == date('Y', strtotime('now'))){
            //current year
            // $to = date('Y-m-d', strtotime('now'));
            $to = date('Y-m-d', strtotime('+1 day'));
        } else {
            //previous year
            $to = $data['year'].'-12-31';
        }
        
        $query .= "from mst_item mi ";
        
        $query .= "left join (	select dtrandate, ndebitqty, vitemcode from trn_sales sl 
                                    join trn_salesdetail tsd on sl.isalesid=tsd.isalesid
                                    where sl.vtrntype='Transaction' and date(dtrandate) between '{$from}' and '{$to}' 
                                ) sale ON sale.vitemcode = mi.vbarcode ";
        
        $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_receivingorderdetail
                                 trn_pod join trn_receivingorder trn_po on trn_po.iroid = trn_pod.iroid 
                                 WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                 GROUP by vbarcode
                               ) tpod on mi.vitemcode=tpod.vbarcode "; 
         $query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
                                 FROM trn_physicalinventory tpi 
                                 join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
                                 WHERE tpi.estatus = 'close' and date(tpi.dcreatedate) between '$from' and '$to'
                                 GROUP BY tpid.vbarcode
                              ) pa on mi.vitemcode=pa.vbarcode ";
                
         $join_query = false;                     
         if(isset($data['vendors'])){
            //  $vendors = $data['vendors'];
            //  $join_query = true;
            //  $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';             
             $where_query_vendor = " mi.vsuppliercode =$vendors and ";
         } else {
             $join_query_vendor = $where_query_vendor = '';
         }
         
         if(isset($data['departments'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            //  }             
             $where_query_department = " mi.vdepcode='".$data['departments']."' and ";
 
         } else {
             $join_query_department = $where_query_department = '';
         }
         
         if(isset($data['categories'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }                 
                 $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
         } else {
             $join_query_category = $where_query_category = '';
         }
         if(isset($data['sub_categories'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                 $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";
         } else {
             $join_query_subcategory = $where_query_subcategory = '';
         }
         
         if(isset($data['item_name'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                //  $term_data = implode(',', $data['item_name']);
                 $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
         } else {
             $join_query_itemname = $where_query_itemname = '';
         }
         
         if(isset($data['barcode'])){
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                //  $term_data = implode(',', $data['barcode']);
                 $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                 
         } else {
             $join_query_barcode = $where_query_barcode = '';
         }
         
         if(isset($data['size'])){ 
            //  echo $data['size'];die;
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
             $term_data = implode(',', (array) $data['size']);
                $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                 
         } else {
             $join_query_size = $where_query_size = '';
         }  
         if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }             
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
         $where_query = " where";
         $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
         $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;            
         $where_query .= " mi.estatus = 'Active' GROUP BY trim(vitemname), mi.vsize";
        //  if($join_query === false){
        //      $join_query = true;
        //      $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
        //  }                     
         $query = $query.$where_query;
         
         
        //  echo $query; die;
         
         $return_data = DB::connection('mysql_dynamic')->select($query);       
         $return = [];
         $return['result'] = json_decode(json_encode($return_data), true);
         $return['header'] = $header;
         return $return;
    }
 
    public function getCustomBreakup($data){
         $query = "select mi.iitemid iitemid,trim(mi.vitemname) itemname, mi.vsize, ";
         $header[] = 'Sale';
         $header[] = 'Pur';
         $header[] = 'Adj';
         $header[] = 'QoH';
         $query .= 'TRIM(sum(ndebitqty))+0 as sales, ifnull(TRIM(tpod.pur_qty)+0,0) as purchase, ifnull(format(pa_qty, 0), 0) as adj_qty, ';
         $query .= 'mi.iqtyonhand as qoh ';
         $to = $data['to_date'];         
         $from = $data['from_date'];       
         
        $query .= "from mst_item mi ";
        
        $query .= "left join (	select dtrandate, ndebitqty, vitemcode from trn_sales sl 
                                join trn_salesdetail tsd on sl.isalesid=tsd.isalesid
                                where sl.vtrntype='Transaction' and date(dtrandate) between '{$from}' and '{$to}' 
                            ) sale ON sale.vitemcode = mi.vbarcode ";
            
        $query .= "left join (	SELECT sum(trn_pod.itotalunit) pur_qty, trn_pod.vbarcode FROM trn_receivingorderdetail
                                 trn_pod join trn_receivingorder trn_po on trn_po.iroid = trn_pod.iroid 
                                 WHERE trn_po.estatus='close' AND (trn_po.dreceiveddate) between '{$from}' and '{$to}' 
                                 GROUP by vbarcode
                               ) tpod on mi.vitemcode=tpod.vbarcode ";
         $query .= "left join (	SELECT tpid.vbarcode,sum(tpid.ndebitqty) pa_qty 
                                 FROM trn_physicalinventory tpi 
                                 join trn_physicalinventorydetail tpid on tpi.ipiid=tpid.ipiid 
                                 WHERE tpi.estatus = 'close' and
                                 tpi.dcreatedate between '{$from}' and '{$to}'
                                 GROUP BY tpid.vbarcode
                              ) pa on mi.vitemcode=pa.vbarcode ";         
         
         $join_query = false;
                     
         if(isset($data['vendors'])){
            //  $vendors = $data['vendors'];
            //  $join_query = true;
            //  $query .= $join_query_vendor = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';             
             $where_query_vendor = " mi.vsuppliercode =$vendors and ";
         } else {
             $join_query_vendor = $where_query_vendor = '';
         }
         
         if(isset($data['departments'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_department = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
            //  }             
             $where_query_department = " mi.vdepcode='".$data['departments']."' and ";
 
         } else {
             $join_query_department = $where_query_department = '';
         }
         
         if(isset($data['categories'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_category = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                 $where_query_category = " mi.vcategorycode ='".$data['categories']."' and ";
                 
         } else {
             $join_query_category = $where_query_category = '';
         }
         
         
         if(isset($data['sub_categories'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_subcategory = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }                 
                 $where_query_subcategory = " mi.subcat_id ='".$data['categories']."' and ";   
         } else {
             $join_query_subcategory = $where_query_subcategory = '';
         }
         
         if(isset($data['item_name'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_itemname = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                //  $term_data = implode(',', $data['item_name']);
                 $where_query_itemname = " mi.vitemname LIKE '%".$data['item_name']."%' and ";
                 
         } else {
             $join_query_itemname = $where_query_itemname = '';
         }
         
         if(isset($data['barcode'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_barcode = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                //  $term_data = implode(',', $data['barcode']);
                 $where_query_barcode = " mi.vbarcode LIKE '%".$data['barcode']."%' and ";
                 
         } else {
             $join_query_barcode = $where_query_barcode = '';
         }
         
         if(isset($data['size'])){ 
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_size = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
                // $term_data = implode(',', $data['size']);
                $where_query_size = " mi.vsize LIKE '".$data['size']."%' and ";
                 
         } else {
             $join_query_size = $where_query_size = '';
         }
         
         if(isset($data['price_select_by']) && isset($data['select_by_value_1'])){             
            //  if($join_query === false){
            //      $join_query = true;
            //      $query .= $join_query_price_select_by = ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode ';
            //  }
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
         $where_query = " where ";
         $where_query .= $where_query_vendor.$where_query_department.$where_query_category;
         $where_query .= $where_query_itemname.$where_query_barcode.$where_query_size.$where_query_price;
         $where_query .= "mi.estatus = 'Active' group by trim(mi.vitemname), vsize";
 
        //  if($join_query === false){
        //      $join_query = true;
        //      $query .= ' LEFT JOIN mst_item mi ON tsd.vitemcode = mi.vbarcode';
        //  }
                     
         $query = $query.$where_query;

         $return_data = DB::connection('mysql_dynamic')->select($query);
         $return = [];
         $return['result'] = json_decode(json_encode($return_data), true);
         $return['header'] = $header;
         return $return;
    }	
    
    public function getDepartments() {
		$sql = "SELECT vdepcode as id, vdepartmentname as name FROM mst_department";
        $result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
    }

    public function get_subcategories($category_ids){	    
        $query = "SELECT subcat_id as id, subcat_name as text FROM mst_subcategory WHERE cat_id='".$category_ids."'";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $return = json_decode(json_encode($return_data), true); 	
        return $return;
    }

    public function ajaxDataReportItemGroup($data) {
		$query_i_g = $this->db2->query("SELECT iitemgroupid as id, vitemgroupname as name FROM itemgroup WHERE vitemgroupname='". $data['report_pull_id'] ."'")->row;
		$item_groups = $query_i_g['id'];
		$query = $this->db2->query("CALL rp_profitloss('" . $data['start_date'] . "','".$data['end_date']."','Item Group','".$item_groups."')");
		return $query->rows;
	}

    public function ajaxDataReportDepartment($data) {
        $query_dep = $this->db2->query("SELECT vdepcode as id, vdepartmentname as name FROM mst_department WHERE vdepartmentname='". $data['report_pull_id'] ."'")->row;
        $vdepcodes = $query_dep['id'];              
        $query = $this->db2->query("CALL rp_profitloss('" . $data['start_date'] . "','".$data['end_date']."','Department','".$vdepcodes."')");
        return $query->rows;
    }

    public function ajaxDataReportCategory($data) {
		$query_cat = $this->db2->query("SELECT vcategorycode as id, vcategoryname as name FROM mst_category WHERE vcategoryname='". $data['report_pull_id'] ."'")->row;
		$vcatcodes = $query_cat['id'];        
		$query = $this->db2->query("CALL rp_profitloss('" . $data['start_date'] . "','".$data['end_date']."','Category','".$vcatcodes."')");
		return $query->rows;
	}

    public function getGroups() {
		$sql = "SELECT iitemgroupid as id, vitemgroupname as name FROM itemgroup";
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 	
		return $return;
	}

    public function getDepartments_list() {
		$sql = "SELECT vdepcode as id, vdepartmentname as name FROM mst_department";
		$return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 	
		return $return;
	}

    public function getCategories() {
		$sql = "SELECT vcategorycode as id, vcategoryname as name FROM mst_category";
		$return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 	
		return $return;
	}
    
    public function getBatches($start_date){
	    if($start_date){
	         $fDateTime = DateTime::createFromFormat('Y-m-d', $start_date);
             $fDateString = $fDateTime->format('m-d-Y');
	         $sql="select batchid AS ibatchid  
                    from trn_endofday e 
                    join trn_endofdaydetail d on e.id=d.eodid 
    				where date_format(e.dstartdatetime,'%m-%d-%Y') 
                    between '". $fDateString ."' and  '". $fDateString ."'";

                $return_data = DB::connection('mysql_dynamic')->select($sql);
                $return = json_decode(json_encode($return_data), true); 	
                return $return;    
	    }
	    return array();
	    
    }

    public function get_barcodes($term){
        $term_data = implode(',', $term);
	    $query = "SELECT vbarcode as text, SID FROM mst_item WHERE vbarcode LIKE '".$term_data."%' LIMIT 10";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $return = json_decode(json_encode($return_data), true); 	
        $result = [];
        foreach($return as $v){
            $result[] = $v['text'];
        }
        return $result;
	}

    public function getSizes($size){
	    $term_data = implode(',', $size);
	    $search_string = $term_data.'%';	    
	    $sql = "SELECT vsize as id, vsize as name FROM mst_size WHERE vsize LIKE '".$search_string."'";
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 		
        foreach($return as $v){
            $result[] = $v['name'];
        }        
        return $result;	
	}
    
    public function get_item_names($term1){
        $term = implode(',', $term1);
        $query = "SELECT vitemname as text, SID FROM mst_item WHERE estatus = 'Active' AND vitemname Like '".$term."%' LIMIT 10";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $return = json_decode(json_encode($return_data), true); 
        $result = [];        
        foreach($return as $v){
            $result[] = $v['text'];
        }
        return $result;
	}

    public function getVendors() {
		$sql = "SELECT isupplierid as id, vcompanyname as name FROM mst_supplier WHERE estatus='Active'";
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 	
        return $return;
    }

    public function get_categories($department_ids){	    
        $query = "SELECT vcategorycode as id, vcategoryname as text FROM mst_category WHERE dept_code = '".$department_ids."'";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 
        return $result;
    }    
}