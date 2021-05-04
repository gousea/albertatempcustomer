<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class category extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_category';
    public $timestamps = false;
    protected $primaryKey = 'icategoryid';

    protected $guarded = ['*'];

    protected $fillable = ['vcategoryname', 
        'vdescription', 
        'vcategorttype', 
        'estatus', 
        'isequence', 
        'dept_code',     
        'SID'
    ];
    
    public function getDepartments(){
        $results = DB::connection('mysql_dynamic')->table('mst_department')
                ->select('*')
                ->orderByRaw('vdepartmentname','DESC')
                ->get();
        return $results;
    }
    
    public function get_department_categories($dep_code){
        $cond = implode(',', $dep_code);
        $result = Category::WhereIn('dept_code', [$cond])->orderBy('vcategoryname', 'ASC')->get()->toArray();
        return $result;
    }
    
    public function getcategories(){
        $results = DB::connection('mysql_dynamic')->table('mst_category')
                ->select('*')
                ->orderByRaw('vcategoryname','DESC')
                ->get();
        return $results;
    }

    public function getCategoriesInDepartment1($dept_code) {
        $array = implode("','", $dept_code['dept_code']); 
       
        if($dept_code['dept_code'][0]=='All' || $dept_code['dept_code'][0]==''){
             $query = "SELECT * FROM mst_category  ORDER BY vcategoryname";
        }else {
            $query = "SELECT * FROM mst_category WHERE dept_code IN('".$array."') ORDER BY vcategoryname";
        }
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 	
        return $result;
        // $array = implode("','", $dept_code['dept_code']); 
        // $query = "SELECT * FROM mst_category WHERE dept_code IN('".$array."') ORDER BY vcategoryname";
        
    }

    public function getSubCategories1($cat_id) {    
        $array = implode("','", $cat_id['cat_id']);  
        
        if($cat_id['cat_id'][0]=='All' || $cat_id['cat_id'][0]==''){
            $query = "SELECT * FROM mst_subcategory  ORDER BY subcat_name";
        }else{
            $query = "SELECT * FROM mst_subcategory WHERE cat_id IN('".$array."') ORDER BY subcat_name";
        }
        $result = DB::connection('mysql_dynamic')->select($query);
        return $result;
        
        // $array = implode("','", $cat_id['cat_id']);  
        // $query = "SELECT * FROM mst_subcategory WHERE cat_id IN('".$array."') ORDER BY subcat_name";
        
    }
    
    
}
