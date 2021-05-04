<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Transfer extends Model
{
   
    public function getTransfers($vtransfertype,$vvendorid) {
        if($vtransfertype == 'WarehouseToStore'){
            $query = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseitems item LEFT JOIN trn_warehouseinvoice invoice ON(item.invoiceid=invoice.vinvnum) WHERE  item.vtransfertype!='StoretoWarehouse' AND item.vvendorid='" .$vvendorid. "'");

            return $query;
        }else{
            $query = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseitems item WHERE item.vtransfertype='" .$vtransfertype. "' AND item.vvendorid='" .$vvendorid. "'");

            return $query;
        }
        
    }

    public function deleteTransferProduct($vtransfertype, $vvendorid, $vbarcode, $dreceivedate) {
        $success = array();

        $dreceivedate = \DateTime::createFromFormat('m-d-Y', $dreceivedate);
        $dreceivedate = $dreceivedate->format('Y-m-d');

        $itemgroup = $this->db2->query("DELETE FROM trn_warehouseitems WHERE vtransfertype='" . ($vtransfertype) . "' AND vvendorid='" . (int)($vvendorid) . "' AND vbarcode='" . ($vbarcode) . "' AND dreceivedate='" . ($dreceivedate) . "' ");

        $success['success'] = 'Successfully Deleted Transfer Product';
        return $success;
    
    }

    public function addTransfer($datas = array()) {
        
        $success =array();
        $error =array();

        $istoreid = $this->db2->query("SELECT * FROM mst_store")->row;

        if(isset($datas) && count($datas) > 0){
            foreach ($datas as $key => $data) {

                if(isset($data['items']) && count($data['items']) > 0){

                    if($data['vtransfertype'] == 'WarehouseToStore'){

                        $this->db2->query("INSERT INTO trn_warehouseinvoice SET  istoreid = '" . (int)$istoreid['istoreid'] . "',`vstorecode` = '" . $istoreid['vcompanycode'] . "', dinvoicedate = '" . ($data['dreceivedate']) . "',`vinvnum` = '" . ($data['vinvnum']) . "',`vvendorid` = '" . ($data['vvendorid']) . "',SID = '" . (int)( session()->get('sid'))."'");

                        $invoiceid = $this->db2->getLastId();

                    }

                    foreach ($data['items'] as $k => $item) {
                       try {
                            if($data['vtransfertype'] == 'WarehouseToStore'){
                                $this->db2->query("INSERT INTO trn_warehouseitems SET  vwhcode = '" . ($data['vwhcode']) . "',`invoiceid` = '" . $invoiceid . "', vvendorid = '" . (int)($data['vvendorid']) . "',`dreceivedate` = '" . ($data['dreceivedate']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = '" . ($data['estatus']) . "', vvendortype = '" . ($data['vvendortype']) . "', vtransfertype = '" . ($data['vtransfertype']) . "', ntransferqty = '" . ($item['ntransferqty']) . "', vsize = '" . ($item['vsize']) . "',SID = '" . (int)( session()->get('sid'))."'");
                            }else{
                                $this->db2->query("INSERT INTO trn_warehouseitems SET  vwhcode = '" . ($data['vwhcode']) . "', vvendorid = '" . (int)($data['vvendorid']) . "',`dreceivedate` = '" . ($data['dreceivedate']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = '" . ($data['estatus']) . "', vvendortype = '" . ($data['vvendortype']) . "', vtransfertype = '" . ($data['vtransfertype']) . "', ntransferqty = '" . ($item['ntransferqty']) . "', vsize = '" . ($item['vsize']) . "',SID = '" . (int)( session()->get('sid'))."'");
                            }
                            
                        }
                        catch (MySQLDuplicateKeyException $e) {
                            // duplicate entry exception
                           $error['error'] = $e->getMessage(); 
                            return $error; 
                        }
                        catch (MySQLException $e) {
                            // other mysql exception (not duplicate key entry)
                            
                            $error['error'] = $e->getMessage(); 
                            return $error; 
                        }
                        catch (Exception $e) {
                            // not a MySQL exception
                           
                            $error['error'] = $e->getMessage(); 
                            return $error; 
                        } 
                    }
                }
            }
        }

        $success['success'] = 'Successfully Added Transfer';
        return $success;
    }

    public function editlistTransfer($datas = array()) {

        $istoreid = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_store");
        $istoreid = json_decode(json_encode($istoreid), true);
        if(count($istoreid) > 0){
            $istoreid = $istoreid[0];
        }
        
        $success =array();
        $error =array();
        
        if(isset($datas) && count($datas) > 0){
            
            foreach ($datas as $key => $data) {
               
                if(isset($data['items']) && count($data['items']) > 0){

                    if($data['vtransfertype'] == 'WarehouseToStore'){

                        $trn_inv = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseinvoice WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vinvnum='" .($data['vinvnum']). "'");
                        
                        if(count($trn_inv) > 0){
                            DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseinvoice SET  istoreid = '" . (int)$istoreid['istoreid'] . "',`vstorecode` = '" . $istoreid['vcompanycode'] . "',`vinvnum` = '" . ($data['vinvnum']) . "',`dinvoicedate` = '" . ($data['dreceivedate']) . "' WHERE vvendorid='" . ($data['vvendorid']) . "' ");

                            // $invoiceid = $trn_inv['iid'];
                            $invoiceid = $data['vinvnum'];

                        }else{
                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_warehouseinvoice SET  istoreid = '" . (int)$istoreid['istoreid'] . "',`vstorecode` = '" . $istoreid['vcompanycode'] . "', dinvoicedate = '" . ($data['dreceivedate']) . "',`vinvnum` = '" . ($data['vinvnum']) . "',`vvendorid` = '" . ($data['vvendorid']) . "',SID = '" . (int)( session()->get('sid'))."'");

                            // $invoiceid = $this->db2->getLastId();
                            $invoiceid = $data['vinvnum'];
                        }

                    }else{
                        $invoiceid = null;
                    }

                    // $delete_items = $this->db2->query("SELECT `iwtrnid` FROM trn_warehouseitems WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vtransfertype='" .($data['vtransfertype']). "'")->rows;

                    // if(count($delete_items) > 0){
                    //     foreach ($delete_items as $delete_item) {
                    //        $this->db2->query("INSERT INTO mst_delete_table SET  TableName = 'trn_warehouseitems',`Action` = 'delete',`TableId` = '" . (int)$delete_item['iwtrnid'] . "',SID = '" . (int)( session()->get('sid'))."'");
                    //     }
                    // }

                    // $this->db2->query("DELETE FROM trn_warehouseitems WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vtransfertype='" .($data['vtransfertype']). "'");

                    foreach ($data['items'] as $k => $item) {
                        if($item['ntransferqty'] != ''){

                            $trn_data = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseitems WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vtransfertype='" .($data['vtransfertype']). "' AND vbarcode='" .($item['vbarcode']). "' AND invoiceid='" .($invoiceid). "'");

                            if(count($trn_data) > 0){
                                $trn_warehouseitems_id =$trn_data['iwtrnid']; 
                                if($data['vtransfertype'] == 'WarehouseToStore'){
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseitems SET  vwhcode = '" . ($data['vwhcode']) . "',`invoiceid` = '" . $invoiceid . "', vitemname = '" . ($item['vitemname']) . "', dreceivedate = '" . ($data['dreceivedate']) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = '" . ($data['estatus']) . "', vvendortype = '" . ($data['vvendortype']) . "', ntransferqty = '" . ($item['ntransferqty']) * $item['npackqty'] . "', vsize = '" . ($item['vsize']) . "' WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vtransfertype='" .($data['vtransfertype']). "' AND vbarcode='" .($item['vbarcode']). "' ");
                                }else{
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseitems SET  vwhcode = '" . ($data['vwhcode']) . "', vitemname = '" . ($item['vitemname']) . "', dreceivedate = '" . ($data['dreceivedate']) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = '" . ($data['estatus']) . "', vvendortype = '" . ($data['vvendortype']) . "', vtransfertype = '" . ($data['vtransfertype']) . "', ntransferqty = '" . ($item['ntransferqty']) * $item['npackqty'] . "', vsize = '" . ($item['vsize']) . "' WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vtransfertype='" .($data['vtransfertype']). "' AND vbarcode='" .($item['vbarcode']). "' ");
                                }
                            }else{
                                if($data['vtransfertype'] == 'WarehouseToStore'){
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_warehouseitems SET  vwhcode = '" . ($data['vwhcode']) . "',`invoiceid` = '" . $invoiceid . "', vvendorid = '" . (int)($data['vvendorid']) . "',`dreceivedate` = '" . ($data['dreceivedate']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = '" . ($data['estatus']) . "', vvendortype = '" . ($data['vvendortype']) . "', vtransfertype = '" . ($data['vtransfertype']) . "', ntransferqty = '" . ($item['ntransferqty']) * $item['npackqty'] . "', vsize = '" . ($item['vsize']) . "',SID = '" . (int)( session()->get('sid'))."'");
                                    $trn_warehouseitems_id =DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id; 
                                }else{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_warehouseitems SET  vwhcode = '" . ($data['vwhcode']) . "', vvendorid = '" . (int)($data['vvendorid']) . "',`dreceivedate` = '" . ($data['dreceivedate']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', vitemname = '" . ($item['vitemname']) . "', nitemqoh = '" . ($item['nitemqoh']) . "', npackqty = '" . ($item['npackqty']) . "', estatus = '" . ($data['estatus']) . "', vvendortype = '" . ($data['vvendortype']) . "', vtransfertype = '" . ($data['vtransfertype']) . "', ntransferqty = '" . ($item['ntransferqty']) * $item['npackqty'] . "', vsize = '" . ($item['vsize']) . "',SID = '" . (int)( session()->get('sid'))."'");
                                    $trn_warehouseitems_id =DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id; 
                                }
                            }

                            if($data['vtransfertype'] == 'WarehouseToStore'){
                                $trn_qoh_data = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseqoh WHERE ivendorid='" .(int)($data['vvendorid']). "' AND vbarcode='" .($item['vbarcode']). "'");

                                if(count($trn_qoh_data) > 0){
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseqoh SET  npack = '" . ($item['npackqty']) . "', onhandcaseqty =onhandcaseqty - '" . ($item['ntransferqty']) . "' WHERE ivendorid='" .(int)($data['vvendorid']). "' AND vbarcode='" .($item['vbarcode']). "'");
                                }else{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_warehouseqoh SET  ivendorid = '" . (int)($data['vvendorid']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', npack = '" . ($item['npackqty']) . "', onhandcaseqty = '" . ($item['ntransferqty']) . "',SID = '" . (int)( session()->get('sid'))."'");
                                }

                                $mst_item_data = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE vbarcode='" .($item['vbarcode']). "'");
                                $mst_item_data = json_decode(json_encode($mst_item_data), true);
                                
                                if(count($mst_item_data) > 0){
                                    if(isset($mst_item_data[0]) && count($mst_item_data[0]) > 0){
                                        $mst_item_data = $mst_item_data[0];
                                    }
                                    if($mst_item_data['isparentchild'] == 1){
                                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET iqtyonhand = '0' WHERE vbarcode='" . ($item['vbarcode']) . "' ");

                                        //trn_itempricecosthistory

                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $mst_item_data['iitemid'] . "',vbarcode = '" . ($item['vbarcode']) . "', vtype = 'TrnfQOH', noldamt = '" . ($mst_item_data['iqtyonhand']) . "', nnewamt = '0', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)( session()->get('sid'))."'");

                                        //trn_itempricecosthistory

                                        //trn_webadmin_history
                                        if(DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")){
                                            $old_item_values = $mst_item_data;
                                            unset($old_item_values['itemimage']);

                                            $x_general_child = new \stdClass();
                                            $x_general_child->trn_warehouseitems_id = $trn_warehouseitems_id;
                                            $x_general_child->is_child = 'Yes';
                                            $x_general_child->parentmasterid = $old_item_values['parentmasterid'];
                                            $x_general_child->current_transfer_item_values = $item;
                                            $x_general_child->old_item_values = $old_item_values;

                                            $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$mst_item_data['iitemid'] ."' ");
                                            unset($new_item_values['itemimage']);

                                            $x_general_child->new_item_values = $new_item_values;

                                            $x_general_child = addslashes(json_encode($x_general_child));
                                            try{

                                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $mst_item_data['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($mst_item_data['iqtyonhand']) . "', newamount = '0', general = '" . $x_general_child . "', source = 'Transfer', historydatetime = NOW(),SID = '" . (int)( session()->get('sid'))."'");
                                            }
                                            catch (\Exception $e) {
                                                $this->log->write($e);
                                            }
                                        }
                                            //trn_webadmin_history

                                        //trn_itempricecosthistory

                                        $parent_item = DB::connection('mysql_dynamic')->select("SELECT * FROM  mst_item WHERE iitemid='" . (int)($mst_item_data['parentmasterid']) . "'");
                                        // DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'TrnfQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '". (($parent_item['iqtyonhand']) + (($item['ntransferqty']) * ($item['npackqty']))) ."', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)( session()->get('sid'))."'");
                                        
                                        for($a=0;$a<count($parent_item); $a++){
                                            // DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item['iitemid'] . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'TrnfQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '". (($parent_item['iqtyonhand']) + (($item['ntransferqty']) * ($item['npackqty']))) ."', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)( session()->get('sid'))."'");
                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $parent_item[$a]->iitemid . "',vbarcode = '" . ($parent_item[$a]->vbarcode) . "', vtype = 'TrnfQOH', noldamt = '" . ($parent_item[$a]->iqtyonhand) . "', nnewamt = '". (($parent_item[$a]->iqtyonhand) + (($item['ntransferqty']) * ($item['npackqty']))) ."', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)( session()->get('sid'))."'");
                                        
                                        }
                                            
                                        //trn_itempricecosthistory

                                        //trn_webadmin_history
                                            if(DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")){
                                            $old_item_values = $mst_item_data;
                                            unset($old_item_values['itemimage']);

                                            $x_general_parent = new \stdClass();
                                            $x_general_parent->trn_warehouseitems_id = $trn_warehouseitems_id;
                                            $x_general_parent->is_parent = 'Yes';
                                            $x_general_parent->current_transfer_item_values = $item;
                                            $x_general_parent->old_item_values = $old_item_values;
                                            try{

                                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $parent_item['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". (($parent_item['iqtyonhand']) + (($item['ntransferqty']) * ($item['npackqty']))) ."', source = 'Transfer', historydatetime = NOW(),SID = '" . (int)( session()->get('sid'))."'");
                                            }
                                            catch (\Exception $e) {
                                                $this->log->write($e);
                                            }
                                            $trn_webadmin_history_last_id_parent = DB::connection('mysql_dynamic')->select('SELECT LAST_INSERT_ID() as last_insert_id')[0]->last_insert_id;
                                            }
                                        //trn_webadmin_history

                                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  iqtyonhand =iqtyonhand + ('" . ($item['ntransferqty']) * ($item['npackqty']) . "') WHERE iitemid='" .($mst_item_data['parentmasterid']). "'");

                                        //trn_webadmin_history
                                        if(DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")){
                                        $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$mst_item_data['parentmasterid'] ."' ");
                                        unset($new_item_values['itemimage']);
                                        $x_general_parent->new_item_values = $new_item_values;

                                        $x_general_parent = addslashes(json_encode($x_general_parent));
                                        try{

                                            DB::connection('mysql_dynamic')->update("UPDATE trn_webadmin_history SET general = '" . $x_general_parent . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id_parent . "'");
                                        }
                                        catch (\Exception $e) {
                                            $this->log->write($e);
                                        }
                                        }
                                        //trn_webadmin_history


                                    }else{
                                        
                                        DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  iqtyonhand =iqtyonhand + ('" . ($item['ntransferqty']) * ($item['npackqty']) . "') WHERE vbarcode='" .($item['vbarcode']). "'");
                                        //trn_itempricecosthistory
                                        
                                        DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $mst_item_data['iitemid'] . "',vbarcode = '" . ($item['vbarcode']) . "', vtype = 'TrnfQOH', noldamt = '" . ($mst_item_data['iqtyonhand']) . "', nnewamt = '". (($mst_item_data['iqtyonhand']) + (($item['ntransferqty']) * ($item['npackqty']))) ."', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)( session()->get('sid'))."'");
                                        
                                        //trn_itempricecosthistory

                                        //trn_webadmin_history
                                        if(DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")){
                                            $old_item_values = $mst_item_data;
                                            unset($old_item_values['itemimage']);

                                            $x_general = new \stdClass();
                                            $x_general->trn_warehouseitems_id = $trn_warehouseitems_id;
                                            $x_general->current_transfer_item_values = $item;
                                            $x_general->old_item_values = $old_item_values;
                                            
                                            $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$mst_item_data['iitemid'] ."' ");
                                            unset($new_item_values['itemimage']);

                                            $x_general->new_item_values = $new_item_values;

                                            $x_general = addslashes(json_encode($x_general));
                                            try{

                                                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $mst_item_data['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($mst_item_data['iqtyonhand']) . "', newamount = '". (($mst_item_data['iqtyonhand']) + (($item['ntransferqty']) * ($item['npackqty']))) ."', general = '" . $x_general . "', source = 'Transfer', historydatetime = NOW(),SID = '" . (int)( session()->get('sid'))."'");
                                            }
                                            catch (\Exception $e) {
                                                $this->log->write($e);
                                            }
                                        }
                                        //trn_webadmin_history
                                    }
                                }

                            }else{
                                $trn_qoh_data = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseqoh WHERE ivendorid='" .(int)($data['vvendorid']). "' AND vbarcode='" .($item['vbarcode']). "'");

                                if(count($trn_qoh_data) > 0){
                                    DB::connection('mysql_dynamic')->update("UPDATE trn_warehouseqoh SET  npack = '" . ($item['npackqty']) . "', onhandcaseqty =onhandcaseqty + '" . ($item['ntransferqty']) . "' WHERE ivendorid='" .(int)($data['vvendorid']). "' AND vbarcode='" .($item['vbarcode']). "'");
                                }else{
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_warehouseqoh SET  ivendorid = '" . (int)($data['vvendorid']) . "',`vbarcode` = '" . ($item['vbarcode']) . "', npack = '" . ($item['npackqty']) . "', onhandcaseqty = '" . ($item['ntransferqty']) . "',SID = '" . (int)( session()->get('sid'))."'");
                                }

                                $mst_item_data1 = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE vbarcode='" .($item['vbarcode']). "'");
                                $mst_item_data_obj = $mst_item_data1[0];
                                $mst_item_data = json_decode(json_encode($mst_item_data_obj), true); 	
                                // echo "<pre>";
                                // print_r($mst_item_data);
                                // die;
                                if(count($mst_item_data) > 0){
                                    DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  iqtyonhand =iqtyonhand - ('" . ($item['ntransferqty']) * ($item['npackqty']) . "') WHERE vbarcode='" .($item['vbarcode']). "'");

                                    //trn_itempricecosthistory
                                        
                                    DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $mst_item_data['iitemid'] . "',vbarcode = '" . ($item['vbarcode']) . "', vtype = 'TrnfQOH', noldamt = '" . ($mst_item_data['iqtyonhand']) . "', nnewamt = '". (($mst_item_data['iqtyonhand']) - (($item['ntransferqty']) * ($item['npackqty']))) ."', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)( session()->get('sid'))."'");
                                        
                                    //trn_itempricecosthistory

                                    //trn_webadmin_history
                                        if(DB::connection('mysql_dynamic')->select(" SHOW tables LIKE 'trn_webadmin_history'")){
                                        $old_item_values = $mst_item_data;
                                        unset($old_item_values['itemimage']);

                                        $x_general = new \stdClass();
                                        $x_general->trn_warehouseitems_id = $trn_warehouseitems_id;
                                        $x_general->current_transfer_item_values = $item;
                                        $x_general->old_item_values = $old_item_values;

                                        $sql = "SELECT * FROM mst_item WHERE iitemid= '". (int)($mst_item_data['iitemid']) ."' ";
                                        $return_data = DB::connection('mysql_dynamic')->select($sql);
                                        // echo "<pre>";
                                        // print_r($return_data);
                                        // die;
                                        $mst_item_obj = $return_data[0];
                                        $new_item_values = json_decode(json_encode($mst_item_obj), true); 
                                        
                                        unset($new_item_values['itemimage']);
                                        $x_general->new_item_values = $new_item_values;

                                        $x_general = addslashes(json_encode($x_general));
                                        try{

                                            DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . $mst_item_data['iitemid'] . "',userid = '" . Auth::user()->id . "',barcode = '" . ($item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($mst_item_data['iqtyonhand']) . "', newamount = '". (($mst_item_data['iqtyonhand']) - (($item['ntransferqty']) * ($item['npackqty']))) ."', general = '" . $x_general . "', source = 'Transfer', historydatetime = NOW(),SID = '" . (int)( session()->get('sid'))."'");
                                        }
                                        catch (\Exception $e) {
                                            $this->log->write($e);
                                        }
                                    }
                                    //trn_webadmin_history
                                }
                            }
                        }
                    }
                }else{
                    $delete_items = DB::connection('mysql_dynamic')->select("SELECT `iwtrnid` FROM trn_warehouseitems WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vtransfertype='" .($data['vtransfertype']). "'");

                    if(count($delete_items) > 0){
                        foreach ($delete_items as $delete_item) {
                            DB::connection('mysql_dynamic')->insert("INSERT INTO mst_delete_table SET  TableName = 'trn_warehouseitems',`Action` = 'delete',`TableId` = '" . (int)$delete_item['iwtrnid'] . "'SID = '" . (int)( session()->get('sid'))."'");
                        }
                    }

                    DB::connection('mysql_dynamic')->delete("DELETE FROM trn_warehouseitems WHERE vvendorid='" .(int)($data['vvendorid']). "' AND vtransfertype='" .($data['vtransfertype']). "'");
                }
            }

        }

        $success['success'] = 'Successfully Updated Transfer';
        return $success;
    }

    public function getCheckInvoice($invoice) {
        $invoices = DB::connection('mysql_dynamic')->select("SELECT * FROM trn_warehouseitems WHERE invoiceid='" .($invoice). "'");
        $return = array();
        if(count($invoices) > 0){
            $return['error'] = 'Invoice Already Exist!';
        }else{
            $return['success'] = 'Not Exist!';
        }

        return $return;

    }

    public function getTransfersData($data = array()) {

        $sql = "SELECT tw.*, ms.vstorename,msupp.vcompanyname FROM trn_warehouseinvoice as tw LEFT JOIN mst_store as ms ON (tw.istoreid=ms.istoreid) LEFT JOIN mst_supplier as msupp ON (msupp.isupplierid=tw.vvendorid)";

        $sql .= ' ORDER BY tw.LastUpdate DESC';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = DB::connection('mysql_dynamic')->select($sql);

        return $query;

    }

    public function getTransfersTotal($data = array()) {

        $sql = "SELECT * FROM trn_warehouseinvoice";

        $query = DB::connection('mysql_dynamic')->select($sql);

        return count($query);
    }

    

	public function getRightItems($data = array()) {

		$return = array();

		if(count($data) > 0){

			$item_ids = implode(',', $data);

			$query = DB::connection('mysql_dynamic')->select("SELECT iitemid,vbarcode,vitemname,iqtyonhand,npack FROM mst_item WHERE iitemid IN($item_ids) AND estatus='Active'");
			$return = $query;
		}else{
			$return['error'] = 'data not found';
		}
		return $return;
	}

	public function getLeftItems($data = array()) {

		$return = array();

		if(count($data) > 0){

			$item_ids = implode(',', $data);

			$query = DB::connection('mysql_dynamic')->select("SELECT iitemid,vbarcode,vitemname,iqtyonhand,npack FROM mst_item WHERE iitemid NOT IN($item_ids) AND estatus='Active'");
			$return = $query;
		}else{
			$query = DB::connection('mysql_dynamic')->select("SELECT iitemid,vbarcode,vitemname,iqtyonhand,npack FROM mst_item WHERE estatus='Active'");
			$return = $query;
		}
		return $return;
	}

	public function getEditLeftItems($data = array(), $vendor_id) {

		$return = array();
		$data = array();

		if(count($data) > 0){

			$item_ids = implode(',', $data);

			$query = DB::connection('mysql_dynamic')->select("SELECT iitemid,vbarcode,vitemname,iqtyonhand FROM mst_item WHERE iitemid NOT IN($item_ids) AND vsuppliercode='". $vendor_id ."' AND estatus='Active'");
			$return = $query;
		}else{
			$query = DB::connection('mysql_dynamic')->select("SELECT iitemid,vbarcode,vitemname,iqtyonhand FROM mst_item WHERE vsuppliercode='". $vendor_id ."' AND estatus='Active'");
			$return = $query;
		}
		return $return;
	}

	public function getEditRightItems($data = array(),$vtransfertype,$vendor_id) {

        $return = array();
        
		if(count($data) > 0){

			$item_ids = implode(',', $data);

			if($vtransfertype == 'WarehouseToStore'){
				// $query = $this->db2->query("SELECT mi.iitemid,mi.vbarcode,mi.vitemname,mi.iqtyonhand,twhi.vwhcode,twhi.vvendorid,twhi.dreceivedate,twhi.nitemqoh,twhi.npackqty,twhi.estatus,twhi.estatus,twhi.vvendortype,twhi.vtransfertype,twhi.vsize,twhi.ntransferqty,twhin.vinvnum,twqoh.onhandcaseqty as onhandcaseqty FROM mst_item as mi,trn_warehouseinvoice as twhin, trn_warehouseitems as twhi LEFT JOIN trn_warehouseqoh as twqoh ON(twhi.vvendorid=twqoh.ivendorid AND twhi.vbarcode=twqoh.vbarcode)  WHERE mi.vbarcode=twhi.vbarcode AND twhi.invoiceid=twhin.vinvnum AND twhi.vtransfertype='" . $vtransfertype . "' AND twhi.vvendorid='" . (int)$vendor_id . "'");
				$query = DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vbarcode,mi.vitemname,mi.iqtyonhand,mi.npack as npackqty, mi.vsize as vsize ,mi.vsuppliercode,twqoh.onhandcaseqty as onhandcaseqty FROM mst_item as mi ,trn_warehouseqoh as twqoh WHERE mi.vbarcode=twqoh.vbarcode AND mi.estatus='Active' AND twqoh.ivendorid='" . (int)$vendor_id . "' AND twqoh.onhandcaseqty > 0");

			}else{
				$query = DB::connection('mysql_dynamic')->select("SELECT mi.iitemid,mi.vbarcode,mi.vitemname,mi.iqtyonhand,twhi.vwhcode,twhi.vvendorid,twhi.dreceivedate,twhi.nitemqoh,twhi.npackqty,twhi.estatus,twhi.estatus,twhi.vvendortype,twhi.vtransfertype,twhi.vsize,twhi.ntransferqty FROM mst_item as mi,trn_warehouseitems as twhi WHERE mi.vbarcode=twhi.vbarcode AND mi.estatus='Active' AND mi.iitemid IN($item_ids) AND twhi.vtransfertype='" . $vtransfertype . "' AND twhi.vvendorid='" . (int)$vendor_id . "'");
			}

			$return = $query;
		}
		return $return;
	}

	public function getPrevRightItemIds($datas = array()) {

		$return = array();

		if(count($datas) > 0){
			foreach($datas as $data){
			$return[] = DB::connection('mysql_dynamic')->select("SELECT iitemid FROM mst_item WHERE vbarcode='" . ($data['vbarcode']) . "' AND vitemname='" . ($data['vitemname']) . "' AND estatus='Active'");
			}
		}
		$return = json_decode(json_encode($return), true);
		$item_arr = array();
		for($i=0; $i < count($return); $i++){
		    for($j=0; $j< count($return[$i]); $j++){
		        $item_arr[] = $return[$i][$j]['iitemid'];
		    }
		}
        // 		if(count($return) > 0){
        // 		    dd($return[0][0]['iitemid']);
        // 			foreach ($return as  $v) {
        //                 $v = $v[0];
        // 				if(isset($v['iitemid'])){
        // 					$item_arr[] = $v['iitemid'];
        // 				}
        //             }
        // 		}
        		return $item_arr;
	}

	public function getVendors() {

		$query = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_supplier ");
			
		return $query;
    }
    
    public function getlistItems() {
        
        $query = "SELECT `iitemid`,`vitemtype`,`vitemcode`,`vitemname`,`vunitcode`,`vbarcode`,`vpricetype`,`vcategorycode`,";
        
        if($this->session->data['new_database'] !== false){
            $query .= "`subcat_id`,";
        }
        
        $query .= "`vdepcode`,`vsuppliercode`,`iqtyonhand`,`dcostprice`,`dunitprice`,`nsaleprice` FROM mst_item WHERE estatus='Active'";
        
        $run_query = $this->db2->query($query);
        
        return $run_query->rows;
    }

    public function getItems($itemdata = array()) {
        $datas = array();
        $sql_string = '';
        
        if (isset($itemdata['searchbox']) && !empty($itemdata['searchbox'])) {
            $sql_string .= " WHERE a.iitemid= ". (int)($itemdata['searchbox']);
        }else{
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

        }

        $query = $this->db2->query("SELECT a.*, CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND, case isparentchild when 0 then a.VITEMNAME  when 1 then Concat(a.VITEMNAME,' [Child]') when 2 then  Concat(a.VITEMNAME,' [Parent]') end   as VITEMNAME FROM mst_item as a $sql_string ");
        
        if(count($query->rows) > 0){
            foreach ($query->rows as $key => $value) {
                $groupid = $this->db2->query("SELECT * FROM itemgroupdetail WHERE vsku='". $value['vbarcode'] ."'")->row;

                $itemalias = $this->db2->query("SELECT * FROM mst_itemalias WHERE vsku='". $value['vbarcode'] ."'")->rows;

                $itemslabprices = $this->db2->query("SELECT * FROM mst_itemslabprice WHERE vsku='". $value['vbarcode'] ."'")->rows;

                if($value['isparentchild'] == 2){
                    $itemchilditems = $this->db2->query("SELECT `iitemid`,`vitemname`,`npack` FROM mst_item WHERE parentmasterid='". $value['iitemid'] ."'")->rows;
                }else{
                    $itemchilditems = $this->db2->query("SELECT `iitemid`,`vitemname`,`npack` FROM mst_item WHERE parentid='". $value['iitemid'] ."'")->rows;
                }

                $itemparentitems = $this->db2->query("SELECT `iitemid`,`vitemname`,`npack`, `vbarcode` FROM mst_item WHERE iitemid='". $value['parentid'] ."'")->rows;

                $remove_parent_item = $this->db2->query("SELECT `iitemid` FROM mst_item WHERE parentid in('". $value['iitemid'] ."') AND isparentchild !=0")->rows;

                $itempacks = $this->db2->query("SELECT * FROM mst_itempackdetail WHERE iitemid='". (int)$value['iitemid'] ."' ORDER BY isequence")->rows;

                $itemvendors = $this->db2->query("SELECT * FROM mst_itemvendor as miv,mst_supplier as ms WHERE miv.ivendorid=ms.isupplierid AND  miv.iitemid='". (int)$value['iitemid'] ."'")->rows;
                
                $ndiscountper = $this->db2->query("SELECT b.buydown_amount FROM trn_buydown a LEFT JOIN trn_buydown_details b ON ( a.buydown_id = b.buydown_id ) WHERE a.status = 'Active' AND b.vbarcode = '". $value['vbarcode'] ."' ")->row;
                
                $temp = array();
                $temp['iitemid'] = $value['iitemid'];
                $temp['iitemgroupid'] = $groupid;
                $temp['itempacks'] = $itempacks;
                $temp['itemalias'] = $itemalias;
                $temp['itemvendors'] = $itemvendors;
                $temp['itemslabprices'] = $itemslabprices;
                $temp['itemchilditems'] = $itemchilditems;
                $temp['itemparentitems'] = $itemparentitems;
                $temp['remove_parent_item'] = $remove_parent_item;
                $temp['webstore'] = $value['webstore'];
                $temp['vitemtype'] = $value['vitemtype'];
                $temp['vitemcode'] = $value['vitemcode'];
                $temp['vitemname'] = $value['vitemname'];
                $temp['VITEMNAME'] = $value['VITEMNAME'];
                $temp['vunitcode'] = $value['vunitcode'];
                $temp['vbarcode'] = $value['vbarcode'];
                $temp['vpricetype'] = $value['vpricetype'];
                $temp['vcategorycode'] = $value['vcategorycode'];
                $temp['subcat_id'] = $value['subcat_id'];
                $temp['vdepcode'] = $value['vdepcode'];
                $temp['vsuppliercode'] = $value['vsuppliercode'];
                $temp['iqtyonhand'] = $value['iqtyonhand'];
                $temp['QOH'] = $value['IQTYONHAND'];
                $temp['QOH'] = $value['IQTYONHAND'];
                $temp['ireorderpoint'] = $value['ireorderpoint'];
                $temp['reorder_duration'] = $value['reorder_duration'];
                $temp['manufacturer_id'] = $value['manufacturer_id'];
                $temp['ireorderpointdays'] = $value['ireorderpointdays'];
                $temp['dcostprice'] = $value['dcostprice'];
                $temp['dunitprice'] = $value['dunitprice'];
                $temp['nsaleprice'] = $value['nsaleprice'];
                $temp['nlevel2'] = $value['nlevel2'];
                $temp['nlevel3'] = $value['nlevel3'];
                $temp['nlevel4'] = $value['nlevel4'];
                $temp['iquantity'] = $value['iquantity'];
                $temp['ndiscountper'] = $ndiscountper['buydown_amount'];
                $temp['ndiscountamt'] = $value['ndiscountamt'];
                $temp['vtax1'] = $value['vtax1'];
                $temp['vtax2'] = $value['vtax2'];
                $temp['vfooditem'] = $value['vfooditem'];
                $temp['vdescription'] = $value['vdescription'];
                $temp['dlastsold'] = $value['dlastsold'];
                $temp['visinventory'] = $value['visinventory'];
                $temp['dpricestartdatetime'] = $value['dpricestartdatetime'];
                $temp['dpriceenddatetime'] = $value['dpriceenddatetime'];
                $temp['estatus'] = $value['estatus'];
                $temp['nbuyqty'] = $value['nbuyqty'];
                $temp['ndiscountqty'] = $value['ndiscountqty'];
                $temp['nsalediscountper'] = $value['nsalediscountper'];
                $temp['vshowimage'] = $value['vshowimage'];
                if(isset($value['vshowimage']) && !empty($value['vshowimage'])){
                    $temp['itemimage'] = $value['itemimage'];
                }else{
                    $temp['itemimage'] = '';
                }
                
                $temp['vageverify'] = $value['vageverify'];
                $temp['ebottledeposit'] = $value['ebottledeposit'];
                $temp['nbottledepositamt'] = $value['nbottledepositamt'];
                $temp['vbarcodetype'] = $value['vbarcodetype'];
                $temp['ntareweight'] = $value['ntareweight'];
                $temp['ntareweightper'] = $value['ntareweightper'];
                $temp['dcreated'] = $value['dcreated'];
                $temp['dlastupdated'] = $value['dlastupdated'];
                $temp['dlastreceived'] = $value['dlastreceived'];
                $temp['dlastordered'] = $value['dlastordered'];
                $temp['nlastcost'] = $value['nlastcost'];
                $temp['nonorderqty'] = $value['nonorderqty'];
                $temp['vparentitem'] = $value['vparentitem'];
                $temp['nchildqty'] = $value['nchildqty'];
                $temp['vsize'] = $value['vsize'];
                $temp['npack'] = $value['npack'];
                $temp['nunitcost'] = $value['nunitcost'];
                $temp['ionupload'] = $value['ionupload'];
                $temp['nsellunit'] = $value['nsellunit'];
                $temp['ilotterystartnum'] = $value['ilotterystartnum'];
                $temp['ilotteryendnum'] = $value['ilotteryendnum'];
                $temp['etransferstatus'] = $value['etransferstatus'];
                $temp['vsequence'] = $value['vsequence'];
                $temp['vcolorcode'] = $value['vcolorcode'];
                $temp['vdiscount'] = $value['vdiscount'];
                $temp['norderqtyupto'] = $value['norderqtyupto'];
                $temp['vshowsalesinzreport'] = $value['vshowsalesinzreport'];
                $temp['iinvtdefaultunit'] = $value['iinvtdefaultunit'];
                $temp['LastUpdate'] = $value['LastUpdate'];
                $temp['SID'] = $value['SID'];
                $temp['stationid'] = $value['stationid'];
                $temp['shelfid'] = $value['shelfid'];
                $temp['aisleid'] = $value['aisleid'];
                $temp['shelvingid'] = $value['shelvingid'];
                $temp['rating'] = $value['rating'];
                $temp['vintage'] = $value['vintage'];
                $temp['PrinterStationId'] = $value['PrinterStationId'];
                $temp['liability'] = $value['liability'];
                $temp['isparentchild'] = $value['isparentchild'];
                $temp['parentid'] = $value['parentid'];
                $temp['parentmasterid'] = $value['parentmasterid'];
                $temp['wicitem'] = $value['wicitem'];
                
                $datas[] = $temp;
            }
        }  

        return $datas;
    }





}
