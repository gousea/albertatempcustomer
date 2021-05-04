<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class SalesReport extends Model{
    protected $connection = 'mysql_dynamic';

    
    public function getsalesdata($data){      
        $startdate=  $data['start_date'];
        $end_date = $data['end_date'];
        try{
            $query = "CALL rpt_EodSales('".$startdate."','".$end_date."')";
        }
        catch (Exception $e) {
            // other mysql exception (not duplicate key entry)                    
            $error['error'] ='Error';
            return $error;
        }
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
    }
    
    public function getStore(){
       
        $sql="SELECT * FROM mst_store";
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($return_data), true); 
        return $result;
    }
}