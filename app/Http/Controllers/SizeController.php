<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\SizeRequest;
use App\Model\ Size;
use Session;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SizeController extends Controller
{
    public function size()
    {
        $size = Size::paginate(10);
       return view('administration.size',['sizedata'=>$size]);
    }

    public function insertsize(Request $request)
    {
        $input = $request->all();
        //dd($input);
        $messages = [
                        'vsize.unique' => 'The name has already been taken !',
                    ];
        
        $validator = Validator::make($input, [
            'vsize' => 'required|unique:App\Model\Size,vsize'
        ], $messages);

        if ($validator->fails()) { 
            //$request->session()->flash('error', 'Error:  Size Name Required!');
               
            return redirect('/size')
                    ->withErrors($validator);
            
        }
        
        $sql = array();
        
        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            }
            foreach($stores as $store){
                    $insert_query = "Insert INTO u".$store.".mst_size (vsize) values('".$input['vsize']."')";  
                    array_push($sql,$insert_query);
                    $inserting = DB::connection("mysql")->select($insert_query); 
                }
                $request->session()->flash('message','Success: You have Added Size Successfully!');
                return redirect('/size'); 
                
        }else{
            $size = new Size;
            $size->vsize = $request->input('vsize');
            $size->save();
            $request->session()->flash('message','Success: You have Added Size Successfully!');
            return redirect('/size');
            
        }
    }

    public function sizesearch(Request $req)
    {
          $size = Size::where('vsize','LIKE','%'.$req->input('q').'%')->get();
         
          return response()->json(compact('size'));
        
    }

    public function edit_list(Request $request){
        $input = $request->all();
        // dd($input);
        foreach($input['data'] as $inp){
            $messages = [
                            'vsize.unique' => 'The name has already been taken !',
                        ];
            
            $validator = Validator::make($inp, [
                            'vsize' =>  [
                            'required',
                        Rule::unique('mysql_dynamic.mst_size', 'vsize')->ignore($inp['isizeid'], 'isizeid'),
                        ],
                        ], $messages);
            
            if ($validator->fails()) { 
                return response()->json($validator->messages()->getMessages(), 422);
            }
            $sql = array();
            if(isset($input['stores_hq'])){
                $sizes = DB::connection('mysql_dynamic')->select("Select * from mst_size where isizeid = '".$inp['isizeid']."' ");

                foreach($sizes as $size){
                    $vsizeValue = $size->vsize;
                }
                foreach($input['stores_hq'] as $store){
                    $size_id= DB::connection("mysql")->select("SELECT isizeid  FROM u".$store.".mst_size WHERE vsize = '".$vsizeValue."' ");
                    foreach($size_id as $sizeId){
                        $id_size = $sizeId->isizeid;
                    }
                    if(count($size_id) > 0){
                        $update_query = "Update u".$store.".mst_size set  vsize ='".$inp['vsize']."' where isizeid = '".$id_size."' ";
                        
                        array_push($sql,$update_query);
                        DB::connection("mysql")->select($update_query);
                    }else{
                        $insert_query = "Insert INTO u".$store.".mst_size (vsize) values('".$inp['vsize']."')";  
                      
                        $inserting = DB::connection("mysql")->select($insert_query); 
                    }
                    
                    // return redirect(route('size'))->with('message','Size Updated Successfully!!');
                }
            }else{
                $update_query = "Update mst_size set  vsize ='".$inp['vsize']."' where isizeid = '".$inp['isizeid']."' ";
                DB::connection("mysql_dynamic")->select($update_query);
            }
           
        }
        $request->session()->flash('message','Success: You have modified Size Successfully!');
        // return response()->json(['key' => 'value'], 200);
        return redirect('/size');
    }

    public function delete(Request $req)
    {
        $input = $req->all();
        
        foreach($input as $value){
            
            if(isset($value['stores_hq'])){
                $stores = $value['stores_hq'];
                foreach($stores as $store){
                    
                    $size_id = '';
                    $size_name = DB::connection("mysql")->select("select * from u".$store.".mst_size where isizeid = '".$value['isizeid']."' ");
                    foreach($size_name as $size){
        	           $size_id =  $size->isizeid;
        	        }
                    DB::connection("mysql")->delete("DELETE FROM u".$store.".mst_size WHERE isizeid = '".$size_id."' ");
                }
            }else{
                $delete_size = Size::find($value['isizeid'])->delete();
            }
            //Size::find($v['id'])->delete();
        }
        Session::flash('message','Size Deleted Successfully!');
        return response()->json(['status'=> 0, 'success'=>'Size Deleted Successfully!' ]);
    }
    
    public function duplicatesize(Request $request){
        $input = $request->all(); 
       
        $size_values = $input['avArr'];
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        foreach($size_values as $k => $value ){
            $sizes = DB::connection('mysql_dynamic')->select("Select * from mst_size where isizeid = '".$value['isizeid']."' ");
    
            foreach($sizes as $size){
                $vsizeValue = $size->vsize;
            }
            foreach($stores as $store){
                $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_size where vsize = '".$vsizeValue."' "); 
                if(count($query) == 0){
                  array_push($notExistStore, $store->id); 
                }
            }
        }
        
        return $notExistStore;
    }

}   

