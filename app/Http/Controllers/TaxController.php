<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Tax;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    public function tax()
    {
        $taxes = Tax::all();
        
        // return $taxes;
        
        //dd($taxes);

        return view('administration/tax', compact('taxes'));
    }

    public function updatetax(Request $req)
    {
        //message
        $input = $req->all();
        
        if(isset($input['stores_hq'])){
            if($input['stores_hq'] === session()->get('sid')){
                $stores = [session()->get('sid')];
            }else{
                $stores = explode(",", $input['stores_hq']);
            }
            foreach($stores as $store){
                // for Tax1
                $tax1 = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_tax where Id = '".$req->input('Id1')."' ");
                
                if($tax1[0]->vtaxtype != $req->input('vtaxtype1') || $tax1[0]->ntaxrate != $req->input('ntaxrate1'))
                {
                     $messages = [
                                    'vtaxtype1.unique' => 'The Taxtype is already taken !',
                                    'ntaxrate1.unique' => 'The Taxrate is already taken !',
                                ];
                                
                     $validator = Validator::make($input, [
                                    'vtaxtype1' =>  [
                                                    'required',
                                                    Rule::unique('mysql_dynamic.mst_tax', 'vtaxtype')->ignore($input['Id1'], 'Id'),
                                                ],
                                    // 'ntaxrate1' =>  [
                                    //                 'required',
                                    //                 Rule::unique('mysql_dynamic.mst_tax', 'ntaxrate')->ignore($input['Id1'], 'Id'),
                                    //             ]
                                ], $messages);
                    
                    if ($validator->fails()) {
                        return redirect('/tax')
                                ->withErrors($validator);
                    }
                    
                    
                    
                    // $tax1->vtaxtype = $req->input('vtaxtype1');
                    // $tax1->ntaxrate = $req->input('ntaxrate1');
                    // $tax1->save(); 
                    
                    $id_tax = DB::connection('mysql')->select("select * from u".$store.".mst_tax where vtaxtype = '".$tax1[0]->vtaxtype."' ");
                    
                    foreach($id_tax as $idtax){
                        $tax_id = $idtax->Id;
                    }
                    
                    $tax1 = DB::connection()->update("Update u".$store.".mst_tax set vtaxtype = '". $req->input('vtaxtype1')."', ntaxrate = '".$req->input('ntaxrate1')."' where id = '".(int)$tax_id."' ");
                    
                    $req->session()->flash('message','Success: You have modified Tax!');
                }
        
                // for Tax2
                $tax2 = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_tax where Id = '".$req->input('Id2')."' ");
                
                if($tax2[0]->vtaxtype != $req->input('vtaxtype2') || $tax2[0]->ntaxrate != $req->input('ntaxrate2'))
                {
                    
                    $messages = [
                                    'vtaxtype2.unique' => 'The Taxtype is already taken !',
                                    'ntaxrate2.unique' => 'The Taxrate is already taken !',
        
                                ];
                                
                     $validator = Validator::make($input, [
                                    'vtaxtype2' =>  [
                                                    'required',
                                                    Rule::unique('mysql_dynamic.mst_tax', 'vtaxtype')->ignore($input['Id2'], 'Id'),
                                                ],
                                    // 'ntaxrate2' =>  [
                                    //                 'required',
                                    //                 Rule::unique('mysql_dynamic.mst_tax', 'ntaxrate')->ignore($input['Id2'], 'Id'),
                                    //             ]
                                ], $messages);
                                
        
                    if ($validator->fails()) {
                        return redirect('/tax')
                                ->withErrors($validator);
                    }
                    
                    // $tax2->vtaxtype = $req->input('vtaxtype2');
                    
                    // $tax2->ntaxrate = $req->input('ntaxrate2');
                    // $tax2->save();
                    
                    $id_tax = DB::connection('mysql')->select("select * from u".$store.".mst_tax where vtaxtype = '".$tax2[0]->vtaxtype."' ");
                    
                    foreach($id_tax as $idtax){
                        $tax_id = $idtax->Id;
                    }
                    
                    $tax1 = DB::connection()->select("Update u".$store.".mst_tax set vtaxtype = '". $req->input('vtaxtype2')."', ntaxrate = '".$req->input('ntaxrate2')."' where id = '".(int)$tax_id."' ");
                    
                    $req->session()->flash('message','Success: You have modified Tax!');
                }
        
                // for Tax3
                $tax3 = DB::connection('mysql')->select("SELECT * FROM u".$store.".mst_tax where Id = '".$req->input('Id3')."' ");
                
                if((($tax3 !== null) && ($req->input('vtaxtype3') !== null) && ($req->input('ntaxrate3') !== null)) &&  ($tax3[0]->vtaxtype != $req->input('vtaxtype3') || $tax3[0]->ntaxrate != $req->input('ntaxrate3')))
                {
                      $messages = [
                                    'vtaxtype3.unique' => 'The Taxtype is already taken !',
                                    'ntaxrate3.unique' => 'The Taxrate is already taken !',
        
                                ];
                                
                     $validator = Validator::make($input, [
                                    'vtaxtype3' =>  [
                                                    'required',
                                                    Rule::unique('mysql_dynamic.mst_tax', 'vtaxtype')->ignore($input['Id3'], 'Id'),
                                                ],
                                    // 'ntaxrate3' =>  [
                                    //                 'required',
                                    //                 Rule::unique('mysql_dynamic.mst_tax', 'ntaxrate')->ignore($input['Id3'], 'Id'),
                                    //             ]
                                ], $messages);
                                
        
                    if ($validator->fails()) {
                        return redirect('/tax')
                                ->withErrors($validator);
                    }
                  
                    
                    // $tax3->vtaxtype = $req->input('vtaxtype3');
                    // $tax3->ntaxrate = $req->input('ntaxrate3');
                    // $tax3->save();
                    // 
                    
                    $id_tax = DB::connection('mysql')->select("select * from u".$store.".mst_tax where vtaxtype = '".$tax3[0]->vtaxtype."' ");
                    foreach($id_tax as $idtax){
                        $tax_id = $idtax->Id;
                    }
                    $tax1 = DB::connection('mysql')->select("Update u".$store.".mst_tax set vtaxtype = '". $req->input('vtaxtype3')."', ntaxrate = '".$req->input('ntaxrate3')."' where id = '".(int)$tax_id."' ");
                    
                    $req->session()->flash('message','Success: You have modified Tax!');
                }
            }
            
        }else{
            // for Tax1
            $tax1 = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_tax where Id = '".$req->input('Id1')."' ");
                
            if($tax1[0]->vtaxtype != $req->input('vtaxtype1') || $tax1[0]->ntaxrate != $req->input('ntaxrate1'))
            {
                 $messages = [
                                'vtaxtype1.unique' => 'The Taxtype is already taken !',
                                // 'ntaxrate1.unique' => 'The Taxrate is already taken !',
                            ];
                            
                 $validator = Validator::make($input, [
                                'vtaxtype1' =>  [
                                                'required',
                                                Rule::unique('mysql_dynamic.mst_tax', 'vtaxtype')->ignore($input['Id1'], 'Id'),
                                            ],
                                // 'ntaxrate1' =>  [
                                //                 'required',
                                //                 Rule::unique('mysql_dynamic.mst_tax', 'ntaxrate')->ignore($input['Id1'], 'Id'),
                                //             ]
                            ], $messages);
                        
                if ($validator->fails()) {
                    return redirect('/tax')
                            ->withErrors($validator);
                }
                
                
                $tax1 = Tax::find($req->input('Id1'));
                $tax1->vtaxtype = $req->input('vtaxtype1');
                $tax1->ntaxrate = $req->input('ntaxrate1');
                $tax1->save(); 
                $req->session()->flash('message','Success: You have modified Tax!');
            }
    
            // for Tax2
            $tax2 = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_tax where Id = '".$req->input('Id2')."' ");
                
            if($tax2[0]->vtaxtype != $req->input('vtaxtype2') || $tax2[0]->ntaxrate != $req->input('ntaxrate2'))
            {
                
                $messages = [
                                'vtaxtype2.unique' => 'The Taxtype is already taken !',
                                // 'ntaxrate2.unique' => 'The Taxrate is already taken !',
    
                            ];
                            
                 $validator = Validator::make($input, [
                                'vtaxtype2' =>  [
                                                'required',
                                                Rule::unique('mysql_dynamic.mst_tax', 'vtaxtype')->ignore($input['Id2'], 'Id'),
                                            ],
                                // 'ntaxrate2' =>  [
                                //                 'required',
                                //                 Rule::unique('mysql_dynamic.mst_tax', 'ntaxrate')->ignore($input['Id2'], 'Id'),
                                //             ]
                            ], $messages);
                            
    
                if ($validator->fails()) {
                    return redirect('/tax')
                            ->withErrors($validator);
                }
                
                $tax2 = Tax::find($req->input('Id2'));
                $tax2->vtaxtype = $req->input('vtaxtype2');
                $tax2->ntaxrate = $req->input('ntaxrate2');
                $tax2->save();
                $req->session()->flash('message','Success: You have modified Tax!');
            }
    
            // for Tax3
            $tax3 = DB::connection('mysql_dynamic')->select("SELECT * FROM mst_tax where Id = '".$req->input('Id3')."' ");
                
            if((($tax3 !== null) && ($req->input('vtaxtype3') !== null) && ($req->input('ntaxrate3') !== null)) &&  ($tax3[0]->vtaxtype != $req->input('vtaxtype3') || $tax3[0]->ntaxrate != $req->input('ntaxrate3')))
            {
                  $messages = [
                                'vtaxtype3.unique' => 'The Taxtype is already taken !',
                                'ntaxrate3.unique' => 'The Taxrate is already taken !',
    
                            ];
                            
                 $validator = Validator::make($input, [
                                'vtaxtype3' =>  [
                                                'required',
                                                Rule::unique('mysql_dynamic.mst_tax', 'vtaxtype')->ignore($input['Id3'], 'Id'),
                                            ],
                                // 'ntaxrate3' =>  [
                                //                 'required',
                                //                 Rule::unique('mysql_dynamic.mst_tax', 'ntaxrate')->ignore($input['Id3'], 'Id'),
                                //             ]
                            ], $messages);
                            
    
                if ($validator->fails()) {
                    return redirect('/tax')
                            ->withErrors($validator);
                }
              
                $tax3 = Tax::find($req->input('Id3'));
                $tax3->vtaxtype = $req->input('vtaxtype3');
                $tax3->ntaxrate = $req->input('ntaxrate3');
                $tax3->save();
                $req->session()->flash('message','Success: You have modified Tax!');
            }
        }
        
        return redirect('/tax');
    }
    
    public function duplicatetax(Request $request){
        
        $input = $request->all();  
        $taxvalues = $input['tax_code'];
        $stores = session()->get('stores_hq');
        $notExistStore = [];
        foreach($taxvalues as  $value ){
            foreach($stores as $store){
                $query = DB::connection("mysql")->select("Select * from u".$store->id.".mst_tax where vtaxcode = '".$value."' "); 
                if(count($query) == 0){
                  array_push($notExistStore, $store->id); 
                }
            }
        }
        return $notExistStore;
    }

     
}
