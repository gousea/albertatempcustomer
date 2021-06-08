<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Reason;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReasonController extends Controller
{
    public function reason()
    {

        $reason = Reason::paginate(10);
        return view('inventory.reason',['reason'=>$reason]);
    }

    public function insertreason(Request $req) 
    {
        $input = $req->all();
        
        $messages = [
                        'vreasonename.unique' => 'The name is already taken !',
                    ];

        $validator = Validator::make($input, [
            'vreasonename' => 'required|unique:App\Model\Reason,vreasonename'
        ], $messages);

        if ($validator->fails()) { 
            //$request->session()->flash('error', 'Error:  Reason Name Required!');
               
            return redirect('/reason')
                    ->withErrors($validator);
        }
        
        
        $reason = new Reason;
        $reason->vreasonename = $req->input('vreasonename');
        $reason->save();
        $req->session()->flash('message','Success: You have added successfully!');
        return redirect('/reason');
    }

    public function reasonsearch(Request $req)
    {

        $reason = Reason::where('vreasonename','LIKE','%'.$req->input('q').'%')->get();
        
        return response()->json(compact('reason'));
    }

    public function update(Request $req)
    {
        $input = $req->all();
        
        foreach($input as $inp)
        {
            // $validator = Validator::make($inp, [
                
            //     'vreasonename' => 'required'
            // ]);

            $messages = [
                            'vreasonename.unique' => 'The name is already taken !',
                        ];
            
            $validator = Validator::make($inp, [
                            'vreasonename' =>  [
                                            'required',
                                            Rule::unique('mysql_dynamic.mst_adjustmentreason', 'vreasonename')->ignore($inp['ireasonid'], 'ireasonid'),
                                        ],
                        ], $messages);



            if ($validator->fails()) {                
                
                return response()->json($validator->messages()->getMessages(), 422);
            }
            
            $reason = Reason::find($inp['ireasonid']);
            $reason->vreasonename = $inp['vreasonename'];
            $reason->save();
        }
        $req->session()->flash('message','Success: you have modified Reason!');
        return response()->json(['key' => 'value'], 200);

    }
}
