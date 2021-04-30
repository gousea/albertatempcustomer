<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EditMultipleItems extends Model
{
    protected $connection = 'mysql_dynamic';
    
    
    
    public function addDepartment($sid, $depid=''){
        if(isset($depid) && $depid != "no-update"){
            
            $get_dep_name = DB::connection('mysql_dynamic')->select("select * from mst_department where vdepcode = '".(int)$depid."' ");
            
            if(isset($get_dep_name[0])){
                $dep_name = $get_dep_name[0]->vdepartmentname;
                $dep_desc = $get_dep_name[0]->vdescription;
                $dep_s_time = $get_dep_name[0]->starttime;
                $dep_e_time = $get_dep_name[0]->endtime;
                $dep_isequence = $get_dep_name[0]->isequence;
                $dep_estatus = $get_dep_name[0]->estatus; 
            }
            
            $checkExists = DB::connection('mysql')->select("select * from u".$sid.".mst_department where  vdepartmentname = '".$dep_name."' ");
            if(count($checkExists) > 0){
                if(isset($checkExists[0])){
                    return $checkExists[0]->vdepcode;
                }
            }else{
                $sql = "INSERT INTO u".$sid.".mst_department(vdepartmentname, vdescription, starttime, endtime, isequence, estatus, SID) 
                        values('".$dep_name."', '".$dep_desc."', '".$dep_s_time."', '".$dep_e_time."', '".$dep_isequence."', '".$dep_estatus."', '".$sid."') ";
                DB::connection('mysql')->statement($sql);
                $query_data = "select idepartmentid from u".$sid.".mst_department where vdepartmentname = '" .$dep_name."' ";
                $return_query_data = DB::connection('mysql')->select($query_data);
                if(isset($return_query_data[0])){
                    $last_id = $return_query_data[0]->idepartmentid;
                }
                
                $sql2 = "UPDATE u".$sid.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                DB::connection('mysql')->update($sql2);
                
                return $last_id;
            }
        }
    }
    
    public function addCategory($sid, $catid=''){
        if(isset($catid) && $catid != "no-update"){
            $get_cat_name = DB::connection("mysql_dynamic")->select("SELECT * FROM mst_category where icategoryid = '".$catid."' ");
            if(isset($get_cat_name[0])){
                $cat_name = $get_cat_name[0]->vcategoryname;
                $cat_desc = $get_cat_name[0]->vdescription;
                $cat_type = $get_cat_name[0]->vcategorttype;
                $cat_isequence = $get_cat_name[0]->isequence;
                $cat_status = $get_cat_name[0]->estatus;
                $cat_dep_code = $get_cat_name[0]->dept_code;
            }
            
            $checkExists = DB::connection('mysql')->select("select * from u".$sid.".mst_category where  vcategoryname = '".$cat_name."' ");
            if(count($checkExists) > 0){
                if(isset($checkExists[0])){
                    return $checkExists[0]->vcategorycode;
                }
            }else {
                $depname = DB::connection("mysql_dynamic")->select("select * from mst_department where vdepcode = '".$cat_dep_code."' ");
                if(isset($depname[0])){
                    $department_name = $depname[0]->vdepartmentname;
                    $dep_desc = $depname[0]->vdescription;
                    $dep_s_time = $depname[0]->starttime;
                    $dep_e_time = $depname[0]->endtime;
                    $dep_isequence = $depname[0]->isequence;
                    $dep_estatus = $dep->estatus; 
                }
                
                $department_code = DB::connection("mysql")->select("select vdepcode from u".$sid.".mst_department where vdepartmentname = '".$department_name."' ");
                
                if(count($department_code) > 0){
                    if(isset($department_code[0])){
                        $depcode = $department_code[0]->vdepcode;
                    }
                }else {
                    $sql = "INSERT INTO u".$sid.".mst_department(vdepartmentname, vdescription, starttime, endtime, isequence, estatus, SID) 
                        values('".$department_name."', '".$dep_desc."', '".$dep_s_time."', '".$dep_e_time."', '".$dep_isequence."', '".$dep_estatus."', '".$sid."') ";
                    DB::connection('mysql')->statement($sql);
                    $query_data = "select idepartmentid from u".$sid.".mst_department where vdepartmentname = '" .$department_name."' ";
                    $return_query_data = DB::connection('mysql')->select($query_data);
                    if(isset($return_query_data[0])){
                        $last_id = $return_query_data[0]->idepartmentid;  
                    }
                    
                    
                    $sql2 = "UPDATE u".$sid.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                    DB::connection('mysql')->update($sql2);
                    $depcode = $last_id;
                }
                
                $inserting = DB::connection("mysql")->statement("Insert INTO u".$sid.".mst_category (vcategoryname, vdescription, vcategorttype, isequence, estatus, dept_code, SID) VAlues('".$cat_name."', '".$cat_desc."', '".$cat_type."',  '".$cat_isequence."', '".$cat_status."', '".$depcode."', '".$sid."') ");
                $lastinsertcat_id = DB::connection("mysql")->select("SELECT * FROM u".$sid.".mst_category order by icategoryid DESC limit 1"); 
                if(isset($lastinsertcat_id[0])){
                    $vcategorycode = $lastinsertcat_id[0]->icategoryid;
                }
                $updating = DB::connection("mysql")->statement("UPDATE u".$sid.".mst_category SET vcategorycode = '".$vcategorycode."'  WHERE icategoryid = '".$vcategorycode."' ");
                
                return $vcategorycode;
            }
        }
    }
    
    public function addSubcategory($sid, $subcatid=''){
        if(isset($subcatid) && $subcatid != "no-update" && $subcatid != '--Select Subcategory--'){
            $get_subcat_name = DB::connection('mysql_dynamic')->select("select * from mst_subcategory where subcat_id = '".$subcatid."' ");
            
            // $sub_cat_name = '';
            // $sub_cat_status = '';
            // $cat_id = '';
                
            if(isset($get_subcat_name[0])){
                $sub_cat_name = $get_subcat_name[0]->subcat_name;
                $sub_cat_status = $get_subcat_name[0]->status;
                $cat_id = $get_subcat_name[0]->cat_id;
            }
            $checkExists = DB::connection('mysql')->select("select * from u".$sid.".mst_subcategory where  subcat_name = '".$sub_cat_name."' ");
            if(count($checkExists) > 0){
                if(isset($checkExists[0])){
                    return $checkExists[0]->subcat_id;
                } 
            }else {
                
                $category = DB::connection("mysql_dynamic")->select("select * from mst_category where icategoryid = '".$cat_id."' ");
                if(isset($category[0])){
                    $cat_name = $category[0]->vcategoryname;
                    $cat_desc = $category[0]->vdescription;
                    $cat_type = $category[0]->vcategorttype;
                    $cat_isequence = $category[0]->isequence;
                    $cat_status = $category[0]->estatus;
                    $cat_dep_code = $category[0]->dept_code;
                }
                
                $checkCateogory  = DB::connection("mysql")->select("select * from u".$sid.".mst_category where vcategoryname = '".$cat_name."' ");
                if(count($checkCateogory) > 0){
                    if(isset($checkCateogory[0])){
                        $new_cat_id = $checkCateogory[0]->icategoryid;
                    }
                    $created_at = date('Y-m-d');
                    $LastUpdate = date('Y-m-d');
                    $status     = "Active";
                    $insert_query = "INSERT INTO u".$sid.".mst_subcategory (cat_id,subcat_name,created_at,LastUpdate,SID) values ('".$new_cat_id."', '".$sub_cat_name."', '".$created_at."', '".$LastUpdate."', '".$sid."' )";
                    DB::connection('mysql')->statement($insert_query);
                    
                    $subcat_id = DB::connection("mysql")->select("select subcat_id from u".$sid.".mst_subcategory order by subcat_id DESC limit 1 ");
                    if(isset($subcat_id[0])) {
                        return $subcat_id[0]->subcat_id;
                    }
                }else {
                    $depname = DB::connection("mysql_dynamic")->select("select * from mst_department where vdepcode = '".$cat_dep_code."' ");
                    if(isset($depname[0])){
                        $department_name = $depname[0]->vdepartmentname;
                        $dep_desc = $depname[0]->vdescription;
                        $dep_s_time = $depname[0]->starttime;
                        $dep_e_time = $depname[0]->endtime;
                        $dep_isequence = $depname[0]->isequence;
                        $dep_estatus = $depname[0]->estatus; 
                    }
                    
                    $department_code = DB::connection("mysql")->select("select vdepcode from u".$sid.".mst_department where vdepartmentname = '".$department_name."' ");
                    
                    if(count($department_code) > 0){
                        if(isset($department_code[0])){
                            $depcode = $department_code[0]->vdepcode;
                        }
                    }else {
                        $sql = "INSERT INTO u".$sid.".mst_department(vdepartmentname, vdescription, starttime, endtime, isequence, estatus, SID) 
                            values('".$department_name."', '".$dep_desc."', '".$dep_s_time."', '".$dep_e_time."', '".$dep_isequence."', '".$dep_estatus."', '".$sid."') ";
                        DB::connection('mysql')->statement($sql);
                        $query_data = "select idepartmentid from u".$sid.".mst_department where vdepartmentname = '" .$department_name."' ";
                        $return_query_data = DB::connection('mysql')->select($query_data);
                        if(isset($return_query_data[0])){
                            $last_id = $return_query_data[0]->idepartmentid;
                        }
                        
                        $sql2 = "UPDATE u".$sid.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                        DB::connection('mysql')->update($sql2);
                        $depcode = $last_id;
                    }
                    
                    $inserting = DB::connection("mysql")->statement("Insert INTO u".$sid.".mst_category (vcategoryname, vdescription, vcategorttype, isequence, estatus, dept_code, SID) 
                    VAlues('".$cat_name."', '".$cat_desc."', '".$cat_type."',  '".$cat_isequence."', '".$cat_status."', '".$depcode."', '".$sid."') ");
                    $lastinsertcat_id = DB::connection("mysql")->select("SELECT * FROM u".$sid.".mst_category order by icategoryid DESC limit 1"); 
                    if(isset($lastinsertcat_id[0])){
                        $vcategorycode = $lastinsertcat_id[0]->icategoryid;
                    }
                    $updating = DB::connection("mysql")->statement("UPDATE u".$sid.".mst_category SET vcategorycode = '".$vcategorycode."'  WHERE icategoryid = '".$vcategorycode."' ");
                    
                    
                    $created_at = date('Y-m-d');
                    $LastUpdate = date('Y-m-d');
                    $status     = "Active";
                    $insert_query = "INSERT INTO u".$sid.".mst_subcategory (cat_id,subcat_name,created_at,LastUpdate,SID) values ('".$vcategorycode."', '".$sub_cat_name."', '".$created_at."', '".$LastUpdate."', '".$sid."' )";
                    DB::connection('mysql')->statement($insert_query);
                    
                    $subcat_id = DB::connection("mysql")->select("select subcat_id from u".$sid.".mst_subcategory order by subcat_id DESC limit 1 ");
                    if(isset($subcat_id[0])) {
                        return $subcat_id[0]->subcat_id;
                    }
                }
                
            }
        }
    }
    
    public function addSupplier($sid, $supplierid=''){
        
        if(isset($supplierid) && $supplierid != "no-update"){
            $supplier = DB::connection("mysql_dynamic")->select("SELECT * FROM mst_supplier where isupplierid = '".$supplierid."' ");
            if(isset($supplier[0])){
                $vcompanyname   = $supplier[0]->vcompanyname;
                $vvendortype    = $supplier[0]->vvendortype;
                $vfnmae         = $supplier[0]->vfnmae;
                $vlname         = $supplier[0]->vlname;
                $vcode          = $supplier[0]->vcode;
                $vaddress1      = $supplier[0]->vaddress1; 
                $vcity          = $supplier[0]->vcity;
                $vstate         = $supplier[0]->vstate;
                $vzip           = $supplier[0]->vzip;
                $vcountry       = $supplier[0]->vcountry;
                $vphone         = $supplier[0]->vphone;
                $vemail         = $supplier[0]->vemail;
                $estatus        = $supplier[0]->estatus;
                $plcbtype       = $supplier[0]->plcbtype;
                $edi            = $supplier[0]->edi;
            }
            $checkExists = DB::connection("mysql")->select("select * from u".$sid.".mst_supplier where vcompanyname = '".$vcompanyname."' ");
            
            if(count($checkExists) > 0){
                if(isset($checkExists[0])){
                    return $checkExists[0]->isupplierid;
                }
            }else{
                $insert_vendor_sql = "INSERT INTO u".$sid.".mst_supplier (vcompanyname, vvendortype, vfnmae, vlname, vcode,
                                vaddress1, vcity, vstate, vphone, vzip, vcountry, vemail, plcbtype, edi, estatus, SID) 
                                VALUES ( '".$vcompanyname."','".$vvendortype."', '".$vfnmae."', '".$vlname."', '".$vcode."', '".$vaddress1."', 
                                '".$vcity."', '".$vstate."', '".$vphone."', '".$vzip."', '".$vcountry."', '".$vemail."', '".$plcbtype."',
                                '".$edi."', '".$estatus."', '".$sid."' ) ";
                            
                $vendor = DB::connection("mysql")->statement($insert_vendor_sql);
                $sup_query = "Select isupplierid from u".$sid.".mst_supplier ORDER BY  isupplierid DESC LIMIT 1";
                $sup_id = DB::connection("mysql")->select($sup_query);
                
                if(isset($sup_id[0])){
                   $isuppliercode = $sup_id[0]->isupplierid; 
                }
                
                $update_query = "UPDATE u".$sid.".mst_supplier SET vsuppliercode = '".$isuppliercode."' WHERE isupplierid = '".$isuppliercode."' ";
                DB::connection("mysql")->update($update_query);
                
                return $isuppliercode;
            }
            
           
        }
    }
    
    public function addSize($sid, $vsize=''){
        if(isset($vsize)) {
            $get_size = DB::connection("mysql")->select("SELECT * FROM u".$sid.".mst_size where vsize = '".$vsize."' ");
            if(count($get_size) > 0) {
                return $vsize;
            }
            else {
                $insert = DB::connection('mysql')->statement("INSERT into u".$sid.".mst_size (vsize, SID) values ('".$vsize."', '".$sid."') ");
                return $vsize;
            }
        }
    }
    
    
    public function editlistItems($data = array()) {
        $success =array();
        $error =array();
        if(isset($data) && count($data) > 0){
            if(isset($data['stores_hq'])){
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                }
                
                try {
                    if(count($data['item_ids']) > 0){
                        foreach($data['item_ids'] as $value){
                                $barcode = DB::connection('mysql_dynamic')->select("SELECT vbarcode FROM mst_item WHERE iitemid='". (int)$value ."'");
                                
                                if(isset($barcode[0])){
                                    $vbarcode = $barcode[0]->vbarcode;
                                }
                                
                                foreach($stores as $store){
                                    $item_details = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE vbarcode='".$vbarcode."'");
                                        
                                    if(count($item_details) > 0){
                                        
                                    
                                        if(isset($item_details[0])){
                                            $iitem_id = $item_details[0]->iitemid;
                                        }
                                        
                                        if(isset($data['update_vdepcode'])){
                                            $dep_code   = $this->addDepartment($store, $data['update_vdepcode']);
                                        }
                                        if(isset($data['update_vcategorycode'])){
                                            $cat_code   = $this->addCategory($store,$data['update_vcategorycode']);
                                        }
                                        if(isset($data['update_subcat_id'])){
                                            $sub_cat_id = $this->addSubcategory($store, $data['update_subcat_id']);
                                        }
                                        if(isset($data['update_vsuppliercode'])){
                                           $supplier_id     = $this->addSupplier($store, $data['update_vsuppliercode']); 
                                        }
                                        if(isset($data['update_vsize'])){
                                            $size       = $this->addSize($store,$data['update_vsize']);
                                        }
                                        
                                        
                                    
                                        $updated_column = array();
                                        $current_item = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid='".(int)$iitem_id."' ");
                                        $current_item = isset($current_item[0])?(array)$current_item[0]:[];
                                        
                                        $sql = "";
                                        $sql .= "UPDATE u".$store.".mst_item SET";
                                        
                                        if($data['update_vitemtype'] != 'no-update'){
                                            $sql .= " vitemtype='" . ($data['update_vitemtype']) . "',";
                                            $updated_column[] = 'vitemtype';
                                        }
                                            
                                        if($data['update_vcategorycode'] != 'no-update'){
                                            if(isset($cat_code)){
                                                $sql .= " vcategorycode='" .$cat_code. "',"; 
                                            }
                                            $updated_column[] = 'vcategorycode';
                                        }
                                            
                                        if($data['update_vunitcode'] != 'no-update'){
                                            $sql .= " vunitcode='" . ($data['update_vunitcode']) . "',";
                                            $updated_column[] = 'vunitcode';
                                        }
                                                    
                                        if($data['update_vsize'] != 'no-update'){
                                            if(isset($size)){
                                                $sql .= " vsize='" .$size. "',";  
                                            }
                                            $updated_column[] = 'vsize';
                                        }
                                            
                                        if($data['update_vdepcode'] != 'no-update'){
                                            if(isset($dep_code)){
                                                $sql .= " vdepcode='" . $dep_code . "',";
                                            }
                                        
                                            $updated_column[] = 'vdepcode';
                                        }
                                            
                                        if($data['update_vsuppliercode'] != 'no-update'){
                                            if(isset($supplier_id)){
                                                $sql .= " vsuppliercode='" .$supplier_id. "',";
                                            }
                                            $updated_column[] = 'vsuppliercode';
                                        }
                                            
                                        if($data['update_vfooditem'] != 'no-update'){
                                            $sql .= " vfooditem='" . ($data['update_vfooditem']) . "',";
                                            $updated_column[] = 'vfooditem';
                                        }
                                            
                                        if($data['update_vtax1'] != 'no-update'){
                                            $sql .= " vtax1='" . ($data['update_vtax1']) . "',";
                                            $updated_column[] = 'vtax1';
                                        }
                                            
                                        if($data['update_vtax2'] != 'no-update'){
                                            $sql .= " vtax2='" . ($data['update_vtax2']) . "',";
                                            $updated_column[] = 'vtax2';
                                        }
                                            
                                        if($data['update_aisleid'] != 'no-update'){
                                            $sql .= " aisleid='" . ($data['update_aisleid']) . "',";
                                            $updated_column[] = 'aisleid';
                                        }
                                            
                                        if($data['update_shelfid'] != 'no-update'){
                                            $sql .= " shelfid='" . ($data['update_shelfid']) . "',";
                                            $updated_column[] = 'shelfid';
                                        }
                                            
                                        if($data['update_shelvingid'] != 'no-update'){
                                            $sql .= " shelvingid='" . ($data['update_shelvingid']) . "',";
                                            $updated_column[] = 'shelvingid';
                                        }
                                            
                                        // if(isset($data['update_dunitprice_checkbox']) && $data['update_dunitprice_checkbox'] == 'Y'){
                                        //     $sql .= " dunitprice='0',";
                                        //     $updated_column[] = 'dunitprice';
                                        // }elseif(isset($data['update_dunitprice']) && $data['update_dunitprice'] != '' && $data['update_dunitprice'] != '0'){
                                        //     $sql .= " dunitprice='" . ($data['update_dunitprice']) . "',";
                                        //     $updated_column[] = 'dunitprice';
                                        // }
                                            
                                        if(isset($data['update_dunitprice_checkbox']) && $data['update_dunitprice_checkbox'] == 'Y'){
                                            $sql .= " dunitprice='0',";
                                            $new_dunitprice=0;
                                            $updated_column[] = 'dunitprice';
                                        }else if(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && !isset($data['update_dunitprice_increment']) && !isset($data['update_dunitprice_increment_percent'])){
                                            $sql .= " dunitprice='" . ($data['update_dunitprice']) . "',";
                                            $updated_column[] = 'dunitprice';
                                            $new_dunitprice=$data['update_dunitprice'];
                                        }elseif(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && isset($data['update_dunitprice_increment']) && $data['update_dunitprice_increment'] == 'Y' && !isset($data['update_dunitprice_increment_percent'])){
                                            $new_dunitprice = $current_item['dunitprice'] + ($data['update_dunitprice']);
                                            $sql .= " dunitprice='" . $new_dunitprice . "',";
                                            $updated_column[] = 'dunitprice';
                                        }elseif(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && !isset($data['update_dunitprice_increment']) && isset($data['update_dunitprice_increment_percent']) && $data['update_dunitprice_increment_percent'] == 'Y'){
            
                                            $new_dunitprice = (($current_item['dunitprice'] * ($data['update_dunitprice'])) / 100) + ($current_item['dunitprice']);
                                            $sql .= " dunitprice='" . $new_dunitprice . "',";
                                            $updated_column[] = 'dunitprice';
                                            
                                        }else{
                                            $new_dunitprice= $current_item['dunitprice'];
                                        }
                                            
                                        if(isset($data['update_npack_checkbox']) && $data['update_npack_checkbox'] == 'Y'){
                                            $sql .= " npack='1',";
                                            $current_npack = 1;
                                            $updated_column[] = 'npack';
                                        }elseif(isset($data['update_npack']) && $data['update_npack'] != '' && $data['update_npack'] != '1' && $data['update_npack'] != '0'){
                                            $sql .= " npack='" . ($data['update_npack']) . "',";
                                            $current_npack = $data['update_npack'];
                                            $updated_column[] = 'npack'; 
                                        }else{
                                            $current_npack = $current_item['npack'];
                                            if($current_npack == 0){
                                                $current_npack = 1;
                                            }
                                        }
                                        
                                        if(isset($data['update_nsellunit_checkbox']) && $data['update_nsellunit_checkbox'] == 'Y'){
                                            $sql .= " nsellunit='1',";
                                            $updated_column[] = 'nsellunit';
                                        }elseif(isset($data['update_nsellunit']) && $data['update_nsellunit'] != '' && $data['update_nsellunit'] != '1' && $data['update_nsellunit'] != '0'){
                                            $sql .= " nsellunit='" . ($data['update_nsellunit']) . "',";
                                            $updated_column[] = 'nsellunit';
                                        }
                                            
                                        // if(isset($data['update_dcostprice_checkbox']) && $data['update_dcostprice_checkbox'] == 'Y'){
                                        //     $sql .= " dcostprice='0',";
                                        //     $current_dcostprice = 0;
                                        //     $updated_column[] = 'dcostprice';
                                        // }elseif(isset($data['update_dcostprice']) && $data['update_dcostprice'] != '' && $data['update_dcostprice'] != '0'){
                                        //     $sql .= " dcostprice='" . ($data['update_dcostprice']) . "',";
                                        //     $current_dcostprice = $data['update_dcostprice'];
                                        //     $updated_column[] = 'dcostprice';
                                        // }else{
                                        //     $current_dcostprice = $current_item['dcostprice'];
                                        // }
                                        
                                        if(isset($data['update_dcostprice_checkbox']) && $data['update_dcostprice_checkbox'] == 'Y' && $current_item['isparentchild'] != 1){
                                            // $sql .= " dcostprice='0',";
                                            $current_dcostprice = 0;
                                            $new_dcostprice = 0;
                                            $updated_column[] = 'dcostprice';
                                            $sql .= " dcostprice='0', new_costprice='0',";
                    
                                        }else if(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && !isset($data['update_dcostprice_increment_percent']) && $current_item['isparentchild'] !=1){
                                            // $sql .= " dcostprice='" . ($data['update_dcostprice']) . "',";
                                            $sql .= " dcostprice='" . ($data['update_dcostprice']) . "', new_costprice='" . ($data['update_dcostprice']) . "',";
                                            $current_dcostprice = $data['update_dcostprice'];
                                            $new_dcostprice = $data['update_dcostprice'];
                                            $updated_column[] = 'dcostprice';
                                        // }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && isset($data['update_dcostprice_increment']) && $data['update_dcostprice_increment'] == 'Y' && !isset($data['update_dcostprice_increment_percent']) && $current_item['isparentchild'] !=1){
                                        }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && isset($data['update_dcostprice_increment']) && $data['update_dcostprice_increment'] == 'Y' && !isset($data['update_dcostprice_increment_percent']) ){
                                            $new_dcostprice = $current_item['dcostprice'] + ($data['update_dcostprice']);
                                            // $sql .= " dcostprice='" . $new_dcostprice . "',";
                                            $sql .= " dcostprice='" . $new_dcostprice . "', new_costprice='" . $new_dcostprice . "',";
                                            $current_dcostprice = $new_dcostprice;
                                            $updated_column[] = 'dcostprice';
                                        // }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && isset($data['update_dcostprice_increment_percent']) && $data['update_dcostprice_increment_percent'] == 'Y' && $current_item['isparentchild'] !=1){
                                        }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && isset($data['update_dcostprice_increment_percent']) && $data['update_dcostprice_increment_percent'] == 'Y' ){
                                            
                                            $new_dcostprice = (($current_item['dcostprice'] * ($data['update_dcostprice'])) / 100) + ($current_item['dcostprice']);
                                            // $sql .= " dcostprice='" . $new_dcostprice . "',";
                                            $sql .= " dcostprice='" . $new_dcostprice . "', new_costprice='" . $new_dcostprice . "',";
                                            $current_dcostprice = $new_dcostprice;
                                            $updated_column[] = 'dcostprice';
                                        
                                        //=====commented on 25/06/2020===========
                                        }elseif(isset($data['update_dcostprice']) && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_increment']) && !isset($data['update_dcostprice_increment_percent'])){
                                            
                                            $sql .= " dcostprice='" . ($data['update_dcostprice']) . "', new_costprice='" . ($data['update_dcostprice']) . "',";
                                            $current_dcostprice = $data['update_dcostprice'];
                                            $new_dcostprice = $data['update_dcostprice'];
                                            $updated_column[] = 'dcostprice';
                                            
                                        }else{
                                            $new_dcostprice = $current_item['dcostprice'];
                                            $current_dcostprice = $current_item['dcostprice'];
                                        }
                                        
                                        $current_nunitcost = $current_dcostprice /  $current_npack;
                                        $sql .= " nunitcost='" . $current_nunitcost . "',";
                                            
                                        if($data['update_visinventory'] != 'no-update'){
                                            $sql .= " visinventory='" . ($data['update_visinventory']) . "',";
                                            $updated_column[] = 'visinventory';
                                        }
                                            
                                        if($data['update_ndiscountper'] != ''){
                                            $sql .= " ndiscountper='" . (float)$data['update_ndiscountper'] . "',";
                                            $updated_column[] = 'ndiscountper';
                                        }
                                            
                                        if(isset($data['update_nlevel2']) && $data['update_nlevel2'] != ''){
                                            $sql .= " nlevel2='" . ($data['update_nlevel2']) . "',";
                                            $updated_column[] = 'nlevel2';
                                        }
                                            
                                        if(isset($data['update_nlevel3']) && $data['update_nlevel3'] != ''){
                                            $sql .= " nlevel3='" . ($data['update_nlevel3']) . "',";
                                            $updated_column[] = 'nlevel3';
                                        }
                                            
                                        if(isset($data['update_nlevel4']) && $data['update_nlevel4'] != ''){
                                            $sql .= " nlevel4='" . ($data['update_nlevel4']) . "',";
                                            $updated_column[] = 'nlevel4';
                                        }
                                            
                                        if($data['update_wicitem'] != 'no-update'){
                                            $sql .= " wicitem='" . ($data['update_wicitem']) . "',";
                                            $updated_column[] = 'wicitem';
                                        }
                                            
                                        if($data['update_stationid'] != 'no-update'){
                                            $sql .= " stationid='" . ($data['update_stationid']) . "',";
                                            $updated_column[] = 'stationid';
                                        }
                                            
                                        if($data['update_vbarcodetype'] != 'no-update'){
                                            $sql .= " vbarcodetype='" . ($data['update_vbarcodetype']) . "',";
                                            $updated_column[] = 'vbarcodetype';
                                        }
                                            
                                        if($data['update_vdiscount'] != 'no-update'){
                                            $sql .= " vdiscount='" . ($data['update_vdiscount']) . "',";
                                            $updated_column[] = 'vdiscount';
                                        }
            
                                        if($data['update_liability'] != 'no-update'){
                                            $sql .= " liability='" . ($data['update_liability']) . "',";
                                            $updated_column[] = 'liability';
                                        }
                                            
                                        if($data['update_ireorderpoint'] != ''){
                                            $sql .= " ireorderpoint='" . ($data['update_ireorderpoint']) . "',";
                                            $updated_column[] = 'ireorderpoint';
                                        }
                                            
                                        if($data['update_norderqtyupto'] != ''){
                                            $sql .= " norderqtyupto='" . ($data['update_norderqtyupto']) . "',";
                                            $updated_column[] = 'norderqtyupto';
                                        }
                                            
                                        if($data['update_vintage'] != ''){
                                            $sql .= " vintage='" . ($data['update_vintage']) . "',";
                                            $updated_column[] = 'vintage';
                                        }
                                            
                                        if($data['update_vshowsalesinzreport'] != 'no-update'){
                                            $sql .= " vshowsalesinzreport='" . ($data['update_vshowsalesinzreport']) . "',";
                                            $updated_column[] = 'vshowsalesinzreport';
                                        }
                                            
                                        if($data['update_visinventory'] != 'no-update'){
                                            $sql .= " visinventory='" . ($data['update_visinventory']) . "',";
                                            $updated_column[] = 'visinventory';
                                        }
                                            
                                        if($data['update_vageverify'] != 'no-update'){
                                            $sql .= " vageverify='" . ($data['update_vageverify']) . "',";
                                            $updated_column[] = 'vageverify';
                                        }
                                            
                                        if($data['update_nbottledepositamt'] != ''){
                                            if($data['update_nbottledepositamt'] == '0.00' || $data['update_nbottledepositamt'] == '0' || $data['update_nbottledepositamt'] == '0.0' ){
                                                $sql .= " ebottledeposit='No',";
                                                $sql .= " nbottledepositamt='0.00',";
                                            $updated_column[] = 'ebottledeposit';
                                            $updated_column[] = 'nbottledepositamt';
                                            }else{
                                                $sql .= " ebottledeposit='Yes',";
                                                $sql .= " nbottledepositamt='". ($data['update_nbottledepositamt']) ."',";
                                                $updated_column[] = 'ebottledeposit';
                                                $updated_column[] = 'nbottledepositamt';
                                            }
                                        }
                                            
                                        if($data['update_rating'] != ''){
                                            $sql .= " rating='" . ($data['update_rating']) . "',";
                                            $updated_column[] = 'rating';
                                        }
                                        
                                        // Update subcategory
                                        if(isset($data['update_subcat_id']) && $data['update_subcat_id'] != 'no-update'){
                                            if(isset($sub_cat_id)){
                                                $sql .= " subcat_id='" .$sub_cat_id . "',";
                                            }
                                            $updated_column[] = 'subcategory';
                                        }else{
                                            if((isset($data['update_vcategorycode']) && $data['update_vcategorycode'] != 'no-update') && (isset($data['update_vdepcode']) && $data['update_vdepcode'] != 'no-update')){
                                                $sql .= " subcat_id='" . null . "',";
                                                $updated_column[] = 'subcategory';
                                            }
                                        }
                                        
                                        // Update manufacturer
                                        if($data['update_manufacturerid'] != 'no-update'){
                                            $sql .= " manufacturer_id='" . ($data['update_manufacturerid']) . "',";
                                            $updated_column[] = 'manufcaturer';
                                        }
                                            
                                            
                                        if($data['update_estatus'] != 'no-update'){
                                            $sql .= " estatus='" . ($data['update_estatus']) . "',";
                                            $updated_column[] = 'estatus';
                                        }
                                            
                                        $sql = rtrim($sql,',');
                                        $sql .= " WHERE iitemid = '" . (int)$current_item['iitemid'] . "'";
                                      
                                        DB::connection('mysql')->update($sql);
                                        //mst plcb item
                                            
                                        if(isset($data['options_data']) && count($data['options_data']) > 0){
                                            
                                            $mst_item_size = DB::connection('mysql')->select("SELECT * FROM  u".$store.".mst_item_size WHERE item_id= '". (int)$current_item['iitemid'] ."' ");
                                            $mst_item_size = isset($mst_item_size[0])?(array)$mst_item_size[0]:[];
                                            
                                            if(count($mst_item_size) > 0){
                                                
                                                DB::connection('mysql')->update("UPDATE  u".$store.".mst_item_size SET  unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."' WHERE item_id = '" . (int)$current_item['iitemid'] . "'");
                                                
                                            }else{
                                                DB::connection('mysql')->insert("INSERT INTO  u".$store.".mst_item_size SET  item_id = '". (int)$current_item['iitemid'] ."',unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."',SID = '" . (int)$store."'");
                                            }
                                                
                                            $mst_plcb_item = DB::connection('mysql')->select("SELECT * FROM  u".$store.".mst_plcb_item WHERE item_id= '". (int)$current_item['iitemid'] ."' ");
                                            $mst_plcb_item = isset($mst_plcb_item[0])?(array)$mst_plcb_item[0]:[];
                                            
                                            if(count($mst_plcb_item) > 0){
                                                DB::connection('mysql')->update("UPDATE mst_plcb_item SET  bucket_id = '". (int)$data['options_data']['bucket_id'] ."',malt = '". (int)$data['options_data']['malt'] ."' WHERE item_id = '" . (int)$current_item['iitemid'] . "'");
                                            }else{
                                                DB::connection('mysql')->insert("INSERT INTO mst_plcb_item SET  item_id = '". (int)$current_item['iitemid'] ."',bucket_id = '". (int)$data['options_data']['bucket_id'] ."',prev_mo_beg_qty = '". $current_item['iqtyonhand'] ."',prev_mo_end_qty = '". $current_item['iqtyonhand'] ."',malt = '". (int)$data['options_data']['malt'] ."',SID = '" . (int)$store."'");
                                            }
                                        }else{
                                            $checkexist_mst_item_size = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item_size WHERE item_id='" . (int)$current_item['iitemid'] . "'");
                                            $checkexist_mst_item_size = isset($checkexist_mst_item_size[0])?(array)$checkexist_mst_item_size[0]:[];
                                            
                                            if(count($checkexist_mst_item_size) > 0){
            
                                                DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_delete_table SET  TableName = 'mst_item_size',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_item_size['id'] . "',SID = '" . (int)$store."'");
            
                                                DB::connection('mysql')->statement("DELETE FROM u".$store.".mst_item_size WHERE id='" . (int)$checkexist_mst_item_size['id'] . "'");
            
                                            }
            
                                            $checkexist_mst_plcb_item = DB::connection('mysql')->select("SELECT * FROM  u".$store.".mst_plcb_item WHERE item_id='" . (int)$current_item['iitemid'] . "'");
                                            $checkexist_mst_plcb_item = isset($checkexist_mst_plcb_item[0])?(array)$checkexist_mst_plcb_item[0]:[];
                                            
                                            if(count($checkexist_mst_plcb_item) > 0){
            
                                                DB::connection('mysql')->insert("INSERT INTO  u".$store.".mst_delete_table SET  TableName = 'mst_plcb_item',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_plcb_item['id'] . "',SID = '" . (int)$store."'");
            
                                                DB::connection('mysql')->statement("DELETE FROM  u".$store.".mst_plcb_item WHERE id='" . (int)$checkexist_mst_plcb_item['id'] . "'");
            
                                            }
                                        }
                                            
                                        //mst plcb item
                                            
                                        //trn_itempricecosthistory
                                        if($current_item['dunitprice'] != $new_dunitprice){
                                            DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'EitemPrice', noldamt = '" . ($current_item['dunitprice']) . "', nnewamt = '" . ($new_dunitprice) . "', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                            
                                        if($current_item['dcostprice'] != $new_dcostprice){
                                            DB::connection('mysql')->statement("INSERT INTO u".$store.".trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'EitemCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_dcostprice) . "', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                            
                                        //trn_itempricecosthistory
                                            
                                        //trn_webadmin_history
                                        $result = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = 'u".$store."'  AND table_name = 'trn_webadmin_history'");
                                        $result = isset($result[0])?(array)$result[0]:[];
                                        if(count($result)){ 
                                            if((($current_item['dunitprice'] != $new_dunitprice) && ($current_item['dunitprice'] != '0.00')) && (($current_item['dcostprice'] != $new_dcostprice) && ($current_item['dcostprice'] != '0.0000'))){
                                                $old_item_values = $current_item;
                                                unset($old_item_values['itemimage']);
            
                                                $x_general = new \stdClass();
                                                $x_general->old_item_values = $old_item_values;
                                                
                                                $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
                                                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                                
                                                unset($new_item_values['itemimage']);
                                                $x_general->new_item_values = $new_item_values;
                                                $x_general = addslashes(json_encode($x_general));
                                                try{
                                                    DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'All', oldamount = '0', newamount = '0', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)$store."'");
                                                }
                                                catch (QueryException $e) {
                                                    Log::error($e);
                                                }
                                            }else{
                                                if(($current_item['dunitprice'] != $new_dunitprice) && ($current_item['dunitprice'] != '0.00')){
                                                    $old_item_values = $current_item;
                                                    unset($old_item_values['itemimage']);
            
                                                    $x_general = new \stdClass();
                                                    $x_general->old_item_values = $old_item_values;
                                                    
                                                    $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
                                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                                    
                                                    unset($new_item_values['itemimage']);
                                                    $x_general->new_item_values = $new_item_values;
                                                    $x_general = addslashes(json_encode($x_general));
                                                    try{
            
                                                        DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Price', oldamount = '" . $current_item['dunitprice'] . "', newamount = '". $new_dunitprice ."', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                                    }
                                                    catch (QueryException $e) {
                                                        Log::error($e);
                                                    }
                                                }
                                                
                                                if(($current_item['dcostprice'] != $new_dcostprice) && ($current_item['dcostprice'] != '0.0000')){
                                                    $old_item_values = $current_item;
                                                    unset($old_item_values['itemimage']);
                                                    $x_general = new \stdClass();
                                                    $x_general->old_item_values = $old_item_values;
                                                    
                                                    $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
                                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                                    
                                                    unset($new_item_values['itemimage']);
                                                    $x_general->new_item_values = $new_item_values;
                                                    $x_general = addslashes(json_encode($x_general));
                                                    try{
            
                                                        DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $current_item['dcostprice'] . "', newamount = '". $new_dcostprice ."', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                                    }
                                                    catch (QueryException $e) {
                                                        Log::error($e);
                                                    }
                                                }
                                            }
                                        }
                                            
                                        //trn_webadmin_history
                                            
                                        // $user_id = Auth::user()->id;
                                        // if(count($updated_column) > 0){
                                        //     $object = new stdClass();
                                        //     foreach ($updated_column as $k => $u_c){
                                        //         $object->$k = $u_c;
                                        //     }
                                        //     $sql_insert = "";
                                        //     $sql_insert .= "INSERT INTO last_modified SET user_id='". (int)$user_id ."', table_name='mst_item', updated_column='". json_encode($object) ."', updated_item='". $value ."', SID='". session()->get('sid') ."'";
                                        //     DB::connection('mysql_dynamic')->select($sql_insert);                               
                                        // }
                                        
                                        // update child values
                                        $isParentCheck = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid='". (int)$current_item['iitemid'] ."'");
                                        $isParentCheck = isset($isParentCheck[0])?(array)$isParentCheck[0]:[];
                                            
                                        if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                                            $child_items = DB::connection('mysql')->select("SELECT `iitemid`,`vbarcode`,`dcostprice`,`npack` FROM u".$store.".mst_item WHERE parentmasterid= '". (int)$current_item['iitemid'] ."' ");
                                            $child_items = array_map(function ($value) {
                                                return (array)$value;
                                            }, $child_items);
                                            
                                            if(count($child_items) > 0){
                                                foreach($child_items as $chi_item){
            
                                                    //trn_webadmin_history
                                                    //trn_webadmin_history
                                                    $result = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = 'u".$store."'  AND table_name = 'trn_webadmin_history'");
                                                    $result = isset($result[0])?(array)$result[0]:[];
                                                    if(count($result)){ 
                                                        $old_item_values = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                                        $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
                                                        
                                                        unset($old_item_values['itemimage']);
            
                                                        $x_general_child = new \stdClass();
                                                        $x_general_child->is_child = 'Yes';
                                                        $x_general_child->parentmasterid = $old_item_values['parentmasterid'];
                                                        $x_general_child->old_item_values = $old_item_values;
                                                        try{
            
                                                            DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_webadmin_history SET  itemid = '" . ($chi_item['iitemid']) . "',userid = '" . Auth::user()->id . "',barcode = '" . ($chi_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $chi_item['dcostprice'] . "', newamount = '". (($chi_item['npack']) * (($isParentCheck['nunitcost']))) ."', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                                        }
                                                        catch (QueryException $e) {
                                                            Log::error($e);
                                                        }
                                                        // $trn_webadmin_history_last_id_child = $this->db2->getLastId();
                                                        $return = DB::connection('mysql')->select("SELECT historyid FROM u".$store.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                                        
                                                        $trn_webadmin_history_last_id_child = $return[0]->historyid;
                                                    }
                                                    //trn_webadmin_history
                                                        
                                                    DB::connection('mysql')->update("UPDATE u".$store.".mst_item SET dcostprice=npack*
                                                        '". ($isParentCheck['nunitcost']) ."',nunitcost='". ($isParentCheck['nunitcost']) ."' WHERE iitemid= '". (int)($chi_item['iitemid']) ."'");
                                                        
                                                    //trn_webadmin_history
                                                    //trn_webadmin_history
                                                    $result = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = 'u".$store."'  AND table_name = 'trn_webadmin_history'");
                                                    $result = isset($result[0])?(array)$result[0]:[];
                                                    if(count($result)){ 
                                                        $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                                        
                                                        unset($new_item_values['itemimage']);
                                                        
                                                        $x_general_child = new \stdClass();
                                                        $x_general_child->new_item_values = $new_item_values;
                                                            
                                                        $x_general_child = addslashes(json_encode($x_general_child));
                                                        try{
                                                            
                                                            DB::connection('mysql')->update("UPDATE u".$store."trn_webadmin_history SET general = '" . $x_general_child . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_child . "'");
                                                        }
                                                        catch (QueryException $e) {
                                                            Log::error($e);
                                                        }
                                                    }
                                                    //trn_webadmin_history
                                                }
                                            }
                                        }
                                            
                                        if(isset($data['update_iitemgroupid']) && $data['update_iitemgroupid'] != 'no-update'){
                                            $delete_ids = DB::connection('mysql')->select("SELECT `Id` FROM u".$store."itemgroupdetail WHERE vsku='" . ($isParentCheck['vbarcode']) . "'");
                                            $delete_ids = isset($delete_ids[0])?(array)$delete_ids[0]:[];
                                            
                                            if(count($delete_ids) > 0){
                                                DB::connection('mysql')->insert("INSERT INTO u".$store."mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)$store."'");
                                            }
            
                                            DB::connection('mysql')->statement("DELETE FROM u".$store."itemgroupdetail WHERE vsku='" . ($isParentCheck['vbarcode']) . "'");
            
                                            DB::connection('mysql')->insert("INSERT INTO u".$store."itemgroupdetail SET  iitemgroupid = '" . (int)($data['update_iitemgroupid']) . "', vsku='". ($isParentCheck['vbarcode']) ."',vtype='Product',SID = '" . (int)$store . "' ");
                                        }
                                            
                                        //update item pack details
                                        if(($isParentCheck['vitemtype']) == 'Lot Matrix'){
                                    
                                            if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                                                $lot_child_items = DB::connection('mysql')->select("SELECT `iitemid` FROM u".$store.".mst_item WHERE parentmasterid= '". (int)$current_item['iitemid'] ."' ");
                                                $lot_child_items = array_map(function ($value) {
                                                    return (array)$value;
                                                }, $lot_child_items);
                                                
                                                if(count($lot_child_items) > 0){
                                                    foreach($lot_child_items as $chi){
                                                        $pack_lot_child_item = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_itempackdetail WHERE iitemid= '". (int)($chi['iitemid']) ."' ");
                                                        $pack_lot_child_item = array_map(function ($value) {
                                                            return (array)$value;
                                                        }, $pack_lot_child_item);
                                                        
                                                        if(count($pack_lot_child_item) > 0){
                                                            foreach ($pack_lot_child_item as $k => $v) {
                                                                $parent_nunitcost = ($isParentCheck['nunitcost']);
                                                                
                                                                if($parent_nunitcost == ''){
                                                                    $parent_nunitcost = 0;
                                                                }
                                                                
                                                                $parent_ipack = (int)$v['ipack'];
                                                                $parent_npackprice = $v['npackprice'];
            
                                                                $parent_npackcost = (int)$parent_ipack * $parent_nunitcost;
                                                                
                                                                $parent_npackmargin = $parent_npackprice - $parent_npackcost;
            
                                                                if($parent_npackprice == 0){
                                                                    $parent_npackprice = 1;
                                                                }
            
                                                                if($parent_npackmargin > 0){
                                                                    $parent_npackmargin = $parent_npackmargin;
                                                                }else{
                                                                    $parent_npackmargin = 0;
                                                                }
            
                                                                $parent_npackmargin = (($parent_npackmargin/$parent_npackprice) * 100);
                                                                $parent_npackmargin = number_format((float)$parent_npackmargin, 2, '.', '');
            
                                                                DB::connection('mysql')->update("UPDATE u".$store.".mst_itempackdetail SET  `npackcost` = '" . $parent_npackcost . "',`nunitcost` = '" . $parent_nunitcost . "',`npackmargin` = '" . $parent_npackmargin . "' WHERE idetid='". (int)($v['idetid']) ."'");
                                                            }
                                                        }
                                                    }
                                                }
                                            }
            
                                            $vpackname = 'Case';
                                            $vdesc = 'Case';
            
                                            $nunitcost = ($isParentCheck['nunitcost']);
                                            if($nunitcost == ''){
                                                $nunitcost = 0;
                                            }
            
                                            $ipack = ($isParentCheck['nsellunit']);
                                            if(($isParentCheck['nsellunit']) == ''){
                                                $ipack = 0;
                                            }
            
                                            $npackprice = ($isParentCheck['dunitprice']);
                                            if(($isParentCheck['dunitprice']) == ''){
                                                $npackprice = 0;
                                            }
            
                                            $npackcost = (int)$ipack * $nunitcost;
                                            $iparentid = 1;
                                            $npackmargin = $npackprice - $npackcost;
            
                                            if($npackprice == 0){
                                                $npackprice = 1;
                                            }
            
                                            if($npackmargin > 0){
                                                $npackmargin = $npackmargin;
                                            }else{
                                                $npackmargin = 0;
                                            }
            
                                            $npackmargin = (($npackmargin/$npackprice) * 100);
                                            $npackmargin = number_format((float)$npackmargin, 2, '.', '');
            
                                            $itempackexist = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_itempackdetail WHERE vbarcode='". ($isParentCheck['vbarcode']) ."' AND iitemid='". (int)$current_item['iitemid'] ."' AND iparentid=1");
            
                                            if(count($itempackexist) > 0){
                                                DB::connection('mysql')->statement("UPDATE u".$store.".mst_itempackdetail SET `ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='". ($isParentCheck['vbarcode']) ."' AND iitemid='". (int)$current_item['iitemid'] ."' AND iparentid=1");
                                            }
                                        }
                                    }
                                }
                                    
                        }            
                    }
                }
                    
                catch (QueryException $e) {
                    // not a MySQL exception   
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                
            }else {
                try {
                    if(count($data['item_ids']) > 0){
                               
                        foreach($data['item_ids'] as $value){
                            $updated_column = array();
                                
                            $current_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$value ."'");
                            $current_item = isset($current_item[0])?(array)$current_item[0]:[];
                            
                            $sql = "";
                            $sql .= "UPDATE mst_item SET";
                            
                            if($data['update_vitemtype'] != 'no-update'){
                                $sql .= " vitemtype='" . ($data['update_vitemtype']) . "',";
                                $updated_column[] = 'vitemtype';
                            }
                                
                            if($data['update_vcategorycode'] != 'no-update'){
                                $sql .= " vcategorycode='" . ($data['update_vcategorycode']) . "',";
                                $updated_column[] = 'vcategorycode';
                            }
                                
                            if($data['update_vunitcode'] != 'no-update'){
                                $sql .= " vunitcode='" . ($data['update_vunitcode']) . "',";
                                $updated_column[] = 'vunitcode';
                            }
                                        
                            if($data['update_vsize'] != 'no-update'){
                                $sql .= " vsize='" . ($data['update_vsize']) . "',";
                                $updated_column[] = 'vsize';
                            }
                                
                            if($data['update_vdepcode'] != 'no-update'){
                                $sql .= " vdepcode='" . ($data['update_vdepcode']) . "',";
                                $updated_column[] = 'vdepcode';
                            }
                                
                            if($data['update_vsuppliercode'] != 'no-update'){
                                $sql .= " vsuppliercode='" . ($data['update_vsuppliercode']) . "',";
                                $updated_column[] = 'vsuppliercode';
                            }
                                
                            if($data['update_vfooditem'] != 'no-update'){
                                $sql .= " vfooditem='" . ($data['update_vfooditem']) . "',";
                                $updated_column[] = 'vfooditem';
                            }
                                
                            if($data['update_vtax1'] != 'no-update'){
                                $sql .= " vtax1='" . ($data['update_vtax1']) . "',";
                                $updated_column[] = 'vtax1';
                            }
                                
                            if($data['update_vtax2'] != 'no-update'){
                                $sql .= " vtax2='" . ($data['update_vtax2']) . "',";
                                $updated_column[] = 'vtax2';
                            }
                                
                            if($data['update_aisleid'] != 'no-update'){
                                $sql .= " aisleid='" . ($data['update_aisleid']) . "',";
                                $updated_column[] = 'aisleid';
                            }
                                
                            if($data['update_shelfid'] != 'no-update'){
                                $sql .= " shelfid='" . ($data['update_shelfid']) . "',";
                                $updated_column[] = 'shelfid';
                            }
                                
                            if($data['update_shelvingid'] != 'no-update'){
                                $sql .= " shelvingid='" . ($data['update_shelvingid']) . "',";
                                $updated_column[] = 'shelvingid';
                            }
                                
                            // if(isset($data['update_dunitprice_checkbox']) && $data['update_dunitprice_checkbox'] == 'Y'){
                            //     $sql .= " dunitprice='0',";
                            //     $updated_column[] = 'dunitprice';
                            // }elseif(isset($data['update_dunitprice']) && $data['update_dunitprice'] != '' && $data['update_dunitprice'] != '0'){
                            //     $sql .= " dunitprice='" . ($data['update_dunitprice']) . "',";
                            //     $updated_column[] = 'dunitprice';
                            // }
                                
                            if(isset($data['update_dunitprice_checkbox']) && $data['update_dunitprice_checkbox'] == 'Y'){
                                $sql .= " dunitprice='0',";
                                $new_dunitprice=0;
                                $updated_column[] = 'dunitprice';
                            }else if(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && !isset($data['update_dunitprice_increment']) && !isset($data['update_dunitprice_increment_percent'])){
                                $sql .= " dunitprice='" . ($data['update_dunitprice']) . "',";
                                $updated_column[] = 'dunitprice';
                                $new_dunitprice=$data['update_dunitprice'];
                            }elseif(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && isset($data['update_dunitprice_increment']) && $data['update_dunitprice_increment'] == 'Y' && !isset($data['update_dunitprice_increment_percent'])){
                                $new_dunitprice = $current_item['dunitprice'] + ($data['update_dunitprice']);
                                $sql .= " dunitprice='" . $new_dunitprice . "',";
                                $updated_column[] = 'dunitprice';
                            }elseif(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && !isset($data['update_dunitprice_increment']) && isset($data['update_dunitprice_increment_percent']) && $data['update_dunitprice_increment_percent'] == 'Y'){

                                $new_dunitprice = (($current_item['dunitprice'] * ($data['update_dunitprice'])) / 100) + ($current_item['dunitprice']);
                                $sql .= " dunitprice='" . $new_dunitprice . "',";
                                $updated_column[] = 'dunitprice';
                                
                            }else{
                                $new_dunitprice= $current_item['dunitprice'];
                            }
                                
                            if(isset($data['update_npack_checkbox']) && $data['update_npack_checkbox'] == 'Y'){
                                $sql .= " npack='1',";
                                $current_npack = 1;
                                $updated_column[] = 'npack';
                            }elseif(isset($data['update_npack']) && $data['update_npack'] != '' && $data['update_npack'] != '1' && $data['update_npack'] != '0'){
                                $sql .= " npack='" . ($data['update_npack']) . "',";
                                $current_npack = $data['update_npack'];
                                $updated_column[] = 'npack'; 
                            }else{
                                $current_npack = $current_item['npack'];
                                if($current_npack == 0){
                                    $current_npack = 1;
                                }
                            }
                            
                            if(isset($data['update_nsellunit_checkbox']) && $data['update_nsellunit_checkbox'] == 'Y'){
                                $sql .= " nsellunit='1',";
                                $updated_column[] = 'nsellunit';
                                $current_nsellunit = 1;
                            }elseif(isset($data['update_nsellunit']) && $data['update_nsellunit'] != '' && $data['update_nsellunit'] != '1' && $data['update_nsellunit'] != '0'){
                                $sql .= " nsellunit='" . ($data['update_nsellunit']) . "',";
                                $updated_column[] = 'nsellunit';
                                $current_nsellunit = $data['update_nsellunit'];
                            }else{
                                $current_nsellunit = $current_item['nsellunit'];
                            }
                                
                            // if(isset($data['update_dcostprice_checkbox']) && $data['update_dcostprice_checkbox'] == 'Y'){
                            //     $sql .= " dcostprice='0',";
                            //     $current_dcostprice = 0;
                            //     $updated_column[] = 'dcostprice';
                            // }elseif(isset($data['update_dcostprice']) && $data['update_dcostprice'] != '' && $data['update_dcostprice'] != '0'){
                            //     $sql .= " dcostprice='" . ($data['update_dcostprice']) . "',";
                            //     $current_dcostprice = $data['update_dcostprice'];
                            //     $updated_column[] = 'dcostprice';
                            // }else{
                            //     $current_dcostprice = $current_item['dcostprice'];
                            // }
                            
                            if(isset($data['update_dcostprice_checkbox']) && $data['update_dcostprice_checkbox'] == 'Y' && $current_item['isparentchild'] !=1){
                                // $sql .= " dcostprice='0',";
                                $current_dcostprice = 0;
                                $new_dcostprice = 0;
                                $updated_column[] = 'dcostprice';
                                $sql .= " dcostprice='0', new_costprice='0',";
        
                            }else if(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && !isset($data['update_dcostprice_increment_percent']) && $current_item['isparentchild'] !=1){
                                // $sql .= " dcostprice='" . ($data['update_dcostprice']) . "',";
                                $sql .= " dcostprice='" . ($data['update_dcostprice']) . "', new_costprice='" . ($data['update_dcostprice']) . "',";
                                $current_dcostprice = $data['update_dcostprice'];
                                $new_dcostprice = $data['update_dcostprice'];
                                $updated_column[] = 'dcostprice';
                            // }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && isset($data['update_dcostprice_increment']) && $data['update_dcostprice_increment'] == 'Y' && !isset($data['update_dcostprice_increment_percent']) && $current_item['isparentchild'] !=1){
                            }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && isset($data['update_dcostprice_increment']) && $data['update_dcostprice_increment'] == 'Y' && !isset($data['update_dcostprice_increment_percent']) ){
                                $new_dcostprice = $current_item['dcostprice'] + ($data['update_dcostprice']);
                                // $sql .= " dcostprice='" . $new_dcostprice . "',";
                                $sql .= " dcostprice='" . $new_dcostprice . "', new_costprice='" . $new_dcostprice . "',";
                                $current_dcostprice = $new_dcostprice;
                                $updated_column[] = 'dcostprice';
                            // }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && isset($data['update_dcostprice_increment_percent']) && $data['update_dcostprice_increment_percent'] == 'Y' && $current_item['isparentchild'] !=1){
                            }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && isset($data['update_dcostprice_increment_percent']) && $data['update_dcostprice_increment_percent'] == 'Y' ){
                                
                                $new_dcostprice = (($current_item['dcostprice'] * ($data['update_dcostprice'])) / 100) + ($current_item['dcostprice']);
                                // $sql .= " dcostprice='" . $new_dcostprice . "',";
                                $sql .= " dcostprice='" . $new_dcostprice . "', new_costprice='" . $new_dcostprice . "',";
                                $current_dcostprice = $new_dcostprice;
                                $updated_column[] = 'dcostprice';
                            
                            //=====commented on 25/06/2020===========
                            }elseif(isset($data['update_dcostprice']) && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_increment']) && !isset($data['update_dcostprice_increment_percent'])){
                                
                                $sql .= " dcostprice='" . ($data['update_dcostprice']) . "', new_costprice='" . ($data['update_dcostprice']) . "',";
                                $current_dcostprice = $data['update_dcostprice'];
                                $new_dcostprice = $data['update_dcostprice'];
                                $updated_column[] = 'dcostprice';
                                
                                $current_nunitcost = $current_dcostprice /  $current_nsellunit;
                                $sql .= " nunitcost='" . $current_nunitcost . "',";
                                
                            }else{
                                $new_dcostprice = $current_item['dcostprice'];
                                $current_dcostprice = $current_item['dcostprice'];
                            }
                            
                            // $current_nunitcost = $current_dcostprice /  $current_npack;
                            // $sql .= " nunitcost='" . $current_nunitcost . "',";
                                
                            if($data['update_visinventory'] != 'no-update'){
                                $sql .= " visinventory='" . ($data['update_visinventory']) . "',";
                                $updated_column[] = 'visinventory';
                            }
                                
                            if($data['update_ndiscountper'] != ''){
                                $sql .= " ndiscountper='" . (float)$data['update_ndiscountper'] . "',";
                                $updated_column[] = 'ndiscountper';
                            }
                                
                            if(isset($data['update_nlevel2']) && $data['update_nlevel2'] != ''){
                                $sql .= " nlevel2='" . ($data['update_nlevel2']) . "',";
                                $updated_column[] = 'nlevel2';
                            }
                                
                            if(isset($data['update_nlevel3']) && $data['update_nlevel3'] != ''){
                                $sql .= " nlevel3='" . ($data['update_nlevel3']) . "',";
                                $updated_column[] = 'nlevel3';
                            }
                                
                            if(isset($data['update_nlevel4']) && $data['update_nlevel4'] != ''){
                                $sql .= " nlevel4='" . ($data['update_nlevel4']) . "',";
                                $updated_column[] = 'nlevel4';
                            }
                                
                            if($data['update_wicitem'] != 'no-update'){
                                $sql .= " wicitem='" . ($data['update_wicitem']) . "',";
                                $updated_column[] = 'wicitem';
                            }
                                
                            if($data['update_stationid'] != 'no-update'){
                                $sql .= " stationid='" . ($data['update_stationid']) . "',";
                                $updated_column[] = 'stationid';
                            }
                                
                            if($data['update_vbarcodetype'] != 'no-update'){
                                $sql .= " vbarcodetype='" . ($data['update_vbarcodetype']) . "',";
                                $updated_column[] = 'vbarcodetype';
                            }
                                
                            if($data['update_vdiscount'] != 'no-update'){
                                $sql .= " vdiscount='" . ($data['update_vdiscount']) . "',";
                                $updated_column[] = 'vdiscount';
                            }

                            if($data['update_liability'] != 'no-update'){
                                $sql .= " liability='" . ($data['update_liability']) . "',";
                                $updated_column[] = 'liability';
                            }
                                
                            if($data['update_ireorderpoint'] != ''){
                                $sql .= " ireorderpoint='" . ($data['update_ireorderpoint']) . "',";
                                $updated_column[] = 'ireorderpoint';
                            }
                                
                            if($data['update_norderqtyupto'] != ''){
                                $sql .= " norderqtyupto='" . ($data['update_norderqtyupto']) . "',";
                                $updated_column[] = 'norderqtyupto';
                            }
                                
                            if($data['update_vintage'] != ''){
                                $sql .= " vintage='" . ($data['update_vintage']) . "',";
                                $updated_column[] = 'vintage';
                            }
                                
                            if($data['update_vshowsalesinzreport'] != 'no-update'){
                                $sql .= " vshowsalesinzreport='" . ($data['update_vshowsalesinzreport']) . "',";
                                $updated_column[] = 'vshowsalesinzreport';
                            }
                                
                            if($data['update_visinventory'] != 'no-update'){
                                $sql .= " visinventory='" . ($data['update_visinventory']) . "',";
                                $updated_column[] = 'visinventory';
                            }
                                
                            if($data['update_vageverify'] != 'no-update'){
                                $sql .= " vageverify='" . ($data['update_vageverify']) . "',";
                                $updated_column[] = 'vageverify';
                            }
                                
                            if($data['update_nbottledepositamt'] != ''){
                                if($data['update_nbottledepositamt'] == '0.00' || $data['update_nbottledepositamt'] == '0' || $data['update_nbottledepositamt'] == '0.0' ){
                                    $sql .= " ebottledeposit='No',";
                                    $sql .= " nbottledepositamt='0.00',";
                                $updated_column[] = 'ebottledeposit';
                                $updated_column[] = 'nbottledepositamt';
                                }else{
                                    $sql .= " ebottledeposit='Yes',";
                                    $sql .= " nbottledepositamt='". ($data['update_nbottledepositamt']) ."',";
                                    $updated_column[] = 'ebottledeposit';
                                    $updated_column[] = 'nbottledepositamt';
                                }
                            }
                                
                            if($data['update_rating'] != ''){
                                $sql .= " rating='" . ($data['update_rating']) . "',";
                                $updated_column[] = 'rating';
                            }
                            
                            // Update subcategory
                            if(isset($data['update_subcat_id']) && $data['update_subcat_id'] != 'no-update'){
                                $sql .= " subcat_id='" . ($data['update_subcat_id']) . "',";
                                $updated_column[] = 'subcategory';
                            }else{
                                if((isset($data['update_vcategorycode']) && $data['update_vcategorycode'] != 'no-update') && (isset($data['update_vdepcode']) && $data['update_vdepcode'] != 'no-update')){
                                    $sql .= " subcat_id='" . null . "',";
                                    $updated_column[] = 'subcategory';
                                }
                            }
                            
                            // Update manufacturer
                            if($data['update_manufacturerid'] != 'no-update'){
                                $sql .= " manufacturer_id='" . ($data['update_manufacturerid']) . "',";
                                $updated_column[] = 'manufcaturer';
                            }
                                
                                
                            if($data['update_estatus'] != 'no-update'){
                                $sql .= " estatus='" . ($data['update_estatus']) . "',";
                                $updated_column[] = 'estatus';
                            }
                                
                            $sql = rtrim($sql,',');
                            $sql .= " WHERE iitemid = '" . (int)$value . "'";
                                
                            DB::connection('mysql_dynamic')->update($sql);
                            
                            // print_r($sql);
                            
                            //mst plcb item
                                
                            if(isset($data['options_data']) && count($data['options_data']) > 0){
                                
                                $mst_item_size = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id= '". (int)$value ."' ");
                                $mst_item_size = isset($mst_item_size[0])?(array)$mst_item_size[0]:[];
                                
                                if(count($mst_item_size) > 0){
                                    
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item_size SET  unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."' WHERE item_id = '" . (int)$value . "'");
                                    
                                }else{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_item_size SET  item_id = '". (int)$value ."',unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."',SID = '" . (int)(session()->get('sid'))."'");
                                }
                                    
                                $mst_plcb_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE item_id= '". (int)$value ."' ");
                                $mst_plcb_item = isset($mst_plcb_item[0])?(array)$mst_plcb_item[0]:[];
                                
                                if(count($mst_plcb_item) > 0){
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_plcb_item SET  bucket_id = '". (int)$data['options_data']['bucket_id'] ."',malt = '". (int)$data['options_data']['malt'] ."' WHERE item_id = '" . (int)$value . "'");
                                }else{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_plcb_item SET  item_id = '". (int)$value ."',bucket_id = '". (int)$data['options_data']['bucket_id'] ."',prev_mo_beg_qty = '". $current_item['iqtyonhand'] ."',prev_mo_end_qty = '". $current_item['iqtyonhand'] ."',malt = '". (int)$data['options_data']['malt'] ."',SID = '" . (int)(session()->get('sid'))."'");
                                }
                            }else{
                                $checkexist_mst_item_size = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id='" . (int)$value . "'");
                                $checkexist_mst_item_size = isset($checkexist_mst_item_size[0])?(array)$checkexist_mst_item_size[0]:[];
                                
                                if(count($checkexist_mst_item_size) > 0){

                                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_item_size',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_item_size['id'] . "',SID = '" . (int)(session()->get('sid'))."'");

                                    DB::connection('mysql_dynamic')->statement("DELETE FROM mst_item_size WHERE id='" . (int)$checkexist_mst_item_size['id'] . "'");

                                }

                                $checkexist_mst_plcb_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE item_id='" . (int)$value . "'");
                                $checkexist_mst_plcb_item = isset($checkexist_mst_plcb_item[0])?(array)$checkexist_mst_plcb_item[0]:[];
                                
                                if(count($checkexist_mst_plcb_item) > 0){

                                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_plcb_item',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_plcb_item['id'] . "',SID = '" . (int)(session()->get('sid'))."'");

                                    DB::connection('mysql_dynamic')->statement("DELETE FROM mst_plcb_item WHERE id='" . (int)$checkexist_mst_plcb_item['id'] . "'");

                                }
                            }
                                
                            //mst plcb item
                                
                            //trn_itempricecosthistory
                            if($current_item['dunitprice'] != $new_dunitprice){
                                
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'EitemPrice', noldamt = '" . ($current_item['dunitprice']) . "', nnewamt = '" . ($new_dunitprice) . "', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                            }
                                
                            if($current_item['dcostprice'] != $new_dcostprice){
                                
                                DB::connection('mysql_dynamic')->statement("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'EitemCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_dcostprice) . "', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                            }
                                
                            //trn_itempricecosthistory
                                
                            //trn_webadmin_history
                            $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                            $result = isset($result[0])?(array)$result[0]:[];
                            if(count($result)){ 
                                if((($current_item['dunitprice'] != $new_dunitprice) && ($current_item['dunitprice'] != '0.00')) && (($current_item['dcostprice'] != $new_dcostprice) && ($current_item['dcostprice'] != '0.0000'))){
                                    $old_item_values = $current_item;
                                    unset($old_item_values['itemimage']);

                                    $x_general = new \stdClass();
                                    $x_general->old_item_values = $old_item_values;
                                    
                                    $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                    
                                    unset($new_item_values['itemimage']);
                                    $x_general->new_item_values = $new_item_values;
                                    $x_general = addslashes(json_encode($x_general));
                                    try{

                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'All', oldamount = '0', newamount = '0', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                    
                                }else{
                                    if(($current_item['dunitprice'] != $new_dunitprice) && ($current_item['dunitprice'] != '0.00')){
                                        $old_item_values = $current_item;
                                        unset($old_item_values['itemimage']);

                                        $x_general = new \stdClass();
                                        $x_general->old_item_values = $old_item_values;
                                        
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        $x_general = addslashes(json_encode($x_general));
                                        try{

                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Price', oldamount = '" . $current_item['dunitprice'] . "', newamount = '". $new_dunitprice ."', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                    }
                                    
                                    if(($current_item['dcostprice'] != $new_dcostprice) && ($current_item['dcostprice'] != '0.0000')){
                                        $old_item_values = $current_item;
                                        unset($old_item_values['itemimage']);
                                        $x_general = new \stdClass();
                                        $x_general->old_item_values = $old_item_values;
                                        
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        $x_general = addslashes(json_encode($x_general));
                                        try{

                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $current_item['dcostprice'] . "', newamount = '". $new_dcostprice ."', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                    }
                                }
                            }
                                
                            //trn_webadmin_history
                                
                            // $user_id = Auth::user()->id;
                            // if(count($updated_column) > 0){
                            //     $object = new stdClass();
                            //     foreach ($updated_column as $k => $u_c){
                            //         $object->$k = $u_c;
                            //     }
                            //     $sql_insert = "";
                            //     $sql_insert .= "INSERT INTO last_modified SET user_id='". (int)$user_id ."', table_name='mst_item', updated_column='". json_encode($object) ."', updated_item='". $value ."', SID='". session()->get('sid') ."'";
                            //     DB::connection('mysql_dynamic')->select($sql_insert);                               
                            // }
                            
                            // update child values
                            $isParentCheck = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$value ."'");
                            $isParentCheck = isset($isParentCheck[0])?(array)$isParentCheck[0]:[];
                                
                            if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                                $child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vbarcode`,`dcostprice`,`npack` FROM mst_item WHERE parentmasterid= '". (int)$value ."' ");
                                $child_items = array_map(function ($value) {
                                    return (array)$value;
                                }, $child_items);
                                
                                if(count($child_items) > 0){
                                    foreach($child_items as $chi_item){

                                        //trn_webadmin_history
                                        //trn_webadmin_history
                                        $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                        $result = isset($result[0])?(array)$result[0]:[];
                                        if(count($result)){ 
                                            $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                            $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
                                            
                                            unset($old_item_values['itemimage']);

                                            $x_general_child = new \stdClass();
                                            $x_general_child->is_child = 'Yes';
                                            $x_general_child->parentmasterid = $old_item_values['parentmasterid'];
                                            $x_general_child->old_item_values = $old_item_values;
                                            try{

                                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . ($chi_item['iitemid']) . "',userid = '" . Auth::user()->id . "',barcode = '" . ($chi_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $chi_item['dcostprice'] . "', newamount = '". (($chi_item['npack']) * (($isParentCheck['nunitcost']))) ."', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                            }
                                            catch (QueryException $e) {
                                                Log::error($e);
                                            }
                                            // $trn_webadmin_history_last_id_child = $this->db2->getLastId();
                                            $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                            
                                            $trn_webadmin_history_last_id_child = $return[0]->historyid;
                                        }
                                        //trn_webadmin_history
                                            
                                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dcostprice=npack*
                                            '". ($isParentCheck['nunitcost']) ."',nunitcost='". ($isParentCheck['nunitcost']) ."' WHERE iitemid= '". (int)($chi_item['iitemid']) ."'");
                                            
                                        //trn_webadmin_history
                                        //trn_webadmin_history
                                        $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                        $result = isset($result[0])?(array)$result[0]:[];
                                        if(count($result)){ 
                                            $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                            $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                            
                                            unset($new_item_values['itemimage']);
                                            
                                            $x_general_child = new \stdClass();
                                            $x_general_child->new_item_values = $new_item_values;
                                                
                                            $x_general_child = addslashes(json_encode($x_general_child));
                                            try{
                                                
                                                DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_child . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_child . "'");
                                            }
                                            catch (QueryException $e) {
                                                Log::error($e);
                                            }
                                        }
                                        //trn_webadmin_history
                                    }
                                }
                            }
                                
                            if(isset($data['update_iitemgroupid']) && $data['update_iitemgroupid'] != 'no-update'){
                                $delete_ids = DB::connection('mysql_dynamic')->select("SELECT `Id` FROM itemgroupdetail WHERE vsku='" . ($isParentCheck['vbarcode']) . "'");
                                $delete_ids = isset($delete_ids[0])?(array)$delete_ids[0]:[];
                                
                                if(count($delete_ids) > 0){
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)(session()->get('sid'))."'");
                                }

                                DB::connection('mysql_dynamic')->statement("DELETE FROM itemgroupdetail WHERE vsku='" . ($isParentCheck['vbarcode']) . "'");

                                DB::connection('mysql_dynamic')->insert("INSERT INTO itemgroupdetail SET  iitemgroupid = '" . (int)($data['update_iitemgroupid']) . "', vsku='". ($isParentCheck['vbarcode']) ."',vtype='Product',SID = '" . (int)(session()->get('sid')) . "' ");
                            }
                                
                            //update item pack details
                            if(($isParentCheck['vitemtype']) == 'Lot Matrix'){
                        
                                if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                                    $lot_child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentmasterid= '". (int)$value ."' ");
                                    $lot_child_items = array_map(function ($value) {
                                        return (array)$value;
                                    }, $lot_child_items);
                                    
                                    if(count($lot_child_items) > 0){
                                        foreach($lot_child_items as $chi){
                                            $pack_lot_child_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid= '". (int)($chi['iitemid']) ."' ");
                                            $pack_lot_child_item = array_map(function ($value) {
                                                return (array)$value;
                                            }, $pack_lot_child_item);
                                            
                                            if(count($pack_lot_child_item) > 0){
                                                foreach ($pack_lot_child_item as $k => $v) {
                                                    $parent_nunitcost = ($isParentCheck['nunitcost']);
                                                    
                                                    if($parent_nunitcost == ''){
                                                        $parent_nunitcost = 0;
                                                    }
                                                    
                                                    $parent_ipack = (int)$v['ipack'];
                                                    $parent_npackprice = $v['npackprice'];

                                                    $parent_npackcost = (int)$parent_ipack * $parent_nunitcost;
                                                    
                                                    $parent_npackmargin = $parent_npackprice - $parent_npackcost;

                                                    if($parent_npackprice == 0){
                                                        $parent_npackprice = 1;
                                                    }

                                                    if($parent_npackmargin > 0){
                                                        $parent_npackmargin = $parent_npackmargin;
                                                    }else{
                                                        $parent_npackmargin = 0;
                                                    }

                                                    $parent_npackmargin = (($parent_npackmargin/$parent_npackprice) * 100);
                                                    $parent_npackmargin = number_format((float)$parent_npackmargin, 2, '.', '');

                                                    DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `npackcost` = '" . $parent_npackcost . "',`nunitcost` = '" . $parent_nunitcost . "',`npackmargin` = '" . $parent_npackmargin . "' WHERE idetid='". (int)($v['idetid']) ."'");
                                                }
                                            }
                                        }
                                    }
                                }

                                $vpackname = 'Case';
                                $vdesc = 'Case';

                                $nunitcost = ($isParentCheck['nunitcost']);
                                if($nunitcost == ''){
                                    $nunitcost = 0;
                                }

                                $ipack = ($isParentCheck['nsellunit']);
                                if(($isParentCheck['nsellunit']) == ''){
                                    $ipack = 0;
                                }

                                $npackprice = ($isParentCheck['dunitprice']);
                                if(($isParentCheck['dunitprice']) == ''){
                                    $npackprice = 0;
                                }

                                $npackcost = (int)$ipack * $nunitcost;
                                $iparentid = 1;
                                $npackmargin = $npackprice - $npackcost;

                                if($npackprice == 0){
                                    $npackprice = 1;
                                }

                                if($npackmargin > 0){
                                    $npackmargin = $npackmargin;
                                }else{
                                    $npackmargin = 0;
                                }

                                $npackmargin = (($npackmargin/$npackprice) * 100);
                                $npackmargin = number_format((float)$npackmargin, 2, '.', '');

                                $itempackexist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE vbarcode='". ($isParentCheck['vbarcode']) ."' AND iitemid='". (int)$value ."' AND iparentid=1");

                                if(count($itempackexist) > 0){
                                    DB::connection('mysql_dynamic')->statement("UPDATE mst_itempackdetail SET `ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='". ($isParentCheck['vbarcode']) ."' AND iitemid='". (int)$value ."' AND iparentid=1");
                                }
                            }
                        } 
                        
                    }
                }
                
                catch (QueryException $e) {
                    // not a MySQL exception
                   
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                
            }        
        }           
                        
        $success['success'] = 'Successfully Updated Item';
        return $success;
    }
    
    // public function editlistItems($data = array()) {
            
    //     $success =array();
    //     $error =array();
            
    //     if(isset($data) && count($data) > 0){
                    
    //         try {
    //                 if(count($data['item_ids']) > 0){
                                
    //                     foreach($data['item_ids'] as $value){
    //                         $updated_column = array();
                                
    //                         $current_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$value ."'");
    //                         $current_item = isset($current_item[0])?(array)$current_item[0]:[];
                            
    //                         $sql = "";
    //                         $sql .= "UPDATE mst_item SET";
                            
    //                         if($data['update_vitemtype'] != 'no-update'){
    //                             $sql .= " vitemtype='" . ($data['update_vitemtype']) . "',";
    //                             $updated_column[] = 'vitemtype';
    //                         }
                                
    //                         if($data['update_vcategorycode'] != 'no-update'){
    //                             $sql .= " vcategorycode='" . ($data['update_vcategorycode']) . "',";
    //                             $updated_column[] = 'vcategorycode';
    //                         }
                                
    //                         if($data['update_vunitcode'] != 'no-update'){
    //                             $sql .= " vunitcode='" . ($data['update_vunitcode']) . "',";
    //                             $updated_column[] = 'vunitcode';
    //                         }
                                        
    //                         if($data['update_vsize'] != 'no-update'){
    //                             $sql .= " vsize='" . ($data['update_vsize']) . "',";
    //                             $updated_column[] = 'vsize';
    //                         }
                                
    //                         if($data['update_vdepcode'] != 'no-update'){
    //                             $sql .= " vdepcode='" . ($data['update_vdepcode']) . "',";
    //                             $updated_column[] = 'vdepcode';
    //                         }
                                
    //                         if($data['update_vsuppliercode'] != 'no-update'){
    //                             $sql .= " vsuppliercode='" . ($data['update_vsuppliercode']) . "',";
    //                             $updated_column[] = 'vsuppliercode';
    //                         }
                                
    //                         if($data['update_vfooditem'] != 'no-update'){
    //                             $sql .= " vfooditem='" . ($data['update_vfooditem']) . "',";
    //                             $updated_column[] = 'vfooditem';
    //                         }
                                
    //                         if($data['update_vtax1'] != 'no-update'){
    //                             $sql .= " vtax1='" . ($data['update_vtax1']) . "',";
    //                             $updated_column[] = 'vtax1';
    //                         }
                                
    //                         if($data['update_vtax2'] != 'no-update'){
    //                             $sql .= " vtax2='" . ($data['update_vtax2']) . "',";
    //                             $updated_column[] = 'vtax2';
    //                         }
                                
    //                         if($data['update_aisleid'] != 'no-update'){
    //                             $sql .= " aisleid='" . ($data['update_aisleid']) . "',";
    //                             $updated_column[] = 'aisleid';
    //                         }
                                
    //                         if($data['update_shelfid'] != 'no-update'){
    //                             $sql .= " shelfid='" . ($data['update_shelfid']) . "',";
    //                             $updated_column[] = 'shelfid';
    //                         }
                                
    //                         if($data['update_shelvingid'] != 'no-update'){
    //                             $sql .= " shelvingid='" . ($data['update_shelvingid']) . "',";
    //                             $updated_column[] = 'shelvingid';
    //                         }
                                
    //                         // if(isset($data['update_dunitprice_checkbox']) && $data['update_dunitprice_checkbox'] == 'Y'){
    //                         //     $sql .= " dunitprice='0',";
    //                         //     $updated_column[] = 'dunitprice';
    //                         // }elseif(isset($data['update_dunitprice']) && $data['update_dunitprice'] != '' && $data['update_dunitprice'] != '0'){
    //                         //     $sql .= " dunitprice='" . ($data['update_dunitprice']) . "',";
    //                         //     $updated_column[] = 'dunitprice';
    //                         // }
                                
    //                         if(isset($data['update_dunitprice_checkbox']) && $data['update_dunitprice_checkbox'] == 'Y'){
    //                             $sql .= " dunitprice='0',";
    //                             $new_dunitprice=0;
    //                             $updated_column[] = 'dunitprice';
    //                         }else if(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && !isset($data['update_dunitprice_increment']) && !isset($data['update_dunitprice_increment_percent'])){
    //                             $sql .= " dunitprice='" . ($data['update_dunitprice']) . "',";
    //                             $updated_column[] = 'dunitprice';
    //                             $new_dunitprice=$data['update_dunitprice'];
    //                         }elseif(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && isset($data['update_dunitprice_increment']) && $data['update_dunitprice_increment'] == 'Y' && !isset($data['update_dunitprice_increment_percent'])){
    //                             $new_dunitprice = $current_item['dunitprice'] + ($data['update_dunitprice']);
    //                             $sql .= " dunitprice='" . $new_dunitprice . "',";
    //                             $updated_column[] = 'dunitprice';
    //                         }elseif(isset($data['update_dunitprice_select']) && $data['update_dunitprice_select'] == 'set as price' && $data['update_dunitprice'] != '0' && !isset($data['update_dunitprice_checkbox']) && !isset($data['update_dunitprice_increment']) && isset($data['update_dunitprice_increment_percent']) && $data['update_dunitprice_increment_percent'] == 'Y'){

    //                             $new_dunitprice = (($current_item['dunitprice'] * ($data['update_dunitprice'])) / 100) + ($current_item['dunitprice']);
    //                             $sql .= " dunitprice='" . $new_dunitprice . "',";
    //                             $updated_column[] = 'dunitprice';
                                
    //                         }else{
    //                             $new_dunitprice= $current_item['dunitprice'];
    //                         }
                                
    //                         if(isset($data['update_npack_checkbox']) && $data['update_npack_checkbox'] == 'Y'){
    //                             $sql .= " npack='1',";
    //                             $current_npack = 1;
    //                             $updated_column[] = 'npack';
    //                         }elseif(isset($data['update_npack']) && $data['update_npack'] != '' && $data['update_npack'] != '1' && $data['update_npack'] != '0'){
    //                             $sql .= " npack='" . ($data['update_npack']) . "',";
    //                             $current_npack = $data['update_npack'];
    //                             $updated_column[] = 'npack'; 
    //                         }else{
    //                             $current_npack = $current_item['npack'];
    //                             if($current_npack == 0){
    //                                 $current_npack = 1;
    //                             }
    //                         }
                            
    //                         if(isset($data['update_nsellunit_checkbox']) && $data['update_nsellunit_checkbox'] == 'Y'){
    //                             $sql .= " nsellunit='1',";
    //                             $updated_column[] = 'nsellunit';
    //                             $current_nsellunit = 1;
    //                         }elseif(isset($data['update_nsellunit']) && $data['update_nsellunit'] != '' && $data['update_nsellunit'] != '1' && $data['update_nsellunit'] != '0'){
    //                             $sql .= " nsellunit='" . ($data['update_nsellunit']) . "',";
    //                             $updated_column[] = 'nsellunit';
    //                             $current_nsellunit = $data['update_nsellunit'];
    //                         }else{
    //                             $current_nsellunit = $current_item['nsellunit'];
    //                         }
                                
    //                         // if(isset($data['update_dcostprice_checkbox']) && $data['update_dcostprice_checkbox'] == 'Y'){
    //                         //     $sql .= " dcostprice='0',";
    //                         //     $current_dcostprice = 0;
    //                         //     $updated_column[] = 'dcostprice';
    //                         // }elseif(isset($data['update_dcostprice']) && $data['update_dcostprice'] != '' && $data['update_dcostprice'] != '0'){
    //                         //     $sql .= " dcostprice='" . ($data['update_dcostprice']) . "',";
    //                         //     $current_dcostprice = $data['update_dcostprice'];
    //                         //     $updated_column[] = 'dcostprice';
    //                         // }else{
    //                         //     $current_dcostprice = $current_item['dcostprice'];
    //                         // }
                            
    //                         if(isset($data['update_dcostprice_checkbox']) && $data['update_dcostprice_checkbox'] == 'Y' && $current_item['isparentchild'] !=1){
    //                             // $sql .= " dcostprice='0',";
    //                             $current_dcostprice = 0;
    //                             $new_dcostprice = 0;
    //                             $updated_column[] = 'dcostprice';
    //                             $sql .= " dcostprice='0', new_costprice='0',";
        
    //                         }else if(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && !isset($data['update_dcostprice_increment_percent']) && $current_item['isparentchild'] !=1){
    //                             // $sql .= " dcostprice='" . ($data['update_dcostprice']) . "',";
    //                             $sql .= " dcostprice='" . ($data['update_dcostprice']) . "', new_costprice='" . ($data['update_dcostprice']) . "',";
    //                             $current_dcostprice = $data['update_dcostprice'];
    //                             $new_dcostprice = $data['update_dcostprice'];
    //                             $updated_column[] = 'dcostprice';
    //                         // }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && isset($data['update_dcostprice_increment']) && $data['update_dcostprice_increment'] == 'Y' && !isset($data['update_dcostprice_increment_percent']) && $current_item['isparentchild'] !=1){
    //                         }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && isset($data['update_dcostprice_increment']) && $data['update_dcostprice_increment'] == 'Y' && !isset($data['update_dcostprice_increment_percent']) ){
    //                             $new_dcostprice = $current_item['dcostprice'] + ($data['update_dcostprice']);
    //                             // $sql .= " dcostprice='" . $new_dcostprice . "',";
    //                             $sql .= " dcostprice='" . $new_dcostprice . "', new_costprice='" . $new_dcostprice . "',";
    //                             $current_dcostprice = $new_dcostprice;
    //                             $updated_column[] = 'dcostprice';
    //                         // }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && isset($data['update_dcostprice_increment_percent']) && $data['update_dcostprice_increment_percent'] == 'Y' && $current_item['isparentchild'] !=1){
    //                         }elseif(isset($data['update_dcostprice_select']) && $data['update_dcostprice_select'] == 'set as cost' && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_checkbox']) && !isset($data['update_dcostprice_increment']) && isset($data['update_dcostprice_increment_percent']) && $data['update_dcostprice_increment_percent'] == 'Y' ){
                                
    //                             $new_dcostprice = (($current_item['dcostprice'] * ($data['update_dcostprice'])) / 100) + ($current_item['dcostprice']);
    //                             // $sql .= " dcostprice='" . $new_dcostprice . "',";
    //                             $sql .= " dcostprice='" . $new_dcostprice . "', new_costprice='" . $new_dcostprice . "',";
    //                             $current_dcostprice = $new_dcostprice;
    //                             $updated_column[] = 'dcostprice';
                            
    //                         //=====commented on 25/06/2020===========
    //                         }elseif(isset($data['update_dcostprice']) && $data['update_dcostprice'] != '0' && !isset($data['update_dcostprice_increment']) && !isset($data['update_dcostprice_increment_percent'])){
                                
    //                             $sql .= " dcostprice='" . ($data['update_dcostprice']) . "', new_costprice='" . ($data['update_dcostprice']) . "',";
    //                             $current_dcostprice = $data['update_dcostprice'];
    //                             $new_dcostprice = $data['update_dcostprice'];
    //                             $updated_column[] = 'dcostprice';
                                
    //                             $current_nunitcost = $current_dcostprice /  $current_nsellunit;
    //                             $sql .= " nunitcost='" . $current_nunitcost . "',";
                                
    //                         }else{
    //                             $new_dcostprice = $current_item['dcostprice'];
    //                             $current_dcostprice = $current_item['dcostprice'];
    //                         }
                            
    //                         // $current_nunitcost = $current_dcostprice /  $current_npack;
    //                         // $sql .= " nunitcost='" . $current_nunitcost . "',";
                                
    //                         if($data['update_visinventory'] != 'no-update'){
    //                             $sql .= " visinventory='" . ($data['update_visinventory']) . "',";
    //                             $updated_column[] = 'visinventory';
    //                         }
                                
    //                         if($data['update_ndiscountper'] != ''){
    //                             $sql .= " ndiscountper='" . ($data['update_ndiscountper']) . "',";
    //                             $updated_column[] = 'ndiscountper';
    //                         }
                                
    //                         if(isset($data['update_nlevel2']) && $data['update_nlevel2'] != ''){
    //                             $sql .= " nlevel2='" . ($data['update_nlevel2']) . "',";
    //                             $updated_column[] = 'nlevel2';
    //                         }
                                
    //                         if(isset($data['update_nlevel3']) && $data['update_nlevel3'] != ''){
    //                             $sql .= " nlevel3='" . ($data['update_nlevel3']) . "',";
    //                             $updated_column[] = 'nlevel3';
    //                         }
                                
    //                         if(isset($data['update_nlevel4']) && $data['update_nlevel4'] != ''){
    //                             $sql .= " nlevel4='" . ($data['update_nlevel4']) . "',";
    //                             $updated_column[] = 'nlevel4';
    //                         }
                                
    //                         if($data['update_wicitem'] != 'no-update'){
    //                             $sql .= " wicitem='" . ($data['update_wicitem']) . "',";
    //                             $updated_column[] = 'wicitem';
    //                         }
                                
    //                         if($data['update_stationid'] != 'no-update'){
    //                             $sql .= " stationid='" . ($data['update_stationid']) . "',";
    //                             $updated_column[] = 'stationid';
    //                         }
                                
    //                         if($data['update_vbarcodetype'] != 'no-update'){
    //                             $sql .= " vbarcodetype='" . ($data['update_vbarcodetype']) . "',";
    //                             $updated_column[] = 'vbarcodetype';
    //                         }
                                
    //                         if($data['update_vdiscount'] != 'no-update'){
    //                             $sql .= " vdiscount='" . ($data['update_vdiscount']) . "',";
    //                             $updated_column[] = 'vdiscount';
    //                         }

    //                         if($data['update_liability'] != 'no-update'){
    //                             $sql .= " liability='" . ($data['update_liability']) . "',";
    //                             $updated_column[] = 'liability';
    //                         }
                                
    //                         if($data['update_ireorderpoint'] != ''){
    //                             $sql .= " ireorderpoint='" . ($data['update_ireorderpoint']) . "',";
    //                             $updated_column[] = 'ireorderpoint';
    //                         }
                                
    //                         if($data['update_norderqtyupto'] != ''){
    //                             $sql .= " norderqtyupto='" . ($data['update_norderqtyupto']) . "',";
    //                             $updated_column[] = 'norderqtyupto';
    //                         }
                                
    //                         if($data['update_vintage'] != ''){
    //                             $sql .= " vintage='" . ($data['update_vintage']) . "',";
    //                             $updated_column[] = 'vintage';
    //                         }
                                
    //                         if($data['update_vshowsalesinzreport'] != 'no-update'){
    //                             $sql .= " vshowsalesinzreport='" . ($data['update_vshowsalesinzreport']) . "',";
    //                             $updated_column[] = 'vshowsalesinzreport';
    //                         }
                                
    //                         if($data['update_visinventory'] != 'no-update'){
    //                             $sql .= " visinventory='" . ($data['update_visinventory']) . "',";
    //                             $updated_column[] = 'visinventory';
    //                         }
                                
    //                         if($data['update_vageverify'] != 'no-update'){
    //                             $sql .= " vageverify='" . ($data['update_vageverify']) . "',";
    //                             $updated_column[] = 'vageverify';
    //                         }
                                
    //                         if($data['update_nbottledepositamt'] != ''){
    //                             if($data['update_nbottledepositamt'] == '0.00' || $data['update_nbottledepositamt'] == '0' || $data['update_nbottledepositamt'] == '0.0' ){
    //                                 $sql .= " ebottledeposit='No',";
    //                                 $sql .= " nbottledepositamt='0.00',";
    //                             $updated_column[] = 'ebottledeposit';
    //                             $updated_column[] = 'nbottledepositamt';
    //                             }else{
    //                                 $sql .= " ebottledeposit='Yes',";
    //                                 $sql .= " nbottledepositamt='". ($data['update_nbottledepositamt']) ."',";
    //                                 $updated_column[] = 'ebottledeposit';
    //                                 $updated_column[] = 'nbottledepositamt';
    //                             }
    //                         }
                                
    //                         if($data['update_rating'] != ''){
    //                             $sql .= " rating='" . ($data['update_rating']) . "',";
    //                             $updated_column[] = 'rating';
    //                         }
                            
    //                         // Update subcategory
    //                         if(isset($data['update_subcat_id']) && $data['update_subcat_id'] != 'no-update'){
    //                             $sql .= " subcat_id='" . ($data['update_subcat_id']) . "',";
    //                             $updated_column[] = 'subcategory';
    //                         }else{
    //                             if((isset($data['update_vcategorycode']) && $data['update_vcategorycode'] != 'no-update') && (isset($data['update_vdepcode']) && $data['update_vdepcode'] != 'no-update')){
    //                                 $sql .= " subcat_id='" . null . "',";
    //                                 $updated_column[] = 'subcategory';
    //                             }
    //                         }
                            
    //                         // Update manufacturer
    //                         if($data['update_manufacturerid'] != 'no-update'){
    //                             $sql .= " manufacturer_id='" . ($data['update_manufacturerid']) . "',";
    //                             $updated_column[] = 'manufcaturer';
    //                         }
                                
                                
    //                         if($data['update_estatus'] != 'no-update'){
    //                             $sql .= " estatus='" . ($data['update_estatus']) . "',";
    //                             $updated_column[] = 'estatus';
    //                         }
                                
    //                         $sql = rtrim($sql,',');
    //                         $sql .= " WHERE iitemid = '" . (int)$value . "'";
                                
    //                         DB::connection('mysql_dynamic')->update($sql);
                            
    //                         // print_r($sql);
                            
    //                         //mst plcb item
                                
    //                         if(isset($data['options_data']) && count($data['options_data']) > 0){
                                
    //                             $mst_item_size = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id= '". (int)$value ."' ");
    //                             $mst_item_size = isset($mst_item_size[0])?(array)$mst_item_size[0]:[];
                                
    //                             if(count($mst_item_size) > 0){
                                    
    //                                 DB::connection('mysql_dynamic')->update("UPDATE mst_item_size SET  unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."' WHERE item_id = '" . (int)$value . "'");
                                    
    //                             }else{
    //                                 DB::connection('mysql_dynamic')->insert("INSERT INTO mst_item_size SET  item_id = '". (int)$value ."',unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."',SID = '" . (int)(session()->get('sid'))."'");
    //                             }
                                    
    //                             $mst_plcb_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE item_id= '". (int)$value ."' ");
    //                             $mst_plcb_item = isset($mst_plcb_item[0])?(array)$mst_plcb_item[0]:[];
                                
    //                             if(count($mst_plcb_item) > 0){
    //                                 DB::connection('mysql_dynamic')->update("UPDATE mst_plcb_item SET  bucket_id = '". (int)$data['options_data']['bucket_id'] ."',malt = '". (int)$data['options_data']['malt'] ."' WHERE item_id = '" . (int)$value . "'");
    //                             }else{
    //                                 DB::connection('mysql_dynamic')->insert("INSERT INTO mst_plcb_item SET  item_id = '". (int)$value ."',bucket_id = '". (int)$data['options_data']['bucket_id'] ."',prev_mo_beg_qty = '". $current_item['iqtyonhand'] ."',prev_mo_end_qty = '". $current_item['iqtyonhand'] ."',malt = '". (int)$data['options_data']['malt'] ."',SID = '" . (int)(session()->get('sid'))."'");
    //                             }
    //                         }else{
    //                             $checkexist_mst_item_size = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id='" . (int)$value . "'");
    //                             $checkexist_mst_item_size = isset($checkexist_mst_item_size[0])?(array)$checkexist_mst_item_size[0]:[];
                                
    //                             if(count($checkexist_mst_item_size) > 0){

    //                                 DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_item_size',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_item_size['id'] . "',SID = '" . (int)(session()->get('sid'))."'");

    //                                 DB::connection('mysql_dynamic')->select("DELETE FROM mst_item_size WHERE id='" . (int)$checkexist_mst_item_size['id'] . "'");

    //                             }

    //                             $checkexist_mst_plcb_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE item_id='" . (int)$value . "'");
    //                             $checkexist_mst_plcb_item = isset($checkexist_mst_plcb_item[0])?(array)$checkexist_mst_plcb_item[0]:[];
                                
    //                             if(count($checkexist_mst_plcb_item) > 0){

    //                                 DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_plcb_item',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_plcb_item['id'] . "',SID = '" . (int)(session()->get('sid'))."'");

    //                                 DB::connection('mysql_dynamic')->select("DELETE FROM mst_plcb_item WHERE id='" . (int)$checkexist_mst_plcb_item['id'] . "'");

    //                             }
    //                         }
                                
    //                         //mst plcb item
                                
    //                         //trn_itempricecosthistory
    //                         if($current_item['dunitprice'] != $new_dunitprice){
                                
    //                             DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'EitemPrice', noldamt = '" . ($current_item['dunitprice']) . "', nnewamt = '" . ($new_dunitprice) . "', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
    //                         }
                                
    //                         if($current_item['dcostprice'] != $new_dcostprice){
                                
    //                             DB::connection('mysql_dynamic')->select("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $current_item['iitemid'] . "',vbarcode = '" . ($current_item['vbarcode']) . "', vtype = 'EitemCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_dcostprice) . "', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
    //                         }
                                
    //                         //trn_itempricecosthistory
                                
    //                         //trn_webadmin_history
    //                         $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
    //                         $result = isset($result[0])?(array)$result[0]:[];
    //                         if(count($result)){ 
    //                             if((($current_item['dunitprice'] != $new_dunitprice) && ($current_item['dunitprice'] != '0.00')) && (($current_item['dcostprice'] != $new_dcostprice) && ($current_item['dcostprice'] != '0.0000'))){
    //                                 $old_item_values = $current_item;
    //                                 unset($old_item_values['itemimage']);

    //                                 $x_general = new \stdClass();
    //                                 $x_general->old_item_values = $old_item_values;
                                    
    //                                 $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
    //                                 $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                    
    //                                 unset($new_item_values['itemimage']);
    //                                 $x_general->new_item_values = $new_item_values;
    //                                 $x_general = addslashes(json_encode($x_general));
    //                                 try{

    //                                 DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'All', oldamount = '0', newamount = '0', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
    //                                 }
    //                                 catch (QueryException $e) {
    //                                     Log::error($e);
    //                                 }
                                    
    //                             }else{
    //                                 if(($current_item['dunitprice'] != $new_dunitprice) && ($current_item['dunitprice'] != '0.00')){
    //                                     $old_item_values = $current_item;
    //                                     unset($old_item_values['itemimage']);

    //                                     $x_general = new \stdClass();
    //                                     $x_general->old_item_values = $old_item_values;
                                        
    //                                     $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
    //                                     $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
    //                                     unset($new_item_values['itemimage']);
    //                                     $x_general->new_item_values = $new_item_values;
    //                                     $x_general = addslashes(json_encode($x_general));
    //                                     try{

    //                                         DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Price', oldamount = '" . $current_item['dunitprice'] . "', newamount = '". $new_dunitprice ."', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
    //                                     }
    //                                     catch (QueryException $e) {
    //                                         Log::error($e);
    //                                     }
    //                                 }
                                    
    //                                 if(($current_item['dcostprice'] != $new_dcostprice) && ($current_item['dcostprice'] != '0.0000')){
    //                                     $old_item_values = $current_item;
    //                                     unset($old_item_values['itemimage']);
    //                                     $x_general = new \stdClass();
    //                                     $x_general->old_item_values = $old_item_values;
                                        
    //                                     $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($current_item['iitemid']) ."' ");
    //                                     $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
    //                                     unset($new_item_values['itemimage']);
    //                                     $x_general->new_item_values = $new_item_values;
    //                                     $x_general = addslashes(json_encode($x_general));
    //                                     try{

    //                                         DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $current_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $current_item['dcostprice'] . "', newamount = '". $new_dcostprice ."', general = '" . $x_general . "', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
    //                                     }
    //                                     catch (QueryException $e) {
    //                                         Log::error($e);
    //                                     }
    //                                 }
    //                             }
    //                         }
                                
    //                         //trn_webadmin_history
                                
    //                         // $user_id = Auth::user()->id;
    //                         // if(count($updated_column) > 0){
    //                         //     $object = new stdClass();
    //                         //     foreach ($updated_column as $k => $u_c){
    //                         //         $object->$k = $u_c;
    //                         //     }
    //                         //     $sql_insert = "";
    //                         //     $sql_insert .= "INSERT INTO last_modified SET user_id='". (int)$user_id ."', table_name='mst_item', updated_column='". json_encode($object) ."', updated_item='". $value ."', SID='". session()->get('sid') ."'";
    //                         //     DB::connection('mysql_dynamic')->select($sql_insert);                               
    //                         // }
                            
    //                         // update child values
    //                         $isParentCheck = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$value ."'");
    //                         $isParentCheck = isset($isParentCheck[0])?(array)$isParentCheck[0]:[];
                                
    //                         if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
    //                             $child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vbarcode`,`dcostprice`,`npack` FROM mst_item WHERE parentmasterid= '". (int)$value ."' ");
    //                             $child_items = array_map(function ($value) {
    //                                 return (array)$value;
    //                             }, $child_items);
                                
    //                             if(count($child_items) > 0){
    //                                 foreach($child_items as $chi_item){

    //                                     //trn_webadmin_history
    //                                     //trn_webadmin_history
    //                                     $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
    //                                     $result = isset($result[0])?(array)$result[0]:[];
    //                                     if(count($result)){ 
    //                                         $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
    //                                         $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
                                            
    //                                         unset($old_item_values['itemimage']);

    //                                         $x_general_child = new \stdClass();
    //                                         $x_general_child->is_child = 'Yes';
    //                                         $x_general_child->parentmasterid = $old_item_values['parentmasterid'];
    //                                         $x_general_child->old_item_values = $old_item_values;
    //                                         try{

    //                                             DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . ($chi_item['iitemid']) . "',userid = '" . Auth::user()->id . "',barcode = '" . ($chi_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $chi_item['dcostprice'] . "', newamount = '". (($chi_item['npack']) * (($isParentCheck['nunitcost']))) ."', source = 'EditMultipleItem', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
    //                                         }
    //                                         catch (QueryException $e) {
    //                                             Log::error($e);
    //                                         }
    //                                         // $trn_webadmin_history_last_id_child = $this->db2->getLastId();
    //                                         $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                            
    //                                         $trn_webadmin_history_last_id_child = $return[0]->historyid;
    //                                     }
    //                                     //trn_webadmin_history
                                            
    //                                     DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dcostprice=npack*
    //                                         '". ($isParentCheck['nunitcost']) ."',nunitcost='". ($isParentCheck['nunitcost']) ."' WHERE iitemid= '". (int)($chi_item['iitemid']) ."'");
                                            
    //                                     //trn_webadmin_history
    //                                     //trn_webadmin_history
    //                                     $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
    //                                     $result = isset($result[0])?(array)$result[0]:[];
    //                                     if(count($result)){ 
    //                                         $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
    //                                         $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                            
    //                                         unset($new_item_values['itemimage']);
                                            
    //                                         $x_general_child = new \stdClass();
    //                                         $x_general_child->new_item_values = $new_item_values;
                                                
    //                                         $x_general_child = addslashes(json_encode($x_general_child));
    //                                         try{
                                                
    //                                             DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_child . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_child . "'");
    //                                         }
    //                                         catch (QueryException $e) {
    //                                             Log::error($e);
    //                                         }
    //                                     }
    //                                     //trn_webadmin_history
    //                                 }
    //                             }
    //                         }
                                
    //                         if(isset($data['update_iitemgroupid']) && $data['update_iitemgroupid'] != 'no-update'){
    //                             $delete_ids = DB::connection('mysql_dynamic')->select("SELECT `Id` FROM itemgroupdetail WHERE vsku='" . ($isParentCheck['vbarcode']) . "'");
    //                             $delete_ids = isset($delete_ids[0])?(array)$delete_ids[0]:[];
                                
    //                             if(count($delete_ids) > 0){
    //                                 DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)(session()->get('sid'))."'");
    //                             }

    //                             DB::connection('mysql_dynamic')->select("DELETE FROM itemgroupdetail WHERE vsku='" . ($isParentCheck['vbarcode']) . "'");

    //                             DB::connection('mysql_dynamic')->insert("INSERT INTO itemgroupdetail SET  iitemgroupid = '" . (int)($data['update_iitemgroupid']) . "', vsku='". ($isParentCheck['vbarcode']) ."',vtype='Product',SID = '" . (int)(session()->get('sid')) . "' ");
    //                         }
                                
    //                         //update item pack details
    //                         if(($isParentCheck['vitemtype']) == 'Lot Matrix'){
                        
    //                             if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
    //                                 $lot_child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentmasterid= '". (int)$value ."' ");
    //                                 $lot_child_items = array_map(function ($value) {
    //                                     return (array)$value;
    //                                 }, $lot_child_items);
                                    
    //                                 if(count($lot_child_items) > 0){
    //                                     foreach($lot_child_items as $chi){
    //                                         $pack_lot_child_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid= '". (int)($chi['iitemid']) ."' ");
    //                                         $pack_lot_child_item = array_map(function ($value) {
    //                                             return (array)$value;
    //                                         }, $pack_lot_child_item);
                                            
    //                                         if(count($pack_lot_child_item) > 0){
    //                                             foreach ($pack_lot_child_item as $k => $v) {
    //                                                 $parent_nunitcost = ($isParentCheck['nunitcost']);
                                                    
    //                                                 if($parent_nunitcost == ''){
    //                                                     $parent_nunitcost = 0;
    //                                                 }
                                                    
    //                                                 $parent_ipack = (int)$v['ipack'];
    //                                                 $parent_npackprice = $v['npackprice'];

    //                                                 $parent_npackcost = (int)$parent_ipack * $parent_nunitcost;
                                                    
    //                                                 $parent_npackmargin = $parent_npackprice - $parent_npackcost;

    //                                                 if($parent_npackprice == 0){
    //                                                     $parent_npackprice = 1;
    //                                                 }

    //                                                 if($parent_npackmargin > 0){
    //                                                     $parent_npackmargin = $parent_npackmargin;
    //                                                 }else{
    //                                                     $parent_npackmargin = 0;
    //                                                 }

    //                                                 $parent_npackmargin = (($parent_npackmargin/$parent_npackprice) * 100);
    //                                                 $parent_npackmargin = number_format((float)$parent_npackmargin, 2, '.', '');

    //                                                 DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `npackcost` = '" . $parent_npackcost . "',`nunitcost` = '" . $parent_nunitcost . "',`npackmargin` = '" . $parent_npackmargin . "' WHERE idetid='". (int)($v['idetid']) ."'");
    //                                             }
    //                                         }
    //                                     }
    //                                 }
    //                             }

    //                             $vpackname = 'Case';
    //                             $vdesc = 'Case';

    //                             $nunitcost = ($isParentCheck['nunitcost']);
    //                             if($nunitcost == ''){
    //                                 $nunitcost = 0;
    //                             }

    //                             $ipack = ($isParentCheck['nsellunit']);
    //                             if(($isParentCheck['nsellunit']) == ''){
    //                                 $ipack = 0;
    //                             }

    //                             $npackprice = ($isParentCheck['dunitprice']);
    //                             if(($isParentCheck['dunitprice']) == ''){
    //                                 $npackprice = 0;
    //                             }

    //                             $npackcost = (int)$ipack * $nunitcost;
    //                             $iparentid = 1;
    //                             $npackmargin = $npackprice - $npackcost;

    //                             if($npackprice == 0){
    //                                 $npackprice = 1;
    //                             }

    //                             if($npackmargin > 0){
    //                                 $npackmargin = $npackmargin;
    //                             }else{
    //                                 $npackmargin = 0;
    //                             }

    //                             $npackmargin = (($npackmargin/$npackprice) * 100);
    //                             $npackmargin = number_format((float)$npackmargin, 2, '.', '');

    //                             $itempackexist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE vbarcode='". ($isParentCheck['vbarcode']) ."' AND iitemid='". (int)$value ."' AND iparentid=1");

    //                             if(count($itempackexist) > 0){
    //                                 DB::connection('mysql_dynamic')->select("UPDATE mst_itempackdetail SET `ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='". ($isParentCheck['vbarcode']) ."' AND iitemid='". (int)$value ."' AND iparentid=1");
    //                             }
    //                         }
    //                     }
                            
    //                 }
    //             }
                
    //         catch (QueryException $e) {
    //                 // not a MySQL exception
                   
    //                 $error['error'] = $e->getMessage(); 
    //                 return $error; 
    //             }
                    
    //     }           
                        
    //     $success['success'] = 'Successfully Updated Item';
    //     return $success;
    // }
    
}

?>