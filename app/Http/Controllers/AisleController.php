<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Aisle;
use Session;
//use App\Http\Requests\AisleRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AisleController extends Controller
{

    //Display Table Data
    public function aisle(){

        $aisle = Aisle::paginate(10);
        return view('administration.aisle',['aisle'=>$aisle]);
    }

    //Insert Aisle
    public function insertaisle(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'aislename' => 'required|unique:App\Model\Aisle,aislename'
        ]);

        if ($validator->fails()) { 
            //$request->session()->flash('error', 'Error:  Aisle Name Required!');
               
            return redirect('/aisle')
                    ->withErrors($validator);
        }

        $aisle = new Aisle;
        $aisle->aislename = $request->input('aislename');
        $aisle->save();
        $request->session()->flash('message','Success: You have Added Aisle Name Successfully!');
        return redirect('/aisle');
    }

    //Search Controller
    public function aislesearch(Request $req)
    {
        

        $aisle = Aisle::where('aislename','LIKE','%'.$req->input('q').'%')->get();
        
        return response()->json(compact('aisle'));
    }

    //Update Aisle
    public function update(Request $request)
    {
        $input = $request->all();

        foreach($input as $inp)
        {

            $messages = [
                            'aislename.unique' => 'The name is already taken !',
                        ];
            
            $validator = Validator::make($inp, [
                            'aislename' =>  [
                                            'required',
                                            Rule::unique('mysql_dynamic.mst_aisle', 'aislename')->ignore($inp['id'], 'id'),
                                        ],
                        ], $messages);
            
            
            if ($validator->fails()) {                
                
                return response()->json($validator->messages()->getMessages(), 422);
                
            }
            
            $aisle = Aisle::find($inp['id']);
            $aisle->aislename = $inp['aislename'];
            $aisle->save();
        }
        $request->session()->flash('message','Success: you have modified AisleName!');
        return response()->json(['key' => 'value'], 200);
    }

    public function deleteaisle(Request $req){

        $input = $req->get('input');

        foreach($input as $a){
            Aisle::find($a['id'])->delete();
        }

        Session::flash('message','Ailse Deleted successfully');
        return response()->json(['status'=>0]);
    }
    
}
