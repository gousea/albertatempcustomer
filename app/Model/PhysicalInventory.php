<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class PhysicalInventory extends Model
{
    protected $connection ='mysql_dynamic';
    protected $table = 'trn_physicalinventory';
    protected $primaryKey = 'ipiid';
    public $timestamps = false;
    
    
    public function snapshot($selected_itemid)
    {
        // dd($selected_itemid);
        $last_refno = DB::connection('mysql_dynamic')->select("SELECT vrefnumber FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
        $last_refno = isset($last_refno[0])?(array)$last_refno[0]:[];
        
        if(isset($last_refno) && !empty($last_refno)){
                $vrefnumber = $last_refno['vrefnumber'] + 1;
                $vrefnumber = str_pad($vrefnumber, 8, '0', STR_PAD_LEFT);
        }else{
                $vrefnumber = 1;
                $vrefnumber = str_pad($vrefnumber, 8, '0', STR_PAD_LEFT);
            }
        
        
        $dcreatedate = date("Y-m-d H:i:s");
		
		$query_insert = "INSERT INTO trn_physicalinventory SET  vrefnumber = '" . $vrefnumber . "', dcreatedate = '" . $dcreatedate . "', estatus = 'Open', vtype = 'Physical', SID = '" . (int)(session()->get('sid'))."' ";
	    $insert  = DB::connection('mysql_dynamic')->insert($query_insert);
        $ipiid = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id FROM trn_physicalinventory ')[0]->last_insert_id;
        
        $sql = array();
        
        if($insert == true){ 
            foreach ($selected_itemid as $itemid){
                
                $itemdata = DB::connection('mysql_dynamic')->select("select vbarcode, iqtyonhand, npack from mst_item where iitemid = '".$itemid."'");
                $itemdata = isset($itemdata[0])?(array)$itemdata[0]:[];
                // print_r($itemdata); die;
                
                // tackled at line 712
                // $this->check_table_mst_physical_inventory_snapshot();
                
                
                /*// query will return false if the table does not exist.
                $val = DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'mst_physical_inventory_snapshot'")->num_rows;
                
                if($val == FALSE)
                {
                   $table_create_query = "CREATE TABLE `mst_physical_inventory_snapshot` (
                                          `snapshotid` int(11) NOT NULL AUTO_INCREMENT,
                                          `ipiid` int(11) NOT NULL,
                                          `iitemid` int(11) NOT NULL,
                                          `vbarcode` varchar(50) NOT NULL,
                                          `iqtyonhand` int(11) DEFAULT '0',
                                          `npack` int(11) NOT NULL DEFAULT '0',
                                          `created` varchar(45) NOT NULL,
                                          PRIMARY KEY (`snapshotid`)
                                        ) COMMENT='with open status'";
                    DB::connection('mysql_dynamic')->select($table_create_query);
                }*/
                
                // $insert_item_query = "INSERT INTO mst_physical_inventory_snapshot SET  ipiid = '" . $ipiid . "', iitemid = '" . ($itemid) . "', vbarcode = '" . ($itemdata['vbarcode']) . "', iqtyonhand = '" . ($itemdata['iqtyonhand']) . "', npack = '" . ($itemdata['npack']) . "', created = '" . $dcreatedate . "' ";
                // $insert  = DB::connection('mysql_dynamic')->insert($insert_item_query);
                
                //=====creating values for bulk insert===================
					$sql[] = "( '" . (int)$ipiid . "', '" . ($itemid) . "', '" . ($itemdata['vbarcode']) . "', '" . ($itemdata['iqtyonhand']) . "', '" . ($itemdata['npack']) . "', '" . ($dcreatedate) . "' )";
                
            }
            $insert_item_query = "INSERT INTO mst_physical_inventory_snapshot (ipiid, iitemid, vbarcode, iqtyonhand, npack, created) VALUES ".implode(',', $sql)." ";
            $insert  = DB::connection('mysql_dynamic')->insert($insert_item_query);
            
            $text  = "Ref. number ".$vrefnumber.PHP_EOL.
            "No of snapshot items: ".count($selected_itemid).PHP_EOL.
            "-------------------------".PHP_EOL;
            
            $file_path = storage_path("logs/physicalInventory/physical_inventory.log");
                
            //open a file in public folder
            $myfile = fopen($file_path, 'a');
            fwrite($myfile, $text);
            fclose($myfile);
            
            session()->forget('selected_itemid');
            session()->forget('scanned_selected_itemid');
            session()->put('ipiid', $ipiid);
            return $ipiid;
        }
    }
    
    public function check_status()
    {
        $result = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_physicalinventory WHERE estatus != 'Close' AND vtype = 'Physical' ");
        $result = array_map(function ($value) {
            return (array)$value;
        }, $result);
            
        return $result;
    }

    public function addPhysicalInventory($datas = array(),$status = null) {
       
        $success =array();
        $error =array();
        $ipiid = null;
        if(isset($datas) && count($datas) > 0){
           
            foreach ($datas as $key => $data) {

                if(!empty($status)){
                    $status = $status;
                }else{
                    $status = ($data['estatus']);
                }
               
               try {
                   
                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventory SET  vpinvtnumber = '" . ($data['vpinvtnumber']) . "',`vrefnumber` = '" . ($data['vrefnumber']) . "', nnettotal = '" . ($data['nnettotal']) . "',`ntaxtotal` = '" . ($data['ntaxtotal']) . "',`dcreatedate` = '" . ($data['dcreatedate']) . "', estatus = '" . $status . "', vordertitle = '" . ($data['vordertitle']) . "', vnotes = '" . ($data['vnotes']) . "', dlastupdate = '" . ($data['dlastupdate']) . "', vtype = '" . ($data['vtype']) . "', ilocid = '" . (int)($data['ilocid']) . "', dcalculatedate = '" . ($data['dcalculatedate']) . "', dclosedate = '" . ($data['dclosedate']) . "',SID = '" . (int)(session()->get('sid'))."'");

                $sql = "INSERT INTO trn_physicalinventory SET  vpinvtnumber = '" . ($data['vpinvtnumber']) . "',`vrefnumber` = '" . ($data['vrefnumber']) . "', nnettotal = '" . ($data['nnettotal']) . "',`ntaxtotal` = '" . ($data['ntaxtotal']) . "',`dcreatedate` = '" . ($data['dcreatedate']) . "', estatus = '" . $status . "', vordertitle = '" . ($data['vordertitle']) . "', vnotes = '" . ($data['vnotes']) . "', dlastupdate = '" . ($data['dlastupdate']) . "', vtype = '" . ($data['vtype']) . "', ilocid = '" . (int)($data['ilocid']) . "', dcalculatedate = '" . ($data['dcalculatedate']) . "', dclosedate = '" . ($data['dclosedate']) . "',SID = '" . (int)(session()->get('sid'))."'";

                    $ipiid = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id;
                   
                    if(count($data['items']) > 0){
                        foreach ($data['items'] as $k => $item) {
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',`vitemid` = '" . ($item['vitemid']) . "', vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "', beforeQOH = '".($item['beforeQOH'])."', afterQOH = '".($item['afterQOH'])."', SID = '" . (int)(session()->get('sid'))."'");
                            //========commanted query is only for column iqtyonhand and dunitprice in store 1001============
                            // DB::connection('mysql_dynamic')->select("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',`vitemid` = '" . ($item['vitemid']) . "', vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "', iqtyonhand = '" . ($item['iqtyonhand']) . "', dunitprice = '" . ($item['dunitprice']) . "', SID = '" . (int)(session()->get('sid'))."'");
                        }
                    }
                }
                catch (QueryException $e) {
                    // duplicate entry exception
                    dd($e);
                   $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                catch (QueryException $e) {
                    // other mysql exception (not duplicate key entry)
                    dd($e);
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                catch (\Exception $e) {
                    // not a MySQL exception
                   dd($e);
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
            }
        }

        $success['success'] = 'Successfully Added Physical Inventory';
        $success['ipiid'] = $ipiid;
        
        return $success;
    }
    
    public function editlistPhsicalInventory($datas = array(),$status = null) {

        $success =array();
        $error =array();
        
        if(isset($datas) && count($datas) > 0){
            foreach ($datas as $key => $data) {

                if(!empty($status)){
                    $status = $status;
                }else{
                    $status = ($data['estatus']);
                }

                try {

                    DB::connection('mysql_dynamic')->update("UPDATE trn_physicalinventory SET  vpinvtnumber = '" . ($data['vpinvtnumber']) . "',`vrefnumber` = '" . ($data['vrefnumber']) . "', nnettotal = '" . ($data['nnettotal']) . "',`ntaxtotal` = '" . ($data['ntaxtotal']) . "',`dcreatedate` = '" . ($data['dcreatedate']) . "', estatus = '" . $status . "', vordertitle = '" . ($data['vordertitle']) . "', vnotes = '" . ($data['vnotes']) . "', dlastupdate = '" . ($data['dlastupdate']) . "', vtype = '" . ($data['vtype']) . "', ilocid = '" . (int)($data['ilocid']) . "', dcalculatedate = '" . ($data['dcalculatedate']) . "', dclosedate = '" . ($data['dclosedate']) . "' WHERE ipiid = '" . (int)($data['ipiid']) . "'");

                    if(count($data['items']) > 0){

                        $physical_ids = DB::connection('mysql_dynamic')->select("SELECT `ipidetid` FROM trn_physicalinventorydetail WHERE ipiid='" . (int)($data['ipiid']) . "' ");

                        if(count($physical_ids) > 0){
                            foreach ($physical_ids as $k => $physical_id) {
                                DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_physicalinventorydetail',`Action` = 'delete',`TableId` = '" . (int)$physical_id->ipidetid . "',SID = '" . (int)(session()->get('sid'))."'");
                            }
                        }

                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_physicalinventorydetail WHERE ipiid='" . (int)($data['ipiid']) . "' ");

                        foreach ($data['items'] as $k => $item) {

                            $inventory_detail_datas = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_physicalinventorydetail WHERE ipiid='" . (int)($data['ipiid']) . "' AND  vitemid='" . (int)($item['vitemid']) . "' ");

                            if(count($inventory_detail_datas) > 0){

                                DB::connection('mysql_dynamic')->update("UPDATE trn_physicalinventorydetail SET vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "' WHERE ipiid='" . (int)($data['ipiid']) . "' AND  vitemid='" . (int)($item['vitemid']) . "'");
                                
                            //========commanted query is only for column iqtyonhand and dunitprice in store 1001============ 
                                // DB::connection('mysql_dynamic')->select("UPDATE trn_physicalinventorydetail SET vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "', iqtyonhand = '" . ($item['iqtyonhand']) . "', dunitprice = '" . ($item['dunitprice']) . "' WHERE ipiid='" . (int)($data['ipiid']) . "' AND  vitemid='" . (int)($item['vitemid']) . "'");

                            }else{
                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)($data['ipiid']) . "',`vitemid` = '" . ($item['vitemid']) . "', vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "',beforeQOH = '".($item['beforeQOH'])."',afterQOH = '".($item['afterQOH'])."', SID = '" . (int)(session()->get('sid'))."'");
                            
                            //========commanted query is only for column iqtyonhand and dunitprice in store 1001============
                                // DB::connection('mysql_dynamic')->select("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)($data['ipiid']) . "',`vitemid` = '" . ($item['vitemid']) . "', vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "', iqtyonhand = '" . ($item['iqtyonhand']) . "', dunitprice = '" . ($item['dunitprice']) . "', SID = '" . (int)(session()->get('sid'))."'");
                            }
                            

                        }

                    }else{
                        $physical_ids = DB::connection('mysql_dynamic')->select("SELECT `ipidetid` FROM trn_physicalinventorydetail WHERE ipiid='" . (int)($data['ipiid']) . "' ");

                        if(count($physical_ids) > 0){
                            foreach ($physical_ids as $k => $physical_id) {
                                DB::connection('mysql_dynamic')->select("INSERT INTO mst_delete_table SET  TableName = 'trn_physicalinventorydetail',`Action` = 'delete',`TableId` = '" . (int)$physical_id['ipidetid'] . "',SID = '" . (int)(session()->get('sid'))."'");
                            }
                        }

                        DB::connection('mysql_dynamic')->delete("DELETE FROM trn_physicalinventorydetail WHERE ipiid='" . (int)($data['ipiid']) . "' ");
                    }

                }
                catch (\MySQLDuplicateKeyException $e) {
                    // duplicate entry exception
                    echo "exit1";
                    dd($e);
                   $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                catch (\MySQLException $e) {
                    // other mysql exception (not duplicate key entry)
                    echo "exit2";
                    dd($e);
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }
                catch (\Exception $e) {
                    // not a MySQL exception
                    echo "exit3";
                    dd($e);
                    $error['error'] = $e->getMessage(); 
                    return $error; 
                }

            }

        }

        $success['success'] = 'Successfully Updated Physical Inventory';
        return $success;
    }
    
    public function getPhysicalInventory($ipiid) {
        $data = array();
        $query = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_physicalinventory WHERE ipiid='" .(int)$ipiid. "'");

        $inventory_detail_datas = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_physicalinventorydetail WHERE ipiid='" . (int)$ipiid . "' ");
        $data = $query;
        $data['items'] = $inventory_detail_datas;
        return $data;
    }
    
    public function getLastInsertedID() {
        $query = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");

        return $query;
    }
    
    public function getPhysicalInventorySearch($search) {
        $inventory_datas = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_physicalinventory WHERE vpinvtnumber LIKE  '%" .($search). "%' OR vrefnumber LIKE  '%" .($search). "%' OR vtype LIKE  '%" .($search). "%' OR vordertitle LIKE  '%" .($search). "%' ");
        
        $data = array();
        $inventory_datas = json_decode(json_encode($inventory_datas), true);
        foreach ($inventory_datas as $key => $inventory_data) {
            $inventory_detail_datas = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_physicalinventorydetail WHERE ipiid='" . (int)($inventory_data['ipiid']) . "' ");
            $data[$key] = $inventory_data;
            $data[$key]['items'] = $inventory_detail_datas;
        }

        return $data;
    }
    
    public function calclulatePost($datas = array()) {
       
        $success =array();
        $error =array();

        $data = $datas[0];
        if(isset($datas) && count($datas) > 0){

            if($data['vtype'] == 'Physical'){

                $cal_date = $data['dcalculatedate'];
                $to_date = date('Y-m-d');

                if(count($data['items']) > 0){
                    $temp_items = array();
                    foreach ($data['items'] as $k => $item) {
                       
                        $sql = "SELECT ifnull(SUM(trn_sd.ndebitqty),0) as debitqty FROM trn_salesdetail trn_sd , trn_sales trn_s WHERE trn_s.vtrntype='Transaction' AND  trn_sd.ndebitqty > 0 AND date_format(trn_s.dtrandate,'%Y-%m-%d') >= '".$cal_date."' AND date_format(trn_s.dtrandate,'%Y-%m-%d') <= '".$to_date."' AND trn_s.isalesid=trn_sd.isalesid AND trn_sd.vitemcode='". ($item['vbarcode']) ."'";

                        $query_data = DB::connection('mysql_dynamic')->select($sql)->row;

                        $item['itotalunit'] = $item['itotalunit'] - $query_data['debitqty'];
                        $item['ndebitqty'] = $item['itotalunit'] / $item['npackqty'];
                        $item['ndebitextprice'] = number_format((float)$item['itotalunit'] * $item['nunitcost'], 2, '.', '');

                        $temp_items[] = $item;
                    }

                    $data['items'] = $temp_items;
                    $datas[0]['items'] = $temp_items;
                    
                }
            }
           
            $status = 'Close';
            if(isset($data['ipiid'])){
                $this->editlistPhsicalInventory($datas,$status);
                $trn_physicalinventory_id = $data['ipiid'];
            }else{
                $add_physical = $this->addPhysicalInventory($datas,$status);
                $trn_physicalinventory_id = $add_physical['ipiid'];
            }

           try {
                if(count($data['items']) > 0){
                    
                    foreach ($data['items'] as $k => $item) {
                        //update QOH in mst_item table
                        $parent_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                        $parent_item = json_decode(json_encode($parent_item[0]), true);
                        
                        if($parent_item['visinventory'] == 'Yes'){  
                            if($parent_item['isparentchild'] == 1){
                                if($data['vtype'] == 'Waste'){
                                    
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'WstQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '0', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){ 
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
                                        
                                        $x_general = new \stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->is_child = 'Yes';
                                        $x_general->parentmasterid = $old_item_values['parentmasterid'];
                                        $x_general->current_waste_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                            
                                        $x_general->new_item_values = $new_item_values;
                                        
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
                                            
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '0', general = '" . $x_general . "', source = 'Waste', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history


                                    $parent_master_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)($parent_item['parentmasterid']) . "' ");
                                    $parent_master_item = isset($parent_master_item[0])?(array)$parent_master_item[0]:[];
                                    
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_master_item['iitemid'] . "',vbarcode = '" . ($parent_master_item['vbarcode']) . "', vtype = 'WstQOH', noldamt = '" . ($parent_master_item['iqtyonhand']) . "', nnewamt = '" . (($parent_master_item['iqtyonhand']) - ($item['itotalunit'])) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_master_item;
                                        unset($old_item_values['itemimage']);
    
                                        $x_general_parent = new \stdClass();
                                        $x_general_parent->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general_parent->is_parent = 'Yes';
                                        $x_general_parent->current_waste_item_values = $item;
                                        $x_general_parent->old_item_values = $old_item_values;
                                        try{
    
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_master_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_master_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_master_item['iqtyonhand']) . "', newamount = '". (($parent_master_item['iqtyonhand']) - ($item['itotalunit'])) ."', source = 'Waste', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                        $trn_webadmin_history_last_id_parent = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id;
                                    }
                                    //trn_webadmin_history
                                    
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  iqtyonhand = iqtyonhand-'" . ($item['itotalunit']) . "' WHERE iitemid='" . (int)$parent_item['parentmasterid'] . "'");
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['parentmasterid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        
                                        $x_general_parent->new_item_values = $new_item_values;
                                        
                                        $x_general_parent = addslashes(json_encode($x_general_parent));
                                        try{
                                        
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_parent . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_parent . "'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                }else if($data['vtype'] == 'Physical'){

                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'PhyQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '0', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
    
                                        $x_general = new \stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->is_child = 'Yes';
                                        $x_general->parentmasterid = $old_item_values['parentmasterid'];
                                        $x_general->current_physical_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
                                        
                                            DB::connection('mysql_dynamic')->select("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'PhyQOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '0', general = '" . $x_general . "', source = 'Physical', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                    
                                    
                                    $parent_master_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)($parent_item['parentmasterid']) . "' ");
                                    $parent_master_item = isset($parent_master_item[0])?(array)$parent_master_item[0]:[];
                                    
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->select("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_master_item['iitemid'] . "',vbarcode = '" . ($parent_master_item['vbarcode']) . "', vtype = 'PhyQOH', noldamt = '" . ($parent_master_item['iqtyonhand']) . "', nnewamt = '" . ($item['itotalunit']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_master_item;
                                        unset($old_item_values['itemimage']);
    
                                        $x_general_parent = new \stdClass();
                                        $x_general_parent->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general_parent->is_parent = 'Yes';
                                        $x_general_parent->current_physical_item_values = $item;
                                        $x_general_parent->old_item_values = $old_item_values;
                                    try{
                                        
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_master_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_master_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_master_item['iqtyonhand']) . "', newamount = '". ($item['itotalunit']) ."', source = 'Physical', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                    }
                                    catch (\Exception $e) {
                                        Log::error($e);
                                    }
                                        $trn_webadmin_history_last_id_parent = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id;
                                    }
                                    //trn_webadmin_history
                                    
                                    $update_sql = "UPDATE mst_item SET  iqtyonhand = '" . ($item['itotalunit']) . "' WHERE iitemid='" . (int)$parent_item['parentmasterid'] . "'";
                                    DB::connection('mysql_dynamic')->update($update_sql);

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['parentmasterid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        
                                        $x_general_parent->new_item_values = $new_item_values;
                                        
                                        $x_general_parent = addslashes(json_encode($x_general_parent));
                                        try{
                                            
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_parent . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_parent . "'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                    
                                }else{
                                    
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'AdjQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '0', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
                                        
                                        $x_general = new \stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->is_child = 'Yes';
                                        $x_general->parentmasterid = $old_item_values['parentmasterid'];
                                        $x_general->current_adjustment_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
    
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
    
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '0', general = '" . $x_general . "', source = 'Adjustment', historydatetime = NOW(),SID = '" . (int)(session('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history

                                    $parent_master_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)($parent_item['parentmasterid']) . "' ");
                                    
                                    $parent_master_item = isset($parent_master_item[0])?(array)$parent_master_item[0]:[];
                                    
                                    
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_master_item['iitemid'] . "',vbarcode = '" . ($parent_master_item['vbarcode']) . "', vtype = 'AdjQOH', noldamt = '" . ($parent_master_item['iqtyonhand']) . "', nnewamt = '" . (($parent_master_item['iqtyonhand']) + ($item['itotalunit'])) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory

                                    //trn_webadmin_history
                                    
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_master_item;
                                        unset($old_item_values['itemimage']);
    
                                        $x_general_parent = new \stdClass();
                                        $x_general_parent->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general_parent->is_parent = 'Yes';
                                        $x_general_parent->current_adjustment_item_values = $item;
                                        $x_general_parent->old_item_values = $old_item_values;
                                        try{
    
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_master_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_master_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_master_item['iqtyonhand']) . "', newamount = '". (($parent_master_item['iqtyonhand']) + ($item['itotalunit'])) ."', source = 'Adjustment', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                        $trn_webadmin_history_last_id_parent = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id;
                                    }
                                    //trn_webadmin_history

                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  iqtyonhand = iqtyonhand+'" . ($item['itotalunit']) . "' WHERE iitemid='" . (int)$parent_item['parentmasterid'] . "'");

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values =DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['parentmasterid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        
                                        $x_general_parent->new_item_values = $new_item_values;
                                        
                                        $x_general_parent = addslashes(json_encode($x_general_parent));
                                        try{
                                        
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_parent . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_parent . "'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                }
                            }else{
                                if($data['vtype'] == 'Waste'){
                                
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'WstQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '" . (($parent_item['iqtyonhand']) - ($item['itotalunit'])) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
                                        
                                        $x_general = new \stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->current_waste_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        try{
                                        
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". (($parent_item['iqtyonhand']) - ($item['itotalunit'])) ."', source = 'Waste', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                        $trn_webadmin_history_last_id = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id;
                                    }
                                    //trn_webadmin_history

                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  iqtyonhand = iqtyonhand-'" . ($item['itotalunit']) . "' WHERE iitemid='" . (int)($item['vitemid']) . "'");

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values =  DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($item['vitemid']) ."' ");
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
    
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                        //trn_webadmin_history
                                        }
                                }else if($data['vtype'] == 'Physical'){
                                    
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->select("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'PhyQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '" . ($item['itotalunit']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
                                        
                                        $x_general = new stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->current_physical_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        try{
                                        
                                        DB::connection('mysql_dynamic')->select("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". ($item['itotalunit']) ."', source = 'Physical', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (Exception $e) {
                                            Log::error($e);
                                        }
                                        $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                        
                                        $trn_webadmin_history_last_id = $return[0]->historyid;
                                        // $trn_webadmin_history_last_id = $this->db2->getLastId();
                                    }
                                    //trn_webadmin_history
    
                                    DB::connection('mysql_dynamic')->select("UPDATE mst_item SET  iqtyonhand = '" . ($item['itotalunit']) . "' WHERE iitemid='" . (int)($item['vitemid']) . "'");

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($item['vitemid']) ."' ")->row;
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
    
                                        DB::connection('mysql_dynamic')->select("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                }else{
                                    
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'AdjQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '" . (($parent_item['iqtyonhand']) + ($item['itotalunit'])) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
    
                                        $x_general = new \stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->current_adjustment_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        try{
                                        // echo "INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". (($parent_item['iqtyonhand']) - ($item['ndebitqty'])) ."', source = 'Adjustment', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'";exit;
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". (($parent_item['iqtyonhand']) - ($item['ndebitqty'])) ."', source = 'Adjustment', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                        $trn_webadmin_history_last_id = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id;
                                    }
                                    //trn_webadmin_history

                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  iqtyonhand = iqtyonhand-'" . ($item['ndebitqty']) . "' WHERE iitemid='" . (int)($item['vitemid']) . "'");

                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($item['vitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
                                           
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                        }
                                        catch (\Exception $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
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
        }

        $success['success'] = 'Successfully Calculated/Posted';
        return $success;
    }

    
    public function getPhysicalInventoryByIpiid($ipiid)
    {
        
        try {
            
            $query = DB::connection('mysql_dynamic')->select("SELECT ipiid, vrefnumber, vordertitle, dcreatedate, estatus, vordertitle, vnotes, dcalculatedate, dclosedate from trn_physicalinventory WHERE ipiid = '".$ipiid."' ");
            $query = isset($query[0])?(array)$query[0]:[];
        }
        
        catch (QueryException $e) {
            // not a MySQL exception
           
            $error['error'] = $e->getMessage(); dd($error);
            return $error; 
        }
        return $query;
    }
    
    public function getPhyInventoryItemByIpiid($ipiid, $start_from=null, $length=null, $condition = null)
    {
        // $limit = $length;
        $limit = 20;
        try{
            // $sql_items = "SELECT tpid.ipidetid, tpid.ipiid, tpid.vitemid, tpid.vitemname, tpid.vbarcode, tpid.npackqty, tpid.ndebitqty, tpid.ndiffqty, mpis.iqtyonhand FROM trn_physicalinventorydetail as tpid LEFT JOIN mst_physical_inventory_snapshot as mpis ON (tpid.ipiid = mpis.ipiid AND tpid.vitemid = mpis.iitemid) WHERE tpid.ipiid = '".$ipiid."' ORDER BY tpid.vitemid LIMIT ". $start_from.", ".$limit;
            if(isset($condition)){
                
                $sql_items = "SELECT tpid.ipidetid, tpid.ipiid, tpid.vitemid, tpid.vitemname, tpid.vbarcode, tpid.npackqty, tpid.ndebitqty, tpid.ndiffqty FROM trn_physicalinventorydetail as tpid WHERE tpid.ipiid = '".$ipiid."' AND tpid.ndiffqty != '".$condition."' ORDER BY tpid.vbarcode LIMIT ". $start_from.", ".$limit;
            
            }else{
                $sql_items = "SELECT tpid.ipidetid, tpid.ipiid, tpid.vitemid, tpid.vitemname, tpid.vbarcode, tpid.npackqty, tpid.ndebitqty, tpid.ndiffqty FROM trn_physicalinventorydetail as tpid WHERE tpid.ipiid = '".$ipiid."' ORDER BY tpid.vbarcode LIMIT ". $start_from.", ".$limit;
            }
            //dd($sql_items);
            $query = DB::connection('mysql_dynamic')->select($sql_items); 
            
            $query = array_map(function ($value) {
                return (array)$value;
            }, $query);
        } 
        
        catch (QueryException $e) {
            // not a MySQL exception
           
            $error['error'] = $e->getMessage(); dd($error);
            return $error; 
        }
    
        return $query;
    }
    
    public function getPhysicalInventoriesByTypeTotal($vtype) {
        
        $sql = "SELECT * FROM trn_physicalinventory WHERE vtype='" . $vtype . "' ";

        $inventory_datas = DB::connection('mysql_dynamic')->select($sql);

        $data = count($inventory_datas);

        return $data;
    }
    
    public function getPhysicalInventoriesByType($vtype, $datas = array()) {
        $data = array();

        $sql = "SELECT * FROM trn_physicalinventory WHERE vtype='" . $vtype . "' ";

        if(isset($datas['searchbox']) && !empty($datas['searchbox'])){
            $sql .= " AND ipiid= ". ($datas['searchbox']);
        }

        $sql .= ' ORDER BY LastUpdate DESC';

        if (isset($datas['start']) || isset($datas['limit'])) {
            if ($datas['start'] < 0) {
                $datas['start'] = 0;
            }

            if ($datas['limit'] < 1) {
                $datas['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$datas['start'] . "," . (int)$datas['limit'];
        }

        $inventory_datas = DB::connection('mysql_dynamic')->select($sql);

        foreach ($inventory_datas as $key => $inventory_data) {
            $inventory_detail_datas = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_physicalinventorydetail WHERE ipiid='" . (int)($inventory_data->ipiid) . "' ");
            $data[$key] = $inventory_data;
            $data[$key]->items = $inventory_detail_datas;
        }

        return $data;
    }
    
    public function commit($data)
    {
        $success =array();
        $error =array();
        $trn_physicalinventory_id = $data['ipiid'];
        try {
                if(count($data['items']) > 0){
                    
                    DB::connection('mysql_dynamic')->update("UPDATE trn_physicalinventory SET estatus = 'Close', dclosedate = '".$data['dclosedate']."' WHERE ipiid = '".$data['ipiid']."' ");
                    //dd("UPDATE trn_physicalinventory SET estatus = 'Close', dclosedate = '".$data['dclosedate']."' WHERE ipiid = '".$data['ipiid']."' ");
                    foreach ($data['items'] as $k => $item) {
                        //update QOH in mst_item table
                        $parent_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)($item['vitemid']) . "' ");
                        $parent_item = isset($parent_item[0])?(array)$parent_item[0]:[];
                        
                            if($parent_item['isparentchild'] == 1){
                                
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'PhyQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '0', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){ 
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
                                        
                                        $x_general = new \stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->is_child = 'Yes';
                                        $x_general->parentmasterid = $old_item_values['parentmasterid'];
                                        $x_general->current_physical_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['iitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
                                            
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'PhyQOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '0', general = '" . $x_general . "', source = 'Physical', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                    
                                    
                                    $parent_master_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid='" . (int)($parent_item['parentmasterid']) . "' ");
                                    $parent_master_item = isset($parent_master_item[0])?(array)$parent_master_item[0]:[];
                                    
                                    //trn_itempricecosthistory
                                    if(isset($parent_master_item['iitemid'] )){
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_master_item['iitemid'] . "',vbarcode = '" . ($parent_master_item['vbarcode']) . "', vtype = 'PhyQOH', noldamt = '" . ($parent_master_item['iqtyonhand']) . "', nnewamt = '" . ($item['itotalunit']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    }
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_master_item;
                                        unset($old_item_values['itemimage']);
                                        
                                        $x_general_parent = new \stdClass();
                                        $x_general_parent->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general_parent->is_parent = 'Yes';
                                        $x_general_parent->current_physical_item_values = $item;
                                        $x_general_parent->old_item_values = $old_item_values;
                                        try{
                                            if(isset($parent_master_item['iitemid'])){
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_master_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_master_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_master_item['iqtyonhand']) . "', newamount = '". ($item['itotalunit']) ."', source = 'Physical', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                            }
                                                
                                            }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                        $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                        
                                        $trn_webadmin_history_last_id_parent = $return[0]->historyid;
                                        // $trn_webadmin_history_last_id_parent = $this->db2->getLastId();
                                    }
                                    //trn_webadmin_history
                                    
                                    $update_sql = "UPDATE mst_item SET  iqtyonhand = '" . ($item['itotalunit']) . "' WHERE iitemid='" . (int)$parent_item['parentmasterid'] . "'";
                                    DB::connection('mysql_dynamic')->update($update_sql);
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($parent_item['parentmasterid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        
                                        $x_general_parent->new_item_values = $new_item_values;
                                        
                                        $x_general_parent = addslashes(json_encode($x_general_parent));
                                        try{
                                            
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_parent . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_parent . "'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history

                                
                            }else{
                                
                                    //trn_itempricecosthistory
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'PhyQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '" . ($item['itotalunit']) . "', iuserid = '" . Auth::user()->iuserid . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                                    //trn_itempricecosthistory
                                    
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $old_item_values = $parent_item;
                                        unset($old_item_values['itemimage']);
                                        
                                        $x_general = new \stdClass();
                                        $x_general->trn_physicalinventory_id = $trn_physicalinventory_id;
                                        $x_general->current_physical_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;
                                        try{
                                            
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->iuserid . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". ($item['itotalunit']) ."', source = 'Physical', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                        $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                                        
                                        $trn_webadmin_history_last_id = $return[0]->historyid;
                                        
                                    }
                                    //trn_webadmin_history
                                        
                                    $update_sql = "UPDATE mst_item SET  iqtyonhand = '" . ($item['itotalunit']) . "' WHERE iitemid='" . (int)($item['vitemid']) . "'";
                                     DB::connection('mysql_dynamic')->update($update_sql);
                                     
                                    //trn_webadmin_history
                                    $result = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                                    $result = isset($result[0])?(array)$result[0]:[];
                                    if(count($result)){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($item['vitemid']) ."' ");
                                        $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;
                                        $x_general = addslashes(json_encode($x_general));
                                        try{
                                                
                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                                        }
                                        catch (QueryException $e) {
                                            Log::error($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                
                            } 

                        
                    }

                }
                
            }
            
            catch (QueryException $e) {
                // not a MySQL exception
               
                $error['error'] = $e->getMessage(); 
                return $error; 
            }
            
        $success['success'] = 'Successfully Calculated/Commit';
        $success['estatus'] = 'Close';
        return $success;
        
        
    }
    
    public function addNewPhysicalInventory($datas = array(),$status = null) 
    {
        $success =array();
        $error =array();
        
        if(isset($datas) && count($datas) > 0){
            foreach ($datas as $key => $data) {
                
                $items = $data['items'];
                
                if(!empty($status)){
                    $status = $status;
                }else{
                    $status = ($data['estatus']);
                }
                    
                $ipiid = ($data['ipiid']);
                
               try {
                    DB::connection('mysql_dynamic')->update("UPDATE trn_physicalinventory SET  vpinvtnumber = '" . ($data['vpinvtnumber']) . "',`vrefnumber` = '" . ($data['vrefnumber']) . "', nnettotal = '" . ($data['nnettotal']) . "',`ntaxtotal` = '" . ($data['ntaxtotal']) . "', estatus = '" . $status . "', vordertitle = '" . ($data['vordertitle']) . "', vnotes = '" . ($data['vnotes']) . "', dlastupdate = '" . ($data['dlastupdate']) . "', vtype = '" . ($data['vtype']) . "', ilocid = '" . ($data['ilocid']) . "', dcalculatedate = '" . ($data['dcalculatedate']) . "', dclosedate = '" . ($data['dclosedate']) . "',SID = '" . (int)(session()->get('sid'))."' WHERE ipiid = '".$ipiid."' ");
                    
                    $query = "INSERT INTO trn_physicalinventorydetail (ipiid, `vitemid`, vitemname ,`vunitcode` ,`vunitname` , ndebitqty , ncreditqty , ndebitunitprice , ncrediteunitprice, nordtax, ndebitextprice, ncrditextprice, ndebittextprice, ncredittextprice, vbarcode, vreasoncode, ndiffqty, vvendoritemcode, npackqty, nunitcost, itotalunit, SID, beforeQOH, afterQOH) VALUES ".implode(',', $items)." ";
                    
                    DB::connection('mysql_dynamic')->insert($query);
                        
                    // if(count($data['items']) > 0){
                    
                    //     foreach ($data['items'] as $k => $item) {
                            
                    //         DB::connection('mysql_dynamic')->select("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',`vitemid` = '" . ($item['vitemid']) . "', vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "',SID = '" . (int)(session()->get('sid'))."'");
                        
                    //     //========commanted query is only for column iqtyonhand and dunitprice in store 1001============
                    //         // DB::connection('mysql_dynamic')->select("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',`vitemid` = '" . ($item['vitemid']) . "', vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "', iqtyonhand = '" . ($item['iqtyonhand']) . "', dunitprice = '" . ($item['dunitprice']) . "', SID = '" . (int)(session()->get('sid'))."'");
                    //     }
                    
                    // }                    
                    
                }
                catch (QueryException $e) {
                    
                   $error['error'] = $e->getMessage();
                    return $error; 
                }
                
            }
        }
        
        $success['success'] = 'Successfully Added Physical Inventory';
        $success['ipiid'] = $ipiid;
        return $success;
    }
    
    public function editNewPhysicalInventory($datas = array()) 
    {
        $success =array();
        $error =array();
        
        if(isset($datas) && count($datas) > 0){
            foreach ($datas as $key => $data) {
                $items = $data['items'];
                $ipiid = ($data['ipiid']);
                      
                try {
                    DB::connection('mysql_dynamic')->update("UPDATE trn_physicalinventory SET nnettotal = '" . ($data['nnettotal']) . "', vordertitle = '" . ($data['vordertitle']) . "', dcalculatedate = '" . ($data['dcalculatedate']) . "' WHERE ipiid = '".$ipiid."' ");
                        
                    if(!empty($data['items'])){
                        
                        DB::connection('mysql_dynamic')->update($items);
                        
                        // DB::connection('mysql_dynamic')->select("DELETE FROM trn_physicalinventorydetail WHERE ipiid = '".$ipiid."' ");
                        // $query_update = "INSERT INTO trn_physicalinventorydetail (ipiid, vitemid, ndebitqty, ndebitextprice, ndiffqty, itotalunit) VALUES ".implode(',', $items)." ";
                        
                        // foreach ($data['items'] as $k => $item) {
                            
                        //     $query_update = "UPDATE trn_physicalinventorydetail SET ndebitqty = '" . ($item['ndebitqty']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', itotalunit = '" . ($item['itotalunit']) . "' WHERE ipiid = '".$ipiid."' AND vitemid = '".$item['vitemid']."' ";
                        //     DB::connection('mysql_dynamic')->select($query_update);
                        // // print_r($query_update);
                        // //========commanted query is only for column iqtyonhand and dunitprice in store 1001============
                        //     // DB::connection('mysql_dynamic')->select("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',`vitemid` = '" . ($item['vitemid']) . "', vitemname = '" . ($item['vitemname']) . "',`vunitcode` = '" . ($item['vunitcode']) . "',`vunitname` = '" . ($item['vunitname']) . "', ndebitqty = '" . ($item['ndebitqty']) . "', ncreditqty = '" . ($item['ncreditqty']) . "', ndebitunitprice = '" . ($item['ndebitunitprice']) . "', ncrediteunitprice = '" . ($item['ncrediteunitprice']) . "', nordtax = '" . ($item['nordtax']) . "', ndebitextprice = '" . ($item['ndebitextprice']) . "', ncrditextprice = '" . ($item['ncrditextprice']) . "', ndebittextprice = '" . ($item['ndebittextprice']) . "', ncredittextprice = '" . ($item['ncredittextprice']) . "', vbarcode = '" . ($item['vbarcode']) . "', vreasoncode = '" . ($item['vreasoncode']) . "', ndiffqty = '" . ($item['ndiffqty']) . "', vvendoritemcode = '" . ($item['vvendoritemcode']) . "', npackqty = '" . ($item['npackqty']) . "', nunitcost = '" . ($item['nunitcost']) . "', itotalunit = '" . ($item['itotalunit']) . "', iqtyonhand = '" . ($item['iqtyonhand']) . "', dunitprice = '" . ($item['dunitprice']) . "', SID = '" . (int)(session()->get('sid'))."'");
                        // }
                        
                    }
                        
                }
                catch (QueryException $e) {
                    
                  $error['error'] = $e->getMessage();
                    return $error; 
                }
                
            }
        }
        
        $success['success'] = 'Successfully Updated Physical Inventory';
        $success['ipiid'] = $ipiid;
        return $success;
    }
    
    public function assign_user_phyinventory($data = array(), $ipiid, $selected_userid)
    {
        
        $check = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM mst_physical_inventory_assign_users WHERE ipiid = '".$ipiid."' ");
        
        if(isset($check) && count($check)){
            
            $query = "DELETE FROM mst_physical_inventory_assign_users WHERE ipiid = '".$ipiid."' AND user_id NOT IN ('".implode(',', $selected_userid)."') ";
            // dd($query);
            $info = DB::connection('mysql_dynamic')->delete($query);
            if($info){
                
                $exist_ids = DB::connection('mysql_dynamic')->select("SELECT ipiid, user_id FROM mst_physical_inventory_assign_users WHERE ipiid = '".$ipiid."' ");
                $exist_ids = array_map(function ($value) {
                    return (array)$value;
                }, $exist_ids);
            
                foreach($exist_ids as $user_id){
                    
                    if(in_array($user_id['user_id'], $selected_userid) == true){
                        
                        $key = array_search($user_id['user_id'], $selected_userid);
                        unset($selected_userid[$key]);
                    }
                    // print_r($selected_userid);
                }
                
                foreach($selected_userid as $id){
                    $values[] = "('".$ipiid."', '".$id."', '".session()->get('sid')."', 'Active')";
                }
                $query = "INSERT INTO mst_physical_inventory_assign_users (ipiid, user_id, SID, status) VALUES ".implode(',', $values)." ";
                // dd($query);
                $result = DB::connection('mysql_dynamic')->insert($query);
                return $result;
            }
        }else{
            
            $query = "INSERT INTO mst_physical_inventory_assign_users (ipiid, user_id, SID, status) VALUES ".implode(',', $data)." ";
            // dd($query);
            $result = DB::connection('mysql_dynamic')->insert($query);
            return $result;
        }
        
    }
    
}
