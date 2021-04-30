<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Paidout;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class PaidoutController extends Controller
{
    public function paidout()
    {
        $paid = Paidout::paginate(10);
        return view('administration.paidout',['paidout'=>$paid]);
    }

    public function insertpaidout(Request $request)
    {
        $input = $request->all();
        
        $messages = [
                            'vpaidoutname.required' => 'The name is required !',
                            'vpaidoutname.unique' => 'The name is already taken !',
                        ];
        
        
        $validator =    Validator::make($input, [
                            'vpaidoutname' => 'required|unique:App\Model\Paidout,vpaidoutname'
                        ], $messages);

        if ($validator->fails()) { 
            //$request->session()->flash('error', 'Error:  Aisle Name Required!');
               
            return redirect('/paidout')
                    ->withErrors($validator);
        }

        $paidout = new Paidout;
        $paidout->vpaidoutname = $request->input('vpaidoutname');
        $paidout->estatus =$request->input('paidoutestatus');
        $paidout->save();
        $request->session()->flash('message','Success: You have Added Paidout Successfully!');
        return redirect('/paidout');
    }

    public function update(Request $request){
        $input = $request->all();

        foreach($input as $inp)
        {
            // $validator = Validator::make($inp, [
            //     'vpaidoutname' => 'required'
            // ]);
            
              $messages = [
                            'vpaidoutname.required' => 'The name is required !',
                            'vpaidoutname.unique'   => 'The name is already taken !',
                        ];
            
            $validator = Validator::make($inp, [
                            'vpaidoutname' =>  [
                                            'required',
                                            Rule::unique('mysql_dynamic.mst_paidout', 'vpaidoutname')->ignore($inp['ipaidoutid'], 'ipaidoutid'),
                                        ],
                        ], $messages);
            
            if ($validator->fails()) {                

                return response()->json($validator->messages()->getMessages(), 422);
            }
           
            $paidout = Paidout::find($inp['ipaidoutid']);
            $paidout->vpaidoutname = $inp['vpaidoutname'];
            $paidout->estatus = $inp['estatus'];
            $paidout->save();
        }
        $request->session()->flash('message',' Success: You have modified Paidout !');
        return response()->json(['success' => 0], 200);
    }

    public function paidoutsearch(Request $req)
    {
        $paidout = Paidout::where('vpaidoutname','LIKE','%'.$req->input('q').'%')->get();

          return response()->json(compact('paidout'));
        
    }

    public function deletepaidout(Request $req)
    {
        $input = $req->get('input');

        foreach($input as $v){
            Paidout::find($v['ipaidoutid'])->delete();
        }
        
        Session::flash('message','Paidout Deleted Succseefully!');
        return response()->json(['status' => 0], 200);
    }
}
