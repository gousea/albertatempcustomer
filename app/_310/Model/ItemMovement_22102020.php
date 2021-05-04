<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ItemMovement extends Model
{
    protected $connection = 'mysql_dynamic';
    
    
    public function getItemMovementReport($data) {

		$start_date = date("Y",strtotime("-1 year"));
		$end_date = date('Y');
		
	$query = "SELECT a.iitemid, a.vitemtype, a.vbarcode, a.vitemname, a.npack, CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND FROM mst_item as a WHERE a.vbarcode='". ($data['search_vbarcode']) ."'";
    //$query = "SELECT a.iitemid, a.vitemtype, a.vbarcode, a.vitemname, a.npack, a.IQTYONHAND IQTYONHAND FROM mst_item as a WHERE a.vbarcode='". ($data['search_vbarcode']) ."'";

		$query_item = DB::connection('mysql_dynamic')->select($query);

		$sql = "SELECT ifnull(SUM(trn_sd.ndebitqty),0) as items_sold, date_format(trn_s.dtrandate,'%m-%Y') as dtrandate, max(trn_sd.npack) FROM trn_salesdetail trn_sd , 
		        trn_sales trn_s WHERE trn_s.vtrntype='Transaction' AND trn_s.isalesid=trn_sd.isalesid AND trn_sd.vitemcode='". ($data['search_vbarcode']) ."' 
		        AND (date_format(trn_s.dtrandate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY date_format(trn_s.dtrandate,'%m-%Y')";
        //echo $sql;die;
		$query_data = DB::connection('mysql_dynamic')->select($sql);
        
        //========change query on 22/05/2020========
        // 	$sql1 = "SELECT ifnull(SUM(tpd.itotalunit),0) as items_receive, group_concat(tp.vponumber) as ponumber, DATE_FORMAT(tp.dcreatedate,'%m-%Y') as dcreatedate, tpd.npackqty as npack FROM trn_purchaseorderdetail tpd LEFT JOIN trn_purchaseorder tp ON (tpd.ipoid = tp.ipoid) WHERE tpd.vitemid='". ($data['search_iitemid']) ."' AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY date_format(tp.dcreatedate,'%m-%d-%Y')";
        $sql1 = "SELECT ifnull(SUM(trd.itotalunit),0) as items_receive, group_concat(tr.vponumber) as ponumber, DATE_FORMAT(tr.dcreatedate,'%m-%Y') as dcreatedate, 
                max(trd.npackqty) as npack FROM trn_receivingorderdetail trd LEFT JOIN trn_receivingorder tr ON (trd.iroid = tr.iroid) 
                WHERE trd.vitemid='". ($data['search_iitemid']) ."' AND (date_format(tr.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') 
                AND tr.vordertype='PO'
                AND tr.estatus='Close'
                GROUP BY date_format(tr.dcreatedate,'%m-%Y')";
                
        //echo $sql1;die;        
        
		$query_data1 = DB::connection('mysql_dynamic')->select($sql1);
		
		
		//query added 09/09/2020 werehose items 
    		$sql_itemid="SELECT vbarcode  FROM mst_item where iitemid='". ($data['search_iitemid']) ."'";
    		$query_vbarcode = DB::connection('mysql_dynamic')->select($sql_itemid);
    		$vbarcode=$query_vbarcode[0]->vbarcode;
		
		
    		 $sql_were_house="SELECT ifnull(SUM(tw.ntransferqty),0) as total_were,DATE_FORMAT(tw.dreceivedate,'%m-%Y') as dcreatedate
    		 FROM trn_warehouseitems tw
    		 where vbarcode='".$vbarcode."'
    		 AND (date_format(tw.dreceivedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."')  AND vtransfertype='POtoWarehouse'
    		 GROUP BY date_format(tw.dreceivedate,'%m-%Y')";
    		 
    		 $were_data= DB::connection('mysql_dynamic')->select($sql_were_house);
    		 
    		 //print_r($were_data);die;
    
		
		
		
		// end were house 
		
		$sql_ajustment ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_adjustment, DATE_FORMAT(tp.dcreatedate,'%m-%Y') as dcreatedate 
		                    FROM trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where vitemid='". ($data['search_iitemid']) ."' 
		                    AND tp.estatus='Close' AND tp.vtype='Adjustment' AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') 
		                    GROUP BY date_format(tp.dcreatedate,'%m-%Y')";
// 		 dd($sql_ajustment);                   
       
        $adjustment_data = DB::connection('mysql_dynamic')->select($sql_ajustment);
        
        
        $sql_ajustment_pysical ="SELECT ifnull(SUM(tpd.ndebitqty),0) as ptotal_adjustment, DATE_FORMAT(tp.dcreatedate,'%m-%Y') as dcreatedate 
		                    FROM trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where vitemid='". ($data['search_iitemid']) ."' 
		                    AND tp.estatus='Close' AND tp.vtype='Physical' AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') 
		                    GROUP BY date_format(tp.dcreatedate,'%m-%Y')";
// 		 dd($sql_ajustment);                   
       
        $ajustment_pysical = DB::connection('mysql_dynamic')->select($sql_ajustment_pysical);
        
        //dd($ajustment_pysical);
        
        //Qoh Histroy (quick upade )
        $sql_qoh ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_qoh, DATE_FORMAT(tp.LastUpdate,'%m-%Y') as dcreatedate 
                    FROM trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where 
                    vitemid='". ($data['search_iitemid']) ."' AND  vtype='Quick Update' AND tp.estatus='Close'
                    AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY date_format(tp.LastUpdate,'%m-%Y')";
        
        $qoh_data = DB::connection('mysql_dynamic')->select($sql_qoh);
        //Qoh
       
        //inventory reset items
        $sql_inv ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_inv, DATE_FORMAT(tp.LastUpdate,'%m-%Y') as dcreatedate 
                    FROM trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where 
                    vitemid='". ($data['search_iitemid']) ."' And  vtype='Inventory Reset' AND tp.estatus='Close'
                    AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') 
                    GROUP BY date_format(tp.LastUpdate,'%m-%Y')";
        $inv_data = DB::connection('mysql_dynamic')->select($sql_inv);
        //inventory reset items End 
        
        //Opening Qoh start
        $sql_oqoh ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_oqoh, DATE_FORMAT(tp.LastUpdate,'%m-%Y') as dcreatedate FROM 
                    trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where 
                    vitemid='". ($data['search_iitemid']) ."' And  vtype='Opening QoH' AND tp.estatus='Close'
                    AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') 
                    GROUP BY date_format(tp.LastUpdate,'%m-%Y')";
        //echo $sql_oqoh;die;
        $oqoh_data = DB::connection('mysql_dynamic')->select($sql_oqoh);
        // dd($oqoh_data);
        
        //perent  qoh start
        $sql_pqoh ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_pqoh, DATE_FORMAT(tp.LastUpdate,'%m-%Y') as dcreatedate 
        FROM trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where vitemid='". ($data['search_iitemid']) ."' 
        And  vtype='Perent Update'  
        AND tp.estatus='Close'
        AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY date_format(tp.LastUpdate,'%m-%Y')";
        $pqoh_data = DB::connection('mysql_dynamic')->select($sql_pqoh);
        //perent QoH Update 
       // dd($pqoh_data);
        
        
        //perent child relationship
        $sql_cqoh ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_cqoh, DATE_FORMAT(tp.LastUpdate,'%m-%Y') as dcreatedate FROM trn_physicalinventorydetail tpd 
                    left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where vitemid='". ($data['search_iitemid']) ."' 
                    And  vtype='Child Update' AND tp.estatus='Close' AND (date_format(tp.dcreatedate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') 
                    GROUP BY date_format(tp.LastUpdate,'%m-%Y')";
            $cqoh_data = DB::connection('mysql_dynamic')->select($sql_cqoh);
        //perent Qoh update end 
        
        
        //Update By Phone Start
        $sql_phqoh ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_phqoh, DATE_FORMAT(tp.LastUpdate,'%m-%Y') as dcreatedate 
        FROM trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid
        where vitemid='". ($data['search_iitemid']) ."' 
        And  vtype='Update By Phone'  
       
        AND (date_format(tp.LastUpdate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY date_format(tp.LastUpdate,'%m-%Y')";
        //echo $sql_phqoh;die;
        $phqoh_data = DB::connection('mysql_dynamic')->select($sql_phqoh);
        //update phone end 
        
        //Opening QoH Start Phone
        $sql_ophqoh ="SELECT ifnull(SUM(tpd.ndebitqty),0) as total_ophqoh, DATE_FORMAT(tp.LastUpdate,'%m-%Y') as dcreatedate 
        FROM trn_physicalinventorydetail tpd left join trn_physicalinventory tp on tp.ipiid = tpd.ipiid where 
        vitemid='". ($data['search_iitemid']) ."' 
        And  vtype='OQohbyphone'  
        AND tp.estatus='Close'
        AND (date_format(tp.LastUpdate,'%Y') BETWEEN '".$start_date."' AND '".$end_date."') GROUP BY date_format(tp.LastUpdate,'%m-%Y')";
        $ophqoh_data = DB::connection('mysql_dynamic')->select($sql_ophqoh);
        //end
        
        
		$year_arr_sold = array();
		$month_year_arr_sold = array();

		$year_arr_receive = array();
		$month_year_arr_receive = array();
        
        //====converting object data into array=====
        $query_data = array_map(function ($value) {
            return (array)$value;
        }, $query_data);
		if(count($query_data) > 0){
            
			foreach ($query_data as $key => $value) {
				$t = explode('-', $value['dtrandate']);

				if(array_key_exists($t[1], $year_arr_sold)){

                    if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                        $year_arr_sold[$t[1]]['total_sold'] = $year_arr_sold[$t[1]]['total_sold'] + ($value['items_sold'] * $value['npack']);
                    }else{
                        $year_arr_sold[$t[1]]['total_sold'] = $year_arr_sold[$t[1]]['total_sold'] + $value['items_sold'];
                    }

					
				}else{

                    if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                        $year_arr_sold[$t[1]]['total_sold'] = ($value['items_sold'] * $value['npack']);
                    }else{
                        $year_arr_sold[$t[1]]['total_sold'] = $value['items_sold'];
                    }
				}

				if(array_key_exists($t[1], $month_year_arr_sold)){
					if(array_key_exists($t[0], $month_year_arr_sold[$t[1]])){

                        if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                            $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] = $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] + ($value['items_sold'] * $value['npack']);
                        }else{
                            $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] = $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] + $value['items_sold'];
                        }

						
					}else{
                        if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                            $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] = ($value['items_sold'] * $value['npack']);
                        }else{
                            $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] = $value['items_sold'];
                        }
					}

				}else{
                    if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                        $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] = ($value['items_sold'] * $value['npack']);
                    }else{
                        $month_year_arr_sold[$t[1]][$t[0]]['total_sold'] = $value['items_sold'];
                    }
					
				}
			}

		}
        
        $query_data1 = array_map(function ($value) {
            return (array)$value;
        }, $query_data1);
		if(count($query_data1) > 0){
			foreach ($query_data1 as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_receive)){

                    if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                        $year_arr_receive[$t[1]]['total_receive'] = $year_arr_receive[$t[1]]['total_receive'] + ($value['items_receive'] * $value['npack']);
                    }else{
                        $year_arr_receive[$t[1]]['total_receive'] = $year_arr_receive[$t[1]]['total_receive'] + $value['items_receive'];
                    }
					
				}else{
                    if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                        $year_arr_receive[$t[1]]['total_receive'] = ($value['items_receive'] * $value['npack']);
                    }else{
                        $year_arr_receive[$t[1]]['total_receive'] = $value['items_receive'];
                    }
				}

				if(array_key_exists($t[1], $month_year_arr_receive)){
					if(array_key_exists($t[0], $month_year_arr_receive[$t[1]])){
                        if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                            $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] = $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] + ($value['items_receive'] * $value['npack']);
                        }else{
                            $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] = $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] + $value['items_receive'];
                        }
					}else{
                        if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                            $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] = ($value['items_receive'] * $value['npack']);
                        }else{
                            $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] = $value['items_receive'];
                        }
					}

				}else{
                    if(isset($query_item['vitemtype']) && $query_item['vitemtype'] == 'Lot Matrix'){
                        $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] = ($value['items_receive'] * $value['npack']);
                    }else{
                        $month_year_arr_receive[$t[1]][$t[0]]['total_receive'] = $value['items_receive'];
                    }
				}
				$month_year_arr_receive[$t[1]][$t[0]]['ponumber'] = $value['ponumber'];
			}

		}
		
		$year_arr_adjustment = array();
		$month_year_arr_adjustment = array();
		
		$adjustment_data = array_map(function ($value) {
            return (array)$value;
        }, $adjustment_data);
        
		if(count($adjustment_data) > 0){
            
			foreach ($adjustment_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_adjustment)){
                    $year_arr_adjustment[$t[1]]['total_adjustment'] = $year_arr_adjustment[$t[1]]['total_adjustment'];
				}else{
                    $year_arr_adjustment[$t[1]]['total_adjustment'] = $value['total_adjustment'];
				}

				if(array_key_exists($t[1], $month_year_arr_adjustment)){
					if(array_key_exists($t[0], $month_year_arr_adjustment[$t[1]])){
                        $month_year_arr_adjustment[$t[1]][$t[0]]['total_adjustment'] = $month_year_arr_adjustment[$t[1]][$t[0]]['total_adjustment'];
					}else{
                        $month_year_arr_adjustment[$t[1]][$t[0]]['total_adjustment'] = $value['total_adjustment'];
					}
				}else{
                    $month_year_arr_adjustment[$t[1]][$t[0]]['total_adjustment'] = $value['total_adjustment'];
				}
			}

		}
		
		//pyscical
		
		$year_arr_adjustment_phy = array();
		$month_year_arr_adjustment_phy = array();
		
		$ajustment_pysical = array_map(function ($value) {
            return (array)$value;
        }, $ajustment_pysical);
        
		if(count($ajustment_pysical) > 0){
            
			foreach ($ajustment_pysical as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_adjustment_phy)){
                    $year_arr_adjustment_phy[$t[1]]['ptotal_adjustment'] = $year_arr_adjustment_phy[$t[1]]['ptotal_adjustment'];
				}else{
                    $year_arr_adjustment_phy[$t[1]]['ptotal_adjustment'] = $value['ptotal_adjustment'];
				}

				if(array_key_exists($t[1], $month_year_arr_adjustment_phy)){
					if(array_key_exists($t[0], $month_year_arr_adjustment_phy[$t[1]])){
                        $month_year_arr_adjustment_phy[$t[1]][$t[0]]['ptotal_adjustment'] = $month_year_arr_adjustment_phy[$t[1]][$t[0]]['ptotal_adjustment'];
					}else{
                        $month_year_arr_adjustment_phy[$t[1]][$t[0]]['ptotal_adjustment'] = $value['ptotal_adjustment'];
					}
				}else{
                    $month_year_arr_adjustment_phy[$t[1]][$t[0]]['ptotal_adjustment'] = $value['ptotal_adjustment'];
				}
			}

		}
		//qoh histroy 
		$year_arr_qoh = array();
		$month_year_arr_qoh = array();
	    
	    $qoh_data = array_map(function ($value) {
            return (array)$value;
        }, $qoh_data);
		if(count($qoh_data) > 0){
            
			foreach ($qoh_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_qoh)){
                    $year_arr_qoh[$t[1]]['total_qoh'] = $year_arr_qoh[$t[1]]['total_qoh'];
				}else{
                    $year_arr_qoh[$t[1]]['total_qoh'] = $value['total_qoh'];
				}

				if(array_key_exists($t[1], $month_year_arr_qoh)){
					if(array_key_exists($t[0], $month_year_arr_qoh[$t[1]])){
                        $month_year_arr_qoh[$t[1]][$t[0]]['total_qoh'] = $month_year_arr_qoh[$t[1]][$t[0]]['total_qoh'];
					}else{
                        $month_year_arr_qoh[$t[1]][$t[0]]['total_qoh'] = $value['total_qoh'];
					}
				}else{
                    $month_year_arr_qoh[$t[1]][$t[0]]['total_qoh'] = $value['total_qoh'];
				}
			}

		}
		//qoh
		
		//inventory item update
		$year_arr_inv = array();
		$month_year_arr_inv = array();
		
		$inv_data = array_map(function ($value) {
            return (array)$value;
        }, $inv_data);
		if(count($inv_data) > 0){
            
			foreach ($inv_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_inv)){
                    $year_arr_inv[$t[1]]['total_inv'] = $year_arr_inv[$t[1]]['total_inv'];
				}else{
                    $year_arr_inv[$t[1]]['total_inv'] = $value['total_inv'];
				}

				if(array_key_exists($t[1], $month_year_arr_inv)){
					if(array_key_exists($t[0], $month_year_arr_inv[$t[1]])){
                        $month_year_arr_inv[$t[1]][$t[0]]['total_inv'] = $month_year_arr_inv[$t[1]][$t[0]]['total_inv'];
					}else{
                        $month_year_arr_inv[$t[1]][$t[0]]['total_inv'] = $value['total_inv'];
					}
				}else{
                    $month_year_arr_inv[$t[1]][$t[0]]['total_inv'] = $value['total_inv'];
				}
			}

		}
		//inventory end
		
		//opening qoh start
		$year_arr_oqoh = array();
		$month_year_arr_oqoh = array();
        	
        $oqoh_data = array_map(function ($value) {
            return (array)$value;
        }, $oqoh_data);
		if(count($oqoh_data) > 0){
            
			foreach ($oqoh_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_oqoh)){
                    $year_arr_oqoh[$t[1]]['total_oqoh'] = $year_arr_oqoh[$t[1]]['total_oqoh'];
				}else{
                    $year_arr_oqoh[$t[1]]['total_oqoh'] = $value['total_oqoh'];
				}

				if(array_key_exists($t[1], $month_year_arr_oqoh)){
					if(array_key_exists($t[0], $month_year_arr_oqoh[$t[1]])){
                        $month_year_arr_oqoh[$t[1]][$t[0]]['total_oqoh'] = $month_year_arr_oqoh[$t[1]][$t[0]]['total_oqoh'];
					}else{
                        $month_year_arr_oqoh[$t[1]][$t[0]]['total_oqoh'] = $value['total_oqoh'];
					}
				}else{
                    $month_year_arr_oqoh[$t[1]][$t[0]]['total_oqoh'] = $value['total_oqoh'];
				}
			}

		}
		//opening qoh end
		
		
		//perent child relationship Start
		$year_arr_pqoh = array();
		$month_year_arr_pqoh = array();
		
		$pqoh_data = array_map(function ($value) {
            return (array)$value;
        }, $pqoh_data);
		if(count($pqoh_data) > 0){
            
			foreach ($pqoh_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);
              //dd($year_arr_qoh);
				if(array_key_exists($t[1], $year_arr_pqoh)){
                    $year_arr_pqoh[$t[1]]['total_pqoh'] = $year_arr_pqoh[$t[1]]['total_pqoh'];
				}else{
                    $year_arr_pqoh[$t[1]]['total_pqoh'] = $value['total_pqoh'];
				}

				if(array_key_exists($t[1], $month_year_arr_pqoh)){
					if(array_key_exists($t[0], $month_year_arr_pqoh[$t[1]])){
                        $month_year_arr_pqoh[$t[1]][$t[0]]['total_pqoh'] = $month_year_arr_pqoh[$t[1]][$t[0]]['total_pqoh'];
					}else{
                        $month_year_arr_pqoh[$t[1]][$t[0]]['total_pqoh'] = $value['total_pqoh'];
					}
				}else{
                    $month_year_arr_pqoh[$t[1]][$t[0]]['total_pqoh'] = $value['total_pqoh'];
				}
			}

		}
		
		
		//perent child relationship end
		
		
		$year_arr_cqoh = array();
		$month_year_arr_cqoh = array();
		
		$cqoh_data = array_map(function ($value) {
            return (array)$value;
        }, $cqoh_data);
		if(count($cqoh_data) > 0){
            
			foreach ($cqoh_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_cqoh)){
                    $year_arr_cqoh[$t[1]]['total_cqoh'] = $year_arr_cqoh[$t[1]]['total_cqoh'];
				}else{
                    $year_arr_cqoh[$t[1]]['total_cqoh'] = $value['total_cqoh'];
				}

				if(array_key_exists($t[1], $month_year_arr_cqoh)){
					if(array_key_exists($t[0], $month_year_arr_cqoh[$t[1]])){
                        $month_year_arr_cqoh[$t[1]][$t[0]]['total_cqoh'] = $month_year_arr_cqoh[$t[1]][$t[0]]['total_cqoh'];
					}else{
                        $month_year_arr_cqoh[$t[1]][$t[0]]['total_cqoh'] = $value['total_cqoh'];
					}
				}else{
                    $month_year_arr_cqoh[$t[1]][$t[0]]['total_cqoh'] = $value['total_cqoh'];
				}
			}

		}
		
		//update qoh by phone
		$year_arr_phqoh = array();
		$month_year_arr_phqoh = array();
		
		$phqoh_data = array_map(function ($value) {
            return (array)$value;
        }, $phqoh_data);
		if(count($phqoh_data) > 0){
            
			foreach ($phqoh_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_phqoh)){
                    $year_arr_phqoh[$t[1]]['total_phqoh'] = $year_arr_phqoh[$t[1]]['total_phqoh'];
				}else{
                    $year_arr_phqoh[$t[1]]['total_phqoh'] = $value['total_phqoh'];
				}

				if(array_key_exists($t[1], $month_year_arr_phqoh)){
					if(array_key_exists($t[0], $month_year_arr_phqoh[$t[1]])){
                        $month_year_arr_phqoh[$t[1]][$t[0]]['total_phqoh'] = $month_year_arr_phqoh[$t[1]][$t[0]]['total_phqoh'];
					}else{
                        $month_year_arr_phqoh[$t[1]][$t[0]]['total_phqoh'] = $value['total_phqoh'];
					}
				}else{
                    $month_year_arr_phqoh[$t[1]][$t[0]]['total_phqoh'] = $value['total_phqoh'];
				}
			}

		}
		//
		$year_arr_ophqoh = array();
		$month_year_arr_ophqoh = array();
		
		$ophqoh_data = array_map(function ($value) {
            return (array)$value;
        }, $ophqoh_data);
		if(count($ophqoh_data) > 0){
            
			foreach ($ophqoh_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_ophqoh)){
                    $year_arr_ophqoh[$t[1]]['total_ophqoh'] = $year_arr_ophqoh[$t[1]]['total_ophqoh'];
				}else{
                    $year_arr_ophqoh[$t[1]]['total_ophqoh'] = $value['total_ophqoh'];
				}

				if(array_key_exists($t[1], $month_year_arr_ophqoh)){
					if(array_key_exists($t[0], $month_year_arr_ophqoh[$t[1]])){
                        $month_year_arr_ophqoh[$t[1]][$t[0]]['total_ophqoh'] = $month_year_arr_ophqoh[$t[1]][$t[0]]['total_ophqoh'];
					}else{
                        $month_year_arr_ophqoh[$t[1]][$t[0]]['total_ophqoh'] = $value['total_ophqoh'];
					}
				}else{
                    $month_year_arr_ophqoh[$t[1]][$t[0]]['total_ophqoh'] = $value['total_ophqoh'];
				}
			}

		}
		//were house
		$year_arr_were = array();
		$month_year_arr_were = array();
		
		$were_data = array_map(function ($value) {
            return (array)$value;
        }, $were_data);
		if(count($were_data) > 0){
            
			foreach ($were_data as $key => $value) {
				$t = explode('-', $value['dcreatedate']);

				if(array_key_exists($t[1], $year_arr_were)){
                    $year_arr_were[$t[1]]['total_were'] = $year_arr_were[$t[1]]['total_were'];
				}else{
                    $year_arr_were[$t[1]]['total_were'] = $value['total_were'];
				}

				if(array_key_exists($t[1], $month_year_arr_were)){
					if(array_key_exists($t[0], $month_year_arr_were[$t[1]])){
                        $month_year_arr_were[$t[1]][$t[0]]['total_were'] = $month_year_arr_were[$t[1]][$t[0]]['total_were'];
					}else{
                        $month_year_arr_were[$t[1]][$t[0]]['total_were'] = $value['total_were'];
					}
				}else{
                    $month_year_arr_were[$t[1]][$t[0]]['total_were'] = $value['total_were'];
				}
			}

		}
		
		//
		$return = array();
		
		$query_item = array_map(function ($value) {
            return (array)$value;
        }, $query_item);
        
		$return['item_data'] = $query_item;
		$return['year_arr_sold'] = $year_arr_sold;
		$return['year_arr_receive'] = $year_arr_receive;
		$return['month_year_arr_sold'] = $month_year_arr_sold;
		$return['month_year_arr_receive'] = $month_year_arr_receive;
		
		$return['year_arr_adjustment'] = $year_arr_adjustment;
		$return['month_year_arr_adjustment'] = $month_year_arr_adjustment;
		
		$return['year_arr_adjustment_phy'] = $year_arr_adjustment_phy;
		$return['month_year_arr_adjustment_phy'] = $month_year_arr_adjustment_phy;
	//	dd($return['month_year_arr_adjustment_phy'] );
		//Qoh  update Histroy 
		$return['year_arr_qoh'] = $year_arr_qoh;
		$return['month_year_arr_qoh'] = $month_year_arr_qoh; 
		//qoh Update Histroy
		
		//inventory dupadte return 
		$return['year_arr_inv'] = $year_arr_inv;
		$return['month_year_arr_inv'] = $month_year_arr_inv; 
		//end
		
		//opening qoh start
		$return['year_arr_oqoh'] = $year_arr_oqoh;
		//print_r($return['year_arr_oqoh']);die;
		$return['month_year_arr_oqoh'] = $month_year_arr_oqoh;
		//pening qoh end
		
		$return['year_arr_pqoh'] = $year_arr_pqoh;
		$return['month_year_arr_pqoh'] = $month_year_arr_pqoh;
		
		$return['year_arr_cqoh'] = $year_arr_cqoh;
		$return['month_year_arr_cqoh'] = $month_year_arr_cqoh;
		
		$return['year_arr_phqoh'] = $year_arr_phqoh;
		$return['month_year_arr_phqoh'] = $month_year_arr_phqoh;
		
		$return['year_arr_ophqoh'] = $year_arr_ophqoh;
		$return['month_year_arr_ophqoh'] = $month_year_arr_ophqoh;
		
		$return['year_arr_were'] = $year_arr_were;
		$return['month_year_arr_were'] = $month_year_arr_were;
		
		
		return $return;
	}
	
	public function getItemMovementData($vbarcode,$start_date,$end_date,$data_by) {

        $start_date = \DateTime::createFromFormat('m-d-Y', $start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = \DateTime::createFromFormat('m-d-Y', $end_date);
        $end_date = $end_date->format('Y-m-d');
        
        
        switch ($data_by) {
           
        
            case "sold":
                $sql = "SELECT trn_sd.idettrnid as idettrnid,trn_sd.isalesid as isalesid,trn_sd.ndebitqty as items_count, date_format(trn_s.dtrandate,'%m-%d-%Y %h:%i %p') as ddate, trn_sd.npack as npack, trn_sd.vsize as size, trn_sd.nunitprice as dunitprice, trn_sd.ndebitqty as total_qoh, trn_sd.nextunitprice as total_price  FROM trn_salesdetail trn_sd , trn_sales trn_s WHERE trn_s.vtrntype='Transaction' AND trn_s.isalesid=trn_sd.isalesid AND trn_sd.vitemcode='". ($vbarcode) ."' AND date_format(trn_s.dtrandate,'%Y-%m-%d') >= '".$start_date."' AND date_format(trn_s.dtrandate,'%Y-%m-%d') <= '".$end_date."'";
                $query_data = DB::connection('mysql_dynamic')->select($sql);
        
                break;
        
        
            case "receive":
                
                //=======change query on 22/05/2020====
                //  	$sql1 = "SELECT tp.vponumber as ponumber, tpd.itotalunit as items_count, tpd.npackqty as npack, tpd.vsize as size, tpd.nordunitprice as dunitprice,tpd.itotalunit as total_qoh , (tpd.nordunitprice * tpd.itotalunit) as total_price, DATE_FORMAT(tp.dcreatedate,'%m-%d-%Y %h:%i %p') as ddate FROM trn_purchaseorderdetail tpd LEFT JOIN trn_purchaseorder tp ON (tpd.ipoid = tp.ipoid) WHERE tpd.vbarcode='". ($vbarcode) ."' AND date_format(tp.dcreatedate,'%Y-%m-%d') >= '".$start_date."' AND date_format(tp.dcreatedate,'%Y-%m-%d') <= '".$end_date."'";
                
                $sql1 = "SELECT tr.vponumber as ponumber, trd.itotalunit as items_count, trd.npackqty as npack, trd.vsize as size, 
                trd.nordunitprice as dunitprice,trd.itotalunit as total_qoh , (trd.nordunitprice * trd.itotalunit) as total_price,
                DATE_FORMAT(tr.dcreatedate,'%m-%d-%Y %h:%i %p') as ddate FROM trn_receivingorderdetail trd LEFT JOIN trn_receivingorder tr 
                ON (trd.iroid = tr.iroid) WHERE trd.vbarcode='". ($vbarcode) ."' 
                AND tr.vordertype='PO'
                AND tr.estatus='Close'
                AND date_format(tr.dcreatedate,'%Y-%m-%d') >= '".$start_date."' AND date_format(tr.dcreatedate,'%Y-%m-%d') <= '".$end_date."'";
                
                $query_data = DB::connection('mysql_dynamic')->select($sql1);
                break;
                
            case "openingqoh":
                $sql_itemid = "SELECT iitemid FROM mst_item WHERE vbarcode = '". ($vbarcode) ."'";
                $itemid = DB::connection('mysql_dynamic')->select($sql_itemid);
                $itemid = $itemid[0]->iitemid;
                
                $sql1 = "SELECT vtype,ifnull(tp.vrefnumber,0) as vrefnumber,tpd.ndebitqty as items_count, DATE_FORMAT(tp.LastUpdate,'%m-%d-%Y %h:%i %p') as ddate
                FROM trn_physicalinventorydetail tpd LEFT JOIN trn_physicalinventory tp ON (tpd.ipiid = tp.ipiid) 
                And  vtype='Opening QoH'  WHERE tpd.vitemid='". ($itemid) ."' AND date_format(tp.LastUpdate,'%Y-%m-%d') >= '".$start_date."' 
                AND date_format(tp.LastUpdate,'%Y-%m-%d') <= '".$end_date."'";
                $query_data = DB::connection('mysql_dynamic')->select($sql1);
                break;
                
            case "adjustment":
                $sql_itemid = "SELECT iitemid FROM mst_item WHERE vbarcode = '". ($vbarcode) ."'";
                $itemid = DB::connection('mysql_dynamic')->select($sql_itemid);
                $itemid = $itemid[0]->iitemid;
                
                $sql1 = "SELECT vtype,ifnull(tp.vrefnumber,0) as vrefnumber,tpd.ndebitqty as items_count, DATE_FORMAT(tp.LastUpdate,'%m-%d-%Y %h:%i %p') as ddate
                FROM trn_physicalinventorydetail tpd LEFT JOIN trn_physicalinventory tp ON (tpd.ipiid = tp.ipiid) 
                and  vtype in('Quick Update','Perent Update','Inventory Reset','Child Update','Adjustment','Physical')  WHERE tpd.vitemid='". ($itemid) ."' AND date_format(tp.LastUpdate,'%Y-%m-%d') >= '".$start_date."' 
                AND date_format(tp.LastUpdate,'%Y-%m-%d') <= '".$end_date."'";
                
                $query_data = DB::connection('mysql_dynamic')->select($sql1);
              
                break;
                case "phoneadjustment":
                $sql_itemid = "SELECT iitemid FROM mst_item WHERE vbarcode = '". ($vbarcode) ."'";
                $itemid = DB::connection('mysql_dynamic')->select($sql_itemid);
                $itemid = $itemid[0]->iitemid;
                
                $sql1 = "SELECT ifnull(tp.vrefnumber,0) as vrefnumber,tpd.ndebitqty as items_count, DATE_FORMAT(tp.LastUpdate,'%m-%d-%Y %h:%i %p') as ddate
                FROM trn_physicalinventorydetail tpd LEFT JOIN trn_physicalinventory tp ON (tpd.ipiid = tp.ipiid) 
                And  vtype='Update By Phone'  WHERE tpd.vitemid='". ($itemid) ."' AND date_format(tp.LastUpdate,'%Y-%m-%d') >= '".$start_date."' 
                AND date_format(tp.LastUpdate,'%Y-%m-%d') <= '".$end_date."'";
           
                $query_data = DB::connection('mysql_dynamic')->select($sql1);
                break;
                case "openingqohphone":
                $sql_itemid = "SELECT iitemid FROM mst_item WHERE vbarcode = '". ($vbarcode) ."'";
                $itemid = DB::connection('mysql_dynamic')->select($sql_itemid);
                $itemid = $itemid[0]->iitemid;
                
                $sql1 = "SELECT ifnull(tp.vrefnumber,0) as vrefnumber,tpd.ndebitqty as items_count, DATE_FORMAT(tp.LastUpdate,'%m-%d-%Y %h:%i %p') as ddate
                FROM trn_physicalinventorydetail tpd LEFT JOIN trn_physicalinventory tp ON (tpd.ipiid = tp.ipiid) 
                And  vtype='OQohbyphone'  WHERE tpd.vitemid='". ($itemid) ."' AND date_format(tp.LastUpdate,'%Y-%m-%d') >= '".$start_date."' 
                AND date_format(tp.LastUpdate,'%Y-%m-%d') <= '".$end_date."'";
                
                $query_data = DB::connection('mysql_dynamic')->select($sql1);
                break;
                
                
       }
            
        return $query_data;
    }
    
    public function getSalesById($salesid) {
        
        $sql = "SELECT *,date_format(dtrandate,'%m-%d-%Y %h:%i %p') as trandate FROM trn_sales WHERE isalesid = ". $salesid;
        
        $query = DB::connection('mysql_dynamic')->select($sql);
        $query = isset($query[0])?(array)$query[0]:[];
        
        return $query;
    }
    
    public function getSalesPerview($idettrnid){
        
        $query=DB::connection('mysql_dynamic')->select("select * from trn_salesdetail a  where a.idettrnid=".$idettrnid);   
        $query = isset($query[0])?(array)$query[0]:[];
        
        return $query;    
    }
    
    public function getSalesByCustomer($icustomerid) {
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_customer WHERE icustomerid='" .(int)$icustomerid. "'");
        $query = isset($query[0])?(array)$query[0]:[];
        
        return $query;
    }
    
}

?>