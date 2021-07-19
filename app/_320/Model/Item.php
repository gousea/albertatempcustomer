<?php

namespace App\_320\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_item';
    public $timestamps = false;
    protected $guarded = ['*'];
    protected $fillable = ['*'];
    
    
    public function deleteItems($data, $srotes_hq = null) {
        $return = array();
        
        if(isset($data) && count($data) > 0){
            if(isset($srotes_hq) &&  $srotes_hq != null){
                $cntStores = count($srotes_hq);
                for($i=0; $i < $cntStores; $i++){
                    $return = [];
                    $cnt = count($data);
                    for($j=0; $j < $cnt; $j++ ){
                            if($data[$j] > 0 && $data[$j] < 100){ 
                                $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                                return $return;
                            }else{
                                $query_i = "SELECT * FROM u".$srotes_hq[$i].".trn_purchaseorderdetail WHERE vitemid='" .$data[$j]. "' ";
                                $query1 = DB::connection('mysql')->select($query_i);
                                
                                if(count($query1) > 0){
                                    $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                                    return $return;
                                }
                                
                                $query_ii = "SELECT * FROM u".$srotes_hq[$i].".trn_salesdetail as ts, u".$srotes_hq[$i].".mst_item as mi WHERE mi.vbarcode=ts.vitemcode AND mi.iitemid='" .$data[$j]. "'";
                                $query2 = DB::connection('mysql')->select($query_ii);
                                
                                if(count($query2) > 0){
                                    $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                                }
                                
                                $sid = session()->get('sid');
                                
                                
                                $insert_query = "INSERT INTO u".$srotes_hq[$i].".mst_delete_table( TableName, TableId, Action, SID) VALUES('mst_item',  '" . (int)$data[$j] . "', 'delete', '" . (int)($srotes_hq[$i])."') ";
                                
                                $a = DB::connection('mysql')->insert($insert_query);
                                
                                $sku = self::where('iitemid', (int)$data[$j])->get('vbarcode');
                                
                                
                                if(isset($sku[0])){
                                    $vbarcode = $sku[0]->vbarcode;
                                }
                                $itemid_query = "Select iitemid from u".$srotes_hq[$i].".mst_item where  vbarcode='" .$vbarcode. "' ";
                                $iitem_ids = DB::connection('mysql')->select($itemid_query);
                                
                                $itemaliass = "SELECT * FROM u".$srotes_hq[$i].".mst_itemalias WHERE vsku = '".$vbarcode."' ";
                                $dumpaliases = DB::connection('mysql')->select($itemaliass);
                                
                                foreach($dumpaliases as $val ){
                                    $del_query = "DELETE FROM u".$srotes_hq[$i].".mst_itemalias WHERE iitemaliasid = '".$val->iitemaliasid."' ";
                                    DB::connection('mysql')->delete($del_query);
                                    
                                    DB::connection('mysql')->insert("INSERT INTO u".$srotes_hq[$i].".mst_delete_table SET  TableName = 'mst_itemalias', 
                                                                    Action = 'delete',TableId = '" . (int)$val->iitemaliasid . "',SID = '" . (int)($srotes_hq[$i])."'");
                                }
                                
                                foreach($iitem_ids as $key => $value){
                                    $item_del = "DELETE FROM u".$srotes_hq[$i].".mst_item WHERE  iitemid='" .$value->iitemid . "' ";
                                    DB::connection('mysql')->delete($item_del);
                                }
                        }
                        
                    }
                }
                $return['success'] = 'Item Deleted Successfully';
            }else{
                $return = [];
                foreach ($data as $key => $value) {
                    if($value > 0 && $value < 100){ 
                        $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                        return $return;
                    }else{
                        $query1 = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_purchaseorderdetail WHERE vitemid='" .$value. "'");
                        if(count($query1) > 0){
                            $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                        }
                        $query2 = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_salesdetail as ts, mst_item as mi WHERE mi.vbarcode=ts.vitemcode AND mi.iitemid='" .$value. "'");
                        if(count($query2) > 0){
                            $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
                        }
                        
                        $sid = session()->get('sid');
                        $a = DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_item',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)($sid)."'");
                        
                        $sku = self::where('iitemid', $value)->get('vbarcode');
                        DB::connection('mysql_dynamic')->delete("DELETE FROM mst_itemalias WHERE vsku = '".$sku[0]->vbarcode."' ");
                        
                        $itemaliass = "SELECT * FROM mst_itemalias WHERE vsku = '".$sku[0]->vbarcode."' ";
                        $dumpaliases = DB::connection('mysql_dynamic')->select($itemaliass);
                        
                        foreach($dumpaliases as $val ){
                            $del_query = "DELETE FROM mst_itemalias WHERE iitemaliasid = '".$val->iitemaliasid."' ";
                            DB::connection('mysql_dynamic')->delete($del_query);
                            
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_itemalias', 
                                                            Action = 'delete',TableId = '" . (int)$val->iitemaliasid . "',SID = '" . (int)($sid)."'");
                        }
                        DB::connection('mysql_dynamic')->delete("DELETE FROM mst_item WHERE iitemid='" . (int)$value . "'");
                    }
                }
                $return['success'] = 'Item Deleted Successfully';
            }
        }
        return $return;

    }

    // public function deleteItems($data) {
    //     $return = array();
        
    //     if(isset($data) && count($data) > 0){
            
    //         $return = [];
            
    //         foreach ($data as $key => $value) {
                
    //             if($value > 0 && $value < 100){ 
    //                 $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
    //                 return $return;
    //             }else{
                    
    //                 $query1 = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_purchaseorderdetail WHERE vitemid='" .$value. "'");
    //                 // $query1 = DB::table('trn_purchaseorderdetail')->where('vitemid', $value)->get()->toArray();
                    
    //                 if(count($query1) > 0){
    //                     $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
    //                     // return $return;
    //                 }
                    
    //                 $query2 = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_salesdetail as ts, mst_item as mi WHERE mi.vbarcode=ts.vitemcode AND mi.iitemid='" .$value. "'");
    //                 // dd(count($query2));
    //                 if(count($query2) > 0){
    //                     $return['error'] = 'Selected item is used in system OR it is default item. You can not delete this item. ';
    //                     // return $return;
    //                 }
    //                 $sid = session()->get('sid');
    //                 $a = DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_item',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)($sid)."'");
                    
    //                 $sku = self::where('iitemid', $value)->get('vbarcode');
    //                 DB::connection('mysql_dynamic')->delete("DELETE FROM mst_itemalias WHERE vsku = '".$sku[0]->vbarcode."' ");
    //                 DB::connection('mysql_dynamic')->delete("DELETE FROM mst_item WHERE iitemid='" . (int)$value . "'");
    //             }
    //             // self::find();
    //         }

    //     }

    //     $return['success'] = 'Item Deleted Successfully';

    //     return $return;

    // }

    public function getItem($iitemid)
    {
        // ini_set('memory_limit', -1');
        
        $datas = array();

        $query = DB::connection('mysql_dynamic')->select("SELECT a.*, CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND FROM mst_item as a where iitemid='". (int)$iitemid ."'");

        if(count($query) > 0){
            $value = $query[0];
            
                $groupid = DB::connection('mysql_dynamic')->select("SELECT * FROM itemgroupdetail WHERE vsku='". $value->vbarcode ."'");
                $groupid = isset($groupid[0])?(array)$groupid[0]:[];

                $itemalias = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemalias WHERE vsku='". $value->vbarcode ."'");
                $itemalias = isset($itemalias)?(array)$itemalias:[];

                $itemslabprices = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemslabprice WHERE vsku='". $value->vbarcode ."'");
                $itemslabprices = isset($itemslabprices[0])?(array)$itemslabprices[0]:[];

                if($value->isparentchild == 2){
         
                    $itemchilditems = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vitemname`,`vbarcode`,`npack` FROM mst_item WHERE parentmasterid='". $value->iitemid ."'");
                    $itemchilditems = isset($itemchilditems[0])?(array)$itemchilditems[0]:[];
                }else{
                  
                    $itemchilditems = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vitemname`,`vbarcode`,`npack` FROM mst_item WHERE parentid='". $value->iitemid ."'");
                    $itemchilditems = isset($itemchilditems[0])?(array)$itemchilditems[0]:[];
                }
                 
                $itemparentitems = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vbarcode`,`vitemname`,`npack`,`IQTYONHAND` FROM mst_item WHERE iitemid='". $value->parentid ."'");
                $itemparentitems = isset($itemparentitems[0])?$itemparentitems[0]:[];
                
                $remove_parent_item = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentid in('". $value->iitemid ."') AND isparentchild !=0");
                $remove_parent_item = isset($remove_parent_item[0])?(array)$remove_parent_item[0]:[];

                $itempacks = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid='". (int)$value->iitemid ."' ORDER BY isequence");
                $itempacks = isset($itempacks)?(array)$itempacks:[];

                $itemvendors = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemvendor as miv,mst_supplier as ms WHERE miv.ivendorid=ms.isupplierid AND  miv.iitemid='". (int)$value->iitemid ."' order by miv.LastUpdate desc ");
                $itemvendors = isset($itemvendors)?(array)$itemvendors:[];
                       
                $get_purchase_details = DB::connection('mysql_dynamic')->select("SELECT ipodetid FROM trn_purchaseorderdetail WHERE vitemid='". (int)$value->iitemid ."'");
                $get_purchase_details = isset($get_purchase_details[0])?(array)$get_purchase_details[0]:[];
                // dd($get_purchase_details->ipodetid);
                
                $ndiscountper = DB::connection('mysql_dynamic')->select("SELECT b.buydown_amount FROM trn_buydown a LEFT JOIN trn_buydown_details b ON ( a.buydown_id = b.buydown_id ) WHERE a.status = 'Active' AND b.vbarcode = '". $value->vbarcode ."' ORDER BY a.buydown_id DESC ");
                $ndiscountper = isset($ndiscountper[0])?(array)$ndiscountper[0]:[];
                
                if(isset($get_purchase_details['ipodetid']) > 0 && !empty($get_purchase_details['ipodetid'])){
                    $data['po_exists'] = true;
                } else {
                    $data['po_exists'] = true;
                }
 
                
                $datas['iitemid'] = $value->iitemid;
                $datas['itempacks'] = $itempacks;
                $datas['iitemgroupid'] = $groupid;
                $datas['itemalias'] = $itemalias;
                $datas['itemvendors'] = $itemvendors;
                $datas['itemslabprices'] = $itemslabprices;
                $datas['itemchilditems'] = $itemchilditems;
                $datas['itemparentitems'] = $itemparentitems;
                $datas['remove_parent_item'] = $remove_parent_item;
                $datas['webstore'] = $value->webstore;
                $datas['vitemtype'] = $value->vitemtype;
                $datas['vitemcode'] = $value->vitemcode;
                $datas['vitemname'] = $value->vitemname;
                $datas['vunitcode'] = $value->vunitcode;
                $datas['vbarcode'] = $value->vbarcode;
                $datas['vpricetype'] = $value->vpricetype;
                $datas['vcategorycode'] = $value->vcategorycode;
                $datas['subcat_id'] = $value->subcat_id;
                $datas['vdepcode'] = $value->vdepcode;
                $datas['vsuppliercode'] = $value->vsuppliercode;
                $datas['iqtyonhand'] = $value->iqtyonhand;
                $datas['QOH'] = $value->iqtyonhand;
                // $datas['QOH'] = $value->IQTYONHAND']; To show QTYH by case
                $datas['ireorderpoint'] = $value->ireorderpoint;
                $datas['reorder_duration'] = $value->reorder_duration;
                $datas['manufacturer_id'] = $value->manufacturer_id;
                // $datas['ireorderpointdays'] = $value->ireorderpointdays;
                $datas['dcostprice'] = $value->dcostprice;
                $datas['dunitprice'] = $value->dunitprice;
                $datas['nsaleprice'] = $value->nsaleprice;
                $datas['nlevel2'] = $value->nlevel2;
                $datas['nlevel3'] = $value->nlevel3;
                $datas['nlevel4'] = $value->nlevel4;
                $datas['iquantity'] = $value->iquantity;

                if(isset($ndiscountper['buydown_amount'])){
                    $datas['ndiscountper'] = $ndiscountper['buydown_amount'];
                }
                
                $datas['ndiscountamt'] = $value->ndiscountamt;
                $datas['vtax1'] = $value->vtax1;
                $datas['vtax2'] = $value->vtax2;
                $datas['vtax3'] = $value->vtax3;
                $datas['vfooditem'] = $value->vfooditem;
                $datas['vdescription'] = $value->vdescription;
                $datas['dlastsold'] = $value->dlastsold;
                $datas['visinventory'] = $value->visinventory;
                $datas['dpricestartdatetime'] = $value->dpricestartdatetime;
                $datas['dpriceenddatetime'] = $value->dpriceenddatetime;
                $datas['estatus'] = $value->estatus;
                $datas['nbuyqty'] = $value->nbuyqty;
                $datas['ndiscountqty'] = $value->ndiscountqty;
                $datas['nsalediscountper'] = $value->nsalediscountper;
                $datas['vshowimage'] = $value->vshowimage;
                if(isset($value->vshowimage) && !empty($value->vshowimage)){
                    $datas['itemimage'] = $value->itemimage;
                }else{
                    $datas['itemimage'] = '';
                }
                
                $datas['vageverify'] = $value->vageverify;
                $datas['ebottledeposit'] = $value->ebottledeposit;
                $datas['nbottledepositamt'] = $value->nbottledepositamt;
                $datas['vbarcodetype'] = $value->vbarcodetype;
                $datas['ntareweight'] = $value->ntareweight;
                $datas['ntareweightper'] = $value->ntareweightper;
                $datas['dcreated'] = $value->dcreated;
                $datas['dlastupdated'] = $value->dlastupdated;
                $datas['dlastreceived'] = $value->dlastreceived;
                $datas['dlastordered'] = $value->dlastordered;
                $datas['nlastcost'] = $value->nlastcost;
                $datas['nonorderqty'] = $value->nonorderqty;
                $datas['vparentitem'] = $value->vparentitem;
                $datas['nchildqty'] = $value->nchildqty;
                $datas['vsize'] = $value->vsize;
                $datas['npack'] = $value->npack;
                $datas['nunitcost'] = $value->nunitcost;
                $datas['ionupload'] = $value->ionupload;
                $datas['nsellunit'] = $value->nsellunit;
                $datas['ilotterystartnum'] = $value->ilotterystartnum;
                $datas['ilotteryendnum'] = $value->ilotteryendnum;
                $datas['etransferstatus'] = $value->etransferstatus;
                // $datas['vsequence'] = $value->vsequence;
                $datas['vcolorcode'] = $value->vcolorcode;
                $datas['vdiscount'] = $value->vdiscount;
                $datas['norderqtyupto'] = $value->norderqtyupto;
                // $datas['vshowsalesinzreport'] = $value->vshowsalesinzreport'];
                $datas['iinvtdefaultunit'] = $value->iinvtdefaultunit;
                $datas['LastUpdate'] = $value->LastUpdate;
                $datas['SID'] = $value->SID;
                $datas['stationid'] = $value->stationid;
                $datas['shelfid'] = $value->shelfid;
                $datas['aisleid'] = $value->aisleid;
                $datas['shelvingid'] = $value->shelvingid;
                $datas['rating'] = $value->rating;
                $datas['vintage'] = $value->vintage;
                $datas['PrinterStationId'] = $value->PrinterStationId;
                $datas['liability'] = $value->liability;
                $datas['isparentchild'] = $value->isparentchild;
                $datas['parentid'] = $value->parentid;
                $datas['parentmasterid'] = $value->parentmasterid;
                $datas['wicitem'] = $value->wicitem;
                
                $datas['last_costprice'] = $value->last_costprice;
                $datas['new_costprice'] = $value->new_costprice;
            
        }  

        return $datas;
    }

    public function getSKU($vbarcode) {
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemalias WHERE valiassku= '". $vbarcode ."'");

        if(count($query) == 0){
            $query = self::where('vbarcode',  $vbarcode)->get()->toArray();
        }

        return $query;
    }
    
    public function getSkupdate($vbarcode, $itemid){
        $query = self::where('vbarcode',  $vbarcode)->where('iitemid', '<>', $itemid)->get()->toArray();
        return $query;
    }


    public function addLotteryItems($data = array()){
        
        $success =array();
        $error =array();
        $last_id = '';
        

        if(isset($data) && count($data) > 0){
            
            $vitemname = str_replace ("'","\'",$data['vitemname']);
            if(isset($data['stores_hq'])){
                
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                }
                
                foreach($stores as $store){
                    try {
                        // $checkDepartment = DB::connection('mysql_dynamic')->table('mst_department')->where('idepartmentid', '4')->get();
                        
                        $checkDepartment = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_department WHERE idepartmentid = 4 ");
                        if(count($checkDepartment) <= 0){
                            DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_department SET idepartmentid = '4', vdepcode = '4', vdepartmentname = 'Lottery DEPT', vdescription = 'Default for Lottery(Instant) items', estatus = 'Active' ");
                        }
                        
                        // $checkCategory = DB::connection('mysql_dynamic')->table('mst_category')->where('icategoryid', '4')->get();
                        $checkCategory = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_category WHERE icategoryid = 4 ");
                        
                        if(count($checkCategory) <= 0){
                            DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_category SET icategoryid = '4', vcategorycode = '4', vcategoryname = 'Lottery Cat', vdescription = 'Default for Lottery(Instant) items', isequence = '4', estatus = 'Active', dept_code = '4' ");
                        }
                        
                        $sql_insert = "INSERT INTO u".$store.".mst_item SET  vitemtype = '" . $data['vitemtype'] . "',
                                        vitemname = '" . $vitemname . "', vbarcode = '" . $data['vbarcode'] . "', 
                                        vcategorycode = '4', vdepcode = '4',
                                        iqtyonhand = '" . (int)$data['iqtyonhand'] . "', dcostprice = '" . $data['dcostprice'] . "', 
                                        dunitprice = '" . $data['dunitprice'] . "', visinventory = '" . $data['visinventory'] . "', 
                                        vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', 
                                        vtax3 = '" . $data['vtax3'] . "', estatus = '" . $data['estatus'] . "', 
                                        dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', 
                                        npack = '" . (int)$data['npack'] . "', SID = '" . (int)($store) . "', 
                                        liability = '" . $data['liability'] . "' ";
    
                        $return = DB::connection('mysql')->insert($sql_insert);
                        
                        // $return = self::orderBy('iitemid', 'DESC')->first();
                        
                        $return = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item ORDER BY iitemid  DESC LIMIT 1");
                        if(isset($return[0])){
                            $last_id = $return[0]->iitemid;
                            DB::connection('mysql')->update("UPDATE u".$store.".mst_item SET vitemcode = '" . $data['vbarcode'] . "' WHERE iitemid = '" . (int)$last_id . "'");
                        }
         
                    }
                    catch ( QueryException $e) {
                        // not a MySQL exception
                       
                        $error['error'] = $e->getMessage(); 
                        return $error; 
                    }
                    
                }
            }else{
                try {
                    //    dd($data);
                    
                    $checkDepartment = DB::connection('mysql_dynamic')->table('mst_department')->where('idepartmentid', '4')->get();
                    
                    if(count($checkDepartment) <= 0){
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_department SET idepartmentid = '4', vdepcode = '4', vdepartmentname = 'Lottery DEPT', vdescription = 'Default for Lottery(Instant) items', estatus = 'Active' ");
                    }
                    
                    $checkCategory = DB::connection('mysql_dynamic')->table('mst_category')->where('icategoryid', '4')->get();
                    
                    if(count($checkCategory) <= 0){
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_category SET icategoryid = '4', vcategorycode = '4', vcategoryname = 'Lottery Cat', vdescription = 'Default for Lottery(Instant) items', isequence = '4', estatus = 'Active', dept_code = '4' ");
                    }
                    
                    $sql_insert = "INSERT INTO mst_item SET  vitemtype = '" . $data['vitemtype'] . "',
                                    vitemname = '" . $vitemname . "', vbarcode = '" . $data['vbarcode'] . "', 
                                    vcategorycode = '4', vdepcode = '4',
                                    iqtyonhand = '" . (int)$data['iqtyonhand'] . "', dcostprice = '" . $data['dcostprice'] . "', 
                                    dunitprice = '" . $data['dunitprice'] . "', visinventory = '" . $data['visinventory'] . "', 
                                    vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', 
                                    vtax3 = '" . $data['vtax3'] . "', estatus = '" . $data['estatus'] . "', 
                                    dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', 
                                    npack = '" . (int)$data['npack'] . "', `SID` = '" . (int)(session()->get('sid')) . "', 
                                    liability = '" . $data['liability'] . "' ";

                    $return = DB::connection('mysql_dynamic')->insert($sql_insert);
                    
                    $return = self::orderBy('iitemid', 'DESC')->first();
                    $last_id = $return->iitemid;
                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vitemcode = '" . $data['vbarcode'] . "' WHERE iitemid = '" . (int)$last_id . "'");
     
                }
                   
                
                catch ( QueryException $e) {
                    // not a MySQL exception
                   
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                
            }
        }
        
        $success['success'] = 'Successfully Added Item';
        $success['iitemid'] = $last_id;
        return $success;
        
        
    }
    
    public function addDepartment($sid, $depid=''){
        if(isset($depid)){
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
                if($return_query_data[0]){
                    $last_id = $return_query_data[0]->idepartmentid;
                }
                // $return_data = $return_query_data[0];
                
                $sql2 = "UPDATE u".$sid.".mst_department SET vdepcode = '".(int)$last_id."' WHERE idepartmentid = '" . (int)$last_id . "'";
                DB::connection('mysql')->update($sql2);
                
                return $last_id;
            }
        }
    }
    
    public function addCategory($sid, $catid=''){
        if(isset($catid) ){
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
                    $dep_estatus = $depname[0]->estatus; 
                }
                
                $department_code = DB::connection("mysql")->select("select * from u".$sid.".mst_department where vdepartmentname = '".$department_name."' ");
                
                if(isset($department_code[0])){
                    
                    $depcode = $department_code[0]->vdepcode;
                  
                } else {
                    
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
        if(isset($subcatid)){
            $get_subcat_name = DB::connection('mysql_dynamic')->select("select * from mst_subcategory where subcat_id = '".$subcatid."' ");
            
            if(isset($get_subcat_name[0])){
                $sub_cat_name   = $get_subcat_name[0]->subcat_name;
                $sub_cat_status = $get_subcat_name[0]->status;
                $cat_id         = $get_subcat_name[0]->cat_id;
            }
            $checkExists = DB::connection('mysql')->select("select * from u".$sid.".mst_subcategory where  subcat_name = '".$sub_cat_name."' ");
            if(count($checkExists) > 0){
                if(isset($checkExists[0])){
                    return $checkExists[0]->subcat_id;
                } 
            }else {
                
                $category = DB::connection("mysql_dynamic")->select("select * from mst_category where icategoryid = '".$cat_id."' ");
                if(isset($category[0])){
                    $cat_name       = $category[0]->vcategoryname;
                    $cat_desc       = $category[0]->vdescription;
                    $cat_type       = $category[0]->vcategorttype;
                    $cat_isequence  = $category[0]->isequence;
                    $cat_status     = $category[0]->estatus;
                    $cat_dep_code   = $category[0]->dept_code;
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
                    if(isset($subcat_id[0])){
                        return $subcat_id[0]->subcat_id;
                    }
                }else {
                    $depname = DB::connection("mysql_dynamic")->select("select * from mst_department where vdepcode = '".$cat_dep_code."' ");
                    if(isset($depname[0])){
                        $department_name    = $depname[0]->vdepartmentname;
                        $dep_desc           = $depname[0]->vdescription;
                        $dep_s_time         = $depname[0]->starttime;
                        $dep_e_time         = $depname[0]->endtime;
                        $dep_isequence      = $depname[0]->isequence;
                        $dep_estatus        = $depname[0]->estatus; 
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
                    // foreach($lastinsertcat_id as $last_id){
                    //     $vcategorycode = $last_id->icategoryid;
                    // }
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
                    // foreach($subcat_id as $s_cat_id) {
                    //     return $s_cat_id->subcat_id;
                    // }
                    if(isset($subcat_id[0])){
                        return $subcat_id[0]->subcat_id;
                    }
                }
                
            }
        }
    }
    
    public function addSupplier($sid, $supplierid=''){
        if(isset($supplierid)){
            $supplier = DB::connection("mysql_dynamic")->select("SELECT * FROM mst_supplier where vsuppliercode = '".$supplierid."' ");
            $vcompanyname = '';
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
                
                // Disabled because vsuppliercode is needed to store in db table
                // $sup_query = "Select isupplierid from u".$sid.".mst_supplier ORDER BY  isupplierid DESC LIMIT 1";
                
                $sup_query = "Select isupplierid, vsuppliercode from u".$sid.".mst_supplier ORDER BY  isupplierid DESC LIMIT 1";
                $sup_id = DB::connection("mysql")->select($sup_query);
                
                if(isset($sup_id[0])){
                   $isuppliercode  = $sup_id[0]->vsuppliercode;
                   $isupplierid  = $sup_id[0]->isupplierid;
                }
                
                // $update_query = "UPDATE u".$sid.".mst_supplier SET vsuppliercode = '".$isuppliercode."' WHERE isupplierid = '".$isuppliercode."' ";
                
                $update_query = "UPDATE u".$sid.".mst_supplier SET vsuppliercode = '".$isuppliercode."' WHERE isupplierid = '".$isupplierid."' ";
                DB::connection("mysql")->statement($update_query);
                
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

    public function get_average_sales($vitemcode,$start_date,$end_date)
    {
        
        $sql = "SELECT vitemcode,sum(ndebitqty>0) as sum FROM trn_salesdetail where vitemcode = '". $vitemcode ."' and date(LastUpdate) between '".$start_date."' and '".$end_date."'";
        // echo $sql;exit;
        $query = DB::connection('mysql_dynamic')->select($sql);
        return $query;
    }
    
    public function addItems($data = array()) {    
        // dd($data);
        $success =array();
        $error =array();
        $last_id = '';
        
        $vitemname = str_replace("'","\'",$data['vitemname']);
        
        if(isset($data) && count($data) > 0){
            // dd($data);
            if(isset($data['stores_hq'])){
                
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                }
                
                for($i=0;$i<count($stores); $i++){
                    try {
                        $sql_insert = '';
                        $department_code    = $this->addDepartment($stores[$i], $data['vdepcode'] );
                        $cat_code           = $this->addCategory($stores[$i], $data['vcategorycode']);
                        $supplier_code      = $this->addSupplier($stores[$i], $data['vsuppliercode']);
                        $vsize              = $this->addSize($stores[$i], $data['vsize']);
                        
                        if(isset($data['itemimage']) && !empty($data['itemimage'])){
                           
                            $img = addslashes($data['itemimage']);
                    
                            if(isset($data['subcat_id']) && !empty($data['subcat_id']) && ( trim($data['subcat_id']) != '' && trim($data['subcat_id']) != '--Select SubCategory--' )){
                                 $sub_cat_code       = $this->addSubcategory($stores[$i], $data['subcat_id']);
                                 $sql_insert = "INSERT INTO u".$stores[$i].".mst_item SET  webstore = '" . $data['webstore'] . "',vitemtype = '" . $data['vitemtype'] . "',vitemcode = '" . $data['vbarcode'] . "', vitemname = '" . $vitemname . "',vunitcode = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $cat_code . "', subcat_id = '" .$sub_cat_code. "', vdepcode = '".$department_code."', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', itemimage = '" . $img . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" .$vsize. "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                            }else{
                                $sql_insert = "INSERT INTO u".$stores[$i].".mst_item SET  webstore = '" . $data['webstore'] . "',vitemtype = '" . $data['vitemtype'] . "',vitemcode = '" . $data['vbarcode'] . "', vitemname = '" . $vitemname . "',vunitcode = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $cat_code . "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . (float)$data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', itemimage = '" . $img . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" .$vsize. "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                            }
                        }else{
                            if(isset($data['subcat_id']) && !empty($data['subcat_id']) && ( trim($data['subcat_id']) != '' &&  trim($data['subcat_id']) != '--Select SubCategory--' )){
                                
                                $sub_cat_code       = $this->addSubcategory($stores[$i], $data['subcat_id']);
                                $sql_insert = "INSERT INTO u".$stores[$i].".mst_item SET  webstore = '" . $data['webstore'] . "',vitemtype = '" . $data['vitemtype'] . "',vitemcode = '" . $data['vbarcode'] . "', vitemname = '" . $vitemname . "',vunitcode = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" .$cat_code. "', subcat_id = '" .$sub_cat_code. "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" .$vsize. "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                            }else{
                                
                                $sql_insert = "INSERT INTO u".$stores[$i].".mst_item SET  webstore = '" . $data['webstore'] . "',vitemtype = '" . $data['vitemtype'] . "',vitemcode = '" . $data['vbarcode'] . "', vitemname = '" . $vitemname . "',vunitcode = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" .$cat_code. "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . (float)$data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" .$vsize. "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                            }
                        }
                        
                            $result = DB::connection('mysql')->statement($sql_insert);
                        
    
                        // $return = self::orderBy('iitemid', 'DESC')->first();
                        
                        $item_lastod = DB::connection('mysql')->select("select * from u".$stores[$i].".mst_item order by iitemid DESC LIMIT 1 ");
                        
                        $last_id = $item_lastod[0]->iitemid;
                        // DB::connection('mysql')->update("UPDATE u".$stores[$i].".mst_item SET vitemcode = '" . $data['vbarcode'] . "' WHERE iitemid = '" . (int)$last_id . "'");
                        
                        if(isset($taxlist)){
                            foreach($taxlist as $list){
                             $sql="INSERT INTO mst_item_tax SET item_id='" . (int)$last_id . "', tax_id='".$list."'" ;
                             DB::connection('mysql_dynamic')->insert($sql);
                            }
                            
                        }
                        //mst plcb item
                        
                        //=============20-11-2020========
                        // the auto generated codes to a sequence starting with 'AP00' sothat way we can distinguish which is auto generated
                        $vvendoritemcode = 'AP00'.$last_id;
                        DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".mst_itemvendor SET  iitemid = '" . (int)$last_id . "',`ivendorid` = '" . (int)$data['vsuppliercode'] . "',`vvendoritemcode` = '" . $vvendoritemcode . "', SID = '" . (int)(session()->get('sid')) . "'");
                        
                        if(empty($data['iqtyonhand'])){
                            $data['iqtyonhand'] = 0;
                        }

                        if(isset($data['options_data']) && count($data['options_data']) > 0){
    
                            DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".mst_item_size SET  item_id = '". (int)$last_id ."',unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."',SID = '" . (int)$stores[$i]."'");
    
                            DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".mst_plcb_item SET  item_id = '". (int)$last_id ."',bucket_id = '". (int)$data['options_data']['bucket_id'] ."',prev_mo_beg_qty = '". $data['iqtyonhand'] ."',prev_mo_end_qty = '". $data['iqtyonhand'] ."',malt = '". (int)$data['options_data']['malt'] ."',SID = '" . (int)$stores[$i]."'");
    
                        }else{
                            $checkexist_mst_item_size = DB::connection('mysql')->select("SELECT * FROM u".$stores[$i].".mst_item_size WHERE item_id='" . (int)$last_id . "'");
    
                            if(count($checkexist_mst_item_size) > 0){
    
                                DB::connection('mysql')->insert("INSERT u".$stores[$i].".INTO mst_delete_table SET  TableName = 'mst_item_size',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_item_size['id'] . "',SID = '" . (int)$stores[$i]."'");
    
                                DB::connection('mysql')->select("DELETE FROM u".$stores[$i].".mst_item_size WHERE id='" . (int)$checkexist_mst_item_size['id'] . "'");
    
                            }
    
                            $checkexist_mst_plcb_item = DB::connection('mysql')->select("SELECT * FROM u".$stores[$i].".mst_plcb_item WHERE item_id='" . (int)$last_id . "'");
    
                            if(count($checkexist_mst_plcb_item) > 0){
    
                                DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".mst_delete_table SET  TableName = 'mst_plcb_item',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_plcb_item['id'] . "',SID = '" . (int)$stores[$i]."'");
    
                                DB::connection('mysql')->select("DELETE FROM u".$stores[$i].".mst_plcb_item WHERE id='" . (int)$checkexist_mst_plcb_item['id'] . "'");
    
                            }
                        }
                        
                        //mst plcb item
                        
                        if(isset($data['iitemgroupid'])){
    
                            $delete_ids = DB::connection('mysql')->select("SELECT `Id` FROM u".$stores[$i].".itemgroupdetail WHERE vsku='" . $data['vbarcode'] . "'");
    
                            if(count($delete_ids) > 0){
                                DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)$stores[$i]."'");
                            }
    
                            DB::connection('mysql')->select("DELETE FROM u".$stores[$i].".itemgroupdetail WHERE vsku='" . $data['vbarcode'] . "'");
    
                            if($data['iitemgroupid'] != ''){
                                DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".itemgroupdetail SET  iitemgroupid = '" . (int)$data['iitemgroupid'] . "', vsku='". $data['vbarcode'] ."',vtype='Product',SID = '" . (int)$stores[$i] . "' ");
                            }
    
                        }
                        
                        if($data['vitemtype'] == 'Lot Matrix'){
                            $vpackname = 'Case';
                            $vdesc = 'Case';
    
                            $nunitcost = $data['nunitcost'];
                            if($nunitcost == ''){
                                $nunitcost = 0;
                            }
    
                            $ipack = $data['nsellunit'];
                            if($data['nsellunit'] == ''){
                                $ipack = 0;
                            }
    
                            $npackprice = $data['dunitprice'];
                            if($data['dunitprice'] == ''){
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
    
                            DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".mst_itempackdetail SET  iitemid = '" . (int)$last_id . "',`vbarcode` = '" . $data['vbarcode'] . "',`vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`ipack` = '" . (int)$ipack . "',`iparentid` = '" . (int)$iparentid . "',`npackcost` = '" . $npackcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "', SID = '" . (int)$stores[$i]. "'");
                        }
                        
                    }
                    catch ( QueryException $e) {
                        // not a MySQL exception
                      
                        if (strpos($e->getMessage(), "Unknown column 'vtax3'") !== false) {
                            // $error['error_tax3'] = 'Tax3 is not supported by this Store. Please contact Technical Support.';
                            $error['error_tax3'] = 'Please update POS to Version 3.0.7';
                        } else {
                            $error['error'] = $e->getMessage(); 
                        }
                        return $error; 
                    }
                }
            }else {
               
                try {
                    $sql_insert = '';   
                    if(isset($data['itemimage']) && !empty($data['itemimage'])){
                        $img = addslashes($data['itemimage']);
                          
                        if(isset($data['subcat_id']) && !empty($data['subcat_id']) && ( trim($data['subcat_id']) != '' && trim($data['subcat_id']) != '--Select SubCategory--' )){
                           
                            $sql_insert = "INSERT INTO mst_item SET  webstore = '" . $data['webstore'] . "',`vitemtype` = '" . $data['vitemtype'] . "',`vitemname` = '" . $vitemname . "',`vunitcode` = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $data['vcategorycode'] . "', subcat_id = '" . $data['subcat_id'] . "', vdepcode = '" . $data['vdepcode'] . "', vsuppliercode = '" . $data['vsuppliercode'] . "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', itemimage = '" . $img . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" . $data['vsize'] . "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                        }else{
                            
                            $sql_insert = "INSERT INTO mst_item SET  webstore = '" . $data['webstore'] . "',`vitemtype` = '" . $data['vitemtype'] . "',`vitemname` = '" . $vitemname . "',`vunitcode` = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $data['vcategorycode'] . "', vdepcode = '" . $data['vdepcode'] . "', vsuppliercode = '" . $data['vsuppliercode'] . "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', itemimage = '" . $img . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" . $data['vsize'] . "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                        }
                    }else{
                         
                        if(isset($data['subcat_id']) && !empty($data['subcat_id']) && ( trim($data['subcat_id']) != '' &&  trim($data['subcat_id']) != '--Select SubCategory--' )){
                            $sql_insert = "INSERT INTO mst_item SET  webstore = '" . $data['webstore'] . "',`vitemtype` = '" . $data['vitemtype'] . "',`vitemname` = '" . $vitemname . "',`vunitcode` = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $data['vcategorycode'] . "', subcat_id = '" . $data['subcat_id'] . "', vdepcode = '" . $data['vdepcode'] . "', vsuppliercode = '" . $data['vsuppliercode'] . "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" . $data['vsize'] . "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                        }else{
                            
                            $sql_insert = "INSERT INTO mst_item SET  webstore = '" . $data['webstore'] . "',`vitemtype` = '" . $data['vitemtype'] . "',`vitemname` = '" . $vitemname . "',`vunitcode` = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $data['vcategorycode'] . "', vdepcode = '" . $data['vdepcode'] . "', vsuppliercode = '" . $data['vsuppliercode'] . "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', visinventory = '" . $data['visinventory'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" . $data['vsize'] . "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                        }
                    }
                    // dd($sql_insert);
                    
                    DB::connection('mysql_dynamic')->insert($sql_insert);
                    
                    $return = self::orderBy('iitemid', 'DESC')->first();
                    
                    $last_id = $return->iitemid;
                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vitemcode = '" . $data['vbarcode'] . "' WHERE iitemid = '" . (int)$last_id . "'");
                    
                    if(isset($taxlist)){
                        foreach($taxlist as $list){
                         $sql="INSERT INTO mst_item_tax SET item_id='" . (int)$last_id . "', tax_id='".$list."'" ;
                         DB::connection('mysql_dynamic')->insert($sql);
                        }
                        
                        
                    }
                   
                    //=============20-11-2020========
                    // the auto generated codes to a sequence starting with 'AP00' sothat way we can distinguish which is auto generated
                    $vvendoritemcode = 'AP00'.$last_id;
                    DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)$last_id . "',`ivendorid` = '" . (int)$data['vsuppliercode'] . "',`vvendoritemcode` = '" . $vvendoritemcode . "', SID = '" . (int)(session()->get('sid')) . "'");

                    //mst plcb item

                    if(empty($data['iqtyonhand'])){
                        $data['iqtyonhand'] = 0;
                    }
                    
                    if(isset($data['options_data']) && count($data['options_data']) > 0){
                        
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_item_size SET  item_id = '". (int)$last_id ."',unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."',SID = '" . (int)(session()->get('sid'))."'");
                        
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_plcb_item SET  item_id = '". (int)$last_id ."',bucket_id = '". (int)$data['options_data']['bucket_id'] ."',prev_mo_beg_qty = '". $data['iqtyonhand'] ."',prev_mo_end_qty = '". $data['iqtyonhand'] ."',malt = '". (int)$data['options_data']['malt'] ."',SID = '" . (int)(session()->get('sid'))."'");
                        
                    }else{
                        $checkexist_mst_item_size = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id='" . (int)$last_id . "'");
                        
                        if(count($checkexist_mst_item_size) > 0){
                            
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_item_size',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_item_size['id'] . "',SID = '" . (int)(session()->get('sid'))."'");
                            
                            DB::connection('mysql_dynamic')->select("DELETE FROM mst_item_size WHERE id='" . (int)$checkexist_mst_item_size['id'] . "'");
                            
                        }
                            
                        $checkexist_mst_plcb_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE item_id='" . (int)$last_id . "'");
                        
                        if(count($checkexist_mst_plcb_item) > 0){
                            
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_plcb_item',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_plcb_item['id'] . "',SID = '" . (int)(session()->get('sid'))."'");
                            
                            DB::connection('mysql_dynamic')->select("DELETE FROM mst_plcb_item WHERE id='" . (int)$checkexist_mst_plcb_item['id'] . "'");
                            
                        }
                    }
                    
                    //mst plcb item
                    
                    if(isset($data['iitemgroupid'])){
                        
                        $delete_ids = DB::connection('mysql_dynamic')->select("SELECT `Id` FROM itemgroupdetail WHERE vsku='" . $data['vbarcode'] . "'");
                        
                        if(count($delete_ids) > 0){
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)(session()->get('sid'))."'");
                        }
                            
                        DB::connection('mysql_dynamic')->select("DELETE FROM itemgroupdetail WHERE vsku='" . $data['vbarcode'] . "'");

                        if($data['iitemgroupid'] != ''){
                            DB::connection('mysql_dynamic')->insert("INSERT INTO itemgroupdetail SET  iitemgroupid = '" . (int)$data['iitemgroupid'] . "', vsku='". $data['vbarcode'] ."',vtype='Product',SID = '" . (int)(session()->get('sid')) . "' ");
                        }

                    }
                    
                    if($data['vitemtype'] == 'Lot Matrix'){
                        $vpackname = 'Case';
                        $vdesc = 'Case';

                        $nunitcost = $data['nunitcost'];
                        if($nunitcost == ''){
                            $nunitcost = 0;
                        }

                        $ipack = $data['nsellunit'];
                        if($data['nsellunit'] == ''){
                            $ipack = 0;
                        }

                        $npackprice = $data['dunitprice'];
                        if($data['dunitprice'] == ''){
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

                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itempackdetail SET  iitemid = '" . (int)$last_id . "',`vbarcode` = '" . $data['vbarcode'] . "',`vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`ipack` = '" . (int)$ipack . "',`iparentid` = '" . (int)$iparentid . "',`npackcost` = '" . $npackcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "', SID = '" . (int)(session()->get('sid')) . "'");
                    }
                    
                }
                // catch ( \MySQLException $e) {
                //     // other mysql exception (not duplicate key entry)
                //     $error['error'] = $e->getMessage(); 
                //     return $error; 
                // }
                catch ( QueryException $e) {
                    // not a MySQL exception
                  
                    if (strpos($e->getMessage(), "Unknown column 'vtax3'") !== false) {
                        // $error['error_tax3'] = 'Tax3 is not supported by this Store. Please contact Technical Support.';
                        $error['error_tax3'] = 'Please update POS to Version 3.0.7';
                    } else {
                        $error['error'] = $e->getMessage(); 
                    }
                    return $error; 
                }
            }
        }
        
        if(isset($data['stores_hq'])){
            
            if($data['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $data['stores_hq']);
            }
            for($i=0;$i<count($stores); $i++){
                
                 
                $item_lastod = DB::connection('mysql')->select("select * from u".$stores[$i].".mst_item order by iitemid DESC LIMIT 1 ");
                
                $last_id = $item_lastod[0]->iitemid;
                
                // Opening Qoh update
                $query = DB::connection('mysql')->select("SELECT ipiid FROM u".$stores[$i].".trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
                if(!empty($query)){
                    $ipid = $query[0]->ipiid;
                    $vrefnumber = str_pad($ipid+1,9,"0",STR_PAD_LEFT);
                }else{
                    $vrefnumber = str_pad(1,9,"0",STR_PAD_LEFT);
                }
                DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".trn_physicalinventory SET  vrefnumber= $vrefnumber, estatus='Close',dcreatedate=CURRENT_TIMESTAMP, vtype ='Opening QoH', SID = '" . (int)$stores[$i]."'");
                $return = DB::connection('mysql')->select("SELECT ipiid FROM u".$stores[$i].".trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
                $ipiid = $return[0]->ipiid;
                
                DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',
                                vitemid = '" . (int)$last_id . "',
                                vitemname ='" . $vitemname . "',
                                vunitcode = '" . $data['vunitcode']. "',
                                ndebitqty= '" . (int)$data['iqtyonhand']. "', 
                                vbarcode = '" . $data['vbarcode']. "', 
                                SID = '" . (int)$stores[$i]."'
                ");
                
                // Opening Qoh  end
                $success['success'] = 'Successfully Added Item';
                $success['iitemid'] = $last_id;
                return $success;  
                
            }
            
        }else{
                $return = self::orderBy('iitemid', 'DESC')->first();
                $last_id = $return->iitemid;
                // Opening Qoh update
                $query = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
                if(!empty($query)){
                    $ipid = $query[0]->ipiid;
                    $vrefnumber = str_pad($ipid+1,9,"0",STR_PAD_LEFT);
                }else{
                    $vrefnumber = str_pad(1,9,"0",STR_PAD_LEFT);
                }
                
                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventory SET  vrefnumber= $vrefnumber, estatus='Close',dcreatedate=CURRENT_TIMESTAMP, vtype ='Opening QoH', SID = '" . (int)(session()->get('sid'))."'");
                $return = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
                $ipiid = $return[0]->ipiid;
                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',
                                 vitemid = '" . (int)$last_id . "',
                                 vitemname ='" . $vitemname . "',
                                 vunitcode = '" . $data['vunitcode']. "',
                                 ndebitqty= '" . (int)$data['iqtyonhand']. "',
                                 vbarcode = '" . $data['vbarcode']. "', 
                                 SID = '" . (int)(session()->get('sid'))."'
                                 ");
                // Opening Qoh  end
                $success['success'] = 'Successfully Added Item';
                $success['iitemid'] = $last_id;
                return $success;
        }
        
        
    }

    public function get_promotion_by_item( $vbarcode ){
        
        if(isset($vbarcode)){
            
            $date = date('Y-m-d');
            
            // $sql_query =    "SELECT tpd.prom_id, tp.prom_name FROM trn_prom_details tpd join trn_promotions tp on (tpd.prom_id = tp.prom_id) 
            //                 where barcode = '".$vbarcode."' AND tp.status = 'Active' and DATE_FORMAT(tp.end_date, '%Y-%m-%d') <= '".$date."' GROUP BY tp.prom_id;";
            $sql_query =    "SELECT tpd.prom_id, tp.prom_name, tp.category, DATE_FORMAT(tp.end_date, '%Y-%m-%d') as end_date FROM trn_prom_details tpd join trn_promotions tp on (tpd.prom_id = tp.prom_id) 
                            where barcode = '".$vbarcode."' AND tp.status = 'Active' GROUP BY tp.prom_id;";
            
            $query = DB::connection('mysql_dynamic')->select($sql_query);
            
            $data = array();
            
            foreach($query as $val){
                
                $obj = new \stdClass;
                
                if($val->category == 'Time Bound'){
                    if($val->end_date <= $date){
                        $obj->prom_id = $val->prom_id;
                        $obj->prom_name = $val->prom_name; 
                    }
                    
                }else{
                    $obj->prom_id = $val->prom_id;
                    $obj->prom_name = $val->prom_name;
                }
                
                if(!empty((array)$obj)){
                    $data[] = $obj;
                }
            }
            return $data;
        }
    }

    public function gettaxinfo()
    {
        $sql="SHOW COLUMNS FROM mst_item LIKE 'vtax3'";
        $query = DB::connection('mysql_dynamic')->select($sql);
         
        $data = isset($query[0])?(array)$query[0]:[];
            return $data;
         
    }

    public function getItemUnitData($iitemid){

        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id='". (int)$iitemid ."'");

        $data = isset($query[0])?(array)$query[0]:[];
            return $data;
    }

    public function getItemBucketData($iitemid){
        $sql = "SELECT * FROM mst_plcb_item WHERE bucket_id != 13 and item_id = '".$iitemid."' ";
        $query = DB::connection('mysql_dynamic')->select($sql);
        
        $data = isset($query[0])?(array)$query[0]:[];
            return $data;
    }

    public function hqStoreUpdateItemVendor($data = array(), $stores_hq = null){
        $success =array();
        $error =array();
        $query = array();
        // echo $stores_hq;
        // dd($data);
        if(isset($data) && count($data) > 0){
            $vbarcode = DB::connection('mysql_dynamic')->select("SELECT vbarcode FROM mst_item where iitemid = '". (int)$data['iitemid'] ."' ");
            $vcompanyName = DB::connection('mysql_dynamic')->select("SELECT vcompanyname FROM mst_supplier where isupplierid = '".$data['ivendorid']."' ");
            
            if($stores_hq === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                
                if(is_array($stores_hq)){
                    $stores = $stores_hq;
                }else{
                    $stores = explode(",", $stores_hq);
                }
            } 
            foreach($stores as $store){
                foreach($vbarcode as $barcode){
                    $item_id = DB::connection('mysql')->select("SELECT iitemid FROM u".$store.".mst_item where vbarcode = '".$barcode->vbarcode."' ");
                } 
                foreach($vcompanyName as $vcompname){
                    $isup_id = DB::connection('mysql')->select("SELECT isupplierid  FROM u".$store.".mst_supplier where vcompanyname = '".$vcompname->vcompanyname."' ");
                }
                $sup_id = $isup_id[0]->isupplierid;
                foreach($item_id as $iitemId){
                    // $mst_itemvendor = DB::connection('mysql_dynamic')->select("SELECT * from mst_itemvendor where Id = '".$data['Id']."' ");
                    // foreach($mst_itemvendor as $itemvendor){
                    //     $itmevendorcode =    $itemvendor->vvendoritemcode;
                    // }
                    try {
                        $udatequery= "UPDATE u".$store.".mst_itemvendor SET  vvendoritemcode = '".$data['vvendoritemcode']."' WHERE iitemid= '".(int)$iitemId->iitemid."' AND ivendorid='".(int)$sup_id."' ";
                    
                        DB::connection('mysql')->update($udatequery);
                        $success['success'] = 'Successfully Updated Item Vendor';
                        
                    }
                    catch (QueryException $e) {
                        $error['error'] = $e->getMessage(); 
                        return $error; 
                    }
                }
            }
        }
        return $success;
    }
    
    public function addUpdateItemVendor($data = array(), $stores_hq = null) {
        $success =array();
        $error =array();
        $data['stores_hq'] = $stores_hq;

        if(isset($data) && count($data) > 0){
            $vbarcode = DB::connection('mysql_dynamic')->select("SELECT vbarcode FROM mst_item where iitemid = '". (int)$data['iitemid'] ."' ");
            $vcompanyName = DB::connection('mysql_dynamic')->select("SELECT vcompanyname FROM mst_supplier where isupplierid = '".$data['ivendorid']."' ");
            
            
            if(isset($data['stores_hq'])){
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    if(is_array($data['stores_hq'])){
                        $stores = $data['stores_hq'];
                    }else{
                        $stores = explode(",", $data['stores_hq']);
                    }
                } 
                $storescount = count($stores);
                foreach($stores as $store){
                    
                    foreach($vbarcode as $barcode){
                        $item_id = DB::connection('mysql')->select("SELECT iitemid FROM u".$store.".mst_item where vbarcode = '".$barcode->vbarcode."' ");
                    } 
                    
                    foreach($vcompanyName as $vcompname){
                        $isup_id = DB::connection('mysql')->select("SELECT isupplierid  FROM u".$store.".mst_supplier where vcompanyname = '".$vcompname->vcompanyname."' ");
                    }
                    
                    $sup_id = $isup_id[0]->isupplierid;
                    
                    foreach($item_id as $iitemId){
                        try {
                            $itemvendor_exist = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_itemvendor WHERE iitemid= '". (int)$iitemId->iitemid ."' AND ivendorid='". (int)$sup_id ."' AND Id='". (int)$data['Id'] ."'");
                            
                            $sql = "select ivendorid from u".$store.".mst_itemvendor where vvendoritemcode ='" . $data['vvendoritemcode'] . "' AND iitemid= '". (int)$iitemId->iitemid ."'";
                            $result = DB::connection('mysql')->select($sql);
                            if(count($result) > 0 && count($itemvendor_exist) != 0){
                                      $error['error'] = 'Vendor Item Code already exist';
                                      return $error; 
                            } 
                            else{
                                if(count($itemvendor_exist) > 0){
                                    DB::connection('mysql')->update("UPDATE u".$store.".mst_itemvendor SET  vvendoritemcode = '" . $data['vvendoritemcode'] . "' WHERE iitemid= '". (int)$iitemId->iitemid ."' AND ivendorid='". (int)$sup_id ."' AND Id='". (int)$data['Id'] ."'");
                                    $success['success'] = 'Successfully Updated Item Vendor';
                                    
                                }else{
                                    
                                    $insert_query = "INSERT INTO u".$store.".mst_itemvendor SET  iitemid = '" . (int)$iitemId->iitemid . "', ivendorid = '" . (int)$sup_id . "', vvendoritemcode = '" . $data['vvendoritemcode'] . "', SID = '" . (int)(session()->get('sid')) . "'";
                                    DB::connection('mysql')->insert($insert_query);
                                    
                                    $update_query = "UPDATE u".$store.".mst_item SET  vsuppliercode = '" . (int)$sup_id . "' WHERE iitemid= '". (int)$iitemId->iitemid ."'";
                                    $update = DB::connection('mysql')->update($update_query);

                                    $success['success'] = 'Successfully Added Item Vendor';
                                    
                                  
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
                return $success;
            }else{
                 try {
                    $itemvendor_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemvendor WHERE iitemid= '". (int)$data['iitemid'] ."' AND ivendorid='". (int)$data['ivendorid'] ."' AND Id='". (int)$data['Id'] ."'");
                    
                     $sql = "select ivendorid from mst_itemvendor where vvendoritemcode ='" . $data['vvendoritemcode'] . "' AND iitemid= '". (int)$data['iitemid'] ."'";
                     $result = DB::connection('mysql_dynamic')->select($sql);
                    if(count($result)>0 && count($itemvendor_exist)!=0){
                               
                              $error['error'] = 'Vendor Item Code already exist';
                              return $error; 
                              
                              
                    } 
                    else{
                        if(count($itemvendor_exist) > 0){
                            
                            DB::connection('mysql_dynamic')->update("UPDATE mst_itemvendor SET  `vvendoritemcode` = '" . $data['vvendoritemcode'] . "' WHERE iitemid= '". (int)$data['iitemid'] ."' AND ivendorid='". (int)$data['ivendorid'] ."' AND Id='". (int)$data['Id'] ."'");
                            $success['success'] = 'Successfully Updated Item Vendor';
                            return $success;
                        }else{
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)$data['iitemid'] . "',`ivendorid` = '" . (int)$data['ivendorid'] . "',`vvendoritemcode` = '" . $data['vvendoritemcode'] . "', SID = '" . (int)(session()->get('sid')) . "'");
                            DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  `vsuppliercode` = '" . (int)$data['ivendorid'] . "' WHERE iitemid= '". (int)$data['iitemid'] ."'");
                            $success['success'] = 'Successfully Added Item Vendor';
                            return $success;
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
        
    }

    // public function addUpdateItemVendor($data = array()) {

    //     $success =array();
    //     $error =array();

    //     if(isset($data) && count($data) > 0){
    //           try {
    //                 $itemvendor_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemvendor WHERE iitemid= '". (int)$data['iitemid'] ."' AND ivendorid='". (int)$data['ivendorid'] ."' AND Id='". (int)$data['Id'] ."'");
                    
    //                  $sql = "select ivendorid from mst_itemvendor where vvendoritemcode ='" . $data['vvendoritemcode'] . "' AND iitemid= '". (int)$data['iitemid'] ."'";
    //                  $result = DB::connection('mysql_dynamic')->select($sql);
    //                 if(count($result)>0 && count($itemvendor_exist)!=0){
                               
    //                           $error['error'] = 'Vendor Item Code already exist';
    //                           return $error; 
                              
                              
    //                 } 
    //                 else{
    //                     if(count($itemvendor_exist) > 0){
                            
    //                         DB::connection('mysql_dynamic')->update("UPDATE mst_itemvendor SET  `vvendoritemcode` = '" . $data['vvendoritemcode'] . "' WHERE iitemid= '". (int)$data['iitemid'] ."' AND ivendorid='". (int)$data['ivendorid'] ."' AND Id='". (int)$data['Id'] ."'");
    //                         $success['success'] = 'Successfully Updated Item Vendor';
    //                         return $success;
    //                     }else{
    //                         DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)$data['iitemid'] . "',`ivendorid` = '" . (int)$data['ivendorid'] . "',`vvendoritemcode` = '" . $data['vvendoritemcode'] . "', SID = '" . (int)(session()->get('sid')) . "'");
    //                         DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  `vsuppliercode` = '" . (int)$data['ivendorid'] . "' WHERE iitemid= '". (int)$data['iitemid'] ."'");
    //                         $success['success'] = 'Successfully Added Item Vendor';
    //                       return $success;
    //                     }
    //                 }

    //             }
                
    //             catch (QueryException $e) {
    //                 // not a MySQL exception
                   
    //                 $error['error'] = $e->getMessage(); 
    //                 return $error; 
    //             }
    //     }

    //     // return $success;
    // }
    

    public function addItemAliasCode($data = array()) {

        $success =array();
        $error =array();

        if(isset($data) && count($data) > 0){
            
            if(isset($data['stores_hq'])){
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                } 
                $storescount = count($stores);
                for($i=0; $i < $storescount; $i++){
                    // dd($stores[$i]);
                    $item_sku = DB::connection('mysql')->select("SELECT * FROM u".$stores[$i].".mst_item WHERE vbarcode= '". ($data['valiassku']) ."' ");
                    $item_sku = isset($item_sku[0])?(array)$item_sku[0]:[];
            
                    if(count($item_sku) > 0){
                        $error['error'] = 'In u'.$stores[$i].' Alias Code Already Exist';
                        return $error;
                    }else{
                        $item_valiassku = DB::connection('mysql')->select("SELECT * FROM u".$stores[$i].".mst_itemalias WHERE valiassku= '". ($data['valiassku']) ."' ");
                        $item_valiassku = isset($item_valiassku[0])?(array)$item_valiassku[0]:[];

                        if(count($item_valiassku) > 0){
                            $error['error'] = 'In u'.$stores[$i].' Alias Code Already Exist';
                            return $error;
                        }else{
                            try {
                                DB::connection('mysql')->insert("INSERT INTO u".$stores[$i].".mst_itemalias SET  vitemcode = '" . ($data['vitemcode']) . "',`vsku` = '" . ($data['vsku']) . "',`valiassku` = '" . ($data['valiassku']) . "', SID = '" . (int)(session()->get('sid')) . "'");
                                $success['success'] = 'Successfully Added Alias Code';
                            }
                            catch (QueryException $e) {
                                // not a MySQL exception
                                $error['error'] = $e->getMessage(); 
                                return $error; 
                            }
                        }
                    } 
                    
                }
            }else{
                $item_sku = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE vbarcode= '". ($data['valiassku']) ."' ");
                $item_sku = isset($item_sku[0])?(array)$item_sku[0]:[];
                
                if(count($item_sku) > 0){
                    $error['error'] = 'Alias Code Already Exist';
                    return $error;
                }else{
                    $item_valiassku = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemalias WHERE valiassku= '". ($data['valiassku']) ."' ");
                    $item_valiassku = isset($item_valiassku[0])?(array)$item_valiassku[0]:[];
    
                    if(count($item_valiassku) > 0){
                        $error['error'] = 'Alias Code Already Exist';
                        return $error;
                    }else{
                        try {
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemalias SET  vitemcode = '" . ($data['vitemcode']) . "',`vsku` = '" . ($data['vsku']) . "',`valiassku` = '" . ($data['valiassku']) . "', SID = '" . (int)(session()->get('sid')) . "'");
    
                                $success['success'] = 'Successfully Added Alias Code';
                        }
                        
                        catch (QueryException $e) {
                            // not a MySQL exception
                           
                            $error['error'] = $e->getMessage(); 
                            return $error; 
                        }
                    }
                }  
            }
            
        }

        return $success;
    }

    public function deleteItemAliasCode($data = array()) {
        // dd($data);
        $success =array();
        $error =array();

        if(isset($data) && count($data) > 0){
            
            foreach($data['data'] as $value){
                
                if(isset($data['stores_hq'])){
                    foreach($data['stores_hq'] as $store){
                        $itemaliasdetails = DB::connection('mysql_dynamic')->select("Select * FROM mst_itemalias WHERE iitemaliasid = '".(int)$value."' ");
                        // foreach($itemaliasdetails as $alias){
                        
                        if(isset($itemaliasdetails[0])){
                            // dd(__LINE__);
                            $vbarcode = $itemaliasdetails[0]->vsku;
                            $valiassku = $itemaliasdetails[0]->valiassku;
                            
                        }
                        // echo $store.'---';
                        
                        try {
                            $alias_ids = DB::connection('mysql')->select(" Select * FROM u".$store.".mst_itemalias WHERE vsku = '".$vbarcode."' AND valiassku = '".$valiassku."' ");
                            
                            if(isset($alias_ids[0])){
                                $iitemaliasid = $alias_ids[0]->iitemaliasid;
                                DB::connection('mysql')->statement("DELETE FROM u".$store.".mst_itemalias WHERE iitemaliasid='" . (int)$iitemaliasid . "'");
                                DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_delete_table SET  TableName = 'mst_itemalias', Action = 'delete',TableId = '" . (int)$iitemaliasid . "',SID = '" . (int)($store)."'");
            
                            }
                        }
                        
                        catch (QueryException $e) {
                            // not a MySQL exception
                            $error['error'] = $e->getMessage(); 
                            return $error; 
                        }
                    }
                    
                }else {
                    
                    try {
                        DB::connection('mysql_dynamic')->statement("DELETE FROM mst_itemalias WHERE iitemaliasid='" . (int)$value . "'");
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_itemalias', Action = 'delete', TableId = '" . (int)$value . "', SID = '" . (int)(session()->get('sid'))."'");
                    }
                    
                    catch (QueryException $e) {
                        // not a MySQL exception
                        // dd($e->getMessage());
                        $error['error'] = $e->getMessage(); 
                        
                        return $error; 
                    }
                }
            }  
        }
        $success['success'] = 'Successfully Deleted Alias Code';
        return $success;
    }

    public function addItemLotMatrix($data = array()) {
        // dd($data);
        $success =array();
        $error =array();

        if(isset($data) && count($data) > 0){
            if(isset($data['stores_hq_lot'])){
                
                foreach($data['stores_hq_lot'] as $store){
                    $item_data = DB::connection('mysql')->select("select * from u".$store.".mst_item where vbarcode = '".$data['vbarcode']."' ");
                    foreach($item_data as $item){
                        $iitem_id = $item->iitemid;
                    }
                    
                    try {
                        $item_lotmatrix = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_itempackdetail WHERE iitemid = '".(int)($iitem_id)."' and vpackname= '". ($data['vpackname']) ."' ");
                        // $item_lotmatrix = isset($item_lotmatrix[0])?(array)$item_lotmatrix[0]:[];
                        
                        if(count($item_lotmatrix) > 0){
                            $error['error'] = 'Item Pack Already Exist';
                            return $error;
                        }else{
                            DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_itempackdetail SET  iitemid = '" . (int)($iitem_id) . "', vbarcode = '" . ($data['vbarcode']) . "',vpackname = '" . ($data['vpackname']) . "',vdesc = '" . ($data['vdesc']) . "',ipack = '" . (int)($data['ipack']) . "',npackcost = '" . ($data['npackcost']) . "',npackprice = '" . ($data['npackprice']) . "',isequence = '" . (int)($data['isequence']) . "',npackmargin = '" . ($data['npackmargin']) . "', SID = '" . (int)$store . "'");
                            
                            $success['success'] = 'Successfully Added Item Pack';
                        }
                    }
                    
                    catch (QueryException $e) {
                        // not a MySQL exception
                        
                        $error['error'] = $e->getMessage(); 
                        return $error; 
                    }
                }
                
            }else{
                try {
                    $item_lotmatrix = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid = '".(int)($data['iitemid'])."' and vpackname= '". ($data['vpackname']) ."' ");
                    // $item_lotmatrix = isset($item_lotmatrix[0])?(array)$item_lotmatrix[0]:[];
                    
                    if(count($item_lotmatrix) > 0){
                        $error['error'] = 'Item Pack Already Exist';
                        return $error;
                    }else{
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itempackdetail SET  iitemid = '" . (int)($data['iitemid']) . "',`vbarcode` = '" . ($data['vbarcode']) . "',`vpackname` = '" . ($data['vpackname']) . "',`vdesc` = '" . ($data['vdesc']) . "',`ipack` = '" . (int)($data['ipack']) . "',`npackcost` = '" . ($data['npackcost']) . "',`npackprice` = '" . ($data['npackprice']) . "',`isequence` = '" . (int)($data['isequence']) . "',`npackmargin` = '" . ($data['npackmargin']) . "', SID = '" . (int)(session()->get('sid')) . "'");
                        
                        $success['success'] = 'Successfully Added Item Pack';
                    }
                }
                
                catch (QueryException $e) {
                    // not a MySQL exception
                    
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                } 
            }
        }

        return $success;
    }

    public function editlistLotMatrixItems($datas = array(), $stores_hq = null ) {
        
        $success =array();
        $error =array();
        // dd($datas);
        if(isset($datas) && count($datas) > 0){
            if(isset($stores_hq)){
                if($stores_hq === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $stores_hq);
                }
                $pack_idet_id = DB::connection('mysql_dynamic')->select("select * from mst_itempackdetail where  idetid= '".(int)$datas['idetid']."' ");
                if(isset($pack_idet_id[0])){
                   $idet_id =  $pack_idet_id[0]->idetid;
                   $vpackname = $pack_idet_id[0]->vpackname;
                   $vbarcode = $pack_idet_id[0]->vbarcode;
                }
                $item_data = DB::connection('mysql_dynamic')->select("select * from mst_item where iitemid = '".(int)$datas['iitemid']."' ");
                if(isset($item_data[0])){
                    $item_vbarcode = $item_data[0]->vbarcode;
                }
                foreach($stores as $store){
                    $item_data_ids = DB::connection('mysql')->select("select * from u".$store.".mst_item where  vbarcode = '".$item_vbarcode."' ");
                    if(isset($item_data_ids[0])){
                        $iitem_id = $item_data_ids[0]->iitemid;
                    }
                    $select_query = "SELECT * FROM u".$store.".mst_itempackdetail WHERE iitemid = '".(int)($iitem_id)."' AND vpackname= '".$vpackname."' AND vbarcode = '".$item_vbarcode."'  ";
                    $item_lotmatrix = DB::connection('mysql')->select($select_query);
                    if(isset($item_lotmatrix[0])){
                       $idet_pack_id =  $item_lotmatrix[0]->idetid;
                    }
                    try {
                        $sql_query = "UPDATE u".$store.".mst_itempackdetail SET npackcost = '".$datas['npackcost']."', 
                                        vpackname = '" .$datas['vpackname']. "', vdesc = '" .$datas['vdesc']. "',
                                        npackprice = '" .$datas['npackprice']. "', isequence ='".$datas['isequence']."',
                                        npackmargin = '" .$datas['npackmargin']. "' 
                                        WHERE  iitemid = '".(int)($iitem_id)."' AND idetid = '".(int)($idet_pack_id)."' ";
                        DB::connection('mysql')->update($sql_query);
                    }
                    catch (QueryException $e) {
                        $error['error'] = $e->getMessage(); 
                        return $error; 
                    } 
                }
            }else{
                try {
                    $sql_query = "UPDATE mst_itempackdetail SET npackcost = '".$datas['npackcost']."', vpackname = '" .$datas['vpackname']. "', vdesc = '" .$datas['vdesc']. "',npackprice = '" .$datas['npackprice']. "',npackmargin = '" .$datas['npackmargin']. "', isequence ='".$datas['isequence']."' WHERE iitemid='". (int)($datas['iitemid']) ."' AND idetid='". (int)($datas['idetid']) ."'";
                    DB::connection('mysql_dynamic')->update($sql_query);
                }
                catch (QueryException $e) {
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                } 
            }
        }
        

        $success['success'] = 'Successfully Updated Lot Item';
        return $success;
    }

    public function deleteItemLotmatrix($data = array()) {
        $success =array();
        $error =array();
        if(isset($data) && count($data) > 0){
            if(isset($data['stores_hq'])){
                foreach($data['data'] as $value){
                    $itempackdetails = DB::connection('mysql_dynamic')->select("select * FROM mst_itempackdetail WHERE idetid='" . (int)$value . "'");
                    foreach($itempackdetails as $packs){
                        $vbarcode = $packs->vbarcode;
                        $vpackname = $packs->vpackname;
                    }
                    foreach($data['stores_hq'] as $store){
                        $itempackdetails_ids = DB::connection('mysql')->select("select * FROM u".$store.".mst_itempackdetail WHERE vbarcode = '".$packs->vbarcode."'  AND vpackname = '".$vpackname."' ");
                        foreach($itempackdetails_ids as $ids_packs){
                            $idetid =  $ids_packs->idetid;
                        }
                        try {
                            DB::connection('mysql')->insert("INSERT INTO u".$store.".mst_delete_table SET  TableName = 'mst_itempackdetail',Action = 'delete',TableId = '" . (int)$idetid . "',SID = '" . (int)$store."'");
                            DB::connection('mysql')->statement("DELETE FROM u".$store.".mst_itempackdetail WHERE idetid='" . (int)$idetid . "'");
                        }
                        catch (QueryException $e) {
                            $error['error'] = $e->getMessage(); 
                            return $error; 
                        }
                    }
                }
            }else{
                foreach($data['data'] as $value){
                    try {
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_itempackdetail',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)(session()->get('sid'))."'");
                        DB::connection('mysql_dynamic')->statement("DELETE FROM mst_itempackdetail WHERE idetid='" . (int)$value . "'");
                    }
                    catch (QueryException $e) {
                        // not a MySQL exception
                        $error['error'] = $e->getMessage(); 
                        return $error; 
                    }
                } 
            }
            
             
        }
        $success['success'] = 'Successfully Deleted Lot Item';
        return $success;
    }

    public function checkVendorItemCode($data) {
        $sql="SELECT * FROM mst_itemvendor WHERE iitemid='". ($data['iitemid']) ."' AND ivendorid='". ($data['ivendorid']) ."' AND vvendoritemcode='". ($data['vvendoritemcode']) ."'";

        $v_count = DB::connection('mysql_dynamic')->select($sql);
        $v_count = isset($v_count[0])?(array)$v_count[0]:[];

        if(count($v_count) > 0){
            $return['error'] = 'Vendor Code Already Exist';
        }else{
            $return['success'] = 'Vendor Code Not Exist';
        }

        return $return;

    }


   

    public function editlistItems($iitemid, $data) {
        
        $success =array();
        $error =array();
        
        if(isset($data) && count($data) > 0){
            
            if(isset($data['stores_hq']) && $data['stores_hq'] !=  ""){
              
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                }
                
                foreach($stores as $key => $value){
                    
                    $item_details = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE  vbarcode = '".$data['vbarcode']."' ");
                    foreach($item_details as $iitem){
                        $item_id = $iitem->iitemid;
                    }
                    // dd($value);
                    $previous_item = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid='" .$item_id. "'");
                    $previous_item = isset($previous_item[0]) ? (array)$previous_item[0]:[];
                    
                    // $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                    $result = DB::connection('mysql')->select("SELECT * FROM information_schema.tables WHERE table_schema = 'u".$value."'  AND table_name = 'trn_webadmin_history'");
                    $result = isset($result[0])?(array)$result[0]:[];
                    // dd($previous_item);
                    
                    if(count($result)){ 
                            if(($previous_item['dcostprice'] != $data['dcostprice']) && ($previous_item['dunitprice'] != $data['dunitprice']) && ($previous_item['new_costprice'] != $data['new_costprice'])){
                                $old_item_values = $previous_item;
                                unset($old_item_values['itemimage']);
                                        
                                $x_general = new \stdClass();
                                $x_general->old_item_values = $old_item_values;
                                $x_general = json_encode($x_general);
                                try{
                                    DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_webadmin_history SET  itemid = '" . $previous_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "', barcode = '" . $previous_item['vbarcode'] . "',  general = '" . $x_general . "', type = 'All', oldamount = '0', newamount = '0', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)$value."'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
                                $return = DB::connection('mysql')->select("SELECT historyid FROM u".$value.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                $trn_webadmin_history_last_id = $return[0]->historyid;
                            }else{ 
                                if($previous_item['dcostprice'] != ($data['dcostprice'])){
                                        
                                    $old_item_values = $previous_item;
                                    unset($old_item_values['itemimage']);
                                        
                                    $x_general_cost = new \stdClass();
                                    $x_general_cost->old_item_values = $old_item_values;
                                    $x_general_cost = json_encode($x_general_cost);
                                    try{
                                        DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_webadmin_history SET  itemid = '" . $previous_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($previous_item['vbarcode']) . "', general = '" . $x_general_cost . "', type = 'Cost', oldamount = '" . $previous_item['dcostprice'] . "', newamount = '" . ($data['dcostprice']) . "', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)$value."'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
            
                                    $return = DB::connection('mysql')->select("SELECT historyid FROM u".$value.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                    $trn_webadmin_history_last_id_cost = $return[0]->historyid;
                                        // dd($trn_webadmin_history_last_id_cost);
                                }
                                elseif($previous_item['new_costprice'] != ($data['new_costprice'])){
                                    $old_item_values = $previous_item;
                                    unset($old_item_values['itemimage']);
                                        
                                    $x_general_cost = new \stdClass();
                                    $x_general_cost->old_item_values = $old_item_values;
                                    $x_general_cost = json_encode($x_general_cost);
                                    try{
                                        DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_webadmin_history SET  itemid = '" . $previous_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($previous_item['vbarcode']) . "', general = '" . $x_general_cost . "', type = 'Cost', oldamount = '" . $previous_item['new_costprice'] . "', newamount = '" . ($data['new_costprice']) . "', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)$value."'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                            
                                    $return = DB::connection('mysql')->select("SELECT historyid FROM u".$value.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                    $trn_webadmin_history_last_id_cost = $return[0]->historyid;
                                }
                                elseif($previous_item['dunitprice'] != ($data['dunitprice'])){
                                    $old_item_values = $previous_item;
                                    unset($old_item_values['itemimage']);
                                    
                                    $x_general_price = new \stdClass();
                                    $x_general_price->old_item_values = $old_item_values;
                                    $x_general_price = json_encode($x_general_price);
                                    try{
                                        DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_webadmin_history SET  itemid = '" . $previous_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . $previous_item['vbarcode'] . "',  general = '" . $x_general_price . "', type = 'Price', oldamount = '" . $previous_item['dunitprice'] . "', newamount = '" . $data['dunitprice'] . "', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)$value."'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                    $return = DB::connection('mysql')->select("SELECT historyid FROM u".$value.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                        
                                    $trn_webadmin_history_last_id_price = $return[0]->historyid;
                                        // $trn_webadmin_history_last_id_price = $this->db2->getLastId();
                                }
                                else{
                                    $old_item_values = $previous_item;
                                    unset($old_item_values['itemimage']);
                                    
                                    $x_general = new \stdClass();
                                    $x_general->old_item_values = $old_item_values;
                                    $x_general = json_encode($x_general);
                                    try{
                                        
                                        DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_webadmin_history SET  itemid = '" . $iitemid . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . $data['vbarcode'] . "', general = '" . $x_general . "',  type = 'All', oldamount = '0', newamount = '0', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)$value."'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
        
                                    $return = DB::connection('mysql')->select("SELECT historyid FROM u".$value.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                    
                                    $trn_webadmin_history_last_id = $return[0]->historyid;
                                }
                            }
                        }
                            
                    //trn_webadmin_history
                    $vitemname = str_replace ("'","\'",$data['vitemname']);
                }
            
            }else{
                //trn_webadmin_history
                    $previous_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$iitemid . "'");
                    $previous_item = isset($previous_item[0])?(array)$previous_item[0]:[];
                    
                    $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                    $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                    
                    if(count($trn_webadmin_history)){ 
                        if(($previous_item['dcostprice'] != $data['dcostprice']) && ($previous_item['dunitprice'] != $data['dunitprice']) && ($previous_item['new_costprice'] != $data['new_costprice'])){
                                $old_item_values = $previous_item;
                                unset($old_item_values['itemimage']);
                                $x_general = new \stdClass();
                                $x_general->old_item_values = $old_item_values;
                                $x_general = json_encode($x_general);
                                try{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $iitemid . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . $data['vbarcode'] . "', general = '" . $x_general . "',  type = 'All', oldamount = '0', newamount = '0', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
                                $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                $trn_webadmin_history_last_id = $return[0]->historyid;
                                
                                // echo __LINE__;dd($trn_webadmin_history_last_id);
                        }else{ 
                            if($previous_item['new_costprice'] != ($data['new_costprice'])){
                                $old_item_values = $previous_item;
                                unset($old_item_values['itemimage']);
                                // dd($old_item_values);
                                $x_general_cost = new \stdClass();
                                $x_general_cost->old_item_values = $old_item_values;
                                $x_general_cost = json_encode($x_general_cost); 
                                try{
                                    $a = DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $iitemid . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($data['vbarcode']) . "', general = '" . $x_general_cost . "', type = 'Cost', oldamount = '" . $previous_item['dcostprice'] . "', newamount = '" . ($data['dcostprice']) . "', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
                                $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                $trn_webadmin_history_last_id_cost = $return[0]->historyid;
                                // echo __LINE__;dd($trn_webadmin_history_last_id);
                            }
                            elseif($previous_item['dcostprice'] != ($data['dcostprice'])){
                                $old_item_values = $previous_item;
                                unset($old_item_values['itemimage']);
                                $x_general_cost = new \stdClass();
                                $x_general_cost->old_item_values = $old_item_values;
                                $x_general_cost = json_encode($x_general_cost);
                                try{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $iitemid . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($data['vbarcode']) . "', general = '" . $x_general_cost . "', type = 'Cost', oldamount = '" . $previous_item['new_costprice'] . "', newamount = '" . ($data['new_costprice']) . "', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }   
                                $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                $trn_webadmin_history_last_id_cost = $return[0]->historyid;
                                // echo __LINE__;dd($trn_webadmin_history_last_id);
                            }
                            elseif($previous_item['dunitprice'] != ($data['dunitprice'])){
                                $old_item_values = $previous_item;
                                unset($old_item_values['itemimage']);
                                $x_general_price = new \stdClass();
                                $x_general_price->old_item_values = $old_item_values;
                                $x_general_price = json_encode($x_general_price);
                                try{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $iitemid . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . $data['vbarcode'] . "', general = '" . $x_general_price . "', type = 'Price', oldamount = '" . $previous_item['dunitprice'] . "', newamount = '" . $data['dunitprice'] . "', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
                                $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                $trn_webadmin_history_last_id_price = $return[0]->historyid;
                                // echo __LINE__;dd($trn_webadmin_history_last_id);
                            }
                            else{
                                $old_item_values = $previous_item;
                                unset($old_item_values['itemimage']);
                                $x_general = new \stdClass();
                                $x_general->old_item_values = $old_item_values;
                                $x_general = json_encode($x_general);
                                try{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $iitemid . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . $data['vbarcode'] . "', general = '" . $x_general . "',  type = 'All', oldamount = '0', newamount = '0', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
                                $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                $trn_webadmin_history_last_id = $return[0]->historyid;
                                
                            }
                        }
                    }
                    
                //trn_webadmin_history
                $vitemname = str_replace ("'","\'",$data['vitemname']);
            }
            // echo __LINE__;dd($trn_webadmin_history_last_id);
            
            
            if(isset($data['stores_hq']) && $data['stores_hq'] !=  ""){
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                }
                $sql = array();
                
                foreach($stores as $key => $value){
                    $item_details = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE  vbarcode = '".$data['vbarcode']."' ");
                    foreach($item_details as $iitem){
                        $item_id = $iitem->iitemid;
                    }
                    
                    $previous_item = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid='" .$item_id. "'");
                    $previous_item = isset($previous_item[0]) ? (array)$previous_item[0]:[];
                    // dd($previous_item);
                    try {
                        $sql_update = "";
                        $department_code    = $this->addDepartment($value, $data['vdepcode'] );
                        $cat_code           = $this->addCategory($value, $data['vcategorycode']);
                        $supplier_code      = $this->addSupplier($value, $data['vsuppliercode']);
                        
                        // $supplier_code      = $data['vsuppliercode'];
                        $vsize              = $this->addSize($value, $data['vsize']);
                        
                        // //=============20-11-2020========
                        $itemvendor_exist = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_itemvendor WHERE iitemid= '".(int)$previous_item['iitemid'] ."' AND ivendorid='". (int)$previous_item['vsuppliercode'] ."' ");
                        if(count($itemvendor_exist) > 0){
                            DB::connection('mysql')->insert("Update u".$value.".mst_itemvendor SET ivendorid = '" . (int)$supplier_code . "' WHERE iitemid = '".(int)$iitemid."' AND ivendorid = '".(int)$previous_item['vsuppliercode']."' ");
                        }else{
                            DB::connection('mysql')->insert("INSERT INTO u".$value.".mst_itemvendor SET  iitemid = '" . (int)$iitemid . "', ivendorid = '" . (int)$supplier_code . "', vvendoritemcode = '" . $iitemid . "', SID = '" . (int)($value) . "'");
                        }
                        
                        if(isset($data['itemimage']) && !empty($data['itemimage'])){
                            $img = addslashes($data['itemimage']);
                        
                            if(isset($data['subcat_id']) && !empty($data['subcat_id']) && trim($data['subcat_id']) != '' && trim($data['subcat_id']) != '--Select SubCategory--'){
                                // $sid = session()->get('sid');
                                $sub_cat_code       = $this->addSubcategory($value, $data['subcat_id']);
                                
                                // if( $sid == "1097")
                                // {   
                                //     $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code. "',subcat_id = '" .$sub_cat_code . "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "', reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . $vsize . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "', ireorderpointdays = '" . (int)($data['ireorderpointdays']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'] . "'";
                                // }
                                // else
                                // {
                                    $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "',  vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code. "',subcat_id = '" . $sub_cat_code . "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . $vsize . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value. "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'] . "'";
                                // }
                            }else{
                                // $sid = session()->get('sid');
                                // if( $sid == "1097")
                                // {   
                                //     $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code. "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "', reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper']  . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . $vsize . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "', ireorderpointdays = '" . (int)($data['ireorderpointdays']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'] . "'";
                                // }
                                // else
                                // {
                                    $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code. "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper']  . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" .$vsize. "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'] . "'";
                                // }
                            }
                        }else{
                            if(isset($data['subcat_id']) && !empty($data['subcat_id']) && trim($data['subcat_id']) != '' && trim($data['subcat_id']) != '--Select SubCategory--'){
                                // $sid = session()->get('sid');
                                $sub_cat_code       = $this->addSubcategory($value, $data['subcat_id']);
                                // if( $sid == "1097")
                                // {
                                //     $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code . "',subcat_id = '" .$sub_cat_code . "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . $vsize. "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'].  "'";   
                                // }
                                // else
                                // {
                                
                                    $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "',  vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code. "',subcat_id = '" .$sub_cat_code . "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "',  vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . $vsize. "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value. "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'] . "'";
                                // }
                            }else{
                                // $sid = session()->get('sid');
                                // if( $sid == "1097")
                                // {
                                //     $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code. "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" .$vsize. "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'].  "'";   
                                // }
                                // else
                                // {
                                    $sql_update = "UPDATE u".$value.".mst_item SET  webstore = '" . ($data['webstore']) . "',vitemtype = '" . ($data['vitemtype']) . "',vitemname = '" . ($vitemname) . "',vunitcode = '" . ($data['vunitcode']) . "',  vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" .$cat_code. "', vdepcode = '" .$department_code. "', vsuppliercode = '" .$supplier_code. "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "',  vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" .$vsize. "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)$value. "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" .(int)$previous_item['iitemid']. "'";
                                // }
                            }
                        }
                        $result = DB::connection('mysql')->select($sql_update);
                        if(isset($data['options_data']) && count($data['options_data']) > 0){
                            
                            $mst_item_size = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item_size WHERE item_id= '". (int)$previous_item['iitemid'] ."' ");
                            $mst_item_size = isset($mst_item_size[0])?(array)$mst_item_size[0]:[];
    
                            if(count($mst_item_size) > 0){
                                DB::connection('mysql')->update("UPDATE u".$value.".mst_item_size SET  unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."' WHERE item_id = '" . (int)$previous_item['iitemid'] . "'");
                            }else{
                                DB::connection('mysql')->insert("INSERT INTO u".$value.".mst_item_size SET  item_id = '". (int)$previous_item['iitemid'] ."',unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."',SID = '" . (int)$value."'");
                            }
                                
                            $mst_plcb_item = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_plcb_item WHERE item_id= '". (int)$previous_item['iitemid'] ."' ");
                            $mst_plcb_item = isset($mst_plcb_item[0])?(array)$mst_plcb_item[0]:[];
    
                            if(count($mst_plcb_item) > 0){
                                DB::connection('mysql')->update("UPDATE u".$value.".mst_plcb_item SET  bucket_id = '". (int)$data['options_data']['bucket_id'] ."',malt = '". (int)$data['options_data']['malt'] ."' WHERE item_id = '" . (int)$previous_item['iitemid'] . "'");
                            }else{
                                DB::connection('mysql')->insert("INSERT INTO u".$value.".mst_plcb_item SET  item_id = '". (int)$previous_item['iitemid'] ."',bucket_id = '". (int)$data['options_data']['bucket_id'] ."',prev_mo_beg_qty = '". $data['iqtyonhand'] ."',prev_mo_end_qty = '". $data['iqtyonhand'] ."',malt = '". (int)$data['options_data']['malt'] ."',SID = '" . (int)(session()->get('sid'))."'");
                            }
                        }else{
                            $checkexist_mst_item_size = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item_size WHERE item_id='" . (int)$previous_item['iitemid'] . "'");
                            $checkexist_mst_item_size = isset($checkexist_mst_item_size[0])?(array)$checkexist_mst_item_size[0]:[];
                            
                            if(count($checkexist_mst_item_size) > 0){
                                
                                DB::connection('mysql')->insert("INSERT INTO u".$value.".mst_delete_table SET  TableName = 'mst_item_size',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_item_size['id'] . "',SID = '" . (int)$value."'");
                                
                                DB::connection('mysql')->select("DELETE FROM u".$value.".mst_item_size WHERE id='" . (int)$checkexist_mst_item_size['id'] . "'");
                                
                            }
                                
                            $checkexist_mst_plcb_item = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_plcb_item WHERE item_id='" . (int)$previous_item['iitemid'] . "'");
                            $checkexist_mst_plcb_item = isset($checkexist_mst_plcb_item[0])?(array)$checkexist_mst_plcb_item[0]:[];
    
                            if(count($checkexist_mst_plcb_item) > 0){
                                
                                DB::connection('mysql')->insert("INSERT INTO u".$value.".mst_delete_table SET  TableName = 'mst_plcb_item',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_plcb_item['id'] . "',SID = '" . (int)$value."'");
                                
                                DB::connection('mysql')->select("DELETE FROM u".$value.".mst_plcb_item WHERE id='" . (int)$checkexist_mst_plcb_item['id'] . "'");
                                
                            }
                        }
                                
                        //mst plcb item
                                
                        //trn_itempricecosthistory
                        $new_update_values = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)$previous_item['iitemid'] ."' ");
                        $new_update_values = isset($new_update_values[0])?(array)$new_update_values[0]:[];
    
                        if($previous_item['dcostprice'] != $new_update_values['dcostprice']){
                                
                            DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($previous_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)$value."'");
                        }
                                
                        if($previous_item['dunitprice'] != $new_update_values['dunitprice']){
                            
                            DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemPrice', noldamt = '" . ($previous_item['dunitprice']) . "', nnewamt = '" . ($new_update_values['dunitprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)$value."'");
                        }
                        
                        if($previous_item['new_costprice'] != $new_update_values['new_costprice']){
                                
                            DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($previous_item['new_costprice']) . "', nnewamt = '" . ($new_update_values['new_costprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)$value."'");
                        }
                        //trn_itempricecosthistory
                            
                        //trn_webadmin_history
                        $trn_webadmin_history = DB::connection('mysql')->select(" SELECT * FROM information_schema.tables WHERE table_schema = 'u".$value."'  AND table_name = 'trn_webadmin_history' ");
                        $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                        
                        if(count($trn_webadmin_history)){
                            // if($this->db2->query(" SHOW tables LIKE 'trn_webadmin_history'")->num_rows){
                            if(($previous_item['dcostprice'] != ($data['dcostprice'])) && ($previous_item['dunitprice'] != ($data['dunitprice'])) && ($previous_item['new_costprice'] != ($data['new_costprice']))){
                               
                                $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)$previous_item['iitemid'] ."' ");
                                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
    
                                unset($new_item_values['itemimage']);
                                
                                $x_general = new \stdClass();
                                $x_general->new_item_values = $new_item_values;
                                    
                                $x_general = addslashes(json_encode($x_general));
                                try{
                                    // $webadmin_data = DB::connection('mysql')->select("SELECT general FROM u".$value.".trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                    // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                    
                                    // $x_general->old_item_values =  $old_item_values;
                                    // $x_general = (json_encode($x_general));
                                    
                                    // DB::connection('mysql')->update("UPDATE u".$value.".trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
    
                            }else{
                                if($previous_item['dcostprice'] != ($data['dcostprice'])){
                                    $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)$previous_item['iitemid'] ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
    
                                    unset($new_item_values['itemimage']);
                                    
                                    $x_general_cost = new \stdClass();
                                    $x_general_cost->new_item_values = $new_item_values;
                                        
                                    $x_general_cost = addslashes(json_encode($x_general_cost));
                                    try{
                                        // $webadmin_data = DB::connection('mysql')->select("SELECT general FROM u".$value.".trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                        // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                        
                                        // $x_general_cost->old_item_values =  $old_item_values;
                                        // $x_general_cost = (json_encode($x_general_cost));
                                        
                                        // DB::connection('mysql')->update("UPDATE u".$value.".trn_webadmin_history SET general = '" . $x_general_cost . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                        
                                }
                                        
                                if($previous_item['dunitprice'] != ($data['dunitprice'])){
                                    $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)$previous_item['iitemid'] ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
    
                                    unset($new_item_values['itemimage']);
                                    
                                    $x_general_price = new \stdClass();
                                        
                                    $x_general_price->new_item_values = $new_item_values;
                                        
                                    $x_general_price = addslashes(json_encode($x_general_price));
                                    try{
                                        // $webadmin_data = DB::connection('mysql')->select("SELECT general FROM u".$value.".trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id_price . "'");
                                        // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                        
                                        // $x_general_price->old_item_values =  $old_item_values;
                                        // $x_general_price = (json_encode($x_general_price));
                                        
                                        // DB::connection('mysql')->update("UPDATE u".$value.".trn_webadmin_history SET general = '" . $x_general_price . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_price . "'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                        
                                }
                                
                                
                                if($previous_item['new_costprice'] != ($data['new_costprice'])){
                                    $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)$previous_item['iitemid'] ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                    
                                    unset($new_item_values['itemimage']);
                                    $x_general_cost = new \stdClass();
                                    $x_general_cost->new_item_values = $new_item_values;
                                        
                                    $x_general_cost = addslashes(json_encode($x_general_cost));
                                    try{
                                        // $webadmin_data = DB::connection('mysql')->select("SELECT general FROM u".$value.".trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                        
                                        // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                        
                                        // $x_general_cost->old_item_values =  $old_item_values;
                                        // $x_general_cost = (json_encode($x_general_cost));
                                        
                                        // DB::connection('mysql')->update("UPDATE u".$value.".trn_webadmin_history SET general = '" . $x_general_cost . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                        
                                }
                            }
                        }
                        //trn_webadmin_history
                                        
                        DB::connection('mysql')->update("UPDATE u".$value.".mst_item SET vitemcode = '" . ($data['vbarcode']) . "' WHERE iitemid = '" . (int)$previous_item['iitemid'] . "'");
                                
                        if(isset($data['iitemgroupid'])){
                            $delete_ids = DB::connection('mysql')->select("SELECT `Id` FROM u".$value.".itemgroupdetail WHERE vsku='" . ($data['vbarcode']) . "'");
                            $delete_ids = isset($delete_ids[0])?(array)$delete_ids[0]:[];
                                    
                            if(count($delete_ids) > 0){
                                DB::connection('mysql')->insert("INSERT INTO u".$value.".mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)$value."'");
                            }
                                
                            DB::connection('mysql')->select("DELETE FROM u".$value.".itemgroupdetail WHERE vsku='" . ($data['vbarcode']) . "'");
                                
                            if($data['iitemgroupid'] != ''){
                                DB::connection('mysql')->insert("INSERT INTO u".$value.".itemgroupdetail SET  iitemgroupid = '" . (int)($data['iitemgroupid']) . "', vsku='". ($data['vbarcode']) ."',vtype='Product',SID = '" . (int)$value. "' ");
                            }
                        }
                                
                        $isParentCheck = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid='". (int)$iitemid ."'");
                        $isParentCheck = isset($isParentCheck[0])?(array)$isParentCheck[0]:[];
                            
                        if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                            $child_items = DB::connection('mysql')->select("SELECT `iitemid`,`vbarcode`,`dcostprice`,`npack` FROM u".$value.".mst_item WHERE parentmasterid= '". (int)$previous_item['iitemid'] ."' ");
                            // $child_items = isset($child_items[0])?(array)$child_items[0]:[];
                            $child_items = array_map(function ($value) {
                                return (array)$value;
                            }, $child_items);
                            
                            if(count($child_items) > 0){
                                foreach($child_items as $chi_item){
                                    //trn_webadmin_history
                                    $trn_webadmin_history = DB::connection('mysql')->select(" SELECT * FROM information_schema.tables WHERE table_schema = 'u".$value."'  AND table_name = 'trn_webadmin_history' ");
                                    $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                                    
                                    if(count($trn_webadmin_history)){
                                        $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                        $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
    
                                        unset($old_item_values['itemimage']);
                                        $x_general_child = new \stdClass();
                                        $x_general_child->is_child = 'Yes';
                                        $x_general_child->parentmasterid = $old_item_values['parentmasterid'];
                                        $x_general_child->old_item_values = $old_item_values;
                                        try{
                                        
                                            DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_webadmin_history SET  itemid = '" . ($chi_item['iitemid']) . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($chi_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $chi_item['dcostprice'] . "', newamount = '". (($chi_item['npack']) * (($isParentCheck['nunitcost']))) ."', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)$value."'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                        $return = DB::connection('mysql')->select("SELECT historyid FROM u".$value.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                
                                        $trn_webadmin_history_last_id_child = $return[0]->historyid;
                                        // $trn_webadmin_history_last_id_child = $this->db2->getLastId();
                                    }
                                    //trn_webadmin_history
                                        
                                    DB::connection('mysql')->update("UPDATE u".$value.".mst_item SET dcostprice=npack*
                                        '". ($isParentCheck['nunitcost']) ."',nunitcost='". ($isParentCheck['nunitcost']) ."' WHERE iitemid= '". (int)($chi_item['iitemid']) ."'");
                                        
                                    //trn_itempricecosthistory
                                    $new_update_values = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)$chi_item['iitemid'] ."' ");
                                    $new_update_values = isset($new_update_values[0])?(array)$new_update_values[0]:[];
    
                                    if($chi_item['dcostprice'] != $new_update_values['dcostprice']){
                                        
                                        DB::connection('mysql')->insert("INSERT INTO u".$value.".trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($chi_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)$value."'");
                                    }
                                    //trn_itempricecosthistory
                                        
                                    //trn_webadmin_history
                                    $trn_webadmin_history = DB::connection('mysql')->select(" SELECT * FROM information_schema.tables WHERE table_schema = 'u".$value."'  AND table_name = 'trn_webadmin_history' ");
                                    $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                                    
                                    if(count($trn_webadmin_history)){
                                        $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
    
                                        unset($new_item_values['itemimage']);
                                        
                                        $x_general_child = new \stdClass();
                                        $x_general_child->new_item_values = $new_item_values;
                                        
                                        $x_general_child = addslashes(json_encode($x_general_child));
                                        try{
                                        
                                            //DB::connection('mysql')->update("UPDATE u".$value.".trn_webadmin_history SET general = '" . $x_general_child . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_child . "'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                }
                            }
                        }
                            
                        if(($data['vitemtype']) == 'Lot Matrix'){
                            
                            if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                                $lot_child_items = DB::connection('mysql')->select("SELECT `iitemid` FROM u".$value.".mst_item WHERE parentmasterid= '". (int)$previous_item['iitemid'] ."' ");
                                $lot_child_items = isset($lot_child_items[0])?(array)$lot_child_items[0]:[];
    
                                if(count($lot_child_items) > 0){
                                    foreach($lot_child_items as $chi){
                                        $pack_lot_child_item = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_itempackdetail WHERE iitemid= '". (int)($chi['iitemid']) ."' ");
                                        $pack_lot_child_item = isset($pack_lot_child_item[0])?(array)$pack_lot_child_item[0]:[];
    
                                        if(count($pack_lot_child_item) > 0){
                                            foreach ($pack_lot_child_item as $k => $v) {
                                                $parent_nunitcost = ($data['nunitcost']);
                                                    
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
                                                    
                                                DB::connection('mysql')->update("UPDATE u".$value.".mst_itempackdetail SET  `npackcost` = '" . $parent_npackcost . "',`nunitcost` = '" . $parent_nunitcost . "',`npackmargin` = '" . $parent_npackmargin . "' WHERE idetid='". (int)($v['idetid']) ."'");
                                            }
                                        }
                                    }
                                }
                            }
                                
                                
                            $vpackname = 'Case';
                            $vdesc = 'Case';
                                
                            $nunitcost = ($data['nunitcost']);
                            if($nunitcost == ''){
                                $nunitcost = 0;
                            }
                                
                            $ipack = ($data['nsellunit']);
                            if(($data['nsellunit']) == ''){
                                $ipack = 0;
                            }
                                
                            $npackprice = ($data['dunitprice']);
                            if(($data['dunitprice']) == ''){
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
                                
                            $itempackexist = DB::connection('mysql')->select("SELECT * FROM u".$value.".mst_itempackdetail WHERE vbarcode='". ($data['vbarcode']) ."' AND iitemid='". (int)$previous_item['iitemid'] ."' AND iparentid=1");
                            $itempackexist = isset($itempackexist[0])?(array)$itempackexist[0]:[];
    
                            //===This is commented because itempack data is not posted from the side of view still 
                            //====this one update nly the first pack of item===11/06/2020====
                            if(count($itempackexist) > 0){
                                // DB::connection('mysql_dynamic')->select("UPDATE mst_itempackdetail SET  `vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='". ($data['vbarcode']) ."' AND iitemid='". (int)$iitemid ."' AND iparentid=1");
                            }else{
                                DB::connection('mysql')->insert("INSERT INTO u".$value.".mst_itempackdetail SET  iitemid = '" . (int)$iitemid . "',`vbarcode` = '" . ($data['vbarcode']) . "',`vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`nunitcost` = '" . $nunitcost . "',`ipack` = '" . (int)$ipack . "',`iparentid` = '" . (int)$iparentid . "',`npackcost` = '" . $npackcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "', SID = '" . (int)$value . "'");
                            }
                            
                        }
                    }
                    catch (QueryException $e) {
                        // not a MySQL exception
                        if (strpos($e->getMessage(), "Unknown column 'vtax3'") !== false) {
                            // $error['error_tax3'] = 'Tax3 is not supported by this Store. Please contact Technical Support.';
                            $error['error_tax3'] = 'Please update POS to Version 3.0.7';
                        } else {
                            $error['error'] = $e->getMessage(); 
                        }
                        // return $error; 
                    }
                }
                
            }else{
                
                try {
                        //=============20-11-2020========
                        $itemvendor_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemvendor WHERE iitemid= '". (int)$iitemid ."' AND ivendorid='". (int)$previous_item['vsuppliercode'] ."' ");
                        if(count($itemvendor_exist) > 0){
                            DB::connection('mysql_dynamic')->insert("Update mst_itemvendor SET `ivendorid` = '" . (int)$data['vsuppliercode'] . "' WHERE iitemid = '".(int)$iitemid."' AND ivendorid = '".(int)$previous_item['vsuppliercode']."' ");
                        }else{
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)$iitemid . "',`ivendorid` = '" . (int)$data['vsuppliercode'] . "',`vvendoritemcode` = '" . $iitemid . "', SID = '" . (int)(session()->get('sid')) . "'");
                        }
                        
                        if(isset($data['itemimage']) && !empty($data['itemimage'])){
                            $img = addslashes($data['itemimage']);
                            
                            if(isset($data['subcat_id']) && !empty($data['subcat_id']) && trim($data['subcat_id']) != '' && trim($data['subcat_id']) != '--Select SubCategory--'){
                                $sid = session()->get('sid');
                                if( $sid == "1097")
                                {   
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "',subcat_id = '" . ($data['subcat_id']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "', reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "', ireorderpointdays = '" . (int)($data['ireorderpointdays']) . "' WHERE iitemid = '" . (int)$iitemid . "'";
                                }
                                else
                                {
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "',subcat_id = '" . ($data['subcat_id']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$iitemid . "'";
                                }
                            }else{
                                $sid = session()->get('sid');
                                if( $sid == "1097")
                                {   
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "', reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "', ireorderpointdays = '" . (int)($data['ireorderpointdays']) . "' WHERE iitemid = '" . (int)$iitemid . "'";
                                }
                                else
                                {
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', itemimage = '" . ($img) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$iitemid . "'";
                                }
                            }
                        }else{
                            if(isset($data['subcat_id']) && !empty($data['subcat_id']) && trim($data['subcat_id']) != '' && trim($data['subcat_id']) != '--Select SubCategory--'){
                                $sid = session()->get('sid');
                                if( $sid == "1097")
                                {
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "',subcat_id = '" . ($data['subcat_id']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" . (float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$iitemid.  "'";   
                                // die;
                                }
                                else
                                {
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "',subcat_id = '" . ($data['subcat_id']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "',  vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$iitemid . "'";
                                }
                            }else{
                                $sid = session()->get('sid');
                                if( $sid == "1097")
                                {
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$iitemid.  "'";   
                                // die;
                                }
                                else
                                {
                                    $sql_update = "UPDATE mst_item SET  webstore = '" . ($data['webstore']) . "',`vitemtype` = '" . ($data['vitemtype']) . "',`vitemname` = '" . ($vitemname) . "',`vunitcode` = '" . ($data['vunitcode']) . "', vbarcode = '" . ($data['vbarcode']) . "', vpricetype = '" . ($data['vpricetype']) . "', vcategorycode = '" . ($data['vcategorycode']) . "', vdepcode = '" . ($data['vdepcode']) . "', vsuppliercode = '" . ($data['vsuppliercode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', ireorderpoint = '" . (int)($data['ireorderpoint']) . "',reorder_duration = '" . (int)($data['reorder_duration']) . "',manufacturer_id = '" . (int)($data['manufacturer_id']) . "',new_costprice = '" . ($data['new_costprice']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', nsaleprice = '" . ($data['nsaleprice']) . "', nlevel2 = '" . ($data['nlevel2']) . "', nlevel3 = '" . ($data['nlevel3']) . "', nlevel4 = '" . ($data['nlevel4']) . "', iquantity = '" . (int)($data['iquantity']) . "', ndiscountper = '" .(float)$data['ndiscountper'] . "', ndiscountamt = '" . ($data['ndiscountamt']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "',  vtax3 = '" . ($data['vtax3']) . "', vfooditem = '" . ($data['vfooditem']) . "', vdescription = '" . ($data['vdescription']) . "', visinventory = '" . ($data['visinventory']) . "', estatus = '" . ($data['estatus']) . "', nbuyqty = '" . (int)($data['nbuyqty']) . "', ndiscountqty = '" . (int)($data['ndiscountqty']) . "', nsalediscountper = '" . ($data['nsalediscountper']) . "', vshowimage = '" . ($data['vshowimage']) . "', vageverify = '" . ($data['vageverify']) . "', ebottledeposit = '" . ($data['ebottledeposit']) . "', nbottledepositamt = '" . ($data['nbottledepositamt']) . "', vbarcodetype = '" . ($data['vbarcodetype']) . "', ntareweight = '" . ($data['ntareweight']) . "', ntareweightper = '" . ($data['ntareweightper']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', nlastcost = '" . ($data['nlastcost']) . "', nonorderqty = '" . (int)($data['nonorderqty']) . "', vparentitem = '" . ($data['vparentitem']) . "', nchildqty = '" . ($data['nchildqty']) . "', vsize = '" . ($data['vsize']) . "', npack = '" . (int)($data['npack']) . "', nunitcost = '" . ($data['nunitcost']) . "', ionupload = '" . ($data['ionupload']) . "', nsellunit = '" . (int)($data['nsellunit']) . "', ilotterystartnum = '" . (int)($data['ilotterystartnum']) . "', ilotteryendnum = '" . (int)($data['ilotteryendnum']) . "', etransferstatus = '" . ($data['etransferstatus']) . "', itemimage =null , vcolorcode = '" . ($data['vcolorcode']) . "', vdiscount = '" . ($data['vdiscount']) . "', norderqtyupto = '" . (int)($data['norderqtyupto']) . "', iinvtdefaultunit = '" . ($data['iinvtdefaultunit']) . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)($data['stationid']) . "', shelfid = '" . (int)($data['shelfid']) . "', aisleid = '" . (int)($data['aisleid']) . "', shelvingid = '" . (int)($data['shelvingid']) . "', PrinterStationId = '" . (int)($data['PrinterStationId']) . "', liability = '" . ($data['liability']) . "', isparentchild = '" . (int)($data['isparentchild']) . "', parentid = '" . (int)($data['parentid']) . "', parentmasterid = '" . (int)($data['parentmasterid']) . "', wicitem = '" . (int)($data['wicitem']) . "' WHERE iitemid = '" . (int)$iitemid . "'";
                                }
                            }
                        }
                        
                        $result = DB::connection('mysql_dynamic')->update($sql_update);
                        
                        //mst plcb item
                            
                        if(isset($data['options_data']) && count($data['options_data']) > 0){
                            
                            $mst_item_size = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id= '". (int)$iitemid ."' ");
                            $mst_item_size = isset($mst_item_size[0])?(array)$mst_item_size[0]:[];
    
                            if(count($mst_item_size) > 0){
                                
                                DB::connection('mysql_dynamic')->update("UPDATE mst_item_size SET  unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."' WHERE item_id = '" . (int)$iitemid . "'");
                                
                            }else{
                                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_item_size SET  item_id = '". (int)$iitemid ."',unit_id = '". (int)$data['options_data']['unit_id'] ."',unit_value = '". (int)$data['options_data']['unit_value'] ."',SID = '" . (int)(session()->get('sid'))."'");
                            }
                                
                            $mst_plcb_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE item_id= '". (int)$iitemid ."' ");
                            $mst_plcb_item = isset($mst_plcb_item[0])?(array)$mst_plcb_item[0]:[];
    
                            if(count($mst_plcb_item) > 0){
                                DB::connection('mysql_dynamic')->update("UPDATE mst_plcb_item SET  bucket_id = '". (int)$data['options_data']['bucket_id'] ."',malt = '". (int)$data['options_data']['malt'] ."' WHERE item_id = '" . (int)$iitemid . "'");
                            }else{
                                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_plcb_item SET  item_id = '". (int)$iitemid ."',bucket_id = '". (int)$data['options_data']['bucket_id'] ."',prev_mo_beg_qty = '". $data['iqtyonhand'] ."',prev_mo_end_qty = '". $data['iqtyonhand'] ."',malt = '". (int)$data['options_data']['malt'] ."',SID = '" . (int)(session()->get('sid'))."'");
                            }
                        }else{
                            $checkexist_mst_item_size = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id='" . (int)$iitemid . "'");
                            $checkexist_mst_item_size = isset($checkexist_mst_item_size[0])?(array)$checkexist_mst_item_size[0]:[];
                            
                            if(count($checkexist_mst_item_size) > 0){
                                
                                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_item_size',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_item_size['id'] . "',SID = '" . (int)(session()->get('sid'))."'");
                                
                                DB::connection('mysql_dynamic')->select("DELETE FROM mst_item_size WHERE id='" . (int)$checkexist_mst_item_size['id'] . "'");
                                
                            }
                                
                            $checkexist_mst_plcb_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE item_id='" . (int)$iitemid . "'");
                            $checkexist_mst_plcb_item = isset($checkexist_mst_plcb_item[0])?(array)$checkexist_mst_plcb_item[0]:[];
    
                            if(count($checkexist_mst_plcb_item) > 0){
                                
                                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_plcb_item',`Action` = 'delete',`TableId` = '" . (int)$checkexist_mst_plcb_item['id'] . "',SID = '" . (int)(session()->get('sid'))."'");
                                
                                DB::connection('mysql_dynamic')->select("DELETE FROM mst_plcb_item WHERE id='" . (int)$checkexist_mst_plcb_item['id'] . "'");
                                
                            }
                        }
                                
                        //mst plcb item
                                
                        //trn_itempricecosthistory
                        $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$iitemid ."' ");
                        $new_update_values = isset($new_update_values[0])?(array)$new_update_values[0]:[];
    
                        if($previous_item['dcostprice'] != $new_update_values['dcostprice']){
                                
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($previous_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                        }
                                
                        if($previous_item['dunitprice'] != $new_update_values['dunitprice']){
                            
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemPrice', noldamt = '" . ($previous_item['dunitprice']) . "', nnewamt = '" . ($new_update_values['dunitprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                        }
                        
                        if($previous_item['new_costprice'] != $new_update_values['new_costprice']){
                                
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($previous_item['new_costprice']) . "', nnewamt = '" . ($new_update_values['new_costprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                        }
                        //trn_itempricecosthistory
                            
                        //trn_webadmin_history
                        $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                        $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                        
                        if(count($trn_webadmin_history)){
                            // if($this->db2->query(" SHOW tables LIKE 'trn_webadmin_history'")->num_rows){
                            if(($previous_item['dcostprice'] != ($data['dcostprice'])) && ($previous_item['dunitprice'] != ($data['dunitprice'])) && ($previous_item['new_costprice'] != ($data['new_costprice']))){
                                    
                                $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$iitemid ."' ");
                                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                
                                unset($new_item_values['itemimage']);
                                
                                $x_general = new \stdClass();
                                $x_general->new_item_values = $new_item_values;
                                    
                                $x_general = addslashes(json_encode($x_general));
                                try{
                                    
                                    // $webadmin_data = DB::connection('mysql_dynamic')->select("SELECT general FROM trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                    // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                    
                                    // $x_general->old_item_values =  $old_item_values;
                                    // $x_general = (json_encode($x_general));
                                        
                                    // DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
    
                            }else{
                                if($previous_item['dcostprice'] != ($data['dcostprice'])){
                                    $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$iitemid ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                    
                                    unset($new_item_values['itemimage']);
                                    
                                    $x_general_cost = new \stdClass();  
                                    $x_general_cost->new_item_values = $new_item_values;
                                    
                                    $x_general_cost = addslashes(json_encode($x_general_cost));
                                    try{
                                        
                                        // $webadmin_data = DB::connection('mysql_dynamic')->select("SELECT general FROM trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                        // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                        
                                        // $x_general_cost->old_item_values =  $old_item_values;
                                        // $x_general_cost = (json_encode($x_general_cost));
                                        
                                        // DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_cost . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                        
                                }
                                        
                                if($previous_item['dunitprice'] != ($data['dunitprice'])){
                                    $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$iitemid ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
    
                                    unset($new_item_values['itemimage']);
                                    
                                    $x_general_price = new \stdClass();
                                        
                                    $x_general_price->new_item_values = $new_item_values;
                                        
                                    $x_general_price = addslashes(json_encode($x_general_price));
                                    try{
                                        
                                        // $webadmin_data = DB::connection('mysql_dynamic')->select("SELECT general FROM trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id_price . "'");
                                        // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                        
                                        // $x_general_price->old_item_values =  $old_item_values;
                                        // $x_general_price = (json_encode($x_general_price));
                                        
                                        // DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_price . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_price . "'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                        
                                }
                                
                                
                                if($previous_item['new_costprice'] != ($data['new_costprice'])){
                                    $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$iitemid ."' ");
                                    $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                    
                                    unset($new_item_values['itemimage']);
                                    $x_general_cost = new \stdClass();
                                    $x_general_cost->new_item_values = $new_item_values;
                                    $x_general_cost = addslashes(json_encode($x_general_cost));  
                                    
                                    try{
                                        
                                        // $webadmin_data = DB::connection('mysql_dynamic')->select("SELECT general FROM trn_webadmin_history WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                        
                                        // $old_item_values = json_decode($webadmin_data[0]->general)->old_item_values;
                                        
                                        // $x_general_cost->old_item_values =  $old_item_values;
                                        // $x_general_cost = (json_encode($x_general_cost));
                                        
                                        // DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_cost . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                    }
                                    catch (QueryException $e) {
                                        Log::error($e);
                                    }
                                        
                                }
                            }
                        }
                        //trn_webadmin_history
                                        
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vitemcode = '" . ($data['vbarcode']) . "' WHERE iitemid = '" . (int)$iitemid . "'");
                                
                        if(isset($data['iitemgroupid'])){
                            $delete_ids = DB::connection('mysql_dynamic')->select("SELECT `Id` FROM itemgroupdetail WHERE vsku='" . ($data['vbarcode']) . "'");
                            $delete_ids = isset($delete_ids[0])?(array)$delete_ids[0]:[];
                                    
                            if(count($delete_ids) > 0){
                                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)(session()->get('sid'))."'");
                            }
                                
                            DB::connection('mysql_dynamic')->select("DELETE FROM itemgroupdetail WHERE vsku='" . ($data['vbarcode']) . "'");
                                
                            if($data['iitemgroupid'] != ''){
                                DB::connection('mysql_dynamic')->insert("INSERT INTO itemgroupdetail SET  iitemgroupid = '" . (int)($data['iitemgroupid']) . "', vsku='". ($data['vbarcode']) ."',vtype='Product',SID = '" . (int)(session()->get('sid')) . "' ");
                            }
                        }
                                
                        $isParentCheck = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='". (int)$iitemid ."'");
                        $isParentCheck = isset($isParentCheck[0])?(array)$isParentCheck[0]:[];
                            
                        if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                            $child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vbarcode`,`dcostprice`,`npack` FROM mst_item WHERE parentmasterid= '". (int)$iitemid ."' ");
                            // $child_items = isset($child_items[0])?(array)$child_items[0]:[];
                            $child_items = array_map(function ($value) {
                                return (array)$value;
                            }, $child_items);
                            
                            if(count($child_items) > 0){
                                foreach($child_items as $chi_item){
                                    //trn_webadmin_history
                                    $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                                    
                                    if(count($trn_webadmin_history)){
                                        $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                        $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
    
                                        unset($old_item_values['itemimage']);
                                        $x_general_child = new \stdClass();
                                        $x_general_child->is_child = 'Yes';
                                        $x_general_child->parentmasterid = $old_item_values['parentmasterid'];
                                        $x_general_child->old_item_values = $old_item_values;
                                        try{
                                        
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . ($chi_item['iitemid']) . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($chi_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $chi_item['dcostprice'] . "', newamount = '". (($chi_item['npack']) * (($isParentCheck['nunitcost']))) ."', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                        $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                
                                        $trn_webadmin_history_last_id_child = $return[0]->historyid;
                                        // $trn_webadmin_history_last_id_child = $this->db2->getLastId();
                                    }
                                    //trn_webadmin_history
                                        
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dcostprice=npack*
                                        '". ($isParentCheck['nunitcost']) ."',nunitcost='". ($isParentCheck['nunitcost']) ."' WHERE iitemid= '". (int)($chi_item['iitemid']) ."'");
                                        
                                    //trn_itempricecosthistory
                                    $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$chi_item['iitemid'] ."' ");
                                    $new_update_values = isset($new_update_values[0])?(array)$new_update_values[0]:[];
    
                                    if($chi_item['dcostprice'] != $new_update_values['dcostprice']){
                                        
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($chi_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    }
                                    //trn_itempricecosthistory
                                        
                                    //trn_webadmin_history
                                    $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                                    
                                    if(count($trn_webadmin_history)){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
    
                                        unset($new_item_values['itemimage']);
                                        
                                        $x_general_child = new \stdClass();
                                        $x_general_child->new_item_values = $new_item_values;
                                        
                                        $x_general_child = addslashes(json_encode($x_general_child));
                                        try{
                                        
                                            // DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_child . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_child . "'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                }
                            }
                        }
                            
                        if(($data['vitemtype']) == 'Lot Matrix'){
                            
                            if((count($isParentCheck) > 0) && ($isParentCheck['isparentchild'] == 2)){
                                $lot_child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentmasterid= '". (int)$iitemid ."' ");
                                $lot_child_items = isset($lot_child_items[0])?(array)$lot_child_items[0]:[];
    
                                if(count($lot_child_items) > 0){
                                    foreach($lot_child_items as $chi){
                                        $pack_lot_child_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid= '". (int)($chi['iitemid']) ."' ");
                                        $pack_lot_child_item = isset($pack_lot_child_item[0])?(array)$pack_lot_child_item[0]:[];
    
                                        if(count($pack_lot_child_item) > 0){
                                            foreach ($pack_lot_child_item as $k => $v) {
                                                $parent_nunitcost = ($data['nunitcost']);
                                                    
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
                                
                            $nunitcost = ($data['nunitcost']);
                            if($nunitcost == ''){
                                $nunitcost = 0;
                            }
                                
                            $ipack = ($data['nsellunit']);
                            if(($data['nsellunit']) == ''){
                                $ipack = 0;
                            }
                                
                            $npackprice = ($data['dunitprice']);
                            if(($data['dunitprice']) == ''){
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
                                
                            $itempackexist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE vbarcode='". ($data['vbarcode']) ."' AND iitemid='". (int)$iitemid ."' AND iparentid=1");
                            $itempackexist = isset($itempackexist[0])?(array)$itempackexist[0]:[];
    
                            //===This is commented because itempack data is not posted from the side of view still 
                            //====this one update nly the first pack of item===11/06/2020====
                            if(count($itempackexist) > 0){
                                // DB::connection('mysql_dynamic')->select("UPDATE mst_itempackdetail SET  `vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`ipack` = '" . (int)$ipack . "',`npackcost` = '" . $npackcost . "',`nunitcost` = '" . $nunitcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "' WHERE vbarcode='". ($data['vbarcode']) ."' AND iitemid='". (int)$iitemid ."' AND iparentid=1");
                            }else{
                                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itempackdetail SET  iitemid = '" . (int)$iitemid . "',`vbarcode` = '" . ($data['vbarcode']) . "',`vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`nunitcost` = '" . $nunitcost . "',`ipack` = '" . (int)$ipack . "',`iparentid` = '" . (int)$iparentid . "',`npackcost` = '" . $npackcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "', SID = '" . (int)(session()->get('sid')) . "'");
                            }
                            
                        }
                    }
                    
                catch (QueryException $e) {
                        // not a MySQL exception
                        //   dd($e);
                        if (strpos($e->getMessage(), "Unknown column 'vtax3'") !== false) {
                            // $error['error_tax3'] = 'Tax3 is not supported by this Store. Please contact Technical Support.';
                            $error['error_tax3'] = 'Please update POS to Version 3.0.7';
                        } else {
                            $error['error'] = $e->getMessage(); 
                        }
                        return $error; 
                    }
                
            }
                 
                
                
        }
            
        $success['success'] = 'Successfully Updated Item';
        return $success;
    }
    
    public function deleteVendorItemCode($data,$vendoritemid) {
        
        if(isset($data) && count($data) > 0){
            
            foreach ($data as $key => $value) {
                
                $sql = "select ivendorid from mst_itemvendor where iitemid ='" . (int)$vendoritemid . "'";
                $result = DB::connection('mysql_dynamic')->select($sql);
                
                $sql2 = "select vsuppliercode from  mst_item where iitemid ='" . (int)$vendoritemid . "'";
                $result2 = DB::connection('mysql_dynamic')->select($sql2);
               
                
                if(count($result)==1 && $result[0]->ivendorid==$result2[0]->vsuppliercode ){
                    // $sql3="SELECT * FROM vcompanyname mst_supplier where vsuppliercode='" . (int)$result2[0]->vsuppliercode . "'";
                    // $result3 = DB::connection('mysql_dynamic')->select($sql3);
                    
                    $return['error'] = 'This vendor already assigned to item please add different vendor then delete it';

                    return $return;
                    
                    break;
                    
                }
                
              
               
                
                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'mst_itemvendor',`Action` = 'delete',`TableId` = '" . (int)$value . "',SID = '" . (int)(session()->get('sid'))."'");

                DB::connection('mysql_dynamic')->select("DELETE FROM mst_itemvendor WHERE Id='" . (int)$value . "'");
                
                
                $sql="select ivendorid from mst_itemvendor where iitemid ='" . (int)$vendoritemid . "' order by LastUpdate desc LIMIT 1 ";
               
                $result = DB::connection('mysql_dynamic')->select($sql);

                if(isset($result[0]->ivendorid)){
                DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  `vsuppliercode` = '" . (int)$result[0]->ivendorid . "' WHERE iitemid= '". (int)$vendoritemid ."'");
                } 
                else{
                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  `vsuppliercode` = '' WHERE iitemid= '". (int)$vendoritemid ."'");
                    
                }
            }
        }

        $return['success'] = 'Item Vendor Code Deleted Successfully';

        return $return;

    }
    
    public function addImportItems($data = array())
    {
        // dd($data);
        if(count($data) > 0){
            // $this->db2->query("INSERT INTO mst_item SET  dlastupdated = '" . ($data['dlastupdated']) . "',dcreated = '" . ($data['dcreated']) . "',vbarcode = '" . ($data['vbarcode']) . "',vitemcode = '" . ($data['vitemcode']) . "',vitemname = '" . ($data['vitemname']) . "',vitemtype = '" . ($data['vitemtype']) . "',vcategorycode = '" . ($data['vcategorycode']) . "',vdepcode = '" . ($data['vdepcode']) . "',estatus = '" . ($data['estatus']) . "',dunitprice = '" . ($data['dunitprice']) . "',dcostPrice = '" . ($data['dcostPrice']) . "',vunitcode = '" . ($data['vunitcode']) . "',vtax1 = '" . ($data['vtax1']) . "',vtax2 = '" . ($data['vtax2']) . "',vfooditem = '" . ($data['vfooditem']) . "',vsuppliercode = '" . ($data['vsuppliercode']) . "',vdescription = '" . ($data['vdescription']) . "',vshowimage = '" . ($data['vshowimage']) . "',iquantity = '" . ($data['iquantity']) . "',ireorderpoint = '" . ($data['ireorderpoint']) . "',iqtyonhand = '" . ($data['iqtyonhand']) . "',npack = '" . ($data['npack']) . "',nunitcost = '" . ($data['nunitcost']) . "',vsize = '" . ($data['vsize']) . "',ionupload = '" . ($data['ionupload']) . "',vcolorcode = '" . ($data['vcolorcode']) . "',vageverify = '". ($data['vageverify']) . "',SID = '" . (int)($this->session->data['sid']) . "'");
            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_item SET  dlastupdated = '" . ($data['dlastupdated']) . "',dcreated = '" . ($data['dcreated']) . "',vbarcode = '" . ($data['vbarcode']) . "',vitemcode = '" . ($data['vitemcode']) . "',vitemname = '" . ($data['vitemname']) . "',vitemtype = '" . ($data['vitemtype']) . "',vcategorycode = '" . ($data['vcategorycode']) . "',vdepcode = '" . ($data['vdepcode']) . "',estatus = '" . ($data['estatus']) . "',dunitprice = '" . ($data['dunitprice']) . "',new_costprice = '" . ($data['dcostPrice']) . "', dcostPrice = '" . ($data['dcostPrice']) . "',vunitcode = '" . ($data['vunitcode']) . "',vtax1 = '" . ($data['vtax1']) . "',vtax2 = '" . ($data['vtax2']) . "',vfooditem = '" . ($data['vfooditem']) . "',vsuppliercode = '" . ($data['vsuppliercode']) . "',vdescription = '" . ($data['vdescription']) . "',vshowimage = '" . ($data['vshowimage']) . "',iquantity = '" . ($data['iquantity']) . "',ireorderpoint = '" . ($data['ireorderpoint']) . "',reorder_duration = '" . ($data['reorder_duration']) . "',iqtyonhand = '" . ($data['iqtyonhand']) . "',npack = '" . ($data['npack']) . "',nunitcost = '" . ($data['nunitcost']) . "',vsize = '" . ($data['vsize']) . "',ionupload = '" . ($data['ionupload']) . "',vcolorcode = '" . ($data['vcolorcode']) . "',vageverify = '". ($data['vageverify']) . "', wicitem = '". (int)($data['wicitem']) . "', nsellunit = '". (int)($data['nsellunit']) . "', SID = '" . (int)(session()->get('sid')) . "',visinventory ='Yes',liability = 'N'");
        }
    }
    
    public function editLotteryItems($iitemid, $data) {

        $success =array();
        $error =array();
        
        $vitemname = str_replace ("'","\'",$data['vitemname']);
        if(isset($data) && count($data) > 0){
            if(isset($data['stores_hq'])){
                
                if($data['stores_hq'] === session()->get('sid')){
                    $stores = [session()->get('sid')];
                }else{
                    $stores = explode(",", $data['stores_hq']);
                }
                
                $barcode = DB::connection('mysql_dynamic')->select("SELECT vbarcode FROM mst_item WHERE iitemid='" . (int)$iitemid . "'");
                if($barcode[0]){
                    $vbarcode = $barcode[0]->vbarcode;
                }
                
                foreach($stores as $store){
                    //trn_webadmin_history
                    $previous_item = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE vbarcode='" .$vbarcode. "'");
                    $previous_item = isset($previous_item[0])?(array)$previous_item[0]:[];
                    
                    $result = DB::connection('mysql_dynamic')->select(" SELECT * FROM information_schema.tables WHERE table_schema = '".$store."'  AND table_name = 'trn_webadmin_history' ");
                    $result = isset($result[0])?(array)$result[0]:[];
                    if(count($result)){ 
                        if($previous_item['dcostprice'] != ($data['dcostprice'])){
                            $old_item_values = $previous_item;
                            unset($old_item_values['itemimage']);
                            $x_general_cost = new \stdClass();
                            $x_general_cost->old_item_values = $old_item_values;
                            try{
                                DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_webadmin_history SET  itemid = '" . $previous_item['iitemid'] . "', userid = '" . Auth::user()->iuserid . "',barcode = '" . ($previous_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $previous_item['dcostprice'] . "', newamount = '" . ($data['dcostprice']) . "', source = 'ItemEdit', historydatetime = NOW(), SID = '" . (int)$store."'");
                            }
                            catch (QueryException $e) {
                                Log::error($e);
                            }
                            $return = DB::connection('mysql')->select("SELECT historyid FROM u".$store.".trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                            $trn_webadmin_history_last_id_cost = $return[0]->historyid;
                        }   
                    }
                    //trn_webadmin_history
                    try { 
                        $sql_update = "UPDATE u".$store.".mst_item SET vitemtype = '" . $data['vitemtype'] . "',  `vitemname` = '" . ($vitemname) . "', vbarcode = '" .($data['vbarcode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', npack = '" . (int)($data['npack']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', SID = '" . (int)$store . "' WHERE iitemid = '" . (int)$previous_item['iitemid'].  "'";   
                        $check = DB::connection('mysql')->update($sql_update);
                        
                        //trn_itempricecosthistory
                        $new_update_values = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid= '". (int)$previous_item['iitemid'] ."' ");
                        $new_update_values = isset($new_update_values[0])?(array)$new_update_values[0]:[];
                        if($previous_item['dcostprice'] != $new_update_values['dcostprice']){
                            DB::connection('mysql')->insert("INSERT INTO u".$store.".trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($previous_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)$store."'");
                        }
                        //trn_itempricecosthistory
                        //trn_webadmin_history
                        $result = DB::connection('mysql')->select(" SELECT * FROM information_schema.tables WHERE table_schema = '".$store."'  AND table_name = 'trn_webadmin_history' ");
                        $result = isset($result[0])?(array)$result[0]:[];
                        if(count($result)){
                            if($previous_item['dcostprice'] != ($data['dcostprice'])){
                                $new_item_values = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item WHERE iitemid= '". (int)$previous_item['iitemid'] ."' ");
                                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                unset($new_item_values['itemimage']);
                                $x_general_cost->new_item_values = $new_item_values;
                                $x_general_cost = addslashes(json_encode($x_general_cost));
                                try{
                                    DB::connection('mysql_dynamic')->update("UPDATE u".$store.".trn_webadmin_history SET general = '" . $x_general_cost . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                                }
                                catch (QueryException $e) {
                                    Log::error($e);
                                }
                            }
                        }
                        //trn_webadmin_history
                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vitemcode = '" . ($data['vbarcode']) . "' WHERE iitemid = '" . (int)$iitemid . "'");
                    }
                    catch (Exception $e) {
                        $error['error'] = $e->getMessage(); 
                        return $error; 
                    }
                }
            }else {
                //trn_webadmin_history
                $previous_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)$iitemid . "'");
                $previous_item = isset($previous_item[0])?(array)$previous_item[0]:[];
                $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                $result = isset($result[0])?(array)$result[0]:[];
                if(count($result)){ 
                    if($previous_item['dcostprice'] != ($data['dcostprice'])){
                        $old_item_values = $previous_item;
                        unset($old_item_values['itemimage']);
                        $x_general_cost = new \stdClass();
                        $x_general_cost->old_item_values = $old_item_values;
                        try{
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $iitemid . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($data['vbarcode']) . "', type = 'Cost', oldamount = '" . $previous_item['dcostprice'] . "', newamount = '" . ($data['dcostprice']) . "', source = 'ItemEdit', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                        }
                        catch (QueryException $e) {
                            Log::error($e);
                        }
                        $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                        $trn_webadmin_history_last_id_cost = $return[0]->historyid;
                    }   
                }
                        
                //trn_webadmin_history
                try {
                    $sql_update = "UPDATE mst_item SET vitemtype = '" . $data['vitemtype'] . "',  `vitemname` = '" . ($vitemname) . "', vbarcode = '" . ($data['vbarcode']) . "', iqtyonhand = '" . (int)($data['iqtyonhand']) . "', dcostprice = '" . ($data['dcostprice']) . "', dunitprice = '" . ($data['dunitprice']) . "', npack = '" . (int)($data['npack']) . "', vtax1 = '" . ($data['vtax1']) . "', vtax2 = '" . ($data['vtax2']) . "', vtax3 = '" . ($data['vtax3']) . "', dlastupdated = '" . ($data['dlastupdated']) . "', SID = '" . (int)(session()->get('sid')) . "' WHERE iitemid = '" . (int)$iitemid.  "'";   
                    $check = DB::connection('mysql_dynamic')->update($sql_update);
                        
                    //trn_itempricecosthistory
                    $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$iitemid ."' ");
                    $new_update_values = isset($new_update_values[0])?(array)$new_update_values[0]:[];
                        
                    if($previous_item['dcostprice'] != $new_update_values['dcostprice']){
                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($previous_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                    }   
                    //trn_itempricecosthistory
                        
                    //trn_webadmin_history
                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                    $result = isset($result[0])?(array)$result[0]:[];
                    if(count($result)){
                        if($previous_item['dcostprice'] != ($data['dcostprice'])){
                            $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$iitemid ."' ");
                            $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                            unset($new_item_values['itemimage']);
                            $x_general_cost->new_item_values = $new_item_values;
                            $x_general_cost = addslashes(json_encode($x_general_cost));
                            try{
                                DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_cost . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_cost . "'");
                            }
                            catch (QueryException $e) {
                                Log::error($e);
                            }
                        }
                    }
                    //trn_webadmin_history
                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET vitemcode = '" . ($data['vbarcode']) . "' WHERE iitemid = '" . (int)$iitemid . "'");
                }
                catch (Exception $e) {
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
            }
            
            
        }
        
        $success['success'] = 'Successfully Updated Item';
        return $success;
    }

}
