<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Unit extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_unit';
    protected $primaryKey = 'iunitid';
    public $timestamps = false;
    
    protected $fillable = ['*'];
    
    public function getUnits($data = array()) {
        $sql = "SELECT * FROM mst_unit";
        if(!empty($data['searchbox'])){
            $sql .= " WHERE iunitid= ". ($data['searchbox']);
        }
        $sql .= " ORDER BY iunitid DESC";
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = DB::connection('mysql_dynamic')->select($sql);
        return $query;
    }
    
    public function getUnitsTotal($data = array()) {
        $sql = "SELECT * FROM mst_unit";
        if(!empty($data['searchbox'])){
            $sql .= " WHERE iunitid= ". ($data['searchbox']);
        }
        $sql .= " ORDER BY iunitid DESC";
        $query = DB::connection('mysql_dynamic')->select($sql);
        return count($query);
    }
    
    public function getUnit($iunitid) {
        $query = $this->db2->query("SELECT * FROM mst_unit WHERE iunitid='" .(int)$iunitid. "'");
        return $query->row;
    }
    
    public function getUnitByName($unit) {
        $query = $this->db2->query("SELECT * FROM mst_unit WHERE vunitname='" .$unit. "'");
        return $query->row;
    }
    
    public function getUnitAllData($vunitcode,$vunitname) {
        $query = $this->db2->query("SELECT * FROM mst_unit WHERE vunitcode='" .($vunitcode). "' AND vunitname='" .($vunitname). "' ");
        return $query->row;
    }
    
    public function getUnitCode($vunitcode) {
        $query = $this->db2->query("SELECT * FROM mst_unit WHERE vunitcode = '" . ($vunitcode) . "'");
        return $query->rows;
    }
    
    public function getUnitSearch($search) {
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_unit WHERE iunitid LIKE  '%" .($search). "%' OR vunitcode LIKE  '%" .($search). "%' OR vunitname LIKE  '%" .($search). "%' ");
        return $query;
    }
    
    public function addUnits($data = array()) {
        $success =array();
        $error =array();
        // dd($data);
        if(isset($data['stores_hq'])){
            if($data['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $data['stores_hq']);
            }
            
            foreach($stores as $store){
                if(isset($data) && count($data) > 0){
                    //$exist_unit = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_unit WHERE vunitcode = '" . ($data['vunitcode']). "' or  `vunitname` = '" . ($data['vunitname']) . "' or vunitdesc = '" . ($data['vunitdesc']) . "'" );
                    $exist_unit = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_unit WHERE vunitcode = '" . ($data['vunitcode']). "' or  `vunitname` = '" . ($data['vunitname']) . "'" );
                    if(count($exist_unit) > 0)
                    {
                        $error['error'] = "Already exists";
                        return $error; 
                    }
                    
                    try {
                        DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_unit SET  vunitcode = '" . ($data['vunitcode']) . "',`vunitname` = '" . ($data['vunitname']) . "', vunitdesc = '" . ($data['vunitdesc']) . "',`estatus` = '" . ($data['estatus']) . "',SID = '" . (int)(session()->get('sid'))."'");
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
            }
        
        }else{
            if(isset($data) && count($data) > 0){
                //  $exist_unit = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_unit WHERE vunitcode = '" . ($data['vunitcode']). "' or  `vunitname` = '" . ($data['vunitname']) . "' or vunitdesc = '" . ($data['vunitdesc']) . "'" );
                $exist_unit = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_unit WHERE vunitcode = '" . ($data['vunitcode']). "' or  `vunitname` = '" . ($data['vunitname']) . "'" );
                     if(count($exist_unit) > 0)
                     {
                        $error['error'] = "Already exists";
                        return $error; 
                     }
                    try {
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_unit SET  vunitcode = '" . ($data['vunitcode']) . "',`vunitname` = '" . ($data['vunitname']) . "', vunitdesc = '" . ($data['vunitdesc']) . "',`estatus` = '" . ($data['estatus']) . "',SID = '" . (int)(session()->get('sid'))."'");
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
        }
        $success['success'] = 'Successfully Added Unit';
        return $success;
    }
    
// public function addUnits($data = array()) {
//         $success =array();
//         $error =array();
//         if(isset($data) && count($data) > 0){
//              $exist_unit = $this->db2->query("SELECT * FROM mst_unit WHERE vunitcode = '" . ($data['vunitcode']). "' or  `vunitname` = '" . ($data['vunitname']) . "' or vunitdesc = '" . ($data['vunitdesc']) . "'" );
//                  if($exist_unit->num_rows > 0)
//                  {
//                     $return['error'] = "Already exists"; 
//                     return $return;
//                  }
//               else {
//                   $this->db2->query("INSERT INTO mst_unit SET  vunitcode = '" . ($data['vunitcode']) . "',`vunitname` = '" . ($data['vunitname']) . "', vunitdesc = '" . ($data['vunitdesc']) . "',`estatus` = '" . ($data['estatus']) . "',SID = '" . (int)(session()->get('sid'))."'");
//                      $return['success'] = 'Successfully Added Unit';
//                      return $return;
//                 }
//         } 
//     }

    public function addUnitByName($unit) {
        $success =array();
        $error =array();
        try {
            $max_id = $this->db2->getLastId();
            // $vunitcode = "UNT00".$max_id+1;
            $this->db2->query("INSERT INTO mst_unit SET `vunitname` = '" . ($unit) . "', vunitdesc = '" . ($unit) . "',`estatus` = 'Active',SID = '" . (int)(session()->get('sid'))."'");
            $iunitid = $this->db2->getLastId();
            $vunitcode = "UNT00".$iunitid;
            $this->db2->query("UPDATE mst_unit SET vunitcode = '" . $vunitcode . "' WHERE iunitid = '" . (int)$iunitid . "'");
            $query = $this->db2->query("SELECT * FROM mst_unit WHERE iunitid='" .(int)$last_id. "'")->row;
        }
        catch (MySQLDuplicateKeyException $e) {
            // duplicate entry exception
           $error['error'] = $e->getMessage(); 
            return $error; 
        }
        catch (MySQLException $e) {
            // other mysql exception (not duplicate key entry)
            $error['error'] = $e->getMessage(); 
            return $error; 
        }
        catch (Exception $e) {
            // not a MySQL exception
            $error['error'] = $e->getMessage(); 
            return $error; 
        }
        return $query['vunitcode'];
    }
    
    public function editlistUnits($iunitid,$data = array(), $stores = []) {
        $success =array();
        $error =array();
        
        
        if(isset($stores)){
            if($stores === session()->get('sid')){
                $stores_hq = [session()->get('sid')];
            }else{
                $stores_hq = explode(",", $stores);
            }
            
            $unitcode = DB::connection("mysql_dynamic")->select("select vunitcode from mst_unit where iunitid = '".$iunitid."' ");
             
            foreach($unitcode as $unit_c){
                $indi_unitCode = $unit_c->vunitcode;
            }
            //  $sql = array();
            foreach($stores_hq as $store){
                $indiUnit_id = DB::connection("mysql")->select("select iunitid from u".$store.".mst_unit where vunitcode = '".$indi_unitCode."' ");
                if(count($indiUnit_id) > 0){
                    foreach($indiUnit_id as $u_id){
                        $unit_id = $u_id->iunitid;
                    }

                    $exist_unit = DB::connection('mysql_dynamic')->select("SELECT * FROM u".$store.".mst_unit WHERE vunitcode = '" . ($data['vunitcode']). "' AND iunitid != '" . (int)$unit_id . "' " );
                    
                    if(count($exist_unit) > 0)
                    {
                        $return['error'] = "Already exists"; 
                        return $return;
                    }
                    else {
                        try {
                            // dd(__LINE__);
                            $update = "UPDATE u".$store.".mst_unit SET  vunitcode = '" . ($data['vunitcode']) . "', vunitname = '" . ($data['vunitname']) . "', vunitdesc = '" . ($data['vunitdesc']) . "', estatus = '" . ($data['estatus']) . "' WHERE iunitid = '" . (int)$unit_id . "' ";
                            // array_push($sql, $update);
                            DB::connection('mysql')->select($update);
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
                   
                }else {
                     DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_unit SET  vunitcode = '" . ($data['vunitcode']) . "',`vunitname` = '" . ($data['vunitname']) . "', vunitdesc = '" . ($data['vunitdesc']) . "',`estatus` = '" . ($data['estatus']) . "',SID = '" . (int)(session()->get('sid'))."'");
                }
                
            }
            //dd($sql);
        }else{
            if(isset($data) && count($data) > 0){

                $exist_unit = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_unit WHERE vunitcode = '" . ($data['vunitcode']). "' AND iunitid != '" . (int)$iunitid . "' " );
                dd($exist_unit);
                if(count($exist_unit) > 0)
                {
                    $return['error'] = "Already exists"; 
                    return $return;
                }
                else {
                    try {
                        DB::connection('mysql_dynamic')->update("UPDATE mst_unit SET  vunitcode = '" . ($data['vunitcode']) . "',`vunitname` = '" . ($data['vunitname']) . "', vunitdesc = '" . ($data['vunitdesc']) . "',`estatus` = '" . ($data['estatus']) . "' WHERE iunitid = '" . (int)$iunitid . "'");
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
            }
            
        }
            
        $success['success'] = 'Successfully Updated Unit';
        return $success;
    }
    
    // public function deleteunit($data) {
        
    //     $exist_deleteunit = array();
    //     if(isset($data) && count($data) > 0){
            
    //     }else{
    //         if(isset($data) && count($data) > 0){
    //         foreach ($data as $key => $value) {
    //             // Check if the age verfication record exists in mst_item
    //             // Get the vvalue (foriegn key in mst_item)
    //             $unitcode= DB::connection('mysql_dynamic')->select("SELECT  vunitcode FROM mst_unit  WHERE  iunitid = '" . ($value) . "'");
    //             $unitcode = json_decode(json_encode($unitcode), true);
    //              $sql="SELECT * FROM mst_item WHERE  vunitcode ='" .$unitcode[0]['vunitcode'] . "'";
    //             $result = DB::connection('mysql_dynamic')->select($sql);
    //             if(count($result) > 0)
    //             {
    //                 // if it exists in mst_item
    //               // $check_db = $this->db2->query("SELECT  vname FROM mst_ageverification WHERE  vvalue = '" . ($agevalue[0]['vvalue']) . "'")->row;
    //                 $check_db= DB::connection('mysql_dynamic')->select("SELECT  vunitcode FROM mst_unit  WHERE  iunitid = '" . ($value) . "'");
    //                 $check_db = json_decode(json_encode($check_db), true);
    //                 $return['error'] = $check_db[0]['vunitcode'].'  is already assigned to item in system. Please unselect it!';
    //                 return $return;
    //             } 
    //             else {
    //                 // Delete if not associated with any item
    //                 $mst_deleteunit = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_unit WHERE  iunitid = '" . ($value) . "'");
    //                 if(count($mst_deleteunit) > 0){
    //                     $exist_deleteunit[] = $value;
    //                     DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_unit ',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)(session()->get('sid'))."'");
    //                     DB::connection('mysql_dynamic')->delete("DELETE FROM mst_unit  WHERE iunitid='" . (int)$value . "'");
    //                 }
    //                   $return['success'] =' Unit Deleted  Successfully';
    //             }
    //         }
    //     }
        
    //         return $return;
    //     }
    // }
    
}