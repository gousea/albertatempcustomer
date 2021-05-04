<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EoDReport extends Model
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
}
