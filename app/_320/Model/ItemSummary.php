<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class ItemSummary extends Model
{
    protected $connection = 'mysql_dynamic';
    
    public function getItemSummaryReport($data = null) {
		if(isset($data)){
			$start_date = $data['start_date'];
			$end_date   = $data['end_date'];
		}else{
			$start_date = date("m-d-Y");
			$end_date = date("m-d-Y");
		}

		$query = "CALL rp_itemsummary('".$start_date."','".$end_date."')";
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