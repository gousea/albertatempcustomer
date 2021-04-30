<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemGroupDetail extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'itemgroupdetail';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    
    public static function getItemGroupData($iitemgroupid){

        $query = DB::connection('mysql_dynamic')->select("SELECT DISTINCT(i.vbarcode), i.vitemname, g.isequence, i.dunitprice FROM itemgroupdetail g join mst_item i on g.vsku=i.vbarcode where g.iitemgroupid = '$iitemgroupid' order by g.isequence ;");

        return $query;
    }


}
