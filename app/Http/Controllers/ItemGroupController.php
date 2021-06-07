<?php

namespace App\Http\Controllers;
use App\Model\ItemGroup;
use App\Model\ItemGroupDetail;
use App\Model\Item;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;
use DB;

class ItemGroupController extends Controller
{
    public function itemgroup()
    {
        $itemgroup = ItemGroup::orderBy('LastUpdate', 'desc')->paginate(10);
        return view('items.itemgroup',['itemgroup' =>$itemgroup]);
        
        // ,['itemgroup' => $itemgroup]);
    }

    public function additemgroup()
    {
        $item = Item::paginate(10);
        return view('items.additemgroup',['item' =>$item]);
    }

    public function  edititemgroup()
    { 
        $itemlist = Item::all();
        return view('items.edititemgroup',['itemlist' =>$itemlist]);
    }

    public function itemformgroupsearch(Request $req)
    {
        $itemgroup = ItemGroup::where('vitemgroupname','LIKE','%'.$req->input('q').'%')->get();

        return response()->json(compact('itemgroup'));
    }

    public function itemsearch(Request $req)
    {
        // dd($req->input('name'));

        $sku = $req->input('sku') !== null && !empty(trim($req->input('sku')))?$req->input('sku'):'';
        $name = $req->input('name') !== null && !empty(trim($req->input('name')))?$req->input('name'):'';

        $item = Item::where('vbarcode','LIKE','%'.$sku.'%')
                    ->where('vitemname','LIKE','%'.$name.'%')
                    ->whereNotIn('vbarcode',$req->input('barcodes'))
                    ->select('iitemid', 'vitemname', 'vitemcode', 'dunitprice')->get();
      
        return response()->json(['item' => $item], 200);
    }

    

    public function deletegroupname(Request $req)
    {
        $input = $req->all();
        
        if(isset($input['stores_hq'])){
             foreach($input['data'] as $group){
                $itemgroupname = ItemGroup::select('vitemgroupname')->where('iitemgroupid', '=', $group['iitemgroupid'] )->get();
                if(isset($itemgroupname[0])){
                    $vitemgroupname = $itemgroupname[0]->vitemgroupname;
                }
                
                foreach($input['stores_hq'] as $store){
                    $dup_query = "Select * from u".$store.".itemgroup where  vitemgroupname = '".$vitemgroupname."' ";
                    
                    $dup_entry = DB::connection("mysql")->select($dup_query);
                 
                    if(isset($dup_entry[0])){
                        $iitemgroupid = $dup_entry[0]->iitemgroupid;
                    }
                    
                    DB::connection('mysql')->delete("DELETE FROM u".$store.".itemgroup WHERE iitemgroupid = '".(int)$iitemgroupid."' ");
                    DB::connection('mysql')->delete("DELETE FROM u".$store.".itemgroupdetail WHERE iitemgroupid = '".(int)$iitemgroupid."'  ");
                }
            }
            Session::flash('message','Itemgroup Deleted Successfully!');
            return response()->json(['status'=> 0 ], 200);
        }else{
            foreach($input as $v){
                ItemGroup::find($v['iitemgroupid'])->delete();
                ItemGroupDetail::where('iitemgroupid',$v['iitemgroupid'])->delete();
            }
            Session::flash('message','Itemgroup Deleted Successfully!');
            return response()->json(['status'=> 0 ], 200);
        }
    }

