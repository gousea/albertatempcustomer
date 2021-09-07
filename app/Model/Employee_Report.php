<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee_Report extends Model
{
    protected $connection = 'mysql_dynamic';

    public function getCategoriesReport($sdate,$edate) {
        $data['start_date'] = $sdate;
    
        $data['end_date'] = $edate;
    
        $sql="SELECT trn_s.vusername as vusername, (case when trn_s.vtendertype LIKE '%Return%' then 'Return' else trn_s.vtrntype end)as TrnType, trn_sd.isalesid as isalesid, date_format(trn_s.dtrandate,'%m-%d-%Y %H:%i:%s') as trn_date_time, trn_sd.vitemname as vitemname, trn_sd.nextunitprice as nextunitprice, trn_s.iuserid as iuserid, trn_sd.idettrnid as idettrnid, trn_sd.vitemcode as vitemcode, trn_sd.ndebitqty as ndebitqty, trn_sd.nunitprice as nunitprice, 'First Query' as QR 
            FROM trn_sales trn_s left join trn_salesdetail trn_sd on trn_s.isalesid=trn_sd.isalesid 
            where trn_s.ibatchid in                 
                            (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
            where date_format(e.dstartdatetime,'%m-%d-%Y') between '".$data['start_date']."'  AND '".$data['end_date']."')
            
            AND ((trn_s.vtrntype in ('Void','No Sale')) OR (trn_s.vtendertype LIKE '%Return%'))
            
            AND date_format(trn_s.dtrandate,'%m-%d-%Y') between '".$data['start_date']."'  AND '".$data['end_date']."'
            
            union all 
            
            select mu.vfname as vusername, 'Delete' as TrnType, mdi.id as isalesid, date_format(mdi.LastUpdate,'%m-%d-%Y %H:%i:%s') as trn_date_time, 
            mdi.itemname as vitemname, mdi.extprice as nextunitprice, mdi.userid as iuserid, mdi.id as idettrnid, 
            barcode as vitemcode, qty as ndebitqty, unitprice as nunitprice, 'Second Query' as QR 
            from mst_deleteditem mdi LEFT JOIN mst_user mu ON(mu.iuserid=mdi.userid) 
            where 
            batchid in (select d.batchid from trn_endofday e join trn_endofdaydetail d on e.id=d.eodid 
            where date_format(e.dstartdatetime,'%m-%d-%Y') between '".$data['start_date']."'  AND '".$data['end_date']."')
            order by trn_date_time";
            // date_format(mdi.LastUpdate,'%m-%d-%Y') >= '".$data['start_date']."'  AND date_format(mdi.LastUpdate,'%m-%d-%Y') <=  '".$data['end_date']."'
            // order by trn_date_time";
            
            
            // dd($sql);
    
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 	
        return $return;

	}
	
	public function getStore(){
       
        $sql="SELECT * FROM mst_store";
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($return_data), true); 
        return $result;
    }


}