<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class SalesAnalyticsReport extends Model
{
    protected $connection = 'mysql_dynamic';

    public function getSalesAnalyticsReport($data = null) {
        
        if(isset($data['department'])){
            $department_code   = $data['department'];
            $array_dept = implode(',', $department_code);
        }else{
            $array_dept = "All";
        }

        if(isset($data['category'])){
            $category_code   = $data['category'];
            $array_cat = implode(',', $category_code);
        }else{
            $array_cat = "All";
        }

        if(isset($data['subcategory'])){
            $subcategory_id   = $data['subcategory'];
            $array_sub = implode(',', $subcategory_id);
        }else{
            $array_sub = "All";
        }
		if(isset($data)){
			$start_date = $data['start_date'];
            $end_date = $data['end_date'];		
		}else{
			$start_date = date("m-d-Y");
			$end_date = date("m-d-Y");
			$report_by = "Category";
        }
        
        $sql = "CALL rpt_salesanalytics('".$start_date."', '".$end_date."', '".$array_dept."', '".$array_cat."', '".$array_sub."')";
		$result = DB::connection('mysql_dynamic')->select($sql);
        return $result;
	}
    
    public function getStore(){       
		$sql="SELECT * FROM mst_store";
		$return_data = DB::connection('mysql_dynamic')->select($sql);
		$result = json_decode(json_encode($return_data), true); 
		return $result;
	}

}