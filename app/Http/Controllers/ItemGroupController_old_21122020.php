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
        $input = $req->get('input');
    
        foreach($input as $v){
            ItemGroup::find($v['iitemgroupid'])->delete();
           
            ItemGroupDetail::where('iitemgroupid',$v['iitemgroupid'])->delete();
        }

        Session::flash('message','Itemgroup Deleted Successfully!');
        return response()->json(['status'=> 0 ], 200);
    }

    public function savegroupname(Request $req)
    {
        $input = $req->all();
        
        $validator = Validator::make($input, [
            'groupname' => 'required|unique:App\Model\ItemGroup,vitemgroupname'
        ]);

        if ($validator->fails()) { 
            
            
            return response()->json($validator->messages(), 402);
            // return redirect('/itemgroup')                    ->withErrors($validator);
        }


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
                'grpname' =>  [
                                'required',
                                Rule::unique('mysql_dynamic.itemgroup', 'vitemgroupname')->ignore($input['grpid'], 'iitemgroupid'),
                            ],
            ], $messages);

            if ($validator->fails()) {                
                
                return response()->json($validator->messages()->getMessages(), 422);
               
            }


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
            $delete_group_detail = ItemGroupDetail::where('iitemgroupid',$req->input('grpid'))->where('vsku',$v)->delete();
        }
      
        $req->session()->flash('message',' Success: You have modified ItemGroup Successfully!');
        
       return response()->json(['key' => 'value'], 200);


    }


    public function myfunction($value) 
    { 
        return $value['vsku']; 
    }
}
