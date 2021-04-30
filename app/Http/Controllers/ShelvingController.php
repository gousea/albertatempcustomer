<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Shelving;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShelvingController extends Controller
{
    public function shelving()
    {
        $shelving = Shelving::all();

        return view('administration.shelving',['shelvingdata'=>$shelving]);
    }

    public function insertshelving(Request $req)
    {

        $input = $req->all();
        
        $messages = [
                        'shelvingname.unique' => 'The name is already taken !',
                    ];
        
        $validator = Validator::make($input, [
            'shelvingname' => 'required|unique:App\Model\Shelving,shelvingname'
        ], $messages);

        if ($validator->fails()) { 
            //$request->session()->flash('error', 'Error:  Aisle Name Required!');
               
            return redirect('/shelving')
                    ->withErrors($validator);
        }

        $shelving = new Shelving;
        $shelving->shelvingname = $req->input('shelvingname');
        $shelving->save();
        $req->session()->flash('message','Success: You have Added Shelving Successfully!');
        return redirect('/shelving');
    }

    public function shelvingsearch(Request $req)
    {
          $shelving = Shelving::where('shelvingname','LIKE','%'.$req->input('q').'%')->get();
         
          return response()->json(compact('shelving'));
        
    }


    public function update(Request $req)
    {
        $input = $req->all();

        foreach($input as $inp)
        {
            
              $messages = [
                            'shelvingname.unique' => 'The name is already taken !',
                        ];
            
            $validator = Validator::make($inp, [
                
                            'shelvingname' =>  [
                                                    'required',
                                                    Rule::unique('mysql_dynamic.mst_shelving', 'shelvingname')->ignore($inp['id'], 'id'),
                                                ],
                            ], $messages);
            

            if ($validator->fails()) {                

                return response()->json($validator->messages()->getMessages(), 422);
                //return redirect('/aisle');
            }


            $shelving = Shelving::find($inp['id']);
            $shelving->shelvingname = $inp['shelvingname'];
            $shelving->save();
        }
        $req->session()->flash('message','Success: You have modified Shelving  Successfully!');
        return response()->json(['key' => 'value'], 200);

    }

    public function deleteshelving(Request $req)
    {
        $input = $req->get('input');

        foreach($input as $v){
            Shelving::find($v['id'])->delete();
        }  
        
        Session::flash('message','Shelve Deleted Successfully!');
        return response()->json(['status'=> 0 ]);
    }
}
