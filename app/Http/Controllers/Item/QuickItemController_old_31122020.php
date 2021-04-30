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
        
        foreach($input['quick_item'] as  $v){
            $items = QuickItem::find($v['iitemgroupid']);

            $items->vitemgroupname = $v['vitemgroupname'];
            $items->isequence = $v['isequence'];

            $items->save();
        }
        $request->session()->flash('message',' Success: You have modified Quick Items !');
       
        $url = '/item/quick_item_list';
                        
        return redirect($url);
    }
}
?>