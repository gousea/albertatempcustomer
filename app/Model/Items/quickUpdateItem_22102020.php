<?php

namespace App\Model\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Model\Item;

class quickUpdateItem extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $db;

    function __construct()
    {
        $this->db = DB::connection('mysql_dynamic');
    }

    public function getCategories($department = null)
    {
        $query = $this->db->table('mst_category')
            ->select('vcategorycode', 'vcategoryname',  'dept_code')
            ->where('estatus', 'Active');
        if (!is_null($department)) {
            $query->where('dept_code', "$department");
        }
        $result = $query->distinct('vcategorycode')->get();
        return $result;
    }

    public function getAllDepartments()
    {
        $query = $this->db->table('mst_department')
            ->select('vdepcode', 'vdepartmentname')
            ->where('estatus', 'Active');
        $result = $query->distinct('vdepcode')->get();
        return $result;
    }

    public function getItemGroups()
    {
        $result = $this->db->table('itemgroup')
            ->select('iitemgroupid', 'vitemgroupname')
            ->get();
        return $result;
    }

    public function getItems_new($itemdata)
    {
        $query = Item::from('mst_item as a')
            ->select(
                'a.iitemid','a.vitemtype','a.vitemname','a.vbarcode',
                'a.vcategorycode','a.vdepcode','a.vsuppliercode','a.vunitcode',
                'a.iqtyonhand','a.vtax1','a.vtax2','a.dcostprice',
                'a.nunitcost','a.dunitprice','a.visinventory','a.isparentchild',
                'mc.vcategoryname','md.vdepartmentname','ms.vcompanyname', 'mu.vunitname',
                DB::raw("CASE 
                        WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND 
                        else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND, 
                        case isparentchild 
                        when 0 then a.VITEMNAME  
                        when 1 then Concat(a.VITEMNAME,' [Child]') 
                        when 2 then  Concat(a.VITEMNAME,' [Parent]') end   as VITEMNAME")
            )
            ->leftjoin('mst_category as mc', 'mc.vcategorycode', '=', 'a.vcategorycode')
            ->leftjoin('mst_department as md', 'md.vdepcode', '=', 'a.vdepcode')
            ->leftjoin('mst_supplier as ms', 'ms.vsuppliercode', '=', 'a.vsuppliercode')
            ->leftjoin('mst_unit as mu', 'mu.vunitcode', '=', 'a.vunitcode')
            ->where('a.estatus', 'Active');

        if (!empty($itemdata['search_radio'])) {
            if (!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'category') {
                $query->where('a.vcategorycode', $itemdata['search_find']);
            } else if (!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'department') {
                $query->where('a.vdepcode', $itemdata['search_find']);
            } else if (!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'item_group') {
                $searchValue = $itemdata['search_find'];
                $query->leftJoin('itemgroupdetail as b', function ($join) use ($searchValue) {
                    // $join->on('b.vsku', '=', 'a.vbarcode');
                    $join->on('b.iitemgroupid', '=', '$searchValue');
                });
                $query->where('b.vsku', '=', 'a.vbarcoe');
            } else if (!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'search') {
                $query->where('a.vitemname', 'like', '%' . $itemdata["search_find"] . '%');
            }
        }
        if (!empty($itemdata['search_item_type'])) {
            $query->where('a.vitemtype', $itemdata['search_item_type']);
        }
        $qeuryResult = $query->paginate(20);
        return $qeuryResult;
    }

    public function getItems($itemdata = array()) {
        // dd($itemdata);
        if(isset($itemdata['search_radio']) && $itemdata['search_radio'] == "category" ){
            
            $itemdata['search_find'] =  isset($itemdata['search_vcategorycode']) ? $itemdata['search_vcategorycode'] : '' ;
            // dd($itemdata['search_vcategorycode']);
        }elseif(isset($itemdata['search_radio']) && $itemdata['search_radio'] == "department"){
            $itemdata['search_find'] =  isset($itemdata['search_vdepcode']) ? $itemdata['search_vdepcode'] : '' ;
        }elseif(isset($itemdata['search_radio']) && $itemdata['search_radio'] == "item_group"){
            $itemdata['search_find'] =  isset($itemdata['search_vitem_group_id']) ? $itemdata['search_vitem_group_id'] : '';
        }elseif(isset($itemdata['search_radio']) && $itemdata['search_radio'] == "search"){
            $itemdata['search_find'] =  isset($itemdata['search_item']) ? $itemdata['search_item'] : '';
        }else{
            $itemdata['search_find'] = "";
        }
        // dd($itemdata);
        $datas = array();
        $sql_total_string = $sql_string = '';
        //dd($itemdata['search_item_type']);
        
        if (!empty($itemdata['search_radio'])) {
            
            if(!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'category'){
                $sql_string .= " WHERE a.estatus='Active' AND a.vcategorycode= ". ($itemdata['search_find']);
                
                if($itemdata['search_item_type']!='All'){
                $sql_string .= " AND a.vitemtype= '". ($itemdata['search_item_type'])."'";
                }
              
             //dd($sql_string);
            }else if(!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'department'){
                $sql_string .= " WHERE a.estatus='Active' AND a.vdepcode= ". ($itemdata['search_find']);
                if($itemdata['search_item_type']!='All'){
                    $sql_string .= " AND a.vitemtype= '". ($itemdata['search_item_type'])."'";
                }
            }else if(!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'item_group'){
                $sql_string.=", itemgroupdetail as b";
                $sql_total_string.=", itemgroupdetail as b";
                $sql_string .= " WHERE a.estatus='Active' AND b.iitemgroupid= '". ($itemdata['search_find'])."' AND b.vsku=a.vbarcode";
                $sql_total_string .= " WHERE a.estatus='Active' AND b.iitemgroupid= '". ($itemdata['search_find'])."' AND b.vsku=a.vbarcode";
                
                if($itemdata['search_item_type']!='All'){
                    $sql_string .= " AND a.vitemtype= '". ($itemdata['search_item_type'])."'";
                }
               
            }else if(!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'search'){
                $sql_string .= " WHERE a.estatus='Active' AND (a.vitemname LIKE  '%" .($itemdata['search_find']). "%' OR  a.vbarcode LIKE  '%" .($itemdata['search_find']). "%' OR  ma.valiassku LIKE  '%" .($itemdata['search_find']). "%')";
                
                if($itemdata['search_item_type']!='All'){
                    $sql_string .= " AND a.vitemtype= '". ($itemdata['search_item_type'])."'";
                } 
            } else{
                if($itemdata['search_item_type'] == "All"){
                    $sql_string .= " WHERE a.estatus='Active'  ";
                }else {
                    
                    $sql_string .= " WHERE a.estatus='Active' AND a.vitemtype= '". ($itemdata['search_item_type'])."' ";
                }
            }
        }else{
            $sql_string .= " WHERE a.estatus='Active' AND a.vitemtype= '". ($itemdata['search_item_type'])."'";
            $sql_string .= ' ORDER BY a.LastUpdate DESC';

            if (isset($itemdata['start']) || isset($itemdata['limit'])) {
                if ($itemdata['start'] < 0) {
                    $itemdata['start'] = 0;
                }
                if ($itemdata['limit'] < 1) {
                    $itemdata['limit'] = 20;
                }
                $sql_string .= " LIMIT " . (int)$itemdata['start'] . "," . (int)$itemdata['limit'];
            }
            
            // dd($sql_string);
        }
        
        $sql_query = "SELECT DISTINCT a.iitemid, a.vitemtype, a.vitemname, a.vbarcode, a.vcategorycode, a.vdepcode, a.vsuppliercode, a.vunitcode,
        a.iqtyonhand, a.vtax1, a.vtax2, a.dcostprice, a.nunitcost, a.dunitprice, a.visinventory, a.isparentchild, mc.vcategoryname, 
        md.vdepartmentname, ms.vcompanyname, mu.vunitname , CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else 
        (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND, case isparentchild when 0
        then a.VITEMNAME  when 1 then Concat(a.VITEMNAME,' [Child]') when 2 then  Concat(a.VITEMNAME,' [Parent]') end   as VITEMNAME 
        FROM mst_item as a LEFT JOIN mst_category mc ON(mc.vcategorycode=a.vcategorycode) 
        LEFT JOIN mst_department md ON(md.vdepcode=a.vdepcode)
        LEFT JOIN mst_supplier ms ON(ms.vsuppliercode=a.vsuppliercode) 
        LEFT JOIN mst_itemalias ma ON(ma.vsku=a.vbarcode) 
        LEFT JOIN mst_unit mu ON(mu.vunitcode=a.vunitcode) $sql_string ";
        // dd($sql_query);
        
        $query = DB::connection('mysql_dynamic')->select($sql_query);
        //dd($query);
        return $query;
    }

    public function updateItem($listItems)
    {
        ini_set('max_execution_time', '0');
        $success = array();
        $error = array();
        if(isset($listItems['items'])){
            $data = $listItems['items'];
        }else{
            $data = [];
        }
        
        error_reporting(0);
        if (isset($data) && count($data) > 0) {
            try {
                foreach ($data as $value) {
                    $current_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$value['iitemid'] . "'");
                    if ($current_item['iqtyonhand'] != $value['iqtyonhand'] && $value['iqtyonhand'] != '') {
                        // $query = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
                        
                        // $ipid = $query['ipiid'];
                        // $txt='';
                        $txt="pre qoh ".$current_item[0]->iqtyonhand."new qoh ".$value['iqtyonhand']."barcode ".$current_item[0]->vbarcode."date and time ". date('Y/m/d H:i:s')." \r\n" ;

                        $myfile = fopen(resource_path('views/quicklog.blade.php'), "a");
                        fwrite($myfile, $txt);  
                        
                        fclose($myfile);  
                                
                        
                        
                        $sql="SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1";
                        $query = DB::connection('mysql_dynamic')->select($sql);
                        $ipid = $query[0]->ipiid;
                       
                        
                        $vrefnumber = str_pad($ipid + 1, 9, "0", STR_PAD_LEFT);
                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventory SET  vrefnumber= $vrefnumber,estatus='Close',dcreatedate=CURRENT_TIMESTAMP, vtype = 'Quick Update',SID = '" . (int)(session()->get('sid')) . "'");
                        // $ipiid = DB::getPdo()->lastInsertId();
                        $ipiid = $vrefnumber;
                       
                        $quickvalue = $value['iqtyonhand'] - $current_item[0]->iqtyonhand;
                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',
                             vitemid = '" . $current_item[0]->iitemid . "',
                             vitemname = '" . $current_item[0]->vitemname. "',
                             vunitcode = '" . $current_item[0]->vunitcode. "',
                             ndebitqty= '" . $quickvalue . "', 
                             vbarcode = '" . $current_item[0]->vbarcode . "', 
                             SID = '" . (int)(session()->get('sid')) . "'
                             ");
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET iqtyonhand = '" . ($value['iqtyonhand']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                    }
                    if ($current_item['nunitcost'] != $value['nunitcost'] && $value['nunitcost'] != '') {
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nunitcost = '" . ($value['nunitcost']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                    }
                    if ($current_item['vdepcode'] != $value['vdepartmentname'] && $value['vdepartmentname'] != '') {  //echo" 134:UPDATE mst_item SET vdepcode = '" . ($value['vdepartmentname']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'";die;
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vdepcode = '" . ($value['vdepartmentname']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                    }
                    if ($current_item['vcategorycode'] != $value['vcategoryname'] && $value['vcategoryname'] != '') {
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vcategorycode = '" . ($value['vcategoryname']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                    }
                    if ($current_item['vcategorycode'] != $value['vcategoryname'] && $value['vcategoryname'] != '') {   //echo" 142:UPDATE mst_item SET vcategorycode = '" . ($value['vcategoryname']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'";die;
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vcategorycode = '" . ($value['vcategoryname']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                    }
                    if ((($current_item['dcostprice'] != $value['dcostprice']) && ($current_item['dcostprice'] != '0.0000') && $current_item['isparentchild'] != 1) && (($current_item['dunitprice'] != $value['dunitprice']) && ($current_item['dunitprice'] != '0.00'))) {
                        if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                            $old_item_values = $current_item;
                            unset($old_item_values['itemimage']);
                            $x_general = new \stdClass();
                            $x_general->old_item_values = $old_item_values;
                            try {
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $value['iitemid'] . "',userid = '" . session()->get('user_id') . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'All', oldamount = '0', newamount = '0', source = 'UpdateItemPrice', historydatetime = NOW(),SID = '" . (int)(session()->get('sid')) . "'");
                            } catch (\Exception $e) {
                                report($e);
                            }
                            $trn_webadmin_history_last_id = DB::getPdo()->lastInsertId();
                        }
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nunitcost = '" . ($value['nunitcost']) . "',dunitprice = '" . ($value['dunitprice']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                        $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)$value['iitemid'] . "' ");
                        if ($current_item['dcostprice'] != $new_update_values['dcostprice']) {
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'UipCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . session()->get('user_id') . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid')) . "'");
                        }
                        if ($current_item['dunitprice'] != $new_update_values['dunitprice']) {
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'UipPrice', noldamt = '" . ($current_item['dunitprice']) . "', nnewamt = '" . ($new_update_values['dunitprice']) . "', iuserid = '" . session()->get('user_id') . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid')) . "'");
                        }
                        //trn_itempricecosthistory
                        //trn_webadmin_history
                        if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                            $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)$value['iitemid'] . "' ");
                            unset($new_item_values['itemimage']);
                            $x_general->new_item_values = $new_item_values;
                            $x_general = addslashes(json_encode($x_general));
                            try {
                                DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                            } catch (\Exception $e) {
                                report($e);
                            }
                        }
                        //trn_webadmin_history
                    } else {
                        if (($current_item['dcostprice'] != $value['dcostprice']) && ($current_item['dcostprice'] != '0.0000') && $current_item['isparentchild'] != 1) {
                            //trn_webadmin_history
                            if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                                $old_item_values = $current_item;
                                unset($old_item_values['itemimage']);
                                $x_general = new \stdClass();
                                $x_general->old_item_values = $old_item_values;
                                try {
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $value['iitemid'] . "',userid = '" . session()->get('user_id') . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $current_item['dcostprice'] . "', newamount = '" . $value['dcostprice'] . "', source = 'UpdateItemPrice', historydatetime = NOW(),SID = '" . (int)(session()->get('sid')) . "'");
                                } catch (\Exception $e) {
                                    report($e);
                                }
                                $trn_webadmin_history_last_id_cost = DB::getPdo()->lastInsertId();
                            }
                            //trn_webadmin_history
                            DB::connection('mysql_dynamic')->update("UPDATE mst_item SET nunitcost = '" . ($value['nunitcost']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                            //trn_itempricecosthistory
                            $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)$value['iitemid'] . "' ");
                            if ($current_item['dcostprice'] != $new_update_values['dcostprice']) {
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'UipCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . session()->get('user_id') . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid')) . "'");
                            }
                            if ($current_item['dunitprice'] != $new_update_values['dunitprice']) {
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'UipPrice', noldamt = '" . ($current_item['dunitprice']) . "', nnewamt = '" . ($new_update_values['dunitprice']) . "', iuserid = '" . session()->get('user_id') . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid')) . "'");
                            }
                            //trn_itempricecosthistory
                            //trn_webadmin_history
                            if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                                $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)$value['iitemid'] . "' ");
                                unset($new_item_values['itemimage']);
                                $x_general->new_item_values = $new_item_values;
                                $x_general = addslashes(json_encode($x_general));
                                try {
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                } catch (\Exception $e) {
                                    report($e);
                                }
                            }
                            //trn_webadmin_history
                        }
                        if (($current_item['dunitprice'] != $value['dunitprice'])) {
                            //trn_webadmin_history
                            if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                                $old_item_values = $current_item;
                                unset($old_item_values['itemimage']);
                                $x_general = new \stdClass();
                                $x_general->old_item_values = $old_item_values;
                                try {
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $value['iitemid'] . "',userid = '" . session()->get('user_id') . "',barcode = '" . ($current_item['vbarcode']) . "', type = 'Price', oldamount = '" . $current_item['dunitprice'] . "', newamount = '" . $value['dunitprice'] . "', source = 'UpdateItemPrice', historydatetime = NOW(),SID = '" . (int)(session()->get('sid')) . "'");
                                } catch (\Exception $e) {
                                    report($e);
                                }
                                $trn_webadmin_history_last_id_price = DB::getPdo()->lastInsertId();
                            }
                            DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dunitprice = '" . ($value['dunitprice']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                            //trn_itempricecosthistory
                            $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)$value['iitemid'] . "' ");
                            if ($current_item['dcostprice'] != $new_update_values['dcostprice']) {
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'UipCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . session()->get('user_id') . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid')) . "'");
                            }
                            if ($current_item['dunitprice'] != $new_update_values['dunitprice']) {
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'UipPrice', noldamt = '" . ($current_item['dunitprice']) . "', nnewamt = '" . ($new_update_values['dunitprice']) . "', iuserid = '" . session()->get('user_id') . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid')) . "'");
                            }
                            //trn_itempricecosthistory
                            //trn_webadmin_history
                            if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                                $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)$value['iitemid'] . "' ");
                                unset($new_item_values['itemimage']);
                                $x_general->new_item_values = $new_item_values;
                                $x_general = addslashes(json_encode($x_general));
                                try {
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_price . "'");
                                } catch (\Exception $e) {
                                    report($e);
                                }
                            }
                            //trn_webadmin_history
                        }
                    }
                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vtax1 = '" . ($value['vtax1']) . "', vtax2 = '" . ($value['vtax2']) . "' WHERE iitemid = '" . (int)$value['iitemid'] . "'");
                    if (($current_item['dcostprice'] != $value['dcostprice']) && ($current_item['dcostprice'] != '0.0000') && $current_item['isparentchild'] != 1) {
                        $isParentCheck = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$value['iitemid'] . "'");
                        if ((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)) {
                            $child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vbarcode`,`dcostprice`,`npack` FROM mst_item WHERE parentmasterid= '" . (int)$value['iitemid'] . "' ");
                            if (count($child_items) > 0) {
                                foreach ($child_items as $chi_item) {
                                    //trn_webadmin_history
                                    if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                                        $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)($chi_item['iitemid']) . "' ");
                                        unset($old_item_values['itemimage']);
                                        $x_general_child = new \stdClass();
                                        $x_general_child->is_child = 'Yes';
                                        $x_general_child->parentmasterid = $old_item_values['parentmasterid'];
                                        $x_general_child->old_item_values = $old_item_values;
                                        try {
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . ($chi_item['iitemid']) . "',userid = '" . session()->get('user_id') . "',barcode = '" . ($chi_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $chi_item['dcostprice'] . "', newamount = '" . (($chi_item['npack']) * (($isParentCheck['nunitcost']))) . "', source = 'UpdateItemPrice', historydatetime = NOW(),SID = '" . (int)(session()->get('sid')) . "'");
                                        } catch (\Exception $e) {
                                            report($e);
                                        }
                                        $trn_webadmin_history_last_id_child = DB::getPdo()->lastInsertId();
                                    }
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dcostprice=npack*
                                                '" . ($isParentCheck['nunitcost']) . "',nunitcost='" . ($isParentCheck['nunitcost']) . "' WHERE iitemid= '" . (int)($chi_item['iitemid']) . "'");
                                    //trn_itempricecosthistory
                                    $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)$chi_item['iitemid'] . "' ");
                                    if ($chi_item['dcostprice'] != $new_update_values['dcostprice']) {
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'UipCost', noldamt = '" . ($current_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . session()->get('user_id') . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid')) . "'");
                                    }
                                    //trn_itempricecosthistory
                                    //trn_webadmin_history
                                    if (DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")) {
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '" . (int)($chi_item['iitemid']) . "' ");
                                        unset($new_item_values['itemimage']);
                                        $x_general_child->new_item_values = $new_item_values;
                                        $x_general_child = addslashes(json_encode($x_general_child));
                                        try {
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_child . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_child . "'");
                                        } catch (\Exception $e) {
                                            report($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                }
                            }
                        }
                        //update item pack details
                        if (($isParentCheck['vitemtype']) == 'Lot Matrix') {
                            if ((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)) {
                                $lot_child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentmasterid= '" . (int)$value['iitemid'] . "' ");
                                if (count($lot_child_items) > 0) {
                                    foreach ($lot_child_items as $chi) {
                                        $pack_lot_child_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid= '" . (int)($chi['iitemid']) . "' ");
                                        if (count($pack_lot_child_item) > 0) {
                                            foreach ($pack_lot_child_item as $k => $v) {
                                                $parent_nunitcost = ($isParentCheck['nunitcost']);
                                                if ($parent_nunitcost == '') {
                                                    $parent_nunitcost = 0;
                                                }
                                                $parent_ipack = (int)$v['ipack'];
                                                $parent_npackprice = $v['npackprice'];
                                                $parent_npackcost = (int)$parent_ipack * $parent_nunitcost;
                                                $parent_npackmargin = $parent_npackprice - $parent_npackcost;
                                                if ($parent_npackprice == 0) {
                                                    $parent_npackprice = 1;
                                                }
                                                if ($parent_npackmargin > 0) {
                                                    $parent_npackmargin = $parent_npackmargin;
                                                } else {
                                                    $parent_npackmargin = 0;
                                                }
                                                $parent_npackmargin = (($parent_npackmargin / $parent_npackprice) * 100);
                                                $parent_npackmargin = number_format((float)$parent_npackmargin, 2, '.', '');
                                                DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `npackcost` = '" . $parent_npackcost . "',`nunitcost` = '" . $parent_nunitcost . "',`npackmargin` = '" . $parent_npackmargin . "' WHERE idetid='" . (int)($v['idetid']) . "'");
                                            }
                                        }
                                    }
                                }
                            }
                            $vpackname = 'Case';
                            $vdesc = 'Case';
                            $nunitcost = ($isParentCheck['nunitcost']);
                            if ($nunitcost == '') {
                                $nunitcost = 0;
                            }
                            $ipack = ($isParentCheck['nsellunit']);
                            if (($isParentCheck['nsellunit']) == '') {
                                $ipack = 0;
                            }
                            $npackprice = ($isParentCheck['dunitprice']);
                            if (($isParentCheck['dunitprice']) == '') {
                                $npackprice = 0;
                            }
                            $npackcost = (int)$ipack * $nunitcost;
                            $iparentid = 1;
                            $npackmargin = $npackprice - $npackcost;
                            if ($npackprice == 0) {
                                $npackprice = 1;
                            }
                            if ($npackmargin > 0) {
                                $npackmargin = $npackmargin;
                            } else {
                                $npackmargin = 0;
                            }
                            $npackmargin = (($npackmargin / $npackprice) * 100);
                            $npackmargin = number_format((float)$npackmargin, 2, '.', '');
                            $itempackexist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE vbarcode='" . ($isParentCheck['vbarcode']) . "' AND iitemid='" . (int)$value . "' AND iparentid=1");
                            if (count($itempackexist) > 0) {
                                DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET `ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='" . ($isParentCheck['vbarcode']) . "' AND iitemid='" . (int)$value . "' AND iparentid=1");
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // duplicate entry exception
                $error['error'] = $e->getMessage();
                return $error;
            } catch (\Exception $e) {
                // other mysql exception (not duplicate key entry)
                $error['error'] = $e->getMessage();
                return $error;
            } catch (\Exception $e) {
                // not a MySQL exception
                $error['error'] = $e->getMessage();
                return $error;
            }
        }
        $success['success'] = 'Successfully Updated Item Price';
        return $success;
    }
}
