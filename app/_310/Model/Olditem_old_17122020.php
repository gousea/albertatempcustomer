<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class Olditem extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_item';
    

    public function getSKU($vbarcode) {
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemalias WHERE valiassku= '". $vbarcode ."'");

        if(count($query) == 0){
            $query = self::where('vbarcode',  $vbarcode)->get()->toArray();
        }

        return $query;
    }

    public function addLotteryItems($data = array()){
        
        $success =array();
        $error =array();
        $last_id = '';

        if(isset($data) && count($data) > 0){
            
               try {
                //    dd($data);
                   $sql_insert = "INSERT INTO mst_item SET  vitemtype = '" . $data['vitemtype'] . "',
                                    vitemname = '" . $data['vitemname'] . "', vbarcode = '" . $data['vbarcode'] . "', 
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
        
        $success['success'] = 'Successfully Added Item';
        $success['iitemid'] = $last_id;
        return $success;
        
        
    }

    public function addItems($data = array()) {    
      
        $success =array();
        $error =array();
        $last_id = '';
        
        if(isset($data) && count($data) > 0){
            
               try {
                    $sql_insert = '';
                    if(isset($data['itemimage']) && !empty($data['itemimage'])){
                        $img = $data['itemimage'];
                        
                        $sql_insert = "INSERT INTO mst_item SET  webstore = '" . $data['webstore'] . "',`vitemtype` = '" . $data['vitemtype'] . "',`vitemname` = '" . $data['vitemname'] . "',`vunitcode` = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $data['vcategorycode'] . "', subcat_id = '" . $data['subcat_id'] . "', vdepcode = '" . $data['vdepcode'] . "', vsuppliercode = '" . $data['vsuppliercode'] . "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . $data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', dlastsold = '" . $data['dlastsold'] . "', visinventory = '" . $data['visinventory'] . "', dpricestartdatetime = '" . $data['dpricestartdatetime'] . "', dpriceenddatetime = '" . $data['dpriceenddatetime'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', itemimage = '" . $img . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', dlastreceived = '" . $data['dlastreceived'] . "', dlastordered = '" . $data['dlastordered'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" . $data['vsize'] . "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";

                    }else{
                        
                        $sql_insert = "INSERT INTO mst_item SET  webstore = '" . $data['webstore'] . "',`vitemtype` = '" . $data['vitemtype'] . "',`vitemname` = '" . $data['vitemname'] . "',`vunitcode` = '" . $data['vunitcode'] . "', vbarcode = '" . $data['vbarcode'] . "', vpricetype = '" . $data['vpricetype'] . "', vcategorycode = '" . $data['vcategorycode'] . "', subcat_id = '" . $data['subcat_id'] . "', vdepcode = '" . $data['vdepcode'] . "', vsuppliercode = '" . $data['vsuppliercode'] . "', iqtyonhand = '" . (int)$data['iqtyonhand'] . "', ireorderpoint = '" . (int)$data['ireorderpoint'] . "', reorder_duration = '" . (int)$data['reorder_duration'] . "',manufacturer_id = '" . (int)$data['manufacturer_id'] . "', new_costprice = '" . $data['new_costprice'] . "', dcostprice = '" . $data['dcostprice'] . "', dunitprice = '" . $data['dunitprice'] . "', nsaleprice = '" . $data['nsaleprice'] . "', nlevel2 = '" . $data['nlevel2'] . "', nlevel3 = '" . $data['nlevel3'] . "', nlevel4 = '" . $data['nlevel4'] . "', iquantity = '" . (int)$data['iquantity'] . "', ndiscountper = '" . $data['ndiscountper'] . "', ndiscountamt = '" . $data['ndiscountamt'] . "', vtax1 = '" . $data['vtax1'] . "', vtax2 = '" . $data['vtax2'] . "', vtax3 = '" . $data['vtax3'] . "', vfooditem = '" . $data['vfooditem'] . "', vdescription = '" . $data['vdescription'] . "', dlastsold = '" . $data['dlastsold'] . "', visinventory = '" . $data['visinventory'] . "', dpricestartdatetime = '" . $data['dpricestartdatetime'] . "', dpriceenddatetime = '" . $data['dpriceenddatetime'] . "', estatus = '" . $data['estatus'] . "', nbuyqty = '" . (int)$data['nbuyqty'] . "', ndiscountqty = '" . (int)$data['ndiscountqty'] . "', nsalediscountper = '" . $data['nsalediscountper'] . "', vshowimage = '" . $data['vshowimage'] . "', vageverify = '" . $data['vageverify'] . "', ebottledeposit = '" . $data['ebottledeposit'] . "', nbottledepositamt = '" . $data['nbottledepositamt'] . "', vbarcodetype = '" . $data['vbarcodetype'] . "', ntareweight = '" . $data['ntareweight'] . "', ntareweightper = '" . $data['ntareweightper'] . "', dcreated = '" . $data['dcreated'] . "', dlastupdated = '" . $data['dlastupdated'] . "', dlastreceived = '" . $data['dlastreceived'] . "', dlastordered = '" . $data['dlastordered'] . "', nlastcost = '" . $data['nlastcost'] . "', nonorderqty = '" . (int)$data['nonorderqty'] . "', vparentitem = '" . $data['vparentitem'] . "', nchildqty = '" . $data['nchildqty'] . "', vsize = '" . $data['vsize'] . "', npack = '" . (int)$data['npack'] . "', nunitcost = '" . $data['nunitcost'] . "', ionupload = '" . $data['ionupload'] . "', nsellunit = '" . (int)$data['nsellunit'] . "', ilotterystartnum = '" . (int)$data['ilotterystartnum'] . "', ilotteryendnum = '" . (int)$data['ilotteryendnum'] . "', etransferstatus = '" . $data['etransferstatus'] . "', vcolorcode = '" . $data['vcolorcode'] . "', vdiscount = '" . $data['vdiscount'] . "', norderqtyupto = '" . (int)$data['norderqtyupto'] . "', iinvtdefaultunit = '" . $data['iinvtdefaultunit'] . "', SID = '" . (int)(session()->get('sid')) . "', stationid = '" . (int)$data['stationid'] . "', shelfid = '" . (int)$data['shelfid'] . "', aisleid = '" . (int)$data['aisleid'] . "', shelvingid = '" . (int)$data['shelvingid'] . "', PrinterStationId = '" . (int)$data['PrinterStationId'] . "', liability = '" . $data['liability'] . "', isparentchild = '" . (int)$data['isparentchild'] . "', parentid = '" . (int)$data['parentid'] . "', parentmasterid = '" . (int)$data['parentmasterid'] . "', wicitem = '" . (int)$data['wicitem'] . "'";
                        
                    }
                    
                    
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
                   
            
                    
                    //mst plcb item

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
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$delete_ids['Id'] . "',SID = '" . (int)($this->session->data['sid'])."'");
                        }

                        DB::connection('mysql_dynamic')->select("DELETE FROM itemgroupdetail WHERE vsku='" . $data['vbarcode'] . "'");

                        if($data['iitemgroupid'] != ''){
                            DB::connection('mysql_dynamic')->insert("INSERT INTO itemgroupdetail SET  iitemgroupid = '" . (int)$data['iitemgroupid'] . "', vsku='". $data['vbarcode'] ."',vtype='Product',SID = '" . (int)($this->session->data['sid']) . "' ");
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

                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itempackdetail SET  iitemid = '" . (int)$last_id . "',`vbarcode` = '" . $data['vbarcode'] . "',`vpackname` = '" . $vpackname . "',`vdesc` = '" . $vdesc . "',`ipack` = '" . (int)$ipack . "',`iparentid` = '" . (int)$iparentid . "',`npackcost` = '" . $npackcost . "',`npackprice` = '" . $npackprice . "',`npackmargin` = '" . $npackmargin . "', SID = '" . (int)($this->session->data['sid']) . "'");
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
        // Opening Qoh update
                    $query = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
                    
                    $ipid = $query[0]->ipiid;
                    $vrefnumber = str_pad($ipid+1,9,"0",STR_PAD_LEFT);
                    
                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventory SET  vrefnumber= $vrefnumber, estatus='Close',dcreatedate=CURRENT_TIMESTAMP, vtype ='Opening QoH', SID = '" . (int)(session()->get('sid'))."'");
                    
                    $return = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
                    
                    $ipiid = $return[0]->ipiid;

                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',
                                     vitemid = '" . (int)$last_id . "',
                                     vitemname ='" . $data['vitemname'] . "',
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

    public function addUpdateItemVendor($data = array()) {

        $success =array();
        $error =array();

        if(isset($data) && count($data) > 0){

               try {
                    $itemvendor_exist = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemvendor WHERE iitemid= '". (int)$data['iitemid'] ."' AND ivendorid='". (int)$data['ivendorid'] ."' AND Id='". (int)$data['Id'] ."'");

                    if(count($itemvendor_exist) > 0){
                        DB::connection('mysql_dynamic')->update("UPDATE mst_itemvendor SET  `vvendoritemcode` = '" . $data['vvendoritemcode'] . "' WHERE iitemid= '". (int)$data['iitemid'] ."' AND ivendorid='". (int)$data['ivendorid'] ."' AND Id='". (int)$data['Id'] ."'");
                        $success['success'] = 'Successfully Updated Item Vendor';
                    }else{
                        DB::connection('mysql_dynamic')->insert("INSERT INTO mst_itemvendor SET  iitemid = '" . (int)$data['iitemid'] . "',`ivendorid` = '" . (int)$data['ivendorid'] . "',`vvendoritemcode` = '" . $data['vvendoritemcode'] . "', SID = '" . (int)(session()->get('sid')) . "'");
                        $success['success'] = 'Successfully Added Item Vendor';
                    }

                }
                
                catch (QueryException $e) {
                    // not a MySQL exception
                   
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
        }

        return $success;
    }


    public function editlistLotMatrixItems($datas = array()) {

        $success =array();
        $error =array();

        if(isset($datas) && count($datas) > 0){

            foreach ($datas as $key => $data) {
                try {
                    DB::connection('mysql_dynamic')->update("UPDATE mst_itempackdetail SET  `npackprice` = '" . ($data['npackprice']) . "',`npackmargin` = '" . ($data['npackmargin']) . "' WHERE iitemid='". (int)($data['iitemid']) ."' AND idetid='". (int)($data['idetid']) ."'");
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

    public function getItem($iitemid) {
        $datas = array();

        $query = DB::connection('mysql_dynamic')->select("SELECT a.*, CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND FROM mst_item as a where iitemid='". (int)$iitemid ."'");
        $query = isset($query[0])?(array)$query[0]:[];

        if(count($query) > 0){
            $value = $query;
                $groupid = DB::connection('mysql_dynamic')->select("SELECT * FROM itemgroupdetail WHERE vsku='". $value['vbarcode'] ."'");
                $groupid = isset($groupid[0])?(array)$groupid[0]:[];

                $itemalias = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemalias WHERE vsku='". $value['vbarcode'] ."'");
                $itemalias = isset($itemalias[0])?(array)$itemalias[0]:[];
     
                $itemslabprices = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemslabprice WHERE vsku='". $value['vbarcode'] ."'");
                $itemslabprices = isset($itemslabprices[0])?(array)$itemslabprices[0]:[];

                if($value['isparentchild'] == 2){
                    $itemchilditems = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vitemname`,`npack` FROM mst_item WHERE parentmasterid='". $value['iitemid'] ."'");
                    $itemchilditems = isset($itemchilditems[0])?(array)$itemchilditems[0]:[];
                }else{
                    $itemchilditems = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vitemname`,`npack` FROM mst_item WHERE parentid='". $value['iitemid'] ."'");
                    $itemchilditems = isset($itemchilditems[0])?(array)$itemchilditems[0]:[];
                }

                $itemparentitems = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`vitemname`,`npack` FROM mst_item WHERE iitemid='". $value['parentid'] ."'");
                $itemparentitems = isset($itemparentitems[0])?(array)$itemparentitems[0]:[];

                $remove_parent_item = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentid in('". $value['iitemid'] ."') AND isparentchild !=0");
                $remove_parent_item = isset($remove_parent_item[0])?(array)$remove_parent_item[0]:[];

                $itempacks = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itempackdetail WHERE iitemid='". (int)$value['iitemid'] ."' ORDER BY isequence");
                $itempacks = isset($itempacks[0])?(array)$itempacks[0]:[];

                $itemvendors = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_itemvendor as miv,mst_supplier as ms WHERE miv.ivendorid=ms.isupplierid AND  miv.iitemid='". (int)$value['iitemid'] ."'");
                $itemvendors = isset($itemvendors[0])?(array)$itemvendors[0]:[];
                
                
                /*$get_purchase_details = $this->db2->query("SELECT nunitcost FROM trn_purchaseorderdetail WHERE vitemid='". (int)$value['iitemid'] ."' ORDER BY LastUpdate DESC LIMIT 2")->rows;
                
                if(count($get_purchase_details) == 2){
                    $nunitcost = $get_purchase_details[1]['nunitcost'];
                } else {
                    $nunitcost = $get_purchase_details[0]['nunitcost'];
                }*/
                
                
                $get_purchase_details = DB::connection('mysql_dynamic')->select("SELECT ipodetid FROM trn_purchaseorderdetail WHERE vitemid='". (int)$value['iitemid'] ."'");
                $get_purchase_details = isset($get_purchase_details[0])?(array)$get_purchase_details[0]:[];
                
                if(count($get_purchase_details) > 0){
                    $data['po_exists'] = true;
                } else {
                    $data['po_exists'] = false;
                }
                
                // print_r($data); die;

                $datas['iitemid'] = $value['iitemid'];
                $datas['itempacks'] = $itempacks;
                $datas['iitemgroupid'] = $groupid;
                $datas['itemalias'] = $itemalias;
                $datas['itemvendors'] = $itemvendors;
                $datas['itemslabprices'] = $itemslabprices;
                $datas['itemchilditems'] = $itemchilditems;
                $datas['itemparentitems'] = $itemparentitems;
                $datas['remove_parent_item'] = $remove_parent_item;
                $datas['webstore'] = $value['webstore'];
                $datas['vitemtype'] = $value['vitemtype'];
                $datas['vitemcode'] = $value['vitemcode'];
                $datas['vitemname'] = $value['vitemname'];
                $datas['vunitcode'] = $value['vunitcode'];
                $datas['vbarcode'] = $value['vbarcode'];
                $datas['vpricetype'] = $value['vpricetype'];
                $datas['vcategorycode'] = $value['vcategorycode'];
                $datas['vdepcode'] = $value['vdepcode'];
                $datas['vsuppliercode'] = $value['vsuppliercode'];
                $datas['iqtyonhand'] = $value['iqtyonhand'];
                $datas['QOH'] = $value['IQTYONHAND'];
                $datas['ireorderpoint'] = $value['ireorderpoint'];
                $datas['dcostprice'] = $value['dcostprice'];
                $datas['dunitprice'] = $value['dunitprice'];
                $datas['nsaleprice'] = $value['nsaleprice'];
                $datas['nlevel2'] = $value['nlevel2'];
                $datas['nlevel3'] = $value['nlevel3'];
                $datas['nlevel4'] = $value['nlevel4'];
                $datas['iquantity'] = $value['iquantity'];
                $datas['ndiscountper'] = $value['ndiscountper'];
                $datas['ndiscountamt'] = $value['ndiscountamt'];
                $datas['vtax1'] = $value['vtax1'];
                $datas['vtax2'] = $value['vtax2'];
                $datas['vtax3'] = $value['vtax3'];
                
                $datas['vfooditem'] = $value['vfooditem'];
                $datas['vdescription'] = $value['vdescription'];
                $datas['dlastsold'] = $value['dlastsold'];
                $datas['visinventory'] = $value['visinventory'];
                $datas['dpricestartdatetime'] = $value['dpricestartdatetime'];
                $datas['dpriceenddatetime'] = $value['dpriceenddatetime'];
                $datas['estatus'] = $value['estatus'];
                $datas['nbuyqty'] = $value['nbuyqty'];
                $datas['ndiscountqty'] = $value['ndiscountqty'];
                $datas['nsalediscountper'] = $value['nsalediscountper'];
                $datas['vshowimage'] = $value['vshowimage'];
                if(isset($value['vshowimage']) && !empty($value['vshowimage'])){
                    $datas['itemimage'] = $value['itemimage'];
                }else{
                    $datas['itemimage'] = '';
                }
                
                $datas['vageverify'] = $value['vageverify'];
                $datas['ebottledeposit'] = $value['ebottledeposit'];
                $datas['nbottledepositamt'] = $value['nbottledepositamt'];
                $datas['vbarcodetype'] = $value['vbarcodetype'];
                $datas['ntareweight'] = $value['ntareweight'];
                $datas['ntareweightper'] = $value['ntareweightper'];
                $datas['dcreated'] = $value['dcreated'];
                $datas['dlastupdated'] = $value['dlastupdated'];
                $datas['dlastreceived'] = $value['dlastreceived'];
                $datas['dlastordered'] = $value['dlastordered'];
                $datas['nlastcost'] = $value['nlastcost'];
                $datas['nonorderqty'] = $value['nonorderqty'];
                $datas['vparentitem'] = $value['vparentitem'];
                $datas['nchildqty'] = $value['nchildqty'];
                $datas['vsize'] = $value['vsize'];
                $datas['npack'] = $value['npack'];
                $datas['nunitcost'] = $value['nunitcost'];
                $datas['ionupload'] = $value['ionupload'];
                $datas['nsellunit'] = $value['nsellunit'];
                $datas['ilotterystartnum'] = $value['ilotterystartnum'];
                $datas['ilotteryendnum'] = $value['ilotteryendnum'];
                $datas['etransferstatus'] = $value['etransferstatus'];
                $datas['vsequence'] = $value['vsequence'];
                $datas['vcolorcode'] = $value['vcolorcode'];
                $datas['vdiscount'] = $value['vdiscount'];
                $datas['norderqtyupto'] = $value['norderqtyupto'];
                $datas['vshowsalesinzreport'] = $value['vshowsalesinzreport'];
                $datas['iinvtdefaultunit'] = $value['iinvtdefaultunit'];
                $datas['LastUpdate'] = $value['LastUpdate'];
                $datas['SID'] = $value['SID'];
                $datas['stationid'] = $value['stationid'];
                $datas['shelfid'] = $value['shelfid'];
                $datas['aisleid'] = $value['aisleid'];
                $datas['shelvingid'] = $value['shelvingid'];
                $datas['rating'] = $value['rating'];
                $datas['vintage'] = $value['vintage'];
                $datas['PrinterStationId'] = $value['PrinterStationId'];
                $datas['liability'] = $value['liability'];
                $datas['isparentchild'] = $value['isparentchild'];
                $datas['parentid'] = $value['parentid'];
                $datas['parentmasterid'] = $value['parentmasterid'];
                $datas['wicitem'] = $value['wicitem'];
                
                $datas['last_costprice'] = $value['last_costprice'];
                $datas['new_costprice'] = $value['new_costprice'];
            
        }  

        return $datas;
    }

    public function getItemUnitData($iitemid){

        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item_size WHERE item_id='". (int)$iitemid ."'");

        $data = isset($query[0])?(array)$query[0]:[];
            return $data;
    }

    public function getItemBucketData($iitemid){

        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_plcb_item WHERE bucket_id != 13");

        $data = isset($query[0])?(array)$query[0]:[];
            return $data;
    }
    
    public function addImportItems($data = array())
    {
        if(count($data) > 0){
            $this->db2->query("INSERT INTO mst_item SET  dlastupdated = '" . ($data['dlastupdated']) . "',dcreated = '" . ($data['dcreated']) . "',vbarcode = '" . ($data['vbarcode']) . "',vitemcode = '" . ($data['vitemcode']) . "',vitemname = '" . ($data['vitemname']) . "',vitemtype = '" . ($data['vitemtype']) . "',vcategorycode = '" . ($data['vcategorycode']) . "',vdepcode = '" . ($data['vdepcode']) . "',estatus = '" . ($data['estatus']) . "',dunitprice = '" . ($data['dunitprice']) . "',dcostPrice = '" . ($data['dcostPrice']) . "',vunitcode = '" . ($data['vunitcode']) . "',vtax1 = '" . ($data['vtax1']) . "',vtax2 = '" . ($data['vtax2']) . "',vfooditem = '" . ($data['vfooditem']) . "',vsuppliercode = '" . ($data['vsuppliercode']) . "',vdescription = '" . ($data['vdescription']) . "',vshowimage = '" . ($data['vshowimage']) . "',iquantity = '" . ($data['iquantity']) . "',ireorderpoint = '" . ($data['ireorderpoint']) . "',iqtyonhand = '" . ($data['iqtyonhand']) . "',npack = '" . ($data['npack']) . "',nunitcost = '" . ($data['nunitcost']) . "',vsize = '" . ($data['vsize']) . "',ionupload = '" . ($data['ionupload']) . "',vcolorcode = '" . ($data['vcolorcode']) . "',vageverify = '". ($data['vageverify']) . "',SID = '" . (int)(session()->get('sid')) . "'");
        }
    }
    
}

?>