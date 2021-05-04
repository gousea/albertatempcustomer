<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Department extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_department';
    public $timestamps = false;
    protected $primaryKey = 'idepartmentid';
    protected $fillable = ['*'];
    protected $guarded = ['*'];

    // protected $fillable = ['vcompanycode', 
    //     'vstoreabbr', 
    //     'vaddress1', 
    //     'vstoredesc', 
    //     'vcity', 
    //     'vstate',     
    //     'vzip', 
    //     'vcountry', 
    //     'vphone1', 
    //     'vfax1',
    //     'vemail', 
    //     'vwebsite', 
    //     'vcontactperson', 
    //     'isequence', 
    //     'vmessage1', 
    //     'vmessage2'
    // ];
    
    public function getDepartments(){
        $results = DB::connection('mysql_dynamic')->table('mst_department')
                ->select('*')
                ->orderByRaw('vdepartmentname','DESC')
                ->get();
        return $results;
    }
    
    public function addDeparmentList($data){
        // dd($data);
        if(isset($data['stores_hq'])){
            if($data['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $data['stores_hq']);
            }
            
            foreach($stores as $store){
                if(isset($data['department'])){
                    foreach($data['department'] as $key=>$value){
                         $starttime = '';
                         $endtime = '';
                         if($value['start_hour'] != '' && $value['start_minute'] != ''){
                             $starttime = $value['start_hour'].':'.$value['start_minute'].':00';
                         }else{
                             $starttime = NULL;
                         }
                         
                         if($value['end_hour'] != '' && $value['end_minute'] != ''){
                             $endtime = $value['end_hour'].':'.$value['end_minute'].':00';
                         }else{
                             $endtime = NULL;
                         }
                        $query= "SELECT count(*) as total FROM u".$store.".mst_department WHERE idepartmentid = '" . $value['idepartmentid'] . "'";
                        $return_data = DB::connection('mysql')->select($query);
                        $qu = json_decode(json_encode($return_data), true); 
                        $que = $qu[0];
                        // $result = json_decode(json_encode($return_data), true); 
                        $sql = "INSERT INTO u".$store.".mst_department SET vdepartmentname = '" . $value['vdepartmentname'] . "',`vdescription` = '" .$value['vdescription'] . "',";
                
                         if(!empty($starttime)){
                             $sql .= " starttime = '" .$starttime. "',";
                         }else{
                             $sql .= " starttime = NULL,";
                         }
                
                         if(!empty($endtime)){
                             $sql .= " endtime = '" .$endtime. "',";
                         }else{
                             $sql .= " endtime = NULL,";
                         }
                
                        $sql .= "isequence = '" . (int)$value['isequence'] . "', estatus = 'Active', SID = '" . (int)($store)."'";
                         
                        DB::connection('mysql')->insert($sql);
                        
                        $query_data = "select idepartmentid from u".$store.".mst_department where vdepartmentname = '" . ($value['vdepartmentname'])."'";
                        $return_query_data = DB::connection('mysql')->select($query_data);
                        $return_data = $return_query_data[0];
                        $last_id = $return_data->idepartmentid;
                        
                        $sql2 = "UPDATE u".$store.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                        DB::connection('mysql')->update($sql2);
                    }
                }
            }
        }else {
            if(isset($data['department'])){
                foreach($data['department'] as $key=>$value){
                     $starttime = '';
                     $endtime = '';
                     if($value['start_hour'] != '' && $value['start_minute'] != ''){
                         $starttime = $value['start_hour'].':'.$value['start_minute'].':00';
                     }else{
                         $starttime = NULL;
                     }
                     
                     if($value['end_hour'] != '' && $value['end_minute'] != ''){
                         $endtime = $value['end_hour'].':'.$value['end_minute'].':00';
                     }else{
                        $endtime = NULL;
                     }
                    $query= "SELECT count(*) as total FROM mst_department WHERE idepartmentid = '" . $value['idepartmentid'] . "'";
                    $return_data = DB::connection('mysql_dynamic')->select($query);
                    $qu = json_decode(json_encode($return_data), true); 
                    $que = $qu[0];
                    // $result = json_decode(json_encode($return_data), true); 
                    $sql = "INSERT INTO mst_department SET vdepartmentname = '" . $value['vdepartmentname'] . "',`vdescription` = '" .$value['vdescription'] . "',";
            
                     if(!empty($starttime)){
                         $sql .= " starttime = '" .$starttime. "',";
                     }else{
                         $sql .= " starttime = NULL,";
                     }
            
                     if(!empty($endtime)){
                         $sql .= " endtime = '" .$endtime. "',";
                     }else{
                         $sql .= " endtime = NULL,";
                     }
            
                     $sql .= "isequence = '" . (int)$value['isequence'] . "',estatus = 'Active',SID = '" . (int)($data['sid'])."'";
                     
                    DB::connection('mysql_dynamic')->insert($sql);
                    $query_data = "select idepartmentid from mst_department where vdepartmentname = '" . ($value['vdepartmentname'])."'";
                    $return_query_data = DB::connection('mysql_dynamic')->select($query_data);
                    $return_data = $return_query_data[0];
                    $last_id = $return_data->idepartmentid;
                    $sql2 = "UPDATE mst_department SET vdepcode = '" . (int)$last_id . "' WHERE idepartmentid = '" . (int)$last_id . "'";
                    DB::connection('mysql_dynamic')->update($sql2);
                }
            }
        }
    }
    
    public function editDepartmentList($data) {
        // dd($data);
        if(isset($data['department'])){
            if(isset($data['stores_hq'])){
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                }
                foreach($data['department'] as $key=>$value){
                    if ( in_array($value['idepartmentid'], $data['selected']) ){
                        $departmentname = DB::connection('mysql_dynamic')->select("select vdepartmentname from mst_department where idepartmentid = '".$value['idepartmentid']."' ");
                        
                        foreach($departmentname as $vdepname){
                            $sep_dep_name = $vdepname->vdepartmentname;
                        }
                        foreach($stores as $store){
                            $invidualdepId= DB::connection("mysql")->select("SELECT idepartmentid  FROM u".$store.".mst_department WHERE vdepartmentname = '" .$sep_dep_name. "'");
                            
                            if(count($invidualdepId) > 0){
                                foreach($invidualdepId as $ind_dep_id){
                                    $ideparmetn_id  = $ind_dep_id->idepartmentid;
                                }
                                $starttime = '';
                                $endtime = '';
                                if($value['start_hour'] != '' && $value['start_minute'] != ''){
                                     $starttime = $value['start_hour'].':'.$value['start_minute'].':00';
                                }else{
                                     $starttime = NULL;
                                 }
                                 
                                if($value['end_hour'] != '' && $value['end_minute'] != ''){
                                     $endtime = $value['end_hour'].':'.$value['end_minute'].':00';
                                }else{
                                     $endtime = NULL;
                                 }
                                $query= "SELECT count(*) as total FROM u".$store.".mst_department WHERE idepartmentid = '" .$ideparmetn_id . "'";
                                $return_data = DB::connection('mysql')->select($query);
                                $qu = json_decode(json_encode($return_data), true); 
                                $que = $qu[0];
                                // $result = json_decode(json_encode($return_data), true); 
                
                                if($que['total'] > 0){
                                     $sql = "UPDATE u".$store.".mst_department SET vdepcode = '".$value['vdepcode']."', vdepartmentname = '" . html_entity_decode($value['vdepartmentname']) . "',`vdescription` = '" . html_entity_decode($value['vdescription']) . "',";
                 
                                     if(!empty($starttime)){
                                         $sql .= " starttime = '" .$starttime. "',";
                                     }else{
                                         $sql .= " starttime = NULL,";
                                     }
                 
                                     if(!empty($endtime)){
                                         $sql .= " endtime = '" .$endtime. "',";
                                     }else{
                                         $sql .= " endtime = NULL,";
                                     }
                 
                                     $sql .= "isequence = '" . (int)$value['isequence'] . "' WHERE idepartmentid = '" . (int)$ideparmetn_id . "'";
                
                                    $return_data = DB::connection('mysql')->statement($sql);
                                    json_decode(json_encode($return_data), true); 
                                    //  $this->db2->query($sql);
                                } 
                            } else {
                                $starttime = '';
                                $endtime = '';
                                if($value['start_hour'] != '' && $value['start_minute'] != ''){
                                    $starttime = $value['start_hour'].':'.$value['start_minute'].':00';
                                }else{
                                    $starttime = NULL;
                                }
                                 
                                if($value['end_hour'] != '' && $value['end_minute'] != ''){
                                    $endtime = $value['end_hour'].':'.$value['end_minute'].':00';
                                }else{
                                    $endtime = NULL;
                                }
                                $query= "SELECT count(*) as total FROM u".$store.".mst_department WHERE idepartmentid = '" .$ideparmetn_id. "'";
                                $return_data = DB::connection('mysql')->select($query);
                                $qu = json_decode(json_encode($return_data), true); 
                                $que = $qu[0];
                                // $result = json_decode(json_encode($return_data), true); 
                                $sql = "INSERT INTO u".$store.".mst_department SET vdepartmentname = '" . $value['vdepartmentname'] . "',`vdescription` = '" .$value['vdescription'] . "',";
                        
                                 if(!empty($starttime)){
                                     $sql .= " starttime = '" .$starttime. "',";
                                 }else{
                                     $sql .= " starttime = NULL,";
                                 }
                        
                                 if(!empty($endtime)){
                                     $sql .= " endtime = '" .$endtime. "',";
                                 }else{
                                     $sql .= " endtime = NULL,";
                                 }
                        
                                $sql .= "isequence = '" . (int)$value['isequence'] . "', estatus = 'Active', SID = '" . (int)($store)."'";
                                 
                                DB::connection('mysql')->insert($sql);
                                
                                $query_data = "select idepartmentid from u".$store.".mst_department where vdepartmentname = '" . ($value['vdepartmentname'])."'";
                                $return_query_data = DB::connection('mysql')->select($query_data);
                                $return_data = $return_query_data[0];
                                $last_id = $return_data->idepartmentid;
                                
                                $sql2 = "UPDATE u".$store.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                                DB::connection('mysql')->update($sql2);
                            }
                        }
                        
                    }
                } 
               
                
            }else {
                foreach($data['department'] as $key=>$value){
                 $starttime = '';
                 $endtime = '';
                 if($value['start_hour'] != '' && $value['start_minute'] != ''){
                     $starttime = $value['start_hour'].':'.$value['start_minute'].':00';
                 }else{
                     $starttime = NULL;
                 }
                 
                 if($value['end_hour'] != '' && $value['end_minute'] != ''){
                     $endtime = $value['end_hour'].':'.$value['end_minute'].':00';
                 }else{
                     $endtime = NULL;
                 }
                $query= "SELECT count(*) as total FROM mst_department WHERE idepartmentid = '" . $value['idepartmentid'] . "'";
                $return_data = DB::connection('mysql_dynamic')->select($query);
                $qu = json_decode(json_encode($return_data), true); 
                $que = $qu[0];
                // $result = json_decode(json_encode($return_data), true); 

                 if($que['total'] > 0){
                     $sql = "UPDATE mst_department SET vdepartmentname = '" . html_entity_decode($value['vdepartmentname']) . "',`vdescription` = '" . html_entity_decode($value['vdescription']) . "',";
 
                     if(!empty($starttime)){
                         $sql .= " starttime = '" .$starttime. "',";
                     }else{
                         $sql .= " starttime = NULL,";
                     }
 
                     if(!empty($endtime)){
                         $sql .= " endtime = '" .$endtime. "',";
                     }else{
                         $sql .= " endtime = NULL,";
                     }
 
                     $sql .= "isequence = '" . (int)$value['isequence'] . "' WHERE idepartmentid = '" . (int)$value['idepartmentid'] . "'";

                    $return_data = DB::connection('mysql_dynamic')->statement($sql);
                    json_decode(json_encode($return_data), true); 
                    //  $this->db2->query($sql);
                 }
            }
            }
        }
    }

    public function deleteDepartment($data,$sid) {   
        $exist_departments = array();        
        if(isset($data) && count($data) > 0){            
            $count_items = 0;
            $count_categories = 0;
            foreach ($data as $key => $value) {                
                if($value==1){
                    $return['error'] =' Department already assigned to item in system please unselect it! ';
                    return $return;
                }
                $mst_department = "SELECT * FROM mst_department WHERE vdepcode = '" . $value . "'";
                $mst_department_result = DB::connection('mysql_dynamic')->select($mst_department);   
                $mst_dept = json_decode(json_encode($mst_department_result), true); 
                $mst_dept_result = $mst_dept[0];
                
                $mst_item_data = "SELECT * FROM mst_item WHERE vdepcode = '" . $value . "'";    
                $mst_item_result = DB::connection('mysql_dynamic')->select($mst_item_data);   
                $mst_item = json_decode(json_encode($mst_item_result), true);          
                
                $mst_category_data = "SELECT * FROM mst_category WHERE dept_code = '" . $value . "' AND estatus = 'Active' ";
                $mst_category_result = DB::connection('mysql_dynamic')->select($mst_category_data);   
                $mst_category = json_decode(json_encode($mst_category_result), true);          

                if(count(array($mst_item)) > 0){
                    // echo "<pre>";
                    // print_r($mst_dept_result);die;
                    $exist_departments[] = $mst_dept_result['vdepartmentname'];
                    $count_items = $count_items + count(array($mst_item));
                }elseif(count($mst_category) > 0){
                    $exist_departments[] = $mst_dept_result['vdepartmentname'];
                    $count_categories = $count_categories + count($mst_category);
                }else{
                  $trn_salesdetail_data = "SELECT * FROM trn_salesdetail WHERE vdepcode = '" . $value . "'";                   
                    $trn_salesdetail_result = DB::connection('mysql_dynamic')->select($trn_salesdetail_data);   
                    $trn_salesdetail = json_decode(json_encode($trn_salesdetail_result), true);          
                  if(count($trn_salesdetail) > 0){
                    $exist_departments1[] = $mst_dept_result['vdepartmentname'];
                  }
                }
            }
            
            foreach($exist_departments as $values){
                if(is_numeric($values)){
                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_department',`Action` = 'delete',`TableId` = '" . (int)$values . "',SID = '" . $sid."'");
                    DB::connection('mysql_dynamic')->update("UPDATE mst_department SET estatus = 'Inactive' WHERE vdepartmentname = '" . $values . "' AND SID = '" . $sid . "' ");
                }
            }
            
            if(count($exist_departments) > 0){
                $exit_dep = implode(",",$exist_departments);
                if($count_items > 0){
                    session()->flash('error_message', $exit_dep.' Department already assigned to '.$count_items.' item in system please unselect it!');
                    // $return['error_message'] = $exit_dep.' Department already assigned to '.$count_items.' item in system please unselect it!';
                }elseif($count_categories > 0){
                    session()->flash('error_message', $exit_dep.' Department is associated with '.$count_categories.' category. Are you sure want to delete this ?');
                    // $return['error_message'] = $exit_dep.' Department is associated with '.$count_categories.' category. Are you sure want to delete this ?';
                }
            }else{
                foreach ($data as $key => $value) {
                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_department',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . $sid."'");
                    DB::connection('mysql_dynamic')->update("UPDATE mst_department SET estatus = 'Inactive' WHERE vdepcode = '" . $value . "' AND SID = '" . $sid . "' ");
                }

                $return['success'] = 'Department deleted Successfully';
            }
            
        }
    }

    public function getDepartmentSearch($search) {
        $query = "SELECT * FROM mst_department WHERE vdepartmentname LIKE  '%" .$search. "%' ";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 
        return $result;
    }
    
    public function checkduplicates($inputs){
        foreach($inputs['department'] as $k => $v){
            $dept_id = $v['idepartmentid'];
            $dept_name =  $v['vdepartmentname'];
            
            if(empty(trim($dept_name))){
                  $this->error['warning'] = "The department name should not be blank.";
                  break;
            }

            $query = "SELECT * FROM mst_department WHERE vdepartmentname = '{$dept_name}'";
            $return_data = DB::connection('mysql_dynamic')->select($query);
            
            if(empty($return_data)){
                return true;
            }
            $run_query_data = json_decode(json_encode($return_data), true); 
            $run_query = $run_query_data[0];
            
           // check if the department id = 0 
            if($dept_id == 0){
                
                //======= insert =======
                if(count($run_query) > 0){
                    $data['errors'] = "The name of that department already exists.";
                    echo "sadfdsaf";die;
                      break;
                }
                
            } else {
                
                //======= update ======
                if(count($run_query) == 1 && $dept_id != $run_query['idepartmentid']){                    
                    $data['errors'] = "One of the selected departments has a name that is already existing.";
                      break;
                }
            }

            
        }
        
        if (!$this->error) {
              return TRUE;
        } else {
              return $data;
        }
    }

}
