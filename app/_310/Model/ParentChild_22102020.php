<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ParentChild extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'mst_item';
    protected $primaryKey = 'iitemid';
    public $timestamps = false;

    public function parent_child($datas = array())
    {
        $sql ="SELECT p.iitemid pid, c.iitemid cid, p.vbarcode pbarcode, c.vbarcode cbarcode, p.LastUpdate,c.vbarcode csku ,p.vbarcode psku,
        p.vitemname pname, c.vitemname cname, p.isparentchild ppid, c.isparentchild  cpid,c.npack cpack
        FROM mst_item c
        join mst_item p ON c.parentid = p.iitemid";
        
        $sql .= ' ORDER BY c.LastUpdate DESC';

        $query = DB::connection('mysql_dynamic')->select($sql);
        // $query = isset($query[0])?(array)$query[0]:[];
        return $query;
        
    }

    public function addParentItem($data = array()) 
    {
      $success =array();
      $error =array();
      $quatity_on_hand = 0;
      
      if(isset($data) && count($data) > 0){
        
         
          try {
              $parent_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($data['parent_item_id']) ."' ");
              $parent_item = isset($parent_item[0])?(array)$parent_item[0]:[];

              $quatity_on_hand = $quatity_on_hand + $parent_item['iqtyonhand'];

              DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  isparentchild = 2,parentid = 0,parentmasterid = 0 WHERE iitemid= '". (int)($data['parent_item_id']) ."'");

              $child_item = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($data['child_item_id']) ."' ");
              $child_item = isset($child_item[0])?(array)$child_item[0]:[];

              $quatity_on_hand = $quatity_on_hand + $child_item['iqtyonhand'];
              
              //Child qoh update
              
              $query = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
              $query = isset($query[0])?(array)$query[0]:[];
                     
              $ipid = $query['ipiid'];
                       

              $vrefnumber = str_pad($ipid+1,9,"0",STR_PAD_LEFT);
                
              DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventory SET  vrefnumber= $vrefnumber, estatus='Close',dcreatedate=CURRENT_TIMESTAMP, vtype = 'Perent Update',SID = '" . (int)(session()->get('sid'))."'");
              
              $query1 = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
              $query1 = isset($query1[0])?(array)$query1[0]:[];

              $ipiid = $query1['ipiid'];
                
                
                DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',
                                vitemid = '" .$child_item['iitemid']. "',
                                vitemname = '" .$child_item['vitemname'] . "',
                                vunitcode = '" . $child_item['vunitcode'] . "',
                                ndebitqty= '" . $child_item['iqtyonhand']. "', 
                                vbarcode = '" . $child_item['vbarcode']. "', 
                                SID = '" . (int)(session()->get('sid'))."'
                ");
                      
              
              
              //child qoh update end 
              
              
              //perent Qoh Update
              $query = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
              $query = isset($query[0])?(array)$query[0]:[];

              $ipid = $query['ipiid'];
                       
              $vrefnumber = str_pad($ipid+1,9,"0",STR_PAD_LEFT);
                
              DB::connection('mysql_dynamic')->insert("INSERT INTO trn_physicalinventory SET  vrefnumber= $vrefnumber,estatus='Close',dcreatedate=CURRENT_TIMESTAMP, vtype = 'Child Update',SID = '" . (int)(session()->get('sid'))."'");
              
              $query1 = DB::connection('mysql_dynamic')->select("SELECT ipiid FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");
              $query1 = isset($query1[0])?(array)$query1[0]:[];

              $ipiid = $query1['ipiid'];
                
                
                DB::connection('mysql_dynamic')->select("INSERT INTO trn_physicalinventorydetail SET  ipiid = '" . (int)$ipiid . "',
                                  vitemid = '" .$parent_item['iitemid']. "',
                                  vitemname = '" .$parent_item['vitemname'] . "',
                                  vunitcode = '" . $parent_item['vunitcode'] . "',
                                  ndebitqty= '" . $child_item['iqtyonhand']. "', 
                                  vbarcode = '" . $parent_item['vbarcode']. "', 
                                  SID = '" . (int)(session()->get('sid'))."'
                ");
                      
              
              
              //perent qoh update end

              //trn_itempricecosthistory
              DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . ($data['child_item_id']) . "',vbarcode = '" . ($child_item['vbarcode']) . "', vtype = 'ItemQOH', noldamt = '" . ($child_item['iqtyonhand']) . "', nnewamt = '0', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
              //trn_itempricecosthistory

              //trn_webadmin_history
              $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
              $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
              
              if(count($trn_webadmin_history)){
                  $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($data['child_item_id']) ."' ");
                  $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];
                  
                  unset($old_item_values['itemimage']);
                  $x_general = new \stdClass();
                  $x_general->old_item_values = $old_item_values;
                  try{

                  DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . ($data['child_item_id']) . "',userid = '" . Auth::user()->id . "',barcode = '" . ($child_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($child_item['iqtyonhand']) . "', newamount = '0', source = 'ItemEditAddParent', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                  }
                  catch (QueryException $e) {
                      Log::error($e);
                  }
                  $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                            
                  $trn_webadmin_history_last_id = $return[0]->historyid;
                  // $trn_webadmin_history_last_id = $this->db2->getLastId();
              }
              
              DB::connection('mysql_dynamic')->update("UPDATE mst_item SET  dcostprice='". ($parent_item['dcostprice']) ."',nunitcost='". ($parent_item['nunitcost']) ."', npack= '". (int)($data['cnpack']) ."',iqtyonhand=0, isparentchild = 1,parentid = '". ($data['parent_item_id']) ."',parentmasterid = '". ($data['parent_item_id']) ."' WHERE iitemid= '". (int)($data['child_item_id']) ."'");

              //trn_webadmin_history
              $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
              $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
              
              if(count($trn_webadmin_history)){
                  $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($data['child_item_id']) ."' ");
                  $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];

                  unset($new_item_values['itemimage']);
                  $x_general->is_child = 'Yes';
                  $x_general->parentmasterid = $new_item_values['parentmasterid'];
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

              $child_items = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`iqtyonhand`,`vbarcode`,`dcostprice`,`npack`,`nunitcost` FROM mst_item WHERE parentmasterid= '". (int)($data['child_item_id']) ."' ");
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
                        
                          $x_general = new \stdClass();
                          $x_general->old_item_values = $old_item_values;
                          try{

                          DB::connection('mysql_dynamic')->select("INSERT INTO trn_webadmin_history SET  itemid = '" . ($chi_item['iitemid']) . "',userid = '" . Auth::user()->id . "',barcode = '" . ($chi_item['vbarcode']) . "', type = 'Cost', oldamount = '" . $chi_item['dcostprice'] . "', newamount = '". (($chi_item['npack']) * (($chi_item['nunitcost']))) ."', source = 'ItemEditAddParent', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                          }
                          catch (QueryException $e) {
                              Log::error($e);
                          }
                          $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                            
                          $trn_webadmin_history_last_id = $return[0]->historyid;
                          // $trn_webadmin_history_last_id = $this->db2->getLastId();
                      }
                      //trn_webadmin_history

                      $quatity_on_hand = $quatity_on_hand + $chi_item['iqtyonhand'];

                      DB::connection('mysql_dynamic')->update("UPDATE mst_item SET dcostprice=npack*'". ($parent_item['nunitcost']) ."',nunitcost='". ($parent_item['nunitcost']) ."',parentmasterid='". ($parent_item['iitemid']) ."' WHERE iitemid= '". (int)($chi_item['iitemid']) ."'");

                      //trn_itempricecosthistory
                      $new_update_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)$chi_item['iitemid'] ."' ");
                      $new_update_values = isset($new_update_values[0])?(array)$new_update_values[0]:[];

                      if($chi_item['dcostprice'] != $new_update_values['dcostprice']){

                          DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . $new_update_values['iitemid'] . "',vbarcode = '" . ($new_update_values['vbarcode']) . "', vtype = 'ItemCost', noldamt = '" . ($chi_item['dcostprice']) . "', nnewamt = '" . ($new_update_values['dcostprice']) . "', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
                      }
                      //trn_itempricecosthistory
                      //trn_webadmin_history
                      $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
                      $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
                      
                      if(count($trn_webadmin_history)){
                          $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($chi_item['iitemid']) ."' ");
                          $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                          unset($new_item_values['itemimage']);
                          $x_general->is_child = 'Yes';
                          $x_general->parentmasterid = $new_item_values['parentmasterid'];
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

              //trn_itempricecosthistory
              DB::connection('mysql_dynamic')->insert("INSERT INTO trn_itempricecosthistory SET  iitemid = '" . ($data['parent_item_id']) . "',vbarcode = '" . ($parent_item['vbarcode']) . "', vtype = 'ItemQOH', noldamt = '" . ($parent_item['iqtyonhand']) . "', nnewamt = '". ($quatity_on_hand) ."', iuserid = '" . Auth::user()->id . "', dhistorydate = CURDATE(), thistorytime = CURTIME(),SID = '" . (int)(session()->get('sid'))."'");
              //trn_itempricecosthistory

              //trn_webadmin_history
              $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
              $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
              
              if(count($trn_webadmin_history)){
                  $old_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($data['parent_item_id']) ."' ");
                  $old_item_values = isset($old_item_values[0])?(array)$old_item_values[0]:[];

                  unset($old_item_values['itemimage']);

                  $x_general = new \stdClass();
                  $x_general->is_parent = 'Yes';
                  $x_general->old_item_values = $old_item_values;

                  try{

                  DB::connection('mysql_dynamic')->insert("INSERT INTO trn_webadmin_history SET  itemid = '" . ($data['parent_item_id']) . "',userid = '" . Auth::user()->id . "',barcode = '" . ($parent_item['vbarcode']) . "', type = 'QOH', oldamount = '" . ($parent_item['iqtyonhand']) . "', newamount = '". ($quatity_on_hand) ."', source = 'ItemEditAddParent', historydatetime = NOW(),SID = '" . (int)(session()->get('sid'))."'");
                  }
                  catch (QueryException $e) {
                      Log::error($e);
                  }

                  $return = DB::connection('mysql_dynamic')->select("SELECT historyid FROM trn_webadmin_history ORDER BY historyid DESC LIMIT 1");
                            
                  $trn_webadmin_history_last_id = $return[0]->historyid;
                  // $trn_webadmin_history_last_id = $this->db2->getLastId();
              }
              //trn_webadmin_history

              DB::connection('mysql_dynamic')->select("UPDATE mst_item SET  iqtyonhand = '". (int)($quatity_on_hand) ."', npack= '". (int)($data['cnpack']) ."',nsellunit='". (int)($data['cnpack']) ."'  WHERE iitemid= '". (int)($data['parent_item_id']) ."'");
              $trn_webadmin_history = DB::connection('mysql_dynamic')->select("SHOW tables LIKE 'trn_webadmin_history' ");
              $trn_webadmin_history = isset($trn_webadmin_history[0])?(array)$trn_webadmin_history[0]:[];
              
              if(count($trn_webadmin_history)){
                $new_item_values = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_item WHERE iitemid= '". (int)($data['parent_item_id']) ."' ");
                $new_item_values = isset($new_item_values[0])?(array)$new_item_values[0]:[];
                
                unset($new_item_values['itemimage']);

                $x_general->new_item_values = $new_item_values;

                $x_general = addslashes(json_encode($x_general));
                try{

                DB::connection('mysql_dynamic')->select("UPDATE trn_webadmin_history SET general = '" . $x_general . "' WHERE historyid = '" . (int)$trn_webadmin_history_last_id . "'");
                }
                catch (QueryException $e) {
                    Log::error($e);
                }
              }

              $success['success'] = 'Successfully Added Parent Item';
          }
          
          catch (QueryException $e) {
              // not a MySQL QueryException
             
              $error['error'] = $e->getMessage(); 
              return $error; 
          }  
      }

      return $success;
    }

    public function removeParentItem($data = array()) {
      // print_r($data);die;
     $success =array();
     $error =array();

     if(isset($data) && count($data) > 0){
        
         try {
            // $remove_parent_item = DB::connection('mysql_dynamic')->select("SELECT `iitemid` FROM mst_item WHERE parentid in('". $data['iitemid'] ."') AND isparentchild !=0");
            // $remove_parent_item = isset($remove_parent_item[0])?(array)$remove_parent_item[0]:[];
            // dd($remove_parent_item);
            // if(count($remove_parent_item) == 0){
            //     DB::connection('mysql_dynamic')->update("UPDATE mst_item SET isparentchild=0,parentid=0,parentmasterid=0 WHERE iitemid= '". (int)($data['iitemid']) ."'");
            // }
            
            DB::connection('mysql_dynamic')->update("UPDATE mst_item SET isparentchild=0,parentid=0,parentmasterid=0 WHERE iitemid= '". (int)($data['iitemid']) ."'");
            
            $remove_parent_item_update = DB::connection('mysql_dynamic')->select("SELECT `iitemid`,`isparentchild` FROM mst_item WHERE  iitemid='". (int)($data['selected_parent_item_id']) ."'");
            $remove_parent_item_update = isset($remove_parent_item_update[0])?(array)$remove_parent_item_update[0]:[];
            
            if($remove_parent_item_update['isparentchild'] == 2){
                DB::connection('mysql_dynamic')->update("UPDATE mst_item SET isparentchild=0,parentid=0,parentmasterid=0 WHERE iitemid= '". (int)($data['selected_parent_item_id']) ."'");
            }
         }
         
         catch (QueryException $e) {
             // not a MySQL exception
            
             $error['error'] = $e->getMessage(); 
             return $error; 
         }  
     }
     $success['success'] = 'Successfully Removed Parent Item';
     return $success;
 }
}

?>