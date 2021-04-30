<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
class StoreSettings extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_storesetting';
    // protected $primaryKey = 'iroid';
    public $timestamps = false;
    
    public function getStoreSettings() {
        $query = DB::connection('mysql_dynamic')->select("SELECT `vsettingname`, `vsettingvalue` FROM mst_storesetting ");
        return $query;
    }
    public function editlistStoreSettings($data = array()) {
        $success =array();
        $error =array();
        $storeid = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_store");
        if(isset($data) && count($data) > 0){
            try { 
                foreach ($data as $key => $value) {
                    $exists = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_storesetting WHERE vsettingname = '" . ($key) . "'");
                    
                    if(count($exists) > 0){
                        DB::connection('mysql_dynamic')->update("UPDATE mst_storesetting SET  vsettingvalue = '" . ($value) . "' WHERE vsettingname = '" . ($key) . "' ");
                    }else{
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_storesetting SET  vsettingvalue = '" . ($value) . "', vsettingname = '" . ($key) . "', istoreid = '" . $storeid[0]->istoreid . "',estatus = 'Active',SID = '" . (int)(session()->get('sid'))."'");
                    }
                } 
            } 
            catch (\MySQLDuplicateKeyException $e) {
                // duplicate entry exception
               $error['error'] = $e->getMessage(); 
                return $error; 
            }
            catch (\MySQLException $e) {
                // other mysql exception (not duplicate key entry)
                $error['error'] = $e->getMessage(); 
                return $error; 
            }
            catch (\Exception $e) {
                // not a MySQL exception
                $error['error'] = $e->getMessage(); 
                return $error; 
            }
        } 
        $success['status'] = 'success';
        $success['success'] = 'Successfully Updated Store Setting';
        return $success;
    }
}
