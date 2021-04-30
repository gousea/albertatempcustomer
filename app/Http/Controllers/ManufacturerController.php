<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Manufacturer;
use Session;
use DB;
//use App\Http\Requests\ManufacturerRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ManufacturerController extends Controller
{
    public function manufacturer()
    {
        $manufacturer = Manufacturer::all();
        return view('administration.manufacturer',['manufacturer'=>$manufacturer]);
    }

    public function insertmanufacturer(Request $req)
    {
        $input = $req->all();
        $validator = Validator::make($input, [
            'mfr_code' => 'required|unique:App\Model\Manufacturer,mfr_code',
            'mfr_name' => 'required|unique:App\Model\Manufacturer,mfr_name'
        ]);

        if ($validator->fails()) { 
            //$request->session()->flash('error', 'Error:  Aisle Name Required!');
            return redirect('/manufacturer')
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
                    $insert_query = "Insert INTO u".$store.".mst_manufacturer (mfr_code, mfr_name ) values('".$input['mfr_code']."', '".$input['mfr_name']."')";  
                    array_push($sql,$insert_query);
                    $inserting = DB::connection("mysql")->select($insert_query); 
                }
                $req->session()->flash('message','Success: You have Added Manufacturer Successfully!');
                return redirect('/manufacturer'); 
                
        }else{
            $manufacture = new Manufacturer;
            $manufacture->mfr_code = $req->input('mfr_code');
            $manufacture->mfr_name = $req->input('mfr_name');
            $manufacture->save();
            $req->session()->flash('message','Success: You have Added Manufacturer Successfully!');
            return redirect('/manufacturer');  
            
        }
    } 

    public function manufacturersearch(Request $req)
    {
        $manufacturer = Manufacturer::where('mfr_name','LIKE','%'.$req->input('q').'%')->orwhere('mfr_code','LIKE','%'.$req->input('q').'%')->get();
          return response()->json(compact('manufacturer'));
    }

    public function edit_list(Request $request){
        
        $input = $request->all();
        // dd($input);
        
      foreach($input['data'] as $inp) {
           
            $messages = [
                            'mfr_code.unique' => 'The manufacturer code is already taken !',
                            'mfr_name.unique' => 'The manufacturer name is already taken !',
                        ];
            $validator = Validator::make($inp, [
                            'mfr_code' =>  [
                                            'required',
                                            Rule::unique('mysql_dynamic.mst_manufacturer', 'mfr_code')->ignore($inp['mfr_id'], 'mfr_id'),
                                        ],
                            'mfr_name' =>  [
                                            'required',
                                            Rule::unique('mysql_dynamic.mst_manufacturer', 'mfr_name')->ignore($inp['mfr_id'], 'mfr_id'),
                                        ]
                        ], $messages);
                        
            if ($validator->fails()) {
                return response()->json($validator->messages()->getMessages(), 422);
            }
            
                $sql = array();
                if(isset($input['stores_hq'])){
                   if($input['stores_hq'] === session()->get('sid')){
                        $stores = [session()->get('sid')];
                    }else{
                        $stores = $input['stores_hq'];
                    }
                    $manufacture_code = DB::connection('mysql_dynamic')->select("select * from mst_manufacturer where mfr_id = '".$inp['mfr_id']."' ");
                    foreach($manufacture_code as $mfr){
                        $mfr_code = $mfr->mfr_code;
                    }
                    
                  foreach($stores as $store){
                       
                        $manufact_id = DB::connection("mysql")->select("SELECT mfr_id  FROM u".$store.".mst_manufacturer WHERE mfr_code = '".$mfr_code."' ");
                      
                        if(count($manufact_id) > 0){
                            foreach($manufact_id as $man_id){
                                $mfrid = $man_id->mfr_id;
                            }
                            
                            $update_query = "Update u".$store.".mst_manufacturer set  mfr_name ='".$inp['mfr_name']."', mfr_code='".$inp['mfr_code']."' where mfr_id = '".$mfrid."' ";
                           
                            $updating =  DB::connection("mysql")->select($update_query);
                            
                            
                        }else{
                            $insert_query = "Insert INTO u".$store.".mst_manufacturer (mfr_code, mfr_name ) values('".$inp['mfr_code']."', '".$inp['mfr_name']."')";  
                            array_push($sql,$insert_query);
                            $inserting = DB::connection("mysql")->select($insert_query); 
                        }
                  }
                }else{
                    $manufacture = Manufacturer::find($inp['mfr_id']);
                    $manufacture->mfr_code = $inp['mfr_code'];
                    $manufacture->mfr_name = $inp['mfr_name'];
                    $manufacture->status = "Active";
                    $manufacture->save();  
                }
            
      }
        $request->session()->flash('message',' Success: You have modified Manufacturer !');
        // return redirect('/administration.shelf');
      return response()->json(['success' => 0], 200);

    }

    public function deletemanufacturer(Request $req)
    {
        $input = $req->all();
        
        if(isset($input['stores_hq'])){
            foreach($input['data'] as $value){ 
                foreach($input['stores_hq'] as $store){
                    $manufacture_id = DB::connection("mysql")->select("select * from u".$store.".mst_manufacturer where mfr_id = '".$value['mfr_id']."' ");
                    
                    foreach($manufacture_id as $manu_id){
        	           $mf_id =  $manu_id->mfr_id;
        	           DB::connection("mysql")->select("DELETE FROM u".$store.".mst_manufacturer WHERE mfr_id = '".$mf_id."' ");
        	        }
                    
                }
            }  
        }else {
            foreach($input as $value){
                DB::connection("mysql_dynamic")->select("DELETE FROM mst_manufacturer WHERE mfr_id = '".$value['mfr_id']."' ");
            }    
        }
        // Session::flash('message','Manufacturer Deleted Successfully!');
        $data['success'] = 'Manufacturer Deleted Successfully!';
        return $data;
        // return response()->json(['status'=> 1 ]);
    }
    
    public function duplicatemanufacturer(Request $request){
        $input = $request->all();
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        foreach($input as $k => $value ){
            $manufacture_code = DB::connection('mysql_dynamic')->select("select * from mst_manufacturer where mfr_id = '".$value['mfr_id']."' ");
            
            foreach($manufacture_code as $mfr){
                $mfr_code = $mfr->mfr_code;
            }
            foreach($stores as $store){
                $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_manufacturer where mfr_code = '".$mfr_code."' "); 
                if(count($query) == 0){
                  array_push($notExistStore, $store->id); 
                }
            }
        }
        
        return $notExistStore;
    }

}
