<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Item;

class Promotion extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'trn_promotions';
    protected $primaryKey = 'prom_id';
    public $timestamps = false;
    

    function getActivePromotionTypes() {
        
        $select_query = "SELECT * FROM mst_prom_type where is_active=1";
        $query = \DB::connection('mysql_dynamic')->select($select_query);
        return $query;
    }
    
    // function getSelectedItems($items) {
    //     if(empty($items)) { return array();}
    //     $query = \DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice,mi.* FROM mst_item as mi WHERE mi.iitemid IN (".implode(',', $items).") AND mi.estatus='Active'");
    //     return $query;
    // }
     function getSelectedItems_refresh($items) {
        if(empty($items)) { return array();}
        
         $query = \DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice,mi.* FROM mst_item as mi WHERE mi.iitemid IN (".$items[0]['iitemid'].") AND mi.estatus='Active'");
        return $query;
     
        // $query = \DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice,mi.* FROM mst_item as mi WHERE mi.iitemid IN (".implode(',', $items).") AND mi.estatus='Active'");
        // return $query;
    }
    function getSelectedItems($items) {
        if(empty($items)) { return array();}
        
        if(isset($items[0]['iitemid'])){
         $query = \DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice,mi.* FROM mst_item as mi WHERE mi.iitemid IN (".$items[0]['iitemid'].") AND mi.estatus='Active'");
        return $query;
        }
        else{
        $query = \DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice,mi.* FROM mst_item as mi WHERE mi.iitemid IN (".implode(',', $items).") AND mi.estatus='Active'");
        return $query;
        }
        
    }
    

    function getItems($search_item) {
        $select_query = "SELECT mi.ireorderpoint,mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.nunitcost,mi.dunitprice,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE (mi.vitemname LIKE  '%" .($search_item). "%' OR mi.vbarcode LIKE  '%" .($search_item). "%' OR mi.vsize LIKE  '%" .($search_item).  "%' OR mia.valiassku LIKE  '%" .($search_item). "%' OR mi.dcostprice LIKE  '%" .($search_item). "%' OR mi.dunitprice LIKE  '%" .($search_item). "%') AND mi.estatus='Active' LIMIT 100";
        dd($select_query);
        $query = \DB::connection('mysql_dynamic')->select($select_query);
        return $query;

    }
    
    function getDepartmentItems($dept_code) {
        $select_query = "SELECT mi.ireorderpoint, mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE mi.vdepcode IN (".implode(',', $dept_code).") AND mi.estatus='Active'";
        $query = \DB::connection('mysql_dynamic')->select($select_query);
        return $query->rows;
    }
    function getCategoryItems($dept_code,$cat_code) {
        $select_query = "SELECT mi.ireorderpoint,mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE mi.vdepcode IN (".implode(',', $dept_code).") AND mi.vcategorycode IN (".implode(',', $cat_code).") AND mi.estatus='Active'";
        $query = \DB::connection('mysql_dynamic')->select($select_query);
        return $query->rows;
    }
    
    function getSubCategoryItems($dept_code,$cat_code,$subcat_id) {
        $select_query = "SELECT mi.ireorderpoint,mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE mi.vdepcode IN (".implode(',', $dept_code).") AND mi.vcategorycode IN (".implode(',', $cat_code).") AND mi.subcat_id IN (".implode(',', $subcat_id).") AND mi.estatus='Active'";
        $query = \DB::connection('mysql_dynamic')->select($select_query);
        return $query->rows;
    }

    function getGroupItems($group_id) {
        if(empty($group_id))
        {
            return array();
        }
        $query = $this->db2->query("SELECT mi.ireorderpoint,mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode,mi.vsize,mi.iqtyonhand,mi.dcostprice,mi.dunitprice,mi.npack,CASE WHEN mi.npack = 1 or (mi.npack is null)   then mi.iqtyonhand else (Concat(cast(((mi.iqtyonhand div mi.npack )) as signed), '  (', Mod(mi.iqtyonhand,mi.npack) ,')') ) end as QOH FROM mst_item as mi  LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) WHERE mi.vbarcode IN (SELECT vsku FROM itemgroupdetail WHERE iitemgroupid IN (".implode(',', $group_id).")) AND mi.estatus='Active'");
        return $query->rows;
    }
    
    public function getCategoriesByDepartment($dep_code) {
        try{
           
            // $query = $this->db2->query("SELECT * FROM mst_category where dept_code IN (".implode(',', $dep_code).") ORDER BY vcategoryname")->rows;
            // return $query;

            
            $select_query="SELECT * FROM mst_category where dept_code IN (".implode(',', $dep_code).") ORDER BY vcategoryname";

            $query = \DB::connection('mysql_dynamic')->select($select_query);
            return $query->rows;

        }
        catch(Exception $e)
        {
            return array();
        }
        
    }
    
    public function getSubCategories($cat_id) {
        try{
            // echo "SELECT * FROM mst_subcategory where cat_id = $cat_id ORDER BY subcat_name";exit;
           
            $query = $this->db2->query("SELECT * FROM mst_subcategory where cat_id = $cat_id ORDER BY subcat_name")->rows;
            return $query;
        }
        catch(Exception $e)
        {
            return array();
        }
        
    }
    
    public function getSubCategoriesByCatCode($cat_code) {
        try{
            // echo "SELECT * FROM mst_subcategory where cat_id IN (SELECT icategoryid FROM mst_category where vcategorycode IN (".implode(',', $cat_code).")) ORDER BY subcat_name";exit;
            $query = $this->db2->query("SELECT * FROM mst_subcategory where cat_id IN (SELECT icategoryid FROM mst_category where vcategorycode IN (".implode(',', $cat_code).")) ORDER BY subcat_name")->rows;
            return $query;
        }
        catch(Exception $e)
        {
            return array();
        }
        
    }
    
    public function getPromotionCode($prom_code) {
        $query = \DB::connection('mysql_dynamic')->select("SELECT * FROM trn_promotions WHERE prom_code = '".$prom_code."'");
        return $query;
    }

    public function addPromotion ($data) 
    {
        
        //    echo "<pre>";print_r($data);echo "<pre>";die;
            $query = "";
            if($data['promotion_category'] == "Open Ended" || $data['promotion_category'] == "Stock Bound")
            {
                $from_date = date("Y-m-d");
                $to_date = NULL;
                
            }
            elseif($data['promotion_category'] == "Time Bound")
            {
                $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_from_date']))) ;
                $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_to_date']))) ;
                $query .= ", end_date = '$to_date' ";
            }
            else
            {
                $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_from_date']))) ;
                $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_to_date']))) ;
                $query .= ", end_date = '$to_date' ";
            }
            
            
            $timeQuery = "";
            if($data['promotion_category'] == "Time Bound")
            {
                $timeQuery .= ", from_time = '".date("H:i:s",strtotime($data['promotion_from_time']))."' ";
                $timeQuery .= ", to_time = '".date("H:i:s",strtotime($data['promotion_to_time']))."' ";
            }
           
            // $this->load->model('administration/items');
            
            if($data['promotion_type'] == 10){
                $discount_type_id = 2;
                $promotion_addl_discount = isset($data['promotion_addl_discount'])?($data['promotion_addl_discount']):($data['promotion_slab_price']);
            } else {
                $discount_type_id = ($data['promotion_discount_type']);
                $promotion_addl_discount = isset($data['promotion_addl_discount'])?($data['promotion_addl_discount']):0;
            }
            
            $same_group = $data['promotion_type'] == 12?"Group Item":($data['promotion_same_itme']);
            
            $qty_limit = ($data['qty_limit']);
            
            $qty_limit = (!is_nan($qty_limit) && ($qty_limit > 0))?$qty_limit:0;
    
            $allow_reg_price = $qty_limit > 0?($data['allow_reg_price']):'Y';
            
            /*$sql = "INSERT INTO trn_promotions 
                            SET prom_name = '" . ($data['promotion_name']) . "', 
                                prom_code = '" . ($data['promotion_code']) . "', 
                                category = '" . ($data['promotion_category']) . "', 
                                period = '" . ($data['promotion_period']) . "', 
                                start_date = '".$from_date."'";
                            $sql .= $query.$timeQuery;
                            $sql .=", qty_limit = '" . ($data['promotion_item_qty_limit']) . "', 
                                prom_type_id = '" . ($data['promotion_type']) . "',
                                buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                                same_group = '" . $same_group . "', 
                                disc_each_nth = '" . ($data['promotion_disc_options']) . "', 
                                bill_value = '" . ($data['promotion_bill_value']) . "', 
                                discount_type_id = '" . $discount_type_id . "', 
                                discounted_value = '" . ($data['promotion_discounted_value']) . "', 
                                addl_disc_cust = '" . $promotion_addl_discount . "', 
                                discounted_item = '" . (json_encode($data['promotion_discounted_item_text'])) . "', 
                                qty_limit_bal = '" . ($data['promotion_item_qty_limit']) . "', 
                                discount_limit = '" . ($data['promotion_discount_limit']) . "', 
                                exclude_include = '" . ($data['promotion_item_state']) . "', 
                                slab_price = '" . ($data['promotion_slab_price']) . "', 
                                mfg_prom_desc = '" . ($data['mfg_prom_desc']) . "', 
                                mfg_buydown_desc = '" . ($data['mfg_buydown_desc']) . "', 
                                mfg_multipack_desc = '" . ($data['mfg_multipack_desc']) . "', 
                                mfg_discount = '" . ($data['mfg_discount']) . "', 
                                img_url = '', 
                                status = '" . ($data['promotion_status']) . "' ";*/
                                
    
            $sql = "INSERT INTO trn_promotions 
                            SET prom_name = '" . ($data['promotion_name']) . "', 
                                prom_code = '" . ($data['promotion_code']) . "', 
                                category = '" . ($data['promotion_category']) . "', 
                                period = '" . ($data['promotion_period']) . "', 
                                start_date = '".$from_date."',";
                            $sql .= $query.$timeQuery;
                            if(!empty($data['promotion_item_qty_limit'])){
                                $sql .=", qty_limit = '" . ($data['promotion_item_qty_limit']) . "',"; 
                            }
                           
                            $sql .="prom_type_id = '" . ($data['promotion_type']) . "',
                                buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                                same_group = '" . $same_group . "', 
                                disc_each_nth = '" . ($data['promotion_disc_options']) . "', ";
                            
                            if(!empty($data['promotion_bill_value'])){
                                $sql .=", bill_value = '" . ($data['promotion_bill_value']) . "',"; 
                            }    
                             
                            $sql .="discount_type_id = '" . $discount_type_id . "', 
                                discounted_value = '" . ($data['promotion_discounted_value']) . "', 
                                addl_disc_cust = '" . $promotion_addl_discount . "', 
                                discounted_item = '" . (json_encode($data['promotion_discounted_item_text'] ?? '')) . "', ";
                            
                                if(!empty($data['promotion_bill_value'])){
                                    $sql .=", qty_limit_bal = '" . ($data['promotion_item_qty_limit']) . "',"; 
                                }   
                                
                                if(!empty($data['promotion_bill_value'])){
                                    $sql .=", discount_limit = '" . ($data['promotion_discount_limit']) . "',"; 
                                }  
                                
                                if(!empty($data['promotion_bill_value'])){
                                    $sql .=", slab_price = '" . ($data['promotion_slab_price']) . "',"; 
                                } 
                                
                                if(!empty($data['promotion_bill_value'])){
                                    $sql .=", mfg_discount = '" . ($data['mfg_discount']) . "', "; 
                                } 
                                 
                            $sql .="exclude_include = '" . ($data['promotion_item_state']) . "', 
                                 
                                mfg_prom_desc = '" . ($data['mfg_prom_desc']) . "', 
                                mfg_buydown_desc = '" . ($data['mfg_buydown_desc']) . "', 
                                mfg_multipack_desc = '" . ($data['mfg_multipack_desc']) . "', 
                                img_url = '', 
                                prom_qty_limit = '" . $qty_limit . "',
                                allow_reg_price = '" . $allow_reg_price . "',
                                status = '" . ($data['promotion_status']) . "' ";
                                
                                
                    // 			echo $sql;exit;
        //     $sql = "INSERT INTO trn_promotions 
                    // 		SET prom_name = '" . ($data['promotion_name']) . "', 
                    // 			prom_code = '" . ($data['promotion_code']) . "', 
                    // 			category = '" . ($data['promotion_category']) . "', 
                    // 			period = '" . ($data['promotion_period']) . "', 
                    // 			start_date = '".$from_date."',
                    // 			end_date = '".$to_date."', 
                    // 			from_time = '" . date("0000-00-00 H:i:s",strtotime($data['promotion_from_time'])) . "',  
                    // 			to_time = '" . date("0000-00-00 H:i:s",strtotime($data['promotion_to_time'])) . "',  
                    // 			qty_limit = '" . ($data['promotion_item_qty_limit']) . "', 
                    // 			prom_type_id = '" . ($data['promotion_type']) . "',
                    // 			buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                    // 			same_group = '" . ($data['promotion_same_itme']) . "', 
                    // 			bill_value = '" . ($data['promotion_bill_value']) . "', 
                    // 			discount_type_id = '" . ($data['promotion_discount_type']) . "', 
                    // 			discounted_value = '" . ($data['promotion_discounted_value']) . "', 
                    // 			discounted_item = '" . (json_encode($data['promotion_discounted_item'])) . "', 
                    // 			discount_limit = '" . ($data['promotion_discount_limit']) . "', 
                    // 			exclude_include = '" . ($data['promotion_item_state']) . "', 
                    // 			slab_price = '" . ($data['promotion_slab_price']) . "', 
                    // 			img_url = '', 
                    // 			status = '" . ($data['promotion_status']) . "'";
                                
            \DB::connection('mysql_dynamic')->insert($sql);
            
            $last_ins_id  = \DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() last_id');
            
            $prom_id = $last_ins_id[0]->last_id;
            

            if(!empty($data['added_promotion_items_text']) && $prom_id)
            {
                $buyItems = $data['added_promotion_items_text'];
                
                if(!empty($data['disc']))
                {
                    $discount_price_list = $data['disc'];
                }
                
                foreach($buyItems as $key => $value)
                {
                    $itemDetails = Item :: where('iitemid',$value)->get()->toArray();
                   dd($itemDetails);
                    // dd($itemDetails);
                    if(!empty($itemDetails))
                    {
                        $disc_value = $discount_price_list[$value] ? $discount_price_list[$value] : 0;
                      
                      //  $disc_value = $value;
                        print_r("disc_value".$disc_value);
                        if($data['promotion_type'] == 10){
                           
                            
                          //  $new_price = $data['promotion_slab_price']/$data['promotion_buy_qty'];
                            
                            $new_price = $promotion_addl_discount/$data['promotion_buy_qty'];
                           print_r('new_price'.$new_price); 
                            $new_price = number_format($new_price , 2);
                            print_r('new_price'.$new_price);
                            \DB::connection('mysql_dynamic')->insert("INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".session()->get('SID')."' ");
                        } else {
                            // dd($itemDetails['vbarcode']);
                            $insert_data = "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".session()->get('SID')."' ";
                           
                            \DB::connection('mysql_dynamic')->insert($insert_data);
                        }
                    }  
                }                
            }
            
            if(!empty($data['promotion_customers']) && $prom_id)
            {
                $customers = $data['promotion_customers'];
               
                foreach($customers as $value)
                {
                    if(!empty($value))
                    {
                        $this->db2->query("INSERT INTO trn_prom_customers SET prom_id ='".$prom_id."', cust_id ='".$value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ");
                    }
                }
                
            }
            
            return $prom_id;
        }

        function getPromotion($prom_id) {
            $query = \DB::connection('mysql_dynamic')->select("SELECT * FROM trn_promotions WHERE prom_id = '".$prom_id."'");
            return $query;
        }

        
        function getSavedItemsAjax($items,$prom_id) {
            
            if(empty($items)) { return array();}
            
            // $query = $this->db2->query("SELECT (select discounted_price from trn_prom_details where barcode = mi.vbarcode and prom_id = $prom_id) as new_price, mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice,mi.* FROM mst_item as mi WHERE mi.vbarcode = '".$items[0]."' AND mi.estatus='Active'");
            $sql = "SELECT (select discounted_price from trn_prom_details where barcode = mi.vbarcode and prom_id = $prom_id limit 1) as new_price, mi.iitemid,mi.vitemcode,mi.vitemname,mi.vbarcode, mi.dcostprice, mi.dunitprice,mi.* FROM mst_item as mi WHERE mi.iitemid = '".$items[0]['iitemid']."' AND mi.estatus='Active'";
            
            $query = \DB::connection('mysql_dynamic')->select($sql);
            
            $query = array_map(function ($value) {
                return (array)$value;
            }, $query);

            return $query;
        }

        public function editPromotion($prom_id, $data) {
            echo "<pre>";print_r($data);echo "<pre>";die;
            
            if (!isset($data['promotion_discounted_item'])) {
                $data['promotion_discounted_item'] = array();
            }
            $query = "";
            if($input['promotion_category'] == "Open Ended" || $input['promotion_category'] == "Stock Bound")
            {
                $from_date = date("Y-m-d");
                $to_date = "NULL";
                $query .= ", end_date = ".$to_date ;
                
            }
            elseif($input['promotion_category'] == "Time Bound")
            {
                $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_from_date']))) ;
                $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_to_date']))) ;
                $query .= ", end_date = '$to_date' ";
            }
            else
            {
                $from_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_from_date']))) ;
                $to_date = date("Y-m-d",strtotime(str_replace('-', '/',$data['promotion_to_date']))) ;
                $query .= ", end_date = '$to_date' ";
            }
            
            $timeQuery = "";
            if($input['promotion_category'] == "Time Bound")
            {
                $timeQuery .= ", from_time = '".date("H:i:s",strtotime($data['promotion_from_time']))."' ";
                $timeQuery .= ", to_time = '".date("H:i:s",strtotime($data['promotion_to_time']))."' ";
            }
            
            
    
            
            if($data['promotion_type'] == 10){
                $discount_type_id = 2; 
                $promotion_addl_discount = isset($data['promotion_addl_discount'])?($data['promotion_addl_discount']):($data['promotion_slab_price']);
                } else {
                $discount_type_id = ($data['promotion_discount_type']);
                $promotion_addl_discount = isset($data['promotion_addl_discount'])?($data['promotion_addl_discount']):0;
                }
            
            
            
            
            $qty_limit = ($data['qty_limit']);
    
            $qty_limit = (!is_nan($qty_limit) && ($qty_limit > 0))?$qty_limit:0;
            
            $allow_reg_price = $qty_limit > 0?($data['allow_reg_price']):'Y';
            
            /*$sql = "UPDATE trn_promotions 
                            SET prom_name = '" . ($data['promotion_name']) . "', 
                                prom_code = '" . ($data['promotion_code']) . "', 
                                category = '" . ($data['promotion_category']) . "', 
                                period = '" . ($data['promotion_period']) . "', 
                                start_date = '".$from_date."'";
                            $sql .= $query.$timeQuery;
                            $sql .=", qty_limit = '" . ($data['promotion_item_qty_limit']) . "', 
                                buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                                same_group = '" . ($data['promotion_same_itme']) . "',
                                disc_each_nth = '" . ($data['promotion_disc_options']) . "',
                                bill_value = '" . ($data['promotion_bill_value']) . "', 
                                discount_type_id = '" . $discount_type_id . "', 
                                discounted_value = '" . ($data['promotion_discounted_value']) . "',
                                addl_disc_cust = '" . $promotion_addl_discount . "', 
                                discounted_item = '" . (json_encode($data['promotion_discounted_item_text'])) . "', 
                                discount_limit = '" . ($data['promotion_discount_limit']) . "', 
                                qty_limit_bal = '" . ($data['promotion_item_qty_limit']) . "', 
                                exclude_include = '" . ($data['promotion_item_state']) . "', 
                                slab_price = '" . ($data['promotion_slab_price']) . "', 
                                mfg_prom_desc = '" . ($data['mfg_prom_desc']) . "', 
                                mfg_buydown_desc = '" . ($data['mfg_buydown_desc']) . "', 
                                mfg_multipack_desc = '" . ($data['mfg_multipack_desc']) . "',
                                mfg_discount = '" . ($data['mfg_discount']) . "',
                                img_url = '', 
                                status = '" . ($data['promotion_status']) . "' WHERE prom_id = '" . (int)$prom_id . "' ";*/
                                
            $sql = "UPDATE trn_promotions 
                            SET prom_name = '" . ($data['promotion_name']) . "', 
                                prom_code = '" . ($data['promotion_code']) . "', 
                                category = '" . ($data['promotion_category']) . "', 
                                period = '" . ($data['promotion_period']) . "', 
                                start_date = '".$from_date."'";
                            $sql .= $query.$timeQuery;
                            $sql .=", qty_limit = '" . ($data['promotion_item_qty_limit']) . "', 
                                buy_qty = '" . ($data['promotion_buy_qty']) . "', 
                                same_group = '" . ($data['promotion_same_itme']) . "',
                                disc_each_nth = '" . ($data['promotion_disc_options']) . "',
                                bill_value = '" . ($data['promotion_bill_value']) . "', 
                                discount_type_id = '" . $discount_type_id . "', 
                                discounted_value = '" . ($data['promotion_discounted_value']) . "',
                                addl_disc_cust = '" . $promotion_addl_discount . "', 
                                discounted_item = '" . (json_encode($data['promotion_discounted_item_text'])) . "', 
                                discount_limit = '" . ($data['promotion_discount_limit']) . "', 
                                qty_limit_bal = '" . ($data['promotion_item_qty_limit']) . "', 
                                exclude_include = '" . ($data['promotion_item_state']) . "', 
                                slab_price = '" . ($data['promotion_slab_price']) . "', 
                                mfg_prom_desc = '" . ($data['mfg_prom_desc']) . "', 
                                mfg_buydown_desc = '" . ($data['mfg_buydown_desc']) . "', 
                                mfg_multipack_desc = '" . ($data['mfg_multipack_desc']) . "',
                                mfg_discount = '" . ($data['mfg_discount']) . "',
                                img_url = '',
                                prom_qty_limit = '" . $qty_limit . "',
                                allow_reg_price = '" . $allow_reg_price . "',
                                status = '" . ($data['promotion_status']) . "' WHERE prom_id = '" . (int)$prom_id . "' ";							
                                
                                
                    // 			echo $sql;die;
                \DB::connection('mysql_dynamic')->update($sql);
        
            
            if(!empty($data['added_promotion_items_text']) && $prom_id) {
                //print_r($data['added_promotion_items_text']);die;
                // \DB::connection('mysql_dynamic')->select("DELETE FROM  trn_prom_details WHERE prom_id = '" . (int)$prom_id . "'");
                
                
                
                if(!empty($data['disc'])) {
                    $discount_price_list = $data['disc'];
                }
                
                
                
                
                $existing_items = \DB::connection('mysql_dynamic')->select("SELECT tpd.prom_detail_id prom_detail_id, tpd.barcode barcode, mi.iitemid item_id FROM  trn_prom_details tpd LEFT JOIN mst_item mi ON tpd.barcode=mi.vbarcode WHERE tpd.prom_id = '" . (int)$prom_id . "'")->rows;
                
                // $existing  = ['delete' => [], 'update' => []];
                foreach($existing_items as $et){
                    
                    if(in_array($et['item_id'], $data['added_promotion_items_text'])){
                        
                        // add it in the items to be updated
                        // $existing['update'][] = $et['item_id'];
                        
                        $itemDetails = $this->model_administration_items->getItem($et['item_id']);
                        
                        
                        if(!empty($itemDetails)) {
    
                            $disc_value = $discount_price_list[$et['item_id']] ? $discount_price_list[$et['item_id']] : 0;
                        
                            if($data['promotion_type'] == 10){
                                
                                $new_price = $promotion_addl_discount/$data['promotion_buy_qty'];
                                $new_price = number_format($new_price , 2);
                                
                                $update_query = "UPDATE trn_prom_details SET `prom_id`='{$prom_id}', `barcode`='{$itemDetails['vbarcode']}', `unit_price`='{$itemDetails['dunitprice']}', `discounted_price`='{$new_price}', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='{$itemDetails['SID']}' WHERE `prom_detail_id`={$et['prom_detail_id']}";
    
                            } else {
                                $update_query = "UPDATE trn_prom_details SET `prom_id`='{$prom_id}', `barcode`='{$itemDetails['vbarcode']}', `unit_price`='{$itemDetails['dunitprice']}', `discounted_price`='{$disc_value}', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='{$itemDetails['SID']}' WHERE `prom_detail_id`={$et['prom_detail_id']}";
                            }
                            //  dd($update_query);   		            
                            \DB::connection('mysql_dynamic')->update($update_query);
                        }
                        
                    } else {
                        // delete the items not part of the promotions
                        // $existing['delete'][] = $et['barcode'];
                        
                        
                        $delete_query = "DELETE FROM  trn_prom_details WHERE prom_detail_id = '" . (int)$et['prom_detail_id'] . "'";
                        \DB::connection('mysql_dynamic')->select($delete_query);
                        
                        $insert_delete_query = "INSERT INTO `mst_delete_table` (`TableName`, `TableId`, `Action`, `LastUpdate`, `SID`) VALUES('trn_prom_details', '". (int)$et['prom_detail_id'] . "', 'delete', '".date('Y-m-d H:i:s')."', '".$this->session->data['SID']."')";
                        \DB::connection('mysql_dynamic')->select($insert_delete_query);
                    }
                    
                    // Remove the items from the input that have already been dealt with.
                    $remove_key = array_search($et['item_id'], $data['added_promotion_items_text']);
                    if($remove_key !== FALSE){
                        unset($data['added_promotion_items_text'][$remove_key]);
                    }
                }
                
                // $existing['insert'] = $data['added_promotion_items_text'];
        
                // return $existing;
                
                // return $data['added_promotion_items_text'];
                
                foreach($data['added_promotion_items_text'] as $prom_item){
    
    
                    $itemDetails = $this->model_administration_items->getItem($prom_item);
                    
                    if(!empty($itemDetails)) {
                        
                        $disc_value = $discount_price_list[$prom_item] ? $discount_price_list[$prom_item] : 0;
    
                        if($data['promotion_type'] == 10){
                            
                            $new_price = $promotion_addl_discount/$data['promotion_buy_qty'];
                            
                            $new_price = number_format($new_price , 2);
                            
                            $query_insert = "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ";
                        } else {
                            $query_insert = "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ";
                        }
                        
                        // dd($query_insert);		            
                        \DB::connection('mysql_dynamic')->select($query_insert);
                        
                    }
                }
                
                
                
                
                //  foreach($buyItems as $key => $value) {
                    
                //      $itemDetails = $this->model_administration_items->getItem($value);
                    
                //      if(!empty($itemDetails)) {
                        
                //          $disc_value = $discount_price_list[$value] ? $discount_price_list[$value] : 0;
                        
                //        //$disc_value = $value;
                        
                //        //  echo "INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ";
                        
                //          if($data['promotion_type'] == 10){
                            
                //            //  $new_price = $data['promotion_slab_price']/$data['promotion_buy_qty'];
                //              $new_price = $promotion_addl_discount/$data['promotion_buy_qty'];
                            
                //              $new_price = number_format($new_price , 2);
                            
                //              \DB::connection('mysql_dynamic')->select("INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$new_price."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ");
                //          } else {
                //              \DB::connection('mysql_dynamic')->select("INSERT INTO trn_prom_details SET prom_id ='".$prom_id."', barcode ='".$itemDetails['vbarcode']."', unit_price ='".$itemDetails['dunitprice']."', discounted_price ='".$disc_value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ");
                //          }
                        
                //      }
                //  }
                
            }
            
            if(!empty($data['promotion_customers']) && $prom_id) {
                \DB::connection('mysql_dynamic')->select("DELETE FROM  trn_prom_customers WHERE prom_id = '" . (int)$prom_id . "'");
                
                $customers = $data['promotion_customers'];
                
                foreach($customers as $value) {
                    if(!empty($value)) {
                        \DB::connection('mysql_dynamic')->select("INSERT INTO trn_prom_customers SET prom_id ='".$prom_id."', cust_id ='".$value."', LastUpdate ='".date('Y-m-d H:i:s')."', SID ='".$itemDetails['SID']."' ");
                    }
                }
            }
            
            return $prom_id;
        }
         
        
}
