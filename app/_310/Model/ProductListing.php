<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProductListing extends Model{

    public function getProductListingReport() {
		$query = "SELECT mi.iitemid, mi.vitemtype, mi.vbarcode, mi.vitemname, mi.dunitprice, mi.npack, mi.vsize, mi.iqtyonhand as qoh, mi.vcategorycode, mi.vdepcode, mc.vcategoryname, md.vdepartmentname, CASE WHEN mi.NPACK = 1 or (mi.npack is null)   then IQTYONHAND else (Concat(cast(((IQTYONHAND div mi.NPACK )) as signed), '  (', Mod(IQTYONHAND,mi.NPACK) ,')') ) end as IQTYONHAND  FROM mst_item as mi LEFT JOIN mst_category mc ON(mc.vcategorycode=mi.vcategorycode) LEFT JOIN mst_department md ON(md.vdepcode=mi.vdepcode)";
        $return_data = DB::connection('mysql_dynamic')->select($query);
        $result = json_decode(json_encode($return_data), true); 
        return $result;
    }
    
    public function getStore(){       
        $sql="SELECT * FROM mst_store";
        $return_data = DB::connection('mysql_dynamic')->select($sql);
        $result = json_decode(json_encode($return_data), true); 
        return $result;
    }
}
