<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ Shelf;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShelfController extends Controller

{
    public function shelf()
    {
        $shelf = Shelf::paginate(10);
        return view('administration.shelf',['shelf'=>$shelf]);
    }

    public function insertshelf(Request $req)
    {
        $input = $req->all();

        $validator = Validator::make($input, [
            'shelfname' => 'required|unique:App\Model\Shelf,shelfname'
        ]);

        if ($validator->fails()) {                
           
            return redirect('/shelf')
                   ->withErrors($validator);
        }

        $shelf = new Shelf;
        $shelf->shelfname = $req->input('shelfname');
        $shelf->save();
        $req->session()->flash('message','Success: You have Added Shelfname Successfully!');
        return redirect('/shelf');
    }


    public function shelfsearch(Request $req)
    {
          $shelf = Shelf::where('shelfname','LIKE','%'.$req->input('q').'%')->get();
          return response()->json(compact('shelf'));
    }

    public function deleteshelf(Request $req)
    {
        $input = $req->get('input');

        foreach($input as $v){
            Shelf::find($v['id'])->delete();
        }  
        
        Session::flash('message','Shelf Deleted Successfully!');
        return response()->json(['status'=> 0 ]);
    }

    
    public function update(Request $request){
        $input = $request->all();
        
        foreach($input as  $inp)

        {
            // $validator = Validator::make($inp, [
            //     'shelfname' => 'required',
            // ]);
            
            $messages = [
                            'shelfname.unique' => 'The name is already taken !',
                        ];
            
            $validator = Validator::make($inp, [
                            'shelfname' =>  [
                                            'required',
                                            Rule::unique('mysql_dynamic.mst_shelf', 'shelfname')->ignore($inp['Id'], 'Id'),
                                        ],
                        ], $messages);
            

            if ($validator->fails()) {                
                
                return response()->json($validator->messages()->getMessages(), 422);
               
            }

            $shelf = Shelf::find($inp['Id']);
            $shelf->shelfname = $inp['shelfname'];
            $shelf->save();
        }
        $request->session()->flash('message',' Success: You have modified Shelf Name!');
        // return redirect('/administration.shelf');
       return response()->json(['key' => 'value'], 200);

    }
    
}
