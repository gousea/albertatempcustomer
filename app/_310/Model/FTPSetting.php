<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class FTPSetting extends Model{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_ftp_settings';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['*'];
    
    public function getTotalFtpSettings($data = array()) {
        
        $sql = "SELECT count(*) count FROM mst_ftp_settings";
            
        if(isset($data['searchbox']) && !empty($data['searchbox'])){
            $sql .= " WHERE Id= ". $this->db->escape($data['searchbox']);
        }

        $sql .= " ORDER BY ftp_id DESC";
        
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 	
        return count($return);
    }
    
    public function getAllFtpSettings($data = array()) {
        
        $sql = "SELECT * FROM mst_ftp_settings";
            
        if(isset($data['searchbox']) && !empty($data['searchbox'])){
            $sql .= " WHERE ftp_id = ". $this->db->escape($data['searchbox']);
        }

        $sql .= " ORDER BY ftp_id DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $return = json_decode(json_encode($return_data), true); 	
        return $return;
    }
    
    public function editFtp($data = array()) {
        
        $query2 = "SELECT * FROM mst_ftp_settings WHERE mfr_retail_no='" . $data['mfr_retail_no'] . "' AND ftp_username ='" . $data['ftp_username'] . "' AND ftp_id !='" . $data['ftp_id']."' ";
        $query2return_data = DB::connection('mysql_dynamic')->select($query2);
        $ftp_inserted_result = json_decode(json_encode($query2return_data), true); 	
        // dd($ftp_inserted_result);
            if(count($ftp_inserted_result) > 0){
                $data['duplicate'] = "Duplicate Entry Already FTP Exist"; 
            }else{
                try {
                    if(isset($data['level_pricing'])){
                        $level_pricing = $data['level_pricing'];
                    }else{
                        $level_pricing = '';
                    }
                    
                    $query = "UPDATE mst_ftp_settings SET mfr_retail_no = '" . $data['mfr_retail_no'] . "', manufacturer = '" . $data['manufacturer'] . "',  purpose = '" . $data['purpose'] . "', host = '" . $data['host'] . "', ftp_username = '" . $data['ftp_username'] . "', ftp_password = '" . $data['ftp_password'] . "', dept_code = '".json_encode($data['dept_code'],true)."', cat_code = '".json_encode($data['cat_code'],true)."', level_pricing = '".$level_pricing."' WHERE ftp_id = ".$data['ftp_id'];
                    DB::connection('mysql_dynamic')->update($query);
                    
                    $cat_code = join("','",$data['cat_code']);
                    
                    if(!empty($data['level_pricing']) && $data['level_pricing'] == 'level_price_2'){
                        $nlevel2 = DB::connection('mysql_dynamic')->SELECT("SELECT iitemid, dunitprice, nlevel2 FROM mst_item Where vcategorycode IN ('".$cat_code."') ");
                        
                        foreach($nlevel2 as $v){
                            if(!empty($v->nlevel2) && $v->nlevel2 == '0'){
                                DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nlevel2 = '".$v->dunitprice."' WHERE iitemid = '".$v->iitemid."'");
                            }
                        }
                    }
                    
                    if(!empty($data['level_pricing']) && $data['level_pricing'] == 'level_price_3'){
                        $nlevel3 = DB::connection('mysql_dynamic')->SELECT("SELECT iitemid, dunitprice, nlevel3 FROM mst_item Where vcategorycode IN ('".$cat_code."') ");
                        
                        foreach($nlevel3 as $v){
                            if(!empty($v->nlevel3) && $v->nlevel3 == '0'){
                                DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nlevel3 = '".$v->dunitprice."' WHERE iitemid = '".$v->iitemid."'");
                            }
                        }
                    }
                    
                    if(!empty($data['level_pricing']) && $data['level_pricing'] == 'level_price_4'){
                        $nlevel4 = DB::connection('mysql_dynamic')->SELECT("SELECT iitemid, dunitprice, nlevel4 FROM mst_item Where vcategorycode IN ('".$cat_code."') ");
                        
                        foreach($nlevel4 as $v){
                            if(!empty($v->nlevel4) && $v->nlevel4 == '0'){
                                DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nlevel4 = '".$v->dunitprice."' WHERE iitemid = '".$v->iitemid."'");
                            }
                        }
                    }
                    
                    $data['updated'] = 'Successfully Updated FTP Settings';
                }catch (Illuminate\Database\QueryException $e) {
                    // duplicate entry exception
                    $errorCode = $e->errorInfo[1];
                    if($errorCode == 1062){
                        $data['error'] = 'Duplicate Entry!';
                    }
                }catch (Exception $e) {
                    $data['error'] =  $e->getMessage();
                }
            }
        return $data;
    }
    
    public function addFtp($data = array(),$sid) {
        // dd($data);
        $success =array();
        $error =array();
        if(isset($data) && count($data) > 0){
            foreach ($data as $key => $value) {
                    
                    $query2 = "SELECT * FROM mst_ftp_settings WHERE mfr_retail_no='" . $value['mfr_retail_no'] . "' AND ftp_username ='" . $value['ftp_username'] . "'";
                    $query2return_data = DB::connection('mysql_dynamic')->select($query2);
                    $ftp_inserted_result = json_decode(json_encode($query2return_data), true); 	
                    
                if(count($ftp_inserted_result) > 0){
                    $data['duplicate'] = "Duplicate Entry Already FTP Exist"; 
                }else{
                    try {
                        if(isset($value['level_pricing'])){
                            $level_pricing = $value['level_pricing'];
                        }else{
                            $level_pricing = '';
                        }
                        $query = "INSERT INTO mst_ftp_settings SET mfr_retail_no = '" . $value['mfr_retail_no'] . "', manufacturer = '" . $value['manufacturer'] . "', purpose = '" . $value['purpose'] . "', host = '" . $value['host'] . "', ftp_username = '" . $value['ftp_username'] . "', ftp_password = '" . $value['ftp_password'] . "', SID = '" . $sid."', dept_code = '".json_encode($value['dept_code'],true)."', cat_code = '".json_encode($value['cat_code'],true)."', level_pricing = '".$level_pricing."' ";
                        DB::connection('mysql_dynamic')->insert($query);
                        
                        $cat_code = join("','",$value['cat_code']);
                        
                        if(!empty($value['level_pricing']) && $value['level_pricing'] == 'level_price_2'){
                            $nlevel2 = DB::connection('mysql_dynamic')->SELECT("SELECT iitemid, dunitprice, nlevel2 FROM mst_item Where vcategorycode IN ('".$cat_code."') ");
                            
                            foreach($nlevel2 as $v){
                                if(!empty($v->nlevel2) && $v->nlevel2 == '0'){
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nlevel2 = '".$v->dunitprice."' WHERE iitemid = '".$v->iitemid."'");
                                }
                            }
                        }
                        
                        if(!empty($value['level_pricing']) && $value['level_pricing'] == 'level_price_3'){
                            $nlevel3 = DB::connection('mysql_dynamic')->SELECT("SELECT iitemid, dunitprice, nlevel3 FROM mst_item Where vcategorycode IN ('".$cat_code."') ");
                            
                            foreach($nlevel3 as $v){
                                if(!empty($v->nlevel3) && $v->nlevel3 == '0'){
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nlevel3 = '".$v->dunitprice."' WHERE iitemid = '".$v->iitemid."'");
                                }
                            }
                        }
                        
                        if(!empty($value['level_pricing']) && $value['level_pricing'] == 'level_price_4'){
                            $nlevel4 = DB::connection('mysql_dynamic')->SELECT("SELECT iitemid, dunitprice, nlevel4 FROM mst_item Where vcategorycode IN ('".$cat_code."') ");
                            
                            foreach($nlevel4 as $v){
                                if(!empty($v->nlevel4) && $v->nlevel4 == '0'){
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nlevel4 = '".$v->dunitprice."' WHERE iitemid = '".$v->iitemid."'");
                                }
                            }
                        }
                    
                        $data['inserted'] = 'Successfully Added FTP Settings';
                    }catch (Illuminate\Database\QueryException $e) {
                        // duplicate entry exception
                        $errorCode = $e->errorInfo[1];
                        if($errorCode == 1062){
                            $data['error'] = 'Duplicate Entry!';
                        }
                    }catch (Exception $e) {
                        $data['error'] =  $e->getMessage();
                    }
                }
                return $data;
            }
        }
    }
    
    public function getFtpSettings($ftp_id) {
        $query = "SELECT * FROM mst_ftp_settings WHERE ftp_id='" .(int)$ftp_id. "'";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $return = json_decode(json_encode($return_data), true); 	
        return $return;
    }
    
     public function deleteFtp($data,$sid) {
        if(isset($data) && count($data) > 0){
            foreach ($data as $key => $value) {
                $query = "SELECT * FROM mst_ftp_settings WHERE  ftp_id = '" . $value . "'";
                $return_data = DB::connection('mysql_dynamic')->select($query);
                $delete_result = json_decode(json_encode($return_data), true); 	
                $delete = $delete_result[0];
                if(count($delete) > 0){
                    $exist_deleteunit[] = $delete['ftp_id'];
                    
                    $query = "INSERT INTO mst_delete_table SET  `TableName` = 'mst_ftp_settings',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" .$sid."'";
                    DB::connection('mysql_dynamic')->insert($query);

                    $query = "DELETE FROM mst_ftp_settings WHERE ftp_id='" . $value  . "'";
                    DB::connection('mysql_dynamic')->delete($query);
                    $return['success'] =' FTP Settings Deleted  Successfully';
                    
                }
            }
            return $return;
        }
    
    }
    
}