    public function savegroupname(Request $req)
    {
        $input = $req->all();
        
        $validator = Validator::make($input, [
            'groupname' => 'required|unique:App\Model\ItemGroup,vitemgroupname'
        ]);

        if ($validator->fails()) { 
            return response()->json($validator->messages(), 402);
            // return redirect('/itemgroup')->withErrors($validator);
        }

        if(isset($input['stores_hq'])){
            foreach($input['stores_hq'] as $store){
                DB::connection("mysql")->statement("INSERT INTO u".$store.".itemgroup (vitemgroupname) VALUES ('".$req->input('groupname')."') ");
            }
        
            foreach($input['barcodes'] as $v){
                foreach($input['stores_hq'] as $store){
                    $itembarcodecheck = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item where vbarcode = '".$v['barcode']."' ");
                    $iitemgroupid = DB::connection('mysql')->select("SELECT * FROM u".$store.".itemgroup order by iitemgroupid DESC LIMIT 1");
                    if(isset($iitemgroupid[0])){
                        $groupid = $iitemgroupid[0]->iitemgroupid;
                    }
                    
                    if(count($itembarcodecheck)){
                        DB::connection('mysql')->statement("INSERT INTO u".$store.".itemgroupdetail (iitemgroupid, vsku, isequence) VALUES ('".(int)$groupid."', '".$v['barcode']."', '".(int)$v['sequence']."') ");
                    }
                }
            }
    
            $req->session()->flash('message','Success: You have Added GroupDetail Successfully!');
            return response()->json(['status' => 'success'], 200);
        }else{
            $itemgroup = new ItemGroup;
            $itemgroup->vitemgroupname = $req->input('groupname');
            $itemgroup->save();
        
            foreach($input['barcodes'] as $v){
                // ItemGroupName::find($v['id'])
                $itemgroupdetail = new ItemGroupDetail;
                $itemgroupdetail->iitemgroupid = $itemgroup->iitemgroupid;
                $itemgroupdetail->vsku = $v['barcode'];
                $itemgroupdetail->isequence = $v['sequence'];
               
                $itemgroupdetail->save();
            }
    
            $req->session()->flash('message','Success: You have Added GroupDetail Successfully!');
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function editgroupdetail($iitemgroupid)
    {
        $groupname = ItemGroup::find($iitemgroupid);
        
        $group = new ItemGroupDetail();
        $groupdetail= $group->getItemGroupData($iitemgroupid);
           
        return view('items.edititemgroup',["groupname"=>$groupname,"groupdetail"=>$groupdetail]);
    }

    public function updategroupdetail(Request $req)
    {
        $input = $req->all();
        //   dd($input);

        $messages = [
            'grpname.unique' => 'The name is already taken !',
        ];
        $validator = Validator::make($input, [
            'grpname'=>[
                            'required',
                            Rule::unique('mysql_dynamic.itemgroup', 'vitemgroupname')->ignore($input['grpid'], 'iitemgroupid'),
                        ],
        ], $messages);
        if ($validator->fails()) {                
            return response()->json($validator->messages()->getMessages(), 422);
        }
        
        if(isset($input['stores_hq'])){
            
            $itemgroupname = ItemGroup::select('vitemgroupname')->where('iitemgroupid', '=', $req->input('grpid') )->get();
            
            if(isset($itemgroupname[0])){
                $group_name = $itemgroupname[0]->vitemgroupname;
            }
            foreach($input['stores_hq'] as $store){
                $item_group_id = DB::connection('mysql')->select("Select * from u".$store.".itemgroup  WHERE vitemgroupname  = '".$group_name."' ");
                if(isset($item_group_id[0])){
                    $itemGroup_id = $item_group_id[0]->iitemgroupid;
                }
                $update_groupname_query = "UPDATE u".$store.".itemgroup SET  vitemgroupname = '".$req->input('grpname')."' WHERE iitemgroupid  = '".(int)$itemGroup_id."' ";
                
                DB::connection('mysql')->statement($update_groupname_query);
                
                $old_array = DB::connection('mysql')->select("SELECT vsku FROM u".$store.".itemgroupdetail where iitemgroupid = '".(int)$itemGroup_id."' ");
                
                $changed_old_array = array();
                foreach($old_array as $vsku){
                    array_push($changed_old_array, $vsku->vsku);
                }
                
                
                
                foreach($input['barcodes'] as $v){
                    if(!in_array($v['barcode'], $changed_old_array)){
                        //insert
                        $itembarcodecheck = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item where vbarcode = '".$v['barcode']."' ");
                        if(count($itembarcodecheck)){
                            $insert_item_group_details = "INSERT INTO u".$store.".itemgroupdetail (iitemgroupid, vsku, isequence) VALUES ('".(int)$itemGroup_id."', '".$v['barcode']."', '".(int)$v['sequence']."') ";
                            DB::connection('mysql')->statement($insert_item_group_details);
                        }
                    }
                    else{
                        // updating
                        $itembarcodecheck = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_item where vbarcode = '".$v['barcode']."' ");
                        if(count($itembarcodecheck)){
                            $update_item_group_details = "UPDATE  u".$store.".itemgroupdetail SET isequence = '".(int)$v['sequence']."' where iitemgroupid = '".(int)$itemGroup_id."' AND  vsku = '".$v['barcode']."'";
                            DB::connection('mysql')->statement($update_item_group_details);
                        }
                    }
        
                    //remove values
                    $index = array_search($v['barcode'], $changed_old_array);
                    if($index !== false){
                        unset($changed_old_array[$index]);
                    }
        
                }
                foreach($changed_old_array as $v){

                    $groupItemid = DB::connection('mysql')->select("SELECT id FROM u".$store.".itemgroupdetail where iitemgroupid = '".(int)$itemGroup_id."' AND  vsku = '".$v."' ");
                    
                    $insert_delete_perm = "INSERT INTO u".$store.".mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$groupItemid[0]->id . "',SID = '" . (int)(session()->get('sid'))."'";
                    $query =  DB::connection('mysql')->insert($insert_delete_perm);
                    
                    $delete_query = "DELETE FROM u".$store.".itemgroupdetail  WHERE iitemgroupid = '".(int)$itemGroup_id."' AND  vsku = '".$v."' ";
                    DB::connection('mysql')->statement($delete_query);
                    // $delete_group_detail = ItemGroupDetail::where('iitemgroupid',$req->input('grpid'))->where('vsku',$v)->delete();
                }
                
                
            }
            $req->session()->flash('message',' Success: You have modified ItemGroup Successfully!');
            return response()->json(['key' => 'value'], 200);
        }else {
            //Update groupname
            $itemgroupname = ItemGroup::find($req->input('grpid'));
            $itemgroupname->vitemgroupname = $req->input('grpname');
            $itemgroupname->save();
    
            
            //Update groupdetails
            $old_array  = ItemGroupDetail::where('iitemgroupid',$req->input('grpid'))->select('vsku')->get();
            
    
            $changed = $old_array->map(function($value, $key){
                return $value['vsku'];
            });
            
           
            $changed_old_array = $changed->toArray();
    
            foreach($input['barcodes'] as $v){
    
                if(!in_array($v['barcode'], $changed_old_array)){
                    //insert
                    $itemgroupdetailinsert = new ItemGroupDetail;
                    $itemgroupdetailinsert->iitemgroupid = $req->input('grpid');
                    $itemgroupdetailinsert->vsku = $v['barcode'];
                    $itemgroupdetailinsert->isequence = $v['sequence'];
                    $itemgroupdetailinsert->save();
                }
                else{
                    $itemgroupdetailupdate = ItemGroupDetail::where('iitemgroupid',$req->input('grpid'))->where('vsku',$v['barcode'])->first();
                    $itemgroupdetailupdate->isequence = $v['sequence'];
                    $itemgroupdetailupdate->save();
                }
    
                //remove values
                $index = array_search($v['barcode'], $changed_old_array);
                if($index !== false){
                    unset($changed_old_array[$index]);
                }
    
            }
    
    
            foreach($changed_old_array as $v){

                $groupItemid = DB::connection('mysql_dynamic')->select("SELECT id FROM itemgroupdetail where iitemgroupid = '".(int)$req->input('grpid')."' AND  vsku = '".$v."' ");
                  
                $insert_delete_perm = "INSERT INTO mst_delete_table SET  TableName = 'itemgroupdetail',`Action` = 'delete',`TableId` = '" . (int)$groupItemid[0]->id . "',SID = '" . (int)(session()->get('sid'))."'";
                $query =  DB::connection('mysql_dynamic')->insert($insert_delete_perm);
                
                $delete_group_detail = ItemGroupDetail::where('iitemgroupid',$req->input('grpid'))->where('vsku',$v)->delete();
            }
          
            $req->session()->flash('message',' Success: You have modified ItemGroup Successfully!');
            
            return response()->json(['key' => 'value'], 200);
        }

    }


    public function myfunction($value) 
    { 
        return $value['vsku']; 
    }
    
    
    public function duplicatehqitemgroups(Request $request){
        $input = $request->all();
        $avalable = array();
        $stores = session()->get('stores_hq');
        foreach($stores as $store){
            foreach($input as $group){
                $itemgroupname = ItemGroup::select('vitemgroupname')->where('iitemgroupid', '=', $group['iitemgroupid'] )->get();
                if(isset($itemgroupname[0])){
                    $dup_query = "Select * from u".$store->id.".itemgroup where  vitemgroupname = '".$itemgroupname[0]->vitemgroupname."' ";
                    $dup_entry = DB::connection("mysql")->select($dup_query);
                    // dd($dup_entry);
                    if(count($dup_entry) == 0){
                        // dd($store->id);
                        array_push($avalable, $store->id); 
                    }
                }
            }
        }
       return $avalable;
    }
    
    /****** this function duplicateitemgroup() is to check item group avilable or not in child *******/
    public function duplicateitemgroup(Request $request){
        $input = $request->all();
        $avalable = array();
        $stores = session()->get('stores_hq');
        foreach($stores as $store){
            $itemgroupname = ItemGroup::select('vitemgroupname')->where('iitemgroupid', '=', $input['grpid'] )->get();
            if(isset($itemgroupname[0])){
                $dup_query = "Select * from u".$store->id.".itemgroup where  vitemgroupname = '".$itemgroupname[0]->vitemgroupname."' ";
                $dup_entry = DB::connection("mysql")->select($dup_query);
                // dd($dup_entry);
                if(count($dup_entry) == 0){
                    // dd($store->id);
                    array_push($avalable, $store->id); 
                }
            }
        }
       return $avalable;
    }
}
