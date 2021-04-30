<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class WebAdminSetting extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'web_admin_settings';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = [ 'variablename', 'variablevalue', 'SID'];
    
    
    public function defaultListings() {
		$query = "SHOW COLUMNS FROM mst_item";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
	}
	
// 	public function getItemListings($module_name="ItemListing") {
	public function getItemListings($module_name) {
		$query = "SELECT variablevalue FROM web_admin_settings WHERE variablename='" . $module_name . "'";
        $result = DB::connection('mysql_dynamic')->select($query);
        return $result;
	}
}
