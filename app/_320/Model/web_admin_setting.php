<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;

class web_admin_setting extends Model{
    protected $connection = 'mysql_dynamic';
    protected $table = 'web_admin_settings';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['icategoryid', 'subcat_name', 'created_at', 'LastUpdate', 'SID'];

    public function defaultListings() {
		$query = "SHOW COLUMNS FROM mst_item";
        $return_data = DB::connection('mysql_dynamic')->select($query);
		$result = json_decode(json_encode($return_data), true); 
		return $result;
    }
    
    public function getItemListings($module_name="ItemListing") {
        $query = "SELECT variablevalue FROM web_admin_settings WHERE variablename='" . $module_name . "'";
        $return_data = DB::connection('mysql_dynamic')->select($query);
		$result = json_decode(json_encode($return_data), true); 
		return $result;
    }
    
    public function editlistSettings($postData, $sid) {
        
        $data = $postData['itemListings'] ? $postData['itemListings'] : array();
        $module = $postData['module_name'] ? $postData['module_name'] : 'ItemListing';
        $settings_exists = "SELECT * FROM web_admin_settings WHERE variablename='" . $module . "'";
        $settings_exist = DB::connection('mysql_dynamic')->select($settings_exists);
        $result = json_decode(json_encode($settings_exist), true); 

		if(count($result) > 0){
            $query = "UPDATE web_admin_settings SET  variablevalue = '" . json_encode($data) . "' WHERE variablename = '" . $module . "'";
            DB::connection('mysql_dynamic')->update($query);
            
		}else{
            $query = "INSERT INTO web_admin_settings SET variablename = '" . $module . "', variablevalue = '" . json_encode($data) . "',SID = '" . (int)($sid)."'";
            DB::connection('mysql_dynamic')->insert($query);
		}
	}

    
}
