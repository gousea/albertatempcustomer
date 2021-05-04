<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class mst_category extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_category';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $fillable = ['vcategoryname', 
        'vdescription', 
        'vcategorttype', 
        'estatus', 
        'isequence', 
        'dept_code',     
        'SID'
    ];

    // static function listCategoriesWithSubcategoriesCount($page=1, $items_per_page=100){

    //     $offset = ($page - 1) * $items_per_page;
    //     $limit = $items_per_page;

    //     echo $query = 'SELECT * FROM mst_category c left join (SELECT cat_id, count(*) count_sub_cat from mst_subcategory group by cat_id) s on c.icategoryid=s.cat_id WHERE c.estatus=\'Active\' LIMIT '.$offset.', '.$limit;
        
    //     $result = DB::connection()->select($query);

    //     echo "adsfadsf";
    //     echo "<pre>";
    //     print_r($result);
    //     echo "<pre>";
    //     die;
    //     // return 
    // }
    // static function listCategoriesWithSubcategoriesCount(){
        
    //     echo $sql = "SELECT * FROM mst_category c left join (SELECT cat_id, count(*) count_sub_cat from mst_subcategory group by cat_id) s on c.icategoryid=s.cat_id WHERE c.estatus='Active' ORDER BY vcategoryname";die;

    //     $query = DB::select($sql);
    //     // $users = DB::select('select * from users');
    //     // return $users;
        
    //     // $result = DB::connection()->select($query)->get();
    //     return $query;
    //     // echo "<pre>";
    //     // print_r($query);
    //     // echo "<pre>";
    //     // die;

    // }
}
