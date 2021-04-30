<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EndOfDayShift extends Model
{
    public function getBatches($data = array()) {
        $sql = "SELECT ibatchid, vbatchname FROM trn_batch WHERE estatus='Close' AND endofday='0' AND ibatchid NOT IN (SELECT DISTINCT batchid FROM trn_endofdaydetail)";
    //   die;
        $query = DB::connection('mysql_dynamic')->select($sql);
        return $query;
    }
    public function editlist($data = array()) {
        $success =array();
        $error =array();
        $start_date = $data['start_date'];
        $dstartdatetime = \DateTime::createFromFormat('m-d-Y', $data['start_date']);
        $dstartdatetime = $dstartdatetime->format('Y-m-d');
        $denddatetime = \DateTime::createFromFormat('m-d-Y', $data['start_date']);
        $denddatetime = $denddatetime->format('Y-m-d');
        $dstartdatetime = $dstartdatetime.' '.date('H:i:s');
        $denddatetime = $denddatetime.' '.date('H:i:s');
        $year = \DateTime::createFromFormat('m-d-Y', $data['start_date']);
        $year = $year->format('y');
        $month = \DateTime::createFromFormat('m-d-Y', $data['start_date']);
        $month = $month->format('m');
        $day = \DateTime::createFromFormat('m-d-Y', $data['start_date']);
        $day = $day->format('d');
        $auto_inc_id = $year.''.$month.''.$day.'101'; 
        
        //=====disabled because to avoid conflict in primary key id=====
        // $check_query = "SELECT * FROM trn_endofday WHERE date_format(dstartdatetime,'%m-%d-%Y')='". $start_date ."'";
        $check_query = "SELECT * FROM trn_endofday WHERE id ='". $auto_inc_id ."'";
        $check_exist = DB::connection('mysql_dynamic')->select($check_query);
            // dd($check_exist);
        if(count($check_exist) > 0){
            $check_exist = json_decode(json_encode($check_exist), true)[0];
        } else {
            $check_exist = [];
        }
        
        $batches = array_values(array_filter($data['batch'], function($value) { return trim($value) !== ''; }));
        $batch_ids = implode(',', $batches);
        if(count($check_exist) > 0){
            $exist_batch_ids = DB::connection('mysql_dynamic')->select("SELECT batchid FROM trn_endofdaydetail WHERE eodid='". $check_exist['id'] ."'");
            $exist_batch_ids = json_decode(json_encode($exist_batch_ids), true);
            $old_batch_ids = array();
            if(count($exist_batch_ids) > 0){
                foreach ($exist_batch_ids as $k => $v) {
                    $old_batch_ids[] = $v['batchid'];
                }
            }
            $new_batch_ids = array();
            foreach ($batches as $new_v) {
                if(!in_array($new_v, $old_batch_ids)){
                    $new_batch_ids[]= $new_v;
                } 
            }
            if(count($new_batch_ids) > 0){
                $batch_ids_new = implode(',', $new_batch_ids);
                $batch_data = DB::connection('mysql_dynamic')->select("SELECT ifnull(SUM(nnetsales),0.00) as nnetsales, ifnull(SUM(nnetpaidout),0.00) as nnetpaidout, ifnull(SUM(nnetcashpickup),0.00) as nnetcashpickup, ifnull(SUM(nopeningbalance),0.00) as nopeningbalance, ifnull(SUM(nclosingbalance),0.00) as nclosingbalance, ifnull(SUM(nuserclosingbalance),0.00) as nuserclosingbalance, ifnull(SUM(nnetaddcash),0.00) as nnetaddcash, ifnull(SUM(ntotalnontaxable),0.00) as ntotalnontaxable, ifnull(SUM(ntotaltaxable),0.00) as ntotaltaxable, ifnull(SUM(ntotalsales),0.00) as ntotalsales, ifnull(SUM(ntotaltax),0.00) as ntotaltax, ifnull(SUM(ntotalcreditsales),0.00) as ntotalcreditsales, ifnull(SUM(ntotalcashsales),0.00) as ntotalcashsales, ifnull(SUM(ntotalgiftsales),0.00) as ntotalgiftsales, ifnull(SUM(ntotalchecksales),0.00) as ntotalchecksales, ifnull(SUM(ntotalreturns),0.00) as ntotalreturns, ifnull(SUM(ntotaldiscount),0.00) as ntotaldiscount, ifnull(SUM(ntotaldebitsales),0.00) as ntotaldebitsales, ifnull(SUM(ntotalebtsales),0.00) as ntotalebtsales FROM trn_batch WHERE ibatchid IN($batch_ids_new)");
                 $batch_data = json_decode(json_encode($batch_data), true)[0];
                DB::connection('mysql_dynamic')->update("UPDATE trn_endofday SET nnetsales =nnetsales+ '" . ($batch_data['nnetsales']) . "', nnetpaidout =nnetpaidout+ '" . ($batch_data['nnetpaidout']) . "', nnetcashpickup =nnetcashpickup+ '" . ($batch_data['nnetcashpickup']) . "', dstartdatetime = '" . ($dstartdatetime) . "', denddatetime = '" . ($denddatetime) . "', nopeningbalance =nopeningbalance+ '" . ($batch_data['nopeningbalance']) . "', nclosingbalance =nclosingbalance+ '" . ($batch_data['nclosingbalance']) . "', nuserclosingbalance =nuserclosingbalance+ '" . ($batch_data['nuserclosingbalance']) . "', nnetaddcash =nnetaddcash+ '" . ($batch_data['nnetaddcash']) . "',  ntotalnontaxable =ntotalnontaxable+ '" . ($batch_data['ntotalnontaxable']) . "', ntotaltaxable =ntotaltaxable+ '" . ($batch_data['ntotaltaxable']) . "', ntotalsales =ntotalsales+ '" . ($batch_data['ntotalsales']) . "', ntotaltax =ntotaltax+ '" . ($batch_data['ntotaltax']) . "', ntotalcreditsales =ntotalcreditsales+ '" . ($batch_data['ntotalcreditsales']) . "', ntotalcashsales =ntotalcashsales+ '" . ($batch_data['ntotalcashsales']) . "', ntotalgiftsales =ntotalgiftsales+ '" . ($batch_data['ntotalgiftsales']) . "', ntotalchecksales =ntotalchecksales+ '" . ($batch_data['ntotalchecksales']) . "', ntotalreturns =ntotalreturns+ '" . ($batch_data['ntotalreturns']) . "', ntotaldiscount =ntotaldiscount+ '" . ($batch_data['ntotaldiscount']) . "', ntotaldebitsales =ntotaldebitsales+ '" . ($batch_data['ntotaldebitsales']) . "', ntotalebtsales =ntotalebtsales+ '" . ($batch_data['ntotalebtsales']) . "' WHERE id='". $check_exist['id'] ."'");
                foreach ($new_batch_ids as $key_id => $new_batch_ids_value) {
                    
                    $result = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_endofdaydetail WHERE batchid = '" . ($new_batch_ids_value) . "'");
                    // $result = json_decode(json_encode($result), true)[0];
                    
                    $check_batch = DB::connection('mysql_dynamic')->select("SELECT ibatchid FROM trn_batch WHERE estatus = 'Close' AND endofday = '1' AND ibatchid = '".$new_batch_ids_value."'");
                    // $check_batch = json_decode(json_encode($check_batch), true)[0];
                    
                    if(count($result) < 1 && count($check_batch) < 1){
                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_endofdaydetail SET eodid = '" . ($check_exist['id']) . "', batchid = '" . ($new_batch_ids_value) . "',SID = '" . (int)(session()->get('sid'))."'");
                    }
                    DB::connection('mysql_dynamic')->update("UPDATE trn_batch SET endofday = '1' WHERE ibatchid='". ($new_batch_ids_value) ."'");
                }
            }
        }else{
            $batch_data_query = "SELECT ifnull(SUM(nnetsales),0.00) as nnetsales, ifnull(SUM(nnetpaidout),0.00) as nnetpaidout, ifnull(SUM(nnetcashpickup),0.00) as nnetcashpickup, ifnull(SUM(nopeningbalance),0.00) as nopeningbalance, ifnull(SUM(nclosingbalance),0.00) as nclosingbalance, ifnull(SUM(nuserclosingbalance),0.00) as nuserclosingbalance, ifnull(SUM(nnetaddcash),0.00) as nnetaddcash, ifnull(SUM(ntotalnontaxable),0.00) as ntotalnontaxable, ifnull(SUM(ntotaltaxable),0.00) as ntotaltaxable, ifnull(SUM(ntotalsales),0.00) as ntotalsales, ifnull(SUM(ntotaltax),0.00) as ntotaltax, ifnull(SUM(ntotalcreditsales),0.00) as ntotalcreditsales, ifnull(SUM(ntotalcashsales),0.00) as ntotalcashsales, ifnull(SUM(ntotalgiftsales),0.00) as ntotalgiftsales, ifnull(SUM(ntotalchecksales),0.00) as ntotalchecksales, ifnull(SUM(ntotalreturns),0.00) as ntotalreturns, ifnull(SUM(ntotaldiscount),0.00) as ntotaldiscount, ifnull(SUM(ntotaldebitsales),0.00) as ntotaldebitsales, ifnull(SUM(ntotalebtsales),0.00) as ntotalebtsales FROM trn_batch WHERE ibatchid IN($batch_ids)";
            $batch_data = DB::connection('mysql_dynamic')->select($batch_data_query);
            $batch_data = json_decode(json_encode($batch_data), true)[0];
            
            $insert_query = "INSERT INTO trn_endofday SET id='". $auto_inc_id ."', nnetsales = '" . ($batch_data['nnetsales']) . "', nnetpaidout = '" . ($batch_data['nnetpaidout']) . "', nnetcashpickup = '" . ($batch_data['nnetcashpickup']) . "', dstartdatetime = '" . ($dstartdatetime) . "', denddatetime = '" . ($denddatetime) . "', nopeningbalance = '" . ($batch_data['nopeningbalance']) . "', nclosingbalance = '" . ($batch_data['nclosingbalance']) . "', nuserclosingbalance = '" . ($batch_data['nuserclosingbalance']) . "', nnetaddcash = '" . ($batch_data['nnetaddcash']) . "',  ntotalnontaxable = '" . ($batch_data['ntotalnontaxable']) . "', ntotaltaxable = '" . ($batch_data['ntotaltaxable']) . "', ntotalsales = '" . ($batch_data['ntotalsales']) . "', ntotaltax = '" . ($batch_data['ntotaltax']) . "', ntotalcreditsales = '" . ($batch_data['ntotalcreditsales']) . "', ntotalcashsales = '" . ($batch_data['ntotalcashsales']) . "', ntotalgiftsales = '" . ($batch_data['ntotalgiftsales']) . "', ntotalchecksales = '" . ($batch_data['ntotalchecksales']) . "', ntotalreturns = '" . ($batch_data['ntotalreturns']) . "', ntotaldiscount = '" . ($batch_data['ntotaldiscount']) . "', ntotaldebitsales = '" . ($batch_data['ntotaldebitsales']) . "', ntotalebtsales = '" . ($batch_data['ntotalebtsales']) . "' ,SID = '" . (int)(session()->get('sid'))."'";
            DB::connection('mysql_dynamic')->insert($insert_query);
            // $last_id = $this->db2->getLastId();
            foreach ($batches as $key => $value) {
                
                $result = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_endofdaydetail WHERE batchid = '" . ($value) . "'");
                // $result = json_decode(json_encode($result), true)[0];
                
                $check_batch = DB::connection('mysql_dynamic')->select("SELECT ibatchid FROM trn_batch WHERE estatus = 'Close' AND endofday = '1' AND ibatchid = '".$value."' ");
                // $check_batch = json_decode(json_encode($check_batch), true)[0];
                
                if(count($result) < 1 && count($check_batch) < 1){
                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_endofdaydetail SET eodid = '" . ($auto_inc_id) . "', batchid = '" . ($value) . "',SID = '" . (int)(session()->get('sid'))."'");
                }
                DB::connection('mysql_dynamic')->update("UPDATE trn_batch SET endofday = '1' WHERE ibatchid='". ($value) ."'");
            }
        }
        $success['success'] = 'Successfully Updated End of Day Shift';
        return $success;
    }
    public function getBatches_assoiciated($start_date) {
        if($start_date==''){
            $start_date=date("m-d-Y");
        }
        $sql="select *  
        from trn_endofday e 
        join trn_endofdaydetail d on e.id=d.eodid 
        where date_format(e.dstartdatetime,'%m-%d-%Y') 
        between '". $start_date ."' and  '". $start_date ."'";
        $query = DB::connection('mysql_dynamic')->select($sql);
        return $query;
    }
}
