<?php

namespace App\Http\Controllers\Item;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Model\Item;
use App\Model\QuickItem;

class QuickItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
		$items = QuickItem::all()->toArray();

		
        return view('items.quick_item_list', ['items'=>$items]);
	}
	
	public function edit_list(Request $request){
        $input = $request->all();
        // dd($input);
        
        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            } 
            foreach($input['quick_item'] as  $v){
                $items = QuickItem::find($v['iitemgroupid']);
                if(isset($items)){
                    $itemgroupname = $items['vitemgroupname'];
                }
                foreach($stores as $store){
                    $quickitem_id = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_itemgroup WHERE vitemgroupname = '".$itemgroupname."' ");
                    if(isset($quickitem_id[0])){
                        $id = $quickitem_id[0]->iitemgroupid;
                    }
                    DB::connection('mysql')->statement("UPDATE u".$store.".mst_itemgroup set vitemgroupname = '".$v['vitemgroupname']."', isequence = '".$v['isequence']."' WHERE iitemgroupid = '".$id."'   ");
                }
            }
            $request->session()->flash('message',' Success: You have modified Quick Items !');
            $url = '/item/quick_item_list';
            return redirect($url);  
        }else{
            foreach($input['quick_item'] as  $v){
                $items = QuickItem::find($v['iitemgroupid']);
                $items->vitemgroupname  = $v['vitemgroupname'];
                $items->isequence       = $v['isequence'];
                $items->save();
            }
            $request->session()->flash('message',' Success: You have modified Quick Items !');
           
            $url = '/item/quick_item_list';
                            
            return redirect($url);
        }
        
    }
}
?>