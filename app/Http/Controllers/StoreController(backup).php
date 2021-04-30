<?php

namespace App\Http\Controllers;

use App\Model\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $error = array();

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        // return view('store/store');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Store $store)
    {
        
        // print_r($request->session('sid'));
        $sid = $request->session()->get('sid');
        $store = Store::where('SID', '=', $sid)->get();
        $storeData = $store[0];
        return view('stores.store', compact('storeData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Store $store)
    {

        $input = $request->all();
        $storeid = $input['istoreid'];   

        $request->validate(
            [
                'vstoreabbr'=> 'required',    
                'vaddress1'=>'required',
                'vstoredesc'=>'required',
                'vcity'=>'required',
                'vstate'=>'required',
                'vzip'=>'required',
                'vphone1'=>'required',
                'vcompanycode'=>'required',         
            ]
        );
        Store::where('istoreid', '=', $storeid)->update(
            [
                'vcompanycode'      =>  $input['vcompanycode'],
                'vstoreabbr'        =>  $input['vstoreabbr'],
                'vaddress1'         =>  $input['vaddress1'],
                'vstoredesc'        =>  $input['vstoredesc'],
                'vcity'             =>  $input['vcity'],
                'vstate'            =>  $input['vstate'],
                'vzip'              =>  $input['vzip'],
                'vcountry'          =>  $input['vcountry'],
                'vphone1'           =>  $input['vphone1'],
                'vphone2'           =>  $input['vphone2'],
                'vfax1'             =>  $input['vfax1'],
                'vemail'            =>  $input['vemail'],
                'vwebsite'          =>  $input['vwebsite'],
                'vcontactperson'    =>  $input['vcontactperson'],
                'isequence'         =>  $input['isequence'],
                'vmessage1'         =>  $input['vmessage1'],
                'vmessage2'         =>  $input['vmessage2']
            ]
        );

        return redirect(route('store.edit'))->with('message','Successfully Updated Store!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }
}
