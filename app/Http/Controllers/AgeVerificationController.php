<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\AgeVerification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AgeVerificationController extends Controller
{

    public function getlist()
    {
        
        $ageveri = AgeVerification::all();

        return view('administration.ageverify', ['agedata'=>$ageveri]);
    }

    public function ageverifysearch(Request $req)
    {
      
          $ageverify = AgeVerification::where('vname','LIKE','%'.$req->input('q').'%')->get();
          
          return response()->json(compact('ageverify'));
     
    }


    public function update(Request $request){
        $input = $request->all();
        // dd($input);
        
        if(isset($input['stores_hq'])){
            foreach($input['data'] as  $age)
            {
                
                $messages = [
                                'vname.unique' => 'The description is already taken !',
                                'vvalue.unique' => 'The value for age is already taken !',
                            ];
                
                 $validator = Validator::make($age, [
                                'vname' =>  [
                                                'required',
                                                Rule::unique('mysql_dynamic.mst_ageverification', 'vname')->ignore($age['Id'], 'Id'),
                                            ],
                                'vvalue' =>  [
                                                'required',
                                                Rule::unique('mysql_dynamic.mst_ageverification', 'vvalue')->ignore($age['Id'], 'Id'),
                                            ]
                            ], $messages);
                            
                if ($validator->fails()) {                
    
                    return response()->json($validator->messages()->getMessages(), 422);
                    
                }
                
           
                $age_verification = AgeVerification::find($age['Id']);
    
                $age_verification->vname = $age['vname'];
                $age_verification->vvalue = $age['vvalue'];
    
                $age_verification->save();
                
                $agename = DB::connection('mysql_dynamic')->select(" Select * from mst_ageverification where Id = '".$age['Id']."' ");
                foreach($agename as $aname){
                    $age_Id = $aname->Id; 
                }
                foreach($input['stores_hq'] as $store){
                    DB::connection('mysql')->select("Update u".$store.".mst_ageverification set vname = '".$age['vname']."', vvalue = '".$age['vvalue']."' where Id = '".$age_Id."'  ");
                }
            }
            $request->session()->flash('message',' Success: You have modified Age Verifications!');
        }else{
            foreach($input as  $age)
            {
                
                $messages = [
                                'vname.unique' => 'The description is already taken !',
                                'vvalue.unique' => 'The value for age is already taken !',
                            ];
                
                 $validator = Validator::make($age, [
                                'vname' =>  [
                                                'required',
                                                Rule::unique('mysql_dynamic.mst_ageverification', 'vname')->ignore($age['Id'], 'Id'),
                                            ],
                                'vvalue' =>  [
                                                'required',
                                                Rule::unique('mysql_dynamic.mst_ageverification', 'vvalue')->ignore($age['Id'], 'Id'),
                                            ]
                            ], $messages);
                            
                if ($validator->fails()) {                
    
                    return response()->json($validator->messages()->getMessages(), 422);
                    
                }
                
           
                $age_verification = AgeVerification::find($age['Id']);
    
                $age_verification->vname = $age['vname'];
                $age_verification->vvalue = $age['vvalue'];
    
                $age_verification->save();
            }
            $request->session()->flash('message',' Success: You have modified Age Verifications!');
        }
        
        
        return response()->json(['key' => 'value'], 200);
    }
    
    public function duplicateage(Request $request){
        $input = $request->all();
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        foreach($input['data'] as  $value ){
            $agename = DB::connection('mysql_dynamic')->select(" Select * from mst_ageverification where Id = '".$value['Id']."' ");
            foreach($agename as $aname){
                $age_name = $aname->vname; 
            }
            if(isset($age_name)){
                foreach($stores as $store){
                    $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_ageverification where vname = '".$age_name."' "); 
                    if(count($query) == 0){
                      array_push($notExistStore, $store->id); 
                    }
                }
            }
        }
        return $notExistStore;
    }
}
