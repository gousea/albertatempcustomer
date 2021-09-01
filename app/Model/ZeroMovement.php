<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
Use Exception;

class ZeroMovement extends Model{
    
    public function getCategories($data = array()) {		
		$sql = "SELECT * FROM mst_category";            
        if(!empty($data['searchbox'])){
            $sql .= " WHERE icategoryid= ". $data['searchbox'];
        }
        $sql .= " ORDER BY vcategoryname";

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
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
    }

    public function getCategoriesInDepartment($dept_code) {
        
        $query = "SELECT * FROM mst_category WHERE dept_code=".$dept_code." ORDER BY vcategoryname";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
    }

    public function getSubCategories2() {
        $sql = "SELECT * FROM mst_subcategory ORDER BY subcat_name";
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
    }
    
     public function getSubCategories1($cat_id) {
         
        $array = implode("','", $cat_id['cat_id']);  
        
        if($cat_id['cat_id'][0]=='All' || $cat_id['cat_id'][0]==''){
            
         $query = "SELECT * FROM mst_subcategory  ORDER BY subcat_name";
        // dd($array);
        }
        else{
            $query = "SELECT * FROM mst_subcategory WHERE cat_id IN('".$array."') ORDER BY subcat_name";
        }
        
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
    }

    public function getSubCategories($cat_id) {

        try{
            // echo "SELECT * FROM mst_subcategory where cat_id = $cat_id ORDER BY subcat_name";die;
           
            $query = "SELECT * FROM mst_subcategory where cat_id = $cat_id ORDER BY subcat_name";
            
            $return_data = DB::connection('mysql_dynamic')->select($query);
            $result = json_decode(json_encode($return_data), true); 	
            return $result;
        }
        catch(Exception $e)
        {
            return array();
        }
        
    }
    
    public function getStore(){       
		$sql="SELECT * FROM mst_store";
		$return_data = DB::connection('mysql_dynamic')->select($sql);
		$result = json_decode(json_encode($return_data), true); 
		return $result;
	}

    public function get_zero_movement_report($data){
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);
        
        
         if($data['vdepcode']==''){
            $data['vdepcode']='All'; 
         }
         
         if($data['vcategorycode'] ==''){
            $data['vcategorycode']='All'; 
         }
         
         if($data['subcat_id']==''){
            $data['subcat_id']='All'; 
         }
         
         
	   // if (isset($start) || isset($limit)) {
    //         if ($start < 0) {
    //             $start = 0;
    //         }

    //         if ($limit < 1) {
    //             $limit = 20;
    //         }
            
    //     }else{
            
    //         $start = 0;
    //         $limit = 20;
    //     }
        // dd($data);
        
        
        $page = $data['page'];
        $itemsPerPage=30;
        $offset = ($page - 1) * $itemsPerPage + 1;
         
         $start = $offset;
         $limit = $offset+$itemsPerPage;
        $sql="CALL rp_webzeroitemmovement('".$data['p_start_date']."','" . $data['p_end_date'] . "','".$data['vdepcode']."','".$data['vcategorycode']."','".$data['subcat_id']."', '".(int)$start."', '".(int)$limit."')";
      // $sql="CALL rp_webzeroitemmovement('".$data['p_start_date']."','" . $data['p_end_date'] . "','".$data['vdepcode']."','".$data['vcategorycode']."','".$data['subcat_id']."')";
     
       try{
           
        $return_data = DB::connection('mysql_dynamic')->select($sql);
       
           
       }
       catch(Exception $e){
          return array();
       }
       
       $result = json_decode(json_encode($return_data), true); 	
        
