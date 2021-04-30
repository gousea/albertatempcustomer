<?php

namespace App\Http\Controllers;

use App\Model\WebStoreSettings;
use Illuminate\Http\Request;

class EndOfShiftPrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $sid = $request->session()->get('sid');
        $data = array();
        $data['EndOfShiftPrinting'] = WebStoreSettings::where('variablename','EndOfShiftPrinting')->first();
        $data['PrintDeliveryStation'] = WebStoreSettings::where('variablename','PrintDeliveryStation')->first();
        $data['PrintDeliItemwise'] = WebStoreSettings::where('variablename', 'PrintDeliItemwise')->first();
        $data['sid'] = $sid;
        return view('endofshiftprintings.end_of_shift_printing', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        // EndOfShiftPrinting
        if(isset($input) && isset($input['EndOfShiftPrinting'])){
            $v_EndOfShiftPrinting = $input['EndOfShiftPrinting'];
        }else{
            $v_EndOfShiftPrinting = 0;
        }

        $check_exist = WebStoreSettings::select('*')->where('variablename', '=', 'EndOfShiftPrinting')->get();
        if(count($check_exist) > 0){
            WebStoreSettings::where('variablename','EndOfShiftPrinting')->update([
                'variablevalue'=> $v_EndOfShiftPrinting,
            ]);
        }else{ 
            WebStoreSettings::insert([
                'variablename'=> 'EndOfShiftPrinting',
                'variablevalue'=>$v_EndOfShiftPrinting,
            ]);
            
        }
        
        // PrintDeliveryStation
        if(isset($input) && isset($input['PrintDeliveryStation'])){
            $v_PrintDeliveryStation = $input['PrintDeliveryStation'];
        }else{
            $v_PrintDeliveryStation = 0;
        }
        
        $check_exist = WebStoreSettings::select('*')->where('variablename', '=', 'PrintDeliveryStation')->get();
        if(count($check_exist) > 0){
            WebStoreSettings::where('variablename','PrintDeliveryStation')->update([
                'variablevalue'=>$v_PrintDeliveryStation,
            ]);
        }else{
            WebStoreSettings::insert([
                'variablename'=> 'PrintDeliveryStation',
                'variablevalue'=>$v_PrintDeliveryStation,
            ]);
        }
        
        // PrintDeliItemwise
        if(isset($input) && isset($input['PrintDeliItemwise'])){
            $v_PrintDeliItemwise = $input['PrintDeliItemwise'];
        }else{
            $v_PrintDeliItemwise = 0;
        }
        
        $check_exist = WebStoreSettings::select('*')->where('variablename', '=', 'PrintDeliItemwise')->get();
        if(count($check_exist) > 0){
            WebStoreSettings::where('variablename','PrintDeliItemwise')->update([
                'variablevalue'=>$v_PrintDeliItemwise,
            ]);
        }else{
            WebStoreSettings::insert([
                'variablename'=> 'PrintDeliItemwise',
                'variablevalue'=>$v_PrintDeliItemwise,
            ]);
        }
        return redirect(route('end_of_shift_printing'))->with('message','Successfully Updated!!');
        // return redirect()->back()->with('message','Successfully Updated');
    }
}
