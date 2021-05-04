<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class BuyDown extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'trn_buydown';
    protected $primaryKey = 'buydown_id';
    public $timestamps = false;
    
    protected $fillable = ['buydown_name', 'buydown_code', 'buydown_amount', 'start_date', 'end_date', 'created_at', 'LastUpdate', 'status', 'SID'];

 
    // public function getSelectedItems($selectedItemid){
    //     $query = "SELECT iitemid, vitemname, vbarcode, dunitprice, dcostprice, npack, isparentchild, parentid FROM mst_item WHERE iitemid = '".$selectedItemid."' ";
    //     $result = DB::connection('mysql_dynamic')->select($query);
    //     dd($result);
    //     return $result;
    // }

    function getSavedBuyItems($buydown_id) {
          $query = DB::connection('mysql_dynamic')->select("SELECT a.*, b.new_costprice as live_cost, b.dunitprice as live_price, b.iitemid FROM trn_buydown_details a Left JOIN mst_item b ON (a.vbarcode = b.vbarcode) WHERE buydown_id = '".$buydown_id."'");
          return $query;
    }
    
    
    // function getbuydown($buydown_id) {
    //      //print_r("test");die;
    //     $query = $this->db2->query("SELECT * FROM trn_buydown WHERE buydown_id = '".$buydown_id."'");
    //     return $query->row;
    // }
    
    function getActivePromotionTypes() {
        $select_query = "SELECT * FROM mst_prom_type where is_active=1";
        $query = \DB::connection('mysql_dynamic')->select($select_query);
        return $query;
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
    
    
    public function getAllBuydown($start_from,$limit) {
        $query = \DB::connection('mysql_dynamic')->select("SELECT buydown_id,buydown_name, buydown_code,buydown_amount,DATE_FORMAT(start_date,'%m-%d-%Y') as start_date ,DATE_FORMAT(end_date,'%m-%d-%Y') as end_date ,status FROM trn_buydown ORDER BY buydown_id DESC LIMIT $start_from, $limit");
        
        //====converting object data into array=====
        $query = array_map(function ($value) {
            return (array)$value;
        }, $query);
        
        $count_query = "SELECT count(DISTINCT(buydown_id)) as total_count FROM trn_buydown";
        $run_count_query = \DB::connection('mysql_dynamic')->select($count_query);
        $count_total = $run_count_query[0]->total_count;
        
        $return = ['records'=>$query,'total_count'=>$count_total];
        return $return;
        // return $query->rows;
    }
    
    public function searchBuydown($search,$start_from,$limit) {
        $condition = "";
        if(isset($search[1]['search']['value']) && $search[1]['search']['value'])
        {
            $condition .= " AND buydown_name LIKE  '%" . $search[1]['search']['value'] . "%'";
        }
        
        if(isset($search[2]['search']['value']) && $search[2]['search']['value'])
        {
            $condition .= " AND buydown_code LIKE '%" . $search[2]['search']['value'] ."%' ";    
        }
        
        if(isset($search[3]['search']['value']) && $search[3]['search']['value'])
        {
            $condition .= " AND buydown_amount = '". $search[3]['search']['value'] ."' ";    
        }
        
        if(isset($search[4]['search']['value']) && $search[4]['search']['value'])
        {
            $condition .= " AND start_date = '". $search[4]['search']['value'] ."' ";    
        }
       
        if(isset($search[5]['search']['value']) && $search[5]['search']['value'])
        {
            
                $condition .= " AND end_date = '". $search[5]['search']['value'] ."' ";
           
        }
        
        $query = \DB::connection('mysql_dynamic')->select("SELECT buydown_id,buydown_name, buydown_code,buydown_amount,DATE_FORMAT(start_date,'%m-%d-%Y') as start_date ,DATE_FORMAT(end_date,'%m-%d-%Y') as end_date ,status FROM trn_buydown WHERE buydown_id != '' $condition ORDER BY buydown_id DESC LIMIT $start_from, $limit");
        
        //====converting object data into array=====
        $query = array_map(function ($value) {
            return (array)$value;
        }, $query);
        
        $run_count_query = \DB::connection('mysql_dynamic')->select("SELECT count(*) as total_count FROM trn_buydown WHERE buydown_id != '' $condition ORDER BY buydown_id DESC");
        $count_total = $run_count_query[0]->total_count;
        
        $return = ['records'=>$query,'total_count'=>$count_total];
        return $return;
        
        // return $query->rows;
        
    }


}