       return $result;
    }
    
    public function getcat_SubCategories($cat_id) {
        // echo $cat_id;die;
        try{           
            if($cat_id == 'All'){
                $query = "SELECT * FROM mst_subcategory where cat_id = '$cat_id' ORDER BY subcat_name";
            }elseif($cat_id == ''){
                $query = "SELECT * FROM mst_subcategory where cat_id = '' ORDER BY subcat_name";
            }else{
                $query = "SELECT * FROM mst_subcategory where cat_id = $cat_id ORDER BY subcat_name";
            }
            
            $return_data = DB::connection('mysql_dynamic')->select($query);
            $result = json_decode(json_encode($return_data), true); 	
            return $result;
        }
        catch(Exception $e)
        {
            return array();
        }
        
    }

    public function getDepartments($data = array()) {
		$sql = "SELECT * FROM mst_department";
            
        if(!empty($data['searchbox'])){
            $sql .= " WHERE idepartmentid= ". $data['searchbox'];
        }

        $sql .= " ORDER BY vdepartmentname";

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
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
	}

    public function get_zero_movement_report_total($data){
	    
	    if($data['vdepcode']=='')
	    {
	       $data['vdepcode']='All'; 
	    }
	    if($data['vcategorycode']=='')
	    {
	       $data['vcategorycode']='All'; 
	    }
	    if($data['subcat_id']=='')
	    {
	       $data['subcat_id']='All'; 
	    }
	    
	    $sql = "SELECT  count(vitemcode) total 
	            FROM  mst_item
                 where ( FIND_IN_SET(vdepcode, '".$data['vdepcode']."')  or '".$data['vdepcode']."' = 'All') 
                 and (FIND_IN_SET(vcategorycode, '".$data['vcategorycode']."') or '".$data['vcategorycode']."' = 'All')
                 and (FIND_IN_SET(subcat_id, '".$data['subcat_id']."') or '".$data['subcat_id']."' = 'All') 
                 and vbarcode > 30
                 and vbarcode not in 
                     (select a.vitemcode from trn_salesdetail a,trn_sales b where a.isalesid=b.isalesid and vtrntype='Transaction'
                     and b.dtrandate between str_to_date('".$data['p_start_date']."', '%m-%d-%Y') and str_to_date('".$data['p_end_date']."' ,'%m-%d-%Y') 
                     )
                 and estatus='Active' ";
        
            $return_data = DB::connection('mysql_dynamic')->select($sql);
            $result['total'] = json_decode(json_encode($return_data), true); 	
            return $result;
    }
    

    public function deleteItems($data,$sid) {
        $return = array();

        if(isset($data) && count($data) > 0){
            
            $return = [];

            foreach ($data as $key => $value) {                
                if($value > 0 && $value < 100){
                    $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                    return $return;
                }
                $query1 = "SELECT * FROM trn_purchaseorderdetail WHERE vitemid='" .$value. "'";
                $query1return_data = DB::connection('mysql_dynamic')->select($query1);
                if(count($query1return_data) > 0){
                    $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                }
                $query2 = "SELECT * FROM trn_salesdetail as ts, mst_item as mi WHERE mi.vbarcode=ts.vitemcode AND mi.iitemid='" .$value. "'";
                $query2return_data = DB::connection('mysql_dynamic')->select($query2);
                if(count($query2return_data) > 0){
                    $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                }
                $sql = "INSERT INTO mst_delete_table SET  TableName = 'mst_item',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . $sid."'";
                DB::connection('mysql_dynamic')->insert($sql);
                $sql2 = "DELETE FROM mst_item WHERE iitemid='" . (int)$value . "'";
                DB::connection('mysql_dynamic')->delete($sql2);
            }
        }
        $return['success'] = 'Item Deleted Successfully';
        return $return;
    }    

    public function update_status($data){
        $return = array();
        if(isset($data) && count($data) > 0){           
            $return = [];
            foreach ($data as $key => $value) {
                $update = "UPDATE mst_item SET estatus = 'Inactive'  WHERE iitemid='" . (int)$value . "'";
                DB::connection('mysql_dynamic')->update($update);
            }
        }
       $return['success'] = 'Item Updated  Successfully';
       return $return;
  
    }
}
